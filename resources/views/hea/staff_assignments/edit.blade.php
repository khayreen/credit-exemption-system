@extends('layouts.app')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap');

    :root {
        --uitm-blue: #1e3a8a;
        --uitm-blue-light: #3b82f6;
        --uitm-amber: #f59e0b;
        --industrial-dark: #0f172a;
        --industrial-gray: #334155;
        --industrial-light: #f1f5f9;
        --success-color: #059669;
        --danger-color: #dc2626;
        --warning-color: #ea580c;
        --info-color: #0d9488;
    }

    .edit-assignment-page {
        font-family: 'IBM Plex Sans', sans-serif;
        background: var(--industrial-light);
        min-height: 100vh;
        padding: 2rem 0;
    }

    /* Page Header */
    .page-header {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--industrial-dark) 100%);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
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
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='4'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    .breadcrumb-nav {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .breadcrumb-nav a {
        color: rgba(255, 255, 255, 0.7);
        text-decoration: none;
        font-size: 0.875rem;
        transition: color 0.2s ease;
    }

    .breadcrumb-nav a:hover {
        color: #fff;
    }

    .breadcrumb-nav .separator {
        color: rgba(255, 255, 255, 0.4);
    }

    .breadcrumb-nav .current {
        color: var(--uitm-amber);
        font-weight: 500;
    }

    .page-header h1 {
        color: #fff;
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
    }

    .page-header p {
        color: rgba(255, 255, 255, 0.8);
        margin: 0;
        font-size: 1rem;
    }

    .header-actions {
        position: absolute;
        top: 50%;
        right: 2rem;
        transform: translateY(-50%);
    }

    .btn-back {
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
    }

    .btn-back:hover {
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
    }

    /* Alert Messages */
    .alert-industrial {
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-left: 4px solid var(--danger-color);
        border-radius: 8px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .alert-industrial i {
        color: var(--danger-color);
        margin-right: 0.75rem;
    }

    /* Staff Info Card */
    .staff-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        height: 100%;
    }

    .staff-card-header {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--uitm-blue-light) 100%);
        padding: 1.25rem;
        text-align: center;
    }

    .staff-card-header h5 {
        color: #fff;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .staff-avatar {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--industrial-dark) 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        border: 4px solid var(--uitm-amber);
        box-shadow: 0 4px 12px rgba(30, 58, 138, 0.2);
    }

    .staff-avatar span {
        color: #fff;
        font-size: 2.5rem;
        font-weight: 700;
    }

    .staff-body {
        padding: 1.5rem;
    }

    .staff-name {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--industrial-dark);
        margin-bottom: 0.25rem;
        text-align: center;
    }

    .staff-email {
        color: var(--industrial-gray);
        font-size: 0.9rem;
        text-align: center;
        margin-bottom: 1.5rem;
        font-family: 'IBM Plex Mono', monospace;
    }

    .staff-divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, #e2e8f0, transparent);
        margin: 1.5rem 0;
    }

    .staff-info-item {
        margin-bottom: 1.25rem;
    }

    .staff-info-item:last-child {
        margin-bottom: 0;
    }

    .info-label {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--industrial-gray);
        margin-bottom: 0.5rem;
    }

    .role-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .role-badge.academic-advisor {
        background: rgba(5, 150, 105, 0.1);
        color: var(--success-color);
        border: 1px solid rgba(5, 150, 105, 0.2);
    }

    .role-badge.program-coordinator {
        background: rgba(245, 158, 11, 0.1);
        color: #b45309;
        border: 1px solid rgba(245, 158, 11, 0.2);
    }

    .role-badge.resource-person {
        background: rgba(13, 148, 136, 0.1);
        color: var(--info-color);
        border: 1px solid rgba(13, 148, 136, 0.2);
    }

    .assignment-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }

    .assignment-badge {
        background: var(--uitm-blue);
        color: #fff;
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 500;
        font-family: 'IBM Plex Mono', monospace;
    }

    .assignment-badge.category {
        background: var(--uitm-amber);
        color: var(--industrial-dark);
    }

    .assignment-badge.program {
        background: var(--info-color);
        color: #fff;
    }

    .no-assignment {
        color: var(--industrial-gray);
        font-style: italic;
    }

    .assignment-programs {
        font-size: 0.8rem;
        color: var(--industrial-gray);
        margin-top: 0.5rem;
    }

    .verification-status {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .verification-status.verified {
        color: var(--success-color);
    }

    .verification-status.not-verified {
        color: var(--warning-color);
    }

    /* Form Card */
    .form-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .form-card-header {
        background: var(--industrial-light);
        padding: 1.25rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .form-card-header h5 {
        color: var(--industrial-dark);
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-card-header h5 i {
        color: var(--uitm-blue);
    }

    .form-body {
        padding: 1.5rem;
    }

    /* Info Alert */
    .info-alert {
        background: rgba(13, 148, 136, 0.08);
        border: 1px solid rgba(13, 148, 136, 0.2);
        border-radius: 8px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .info-alert i {
        color: var(--info-color);
        margin-top: 2px;
    }

    .info-alert p {
        margin: 0;
        color: var(--industrial-gray);
        line-height: 1.5;
    }

    /* Session Filter */
    .session-filter {
        background: var(--industrial-light);
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
    }

    .filter-row {
        display: flex;
        align-items: flex-end;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .filter-group {
        flex: 1;
        min-width: 150px;
    }

    .filter-group label {
        display: block;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--industrial-gray);
        margin-bottom: 0.5rem;
    }

    .filter-group select {
        width: 100%;
        padding: 0.5rem 0.75rem;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.875rem;
        background: #fff;
        color: var(--industrial-dark);
        transition: all 0.2s ease;
    }

    .filter-group select:focus {
        outline: none;
        border-color: var(--uitm-blue);
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
    }

    .filter-info {
        flex: 2;
        text-align: right;
    }

    .session-badge {
        display: inline-block;
        background: var(--info-color);
        color: #fff;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 500;
    }

    /* Program Group Cards */
    .program-group-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        margin-bottom: 1rem;
        overflow: hidden;
    }

    .program-group-header {
        background: var(--industrial-light);
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .program-select-checkbox {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .program-select-checkbox input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: var(--uitm-blue);
        cursor: pointer;
    }

    .program-select-checkbox label {
        font-weight: 600;
        color: var(--industrial-dark);
        cursor: pointer;
        margin: 0;
    }

    .program-group-body {
        padding: 1rem;
    }

    .semester-section {
        margin-bottom: 1rem;
    }

    .semester-section:last-child {
        margin-bottom: 0;
    }

    .semester-label {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        color: var(--industrial-gray);
        margin-bottom: 0.5rem;
    }

    .group-checkboxes {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .group-checkbox {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .group-checkbox input[type="checkbox"] {
        width: 16px;
        height: 16px;
        accent-color: var(--uitm-blue);
        cursor: pointer;
    }

    .group-checkbox label {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.875rem;
        color: var(--industrial-dark);
        cursor: pointer;
        margin: 0;
    }

    /* Empty State */
    .empty-state {
        background: rgba(245, 158, 11, 0.08);
        border: 1px solid rgba(245, 158, 11, 0.2);
        border-radius: 8px;
        padding: 1.5rem;
        text-align: center;
    }

    .empty-state i {
        font-size: 2rem;
        color: var(--warning-color);
        margin-bottom: 0.75rem;
    }

    .empty-state p {
        color: var(--industrial-gray);
        margin: 0;
    }

    .empty-state a {
        color: var(--uitm-blue);
        font-weight: 500;
    }

    /* Category Cards */
    .category-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1rem;
    }

    .category-card {
        background: #fff;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.25rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .category-card:hover {
        border-color: var(--uitm-blue-light);
        box-shadow: 0 4px 12px rgba(30, 58, 138, 0.1);
    }

    .category-card.selected {
        border-color: var(--uitm-blue);
        background: rgba(30, 58, 138, 0.02);
    }

    .category-radio {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .category-radio input[type="radio"] {
        width: 20px;
        height: 20px;
        accent-color: var(--uitm-blue);
        cursor: pointer;
        margin-top: 2px;
    }

    .category-content {
        flex: 1;
    }

    .category-label {
        font-weight: 600;
        color: var(--industrial-dark);
        font-size: 1rem;
        margin-bottom: 0.5rem;
    }

    .category-description {
        color: var(--industrial-gray);
        font-size: 0.875rem;
        margin-bottom: 0.75rem;
        line-height: 1.4;
    }

    .category-programs {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .program-tag {
        background: var(--industrial-light);
        color: var(--industrial-gray);
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 500;
    }

    /* Program Select */
    .program-select-container {
        max-width: 500px;
    }

    .program-select-container label {
        display: block;
        font-weight: 600;
        color: var(--industrial-dark);
        margin-bottom: 0.5rem;
    }

    .program-select-container label .required {
        color: var(--danger-color);
    }

    .program-select-container select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 1rem;
        background: #fff;
        color: var(--industrial-dark);
        transition: all 0.2s ease;
    }

    .program-select-container select:focus {
        outline: none;
        border-color: var(--uitm-blue);
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
    }

    /* Form Actions */
    .form-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1.5rem;
        margin-top: 1.5rem;
        border-top: 1px solid #e2e8f0;
    }

    .btn-cancel {
        background: var(--industrial-light);
        color: var(--industrial-gray);
        border: 1px solid #e2e8f0;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-cancel:hover {
        background: #e2e8f0;
        color: var(--industrial-dark);
    }

    .btn-save {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--uitm-blue-light) 100%);
        color: #fff;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-save:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
        }

        .header-actions {
            position: static;
            transform: none;
            margin-top: 1rem;
        }

        .filter-row {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-info {
            text-align: left;
            margin-top: 0.5rem;
        }

        .form-actions {
            flex-direction: column;
            gap: 1rem;
        }

        .form-actions a,
        .form-actions button {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="edit-assignment-page">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <nav class="breadcrumb-nav">
                <a href="{{ route('hea.dashboard') }}">Dashboard</a>
                <span class="separator">/</span>
                <a href="{{ route('hea.users.active') }}">Active Staff</a>
                <span class="separator">/</span>
                <span class="current">Edit Assignment</span>
            </nav>
            <h1>Edit Staff Assignment</h1>
            <p>Update program assignment for {{ $user->name }}</p>
            <div class="header-actions">
                <a href="{{ route('hea.users.active') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i>Back to Active Staff
                </a>
            </div>
        </div>

        @if(session('error'))
            <div class="alert-industrial">
                <span>
                    <i class="fas fa-exclamation-circle"></i>{{ session('error') }}
                </span>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <!-- Staff Info Card -->
            <div class="col-md-4 mb-4">
                <div class="staff-card">
                    <div class="staff-card-header">
                        <h5><i class="fas fa-user"></i>Staff Information</h5>
                    </div>
                    <div class="staff-body">
                        <div class="staff-avatar">
                            <span>{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        </div>
                        <div class="staff-name">{{ $user->name }}</div>
                        <div class="staff-email">{{ $user->email }}</div>

                        <div class="staff-divider"></div>

                        <div class="staff-info-item">
                            <div class="info-label">Role</div>
                            @switch($user->current_role)
                                @case('academic_advisor')
                                    <span class="role-badge academic-advisor">
                                        <i class="fas fa-chalkboard-teacher me-1"></i>Academic Advisor
                                    </span>
                                    @break
                                @case('program_coordinator')
                                    <span class="role-badge program-coordinator">
                                        <i class="fas fa-user-tie me-1"></i>Program Coordinator
                                    </span>
                                    @break
                                @case('resource_person')
                                    <span class="role-badge resource-person">
                                        <i class="fas fa-user-graduate me-1"></i>Resource Person
                                    </span>
                                    @break
                            @endswitch
                        </div>

                        <div class="staff-info-item">
                            <div class="info-label">Current Assignment</div>
                            @if($currentAssignment['type'] === 'program_groups')
                                @php
                                    $groups = $currentAssignment['data'] ?? [];
                                    if (is_string($groups)) {
                                        $groups = json_decode($groups, true) ?? [];
                                    }
                                @endphp
                                @if(count($groups) > 0)
                                    <div class="assignment-badges">
                                        @foreach($groups as $group)
                                            <span class="assignment-badge">
                                                {{ is_array($group) ? ($group['group'] ?? 'N/A') : $group }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="no-assignment">No groups assigned</span>
                                @endif
                            @elseif($currentAssignment['type'] === 'category')
                                @php
                                    $categoryInfo = $coordinatorCategories[$currentAssignment['data']] ?? null;
                                @endphp
                                @if($categoryInfo)
                                    <span class="assignment-badge category">{{ $categoryInfo['label'] }}</span>
                                    <div class="assignment-programs">{{ implode(', ', $categoryInfo['programs']) }}</div>
                                @else
                                    <span class="no-assignment">No category assigned</span>
                                @endif
                            @elseif($currentAssignment['type'] === 'program')
                                @if($currentAssignment['data'])
                                    <span class="assignment-badge program">{{ $currentAssignment['data'] }}</span>
                                    <div class="assignment-programs">
                                        {{ $supportedPrograms[$currentAssignment['data']] ?? '' }}
                                    </div>
                                @else
                                    <span class="no-assignment">No program assigned</span>
                                @endif
                            @endif
                        </div>

                        <div class="staff-info-item">
                            <div class="info-label">Email Verified</div>
                            @if($user->hasVerifiedEmail())
                                <div class="verification-status verified">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Verified</span>
                                </div>
                            @else
                                <div class="verification-status not-verified">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <span>Not Verified</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Assignment Form -->
            <div class="col-md-8 mb-4">
                <div class="form-card">
                    <div class="form-card-header">
                        <h5><i class="fas fa-edit"></i>Update Assignment</h5>
                    </div>
                    <div class="form-body">
                        <form action="{{ route('hea.staff_assignments.update', $user) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            @switch($user->current_role)
                                @case('academic_advisor')
                                    <div class="info-alert">
                                        <i class="fas fa-info-circle"></i>
                                        <p>Select the program groups this Academic Advisor will be responsible for. Groups are organized by program and semester.</p>
                                    </div>

                                    <!-- Session Filter -->
                                    <div class="session-filter">
                                        <div class="filter-row">
                                            <div class="filter-group">
                                                <label for="filterAcademicYear">Academic Year</label>
                                                <select id="filterAcademicYear" onchange="updateSessionFilter()">
                                                    @foreach($academicYears as $year => $label)
                                                        <option value="{{ $year }}" {{ $academicYear === $year ? 'selected' : '' }}>
                                                            {{ $label }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="filter-group">
                                                <label for="filterIntake">Intake</label>
                                                <select id="filterIntake" onchange="updateSessionFilter()">
                                                    @foreach($intakes as $key => $label)
                                                        <option value="{{ $key }}" {{ $intake === $key ? 'selected' : '' }}>
                                                            {{ $label }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="filter-info">
                                                <span class="session-badge">
                                                    <i class="fas fa-calendar-alt me-1"></i>
                                                    {{ $intakes[$intake] ?? $intake }} {{ $academicYear }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    @php
                                        $currentGroups = $currentAssignment['data'] ?? [];
                                        if (is_string($currentGroups)) {
                                            $currentGroups = json_decode($currentGroups, true) ?? [];
                                        }
                                        $selectedGroupCodes = array_map(function($g) {
                                            return is_array($g) ? ($g['group'] ?? '') : $g;
                                        }, $currentGroups);
                                    @endphp

                                    @foreach($supportedPrograms as $programCode => $programName)
                                        @if(isset($programGroups[$programCode]) && $programGroups[$programCode]->count() > 0)
                                        <div class="program-group-card">
                                            <div class="program-group-header">
                                                <div class="program-select-checkbox">
                                                    <input type="checkbox" class="program-select-all"
                                                           id="selectAll{{ $programCode }}"
                                                           data-program="{{ $programCode }}">
                                                    <label for="selectAll{{ $programCode }}">
                                                        {{ $programCode }} - {{ $programName }}
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="program-group-body">
                                                <div class="row">
                                                    @foreach($programGroups[$programCode]->groupBy('semester') as $semester => $semGroups)
                                                    <div class="col-md-6 col-lg-4 mb-3">
                                                        <div class="semester-section">
                                                            <div class="semester-label">Semester {{ $semester }}</div>
                                                            <div class="group-checkboxes">
                                                                @foreach($semGroups as $group)
                                                                <div class="group-checkbox">
                                                                    <input type="checkbox"
                                                                           class="group-checkbox-input program-{{ $programCode }}"
                                                                           name="program_groups[]"
                                                                           value="{{ $group->group_code }}"
                                                                           id="group{{ $group->id }}"
                                                                           {{ in_array($group->group_code, $selectedGroupCodes) ? 'checked' : '' }}>
                                                                    <label for="group{{ $group->id }}">{{ $group->group_letter }}</label>
                                                                </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    @endforeach

                                    @if($programGroups->isEmpty())
                                        <div class="empty-state">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            <p>
                                                No program groups have been configured for {{ $intakes[$intake] ?? $intake }} {{ $academicYear }}.
                                                <a href="{{ route('hea.program_groups.index', ['academic_year' => $academicYear, 'intake' => $intake]) }}">Configure program groups</a> first.
                                            </p>
                                        </div>
                                    @endif
                                    @break

                                @case('program_coordinator')
                                    <div class="info-alert">
                                        <i class="fas fa-info-circle"></i>
                                        <p>Select the program category this Program Coordinator will manage.</p>
                                    </div>

                                    <div class="category-grid">
                                        @foreach($coordinatorCategories as $categoryKey => $categoryInfo)
                                        <div class="category-card {{ $currentAssignment['data'] === $categoryKey ? 'selected' : '' }}"
                                             onclick="document.getElementById('category{{ $categoryKey }}').checked = true; updateCategoryCards();">
                                            <div class="category-radio">
                                                <input type="radio"
                                                       name="program_category"
                                                       value="{{ $categoryKey }}"
                                                       id="category{{ $categoryKey }}"
                                                       {{ $currentAssignment['data'] === $categoryKey ? 'checked' : '' }}
                                                       required
                                                       onchange="updateCategoryCards()">
                                                <div class="category-content">
                                                    <div class="category-label">{{ $categoryInfo['label'] }}</div>
                                                    <div class="category-description">{{ $categoryInfo['description'] }}</div>
                                                    <div class="category-programs">
                                                        @foreach($categoryInfo['programs'] as $program)
                                                            <span class="program-tag">{{ $program }}</span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    @break

                                @case('resource_person')
                                    <div class="info-alert">
                                        <i class="fas fa-info-circle"></i>
                                        <p>Select the program this Resource Person will be responsible for managing course equivalencies.</p>
                                    </div>

                                    <div class="program-select-container">
                                        <label>
                                            Assigned Program <span class="required">*</span>
                                        </label>
                                        <select name="assigned_program" required>
                                            <option value="">Select a program</option>
                                            @foreach($supportedPrograms as $code => $name)
                                                <option value="{{ $code }}"
                                                        {{ $currentAssignment['data'] === $code ? 'selected' : '' }}>
                                                    {{ $code }} - {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @break
                            @endswitch

                            <div class="form-actions">
                                <a href="{{ route('hea.users.active') }}" class="btn-cancel">
                                    <i class="fas fa-times"></i>Cancel
                                </a>
                                <button type="submit" class="btn-save">
                                    <i class="fas fa-save"></i>Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function updateSessionFilter() {
    const year = document.getElementById('filterAcademicYear').value;
    const intake = document.getElementById('filterIntake').value;
    window.location.href = '{{ route('hea.staff_assignments.edit', $user) }}?academic_year=' + encodeURIComponent(year) + '&intake=' + encodeURIComponent(intake);
}

function updateCategoryCards() {
    document.querySelectorAll('.category-card').forEach(function(card) {
        const radio = card.querySelector('input[type="radio"]');
        if (radio.checked) {
            card.classList.add('selected');
        } else {
            card.classList.remove('selected');
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Select All functionality for Academic Advisor groups
    document.querySelectorAll('.program-select-all').forEach(function(selectAll) {
        const programCode = selectAll.dataset.program;
        const checkboxes = document.querySelectorAll('.program-' + programCode);

        // Update select-all state based on individual checkboxes
        function updateSelectAllState() {
            const checkedCount = document.querySelectorAll('.program-' + programCode + ':checked').length;
            selectAll.checked = checkedCount === checkboxes.length && checkboxes.length > 0;
            selectAll.indeterminate = checkedCount > 0 && checkedCount < checkboxes.length;
        }

        // Toggle all checkboxes when select-all is clicked
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = selectAll.checked;
            });
        });

        // Update select-all when individual checkbox changes
        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', updateSelectAllState);
        });

        // Initial state
        updateSelectAllState();
    });

    // Initialize category card selection state
    updateCategoryCards();
});
</script>
@endpush
