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

    .btn-back {
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.3);
        color: white;
        padding: 0.5rem 1.25rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn-back:hover {
        background: rgba(255,255,255,0.25);
        color: white;
        transform: translateX(-3px);
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

    .alert-industrial.danger {
        background: rgba(220, 38, 38, 0.1);
        border-left: 4px solid var(--danger);
        color: var(--danger);
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

    .status-badge-large {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.5rem 1rem;
        background: rgba(255,255,255,0.2);
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .list-info-body {
        padding: 1.5rem;
    }

    .info-table {
        width: 100%;
        margin-bottom: 0;
    }

    .info-table td {
        padding: 0.5rem 0;
        vertical-align: top;
    }

    .info-table td:first-child {
        color: var(--industrial-gray);
        width: 150px;
        font-weight: 500;
    }

    .info-table td:last-child {
        color: var(--industrial-dark);
    }

    .info-alert {
        border-radius: 10px;
        padding: 1rem 1.25rem;
        margin-top: 1rem;
        margin-bottom: 0;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .info-alert.internal {
        background: rgba(30, 58, 138, 0.08);
        border: 1px solid rgba(30, 58, 138, 0.2);
        color: var(--uitm-blue);
    }

    .info-alert.external {
        background: rgba(5, 150, 105, 0.08);
        border: 1px solid rgba(5, 150, 105, 0.2);
        color: var(--success);
    }

    .info-alert i {
        margin-top: 0.15rem;
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.25rem;
        text-align: center;
        border: 2px solid transparent;
        transition: all 0.2s ease;
    }

    .stat-card.primary {
        border-color: var(--uitm-blue);
    }

    .stat-card.success {
        border-color: var(--success);
    }

    .stat-card.warning {
        border-color: var(--warning);
    }

    .stat-card.info {
        border-color: var(--info);
    }

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
    .stat-card.info .number { color: var(--info); }

    .stat-card .label {
        font-size: 0.8rem;
        color: var(--industrial-gray);
    }

    /* Changes Card */
    .changes-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .changes-header {
        background: linear-gradient(135deg, var(--info) 0%, #14b8a6 100%);
        color: white;
        padding: 1rem 1.25rem;
    }

    .changes-header h6 {
        margin: 0;
        font-weight: 600;
    }

    .changes-body {
        padding: 1.25rem;
    }

    .change-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .change-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
        padding: 0.35rem 0.5rem;
        border-radius: 6px;
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 700;
        font-size: 0.85rem;
    }

    .change-badge.added {
        background: rgba(5, 150, 105, 0.1);
        color: var(--success);
    }

    .change-badge.modified {
        background: rgba(234, 88, 12, 0.1);
        color: var(--warning);
    }

    .change-badge.removed {
        background: rgba(220, 38, 38, 0.1);
        color: var(--danger);
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
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 600;
        font-size: 0.8rem;
    }

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

    .new-badge {
        display: inline-block;
        background: var(--success);
        color: white;
        padding: 0.2rem 0.5rem;
        border-radius: 4px;
        font-size: 0.7rem;
        font-weight: 700;
        margin-left: 0.5rem;
    }

    .mappings-footer {
        background: var(--industrial-light);
        padding: 0.875rem 1.25rem;
        border-top: 1px solid #e2e8f0;
        font-size: 0.85rem;
        color: var(--industrial-gray);
    }

    .mappings-footer i {
        margin-right: 0.35rem;
    }

    /* Activity Log */
    .activity-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        overflow: hidden;
        margin-bottom: 1.5rem;
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

    /* Decision Card */
    .decision-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .decision-header {
        background: linear-gradient(135deg, var(--industrial-dark) 0%, #1e293b 100%);
        color: white;
        padding: 1.25rem 1.5rem;
    }

    .decision-header h5 {
        margin: 0;
        font-weight: 700;
    }

    .decision-body {
        padding: 1.5rem;
    }

    .decision-option {
        background: white;
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        overflow: hidden;
        height: 100%;
        transition: all 0.2s ease;
    }

    .decision-option:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .decision-option.endorse {
        border-color: var(--success);
    }

    .decision-option.reject {
        border-color: var(--danger);
    }

    .decision-option-header {
        padding: 1rem 1.25rem;
        color: white;
    }

    .decision-option.endorse .decision-option-header {
        background: linear-gradient(135deg, var(--success) 0%, #10b981 100%);
    }

    .decision-option.reject .decision-option-header {
        background: linear-gradient(135deg, var(--danger) 0%, #ef4444 100%);
    }

    .decision-option-header h6 {
        margin: 0;
        font-weight: 600;
    }

    .decision-option-body {
        padding: 1.25rem;
    }

    .decision-option-body ul {
        padding-left: 1.25rem;
        margin-bottom: 1.25rem;
    }

    .decision-option-body li {
        font-size: 0.85rem;
        color: var(--industrial-gray);
        margin-bottom: 0.35rem;
    }

    .btn-decision {
        width: 100%;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .btn-decision.endorse {
        background: linear-gradient(135deg, var(--success) 0%, #10b981 100%);
        border: none;
        color: white;
    }

    .btn-decision.endorse:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(5, 150, 105, 0.4);
    }

    .btn-decision.reject {
        background: linear-gradient(135deg, var(--danger) 0%, #ef4444 100%);
        border: none;
        color: white;
    }

    .btn-decision.reject:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(220, 38, 38, 0.4);
    }

    /* Modal Styles */
    .modal-content {
        border-radius: 16px;
        border: none;
        overflow: hidden;
    }

    .modal-header.endorse {
        background: linear-gradient(135deg, var(--success) 0%, #10b981 100%);
        color: white;
        border: none;
    }

    .modal-header.reject {
        background: linear-gradient(135deg, var(--danger) 0%, #ef4444 100%);
        color: white;
        border: none;
    }

    .modal-header .btn-close {
        filter: invert(1);
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-alert {
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .modal-alert.warning {
        background: rgba(234, 88, 12, 0.1);
        border: 1px solid var(--warning);
        color: var(--warning);
    }

    .modal-alert.danger {
        background: rgba(220, 38, 38, 0.1);
        border: 1px solid var(--danger);
        color: var(--danger);
    }

    .modal-table {
        width: 100%;
        margin-bottom: 1.25rem;
    }

    .modal-table td {
        padding: 0.5rem;
        border: 1px solid #e2e8f0;
    }

    .modal-table td:first-child {
        background: var(--industrial-light);
        color: var(--industrial-gray);
        font-weight: 500;
        width: 150px;
    }

    .form-label {
        font-weight: 500;
        color: var(--industrial-dark);
        margin-bottom: 0.5rem;
    }

    .form-control {
        border-radius: 10px;
        border: 2px solid #e2e8f0;
        padding: 0.75rem 1rem;
        transition: all 0.2s ease;
    }

    .form-control:focus {
        border-color: var(--uitm-blue);
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
    }

    .form-check-input:checked {
        background-color: var(--success);
        border-color: var(--success);
    }

    .modal-footer {
        background: var(--industrial-light);
        border: none;
        padding: 1rem 1.5rem;
    }

    .btn-cancel {
        background: white;
        border: 2px solid var(--industrial-gray);
        color: var(--industrial-gray);
        padding: 0.625rem 1.25rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .btn-cancel:hover {
        background: var(--industrial-gray);
        color: white;
    }

    .btn-modal-action {
        padding: 0.625rem 1.25rem;
        border-radius: 10px;
        font-weight: 600;
        border: none;
        color: white;
        transition: all 0.2s ease;
    }

    .btn-modal-action.endorse {
        background: linear-gradient(135deg, var(--success) 0%, #10b981 100%);
    }

    .btn-modal-action.reject {
        background: linear-gradient(135deg, var(--danger) 0%, #ef4444 100%);
    }

    .btn-modal-action:hover {
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
            margin: -1rem -1rem 1.5rem -1rem;
            border-radius: 0 0 16px 16px;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="page-header">
        <h2><i class="fas fa-clipboard-check me-2"></i>Review Equivalency List</h2>
        <a href="{{ route('hea.equivalency_lists.pending') }}" class="btn btn-back">
            <i class="fas fa-arrow-left me-1"></i> Back to Pending
        </a>
    </div>

    @if(session('success'))
    <div class="alert-industrial success">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="alert-industrial danger">
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
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
                <small>
                    @if($list->isInternal())
                        Source: CS110 - Diploma in Computer Science (UiTM)
                    @else
                        Source: {{ $list->source_institution }} (Various Diploma Programs)
                    @endif
                </small>
            </div>
            <span class="status-badge-large">{{ $list->status_display }}</span>
        </div>
        <div class="list-info-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="info-table">
                        <tr>
                            <td>Target Program:</td>
                            <td><strong>{{ $list->program_code }}</strong> - {{ $list->program_name }}</td>
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
                    <table class="info-table">
                        <tr>
                            <td>Submitted By:</td>
                            <td>{{ $list->creator->name ?? 'Unknown' }}</td>
                        </tr>
                        <tr>
                            <td>Submitted On:</td>
                            <td style="font-family: 'IBM Plex Mono', monospace;">{{ $list->submitted_at?->format('d M Y, H:i') }}</td>
                        </tr>
                        @if($list->reviewer)
                        <tr>
                            <td>Reviewing:</td>
                            <td>{{ $list->reviewer->name }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            @if($list->submission_notes)
            <div class="info-alert {{ $list->isInternal() ? 'internal' : 'external' }}" style="background: rgba(245, 158, 11, 0.1); border-color: rgba(245, 158, 11, 0.3); color: #92400e;">
                <i class="fas fa-comment"></i>
                <div><strong>Submission Notes:</strong> {{ $list->submission_notes }}</div>
            </div>
            @endif

            <div class="info-alert {{ $list->isInternal() ? 'internal' : 'external' }}">
                <i class="fas fa-info-circle"></i>
                <div>
                    @if($list->isInternal())
                        <strong>Internal List:</strong> This list maps courses from UiTM's own CS110 Diploma in Computer Science program to the {{ $list->program_code }} degree program. CS110 students typically qualify for the most credit exemptions due to high curriculum similarity.
                    @else
                        <strong>External List:</strong> This list maps diploma courses from {{ $list->source_institution }} to the UiTM {{ $list->program_code }} degree program (external credit transfer).
                    @endif
                </div>
            </div>
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
        @if($changes)
        <div class="stat-card info">
            <div class="number">{{ $changes['added_count'] + $changes['modified_count'] + $changes['removed_count'] }}</div>
            <div class="label">Changes from Previous</div>
        </div>
        @endif
    </div>

    <!-- Changes from Previous Semester -->
    @if($changes && ($changes['added_count'] > 0 || $changes['modified_count'] > 0 || $changes['removed_count'] > 0))
    <div class="changes-card">
        <div class="changes-header">
            <h6><i class="fas fa-exchange-alt me-2"></i>Changes from Previous Semester ({{ $previousList->semester }})</h6>
        </div>
        <div class="changes-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="change-item">
                        <span class="change-badge added">+{{ $changes['added_count'] }}</span>
                        <span>New mappings added</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="change-item">
                        <span class="change-badge modified">~{{ $changes['modified_count'] }}</span>
                        <span>Match % updated</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="change-item">
                        <span class="change-badge removed">-{{ $changes['removed_count'] }}</span>
                        <span>Mappings removed</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

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
                        <th>{{ $list->isInternal() ? 'CS110' : $list->source_institution }} Diploma Course</th>
                        <th>{{ $list->program_code }} Degree Course</th>
                        <th class="text-center" style="width: 100px;">Match %</th>
                        <th class="text-center" style="width: 100px;">Eligible</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($list->courseEquivalencies as $index => $eq)
                    <tr>
                        <td class="text-muted">{{ $index + 1 }}</td>
                        <td>
                            <div class="course-code">{{ $eq->diploma_course_code }}</div>
                            <div class="course-details">{{ $eq->diploma_course_name }} ({{ $eq->diploma_credit_hour }} cr)</div>
                            @if($changes && in_array($eq, $changes['added']))
                                <span class="new-badge">NEW</span>
                            @endif
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

    <!-- Decision Section -->
    <div class="decision-card">
        <div class="decision-header">
            <h5><i class="fas fa-gavel me-2"></i>HEA Decision</h5>
        </div>
        <div class="decision-body">
            <div class="row g-4">
                <!-- Endorse & Publish -->
                <div class="col-md-6">
                    <div class="decision-option endorse">
                        <div class="decision-option-header">
                            <h6><i class="fas fa-check-circle me-2"></i>Endorse & Publish</h6>
                        </div>
                        <div class="decision-option-body">
                            <p class="text-muted mb-2" style="font-size: 0.9rem;">Endorsing this list will:</p>
                            <ul>
                                <li>Mark the list as officially endorsed by HEA</li>
                                <li>Publish it for student reference</li>
                                <li>Archive any previous active list for this program/source</li>
                            </ul>
                            <button type="button" class="btn-decision endorse" data-bs-toggle="modal" data-bs-target="#endorseModal">
                                <i class="fas fa-check me-2"></i>Endorse & Publish
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Reject & Return -->
                <div class="col-md-6">
                    <div class="decision-option reject">
                        <div class="decision-option-header">
                            <h6><i class="fas fa-times-circle me-2"></i>Reject & Request Changes</h6>
                        </div>
                        <div class="decision-option-body">
                            <p class="text-muted mb-2" style="font-size: 0.9rem;">Rejecting this list will:</p>
                            <ul>
                                <li>Return the list to the Resource Person</li>
                                <li>Require them to make revisions</li>
                                <li>Allow resubmission after corrections</li>
                            </ul>
                            <button type="button" class="btn-decision reject" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                <i class="fas fa-times me-2"></i>Reject & Request Changes
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Endorse Modal -->
<div class="modal fade" id="endorseModal" tabindex="-1" aria-labelledby="endorseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('hea.equivalency_lists.endorse', $list) }}" method="POST">
                @csrf
                <div class="modal-header endorse">
                    <h5 class="modal-title" id="endorseModalLabel">
                        <i class="fas fa-check-circle me-2"></i>Endorse & Publish Equivalency List
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="modal-alert warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <div>You are about to endorse and publish this equivalency list.</div>
                    </div>

                    <table class="modal-table">
                        <tr>
                            <td>Category:</td>
                            <td>{{ $list->isInternal() ? 'CS110 (UiTM Diploma)' : 'External ('.$list->source_institution.')' }}</td>
                        </tr>
                        <tr>
                            <td>Target Program:</td>
                            <td>{{ $list->program_code }} - {{ $list->program_name }}</td>
                        </tr>
                        <tr>
                            <td>Semester:</td>
                            <td>{{ $list->semester }}</td>
                        </tr>
                        <tr>
                            <td>Total Mappings:</td>
                            <td>{{ $list->total_equivalencies }}</td>
                        </tr>
                        <tr>
                            <td>Eligible Courses:</td>
                            <td>{{ $list->eligible_count }}</td>
                        </tr>
                    </table>

                    <div class="mb-3">
                        <label for="endorsement_notes" class="form-label">Endorsement Notes (Optional)</label>
                        <textarea name="endorsement_notes" id="endorsement_notes" class="form-control" rows="3" placeholder="Add any notes about this endorsement..."></textarea>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="confirm" id="confirmEndorse" value="1" required>
                        <label class="form-check-label" for="confirmEndorse">
                            I confirm that I have reviewed all equivalency mappings and endorse this list for publication.
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-modal-action endorse">
                        <i class="fas fa-check me-1"></i>Endorse & Publish Now
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('hea.equivalency_lists.reject', $list) }}" method="POST">
                @csrf
                <div class="modal-header reject">
                    <h5 class="modal-title" id="rejectModalLabel">
                        <i class="fas fa-times-circle me-2"></i>Reject & Request Changes
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="modal-alert danger">
                        <i class="fas fa-exclamation-triangle"></i>
                        <div>This list will be returned to the Resource Person for revision.</div>
                    </div>

                    <table class="modal-table">
                        <tr>
                            <td>Category:</td>
                            <td>{{ $list->isInternal() ? 'CS110 (UiTM Diploma)' : 'External ('.$list->source_institution.')' }}</td>
                        </tr>
                        <tr>
                            <td>Target Program:</td>
                            <td>{{ $list->program_code }}</td>
                        </tr>
                        <tr>
                            <td>Submitted By:</td>
                            <td>{{ $list->creator->name ?? 'Unknown' }}</td>
                        </tr>
                    </table>

                    <div class="mb-3">
                        <label for="review_notes" class="form-label">Reason for Rejection <span class="text-danger">*</span></label>
                        <textarea name="review_notes" id="review_notes" class="form-control" rows="4" required minlength="10" placeholder="Please explain what needs to be corrected or improved..."></textarea>
                        <small class="text-muted">Minimum 10 characters. This will be visible to the Resource Person.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-modal-action reject">
                        <i class="fas fa-times me-1"></i>Reject & Return
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
