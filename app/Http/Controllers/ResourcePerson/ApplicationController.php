<?php

namespace App\Http\Controllers\ResourcePerson;

use App\Http\Controllers\Controller;
use App\Models\ApplicationSubject;
use App\Models\Course;
use App\Models\CourseEquivalency;
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

        $stats = [
            'subjects_for_review' => $subjects->count(),
            'syllabus_requests' => ApplicationSubject::where('status', 'Syllabus Requested')
                ->when(!empty($assignedPrograms), function($query) use ($assignedPrograms) {
                    $query->whereHas('exemptionApplication', function($q) use ($assignedPrograms) {
                        $q->whereIn('current_program_code', $assignedPrograms);
                    });
                })
                ->count(),
            'assigned_programs' => $assignedPrograms,
        ];

        return view('resource_person.dashboard', compact('subjects', 'stats'));
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
            'external_lecturer_email' => 'required|email',
            'request_notes' => 'nullable|string|max:1000'
        ]);

        try {
            DB::beginTransaction();

            // Create external lecturer request record
            $externalRequest = ExternalLecturerRequest::create([
                'application_subject_id' => $subject->id,
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

            // Send email notification (for now, just log it)
            Log::info('Syllabus request sent to: ' . $request->external_lecturer_email);
            Log::info('Submission URL: ' . $submissionUrl);
            Log::info('Course: ' . $subject->course_code . ' - ' . $subject->course_name);

            DB::commit();

            return redirect()->route('resource_person.dashboard')->with('success', 
                'Syllabus request has been sent to ' . $request->external_lecturer_email . 
                ' for course ' . $subject->course_code . '.');

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
     */
    public function getExistingEquivalencies(Request $request)
    {
        $programCode = $request->input('program_code');
        
        // Get existing equivalencies for this specific program, ordered by oldest first (most recent at bottom)
        $equivalencies = CourseEquivalency::where('program_code', $programCode)
                                         ->with('degreeCourse')
                                         ->orderBy('updated_at', 'asc')
                                         ->get();

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
                'decision' => 'required|in:Approved,Rejected'
            ]);

            DB::beginTransaction();

            // Update the subject status based on decision
            $status = $request->decision === 'Approved' ? 'Approved (RP: ' . Auth::user()->name . ')' : 'Rejected (RP: ' . Auth::user()->name . ')';
            
            $subject->update([
                'status' => $status,
                'notes' => $request->notes ?? $subject->notes
            ]);

            // If approved, automatically create course equivalency
            if ($request->decision === 'Approved') {
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
                    'notes' => $request->notes,
                    'course_code' => $subject->course_code,
                    'course_name' => $subject->course_name,
                    'program_code' => $subject->exemptionApplication->current_program_code ?? 'N/A',
                    'extraction_method' => $subject->extraction_method,
                    'ocr_confidence' => $subject->ocr_confidence_score,
                    'exemption_reason' => $subject->exemption_reason,
                    'grade' => $subject->grade,
                    'credit_hours' => $subject->credit_hour,
                    'equivalency_created' => $request->decision === 'Approved' ? 'Yes' : 'No'
                ])
            ]);

            DB::commit();

            $message = $request->decision === 'Approved' 
                ? 'Course approved and equivalency created automatically for ' . $subject->course_code
                : 'Course rejected for ' . $subject->course_code;

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
}
