@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">
                <i class="fas fa-inbox me-2 text-primary"></i>Course Equivalency Requests
            </h2>
            <p class="text-muted mb-0">
                Review and process student requests for course equivalency mappings
            </p>
        </div>
        <div>
            <a href="{{ route('program_coordinator.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
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
            <i class="fas fa-exclamation-triangle me-2"></i>
            @foreach($errors->all() as $error)
                {{ $error }}
            @endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 stat-card stat-card-pending">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small text-uppercase fw-semibold">Pending Review</p>
                            <h2 class="mb-0 fw-bold">{{ $stats['pending'] }}</h2>
                        </div>
                        <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <span class="badge bg-warning text-dark">{{ $stats['unique_courses'] }} unique course(s)</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 stat-card stat-card-approved">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small text-uppercase fw-semibold">Approved</p>
                            <h2 class="mb-0 fw-bold text-success">{{ $stats['approved'] }}</h2>
                        </div>
                        <div class="stat-icon bg-success bg-opacity-10 text-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small class="text-muted">Marked as equivalent</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 stat-card stat-card-rejected">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small text-uppercase fw-semibold">Rejected</p>
                            <h2 class="mb-0 fw-bold text-danger">{{ $stats['rejected'] }}</h2>
                        </div>
                        <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                            <i class="fas fa-times-circle"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small class="text-muted">Not equivalent</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 stat-card stat-card-forwarded">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small text-uppercase fw-semibold">Forwarded</p>
                            <h2 class="mb-0 fw-bold text-info">{{ $stats['forwarded'] }}</h2>
                        </div>
                        <div class="stat-icon bg-info bg-opacity-10 text-info">
                            <i class="fas fa-share"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small class="text-muted">Sent to Resource Person</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="card shadow-sm border-0">
        <!-- Program Filter Tabs -->
        <div class="card-header bg-white border-bottom">
            <ul class="nav nav-tabs card-header-tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all-requests" type="button" role="tab">
                        <i class="fas fa-list me-2"></i>All Programs
                        @if($stats['pending'] > 0)
                            <span class="badge bg-primary ms-2">{{ $stats['pending'] }}</span>
                        @endif
                    </button>
                </li>
                @foreach($programBreakdown as $code => $program)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-{{ $code }}" data-bs-toggle="tab" data-bs-target="#program-{{ $code }}" type="button" role="tab">
                            {{ $code }}
                            @if($program['pending_count'] > 0)
                                <span class="badge bg-warning text-dark ms-1">{{ $program['pending_count'] }}</span>
                            @endif
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="card-body p-0">
            <div class="tab-content">
                <!-- All Programs Tab -->
                <div class="tab-pane fade show active" id="all-requests" role="tabpanel">
                    @if($groupedRequests->isEmpty())
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-inbox fa-4x text-muted opacity-50"></i>
                            </div>
                            <h5 class="text-muted fw-normal">No Pending Requests</h5>
                            <p class="text-muted mb-0">There are no course equivalency requests awaiting your review.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4" style="width: 30%">Diploma Course</th>
                                        <th style="width: 20%">Source Institution</th>
                                        <th class="text-center" style="width: 12%">Students</th>
                                        <th class="text-center" style="width: 12%">Lecturers</th>
                                        <th style="width: 15%">Programs</th>
                                        <th class="text-center pe-4" style="width: 11%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($groupedRequests as $courseCode => $courseRequests)
                                        @php
                                            $firstRequest = $courseRequests->first();
                                            $uniqueLecturers = $courseRequests->pluck('external_lecturer_email')->unique()->filter();
                                            $uniquePrograms = $courseRequests->pluck('current_program_code')->unique();
                                        @endphp
                                        <tr class="request-row">
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="course-icon me-3">
                                                        <i class="fas fa-book"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-semibold">{{ $courseCode }}</h6>
                                                        <small class="text-muted">{{ Str::limit($firstRequest->diploma_course_name, 40) }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-muted">
                                                    <i class="fas fa-university me-1"></i>
                                                    {{ Str::limit($firstRequest->diploma_institution, 30) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                                                    <i class="fas fa-users me-1"></i>{{ $courseRequests->count() }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2">
                                                    <i class="fas fa-chalkboard-teacher me-1"></i>{{ $uniqueLecturers->count() }}
                                                </span>
                                            </td>
                                            <td>
                                                @foreach($uniquePrograms as $program)
                                                    <span class="badge bg-light text-dark border me-1">{{ $program }}</span>
                                                @endforeach
                                            </td>
                                            <td class="text-center pe-4">
                                                <a href="{{ route('program_coordinator.course_requests', $courseCode) }}"
                                                   class="btn btn-primary btn-sm px-3">
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

                <!-- Individual Program Tabs -->
                @foreach($programBreakdown as $code => $program)
                    <div class="tab-pane fade" id="program-{{ $code }}" role="tabpanel">
                        @if($program['grouped']->isEmpty())
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <i class="fas fa-check-circle fa-4x text-success opacity-50"></i>
                                </div>
                                <h5 class="text-muted fw-normal">All Clear!</h5>
                                <p class="text-muted mb-0">No pending requests for {{ $program['name'] }}</p>
                            </div>
                        @else
                            <div class="p-3 bg-light border-bottom">
                                <h6 class="mb-0">
                                    <i class="fas fa-graduation-cap me-2 text-primary"></i>
                                    {{ $program['name'] }}
                                </h6>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4" style="width: 35%">Diploma Course</th>
                                            <th style="width: 25%">Source Institution</th>
                                            <th class="text-center" style="width: 15%">Students</th>
                                            <th class="text-center" style="width: 15%">Lecturers</th>
                                            <th class="text-center pe-4" style="width: 10%">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($program['grouped'] as $courseCode => $courseRequests)
                                            @php
                                                $firstRequest = $courseRequests->first();
                                                $uniqueLecturers = $courseRequests->pluck('external_lecturer_email')->unique()->filter();
                                            @endphp
                                            <tr class="request-row">
                                                <td class="ps-4">
                                                    <div class="d-flex align-items-center">
                                                        <div class="course-icon me-3">
                                                            <i class="fas fa-book"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0 fw-semibold">{{ $courseCode }}</h6>
                                                            <small class="text-muted">{{ Str::limit($firstRequest->diploma_course_name, 45) }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="text-muted">
                                                        <i class="fas fa-university me-1"></i>
                                                        {{ Str::limit($firstRequest->diploma_institution, 35) }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                                                        <i class="fas fa-users me-1"></i>{{ $courseRequests->count() }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2">
                                                        <i class="fas fa-chalkboard-teacher me-1"></i>{{ $uniqueLecturers->count() }}
                                                    </span>
                                                </td>
                                                <td class="text-center pe-4">
                                                    <a href="{{ route('program_coordinator.course_requests', $courseCode) }}"
                                                       class="btn btn-primary btn-sm px-3">
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
                @endforeach
            </div>
        </div>
    </div>

    <!-- Quick Info Section -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body py-3">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h6 class="mb-1"><i class="fas fa-info-circle me-2 text-info"></i>How to Review Requests</h6>
                            <p class="text-muted mb-0 small">
                                Click <strong>Review</strong> to see all student requests for a specific diploma course.
                                You can mark courses as <span class="text-success fw-semibold">Equivalent</span>,
                                <span class="text-danger fw-semibold">Not Equivalent</span>, or
                                <span class="text-info fw-semibold">Forward to Resource Person</span> for expert evaluation.
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <a href="{{ route('program_coordinator.course_equivalencies.view') }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-search me-1"></i>View All Course Mappings
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.stat-card {
    border-radius: 12px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,0.1) !important;
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.course-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.9rem;
}

.request-row {
    transition: background-color 0.15s ease;
}

.request-row:hover {
    background-color: #f8f9ff;
}

.nav-tabs .nav-link {
    border: none;
    color: #6c757d;
    font-weight: 500;
    padding: 0.75rem 1.25rem;
    border-radius: 0;
    border-bottom: 2px solid transparent;
}

.nav-tabs .nav-link:hover {
    color: #495057;
    border-color: transparent;
}

.nav-tabs .nav-link.active {
    color: #0d6efd;
    background-color: transparent;
    border-bottom: 2px solid #0d6efd;
}

.table > thead > tr > th {
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #6c757d;
    border-bottom-width: 1px;
}

.table > tbody > tr > td {
    padding: 1rem 0.75rem;
    vertical-align: middle;
}

.badge {
    font-weight: 500;
}
</style>
@endsection
