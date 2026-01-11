@extends('layouts.app')

@push('styles')
<style>
    .icon-green {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-0">Resource Person Dashboard</h2>
        @if(!empty($stats['assigned_programs']))
            <p class="text-muted mb-0 mt-2">
                <i class="fas fa-graduation-cap me-1"></i>
                <strong>Assigned Programs:</strong>
                @foreach($stats['assigned_programs'] as $index => $program)
                    <span class="badge bg-primary">{{ $program }}</span>{{ $index < count($stats['assigned_programs']) - 1 ? ', ' : '' }}
                @endforeach
            </p>
        @endif
    </div>
    <div>
        @if(!empty($stats['assigned_programs']))
            @foreach($stats['assigned_programs'] as $program)
                <a href="{{ route('resource_person.equivalency_lists.edit', $program) }}" class="btn btn-success me-2">
                    <i class="fas fa-edit"></i> Edit CS110 List for {{ $program }}
                </a>
            @endforeach
        @endif
    </div>
</div>

<!-- Stat Cards -->
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon icon-orange"><i class="fas fa-book-open"></i></div>
                <div>
                    <h5 class="card-title mb-0">{{ $stats['subjects_for_review'] }}</h5>
                    <p class="card-text text-muted">Subjects for Review</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon icon-blue"><i class="fas fa-paper-plane"></i></div>
                <div>
                    <h5 class="card-title mb-0">{{ $stats['syllabus_requests'] }}</h5>
                    <p class="card-text text-muted">Syllabus Requests Sent</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon icon-purple"><i class="fas fa-file-signature"></i></div>
                <div>
                    <h5 class="card-title mb-0">{{ $stats['equivalency_requests'] }}</h5>
                    <p class="card-text text-muted">Equivalency Requests</p>
                </div>
            </div>
        </div>
    </div>
    @if($stats['equivalency_syllabus_received'] > 0)
    <div class="col-md-4 mb-4">
        <div class="card stat-card border-success" style="border-width: 2px !important;">
            <div class="d-flex align-items-center">
                <div class="stat-icon icon-green"><i class="fas fa-check-circle"></i></div>
                <div>
                    <h5 class="card-title mb-0">{{ $stats['equivalency_syllabus_received'] }}</h5>
                    <p class="card-text text-success fw-bold">Syllabus Received! <span class="badge bg-success">NEW</span></p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Pending Subjects Table -->
<div class="card shadow-sm">
    <div class="card-header bg-light py-3">
        <h5 class="mb-0">Subjects Pending Your Expertise</h5>
    </div>
    <div class="card-body">
        @if($subjects->isEmpty())
            <p class="text-center text-muted">There are no subjects currently pending your review.</p>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Diploma Course Details</th>
                            <th>Student Name</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subjects as $subject)
                        <tr>
                            <td>
                                <strong>{{ $subject->course_code }}</strong> - {{ $subject->course_name }}<br>
                                <small class="text-muted">Institution: {{ $subject->exemptionApplication->previous_institution }}</small>
                            </td>
                            <td>
                                {{ $subject->exemptionApplication->student->user->name }}
                            </td>
                            <td class="text-end">
                                <a href="{{ route('resource_person.subject.review', $subject) }}" class="btn btn-sm btn-primary">Review Subject</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<!-- Equivalency Requests - Unique Equivalencies View -->
<div class="mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0"><i class="fas fa-file-signature me-2"></i>Course Equivalency Requests</h5>
        <a href="{{ route('resource_person.equivalency_requests.index') }}" class="btn btn-sm btn-outline-primary">
            <i class="fas fa-list me-1"></i>View All Requests
        </a>
    </div>

    @if($groupedByEquivalency->isEmpty())
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">No Equivalency Requests</h5>
                <p class="text-muted mb-0">There are no course equivalency requests awaiting your review.</p>
            </div>
        </div>
    @else
        <div class="card shadow-sm">
            <div class="card-header bg-light py-3">
                <small class="text-muted">Each row represents a unique equivalency. Multiple students may request the same equivalency.</small>
            </div>
            <div class="card-body p-0">
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
            </div>
        </div>
    @endif
</div>
@endsection
