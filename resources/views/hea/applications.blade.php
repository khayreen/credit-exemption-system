@extends('layouts.app')

@section('content')
{{-- This is the corrected back button, styled to match the other pages --}}
<a href="{{ route('hea.dashboard') }}" class="btn btn-light mb-3"><< Back to Dashboard
</a>

<h2 class="mb-4">Application Monitoring</h2>

<div class="card shadow-sm">
    <div class="card-header bg-light py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0">All Submitted Applications</h5>
        <div class="d-flex align-items-center">
            <label class="me-2 mb-0">Filter by Status:</label>
            <form method="GET" action="{{ route('hea.applications.index') }}" class="d-flex">
                <select name="status_filter" class="form-select form-select-sm me-2" onchange="this.form.submit()" style="width: auto;">
                    <option value="all" {{ request('status_filter', 'all') == 'all' ? 'selected' : '' }}>All Statuses</option>
                    <option value="pending" {{ request('status_filter') == 'pending' ? 'selected' : '' }}>Pending Review</option>
                    <option value="reviewed" {{ request('status_filter') == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                </select>
            </form>
        </div>
    </div>
    <div class="card-body">
        @if($applications->isEmpty())
             <p class="text-center text-muted mt-3">No applications have been submitted yet.</p>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Current Degree Course</th>
                            <th>Class</th>
                            <th>Submitted On</th>
                            <th>Current Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications as $app)
                        <tr>
                            <td>{{ $app->student_name }}</td>
                            <td>{{ $app->current_program_code ?? 'N/A' }}</td>
                            <td>{{ $app->current_semester ?? 'N/A' }}</td>
                            <td>{{ $app->created_at->format('d M Y, h:i A') }}</td>
                            <td>
                                {{-- You can add conditional badge colors here for different statuses --}}
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
                                        {{ $displayStatus }}
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
                                        {{ $displayStatus }}
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
        @endif
    </div>
</div>

{{-- Pending Review Status Details Modal --}}
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel">Application Status Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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

{{-- Reviewed Application Details Modal --}}
<div class="modal fade" id="reviewedModal" tabindex="-1" aria-labelledby="reviewedModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewedModalLabel">Reviewed Application Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
    // Populate modal with data when clicking on Pending Review badge
    document.addEventListener('DOMContentLoaded', function() {
        const statusModal = document.getElementById('statusModal');
        const reviewedModal = document.getElementById('reviewedModal');

        // Pending Review Modal
        statusModal.addEventListener('show.bs.modal', function (event) {
            // Button that triggered the modal
            const button = event.relatedTarget;

            // Extract info from data-* attributes
            const studentName = button.getAttribute('data-student-name');
            const status = button.getAttribute('data-status');
            const submittedOn = button.getAttribute('data-submitted-on');

            // Update the modal's content
            document.getElementById('modal-student-name').textContent = studentName;
            document.getElementById('modal-status').textContent = status;
            document.getElementById('modal-submitted-on').textContent = submittedOn;
        });

        // Reviewed Application Modal
        reviewedModal.addEventListener('show.bs.modal', function (event) {
            // Button that triggered the modal
            const button = event.relatedTarget;

            // Extract info from data-* attributes
            const studentName = button.getAttribute('data-student-name');
            const studentGroup = button.getAttribute('data-student-group');
            const status = button.getAttribute('data-status');
            const submittedOn = button.getAttribute('data-submitted-on');
            const advisorName = button.getAttribute('data-advisor-name');

            // Update the modal's content
            document.getElementById('reviewed-modal-student-name').textContent = studentName;
            document.getElementById('reviewed-modal-student-group').textContent = studentGroup;
            document.getElementById('reviewed-modal-submitted-on').textContent = submittedOn;
            document.getElementById('reviewed-modal-advisor-name').textContent = advisorName;
        });
    });
</script>
@endpush
