@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Equivalency Lists Statistics & Reports</h2>
            <p class="text-muted mb-0 mt-2">
                <i class="fas fa-chart-bar me-1"></i>
                Comprehensive analytics and reports across all programs
            </p>
        </div>
        <div>
            <button class="btn btn-success" onclick="window.print()">
                <i class="fas fa-print"></i> Print Report
            </button>
            <a href="{{ route('hea.equivalency_lists.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <!-- Overall Statistics -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Overall System Statistics</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="card stat-card border-primary mb-3">
                        <div class="card-body text-center">
                            <div class="stat-icon icon-blue mb-2">
                                <i class="fas fa-list-alt fa-2x"></i>
                            </div>
                            <h3 class="mb-0">{{ $stats['total_lists'] }}</h3>
                            <p class="text-muted mb-0">Total Lists</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card border-success mb-3">
                        <div class="card-body text-center">
                            <div class="stat-icon icon-green mb-2">
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                            <h3 class="mb-0">{{ $stats['published_lists'] }}</h3>
                            <p class="text-muted mb-0">Published</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card border-warning mb-3">
                        <div class="card-body text-center">
                            <div class="stat-icon icon-orange mb-2">
                                <i class="fas fa-file-alt fa-2x"></i>
                            </div>
                            <h3 class="mb-0">{{ $stats['draft_lists'] }}</h3>
                            <p class="text-muted mb-0">Drafts</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card border-info mb-3">
                        <div class="card-body text-center">
                            <div class="stat-icon icon-purple mb-2">
                                <i class="fas fa-exchange-alt fa-2x"></i>
                            </div>
                            <h3 class="mb-0">{{ $stats['total_mappings'] }}</h3>
                            <p class="text-muted mb-0">Total Mappings</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Breakdown -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Lists by Category</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="p-3 border rounded bg-primary text-white mb-2">
                                <h2 class="mb-0">{{ $stats['internal_lists'] }}</h2>
                                <small>Internal (CS110)</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 border rounded bg-info text-white mb-2">
                                <h2 class="mb-0">{{ $stats['external_lists'] }}</h2>
                                <small>External Institutions</small>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row text-center">
                        <div class="col-6">
                            <strong>{{ $stats['internal_mappings'] }}</strong>
                            <br><small class="text-muted">Internal Mappings</small>
                        </div>
                        <div class="col-6">
                            <strong>{{ $stats['external_mappings'] }}</strong>
                            <br><small class="text-muted">External Mappings</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-users me-2"></i>Activity Overview</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Active Program Coordinators</span>
                            <span class="badge bg-primary fs-6">{{ $stats['active_coordinators'] }}</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-primary" style="width: 100%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Pending Mappings (RP→PC)</span>
                            <span class="badge bg-warning text-dark fs-6">{{ $stats['pending_mappings'] }}</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-warning" style="width: {{ $stats['pending_mappings'] > 0 ? '100' : '0' }}%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Active Equivalency Lists</span>
                            <span class="badge bg-success fs-6">{{ $stats['active_lists'] }}</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" style="width: {{ $stats['active_lists'] > 0 ? '100' : '0' }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Program-Specific Statistics -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Statistics by Program</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
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
                            <td><span class="badge bg-primary">{{ $program->program_code }}</span></td>
                            <td>{{ Str::limit($program->program_name, 40) }}</td>
                            <td class="text-center">
                                <span class="badge bg-secondary">{{ $program->total_lists }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success">{{ $program->published_lists }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-warning text-dark">{{ $program->draft_lists }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info">{{ $program->total_mappings }}</span>
                            </td>
                            <td class="text-center">
                                @if($program->has_internal && $program->has_external)
                                    <span class="badge bg-success">Full</span>
                                @elseif($program->has_internal || $program->has_external)
                                    <span class="badge bg-warning text-dark">Partial</span>
                                @else
                                    <span class="badge bg-secondary">None</span>
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
    </div>

    <!-- External Institutions Statistics -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="fas fa-university me-2"></i>External Institutions Coverage</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
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
                                <span class="badge bg-primary">{{ $institution->programs_count }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info">{{ $institution->lists_count }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success">{{ $institution->mappings_count }}</span>
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
        </div>
        @if($institutionStats->count() > 0)
        <div class="card-footer bg-light">
            <small class="text-muted">
                <i class="fas fa-info-circle me-1"></i>
                Total External Institutions: <strong>{{ $institutionStats->count() }}</strong> |
                Average Mappings per Institution: <strong>{{ number_format($institutionStats->avg('mappings_count'), 1) }}</strong>
            </small>
        </div>
        @endif
    </div>

    <!-- Recent Activity Timeline -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Recent Publishing Activity</h5>
        </div>
        <div class="card-body">
            @forelse($recentPublished as $list)
            <div class="d-flex mb-3 pb-3 border-bottom">
                <div class="me-3">
                    <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="fas fa-check"></i>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between">
                        <div>
                            <strong>{{ $list->program_code }}</strong> - {{ $list->semester }}
                            @if($list->category === 'internal')
                                <span class="badge bg-primary ms-2">CS110</span>
                            @else
                                <span class="badge bg-info ms-2">{{ Str::limit($list->source_institution, 25) }}</span>
                            @endif
                        </div>
                        <small class="text-muted">{{ $list->published_at->diffForHumans() }}</small>
                    </div>
                    <small class="text-muted">
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
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-heartbeat me-2"></i>Coverage Health</h6>
                </div>
                <div class="card-body">
                    @php
                        $coveragePercentage = $stats['total_programs'] > 0
                            ? ($stats['programs_with_lists'] / $stats['total_programs']) * 100
                            : 0;
                    @endphp
                    <div class="text-center mb-3">
                        <h2 class="mb-0 {{ $coveragePercentage >= 80 ? 'text-success' : ($coveragePercentage >= 50 ? 'text-warning' : 'text-danger') }}">
                            {{ number_format($coveragePercentage, 1) }}%
                        </h2>
                        <small class="text-muted">Programs Covered</small>
                    </div>
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar {{ $coveragePercentage >= 80 ? 'bg-success' : ($coveragePercentage >= 50 ? 'bg-warning' : 'bg-danger') }}"
                             style="width: {{ $coveragePercentage }}%">
                            {{ $stats['programs_with_lists'] }} / {{ $stats['total_programs'] }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-percentage me-2"></i>Eligibility Rate</h6>
                </div>
                <div class="card-body">
                    @php
                        $eligibilityRate = $stats['total_mappings'] > 0
                            ? ($stats['eligible_mappings'] / $stats['total_mappings']) * 100
                            : 0;
                    @endphp
                    <div class="text-center mb-3">
                        <h2 class="mb-0 text-success">
                            {{ number_format($eligibilityRate, 1) }}%
                        </h2>
                        <small class="text-muted">Eligible Mappings (≥80%)</small>
                    </div>
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar bg-success" style="width: {{ $eligibilityRate }}%">
                            {{ $stats['eligible_mappings'] }} / {{ $stats['total_mappings'] }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-tasks me-2"></i>Workflow Status</h6>
                </div>
                <div class="card-body">
                    @php
                        $workflowHealth = $stats['pending_mappings'] == 0 ? 100 :
                            ($stats['published_lists'] / max($stats['total_lists'], 1)) * 100;
                    @endphp
                    <div class="text-center mb-3">
                        <h2 class="mb-0 {{ $workflowHealth >= 80 ? 'text-success' : 'text-warning' }}">
                            {{ number_format($workflowHealth, 0) }}%
                        </h2>
                        <small class="text-muted">Publishing Rate</small>
                    </div>
                    <small class="text-muted d-block text-center">
                        {{ $stats['pending_mappings'] }} pending mappings
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Panel -->
    <div class="alert alert-info">
        <h6 class="alert-heading">
            <i class="fas fa-info-circle me-2"></i>About These Statistics
        </h6>
        <p class="mb-0">
            This dashboard provides comprehensive analytics across all equivalency lists in the system.
            Data is updated in real-time and reflects the current state of all program coordination activities.
            HEA personnel can use these insights to monitor system coverage and identify programs requiring attention.
        </p>
    </div>
</div>

<style>
@media print {
    .btn, .alert { display: none !important; }
    .card { page-break-inside: avoid; }
}
</style>
@endsection
