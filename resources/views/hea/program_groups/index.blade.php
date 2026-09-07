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

    /* Industrial Page Header */
    .page-header {
        position: relative;
        background: linear-gradient(135deg, var(--industrial-dark) 0%, #1e293b 50%, var(--uitm-blue) 100%);
        margin: -1rem -1.5rem 1.5rem;
        padding: 2rem 2rem;
        overflow: hidden;
        border-radius: 0 0 12px 12px;
    }

    .page-header-grid {
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px),
            linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px);
        background-size: 32px 32px;
    }

    .page-header-accent {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--uitm-amber), var(--uitm-amber-light, #fbbf24), transparent);
    }

    .page-header-content {
        position: relative;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .page-header-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .page-header-icon {
        width: 48px;
        height: 48px;
        background: rgba(245, 158, 11, 0.15);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--uitm-amber);
        font-size: 1.25rem;
    }

    .page-header h2 {
        font-weight: 700;
        color: white;
        margin-bottom: 0.25rem;
        font-size: 1.5rem;
    }

    .page-header p {
        color: rgba(255,255,255,0.6);
        margin: 0;
        font-size: 0.9rem;
    }

    .page-header .header-actions {
        display: flex;
        gap: 0.75rem;
    }

    .page-header .header-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        background: rgba(255,255,255,0.1);
        color: rgba(255,255,255,0.9);
        border: 1px solid rgba(255,255,255,0.15);
        transition: all 0.2s ease;
    }

    .page-header .header-btn:hover {
        background: rgba(255,255,255,0.2);
        color: white;
    }

    /* Alert Messages */
    .alert-success-custom {
        background: rgba(5,150,105,0.08);
        border: 1px solid rgba(5,150,105,0.2);
        border-left: 4px solid var(--success);
        border-radius: 8px;
        padding: 1rem 1.25rem;
        color: var(--success);
        margin-bottom: 1.5rem;
    }

    .alert-danger-custom {
        background: rgba(220,38,38,0.08);
        border: 1px solid rgba(220,38,38,0.2);
        border-left: 4px solid var(--danger);
        border-radius: 8px;
        padding: 1rem 1.25rem;
        color: var(--danger);
        margin-bottom: 1.5rem;
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
        padding: 1.25rem 1.5rem;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .main-card-header.success {
        background: linear-gradient(135deg, var(--success) 0%, #047857 100%);
    }

    .main-card-header.primary {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, #1e40af 100%);
    }

    .main-card-header-light {
        background: var(--industrial-light);
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .main-card-header h5, .main-card-header-light h5 {
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .main-card-header-light h5 {
        color: var(--industrial-dark);
    }

    .main-card-body {
        padding: 1.5rem;
    }

    /* Session Badge */
    .session-badge {
        background: rgba(255,255,255,0.2);
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .session-badge-light {
        background: var(--info);
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    /* Program Cards in Overview */
    .program-overview-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 1rem;
    }

    .program-mini-card {
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        transition: all 0.2s ease;
    }

    .program-mini-card.active {
        border-color: var(--success);
    }

    .program-mini-card.inactive {
        border-color: #e2e8f0;
    }

    .program-mini-header {
        padding: 0.75rem;
        font-weight: 700;
        font-size: 0.9rem;
    }

    .program-mini-header.active {
        background: var(--success);
        color: white;
    }

    .program-mini-header.inactive {
        background: var(--industrial-light);
        color: var(--industrial-gray);
    }

    .program-mini-body {
        padding: 0.75rem;
        background: white;
    }

    .group-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.35rem 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .group-item:last-child {
        border-bottom: none;
    }

    .group-badge {
        background: var(--uitm-blue);
        color: white;
        padding: 0.2rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
        font-family: 'IBM Plex Mono', monospace;
    }

    .group-assigned {
        color: var(--success);
        font-size: 0.75rem;
    }

    .group-unassigned {
        color: var(--uitm-amber);
        font-size: 0.75rem;
    }

    .no-groups-text {
        text-align: center;
        color: var(--industrial-gray);
        font-size: 0.8rem;
        padding: 0.5rem;
    }

    /* Warning Alert */
    .warning-alert {
        background: rgba(245,158,11,0.1);
        border: 1px solid rgba(245,158,11,0.3);
        border-left: 4px solid var(--uitm-amber);
        border-radius: 8px;
        padding: 1rem;
        color: var(--industrial-dark);
    }

    .warning-alert i {
        color: var(--uitm-amber);
    }

    /* Info Alert */
    .info-alert {
        background: rgba(13,148,136,0.08);
        border: 1px solid rgba(13,148,136,0.2);
        border-left: 4px solid var(--info);
        border-radius: 8px;
        padding: 1rem;
        color: var(--industrial-dark);
    }

    .info-alert i {
        color: var(--info);
    }

    /* Form Elements */
    .form-label {
        font-weight: 600;
        color: var(--industrial-dark);
        margin-bottom: 0.5rem;
    }

    .form-select {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.625rem 1rem;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }

    .form-select:focus {
        border-color: var(--uitm-blue);
        box-shadow: 0 0 0 3px rgba(30,58,138,0.1);
    }

    /* Configuration Table */
    .config-table {
        width: 100%;
        border-collapse: collapse;
    }

    .config-table thead th {
        background: var(--industrial-light);
        color: var(--industrial-dark);
        font-weight: 600;
        padding: 1rem;
        text-align: left;
        border-bottom: 2px solid #e2e8f0;
        font-size: 0.85rem;
    }

    .config-table tbody td {
        padding: 1rem;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
    }

    .config-table tbody tr:hover {
        background: rgba(30,58,138,0.02);
    }

    .config-table tbody tr.inactive {
        background: var(--industrial-light);
    }

    .config-table tbody tr.inactive:hover {
        background: #e2e8f0;
    }

    /* Group Code Label */
    .group-code-label {
        font-weight: 700;
        font-family: 'IBM Plex Mono', monospace;
    }

    .group-code-label.active {
        color: var(--success);
    }

    .group-code-label.inactive {
        color: var(--industrial-gray);
    }

    .group-letter {
        display: block;
        font-size: 0.8rem;
        color: var(--industrial-gray);
        font-weight: 500;
    }

    /* Badges */
    .badge-primary {
        background: var(--uitm-blue);
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .badge-success {
        background: var(--success);
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        white-space: nowrap;
    }

    .badge-warning {
        background: var(--uitm-amber);
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        white-space: nowrap;
    }

    .badge-secondary {
        background: #64748b;
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        white-space: nowrap;
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

    .btn-primary-industrial {
        background: var(--uitm-blue);
        color: white;
        border: none;
    }

    .btn-primary-industrial:hover {
        background: #1e40af;
        color: white;
    }

    .btn-outline-success {
        background: transparent;
        color: var(--success);
        border: 1px solid var(--success);
    }

    .btn-outline-success:hover {
        background: var(--success);
        color: white;
    }

    .btn-outline-secondary {
        background: transparent;
        color: var(--industrial-gray);
        border: 1px solid var(--industrial-gray);
    }

    .btn-outline-secondary:hover {
        background: var(--industrial-gray);
        color: white;
    }

    /* Sidebar Card */
    .sidebar-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .sidebar-header {
        background: var(--industrial-light);
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .sidebar-header h5 {
        font-weight: 700;
        color: var(--industrial-dark);
        margin: 0;
    }

    .sidebar-header small {
        color: var(--industrial-gray);
    }

    /* List Group */
    .list-group-custom {
        border-radius: 0;
    }

    .list-group-item-custom {
        padding: 1rem 1.25rem;
        border: none;
        border-bottom: 1px solid #e2e8f0;
    }

    .list-group-item-custom:last-child {
        border-bottom: none;
    }

    .list-group-item-custom.active {
        background: var(--industrial-light);
        border-left: 3px solid var(--uitm-blue);
    }

    /* Footer Stats */
    .sidebar-footer {
        background: var(--industrial-light);
        padding: 1rem 1.25rem;
        border-top: 1px solid #e2e8f0;
    }

    .sidebar-footer-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.35rem;
    }

    .sidebar-footer-row:last-child {
        margin-bottom: 0;
    }

    /* Help Card */
    .help-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .help-header {
        background: var(--industrial-light);
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .help-header h5 {
        font-weight: 700;
        color: var(--info);
        margin: 0;
    }

    .help-body {
        padding: 1.25rem;
    }

    .help-body p {
        font-size: 0.85rem;
        color: var(--industrial-dark);
        margin-bottom: 0.5rem;
    }

    .help-body ol, .help-body ul {
        font-size: 0.85rem;
        color: var(--industrial-gray);
        padding-left: 1.25rem;
        margin-bottom: 0.75rem;
    }

    .help-body ol li, .help-body ul li {
        margin-bottom: 0.35rem;
    }

    /* Checkbox Styling */
    .form-check-input:checked {
        background-color: var(--success);
        border-color: var(--success);
    }

    .form-check-input:focus {
        box-shadow: 0 0 0 3px rgba(5,150,105,0.2);
    }

    /* Lock Icon */
    .lock-icon {
        color: var(--industrial-gray);
    }

    @media (max-width: 1200px) {
        .program-overview-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {
        .program-overview-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Industrial Page Header -->
    <div class="page-header">
        <div class="page-header-grid"></div>
        <div class="page-header-accent"></div>
        <div class="page-header-content">
            <div class="page-header-left">
                <div class="page-header-icon">
                    <i class="fas fa-cog"></i>
                </div>
                <div>
                    <h2>Program Group Configuration</h2>
                    <p>Configure groups and assign Academic Advisors for each session</p>
                </div>
            </div>
            <div class="header-actions">
                <a href="{{ route('hea.program_groups.assignments') }}" class="header-btn">
                    <i class="fas fa-users-cog"></i> View Assignments
                </a>
                <a href="{{ route('hea.dashboard') }}" class="header-btn">
                    <i class="fas fa-arrow-left"></i> Dashboard
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-success-custom">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert-danger-custom">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        </div>
    @endif

    <!-- Current Active Groups Overview -->
    <div class="main-card">
        <div class="main-card-header success">
            <h5><i class="fas fa-list-check"></i> Current Active Groups</h5>
            <span class="session-badge">
                {{ $intakes[$intake] ?? $intake }} {{ $academicYear }}
            </span>
        </div>
        <div class="main-card-body">
            @if($allActiveGroups->isEmpty())
                <div class="warning-alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    No active groups configured for this session yet.
                </div>
            @else
                <div class="program-overview-grid">
                    @foreach($supportedPrograms as $code => $name)
                        <div class="program-mini-card {{ isset($allActiveGroups[$code]) ? 'active' : 'inactive' }}">
                            <div class="program-mini-header {{ isset($allActiveGroups[$code]) ? 'active' : 'inactive' }}">
                                {{ $code }}
                            </div>
                            <div class="program-mini-body">
                                @if(isset($allActiveGroups[$code]) && $allActiveGroups[$code]->count() > 0)
                                    @foreach($allActiveGroups[$code] as $group)
                                        <div class="group-item">
                                            <div>
                                                <span class="group-badge">{{ $group->group_code }}</span>
                                                <small class="text-muted ms-1">Sem {{ $group->semester }}</small>
                                            </div>
                                            <div>
                                                @if($group->assignedUser)
                                                    <span class="group-assigned" title="{{ $group->assignedUser->name }}">
                                                        <i class="fas fa-user-check"></i>
                                                        {{ Str::limit($group->assignedUser->name, 12) }}
                                                    </span>
                                                @else
                                                    <span class="group-unassigned">
                                                        <i class="fas fa-user-times"></i> Unassigned
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="no-groups-text">
                                        <small>No active groups</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        <!-- Main Configuration Panel -->
        <div class="col-lg-8">
            <div class="main-card">
                <div class="main-card-header primary">
                    <h5><i class="fas fa-cog"></i> Select Context</h5>
                </div>
                <div class="main-card-body">
                    <form id="filterForm" method="GET" action="{{ route('hea.program_groups.index') }}">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Academic Year</label>
                                <select name="academic_year" id="academicYear" class="form-select" onchange="this.form.submit()">
                                    @foreach($academicYears as $year => $label)
                                        <option value="{{ $year }}" {{ $academicYear === $year ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Intake</label>
                                <select name="intake" id="intake" class="form-select" onchange="this.form.submit()">
                                    @foreach($intakes as $key => $label)
                                        <option value="{{ $key }}" {{ $intake === $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Program</label>
                                <select name="program_code" id="programCode" class="form-select" onchange="this.form.submit()">
                                    @foreach($supportedPrograms as $code => $name)
                                        <option value="{{ $code }}" {{ $programCode === $code ? 'selected' : '' }}>
                                            {{ $code }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Student Semester</label>
                                <select name="semester" id="semester" class="form-select" onchange="this.form.submit()">
                                    @foreach($semesters as $sem)
                                        <option value="{{ $sem }}" {{ $semester == $sem ? 'selected' : '' }}>
                                            Semester {{ $sem }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Group Configuration Card -->
            @php
                $isCurrentSession = ($academicYear === \App\Models\ProgramGroupConfig::getCurrentAcademicYear()
                                    && $intake === \App\Models\ProgramGroupConfig::getCurrentSession());
            @endphp
            <div class="main-card">
                <div class="main-card-header-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5><i class="fas fa-users text-primary me-2"></i>Groups for {{ $programCode }} Semester {{ $semester }}</h5>
                        <span class="session-badge-light">
                            {{ $intakes[$intake] ?? $intake }} {{ $academicYear }}
                        </span>
                    </div>
                </div>
                <div class="main-card-body">
                    @if($isCurrentSession)
                        <div class="warning-alert mb-4">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Current Active Session:</strong>
                            Group activation/deactivation is disabled. You can only reassign Academic Advisors.
                        </div>
                    @else
                        <div class="info-alert mb-4">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Context:</strong> You are configuring groups for
                            <strong>{{ $supportedPrograms[$programCode] }}</strong>,
                            students in <strong>Semester {{ $semester }}</strong>,
                            for <strong>{{ $intakes[$intake] ?? $intake }} {{ $academicYear }}</strong>.
                        </div>
                    @endif

                    @if($academicAdvisors->isEmpty())
                        <div class="warning-alert mb-4">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>No Academic Advisors available.</strong>
                            You need to approve some Academic Advisors before you can assign them to groups.
                        </div>
                    @endif

                    <form action="{{ route('hea.program_groups.save') }}" method="POST">
                        @csrf
                        <input type="hidden" name="academic_year" value="{{ $academicYear }}">
                        <input type="hidden" name="intake" value="{{ $intake }}">
                        <input type="hidden" name="program_code" value="{{ $programCode }}">
                        <input type="hidden" name="semester" value="{{ $semester }}">

                        <div class="table-responsive">
                            <table class="config-table">
                                <thead>
                                    <tr>
                                        <th style="width: 60px;" class="text-center">Active</th>
                                        <th style="width: 140px;">Group Code</th>
                                        <th>Assigned Academic Advisor</th>
                                        <th style="width: 100px;" class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($groupLetters as $letter)
                                        @php
                                            $group = $configuredGroups[$letter] ?? null;
                                            $isActive = $group ? $group->is_active : false;
                                            $groupCode = $programCode . $semester . $letter;
                                            $assignedUserId = $group?->assigned_user_id;
                                            $assignedUser = $group?->assignedUser;
                                        @endphp
                                        <tr class="{{ $isActive ? '' : 'inactive' }}">
                                            <td class="text-center">
                                                @if($isCurrentSession)
                                                    @if($isActive)
                                                        <input type="hidden" name="groups[]" value="{{ $letter }}">
                                                        <i class="fas fa-check-circle text-success" title="Active"></i>
                                                    @else
                                                        <i class="fas fa-minus-circle text-muted" title="Inactive"></i>
                                                    @endif
                                                @else
                                                    <input type="checkbox"
                                                           class="form-check-input group-active-checkbox"
                                                           name="groups[]"
                                                           value="{{ $letter }}"
                                                           id="group{{ $letter }}"
                                                           data-letter="{{ $letter }}"
                                                           {{ $isActive ? 'checked' : '' }}>
                                                @endif
                                            </td>
                                            <td>
                                                <label for="group{{ $letter }}" class="group-code-label {{ $isActive ? 'active' : 'inactive' }} mb-0">
                                                    {{ $groupCode }}
                                                </label>
                                                <small class="group-letter">Group {{ $letter }}</small>
                                            </td>
                                            <td>
                                                @if($isCurrentSession && !$isActive)
                                                    <span class="text-muted">-</span>
                                                @else
                                                    <select name="assignments[{{ $letter }}]"
                                                            id="assignment{{ $letter }}"
                                                            class="form-select form-select-sm assignment-select"
                                                            data-letter="{{ $letter }}"
                                                            {{ $isActive ? '' : 'disabled' }}>
                                                        <option value="">-- Select Academic Advisor --</option>
                                                        @foreach($academicAdvisors as $aa)
                                                            <option value="{{ $aa->id }}" {{ $assignedUserId === $aa->id ? 'selected' : '' }}>
                                                                {{ $aa->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if(!$isActive)
                                                    <span class="badge-secondary">Inactive</span>
                                                @elseif($assignedUser)
                                                    <span class="badge-success">
                                                        <i class="fas fa-check"></i> Assigned
                                                    </span>
                                                @else
                                                    <span class="badge-warning">
                                                        <i class="fas fa-exclamation-triangle"></i> Unassigned
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-3 mt-3 border-top">
                            <div>
                                @if(!$isCurrentSession)
                                    <button type="button" class="btn btn-industrial btn-outline-success me-2" onclick="selectAllGroups()">
                                        <i class="fas fa-check-double"></i> Activate All
                                    </button>
                                    <button type="button" class="btn btn-industrial btn-outline-secondary" onclick="clearAllGroups()">
                                        <i class="fas fa-times"></i> Deactivate All
                                    </button>
                                @else
                                    <span class="text-muted small lock-icon">
                                        <i class="fas fa-lock me-1"></i>Group changes locked for current session
                                    </span>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-industrial btn-primary-industrial">
                                <i class="fas fa-save"></i> Save Configuration
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Session Stats Sidebar -->
        <div class="col-lg-4">
            <div class="sidebar-card">
                <div class="sidebar-header">
                    <h5><i class="fas fa-chart-bar me-2 text-primary"></i>Session Summary</h5>
                    <small>{{ $intakes[$intake] ?? $intake }} {{ $academicYear }}</small>
                </div>
                <div class="list-group-custom">
                    @foreach($sessionStats as $code => $stat)
                        <div class="list-group-item-custom {{ $code === $programCode ? 'active' : '' }}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong>{{ $code }}</strong>
                                    <small class="d-block text-muted text-truncate" style="max-width: 150px;">
                                        {{ Str::limit($stat['name'], 25) }}
                                    </small>
                                </div>
                                <div class="text-end">
                                    @if($stat['active'] > 0)
                                        <span class="badge-success">{{ $stat['assigned'] }} assigned</span>
                                        @if($stat['unassigned'] > 0)
                                            <span class="badge-warning">{{ $stat['unassigned'] }} unassigned</span>
                                        @endif
                                    @else
                                        <span class="badge-secondary">Not configured</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="sidebar-footer">
                    @php
                        $totalActive = collect($sessionStats)->sum('active');
                        $totalAssigned = collect($sessionStats)->sum('assigned');
                        $totalUnassigned = collect($sessionStats)->sum('unassigned');
                    @endphp
                    <div class="sidebar-footer-row">
                        <span>Total Active Groups:</span>
                        <strong>{{ $totalActive }}</strong>
                    </div>
                    <div class="sidebar-footer-row text-success">
                        <span>Assigned:</span>
                        <span>{{ $totalAssigned }}</span>
                    </div>
                    @if($totalUnassigned > 0)
                        <div class="sidebar-footer-row" style="color: var(--uitm-amber);">
                            <span>Unassigned:</span>
                            <strong>{{ $totalUnassigned }}</strong>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Help -->
            <div class="help-card">
                <div class="help-header">
                    <h5><i class="fas fa-question-circle me-2"></i>Help</h5>
                </div>
                <div class="help-body">
                    <p><strong>How to use this page:</strong></p>
                    <ol>
                        <li>Select the academic session context above</li>
                        <li>Check the groups that exist for this semester</li>
                        <li>Assign an Academic Advisor to each active group</li>
                        <li>Click "Save Configuration"</li>
                    </ol>
                    <p><strong>Status Indicators:</strong></p>
                    <ul>
                        <li><span class="badge-success" style="margin-right: 0.4rem;">Assigned</span> - AA assigned</li>
                        <li><span class="badge-warning" style="margin-right: 0.4rem;">Unassigned</span> - Needs AA</li>
                        <li><span class="badge-secondary" style="margin-right: 0.4rem;">Inactive</span> - Group disabled</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function selectAllGroups() {
    document.querySelectorAll('.group-active-checkbox').forEach(cb => {
        cb.checked = true;
        updateRowState(cb);
    });
}

function clearAllGroups() {
    document.querySelectorAll('.group-active-checkbox').forEach(cb => {
        cb.checked = false;
        updateRowState(cb);
    });
}

function updateRowState(checkbox) {
    const letter = checkbox.dataset.letter;
    const row = checkbox.closest('tr');
    const select = document.getElementById('assignment' + letter);

    if (checkbox.checked) {
        row.classList.remove('inactive');
        select.disabled = false;
    } else {
        row.classList.add('inactive');
        select.disabled = true;
        select.value = '';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.group-active-checkbox').forEach(cb => {
        cb.addEventListener('change', function() {
            updateRowState(this);
        });
    });
});
</script>
@endpush
