<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\LoginAttempt;
use App\Models\SecurityEvent;
use App\Models\AccessLog;
use App\Models\AuditTrail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SecurityController extends Controller
{
    /**
     * Display login attempts log
     */
    public function loginAttempts(Request $request)
    {
        $query = LoginAttempt::with('user')
            ->orderBy('attempted_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by email
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        // Filter by IP
        if ($request->filled('ip')) {
            $query->where('ip_address', 'like', '%' . $request->ip . '%');
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('attempted_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('attempted_at', '<=', $request->date_to . ' 23:59:59');
        }

        $loginAttempts = $query->paginate(20)->withQueryString();

        // Statistics
        $stats = [
            'total_attempts' => LoginAttempt::count(),
            'failed_24h' => LoginAttempt::failed()->where('attempted_at', '>=', now()->subDay())->count(),
            'success_24h' => LoginAttempt::successful()->where('attempted_at', '>=', now()->subDay())->count(),
            'unique_ips_24h' => LoginAttempt::where('attempted_at', '>=', now()->subDay())
                ->distinct('ip_address')->count('ip_address'),
        ];

        return view('admin.security.login-attempts', compact('loginAttempts', 'stats'));
    }

    /**
     * Display active sessions
     */
    public function activeSessions(Request $request)
    {
        $sessions = DB::table('sessions')
            ->leftJoin('users', 'sessions.user_id', '=', 'users.id')
            ->select(
                'sessions.*',
                'users.name as user_name',
                'users.email as user_email',
                'users.current_role'
            )
            ->whereNotNull('sessions.user_id')
            ->orderBy('sessions.last_activity', 'desc')
            ->paginate(20);

        // Process sessions for display
        $sessions->getCollection()->transform(function ($session) {
            $session->last_activity_at = \Carbon\Carbon::createFromTimestamp($session->last_activity);
            $session->is_current = $session->id === session()->getId();
            return $session;
        });

        $stats = [
            'total_active' => DB::table('sessions')->whereNotNull('user_id')->count(),
            'active_30min' => DB::table('sessions')
                ->whereNotNull('user_id')
                ->where('last_activity', '>=', now()->subMinutes(30)->timestamp)
                ->count(),
        ];

        return view('admin.security.active-sessions', compact('sessions', 'stats'));
    }

    /**
     * Terminate a specific session
     */
    public function terminateSession(Request $request, string $sessionId)
    {
        // Don't allow terminating current session
        if ($sessionId === session()->getId()) {
            return back()->with('error', 'Cannot terminate your own session.');
        }

        DB::table('sessions')->where('id', $sessionId)->delete();

        // Log the action
        AuditTrail::create([
            'id' => Str::uuid(),
            'user_id' => auth()->id(),
            'action' => 'session_terminated',
            'details' => json_encode([
                'terminated_session_id' => $sessionId,
                'terminated_by' => auth()->user()->email,
            ]),
        ]);

        return back()->with('success', 'Session terminated successfully.');
    }

    /**
     * Terminate all sessions for a user
     */
    public function terminateUserSessions(Request $request, User $user)
    {
        $currentSessionId = session()->getId();

        DB::table('sessions')
            ->where('user_id', $user->id)
            ->where('id', '!=', $currentSessionId)
            ->delete();

        // Log the action
        AuditTrail::create([
            'id' => Str::uuid(),
            'user_id' => auth()->id(),
            'action' => 'user_sessions_terminated',
            'details' => json_encode([
                'target_user_id' => $user->id,
                'target_user_email' => $user->email,
                'terminated_by' => auth()->user()->email,
            ]),
        ]);

        return back()->with('success', "All sessions for {$user->name} have been terminated.");
    }

    /**
     * Display locked accounts
     */
    public function lockedAccounts()
    {
        $lockedUsers = User::whereNotNull('locked_at')
            ->orderBy('locked_at', 'desc')
            ->paginate(20);

        return view('admin.security.locked-accounts', compact('lockedUsers'));
    }

    /**
     * Unlock a user account
     */
    public function unlockAccount(User $user)
    {
        $user->unlockAccount();

        // Log the action
        AuditTrail::create([
            'id' => Str::uuid(),
            'user_id' => auth()->id(),
            'action' => 'account_unlocked',
            'details' => json_encode([
                'unlocked_user_id' => $user->id,
                'unlocked_user_email' => $user->email,
                'unlocked_by' => auth()->user()->email,
            ]),
        ]);

        return back()->with('success', "Account for {$user->name} has been unlocked.");
    }

    /**
     * Lock a user account manually
     */
    public function lockAccount(Request $request, User $user)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        // Don't allow locking own account
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Cannot lock your own account.');
        }

        $user->lockAccount($validated['reason']);

        // Log the action
        AuditTrail::create([
            'id' => Str::uuid(),
            'user_id' => auth()->id(),
            'action' => 'account_locked_manually',
            'details' => json_encode([
                'locked_user_id' => $user->id,
                'locked_user_email' => $user->email,
                'lock_reason' => $validated['reason'],
                'locked_by' => auth()->user()->email,
            ]),
        ]);

        return back()->with('success', "Account for {$user->name} has been locked.");
    }

    /**
     * Display access denied logs
     */
    public function accessLogs(Request $request)
    {
        $query = AccessLog::with('user')
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $accessLogs = $query->paginate(20)->withQueryString();

        $stats = [
            'total_denied' => AccessLog::denied()->count(),
            'denied_24h' => AccessLog::denied()->where('created_at', '>=', now()->subDay())->count(),
        ];

        return view('admin.security.access-logs', compact('accessLogs', 'stats'));
    }

    /**
     * Display security events
     */
    public function securityEvents(Request $request)
    {
        $query = SecurityEvent::with('user')
            ->orderBy('created_at', 'desc');

        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }

        $securityEvents = $query->paginate(20)->withQueryString();

        $stats = [
            'total_events' => SecurityEvent::count(),
            'events_24h' => SecurityEvent::where('created_at', '>=', now()->subDay())->count(),
            'blocked_24h' => SecurityEvent::where('created_at', '>=', now()->subDay())
                ->where('blocked', true)->count(),
        ];

        $eventTypes = [
            'sql_injection' => 'SQL Injection',
            'xss_attempt' => 'XSS Attempt',
            'csrf_failure' => 'CSRF Failure',
            'suspicious_input' => 'Suspicious Input',
            'brute_force' => 'Brute Force',
            'other' => 'Other',
        ];

        return view('admin.security.security-events', compact('securityEvents', 'stats', 'eventTypes'));
    }

    /**
     * Display all users with lock/unlock functionality
     */
    public function allUsers(Request $request)
    {
        $query = User::query()->orderBy('created_at', 'desc');

        // Filter by search (name or email)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('current_role', $request->role);
        }

        // Filter by status (locked/active)
        if ($request->filled('status')) {
            if ($request->status === 'locked') {
                $query->whereNotNull('locked_at');
            } elseif ($request->status === 'active') {
                $query->whereNull('locked_at');
            }
        }

        $users = $query->paginate(20)->withQueryString();

        // Statistics
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::whereNull('locked_at')->count(),
            'locked_users' => User::whereNotNull('locked_at')->count(),
        ];

        // Available roles for filtering
        $roles = [
            'student' => 'Student',
            'academic_advisor' => 'Academic Advisor',
            'coordinator' => 'Coordinator',
            'program_coordinator' => 'Program Coordinator',
            'resource_person' => 'Resource Person',
            'hea_personnel' => 'HEA Personnel',
            'admin' => 'Administrator',
        ];

        return view('admin.security.all-users', compact('users', 'stats', 'roles'));
    }
}
