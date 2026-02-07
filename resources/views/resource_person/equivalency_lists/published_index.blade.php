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
        border-radius: 12px;
        padding: 1.75rem;
        margin-bottom: 1.5rem;
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
        background: linear-gradient(135deg, transparent 0%, rgba(245,158,11,0.1) 100%);
        pointer-events: none;
    }

    .page-header-icon {
        width: 56px;
        height: 56px;
        background: rgba(255,255,255,0.15);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        border: 1px solid rgba(255,255,255,0.2);
    }

    .page-header h1 {
        font-size: 1.5rem;
        font-weight: 700;
        color: white;
        margin: 0;
        letter-spacing: -0.025em;
    }

    .page-header p {
        color: rgba(255,255,255,0.85);
        margin: 0.35rem 0 0 0;
        font-size: 0.9rem;
    }

    /* Program Tabs */
    .program-tabs {
        background: white;
        border-radius: 12px;
        padding: 0.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        margin-bottom: 1.5rem;
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .program-tab {
        padding: 0.75rem 1.25rem;
        border-radius: 8px;
        border: none;
        background: transparent;
        color: var(--industrial-gray);
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .program-tab:hover {
        background: var(--industrial-light);
        color: var(--uitm-blue);
    }

    .program-tab.active {
        background: var(--uitm-blue);
        color: white;
    }

    .program-tab.assigned {
        border: 2px solid var(--uitm-amber);
    }

    .program-tab.assigned.active {
        border-color: transparent;
    }

    /* Info Card */
    .info-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .info-card-header {
        background: var(--industrial-light);
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: between;
        align-items: center;
    }

    .info-card-header h6 {
        font-weight: 600;
        color: var(--industrial-dark);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-card-body {
        padding: 1.25rem;
    }

    /* Mappings Table */
    .mappings-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .mappings-header {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, #1e40af 100%);
        padding: 1rem 1.25rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .mappings-header h6 {
        color: white;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .mappings-table {
        margin: 0;
    }

    .mappings-table thead th {
        background: var(--industrial-light);
        font-weight: 600;
        color: var(--industrial-dark);
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0.875rem 1rem;
        border-bottom: 2px solid #e2e8f0;
        white-space: nowrap;
    }

    .mappings-table tbody td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .mappings-table tbody tr:hover {
        background: #fafbfc;
    }

    .course-code {
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 600;
        color: var(--uitm-blue);
    }

    .course-name {
        color: var(--industrial-gray);
        font-size: 0.85rem;
    }

    .match-badge {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.85rem;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
    }

    .match-badge.high {
        background: rgba(5,150,105,0.1);
        color: var(--success);
    }

    .match-badge.medium {
        background: rgba(245,158,11,0.1);
        color: var(--warning);
    }

    /* Stats Footer */
    .stats-footer {
        background: var(--industrial-light);
        padding: 1rem 1.25rem;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: center;
        gap: 3rem;
    }

    .stat-item {
        text-align: center;
    }

    .stat-item span {
        font-weight: 500;
        color: var(--industrial-gray);
        font-size: 0.875rem;
    }

    .stat-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        height: 24px;
        border-radius: 12px;
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.8rem;
        font-weight: 600;
        margin-left: 0.5rem;
    }

    /* Status Badges */
    .status-badge {
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .status-draft { background: rgba(51,65,85,0.1); color: var(--industrial-gray); }
    .status-submitted { background: rgba(59,130,246,0.1); color: var(--uitm-blue-light); }
    .status-under-review { background: rgba(13,148,136,0.1); color: var(--info); }
    .status-endorsed { background: rgba(5,150,105,0.1); color: var(--success); }
    .status-published { background: rgba(5,150,105,0.15); color: var(--success); }
    .status-rejected { background: rgba(220,38,38,0.1); color: var(--danger); }

    /* History Card */
    .history-card {
        background: white;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        margin-bottom: 1rem;
        overflow: hidden;
    }

    .history-card-body {
        padding: 1.25rem;
    }

    /* Empty State */
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
    }

    .empty-state i {
        font-size: 4rem;
        color: #cbd5e1;
        margin-bottom: 1rem;
    }

    .empty-state h5 {
        color: var(--industrial-gray);
        font-weight: 600;
    }

    .empty-state p {
        color: #94a3b8;
    }

    /* Rejection Alert */
    .rejection-alert {
        background: rgba(220,38,38,0.05);
        border: 1px solid rgba(220,38,38,0.2);
        border-left: 4px solid var(--danger);
        border-radius: 8px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
    }

    .rejection-alert h6 {
        color: var(--danger);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    /* Assigned Badge */
    .assigned-badge {
        background: linear-gradient(135deg, var(--uitm-amber) 0%, #d97706 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Action Buttons */
    .btn-industrial {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .btn-primary-industrial {
        background: var(--uitm-blue);
        color: white;
        border: none;
    }

    .btn-primary-industrial:hover {
        background: #1e40af;
        color: white;
        transform: translateY(-1px);
    }

    .btn-warning-industrial {
        background: var(--uitm-amber);
        color: white;
        border: none;
    }

    .btn-warning-industrial:hover {
        background: #d97706;
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

    .btn-danger-industrial {
        background: var(--danger);
        color: white;
        border: none;
    }

    .btn-danger-industrial:hover {
        background: #b91c1c;
        color: white;
    }

    /* Modal Styling */
    .modal-industrial .modal-header {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, #1e40af 100%);
        color: white;
        border: none;
        padding: 1.25rem 1.5rem;
    }

    .modal-industrial .modal-title {
        font-weight: 600;
    }

    .modal-industrial.modal-success .modal-header {
        background: linear-gradient(135deg, var(--success) 0%, #047857 100%);
    }

    .modal-industrial .modal-body {
        padding: 1.5rem;
    }

    .modal-industrial .modal-footer {
        border-top: 1px solid #e2e8f0;
        padding: 1rem 1.5rem;
    }

    /* Info Box */
    .info-box {
        background: rgba(59,130,246,0.08);
        border: 1px solid rgba(59,130,246,0.2);
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1.25rem;
    }

    .info-box i { color: var(--uitm-blue-light); }

    /* Table Info */
    .table-info {
        font-size: 0.9rem;
    }

    .table-info th {
        color: var(--industrial-gray);
        font-weight: 500;
        width: 140px;
        padding: 0.5rem 0;
    }

    .table-info td {
        padding: 0.5rem 0;
    }

    /* History Table */
    .history-table {
        font-size: 0.875rem;
    }

    .history-table thead th {
        background: var(--industrial-light);
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .action-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.7rem;
        font-weight: 600;
    }

    .action-badge.deleted { background: rgba(220,38,38,0.1); color: var(--danger); }
    .action-badge.replaced { background: rgba(245,158,11,0.1); color: var(--warning); }

    /* Program Name & Status Header */
    .program-status-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .program-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--uitm-blue);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .status-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 1.25rem;
        }

        .page-header h1 {
            font-size: 1.25rem;
        }

        .program-tabs {
            flex-direction: column;
        }

        .program-tab {
            width: 100%;
            justify-content: center;
        }

        .stats-footer {
            flex-direction: column;
            gap: 1rem;
        }

        .program-status-header {
            flex-direction: column;
        }

        .action-buttons {
            width: 100%;
        }

        .action-buttons .btn {
            flex: 1;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex align-items-center gap-3 flex-wrap">
            <div class="page-header-icon">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <div class="flex-grow-1">
                <h1>CS110 EQUIVALENCY LISTS</h1>
                <p>
                    View and manage course equivalency lists organized by degree program
                    @if(!empty($assignedPrograms))
                        <br><span class="d-inline-flex align-items-center gap-2 mt-1">
                            <i class="fas fa-star text-warning"></i>Your assigned:
                            @foreach($assignedPrograms as $program)
                                <span class="badge bg-white text-primary fw-bold">{{ $program }}</span>
                            @endforeach
                        </span>
                    @endif
                </p>
            </div>
            <div>
                <a href="{{ route('resource_person.course_equivalencies.view') }}" class="btn btn-light btn-industrial">
                    <i class="fas fa-search me-2"></i>All Course Mappings
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" style="border-left: 4px solid var(--success) !important;">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" style="border-left: 4px solid var(--danger) !important;">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Program Tabs -->
    <div class="program-tabs" role="tablist">
        @foreach($programs as $index => $programCode)
        <button class="program-tab {{ $defaultProgram === $programCode ? 'active' : '' }} {{ in_array($programCode, $assignedPrograms) ? 'assigned' : '' }}"
                id="{{ strtolower($programCode) }}-tab"
                data-bs-toggle="tab"
                data-bs-target="#{{ strtolower($programCode) }}"
                type="button"
                role="tab">
            @if(in_array($programCode, $assignedPrograms))
                <i class="fas fa-star text-warning" title="Your assigned program"></i>
            @endif
            <span class="font-mono">{{ $programCode }}</span>
        </button>
        @endforeach
    </div>

    <div class="tab-content" id="programTabsContent">
        @foreach($programs as $index => $programCode)
        <div class="tab-pane fade {{ $defaultProgram === $programCode ? 'show active' : '' }}"
             id="{{ strtolower($programCode) }}"
             role="tabpanel">

            <!-- Program Name & Status -->
            <div class="program-status-header">
                <div>
                    <h4 class="program-title">
                        <i class="fas fa-graduation-cap"></i>{{ $programData[$programCode]['name'] }}
                    </h4>
                    @if($programData[$programCode]['current'])
                        @php $list = $programData[$programCode]['current']; @endphp
                        <div class="status-badges">
                            <span class="status-badge status-{{ str_replace('_', '-', $list->status) }}">
                                {{ $list->status_display ?? ucfirst($list->status) }}
                            </span>
                            @if($list->target_semester)
                                <span class="status-badge" style="background: rgba(245,158,11,0.1); color: var(--warning);">
                                    Target: {{ $list->target_semester }}
                                </span>
                            @endif
                            @if($list->semester)
                                <span class="status-badge status-published">
                                    Last Published: {{ $list->semester }}
                                </span>
                            @endif
                            @if($list->is_active)
                                <span class="status-badge" style="background: rgba(13,148,136,0.1); color: var(--info);">
                                    Active
                                </span>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="action-buttons">
                    @if($programData[$programCode]['isAssigned'])
                        @if($programData[$programCode]['current'])
                            @if($programData[$programCode]['canEdit'])
                                <a href="{{ route('resource_person.equivalency_lists.edit', $programCode) }}" class="btn btn-warning-industrial btn-industrial">
                                    <i class="fas fa-edit me-1"></i>Edit List
                                </a>
                            @endif
                            @if($programData[$programCode]['canSubmit'])
                                <button type="button" class="btn btn-primary-industrial btn-industrial"
                                        data-bs-toggle="modal"
                                        data-bs-target="#submitModal"
                                        onclick="setSubmitProgram('{{ $programCode }}', {{ $programData[$programCode]['current']->courseEquivalencies->count() ?? 0 }})">
                                    <i class="fas fa-paper-plane me-1"></i>Submit to HEA
                                </button>
                            @endif
                        @else
                            <a href="{{ route('resource_person.equivalency_lists.edit', $programCode) }}" class="btn btn-success-industrial btn-industrial">
                                <i class="fas fa-plus-circle me-1"></i>Create List
                            </a>
                        @endif
                    @endif
                    @if($programData[$programCode]['current'] && $programData[$programCode]['current']->status === 'published')
                        <a href="{{ route('resource_person.equivalency_lists.pdf', $programData[$programCode]['current']) }}"
                           class="btn btn-danger-industrial btn-industrial"
                           target="_blank">
                            <i class="fas fa-file-pdf me-1"></i>View as PDF
                        </a>
                    @endif
                </div>
            </div>

            @if($programData[$programCode]['current'])
                @php $list = $programData[$programCode]['current']; @endphp

                <!-- Rejection Alert -->
                @if($list->status === 'rejected')
                    <div class="rejection-alert">
                        <h6><i class="fas fa-times-circle me-2"></i>This list was rejected by HEA</h6>
                        <p class="mb-0"><strong>Reason:</strong> {{ $list->review_notes }}</p>
                        <small class="text-muted">Please make the necessary changes and resubmit.</small>
                    </div>
                @endif

                <!-- List Information Card -->
                <div class="info-card">
                    <div class="info-card-header">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <h6><i class="fas fa-info-circle"></i>List Information</h6>
                            @if($programData[$programCode]['isAssigned'])
                                <span class="assigned-badge">
                                    <i class="fas fa-star"></i>YOUR ASSIGNED PROGRAM
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="info-card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless table-info mb-0">
                                    <tr>
                                        <th>Program Code:</th>
                                        <td><span class="badge bg-primary font-mono">{{ $list->program_code }}</span></td>
                                    </tr>
                                    <tr>
                                        <th>Program Name:</th>
                                        <td>{{ $list->program_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Category:</th>
                                        <td><span class="badge" style="background: var(--uitm-blue);">Internal (CS110)</span></td>
                                    </tr>
                                    <tr>
                                        <th>Semester:</th>
                                        <td>{{ $list->semester ?? 'Not published yet' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless table-info mb-0">
                                    <tr>
                                        <th>Status:</th>
                                        <td><span class="status-badge status-{{ str_replace('_', '-', $list->status) }}">{{ $list->status_display ?? ucfirst($list->status) }}</span></td>
                                    </tr>
                                    <tr>
                                        <th>Total Mappings:</th>
                                        <td><strong class="font-mono">{{ $list->courseEquivalencies->count() }}</strong> course(s)</td>
                                    </tr>
                                    <tr>
                                        <th>Created By:</th>
                                        <td>{{ $list->creator->name ?? 'N/A' }}</td>
                                    </tr>
                                    @if($list->published_at)
                                        <tr>
                                            <th>Published At:</th>
                                            <td>{{ $list->published_at->format('d M Y, h:i A') }}</td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Course Mappings -->
                <div class="mappings-card">
                    <div class="mappings-header">
                        <h6><i class="fas fa-exchange-alt me-2"></i>Course Equivalency Mappings ({{ $list->courseEquivalencies->count() }})</h6>
                        @if($programData[$programCode]['isAssigned'] && $programData[$programCode]['canEditInline'])
                            <button type="button" class="btn btn-light btn-sm btn-industrial"
                                    data-bs-toggle="modal"
                                    data-bs-target="#addMappingModal"
                                    onclick="setAddMappingProgram('{{ $programCode }}')">
                                <i class="fas fa-plus-circle me-1"></i>Add Mapping
                            </button>
                        @endif
                    </div>
                    @if($list->courseEquivalencies->isEmpty())
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <h5>No Course Mappings</h5>
                            <p class="mb-0">This equivalency list does not have any course mappings yet.</p>
                            @if($programData[$programCode]['isAssigned'] && $programData[$programCode]['canEditInline'])
                                <button type="button" class="btn btn-success-industrial btn-industrial mt-3"
                                        data-bs-toggle="modal"
                                        data-bs-target="#addMappingModal"
                                        onclick="setAddMappingProgram('{{ $programCode }}')">
                                    <i class="fas fa-plus-circle me-1"></i>Add First Mapping
                                </button>
                            @elseif($programData[$programCode]['isAssigned'] && $programData[$programCode]['canEdit'])
                                <p class="text-muted mt-3">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Click <strong>"Edit List"</strong> button above to add mappings.
                                </p>
                            @endif
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table mappings-table">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="5%">#</th>
                                        <th width="30%">Diploma Course</th>
                                        <th width="30%">Degree Course</th>
                                        <th class="text-center" width="10%">Match %</th>
                                        <th class="text-center" width="10%">Credit Hours</th>
                                        <th class="text-center" width="5%">Eligible</th>
                                        @if($programData[$programCode]['isAssigned'] && $programData[$programCode]['canEditInline'])
                                            <th class="text-center" width="10%">Actions</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($list->courseEquivalencies->sortBy('diploma_course_code') as $idx => $eq)
                                    <tr>
                                        <td class="text-center text-muted font-mono">{{ $idx + 1 }}</td>
                                        <td>
                                            <span class="course-code">{{ $eq->diploma_course_code }}</span>
                                            <br>
                                            <span class="course-name">{{ $eq->diploma_course_name }}</span>
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-university me-1"></i>{{ $eq->diploma_institution ?? 'CS110' }}
                                            </small>
                                        </td>
                                        <td>
                                            <span class="course-code">{{ $eq->degree_course_code }}</span>
                                            <br>
                                            <span class="course-name">{{ $eq->degree_course_name }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="match-badge {{ $eq->match_percentage >= 80 ? 'high' : 'medium' }}">
                                                {{ number_format($eq->match_percentage, 0) }}%
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="font-mono text-muted">
                                                {{ $eq->diploma_credit_hour }} <i class="fas fa-arrow-right mx-1"></i> {{ $eq->degree_credit_hour }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if($eq->is_eligible)
                                                <i class="fas fa-check-circle text-success fs-5"></i>
                                            @else
                                                <i class="fas fa-times-circle text-danger fs-5"></i>
                                            @endif
                                        </td>
                                        @if($programData[$programCode]['isAssigned'] && $programData[$programCode]['canEditInline'])
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-primary-industrial me-1" onclick="editMapping('{{ $programCode }}', '{{ $eq->id }}', {{ json_encode($eq) }})">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger-industrial" onclick="deleteMapping('{{ $programCode }}', '{{ $eq->id }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Statistics Footer -->
                        <div class="stats-footer">
                            <div class="stat-item">
                                <span>Total Mappings:</span>
                                <span class="stat-badge" style="background: var(--uitm-blue); color: white;">{{ $list->courseEquivalencies->count() }}</span>
                            </div>
                            <div class="stat-item">
                                <span>Eligible (>=80%):</span>
                                <span class="stat-badge" style="background: var(--success); color: white;">{{ $list->courseEquivalencies->where('is_eligible', true)->count() }}</span>
                            </div>
                            <div class="stat-item">
                                <span>Not Eligible (<80%):</span>
                                <span class="stat-badge" style="background: var(--uitm-amber); color: white;">{{ $list->courseEquivalencies->where('is_eligible', false)->count() }}</span>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Course Mapping History -->
                @if($programData[$programCode]['isAssigned'] && $list->courseEquivalencyHistory && $list->courseEquivalencyHistory->count() > 0)
                <div class="info-card mt-4">
                    <div class="info-card-header" style="background: var(--industrial-gray);">
                        <h6 style="color: white;"><i class="fas fa-history me-2"></i>Course Mapping History ({{ $list->courseEquivalencyHistory->count() }})</h6>
                    </div>
                    <div class="info-card-body">
                        <p class="text-muted mb-3">
                            <i class="fas fa-info-circle me-1"></i>
                            Previously deleted or replaced course mappings are archived here for record-keeping.
                        </p>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover history-table">
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>Diploma Course</th>
                                        <th>Degree Course</th>
                                        <th>Match %</th>
                                        <th>Archived By</th>
                                        <th>Archived At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($list->courseEquivalencyHistory->sortByDesc('archived_at') as $history)
                                    <tr>
                                        <td>
                                            <span class="action-badge {{ $history->action }}">
                                                {{ ucfirst($history->action) }}
                                            </span>
                                        </td>
                                        <td>
                                            <strong class="font-mono">{{ $history->diploma_course_code }}</strong><br>
                                            <small class="text-muted">{{ Str::limit($history->diploma_course_name, 30) }}</small>
                                        </td>
                                        <td>
                                            <strong class="font-mono">{{ $history->degree_course_code }}</strong><br>
                                            <small class="text-muted">{{ Str::limit($history->degree_course_name, 30) }}</small>
                                        </td>
                                        <td class="font-mono">{{ number_format($history->match_percentage, 0) }}%</td>
                                        <td>{{ $history->archivedBy->name ?? 'System' }}</td>
                                        <td>{{ $history->archived_at->format('d M Y H:i') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                <!-- History -->
                @if($programData[$programCode]['history']->isNotEmpty())
                <div class="mt-4">
                    <h5 class="mb-3" style="color: var(--industrial-dark); font-weight: 600;">
                        <i class="fas fa-history me-2"></i>Previous Published Lists
                    </h5>
                    @foreach($programData[$programCode]['history'] as $historyList)
                    <div class="history-card">
                        <div class="history-card-body">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h6 class="mb-2" style="color: var(--industrial-dark);">
                                        <i class="fas fa-calendar-alt me-2 text-muted"></i>{{ $historyList->semester }}
                                    </h6>
                                    <p class="mb-1">
                                        <span class="badge" style="background: var(--uitm-blue);">UiTM CS110</span>
                                        <span class="badge bg-secondary ms-2">
                                            <i class="fas fa-book me-1"></i>{{ $historyList->courseEquivalencies->count() }} courses
                                        </span>
                                    </p>
                                    <p class="text-muted mb-0">
                                        <small>
                                            <i class="fas fa-user me-1"></i>Published by {{ $historyList->publisher->name ?? 'N/A' }}
                                            on {{ $historyList->published_at ? $historyList->published_at->format('d M Y') : 'N/A' }}
                                        </small>
                                    </p>
                                </div>
                                <div class="col-md-6 text-end">
                                    <span class="status-badge" style="background: var(--industrial-gray); color: white;">
                                        <i class="fas fa-archive me-1"></i>ARCHIVED
                                    </span>
                                    <a href="{{ route('resource_person.equivalency_lists.show', $historyList) }}"
                                       class="btn btn-outline-secondary btn-industrial ms-2">
                                        <i class="fas fa-eye me-1"></i>View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

            @else
                <!-- No list exists yet -->
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h5>No Equivalency List Yet</h5>
                    <p>No equivalency list has been created for this program yet.</p>
                    @if($programData[$programCode]['isAssigned'])
                        <a href="{{ route('resource_person.equivalency_lists.edit', $programCode) }}" class="btn btn-success-industrial btn-industrial mt-2">
                            <i class="fas fa-plus-circle me-1"></i>Create List for {{ $programCode }}
                        </a>
                    @endif
                </div>
            @endif
        </div>
        @endforeach
    </div>
</div>

<!-- Add Mapping Modal -->
<div class="modal fade modal-industrial modal-success" id="addMappingModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Add Course Mapping</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="addMappingForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="info-box">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>CS110 Internal Mapping:</strong> Map CS110 diploma courses to <span id="addMappingProgramDisplay" class="font-mono fw-bold"></span> degree courses
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h6 style="color: var(--uitm-blue); font-weight: 600;" class="mb-3">CS110 Diploma Course</h6>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Course Code <span class="text-danger">*</span></label>
                                <input type="text" name="diploma_course_code" class="form-control" placeholder="CSC126" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Course Name <span class="text-danger">*</span></label>
                                <input type="text" name="diploma_course_name" class="form-control" placeholder="Fundamentals of Algorithms" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Credit Hours <span class="text-danger">*</span></label>
                                <input type="number" name="diploma_credit_hour" class="form-control font-mono" min="1" max="10" value="3" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h6 style="color: var(--success); font-weight: 600;" class="mb-3"><span id="addMappingDegreeLabel"></span> Degree Course</h6>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Course Code <span class="text-danger">*</span></label>
                                <input type="text" name="degree_course_code" class="form-control" placeholder="CSC402" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Course Name <span class="text-danger">*</span></label>
                                <input type="text" name="degree_course_name" class="form-control" placeholder="Programming I" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Credit Hours <span class="text-danger">*</span></label>
                                <input type="number" name="degree_credit_hour" class="form-control font-mono" min="1" max="10" value="3" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-medium">Match Percentage <span class="text-danger">*</span></label>
                                <input type="number" name="match_percentage" class="form-control font-mono" min="0" max="100" value="85" required>
                                <small class="text-muted">Recommended: >=80% for eligibility</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-medium">Eligible for Exemption</label>
                                <select name="is_eligible" class="form-select" required>
                                    <option value="1">Yes - Eligible</option>
                                    <option value="0">No - Not Eligible</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success-industrial"><i class="fas fa-plus-circle me-1"></i>Add Mapping</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Mapping Modal -->
<div class="modal fade modal-industrial" id="editMappingModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Course Mapping</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editMappingForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="info-box">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Update CS110 Mapping:</strong> Modify the course equivalency details
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h6 style="color: var(--uitm-blue); font-weight: 600;" class="mb-3">CS110 Diploma Course</h6>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Course Code <span class="text-danger">*</span></label>
                                <input type="text" id="edit_diploma_course_code" name="diploma_course_code" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Course Name <span class="text-danger">*</span></label>
                                <input type="text" id="edit_diploma_course_name" name="diploma_course_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Credit Hours <span class="text-danger">*</span></label>
                                <input type="number" id="edit_diploma_credit_hour" name="diploma_credit_hour" class="form-control font-mono" min="1" max="10" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h6 style="color: var(--success); font-weight: 600;" class="mb-3"><span id="editMappingDegreeLabel"></span> Degree Course</h6>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Course Code <span class="text-danger">*</span></label>
                                <input type="text" id="edit_degree_course_code" name="degree_course_code" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Course Name <span class="text-danger">*</span></label>
                                <input type="text" id="edit_degree_course_name" name="degree_course_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Credit Hours <span class="text-danger">*</span></label>
                                <input type="number" id="edit_degree_credit_hour" name="degree_credit_hour" class="form-control font-mono" min="1" max="10" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-medium">Match Percentage <span class="text-danger">*</span></label>
                                <input type="number" id="edit_match_percentage" name="match_percentage" class="form-control font-mono" min="0" max="100" required>
                                <small class="text-muted">Recommended: >=80% for eligibility</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-medium">Eligible for Exemption</label>
                                <select id="edit_is_eligible" name="is_eligible" class="form-select" required>
                                    <option value="1">Yes - Eligible</option>
                                    <option value="0">No - Not Eligible</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary-industrial"><i class="fas fa-save me-1"></i>Update Mapping</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Submit to HEA Modal -->
<div class="modal fade modal-industrial" id="submitModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-paper-plane me-2"></i>Submit to HEA</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="submitForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="info-box">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong><span id="submitMappingCount" class="font-mono">0</span> course mappings</strong> will be submitted to HEA for review and endorsement.
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Target Semester <span class="text-danger">*</span></label>
                        <input type="text" name="target_semester" class="form-control" placeholder="e.g., 2025/2026-1" required>
                        <small class="text-muted">Format: Academic Year - Semester (e.g., 2025/2026-1 for Semester 1 of 2025/2026)</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Submission Notes (Optional)</label>
                        <textarea name="submission_notes" class="form-control" rows="3" placeholder="Add any notes or comments for HEA review..."></textarea>
                    </div>

                    <p class="mb-0 fw-medium">After submission:</p>
                    <ol class="mb-0 text-muted">
                        <li>HEA will review your mappings for <strong class="text-primary">target semester</strong></li>
                        <li>HEA will endorse or reject the list</li>
                        <li>If endorsed, HEA will publish as PDF for the specified semester</li>
                        <li>After publication, list reverts to DRAFT for next semester's updates</li>
                    </ol>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary-industrial">
                        <i class="fas fa-paper-plane me-1"></i>Submit to HEA
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Form (Hidden) -->
<form id="deleteMappingForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
function setAddMappingProgram(programCode) {
    document.getElementById('addMappingForm').action = `/resource-person/cs110-lists/${programCode}/mappings`;
    document.getElementById('addMappingProgramDisplay').textContent = programCode;
    document.getElementById('addMappingDegreeLabel').textContent = programCode;
}

function editMapping(programCode, mappingId, mapping) {
    document.getElementById('edit_diploma_course_code').value = mapping.diploma_course_code;
    document.getElementById('edit_diploma_course_name').value = mapping.diploma_course_name;
    document.getElementById('edit_diploma_credit_hour').value = mapping.diploma_credit_hour;
    document.getElementById('edit_degree_course_code').value = mapping.degree_course_code;
    document.getElementById('edit_degree_course_name').value = mapping.degree_course_name;
    document.getElementById('edit_degree_credit_hour').value = mapping.degree_credit_hour;
    document.getElementById('edit_match_percentage').value = mapping.match_percentage;
    document.getElementById('edit_is_eligible').value = mapping.is_eligible ? '1' : '0';
    document.getElementById('editMappingDegreeLabel').textContent = programCode;
    document.getElementById('editMappingForm').action = `/resource-person/cs110-lists/${programCode}/mappings/${mappingId}`;

    const editModal = new bootstrap.Modal(document.getElementById('editMappingModal'));
    editModal.show();
}

function deleteMapping(programCode, mappingId) {
    if (confirm('Are you sure you want to delete this course mapping?\n\nThis mapping will be moved to history and can be viewed later.')) {
        const form = document.getElementById('deleteMappingForm');
        form.action = `/resource-person/cs110-lists/${programCode}/mappings/${mappingId}`;
        form.submit();
    }
}

function setSubmitProgram(programCode, mappingCount) {
    document.getElementById('submitForm').action = `/resource-person/cs110-lists/${programCode}/submit`;
    document.getElementById('submitMappingCount').textContent = mappingCount;
}
</script>
@endpush
