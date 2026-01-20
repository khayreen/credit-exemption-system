<?php

namespace App\Http\Controllers\Hea;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ExemptionApplication;
use App\Models\AuditTrail;
use App\Models\SystemSetting;
use App\Models\EquivalencyList;
use App\Models\ResourcePerson;
use App\Notifications\SemesterReminderNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Show the main HEA dashboard.
     */
    public function index()
    {
        // Fetch pending user approvals once - use for both count and display
        $allPendingUserApprovals = User::where('approval_status', 'pending')
            ->whereIn('requested_role', ['academic_advisor', 'coordinator', 'resource_person'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Fetch pending endorsements once - use for count and categorization
        $allPendingEndorsements = EquivalencyList::with(['creator'])
            ->pending()
            ->orderBy('submitted_at', 'asc')
            ->get();

        // Build stats from already-fetched data where possible
        $stats = [
            'total_users' => User::count(),
            'pending_applications' => ExemptionApplication::whereNotIn('status', ['Completed', 'Rejected by HEA'])->count(),
            'pending_endorsements' => $allPendingEndorsements->count(),
            'pending_internal' => $allPendingEndorsements->where('category', 'internal')->count(),
            'pending_external' => $allPendingEndorsements->where('category', 'external')->count(),
            'published_lists' => EquivalencyList::published()->count(),
            'pending_user_approvals' => $allPendingUserApprovals->count(),
        ];

        // Take top 5 for display
        $pendingLists = $allPendingEndorsements->take(5);
        $pendingUserApprovals = $allPendingUserApprovals->take(5);

        // Get notifications for current HEA user (single query with count)
        $user = Auth::user();
        $allUnreadNotifications = $user->unreadNotifications()
            ->orderBy('created_at', 'desc')
            ->get();
        $unreadNotifications = $allUnreadNotifications->take(10);
        $unreadCount = $allUnreadNotifications->count();

        return view('hea.dashboard', compact('stats', 'pendingLists', 'pendingUserApprovals', 'unreadNotifications', 'unreadCount'));
    }

    /**
     * Mark a notification as read.
     */
    public function markNotificationRead(Request $request, string $notificationId)
    {
        $user = Auth::user();
        $notification = $user->notifications()->find($notificationId);

        if ($notification) {
            $notification->markAsRead();
        }

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return back();
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllNotificationsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return back()->with('success', 'All notifications marked as read.');
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
            $query->where('current_role', $roleFilter);
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
            if ($user->current_role === 'external_lecturer' && $user->externalLecturer) {
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

    /**
     * Show the semester reminder form.
     */
    public function showSemesterReminderForm()
    {
        // Get all Resource Persons with their assigned programs
        $resourcePersons = ResourcePerson::with('user')
            ->whereHas('user', function ($query) {
                $query->where('approval_status', 'approved');
            })
            ->get();

        // Get the current semester suggestion
        $currentMonth = now()->month;
        $currentYear = now()->year;

        // Determine semester based on month
        // March-August: Semester 2, September-February: Semester 1
        if ($currentMonth >= 3 && $currentMonth <= 8) {
            $semesterSuggestion = "Semester 2 {$currentYear}/{$currentYear}";
        } else {
            // For Sept-Dec, use current year / next year
            // For Jan-Feb, use previous year / current year
            if ($currentMonth >= 9) {
                $semesterSuggestion = "Semester 1 {$currentYear}/" . ($currentYear + 1);
            } else {
                $semesterSuggestion = "Semester 1 " . ($currentYear - 1) . "/{$currentYear}";
            }
        }

        return view('hea.semester_reminder', compact('resourcePersons', 'semesterSuggestion'));
    }

    /**
     * Send semester reminders to Resource Persons.
     */
    public function sendSemesterReminders(Request $request)
    {
        $request->validate([
            'semester' => 'required|string|max:50',
            'resource_persons' => 'required|array|min:1',
            'resource_persons.*' => 'exists:resource_persons,id',
        ], [
            'resource_persons.required' => 'Please select at least one Resource Person to send the reminder.',
            'resource_persons.min' => 'Please select at least one Resource Person to send the reminder.',
        ]);

        $semester = $request->input('semester');
        $selectedIds = $request->input('resource_persons');
        $sender = Auth::user();

        $sentCount = 0;
        $failedCount = 0;
        $errors = [];

        foreach ($selectedIds as $rpId) {
            try {
                $resourcePerson = ResourcePerson::with('user')->find($rpId);

                if (!$resourcePerson || !$resourcePerson->user) {
                    $failedCount++;
                    $errors[] = "Resource Person ID {$rpId} not found or has no associated user.";
                    continue;
                }

                // Get assigned program
                $assignedProgram = $resourcePerson->assigned_program;
                if (!$assignedProgram && is_array($resourcePerson->assigned_programs)) {
                    $assignedProgram = $resourcePerson->assigned_programs[0] ?? null;
                }

                if (!$assignedProgram) {
                    $failedCount++;
                    $errors[] = "{$resourcePerson->user->name} has no assigned program.";
                    continue;
                }

                // Send the notification
                $resourcePerson->user->notify(new SemesterReminderNotification(
                    $semester,
                    $assignedProgram,
                    $sender
                ));

                $sentCount++;

                // Log the action
                AuditTrail::create([
                    'user_id' => $sender->id,
                    'action' => 'Sent Semester Reminder',
                    'target_entity' => 'ResourcePerson',
                    'target_id' => $resourcePerson->id,
                    'details' => json_encode([
                        'semester' => $semester,
                        'program_code' => $assignedProgram,
                        'recipient_name' => $resourcePerson->user->name,
                        'recipient_email' => $resourcePerson->user->email,
                    ]),
                ]);

            } catch (\Exception $e) {
                $failedCount++;
                $recipientName = isset($resourcePerson) && $resourcePerson->user ? $resourcePerson->user->name : 'Unknown';
                $errors[] = "Failed to send to {$recipientName}: " . $e->getMessage();
                Log::error("Failed to send semester reminder: " . $e->getMessage(), [
                    'resource_person_id' => $rpId,
                    'semester' => $semester,
                ]);
            }
        }

        // Build response message
        if ($sentCount > 0 && $failedCount === 0) {
            return redirect()->route('hea.semester_reminder')
                ->with('success', "Successfully sent semester reminder to {$sentCount} Resource Person(s) for {$semester}.");
        } elseif ($sentCount > 0 && $failedCount > 0) {
            return redirect()->route('hea.semester_reminder')
                ->with('warning', "Sent reminder to {$sentCount} Resource Person(s), but {$failedCount} failed. Errors: " . implode('; ', $errors));
        } else {
            return redirect()->route('hea.semester_reminder')
                ->with('error', "Failed to send reminders. Errors: " . implode('; ', $errors));
        }
    }
}
