@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Semester Reminder</h2>
            <p class="text-muted mb-0">Send reminders to Resource Persons to submit updated equivalency lists</p>
        </div>
        <a href="{{ route('hea.dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle me-2"></i>
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-bell me-2"></i>Send Semester Reminder
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('hea.semester_reminder.send') }}" method="POST" id="reminderForm">
                        @csrf

                        <!-- Semester Input -->
                        <div class="mb-4">
                            <label for="semester" class="form-label fw-bold">
                                <i class="fas fa-calendar-alt me-1"></i>Semester <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control @error('semester') is-invalid @enderror"
                                   id="semester"
                                   name="semester"
                                   value="{{ old('semester', $semesterSuggestion) }}"
                                   placeholder="e.g., Semester 1 2025/2026"
                                   required>
                            <small class="text-muted">Enter the semester for which Resource Persons should submit their equivalency lists.</small>
                            @error('semester')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Resource Persons Selection -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-users me-1"></i>Select Resource Persons <span class="text-danger">*</span>
                            </label>

                            @if($resourcePersons->isEmpty())
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    No approved Resource Persons found. Please approve Resource Person registrations first.
                                </div>
                            @else
                                <!-- Select All Checkbox -->
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAll">
                                        <label class="form-check-label fw-bold" for="selectAll">
                                            Select All Resource Persons
                                        </label>
                                    </div>
                                </div>

                                <!-- Resource Persons List -->
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="5%" class="text-center">
                                                    <i class="fas fa-check"></i>
                                                </th>
                                                <th width="30%">Name</th>
                                                <th width="30%">Email</th>
                                                <th width="20%">Assigned Program</th>
                                                <th width="15%">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($resourcePersons as $rp)
                                            @php
                                                $assignedProgram = $rp->assigned_program;
                                                if (!$assignedProgram && is_array($rp->assigned_programs)) {
                                                    $assignedProgram = $rp->assigned_programs[0] ?? null;
                                                }
                                            @endphp
                                            <tr>
                                                <td class="text-center">
                                                    <div class="form-check d-flex justify-content-center">
                                                        <input class="form-check-input rp-checkbox"
                                                               type="checkbox"
                                                               name="resource_persons[]"
                                                               value="{{ $rp->id }}"
                                                               id="rp_{{ $rp->id }}"
                                                               {{ in_array($rp->id, old('resource_persons', [])) ? 'checked' : '' }}>
                                                    </div>
                                                </td>
                                                <td>
                                                    <label for="rp_{{ $rp->id }}" class="mb-0 cursor-pointer">
                                                        <strong>{{ $rp->user->name ?? 'N/A' }}</strong>
                                                    </label>
                                                </td>
                                                <td>
                                                    <small>{{ $rp->user->email ?? $rp->email ?? 'N/A' }}</small>
                                                </td>
                                                <td>
                                                    @if($assignedProgram)
                                                        <span class="badge bg-primary">{{ $assignedProgram }}</span>
                                                    @else
                                                        <span class="badge bg-secondary">Not assigned</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($rp->user && $rp->user->hasVerifiedEmail())
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check-circle me-1"></i>Verified
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning text-dark">
                                                            <i class="fas fa-clock me-1"></i>Unverified
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Selected: <span id="selectedCount">0</span> of {{ $resourcePersons->count() }} Resource Person(s)
                                    </small>
                                </div>
                            @endif
                        </div>

                        <!-- Preview Section -->
                        <div class="mb-4">
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fas fa-eye me-2"></i>Email Preview
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <p class="mb-2"><strong>Subject:</strong> Action Required: Submit CS110 Equivalency List for [Program Code] - <span class="semester-preview">{{ $semesterSuggestion }}</span></p>
                                    <hr>
                                    <p class="mb-1">Dear [Resource Person Name],</p>
                                    <p class="mb-1">This is a reminder from the Higher Education Authority (HEA) regarding the upcoming semester.</p>
                                    <p class="mb-1"><strong>Action Required:</strong> Please submit the updated CS110 Course Equivalency List for your assigned program.</p>
                                    <p class="mb-1"><strong>Semester:</strong> <span class="semester-preview">{{ $semesterSuggestion }}</span></p>
                                    <p class="mb-0 text-muted"><em>Even if there are no changes from the previous semester, please submit the list to confirm it remains current.</em></p>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        @if($resourcePersons->isNotEmpty())
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="button" class="btn btn-outline-secondary me-md-2" onclick="window.history.back()">
                                <i class="fas fa-times me-2"></i>Cancel
                            </button>
                            <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                                <i class="fas fa-paper-plane me-2"></i>Send Reminders
                            </button>
                        </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Panel -->
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>About This Feature
                    </h6>
                </div>
                <div class="card-body">
                    <p class="mb-3">
                        Use this feature to remind Resource Persons to submit updated CS110 equivalency lists at the beginning of each semester.
                    </p>
                    <h6 class="fw-bold">When to send reminders:</h6>
                    <ul class="mb-3">
                        <li>At the start of each new semester</li>
                        <li>Before the deadline for list submissions</li>
                        <li>When lists need urgent updates</li>
                    </ul>
                    <h6 class="fw-bold">What happens:</h6>
                    <ul class="mb-0">
                        <li>Selected Resource Persons receive an email notification</li>
                        <li>They also get an in-app notification</li>
                        <li>The action is logged in the audit trail</li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Quick Stats
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Resource Persons:</span>
                        <strong>{{ $resourcePersons->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>With Verified Email:</span>
                        <strong class="text-success">{{ $resourcePersons->filter(fn($rp) => $rp->user && $rp->user->hasVerifiedEmail())->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Unverified Email:</span>
                        <strong class="text-warning">{{ $resourcePersons->filter(fn($rp) => !$rp->user || !$rp->user->hasVerifiedEmail())->count() }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.cursor-pointer {
    cursor: pointer;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
}

.form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const rpCheckboxes = document.querySelectorAll('.rp-checkbox');
    const selectedCountSpan = document.getElementById('selectedCount');
    const submitBtn = document.getElementById('submitBtn');
    const semesterInput = document.getElementById('semester');
    const semesterPreviews = document.querySelectorAll('.semester-preview');

    // Update selected count and button state
    function updateSelectionState() {
        const checkedCount = document.querySelectorAll('.rp-checkbox:checked').length;
        selectedCountSpan.textContent = checkedCount;

        // Enable/disable submit button based on selection
        if (checkedCount > 0) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Send Reminders (' + checkedCount + ')';
        } else {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Send Reminders';
        }

        // Update "Select All" checkbox state
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = checkedCount === rpCheckboxes.length && rpCheckboxes.length > 0;
            selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < rpCheckboxes.length;
        }
    }

    // Select All functionality
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            rpCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateSelectionState();
        });
    }

    // Individual checkbox change
    rpCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectionState);
    });

    // Update semester preview
    if (semesterInput) {
        semesterInput.addEventListener('input', function() {
            semesterPreviews.forEach(preview => {
                preview.textContent = this.value || 'Semester';
            });
        });
    }

    // Initial state
    updateSelectionState();

    // Form submission confirmation
    document.getElementById('reminderForm')?.addEventListener('submit', function(e) {
        const checkedCount = document.querySelectorAll('.rp-checkbox:checked').length;
        const semester = semesterInput.value;

        if (!confirm('Are you sure you want to send reminders to ' + checkedCount + ' Resource Person(s) for ' + semester + '?')) {
            e.preventDefault();
        }
    });
});
</script>
@endpush
@endsection
