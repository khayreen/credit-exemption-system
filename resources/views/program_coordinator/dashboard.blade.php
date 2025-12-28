@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-2"><i class="fas fa-user-tie me-2"></i>Program Coordinator Dashboard</h2>
            <p class="text-muted mb-0">Managing {{ implode(', ', $coordinator->program_codes) }}</p>
        </div>
        <div>
            <a href="{{ route('program_coordinator.equivalency_lists.create') }}" class="btn btn-success">
                <i class="fas fa-plus-circle me-1"></i> Create New List
            </a>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h6 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Error</h6>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Quick Actions Bar -->
    <div class="card shadow-sm mb-4 border-0 bg-light">
        <div class="card-body py-3">
            <div class="row g-2">
                <div class="col-md-3">
                    <button type="button" class="btn btn-outline-primary w-100" onclick="document.getElementById('requests-tab').click()">
                        <i class="fas fa-inbox me-2"></i>Review Requests
                        @if($stats['total_requests'] > 0)
                            <span class="badge bg-danger ms-2">{{ $stats['total_requests'] }}</span>
                        @endif
                    </button>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('program_coordinator.pending_mappings.index') }}" class="btn btn-outline-success w-100">
                        <i class="fas fa-plus-circle me-2"></i>Add RP Mappings
                        @if($stats['pending_mappings'] > 0)
                            <span class="badge bg-warning text-dark ms-2">{{ $stats['pending_mappings'] }}</span>
                        @endif
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('program_coordinator.equivalency_lists.index') }}" class="btn btn-outline-info w-100">
                        <i class="fas fa-clipboard-list me-2"></i>Manage Lists
                        <span class="badge bg-secondary ms-2">{{ $stats['total_lists'] }}</span>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('program_coordinator.course_equivalencies.view') }}" class="btn btn-outline-dark w-100">
                        <i class="fas fa-list-alt me-2"></i>View All Mappings
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="text-decoration-none" style="cursor: pointer;" onclick="document.getElementById('requests-tab').click()">
                <div class="card shadow-sm border-start border-primary border-4 h-100 hover-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Requests Pending Review</h6>
                                <h2 class="mb-0">{{ $stats['total_requests'] }}</h2>
                                <small class="text-primary">Click to review</small>
                            </div>
                            <div class="text-primary">
                                <i class="fas fa-inbox fa-3x opacity-25"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <a href="{{ route('program_coordinator.pending_mappings.index') }}" class="text-decoration-none">
                <div class="card shadow-sm border-start border-success border-4 h-100 hover-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">RP Mappings Ready to Add</h6>
                                <h2 class="mb-0">{{ $stats['pending_mappings'] }}</h2>
                                <small class="text-success">Already validated by RP</small>
                            </div>
                            <div class="text-success">
                                <i class="fas fa-plus-circle fa-3x opacity-25"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3 mb-3">
            <a href="{{ route('program_coordinator.equivalency_lists.index') }}" class="text-decoration-none">
                <div class="card shadow-sm border-start border-info border-4 h-100 hover-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Equivalency Lists</h6>
                                <h2 class="mb-0">{{ $stats['total_lists'] }}</h2>
                                <small class="text-info">{{ $stats['draft_lists'] }} drafts</small>
                            </div>
                            <div class="text-info">
                                <i class="fas fa-clipboard-list fa-3x opacity-25"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3 mb-3">
            <a href="{{ route('program_coordinator.course_equivalencies.view') }}" class="text-decoration-none">
                <div class="card shadow-sm border-start border-warning border-4 h-100 hover-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Total Mappings Published</h6>
                                <h2 class="mb-0">{{ $stats['total_mappings'] }}</h2>
                                <small class="text-warning">Across all programs</small>
                            </div>
                            <div class="text-warning">
                                <i class="fas fa-list-alt fa-3x opacity-25"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Program Breakdown -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-gradient-primary text-white">
            <h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Programs Overview</h5>
        </div>
        <div class="card-body p-0">
            @if(count($programBreakdown) == 2)
                <!-- 2 Programs: Centered Layout -->
                <div class="row g-0 justify-content-center">
                    @foreach($programBreakdown as $program)
                        <div class="col-lg-6 col-md-6">
                            <div class="p-4 program-card" style="border-right: 1px solid #dee2e6; border-bottom: 1px solid #dee2e6;">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h6 class="text-primary mb-1">{{ $program['code'] }}</h6>
                                        <small class="text-muted">{{ $program['name'] }}</small>
                                    </div>
                                </div>
                                <div class="row text-center">
                                    <div class="col-4">
                                        <div class="metric">
                                            <h4 class="mb-0 text-primary">{{ $program['lists_count'] }}</h4>
                                            <small class="text-muted">Lists</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="metric">
                                            <h4 class="mb-0 text-info">{{ $program['mappings_count'] }}</h4>
                                            <small class="text-muted">Mappings</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="metric">
                                            <h4 class="mb-0 text-warning">{{ $program['requests_count'] }}</h4>
                                            <small class="text-muted">Requests</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <div class="btn-group btn-group-sm w-100">
                                        <a href="{{ route('program_coordinator.equivalency_lists.index') }}" class="btn btn-outline-primary">
                                            <i class="fas fa-clipboard-list"></i> Lists
                                        </a>
                                        <a href="{{ route('program_coordinator.course_equivalencies.view') }}" class="btn btn-outline-info">
                                            <i class="fas fa-list-alt"></i> Mappings
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- 3 Programs: Full Width Layout -->
                <div class="row g-0">
                    @foreach($programBreakdown as $program)
                        <div class="col-md-4">
                            <div class="p-4 border-end border-bottom program-card">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h6 class="text-primary mb-1">{{ $program['code'] }}</h6>
                                        <small class="text-muted">{{ $program['name'] }}</small>
                                    </div>
                                </div>
                                <div class="row text-center">
                                    <div class="col-4">
                                        <div class="metric">
                                            <h4 class="mb-0 text-primary">{{ $program['lists_count'] }}</h4>
                                            <small class="text-muted">Lists</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="metric">
                                            <h4 class="mb-0 text-info">{{ $program['mappings_count'] }}</h4>
                                            <small class="text-muted">Mappings</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="metric">
                                            <h4 class="mb-0 text-warning">{{ $program['requests_count'] }}</h4>
                                            <small class="text-muted">Requests</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <div class="btn-group btn-group-sm w-100">
                                        <a href="{{ route('program_coordinator.equivalency_lists.index') }}" class="btn btn-outline-primary">
                                            <i class="fas fa-clipboard-list"></i> Lists
                                        </a>
                                        <a href="{{ route('program_coordinator.course_equivalencies.view') }}" class="btn btn-outline-info">
                                            <i class="fas fa-list-alt"></i> Mappings
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Pending Work Tabs -->
    <div class="card shadow-sm">
        <div class="card-body">
            <!-- Tabs Navigation -->
            <ul class="nav nav-tabs nav-tabs-custom mb-3" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="requests-tab" data-bs-toggle="tab" data-bs-target="#requests" type="button" role="tab">
                        <i class="fas fa-inbox me-2"></i>Review Equivalency Requests
                        @if($stats['total_requests'] > 0)
                            <span class="badge bg-danger ms-2">{{ $stats['total_requests'] }}</span>
                        @endif
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="rp-mappings-tab" data-bs-toggle="tab" data-bs-target="#rp-mappings" type="button" role="tab">
                        <i class="fas fa-plus-circle me-2"></i>Add RP Mappings
                        @if($stats['pending_mappings'] > 0)
                            <span class="badge bg-success ms-2">{{ $stats['pending_mappings'] }}</span>
                        @endif
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="drafts-tab" data-bs-toggle="tab" data-bs-target="#drafts" type="button" role="tab">
                        <i class="fas fa-file-alt me-2"></i>Draft Lists
                        @if($stats['draft_lists'] > 0)
                            <span class="badge bg-warning text-dark ms-2">{{ $stats['draft_lists'] }}</span>
                        @endif
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content">
                <!-- Equivalency Requests Tab -->
                <div class="tab-pane fade show active" id="requests" role="tabpanel">
                    @if($groupedRequests->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-inbox text-muted fa-4x mb-3"></i>
                            <h5 class="text-muted">No Pending Requests</h5>
                            <p class="text-muted mb-0">There are no course equivalency requests awaiting your review.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Diploma Course</th>
                                        <th>Institution</th>
                                        <th>Students Requesting</th>
                                        <th>Different Lecturers</th>
                                        <th>Programs</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($groupedRequests as $courseCode => $requests)
                                        @php
                                            $firstRequest = $requests->first();
                                            $uniqueLecturers = $requests->pluck('external_lecturer_email')->unique();
                                            $uniquePrograms = $requests->pluck('current_program_code')->unique();
                                        @endphp
                                        <tr>
                                            <td>
                                                <strong>{{ $courseCode }}</strong><br>
                                                <small class="text-muted">{{ $firstRequest->diploma_course_name }}</small>
                                            </td>
                                            <td><small>{{ $firstRequest->diploma_institution }}</small></td>
                                            <td><span class="badge bg-info">{{ $requests->count() }} student(s)</span></td>
                                            <td><span class="badge bg-warning text-dark">{{ $uniqueLecturers->count() }} lecturer(s)</span></td>
                                            <td>
                                                @foreach($uniquePrograms as $program)
                                                    <span class="badge bg-secondary">{{ $program }}</span>
                                                @endforeach
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('program_coordinator.course_requests', $courseCode) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye me-1"></i>Review
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <!-- RP Mappings Tab -->
                <div class="tab-pane fade" id="rp-mappings" role="tabpanel">
                    @if($stats['pending_mappings'] == 0)
                        <div class="text-center py-5">
                            <i class="fas fa-check-circle text-success fa-4x mb-3"></i>
                            <h5 class="text-muted">No Pending RP Mappings</h5>
                            <p class="text-muted mb-0">All RP-validated mappings have been added to lists.</p>
                            <div class="mt-3">
                                <a href="{{ route('program_coordinator.pending_mappings.index') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-history me-2"></i>View History
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Note:</strong> These course mappings have been validated by Resource Person (superior).
                            Your role is to select the appropriate equivalency list and add them.
                        </div>
                        <div class="text-center py-4">
                            <a href="{{ route('program_coordinator.pending_mappings.index') }}" class="btn btn-success btn-lg">
                                <i class="fas fa-plus-circle me-2"></i>View & Add {{ $stats['pending_mappings'] }} RP Mapping(s)
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Draft Lists Tab -->
                <div class="tab-pane fade" id="drafts" role="tabpanel">
                    @if($stats['draft_lists'] == 0)
                        <div class="text-center py-5">
                            <i class="fas fa-check-circle text-success fa-4x mb-3"></i>
                            <h5 class="text-muted">No Draft Lists</h5>
                            <p class="text-muted mb-0">All lists have been published or archived.</p>
                            <div class="mt-3">
                                <a href="{{ route('program_coordinator.equivalency_lists.create') }}" class="btn btn-success">
                                    <i class="fas fa-plus-circle me-2"></i>Create New List
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>{{ $stats['draft_lists'] }} draft list(s)</strong> are ready to be reviewed and published.
                        </div>
                        <div class="text-center py-4">
                            <a href="{{ route('program_coordinator.equivalency_lists.index') }}" class="btn btn-warning">
                                <i class="fas fa-clipboard-list me-2"></i>Manage Draft Lists
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hover-card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.hover-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
}

.program-card {
    transition: background-color 0.2s ease;
}

.program-card:hover {
    background-color: #f8f9fa;
}

.nav-tabs-custom .nav-link {
    font-weight: 500;
    color: #6c757d;
}

.nav-tabs-custom .nav-link.active {
    font-weight: 600;
    color: #0d6efd;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.metric h4 {
    font-weight: 600;
}
</style>
@endsection
