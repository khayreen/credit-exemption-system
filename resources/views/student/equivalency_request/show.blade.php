@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-2"><i class="fas fa-file-alt me-2"></i>Equivalency Request Details</h2>
                    <p class="text-muted">Request ID: {{ $request->id }}</p>
                </div>
                <a href="{{ route('student.equivalency.request.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Status Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header @php
                    echo match($request->status) {
                        'pending' => 'bg-warning text-dark',
                        'under_review' => 'bg-info text-white',
                        'syllabus_received' => 'bg-info text-white',
                        'approved' => 'bg-success text-white',
                        'rejected' => 'bg-secondary text-white',
                        default => 'bg-secondary text-white'
                    };
                @endphp">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Status:
                        @if($request->status === 'rejected')
                            <i class="fas fa-times-circle me-1"></i>NOT EQUIVALENT
                        @elseif($request->status === 'approved')
                            <i class="fas fa-check-circle me-1"></i>EQUIVALENT
                        @elseif($request->status === 'syllabus_received')
                            UNDER REVIEW
                        @else
                            {{ strtoupper(str_replace('_', ' ', $request->status)) }}
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Submitted:</strong> {{ $request->created_at->format('d F Y, h:i A') }}</p>
                    @if($request->reviewed_at)
                        <p class="mb-2"><strong>Reviewed:</strong> {{ $request->reviewed_at->format('d F Y, h:i A') }}</p>
                    @endif
                    @if($request->reviewer_notes)
                        <hr>
                        <p class="mb-1"><strong>Reviewer Notes:</strong></p>
                        <div class="alert alert-light">{{ $request->reviewer_notes }}</div>
                    @endif
                </div>
            </div>

            <!-- Diploma Course Information -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Diploma Course Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1 text-muted">Course Code</p>
                            <p class="mb-0"><strong>{{ $request->diploma_course_code }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 text-muted">Course Name</p>
                            <p class="mb-0">{{ $request->diploma_course_name }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1 text-muted">Institution</p>
                            <p class="mb-0">{{ $request->diploma_institution }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 text-muted">Program</p>
                            <p class="mb-0">{{ $request->diploma_program }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1 text-muted">Credit Hours</p>
                            <p class="mb-0">{{ $request->diploma_credit_hours }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Suggested Degree Course -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Your Suggested Degree Course</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Course Code:</strong> {{ $request->suggested_degree_course_code }}</p>
                    <p class="mb-0"><strong>Course Name:</strong> {{ $request->suggested_degree_course_name }}</p>
                </div>
            </div>

            <!-- Approved Equivalency Result -->
            @if($request->status === 'approved' && $request->approved_degree_course_code)
                <div class="card shadow-sm mb-4 border-success">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-check-circle me-2"></i>Approved Equivalency</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-2"><strong>Degree Course Code:</strong> {{ $request->approved_degree_course_code }}</p>
                        <p class="mb-2"><strong>Degree Course Name:</strong> {{ $request->approved_degree_course_name }}</p>
                        @if($request->match_percentage)
                            <p class="mb-0"><strong>Match Percentage:</strong> {{ $request->match_percentage }}%</p>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Justification -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Justification</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0" style="white-space: pre-wrap;">{{ $request->justification }}</p>
                </div>
            </div>

            <!-- External Lecturer Verification -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-user-tie me-2"></i>External Lecturer Information</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Lecturer Name:</strong> {{ $request->external_lecturer_name }}</p>
                    <p class="mb-3"><strong>Lecturer Email:</strong> {{ $request->external_lecturer_email }}</p>

                    @if($request->syllabus_received_at)
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Official Syllabus Received</strong><br>
                            <small>Received on: {{ $request->syllabus_received_at->format('d M Y, h:i A') }}</small>
                        </div>
                    @elseif($request->syllabus_request_sent_at)
                        <div class="alert alert-warning">
                            <i class="fas fa-clock me-2"></i>
                            <strong>Syllabus Request Sent to Lecturer</strong><br>
                            <small>Sent on: {{ $request->syllabus_request_sent_at->format('d M Y, h:i A') }}</small><br>
                            <small class="text-muted">The resource person has requested the official syllabus from your lecturer.</small>
                        </div>
                    @else
                        <div class="alert alert-light">
                            <i class="fas fa-info-circle me-2"></i>
                            <small class="text-muted">The resource person will request the official syllabus from your lecturer when reviewing your request.</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Current Program -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0"><i class="fas fa-user-graduate me-2"></i>Your Current Program</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Program Code:</strong><br>{{ $request->current_program_code }}</p>
                    <p class="mb-0"><strong>Program Name:</strong><br>{{ $request->current_program_name }}</p>
                </div>
            </div>

            <!-- Timeline -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h6 class="mb-0"><i class="fas fa-clock me-2"></i>Timeline</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <strong>Submitted</strong><br>
                            <small class="text-muted">{{ $request->created_at->format('d M Y, h:i A') }}</small>
                        </li>
                        @if($request->reviewed_at)
                            <li class="mb-0">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <strong>Reviewed</strong><br>
                                <small class="text-muted">{{ $request->reviewed_at->format('d M Y, h:i A') }}</small>
                            </li>
                        @else
                            <li class="mb-0">
                                <i class="fas fa-clock text-warning me-2"></i>
                                <strong>Pending Review</strong><br>
                                <small class="text-muted">Awaiting resource person review</small>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
