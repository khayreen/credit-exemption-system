<?php

namespace App\Http\Controllers\Hea;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ExemptionApplication;
use App\Models\AuditTrail;
use App\Models\SystemSetting;
use App\Models\EquivalencyList;
use App\Models\ResourcePerson;
use App\Models\ProgramGroupConfig;
use App\Notifications\SemesterReminderNotification;
use App\Notifications\ApplicationReviewReminderNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    /**
     * Show the main HEA dashboard.
     */
    public function index()
    {
        // Fetch pending user approvals once - use for both count and display
        $allPendingUserApprovals = User::where('approval_status', 'pending')
            ->whereIn('requested_role', ['academic_advisor', 'program_coordinator', 'resource_person'])
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
        $programFilter = $request->get('program_filter', 'all');
        $groupFilter = $request->get('group_filter', 'all');
        $search = $request->get('search', '');

        $query = ExemptionApplication::with(['student.user', 'student.faculty']);

        // Apply status filter
        if ($statusFilter === 'pending') {
            $query->where('status', 'Submitted');
        } elseif ($statusFilter === 'reviewed') {
            $query->where('status', 'Reviewed by Academic Advisor');
        } elseif ($statusFilter === 'completed') {
            $query->where('status', 'Completed');
        }

        // Apply program filter
        if ($programFilter !== 'all') {
            $query->where('current_program_code', $programFilter);
        }

        // Apply group filter
        if ($groupFilter !== 'all') {
            $query->where('student_group', $groupFilter);
        }

        // Apply search (student name or matric number)
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('student_name', 'like', "%{$search}%")
                  ->orWhere('matric_no', 'like', "%{$search}%");
            });
        }

        // Custom sorting: Pending applications first (oldest first), then reviewed (oldest first)
        $query->orderByRaw("CASE WHEN LOWER(status) = 'submitted' THEN 0 ELSE 1 END")
              ->orderBy('created_at', 'asc');

        // Paginate results
        $applications = $query->paginate(20)->withQueryString();

        // For each reviewed application, fetch the academic advisor who reviewed it from audit trail
        foreach ($applications as $app) {
            if (strtolower($app->status) === 'reviewed by academic advisor') {
                $auditLog = AuditTrail::where('target_entity', 'ExemptionApplication')
                    ->where('target_id', $app->id)
                    ->where('action', 'like', '%Forward%')
                    ->with('user.academicAdvisor')
                    ->latest()
                    ->first();

                $app->reviewed_by_advisor = $auditLog && $auditLog->user ? $auditLog->user : null;
            }
        }

        // Get filter options
        $supportedPrograms = \App\Models\ProgramGroupConfig::getSupportedPrograms();

        // Get current semester groups from config (9 groups for SESI I 2025/2026)
        $programGroups = config('programs.program_groups', []);
        $studentGroups = collect($programGroups)
            ->flatMap(fn($program) => $program['groups'] ?? [])
            ->sort()
            ->values();

        // Stats
        $stats = [
            'total' => ExemptionApplication::count(),
            'pending' => ExemptionApplication::where('status', 'Submitted')->count(),
            'reviewed' => ExemptionApplication::where('status', 'Reviewed by Academic Advisor')->count(),
            'completed' => ExemptionApplication::where('status', 'Completed')->count(),
        ];

        return view('hea.applications', compact(
            'applications',
            'statusFilter',
            'programFilter',
            'groupFilter',
            'search',
            'supportedPrograms',
            'studentGroups',
            'stats'
        ));
    }

    /**
     * Send a reminder to the Academic Advisor to review an application.
     */
    public function sendApplicationReminder(ExemptionApplication $application)
    {
        // Check if application is still pending
        if (strtolower($application->status) !== 'submitted') {
            return back()->with('error', 'This application has already been reviewed.');
        }

        // Check if application has a student group
        if (empty($application->student_group)) {
            return back()->with('error', 'This application has no student group assigned. Cannot determine the responsible Academic Advisor.');
        }

        // Find the assigned AA for this group
        $academicYear = ProgramGroupConfig::getCurrentAcademicYear();
        $intake = ProgramGroupConfig::getCurrentIntake();

        $groupConfig = ProgramGroupConfig::where('group_code', $application->student_group)
            ->forSession($academicYear, $intake)
            ->active()
            ->first();

        if (!$groupConfig) {
            return back()->with('error', 'No active group configuration found for "' . $application->student_group . '" in the current session (' . ucfirst($intake) . ' ' . $academicYear . '). Please configure program groups first.');
        }

        if (!$groupConfig->assigned_user_id) {
            return back()->with('error', 'No Academic Advisor is assigned to group "' . $application->student_group . '". Please assign an AA in Program Groups configuration.');
        }

        $academicAdvisor = User::find($groupConfig->assigned_user_id);

        if (!$academicAdvisor) {
            return back()->with('error', 'The assigned Academic Advisor could not be found.');
        }

        // Send the notification
        try {
            $sender = Auth::user();
            $academicAdvisor->notify(new ApplicationReviewReminderNotification($application, $sender));

            // Update reminder tracking on the application
            $application->reminder_sent_at = now();
            $application->reminder_sent_by = $sender->id;
            $application->save();

            // Create audit trail
            AuditTrail::create([
                'id' => Str::uuid(),
                'user_id' => $sender->id,
                'action' => 'Sent Application Review Reminder',
                'target_entity' => 'ExemptionApplication',
                'target_id' => $application->id,
                'details' => json_encode([
                    'application_id' => $application->id,
                    'student_name' => $application->student_name,
                    'matric_no' => $application->matric_no,
                    'student_group' => $application->student_group,
                    'sent_to_aa_id' => $academicAdvisor->id,
                    'sent_to_aa_name' => $academicAdvisor->name,
                    'sent_to_aa_email' => $academicAdvisor->email,
                ]),
            ]);

            return back()->with('success', 'Reminder sent successfully to ' . $academicAdvisor->name . ' (' . $academicAdvisor->email . ').');

        } catch (\Exception $e) {
            Log::error('Failed to send application review reminder: ' . $e->getMessage(), [
                'application_id' => $application->id,
                'aa_id' => $academicAdvisor->id,
            ]);

            return back()->with('error', 'Failed to send reminder: ' . $e->getMessage());
        }
    }

    /**
     * Get the assigned Academic Advisor for an application (AJAX endpoint).
     */
    public function getAssignedAdvisor(ExemptionApplication $application)
    {
        $isPending = strtolower($application->status) === 'submitted';

        // Get reminder info
        $reminderInfo = null;
        if ($application->reminder_sent_at) {
            $sentBy = $application->reminder_sent_by ? User::find($application->reminder_sent_by) : null;
            $reminderInfo = [
                'sent_at' => $application->reminder_sent_at->format('d M Y, h:i A'),
                'sent_by' => $sentBy ? $sentBy->name : 'Unknown',
            ];
        }

        // Get reviewed by info (for applications that have been reviewed)
        $reviewedBy = null;
        if ($application->reviewed_by) {
            $reviewer = User::find($application->reviewed_by);
            if ($reviewer) {
                $reviewedBy = [
                    'name' => $reviewer->name,
                    'email' => $reviewer->email,
                    'reviewed_at' => $application->reviewed_at ? $application->reviewed_at->format('d M Y, h:i A') : null,
                ];
            }
        }

        // For reviewed applications, we don't need group config - just show the reviewer info
        if (!$isPending) {
            // Try to get reviewer from audit trail if reviewed_by is not set
            if (!$reviewedBy) {
                $auditLog = \App\Models\AuditTrail::where('target_entity', 'ExemptionApplication')
                    ->where('target_id', $application->id)
                    ->where(function($q) {
                        $q->where('action', 'like', '%Forward%')
                          ->orWhere('action', 'like', '%Approved%')
                          ->orWhere('action', 'like', '%Rejected%');
                    })
                    ->with('user')
                    ->latest()
                    ->first();

                if ($auditLog && $auditLog->user) {
                    $reviewedBy = [
                        'name' => $auditLog->user->name,
                        'email' => $auditLog->user->email,
                        'reviewed_at' => $auditLog->created_at->format('d M Y, h:i A'),
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'is_pending' => false,
                'advisor' => null,
                'reminder' => $reminderInfo,
                'reviewed_by' => $reviewedBy,
            ]);
        }

        // For pending applications, we need the group config to find the assigned AA
        if (empty($application->student_group)) {
            return response()->json([
                'success' => false,
                'is_pending' => true,
                'message' => 'No student group assigned',
            ]);
        }

        $academicYear = ProgramGroupConfig::getCurrentAcademicYear();
        $intake = ProgramGroupConfig::getCurrentIntake();

        $groupConfig = ProgramGroupConfig::where('group_code', $application->student_group)
            ->forSession($academicYear, $intake)
            ->active()
            ->with('assignedUser')
            ->first();

        if (!$groupConfig) {
            return response()->json([
                'success' => false,
                'is_pending' => true,
                'message' => 'No group configuration found for current session',
            ]);
        }

        if (!$groupConfig->assignedUser) {
            return response()->json([
                'success' => false,
                'is_pending' => true,
                'message' => 'No Academic Advisor assigned to this group',
            ]);
        }

        return response()->json([
            'success' => true,
            'is_pending' => true,
            'advisor' => [
                'id' => $groupConfig->assignedUser->id,
                'name' => $groupConfig->assignedUser->name,
                'email' => $groupConfig->assignedUser->email,
            ],
            'reminder' => $reminderInfo,
            'reviewed_by' => $reviewedBy,
        ]);
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
