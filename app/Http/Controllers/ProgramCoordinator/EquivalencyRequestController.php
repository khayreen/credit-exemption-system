<?php

namespace App\Http\Controllers\ProgramCoordinator;

use App\Http\Controllers\Controller;
use App\Models\ApplicationSubject;
use App\Models\CourseEquivalencyRequest;
use App\Models\ExemptionApplication;
use App\Models\ProgramCoordinator;
use App\Models\ResourcePerson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EquivalencyRequestController extends Controller
{
    /**
     * Display the dedicated Equivalency Requests page
     */
    public function index()
    {
        $coordinator = Auth::user()->programCoordinator;

        if (!$coordinator) {
            abort(403, 'Program Coordinator profile not found.');
        }

        // Get all pending requests for this coordinator's programs
        $requests = CourseEquivalencyRequest::whereIn('current_program_code', $coordinator->program_codes)
            ->where('coordinator_decision', 'pending')
            ->with(['student'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Add validation for each request
        foreach ($requests as $request) {
            $request->transcript_validation = $this->validateRequestAgainstTranscript($request);
        }

        // Group requests by diploma_course_code
        $groupedRequests = $requests->groupBy('diploma_course_code');

        // Get requests by status for statistics
        $allRequests = CourseEquivalencyRequest::whereIn('current_program_code', $coordinator->program_codes)
            ->with(['student'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Count requests that should be rejected (not in transcript)
        $notInTranscriptCount = $requests->filter(function ($req) {
            return isset($req->transcript_validation['status']) &&
                   $req->transcript_validation['status'] === 'not_in_transcript';
        })->count();

        // Statistics
        $stats = [
            'pending' => $allRequests->where('coordinator_decision', 'pending')->count(),
            'approved' => $allRequests->where('coordinator_decision', 'equivalent')->count(),
            'rejected' => $allRequests->where('coordinator_decision', 'not_equivalent')->count(),
            'forwarded' => $allRequests->where('coordinator_decision', 'forward_to_rp')->count(),
            'unique_courses' => $groupedRequests->count(),
            'not_in_transcript' => $notInTranscriptCount,
        ];

        // Program breakdown for filter
        $programBreakdown = [];
        foreach ($coordinator->program_codes as $programCode) {
            $programRequests = $requests->where('current_program_code', $programCode);
            $programBreakdown[$programCode] = [
                'code' => $programCode,
                'name' => $this->getProgramName($programCode),
                'pending_count' => $programRequests->count(),
                'grouped' => $programRequests->groupBy('diploma_course_code'),
            ];
        }

        // Get recent decisions (last 10) for quick reference
        $recentDecisions = CourseEquivalencyRequest::whereIn('current_program_code', $coordinator->program_codes)
            ->where('coordinator_decision', '!=', 'pending')
            ->with(['student.user'])
            ->orderBy('coordinator_decided_at', 'desc')
            ->limit(10)
            ->get();

        return view('program_coordinator.equivalency_requests.index', compact(
            'groupedRequests',
            'stats',
            'coordinator',
            'programBreakdown',
            'requests',
            'recentDecisions'
        ));
    }

    /**
     * Display the Program Coordinator dashboard with grouped requests
     */
    public function dashboard()
    {
        $coordinator = Auth::user()->programCoordinator;

        if (!$coordinator) {
            abort(403, 'Program Coordinator profile not found.');
        }

        // Get all pending requests for this coordinator's programs
        $requests = CourseEquivalencyRequest::whereIn('current_program_code', $coordinator->program_codes)
            ->where('coordinator_decision', 'pending')
            ->with(['student'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Group requests by diploma_course_code
        $groupedRequests = $requests->groupBy('diploma_course_code');

        // Get equivalency lists statistics
        $totalLists = \App\Models\EquivalencyList::whereIn('program_code', $coordinator->program_codes)->count();
        $draftLists = \App\Models\EquivalencyList::whereIn('program_code', $coordinator->program_codes)
            ->where('status', 'draft')
            ->count();

        // Get total course mappings
        $totalMappings = \App\Models\CourseEquivalency::whereIn('program_code', $coordinator->program_codes)
            ->whereHas('equivalencyList', function($query) {
                $query->where('status', 'published');
            })
            ->count();

        // Statistics
        $stats = [
            'total_requests' => $requests->count(),
            'unique_courses' => $groupedRequests->count(),
            'programs_managed' => count($coordinator->program_codes),
            'total_lists' => $totalLists,
            'draft_lists' => $draftLists,
            'total_mappings' => $totalMappings,
        ];

        // Program breakdown
        $programBreakdown = [];
        foreach ($coordinator->program_codes as $programCode) {
            $programName = $this->getProgramName($programCode);
            $programRequests = $requests->where('current_program_code', $programCode)->count();
            $programLists = \App\Models\EquivalencyList::where('program_code', $programCode)->count();
            $programMappings = \App\Models\CourseEquivalency::where('program_code', $programCode)
                ->whereHas('equivalencyList', function($query) {
                    $query->where('status', 'published');
                })
                ->count();

            $programBreakdown[] = [
                'code' => $programCode,
                'name' => $programName,
                'requests_count' => $programRequests,
                'lists_count' => $programLists,
                'mappings_count' => $programMappings,
            ];
        }

        return view('program_coordinator.dashboard', compact('groupedRequests', 'stats', 'coordinator', 'programBreakdown'));
    }

    /**
     * Get program name from code
     */
    private function getProgramName($code)
    {
        $programs = [
            'CDCS230' => 'Bachelor of Computer Science (Hons.)',
            'CDCS251' => 'Bachelor of Computer Science (Hons.) Netcentric Computing',
            'CDCS253' => 'Bachelor of Computer Science (Hons.) Multimedia Computing',
            'CDCS255' => 'Bachelor of Computer Science (Hons.) Computer Networking',
            'CDCS266' => 'Bachelor of Information Systems (Hons.) Information Systems Engineering',
        ];

        return $programs[$code] ?? $code;
    }

    /**
     * Show detailed view of requests for a specific diploma course
     */
    public function showCourseRequests($diplomaCourseCode)
    {
        $coordinator = Auth::user()->programCoordinator;

        if (!$coordinator) {
            abort(403, 'Program Coordinator profile not found.');
        }

        // Get all requests for this specific diploma course
        $requests = CourseEquivalencyRequest::where('diploma_course_code', $diplomaCourseCode)
            ->whereIn('current_program_code', $coordinator->program_codes)
            ->where('coordinator_decision', 'pending')
            ->with(['student'])
            ->orderBy('created_at', 'desc')
            ->get();

        if ($requests->isEmpty()) {
            return redirect()
                ->route('program_coordinator.dashboard')
                ->with('error', 'No requests found for this course.');
        }

        // Add validation for each request
        foreach ($requests as $request) {
            $request->transcript_validation = $this->validateRequestAgainstTranscript($request);
        }

        return view('program_coordinator.course_requests', compact('requests', 'diplomaCourseCode'));
    }

    /**
     * Validate if the diploma course exists in student's credit exemption application
     */
    private function validateRequestAgainstTranscript(CourseEquivalencyRequest $request): array
    {
        // Check if student has an exemption application
        $application = ExemptionApplication::where('student_id', $request->student_id)->first();

        if (!$application) {
            return [
                'has_application' => false,
                'course_found' => null,
                'subject' => null,
                'recommendation' => 'warning',
                'status' => 'no_application',
                'message' => 'Student has no credit exemption application yet. Cannot verify course in transcript.',
                'can_forward' => true, // Can still forward for future use
            ];
        }

        // Check if diploma course code exists in application subjects
        $subject = ApplicationSubject::where('exemption_application_id', $application->id)
            ->where(function ($query) use ($request) {
                // Match exact course code or partial match (for combined codes)
                $query->where('course_code', $request->diploma_course_code)
                      ->orWhere('course_code', 'LIKE', '%' . $request->diploma_course_code . '%');
            })
            ->first();

        if (!$subject) {
            return [
                'has_application' => true,
                'course_found' => false,
                'subject' => null,
                'recommendation' => 'reject',
                'status' => 'not_in_transcript',
                'message' => 'Course NOT found in student\'s transcript. Student never took this course!',
                'can_forward' => false, // Should not forward
                'application_id' => $application->id,
            ];
        }

        // Course found - determine recommendation based on current status
        $recommendation = 'forward';
        $message = 'Course verified in student\'s transcript.';

        if (in_array($subject->status, ['Approved', 'exempted'])) {
            $recommendation = 'info';
            $message = 'Course already exempted/approved. New equivalency may not be needed.';
        } elseif (in_array($subject->status, ['not_found', 'not_eligible_match'])) {
            $recommendation = 'forward';
            $message = 'Course in transcript but not exempted. Good candidate for equivalency review.';
        } elseif ($subject->status === 'Rejected') {
            $recommendation = 'review';
            $message = 'Course was previously rejected. Review if new equivalency changes decision.';
        }

        return [
            'has_application' => true,
            'course_found' => true,
            'subject' => [
                'id' => $subject->id,
                'course_code' => $subject->course_code,
                'course_name' => $subject->course_name,
                'grade' => $subject->grade,
                'credit_hour' => $subject->credit_hour,
                'status' => $subject->status,
                'exemption_reason' => $subject->exemption_reason,
            ],
            'recommendation' => $recommendation,
            'status' => 'verified',
            'message' => $message,
            'can_forward' => true,
            'application_id' => $application->id,
        ];
    }

    /**
     * Make decision on equivalency requests (Equivalent/Not Equivalent)
     */
    public function makeDecision(Request $request)
    {
        $validated = $request->validate([
            'request_ids' => 'required|array',
            'request_ids.*' => 'required|uuid|exists:course_equivalency_requests,id',
            'decision' => 'required|in:equivalent,not_equivalent',
            'degree_course_code' => 'required_if:decision,equivalent|nullable|string|max:20',
            'degree_course_name' => 'required_if:decision,equivalent|nullable|string|max:255',
            'match_percentage' => 'required_if:decision,equivalent|nullable|integer|min:0|max:100',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $coordinator = Auth::user()->programCoordinator;
            $updatedCount = 0;

            foreach ($validated['request_ids'] as $requestId) {
                $equivalencyRequest = CourseEquivalencyRequest::findOrFail($requestId);

                // Update request with coordinator decision
                $equivalencyRequest->update([
                    'coordinator_decision' => $validated['decision'],
                    'coordinator_notes' => $validated['notes'] ?? null,
                    'coordinator_decided_at' => now(),
                    'approved_degree_course_code' => $validated['decision'] === 'equivalent' ? strtoupper(trim($validated['degree_course_code'])) : null,
                    'approved_degree_course_name' => $validated['decision'] === 'equivalent' ? trim($validated['degree_course_name']) : null,
                    'match_percentage' => $validated['decision'] === 'equivalent' ? $validated['match_percentage'] : null,
                    'status' => $validated['decision'] === 'equivalent' ? 'approved' : 'rejected',
                ]);

                $updatedCount++;

                Log::info('Program Coordinator decision made', [
                    'coordinator_id' => $coordinator->id,
                    'request_id' => $requestId,
                    'decision' => $validated['decision'],
                    'diploma_course' => $equivalencyRequest->diploma_course_code,
                ]);
            }

            DB::commit();

            $decisionText = $validated['decision'] === 'equivalent' ? 'approved' : 'rejected';

            return redirect()
                ->route('program_coordinator.dashboard')
                ->with('success', "{$updatedCount} request(s) {$decisionText} successfully.");

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error making coordinator decision', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()
                ->withErrors(['error' => 'An error occurred while processing your decision: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Forward requests to Resource Person
     */
    public function forwardToRP(Request $request)
    {
        $validated = $request->validate([
            'request_ids' => 'required|array',
            'request_ids.*' => 'required|uuid|exists:course_equivalency_requests,id',
            'selected_lecturer_name' => 'required|string|max:255',
            'selected_lecturer_email' => 'required|email|max:255',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $coordinator = Auth::user()->programCoordinator;
            $updatedCount = 0;

            // Get the first request to determine program code
            $firstRequest = CourseEquivalencyRequest::findOrFail($validated['request_ids'][0]);
            $programCode = $firstRequest->current_program_code;

            // Find the appropriate Resource Person for this program
            $resourcePerson = ResourcePerson::whereJsonContains('assigned_programs', $programCode)->first();

            if (!$resourcePerson) {
                throw new \Exception("No Resource Person assigned for program code: {$programCode}");
            }

            foreach ($validated['request_ids'] as $requestId) {
                $equivalencyRequest = CourseEquivalencyRequest::findOrFail($requestId);

                // Update request with forward decision
                $equivalencyRequest->update([
                    'coordinator_decision' => 'forward_to_rp',
                    'coordinator_notes' => $validated['notes'] ?? null,
                    'coordinator_decided_at' => now(),
                    'selected_lecturer_name' => trim($validated['selected_lecturer_name']),
                    'selected_lecturer_email' => strtolower(trim($validated['selected_lecturer_email'])),
                    'reviewed_by' => $resourcePerson->id,
                    'status' => 'under_review',
                ]);

                $updatedCount++;

                Log::info('Program Coordinator forwarded to RP', [
                    'coordinator_id' => $coordinator->id,
                    'request_id' => $requestId,
                    'resource_person_id' => $resourcePerson->id,
                    'selected_lecturer' => $validated['selected_lecturer_email'],
                ]);
            }

            DB::commit();

            return redirect()
                ->route('program_coordinator.dashboard')
                ->with('success', "{$updatedCount} request(s) forwarded to Resource Person successfully.");

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error forwarding to RP', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()
                ->withErrors(['error' => 'An error occurred while forwarding to Resource Person: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Quick reject requests where course is not in student's transcript
     */
    public function rejectNotInTranscript(Request $request)
    {
        $validated = $request->validate([
            'request_ids' => 'required|array',
            'request_ids.*' => 'required|uuid|exists:course_equivalency_requests,id',
        ]);

        try {
            DB::beginTransaction();

            $coordinator = Auth::user()->programCoordinator;
            $rejectedCount = 0;
            $skippedCount = 0;

            foreach ($validated['request_ids'] as $requestId) {
                $equivalencyRequest = CourseEquivalencyRequest::findOrFail($requestId);

                // Verify this request is not in transcript
                $validation = $this->validateRequestAgainstTranscript($equivalencyRequest);

                if ($validation['status'] !== 'not_in_transcript') {
                    $skippedCount++;
                    continue;
                }

                // Reject the request
                $equivalencyRequest->update([
                    'coordinator_decision' => 'not_equivalent',
                    'coordinator_notes' => 'Rejected: Course not found in student\'s credit exemption application/transcript. ' .
                                          'Student did not take this course.',
                    'coordinator_decided_at' => now(),
                    'status' => 'rejected',
                ]);

                $rejectedCount++;

                Log::info('PC rejected request - not in transcript', [
                    'coordinator_id' => $coordinator->id,
                    'request_id' => $requestId,
                    'student_id' => $equivalencyRequest->student_id,
                    'diploma_course' => $equivalencyRequest->diploma_course_code,
                ]);

                // Notify student about rejection
                $this->notifyStudentOfRejection($equivalencyRequest, 'not_in_transcript');
            }

            DB::commit();

            $message = "{$rejectedCount} request(s) rejected (course not in transcript).";
            if ($skippedCount > 0) {
                $message .= " {$skippedCount} skipped (course found in transcript).";
            }

            return redirect()
                ->route('program_coordinator.equivalency_requests.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error rejecting not-in-transcript requests', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()
                ->withErrors(['error' => 'An error occurred: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Bulk reject all requests that are not in transcript
     */
    public function bulkRejectNotInTranscript()
    {
        try {
            DB::beginTransaction();

            $coordinator = Auth::user()->programCoordinator;

            // Get all pending requests
            $requests = CourseEquivalencyRequest::whereIn('current_program_code', $coordinator->program_codes)
                ->where('coordinator_decision', 'pending')
                ->get();

            $rejectedCount = 0;

            foreach ($requests as $equivalencyRequest) {
                $validation = $this->validateRequestAgainstTranscript($equivalencyRequest);

                if ($validation['status'] !== 'not_in_transcript') {
                    continue;
                }

                $equivalencyRequest->update([
                    'coordinator_decision' => 'not_equivalent',
                    'coordinator_notes' => 'Bulk rejected: Course not found in student\'s credit exemption application/transcript.',
                    'coordinator_decided_at' => now(),
                    'status' => 'rejected',
                ]);

                $rejectedCount++;

                // Notify student
                $this->notifyStudentOfRejection($equivalencyRequest, 'not_in_transcript');
            }

            DB::commit();

            return redirect()
                ->route('program_coordinator.equivalency_requests.index')
                ->with('success', "{$rejectedCount} request(s) bulk rejected (course not in transcript).");

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error in bulk reject not-in-transcript', [
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    /**
     * Notify student about rejection
     */
    private function notifyStudentOfRejection(CourseEquivalencyRequest $request, string $reason): void
    {
        try {
            $student = $request->student;

            if (!$student || !$student->user_id) {
                return;
            }

            $title = 'Course Equivalency Request Rejected';

            if ($reason === 'not_in_transcript') {
                $message = "Your course equivalency request for {$request->diploma_course_code} ({$request->diploma_course_name}) " .
                          "has been rejected. Reason: This course was not found in your credit exemption application. " .
                          "Please ensure you have applied for credit exemption and the course is included in your transcript.";
            } else {
                $message = "Your course equivalency request for {$request->diploma_course_code} has been rejected. " .
                          "Please contact your Program Coordinator for more information.";
            }

            \App\Models\Notification::create([
                'user_id' => $student->user_id,
                'type' => 'equivalency_request_rejected',
                'title' => $title,
                'message' => $message,
                'link' => route('student.equivalency.request.index'),
                'is_read' => false,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to notify student of rejection', [
                'request_id' => $request->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Display decision history for processed requests
     */
    public function history(Request $request)
    {
        $coordinator = Auth::user()->programCoordinator;

        if (!$coordinator) {
            abort(403, 'Program Coordinator profile not found.');
        }

        // Base query for non-pending requests
        $query = CourseEquivalencyRequest::whereIn('current_program_code', $coordinator->program_codes)
            ->where('coordinator_decision', '!=', 'pending')
            ->with(['student.user']);

        // Filter by status
        $status = $request->get('status', 'all');
        if ($status !== 'all') {
            $statusMap = [
                'approved' => 'equivalent',
                'rejected' => 'not_equivalent',
                'forwarded' => 'forward_to_rp',
            ];
            if (isset($statusMap[$status])) {
                $query->where('coordinator_decision', $statusMap[$status]);
            }
        }

        // Search by course code or student name
        $search = $request->get('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('diploma_course_code', 'LIKE', "%{$search}%")
                  ->orWhere('diploma_course_name', 'LIKE', "%{$search}%")
                  ->orWhereHas('student', function ($sq) use ($search) {
                      $sq->where('matric_no', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('student.user', function ($sq) use ($search) {
                      $sq->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Filter by program
        $program = $request->get('program');
        if ($program && in_array($program, $coordinator->program_codes)) {
            $query->where('current_program_code', $program);
        }

        // Filter by date range
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        if ($dateFrom) {
            $query->whereDate('coordinator_decided_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('coordinator_decided_at', '<=', $dateTo);
        }

        // Get results with pagination
        $requests = $query->orderBy('coordinator_decided_at', 'desc')->paginate(20);

        // Statistics for all processed requests
        $allProcessed = CourseEquivalencyRequest::whereIn('current_program_code', $coordinator->program_codes)
            ->where('coordinator_decision', '!=', 'pending');

        $stats = [
            'total' => (clone $allProcessed)->count(),
            'approved' => (clone $allProcessed)->where('coordinator_decision', 'equivalent')->count(),
            'rejected' => (clone $allProcessed)->where('coordinator_decision', 'not_equivalent')->count(),
            'forwarded' => (clone $allProcessed)->where('coordinator_decision', 'forward_to_rp')->count(),
        ];

        return view('program_coordinator.history', compact(
            'requests',
            'stats',
            'coordinator',
            'status',
            'search',
            'program',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * Show details of a specific request
     */
    public function showRequest($id)
    {
        $coordinator = Auth::user()->programCoordinator;

        if (!$coordinator) {
            abort(403, 'Program Coordinator profile not found.');
        }

        $request = CourseEquivalencyRequest::whereIn('current_program_code', $coordinator->program_codes)
            ->with(['student.user'])
            ->findOrFail($id);

        // Add validation info
        $request->transcript_validation = $this->validateRequestAgainstTranscript($request);

        return view('program_coordinator.show_request', compact('request'));
    }
}
