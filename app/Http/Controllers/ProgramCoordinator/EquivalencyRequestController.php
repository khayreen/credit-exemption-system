<?php

namespace App\Http\Controllers\ProgramCoordinator;

use App\Http\Controllers\Controller;
use App\Models\CourseEquivalencyRequest;
use App\Models\ProgramCoordinator;
use App\Models\ResourcePerson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EquivalencyRequestController extends Controller
{
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

        // Get pending mappings from Resource Persons
        $pendingMappings = \App\Models\PendingEquivalencyMapping::whereIn('program_code', $coordinator->program_codes)
            ->where('status', 'pending')
            ->count();

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
            'pending_mappings' => $pendingMappings,
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

        return view('program_coordinator.course_requests', compact('requests', 'diplomaCourseCode'));
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
}
