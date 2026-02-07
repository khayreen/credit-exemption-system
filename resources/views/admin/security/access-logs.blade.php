@extends('layouts.app')

@section('title', 'Access Logs')

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

    .stat-card.danger::before {
        background: linear-gradient(90deg, var(--danger), #b91c1c);
    }

    .stat-card.warning::before {
        background: linear-gradient(90deg, var(--warning), var(--uitm-amber));
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

    .stat-card.danger .stat-icon {
        background: var(--danger-light);
        color: var(--danger);
    }

    .stat-card.warning .stat-icon {
        background: var(--warning-light);
        color: var(--warning);
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

    /* Filter Card */
    .filter-card {
        background: white;
        border: 2px solid var(--neutral-200);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .filter-title {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--uitm-primary);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-title i {
        color: var(--uitm-amber);
    }

    .form-label-industrial {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--neutral-700);
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-select-industrial {
        border: 2px solid var(--neutral-200);
        border-radius: 10px;
        padding: 0.75rem 1rem;
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        width: 100%;
        background-color: white;
    }

    .form-select-industrial:focus {
        border-color: var(--uitm-primary);
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
        outline: none;
    }

    .btn-filter {
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        border: none;
        color: white;
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }

    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
    }

    .btn-reset {
        background: white;
        border: 2px solid var(--neutral-300);
        color: var(--neutral-700);
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-reset:hover {
        background: var(--neutral-50);
        border-color: var(--neutral-400);
        color: var(--neutral-600);
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

    .industrial-table tbody tr.denied-row {
        background: rgba(220, 38, 38, 0.03);
    }

    .industrial-table tbody tr.denied-row:hover {
        background: rgba(220, 38, 38, 0.06);
    }

    /* User Display */
    .user-display {
        font-weight: 500;
        color: var(--industrial-dark);
    }

    .user-display.guest {
        color: var(--neutral-400);
        font-style: italic;
    }

    /* Route Display */
    .route-display {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.8rem;
        color: var(--uitm-primary);
        background: rgba(30, 58, 138, 0.08);
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        display: inline-block;
    }

    /* Method Badge */
    .badge-method {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        background: var(--neutral-100);
        color: var(--neutral-600);
        border: 1px solid var(--neutral-200);
    }

    .badge-method.get {
        background: #dbeafe;
        color: #1d4ed8;
        border-color: #93c5fd;
    }

    .badge-method.post {
        background: var(--success-light);
        color: var(--success);
        border-color: #6ee7b7;
    }

    .badge-method.put,
    .badge-method.patch {
        background: var(--warning-light);
        color: var(--warning);
        border-color: #fdba74;
    }

    .badge-method.delete {
        background: var(--danger-light);
        color: var(--danger);
        border-color: #fca5a5;
    }

    /* IP Address */
    .ip-address {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.85rem;
        color: var(--neutral-600);
    }

    /* Status Badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .status-badge.allowed {
        background: var(--success-light);
        color: var(--success);
    }

    .status-badge.denied {
        background: var(--danger-light);
        color: var(--danger);
    }

    /* Denial Reason */
    .denial-reason {
        font-size: 0.85rem;
        color: var(--neutral-600);
        max-width: 150px;
    }

    .denial-reason.none {
        color: var(--neutral-400);
    }

    /* Time Display */
    .time-display {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.8rem;
        color: var(--neutral-500);
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

        .filter-card .row {
            gap: 1rem;
        }

        .industrial-table {
            font-size: 0.85rem;
        }

        .industrial-table thead th,
        .industrial-table tbody td {
            padding: 0.75rem 0.5rem;
        }

        .route-display {
            max-width: 100px;
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
                        <i class="fas fa-file-alt me-2" style="color: var(--uitm-amber);"></i>
                        Access Logs
                    </h1>
                    <p class="page-subtitle">Monitor unauthorized access attempts and route access patterns</p>
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
        <div class="stat-card danger">
            <div class="stat-icon">
                <i class="fas fa-ban"></i>
            </div>
            <div class="stat-value">{{ $stats['total_denied'] }}</div>
            <div class="stat-label">Total Denied Access</div>
        </div>
        <div class="stat-card warning">
            <div class="stat-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-value">{{ $stats['denied_24h'] }}</div>
            <div class="stat-label">Denied (Last 24 Hours)</div>
        </div>
    </div>

    <!-- Filter -->
    <div class="filter-card">
        <div class="filter-title">
            <i class="fas fa-filter"></i>
            Filter Logs
        </div>
        <form action="{{ route('admin.security.access-logs') }}" method="GET">
            <div class="row align-items-end g-3">
                <div class="col-md-4">
                    <label class="form-label-industrial">Status</label>
                    <select name="status" class="form-select-industrial">
                        <option value="">All Statuses</option>
                        <option value="denied" {{ request('status') == 'denied' ? 'selected' : '' }}>Denied</option>
                        <option value="allowed" {{ request('status') == 'allowed' ? 'selected' : '' }}>Allowed</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn-filter">
                            <i class="fas fa-search"></i>
                            Apply Filter
                        </button>
                        <a href="{{ route('admin.security.access-logs') }}" class="btn-reset">
                            <i class="fas fa-redo"></i>
                            Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Access Logs Table -->
    <div class="industrial-card">
        <div class="industrial-card-header">
            <i class="fas fa-list"></i>
            <h5 class="industrial-card-title">Access Log Entries</h5>
        </div>
        <div class="industrial-card-body">
            <div class="table-responsive">
                <table class="industrial-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Route</th>
                            <th>Method</th>
                            <th>IP Address</th>
                            <th>Status</th>
                            <th>Denial Reason</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($accessLogs as $log)
                        <tr class="{{ $log->status === 'denied' ? 'denied-row' : '' }}">
                            <td>
                                @if($log->user)
                                <span class="user-display">{{ $log->user->name }}</span>
                                @else
                                <span class="user-display guest">Guest</span>
                                @endif
                            </td>
                            <td>
                                <span class="route-display" title="{{ $log->route }}">{{ $log->route }}</span>
                            </td>
                            <td>
                                <span class="badge-method {{ strtolower($log->method) }}">{{ $log->method }}</span>
                            </td>
                            <td>
                                <span class="ip-address">{{ $log->ip_address }}</span>
                            </td>
                            <td>
                                @if($log->status === 'allowed')
                                <span class="status-badge allowed">
                                    <i class="fas fa-check"></i>
                                    Allowed
                                </span>
                                @else
                                <span class="status-badge denied">
                                    <i class="fas fa-times"></i>
                                    Denied
                                </span>
                                @endif
                            </td>
                            <td>
                                <span class="denial-reason {{ !$log->denial_reason ? 'none' : '' }}">
                                    {{ $log->denial_reason ?? '-' }}
                                </span>
                            </td>
                            <td>
                                <span class="time-display">{{ $log->created_at->format('M d, Y H:i') }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <h5 class="empty-state-title">No Access Logs Found</h5>
                                    <p class="empty-state-text">No access log entries match the current filter criteria.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($accessLogs->hasPages())
        <div class="industrial-pagination">
            {{ $accessLogs->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
