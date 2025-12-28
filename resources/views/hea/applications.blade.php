@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Application Monitoring</h2>
            <p class="text-muted mb-0 mt-2">
                <i class="fas fa-file-alt me-1"></i>
                Monitor all credit exemption applications
            </p>
        </div>
        <div>
            <a href="{{ route('hea.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Application Monitoring Card -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-clipboard-list me-2"></i>All Submitted Applications
                </h5>
                <div class="d-flex align-items-center">
                    <label class="text-white me-2 mb-0">Filter by Status:</label>
                    <form method="GET" action="{{ route('hea.applications.index') }}" class="d-flex">
                        <select name="status_filter" class="form-select form-select-sm" onchange="this.form.submit()" style="width: auto; min-width: 150px;">
                            <option value="all" {{ request('status_filter', 'all') == 'all' ? 'selected' : '' }}>All Statuses</option>
                            <option value="pending" {{ request('status_filter') == 'pending' ? 'selected' : '' }}>Pending Review</option>
                            <option value="reviewed" {{ request('status_filter') == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if($applications->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">No Applications Found</h5>
                    <p class="text-muted mb-0">No applications have been submitted yet.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="20%">Student Name</th>
                                <th width="20%">Current Degree Course</th>
                                <th width="10%">Class</th>
                                <th width="25%">Submitted On</th>
                                <th width="25%">Current Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($applications as $app)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-user-graduate fa-lg text-primary me-2"></i>
                                        <strong>{{ $app->student_name }}</strong>
                                    </div>
                                </td>
                                <td>{{ $app->current_program_code ?? 'N/A' }}</td>
                                <td>{{ $app->current_semester ?? 'N/A' }}</td>
                                <td>{{ $app->created_at->format('d M Y, h:i A') }}</td>
                                <td>
                                    @php
                                        $displayStatus = $app->status;
                                        $isPending = false;
                                        $isReviewed = false;
                                        if (strtolower($app->status) === 'submitted') {
                                            $displayStatus = 'Pending Review';
                                            $isPending = true;
                                        } elseif (strtolower($app->status) === 'reviewed by academic advisor') {
                                            $displayStatus = 'Reviewed';
                                            $isReviewed = true;
                                        }
                                    @endphp
                                    @if($isPending)
                                        <span class="badge bg-primary pending-review-badge"
                                              style="cursor: pointer;"
                                              data-bs-toggle="modal"
                                              data-bs-target="#statusModal"
                                              data-student-name="{{ $app->student_name }}"
                                              data-status="{{ $displayStatus }}"
                                              data-submitted-on="{{ $app->created_at->format('d M Y, h:i A') }}">
                                            <i class="fas fa-clock me-1"></i>{{ $displayStatus }}
                                        </span>
                                    @elseif($isReviewed)
                                        <span class="badge bg-success reviewed-badge"
                                              style="cursor: pointer;"
                                              data-bs-toggle="modal"
                                              data-bs-target="#reviewedModal"
                                              data-student-name="{{ $app->student_name }}"
                                              data-student-group="{{ $app->current_faculty ?? 'N/A' }}"
                                              data-status="{{ $displayStatus }}"
                                              data-submitted-on="{{ $app->created_at->format('d M Y, h:i A') }}"
                                              data-advisor-name="{{ $app->reviewed_by_advisor ? $app->reviewed_by_advisor->name : 'Not Available' }}">
                                            <i class="fas fa-check-circle me-1"></i>{{ $displayStatus }}
                                        </span>
                                    @else
                                        <span class="badge bg-primary">{{ $displayStatus }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Statistics Footer -->
                <div class="card-footer bg-light">
                    <div class="row text-center">
                        <div class="col-md-12">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Total Applications: <strong>{{ $applications->count() }}</strong>
                            </small>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Pending Review Status Details Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="statusModalLabel">
                    <i class="fas fa-info-circle me-2"></i>Application Status Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <span class="fw-bold">Student Name:</span>
                    <span id="modal-student-name"></span>
                </div>
                <div class="mb-3">
                    <span class="fw-bold">Current Status:</span>
                    <span id="modal-status"></span>
                </div>
                <div class="mb-3">
                    <span class="fw-bold">Submitted On:</span>
                    <span id="modal-submitted-on"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Reviewed Application Details Modal -->
<div class="modal fade" id="reviewedModal" tabindex="-1" aria-labelledby="reviewedModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="reviewedModalLabel">
                    <i class="fas fa-check-circle me-2"></i>Reviewed Application Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <span class="fw-bold">Student Name:</span>
                    <span id="reviewed-modal-student-name"></span>
                </div>
                <div class="mb-3">
                    <span class="fw-bold">Group (Faculty):</span>
                    <span id="reviewed-modal-student-group"></span>
                </div>
                <div class="mb-3">
                    <span class="fw-bold">Current Status:</span>
                    <span id="reviewed-modal-status">Reviewed</span>
                </div>
                <div class="mb-3">
                    <span class="fw-bold">Submitted On:</span>
                    <span id="reviewed-modal-submitted-on"></span>
                </div>
                <div class="mb-3">
                    <span class="fw-bold">Academic Advisor Assigned:</span>
                    <span id="reviewed-modal-advisor-name"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Populate modal with data when clicking on badges
    document.addEventListener('DOMContentLoaded', function() {
        const statusModal = document.getElementById('statusModal');
        const reviewedModal = document.getElementById('reviewedModal');

        // Pending Review Modal
        if (statusModal) {
            statusModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const studentName = button.getAttribute('data-student-name');
                const status = button.getAttribute('data-status');
                const submittedOn = button.getAttribute('data-submitted-on');

                document.getElementById('modal-student-name').textContent = studentName;
                document.getElementById('modal-status').textContent = status;
                document.getElementById('modal-submitted-on').textContent = submittedOn;
            });
        }

        // Reviewed Application Modal
        if (reviewedModal) {
            reviewedModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const studentName = button.getAttribute('data-student-name');
                const studentGroup = button.getAttribute('data-student-group');
                const status = button.getAttribute('data-status');
                const submittedOn = button.getAttribute('data-submitted-on');
                const advisorName = button.getAttribute('data-advisor-name');

                document.getElementById('reviewed-modal-student-name').textContent = studentName;
                document.getElementById('reviewed-modal-student-group').textContent = studentGroup;
                document.getElementById('reviewed-modal-submitted-on').textContent = submittedOn;
                document.getElementById('reviewed-modal-advisor-name').textContent = advisorName;
            });
        }
    });
</script>
@endpush
