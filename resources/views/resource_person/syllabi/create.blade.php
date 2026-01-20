@extends('layouts.app')

@push('styles')
<!-- Tom Select for searchable dropdown -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<style>
    .ts-wrapper.form-select { padding: 0; height: auto; }
    .ts-wrapper .ts-control { border: none; border-radius: 0.375rem; min-height: calc(1.5em + 0.75rem + 2px); }
    .ts-wrapper.focus .ts-control { border-color: #86b7fe; box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25); }
    .ts-dropdown .option { padding: 8px 12px; }
    .ts-dropdown .option.active { background-color: #0d6efd; color: white; }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('resource_person.syllabi.index') }}">Degree Syllabi</a></li>
                <li class="breadcrumb-item active">Upload New</li>
            </ol>
        </nav>
        <h2 class="mb-1"><i class="fas fa-upload me-2 text-primary"></i>Upload Degree Course Syllabus</h2>
        <p class="text-muted mb-0">Add a new UiTM degree course syllabus for equivalency comparison</p>
    </div>

    @php
        // Ensure prefill is always available
        $prefill = $prefill ?? [];
    @endphp

    <!-- Pre-fill Notice -->
    @if(!empty($prefill['course_code']) || !empty($prefill['course_name']))
        <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Form pre-filled!</strong> The course information has been auto-filled from the equivalency request. You can edit the values if needed.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Upload Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('resource_person.syllabi.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Hidden field for return URL -->
                        @if(!empty($prefill['return_to']))
                            <input type="hidden" name="return_to" value="{{ $prefill['return_to'] }}">
                        @endif

                        <!-- Course Information -->
                        <h5 class="mb-3"><i class="fas fa-book me-2 text-primary"></i>Course Information</h5>

                        <div class="row mb-3">
                            <div class="col-md-9">
                                <label for="course_code" class="form-label">Degree Course <span class="text-danger">*</span></label>
                                <select class="form-select @error('course_code') is-invalid @enderror" id="course_code" name="course_code" required>
                                    <option value="">-- Select Degree Course --</option>
                                    @foreach($degreeCourses as $code => $course)
                                        <option value="{{ $code }}"
                                                data-name="{{ $course['name'] }}"
                                                data-credits="{{ $course['credit_hours'] }}"
                                                {{ old('course_code', $prefill['course_code'] ?? '') == $code ? 'selected' : '' }}>
                                            {{ $code }} - {{ $course['name'] }}
                                        </option>
                                    @endforeach
                                    <option value="__other__">+ Other (Enter manually)</option>
                                </select>
                                <!-- Hidden field for course name (auto-filled from dropdown) -->
                                <input type="hidden" id="course_name" name="course_name" value="{{ old('course_name', $prefill['course_name'] ?? '') }}">
                                @error('course_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="credit_hours" class="form-label">Credit Hours <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('credit_hours') is-invalid @enderror" id="credit_hours" name="credit_hours" value="{{ old('credit_hours', $prefill['credit_hours'] ?? 3) }}" min="1" max="10" step="0.5" required>
                                @error('credit_hours')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Manual Entry Fields (hidden by default, shown when "Other" selected) -->
                        <div class="row mb-3 d-none" id="manualEntryFields">
                            <div class="col-md-4">
                                <label for="manual_course_code" class="form-label">Course Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="manual_course_code" placeholder="e.g., ITT420">
                                <small class="text-muted">Use uppercase letters</small>
                            </div>
                            <div class="col-md-8">
                                <label for="manual_course_name" class="form-label">Course Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="manual_course_name" placeholder="e.g., System Administration">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label">Course Description <span class="text-muted fw-normal">(Optional)</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" placeholder="Brief description of the course content...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <!-- Syllabus File -->
                        <h5 class="mb-3"><i class="fas fa-file-pdf me-2 text-danger"></i>Syllabus Document</h5>

                        <div class="mb-4">
                            <label for="syllabus_file" class="form-label">Upload Syllabus PDF <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('syllabus_file') is-invalid @enderror" id="syllabus_file" name="syllabus_file" accept=".pdf" required>
                            @error('syllabus_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                PDF only, maximum 10MB. Include course outline, topics, learning outcomes.
                            </small>
                        </div>

                        <!-- File Preview Area -->
                        <div id="file-preview" class="mb-4 d-none">
                            <div class="alert alert-info d-flex align-items-center">
                                <i class="fas fa-file-pdf fa-2x me-3 text-danger"></i>
                                <div>
                                    <strong id="file-name"></strong>
                                    <br><small id="file-size" class="text-muted"></small>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('resource_person.syllabi.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload me-2"></i>Upload Syllabus
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Help Sidebar -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-question-circle me-2 text-info"></i>Upload Guidelines</h5>
                    <hr>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <strong>Select Course:</strong> Choose from existing UiTM degree courses
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-plus-circle text-primary me-2"></i>
                            <strong>New Course?</strong> Select "Other" to enter course details manually
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-file-pdf text-danger me-2"></i>
                            <strong>Syllabus PDF:</strong> Should include:
                            <ul class="mt-2">
                                <li>Course objectives</li>
                                <li>Topics covered</li>
                                <li>Learning outcomes</li>
                                <li>Assessment breakdown</li>
                            </ul>
                        </li>
                        <li class="mb-0">
                            <i class="fas fa-info-circle text-info me-2"></i>
                            One syllabus per course - shared across all programs.
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- Tom Select Library -->
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Prefill values from URL parameters
    const prefillCourseCode = "{{ $prefill['course_code'] ?? '' }}";
    const prefillCourseName = "{{ $prefill['course_name'] ?? '' }}";
    const prefillCreditHours = "{{ $prefill['credit_hours'] ?? 3 }}";

    const courseCodeSelect = document.getElementById('course_code');
    const courseNameInput = document.getElementById('course_name');
    const creditHoursInput = document.getElementById('credit_hours');
    const manualEntryFields = document.getElementById('manualEntryFields');
    const manualCourseCode = document.getElementById('manual_course_code');
    const manualCourseName = document.getElementById('manual_course_name');

    // Initialize Tom Select (searchable dropdown)
    const tomSelect = new TomSelect('#course_code', {
        placeholder: 'Search or select a degree course...',
        allowEmptyOption: true,
        sortField: { field: 'text', direction: 'asc' },
        render: {
            option: function(data, escape) {
                if (data.value === '__other__') {
                    return '<div class="option text-primary fw-bold"><i class="fas fa-plus me-2"></i>' + escape(data.text) + '</div>';
                }
                return '<div class="option">' + escape(data.text) + '</div>';
            },
            item: function(data, escape) {
                return '<div class="item">' + escape(data.text) + '</div>';
            }
        }
    });

    // Show manual entry fields
    function showManualEntry(defaultCode = '', defaultName = '') {
        // Hide the dropdown row
        courseCodeSelect.closest('.row').classList.add('d-none');
        // Destroy Tom Select wrapper visibility
        const tsWrapper = document.querySelector('.ts-wrapper');
        if (tsWrapper) tsWrapper.style.display = 'none';

        // Show manual fields
        manualEntryFields.classList.remove('d-none');

        // Set values
        manualCourseCode.value = defaultCode;
        manualCourseName.value = defaultName;

        // Add back button if not exists
        if (!document.getElementById('backToDropdownBtn')) {
            const backBtn = document.createElement('button');
            backBtn.type = 'button';
            backBtn.id = 'backToDropdownBtn';
            backBtn.className = 'btn btn-outline-secondary btn-sm mt-2';
            backBtn.innerHTML = '<i class="fas fa-arrow-left me-1"></i>Back to course list';
            backBtn.onclick = function() {
                location.reload();
            };
            manualEntryFields.appendChild(backBtn);
        }

        // Sync manual inputs to actual form fields
        manualCourseCode.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
            courseCodeSelect.name = ''; // Disable select
            this.name = 'course_code';
        });
        manualCourseName.addEventListener('input', function() {
            courseNameInput.value = this.value;
        });

        // Initial sync
        manualCourseCode.name = 'course_code';
        courseCodeSelect.name = '';
    }

    // Handle Tom Select change event
    tomSelect.on('change', function(value) {
        if (value === '__other__') {
            showManualEntry(prefillCourseCode, prefillCourseName);
            return;
        }

        const selectedOption = courseCodeSelect.querySelector('option[value="' + value + '"]');
        if (value && selectedOption && selectedOption.dataset.name) {
            courseNameInput.value = selectedOption.dataset.name;
            creditHoursInput.value = selectedOption.dataset.credits || 3;
        } else {
            courseNameInput.value = '';
            creditHoursInput.value = 3;
        }
    });

    // Handle prefill on page load
    if (prefillCourseCode) {
        // Check if course exists in dropdown
        let found = false;
        for (let opt of courseCodeSelect.options) {
            if (opt.value === prefillCourseCode) {
                tomSelect.setValue(prefillCourseCode);
                found = true;
                break;
            }
        }
        // If not found, show manual entry
        if (!found) {
            showManualEntry(prefillCourseCode, prefillCourseName);
        }
    }

    // File upload preview
    document.getElementById('syllabus_file').addEventListener('change', function(e) {
        const preview = document.getElementById('file-preview');
        const fileName = document.getElementById('file-name');
        const fileSize = document.getElementById('file-size');

        if (this.files && this.files[0]) {
            const file = this.files[0];
            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);
            preview.classList.remove('d-none');
        } else {
            preview.classList.add('d-none');
        }
    });

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
});
</script>
@endpush
@endsection
