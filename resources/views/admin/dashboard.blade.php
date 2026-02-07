@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
    :root {
        --uitm-blue: #1e3a8a;
        --uitm-blue-light: #3b82f6;
        --uitm-amber: #f59e0b;
        --uitm-amber-light: #fbbf24;
        --industrial-dark: #0f172a;
        --industrial-gray: #334155;
        --industrial-light: #f1f5f9;
        --success-green: #059669;
        --danger-red: #dc2626;
        --warning-orange: #ea580c;
        --info-cyan: #0891b2;
    }

    body { font-family: 'IBM Plex Sans', -apple-system, BlinkMacSystemFont, sans-serif; }
    .font-mono { font-family: 'IBM Plex Mono', monospace; }

    /* Admin Header */
    .admin-header {
        background: linear-gradient(135deg, var(--industrial-dark) 0%, #1a1a2e 50%, var(--uitm-blue) 100%);
        border-radius: 20px;
        padding: 2.5rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }

    .admin-header::before {
        content: '';
        position: absolute;
        top: -100px;
        right: -100px;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(245, 158, 11, 0.15) 0%, transparent 70%);
        border-radius: 50%;
        animation: pulse-glow 4s ease-in-out infinite;
    }

    .admin-header::after {
        content: '';
        position: absolute;
        bottom: -50px;
        left: 30%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(59, 130, 246, 0.1) 0%, transparent 70%);
        border-radius: 50%;
    }

    @keyframes pulse-glow {
        0%, 100% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.1); opacity: 0.8; }
    }

    .admin-header h1 {
        color: #fff;
        font-weight: 700;
        font-size: 2rem;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 1;
    }

    .admin-header p {
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 0;
        position: relative;
        z-index: 1;
    }

    .admin-badge {
        background: linear-gradient(135deg, var(--industrial-gray) 0%, var(--industrial-dark) 100%);
        color: #fff;
        padding: 0.625rem 1.25rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .admin-badge i {
        color: var(--uitm-amber);
    }

    .date-display {
        color: rgba(255, 255, 255, 0.5);
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }

    /* Security Alert Banner */
    .security-alert {
        background: linear-gradient(135deg, rgba(220, 38, 38, 0.1) 0%, rgba(234, 88, 12, 0.1) 100%);
        border: 1px solid rgba(220, 38, 38, 0.2);
        border-left: 4px solid var(--danger-red);
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .security-alert-icon {
        width: 48px;
        height: 48px;
        background: rgba(220, 38, 38, 0.1);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: var(--danger-red);
        animation: alert-pulse 2s ease-in-out infinite;
    }

    @keyframes alert-pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    .security-alert-content {
        flex: 1;
    }

    .security-alert-content strong {
        color: var(--danger-red);
        font-weight: 700;
    }

    .security-alert-content span {
        color: #64748b;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    @media (max-width: 1200px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 576px) {
        .stats-grid { grid-template-columns: 1fr; }
    }

    .stat-card {
        background: #fff;
        border-radius: 16px;
        padding: 1.5rem;
        position: relative;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        text-decoration: none;
        display: block;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        text-decoration: none;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
    }

    .stat-card.warning::before { background: linear-gradient(90deg, var(--uitm-amber), var(--uitm-amber-light)); }
    .stat-card.danger::before { background: linear-gradient(90deg, var(--danger-red), #ef4444); }
    .stat-card.info::before { background: linear-gradient(90deg, var(--info-cyan), #22d3ee); }
    .stat-card.dark::before { background: linear-gradient(90deg, var(--industrial-dark), var(--industrial-gray)); }

    .stat-card-inner {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .stat-card.warning .stat-icon { background: rgba(245, 158, 11, 0.15); color: var(--uitm-amber); }
    .stat-card.danger .stat-icon { background: rgba(220, 38, 38, 0.15); color: var(--danger-red); }
    .stat-card.info .stat-icon { background: rgba(8, 145, 178, 0.15); color: var(--info-cyan); }
    .stat-card.dark .stat-icon { background: rgba(15, 23, 42, 0.1); color: var(--industrial-dark); }

    .stat-content {
        flex: 1;
    }

    .stat-value {
        font-size: 2.25rem;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 0.25rem;
    }

    .stat-card.warning .stat-value { color: var(--uitm-amber); }
    .stat-card.danger .stat-value { color: var(--danger-red); }
    .stat-card.info .stat-value { color: var(--info-cyan); }
    .stat-card.dark .stat-value { color: var(--industrial-dark); }

    .stat-label {
        color: var(--industrial-dark);
        font-weight: 600;
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
    }

    .stat-sublabel {
        color: #94a3b8;
        font-size: 0.75rem;
    }

    /* Panel Cards */
    .panel-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    @media (max-width: 992px) {
        .panel-grid { grid-template-columns: 1fr; }
    }

    .panel-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .panel-header {
        background: linear-gradient(135deg, var(--industrial-light) 0%, #fff 100%);
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .panel-header i {
        font-size: 1.25rem;
    }

    .panel-header.primary i { color: var(--uitm-blue); }
    .panel-header.success i { color: var(--success-green); }
    .panel-header.warning i { color: var(--uitm-amber); }

    .panel-header h5 {
        margin: 0;
        font-weight: 600;
        color: var(--industrial-dark);
        font-size: 1rem;
    }

    .panel-body {
        padding: 1.5rem;
    }

    /* Content Stats */
    .content-stat-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .content-stat-row:last-child {
        border-bottom: none;
    }

    .content-stat-row span:first-child {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #64748b;
    }

    .content-stat-row span:first-child i {
        width: 20px;
        text-align: center;
    }

    .content-stat-badge {
        padding: 0.375rem 0.75rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.75rem;
    }

    .content-stat-badge.info { background: rgba(8, 145, 178, 0.15); color: var(--info-cyan); }
    .content-stat-badge.success { background: rgba(5, 150, 105, 0.15); color: var(--success-green); }
    .content-stat-badge.warning { background: rgba(245, 158, 11, 0.15); color: var(--uitm-amber); }

    /* User Role Distribution */
    .user-total {
        background: linear-gradient(135deg, rgba(30, 58, 138, 0.05) 0%, rgba(59, 130, 246, 0.02) 100%);
        padding: 1rem;
        border-radius: 10px;
        margin-bottom: 1rem;
        text-align: center;
    }

    .user-total-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--uitm-blue);
    }

    .user-total-label {
        font-size: 0.75rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .role-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
    }

    .role-row span:first-child {
        color: #64748b;
        text-transform: capitalize;
    }

    .role-count {
        background: var(--industrial-light);
        color: var(--industrial-gray);
        padding: 0.25rem 0.625rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.75rem;
    }

    /* Quick Actions */
    .quick-action-btn {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        width: 100%;
        padding: 0.875rem 1rem;
        border-radius: 10px;
        border: none;
        font-weight: 600;
        font-size: 0.875rem;
        text-decoration: none;
        margin-bottom: 0.75rem;
        transition: all 0.2s ease;
    }

    .quick-action-btn:last-child {
        margin-bottom: 0;
    }

    .quick-action-btn.primary {
        background: linear-gradient(135deg, var(--uitm-amber) 0%, var(--warning-orange) 100%);
        color: #fff;
    }

    .quick-action-btn.primary:hover {
        transform: translateX(4px);
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
        color: #fff;
    }

    .quick-action-btn.outline-danger {
        background: rgba(220, 38, 38, 0.05);
        border: 1px solid rgba(220, 38, 38, 0.2);
        color: var(--danger-red);
    }

    .quick-action-btn.outline-danger:hover {
        background: var(--danger-red);
        color: #fff;
        transform: translateX(4px);
    }

    .quick-action-btn.outline-dark {
        background: rgba(15, 23, 42, 0.05);
        border: 1px solid rgba(15, 23, 42, 0.15);
        color: var(--industrial-dark);
    }

    .quick-action-btn.outline-dark:hover {
        background: var(--industrial-dark);
        color: #fff;
        transform: translateX(4px);
    }

    .quick-action-btn.outline-success {
        background: rgba(5, 150, 105, 0.05);
        border: 1px solid rgba(5, 150, 105, 0.2);
        color: var(--success-green);
    }

    .quick-action-btn.outline-success:hover {
        background: var(--success-green);
        color: #fff;
        transform: translateX(4px);
    }

    .quick-action-btn .badge {
        margin-left: auto;
        background: rgba(0, 0, 0, 0.2);
        padding: 0.25rem 0.5rem;
        border-radius: 50px;
        font-size: 0.7rem;
    }

    .btn-manage {
        display: block;
        width: 100%;
        padding: 0.625rem;
        border-radius: 8px;
        text-align: center;
        font-weight: 500;
        font-size: 0.8125rem;
        text-decoration: none;
        margin-top: 0.5rem;
        transition: all 0.2s ease;
    }

    .btn-manage.primary {
        background: rgba(30, 58, 138, 0.1);
        color: var(--uitm-blue);
        border: 1px solid rgba(30, 58, 138, 0.15);
    }

    .btn-manage.primary:hover {
        background: var(--uitm-blue);
        color: #fff;
    }

    .btn-manage.info {
        background: rgba(8, 145, 178, 0.1);
        color: var(--info-cyan);
        border: 1px solid rgba(8, 145, 178, 0.15);
    }

    .btn-manage.info:hover {
        background: var(--info-cyan);
        color: #fff;
    }

    /* Activity Tables */
    .activity-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }

    @media (max-width: 992px) {
        .activity-grid { grid-template-columns: 1fr; }
    }

    .activity-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .activity-header {
        background: linear-gradient(135deg, var(--industrial-light) 0%, #fff 100%);
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .activity-header-left {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .activity-header-left i {
        font-size: 1.25rem;
    }

    .activity-header-left.danger i { color: var(--danger-red); }
    .activity-header-left.warning i { color: var(--uitm-amber); }

    .activity-header h5 {
        margin: 0;
        font-weight: 600;
        font-size: 0.9375rem;
        color: var(--industrial-dark);
    }

    .btn-view-all {
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-view-all.danger {
        background: rgba(220, 38, 38, 0.1);
        color: var(--danger-red);
    }

    .btn-view-all.danger:hover {
        background: var(--danger-red);
        color: #fff;
    }

    .btn-view-all.warning {
        background: rgba(245, 158, 11, 0.1);
        color: var(--uitm-amber);
    }

    .btn-view-all.warning:hover {
        background: var(--uitm-amber);
        color: #fff;
    }

    /* Empty State */
    .empty-activity {
        padding: 3rem 1.5rem;
        text-align: center;
    }

    .empty-activity-icon {
        width: 64px;
        height: 64px;
        background: rgba(5, 150, 105, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 1.75rem;
        color: var(--success-green);
    }

    .empty-activity p {
        color: #64748b;
        margin: 0;
    }

    /* Data Table */
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table thead th {
        background: var(--industrial-light);
        padding: 0.875rem 1rem;
        text-align: left;
        font-weight: 600;
        font-size: 0.6875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
        border-bottom: 1px solid #e2e8f0;
    }

    .data-table tbody td {
        padding: 0.875rem 1rem;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.875rem;
    }

    .data-table tbody tr:last-child td {
        border-bottom: none;
    }

    .data-table tbody tr:hover {
        background: rgba(30, 58, 138, 0.02);
    }

    .ip-code {
        font-family: 'IBM Plex Mono', monospace;
        background: var(--industrial-light);
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        color: var(--industrial-gray);
    }

    .status-badge {
        padding: 0.25rem 0.625rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.6875rem;
    }

    .status-badge.danger { background: rgba(220, 38, 38, 0.15); color: var(--danger-red); }
    .status-badge.success { background: rgba(5, 150, 105, 0.15); color: var(--success-green); }
    .status-badge.warning { background: rgba(245, 158, 11, 0.15); color: var(--uitm-amber); }

    .time-text {
        color: #94a3b8;
        font-size: 0.75rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Admin Header -->
    <div class="admin-header">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <h1><i class="fas fa-shield-alt me-3"></i>System Administration</h1>
                <p>Welcome back, {{ auth()->user()->name }}</p>
            </div>
            <div class="text-end">
                <div class="admin-badge">
                    <i class="fas fa-user-shield"></i>
                    Administrator
                </div>
                <div class="date-display">
                    <i class="far fa-calendar-alt me-1"></i>{{ now()->format('l, F d, Y') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Security Alert Banner -->
    @if($lockedAccountsCount > 0 || $securityEventsLast24h > 0)
    <div class="security-alert">
        <div class="security-alert-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="security-alert-content">
            <strong>Security Alert:</strong>
            <span>
                @if($lockedAccountsCount > 0)
                    {{ $lockedAccountsCount }} locked account(s) require attention.
                @endif
                @if($securityEventsLast24h > 0)
                    {{ $securityEventsLast24h }} security event(s) detected in the last 24 hours.
                @endif
            </span>
        </div>
        <a href="{{ route('admin.security.security-events') }}" class="btn-view-all danger">
            <i class="fas fa-eye me-1"></i>View Events
        </a>
    </div>
    @endif

    <!-- Quick Stats -->
    <div class="stats-grid">
        <!-- Pending HEA Approvals -->
        <a href="{{ route('admin.hea.approvals') }}" class="stat-card warning">
            <div class="stat-card-inner">
                <div class="stat-icon">
                    <i class="fas fa-user-clock"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $pendingHeaCount }}</div>
                    <div class="stat-label">Pending HEA Approvals</div>
                    <div class="stat-sublabel">Awaiting review</div>
                </div>
            </div>
        </a>

        <!-- Failed Logins -->
        <a href="{{ route('admin.security.login-attempts') }}" class="stat-card danger">
            <div class="stat-card-inner">
                <div class="stat-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $failedLoginsLast24h }}</div>
                    <div class="stat-label">Failed Logins (24h)</div>
                    <div class="stat-sublabel">{{ $failedLoginsLast7d }} in last 7 days</div>
                </div>
            </div>
        </a>

        <!-- Active Sessions -->
        <a href="{{ route('admin.security.sessions') }}" class="stat-card info">
            <div class="stat-card-inner">
                <div class="stat-icon">
                    <i class="fas fa-users-cog"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $activeSessionsCount }}</div>
                    <div class="stat-label">Active Sessions</div>
                    <div class="stat-sublabel">Online in last 30 min</div>
                </div>
            </div>
        </a>

        <!-- Locked Accounts -->
        <a href="{{ route('admin.security.locked-accounts') }}" class="stat-card dark">
            <div class="stat-card-inner">
                <div class="stat-icon">
                    <i class="fas fa-lock"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $lockedAccountsCount }}</div>
                    <div class="stat-label">Locked Accounts</div>
                    <div class="stat-sublabel">Requires attention</div>
                </div>
            </div>
        </a>
    </div>

    <!-- Panel Grid -->
    <div class="panel-grid">
        <!-- Content Management -->
        <div class="panel-card">
            <div class="panel-header primary">
                <i class="fas fa-file-alt"></i>
                <h5>Content Management</h5>
            </div>
            <div class="panel-body">
                <div class="content-stat-row">
                    <span><i class="fas fa-bullhorn text-info"></i>Active Announcements</span>
                    <span class="content-stat-badge info">{{ $announcementsCount }}</span>
                </div>
                <div class="content-stat-row">
                    <span><i class="fas fa-question-circle text-success"></i>FAQ Items</span>
                    <span class="content-stat-badge success">{{ $faqCount }}</span>
                </div>
                <div class="content-stat-row">
                    <span><i class="fas fa-book text-warning"></i>Help Articles</span>
                    <span class="content-stat-badge warning">{{ $helpArticlesCount }}</span>
                </div>
                <hr class="my-3">
                <a href="{{ route('admin.content.terms.index') }}" class="btn-manage primary">
                    <i class="fas fa-file-contract me-2"></i>Manage Terms & Conditions
                </a>
                <a href="{{ route('admin.content.announcements.index') }}" class="btn-manage info">
                    <i class="fas fa-bullhorn me-2"></i>Manage Announcements
                </a>
            </div>
        </div>

        <!-- User Statistics -->
        <div class="panel-card">
            <div class="panel-header success">
                <i class="fas fa-users"></i>
                <h5>User Statistics</h5>
            </div>
            <div class="panel-body">
                <div class="user-total">
                    <div class="user-total-value">{{ $totalUsers }}</div>
                    <div class="user-total-label">Total Users</div>
                </div>
                @foreach($usersByRole as $role => $count)
                <div class="role-row">
                    <span>{{ str_replace('_', ' ', $role) }}</span>
                    <span class="role-count">{{ $count }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="panel-card">
            <div class="panel-header warning">
                <i class="fas fa-bolt"></i>
                <h5>Quick Actions</h5>
            </div>
            <div class="panel-body">
                <a href="{{ route('admin.hea.approvals') }}" class="quick-action-btn primary">
                    <i class="fas fa-user-check"></i>
                    Review HEA Registrations
                    @if($pendingHeaCount > 0)
                    <span class="badge">{{ $pendingHeaCount }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.security.login-attempts') }}" class="quick-action-btn outline-danger">
                    <i class="fas fa-history"></i>
                    View Login History
                </a>
                <a href="{{ route('admin.security.security-events') }}" class="quick-action-btn outline-dark">
                    <i class="fas fa-shield-virus"></i>
                    Security Events
                </a>
                <a href="{{ route('admin.content.faq.index') }}" class="quick-action-btn outline-success">
                    <i class="fas fa-question-circle"></i>
                    Manage FAQ
                </a>
            </div>
        </div>
    </div>

    <!-- Activity Tables -->
    <div class="activity-grid">
        <!-- Recent Failed Logins -->
        <div class="activity-card">
            <div class="activity-header">
                <div class="activity-header-left danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <h5>Recent Failed Logins</h5>
                </div>
                <a href="{{ route('admin.security.login-attempts', ['status' => 'failed']) }}" class="btn-view-all danger">
                    View All
                </a>
            </div>
            @if($recentFailedLogins->isEmpty())
            <div class="empty-activity">
                <div class="empty-activity-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <p>No recent failed login attempts</p>
            </div>
            @else
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Email</th>
                            <th>IP Address</th>
                            <th>Reason</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentFailedLogins as $attempt)
                        <tr>
                            <td>{{ Str::limit($attempt->email, 25) }}</td>
                            <td><code class="ip-code">{{ $attempt->ip_address }}</code></td>
                            <td><span class="status-badge danger">{{ $attempt->failure_reason ?? 'Unknown' }}</span></td>
                            <td><span class="time-text">{{ $attempt->attempted_at->diffForHumans() }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        <!-- Recent Security Events -->
        <div class="activity-card">
            <div class="activity-header">
                <div class="activity-header-left warning">
                    <i class="fas fa-shield-virus"></i>
                    <h5>Recent Security Events</h5>
                </div>
                <a href="{{ route('admin.security.security-events') }}" class="btn-view-all warning">
                    View All
                </a>
            </div>
            @if($recentSecurityEvents->isEmpty())
            <div class="empty-activity">
                <div class="empty-activity-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <p>No recent security events</p>
            </div>
            @else
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Event Type</th>
                            <th>IP Address</th>
                            <th>Status</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentSecurityEvents as $event)
                        <tr>
                            <td><span class="status-badge {{ $event->badge_color }}">{{ $event->event_type_label }}</span></td>
                            <td><code class="ip-code">{{ $event->ip_address }}</code></td>
                            <td>
                                @if($event->blocked)
                                <span class="status-badge success">Blocked</span>
                                @else
                                <span class="status-badge warning">Allowed</span>
                                @endif
                            </td>
                            <td><span class="time-text">{{ $event->created_at->diffForHumans() }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
