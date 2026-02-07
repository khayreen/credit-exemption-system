@extends('layouts.app')

@section('title', 'Active Sessions')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600;700&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
    :root {
        --uitm-primary: #1e3a8a;
        --uitm-primary-dark: #1e293b;
        --uitm-primary-light: #3b82f6;
        --uitm-amber: #f59e0b;
        --uitm-amber-dark: #d97706;
        --industrial-dark: #0f172a;
        --industrial-gray: #334155;
        --industrial-light: #f1f5f9;
        --success: #059669;
        --success-light: #d1fae5;
        --danger: #dc2626;
        --danger-light: #fee2e2;
        --warning: #ea580c;
        --warning-light: #ffedd5;
        --teal: #0d9488;
        --teal-light: #ccfbf1;
        --info: #0284c7;
        --info-light: #e0f2fe;
        --neutral-700: #404040;
        --neutral-600: #525252;
        --neutral-500: #737373;
        --neutral-400: #a3a3a3;
        --neutral-300: #d4d4d4;
        --neutral-200: #e5e5e5;
        --neutral-100: #f5f5f5;
        --neutral-50: #fafafa;
    }

    body {
        font-family: 'IBM Plex Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        background: var(--industrial-light);
    }

    /* Page Header */
    .page-header {
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--industrial-dark) 100%);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(245, 158, 11, 0.15) 0%, transparent 70%);
        border-radius: 50%;
    }

    .page-header::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: 10%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(59, 130, 246, 0.1) 0%, transparent 70%);
        border-radius: 50%;
    }

    .page-header-content {
        position: relative;
        z-index: 1;
    }

    .page-header .page-title {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 1.75rem;
        font-weight: 700;
        color: white;
        margin-bottom: 0.5rem;
    }

    .page-header .page-subtitle {
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.95rem;
        margin-bottom: 0;
    }

    .btn-back {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-back:hover {
        background: rgba(255, 255, 255, 0.25);
        color: white;
        transform: translateY(-2px);
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border: 2px solid var(--neutral-200);
        border-radius: 16px;
        padding: 1.5rem;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
    }

    .stat-card.info::before {
        background: linear-gradient(90deg, var(--info), var(--teal));
    }

    .stat-card.success::before {
        background: linear-gradient(90deg, var(--success), var(--teal));
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .stat-card.info .stat-icon {
        background: var(--info-light);
        color: var(--info);
    }

    .stat-card.success .stat-icon {
        background: var(--success-light);
        color: var(--success);
    }

    .stat-icon i {
        font-size: 1.25rem;
    }

    .stat-value {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--industrial-dark);
        line-height: 1;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 0.875rem;
        color: var(--neutral-600);
        font-weight: 500;
    }

    /* Alert Styles */
    .alert-industrial {
        border: none;
        border-radius: 12px;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .alert-industrial.success {
        background: var(--success-light);
        border-left: 4px solid var(--success);
    }

    .alert-industrial.danger {
        background: var(--danger-light);
        border-left: 4px solid var(--danger);
    }

    .alert-industrial-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .alert-industrial.success .alert-industrial-icon {
        background: var(--success);
        color: white;
    }

    .alert-industrial.danger .alert-industrial-icon {
        background: var(--danger);
        color: white;
    }

    .alert-industrial-content {
        flex: 1;
        font-size: 0.9rem;
    }

    .alert-industrial.success .alert-industrial-content {
        color: #047857;
    }

    .alert-industrial.danger .alert-industrial-content {
        color: #b91c1c;
    }

    .alert-close {
        background: none;
        border: none;
        font-size: 1.25rem;
        color: inherit;
        opacity: 0.5;
        cursor: pointer;
        padding: 0;
        line-height: 1;
    }

    .alert-close:hover {
        opacity: 1;
    }

    /* Industrial Card */
    .industrial-card {
        background: white;
        border: 2px solid var(--neutral-200);
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .industrial-card-header {
        background: linear-gradient(135deg, var(--neutral-50) 0%, white 100%);
        border-bottom: 2px solid var(--neutral-200);
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .industrial-card-header i {
        font-size: 1.1rem;
        color: var(--uitm-amber);
    }

    .industrial-card-title {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 1rem;
        font-weight: 600;
        color: var(--industrial-dark);
        margin: 0;
    }

    .industrial-card-body {
        padding: 0;
    }

    /* Table Styles */
    .industrial-table {
        width: 100%;
        border-collapse: collapse;
    }

    .industrial-table thead th {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--neutral-500);
        background: var(--neutral-50);
        padding: 0.875rem 1rem;
        border-bottom: 2px solid var(--neutral-200);
        text-align: left;
    }

    .industrial-table tbody td {
        padding: 1rem;
        border-bottom: 1px solid var(--neutral-100);
        font-size: 0.9rem;
        color: var(--neutral-700);
        vertical-align: middle;
    }

    .industrial-table tbody tr:hover {
        background: var(--neutral-50);
    }

    .industrial-table tbody tr:last-child td {
        border-bottom: none;
    }

    .industrial-table tbody tr.current-session {
        background: var(--info-light);
    }

    .industrial-table tbody tr.current-session:hover {
        background: #bae6fd;
    }

    /* User Info */
    .user-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .user-avatar {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .user-name {
        font-weight: 600;
        color: var(--industrial-dark);
    }

    .user-email {
        font-size: 0.8rem;
        color: var(--neutral-500);
    }

    /* Badges */
    .badge-current {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        background: var(--uitm-primary);
        color: white;
    }

    .badge-role {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: capitalize;
        background: var(--neutral-100);
        color: var(--neutral-600);
        border: 1px solid var(--neutral-200);
    }

    .ip-address {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.85rem;
        color: var(--uitm-primary);
        background: rgba(30, 58, 138, 0.08);
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
    }

    .time-info {
        display: flex;
        flex-direction: column;
        gap: 0.125rem;
    }

    .time-relative {
        font-weight: 500;
        color: var(--industrial-dark);
    }

    .time-absolute {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.75rem;
        color: var(--neutral-500);
    }

    /* Buttons */
    .btn-terminate {
        background: white;
        border: 2px solid var(--danger);
        color: var(--danger);
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.8rem;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        cursor: pointer;
    }

    .btn-terminate:hover {
        background: var(--danger);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
    }

    .empty-state-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: var(--neutral-100);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }

    .empty-state-icon i {
        font-size: 2rem;
        color: var(--neutral-400);
    }

    .empty-state-title {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--industrial-dark);
        margin-bottom: 0.5rem;
    }

    .empty-state-text {
        color: var(--neutral-500);
        font-size: 0.9rem;
    }

    /* Pagination */
    .industrial-pagination {
        background: var(--neutral-50);
        border-top: 2px solid var(--neutral-200);
        padding: 1rem 1.5rem;
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
        }

        .page-title {
            font-size: 1.5rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .stat-value {
            font-size: 2rem;
        }

        .industrial-table {
            font-size: 0.85rem;
        }

        .industrial-table thead th,
        .industrial-table tbody td {
            padding: 0.75rem 0.5rem;
        }

        .user-info {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.25rem;
        }

        .user-avatar {
            display: none;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <div>
                    <h1 class="page-title">
                        <i class="fas fa-desktop me-2" style="color: var(--uitm-amber);"></i>
                        Active Sessions
                    </h1>
                    <p class="page-subtitle">Monitor and manage user sessions across the system</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i>
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-card info">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-value">{{ $stats['total_active'] }}</div>
            <div class="stat-label">Total Active Sessions</div>
        </div>
        <div class="stat-card success">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-value">{{ $stats['active_30min'] }}</div>
            <div class="stat-label">Active in Last 30 Minutes</div>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
    <div class="alert-industrial success">
        <div class="alert-industrial-icon">
            <i class="fas fa-check"></i>
        </div>
        <div class="alert-industrial-content">{{ session('success') }}</div>
        <button type="button" class="alert-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert-industrial danger">
        <div class="alert-industrial-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="alert-industrial-content">{{ session('error') }}</div>
        <button type="button" class="alert-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    <!-- Sessions Table -->
    <div class="industrial-card">
        <div class="industrial-card-header">
            <i class="fas fa-list"></i>
            <h5 class="industrial-card-title">Session List</h5>
        </div>
        <div class="industrial-card-body">
            <div class="table-responsive">
                <table class="industrial-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Role</th>
                            <th>IP Address</th>
                            <th>Last Activity</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sessions as $session)
                        <tr class="{{ $session->is_current ? 'current-session' : '' }}">
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar">
                                        {{ strtoupper(substr($session->user_name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="user-name">
                                            {{ $session->user_name ?? 'Unknown' }}
                                            @if($session->is_current)
                                            <span class="badge-current ms-1">
                                                <i class="fas fa-check-circle"></i> Current
                                            </span>
                                            @endif
                                        </div>
                                        <div class="user-email">{{ $session->user_email ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge-role">
                                    {{ str_replace('_', ' ', $session->current_role ?? '-') }}
                                </span>
                            </td>
                            <td>
                                <span class="ip-address">{{ $session->ip_address }}</span>
                            </td>
                            <td>
                                <div class="time-info">
                                    <span class="time-relative">{{ $session->last_activity_at->diffForHumans() }}</span>
                                    <span class="time-absolute">{{ $session->last_activity_at->format('M d, H:i') }}</span>
                                </div>
                            </td>
                            <td>
                                @if(!$session->is_current)
                                <form action="{{ route('admin.security.sessions.terminate', $session->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to terminate this session?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-terminate">
                                        <i class="fas fa-times"></i>
                                        Terminate
                                    </button>
                                </form>
                                @else
                                <span style="color: var(--neutral-400); font-size: 0.85rem;">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-desktop"></i>
                                    </div>
                                    <h5 class="empty-state-title">No Active Sessions</h5>
                                    <p class="empty-state-text">There are currently no active sessions to display.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($sessions->hasPages())
        <div class="industrial-pagination">
            {{ $sessions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
