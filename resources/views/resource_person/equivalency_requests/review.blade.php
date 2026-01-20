@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-2"><i class="fas fa-file-signature me-2"></i>Review Equivalency Request</h2>
                    <p class="text-muted">Request ID: {{ $request->id }}</p>
                </div>
                <a href="{{ route('resource_person.equivalency_requests.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Requests
                </a>
            </div>
        </div>
    </div>

    <!-- Error Messages -->
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h6 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Validation Errors</h6>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <!-- Students Affected Notice -->
            @php
                $affectedStudents = \App\Models\CourseEquivalencyRequest::where('diploma_course_code', $request->diploma_course_code)
                    ->where('suggested_degree_course_code', $request->suggested_degree_course_code)
                    ->where('current_program_code', $request->current_program_code)
                    ->where('status', '!=', 'approved')
                    ->where('status', '!=', 'rejected')
                    ->count();
            @endphp
            @if($affectedStudents > 1)
                <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
                    <h5 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Multiple Students Affected</h5>
                    <p class="mb-0">
                        <strong>{{ $affectedStudents }} student(s)</strong> have requested this same equivalency
                        (<strong>{{ $request->diploma_course_code }} → {{ $request->suggested_degree_course_code }}</strong> for <strong>{{ $request->current_program_code }}</strong>).
                        <br>
                        Your decision will automatically apply to all of them.
                    </p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Status Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header @php
                    // Check status and syllabus request state - use syllabus_received_at timestamp
                    if ($request->syllabus_received_at !== null) {
                        echo 'bg-success text-white';
                    } elseif ($request->syllabus_request_sent_at && !$request->syllabus_received_at) {
                        echo 'bg-info text-white';
                    } else {
                        echo match($request->status) {
                            'pending' => 'bg-warning text-dark',
                            'approved' => 'bg-success text-white',
                            'rejected' => 'bg-danger text-white',
                            default => 'bg-secondary text-white'
                        };
                    }
                @endphp">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Status:
                        @if($request->syllabus_received_at !== null && !in_array($request->status, ['approved', 'rejected']))
                            <i class="fas fa-check-circle me-1"></i>Syllabus Received - Ready for Review
                        @elseif($request->syllabus_request_sent_at && !$request->syllabus_received_at)
                            Awaiting Lecturer Response
                        @else
                            {{ strtoupper(str_replace('_', ' ', $request->status)) }}
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Submitted:</strong> {{ $request->created_at->format('d F Y, h:i A') }}</p>
                    @if($request->syllabus_request_sent_at)
                        <p class="mb-2"><strong>Syllabus Requested:</strong> {{ $request->syllabus_request_sent_at->format('d F Y, h:i A') }}</p>
                    @endif
                    @if($request->reviewed_at)
                        <p class="mb-2"><strong>Reviewed:</strong> {{ $request->reviewed_at->format('d F Y, h:i A') }}</p>
                        <p class="mb-2"><strong>Reviewed By:</strong> {{ $request->reviewer->user->name ?? 'N/A' }}</p>
                    @endif
                    @if($request->reviewer_notes)
                        <hr>
                        <p class="mb-1"><strong>Reviewer Notes:</strong></p>
                        <div class="alert alert-light">{{ $request->reviewer_notes }}</div>
                    @endif
                </div>
            </div>

            <!-- Course Comparison - Side by Side -->
            <div class="row mb-4">
                <!-- Diploma Course Information -->
                <div class="col-lg-6 mb-3 mb-lg-0">
                    <div class="card shadow-sm h-100 border-primary">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Diploma Course (From)</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <p class="mb-1 text-muted small">Course Code</p>
                                <p class="mb-0 fs-5"><strong>{{ $request->diploma_course_code }}</strong></p>
                            </div>
                            <div class="mb-3">
                                <p class="mb-1 text-muted small">Course Name</p>
                                <p class="mb-0">{{ $request->diploma_course_name }}</p>
                            </div>
                            <div class="mb-3">
                                <p class="mb-1 text-muted small">Institution</p>
                                <p class="mb-0">{{ $request->diploma_institution }}</p>
                            </div>
                            <div>
                                <p class="mb-1 text-muted small">Program</p>
                                <p class="mb-0">{{ $request->diploma_program }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Student's Suggested Degree Course -->
                <div class="col-lg-6">
                    <div class="card shadow-sm h-100 border-success">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0"><i class="fas fa-university me-2"></i>Degree Course (To)</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <p class="mb-1 text-muted small">Course Code</p>
                                <p class="mb-0 fs-5"><strong>{{ $request->suggested_degree_course_code }}</strong></p>
                            </div>
                            <div class="mb-3">
                                <p class="mb-1 text-muted small">Course Name</p>
                                <p class="mb-0">{{ $request->suggested_degree_course_name }}</p>
                            </div>
                            <div class="mb-3">
                                <p class="mb-1 text-muted small">Program</p>
                                <p class="mb-0">{{ $request->current_program_code }} - {{ $request->current_program_name }}</p>
                            </div>
                            <div>
                                <p class="mb-1 text-muted small">Suggested By</p>
                                <p class="mb-0"><span class="badge bg-info">Student</span></p>
                            </div>
                        </div>
                    </div>
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

            <!-- External Lecturer Verification (Combined Card) -->
            @php
                // Use PC-selected lecturer if available, otherwise use student-provided lecturer
                $lecturerName = $request->selected_lecturer_name ?? $request->external_lecturer_name;
                $lecturerEmail = $request->selected_lecturer_email ?? $request->external_lecturer_email;
                $lecturerSource = $request->selected_lecturer_name ? 'Selected by PC' : 'Provided by Student';
                $submission = $request->externalLecturerRequest?->submission;
            @endphp
            @if($lecturerName && $lecturerEmail)
            <div class="card shadow-sm mb-4 {{ $request->syllabus_received_at ? 'border-success' : '' }}">
                <div class="card-header {{ $request->syllabus_received_at ? 'bg-success' : 'bg-secondary' }} text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-user-tie me-2"></i>External Lecturer Verification
                        @if($request->syllabus_received_at)
                            <span class="badge bg-light text-success ms-2"><i class="fas fa-check me-1"></i>Syllabus Received</span>
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Lecturer Info -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1 text-muted small">Lecturer Name</p>
                            <p class="mb-0"><strong>{{ $lecturerName }}</strong> <span class="badge bg-secondary">{{ $lecturerSource }}</span></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 text-muted small">Lecturer Email</p>
                            <p class="mb-0">{{ $lecturerEmail }}</p>
                        </div>
                    </div>

                    <hr class="my-3">

                    <!-- State-based Content -->
                    @if($request->syllabus_received_at && $submission)
                        {{-- STATE: Syllabus Received --}}
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-success text-white rounded-circle p-2 me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <strong class="text-success">Syllabus Received</strong><br>
                                <small class="text-muted">{{ $request->syllabus_received_at->format('d M Y, h:i A') }}</small>
                            </div>
                        </div>

                        {{-- Submitted Course Details --}}
                        <div class="bg-light rounded p-3 mb-3">
                            <h6 class="text-muted mb-3"><i class="fas fa-file-alt me-2"></i>Submitted Course Details</h6>
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <p class="mb-1 text-muted small">Course Code</p>
                                    <p class="mb-0"><strong>{{ $submission->course_code }}</strong></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1 text-muted small">Course Name</p>
                                    <p class="mb-0">{{ $submission->course_name }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1 text-muted small">Institution</p>
                                    <p class="mb-0">{{ $submission->institution_name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1 text-muted small">Credit Hours</p>
                                    <p class="mb-0"><strong>{{ number_format($submission->credit_hours, 2) }}</strong></p>
                                </div>
                            </div>
                            @if($submission->justification_notes)
                                <hr class="my-2">
                                <p class="mb-1 text-muted small">Lecturer's Justification</p>
                                <p class="mb-0 fst-italic">"{{ $submission->justification_notes }}"</p>
                            @endif
                        </div>

                        {{-- Action Buttons --}}
                        <div class="d-flex gap-2">
                            <a href="{{ route('resource_person.external_submission.view_syllabus', $submission) }}"
                               class="btn btn-outline-success" target="_blank">
                                <i class="fas fa-file-pdf me-1"></i>View Syllabus PDF
                            </a>
                            <a href="{{ route('resource_person.equivalency_requests.compare', $request) }}" class="btn btn-success">
                                <i class="fas fa-columns me-1"></i>Compare with Degree Syllabi
                            </a>
                        </div>

                    @elseif($request->syllabus_request_sent_at)
                        {{-- STATE: Awaiting Response --}}
                        <div class="d-flex align-items-center">
                            <div class="bg-info text-white rounded-circle p-2 me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <strong class="text-info">Awaiting Lecturer Response</strong><br>
                                <small class="text-muted">Syllabus requested on {{ $request->syllabus_request_sent_at->format('d M Y, h:i A') }}</small><br>
                                <small class="text-muted">Waiting for lecturer to submit the official syllabus...</small>
                            </div>
                        </div>

                    @else
                        {{-- STATE: Not Requested --}}
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-warning text-dark rounded-circle p-2 me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <strong class="text-warning">Syllabus Not Yet Requested</strong><br>
                                <small class="text-muted">Request the official course syllabus from the lecturer for verification.</small>
                            </div>
                        </div>
                        <a href="{{ route('resource_person.equivalency_requests.preview_email', $request) }}" class="btn btn-primary">
                            <i class="fas fa-envelope-open-text me-2"></i>Preview & Send Email to Lecturer
                        </a>
                    @endif
                </div>
            </div>
            @endif

            <!-- Review Form - Only show when syllabus NOT received (for quick reject without comparison) -->
            @if(!$request->syllabus_received_at && in_array($request->status, ['pending', 'under_review']))
                <div class="card shadow-sm mb-4 border-warning">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Quick Decision (No Syllabus)</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning mb-3">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Note:</strong> No official syllabus has been received yet. You may reject this request if clearly not equivalent, or request the syllabus from the external lecturer for proper comparison.
                        </div>

                        <form action="{{ route('resource_person.equivalency_requests.process', $request) }}" method="POST" id="reviewForm">
                            @csrf

                            <!-- Diploma Course (Student Submitted) -->
                            <div class="card bg-light mb-3">
                                <div class="card-body">
                                    <h6 class="text-muted mb-2"><i class="fas fa-graduation-cap me-2"></i>Diploma Course</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-1 text-muted small">Course Code</p>
                                            <p class="mb-0"><strong>{{ $request->diploma_course_code }}</strong></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-1 text-muted small">Course Name</p>
                                            <p class="mb-0">{{ $request->diploma_course_name }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Suggested Degree Course (Student Submitted) -->
                            <div class="card bg-light mb-3">
                                <div class="card-body">
                                    <h6 class="text-muted mb-2"><i class="fas fa-lightbulb me-2"></i>Suggested Degree Course</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-1 text-muted small">Course Code</p>
                                            <p class="mb-0"><strong>{{ $request->suggested_degree_course_code }}</strong></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-1 text-muted small">Course Name</p>
                                            <p class="mb-0">{{ $request->suggested_degree_course_name }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Match Percentage Input -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Match Percentage <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="match_percentage" class="form-control"
                                           value="{{ old('match_percentage', 0) }}" min="0" max="100" step="1" required>
                                    <span class="input-group-text">%</span>
                                </div>
                                <small class="text-muted">For rejection without syllabus comparison, typically enter a low percentage (0-50%).</small>
                            </div>

                            <!-- Decision -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Decision <span class="text-danger">*</span></label>
                                <div class="btn-group w-100" role="group">
                                    <input type="radio" class="btn-check" name="decision" id="decision_approve" value="approved" required>
                                    <label class="btn btn-outline-success" for="decision_approve">
                                        <i class="fas fa-check-circle me-1"></i>Equivalent
                                    </label>

                                    <input type="radio" class="btn-check" name="decision" id="decision_reject" value="rejected" required>
                                    <label class="btn btn-outline-danger" for="decision_reject">
                                        <i class="fas fa-times-circle me-1"></i>Not Equivalent
                                    </label>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('resource_person.equivalency_requests.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                                    <i class="fas fa-paper-plane me-2"></i>Submit Decision
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Program Information -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h6 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Program Information</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Program Code:</strong><br>
                        <span class="badge bg-primary">{{ $request->current_program_code }}</span>
                    </p>
                    <p class="mb-0"><strong>Program Name:</strong><br>
                        {{ $request->current_program_name }}
                    </p>
                </div>
            </div>

            <!-- Coordinator Information -->
            @if($request->coordinator)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="fas fa-user-tie me-2"></i>Forwarded By</h6>
                </div>
                <div class="card-body">
                    <p class="mb-0"><strong>Program Coordinator:</strong><br>{{ $request->coordinator->name }}</p>
                </div>
            </div>
            @endif

            <!-- Timeline -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
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
                                <small class="text-muted">Awaiting resource person decision</small>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@if(!$request->syllabus_received_at && in_array($request->status, ['pending', 'under_review']))
<script>
document.addEventListener('DOMContentLoaded', function() {
    const approveRadio = document.getElementById('decision_approve');
    const rejectRadio = document.getElementById('decision_reject');
    const submitBtn = document.getElementById('submitBtn');

    // Enable submit button when a decision is selected
    function enableSubmitButton() {
        if (approveRadio.checked || rejectRadio.checked) {
            submitBtn.disabled = false;
        }
    }

    approveRadio.addEventListener('change', enableSubmitButton);
    rejectRadio.addEventListener('change', enableSubmitButton);

    // Initialize on page load
    if (approveRadio.checked || rejectRadio.checked) {
        enableSubmitButton();
    }
});
</script>
@endif
@endsection
