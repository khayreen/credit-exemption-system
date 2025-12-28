<?php

namespace App\Http\Controllers\Hea;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ExemptionApplication;
use App\Models\AuditTrail;
use App\Models\SystemSetting;
use App\Models\EquivalencyList;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the main HEA dashboard.
     */
    public function index()
    {
        $stats = [
            'total_users' => \App\Models\User::count(),
            'total_applications' => \App\Models\ExemptionApplication::count(),
            'pending_applications' => \App\Models\ExemptionApplication::whereNotIn('status', ['Completed', 'Rejected by HEA'])->count(),
            'total_logs' => \App\Models\AuditTrail::count(),
            'pending_endorsements' => EquivalencyList::pending()->count(),
            'pending_internal' => EquivalencyList::pending()->where('category', 'internal')->count(),
            'pending_external' => EquivalencyList::pending()->where('category', 'external')->count(),
            'published_lists' => EquivalencyList::published()->count(),
        ];

        // Get recent pending submissions
        $pendingLists = EquivalencyList::with(['creator'])
            ->pending()
            ->orderBy('submitted_at', 'asc')
            ->take(5)
            ->get();

        return view('hea.dashboard', compact('stats', 'pendingLists'));
    }

    /**
     * Show the user management page.
     */
    public function users(Request $request)
    {
        // Get filter parameters
        $roleFilter = $request->get('role_filter', 'all');
        $sortOrder = $request->get('sort_order', 'latest');

        // Build query with eager loading of role relationships
        $query = User::with([
            'academicAdvisor',
            'programCoordinator',
            'resourcePerson',
            'externalLecturer'
        ]);

        // Apply role filter
        if ($roleFilter !== 'all') {
            $query->where('role', $roleFilter);
        }

        // Apply sort order
        if ($sortOrder === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $users = $query->get();

        // For external lecturers, get their submission data
        foreach ($users as $user) {
            if ($user->role === 'external_lecturer' && $user->externalLecturer) {
                // Get all submissions by this external lecturer
                $submissions = \App\Models\ExternalLecturerSubmission::whereHas('request', function($q) use ($user) {
                    $q->where('external_lecturer_email', $user->externalLecturer->email);
                })->with('request')->get();

                $user->external_submissions = $submissions;
            }
        }

        return view('hea.users', compact('users', 'roleFilter', 'sortOrder'));
    }

    /**
     * Show the application monitoring page.
     */
    public function applications(Request $request)
    {
        $statusFilter = $request->get('status_filter', 'all');

        $query = ExemptionApplication::with(['student.user', 'student.faculty']);

        // Apply status filter
        if ($statusFilter === 'pending') {
            $query->where('status', 'Submitted');
        } elseif ($statusFilter === 'reviewed') {
            $query->where('status', 'Reviewed by Academic Advisor');
        }

        // Get all applications
        $applications = $query->get();

        // For each reviewed application, fetch the academic advisor who reviewed it from audit trail
        foreach ($applications as $app) {
            if (strtolower($app->status) === 'reviewed by academic advisor') {
                // Find the audit trail entry for this application's review
                $auditLog = AuditTrail::where('target_entity', 'ExemptionApplication')
                    ->where('target_id', $app->id)
                    ->where('action', 'like', '%Forward%')
                    ->with('user.academicAdvisor')
                    ->latest()
                    ->first();

                $app->reviewed_by_advisor = $auditLog && $auditLog->user ? $auditLog->user : null;
            }
        }

        // Custom sorting: Pending applications first (oldest first), then reviewed (oldest first)
        $applications = $applications->sortBy(function ($app) {
            // Pending applications get priority 0, reviewed get priority 1
            $priority = (strtolower($app->status) === 'submitted') ? 0 : 1;
            // Return array for multi-level sorting: [priority, timestamp]
            return [$priority, $app->created_at->timestamp];
        })->values();

        return view('hea.applications', compact('applications'));
    }

    /**
     * Show the system logs page.
     */
    public function logs()
    {
        $logs = AuditTrail::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(50);
        return view('hea.logs', compact('logs'));
    }

    /**
     * Show the system settings page.
     */
    public function settings()
    {
        $settings = SystemSetting::orderBy('key')->get();
        return view('hea.settings', compact('settings'));
    }

    /**
     * Update system settings.
     */
    public function updateSettings(Request $request)
    {
        try {
            foreach ($request->except('_token', '_method') as $key => $value) {
                SystemSetting::set($key, $value);
            }

            return redirect()->route('hea.settings')->with('success', 'Settings updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('hea.settings')->with('error', 'Failed to update settings: ' . $e->getMessage());
        }
    }
}
