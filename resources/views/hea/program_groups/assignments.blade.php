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

    .main-card-header-light h5 {
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

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        line-height: 1;
        font-family: 'IBM Plex Mono', monospace;
        margin-bottom: 0.5rem;
    }

    .stat-value.blue { color: var(--uitm-blue); }
    .stat-value.green { color: var(--success); }
    .stat-value.amber { color: var(--uitm-amber); }
    .stat-value.teal { color: var(--info); }

    .stat-label {
        color: var(--industrial-gray);
        font-size: 0.85rem;
    }

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

    /* Group Code */
    .group-code {
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 600;
        color: var(--uitm-blue);
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

    .badge-secondary {
        background: #64748b;
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
    }

    .badge-warning {
        background: var(--uitm-amber);
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .badge-outline {
        background: white;
        color: var(--industrial-gray);
        border: 1px solid #e2e8f0;
        padding: 0.3rem 0.65rem;
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.75rem;
        font-family: 'IBM Plex Mono', monospace;
    }

    /* Avatar */
    .avatar-sm {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .avatar-sm.green {
        background: var(--success);
        color: white;
    }

    .avatar-sm.blue {
        background: var(--uitm-blue);
        color: white;
    }

    /* User Info */
    .user-info strong {
        color: var(--industrial-dark);
    }

    .user-info small {
        color: var(--industrial-gray);
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

    .btn-outline-primary {
        background: transparent;
        color: var(--uitm-blue);
        border: 1px solid var(--uitm-blue);
    }

    .btn-outline-primary:hover {
        background: var(--uitm-blue);
        color: white;
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

    /* Program Card Header */
    .program-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
        background: var(--industrial-light);
        border-bottom: 1px solid #e2e8f0;
    }

    .program-card-header h5 {
        font-weight: 700;
        color: var(--industrial-dark);
        margin: 0;
    }

    .program-card-header small {
        display: block;
        color: var(--industrial-gray);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-state-icon {
        width: 80px;
        height: 80px;
        background: var(--industrial-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }

    .empty-state-icon i {
        font-size: 2rem;
        color: var(--industrial-gray);
    }

    .empty-state h5 {
        font-weight: 700;
        color: var(--industrial-dark);
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: var(--industrial-gray);
        margin-bottom: 1.5rem;
    }

    /* Group Tags */
    .group-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.35rem;
    }

    @media (max-width: 768px) {
        .stats-grid {
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
                    <i class="fas fa-users-cog"></i>
                </div>
                <div>
                    <h2>Group Assignments Overview</h2>
                    <p>View all Academic Advisor assignments by program group</p>
                </div>
            </div>
            <div class="header-actions">
                <a href="{{ route('hea.program_groups.index') }}" class="header-btn">
                    <i class="fas fa-cog"></i> Configure Groups
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

    <!-- Session Filter -->
    <div class="main-card">
        <div class="main-card-header">
            <h5><i class="fas fa-filter"></i> Filter by Academic Session</h5>
        </div>
        <div class="main-card-body">
            <form method="GET" action="{{ route('hea.program_groups.assignments') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Academic Year</label>
                    <select name="academic_year" class="form-select" onchange="this.form.submit()">
                        @foreach($academicYears as $year => $label)
                            <option value="{{ $year }}" {{ $academicYear === $year ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Intake</label>
                    <select name="intake" class="form-select" onchange="this.form.submit()">
                        @foreach($intakes as $key => $label)
                            <option value="{{ $key }}" {{ $intake === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('hea.program_groups.index', ['academic_year' => $academicYear, 'intake' => $intake]) }}"
                       class="btn btn-industrial btn-outline-primary">
                        <i class="fas fa-cog"></i> Configure Groups
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value blue">{{ $summary['total_groups'] }}</div>
            <div class="stat-label">Total Active Groups</div>
        </div>
        <div class="stat-card">
            <div class="stat-value green">{{ $summary['assigned'] }}</div>
            <div class="stat-label">Assigned</div>
        </div>
        <div class="stat-card">
            <div class="stat-value amber">{{ $summary['unassigned'] }}</div>
            <div class="stat-label">Unassigned</div>
        </div>
        <div class="stat-card">
            <div class="stat-value teal">{{ $summary['total_advisors'] }}</div>
            <div class="stat-label">Active Advisors</div>
        </div>
    </div>

    <!-- Assignments by Program -->
    @foreach($assignmentsByProgram as $programCode => $programData)
    <div class="main-card">
        <div class="program-card-header">
            <div>
                <h5><i class="fas fa-graduation-cap me-2 text-primary"></i>{{ $programCode }}</h5>
                <small>{{ $supportedPrograms[$programCode] ?? $programCode }}</small>
            </div>
            <div>
                <span class="badge-success me-1">{{ $programData['stats']['assigned'] }} assigned</span>
                @if($programData['stats']['unassigned'] > 0)
                    <span class="badge-warning">{{ $programData['stats']['unassigned'] }} unassigned</span>
                @endif
            </div>
        </div>
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th style="width: 120px;">Group Code</th>
                        <th style="width: 100px;">Semester</th>
                        <th>Assigned Academic Advisor</th>
                        <th style="width: 120px;" class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($programData['groups'] as $group)
                    <tr>
                        <td>
                            <strong class="group-code">{{ $group->group_code }}</strong>
                        </td>
                        <td>
                            <span class="badge-secondary">Sem {{ $group->semester }}</span>
                        </td>
                        <td>
                            @if($group->assignedUser)
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm green me-2">
                                        {{ strtoupper(substr($group->assignedUser->name, 0, 1)) }}
                                    </div>
                                    <div class="user-info">
                                        <strong>{{ $group->assignedUser->name }}</strong>
                                        <small class="d-block">{{ $group->assignedUser->email }}</small>
                                    </div>
                                </div>
                            @else
                                <span class="text-muted fst-italic">
                                    <i class="fas fa-user-slash me-1"></i>Not assigned
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($group->assignedUser)
                                <span class="badge-success">
                                    <i class="fas fa-check me-1"></i>Assigned
                                </span>
                            @else
                                <span class="badge-warning">
                                    <i class="fas fa-exclamation-triangle me-1"></i>Pending
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endforeach

    @if($assignmentsByProgram->isEmpty())
    <div class="main-card">
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-folder-open"></i>
            </div>
            <h5>No Active Groups</h5>
            <p>
                There are no active program groups configured for {{ ucfirst($intake) }} {{ $academicYear }}.
            </p>
            <a href="{{ route('hea.program_groups.index', ['academic_year' => $academicYear, 'intake' => $intake]) }}"
               class="btn btn-industrial btn-primary-industrial">
                <i class="fas fa-plus"></i> Configure Groups
            </a>
        </div>
    </div>
    @endif

    <!-- Advisor Workload Summary -->
    @if($advisorWorkload->isNotEmpty())
    <div class="main-card">
        <div class="main-card-header-light">
            <h5><i class="fas fa-chart-pie me-2 text-info"></i>Advisor Workload Summary</h5>
        </div>
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Academic Advisor</th>
                        <th>Email</th>
                        <th class="text-center">Groups Assigned</th>
                        <th>Assigned Groups</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($advisorWorkload as $advisor)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm blue me-2">
                                    {{ strtoupper(substr($advisor['name'], 0, 1)) }}
                                </div>
                                <strong>{{ $advisor['name'] }}</strong>
                            </div>
                        </td>
                        <td>
                            <small class="text-muted">{{ $advisor['email'] }}</small>
                        </td>
                        <td class="text-center">
                            <span class="badge-primary font-mono">{{ $advisor['count'] }}</span>
                        </td>
                        <td>
                            <div class="group-tags">
                                @foreach($advisor['groups'] as $groupCode)
                                    <span class="badge-outline">{{ $groupCode }}</span>
                                @endforeach
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
