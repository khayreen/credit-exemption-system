<?php

namespace App\Http\Controllers\AcademicAdvisor;

use App\Http\Controllers\Controller;
use App\Models\PendingReevaluation;
use App\Services\ReevaluationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReevaluationController extends Controller
{
    protected ReevaluationService $reevaluationService;

    public function __construct(ReevaluationService $reevaluationService)
    {
        $this->reevaluationService = $reevaluationService;
    }

    /**
     * Display list of pending re-evaluations.
     */
    public function index()
    {
        // Get the academic advisor's assigned programs
        $academicAdvisor = Auth::user()->academicAdvisor;
        $assignedPrograms = $academicAdvisor->assigned_programs ?? [];

        // Query pending re-evaluations
        $query = PendingReevaluation::with([
            'applicationSubject.exemptionApplication.student.user',
            'courseEquivalency',
            'decidedBy',
        ])
        ->pending()
        ->notExpired()
        ->orderBy('created_at', 'desc');

        // Filter by assigned programs if applicable
        if (!empty($assignedPrograms)) {
            $query->whereHas('applicationSubject.exemptionApplication', function ($q) use ($assignedPrograms) {
                $q->whereIn('current_program_code', $assignedPrograms);
            });
        }

        $pendingReevaluations = $query->get();

        // Group by equivalency for easier review
        $groupedByEquivalency = $pendingReevaluations->groupBy(function ($item) {
            return $item->diploma_course_code . '|' . $item->degree_course_code;
        });

        // Get recent decisions (last 7 days)
        $recentDecisionsQuery = PendingReevaluation::with([
            'applicationSubject.exemptionApplication.student.user',
            'courseEquivalency',
            'decidedBy',
        ])
        ->whereIn('status', ['approved', 'rejected'])
        ->where('decided_at', '>=', now()->subDays(7))
        ->orderBy('decided_at', 'desc');

        if (!empty($assignedPrograms)) {
            $recentDecisionsQuery->whereHas('applicationSubject.exemptionApplication', function ($q) use ($assignedPrograms) {
                $q->whereIn('current_program_code', $assignedPrograms);
            });
        }

        $recentDecisions = $recentDecisionsQuery->limit(20)->get();

        // Get statistics
        $stats = $this->reevaluationService->getStats();

        return view('academic_advisor.reevaluations.index', compact(
            'pendingReevaluations',
            'groupedByEquivalency',
            'recentDecisions',
            'stats'
        ));
    }

    /**
     * Show details of a specific re-evaluation.
     */
    public function show(PendingReevaluation $reevaluation)
    {
        $reevaluation->load([
            'applicationSubject.exemptionApplication.student.user',
            'applicationSubject.exemptionApplication.transcript',
            'courseEquivalency',
            'decidedBy',
        ]);

        return view('academic_advisor.reevaluations.show', compact('reevaluation'));
    }

    /**
     * Approve a pending re-evaluation.
     */
    public function approve(Request $request, PendingReevaluation $reevaluation)
    {
        $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        if (!$reevaluation->isActionable()) {
            return back()->with('error', 'This re-evaluation is no longer actionable.');
        }

        $result = $this->reevaluationService->approveReevaluation(
            $reevaluation,
            Auth::user(),
            $request->notes
        );

        if ($result['success']) {
            return redirect()
                ->route('academic_advisor.reevaluations.index')
                ->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }

    /**
     * Reject a pending re-evaluation.
     */
    public function reject(Request $request, PendingReevaluation $reevaluation)
    {
        $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        if (!$reevaluation->isActionable()) {
            return back()->with('error', 'This re-evaluation is no longer actionable.');
        }

        $result = $this->reevaluationService->rejectReevaluation(
            $reevaluation,
            Auth::user(),
            $request->notes
        );

        if ($result['success']) {
            return redirect()
                ->route('academic_advisor.reevaluations.index')
                ->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }

    /**
     * Bulk approve selected re-evaluations.
     */
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'reevaluation_ids' => 'required|array|min:1',
            'reevaluation_ids.*' => 'uuid|exists:pending_reevaluations,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        $result = $this->reevaluationService->bulkApprove(
            $request->reevaluation_ids,
            Auth::user(),
            $request->notes
        );

        $message = "Bulk approval completed. Approved: {$result['approved']}, Failed: {$result['failed']}.";

        if ($result['failed'] > 0) {
            return redirect()
                ->route('academic_advisor.reevaluations.index')
                ->with('warning', $message);
        }

        return redirect()
            ->route('academic_advisor.reevaluations.index')
            ->with('success', $message);
    }

    /**
     * Bulk reject selected re-evaluations.
     */
    public function bulkReject(Request $request)
    {
        $request->validate([
            'reevaluation_ids' => 'required|array|min:1',
            'reevaluation_ids.*' => 'uuid|exists:pending_reevaluations,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        $result = $this->reevaluationService->bulkReject(
            $request->reevaluation_ids,
            Auth::user(),
            $request->notes
        );

        $message = "Bulk rejection completed. Rejected: {$result['rejected']}, Failed: {$result['failed']}.";

        if ($result['failed'] > 0) {
            return redirect()
                ->route('academic_advisor.reevaluations.index')
                ->with('warning', $message);
        }

        return redirect()
            ->route('academic_advisor.reevaluations.index')
            ->with('success', $message);
    }

    /**
     * Approve all re-evaluations for a specific equivalency mapping.
     */
    public function approveByEquivalency(Request $request)
    {
        $request->validate([
            'diploma_course_code' => 'required|string',
            'degree_course_code' => 'required|string',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Find all pending re-evaluations for this equivalency mapping
        $reevaluationIds = PendingReevaluation::pending()
            ->notExpired()
            ->where('diploma_course_code', $request->diploma_course_code)
            ->where('degree_course_code', $request->degree_course_code)
            ->pluck('id')
            ->toArray();

        if (empty($reevaluationIds)) {
            return back()->with('info', 'No pending re-evaluations found for this mapping.');
        }

        $result = $this->reevaluationService->bulkApprove(
            $reevaluationIds,
            Auth::user(),
            $request->notes ?? "Bulk approved for mapping: {$request->diploma_course_code} → {$request->degree_course_code}"
        );

        $message = "Approved all {$result['approved']} application(s) for mapping " .
                   "{$request->diploma_course_code} → {$request->degree_course_code}.";

        return redirect()
            ->route('academic_advisor.reevaluations.index')
            ->with('success', $message);
    }
}
