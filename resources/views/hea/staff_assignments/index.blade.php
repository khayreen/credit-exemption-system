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
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .page-header h2 {
        font-weight: 700;
        color: var(--industrial-dark);
        margin-bottom: 0.25rem;
    }

    .page-header p {
        color: var(--industrial-gray);
        margin: 0;
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
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }

    .stat-icon.blue { background: var(--uitm-blue); }
    .stat-icon.green { background: var(--success); }
    .stat-icon.amber { background: var(--uitm-amber); }
    .stat-icon.teal { background: var(--info); }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        line-height: 1;
        font-family: 'IBM Plex Mono', monospace;
    }

    .stat-value.blue { color: var(--uitm-blue); }
    .stat-value.green { color: var(--success); }
    .stat-value.amber { color: var(--uitm-amber); }
    .stat-value.teal { color: var(--info); }

    .stat-label {
        color: var(--industrial-gray);
        font-size: 0.9rem;
        margin-top: 0.25rem;
    }

    /* Main Card */
    .main-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    /* Custom Tabs */
    .custom-tabs {
        display: flex;
        background: var(--industrial-light);
        border-bottom: 1px solid #e2e8f0;
        padding: 0;
        margin: 0;
        list-style: none;
    }

    .custom-tabs .tab-link {
        padding: 1rem 1.5rem;
        color: var(--industrial-gray);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        border-bottom: 3px solid transparent;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .custom-tabs .tab-link:hover {
        color: var(--uitm-blue);
        background: rgba(30,58,138,0.05);
    }

    .custom-tabs .tab-link.active {
        color: var(--uitm-blue);
        border-bottom-color: var(--uitm-blue);
        background: white;
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

    /* Avatar */
    .avatar-sm {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--uitm-blue);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        font-weight: 600;
    }

    /* User Info */
    .user-info strong {
        color: var(--industrial-dark);
    }

    .user-info small {
        color: var(--industrial-gray);
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
    }

    .badge-warning {
        background: var(--uitm-amber);
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .badge-info {
        background: var(--info);
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

    /* Group Tags */
    .group-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.35rem;
    }

    .group-tag {
        background: var(--uitm-blue);
        color: white;
        padding: 0.2rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
        font-family: 'IBM Plex Mono', monospace;
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

    .btn-sm {
        padding: 0.4rem 0.75rem;
        font-size: 0.8rem;
    }

    /* Warning Icon */
    .warning-icon {
        color: var(--uitm-amber);
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
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .custom-tabs {
            overflow-x: auto;
        }

        .custom-tabs .tab-link {
            white-space: nowrap;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2><i class="fas fa-user-cog me-2"></i>Staff Assignment Management</h2>
            <p>View and manage program assignments for all staff members</p>
        </div>
        <a href="{{ route('hea.program_groups.index') }}" class="btn btn-industrial btn-outline-primary">
            <i class="fas fa-cog"></i> Configure Program Groups
        </a>
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

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <div class="stat-value blue">{{ $stats['total'] }}</div>
                <div class="stat-label">Total Staff</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <div>
                <div class="stat-value green">{{ $stats['academic_advisors'] }}</div>
                <div class="stat-label">Academic Advisors</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon amber">
                <i class="fas fa-user-tie"></i>
            </div>
            <div>
                <div class="stat-value amber">{{ $stats['coordinators'] }}</div>
                <div class="stat-label">Coordinators</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon teal">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div>
                <div class="stat-value teal">{{ $stats['resource_persons'] }}</div>
                <div class="stat-label">Resource Persons</div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="main-card">
        <ul class="custom-tabs">
            <li>
                <a class="tab-link {{ $filter === 'all' ? 'active' : '' }}"
                   href="{{ route('hea.staff_assignments.index', ['filter' => 'all']) }}">
                    <i class="fas fa-users"></i> All ({{ $stats['total'] }})
                </a>
            </li>
            <li>
                <a class="tab-link {{ $filter === 'academic_advisors' ? 'active' : '' }}"
                   href="{{ route('hea.staff_assignments.index', ['filter' => 'academic_advisors']) }}">
                    <i class="fas fa-chalkboard-teacher"></i> Academic Advisors ({{ $stats['academic_advisors'] }})
                </a>
            </li>
            <li>
                <a class="tab-link {{ $filter === 'coordinators' ? 'active' : '' }}"
                   href="{{ route('hea.staff_assignments.index', ['filter' => 'coordinators']) }}">
                    <i class="fas fa-user-tie"></i> Coordinators ({{ $stats['coordinators'] }})
                </a>
            </li>
            <li>
                <a class="tab-link {{ $filter === 'resource_persons' ? 'active' : '' }}"
                   href="{{ route('hea.staff_assignments.index', ['filter' => 'resource_persons']) }}">
                    <i class="fas fa-user-graduate"></i> Resource Persons ({{ $stats['resource_persons'] }})
                </a>
            </li>
        </ul>

        @if($staff->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h5>No Staff Found</h5>
                <p>
                    @if($filter === 'all')
                        There are no approved staff members in the system.
                    @else
                        There are no {{ str_replace('_', ' ', $filter) }} in the system.
                    @endif
                </p>
            </div>
        @else
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Current Assignment</th>
                            <th class="text-center" style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($staff as $member)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-2">
                                        {{ strtoupper(substr($member->name, 0, 1)) }}
                                    </div>
                                    <div class="user-info">
                                        <strong>{{ $member->name }}</strong>
                                        @if(!$member->hasVerifiedEmail())
                                            <span class="warning-icon ms-1" title="Email not verified">
                                                <i class="fas fa-exclamation-triangle"></i>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <small class="text-muted">{{ $member->email }}</small>
                            </td>
                            <td>
                                @switch($member->current_role)
                                    @case('academic_advisor')
                                        <span class="badge-success">Academic Advisor</span>
                                        @break
                                    @case('program_coordinator')
                                        <span class="badge-warning">Program Coordinator</span>
                                        @break
                                    @case('resource_person')
                                        <span class="badge-info">Resource Person</span>
                                        @break
                                @endswitch
                            </td>
                            <td>
                                @switch($member->current_role)
                                    @case('academic_advisor')
                                        @php
                                            $groups = $member->academicAdvisor?->program_groups ?? [];
                                            if (is_string($groups)) {
                                                $groups = json_decode($groups, true) ?? [];
                                            }
                                        @endphp
                                        @if(count($groups) > 0)
                                            <div class="group-tags">
                                                @foreach(array_slice($groups, 0, 5) as $group)
                                                    <span class="group-tag">
                                                        {{ is_array($group) ? ($group['group'] ?? 'N/A') : $group }}
                                                    </span>
                                                @endforeach
                                                @if(count($groups) > 5)
                                                    <span class="badge-secondary">+{{ count($groups) - 5 }} more</span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-muted">No groups assigned</span>
                                        @endif
                                        @break

                                    @case('program_coordinator')
                                        @php
                                            $category = $member->programCoordinator?->program_category;
                                            $categoryInfo = $coordinatorCategories[$category] ?? null;
                                        @endphp
                                        @if($categoryInfo)
                                            <span class="badge-warning">
                                                {{ $categoryInfo['label'] }}
                                            </span>
                                            <small class="d-block text-muted mt-1">
                                                {{ implode(', ', $categoryInfo['programs']) }}
                                            </small>
                                        @else
                                            <span class="text-muted">No category assigned</span>
                                        @endif
                                        @break

                                    @case('resource_person')
                                        @php
                                            $program = $member->resourcePerson?->assigned_program;
                                        @endphp
                                        @if($program)
                                            <span class="badge-info">{{ $program }}</span>
                                            <small class="d-block text-muted mt-1">
                                                {{ $supportedPrograms[$program] ?? $program }}
                                            </small>
                                        @else
                                            <span class="text-muted">No program assigned</span>
                                        @endif
                                        @break
                                @endswitch
                            </td>
                            <td class="text-center">
                                <a href="{{ route('hea.staff_assignments.edit', $member) }}"
                                   class="btn btn-industrial btn-outline-primary btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
