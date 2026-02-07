@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
    :root {
        --uitm-blue: #1e3a8a;
        --uitm-blue-light: #3b82f6;
        --uitm-amber: #f59e0b;
        --industrial-dark: #0f172a;
        --industrial-gray: #334155;
        --industrial-light: #f1f5f9;
        --success: #059669;
        --danger: #dc2626;
        --warning: #ea580c;
        --info: #0d9488;
    }

    body { font-family: 'IBM Plex Sans', sans-serif; }
    .font-mono { font-family: 'IBM Plex Mono', monospace; }

    /* Page Header */
    .page-header {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, #1e40af 100%);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 300px;
        height: 100%;
        background: linear-gradient(135deg, transparent 0%, rgba(255,255,255,0.1) 100%);
        clip-path: polygon(100% 0, 0% 100%, 100% 100%);
    }

    .page-header h2 {
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .page-header p {
        opacity: 0.9;
        margin: 0;
    }

    /* Main Card */
    .main-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .main-card-header {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, #1e40af 100%);
        padding: 1.25rem 1.5rem;
        color: white;
    }

    .main-card-header h5 {
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .main-card-header-light {
        background: var(--industrial-light);
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .main-card-header-light h5, .main-card-header-light h6 {
        font-weight: 700;
        color: var(--industrial-dark);
        margin: 0;
    }

    .main-card-body {
        padding: 1.5rem;
    }

    /* Statistics Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
    }

    .stat-icon i {
        font-size: 1.5rem;
    }

    .stat-icon.blue {
        background: rgba(30,58,138,0.15);
        color: var(--uitm-blue);
    }

    .stat-icon.green {
        background: rgba(5,150,105,0.15);
        color: var(--success);
    }

    .stat-icon.orange {
        background: rgba(245,158,11,0.15);
        color: var(--uitm-amber);
    }

    .stat-icon.teal {
        background: rgba(13,148,136,0.15);
        color: var(--info);
    }

    .stat-value {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--industrial-dark);
        line-height: 1;
        font-family: 'IBM Plex Mono', monospace;
    }

    .stat-label {
        color: var(--industrial-gray);
        font-size: 0.9rem;
        margin-top: 0.5rem;
    }

    /* Category Stats */
    .category-stats {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .category-stat {
        padding: 1.5rem;
        border-radius: 12px;
        text-align: center;
        color: white;
    }

    .category-stat.primary {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, #1e40af 100%);
    }

    .category-stat.teal {
        background: linear-gradient(135deg, var(--info) 0%, #0f766e 100%);
    }

    .category-stat h2 {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
        font-family: 'IBM Plex Mono', monospace;
    }

    .category-stat small {
        opacity: 0.9;
    }

    .category-mapping-stats {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        text-align: center;
    }

    .mapping-stat strong {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 1.25rem;
    }

    .mapping-stat small {
        display: block;
        color: var(--industrial-gray);
    }

    /* Progress Bars */
    .activity-item {
        margin-bottom: 1.25rem;
    }

    .activity-item:last-child {
        margin-bottom: 0;
    }

    .activity-label {
        display: flex;
        justify-content: between;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .progress-custom {
        height: 10px;
        border-radius: 5px;
        background: var(--industrial-light);
        overflow: hidden;
    }

    .progress-bar-custom {
        height: 100%;
        border-radius: 5px;
    }

    .progress-bar-custom.blue { background: var(--uitm-blue); }
    .progress-bar-custom.amber { background: var(--uitm-amber); }
    .progress-bar-custom.green { background: var(--success); }

    /* Custom Table */
    .custom-table {
        width: 100%;
        border-collapse: collapse;
    }

    .custom-table thead th {
        background: var(--industrial-light);
        color: var(--industrial-dark);
        font-weight: 600;
        padding: 1rem;
        text-align: left;
        border-bottom: 2px solid #e2e8f0;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .custom-table tbody td {
        padding: 1rem;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
    }

    .custom-table tbody tr:hover {
        background: rgba(30,58,138,0.02);
    }

    /* Badges */
    .badge-primary {
        background: var(--uitm-blue);
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8rem;
        font-family: 'IBM Plex Mono', monospace;
    }

    .badge-success {
        background: var(--success);
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .badge-warning {
        background: var(--uitm-amber);
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .badge-secondary {
        background: #64748b;
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .badge-info {
        background: var(--info);
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8rem;
    }

    /* Timeline */
    .timeline-item {
        display: flex;
        gap: 1rem;
        padding: 1rem 0;
        border-bottom: 1px solid #e2e8f0;
    }

    .timeline-item:last-child {
        border-bottom: none;
    }

    .timeline-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--success);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .timeline-content {
        flex: 1;
    }

    .timeline-content strong {
        color: var(--industrial-dark);
    }

    .timeline-content small {
        display: block;
        color: var(--industrial-gray);
    }

    /* Health Cards */
    .health-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }

    .health-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .health-card-header {
        background: var(--industrial-light);
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .health-card-header h6 {
        font-weight: 700;
        color: var(--industrial-dark);
        margin: 0;
    }

    .health-card-body {
        padding: 1.5rem;
        text-align: center;
    }

    .health-value {
        font-size: 2.5rem;
        font-weight: 700;
        line-height: 1;
        font-family: 'IBM Plex Mono', monospace;
    }

    .health-value.green { color: var(--success); }
    .health-value.amber { color: var(--uitm-amber); }
    .health-value.red { color: var(--danger); }

    .health-label {
        color: var(--industrial-gray);
        font-size: 0.85rem;
        margin-top: 0.5rem;
    }

    .health-progress {
        height: 20px;
        border-radius: 10px;
        background: var(--industrial-light);
        overflow: hidden;
        margin-top: 1rem;
    }

    .health-progress-bar {
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 0.8rem;
    }

    /* Buttons */
    .btn-industrial {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-back {
        background: rgba(255,255,255,0.2);
        color: white;
        border: 1px solid rgba(255,255,255,0.3);
    }

    .btn-back:hover {
        background: rgba(255,255,255,0.3);
        color: white;
    }

    .btn-success-industrial {
        background: var(--success);
        color: white;
        border: none;
    }

    .btn-success-industrial:hover {
        background: #047857;
        color: white;
    }

    /* Info Alert */
    .info-alert {
        background: rgba(13,148,136,0.08);
        border: 1px solid rgba(13,148,136,0.2);
        border-left: 4px solid var(--info);
        border-radius: 8px;
        padding: 1.25rem 1.5rem;
    }

    .info-alert h6 {
        font-weight: 700;
        color: var(--info);
        margin-bottom: 0.5rem;
    }

    .info-alert p {
        color: var(--industrial-gray);
        margin: 0;
    }

    /* Card Footer */
    .card-footer-stats {
        background: var(--industrial-light);
        padding: 1rem 1.5rem;
        border-top: 1px solid #e2e8f0;
        font-size: 0.85rem;
        color: var(--industrial-gray);
    }

    @media print {
        .btn, .info-alert { display: none !important; }
        .main-card { page-break-inside: avoid; }
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .health-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex flex-wrap justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-chart-bar me-2"></i>Equivalency Lists Statistics & Reports</h2>
                <p><i class="fas fa-clipboard-list me-2"></i>Comprehensive analytics and reports across all programs</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-industrial btn-success-industrial" onclick="window.print()">
                    <i class="fas fa-print"></i> Print Report
                </button>
                <a href="{{ route('hea.equivalency_lists.index') }}" class="btn btn-industrial btn-back">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>

    <!-- Overall Statistics -->
    <div class="main-card">
        <div class="main-card-header">
            <h5><i class="fas fa-chart-pie"></i> Overall System Statistics</h5>
        </div>
        <div class="main-card-body">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon blue">
                        <i class="fas fa-list-alt"></i>
                    </div>
                    <div class="stat-value">{{ $stats['total_lists'] }}</div>
                    <div class="stat-label">Total Lists</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon green">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-value">{{ $stats['published_lists'] }}</div>
                    <div class="stat-label">Published</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon orange">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="stat-value">{{ $stats['draft_lists'] }}</div>
                    <div class="stat-label">Drafts</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon teal">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <div class="stat-value">{{ $stats['total_mappings'] }}</div>
                    <div class="stat-label">Total Mappings</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Breakdown -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="main-card h-100">
                <div class="main-card-header-light">
                    <h6><i class="fas fa-chart-bar me-2"></i>Lists by Category</h6>
                </div>
                <div class="main-card-body">
                    <div class="category-stats">
                        <div class="category-stat primary">
                            <h2>{{ $stats['internal_lists'] }}</h2>
                            <small>Internal (CS110)</small>
                        </div>
                        <div class="category-stat teal">
                            <h2>{{ $stats['external_lists'] }}</h2>
                            <small>External Institutions</small>
                        </div>
                    </div>
                    <hr>
                    <div class="category-mapping-stats">
                        <div class="mapping-stat">
                            <strong>{{ $stats['internal_mappings'] }}</strong>
                            <small>Internal Mappings</small>
                        </div>
                        <div class="mapping-stat">
                            <strong>{{ $stats['external_mappings'] }}</strong>
                            <small>External Mappings</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="main-card h-100">
                <div class="main-card-header-light">
                    <h6><i class="fas fa-users me-2"></i>Activity Overview</h6>
                </div>
                <div class="main-card-body">
                    <div class="activity-item">
                        <div class="activity-label">
                            <span>Active Program Coordinators</span>
                            <span class="badge-primary">{{ $stats['active_coordinators'] }}</span>
                        </div>
                        <div class="progress-custom">
                            <div class="progress-bar-custom blue" style="width: 100%"></div>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-label">
                            <span>Pending Mappings (RP→PC)</span>
                            <span class="badge-warning">{{ $stats['pending_mappings'] }}</span>
                        </div>
                        <div class="progress-custom">
                            <div class="progress-bar-custom amber" style="width: {{ $stats['pending_mappings'] > 0 ? '100' : '0' }}%"></div>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-label">
                            <span>Active Equivalency Lists</span>
                            <span class="badge-success">{{ $stats['active_lists'] }}</span>
                        </div>
                        <div class="progress-custom">
                            <div class="progress-bar-custom green" style="width: {{ $stats['active_lists'] > 0 ? '100' : '0' }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Program-Specific Statistics -->
    <div class="main-card">
        <div class="main-card-header-light">
            <h5><i class="fas fa-graduation-cap me-2"></i>Statistics by Program</h5>
        </div>
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Program Code</th>
                        <th>Program Name</th>
                        <th class="text-center">Total Lists</th>
                        <th class="text-center">Published</th>
                        <th class="text-center">Drafts</th>
                        <th class="text-center">Total Mappings</th>
                        <th class="text-center">Coverage</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($programStats as $program)
                    <tr>
                        <td><span class="badge-primary">{{ $program->program_code }}</span></td>
                        <td>{{ Str::limit($program->program_name, 40) }}</td>
                        <td class="text-center">
                            <span class="badge-secondary">{{ $program->total_lists }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge-success">{{ $program->published_lists }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge-warning">{{ $program->draft_lists }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge-info">{{ $program->total_mappings }}</span>
                        </td>
                        <td class="text-center">
                            @if($program->has_internal && $program->has_external)
                                <span class="badge-success">Full</span>
                            @elseif($program->has_internal || $program->has_external)
                                <span class="badge-warning">Partial</span>
                            @else
                                <span class="badge-secondary">None</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No program data available</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- External Institutions Statistics -->
    <div class="main-card">
        <div class="main-card-header-light">
            <h5><i class="fas fa-university me-2"></i>External Institutions Coverage</h5>
        </div>
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Institution Name</th>
                        <th class="text-center">Programs Covered</th>
                        <th class="text-center">Total Lists</th>
                        <th class="text-center">Total Mappings</th>
                        <th class="text-center">Last Updated</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($institutionStats as $institution)
                    <tr>
                        <td>{{ Str::limit($institution->source_institution, 50) }}</td>
                        <td class="text-center">
                            <span class="badge-primary">{{ $institution->programs_count }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge-info">{{ $institution->lists_count }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge-success">{{ $institution->mappings_count }}</span>
                        </td>
                        <td class="text-center">
                            <small class="text-muted">{{ $institution->last_updated ? $institution->last_updated->format('d M Y') : 'N/A' }}</small>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">No external institution data available</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($institutionStats->count() > 0)
        <div class="card-footer-stats">
            <i class="fas fa-info-circle me-1"></i>
            Total External Institutions: <strong>{{ $institutionStats->count() }}</strong> |
            Average Mappings per Institution: <strong>{{ number_format($institutionStats->avg('mappings_count'), 1) }}</strong>
        </div>
        @endif
    </div>

    <!-- Recent Activity Timeline -->
    <div class="main-card">
        <div class="main-card-header-light">
            <h5><i class="fas fa-clock me-2"></i>Recent Publishing Activity</h5>
        </div>
        <div class="main-card-body">
            @forelse($recentPublished as $list)
            <div class="timeline-item">
                <div class="timeline-icon">
                    <i class="fas fa-check"></i>
                </div>
                <div class="timeline-content">
                    <div class="d-flex justify-content-between">
                        <div>
                            <strong>{{ $list->program_code }}</strong> - {{ $list->semester }}
                            @if($list->category === 'internal')
                                <span class="badge-primary ms-2">CS110</span>
                            @else
                                <span class="badge-info ms-2">{{ Str::limit($list->source_institution, 25) }}</span>
                            @endif
                        </div>
                        <small class="text-muted">{{ $list->published_at->diffForHumans() }}</small>
                    </div>
                    <small>
                        Published by {{ $list->publisher->name ?? 'N/A' }} |
                        {{ $list->courseEquivalencies->count() }} mappings
                    </small>
                </div>
            </div>
            @empty
            <p class="text-muted text-center mb-0">No recent publishing activity</p>
            @endforelse
        </div>
    </div>

    <!-- System Health Indicators -->
    <div class="health-grid">
        <div class="health-card">
            <div class="health-card-header">
                <h6><i class="fas fa-heartbeat me-2"></i>Coverage Health</h6>
            </div>
            <div class="health-card-body">
                @php
                    $coveragePercentage = $stats['total_programs'] > 0
                        ? ($stats['programs_with_lists'] / $stats['total_programs']) * 100
                        : 0;
                @endphp
                <div class="health-value {{ $coveragePercentage >= 80 ? 'green' : ($coveragePercentage >= 50 ? 'amber' : 'red') }}">
                    {{ number_format($coveragePercentage, 1) }}%
                </div>
                <div class="health-label">Programs Covered</div>
                <div class="health-progress">
                    <div class="health-progress-bar bg-{{ $coveragePercentage >= 80 ? 'success' : ($coveragePercentage >= 50 ? 'warning' : 'danger') }}"
                         style="width: {{ $coveragePercentage }}%">
                        {{ $stats['programs_with_lists'] }} / {{ $stats['total_programs'] }}
                    </div>
                </div>
            </div>
        </div>

        <div class="health-card">
            <div class="health-card-header">
                <h6><i class="fas fa-percentage me-2"></i>Eligibility Rate</h6>
            </div>
            <div class="health-card-body">
                @php
                    $eligibilityRate = $stats['total_mappings'] > 0
                        ? ($stats['eligible_mappings'] / $stats['total_mappings']) * 100
                        : 0;
                @endphp
                <div class="health-value green">
                    {{ number_format($eligibilityRate, 1) }}%
                </div>
                <div class="health-label">Eligible Mappings (≥80%)</div>
                <div class="health-progress">
                    <div class="health-progress-bar bg-success" style="width: {{ $eligibilityRate }}%">
                        {{ $stats['eligible_mappings'] }} / {{ $stats['total_mappings'] }}
                    </div>
                </div>
            </div>
        </div>

        <div class="health-card">
            <div class="health-card-header">
                <h6><i class="fas fa-tasks me-2"></i>Workflow Status</h6>
            </div>
            <div class="health-card-body">
                @php
                    $workflowHealth = $stats['pending_mappings'] == 0 ? 100 :
                        ($stats['published_lists'] / max($stats['total_lists'], 1)) * 100;
                @endphp
                <div class="health-value {{ $workflowHealth >= 80 ? 'green' : 'amber' }}">
                    {{ number_format($workflowHealth, 0) }}%
                </div>
                <div class="health-label">Publishing Rate</div>
                <small class="text-muted d-block mt-2">
                    {{ $stats['pending_mappings'] }} pending mappings
                </small>
            </div>
        </div>
    </div>

    <!-- Info Panel -->
    <div class="info-alert mt-4">
        <h6><i class="fas fa-info-circle me-2"></i>About These Statistics</h6>
        <p>
            This dashboard provides comprehensive analytics across all equivalency lists in the system.
            Data is updated in real-time and reflects the current state of all program coordination activities.
            HEA personnel can use these insights to monitor system coverage and identify programs requiring attention.
        </p>
    </div>
</div>
@endsection
