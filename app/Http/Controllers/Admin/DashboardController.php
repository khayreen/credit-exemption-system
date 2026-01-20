<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\LoginAttempt;
use App\Models\SecurityEvent;
use App\Models\AccessLog;
use App\Models\Announcement;
use App\Models\FaqItem;
use App\Models\HelpArticle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard
     */
    public function index()
    {
        // Pending HEA approvals count
        $pendingHeaCount = User::where('approval_status', 'pending_admin')
            ->where('requested_role', 'hea_personnel')
            ->count();

        // Security statistics
        $failedLoginsLast24h = LoginAttempt::where('status', 'failed')
            ->where('attempted_at', '>=', now()->subDay())
            ->count();

        $failedLoginsLast7d = LoginAttempt::where('status', 'failed')
            ->where('attempted_at', '>=', now()->subDays(7))
            ->count();

        $lockedAccountsCount = User::whereNotNull('locked_at')->count();

        $securityEventsLast24h = SecurityEvent::where('created_at', '>=', now()->subDay())->count();

        $accessDeniedLast24h = AccessLog::where('status', 'denied')
            ->where('created_at', '>=', now()->subDay())
            ->count();

        // Active sessions count
        $activeSessionsCount = DB::table('sessions')
            ->whereNotNull('user_id')
            ->where('last_activity', '>=', now()->subMinutes(30)->timestamp)
            ->count();

        // Content statistics
        $announcementsCount = Announcement::where('is_active', true)->count();
        $faqCount = FaqItem::where('is_active', true)->count();
        $helpArticlesCount = HelpArticle::where('is_active', true)->count();

        // Recent failed logins
        $recentFailedLogins = LoginAttempt::where('status', 'failed')
            ->with('user')
            ->orderBy('attempted_at', 'desc')
            ->limit(5)
            ->get();

        // Recent security events
        $recentSecurityEvents = SecurityEvent::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // User statistics
        $totalUsers = User::count();
        $usersByRole = User::select('current_role', DB::raw('count(*) as count'))
            ->whereNotNull('current_role')
            ->groupBy('current_role')
            ->pluck('count', 'current_role')
            ->toArray();

        return view('admin.dashboard', compact(
            'pendingHeaCount',
            'failedLoginsLast24h',
            'failedLoginsLast7d',
            'lockedAccountsCount',
            'securityEventsLast24h',
            'accessDeniedLast24h',
            'activeSessionsCount',
            'announcementsCount',
            'faqCount',
            'helpArticlesCount',
            'recentFailedLogins',
            'recentSecurityEvents',
            'totalUsers',
            'usersByRole'
        ));
    }
}
