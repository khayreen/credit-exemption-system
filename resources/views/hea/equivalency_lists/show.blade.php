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
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .page-header h2 {
        font-weight: 700;
        font-size: 1.75rem;
        margin: 0;
    }

    .header-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .btn-header {
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.3);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.85rem;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-header:hover {
        background: rgba(255,255,255,0.25);
        color: white;
    }

    .btn-header.primary {
        background: var(--uitm-amber);
        border-color: var(--uitm-amber);
        color: var(--industrial-dark);
    }

    .btn-header.primary:hover {
        background: #fbbf24;
    }

    .btn-header.pdf {
        background: var(--danger);
        border-color: var(--danger);
    }

    .alert-industrial {
        border-radius: 12px;
        border: none;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
    }

    .alert-industrial.success {
        background: rgba(5, 150, 105, 0.1);
        border-left: 4px solid var(--success);
        color: var(--success);
    }

    /* List Info Card */
    .list-info-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .list-info-header {
        padding: 1.25rem 1.5rem;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .list-info-header.internal {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--uitm-blue-light) 100%);
    }

    .list-info-header.external {
        background: linear-gradient(135deg, var(--success) 0%, #10b981 100%);
    }

    .list-info-header h5 {
        margin: 0 0 0.25rem 0;
        font-weight: 700;
    }

    .list-info-header small {
        opacity: 0.9;
    }

    .status-badges {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 0.35rem;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.4rem 0.875rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .status-badge.published {
        background: rgba(255,255,255,0.2);
        color: white;
    }

    .status-badge.active {
        background: var(--uitm-amber);
        color: var(--industrial-dark);
    }

    .list-info-body {
        padding: 1.5rem;
    }

    .info-section-title {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--industrial-gray);
        font-weight: 600;
        margin-bottom: 0.75rem;
    }

    .info-table {
        width: 100%;
    }

    .info-table td {
        padding: 0.5rem 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .info-table td:first-child {
        color: var(--industrial-gray);
        font-weight: 500;
        width: 150px;
    }

    .info-table td:last-child {
        color: var(--industrial-dark);
    }

    .info-table tr:last-child td {
        border-bottom: none;
    }

    .category-badge {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .category-badge.internal {
        background: rgba(30, 58, 138, 0.1);
        color: var(--uitm-blue);
    }

    .category-badge.external {
        background: rgba(5, 150, 105, 0.1);
        color: var(--success);
    }

    .notes-alert {
        border-radius: 10px;
        padding: 1rem;
        margin-top: 1rem;
        margin-bottom: 0;
    }

    .notes-alert.info {
        background: rgba(245, 158, 11, 0.1);
        border: 1px solid rgba(245, 158, 11, 0.2);
        color: #92400e;
    }

    .notes-alert.success {
        background: rgba(5, 150, 105, 0.1);
        border: 1px solid rgba(5, 150, 105, 0.2);
        color: var(--success);
    }

    .notes-alert.danger {
        background: rgba(220, 38, 38, 0.1);
        border: 1px solid rgba(220, 38, 38, 0.2);
        color: var(--danger);
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.25rem;
        text-align: center;
        border: 2px solid transparent;
    }

    .stat-card.primary { border-color: var(--uitm-blue); }
    .stat-card.success { border-color: var(--success); }
    .stat-card.warning { border-color: var(--warning); }

    .stat-card .number {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 2rem;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 0.35rem;
    }

    .stat-card.primary .number { color: var(--uitm-blue); }
    .stat-card.success .number { color: var(--success); }
    .stat-card.warning .number { color: var(--warning); }

    .stat-card .label {
        font-size: 0.8rem;
        color: var(--industrial-gray);
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
        background: var(--industrial-light);
        padding: 1rem 1.25rem;
        border-bottom: 2px solid var(--uitm-blue);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .mappings-header h6 {
        margin: 0;
        font-weight: 600;
        color: var(--industrial-dark);
    }

    .mappings-count {
        background: var(--industrial-gray);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .mappings-table {
        width: 100%;
        margin: 0;
    }

    .mappings-table thead th {
        background: var(--industrial-light);
        color: var(--industrial-gray);
        font-weight: 600;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 0.875rem 1rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .mappings-table tbody td {
        padding: 1rem;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .mappings-table tbody tr:hover {
        background: rgba(30, 58, 138, 0.02);
    }

    .course-code {
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 600;
        color: var(--uitm-blue);
    }

    .course-details {
        font-size: 0.85rem;
        color: var(--industrial-gray);
    }

    .match-badge {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .match-badge.high {
        background: rgba(5, 150, 105, 0.1);
        color: var(--success);
    }

    .match-badge.low {
        background: rgba(234, 88, 12, 0.1);
        color: var(--warning);
    }

    .mappings-footer {
        background: var(--industrial-light);
        padding: 0.875rem 1.25rem;
        border-top: 1px solid #e2e8f0;
        font-size: 0.85rem;
        color: var(--industrial-gray);
    }

    /* Activity Card */
    .activity-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        overflow: hidden;
    }

    .activity-header {
        background: var(--industrial-light);
        padding: 1rem 1.25rem;
        border-bottom: 2px solid var(--uitm-blue);
    }

    .activity-header h6 {
        margin: 0;
        font-weight: 600;
        color: var(--industrial-dark);
    }

    .activity-item {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-action {
        font-weight: 600;
        color: var(--industrial-dark);
    }

    .activity-user {
        font-size: 0.85rem;
        color: var(--industrial-gray);
    }

    .activity-time {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.8rem;
        color: var(--industrial-gray);
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
            margin: -1rem -1rem 1.5rem -1rem;
            border-radius: 0 0 16px 16px;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="page-header">
        <h2><i class="fas fa-clipboard-list me-2"></i>Equivalency List Details</h2>
        <div class="header-actions">
            @if(in_array($list->status, ['submitted', 'under_review']))
                <a href="{{ route('hea.equivalency_lists.review', $list) }}" class="btn-header primary">
                    <i class="fas fa-clipboard-check me-1"></i> Review
                </a>
            @endif
            <a href="{{ route('hea.equivalency_lists.pdf', $list) }}" class="btn-header pdf" target="_blank">
                <i class="fas fa-file-pdf me-1"></i> Download PDF
            </a>
            <a href="{{ route('hea.equivalency_lists.index') }}" class="btn-header">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert-industrial success">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    </div>
    @endif

    <!-- List Information Card -->
    <div class="list-info-card">
        <div class="list-info-header {{ $list->isInternal() ? 'internal' : 'external' }}">
            <div>
                <h5>
                    @if($list->isInternal())
                        CS110 (UiTM DIPLOMA) LIST
                    @else
                        EXTERNAL LIST ({{ $list->source_institution }})
                    @endif
                </h5>
                <small>{{ $list->program_code }} - {{ $list->program_name }}</small>
            </div>
            <div class="status-badges">
                @if($list->published_at)
                    <span class="status-badge published">Published</span>
                @else
                    <span class="status-badge published">{{ $list->status_display }}</span>
                @endif
                @if($list->is_active)
                    <span class="status-badge active">ACTIVE LIST</span>
                @endif
            </div>
        </div>
        <div class="list-info-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="info-section-title">List Information</div>
                    <table class="info-table">
                        <tr>
                            <td>Category:</td>
                            <td>
                                @if($list->isInternal())
                                    <span class="category-badge internal">Internal (CS110)</span>
                                @else
                                    <span class="category-badge external">External</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Source:</td>
                            <td>{{ $list->source_display }}</td>
                        </tr>
                        <tr>
                            <td>Target Program:</td>
                            <td><strong style="font-family: 'IBM Plex Mono', monospace;">{{ $list->program_code }}</strong></td>
                        </tr>
                        <tr>
                            <td>Semester:</td>
                            <td>{{ $list->semester }}</td>
                        </tr>
                        <tr>
                            <td>Academic Year:</td>
                            <td>{{ $list->academic_year }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <div class="info-section-title">Workflow Information</div>
                    <table class="info-table">
                        <tr>
                            <td>Created By:</td>
                            <td>{{ $list->creator->name ?? 'Unknown' }}</td>
                        </tr>
                        <tr>
                            <td>Created:</td>
                            <td style="font-family: 'IBM Plex Mono', monospace; font-size: 0.9rem;">{{ $list->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                        @if($list->submitted_at)
                        <tr>
                            <td>Submitted:</td>
                            <td style="font-family: 'IBM Plex Mono', monospace; font-size: 0.9rem;">{{ $list->submitted_at->format('d M Y, H:i') }}</td>
                        </tr>
                        @endif
                        @if($list->endorsed_at)
                        <tr>
                            <td>Endorsed By:</td>
                            <td>{{ $list->endorser->name ?? 'Unknown' }}</td>
                        </tr>
                        <tr>
                            <td>Endorsed:</td>
                            <td style="font-family: 'IBM Plex Mono', monospace; font-size: 0.9rem;">{{ $list->endorsed_at->format('d M Y, H:i') }}</td>
                        </tr>
                        @endif
                        @if($list->published_at)
                        <tr>
                            <td>Published:</td>
                            <td style="font-family: 'IBM Plex Mono', monospace; font-size: 0.9rem;">{{ $list->published_at->format('d M Y, H:i') }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            @if($list->submission_notes)
            <div class="notes-alert info">
                <strong><i class="fas fa-comment me-1"></i> Submission Notes:</strong><br>
                {{ $list->submission_notes }}
            </div>
            @endif

            @if($list->endorsement_notes)
            <div class="notes-alert success">
                <strong><i class="fas fa-check-circle me-1"></i> Endorsement Notes:</strong><br>
                {{ $list->endorsement_notes }}
            </div>
            @endif

            @if($list->review_notes && $list->status === 'rejected')
            <div class="notes-alert danger">
                <strong><i class="fas fa-times-circle me-1"></i> Rejection Reason:</strong><br>
                {{ $list->review_notes }}
            </div>
            @endif
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card primary">
            <div class="number">{{ $list->total_equivalencies }}</div>
            <div class="label">Total Mappings</div>
        </div>
        <div class="stat-card success">
            <div class="number">{{ $list->eligible_count }}</div>
            <div class="label">Eligible (≥80%)</div>
        </div>
        <div class="stat-card warning">
            <div class="number">{{ $list->not_eligible_count }}</div>
            <div class="label">Not Eligible (&lt;80%)</div>
        </div>
    </div>

    <!-- Equivalency Mappings Table -->
    <div class="mappings-card">
        <div class="mappings-header">
            <h6><i class="fas fa-list me-2"></i>Course Equivalency Mappings</h6>
            <span class="mappings-count">{{ $list->courseEquivalencies->count() }} mappings</span>
        </div>
        <div class="table-responsive">
            <table class="mappings-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Diploma Course</th>
                        <th>Degree Course</th>
                        <th class="text-center" style="width: 100px;">Match %</th>
                        <th class="text-center" style="width: 100px;">Eligible</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($list->courseEquivalencies->sortBy('diploma_course_code') as $index => $eq)
                    <tr>
                        <td class="text-muted">{{ $index + 1 }}</td>
                        <td>
                            <div class="course-code">{{ $eq->diploma_course_code }}</div>
                            <div class="course-details">{{ $eq->diploma_course_name }} ({{ $eq->diploma_credit_hour }} cr)</div>
                        </td>
                        <td>
                            <div class="course-code">{{ $eq->degree_course_code }}</div>
                            <div class="course-details">{{ $eq->degree_course_name }} ({{ $eq->degree_credit_hour }} cr)</div>
                        </td>
                        <td class="text-center">
                            <span class="match-badge {{ $eq->match_percentage >= 80 ? 'high' : 'low' }}">
                                {{ number_format($eq->match_percentage, 0) }}%
                            </span>
                        </td>
                        <td class="text-center">
                            @if($eq->is_eligible)
                                <i class="fas fa-check-circle fa-lg" style="color: var(--success);" title="Eligible"></i>
                            @else
                                <i class="fas fa-times-circle fa-lg" style="color: var(--danger);" title="Not Eligible"></i>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">No course mappings found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mappings-footer">
            <i class="fas fa-check-circle" style="color: var(--success);"></i> Eligible = Match ≥80% (student must also have grade C or above)
            <span class="mx-2">|</span>
            <i class="fas fa-times-circle" style="color: var(--danger);"></i> Not Eligible = Match &lt;80%
        </div>
    </div>

    <!-- Activity Log -->
    @if($activityLog && $activityLog->count() > 0)
    <div class="activity-card">
        <div class="activity-header">
            <h6><i class="fas fa-history me-2"></i>Activity Log</h6>
        </div>
        @foreach($activityLog as $log)
        <div class="activity-item">
            <div>
                <div class="activity-action">{{ $log->action }}</div>
                <div class="activity-user">by {{ $log->user->name ?? 'System' }}</div>
            </div>
            <div class="activity-time">{{ $log->created_at->format('d M Y, H:i') }}</div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
