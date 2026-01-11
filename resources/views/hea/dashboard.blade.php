@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">HEA Dashboard</h2>
        <p class="text-muted mb-0">Welcome back, {{ auth()->user()->name }}</p>
    </div>
    <div class="text-end">
        <small class="text-muted">{{ now()->format('l, F d, Y') }}</small>
    </div>
</div>

<!-- Key Metrics Row -->
<div class="row">
    <!-- Pending Endorsements Card -->
    <div class="col-md-3 mb-4">
        <a href="{{ route('hea.equivalency_lists.pending') }}" class="text-decoration-none">
            <div class="card stat-card border-info h-100 hover-shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="stat-icon me-3" style="background-color: #17a2b8;">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 text-info">{{ $stats['pending_endorsements'] }}</h3>
                        </div>
                    </div>
                    <p class="card-text text-muted mb-1 fw-bold">Pending Endorsements</p>
                    <small class="text-muted">
                        <span class="badge bg-primary">{{ $stats['pending_internal'] }} CS110</span>
                        @if($stats['pending_external'] > 0)
                        <span class="badge bg-success">{{ $stats['pending_external'] }} External</span>
                        @endif
                    </small>
                </div>
            </div>
        </a>
    </div>

    <!-- Published Lists Card -->
    <div class="col-md-3 mb-4">
        <a href="{{ route('hea.equivalency_lists.published') }}" class="text-decoration-none">
            <div class="card stat-card border-success h-100 hover-shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="stat-icon icon-green me-3">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 text-success">{{ $stats['published_lists'] }}</h3>
                        </div>
                    </div>
                    <p class="card-text text-muted mb-0 fw-bold">Published Lists</p>
                    <small class="text-muted">Equivalency lists endorsed</small>
                </div>
            </div>
        </a>
    </div>

    <!-- Pending Applications Card -->
    <div class="col-md-3 mb-4">
        <a href="{{ route('hea.applications.index') }}" class="text-decoration-none">
            <div class="card stat-card border-warning h-100 hover-shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="stat-icon icon-orange me-3">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 text-warning">{{ $stats['pending_applications'] }}</h3>
                        </div>
                    </div>
                    <p class="card-text text-muted mb-0 fw-bold">Active Applications</p>
                    <small class="text-muted">In progress or pending</small>
                </div>
            </div>
        </a>
    </div>

    <!-- User Management Card -->
    <div class="col-md-3 mb-4">
        <a href="{{ route('hea.users.index') }}" class="text-decoration-none">
            <div class="card stat-card border-primary h-100 hover-shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="stat-icon icon-blue me-3">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 text-primary">{{ $stats['total_users'] }}</h3>
                        </div>
                    </div>
                    <p class="card-text text-muted mb-1 fw-bold">Total Users</p>
                    <small class="text-muted">
                        @if($stats['pending_user_approvals'] > 0)
                        <span class="badge bg-warning text-dark">
                            <i class="fas fa-clock me-1"></i>{{ $stats['pending_user_approvals'] }} pending approval
                        </span>
                        @else
                        Registered in system
                        @endif
                    </small>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Main Widgets Section -->
<div class="row">
    <!-- Pending Endorsement Requests Section -->
    <div class="col-lg-6 mb-4">
        @if(isset($pendingLists) && $pendingLists->count() > 0)
        <div class="card shadow-sm h-100">
            <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Pending Endorsements</h5>
                <a href="{{ route('hea.equivalency_lists.pending') }}" class="btn btn-sm btn-light">
                    View All ({{ $stats['pending_endorsements'] }})
                </a>
            </div>
    <div class="card-body p-0">
        <div class="list-group list-group-flush">
            @foreach($pendingLists as $list)
            @php
                $daysWaiting = $list->submitted_at ? now()->diffInDays($list->submitted_at) : 0;
                $urgencyClass = $daysWaiting > 30 ? 'danger' : ($daysWaiting > 15 ? 'warning' : 'success');
            @endphp
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center mb-1">
                        @if($list->isInternal())
                            <span class="badge bg-primary me-2" title="Internal - UiTM Diploma">CS110</span>
                        @else
                            <span class="badge bg-success me-2" title="External Institution">{{ Str::limit($list->source_institution, 15) }}</span>
                        @endif
                        <strong>{{ $list->program_code }}</strong>
                        <span class="mx-2 text-muted">|</span>
                        <span>{{ $list->semester }}</span>
                        <span class="mx-2 text-muted">|</span>
                        <span class="text-muted">{{ $list->total_equivalencies }} mappings</span>
                        @if($daysWaiting > 15)
                        <span class="ms-2">
                            @if($daysWaiting > 30)
                                <span class="badge bg-danger">
                                    <i class="fas fa-exclamation-triangle me-1"></i>{{ $daysWaiting }} days
                                </span>
                            @else
                                <span class="badge bg-warning text-dark">
                                    <i class="fas fa-clock me-1"></i>{{ $daysWaiting }} days
                                </span>
                            @endif
                        </span>
                        @endif
                    </div>
                    <small class="text-muted">
                        <i class="fas fa-user me-1"></i>{{ $list->creator->name ?? 'Unknown' }}
                        <span class="mx-1">|</span>
                        <i class="fas fa-calendar me-1"></i>{{ $list->submitted_at?->format('d M Y, H:i') }}
                    </small>
                </div>
                <a href="{{ route('hea.equivalency_lists.review', $list) }}" class="btn btn-sm btn-primary ms-3">
                    <i class="fas fa-clipboard-check me-1"></i>Review
                </a>
            </div>
            @endforeach
        </div>
    </div>
            @if($pendingLists->count() < $stats['pending_endorsements'])
            <div class="card-footer bg-light text-center">
                <small class="text-muted">
                    Showing {{ $pendingLists->count() }} of {{ $stats['pending_endorsements'] }} pending lists
                </small>
            </div>
            @endif
        </div>
        @else
        <div class="card shadow-sm h-100">
            <div class="card-body text-center py-5">
                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                <h5>All Caught Up!</h5>
                <p class="text-muted mb-0">There are no equivalency lists waiting for endorsement at the moment.</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Pending User Approvals Section -->
    <div class="col-lg-6 mb-4">
        @if(isset($pendingUserApprovals) && $pendingUserApprovals->count() > 0)
        <div class="card shadow-sm h-100">
            <div class="card-header bg-warning text-dark py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-user-check me-2"></i>Pending User Approvals</h5>
                <a href="{{ route('hea.users.index') }}" class="btn btn-sm btn-dark">
                    View All ({{ $stats['pending_user_approvals'] }})
                </a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @foreach($pendingUserApprovals as $user)
                    @php
                        $daysWaiting = $user->created_at ? now()->diffInDays($user->created_at) : 0;
                        $urgencyClass = $daysWaiting > 7 ? 'danger' : ($daysWaiting > 3 ? 'warning' : 'success');

                        // Get role-specific data from User model (pending users don't have role records yet)
                        $roleBadge = '';
                        $roleLabel = '';
                        $requestedPrograms = [];

                        if ($user->requested_role === 'academic_advisor') {
                            $roleBadge = 'AA';
                            $roleLabel = 'Academic Advisor';
                            $requestedPrograms = $user->requested_programs ?? [];
                        } elseif ($user->requested_role === 'coordinator' || $user->requested_role === 'program_coordinator') {
                            $roleBadge = 'PC';
                            $roleLabel = 'Program Coordinator';
                            $requestedPrograms = $user->requested_programs ?? [];
                        } elseif ($user->requested_role === 'resource_person') {
                            $roleBadge = 'RP';
                            $roleLabel = 'Resource Person';
                            $requestedPrograms = $user->requested_programs ?? [];
                        }
                    @endphp
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center mb-1">
                                    <span class="badge bg-primary me-2 fw-bold">{{ $roleBadge }}</span>
                                    <strong>{{ $user->name }}</strong>
                                    @if($daysWaiting > 3)
                                    <span class="ms-2">
                                        @if($daysWaiting > 7)
                                            <span class="badge bg-danger">
                                                <i class="fas fa-exclamation-triangle me-1"></i>{{ $daysWaiting }} days
                                            </span>
                                        @else
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-clock me-1"></i>{{ $daysWaiting }} days
                                            </span>
                                        @endif
                                    </span>
                                    @endif
                                </div>
                                <small class="text-muted d-block">
                                    <i class="fas fa-envelope me-1"></i>{{ $user->email }}
                                </small>
                                <small class="text-muted d-block">
                                    <i class="fas fa-user-tag me-1"></i>{{ $roleLabel }}
                                </small>
                                @if(!empty($requestedPrograms))
                                <small class="text-muted d-block mt-1">
                                    <i class="fas fa-graduation-cap me-1"></i>Programs:
                                    @foreach($requestedPrograms as $program)
                                        <span class="badge bg-secondary">{{ $program }}</span>
                                    @endforeach
                                </small>
                                @endif
                                <small class="text-muted d-block mt-1">
                                    <i class="fas fa-calendar me-1"></i>Requested {{ $user->created_at?->format('d M Y') }}
                                </small>
                            </div>
                        </div>
                        <div class="d-flex gap-2 mt-2">
                            <form action="{{ route('hea.users.approve', $user) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Are you sure you want to approve {{ $user->name }} as {{ $roleLabel }}?')">
                                    <i class="fas fa-check me-1"></i>Approve
                                </button>
                            </form>
                            <a href="{{ route('hea.users.pending') }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye me-1"></i>View All
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @if($pendingUserApprovals->count() < $stats['pending_user_approvals'])
            <div class="card-footer bg-light text-center">
                <small class="text-muted">
                    Showing {{ $pendingUserApprovals->count() }} of {{ $stats['pending_user_approvals'] }} pending approvals
                </small>
            </div>
            @endif
        </div>
        @else
        <div class="card shadow-sm h-100">
            <div class="card-body text-center py-5">
                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                <h5>No Pending Approvals!</h5>
                <p class="text-muted mb-0">There are no user registrations waiting for approval at the moment.</p>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Quick Info Section -->
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-info-circle me-2 text-primary"></i>Your Responsibilities</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <strong>Endorse Equivalency Lists:</strong> Review and approve CS110 course mappings submitted by Resource Persons
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-user-check text-primary me-2"></i>
                        <strong>Approve User Registrations:</strong> Review and approve Academic Advisors, Program Coordinators, and Resource Persons
                    </li>
                    <li class="mb-0">
                        <i class="fas fa-chart-line text-info me-2"></i>
                        <strong>Monitor System Activity:</strong> Track applications, review audit logs, and ensure system integrity
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-lightbulb me-2 text-warning"></i>Quick Tips</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="fas fa-arrow-right text-muted me-2"></i>
                        Use the <strong>Pending Endorsements</strong> page to review equivalency lists in detail
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-arrow-right text-muted me-2"></i>
                        Check <strong>User Management</strong> regularly for pending registration approvals
                    </li>
                    <li class="mb-0">
                        <i class="fas fa-arrow-right text-muted me-2"></i>
                        Review <strong>System Logs</strong> to track important actions and changes
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
/* Hover effect for clickable stat cards */
.hover-shadow {
    transition: all 0.3s ease;
}

.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

/* Stat card styling */
.stat-card {
    border-top: 3px solid;
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.icon-blue {
    background-color: #0d6efd;
}

.icon-green {
    background-color: #198754;
}

.icon-orange {
    background-color: #fd7e14;
}

.icon-info {
    background-color: #17a2b8;
}
</style>

@endsection
