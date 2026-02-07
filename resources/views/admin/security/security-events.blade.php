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

    /* Page Header */
    .page-header {
        background: linear-gradient(135deg, var(--danger-red) 0%, #991b1b 50%, var(--industrial-dark) 100%);
        border-radius: 20px;
        padding: 2.5rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: -80px;
        right: -50px;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(245, 158, 11, 0.2) 0%, transparent 70%);
        border-radius: 50%;
        animation: threat-pulse 3s ease-in-out infinite;
    }

    .page-header::after {
        content: '';
        position: absolute;
        bottom: -100px;
        left: 10%;
        width: 250px;
        height: 250px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.05) 0%, transparent 70%);
        border-radius: 50%;
    }

    @keyframes threat-pulse {
        0%, 100% { transform: scale(1); opacity: 0.3; }
        50% { transform: scale(1.15); opacity: 0.6; }
    }

    .page-header h1 {
        color: #fff;
        font-weight: 700;
        font-size: 1.875rem;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .page-header h1 i {
        font-size: 2rem;
    }

    .page-header p {
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 0;
        position: relative;
        z-index: 1;
    }

    .btn-back {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #fff;
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-back:hover {
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
        transform: translateX(-3px);
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    @media (max-width: 992px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 576px) {
        .stats-grid { grid-template-columns: 1fr; }
    }

    .stat-card {
        background: #fff;
        border-radius: 16px;
        padding: 1.75rem;
        position: relative;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        text-align: center;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
    }

    .stat-card.danger::before { background: linear-gradient(90deg, var(--danger-red), #ef4444); }
    .stat-card.warning::before { background: linear-gradient(90deg, var(--uitm-amber), var(--uitm-amber-light)); }
    .stat-card.success::before { background: linear-gradient(90deg, var(--success-green), #10b981); }

    .stat-icon {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        margin: 0 auto 1rem;
    }

    .stat-card.danger .stat-icon {
        background: linear-gradient(135deg, rgba(220, 38, 38, 0.15) 0%, rgba(239, 68, 68, 0.05) 100%);
        color: var(--danger-red);
    }
    .stat-card.warning .stat-icon {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(251, 191, 36, 0.05) 100%);
        color: var(--uitm-amber);
    }
    .stat-card.success .stat-icon {
        background: linear-gradient(135deg, rgba(5, 150, 105, 0.15) 0%, rgba(16, 185, 129, 0.05) 100%);
        color: var(--success-green);
    }

    .stat-value {
        font-size: 2.5rem;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 0.5rem;
    }

    .stat-card.danger .stat-value { color: var(--danger-red); }
    .stat-card.warning .stat-value { color: var(--uitm-amber); }
    .stat-card.success .stat-value { color: var(--success-green); }

    .stat-label {
        color: #64748b;
        font-size: 0.875rem;
        font-weight: 500;
    }

    /* Filter Card */
    .filter-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .filter-form {
        display: flex;
        gap: 1rem;
        align-items: flex-end;
        flex-wrap: wrap;
    }

    .filter-group {
        flex: 1;
        min-width: 200px;
    }

    .filter-label {
        display: block;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
        margin-bottom: 0.5rem;
    }

    .filter-select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        font-size: 0.875rem;
        color: var(--industrial-dark);
        background: var(--industrial-light);
        transition: all 0.2s ease;
    }

    .filter-select:focus {
        outline: none;
        border-color: var(--uitm-blue);
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
    }

    .filter-actions {
        display: flex;
        gap: 0.75rem;
    }

    .btn-filter {
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
    }

    .btn-filter.primary {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--uitm-blue-light) 100%);
        color: #fff;
    }

    .btn-filter.primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(30, 58, 138, 0.25);
    }

    .btn-filter.secondary {
        background: var(--industrial-light);
        color: var(--industrial-gray);
        text-decoration: none;
    }

    .btn-filter.secondary:hover {
        background: #e2e8f0;
        color: var(--industrial-dark);
    }

    /* Events Table Card */
    .events-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .events-card-header {
        background: linear-gradient(135deg, var(--industrial-light) 0%, #fff 100%);
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .events-card-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 600;
        color: var(--industrial-dark);
    }

    .events-card-title i {
        color: var(--danger-red);
        font-size: 1.25rem;
    }

    .event-count-badge {
        background: linear-gradient(135deg, var(--danger-red) 0%, #ef4444 100%);
        color: #fff;
        padding: 0.375rem 0.875rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.75rem;
    }

    /* Data Table */
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table thead th {
        background: var(--industrial-light);
        padding: 1rem 1.25rem;
        text-align: left;
        font-weight: 600;
        font-size: 0.6875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
        border-bottom: 1px solid #e2e8f0;
    }

    .data-table tbody td {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .data-table tbody tr:last-child td {
        border-bottom: none;
    }

    .data-table tbody tr:hover {
        background: rgba(220, 38, 38, 0.02);
    }

    /* Event Type Badges */
    .event-badge {
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.6875rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
    }

    .event-badge.danger {
        background: linear-gradient(135deg, rgba(220, 38, 38, 0.15) 0%, rgba(239, 68, 68, 0.08) 100%);
        color: var(--danger-red);
    }

    .event-badge.warning {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(251, 191, 36, 0.08) 100%);
        color: var(--uitm-amber);
    }

    .event-badge.info {
        background: linear-gradient(135deg, rgba(8, 145, 178, 0.15) 0%, rgba(34, 211, 238, 0.08) 100%);
        color: var(--info-cyan);
    }

    .event-badge.dark {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.1) 0%, rgba(51, 65, 85, 0.05) 100%);
        color: var(--industrial-gray);
    }

    /* User Cell */
    .user-cell {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .user-avatar {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--uitm-blue-light) 100%);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.75rem;
    }

    .user-name {
        font-weight: 500;
        color: var(--industrial-dark);
    }

    .guest-label {
        color: #94a3b8;
        font-style: italic;
    }

    /* Code Cells */
    .ip-code, .route-code {
        font-family: 'IBM Plex Mono', monospace;
        background: var(--industrial-light);
        padding: 0.375rem 0.625rem;
        border-radius: 6px;
        font-size: 0.75rem;
        color: var(--industrial-gray);
    }

    /* Status Badges */
    .blocked-badge {
        padding: 0.375rem 0.75rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.6875rem;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
    }

    .blocked-badge.yes {
        background: linear-gradient(135deg, rgba(5, 150, 105, 0.15) 0%, rgba(16, 185, 129, 0.08) 100%);
        color: var(--success-green);
    }

    .blocked-badge.no {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(251, 191, 36, 0.08) 100%);
        color: var(--uitm-amber);
    }

    /* Details Column */
    .details-text {
        color: #64748b;
        font-size: 0.8125rem;
        max-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Time Column */
    .time-text {
        color: #94a3b8;
        font-size: 0.8125rem;
        white-space: nowrap;
    }

    /* Empty State */
    .empty-state {
        padding: 5rem 2rem;
        text-align: center;
    }

    .empty-state-icon {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, rgba(5, 150, 105, 0.15) 0%, rgba(16, 185, 129, 0.05) 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 3rem;
        color: var(--success-green);
    }

    .empty-state h4 {
        color: var(--industrial-dark);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #64748b;
        margin-bottom: 0;
    }

    /* Pagination */
    .pagination-wrapper {
        padding: 1.25rem 1.5rem;
        border-top: 1px solid #e2e8f0;
        background: var(--industrial-light);
    }

    .pagination-wrapper .pagination {
        margin-bottom: 0;
        justify-content: center;
    }

    .pagination-wrapper .page-link {
        border: 1px solid #e2e8f0;
        color: var(--industrial-gray);
        padding: 0.5rem 0.875rem;
        margin: 0 0.25rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .pagination-wrapper .page-link:hover {
        background: var(--uitm-blue);
        border-color: var(--uitm-blue);
        color: #fff;
    }

    .pagination-wrapper .page-item.active .page-link {
        background: var(--uitm-blue);
        border-color: var(--uitm-blue);
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <h1>
                    <i class="fas fa-shield-virus"></i>
                    Security Events
                </h1>
                <p>Monitor security threats, blocked attacks, and suspicious activities</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i>
                Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card danger">
            <div class="stat-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-value">{{ $stats['total_events'] }}</div>
            <div class="stat-label">Total Events</div>
        </div>

        <div class="stat-card warning">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-value">{{ $stats['events_24h'] }}</div>
            <div class="stat-label">Events (24h)</div>
        </div>

        <div class="stat-card success">
            <div class="stat-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div class="stat-value">{{ $stats['blocked_24h'] }}</div>
            <div class="stat-label">Blocked (24h)</div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-card">
        <form action="{{ route('admin.security.security-events') }}" method="GET" class="filter-form">
            <div class="filter-group">
                <label class="filter-label">Event Type</label>
                <select name="event_type" class="filter-select">
                    <option value="">All Event Types</option>
                    @foreach($eventTypes as $value => $label)
                    <option value="{{ $value }}" {{ request('event_type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn-filter primary">
                    <i class="fas fa-filter"></i>
                    Apply Filter
                </button>
                <a href="{{ route('admin.security.security-events') }}" class="btn-filter secondary">
                    <i class="fas fa-redo"></i>
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Events Table -->
    <div class="events-card">
        <div class="events-card-header">
            <div class="events-card-title">
                <i class="fas fa-list-alt"></i>
                <span>Security Event Log</span>
            </div>
            @if($securityEvents->total() > 0)
            <span class="event-count-badge">
                {{ $securityEvents->total() }} Events
            </span>
            @endif
        </div>

        @if($securityEvents->isEmpty())
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h4>No Security Events</h4>
            <p>The system is running securely with no detected threats.</p>
        </div>
        @else
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Event Type</th>
                        <th>User</th>
                        <th>IP Address</th>
                        <th>Route</th>
                        <th>Blocked</th>
                        <th>Details</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($securityEvents as $event)
                    <tr>
                        <td>
                            <span class="event-badge {{ $event->badge_color }}">
                                @if($event->badge_color === 'danger')
                                <i class="fas fa-exclamation-circle"></i>
                                @elseif($event->badge_color === 'warning')
                                <i class="fas fa-exclamation-triangle"></i>
                                @else
                                <i class="fas fa-info-circle"></i>
                                @endif
                                {{ $event->event_type_label }}
                            </span>
                        </td>
                        <td>
                            @if($event->user)
                            <div class="user-cell">
                                <div class="user-avatar">
                                    {{ strtoupper(substr($event->user->name, 0, 2)) }}
                                </div>
                                <span class="user-name">{{ $event->user->name }}</span>
                            </div>
                            @else
                            <span class="guest-label">Guest / Unknown</span>
                            @endif
                        </td>
                        <td>
                            <code class="ip-code">{{ $event->ip_address }}</code>
                        </td>
                        <td>
                            <code class="route-code">{{ Str::limit($event->route, 30) }}</code>
                        </td>
                        <td>
                            @if($event->blocked)
                            <span class="blocked-badge yes">
                                <i class="fas fa-check-circle"></i>
                                Blocked
                            </span>
                            @else
                            <span class="blocked-badge no">
                                <i class="fas fa-exclamation-circle"></i>
                                Allowed
                            </span>
                            @endif
                        </td>
                        <td>
                            <span class="details-text" title="{{ $event->details }}">
                                {{ Str::limit($event->details, 50) }}
                            </span>
                        </td>
                        <td>
                            <span class="time-text">{{ $event->created_at->format('M d, Y H:i') }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        @if($securityEvents->hasPages())
        <div class="pagination-wrapper">
            {{ $securityEvents->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
