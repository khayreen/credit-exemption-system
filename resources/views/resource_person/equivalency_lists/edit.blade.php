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
    }

    .page-header p {
        color: rgba(255,255,255,0.8);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .semester-badge {
        display: inline-block;
        background: rgba(255,255,255,0.2);
        padding: 0.25rem 0.75rem;
        border-radius: 6px;
        font-size: 0.85rem;
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
        border: none;
    }

    .btn-header.back {
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.3);
        color: white;
    }

    .btn-header.back:hover {
        background: rgba(255,255,255,0.25);
        color: white;
    }

    .btn-header.preview {
        background: var(--info);
        color: white;
    }

    .btn-header.preview:hover {
        background: #0f766e;
        color: white;
    }

    .btn-header.submit {
        background: var(--success);
        color: white;
    }

    .btn-header.submit:hover {
        background: #047857;
        color: white;
    }

    /* Alert Styles */
    .alert-industrial {
        border-radius: 12px;
        border: none;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
    }

    .alert-industrial.alert-success {
        background: rgba(5, 150, 105, 0.1);
        border-left: 4px solid var(--success);
        color: var(--success);
    }

    .alert-industrial.alert-danger {
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
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .list-info-header.internal {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--uitm-blue-light) 100%);
        color: white;
    }

    .list-info-header.external {
        background: linear-gradient(135deg, var(--success) 0%, #10b981 100%);
        color: white;
    }

    .list-info-header h5 {
        margin: 0;
        font-weight: 600;
    }

    .list-info-header small {
        opacity: 0.85;
    }

    .status-pill {
        display: inline-block;
        padding: 0.35rem 0.875rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .status-pill.draft {
        background: rgba(234, 88, 12, 0.2);
        color: #fff;
    }

    .list-info-body {
        padding: 1.5rem;
    }

    .stats-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        text-align: center;
    }

    .stat-item {
        padding: 1rem;
        border-right: 1px solid #e2e8f0;
    }

    .stat-item:last-child {
        border-right: none;
    }

    .stat-item h3 {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0;
    }

    .stat-item h3.primary { color: var(--uitm-blue); }
    .stat-item h3.success { color: var(--success); }
    .stat-item h3.danger { color: var(--danger); }
    .stat-item h3.info { color: var(--info); }

    .stat-item small {
        color: var(--industrial-gray);
        font-size: 0.85rem;
    }

    /* Add Mapping Form Card */
    .form-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .form-card-header {
        background: var(--industrial-light);
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .form-card-header h5 {
        margin: 0;
        font-weight: 600;
        color: var(--industrial-dark);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-card-body {
        padding: 1.5rem;
    }

    .section-title {
        color: var(--industrial-gray);
        font-weight: 600;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-label {
        font-weight: 600;
        color: var(--industrial-dark);
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .form-control, .form-select {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.65rem 1rem;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--uitm-blue);
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
    }

    .form-text {
        color: var(--industrial-gray);
        font-size: 0.85rem;
    }

    .arrow-separator {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: #cbd5e1;
    }

    .btn-add {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--uitm-blue-light) 100%);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
    }

    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(30, 58, 138, 0.3);
        color: white;
    }

    /* Mappings Table Card */
    .mappings-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        overflow: hidden;
    }

    .mappings-card-header {
        background: var(--industrial-light);
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .mappings-card-header h5 {
        margin: 0;
        font-weight: 600;
        color: var(--industrial-dark);
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

    .course-info {
        font-size: 0.85rem;
        color: var(--industrial-gray);
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

    .match-badge.danger {
        background: rgba(220, 38, 38, 0.1);
        color: var(--danger);
    }

    .eligible-icon {
        font-size: 1.25rem;
    }

    .eligible-icon.success { color: var(--success); }
    .eligible-icon.danger { color: var(--danger); }

    /* Action Buttons */
    .action-btn-group {
        display: flex;
        gap: 0.35rem;
        justify-content: flex-end;
    }

    .action-btn {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid;
        transition: all 0.2s ease;
        background: transparent;
    }

    .action-btn.edit {
        border-color: var(--uitm-blue);
        color: var(--uitm-blue);
    }

    .action-btn.edit:hover {
        background: var(--uitm-blue);
        color: white;
    }

    .action-btn.delete {
        border-color: var(--danger);
        color: var(--danger);
    }

    .action-btn.delete:hover {
        background: var(--danger);
        color: white;
    }

    /* Modal Styles */
    .modal-content {
        border: none;
        border-radius: 16px;
        overflow: hidden;
    }

    .modal-header {
        padding: 1.25rem 1.5rem;
    }

    .modal-header.success {
        background: linear-gradient(135deg, var(--success) 0%, #10b981 100%);
        color: white;
    }

    .modal-header .btn-close-white {
        filter: brightness(0) invert(1);
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid #e2e8f0;
    }

    .summary-card {
        background: var(--industrial-light);
        border-radius: 10px;
        padding: 1rem 1.25rem;
        margin-bottom: 1rem;
    }

    .summary-card p {
        margin: 0.25rem 0;
        color: var(--industrial-gray);
    }

    .summary-card strong {
        color: var(--industrial-dark);
    }

    .form-check-input:checked {
        background-color: var(--uitm-blue);
        border-color: var(--uitm-blue);
    }

    .btn-modal-cancel {
        background: var(--industrial-light);
        color: var(--industrial-gray);
        border: none;
        padding: 0.65rem 1.5rem;
        border-radius: 10px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn-modal-cancel:hover {
        background: #e2e8f0;
        color: var(--industrial-dark);
    }

    .btn-modal-submit {
        background: linear-gradient(135deg, var(--success) 0%, #10b981 100%);
        color: white;
        border: none;
        padding: 0.65rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
    }

    .btn-modal-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(5, 150, 105, 0.3);
        color: white;
    }

    .btn-modal-save {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--uitm-blue-light) 100%);
        color: white;
        border: none;
        padding: 0.65rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .btn-modal-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(30, 58, 138, 0.3);
        color: white;
    }

    /* Warning Alert */
    .alert-warning-industrial {
        background: rgba(234, 88, 12, 0.1);
        border: 1px solid rgba(234, 88, 12, 0.2);
        border-left: 4px solid var(--warning);
        border-radius: 10px;
        padding: 1rem 1.25rem;
        color: var(--warning);
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
            margin: -1rem -1rem 1.5rem -1rem;
            border-radius: 0 0 16px 16px;
        }

        .stats-row {
            grid-template-columns: repeat(2, 1fr);
        }

        .stat-item:nth-child(2) {
            border-right: none;
        }

        .stat-item:nth-child(3), .stat-item:nth-child(4) {
            border-top: 1px solid #e2e8f0;
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
                <h2><i class="fas fa-edit me-2"></i>Edit Equivalency List</h2>
                <p>
                    <span class="me-1">{{ $list->category_icon }}</span>
                    {{ $list->source_display }} &rarr; {{ $list->program_code }}
                    <span class="semester-badge">{{ $list->semester }}</span>
                </p>
            </div>
            <div class="header-actions">
                <a href="{{ route('resource_person.equivalency_lists.index') }}" class="btn-header back">
                    <i class="fas fa-arrow-left"></i> Back to Lists
                </a>
                <a href="{{ route('resource_person.equivalency_lists.preview', $list) }}" class="btn-header preview">
                    <i class="fas fa-eye"></i> Preview
                </a>
                @if($equivalencies->count() > 0)
                <button type="button" class="btn-header submit" data-bs-toggle="modal" data-bs-target="#submitModal">
                    <i class="fas fa-paper-plane"></i> Submit for Endorsement
                </button>
                @endif
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-industrial alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-industrial alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- List Info Card -->
    <div class="list-info-card">
        <div class="list-info-header {{ $list->isInternal() ? 'internal' : 'external' }}">
            <div class="d-flex align-items-center gap-3">
                <span style="font-size: 1.75rem;">{{ $list->category_icon }}</span>
                <div>
                    <h5>{{ $list->isInternal() ? 'CS110 (UiTM Diploma)' : $list->source_institution }}</h5>
                    <small>{{ $list->program_code }} - {{ $list->program_name }}</small>
                </div>
            </div>
            @include('resource_person.equivalency_lists.partials.status_badge', ['list' => $list])
        </div>
        <div class="list-info-body">
            <div class="stats-row">
                <div class="stat-item">
                    <h3 class="primary">{{ $list->total_equivalencies }}</h3>
                    <small>Total Mappings</small>
                </div>
                <div class="stat-item">
                    <h3 class="success">{{ $list->eligible_count }}</h3>
                    <small>Eligible (≥80%)</small>
                </div>
                <div class="stat-item">
                    <h3 class="danger">{{ $list->not_eligible_count }}</h3>
                    <small>Not Eligible (&lt;80%)</small>
                </div>
                <div class="stat-item">
                    <h3 class="info">{{ $list->total_equivalencies > 0 ? round(($list->eligible_count / $list->total_equivalencies) * 100, 1) : 0 }}%</h3>
                    <small>Eligibility Rate</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Add New Mapping Form -->
    <div class="form-card">
        <div class="form-card-header">
            <h5><i class="fas fa-plus-circle"></i>Add New Course Mapping</h5>
        </div>
        <div class="form-card-body">
            <form action="{{ route('resource_person.equivalency_lists.add_mapping', $list) }}" method="POST">
                @csrf
                <div class="row">
                    <!-- Diploma Course -->
                    <div class="col-md-5">
                        <h6 class="section-title">
                            <span>{{ $list->category_icon }}</span>
                            Diploma Course ({{ $list->isInternal() ? 'CS110' : $list->source_institution }})
                        </h6>
                        <div class="row">
                            <div class="col-md-5 mb-3">
                                <label class="form-label">Course Code <span class="text-danger">*</span></label>
                                <input type="text" name="diploma_course_code" class="form-control" placeholder="e.g., CSC118" required>
                            </div>
                            <div class="col-md-5 mb-3">
                                <label class="form-label">Course Name <span class="text-danger">*</span></label>
                                <input type="text" name="diploma_course_name" class="form-control" placeholder="e.g., Introduction to Computers" required>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Credits <span class="text-danger">*</span></label>
                                <input type="number" name="diploma_credit_hour" class="form-control" min="1" max="10" placeholder="3" required>
                            </div>
                        </div>
                    </div>

                    <!-- Arrow Separator -->
                    <div class="col-md-1 arrow-separator">
                        <i class="fas fa-arrow-right"></i>
                    </div>

                    <!-- Degree Course -->
                    <div class="col-md-5">
                        <h6 class="section-title">
                            <i class="fas fa-graduation-cap"></i>
                            Degree Course ({{ $list->program_code }})
                        </h6>
                        <div class="row">
                            <div class="col-md-5 mb-3">
                                <label class="form-label">Course Code <span class="text-danger">*</span></label>
                                <input type="text" name="degree_course_code" class="form-control" placeholder="e.g., CSC650" required>
                            </div>
                            <div class="col-md-5 mb-3">
                                <label class="form-label">Course Name <span class="text-danger">*</span></label>
                                <input type="text" name="degree_course_name" class="form-control" placeholder="e.g., Computer Architecture" required>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Credits <span class="text-danger">*</span></label>
                                <input type="number" name="degree_credit_hour" class="form-control" min="1" max="10" placeholder="3" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row align-items-end">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Match Percentage <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="match_percentage" class="form-control" min="0" max="100" step="0.1" placeholder="85" required>
                            <span class="input-group-text">%</span>
                        </div>
                        <small class="form-text">≥80% = Eligible for exemption</small>
                    </div>
                    <div class="col-md-9 mb-3">
                        <button type="submit" class="btn-add">
                            <i class="fas fa-plus"></i> Add Mapping
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Existing Mappings Table -->
    <div class="mappings-card">
        <div class="mappings-card-header">
            <h5><i class="fas fa-list"></i>Course Mappings ({{ $equivalencies->count() }})</h5>
        </div>

        @if($equivalencies->isEmpty())
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h5>No Course Mappings Yet</h5>
                <p>Use the form above to add course mappings to this list.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="mappings-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Diploma Course</th>
                            <th></th>
                            <th>Degree Course</th>
                            <th class="text-center">Match %</th>
                            <th class="text-center">Eligible</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($equivalencies as $index => $mapping)
                        <tr id="mapping-{{ $mapping->id }}">
                            <td class="text-muted">{{ $index + 1 }}</td>
                            <td>
                                <span class="course-code">{{ $mapping->diploma_course_code }}</span>
                                <div class="course-info">{{ Str::limit($mapping->diploma_course_name, 30) }}</div>
                                <div class="course-info">{{ $mapping->diploma_credit_hour }} credits</div>
                            </td>
                            <td class="text-center">
                                <i class="fas fa-arrow-right text-muted"></i>
                            </td>
                            <td>
                                <span class="course-code">{{ $mapping->degree_course_code }}</span>
                                <div class="course-info">{{ Str::limit($mapping->degree_course_name, 30) }}</div>
                                <div class="course-info">{{ $mapping->degree_credit_hour }} credits</div>
                            </td>
                            <td class="text-center">
                                <span class="match-badge {{ $mapping->match_percentage >= 80 ? 'success' : 'danger' }}">
                                    {{ $mapping->match_percentage }}%
                                </span>
                            </td>
                            <td class="text-center">
                                @if($mapping->is_eligible)
                                    <i class="fas fa-check-circle eligible-icon success" title="Eligible"></i>
                                @else
                                    <i class="fas fa-times-circle eligible-icon danger" title="Not Eligible"></i>
                                @endif
                            </td>
                            <td>
                                <div class="action-btn-group">
                                    <button type="button" class="action-btn edit" data-bs-toggle="modal" data-bs-target="#editModal-{{ $mapping->id }}" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('resource_person.equivalency_lists.delete_mapping', [$list, $mapping]) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this mapping?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn delete" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Edit Modal for this mapping -->
                        <div class="modal fade" id="editModal-{{ $mapping->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form action="{{ route('resource_person.equivalency_lists.update_mapping', [$list, $mapping]) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header" style="background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--uitm-blue-light) 100%); color: white;">
                                            <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Course Mapping</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6 class="section-title">Diploma Course</h6>
                                                    <div class="mb-3">
                                                        <label class="form-label">Course Code</label>
                                                        <input type="text" name="diploma_course_code" class="form-control" value="{{ $mapping->diploma_course_code }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Course Name</label>
                                                        <input type="text" name="diploma_course_name" class="form-control" value="{{ $mapping->diploma_course_name }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Credits</label>
                                                        <input type="number" name="diploma_credit_hour" class="form-control" value="{{ $mapping->diploma_credit_hour }}" min="1" max="10" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6 class="section-title">Degree Course</h6>
                                                    <div class="mb-3">
                                                        <label class="form-label">Course Code</label>
                                                        <input type="text" name="degree_course_code" class="form-control" value="{{ $mapping->degree_course_code }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Course Name</label>
                                                        <input type="text" name="degree_course_name" class="form-control" value="{{ $mapping->degree_course_name }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Credits</label>
                                                        <input type="number" name="degree_credit_hour" class="form-control" value="{{ $mapping->degree_credit_hour }}" min="1" max="10" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="form-label">Match Percentage</label>
                                                    <div class="input-group">
                                                        <input type="number" name="match_percentage" class="form-control" value="{{ $mapping->match_percentage }}" min="0" max="100" step="0.1" required>
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn-modal-save">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<!-- Submit Modal -->
<div class="modal fade" id="submitModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('resource_person.equivalency_lists.submit', $list) }}" method="POST">
                @csrf
                <div class="modal-header success">
                    <h5 class="modal-title"><i class="fas fa-paper-plane me-2"></i>Submit for HEA Endorsement</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert-warning-industrial mb-3">
                        <i class="fas fa-exclamation-triangle"></i>
                        <div>
                            <strong>Important:</strong> Once submitted, you cannot edit this list until HEA reviews it.
                        </div>
                    </div>

                    <div class="summary-card">
                        <p><strong>Category:</strong> {{ $list->category_icon }} {{ $list->source_display }}</p>
                        <p><strong>Target:</strong> {{ $list->program_code }} - {{ $list->program_name }}</p>
                        <p><strong>Semester:</strong> {{ $list->semester }}</p>
                        <p><strong>Total Mappings:</strong> {{ $list->total_equivalencies }}</p>
                        <p><strong>Eligible:</strong> {{ $list->eligible_count }} | <strong>Not Eligible:</strong> {{ $list->not_eligible_count }}</p>
                    </div>

                    <div class="mb-3">
                        <label for="submission_notes" class="form-label">Submission Notes (Optional)</label>
                        <textarea name="submission_notes" id="submission_notes" class="form-control" rows="3" placeholder="Any notes for HEA reviewer..."></textarea>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" name="confirm" id="confirm" class="form-check-input" value="1" required>
                        <label for="confirm" class="form-check-label">
                            I confirm that all equivalency mappings have been reviewed and are accurate for the selected semester.
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-modal-submit">
                        <i class="fas fa-paper-plane"></i> Submit for Endorsement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
