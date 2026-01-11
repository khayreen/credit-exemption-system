@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">User Management</h2>
            <p class="text-muted mb-0 mt-2">
                <i class="fas fa-users me-1"></i>
                View and manage all system users
            </p>
        </div>
        <div>
            <a href="{{ route('hea.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <!-- User Management Card -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>All Registered Users
                </h5>
                <div class="d-flex align-items-center gap-2">
                    <form method="GET" action="{{ route('hea.users.index') }}" class="d-flex align-items-center gap-2" id="userFilterForm">
                        <!-- Role Filter -->
                        <div class="d-flex align-items-center">
                            <label class="text-white me-2 mb-0 small">Filter by Role:</label>
                            <select name="role_filter" class="form-select form-select-sm" onchange="this.form.submit()" style="width: auto; min-width: 180px;">
                                <option value="all" {{ ($roleFilter ?? 'all') == 'all' ? 'selected' : '' }}>All Roles</option>
                                <option value="hea_personnel" {{ ($roleFilter ?? '') == 'hea_personnel' ? 'selected' : '' }}>HEA Personnel</option>
                                <option value="program_coordinator" {{ ($roleFilter ?? '') == 'program_coordinator' ? 'selected' : '' }}>Program Coordinator</option>
                                <option value="resource_person" {{ ($roleFilter ?? '') == 'resource_person' ? 'selected' : '' }}>Resource Person</option>
                                <option value="academic_advisor" {{ ($roleFilter ?? '') == 'academic_advisor' ? 'selected' : '' }}>Academic Advisor</option>
                                <option value="external_lecturer" {{ ($roleFilter ?? '') == 'external_lecturer' ? 'selected' : '' }}>External Lecturer</option>
                                <option value="student" {{ ($roleFilter ?? '') == 'student' ? 'selected' : '' }}>Student</option>
                            </select>
                        </div>

                        <!-- Sort Order -->
                        <div class="d-flex align-items-center">
                            <label class="text-white me-2 mb-0 small">Sort by:</label>
                            <select name="sort_order" class="form-select form-select-sm" onchange="this.form.submit()" style="width: auto; min-width: 150px;">
                                <option value="latest" {{ ($sortOrder ?? 'latest') == 'latest' ? 'selected' : '' }}>Latest to Oldest</option>
                                <option value="oldest" {{ ($sortOrder ?? '') == 'oldest' ? 'selected' : '' }}>Oldest to Latest</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if($users->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-users fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">No Users Found</h5>
                    @if(($roleFilter ?? 'all') !== 'all')
                        <p class="text-muted mb-0">No users found with the role: <strong>{{ ucfirst(str_replace('_', ' ', $roleFilter)) }}</strong></p>
                        <a href="{{ route('hea.users.index') }}" class="btn btn-sm btn-outline-primary mt-3">
                            <i class="fas fa-times me-1"></i>Clear Filters
                        </a>
                    @else
                        <p class="text-muted mb-0">There are no registered users in the system.</p>
                    @endif
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="20%">Name</th>
                                <th width="22%">Email</th>
                                <th width="15%">Role</th>
                                <th width="28%">Department & Program Assignments</th>
                                <th width="15%">Registered On</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-user-circle fa-2x text-muted me-2"></i>
                                        <strong>{{ $user->name }}</strong>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @php
                                        $displayRole = $user->current_role ?? $user->requested_role;
                                        $roleBadgeClass = match($displayRole) {
                                            'hea_personnel' => 'bg-danger',
                                            'program_coordinator' => 'bg-info',
                                            'resource_person' => 'bg-secondary',
                                            'academic_advisor' => 'bg-warning text-dark',
                                            'coordinator' => 'bg-info',
                                            'external_lecturer' => 'bg-purple text-white',
                                            'student' => 'bg-success',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $roleBadgeClass }}">
                                        {{ ucfirst(str_replace('_', ' ', $displayRole)) }}
                                    </span>
                                </td>
                                <td>
                                    @if($user->current_role === 'academic_advisor' && $user->academicAdvisor)
                                        <div>
                                            @if($user->academicAdvisor->assigned_programs && count($user->academicAdvisor->assigned_programs) > 0)
                                                <div class="d-flex flex-wrap gap-1">
                                                    @foreach($user->academicAdvisor->assigned_programs as $program)
                                                        <span class="badge bg-warning text-dark">{{ $program }}</span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <small class="text-muted">No programs assigned</small>
                                            @endif
                                        </div>
                                    @elseif($user->current_role === 'program_coordinator' && $user->programCoordinator)
                                        <div>
                                            @if($user->programCoordinator->program_codes && count($user->programCoordinator->program_codes) > 0)
                                                <div class="d-flex flex-wrap gap-1">
                                                    @foreach($user->programCoordinator->program_codes as $code)
                                                        <span class="badge bg-info">{{ $code }}</span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <small class="text-muted">No programs assigned</small>
                                            @endif
                                        </div>
                                    @elseif($user->current_role === 'resource_person' && $user->resourcePerson)
                                        <div>
                                            @if($user->resourcePerson->assigned_programs && count($user->resourcePerson->assigned_programs) > 0)
                                                <div class="d-flex flex-wrap gap-1">
                                                    @foreach($user->resourcePerson->assigned_programs as $program)
                                                        <span class="badge bg-secondary">{{ $program }}</span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <small class="text-muted">No programs assigned</small>
                                            @endif
                                        </div>
                                    @elseif($user->current_role === 'external_lecturer')
                                        <div class="d-flex flex-column gap-1">
                                            @if($user->externalLecturer)
                                                <div>
                                                    <i class="fas fa-university text-purple me-1"></i>
                                                    <small><strong>Institution:</strong> {{ $user->externalLecturer->institution_name ?? 'N/A' }}</small>
                                                </div>
                                            @endif
                                            @if(isset($user->external_submissions) && $user->external_submissions->count() > 0)
                                                <div>
                                                    <i class="fas fa-file-alt text-purple me-1"></i>
                                                    <small><strong>Syllabus Submitted:</strong></small>
                                                    <div class="d-flex flex-wrap gap-1 mt-1">
                                                        @foreach($user->external_submissions as $submission)
                                                            <span class="badge bg-purple text-white" title="{{ $submission->course_name }}">
                                                                {{ $submission->course_code }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @else
                                                @if(!$user->externalLecturer)
                                                    <small class="text-muted">No info available</small>
                                                @endif
                                            @endif
                                        </div>
                                    @else
                                        <small class="text-muted">-</small>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('d M Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Statistics Footer -->
                <div class="card-footer bg-light">
                    <div class="row text-center">
                        <div class="col-md-12">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                @if(($roleFilter ?? 'all') !== 'all' || ($sortOrder ?? 'latest') !== 'latest')
                                    Showing <strong>{{ $users->count() }}</strong> user(s)
                                    @if(($roleFilter ?? 'all') !== 'all')
                                        <span class="badge bg-primary ms-2">
                                            <i class="fas fa-filter me-1"></i>{{ ucfirst(str_replace('_', ' ', $roleFilter)) }}
                                        </span>
                                    @endif
                                    @if(($sortOrder ?? 'latest') !== 'latest')
                                        <span class="badge bg-info ms-2">
                                            <i class="fas fa-sort me-1"></i>{{ $sortOrder === 'oldest' ? 'Oldest First' : 'Latest First' }}
                                        </span>
                                    @endif
                                @else
                                    Total Users: <strong>{{ $users->count() }}</strong>
                                @endif
                            </small>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.bg-purple {
    background-color: #6f42c1 !important;
}
</style>

@endsection
