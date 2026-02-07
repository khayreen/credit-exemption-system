@extends('layouts.app')

@section('title', 'Locked Accounts')

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
        background: linear-gradient(135deg, var(--danger) 0%, #991b1b 100%);
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
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        border-radius: 50%;
    }

    .page-header::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: 10%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(0, 0, 0, 0.1) 0%, transparent 70%);
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
        color: rgba(255, 255, 255, 0.85);
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
        border-left: 4px solid var(--danger);
    }

    .industrial-card-header i {
        font-size: 1.1rem;
        color: var(--danger);
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

    /* User Info */
    .user-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: linear-gradient(135deg, var(--danger) 0%, #991b1b 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 0.9rem;
        position: relative;
    }

    .user-avatar::after {
        content: '';
        position: absolute;
        bottom: -2px;
        right: -2px;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: var(--danger);
        border: 2px solid white;
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

    .badge-attempts {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        padding: 0.375rem 0.625rem;
        border-radius: 8px;
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.85rem;
        font-weight: 700;
        background: var(--danger-light);
        color: var(--danger);
        border: 2px solid var(--danger);
    }

    /* Lock Reason */
    .lock-reason {
        max-width: 200px;
        font-size: 0.85rem;
        color: var(--neutral-600);
    }

    .lock-reason.not-specified {
        color: var(--neutral-400);
        font-style: italic;
    }

    /* Time Info */
    .time-info {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.8rem;
        color: var(--neutral-600);
    }

    /* Buttons */
    .btn-unlock {
        background: linear-gradient(135deg, var(--success) 0%, #047857 100%);
        border: none;
        color: white;
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

    .btn-unlock:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(5, 150, 105, 0.4);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-state-icon {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: var(--success-light);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        animation: pulse-success 2s ease-in-out infinite;
    }

    @keyframes pulse-success {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    .empty-state-icon i {
        font-size: 3rem;
        color: var(--success);
    }

    .empty-state-title {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--success);
        margin-bottom: 0.5rem;
    }

    .empty-state-text {
        color: var(--neutral-500);
        font-size: 0.95rem;
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

        .lock-reason {
            max-width: 120px;
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
                        <i class="fas fa-lock me-2"></i>
                        Locked Accounts
                    </h1>
                    <p class="page-subtitle">Manage accounts that have been locked due to security concerns</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i>
                    Back to Dashboard
                </a>
            </div>
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

    <!-- Locked Accounts Table -->
    <div class="industrial-card">
        <div class="industrial-card-header">
            <i class="fas fa-user-lock"></i>
            <h5 class="industrial-card-title">Locked User Accounts</h5>
        </div>
        <div class="industrial-card-body">
            <div class="table-responsive">
                <table class="industrial-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Role</th>
                            <th>Lock Reason</th>
                            <th>Locked At</th>
                            <th>Failed Attempts</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lockedUsers as $user)
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="user-name">{{ $user->name }}</div>
                                        <div class="user-email">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge-role">
                                    {{ str_replace('_', ' ', $user->current_role ?? '-') }}
                                </span>
                            </td>
                            <td>
                                <span class="lock-reason {{ !$user->lock_reason ? 'not-specified' : '' }}">
                                    {{ $user->lock_reason ?? 'Not specified' }}
                                </span>
                            </td>
                            <td>
                                <span class="time-info">{{ $user->locked_at->format('M d, Y H:i:s') }}</span>
                            </td>
                            <td>
                                <span class="badge-attempts">{{ $user->failed_login_attempts }}</span>
                            </td>
                            <td>
                                <form action="{{ route('admin.security.accounts.unlock', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to unlock this account?')">
                                    @csrf
                                    <button type="submit" class="btn-unlock">
                                        <i class="fas fa-unlock"></i>
                                        Unlock
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <h5 class="empty-state-title">All Clear!</h5>
                                    <p class="empty-state-text">No locked accounts found. All user accounts are currently accessible.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($lockedUsers->hasPages())
        <div class="industrial-pagination">
            {{ $lockedUsers->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
