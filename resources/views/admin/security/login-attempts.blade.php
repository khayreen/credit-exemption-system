@extends('layouts.app')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    :root {
        --uitm-primary: #1e3a8a;
        --uitm-primary-dark: #1e2d5b;
        --uitm-primary-light: #dbeafe;
        --uitm-amber: #f59e0b;
        --uitm-amber-light: #fef3c7;
        --uitm-green: #10b981;
        --uitm-green-light: #d1fae5;
        --uitm-red: #dc2626;
        --uitm-red-light: #fee2e2;
        --uitm-cyan: #06b6d4;
        --uitm-cyan-light: #cffafe;
        --slate-50: #f8fafc;
        --slate-100: #f1f5f9;
        --slate-200: #e2e8f0;
        --slate-300: #cbd5e1;
        --slate-400: #94a3b8;
        --slate-500: #64748b;
        --slate-600: #475569;
        --slate-700: #334155;
        --slate-800: #1e293b;
        --font-sans: 'IBM Plex Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        --font-mono: 'IBM Plex Mono', monospace;
    }

    body {
        font-family: var(--font-sans);
    }

    /* ========== PAGE HEADER ========== */
    .page-header {
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        border-radius: 16px;
        position: relative;
        padding: 2rem 2.5rem;
        margin-bottom: 2rem;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image:
            linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px),
            linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px);
        background-size: 24px 24px;
        pointer-events: none;
    }

    .page-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--uitm-amber), var(--uitm-green), var(--uitm-amber));
    }

    .header-content {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1.5rem;
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 1.25rem;
    }

    .header-icon {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .header-icon i {
        font-size: 1.75rem;
        color: white;
    }

    .header-text .eyebrow {
        font-family: var(--font-mono);
        font-size: 0.7rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.2em;
        color: var(--uitm-amber);
        margin-bottom: 0.25rem;
    }

    .header-text h1 {
        font-size: 1.75rem;
        font-weight: 700;
        color: white;
        margin: 0;
        letter-spacing: -0.02em;
    }

    .header-text p {
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.75);
        margin: 0.25rem 0 0 0;
    }

    .header-action .btn-header {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: rgba(255, 255, 255, 0.15);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        text-decoration: none;
        transition: all 0.2s ease;
        backdrop-filter: blur(8px);
    }

    .header-action .btn-header:hover {
        background: rgba(255, 255, 255, 0.25);
        color: white;
        transform: translateY(-1px);
    }

    /* ========== STATISTICS CARDS ========== */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.25rem;
        margin-bottom: 1.5rem;
    }

    @media (max-width: 992px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 576px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        border: 1px solid var(--slate-200);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
    }

    .stat-card.total::before {
        background: linear-gradient(90deg, var(--uitm-primary), var(--uitm-primary-dark));
    }

    .stat-card.failed::before {
        background: linear-gradient(90deg, var(--uitm-red), #b91c1c);
    }

    .stat-card.success::before {
        background: linear-gradient(90deg, var(--uitm-green), #059669);
    }

    .stat-card.ips::before {
        background: linear-gradient(90deg, var(--uitm-cyan), #0891b2);
    }

    .stat-value {
        font-size: 2.5rem;
        font-weight: 700;
        font-family: var(--font-mono);
        line-height: 1;
        margin-bottom: 0.5rem;
    }

    .stat-card.total .stat-value {
        color: var(--uitm-primary);
    }

    .stat-card.failed .stat-value {
        color: var(--uitm-red);
    }

    .stat-card.success .stat-value {
        color: var(--uitm-green);
    }

    .stat-card.ips .stat-value {
        color: var(--uitm-cyan);
    }

    .stat-label {
        font-size: 0.8125rem;
        font-weight: 500;
        color: var(--slate-500);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    /* ========== FILTER PANEL ========== */
    .filter-panel {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--slate-200);
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .filter-grid {
        display: grid;
        grid-template-columns: 1fr 2fr 1.5fr 1.5fr 1.5fr auto;
        gap: 1rem;
        align-items: end;
    }

    @media (max-width: 1200px) {
        .filter-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {
        .filter-grid {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 576px) {
        .filter-grid {
            grid-template-columns: 1fr;
        }
    }

    .filter-group label {
        display: block;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--slate-600);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.5rem;
    }

    .filter-group .form-control,
    .filter-group .form-select {
        border: 1px solid var(--slate-300);
        border-radius: 8px;
        padding: 0.625rem 1rem;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .filter-group .form-control:focus,
    .filter-group .form-select:focus {
        border-color: var(--uitm-primary);
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
    }

    .btn-filter {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.625rem 1.5rem;
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s ease;
        height: 42px;
    }

    .btn-filter:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
    }

    /* ========== DATA TABLE ========== */
    .data-card {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--slate-200);
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .data-table {
        width: 100%;
        font-size: 0.875rem;
    }

    .data-table thead {
        background: var(--slate-50);
    }

    .data-table thead th {
        padding: 1rem 1.25rem;
        font-weight: 600;
        color: var(--slate-600);
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.08em;
        font-family: var(--font-mono);
        border-bottom: 1px solid var(--slate-200);
        white-space: nowrap;
    }

    .data-table tbody tr {
        border-bottom: 1px solid var(--slate-100);
        transition: background 0.15s ease;
    }

    .data-table tbody tr:last-child {
        border-bottom: none;
    }

    .data-table tbody tr:hover {
        background: var(--slate-50);
    }

    .data-table td {
        padding: 1rem 1.25rem;
        vertical-align: middle;
    }

    .email-cell {
        font-family: var(--font-mono);
        font-size: 0.8125rem;
        color: var(--slate-700);
    }

    .user-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 0.75rem;
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        color: white;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .user-badge.student {
        background: linear-gradient(135deg, var(--uitm-green) 0%, #059669 100%);
    }

    .user-badge.coordinator {
        background: linear-gradient(135deg, var(--uitm-amber) 0%, #d97706 100%);
    }

    .user-badge.resource-person {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    }

    .user-badge.hea {
        background: linear-gradient(135deg, var(--uitm-cyan) 0%, #0891b2 100%);
    }

    .ip-code {
        font-family: var(--font-mono);
        font-size: 0.8125rem;
        color: var(--uitm-primary);
        background: var(--uitm-primary-light);
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.375rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .status-badge.success {
        background: var(--uitm-green-light);
        color: #059669;
    }

    .status-badge.failed {
        background: var(--uitm-red-light);
        color: var(--uitm-red);
    }

    .reason-text {
        font-family: var(--font-mono);
        font-size: 0.8125rem;
        color: var(--uitm-red);
    }

    .reason-none {
        color: var(--slate-400);
    }

    .ua-text {
        font-size: 0.75rem;
        color: var(--slate-500);
        max-width: 180px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .time-text {
        font-family: var(--font-mono);
        font-size: 0.75rem;
        color: var(--slate-600);
        white-space: nowrap;
    }

    /* ========== EMPTY STATE ========== */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-state-icon {
        width: 80px;
        height: 80px;
        background: var(--slate-100);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }

    .empty-state-icon i {
        font-size: 2rem;
        color: var(--slate-400);
    }

    .empty-state h5 {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--slate-600);
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: var(--slate-500);
        font-size: 0.9375rem;
        margin: 0;
    }

    /* ========== PAGINATION ========== */
    .pagination-footer {
        padding: 1rem 1.5rem;
        background: var(--slate-50);
        border-top: 1px solid var(--slate-200);
    }

    .pagination-footer .pagination {
        margin: 0;
        justify-content: center;
    }

    .pagination-footer .page-link {
        border: 1px solid var(--slate-300);
        color: var(--slate-600);
        padding: 0.5rem 0.875rem;
        font-size: 0.875rem;
        margin: 0 0.125rem;
        border-radius: 6px;
    }

    .pagination-footer .page-link:hover {
        background: var(--uitm-primary-light);
        border-color: var(--uitm-primary);
        color: var(--uitm-primary);
    }

    .pagination-footer .page-item.active .page-link {
        background: var(--uitm-primary);
        border-color: var(--uitm-primary);
        color: white;
    }

    /* ========== RESPONSIVE ========== */
    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
        }

        .header-content {
            flex-direction: column;
            text-align: center;
        }

        .header-left {
            flex-direction: column;
        }

        .header-text h1 {
            font-size: 1.375rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Page Header -->
    <header class="page-header">
        <div class="header-content">
            <div class="header-left">
                <div class="header-icon">
                    <i class="fas fa-sign-in-alt"></i>
                </div>
                <div class="header-text">
                    <div class="eyebrow">Security Monitoring</div>
                    <h1>Login Attempts</h1>
                    <p>Monitor authentication activity across the system</p>
                </div>
            </div>
            <div class="header-action">
                <a href="{{ route('admin.dashboard') }}" class="btn-header">
                    <i class="fas fa-arrow-left"></i>
                    Back to Dashboard
                </a>
            </div>
        </div>
    </header>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card total">
            <div class="stat-value">{{ number_format($stats['total_attempts']) }}</div>
            <div class="stat-label">Total Attempts</div>
        </div>
        <div class="stat-card failed">
            <div class="stat-value">{{ number_format($stats['failed_24h']) }}</div>
            <div class="stat-label">Failed (24h)</div>
        </div>
        <div class="stat-card success">
            <div class="stat-value">{{ number_format($stats['success_24h']) }}</div>
            <div class="stat-label">Successful (24h)</div>
        </div>
        <div class="stat-card ips">
            <div class="stat-value">{{ number_format($stats['unique_ips_24h']) }}</div>
            <div class="stat-label">Unique IPs (24h)</div>
        </div>
    </div>

    <!-- Filter Panel -->
    <div class="filter-panel">
        <form action="{{ route('admin.security.login-attempts') }}" method="GET">
            <div class="filter-grid">
                <div class="filter-group">
                    <label>Status</label>
                    <select name="status" class="form-select">
                        <option value="">All</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                        <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Success</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Email</label>
                    <input type="text" name="email" class="form-control" value="{{ request('email') }}" placeholder="Search email...">
                </div>
                <div class="filter-group">
                    <label>IP Address</label>
                    <input type="text" name="ip" class="form-control" value="{{ request('ip') }}" placeholder="IP...">
                </div>
                <div class="filter-group">
                    <label>From Date</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="filter-group">
                    <label>To Date</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="filter-group">
                    <button type="submit" class="btn-filter">
                        <i class="fas fa-filter"></i>
                        Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Login Attempts Table -->
    <div class="data-card">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>User</th>
                        <th>IP Address</th>
                        <th>Status</th>
                        <th>Reason</th>
                        <th>User Agent</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loginAttempts as $attempt)
                    <tr>
                        <td>
                            <span class="email-cell">{{ $attempt->email }}</span>
                        </td>
                        <td>
                            @if($attempt->user)
                                @php
                                    $roleClass = 'user-badge';
                                    if($attempt->user->role_type === 'App\\Models\\Student') {
                                        $roleClass .= ' student';
                                    } elseif($attempt->user->role_type === 'App\\Models\\ProgramCoordinator') {
                                        $roleClass .= ' coordinator';
                                    } elseif($attempt->user->role_type === 'App\\Models\\ResourcePerson') {
                                        $roleClass .= ' resource-person';
                                    } elseif($attempt->user->role_type === 'App\\Models\\HeaPersonnel') {
                                        $roleClass .= ' hea';
                                    }
                                @endphp
                                <span class="{{ $roleClass }}">{{ $attempt->user->name }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <code class="ip-code">{{ $attempt->ip_address }}</code>
                        </td>
                        <td>
                            @if($attempt->status === 'success')
                                <span class="status-badge success">
                                    <i class="fas fa-check-circle"></i>
                                    Success
                                </span>
                            @else
                                <span class="status-badge failed">
                                    <i class="fas fa-times-circle"></i>
                                    Failed
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($attempt->failure_reason)
                                <span class="reason-text">{{ $attempt->failure_reason }}</span>
                            @else
                                <span class="reason-none">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="ua-text" title="{{ $attempt->user_agent }}">
                                {{ Str::limit($attempt->user_agent, 30) }}
                            </span>
                        </td>
                        <td>
                            <span class="time-text">{{ $attempt->attempted_at->format('M d, Y H:i:s') }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-sign-in-alt"></i>
                                </div>
                                <h5>No Login Attempts Found</h5>
                                <p>There are no login attempts matching your filter criteria.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($loginAttempts->hasPages())
        <div class="pagination-footer">
            {{ $loginAttempts->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
