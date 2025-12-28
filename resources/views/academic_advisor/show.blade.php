@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <a href="{{ route('academic_advisor.dashboard') }}" class="btn btn-light mb-3"><< Back to Dashboard</a>
            <div class="card">
                <div class="card-header">Application Details - {{ $application->student_name }}</div>

                <div class="card-body">
                    <!-- Student & Application Info -->
                    <h5>Student Information</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th>Student Name</th>
                            <td>{{ $application->student_name }}</td>
                            <th>Student ID</th>
                            <td>{{ $application->matric_no }}</td>
                        </tr>
                        <tr>
                            <th>Diploma Institution</th>
                            <td>{{ $application->previous_institution }}</td>
                            <th>Diploma Program</th>
                            <td>{{ $application->previous_program }}</td>
                        </tr>
                        <tr>
                            <th>Application Status</th>
                            <td colspan="3"><span class="badge bg-warning text-dark">{{ $application->status }}</span></td>
                        </tr>
                    </table>

                    <!-- Transcript Viewer -->
                    <h5 class="mt-4">Academic Transcript</h5>
                    <a href="{{ route('academic_advisor.application.transcript', $application) }}" target="_blank" class="btn btn-secondary">View Transcript (PDF)</a>

                    <!-- OCR Processing Summary -->
                    @if($application->applicationSubjects->isNotEmpty())
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5><i class="fas fa-robot text-primary"></i> OCR Processing Summary</h5>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="card text-center bg-light">
                                        <div class="card-body py-2">
                                            <h6 class="text-success">{{ $exemptedSubjects->count() }}</h6>
                                            <small class="text-muted">Courses Exempted</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card text-center bg-light">
                                        <div class="card-body py-2">
                                            <h6 class="text-warning">{{ $nonExemptedSubjects->count() }}</h6>
                                            <small class="text-muted">Needs Review</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card text-center bg-light">
                                        <div class="card-body py-2">
                                            <h6 class="text-info">{{ $application->current_program_code ?? 'N/A' }}</h6>
                                            <small class="text-muted">Program Code</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card text-center bg-light">
                                        <div class="card-body py-2">
                                            <h6 class="text-primary">{{ $subjects->count() }}</h6>
                                            <small class="text-muted">Total Courses</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Individual Course Decisions -->

                        <!-- Exempted Courses Section -->
                        @if($exemptedSubjects->isNotEmpty())
                        <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                            <div>
                                <h5 class="mb-1"><i class="fas fa-check-circle text-success"></i> Pre-Qualified Courses (OCR Approved)</h5>
                                <p class="text-muted mb-0">These courses met all OCR criteria: Found in database + Grade C or above + Match >80%</p>
                            </div>
                            <button type="button" class="btn btn-success" id="approveAllBtn">
                                <i class="fas fa-check-double"></i> Approve All
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-success table-striped">
                                <thead>
                                    <tr>
                                        <th>Course Code</th>
                                        <th>Course Name</th>
                                        <th>Grade</th>
                                        <th>Equivalent To</th>
                                        <th>Match %</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($exemptedSubjects as $subject)
                                    <tr>
                                        <td><strong>{{ $subject['course_code'] }}</strong></td>
                                        <td>{{ $subject['course_name'] }}</td>
                                        <td>
                                            @if($subject['is_combination'] && $subject['individual_grades'])
                                                {{-- Display individual grades for combination courses --}}
                                                @php
                                                    $courseCodes = explode(' & ', $subject['course_code']);
                                                    $grades = [];
                                                    foreach($courseCodes as $code) {
                                                        if(isset($subject['individual_grades'][$code])) {
                                                            $grades[] = $subject['individual_grades'][$code];
                                                        }
                                                    }
                                                @endphp
                                                @foreach($grades as $index => $grade)
                                                    <span class="badge bg-success">{{ $grade }}</span>@if($index < count($grades) - 1), @endif
                                                @endforeach
                                            @else
                                                <span class="badge bg-success">{{ $subject['grade_letter'] }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $subject['equivalent_course'] ?: '-' }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $subject['match_percentage'] ?: '0' }}%</span>
                                        </td>
                                        <td>
                                            @if($subject['status'] === 'Approved')
                                                <div class="d-flex align-items-center gap-1">
                                                    <span class="badge bg-success fs-6">
                                                        <i class="fas fa-check"></i> Approved
                                                    </span>
                                                    <button type="button" class="btn btn-outline-secondary btn-sm undo-btn" 
                                                            data-subject-id="{{ $subject['id'] }}" 
                                                            data-course-code="{{ $subject['course_code'] }}"
                                                            title="Undo decision">
                                                        <i class="fas fa-undo"></i>
                                                    </button>
                                                </div>
                                            @else
                                                <div class="btn-group-vertical btn-group-sm" role="group">
                                                    <button type="button" class="btn btn-success btn-sm mb-1 decision-btn"
                                                            data-subject-id="{{ $subject['id'] }}"
                                                            data-decision="Approved"
                                                            data-course-code="{{ $subject['course_code'] }}">
                                                        <i class="fas fa-check"></i> Approve
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-sm decision-btn"
                                                            data-subject-id="{{ $subject['id'] }}"
                                                            data-decision="Rejected"
                                                            data-course-code="{{ $subject['course_code'] }}">
                                                        <i class="fas fa-times"></i> Reject
                                                    </button>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif

                        <!-- Non-Exempted Courses Section -->
                        @if($nonExemptedSubjects->isNotEmpty())
                        <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                            <div>
                                <h5 class="mb-1"><i class="fas fa-exclamation-triangle text-warning"></i> Courses Requiring Manual Review</h5>
                                <p class="text-muted mb-0">These courses did not meet OCR exemption criteria and require manual evaluation.</p>
                            </div>
                            <button type="button" class="btn btn-danger" id="rejectAllBtn">
                                <i class="fas fa-times-circle"></i> Reject All
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-warning table-striped">
                                <thead>
                                    <tr>
                                        <th>Course Code</th>
                                        <th>Course Name</th>
                                        <th>Grade</th>
                                        <th>OCR Status</th>
                                        <th>Reason</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($nonExemptedSubjects as $subject)
                                    <tr>
                                        <td><strong>{{ $subject['course_code'] }}</strong></td>
                                        <td>{{ $subject['course_name'] }}</td>
                                        <td>
                                            @if($subject['is_combination'] && $subject['individual_grades'])
                                                {{-- Display individual grades for combination courses --}}
                                                @php
                                                    $courseCodes = explode(' & ', $subject['course_code']);
                                                    $grades = [];
                                                    foreach($courseCodes as $code) {
                                                        if(isset($subject['individual_grades'][$code])) {
                                                            $grades[] = $subject['individual_grades'][$code];
                                                        }
                                                    }
                                                    $badgeClass = $subject['status'] == 'not_eligible_grade' ? 'bg-danger' : 'bg-secondary';
                                                @endphp
                                                @foreach($grades as $index => $grade)
                                                    <span class="badge {{ $badgeClass }}">{{ $grade }}</span>@if($index < count($grades) - 1), @endif
                                                @endforeach
                                            @else
                                                @if($subject['status'] == 'not_eligible_grade')
                                                    <span class="badge bg-danger">{{ $subject['grade_letter'] }}</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $subject['grade_letter'] }}</span>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if($subject['status'] == 'not_found')
                                                <span class="badge bg-dark">Not Found</span>
                                            @elseif($subject['status'] == 'not_eligible_grade')
                                                <span class="badge bg-danger">Grade Too Low</span>
                                            @elseif($subject['status'] == 'not_eligible_match')
                                                <span class="badge bg-warning">Low Match ({{ $subject['match_percentage'] }}%)</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $subject['status'])) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $subject['exemption_reason'] }}</small>
                                        </td>
                                        <td>
                                            @if(in_array($subject['status'], ['Approved', 'Rejected', 'Forward to Coordinator']))
                                                <div class="d-flex align-items-center gap-1">
                                                    <span class="badge bg-{{ $subject['status'] === 'Approved' ? 'success' : ($subject['status'] === 'Rejected' ? 'danger' : 'warning') }} fs-6">
                                                        <i class="fas fa-{{ $subject['status'] === 'Approved' ? 'check' : ($subject['status'] === 'Rejected' ? 'times' : 'arrow-right') }}"></i> 
                                                        {{ $subject['status'] }}
                                                    </span>
                                                    <button type="button" class="btn btn-outline-secondary btn-sm undo-btn" 
                                                            data-subject-id="{{ $subject['id'] }}" 
                                                            data-course-code="{{ $subject['course_code'] }}"
                                                            title="Undo decision">
                                                        <i class="fas fa-undo"></i>
                                                    </button>
                                                </div>
                                            @else
                                                <div class="btn-group-vertical btn-group-sm" role="group">
                                                    <button type="button" class="btn btn-success btn-sm mb-1 decision-btn"
                                                            data-subject-id="{{ $subject['id'] }}"
                                                            data-decision="Approved"
                                                            data-course-code="{{ $subject['course_code'] }}">
                                                        <i class="fas fa-check"></i> Approve
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-sm decision-btn"
                                                            data-subject-id="{{ $subject['id'] }}"
                                                            data-decision="Rejected"
                                                            data-course-code="{{ $subject['course_code'] }}">
                                                        <i class="fas fa-times"></i> Reject
                                                    </button>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif

                        @if($subjects->isEmpty())
                        <div class="alert alert-info mt-4">
                            <i class="fas fa-info-circle"></i> No courses were extracted from the transcript. Manual review required.
                        </div>
                        @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Toast -->
<div id="toast-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    // Handle Approve All button click
    const approveAllBtn = document.getElementById('approveAllBtn');
    if (approveAllBtn) {
        approveAllBtn.addEventListener('click', function() {
            // Count courses that will be approved (not already approved)
            const preQualifiedTable = document.querySelector('.table-success tbody');
            const pendingCourses = preQualifiedTable ?
                Array.from(preQualifiedTable.querySelectorAll('tr')).filter(row => {
                    return row.querySelector('.decision-btn'); // Has action buttons (not yet approved)
                }).length : 0;

            if (pendingCourses === 0) {
                showToast('error', 'All pre-qualified courses are already approved.');
                return;
            }

            if (confirm(`Are you sure you want to approve all ${pendingCourses} pre-qualified course(s)? This action will approve all courses that met the OCR criteria.`)) {
                // Disable button and show loading
                approveAllBtn.disabled = true;
                const originalText = approveAllBtn.innerHTML;
                approveAllBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Approving...';

                // Send AJAX request
                fetch('{{ route('academic_advisor.application.bulk_approve_prequalified', ['application' => $application->id]) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        showToast('success', data.message);

                        // Update all pending rows in the pre-qualified table
                        const preQualifiedTable = document.querySelector('.table-success tbody');
                        if (preQualifiedTable) {
                            const rows = preQualifiedTable.querySelectorAll('tr');
                            rows.forEach(row => {
                                const actionCell = row.querySelector('td:last-child');
                                // Only update rows that have decision buttons (not already approved)
                                if (actionCell && actionCell.querySelector('.decision-btn')) {
                                    const subjectId = actionCell.querySelector('.decision-btn').dataset.subjectId;
                                    const courseCode = actionCell.querySelector('.decision-btn').dataset.courseCode;

                                    // Update to show approved status with undo button
                                    actionCell.innerHTML = `
                                        <div class="d-flex align-items-center gap-1">
                                            <span class="badge bg-success fs-6">
                                                <i class="fas fa-check"></i> Approved
                                            </span>
                                            <button type="button" class="btn btn-outline-secondary btn-sm undo-btn"
                                                    data-subject-id="${subjectId}"
                                                    data-course-code="${courseCode}"
                                                    title="Undo decision">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        </div>
                                    `;
                                }
                            });
                        }

                        // Hide the Approve All button after successful bulk approval
                        approveAllBtn.style.display = 'none';
                    } else {
                        throw new Error(data.message || 'Unknown error occurred');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('error', error.message || 'An error occurred while processing bulk approval. Please try again.');

                    // Restore button
                    approveAllBtn.disabled = false;
                    approveAllBtn.innerHTML = originalText;
                });
            }
        });
    }

    // Handle Reject All button click
    const rejectAllBtn = document.getElementById('rejectAllBtn');
    if (rejectAllBtn) {
        rejectAllBtn.addEventListener('click', function() {
            // Count courses that will be rejected (not already rejected)
            const manualReviewTable = document.querySelector('.table-warning tbody');
            const pendingCourses = manualReviewTable ?
                Array.from(manualReviewTable.querySelectorAll('tr')).filter(row => {
                    return row.querySelector('.decision-btn'); // Has action buttons (not yet rejected)
                }).length : 0;

            if (pendingCourses === 0) {
                showToast('error', 'All manual review courses are already rejected or decided.');
                return;
            }

            if (confirm(`Are you sure you want to reject all ${pendingCourses} manual review course(s)? This action will reject all courses that did not meet the OCR criteria.`)) {
                // Disable button and show loading
                rejectAllBtn.disabled = true;
                const originalText = rejectAllBtn.innerHTML;
                rejectAllBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Rejecting...';

                // Send AJAX request
                fetch('{{ route('academic_advisor.application.bulk_reject_manual_review', ['application' => $application->id]) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        showToast('success', data.message);

                        // Update all pending rows in the manual review table
                        const manualReviewTable = document.querySelector('.table-warning tbody');
                        if (manualReviewTable) {
                            const rows = manualReviewTable.querySelectorAll('tr');
                            rows.forEach(row => {
                                const actionCell = row.querySelector('td:last-child');
                                // Only update rows that have decision buttons (not already decided)
                                if (actionCell && actionCell.querySelector('.decision-btn')) {
                                    const subjectId = actionCell.querySelector('.decision-btn').dataset.subjectId;
                                    const courseCode = actionCell.querySelector('.decision-btn').dataset.courseCode;

                                    // Update to show rejected status with undo button
                                    actionCell.innerHTML = `
                                        <div class="d-flex align-items-center gap-1">
                                            <span class="badge bg-danger fs-6">
                                                <i class="fas fa-times"></i> Rejected
                                            </span>
                                            <button type="button" class="btn btn-outline-secondary btn-sm undo-btn"
                                                    data-subject-id="${subjectId}"
                                                    data-course-code="${courseCode}"
                                                    title="Undo decision">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        </div>
                                    `;
                                }
                            });
                        }

                        // Hide the Reject All button after successful bulk rejection
                        rejectAllBtn.style.display = 'none';
                    } else {
                        throw new Error(data.message || 'Unknown error occurred');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('error', error.message || 'An error occurred while processing bulk rejection. Please try again.');

                    // Restore button
                    rejectAllBtn.disabled = false;
                    rejectAllBtn.innerHTML = originalText;
                });
            }
        });
    }

    // Handle decision button clicks (using event delegation)
    document.addEventListener('click', function(e) {
        if (e.target.closest('.decision-btn')) {
            const button = e.target.closest('.decision-btn');
            const subjectId = button.dataset.subjectId;
            const decision = button.dataset.decision;
            const courseCode = button.dataset.courseCode;
            
            // Determine if confirmation is needed based on decision and table context
            const row = button.closest('tr');
            const table = button.closest('table');
            const isPreQualifiedTable = table.classList.contains('table-success');
            const isManualReviewTable = table.classList.contains('table-warning');

            let needsConfirmation = false;
            if (decision === 'Rejected' && isPreQualifiedTable) {
                needsConfirmation = true; // Confirm reject in pre-qualified table only
            }
            // No confirmation for: Approve (any table), Reject (manual review table)
            
            if (!needsConfirmation || confirm(`Are you sure you want to ${decision.toLowerCase()} course ${courseCode}?`)) {
                // Disable button and show loading
                button.disabled = true;
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
                
                // Send AJAX request
                fetch(`{{ route('academic_advisor.application.subject.decision', ['application' => $application->id, 'subject' => '__SUBJECT_ID__']) }}`.replace('__SUBJECT_ID__', subjectId), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        decision: decision
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        showToast('success', data.message);
                        
                        // Update the row to show the decision with undo button
                        const row = button.closest('tr');
                        const actionCell = button.closest('td');
                        const iconClass = decision === 'Approved' ? 'check' : (decision === 'Rejected' ? 'times' : 'arrow-right');
                        actionCell.innerHTML = `
                            <div class="d-flex align-items-center gap-1">
                                <span class="badge bg-${getStatusColor(decision)} fs-6">
                                    <i class="fas fa-${iconClass}"></i> ${decision}
                                </span>
                                <button type="button" class="btn btn-outline-secondary btn-sm undo-btn" 
                                        data-subject-id="${subjectId}" 
                                        data-course-code="${courseCode}"
                                        title="Undo decision">
                                    <i class="fas fa-undo"></i>
                                </button>
                            </div>
                        `;
                        
                        // Based on user request: no page reloads, keep courses in their respective tables
                        // All decisions now stay in the same table and show status badges
                    } else {
                        throw new Error(data.message || 'Unknown error occurred');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('error', 'An error occurred while processing your decision. Please try again.');
                    
                    // Restore button
                    button.disabled = false;
                    button.innerHTML = originalText;
                });
            }
        }
    });
    
    // Handle undo button clicks (using event delegation)
    document.addEventListener('click', function(e) {
        if (e.target.closest('.undo-btn')) {
            const button = e.target.closest('.undo-btn');
            const subjectId = button.dataset.subjectId;
            const courseCode = button.dataset.courseCode;
            
            if (confirm(`Are you sure you want to undo the decision for course ${courseCode}? This will reset it to pending status.`)) {
                // Disable button and show loading
                button.disabled = true;
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                
                // Send AJAX request to undo decision
                fetch(`{{ route('academic_advisor.application.subject.decision', ['application' => $application->id, 'subject' => '__SUBJECT_ID__']) }}`.replace('__SUBJECT_ID__', subjectId), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        decision: 'Undo'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        showToast('success', data.message);
                        
                        // Restore the original action buttons
                        const actionCell = button.closest('td');
                        actionCell.innerHTML = `
                            <div class="btn-group-vertical btn-group-sm" role="group">
                                <button type="button" class="btn btn-success btn-sm mb-1 decision-btn"
                                        data-subject-id="${subjectId}"
                                        data-decision="Approved"
                                        data-course-code="${courseCode}">
                                    <i class="fas fa-check"></i> Approve
                                </button>
                                <button type="button" class="btn btn-danger btn-sm decision-btn"
                                        data-subject-id="${subjectId}"
                                        data-decision="Rejected"
                                        data-course-code="${courseCode}">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                            </div>
                        `;
                    } else {
                        throw new Error(data.message || 'Unknown error occurred');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('error', 'An error occurred while undoing the decision. Please try again.');
                    
                    // Restore button
                    button.disabled = false;
                    button.innerHTML = originalText;
                });
            }
        }
    });
    
    function getStatusColor(status) {
        switch(status) {
            case 'Approved': return 'success';
            case 'Rejected': return 'danger';
            case 'Forward to Coordinator': return 'warning';
            default: return 'secondary';
        }
    }
    
    function showToast(type, message) {
        const toastContainer = document.getElementById('toast-container');
        const toastId = 'toast-' + Date.now();
        
        const toast = document.createElement('div');
        toast.id = toastId;
        toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
        toast.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        toastContainer.appendChild(toast);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            const toastElement = document.getElementById(toastId);
            if (toastElement) {
                toastElement.remove();
            }
        }, 5000);
    }
});
</script>
@endpush
