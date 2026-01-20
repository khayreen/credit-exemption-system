@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="welcome-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-1 text-white">
                            <i class="fas fa-user-tie me-2"></i>Welcome back, {{ explode(' ', Auth::user()->name)[0] }}!
                        </h2>
                        <p class="mb-0 text-white-50">
                            Program Coordinator &mdash; Managing {{ implode(', ', $coordinator->program_codes) }}
                        </p>
                    </div>
                    <div class="d-none d-md-block">
                        <span class="badge bg-white text-primary px-3 py-2">
                            <i class="fas fa-calendar-alt me-2"></i>{{ now()->format('l, d F Y') }}
                        </span>
                    </div>
                </div>
            </div>
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

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-3">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-4">
                            <a href="{{ route('program_coordinator.equivalency_requests.index') }}" class="btn btn-primary w-100 py-3 quick-action-btn">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="fas fa-inbox fa-lg me-3"></i>
                                    <div class="text-start">
                                        <span class="d-block fw-semibold">Review Requests</span>
                                        <small class="opacity-75">{{ $stats['total_requests'] }} pending</small>
                                    </div>
                                    @if($stats['total_requests'] > 0)
                                        <span class="badge bg-danger ms-auto">{{ $stats['total_requests'] }}</span>
                                    @endif
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('program_coordinator.equivalency_lists.index') }}" class="btn btn-outline-primary w-100 py-3 quick-action-btn">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="fas fa-clipboard-list fa-lg me-3"></i>
                                    <div class="text-start">
                                        <span class="d-block fw-semibold">Published Lists</span>
                                        <small class="text-muted">{{ $stats['total_lists'] }} lists</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('program_coordinator.course_equivalencies.view') }}" class="btn btn-outline-secondary w-100 py-3 quick-action-btn">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="fas fa-search fa-lg me-3"></i>
                                    <div class="text-start">
                                        <span class="d-block fw-semibold">All Course Mappings</span>
                                        <small class="text-muted">Search & manage</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Overview -->
    <div class="row mb-4">
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon-wrapper bg-primary-soft me-3">
                            <i class="fas fa-inbox text-primary"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1 small text-uppercase">Pending Requests</h6>
                            <h2 class="mb-0 fw-bold">{{ $stats['total_requests'] }}</h2>
                        </div>
                        @if($stats['total_requests'] > 0)
                            <a href="{{ route('program_coordinator.equivalency_requests.index') }}" class="btn btn-sm btn-primary">
                                Review <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        @else
                            <span class="badge bg-success-soft text-success">All Clear</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon-wrapper bg-info-soft me-3">
                            <i class="fas fa-clipboard-list text-info"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1 small text-uppercase">Equivalency Lists</h6>
                            <h2 class="mb-0 fw-bold">{{ $stats['total_lists'] }}</h2>
                        </div>
                        <span class="text-muted small">
                            <i class="fas fa-layer-group me-1"></i>All programs
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon-wrapper bg-success-soft me-3">
                            <i class="fas fa-exchange-alt text-success"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1 small text-uppercase">Course Mappings</h6>
                            <h2 class="mb-0 fw-bold">{{ $stats['total_mappings'] }}</h2>
                        </div>
                        <span class="text-muted small">
                            <i class="fas fa-check-circle me-1"></i>Published
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Programs Overview -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-graduation-cap me-2 text-primary"></i>Programs Overview
                        </h5>
                        <span class="badge bg-primary-soft text-primary">
                            {{ count($programBreakdown) }} Program(s) Managed
                        </span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="row g-0">
                        @foreach($programBreakdown as $index => $program)
                            <div class="col-lg-{{ count($programBreakdown) == 2 ? '6' : '4' }} {{ !$loop->last ? 'border-end' : '' }}">
                                <div class="program-card p-4">
                                    <!-- Program Header -->
                                    <div class="d-flex align-items-start mb-4">
                                        <div class="program-badge me-3">
                                            {{ substr($program['code'], -3) }}
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-bold text-primary">{{ $program['code'] }}</h6>
                                            <small class="text-muted d-block" style="line-height: 1.3;">{{ $program['name'] }}</small>
                                        </div>
                                    </div>

                                    <!-- Statistics Grid -->
                                    <div class="row g-3 mb-4">
                                        <div class="col-4">
                                            <div class="text-center p-2 rounded bg-light">
                                                <h4 class="mb-0 fw-bold text-primary">{{ $program['lists_count'] }}</h4>
                                                <small class="text-muted">Lists</small>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="text-center p-2 rounded bg-light">
                                                <h4 class="mb-0 fw-bold text-info">{{ $program['mappings_count'] }}</h4>
                                                <small class="text-muted">Mappings</small>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="text-center p-2 rounded {{ $program['requests_count'] > 0 ? 'bg-warning bg-opacity-10' : 'bg-light' }}">
                                                <h4 class="mb-0 fw-bold {{ $program['requests_count'] > 0 ? 'text-warning' : 'text-success' }}">
                                                    {{ $program['requests_count'] }}
                                                </h4>
                                                <small class="text-muted">Requests</small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Quick Actions -->
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('program_coordinator.equivalency_lists.index') }}"
                                           class="btn btn-outline-primary btn-sm flex-grow-1">
                                            <i class="fas fa-clipboard-list me-1"></i>View Lists
                                        </a>
                                        <a href="{{ route('program_coordinator.course_equivalencies.view') }}"
                                           class="btn btn-outline-secondary btn-sm flex-grow-1">
                                            <i class="fas fa-search me-1"></i>Mappings
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity / Help Section -->
    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0">
                        <i class="fas fa-lightbulb me-2 text-warning"></i>Quick Tips
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="tip-icon me-3">
                                    <i class="fas fa-inbox text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Review Requests</h6>
                                    <p class="text-muted small mb-0">
                                        Process student requests for new course equivalencies. Approve, reject, or forward to Resource Person.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="tip-icon me-3">
                                    <i class="fas fa-clipboard-list text-info"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Published Lists</h6>
                                    <p class="text-muted small mb-0">
                                        View and download official equivalency lists that have been endorsed by HEA.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="tip-icon me-3">
                                    <i class="fas fa-search text-success"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">All Course Mappings</h6>
                                    <p class="text-muted small mb-0">
                                        Search and browse all course equivalency mappings across all programs.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="tip-icon me-3">
                                    <i class="fas fa-share text-secondary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Forward to RP</h6>
                                    <p class="text-muted small mb-0">
                                        Uncertain about a request? Forward it to a Resource Person for expert evaluation.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0">
                        <i class="fas fa-bolt me-2 text-warning"></i>Quick Links
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('program_coordinator.equivalency_requests.index') }}" class="btn btn-light text-start py-2">
                            <i class="fas fa-inbox me-2 text-primary"></i>
                            Equivalency Requests
                            @if($stats['total_requests'] > 0)
                                <span class="badge bg-danger float-end">{{ $stats['total_requests'] }}</span>
                            @endif
                        </a>
                        <a href="{{ route('program_coordinator.equivalency_lists.index') }}" class="btn btn-light text-start py-2">
                            <i class="fas fa-clipboard-list me-2 text-info"></i>
                            Published Lists
                        </a>
                        <a href="{{ route('program_coordinator.course_equivalencies.view') }}" class="btn btn-light text-start py-2">
                            <i class="fas fa-search me-2 text-success"></i>
                            Search Mappings
                        </a>
                        <a href="{{ route('profile.show') }}" class="btn btn-light text-start py-2">
                            <i class="fas fa-user-edit me-2 text-secondary"></i>
                            My Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.welcome-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
}

.stat-card {
    border-radius: 12px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}

.stat-icon-wrapper {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.bg-primary-soft {
    background-color: rgba(13, 110, 253, 0.1);
}

.bg-info-soft {
    background-color: rgba(13, 202, 240, 0.1);
}

.bg-success-soft {
    background-color: rgba(25, 135, 84, 0.1);
}

.bg-warning-soft {
    background-color: rgba(255, 193, 7, 0.1);
}

.quick-action-btn {
    border-radius: 10px;
    transition: all 0.2s ease;
}

.quick-action-btn:hover {
    transform: translateY(-2px);
}

.program-card {
    transition: background-color 0.2s ease;
    min-height: 100%;
}

.program-card:hover {
    background-color: #f8f9ff;
}

.program-badge {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 0.9rem;
}

.tip-icon {
    width: 40px;
    height: 40px;
    background-color: #f8f9fa;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.btn-light {
    background-color: #f8f9fa;
    border: 1px solid #e9ecef;
}

.btn-light:hover {
    background-color: #e9ecef;
    border-color: #dee2e6;
}
</style>
@endsection
