<?php

namespace App\Http\Controllers\AcademicAdvisor;

use App\Http\Controllers\Controller;
use App\Models\ExemptionApplication;
use App\Models\ApplicationSubject;
use App\Models\CourseEquivalency;
use App\Models\EquivalencyList;
use App\Models\Notification;
use App\Models\PendingEquivalencyMapping;
use App\Models\ProgramGroupConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the applications for the academic advisor.
     * Only shows applications for groups assigned to the current AA.
     */
    public function index()
    {
        $user = Auth::user();

        // Get the AA's assigned group codes from program_group_configs
        // Uses current session's configuration
        $academicYear = ProgramGroupConfig::getCurrentAcademicYear();
        $intake = ProgramGroupConfig::getCurrentIntake();

        $assignedGroupCodes = ProgramGroupConfig::active()
            ->forSession($academicYear, $intake)
            ->where('assigned_user_id', $user->id)
            ->pluck('group_code')
            ->toArray();

        // Build the applications query
        $query = ExemptionApplication::where('status', 'Submitted')
            ->with(['student.user', 'applicationSubjects'])
            ->latest();

        // Filter by assigned groups if the AA has any assignments
        if (!empty($assignedGroupCodes)) {
            $query->whereIn('student_group', $assignedGroupCodes);
        } else {
            // If no groups assigned, show no applications (or optionally show all for HEA testing)
            // For now, show none to enforce proper assignment workflow
            $query->whereRaw('1 = 0'); // This returns no results
        }

        $applications = $query->get();

        // Calculate stats for this AA's assigned applications only
        $assignedApplicationIds = ExemptionApplication::whereIn('student_group', $assignedGroupCodes)
            ->pluck('id');

        $totalExemptedCourses = ApplicationSubject::whereIn('exemption_application_id', $assignedApplicationIds)
            ->where('status', 'exempted')
            ->count();
        $totalProcessedCourses = ApplicationSubject::whereIn('exemption_application_id', $assignedApplicationIds)
            ->whereNotNull('status')
            ->count();

        $stats = [
            'pending_review' => $applications->count(),
            'total_reviewed' => ExemptionApplication::whereIn('student_group', $assignedGroupCodes)
                ->where('status', '!=', 'Submitted')
                ->count(),
            'exempted_courses' => $totalExemptedCourses,
            'total_courses' => $totalProcessedCourses,
            'assigned_groups' => $assignedGroupCodes,
        ];

        return view('academic_advisor.dashboard', compact('applications', 'stats', 'assignedGroupCodes'));
    }

    /**
     * Display the specified application.
     */
    public function show(ExemptionApplication $application)
    {
        // Load the related data needed for the view including course equivalency information
        $application->load(['student.user', 'applicationSubjects', 'transcript']);
        
        // Process application subjects to add display-friendly data
        $subjects = $application->applicationSubjects->map(function($subject) {
            $notes = $subject->notes ? json_decode($subject->notes, true) : null;
            
            // For exempted subjects, try to get equivalency data if notes are empty
            $equivalentCourse = '-';
            $matchPercentage = 0;
            
            if (in_array($subject->status, ['exempted', 'Approved'])) {
                // First try to get from notes
                if ($notes && isset($notes['equivalent_course'])) {
                    $equivalentCourse = $notes['equivalent_course'];
                    $matchPercentage = $notes['match_percentage'] ?? 0;
                } else {
                    // Fallback: Look up from CourseEquivalency table
                    $equivalency = CourseEquivalency::where('diploma_course_code', $subject->course_code)
                        ->where('program_code', $application->current_program_code ?? 'CS251')
                        ->first();
                    
                    if ($equivalency) {
                        $equivalentCourse = $equivalency->degree_course_code;
                        $matchPercentage = $equivalency->match_percentage;
                    }
                }
            } else {
                // For other subjects, try notes first, then fallback to database lookup
                if ($notes && isset($notes['equivalent_course'])) {
                    $equivalentCourse = $notes['equivalent_course'];
                    $matchPercentage = $notes['match_percentage'] ?? 0;
                } else {
                    // Fallback: Look up from CourseEquivalency table for any subject
                    $equivalency = CourseEquivalency::where('diploma_course_code', $subject->course_code)
                        ->where('program_code', $application->current_program_code ?? 'CS251')
                        ->first();
                    
                    if ($equivalency) {
                        $equivalentCourse = $equivalency->degree_course_code;
                        $matchPercentage = $equivalency->match_percentage;
                    } else {
                        $equivalentCourse = '-';
                        $matchPercentage = 0;
                    }
                }
            }
            
            // Check if this is a combination course (contains " & ")
            $isCombination = strpos($subject->course_code, ' & ') !== false;
            $individualGrades = null;

            if ($isCombination && $notes && isset($notes['individual_grades'])) {
                // For combination courses, get individual grades from notes
                $individualGrades = $notes['individual_grades'];
            }

            return [
                'id' => $subject->id,
                'course_code' => $subject->course_code,
                'course_name' => $subject->course_name,
                'credit_hour' => $subject->credit_hour,
                'grade_letter' => $this->convertGPAToGrade($subject->grade),
                'grade_gpa' => $subject->grade,
                'individual_grades' => $individualGrades, // For combination courses
                'is_combination' => $isCombination,
                'status' => $subject->status,
                'exemption_reason' => $subject->exemption_reason,
                'equivalent_course' => $equivalentCourse,
                'match_percentage' => $matchPercentage,
                'extraction_method' => $subject->extraction_method,
                'ocr_confidence_score' => $subject->ocr_confidence_score,
                'needs_verification' => $subject->needs_verification,
            ];
        });
        
        // Group subjects by exemption status for better display
        // Pre-qualified subjects: originally 'exempted' by OCR, may have academic advisor decisions
        $exemptedSubjects = $subjects->where(function($subject) {
            // Include if originally exempted by OCR OR if it's an approved/rejected/forwarded exempted course
            return $subject['status'] === 'exempted' ||
                   (in_array($subject['status'], ['Approved', 'Rejected']) &&
                    str_contains($subject['exemption_reason'] ?? '', 'All criteria met'));
        });

        // Manual review subjects: originally not exempted by OCR, may have academic advisor decisions
        $nonExemptedSubjects = $subjects->where(function($subject) {
            // Include if originally not exempted OR if it's a manual decision on non-exempted course
            return !in_array($subject['status'], ['exempted']) &&
                   !str_contains($subject['exemption_reason'] ?? '', 'All criteria met');
        });

        return view('academic_advisor.show', compact('application', 'subjects', 'exemptedSubjects', 'nonExemptedSubjects'));
    }
    
    /**
     * Convert GPA value back to letter grade.
     */
    private function convertGPAToGrade($gpa)
    {
        // Convert to float for consistent comparison
        $gpa = (float) $gpa;
        
        // Use ranges instead of exact matches for more reliable conversion
        if ($gpa >= 4.00) return 'A';
        if ($gpa >= 3.67) return 'A-';
        if ($gpa >= 3.33) return 'B+';
        if ($gpa >= 3.00) return 'B';
        if ($gpa >= 2.67) return 'B-';
        if ($gpa >= 2.33) return 'C+';
        if ($gpa >= 2.00) return 'C';
        if ($gpa >= 1.67) return 'C-';
        if ($gpa >= 1.33) return 'D+';
        if ($gpa >= 1.00) return 'D';
        if ($gpa >= 0.00) return 'F';
        
        return 'Unknown';
    }

    /**
     * Process a single subject decision.
     */
    public function processSubjectDecision(Request $request, ExemptionApplication $application, ApplicationSubject $subject)
    {
        try {
            $request->validate([
                'decision' => 'required|string|in:Approved,Rejected,Undo',
            ]);

            // Verify that the subject belongs to the application
            if ($subject->exemption_application_id !== $application->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Subject does not belong to this application.'
                ], 400);
            }

            // Wrap in database transaction for data consistency
            return DB::transaction(function() use ($request, $application, $subject) {

            // Handle undo operation
            if ($request->decision === 'Undo') {
                // Reset to original status based on subject type
                if (in_array($subject->status, ['Approved', 'Rejected'])) {
                    // Determine original status based on exemption criteria
                    $originalStatus = $this->determineOriginalStatus($subject, $application);
                    $subject->status = $originalStatus;
                    $subject->save();
                    $subject->refresh(); // Refresh model to ensure we have latest data
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot undo - subject is not in a decided state. Current status: ' . $subject->status
                    ], 400);
                }
            } else {
                // Update the subject status for regular decisions (including after undo)
                $subject->status = $request->decision;
                $subject->save();
                $subject->refresh(); // Refresh model to ensure we have latest data
            }

            // Log the action for audit trail
            $actionType = $request->decision === 'Undo' ? 'Academic Advisor Decision Undo' : 'Academic Advisor Decision';
            $details = [
                'decision' => $request->decision,
                'course_code' => $subject->course_code,
                'course_name' => $subject->course_name,
                'application_id' => $application->id,
                'program_code' => $application->current_program_code ?? 'N/A',
                'extraction_method' => $subject->extraction_method,
            ];
            
            if ($request->decision === 'Undo') {
                $details['new_status'] = $subject->status;
                $details['action_description'] = 'Reset subject to original OCR-determined status';
            }
            
            \App\Models\AuditTrail::create([
                'user_id' => Auth::id(),
                'action' => $actionType,
                'target_type' => 'ApplicationSubject',
                'target_id' => $subject->id,
                'details' => json_encode($details)
            ]);

            // Send notification to student (except for Undo action)
            if ($request->decision !== 'Undo') {
                $this->sendStudentDecisionNotification($application, $subject, $request->decision);
            }

            // Check if all subjects have been processed by academic advisor
            $pendingSubjects = $application->applicationSubjects()
                ->whereNotIn('status', ['Approved', 'Rejected'])
                ->count();

            // If all subjects are processed, update application status and record reviewer
            if ($pendingSubjects === 0) {
                $application->status = 'Reviewed by Academic Advisor';
                $application->reviewed_by = Auth::id();
                $application->reviewed_at = now();
                $application->save();
            }

            $message = $request->decision === 'Undo' 
                ? 'Decision undone successfully for ' . $subject->course_code . '. Course reset to pending status.'
                : 'Decision recorded successfully for ' . $subject->course_code;
                
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'subject_id' => $subject->id,
                    'new_status' => $subject->status, // Use actual current status, not the request decision
                    'pending_subjects' => $pendingSubjects
                ]);
            }); // End DB transaction

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid decision provided.',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Error processing academic advisor decision: ' . $e->getMessage(), [
                'subject_id' => $subject->id,
                'application_id' => $application->id,
                'user_id' => Auth::id(),
                'decision' => $request->decision ?? 'Unknown',
                'subject_status' => $subject->status ?? 'Unknown',
                'stack_trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your decision. Please try again.'
            ], 500);
        }
    }

    /**
     * Process the decisions for each subject in an application.
     */
    public function processDecisions(Request $request, ExemptionApplication $application)
    {
        $request->validate([
            'subjects' => 'required|array',
            'decision' => 'required|array',
            'decision.*' => 'required|string|in:Approved,Rejected',
        ]);

        foreach ($request->subjects as $subjectId) {
            $subject = ApplicationSubject::find($subjectId);
            if ($subject && isset($request->decision[$subjectId])) {
                // Update the status for each individual subject
                $subject->status = $request->decision[$subjectId];
                $subject->save();
            }
        }

        // Update the overall application status to show it has been processed by the academic advisor
        $application->status = 'Reviewed by Academic Advisor';
        $application->save();

        return redirect()->route('academic_advisor.dashboard')->with('success', 'Decisions have been submitted successfully.');
    }

    /**
     * Securely stream the transcript file to the user.
     */
    public function viewTranscript(ExemptionApplication $application)
    {
        $transcript = $application->transcript;

        // Ensure the transcript exists
        if (!$transcript) {
            abort(404, 'Transcript not found.');
        }

        // Check multiple possible file locations due to storage configuration changes
        $filePath = null;
        
        // First try the private disk (new storage location)
        if (Storage::disk('private')->exists($transcript->file_path)) {
            $filePath = Storage::disk('private')->path($transcript->file_path);
        }
        // Then try the default disk (old storage location) 
        elseif (Storage::exists($transcript->file_path)) {
            $filePath = Storage::path($transcript->file_path);
        }
        // Finally try the legacy path with private subdirectory
        else {
            $legacyPath = 'private/' . $transcript->file_path;
            if (file_exists(storage_path('app/' . $legacyPath))) {
                $filePath = storage_path('app/' . $legacyPath);
            }
        }
        
        if (!$filePath || !file_exists($filePath)) {
            abort(404, 'Transcript file not found on server');
        }

        // We can add verification logic here later using the digital signature
        
        // Return the file as a stream so it opens in the browser
        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $transcript->original_filename . '"'
        ]);
    }

    /**
     * Determine the original status for a subject when undoing an academic advisor decision.
     */
    private function determineOriginalStatus(ApplicationSubject $subject, ExemptionApplication $application)
    {
        try {
            // Check if this subject would have been originally exempted based on OCR criteria
            $equivalency = CourseEquivalency::where('diploma_course_code', $subject->course_code)
                ->where('program_code', $application->current_program_code ?? 'CS251')
                ->first();

            if ($equivalency) {
                // Check if it meets the three exemption criteria
                $gradeAcceptable = $subject->grade >= 2.00; // C or above
                $matchPercentageOK = $equivalency->match_percentage > 80;

                if ($gradeAcceptable && $matchPercentageOK) {
                    return 'exempted'; // Would have been pre-qualified
                }
            }

            // Return the appropriate "not eligible" status based on why it failed
            if (!$equivalency) {
                return 'not_found';
            } elseif ($subject->grade < 2.00) {
                return 'not_eligible_grade';
            } elseif ($equivalency->match_percentage <= 80) {
                return 'not_eligible_match';
            }

            // Default fallback
            return 'not_found';

        } catch (\Exception $e) {
            Log::error('Error determining original status: ' . $e->getMessage(), [
                'subject_id' => $subject->id,
                'course_code' => $subject->course_code,
                'application_id' => $application->id
            ]);

            // Safe fallback
            return 'not_found';
        }
    }

    /**
     * Bulk approve all pre-qualified courses in an application.
     */
    public function bulkApprovePreQualified(Request $request, ExemptionApplication $application)
    {
        try {
            // Wrap in database transaction for data consistency
            return DB::transaction(function() use ($application) {

                // Get all pre-qualified subjects (those that met OCR criteria)
                $preQualifiedSubjects = $application->applicationSubjects()
                    ->where(function($query) {
                        $query->where('status', 'exempted')
                              ->orWhere(function($q) {
                                  $q->whereIn('status', ['Approved', 'Rejected'])
                                    ->where('exemption_reason', 'LIKE', '%All criteria met%');
                              });
                    })
                    ->whereNotIn('status', ['Approved']) // Don't re-approve already approved ones
                    ->get();

                if ($preQualifiedSubjects->isEmpty()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No pre-qualified courses available to approve.'
                    ], 400);
                }

                $approvedCount = 0;
                $courseCodes = [];

                foreach ($preQualifiedSubjects as $subject) {
                    // Update status to Approved
                    $subject->status = 'Approved';
                    $subject->save();

                    $approvedCount++;
                    $courseCodes[] = $subject->course_code;

                    // Log the action for audit trail
                    \App\Models\AuditTrail::create([
                        'user_id' => Auth::id(),
                        'action' => 'Academic Advisor Bulk Approval',
                        'target_type' => 'ApplicationSubject',
                        'target_id' => $subject->id,
                        'details' => json_encode([
                            'decision' => 'Approved',
                            'course_code' => $subject->course_code,
                            'course_name' => $subject->course_name,
                            'application_id' => $application->id,
                            'program_code' => $application->current_program_code ?? 'N/A',
                            'extraction_method' => $subject->extraction_method,
                            'bulk_action' => true
                        ])
                    ]);
                }

                // Send single notification for bulk approval
                $this->sendBulkDecisionNotification($application, $courseCodes, 'Approved');

                // Check if all subjects have been processed
                $pendingSubjects = $application->applicationSubjects()
                    ->whereNotIn('status', ['Approved', 'Rejected'])
                    ->count();

                // If all subjects are processed, update application status and record reviewer
                if ($pendingSubjects === 0) {
                    $application->status = 'Reviewed by Academic Advisor';
                    $application->reviewed_by = Auth::id();
                    $application->reviewed_at = now();
                    $application->save();
                }

                return response()->json([
                    'success' => true,
                    'message' => "Successfully approved {$approvedCount} pre-qualified course(s): " . implode(', ', $courseCodes),
                    'approved_count' => $approvedCount,
                    'course_codes' => $courseCodes,
                    'pending_subjects' => $pendingSubjects
                ]);
            }); // End DB transaction

        } catch (\Exception $e) {
            Log::error('Error in bulk approval: ' . $e->getMessage(), [
                'application_id' => $application->id,
                'user_id' => Auth::id(),
                'stack_trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing bulk approval. Please try again.'
            ], 500);
        }
    }

    /**
     * Bulk reject all manual review courses in an application.
     */
    public function bulkRejectManualReview(Request $request, ExemptionApplication $application)
    {
        try {
            // Wrap in database transaction for data consistency
            return DB::transaction(function() use ($application) {

                // Get all manual review subjects (those that did NOT meet OCR criteria)
                $manualReviewSubjects = $application->applicationSubjects()
                    ->where(function($query) {
                        $query->whereNotIn('status', ['exempted'])
                              ->where(function($q) {
                                  $q->where('exemption_reason', 'NOT LIKE', '%All criteria met%')
                                    ->orWhereNull('exemption_reason');
                              });
                    })
                    ->whereNotIn('status', ['Rejected']) // Don't re-reject already rejected ones
                    ->get();

                if ($manualReviewSubjects->isEmpty()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No manual review courses available to reject.'
                    ], 400);
                }

                $rejectedCount = 0;
                $courseCodes = [];

                foreach ($manualReviewSubjects as $subject) {
                    // Update status to Rejected
                    $subject->status = 'Rejected';
                    $subject->save();

                    $rejectedCount++;
                    $courseCodes[] = $subject->course_code;

                    // Log the action for audit trail
                    \App\Models\AuditTrail::create([
                        'user_id' => Auth::id(),
                        'action' => 'Academic Advisor Bulk Rejection',
                        'target_type' => 'ApplicationSubject',
                        'target_id' => $subject->id,
                        'details' => json_encode([
                            'decision' => 'Rejected',
                            'course_code' => $subject->course_code,
                            'course_name' => $subject->course_name,
                            'application_id' => $application->id,
                            'program_code' => $application->current_program_code ?? 'N/A',
                            'extraction_method' => $subject->extraction_method,
                            'bulk_action' => true
                        ])
                    ]);
                }

                // Send single notification for bulk rejection
                $this->sendBulkDecisionNotification($application, $courseCodes, 'Rejected');

                // Check if all subjects have been processed
                $pendingSubjects = $application->applicationSubjects()
                    ->whereNotIn('status', ['Approved', 'Rejected'])
                    ->count();

                // If all subjects are processed, update application status and record reviewer
                if ($pendingSubjects === 0) {
                    $application->status = 'Reviewed by Academic Advisor';
                    $application->reviewed_by = Auth::id();
                    $application->reviewed_at = now();
                    $application->save();
                }

                return response()->json([
                    'success' => true,
                    'message' => "Successfully rejected {$rejectedCount} manual review course(s): " . implode(', ', $courseCodes),
                    'rejected_count' => $rejectedCount,
                    'course_codes' => $courseCodes,
                    'pending_subjects' => $pendingSubjects
                ]);
            }); // End DB transaction

        } catch (\Exception $e) {
            Log::error('Error in bulk rejection: ' . $e->getMessage(), [
                'application_id' => $application->id,
                'user_id' => Auth::id(),
                'stack_trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing bulk rejection. Please try again.'
            ], 500);
        }
    }

    /**
     * Display all equivalency lists (Read-Only for Academic Advisors)
     * Organized by program with current + history structure
     */
    public function viewEquivalencyLists()
    {
        // Define supported programs
        $programs = ['CDCS230', 'CDCS251', 'CDCS253', 'CDCS255', 'CDCS266'];

        $programData = [];
        $totalPublished = 0;
        $latestPublishedProgram = null;
        $latestPublishedDate = null;

        foreach ($programs as $programCode) {
            // Get current (latest) HEA-endorsed CS110 (internal) list for this program
            // ONLY shows internal lists that went through: Resource Person → HEA Endorsement → Published
            // Note: Uses published_at instead of status because lists revert to draft for continuous editing
            $current = EquivalencyList::where('program_code', $programCode)
                ->whereNotNull('published_at') // Has been published at least once
                ->where('category', 'internal') // CS110 lists only, not external institution mappings
                ->whereNotNull('endorsed_at') // Must be HEA-endorsed
                ->where('is_active', true) // Current active list
                ->with(['creator', 'publisher', 'endorsedBy', 'courseEquivalencies'])
                ->orderBy('endorsed_at', 'desc')
                ->first();

            // Academic Advisors do NOT see historical/archived lists
            // Only Resource Person and HEA can view archived lists
            $history = collect(); // Empty collection

            $count = $current ? 1 : 0;
            $totalPublished += $count;

            $programData[$programCode] = [
                'current' => $current,
                'history' => $history,
                'count' => $count,
                'name' => $this->getProgramName($programCode),
            ];

            // Track the program with the latest HEA-endorsed list to auto-expand
            $currentEndorsedDate = $current ? $current->endorsed_at : null;
            if ($currentEndorsedDate && (!$latestPublishedDate || $currentEndorsedDate > $latestPublishedDate)) {
                $latestPublishedProgram = $programCode;
                $latestPublishedDate = $currentEndorsedDate;
            }
        }

        // Statistics (only HEA-endorsed CS110 lists count as published)
        $stats = [
            'total_published' => $totalPublished,
        ];

        // If no published lists exist, default to first program
        if ($latestPublishedProgram === null) {
            $latestPublishedProgram = $programs[0];
        }

        // Indicate this is read-only for Academic Advisors
        $isReadOnly = true;

        return view('academic_advisor.equivalency_lists.index', compact(
            'programData',
            'programs',
            'stats',
            'isReadOnly',
            'latestPublishedProgram'
        ));
    }

    /**
     * Get program name by code
     */
    private function getProgramName($code)
    {
        $programNames = [
            'CDCS230' => 'SARJANA MUDA SAINS KOMPUTER (KEPUJIAN)',
            'CDCS251' => 'SARJANA MUDA SAINS KOMPUTER (KEPUJIAN) PENGKOMPUTERAN NETSENTRIK',
            'CDCS253' => 'SARJANA MUDA SAINS KOMPUTER (KEPUJIAN) PENGKOMPUTERAN MULTIMEDIA',
            'CDCS255' => 'SARJANA MUDA SAINS KOMPUTER (KEPUJIAN) RANGKAIAN KOMPUTER',
            'CDCS266' => 'SARJANA MUDA SISTEM MAKLUMAT (KEPUJIAN) KEJURUTERAAN SISTEM MAKLUMAT',
        ];

        return $programNames[$code] ?? $code;
    }

    /**
     * View all course equivalencies (Read-Only for Academic Advisors)
     * Same view as Program Coordinator
     */
    public function viewAllCourseEquivalencies()
    {
        // Get all supported programs from config (show all 5 degree programs)
        $supportedPrograms = config('programs.supported_programs');
        $programs = collect($supportedPrograms)->map(function($name, $code) {
            return (object) [
                'code' => $code,
                'name' => $name,
            ];
        })->values();

        // Get all unique institutions from course equivalencies
        $institutions = CourseEquivalency::whereNotNull('diploma_institution')
            ->where('diploma_institution', '!=', '')
            ->distinct()
            ->orderBy('diploma_institution')
            ->pluck('diploma_institution')
            ->toArray();

        // Pass the correct API route for Academic Advisors
        $apiRoute = route('academic_advisor.api.existing_equivalencies');
        $backRoute = route('academic_advisor.dashboard');

        return view('program_coordinator.course_equivalencies.view', compact('programs', 'institutions', 'apiRoute', 'backRoute'));
    }

    /**
     * API endpoint to get existing course equivalencies for a program
     * (Read-Only for Academic Advisors)
     */
    public function getExistingEquivalencies(Request $request)
    {
        $programCode = $request->get('program_code');
        $status = $request->get('status', 'current'); // current, all_published, include_drafts
        $semester = $request->get('semester');
        $category = $request->get('category');
        $institution = $request->get('institution');

        if (!$programCode) {
            return response()->json(['error' => 'Program code is required'], 400);
        }

        // Build query for equivalencies
        $query = CourseEquivalency::with('equivalencyList');

        // Filter by program code
        if ($programCode !== 'ALL') {
            $query->where('program_code', $programCode);
        }

        // Filter by status
        $query->whereHas('equivalencyList', function($q) use ($status, $programCode) {
            if ($status === 'current') {
                // Only get from latest published list per program
                $q->where('status', 'published')
                  ->whereIn('id', function($subquery) use ($programCode) {
                      $subquery->selectRaw('MAX(id)')
                          ->from('equivalency_lists')
                          ->where('status', 'published')
                          ->when($programCode !== 'ALL', function($q2) use ($programCode) {
                              $q2->where('program_code', $programCode);
                          })
                          ->groupBy('program_code');
                  });
            } elseif ($status === 'all_published') {
                $q->where('status', 'published');
            }
            // 'include_drafts' = no status filter
        });

        // Filter by semester
        if ($semester && $semester !== 'ALL') {
            $query->whereHas('equivalencyList', function($q) use ($semester) {
                $q->where('semester', $semester);
            });
        }

        // Filter by category
        if ($category && $category !== 'ALL') {
            $query->whereHas('equivalencyList', function($q) use ($category) {
                $q->where('category', $category);
            });
        }

        // Filter by institution
        if ($institution && $institution !== 'ALL') {
            $query->whereHas('equivalencyList', function($q) use ($institution) {
                if ($institution === 'internal') {
                    $q->where('category', 'internal');
                } else {
                    $q->where('source_institution', $institution);
                }
            });
        }

        // Get all equivalencies
        $equivalencies = $query
            ->orderBy('program_code')
            ->orderBy('diploma_course_code')
            ->get()
            ->map(function($eq) {
                return [
                    'id' => $eq->id,
                    'program_code' => $eq->program_code,
                    'diploma_course_code' => $eq->diploma_course_code,
                    'diploma_course_name' => $eq->diploma_course_name,
                    'diploma_credit_hour' => $eq->diploma_credit_hour,
                    'diploma_institution' => $eq->diploma_institution,
                    'degree_course_code' => $eq->degree_course_code,
                    'degree_course_name' => $eq->degree_course_name,
                    'degree_credit_hour' => $eq->degree_credit_hour,
                    'match_percentage' => $eq->match_percentage,
                    'is_eligible' => $eq->is_eligible,
                    'list_semester' => $eq->equivalencyList->semester ?? 'N/A',
                    'list_category' => $eq->equivalencyList->category ?? 'N/A',
                    'list_status' => $eq->equivalencyList->status ?? 'N/A',
                    'source_institution' => $eq->equivalencyList->source_institution ?? 'Internal',
                ];
            });

        return response()->json($equivalencies);
    }

    /**
     * Show a specific equivalency list (Read-Only for Academic Advisors)
     * Same view as Program Coordinator but read-only
     */
    public function showEquivalencyList(EquivalencyList $list)
    {
        $list->load(['creator', 'publisher', 'courseEquivalencies']);

        // Indicate this is read-only for Academic Advisors
        $isReadOnly = true;

        return view('program_coordinator.equivalency_lists.show', compact('list', 'isReadOnly'));
    }

    /**
     * Download PDF of a published equivalency list
     */
    public function downloadListPdf(EquivalencyList $list)
    {
        // Ensure the list is published and HEA-endorsed
        if ($list->endorsed_at === null || !$list->is_active) {
            abort(404, 'List not available');
        }

        $list->load(['creator', 'endorser', 'publisher', 'courseEquivalencies']);

        return view('student.course_equivalencies.pdf', compact('list'));
    }

    /**
     * Show Academic Advisor's students organized by groups
     */
    public function myStudents()
    {
        $user = Auth::user();

        // Get the AA's assigned group codes from program_group_configs
        $academicYear = ProgramGroupConfig::getCurrentAcademicYear();
        $intake = ProgramGroupConfig::getCurrentIntake();

        $assignedGroups = ProgramGroupConfig::active()
            ->forSession($academicYear, $intake)
            ->where('assigned_user_id', $user->id)
            ->pluck('group_code')
            ->toArray();

        if (empty($assignedGroups)) {
            // No assigned groups
            return view('academic_advisor.my_students', [
                'groupData' => [],
                'stats' => [
                    'total_students' => 0,
                    'total_applications' => 0,
                    'pending_applications' => 0,
                    'reviewed_applications' => 0,
                ],
            ]);
        }
        $groupData = [];
        $totalStudents = 0;
        $totalApplications = 0;
        $pendingApplications = 0;
        $reviewedApplications = 0;

        // Group names mapping (group code => full program name)
        $groupProgramNames = [
            'CDCS2301B' => 'BACHELOR OF COMPUTER SCIENCE (HONS.)',
            'CDCS2303B' => 'BACHELOR OF COMPUTER SCIENCE (HONS.)',
            'CDCS2303C' => 'BACHELOR OF COMPUTER SCIENCE (HONS.)',
            'CDCS2513A' => 'BACHELOR OF COMPUTER SCIENCE (HONS.) NETCENTRIC COMPUTING',
            'CDCS2531A' => 'BACHELOR OF COMPUTER SCIENCE (HONS.) MULTIMEDIA COMPUTING',
            'CDCS2533B' => 'BACHELOR OF COMPUTER SCIENCE (HONS.) MULTIMEDIA COMPUTING',
            'CDCS2551A' => 'BACHELOR OF COMPUTER SCIENCE (HONS.) COMPUTER NETWORKS',
            'CDCS2553B' => 'BACHELOR OF COMPUTER SCIENCE (HONS.) COMPUTER NETWORKS',
            'CDCS2663A' => 'BACHELOR OF INFORMATION SYSTEMS (HONS.) INFORMATION SYSTEMS ENGINEERING',
        ];

        foreach ($assignedGroups as $groupCode) {
            // Get students for this group by matching applications with this student_group
            $applications = \App\Models\ExemptionApplication::where('student_group', $groupCode)
                ->with(['student.user'])
                ->latest()
                ->get();

            // Group by matric_no to get unique students (handles data integrity issues)
            $studentsGrouped = $applications->groupBy('matric_no');

            // Add application status to each student
            $studentsData = $studentsGrouped->map(function($studentApplications) use (&$totalApplications, &$pendingApplications, &$reviewedApplications) {
                $latestApplication = $studentApplications->first();
                $student = $latestApplication->student;

                if ($latestApplication) {
                    $totalApplications++;
                    if ($latestApplication->status === 'Submitted') {
                        $pendingApplications++;
                    } else {
                        $reviewedApplications++;
                    }
                }

                return [
                    'id' => $student->id ?? null,
                    'matric_no' => $latestApplication->matric_no ?? 'N/A',
                    'name' => $latestApplication->student_name ?? ($student->user->name ?? 'N/A'),
                    'email' => $student->user->email ?? 'N/A',
                    'campus' => $latestApplication->current_campus ?? 'N/A',
                    'intake_semester' => $latestApplication->current_semester ?? 'N/A',
                    'application_status' => $latestApplication->status,
                    'application_id' => $latestApplication->id,
                    'application_date' => $latestApplication->created_at,
                ];
            })->values();

            $totalStudents += $studentsData->count();

            $groupData[$groupCode] = [
                'code' => $groupCode,
                'name' => $groupProgramNames[$groupCode] ?? $groupCode,
                'students' => $studentsData,
                'count' => $studentsData->count(),
            ];
        }

        $stats = [
            'total_students' => $totalStudents,
            'total_applications' => $totalApplications,
            'pending_applications' => $pendingApplications,
            'reviewed_applications' => $reviewedApplications,
        ];

        return view('academic_advisor.my_students', compact('groupData', 'stats'));
    }

    /**
     * Send notification to student when a single subject decision is made.
     */
    private function sendStudentDecisionNotification(ExemptionApplication $application, ApplicationSubject $subject, string $decision): void
    {
        try {
            // Get student's user_id
            $studentUserId = $application->student->user_id ?? null;

            if (!$studentUserId) {
                Log::warning('Cannot send notification: Student user_id not found', [
                    'application_id' => $application->id,
                    'subject_id' => $subject->id
                ]);
                return;
            }

            // Determine notification message based on decision
            $courseInfo = $subject->course_code . ' - ' . $subject->course_name;

            switch ($decision) {
                case 'Approved':
                    $title = 'Course Approved for Exemption';
                    $message = "Your course {$courseInfo} has been approved for credit exemption.";
                    break;
                case 'Rejected':
                    $title = 'Course Not Approved for Exemption';
                    $message = "Your course {$courseInfo} was not approved for credit exemption.";
                    break;
                default:
                    return; // Unknown decision, don't send notification
            }

            Notification::create([
                'user_id' => $studentUserId,
                'type' => 'request_reviewed',
                'title' => $title,
                'message' => $message,
                'link' => route('student.application.status'),
                'is_read' => false,
            ]);

            Log::info('Student notification sent for subject decision', [
                'student_user_id' => $studentUserId,
                'subject_id' => $subject->id,
                'decision' => $decision
            ]);

        } catch (\Exception $e) {
            // Log error but don't fail the main operation
            Log::error('Failed to send student notification', [
                'error' => $e->getMessage(),
                'application_id' => $application->id,
                'subject_id' => $subject->id
            ]);
        }
    }

    /**
     * Send notification to student for bulk decisions (approve all / reject all).
     */
    private function sendBulkDecisionNotification(ExemptionApplication $application, array $courseCodes, string $decision): void
    {
        try {
            // Get student's user_id
            $studentUserId = $application->student->user_id ?? null;

            if (!$studentUserId) {
                Log::warning('Cannot send bulk notification: Student user_id not found', [
                    'application_id' => $application->id
                ]);
                return;
            }

            $courseCount = count($courseCodes);
            $courseList = implode(', ', array_slice($courseCodes, 0, 3));
            if ($courseCount > 3) {
                $courseList .= ' and ' . ($courseCount - 3) . ' more';
            }

            if ($decision === 'Approved') {
                $title = $courseCount . ' Course(s) Approved';
                $message = "Your courses ({$courseList}) have been approved for credit exemption.";
            } else {
                $title = $courseCount . ' Course(s) Not Approved';
                $message = "Your courses ({$courseList}) were not approved for credit exemption.";
            }

            Notification::create([
                'user_id' => $studentUserId,
                'type' => 'request_reviewed',
                'title' => $title,
                'message' => $message,
                'link' => route('student.application.status'),
                'is_read' => false,
            ]);

            Log::info('Student bulk notification sent', [
                'student_user_id' => $studentUserId,
                'application_id' => $application->id,
                'decision' => $decision,
                'course_count' => $courseCount
            ]);

        } catch (\Exception $e) {
            // Log error but don't fail the main operation
            Log::error('Failed to send student bulk notification', [
                'error' => $e->getMessage(),
                'application_id' => $application->id
            ]);
        }
    }
}
