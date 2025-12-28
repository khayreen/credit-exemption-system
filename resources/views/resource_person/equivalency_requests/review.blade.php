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
                    // Check status and syllabus request state
                    if ($request->status === 'syllabus_received') {
                        echo 'bg-success text-white';
                    } elseif ($request->status === 'pending' && $request->syllabus_request_sent_at) {
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
                        @if($request->status === 'syllabus_received')
                            <i class="fas fa-check-circle me-1"></i>Syllabus Received - Ready for Review
                        @elseif($request->status === 'pending' && $request->syllabus_request_sent_at)
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

            <!-- Student's Suggested Degree Course -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Student's Suggested Degree Course</h5>
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

            <!-- External Lecturer Syllabus Verification -->
            @if($request->selected_lecturer_name && $request->selected_lecturer_email)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-user-tie me-2"></i>External Lecturer Verification</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1 text-muted">Lecturer Name (Selected by PC)</p>
                            <p class="mb-0"><strong>{{ $request->selected_lecturer_name }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 text-muted">Lecturer Email</p>
                            <p class="mb-0">{{ $request->selected_lecturer_email }}</p>
                        </div>
                    </div>

                    <!-- Syllabus Request Status -->
                    @if($request->syllabus_received_at)
                        <div class="alert alert-success mb-0">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Official Syllabus Received</strong><br>
                            <small>Received on: {{ $request->syllabus_received_at->format('d M Y, h:i A') }}</small>
                            @if($request->externalLecturerRequest && $request->externalLecturerRequest->submission)
                                <br>
                                <a href="{{ route('resource_person.external_submission.view_syllabus', $request->externalLecturerRequest->submission) }}"
                                   class="btn btn-sm btn-success mt-2" target="_blank">
                                    <i class="fas fa-file-pdf me-1"></i>View Official Syllabus
                                </a>
                            @endif
                        </div>
                    @elseif($request->syllabus_request_sent_at)
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-clock me-2"></i>
                            <strong>Awaiting Lecturer Response</strong><br>
                            <small>Syllabus requested on: {{ $request->syllabus_request_sent_at->format('d M Y, h:i A') }}</small><br>
                            <small class="text-muted">Waiting for lecturer to submit the syllabus...</small>
                        </div>
                    @else
                        <div class="alert alert-info mb-3">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Official Syllabus Required</strong><br>
                            <small>Request official course syllabus directly from the lecturer for verification.</small>
                        </div>
                        <a href="{{ route('resource_person.equivalency_requests.preview_email', $request) }}" class="btn btn-primary">
                            <i class="fas fa-envelope-open-text me-2"></i>Preview & Send Email to Lecturer
                        </a>
                    @endif
                </div>
            </div>
            @endif

            <!-- Review Form (if pending or syllabus received) -->
            @if(in_array($request->status, ['pending', 'syllabus_received']))
                <div class="card shadow-sm mb-4 border-primary">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-clipboard-check me-2"></i>Review Decision</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('resource_person.equivalency_requests.process', $request) }}" method="POST" id="reviewForm">
                            @csrf

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

                            <!-- Approval Fields (shown when equivalent is selected) -->
                            <div id="approval_fields" style="display: none;">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Note:</strong> Your decision will apply to all students requesting this equivalency. A course equivalency record will be automatically created in the system.
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Approved Degree Course Code <span class="text-danger">*</span></label>
                                        <select name="approved_degree_course_code" id="degree_course_select" class="form-select">
                                            <option value="">Select Course</option>
                                            @foreach($degreeCourses as $course)
                                                <option value="{{ $course->code }}"
                                                        data-name="{{ $course->name }}"
                                                        {{ old('approved_degree_course_code', $request->suggested_degree_course_code) == $course->code ? 'selected' : '' }}>
                                                    {{ $course->code }} - {{ $course->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Approved Degree Course Name</label>
                                        <input type="text" name="approved_degree_course_name" id="degree_course_name" class="form-control"
                                               value="{{ old('approved_degree_course_name', $request->suggested_degree_course_name) }}" readonly>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Match Percentage <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" name="match_percentage" class="form-control"
                                               value="{{ old('match_percentage', 85) }}" min="0" max="100" step="1">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <small class="text-muted">Enter the equivalency match percentage (0-100)</small>
                                </div>
                            </div>

                            <!-- Reviewer Notes -->
                            <div class="mb-3">
                                <label class="form-label">Reviewer Notes (Optional)</label>
                                <textarea name="reviewer_notes" class="form-control" rows="4"
                                          placeholder="Add any notes or comments about your decision...">{{ old('reviewer_notes') }}</textarea>
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

@if(in_array($request->status, ['pending', 'syllabus_received']))
<script>
document.addEventListener('DOMContentLoaded', function() {
    const approveRadio = document.getElementById('decision_approve');
    const rejectRadio = document.getElementById('decision_reject');
    const approvalFields = document.getElementById('approval_fields');
    const submitBtn = document.getElementById('submitBtn');
    const degreeSelect = document.getElementById('degree_course_select');
    const degreeNameInput = document.getElementById('degree_course_name');

    // Enable/disable approval fields based on decision
    function updateApprovalFields() {
        if (approveRadio.checked) {
            approvalFields.style.display = 'block';
            degreeSelect.required = true;
            document.querySelector('input[name="match_percentage"]').required = true;
        } else {
            approvalFields.style.display = 'none';
            degreeSelect.required = false;
            document.querySelector('input[name="match_percentage"]').required = false;
        }
        submitBtn.disabled = false;
    }

    approveRadio.addEventListener('change', updateApprovalFields);
    rejectRadio.addEventListener('change', updateApprovalFields);

    // Auto-fill degree course name when course is selected
    degreeSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const courseName = selectedOption.getAttribute('data-name');
        degreeNameInput.value = courseName || '';
    });

    // Initialize on page load
    if (approveRadio.checked || rejectRadio.checked) {
        updateApprovalFields();
    }
});
</script>
@endif
@endsection
