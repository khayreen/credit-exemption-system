<?php

namespace App\Http\Controllers\AcademicAdvisor;

use App\Http\Controllers\Controller;
use App\Models\ExemptionApplication;
use App\Models\ApplicationSubject;
use App\Models\CourseEquivalency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the applications for the academic advisor.
     */
    public function index()
    {
        // Load applications with their OCR-extracted courses
        $applications = \App\Models\ExemptionApplication::where('status', 'Submitted')
            ->with(['student.user', 'applicationSubjects'])
            ->latest()
            ->get();
        
        // Calculate enhanced stats including OCR data
        $totalExemptedCourses = ApplicationSubject::where('status', 'exempted')->count();
        $totalProcessedCourses = ApplicationSubject::whereNotNull('status')->count();
        
        $stats = [
            'pending_review' => $applications->count(),
            'total_reviewed' => \App\Models\ExemptionApplication::where('status', '!=', 'Submitted')->count(),
            'exempted_courses' => $totalExemptedCourses,
            'total_courses' => $totalProcessedCourses,
        ];

        return view('academic_advisor.dashboard', compact('applications', 'stats'));
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
            
            return [
                'id' => $subject->id,
                'course_code' => $subject->course_code,
                'course_name' => $subject->course_name,
                'credit_hour' => $subject->credit_hour,
                'grade_letter' => $this->convertGPAToGrade($subject->grade),
                'grade_gpa' => $subject->grade,
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
                   (in_array($subject['status'], ['Approved', 'Rejected', 'Forward to Coordinator']) &&
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
                'decision' => 'required|string|in:Approved,Rejected,Forward to Coordinator,Undo',
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
                if (in_array($subject->status, ['Approved', 'Rejected', 'Forward to Coordinator'])) {
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

            // Check if all subjects have been processed by academic advisor
            $pendingSubjects = $application->applicationSubjects()
                ->whereNotIn('status', ['Approved', 'Rejected', 'Forward to Coordinator'])
                ->count();

            // If all subjects are processed, update application status
            if ($pendingSubjects === 0) {
                $application->status = 'Reviewed by Academic Advisor';
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
            'decision.*' => 'required|string|in:Approved,Rejected,Forward to Coordinator',
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
                                  $q->whereIn('status', ['Approved', 'Rejected', 'Forward to Coordinator'])
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

                // Check if all subjects have been processed
                $pendingSubjects = $application->applicationSubjects()
                    ->whereNotIn('status', ['Approved', 'Rejected', 'Forward to Coordinator'])
                    ->count();

                // If all subjects are processed, update application status
                if ($pendingSubjects === 0) {
                    $application->status = 'Reviewed by Academic Advisor';
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

                // Check if all subjects have been processed
                $pendingSubjects = $application->applicationSubjects()
                    ->whereNotIn('status', ['Approved', 'Rejected', 'Forward to Coordinator'])
                    ->count();

                // If all subjects are processed, update application status
                if ($pendingSubjects === 0) {
                    $application->status = 'Reviewed by Academic Advisor';
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
}
