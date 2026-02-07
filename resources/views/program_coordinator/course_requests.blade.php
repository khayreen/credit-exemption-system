@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-2">
                        <i class="fas fa-book me-2"></i>{{ $diplomaCourseCode }}
                    </h2>
                    <p class="text-muted">{{ $requests->first()->diploma_course_name }}</p>
                </div>
                <a href="{{ route('program_coordinator.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Course Information -->
    @php
        $verifiedCount = $requests->filter(fn($r) => isset($r->transcript_validation['status']) && $r->transcript_validation['status'] === 'verified')->count();
        $notInTranscriptCount = $requests->filter(fn($r) => isset($r->transcript_validation['status']) && $r->transcript_validation['status'] === 'not_in_transcript')->count();
        $noApplicationCount = $requests->filter(fn($r) => isset($r->transcript_validation['status']) && $r->transcript_validation['status'] === 'no_application')->count();
        $invalidRequestIds = $requests->filter(fn($r) => isset($r->transcript_validation['status']) && $r->transcript_validation['status'] === 'not_in_transcript')->pluck('id')->toArray();
    @endphp

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Total Requests</h6>
                    <h3 class="mb-0">{{ $requests->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-success">
                <div class="card-body">
                    <h6 class="text-muted mb-2"><i class="fas fa-check-circle text-success me-1"></i>Verified</h6>
                    <h3 class="mb-0 text-success">{{ $verifiedCount }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-danger">
                <div class="card-body">
                    <h6 class="text-muted mb-2"><i class="fas fa-exclamation-circle text-danger me-1"></i>Not in Transcript</h6>
                    <h3 class="mb-0 text-danger">{{ $notInTranscriptCount }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Credit Hours</h6>
                    <h3 class="mb-0">{{ $requests->first()->diploma_credit_hours }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Validation Warning -->
    @if($notInTranscriptCount > 0)
        <div class="alert alert-danger mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Warning:</strong> {{ $notInTranscriptCount }} request(s) are from students who <strong>never took this course</strong>.
                    These students are requesting equivalency for a course not in their transcript and should be rejected.
                </div>
                <form action="{{ route('program_coordinator.reject_not_in_transcript') }}" method="POST" class="d-inline">
                    @csrf
                    @foreach($invalidRequestIds as $invalidId)
                        <input type="hidden" name="request_ids[]" value="{{ $invalidId }}">
                    @endforeach
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Reject {{ $notInTranscriptCount }} invalid request(s)?')">
                        <i class="fas fa-ban me-1"></i>Reject Invalid ({{ $notInTranscriptCount }})
                    </button>
                </form>
            </div>
        </div>
    @endif

    <!-- Students List -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-users me-2"></i>Students Requesting This Course</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>
                                <input type="checkbox" id="selectAll" class="form-check-input">
                            </th>
                            <th>Student</th>
                            <th>Validation</th>
                            <th>Program</th>
                            <th>Lecturer Contact</th>
                            <th>Suggested Degree Course</th>
                            <th>Submitted</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requests as $request)
                            @php
                                $validation = $request->transcript_validation ?? ['status' => 'unknown', 'message' => 'Unknown'];
                                $isInvalid = $validation['status'] === 'not_in_transcript';
                                $isVerified = $validation['status'] === 'verified';
                                $noApp = $validation['status'] === 'no_application';
                            @endphp
                            <tr class="{{ $isInvalid ? 'table-danger' : '' }}">
                                <td>
                                    <input type="checkbox" name="request_ids[]" value="{{ $request->id }}"
                                           class="form-check-input request-checkbox {{ $isInvalid ? 'invalid-request' : '' }}"
                                           data-valid="{{ $isVerified ? 'true' : 'false' }}">
                                </td>
                                <td>
                                    <strong>{{ $request->student->matric_no }}</strong><br>
                                    <small class="text-muted">{{ $request->student->user->name }}</small>
                                </td>
                                <td>
                                    @if($isVerified)
                                        <span class="badge bg-success" title="{{ $validation['message'] }}">
                                            <i class="fas fa-check-circle me-1"></i>Verified
                                        </span>
                                        @if(isset($validation['subject']))
                                            <br>
                                            <small class="text-muted">
                                                Grade: <strong>{{ $validation['subject']['grade'] }}</strong>
                                            </small>
                                        @endif
                                    @elseif($isInvalid)
                                        <span class="badge bg-danger" title="{{ $validation['message'] }}">
                                            <i class="fas fa-times-circle me-1"></i>Not in Transcript
                                        </span>
                                        <br>
                                        <small class="text-danger">
                                            <i class="fas fa-exclamation-triangle"></i> Student never took this course!
                                        </small>
                                    @elseif($noApp)
                                        <span class="badge bg-warning text-dark" title="{{ $validation['message'] }}">
                                            <i class="fas fa-question-circle me-1"></i>No Application
                                        </span>
                                        <br>
                                        <small class="text-muted">Cannot verify</small>
                                    @else
                                        <span class="badge bg-secondary">Unknown</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $request->current_program_code }}</span>
                                </td>
                                <td>
                                    <strong>{{ $request->external_lecturer_name }}</strong><br>
                                    <small class="text-muted">{{ $request->external_lecturer_email }}</small>
                                </td>
                                <td>
                                    <strong>{{ $request->suggested_degree_course_code }}</strong><br>
                                    <small class="text-muted">{{ $request->suggested_degree_course_name }}</small>
                                </td>
                                <td>
                                    <small>{{ $request->created_at->format('d M Y') }}</small>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Decision Interface -->
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0"><i class="fas fa-gavel me-2"></i>Make Decision</h5>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Instructions:</strong> Select the students above, then choose one of the following actions:
                <ul class="mb-0 mt-2">
                    <li><strong>Mark as Equivalent:</strong> If this course is in your Excel spreadsheet and matches a degree course</li>
                    <li><strong>Mark as Not Equivalent:</strong> If this course is in your Excel spreadsheet but has no match</li>
                    <li><strong>Forward to Resource Person:</strong> If this course is NOT in your spreadsheet, select a lecturer and forward for detailed review</li>
                </ul>
            </div>

            <!-- Decision Buttons -->
            <div class="row g-3">
                <div class="col-md-4">
                    <button type="button" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#equivalentModal" id="btnEquivalent" disabled>
                        <i class="fas fa-check-circle me-2"></i>Mark as Equivalent
                    </button>
                </div>
                <div class="col-md-4">
                    <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#notEquivalentModal" id="btnNotEquivalent" disabled>
                        <i class="fas fa-times-circle me-2"></i>Mark as Not Equivalent
                    </button>
                </div>
                <div class="col-md-4">
                    <button type="button" class="btn btn-warning w-100" data-bs-toggle="modal" data-bs-target="#forwardModal" id="btnForward" disabled>
                        <i class="fas fa-share me-2"></i>Forward to RP
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Equivalent Modal -->
<div class="modal fade" id="equivalentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('program_coordinator.make_decision') }}" method="POST">
                @csrf
                <input type="hidden" name="decision" value="equivalent">
                <div id="equivalentRequestIds"></div>

                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fas fa-check-circle me-2"></i>Mark as Equivalent</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>You are approving this course as equivalent to a degree course. Please provide the equivalent degree course details:</p>

                    <div class="mb-3">
                        <label class="form-label">Degree Course Code <span class="text-danger">*</span></label>
                        <input type="text" name="degree_course_code" class="form-control" placeholder="e.g., CS132" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Degree Course Name <span class="text-danger">*</span></label>
                        <input type="text" name="degree_course_name" class="form-control" placeholder="e.g., COMPUTER ARCHITECTURE" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Match Percentage <span class="text-danger">*</span></label>
                        <input type="number" name="match_percentage" class="form-control" min="0" max="100" value="100" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes (Optional)</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Add any additional comments..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-2"></i>Approve as Equivalent
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Not Equivalent Modal -->
<div class="modal fade" id="notEquivalentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('program_coordinator.make_decision') }}" method="POST">
                @csrf
                <input type="hidden" name="decision" value="not_equivalent">
                <div id="notEquivalentRequestIds"></div>

                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-times-circle me-2"></i>Mark as Not Equivalent</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>You are rejecting this course as not equivalent to any degree course in your program.</p>

                    <div class="mb-3">
                        <label class="form-label">Reason for Rejection (Optional)</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Explain why this course is not equivalent..."></textarea>
                    </div>

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Note:</strong> Selected students will be notified that their request has been rejected.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times me-2"></i>Reject Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Forward to RP Modal -->
<div class="modal fade" id="forwardModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('program_coordinator.forward_to_rp') }}" method="POST">
                @csrf
                <div id="forwardRequestIds"></div>

                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title"><i class="fas fa-share me-2"></i>Forward to Resource Person</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Select which lecturer contact to use for syllabus verification:</p>

                    <div class="mb-3">
                        <label class="form-label">Select Lecturer <span class="text-danger">*</span></label>
                        <select class="form-select" id="lecturerSelect" required>
                            <option value="">Choose lecturer...</option>
                            @foreach($requests->unique('external_lecturer_email') as $request)
                                <option value="{{ $request->external_lecturer_email }}"
                                        data-name="{{ $request->external_lecturer_name }}">
                                    {{ $request->external_lecturer_name }} ({{ $request->external_lecturer_email }})
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="selected_lecturer_name" id="selectedLecturerName">
                        <input type="hidden" name="selected_lecturer_email" id="selectedLecturerEmail">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes for Resource Person (Optional)</label>
                        <textarea name="notes" class="form-control" rows="3"
                                  placeholder="Add any context or special instructions..."></textarea>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Note:</strong> The Resource Person will review the course and request the official syllabus from the selected lecturer.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-share me-2"></i>Forward to RP
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select All checkbox
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.request-checkbox');
    const btnEquivalent = document.getElementById('btnEquivalent');
    const btnNotEquivalent = document.getElementById('btnNotEquivalent');
    const btnForward = document.getElementById('btnForward');

    selectAll.addEventListener('change', function() {
        checkboxes.forEach(cb => cb.checked = this.checked);
        updateButtons();
    });

    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateButtons);
    });

    function updateButtons() {
        const checked = document.querySelectorAll('.request-checkbox:checked');
        const checkedCount = checked.length;
        const disabled = checkedCount === 0;

        // Count how many selected are invalid (not in transcript)
        const invalidSelected = Array.from(checked).filter(cb => cb.classList.contains('invalid-request')).length;
        const validSelected = checkedCount - invalidSelected;

        btnEquivalent.disabled = disabled || validSelected === 0;
        btnNotEquivalent.disabled = disabled;
        btnForward.disabled = disabled || validSelected === 0;

        // Update button text to show counts
        if (invalidSelected > 0 && validSelected > 0) {
            btnEquivalent.innerHTML = '<i class="fas fa-check-circle me-2"></i>Mark as Equivalent (' + validSelected + ' valid)';
            btnForward.innerHTML = '<i class="fas fa-share me-2"></i>Forward to RP (' + validSelected + ' valid)';
        } else {
            btnEquivalent.innerHTML = '<i class="fas fa-check-circle me-2"></i>Mark as Equivalent';
            btnForward.innerHTML = '<i class="fas fa-share me-2"></i>Forward to RP';
        }

        // Show warning if only invalid requests are selected
        if (invalidSelected > 0 && validSelected === 0) {
            btnEquivalent.title = 'Cannot approve requests for courses not in transcript';
            btnForward.title = 'Cannot forward requests for courses not in transcript';
        } else {
            btnEquivalent.title = '';
            btnForward.title = '';
        }
    }

    // Update hidden inputs when modals open - only include valid requests for equivalent/forward
    document.getElementById('equivalentModal').addEventListener('show.bs.modal', function(e) {
        const invalidSelected = Array.from(document.querySelectorAll('.request-checkbox:checked'))
            .filter(cb => cb.classList.contains('invalid-request')).length;

        if (invalidSelected > 0) {
            alert('Note: ' + invalidSelected + ' invalid request(s) (course not in transcript) will be excluded from this action.');
        }

        updateModalInputs('equivalentRequestIds', true); // Exclude invalid
    });

    document.getElementById('notEquivalentModal').addEventListener('show.bs.modal', function() {
        updateModalInputs('notEquivalentRequestIds', false); // Include all
    });

    document.getElementById('forwardModal').addEventListener('show.bs.modal', function(e) {
        const invalidSelected = Array.from(document.querySelectorAll('.request-checkbox:checked'))
            .filter(cb => cb.classList.contains('invalid-request')).length;

        if (invalidSelected > 0) {
            alert('Note: ' + invalidSelected + ' invalid request(s) (course not in transcript) will be excluded from this action.');
        }

        updateModalInputs('forwardRequestIds', true); // Exclude invalid
    });

    function updateModalInputs(containerId, excludeInvalid) {
        const container = document.getElementById(containerId);
        container.innerHTML = '';

        document.querySelectorAll('.request-checkbox:checked').forEach(cb => {
            // Skip invalid requests if excludeInvalid is true
            if (excludeInvalid && cb.classList.contains('invalid-request')) {
                return;
            }

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'request_ids[]';
            input.value = cb.value;
            container.appendChild(input);
        });
    }

    // Handle lecturer selection
    const lecturerSelect = document.getElementById('lecturerSelect');
    if (lecturerSelect) {
        lecturerSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            document.getElementById('selectedLecturerEmail').value = this.value;
            document.getElementById('selectedLecturerName').value = selectedOption.dataset.name || '';
        });
    }
});
</script>
@endpush
@endsection
