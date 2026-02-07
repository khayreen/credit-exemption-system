<?php

namespace App\Services;

use App\Models\AcademicAdvisor;
use App\Models\ApplicationSubject;
use App\Models\AuditTrail;
use App\Models\CourseEquivalency;
use App\Models\Notification;
use App\Models\PendingReevaluation;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReevaluationService
{
    /**
     * Acceptable grades (C or above / >= 2.00 GPA)
     */
    private const ACCEPTABLE_GRADES = [
        'A+', 'A', 'A-',
        'B+', 'B', 'B-',
        'C+', 'C',
        // UiTM specific formats
        'LUA+', 'LUA', 'LUA-',
        'LUB+', 'LUB', 'LUB-',
        'LUC+', 'LUC',
    ];

    /**
     * Statuses that are eligible for re-evaluation
     */
    private const ELIGIBLE_STATUSES = [
        'not_found',
        'not_eligible_match',
        'Rejected',
        'Rejected by Resource Person',
    ];

    /**
     * Process a new course equivalency and create pending re-evaluations
     * for affected application subjects.
     */
    public function processNewEquivalency(CourseEquivalency $equivalency): array
    {
        $result = [
            'total_affected' => 0,
            'reevaluations_created' => 0,
            'skipped_grade' => 0,
            'skipped_existing' => 0,
            'skipped_match' => 0,
            'academic_advisors_notified' => 0,
            'affected_students' => [],
        ];

        try {
            DB::beginTransaction();

            // Find all application subjects that could benefit from this equivalency
            $affectedSubjects = $this->findAffectedSubjects($equivalency);
            $result['total_affected'] = $affectedSubjects->count();

            if ($affectedSubjects->isEmpty()) {
                DB::commit();
                Log::info('No affected subjects found for new equivalency', [
                    'equivalency_id' => $equivalency->id,
                    'diploma_course' => $equivalency->diploma_course_code,
                    'program_code' => $equivalency->program_code,
                ]);
                return $result;
            }

            // Group subjects by their application for notification purposes
            $subjectsByStudent = [];

            foreach ($affectedSubjects as $subject) {
                // Check if re-evaluation already exists for this subject + equivalency
                $existingReevaluation = PendingReevaluation::where('application_subject_id', $subject->id)
                    ->where('course_equivalency_id', $equivalency->id)
                    ->first();

                if ($existingReevaluation) {
                    $result['skipped_existing']++;
                    continue;
                }

                // Check grade requirement
                if (!$this->isGradeAcceptable($subject->grade)) {
                    $result['skipped_grade']++;
                    Log::info('Subject skipped due to grade', [
                        'subject_id' => $subject->id,
                        'grade' => $subject->grade,
                    ]);
                    continue;
                }

                // Check match percentage (must be > 80%)
                if ($equivalency->match_percentage <= 80) {
                    $result['skipped_match']++;
                    continue;
                }

                // Create pending re-evaluation record
                $reevaluation = PendingReevaluation::create([
                    'application_subject_id' => $subject->id,
                    'course_equivalency_id' => $equivalency->id,
                    'status' => PendingReevaluation::STATUS_PENDING,
                    'original_subject_status' => $subject->status,
                    'diploma_course_code' => $equivalency->diploma_course_code,
                    'degree_course_code' => $equivalency->degree_course_code,
                    'match_percentage' => $equivalency->match_percentage,
                    'expires_at' => now()->addDays(30), // 30 days to review
                ]);

                $result['reevaluations_created']++;

                // Track for student notification
                $studentId = $subject->exemptionApplication->student_id ?? null;
                if ($studentId) {
                    if (!isset($subjectsByStudent[$studentId])) {
                        $subjectsByStudent[$studentId] = [
                            'student' => $subject->exemptionApplication->student,
                            'subjects' => [],
                        ];
                    }
                    $subjectsByStudent[$studentId]['subjects'][] = $subject;
                    $result['affected_students'][] = $subject->exemptionApplication->student->name ?? 'Unknown';
                }

                // Create audit trail
                AuditTrail::create([
                    'user_id' => auth()->id(),
                    'action' => 'Pending Re-evaluation Created',
                    'auditable_type' => 'PendingReevaluation',
                    'auditable_id' => $reevaluation->id,
                    'details' => json_encode([
                        'trigger' => 'new_course_equivalency',
                        'equivalency_id' => $equivalency->id,
                        'subject_id' => $subject->id,
                        'original_status' => $subject->status,
                        'diploma_course' => $equivalency->diploma_course_code,
                        'degree_course' => $equivalency->degree_course_code,
                        'match_percentage' => $equivalency->match_percentage,
                        'student_name' => $subject->exemptionApplication->student->name ?? 'Unknown',
                    ]),
                ]);
            }

            // Notify Academic Advisors about pending re-evaluations
            if ($result['reevaluations_created'] > 0) {
                $result['academic_advisors_notified'] = $this->notifyAcademicAdvisors(
                    $equivalency,
                    $result['reevaluations_created'],
                    $result['affected_students']
                );
            }

            DB::commit();

            Log::info('Re-evaluation processing completed', $result);

            return $result;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to process new equivalency for re-evaluations', [
                'equivalency_id' => $equivalency->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Find application subjects that could benefit from this equivalency.
     */
    public function findAffectedSubjects(CourseEquivalency $equivalency): Collection
    {
        return ApplicationSubject::query()
            ->where('course_code', $equivalency->diploma_course_code)
            ->whereIn('status', self::ELIGIBLE_STATUSES)
            ->whereHas('exemptionApplication', function ($query) use ($equivalency) {
                $query->where('current_program_code', $equivalency->program_code);
            })
            ->with(['exemptionApplication.student.user'])
            ->get();
    }

    /**
     * Check if a grade is acceptable (C or above).
     */
    public function isGradeAcceptable(?string $grade): bool
    {
        if (empty($grade)) {
            return false;
        }

        $normalizedGrade = strtoupper(trim($grade));

        // Direct match
        if (in_array($normalizedGrade, self::ACCEPTABLE_GRADES)) {
            return true;
        }

        // Handle numeric grades (GPA format)
        if (is_numeric($normalizedGrade)) {
            return (float) $normalizedGrade >= 2.00;
        }

        // Handle grades with spaces or extra characters
        foreach (self::ACCEPTABLE_GRADES as $acceptableGrade) {
            if (str_contains($normalizedGrade, $acceptableGrade)) {
                return true;
            }
        }

        // Reject C-, D+, D, D-, F, TL
        $rejectedGrades = ['C-', 'D+', 'D', 'D-', 'F', 'TL', 'LUD', 'LUE', 'LUF'];
        foreach ($rejectedGrades as $rejected) {
            if (str_contains($normalizedGrade, $rejected)) {
                return false;
            }
        }

        // Default to false for unknown grades
        return false;
    }

    /**
     * Notify Academic Advisors about pending re-evaluations.
     */
    private function notifyAcademicAdvisors(
        CourseEquivalency $equivalency,
        int $count,
        array $affectedStudents
    ): int {
        $notifiedCount = 0;

        // Get all academic advisors (or filter by program if needed)
        $academicAdvisors = AcademicAdvisor::with('user')->get();

        $studentList = array_unique($affectedStudents);
        $studentListText = count($studentList) <= 3
            ? implode(', ', $studentList)
            : implode(', ', array_slice($studentList, 0, 3)) . ' and ' . (count($studentList) - 3) . ' more';

        foreach ($academicAdvisors as $advisor) {
            if (!$advisor->user_id) {
                continue;
            }

            Notification::create([
                'user_id' => $advisor->user_id,
                'type' => 'pending_reevaluation',
                'title' => 'Applications Require Re-evaluation',
                'message' => "A new course equivalency mapping has been created: " .
                           "{$equivalency->diploma_course_code} → {$equivalency->degree_course_code} " .
                           "({$equivalency->match_percentage}% match). " .
                           "{$count} application(s) may now qualify for exemption. " .
                           "Students affected: {$studentListText}.",
                'link' => route('academic_advisor.reevaluations.index'),
                'is_read' => false,
            ]);

            $notifiedCount++;
        }

        return $notifiedCount;
    }

    /**
     * Approve a pending re-evaluation and update the application subject.
     */
    public function approveReevaluation(
        PendingReevaluation $reevaluation,
        User $user,
        ?string $notes = null
    ): array {
        try {
            DB::beginTransaction();

            // Mark re-evaluation as approved
            $reevaluation->approve($user, $notes);

            // Update the application subject
            $subject = $reevaluation->applicationSubject;
            $equivalency = $reevaluation->courseEquivalency;

            $subject->update([
                'status' => 'Approved',
                'exemption_reason' => "Approved after new equivalency mapping created. " .
                                     "Course {$reevaluation->diploma_course_code} is equivalent to " .
                                     "{$reevaluation->degree_course_code} ({$reevaluation->match_percentage}% match).",
                'notes' => json_encode([
                    'equivalent_course' => $reevaluation->degree_course_code,
                    'match_percentage' => $reevaluation->match_percentage,
                    'reevaluation_id' => $reevaluation->id,
                    'approved_by' => $user->name,
                    'approved_at' => now()->toDateTimeString(),
                    'original_status' => $reevaluation->original_subject_status,
                ]),
            ]);

            // Create audit trail
            AuditTrail::create([
                'user_id' => $user->id,
                'action' => 'Re-evaluation Approved',
                'auditable_type' => 'ApplicationSubject',
                'auditable_id' => $subject->id,
                'details' => json_encode([
                    'reevaluation_id' => $reevaluation->id,
                    'original_status' => $reevaluation->original_subject_status,
                    'new_status' => 'Approved',
                    'equivalency_id' => $equivalency->id,
                    'diploma_course' => $reevaluation->diploma_course_code,
                    'degree_course' => $reevaluation->degree_course_code,
                    'match_percentage' => $reevaluation->match_percentage,
                    'decision_notes' => $notes,
                ]),
            ]);

            // Notify the student
            $this->notifyStudentOfDecision($reevaluation, 'approved');

            DB::commit();

            return [
                'success' => true,
                'message' => "Re-evaluation approved. Student's course {$subject->course_code} has been exempted.",
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to approve re-evaluation', [
                'reevaluation_id' => $reevaluation->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Failed to approve re-evaluation: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Reject a pending re-evaluation (keep original status).
     */
    public function rejectReevaluation(
        PendingReevaluation $reevaluation,
        User $user,
        ?string $notes = null
    ): array {
        try {
            DB::beginTransaction();

            // Mark re-evaluation as rejected
            $reevaluation->reject($user, $notes);

            // Create audit trail (subject status remains unchanged)
            AuditTrail::create([
                'user_id' => $user->id,
                'action' => 'Re-evaluation Rejected',
                'auditable_type' => 'PendingReevaluation',
                'auditable_id' => $reevaluation->id,
                'details' => json_encode([
                    'subject_id' => $reevaluation->application_subject_id,
                    'original_status' => $reevaluation->original_subject_status,
                    'decision' => 'rejected',
                    'decision_notes' => $notes,
                    'diploma_course' => $reevaluation->diploma_course_code,
                    'degree_course' => $reevaluation->degree_course_code,
                ]),
            ]);

            // Notify the student
            $this->notifyStudentOfDecision($reevaluation, 'rejected');

            DB::commit();

            return [
                'success' => true,
                'message' => "Re-evaluation rejected. Student's original status has been maintained.",
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to reject re-evaluation', [
                'reevaluation_id' => $reevaluation->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Failed to reject re-evaluation: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Bulk approve multiple re-evaluations.
     */
    public function bulkApprove(array $reevaluationIds, User $user, ?string $notes = null): array
    {
        $results = [
            'approved' => 0,
            'failed' => 0,
            'errors' => [],
        ];

        foreach ($reevaluationIds as $id) {
            $reevaluation = PendingReevaluation::find($id);

            if (!$reevaluation || !$reevaluation->isActionable()) {
                $results['failed']++;
                $results['errors'][] = "Re-evaluation {$id} not found or not actionable";
                continue;
            }

            $result = $this->approveReevaluation($reevaluation, $user, $notes);

            if ($result['success']) {
                $results['approved']++;
            } else {
                $results['failed']++;
                $results['errors'][] = $result['message'];
            }
        }

        return $results;
    }

    /**
     * Bulk reject multiple re-evaluations.
     */
    public function bulkReject(array $reevaluationIds, User $user, ?string $notes = null): array
    {
        $results = [
            'rejected' => 0,
            'failed' => 0,
            'errors' => [],
        ];

        foreach ($reevaluationIds as $id) {
            $reevaluation = PendingReevaluation::find($id);

            if (!$reevaluation || !$reevaluation->isActionable()) {
                $results['failed']++;
                $results['errors'][] = "Re-evaluation {$id} not found or not actionable";
                continue;
            }

            $result = $this->rejectReevaluation($reevaluation, $user, $notes);

            if ($result['success']) {
                $results['rejected']++;
            } else {
                $results['failed']++;
                $results['errors'][] = $result['message'];
            }
        }

        return $results;
    }

    /**
     * Notify student about the re-evaluation decision.
     */
    private function notifyStudentOfDecision(PendingReevaluation $reevaluation, string $decision): void
    {
        try {
            $subject = $reevaluation->applicationSubject;
            $student = $subject->exemptionApplication->student ?? null;

            if (!$student || !$student->user_id) {
                return;
            }

            $courseInfo = "{$reevaluation->diploma_course_code} - {$subject->course_name}";

            if ($decision === 'approved') {
                $title = 'Credit Exemption Approved';
                $message = "Great news! Your course {$courseInfo} has been approved for credit exemption " .
                          "based on a new equivalency mapping. It is now equivalent to {$reevaluation->degree_course_code} " .
                          "({$reevaluation->match_percentage}% match).";
            } else {
                $title = 'Re-evaluation Decision';
                $message = "Your course {$courseInfo} has been reviewed but was not approved for exemption " .
                          "at this time. Please contact your Academic Advisor for more information.";
            }

            Notification::create([
                'user_id' => $student->user_id,
                'type' => 'reevaluation_decision',
                'title' => $title,
                'message' => $message,
                'link' => route('student.application.status'),
                'is_read' => false,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send student notification for re-evaluation decision', [
                'reevaluation_id' => $reevaluation->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Get statistics for pending re-evaluations.
     */
    public function getStats(?string $programCode = null): array
    {
        $query = PendingReevaluation::query();

        if ($programCode) {
            $query->forProgram($programCode);
        }

        return [
            'total_pending' => (clone $query)->pending()->notExpired()->count(),
            'approved_today' => (clone $query)->where('status', 'approved')
                                              ->whereDate('decided_at', today())->count(),
            'rejected_today' => (clone $query)->where('status', 'rejected')
                                              ->whereDate('decided_at', today())->count(),
            'expiring_soon' => (clone $query)->pending()
                                             ->where('expires_at', '<=', now()->addDays(7))
                                             ->where('expires_at', '>', now())
                                             ->count(),
        ];
    }
}
