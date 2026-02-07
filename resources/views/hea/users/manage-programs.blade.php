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

    .breadcrumb {
        background: transparent;
        padding: 0;
        margin-bottom: 0.75rem;
    }

    .breadcrumb-item a {
        color: rgba(255,255,255,0.7);
        text-decoration: none;
    }

    .breadcrumb-item a:hover {
        color: var(--uitm-amber);
    }

    .breadcrumb-item.active {
        color: rgba(255,255,255,0.9);
    }

    .breadcrumb-item + .breadcrumb-item::before {
        color: rgba(255,255,255,0.5);
    }

    .page-header h2 {
        font-weight: 700;
        font-size: 1.75rem;
        margin-bottom: 0.35rem;
    }

    .page-header p {
        color: rgba(255,255,255,0.8);
        margin: 0;
    }

    .btn-new-system {
        background: var(--uitm-amber);
        border: none;
        color: var(--industrial-dark);
        padding: 0.625rem 1.25rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-new-system:hover {
        background: #fbbf24;
        color: var(--industrial-dark);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4);
    }

    /* Alert */
    .alert-industrial {
        background: rgba(13, 148, 136, 0.1);
        border: 1px solid rgba(13, 148, 136, 0.2);
        border-left: 4px solid var(--info);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        color: var(--info);
    }

    .alert-industrial a {
        color: var(--info);
        font-weight: 600;
    }

    .alert-industrial a:hover {
        color: #0f766e;
    }

    /* Staff Info Card */
    .staff-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        overflow: hidden;
    }

    .staff-card-header {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--uitm-blue-light) 100%);
        color: white;
        padding: 1.25rem 1.5rem;
    }

    .staff-card-header h5 {
        margin: 0;
        font-weight: 600;
    }

    .staff-card-body {
        padding: 1.5rem;
        text-align: center;
    }

    .staff-avatar {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--uitm-blue-light) 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        box-shadow: 0 4px 15px rgba(30, 58, 138, 0.3);
    }

    .staff-avatar span {
        color: white;
        font-weight: 700;
        font-size: 2rem;
    }

    .staff-name {
        font-weight: 600;
        font-size: 1.25rem;
        color: var(--industrial-dark);
        margin-bottom: 0.25rem;
    }

    .staff-email {
        color: var(--industrial-gray);
        margin-bottom: 0.75rem;
    }

    .role-badge {
        display: inline-block;
        padding: 0.5rem 1.25rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .role-badge.advisor {
        background: rgba(5, 150, 105, 0.1);
        color: var(--success);
        border: 1px solid rgba(5, 150, 105, 0.2);
    }

    .role-badge.coordinator {
        background: rgba(245, 158, 11, 0.1);
        color: #b45309;
        border: 1px solid rgba(245, 158, 11, 0.2);
    }

    .role-badge.resource {
        background: rgba(13, 148, 136, 0.1);
        color: var(--info);
        border: 1px solid rgba(13, 148, 136, 0.2);
    }

    .divider {
        height: 1px;
        background: linear-gradient(to right, transparent, #e2e8f0, transparent);
        margin: 1.25rem 0;
    }

    .current-programs-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--industrial-gray);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .program-badge {
        display: inline-block;
        background: var(--industrial-light);
        color: var(--industrial-dark);
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 600;
        font-size: 0.8rem;
        margin: 0.25rem;
    }

    .no-programs {
        color: #94a3b8;
        font-style: italic;
    }

    /* Assignment Card */
    .assignment-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        overflow: hidden;
    }

    .assignment-card-header {
        background: linear-gradient(135deg, var(--industrial-light) 0%, #e2e8f0 100%);
        padding: 1rem 1.5rem;
        border-bottom: 2px solid var(--uitm-blue);
    }

    .assignment-card-header h5 {
        margin: 0;
        font-weight: 600;
        color: var(--industrial-dark);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .assignment-card-header h5 i {
        color: var(--uitm-blue);
    }

    .assignment-card-body {
        padding: 1.5rem;
    }

    .alert-success-industrial {
        background: rgba(5, 150, 105, 0.1);
        border: 1px solid rgba(5, 150, 105, 0.2);
        border-left: 4px solid var(--success);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        color: var(--success);
        margin-bottom: 1.25rem;
    }

    .alert-danger-industrial {
        background: rgba(220, 38, 38, 0.1);
        border: 1px solid rgba(220, 38, 38, 0.2);
        border-left: 4px solid var(--danger);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        color: var(--danger);
        margin-bottom: 1.25rem;
    }

    .form-section-label {
        font-weight: 600;
        color: var(--industrial-dark);
        margin-bottom: 0.5rem;
    }

    .form-section-hint {
        font-size: 0.85rem;
        color: var(--industrial-gray);
        margin-bottom: 1rem;
    }

    .programs-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
    }

    .program-checkbox {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 1rem;
        background: var(--industrial-light);
        border-radius: 12px;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .program-checkbox:hover {
        background: #e2e8f0;
    }

    .program-checkbox input[type="checkbox"] {
        width: 20px;
        height: 20px;
        margin-top: 0.1rem;
        accent-color: var(--uitm-blue);
        cursor: pointer;
    }

    .program-checkbox label {
        cursor: pointer;
        flex: 1;
    }

    .program-checkbox .code {
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 700;
        color: var(--uitm-blue);
    }

    .program-checkbox .name {
        font-size: 0.85rem;
        color: var(--industrial-gray);
    }

    .alert-warning-industrial {
        background: rgba(234, 88, 12, 0.1);
        border: 1px solid rgba(234, 88, 12, 0.2);
        border-left: 4px solid var(--warning);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        color: var(--warning);
    }

    .form-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1.5rem;
        border-top: 1px solid #e2e8f0;
        margin-top: 1.5rem;
    }

    .btn-back {
        background: white;
        border: 2px solid var(--industrial-gray);
        color: var(--industrial-gray);
        padding: 0.625rem 1.25rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-back:hover {
        background: var(--industrial-gray);
        color: white;
    }

    .btn-save {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--uitm-blue-light) 100%);
        border: none;
        color: white;
        padding: 0.625rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(30, 58, 138, 0.3);
        color: white;
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
            margin: -1rem -1rem 1.5rem -1rem;
            border-radius: 0 0 16px 16px;
        }

        .programs-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('hea.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('hea.users.active') }}">Active Staff</a></li>
                        <li class="breadcrumb-item active">Manage Programs</li>
                    </ol>
                </nav>
                <h2>Manage Program Assignment</h2>
                <p>Legacy program management for {{ $user->name }}</p>
            </div>
            <a href="{{ route('hea.staff_assignments.edit', $user) }}" class="btn-new-system">
                <i class="fas fa-arrow-right me-2"></i>Use New Assignment System
            </a>
        </div>
    </div>

    <div class="alert-industrial">
        <i class="fas fa-info-circle me-2"></i>
        <strong>Recommended:</strong> Use the new
        <a href="{{ route('hea.staff_assignments.edit', $user) }}">Staff Assignment Management</a>
        system for a better experience with role-specific assignment options.
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="staff-card mb-4">
                <div class="staff-card-header">
                    <h5><i class="fas fa-user me-2"></i>Staff Information</h5>
                </div>
                <div class="staff-card-body">
                    <div class="staff-avatar">
                        <span>{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                    </div>
                    <div class="staff-name">{{ $user->name }}</div>
                    <div class="staff-email">{{ $user->email }}</div>
                    @switch($user->current_role)
                        @case('academic_advisor')
                            <span class="role-badge advisor">Academic Advisor</span>
                            @break
                        @case('program_coordinator')
                            <span class="role-badge coordinator">Program Coordinator</span>
                            @break
                        @case('resource_person')
                            <span class="role-badge resource">Resource Person</span>
                            @break
                    @endswitch

                    <div class="divider"></div>

                    <div class="current-programs-label">Current Programs</div>
                    @if(count($userPrograms) > 0)
                        <div>
                            @foreach($userPrograms as $program)
                                <span class="program-badge">{{ $program }}</span>
                            @endforeach
                        </div>
                    @else
                        <span class="no-programs">No programs assigned</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="assignment-card">
                <div class="assignment-card-header">
                    <h5><i class="fas fa-graduation-cap"></i>Program Assignment</h5>
                </div>
                <div class="assignment-card-body">
                    @if(session('success'))
                        <div class="alert-success-industrial">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert-danger-industrial">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert-danger-industrial">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('hea.users.programs.update', $user) }}" method="POST">
                        @csrf

                        <div class="form-section-label">Select Programs</div>
                        <p class="form-section-hint">
                            Choose the programs this staff member will be assigned to manage.
                        </p>

                        @if($allPrograms->isEmpty())
                            <div class="alert-warning-industrial">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                No programs found in the system.
                            </div>
                        @else
                            <div class="programs-grid">
                                @foreach($allPrograms as $program)
                                    <div class="program-checkbox">
                                        <input type="checkbox"
                                               name="programs[]"
                                               value="{{ $program->code }}"
                                               id="program_{{ $program->code }}"
                                               {{ in_array($program->code, $userPrograms) ? 'checked' : '' }}>
                                        <label for="program_{{ $program->code }}">
                                            <div class="code">{{ $program->code }}</div>
                                            <div class="name">{{ $program->name }}</div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="form-actions">
                            <a href="{{ route('hea.users.active') }}" class="btn-back">
                                <i class="fas fa-arrow-left me-2"></i>Back to Active Staff
                            </a>
                            <button type="submit" class="btn-save">
                                <i class="fas fa-save me-2"></i>Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
