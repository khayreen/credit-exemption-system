@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-2"><i class="fas fa-file-signature me-2"></i>Course Equivalency Requests</h2>
                    <p class="text-muted">Review and process student equivalency requests</p>
                </div>
                <div>
                    <a href="{{ route('resource_person.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                    </a>
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

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-start border-primary border-4">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Unique Equivalencies</h6>
                    <h3 class="mb-0">{{ $groupedByEquivalency->count() }}</h3>
                    <small class="text-muted">Total requests to review</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-start border-warning border-4">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Pending Review</h6>
                    <h3 class="mb-0">{{ $groupedByEquivalency->filter(function($group) { return $group->first()->status === 'pending' || $group->first()->status === 'under_review'; })->count() }}</h3>
                    <small class="text-muted">Awaiting your decision</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-start border-success border-4">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Approved</h6>
                    <h3 class="mb-0">{{ $groupedByEquivalency->filter(function($group) { return $group->first()->status === 'approved'; })->count() }}</h3>
                    <small class="text-muted">Marked as equivalent</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-start border-danger border-4">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Rejected</h6>
                    <h3 class="mb-0">{{ $groupedByEquivalency->filter(function($group) { return $group->first()->status === 'rejected'; })->count() }}</h3>
                    <small class="text-muted">Not equivalent</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Unique Equivalencies Section (Review by Equivalency, NOT by Student) -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0"><i class="fas fa-clipboard-check me-2"></i>Equivalencies for Review</h5>
            <small>Each row represents a unique equivalency request. Multiple students may request the same equivalency.</small>
        </div>
        <div class="card-body">
            @if($groupedByEquivalency->isEmpty())
                <p class="text-center text-muted py-4">No equivalency requests pending review.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Diploma Course</th>
                                <th>Suggested Degree Course</th>
                                <th>Program</th>
                                <th class="text-center">Students</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($groupedByEquivalency as $key => $equivalencyGroup)
                                @php
                                    // Get the first request as representative
                                    $representative = $equivalencyGroup->first();
                                    $studentCount = $equivalencyGroup->count();
                                    $hasSyllabus = $representative->syllabus_received_at !== null;
                                @endphp
                                <tr class="{{ $hasSyllabus ? 'table-success' : '' }}">
                                    <td>
                                        <strong>{{ $representative->diploma_course_code }}</strong><br>
                                        <small class="text-muted">{{ Str::limit($representative->diploma_course_name, 40) }}</small><br>
                                        <small class="text-muted">{{ $representative->diploma_institution }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $representative->suggested_degree_course_code }}</strong><br>
                                        <small class="text-muted">{{ Str::limit($representative->suggested_degree_course_name, 40) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $representative->current_program_code }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info">{{ $studentCount }} student(s)</span>
                                    </td>
                                    <td>
                                        @if($hasSyllabus)
                                            <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Syllabus Received</span>
                                            <br><small class="text-success">Ready for Review!</small>
                                        @elseif($representative->syllabus_request_sent_at)
                                            <span class="badge bg-info">Awaiting Lecturer</span>
                                            <br><small class="text-muted">Email sent {{ $representative->syllabus_request_sent_at->diffForHumans() }}</small>
                                        @elseif($representative->status === 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @elseif($representative->status === 'under_review')
                                            <span class="badge bg-primary">Under Review</span>
                                        @elseif($representative->status === 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($representative->status === 'rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('resource_person.equivalency_requests.review', $representative->id) }}"
                                           class="btn btn-sm btn-primary">
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
    </div>

</div>
@endsection
