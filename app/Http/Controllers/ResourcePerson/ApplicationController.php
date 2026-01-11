<?php

namespace App\Http\Controllers\ResourcePerson;

use App\Http\Controllers\Controller;
use App\Models\ApplicationSubject;
use App\Models\Course;
use App\Models\CourseEquivalency;
use App\Models\CourseEquivalencyRequest;
use App\Models\ExternalLecturerRequest;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class ApplicationController extends Controller
{
    /**
     * Display a listing of subjects for the resource person to review.
     * Filters subjects by assigned program codes.
     */
    public function index()
    {
        // Get the current resource person's assigned programs
        $resourcePerson = Auth::user()->resourcePerson;
        $assignedPrograms = $resourcePerson->assigned_programs ?? [];

        // If no programs are assigned, show all subjects (backward compatibility)
        if (empty($assignedPrograms)) {
            $subjects = ApplicationSubject::whereIn('status', ['Pending Resource Person', 'Syllabus Received'])
                ->with('exemptionApplication.student.user')
                ->get();
        } else {
            // Filter subjects by assigned program codes
            $subjects = ApplicationSubject::whereIn('status', ['Pending Resource Person', 'Syllabus Received'])
                ->with('exemptionApplication.student.user')
                ->whereHas('exemptionApplication', function($query) use ($assignedPrograms) {
                    $query->whereIn('current_program_code', $assignedPrograms);
                })
                ->get();
        }

        // Get equivalency requests forwarded by Program Coordinators or pending review
        if (empty($assignedPrograms)) {
            $equivalencyRequests = CourseEquivalencyRequest::where(function($query) {
                    $query->where('coordinator_decision', 'forward_to_rp')
                          ->orWhere('status', 'pending')
                          ->orWhere('status', 'under_review');
                })
                ->with('student.user', 'coordinator', 'externalLecturerRequest')
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $equivalencyRequests = CourseEquivalencyRequest::where(function($query) {
                    $query->where('coordinator_decision', 'forward_to_rp')
                          ->orWhere('status', 'pending')
                          ->orWhere('status', 'under_review');
                })
                ->whereIn('current_program_code', $assignedPrograms)
                ->with('student.user', 'coordinator', 'externalLecturerRequest')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // Group requests by unique equivalency combination (diploma + suggested degree + program)
        // This ensures RP reviews each unique equivalency ONCE, not per student
        $groupedByEquivalency = $equivalencyRequests->groupBy(function($request) {
            return $request->diploma_course_code . '|' .
                   $request->suggested_degree_course_code . '|' .
                   $request->current_program_code;
        });

        // Group requests by coordinator and then by program code (for organized view)
        $groupedEquivalencyRequests = $equivalencyRequests->groupBy(function($request) {
            return $request->coordinator ? $request->coordinator->name : 'Direct Requests (No Coordinator)';
        })->map(function($coordinatorRequests) {
            return $coordinatorRequests->groupBy('current_program_code');
        });

        // Count equivalency requests with syllabus received (needs attention)
        $syllabusReceivedCount = $equivalencyRequests->filter(function($request) {
            return $request->syllabus_received_at !== null;
        })->count();

        $stats = [
            'subjects_for_review' => $subjects->count(),
            'syllabus_requests' => ApplicationSubject::where('status', 'Syllabus Requested')
                ->when(!empty($assignedPrograms), function($query) use ($assignedPrograms) {
                    $query->whereHas('exemptionApplication', function($q) use ($assignedPrograms) {
                        $q->whereIn('current_program_code', $assignedPrograms);
                    });
                })
                ->count(),
            'equivalency_requests' => $equivalencyRequests->count(),
            'equivalency_syllabus_received' => $syllabusReceivedCount,
            'unique_equivalencies' => $groupedByEquivalency->count(),
            'assigned_programs' => $assignedPrograms,
        ];

        return view('resource_person.dashboard', compact('subjects', 'stats', 'equivalencyRequests', 'groupedEquivalencyRequests', 'groupedByEquivalency'));
    }

    /**
     * Show the dedicated review page for a single subject.
     */
    public function review(ApplicationSubject $subject)
    {
        $degreeCourses = Course::orderBy('code')->get();
        return view('resource_person.review', compact('subject', 'degreeCourses'));
    }

    /**
     * Store a new course equivalency created by the resource person.
     */
    public function storeEquivalency(Request $request, ApplicationSubject $subject)
    {
        $request->validate([
            'degree_course_code' => 'required|string|exists:courses,code',
            'match_percentage' => 'required|numeric|min:0|max:100',
        ]);

        CourseEquivalency::create([
            'diploma_course_code' => $subject->course_code,
            'diploma_course_name' => $subject->course_name,
            'diploma_credit_hour' => $subject->credit_hour,
            'diploma_institution' => $subject->exemptionApplication->previous_institution,
            'degree_course_code' => $request->degree_course_code,
            'match_percentage' => $request->match_percentage,
            'program_code' => $subject->exemptionApplication->current_program_code ?? 'GENERAL',
            'approved_by_user_id' => Auth::id(),
            'notes' => 'Created by Resource Person for program ' . ($subject->exemptionApplication->current_program_code ?? 'GENERAL') . 
                      ' (OCR extracted: ' . ($subject->extraction_method == 'ocr' ? 'Yes' : 'No') . ')',
        ]);

        $subject->status = 'Approved (RP: ' . $request->degree_course_code . ')';
        $subject->save();

        return redirect()->route('resource_person.dashboard')->with('success', 'New equivalency has been created and saved.');
    }

    /**
     * Handle the "Request Syllabus" action with email notification.
     */
    public function requestSyllabus(Request $request, ApplicationSubject $subject)
    {
        $request->validate([
            'external_lecturer_name' => 'required|string|max:255',
            'external_lecturer_email' => 'required|email',
            'request_notes' => 'nullable|string|max:1000'
        ]);

        try {
            DB::beginTransaction();

            // Create external lecturer request record
            $externalRequest = ExternalLecturerRequest::create([
                'application_subject_id' => $subject->id,
                'external_lecturer_name' => $request->external_lecturer_name,
                'external_lecturer_email' => $request->external_lecturer_email,
                'request_notes' => $request->request_notes,
                'access_token' => bin2hex(random_bytes(32)),
                'token_expires_at' => now()->addDays(30), // 30 days expiry
                'status' => 'pending'
            ]);

            // Update subject status
            $subject->status = 'Syllabus Requested';
            $subject->save();

            // Create secure submission URL
            $submissionUrl = route('external.lecturer.submission.form', [
                'token' => $externalRequest->access_token
            ]);

            // Send email to external lecturer
            try {
                \Illuminate\Support\Facades\Mail::to($request->external_lecturer_email)
                    ->send(new \App\Mail\SyllabusRequestMail(
                        $request->external_lecturer_name,
                        $subject->exemptionApplication->student->user->name,
                        Auth::user()->name,
                        $subject->course_code,
                        $subject->course_name,
                        $subject->credit_hour,
                        $subject->exemptionApplication->previous_institution,
                        $subject->exemptionApplication->previous_program,
                        $submissionUrl,
                        $request->request_notes,
                        $externalRequest->token_expires_at
                    ));

                Log::info('Syllabus request email sent successfully', [
                    'external_lecturer_email' => $request->external_lecturer_email,
                    'submission_url' => $submissionUrl,
                    'course' => $subject->course_code . ' - ' . $subject->course_name
                ]);
            } catch (\Exception $mailException) {
                // Log email failure but don't rollback transaction
                Log::error('Failed to send syllabus request email', [
                    'error' => $mailException->getMessage(),
                    'external_lecturer_email' => $request->external_lecturer_email
                ]);

                // Continue with success message but inform user about email issue
                DB::commit();
                return redirect()->route('resource_person.dashboard')
                    ->with('warning', 'Syllabus request created but email could not be sent. Please share this link manually: ' . $submissionUrl);
            }

            DB::commit();

            return redirect()->route('resource_person.dashboard')->with('success',
                'Syllabus request has been sent successfully to ' . $request->external_lecturer_email .
                ' for course ' . $subject->course_code . '!');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to send syllabus request: ' . $e->getMessage());
            return back()->with('error', 'Failed to send syllabus request. Please try again.');
        }
    }

    /**
     * Handle the "Reject Subject" action, now with optional data.
     */
    public function reject(Request $request, ApplicationSubject $subject)
    {
        $request->validate([
            'degree_course_code' => 'nullable|string|exists:courses,code',
            'match_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        // If the RP provided equivalency details as a reason for rejection, record them.
        if ($request->filled('degree_course_code') && $request->filled('match_percentage')) {
            CourseEquivalency::create([
                'diploma_course_code' => $subject->course_code,
                'diploma_course_name' => $subject->course_name,
                'diploma_credit_hour' => $subject->credit_hour,
                'diploma_institution' => $subject->exemptionApplication->previous_institution,
                'degree_course_code' => $request->degree_course_code,
                'match_percentage' => $request->match_percentage,
                'approved_by_user_id' => Auth::id(),
                'notes' => 'Recorded during rejection by Resource Person. Match percentage may be too low.',
            ]);
        }

        // Update the subject's status to Rejected
        $subject->status = 'Rejected by Resource Person';
        $subject->save();

        return redirect()->route('resource_person.dashboard')->with('success', 'Subject ' . $subject->course_code . ' has been rejected.');
    }

    /**
     * Securely stream the submitted syllabus file to the user.
     */
    public function viewSyllabus(ApplicationSubject $subject)
    {
        $syllabusPath = $subject->syllabus_path;

        if (!$syllabusPath || !Storage::disk('private')->exists($syllabusPath)) {
            abort(404, 'Syllabus file not found.');
        }

        $filePath = Storage::disk('private')->path($syllabusPath);
        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="submitted_syllabus.pdf"'
        ]);
    }

    /**
     * Securely stream the external lecturer submission syllabus file.
     */
    public function viewExternalSyllabus(\App\Models\ExternalLecturerSubmission $submission)
    {
        $syllabusPath = $submission->syllabus_file_path;

        if (!$syllabusPath || !Storage::exists($syllabusPath)) {
            abort(404, 'Syllabus file not found.');
        }

        $filePath = Storage::path($syllabusPath);
        $originalName = $submission->syllabus_file_original_name ?? 'syllabus.pdf';

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $originalName . '"'
        ]);
    }

    /**
     * Show the course equivalency management page.
     * Filters programs by assigned program codes.
     */
    public function manageCourseEquivalencies()
    {
        // Get the current resource person's assigned programs
        $resourcePerson = Auth::user()->resourcePerson;
        $assignedPrograms = $resourcePerson->assigned_programs ?? [];

        // Get degree programs for dropdown (filter for SARJANA MUDA programs only)
        $query = Program::where('name', 'LIKE', 'SARJANA MUDA%');

        // If resource person has assigned programs, filter by those
        if (!empty($assignedPrograms)) {
            $query->whereIn('code', $assignedPrograms);
        }

        $degreePrograms = $query->orderBy('code')->get();

        return view('resource_person.course_equivalencies.manage', compact('degreePrograms'));
    }

    /**
     * Get existing equivalencies for a specific degree program via AJAX.
     * Supports optional institution filter for non-UiTM equivalencies.
     */
    public function getExistingEquivalencies(Request $request)
    {
        $programCode = $request->input('program_code');
        $institution = $request->input('institution');

        // Build query for this specific program
        $query = CourseEquivalency::where('program_code', $programCode)
                                 ->with('degreeCourse');

        // Apply institution filter if provided
        if ($institution) {
            $query->where('diploma_institution', 'LIKE', "%{$institution}%");
        }

        // Order by oldest first (most recent at bottom)
        $equivalencies = $query->orderBy('updated_at', 'asc')->get();

        return response()->json($equivalencies);
    }

    /**
     * Update a specific course equivalency.
     */
    public function updateEquivalency(Request $request, $equivalencyId)
    {
        $equivalency = CourseEquivalency::findOrFail($equivalencyId);
        
        $request->validate([
            'diploma_course_code' => ['required', 'string', function ($attribute, $value, $fail) {
                // Flexible validation for course codes - allow single, multiple (/), or combined (+) formats
                $pattern = '/^[A-Z]{2,4}\d{2,4}(\/[A-Z]{2,4}\d{2,4})*(\s*\+\s*[A-Z]{2,4}\d{2,4}(\/[A-Z]{2,4}\d{2,4})*)*$/i';
                if (!preg_match($pattern, trim($value))) {
                    $fail('The diploma course code format is invalid. Use formats like: CSC402, CSC138/CSC126, or CSC138/CSC126 + CSC186');
                }
            }],
            'diploma_course_name' => 'required|string',
            'diploma_credit_hours' => 'required|integer|min:1',
            'degree_course_code' => 'required|string',
            'degree_course_name' => 'required|string',
            'degree_credit_hours' => 'required|integer|min:1',
            'equivalency_percentage' => 'required|numeric|min:0|max:100',
        ]);

        $equivalency->update([
            'diploma_course_code' => $this->normalizeCourseCode($request->diploma_course_code),
            'diploma_course_name' => $request->diploma_course_name,
            'diploma_credit_hour' => $request->diploma_credit_hours,
            'degree_course_code' => $request->degree_course_code,
            'degree_course_name' => $request->degree_course_name,
            'degree_credit_hour' => $request->degree_credit_hours,
            'match_percentage' => $request->equivalency_percentage,
            'notes' => 'Updated by Resource Person on ' . now()->format('Y-m-d H:i:s'),
        ]);

        return response()->json(['success' => true, 'message' => 'Equivalency updated successfully']);
    }

    /**
     * Delete a specific course equivalency.
     */
    public function deleteEquivalency($equivalencyId)
    {
        $equivalency = CourseEquivalency::findOrFail($equivalencyId);
        $equivalency->delete();
        return response()->json(['success' => true, 'message' => 'Equivalency deleted successfully']);
    }

    /**
     * Get courses for a specific degree program via AJAX.
     */
    public function getDegreeProgramCourses(Request $request)
    {
        $programCode = $request->input('program_code');
        
        // Get courses that belong to this program (assuming course codes start with program code)
        $courses = Course::where('code', 'LIKE', $programCode . '%')
                        ->orderBy('code')
                        ->get(['code', 'name', 'credit_hour']);

        return response()->json($courses);
    }

    /**
     * Store bulk course equivalencies for a degree program.
     */
    public function storeBulkEquivalencies(Request $request)
    {
        try {
            // Debug: Log the incoming request data
            Log::info('Bulk equivalencies request data:', $request->all());
            
            $request->validate([
                'degree_program_code' => 'required|string',
                'diploma_courses' => 'required|array|min:1',
                'diploma_courses.*.diploma_course_code' => ['required', 'string', function ($attribute, $value, $fail) {
                    // Flexible validation for course codes - allow single, multiple (/), or combined (+) formats
                    $pattern = '/^[A-Z]{2,4}\d{2,4}(\/[A-Z]{2,4}\d{2,4})*(\s*\+\s*[A-Z]{2,4}\d{2,4}(\/[A-Z]{2,4}\d{2,4})*)*$/i';
                    if (!preg_match($pattern, trim($value))) {
                        $fail('The diploma course code format is invalid. Use formats like: CSC402, CSC138/CSC126, or CSC138/CSC126 + CSC186');
                    }
                }],
                'diploma_courses.*.diploma_course_name' => 'required|string',
                'diploma_courses.*.diploma_credit_hours' => 'required|integer|min:1',
                'diploma_courses.*.degree_course_code' => 'required|string',
                'diploma_courses.*.degree_course_name' => 'required|string',
                'diploma_courses.*.degree_credit_hours' => 'required|integer|min:1',
                'diploma_courses.*.equivalency_percentage' => 'required|numeric|min:0|max:100',
            ]);

            $createdCount = 0;
            $updatedCount = 0;

            foreach ($request->diploma_courses as $courseData) {
                // Normalize diploma course code format
                $normalizedDiplomaCourseCode = $this->normalizeCourseCode($courseData['diploma_course_code']);
                
                // Check if equivalency already exists
                $existingEquivalency = CourseEquivalency::where('diploma_course_code', $normalizedDiplomaCourseCode)
                                                       ->where('degree_course_code', $courseData['degree_course_code'])
                                                       ->where('program_code', $request->degree_program_code)
                                                       ->first();

                $equivalencyData = [
                    'diploma_course_code' => $normalizedDiplomaCourseCode,
                    'diploma_course_name' => $courseData['diploma_course_name'],
                    'diploma_credit_hour' => $courseData['diploma_credit_hours'],
                    'diploma_institution' => 'Multiple', // Since this is a general mapping
                    'degree_course_code' => $courseData['degree_course_code'],
                    'degree_course_name' => $courseData['degree_course_name'],
                    'degree_credit_hour' => $courseData['degree_credit_hours'],
                    'program_code' => $request->degree_program_code,
                    'match_percentage' => $courseData['equivalency_percentage'],
                    'approved_by_user_id' => Auth::id(),
                    'notes' => 'Bulk created/updated by Resource Person for program ' . $request->degree_program_code,
                ];

                if ($existingEquivalency) {
                    $existingEquivalency->update($equivalencyData);
                    $updatedCount++;
                } else {
                    CourseEquivalency::create($equivalencyData);
                    $createdCount++;
                }
            }

            $message = "Course equivalencies processed successfully. Created: {$createdCount}, Updated: {$updatedCount}";
            
            return response()->json([
                'success' => true, 
                'message' => $message,
                'created' => $createdCount,
                'updated' => $updatedCount
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error in storeBulkEquivalencies: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while saving the equivalency: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Normalize course code format for consistency.
     */
    private function normalizeCourseCode($courseCode)
    {
        // Trim whitespace and convert to uppercase
        $normalized = trim(strtoupper($courseCode));
        
        // Replace multiple spaces with single space
        $normalized = preg_replace('/\s+/', ' ', $normalized);
        
        // Ensure proper spacing around + symbol
        $normalized = preg_replace('/\s*\+\s*/', ' + ', $normalized);
        
        return $normalized;
    }

    /**
     * Process the resource person's finding for a subject.
     */
    public function processFinding(Request $request, \App\Models\ApplicationSubject $subject)
    {
        try {
            $request->validate([
                'degree_course_code' => 'required|string',
                'match_percentage' => 'required|numeric|min:0|max:100',
                'notes' => 'nullable|string|max:1000',
                'decision' => 'required|in:Equivalent,Not Equivalent'
            ]);

            DB::beginTransaction();

            // Generate automatic remark based on decision
            $degreeCourseCode = $request->degree_course_code;
            $automaticRemark = '';

            if ($request->decision === 'Equivalent') {
                $automaticRemark = "This subject has been reviewed and is approved for exemption. It is considered equivalent to degree course {$degreeCourseCode}.";
            } else {
                $automaticRemark = "This course has been assessed and is not equivalent to degree course {$degreeCourseCode}.";
            }

            // Combine automatic remark with optional notes
            $finalNotes = $automaticRemark;
            if ($request->notes) {
                $finalNotes .= "\n\nAdditional Notes: " . $request->notes;
            }

            // Update the subject status based on decision
            $status = $request->decision === 'Equivalent' ? 'Approved (RP: ' . Auth::user()->name . ')' : 'Rejected (RP: ' . Auth::user()->name . ')';

            $subject->update([
                'status' => $status,
                'exemption_reason' => $automaticRemark,
                'notes' => $finalNotes
            ]);

            // If equivalent, automatically create course equivalency
            if ($request->decision === 'Equivalent') {
                // Find the degree course to get its name
                $degreeCourse = Course::where('code', $request->degree_course_code)->first();
                $programCode = $subject->exemptionApplication->current_program_code ?? 'CS251';

                // Check if equivalency already exists
                $existingEquivalency = CourseEquivalency::where('diploma_course_code', $subject->course_code)
                    ->where('program_code', $programCode)
                    ->first();

                if (!$existingEquivalency) {
                    CourseEquivalency::create([
                        'diploma_course_code' => $subject->course_code,
                        'diploma_course_name' => $subject->course_name,
                        'diploma_credit_hour' => $subject->credit_hour,
                        'degree_course_code' => $request->degree_course_code,
                        'degree_course_name' => $degreeCourse ? $degreeCourse->name : $request->degree_course_code,
                        'degree_credit_hour' => $degreeCourse ? $degreeCourse->credit_hour : $subject->credit_hour,
                        'match_percentage' => $request->match_percentage,
                        'program_code' => $programCode,
                        'notes' => 'Created by Resource Person ' . Auth::user()->name . ' (OCR extracted: ' . ($subject->extraction_method == 'ocr' ? 'Yes' : 'No') . ')'
                    ]);
                }
            }

            // Log the action in audit trail with OCR context
            \App\Models\AuditTrail::create([
                'user_id' => Auth::id(),
                'action' => 'Resource Person Decision',
                'target_type' => 'ApplicationSubject',
                'target_id' => $subject->id,
                'details' => json_encode([
                    'decision' => $request->decision,
                    'degree_course_code' => $request->degree_course_code,
                    'match_percentage' => $request->match_percentage,
                    'automatic_remark' => $automaticRemark,
                    'additional_notes' => $request->notes,
                    'course_code' => $subject->course_code,
                    'course_name' => $subject->course_name,
                    'program_code' => $subject->exemptionApplication->current_program_code ?? 'N/A',
                    'extraction_method' => $subject->extraction_method,
                    'ocr_confidence' => $subject->ocr_confidence_score,
                    'exemption_reason' => $subject->exemption_reason,
                    'grade' => $subject->grade,
                    'credit_hours' => $subject->credit_hour,
                    'equivalency_created' => $request->decision === 'Equivalent' ? 'Yes' : 'No'
                ])
            ]);

            DB::commit();

            $message = $request->decision === 'Equivalent'
                ? 'Course marked as equivalent and equivalency created automatically for ' . $subject->course_code
                : 'Course marked as not equivalent for ' . $subject->course_code;

            return redirect()->route('resource_person.dashboard')->with('success', $message);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollback();
            \Illuminate\Support\Facades\Log::error('Resource Person finding error: ' . $e->getMessage(), [
                'subject_id' => $subject->id,
                'user_id' => Auth::id()
            ]);

            return back()->withErrors(['general' => 'An error occurred while processing your decision. Please try again.'])->withInput();
        }
    }

    /**
     * Display list of equivalency requests for review.
     */
    public function viewEquivalencyRequests()
    {
        $resourcePerson = Auth::user()->resourcePerson;
        $assignedPrograms = $resourcePerson->assigned_programs ?? [];

        // Get equivalency requests forwarded by Program Coordinators or pending review
        if (empty($assignedPrograms)) {
            $requests = CourseEquivalencyRequest::where(function($query) {
                    $query->where('coordinator_decision', 'forward_to_rp')
                          ->orWhere('status', 'pending')
                          ->orWhere('status', 'under_review');
                })
                ->with('student.user', 'reviewer', 'coordinator', 'externalLecturerRequest')
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $requests = CourseEquivalencyRequest::where(function($query) {
                    $query->where('coordinator_decision', 'forward_to_rp')
                          ->orWhere('status', 'pending')
                          ->orWhere('status', 'under_review');
                })
                ->whereIn('current_program_code', $assignedPrograms)
                ->with('student.user', 'reviewer', 'coordinator', 'externalLecturerRequest')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // Group requests by unique equivalency combination (diploma + suggested degree + program)
        // This ensures RP reviews each unique equivalency ONCE, not per student
        $groupedByEquivalency = $requests->groupBy(function($request) {
            return $request->diploma_course_code . '|' .
                   $request->suggested_degree_course_code . '|' .
                   $request->current_program_code;
        });

        // Group requests by coordinator and then by program code (for organized view)
        $groupedRequests = $requests->groupBy(function($request) {
            return $request->coordinator ? $request->coordinator->name : 'Direct Requests (No Coordinator)';
        })->map(function($coordinatorRequests) {
            return $coordinatorRequests->groupBy('current_program_code');
        });

        return view('resource_person.equivalency_requests.index', compact('requests', 'groupedRequests', 'groupedByEquivalency'));
    }

    /**
     * Display review page for a specific equivalency request.
     */
    public function reviewEquivalencyRequest(CourseEquivalencyRequest $request)
    {
        // Eager load relationships for syllabus viewing
        $request->load(['externalLecturerRequest.submission', 'student.user', 'reviewer.user']);

        return view('resource_person.equivalency_requests.review', compact('request'));
    }

    /**
     * Process the equivalency request (equivalent/not equivalent).
     */
    public function processEquivalencyRequest(Request $httpRequest, CourseEquivalencyRequest $request)
    {
        $httpRequest->validate([
            'decision' => 'required|in:approved,rejected',
            'match_percentage' => 'required|integer|min:0|max:100',
        ]);

        try {
            DB::beginTransaction();

            $decision = $httpRequest->decision;

            // Use the student's suggested degree course for evaluation
            $degreeCourseCode = strtoupper(trim($request->suggested_degree_course_code));
            $degreeCourseName = trim($request->suggested_degree_course_name);

            // Generate automatic remark based on decision
            $automaticRemark = '';
            if ($decision === 'approved') {
                $automaticRemark = "This subject has been reviewed and is approved for exemption. It is considered equivalent to degree course {$degreeCourseCode} ({$degreeCourseName}). Match percentage: {$httpRequest->match_percentage}%.";
            } else {
                $automaticRemark = "This course has been assessed and is not equivalent to degree course {$degreeCourseCode} ({$degreeCourseName}). Match percentage: {$httpRequest->match_percentage}%.";
            }

            // Find ALL requests with the same equivalency combination
            // (same diploma course + suggested degree course + program)
            $matchingRequests = CourseEquivalencyRequest::where('diploma_course_code', $request->diploma_course_code)
                ->where('suggested_degree_course_code', $request->suggested_degree_course_code)
                ->where('current_program_code', $request->current_program_code)
                ->where('status', '!=', 'approved') // Don't re-process already approved
                ->where('status', '!=', 'rejected') // Don't re-process already rejected
                ->get();

            $affectedStudentCount = $matchingRequests->count();

            // Update ALL matching requests with the same decision
            foreach ($matchingRequests as $matchingRequest) {
                $matchingRequest->status = $decision;
                $matchingRequest->reviewed_by = Auth::user()->resourcePerson->id;
                $matchingRequest->reviewed_at = now();
                $matchingRequest->reviewer_notes = $automaticRemark;

                // Store the evaluation details using the student's suggested courses
                $matchingRequest->approved_degree_course_code = $degreeCourseCode;
                $matchingRequest->approved_degree_course_name = $degreeCourseName;
                $matchingRequest->match_percentage = $httpRequest->match_percentage;

                $matchingRequest->save();

                Log::info('Course equivalency decision applied', [
                    'request_id' => $matchingRequest->id,
                    'student_id' => $matchingRequest->student_id,
                    'decision' => $decision,
                ]);
            }

            // Create course equivalency record ONCE for this equivalency (if approved)
            if ($decision === 'approved') {
                $existingEquivalency = CourseEquivalency::where('diploma_course_code', strtoupper(trim($request->diploma_course_code)))
                    ->where('degree_course_code', $degreeCourseCode)
                    ->where('program_code', $request->current_program_code)
                    ->first();

                if (!$existingEquivalency) {
                    CourseEquivalency::create([
                        'diploma_course_code' => strtoupper(trim($request->diploma_course_code)),
                        'diploma_course_name' => $request->diploma_course_name,
                        'diploma_credit_hour' => $request->diploma_credit_hours,
                        'diploma_institution' => $request->diploma_institution,
                        'degree_course_code' => $degreeCourseCode,
                        'degree_course_name' => $degreeCourseName,
                        'match_percentage' => $httpRequest->match_percentage,
                        'program_code' => $request->current_program_code,
                        'notes' => 'Created by Resource Person from equivalency request affecting ' . $affectedStudentCount . ' student(s). Match percentage: ' . $httpRequest->match_percentage . '%',
                    ]);

                    Log::info('Course equivalency created from RP decision', [
                        'diploma_course' => $request->diploma_course_code,
                        'degree_course' => $degreeCourseCode,
                        'match_percentage' => $httpRequest->match_percentage,
                        'affected_students' => $affectedStudentCount,
                    ]);
                }
            }

            DB::commit();

            // Send notifications to all affected parties
            $this->sendEquivalencyDecisionNotifications($request, $decision, $affectedStudentCount, $matchingRequests);

            $message = $decision === 'approved'
                ? "Course marked as equivalent for {$affectedStudentCount} student(s). Course equivalency created successfully!"
                : "Course marked as not equivalent for {$affectedStudentCount} student(s).";

            return redirect()->route('resource_person.equivalency_requests.index')->with('success', $message);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error processing equivalency request', [
                'error' => $e->getMessage(),
                'request_id' => $request->id
            ]);

            return back()->withErrors(['error' => 'An error occurred while processing the request: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Request official syllabus from external lecturer for equivalency request.
     */
    public function requestSyllabusForEquivalency(CourseEquivalencyRequest $equivalencyRequest)
    {
        try {
            DB::beginTransaction();

            // Use PC-selected lecturer if available, otherwise use student-provided lecturer
            $lecturerEmail = $equivalencyRequest->selected_lecturer_email ?? $equivalencyRequest->external_lecturer_email;
            $lecturerName = $equivalencyRequest->selected_lecturer_name ?? $equivalencyRequest->external_lecturer_name;

            // Create external lecturer request record
            $externalRequest = ExternalLecturerRequest::create([
                'course_equivalency_request_id' => $equivalencyRequest->id,
                'external_lecturer_email' => $lecturerEmail,
                'external_lecturer_name' => $lecturerName,
                'request_notes' => 'Official syllabus request for course equivalency verification: ' . $equivalencyRequest->diploma_course_code,
                'access_token' => bin2hex(random_bytes(32)),
                'token_expires_at' => now()->addDays(30), // 30 days expiry
                'status' => 'pending'
            ]);

            // Update equivalency request with sent timestamp
            $equivalencyRequest->syllabus_request_sent_at = now();
            $equivalencyRequest->save();

            // Create secure submission URL
            $submissionUrl = route('external.lecturer.submission.form', [
                'token' => $externalRequest->access_token
            ]);

            // Send email to external lecturer
            try {
                \Illuminate\Support\Facades\Mail::to($lecturerEmail)
                    ->send(new \App\Mail\SyllabusRequestMail(
                        $lecturerName,
                        'Multiple Students', // Don't expose individual student names
                        Auth::user()->name,
                        $equivalencyRequest->diploma_course_code,
                        $equivalencyRequest->diploma_course_name,
                        $equivalencyRequest->diploma_credit_hours,
                        $equivalencyRequest->diploma_institution,
                        $equivalencyRequest->diploma_program,
                        $submissionUrl,
                        'Official syllabus request for course equivalency verification',
                        $externalRequest->token_expires_at
                    ));

                Log::info('Syllabus request email sent successfully', [
                    'equivalency_request_id' => $equivalencyRequest->id,
                    'external_lecturer_email' => $lecturerEmail,
                    'submission_url' => $submissionUrl,
                    'diploma_course' => $equivalencyRequest->diploma_course_code . ' - ' . $equivalencyRequest->diploma_course_name
                ]);
            } catch (\Exception $mailException) {
                // Log email failure but don't rollback transaction
                Log::error('Failed to send syllabus request email', [
                    'error' => $mailException->getMessage(),
                    'external_lecturer_email' => $lecturerEmail
                ]);

                // Continue with success message but inform user about email issue
                DB::commit();
                return redirect()->route('resource_person.equivalency_requests.review', $equivalencyRequest)
                    ->with('warning', 'Syllabus request created but email could not be sent. Please share this link manually: ' . $submissionUrl);
            }

            DB::commit();

            return redirect()->route('resource_person.equivalency_requests.review', $equivalencyRequest)
                ->with('success', 'Syllabus request has been sent successfully to ' . $lecturerEmail . '!');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to send syllabus request for equivalency', [
                'error' => $e->getMessage(),
                'equivalency_request_id' => $equivalencyRequest->id
            ]);
            return back()->with('error', 'Failed to send syllabus request. Please try again.');
        }
    }

    /**
     * Preview the syllabus request email with all equivalency request data.
     */
    public function previewSyllabusEmail(CourseEquivalencyRequest $request)
    {
        // Generate access token and submission URL for preview
        $accessToken = bin2hex(random_bytes(32));
        $submissionUrl = route('external.lecturer.submission.form', ['token' => $accessToken]);
        $tokenExpiresAt = now()->addDays(30);

        // Prepare email data with all important information (use PC-selected or student-provided lecturer)
        $emailData = [
            'lecturer_name' => $request->selected_lecturer_name ?? $request->external_lecturer_name,
            'lecturer_email' => $request->selected_lecturer_email ?? $request->external_lecturer_email,
            'student_name' => 'Multiple Students', // Don't expose individual student names
            'student_matric' => 'N/A',
            'student_program' => $request->current_program_code . ' - ' . $request->current_program_name,
            'diploma_course_code' => $request->diploma_course_code,
            'diploma_course_name' => $request->diploma_course_name,
            'diploma_credit_hours' => $request->diploma_credit_hours,
            'diploma_institution' => $request->diploma_institution,
            'diploma_program' => $request->diploma_program,
            'suggested_degree_course' => $request->suggested_degree_course_code . ' - ' . $request->suggested_degree_course_name,
            'justification' => null, // No justification needed
            'submission_url' => $submissionUrl,
            'token_expires_at' => $tokenExpiresAt->format('d F Y, h:i A'),
            'request_id' => $request->id,
        ];

        // Default email subject and message
        $emailData['subject'] = 'Course Syllabus Request - UiTM Credit Exemption System';
        $emailData['message'] = "I am reaching out to request your kind assistance. My name is " . Auth::user()->name . ", and I am assisting with the course equivalency evaluation for Universiti Teknologi MARA (UiTM). We are currently reviewing a subject previously offered at your institution and require the complete course syllabus to proceed with our assessment.\n\n" .
                               "Please refer to the course details below and submit the syllabus using the secure link provided. We greatly appreciate your time and cooperation.";

        return view('resource_person.equivalency_requests.preview_email', compact('request', 'emailData'));
    }

    /**
     * Send the syllabus request email with custom content.
     */
    public function sendSyllabusEmail(Request $httpRequest, CourseEquivalencyRequest $request)
    {
        $httpRequest->validate([
            'email_greeting' => 'required|string|max:200',
            'email_subject' => 'required|string|max:255',
            'email_message' => 'required|string|max:5000',
        ]);

        try {
            DB::beginTransaction();

            // Use PC-selected lecturer if available, otherwise use student-provided lecturer
            $lecturerEmail = $request->selected_lecturer_email ?? $request->external_lecturer_email;
            $lecturerName = $request->selected_lecturer_name ?? $request->external_lecturer_name;

            // Create external lecturer request record
            $externalRequest = ExternalLecturerRequest::create([
                'course_equivalency_request_id' => $request->id,
                'external_lecturer_email' => $lecturerEmail,
                'external_lecturer_name' => $lecturerName,
                'request_notes' => $httpRequest->email_message,
                'access_token' => bin2hex(random_bytes(32)),
                'token_expires_at' => now()->addDays(30),
                'status' => 'pending'
            ]);

            // Update equivalency request with sent timestamp
            $request->syllabus_request_sent_at = now();
            $request->save();

            // Create secure submission URL
            $submissionUrl = route('external.lecturer.submission.form', [
                'token' => $externalRequest->access_token
            ]);

            // Prepare email data
            $emailData = [
                'subject' => $httpRequest->email_subject,
                'greeting' => $httpRequest->email_greeting,
                'lecturerName' => $lecturerName,
                'studentName' => 'Multiple Students', // Don't expose individual student names
                'studentMatric' => 'N/A',
                'studentProgram' => $request->current_program_code . ' - ' . $request->current_program_name,
                'diplomaCourseCode' => $request->diploma_course_code,
                'diplomaCourseName' => $request->diploma_course_name,
                'diplomaCreditHours' => $request->diploma_credit_hours,
                'diplomaInstitution' => $request->diploma_institution,
                'diplomaProgram' => $request->diploma_program,
                'suggestedDegreeCourse' => $request->suggested_degree_course_code . ' - ' . $request->suggested_degree_course_name,
                'justification' => null, // No justification
                'customMessage' => $httpRequest->email_message,
                'submissionUrl' => $submissionUrl,
                'tokenExpiresAt' => $externalRequest->token_expires_at,
            ];

            // Send email
            try {
                \Illuminate\Support\Facades\Mail::send('emails.syllabus_request_custom', $emailData, function($message) use ($lecturerEmail, $lecturerName, $emailData) {
                    $message->to($lecturerEmail, $lecturerName)
                            ->subject($emailData['subject']);
                });

                Log::info('Custom syllabus request email sent successfully', [
                    'equivalency_request_id' => $request->id,
                    'external_lecturer_email' => $lecturerEmail,
                    'submission_url' => $submissionUrl,
                ]);

                DB::commit();

                return redirect()->route('resource_person.equivalency_requests.review', $request)
                    ->with('success', 'Syllabus request email has been sent successfully to ' . $lecturerEmail . '!');

            } catch (\Exception $mailException) {
                Log::error('Failed to send custom syllabus request email', [
                    'error' => $mailException->getMessage(),
                    'external_lecturer_email' => $lecturerEmail
                ]);

                DB::commit();
                return redirect()->route('resource_person.equivalency_requests.review', $request)
                    ->with('warning', 'Syllabus request created but email could not be sent. Error: ' . $mailException->getMessage() . '. Submission URL: ' . $submissionUrl);
            }

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to create syllabus request', [
                'error' => $e->getMessage(),
                'equivalency_request_id' => $request->id
            ]);
            return back()->with('error', 'Failed to send syllabus request. Please try again. Error: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Send notifications to all affected parties after RP decision.
     */
    private function sendEquivalencyDecisionNotifications(
        CourseEquivalencyRequest $request,
        string $decision,
        int $affectedStudentCount,
        $matchingRequests
    ): void {
        $isApproved = $decision === 'approved';
        $decisionText = $isApproved ? 'Approved' : 'Rejected';
        $diplomaCourse = $request->diploma_course_code . ' - ' . $request->diploma_course_name;
        $degreeCourse = $request->suggested_degree_course_code . ' - ' . $request->suggested_degree_course_name;

        // 1. Notify all students who requested this equivalency
        foreach ($matchingRequests as $studentRequest) {
            \App\Models\Notification::create([
                'user_id' => $studentRequest->student->user_id,
                'type' => 'request_reviewed',
                'title' => 'Equivalency Request ' . $decisionText,
                'message' => "Your course equivalency request for {$diplomaCourse} has been reviewed and {$decisionText}.",
                'link' => route('student.equivalency.request.index'),
            ]);
        }

        // 2. If approved, notify all Academic Advisors about new mapping
        if ($isApproved) {
            // Get all academic advisors from all programs
            $academicAdvisors = \App\Models\AcademicAdvisor::with('user')->get();

            foreach ($academicAdvisors as $advisor) {
                \App\Models\Notification::create([
                    'user_id' => $advisor->user_id,
                    'type' => 'new_mapping',
                    'title' => 'New Course Mapping Added',
                    'message' => "A new course equivalency mapping has been added: {$diplomaCourse} → {$degreeCourse} for program {$request->current_program_code}.",
                    'link' => null, // Will be handled in dashboard
                ]);
            }

            // 3. Notify all Program Coordinators about new mapping
            $programCoordinators = \App\Models\ProgramCoordinator::with('user')->get();

            foreach ($programCoordinators as $coordinator) {
                \App\Models\Notification::create([
                    'user_id' => $coordinator->user_id,
                    'type' => 'new_mapping',
                    'title' => 'New Course Mapping Added',
                    'message' => "A new course equivalency mapping has been added: {$diplomaCourse} → {$degreeCourse} for program {$request->current_program_code}.",
                    'link' => null, // Will be handled in dashboard
                ]);
            }

            Log::info('Equivalency decision notifications sent', [
                'decision' => $decision,
                'affected_students' => $affectedStudentCount,
                'academic_advisors_notified' => $academicAdvisors->count(),
                'program_coordinators_notified' => $programCoordinators->count(),
            ]);
        }
    }
}
