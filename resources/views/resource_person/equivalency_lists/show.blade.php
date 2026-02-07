@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">
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

    body {
        font-family: 'IBM Plex Sans', sans-serif;
        background-color: var(--industrial-light);
    }

    .page-header {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--industrial-dark) 100%);
        border-radius: 0 0 24px 24px;
        padding: 2rem 2.5rem;
        margin: -1.5rem -1.5rem 2rem -1.5rem;
        color: white;
    }

    .page-header-content {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .page-header h2 {
        font-weight: 700;
        font-size: 1.75rem;
        margin-bottom: 0.35rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .page-header p {
        color: rgba(255,255,255,0.8);
        margin: 0;
    }

    .header-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .btn-header {
        padding: 0.5rem 1.25rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-header.pdf {
        background: var(--danger);
        color: white;
        border: none;
    }

    .btn-header.pdf:hover {
        background: #b91c1c;
        color: white;
    }

    .btn-header.back {
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.3);
        color: white;
    }

    .btn-header.back:hover {
        background: rgba(255,255,255,0.25);
        color: white;
        transform: translateX(-3px);
    }

    /* Info Banner */
    .info-banner {
        background: rgba(13, 148, 136, 0.1);
        border: 1px solid rgba(13, 148, 136, 0.2);
        border-left: 4px solid var(--info);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        color: var(--info);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    /* Card Styles */
    .info-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .info-card-header {
        background: var(--industrial-light);
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .info-card-header h5 {
        margin: 0;
        font-weight: 600;
        color: var(--industrial-dark);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-card-body {
        padding: 1.5rem;
    }

    .info-table {
        width: 100%;
    }

    .info-table th {
        width: 150px;
        font-weight: 500;
        color: var(--industrial-gray);
        padding: 0.5rem 0;
        vertical-align: top;
    }

    .info-table td {
        padding: 0.5rem 0;
        color: var(--industrial-dark);
    }

    .program-badge {
        display: inline-block;
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--uitm-blue-light) 100%);
        color: white;
        padding: 0.35rem 0.875rem;
        border-radius: 8px;
        font-weight: 600;
        font-family: 'IBM Plex Mono', monospace;
    }

    .category-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.35rem 0.875rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .category-badge.internal {
        background: rgba(30, 58, 138, 0.1);
        color: var(--uitm-blue);
    }

    .category-badge.external {
        background: rgba(13, 148, 136, 0.1);
        color: var(--info);
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.35rem 0.875rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.85rem;
        background: rgba(5, 150, 105, 0.1);
        color: var(--success);
    }

    /* Mappings Card */
    .mappings-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .mappings-header {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--uitm-blue-light) 100%);
        color: white;
        padding: 1.25rem 1.5rem;
    }

    .mappings-header h5 {
        margin: 0;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-state i {
        font-size: 4rem;
        color: #cbd5e1;
        margin-bottom: 1rem;
    }

    .empty-state h5 {
        color: var(--industrial-gray);
        font-weight: 500;
    }

    .empty-state p {
        color: #94a3b8;
    }

    /* Mappings Table */
    .mappings-table {
        width: 100%;
        margin: 0;
    }

    .mappings-table thead th {
        background: var(--industrial-light);
        color: var(--industrial-gray);
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 1rem;
        border-bottom: 2px solid var(--uitm-blue);
    }

    .mappings-table tbody td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .mappings-table tbody tr:hover {
        background: rgba(30, 58, 138, 0.02);
    }

    .course-code {
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 600;
        color: var(--industrial-dark);
    }

    .course-name {
        font-size: 0.85rem;
        color: var(--industrial-gray);
    }

    .institution-info {
        font-size: 0.8rem;
        color: #94a3b8;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .match-badge {
        display: inline-block;
        padding: 0.4rem 0.875rem;
        border-radius: 8px;
        font-weight: 600;
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.9rem;
    }

    .match-badge.success {
        background: rgba(5, 150, 105, 0.1);
        color: var(--success);
    }

    .match-badge.warning {
        background: rgba(234, 88, 12, 0.1);
        color: var(--warning);
    }

    .credit-info {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.85rem;
        color: var(--industrial-gray);
    }

    .eligible-icon.success {
        color: var(--success);
        font-size: 1.25rem;
    }

    .eligible-icon.danger {
        color: var(--danger);
        font-size: 1.25rem;
    }

    /* Statistics Footer */
    .stats-footer {
        background: var(--industrial-light);
        padding: 1rem 1.5rem;
        border-top: 1px solid #e2e8f0;
    }

    .stats-grid {
        display: flex;
        justify-content: center;
        gap: 3rem;
    }

    .stat-item {
        text-align: center;
    }

    .stat-item strong {
        color: var(--industrial-gray);
        font-size: 0.85rem;
    }

    .stat-item .stat-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 6px;
        font-weight: 700;
        font-family: 'IBM Plex Mono', monospace;
        margin-left: 0.5rem;
    }

    .stat-item .stat-badge.primary {
        background: rgba(30, 58, 138, 0.1);
        color: var(--uitm-blue);
    }

    .stat-item .stat-badge.success {
        background: rgba(5, 150, 105, 0.1);
        color: var(--success);
    }

    .stat-item .stat-badge.warning {
        background: rgba(234, 88, 12, 0.1);
        color: var(--warning);
    }

    /* Action Panel */
    .action-panel {
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    }

    .action-panel h6 {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--industrial-dark);
        font-weight: 600;
        margin-bottom: 0.75rem;
    }

    .action-panel p {
        color: var(--industrial-gray);
        margin-bottom: 1rem;
    }

    .btn-action {
        background: linear-gradient(135deg, var(--success) 0%, #10b981 100%);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(5, 150, 105, 0.3);
        color: white;
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
            margin: -1rem -1rem 1.5rem -1rem;
            border-radius: 0 0 16px 16px;
        }

        .stats-grid {
            flex-direction: column;
            gap: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div>
                <h2>
                    @if($list->category === 'internal')
                        <i class="fas fa-building"></i>
                    @else
                        <i class="fas fa-university"></i>
                    @endif
                    {{ $list->program_code }} Equivalency List
                </h2>
                <p>
                    <i class="fas fa-calendar-alt me-1"></i>{{ $list->semester }}
                    @if($list->category === 'external')
                        <span class="mx-2">|</span>
                        <i class="fas fa-university me-1"></i>{{ $list->source_institution }}
                    @else
                        <span class="mx-2">|</span>
                        <i class="fas fa-building me-1"></i>UiTM CS110 (Internal)
                    @endif
                </p>
            </div>
            <div class="header-actions">
                <a href="{{ route('resource_person.equivalency_lists.pdf', $list) }}" class="btn-header pdf" target="_blank">
                    <i class="fas fa-file-pdf"></i> View as PDF
                </a>
                <a href="{{ route('resource_person.equivalency_lists.published') }}" class="btn-header back">
                    <i class="fas fa-arrow-left"></i> Back to Lists
                </a>
            </div>
        </div>
    </div>

    <!-- Read-Only Notice -->
    <div class="info-banner">
        <i class="fas fa-info-circle"></i>
        <div>
            <strong>Read-Only View:</strong> This is a published equivalency list. Resource Persons can view but cannot edit published lists. To propose new mappings, use the "Forward New Mapping" feature.
        </div>
    </div>

    <!-- List Information Card -->
    <div class="info-card">
        <div class="info-card-header">
            <h5><i class="fas fa-info-circle"></i>List Information</h5>
        </div>
        <div class="info-card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="info-table">
                        <tr>
                            <th>Program Code:</th>
                            <td><span class="program-badge">{{ $list->program_code }}</span></td>
                        </tr>
                        <tr>
                            <th>Program Name:</th>
                            <td>{{ $list->program_name }}</td>
                        </tr>
                        <tr>
                            <th>Category:</th>
                            <td>
                                @if($list->category === 'internal')
                                    <span class="category-badge internal">
                                        <i class="fas fa-building"></i> Internal (CS110)
                                    </span>
                                @else
                                    <span class="category-badge external">
                                        <i class="fas fa-university"></i> External Institution
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @if($list->category === 'external')
                            <tr>
                                <th>Source Institution:</th>
                                <td>{{ $list->source_institution }}</td>
                            </tr>
                        @endif
                        <tr>
                            <th>Semester:</th>
                            <td>{{ $list->semester }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="info-table">
                        <tr>
                            <th>Status:</th>
                            <td>
                                <span class="status-badge">
                                    <i class="fas fa-check-circle"></i> Published
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Total Mappings:</th>
                            <td><strong>{{ $list->courseEquivalencies->count() }}</strong> course(s)</td>
                        </tr>
                        <tr>
                            <th>Published By:</th>
                            <td>{{ $list->publisher->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Published At:</th>
                            <td>{{ $list->published_at ? $list->published_at->format('d M Y, h:i A') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Active List:</th>
                            <td>
                                @if($list->is_active)
                                    <span class="status-badge">
                                        <i class="fas fa-check-circle"></i> Yes
                                    </span>
                                @else
                                    <span class="text-muted">No</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Course Mappings (Read-Only) -->
    <div class="mappings-card">
        <div class="mappings-header">
            <h5><i class="fas fa-exchange-alt"></i>Course Equivalency Mappings</h5>
        </div>

        @if($list->courseEquivalencies->isEmpty())
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h5>No Course Mappings</h5>
                <p>This equivalency list does not have any course mappings.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="mappings-table">
                    <thead>
                        <tr>
                            <th width="5%" class="text-center">#</th>
                            <th width="35%">Diploma Course</th>
                            <th width="35%">Degree Course</th>
                            <th width="10%" class="text-center">Match %</th>
                            <th width="10%" class="text-center">Credit Hours</th>
                            <th width="5%" class="text-center">Eligible</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list->courseEquivalencies->sortBy('diploma_course_code') as $index => $eq)
                        <tr>
                            <td class="text-center text-muted">{{ $index + 1 }}</td>
                            <td>
                                <span class="course-code">{{ $eq->diploma_course_code }}</span>
                                <div class="course-name">{{ $eq->diploma_course_name }}</div>
                                <div class="institution-info">
                                    <i class="fas fa-university"></i>{{ $eq->diploma_institution }}
                                </div>
                            </td>
                            <td>
                                <span class="course-code">{{ $eq->degree_course_code }}</span>
                                <div class="course-name">{{ $eq->degree_course_name }}</div>
                            </td>
                            <td class="text-center">
                                <span class="match-badge {{ $eq->match_percentage >= 80 ? 'success' : 'warning' }}">
                                    {{ number_format($eq->match_percentage, 0) }}%
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="credit-info">{{ $eq->diploma_credit_hour }} → {{ $eq->degree_credit_hour }}</span>
                            </td>
                            <td class="text-center">
                                @if($eq->is_eligible)
                                    <i class="fas fa-check-circle eligible-icon success" title="Eligible for exemption"></i>
                                @else
                                    <i class="fas fa-times-circle eligible-icon danger" title="Not eligible"></i>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Statistics Footer -->
            <div class="stats-footer">
                <div class="stats-grid">
                    <div class="stat-item">
                        <strong>Total Mappings:</strong>
                        <span class="stat-badge primary">{{ $list->courseEquivalencies->count() }}</span>
                    </div>
                    <div class="stat-item">
                        <strong>Eligible (≥80%):</strong>
                        <span class="stat-badge success">{{ $list->courseEquivalencies->where('is_eligible', true)->count() }}</span>
                    </div>
                    <div class="stat-item">
                        <strong>Not Eligible (&lt;80%):</strong>
                        <span class="stat-badge warning">{{ $list->courseEquivalencies->where('is_eligible', false)->count() }}</span>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Action Panel -->
    <div class="action-panel">
        <h6><i class="fas fa-lightbulb"></i>Want to manage course mappings?</h6>
        <p>If you've evaluated a syllabus and need to add or edit course equivalencies for this program, use the "All Course Mappings" page.</p>
        <a href="{{ route('resource_person.course_equivalencies.view') }}" class="btn-action">
            <i class="fas fa-exchange-alt"></i>All Course Mappings
        </a>
    </div>
</div>
@endsection
