@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<!-- Tom Select for searchable dropdown -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<style>
    :root {
        --uitm-blue: #1e3a8a;
        --uitm-blue-light: #3b82f6;
        --uitm-amber: #f59e0b;
        --industrial-dark: #0f172a;
        --industrial-gray: #334155;
        --industrial-light: #f1f5f9;
        --success: #059669;
        --danger: #dc2626;
        --warning: #ea580c;
        --info: #0d9488;
    }

    body {
        font-family: 'IBM Plex Sans', sans-serif;
        background-color: var(--industrial-light);
    }

    .page-header {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--industrial-dark) 100%);
        border-radius: 0 0 24px 24px;
        padding: 2rem 2.5rem;
        margin: -1.5rem -1.5rem 2rem -1.5rem;
        color: white;
    }

    .page-header h2 {
        font-weight: 700;
        font-size: 1.75rem;
        margin-bottom: 0.35rem;
    }

    .page-header p {
        color: rgba(255,255,255,0.8);
        margin: 0;
    }

    .breadcrumb-industrial {
        background: transparent;
        padding: 0;
        margin-bottom: 1rem;
    }

    .breadcrumb-industrial a {
        color: rgba(255,255,255,0.7);
        text-decoration: none;
    }

    .breadcrumb-industrial a:hover {
        color: white;
    }

    .breadcrumb-industrial .active {
        color: rgba(255,255,255,0.5);
    }

    .breadcrumb-industrial .breadcrumb-item + .breadcrumb-item::before {
        color: rgba(255,255,255,0.5);
    }

    /* Pre-fill Notice */
    .prefill-notice {
        background: rgba(13, 148, 136, 0.1);
        border: 1px solid rgba(13, 148, 136, 0.2);
        border-left: 4px solid var(--info);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        color: var(--info);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    /* Form Card */
    .form-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        padding: 2rem;
    }

    .section-title {
        font-weight: 600;
        color: var(--industrial-dark);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1.1rem;
    }

    .section-title i.primary { color: var(--uitm-blue); }
    .section-title i.danger { color: var(--danger); }

    .form-label {
        font-weight: 600;
        color: var(--industrial-dark);
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .form-control, .form-select {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--uitm-blue);
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
    }

    .form-text {
        color: var(--industrial-gray);
        font-size: 0.85rem;
    }

    /* Tom Select Custom Styles */
    .ts-wrapper.form-select {
        padding: 0;
        height: auto;
    }

    .ts-wrapper .ts-control {
        border: none;
        border-radius: 10px;
        min-height: calc(1.5em + 1.5rem + 4px);
        padding: 0.75rem 1rem;
    }

    .ts-wrapper.focus .ts-control {
        border-color: var(--uitm-blue);
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
    }

    .ts-dropdown {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        margin-top: 4px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }

    .ts-dropdown .option {
        padding: 0.75rem 1rem;
    }

    .ts-dropdown .option.active {
        background-color: var(--uitm-blue);
        color: white;
    }

    /* File Preview */
    .file-preview {
        background: rgba(13, 148, 136, 0.1);
        border: 1px solid rgba(13, 148, 136, 0.2);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .file-preview-icon {
        font-size: 2.5rem;
        color: var(--danger);
    }

    .file-preview-info strong {
        color: var(--industrial-dark);
    }

    .file-preview-info small {
        color: var(--industrial-gray);
    }

    /* Help Sidebar */
    .help-card {
        background: var(--industrial-light);
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        padding: 1.5rem;
    }

    .help-card h5 {
        color: var(--industrial-dark);
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .help-card h5 i {
        color: var(--info);
    }

    .help-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .help-list li {
        padding: 0.75rem 0;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .help-list li:last-child {
        border-bottom: none;
    }

    .help-list li i.success { color: var(--success); }
    .help-list li i.primary { color: var(--uitm-blue); }
    .help-list li i.danger { color: var(--danger); }
    .help-list li i.info { color: var(--info); }

    .help-list li strong {
        color: var(--industrial-dark);
    }

    .help-list li span {
        color: var(--industrial-gray);
    }

    .sub-help-list {
        margin-top: 0.5rem;
        padding-left: 1rem;
        list-style: disc;
    }

    .sub-help-list li {
        padding: 0.25rem 0;
        border-bottom: none;
        display: list-item;
        color: var(--industrial-gray);
    }

    /* Buttons */
    .btn-cancel {
        border: 2px solid #e2e8f0;
        color: var(--industrial-gray);
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 500;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-cancel:hover {
        background: var(--industrial-light);
        color: var(--industrial-dark);
    }

    .btn-upload {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--uitm-blue-light) 100%);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
    }

    .btn-upload:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(30, 58, 138, 0.3);
        color: white;
    }

    .btn-back-small {
        background: var(--industrial-light);
        border: 2px solid #e2e8f0;
        color: var(--industrial-gray);
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.85rem;
        margin-top: 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        transition: all 0.2s ease;
    }

    .btn-back-small:hover {
        background: #e2e8f0;
        color: var(--industrial-dark);
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
            margin: -1rem -1rem 1.5rem -1rem;
            border-radius: 0 0 16px 16px;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <nav aria-label="breadcrumb" class="breadcrumb-industrial">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('resource_person.syllabi.index') }}">Degree Syllabi</a></li>
                <li class="breadcrumb-item active">Upload New</li>
            </ol>
        </nav>
        <h2><i class="fas fa-upload me-2"></i>Upload Degree Course Syllabus</h2>
        <p><i class="fas fa-book-open me-2"></i>Add a new UiTM degree course syllabus for equivalency comparison</p>
    </div>

    @php
        $prefill = $prefill ?? [];
    @endphp

    <!-- Pre-fill Notice -->
    @if(!empty($prefill['course_code']) || !empty($prefill['course_name']))
        <div class="prefill-notice">
            <i class="fas fa-info-circle"></i>
            <div>
                <strong>Form pre-filled!</strong> The course information has been auto-filled from the equivalency request. You can edit the values if needed.
            </div>
        </div>
    @endif

    <!-- Upload Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="form-card">
                <form action="{{ route('resource_person.syllabi.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Hidden field for return URL -->
                    @if(!empty($prefill['return_to']))
                        <input type="hidden" name="return_to" value="{{ $prefill['return_to'] }}">
                    @endif

                    <!-- Course Information -->
                    <h5 class="section-title"><i class="fas fa-book primary"></i>Course Information</h5>

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
                            <small class="form-text">Use uppercase letters</small>
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
                    <h5 class="section-title"><i class="fas fa-file-pdf danger"></i>Syllabus Document</h5>

                    <div class="mb-4">
                        <label for="syllabus_file" class="form-label">Upload Syllabus PDF <span class="text-danger">*</span></label>
                        <input type="file" class="form-control @error('syllabus_file') is-invalid @enderror" id="syllabus_file" name="syllabus_file" accept=".pdf" required>
                        @error('syllabus_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            PDF only, maximum 10MB. Include course outline, topics, learning outcomes.
                        </small>
                    </div>

                    <!-- File Preview Area -->
                    <div id="file-preview" class="mb-4 d-none">
                        <div class="file-preview">
                            <i class="fas fa-file-pdf file-preview-icon"></i>
                            <div class="file-preview-info">
                                <strong id="file-name"></strong>
                                <br><small id="file-size"></small>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Submit Buttons -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('resource_person.syllabi.index') }}" class="btn-cancel">
                            <i class="fas fa-arrow-left"></i>Cancel
                        </a>
                        <button type="submit" class="btn-upload">
                            <i class="fas fa-upload"></i>Upload Syllabus
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Help Sidebar -->
        <div class="col-lg-4">
            <div class="help-card">
                <h5><i class="fas fa-question-circle"></i>Upload Guidelines</h5>
                <hr>
                <ul class="help-list">
                    <li>
                        <i class="fas fa-check-circle success"></i>
                        <div>
                            <strong>Select Course:</strong>
                            <span>Choose from existing UiTM degree courses</span>
                        </div>
                    </li>
                    <li>
                        <i class="fas fa-plus-circle primary"></i>
                        <div>
                            <strong>New Course?</strong>
                            <span>Select "Other" to enter course details manually</span>
                        </div>
                    </li>
                    <li>
                        <i class="fas fa-file-pdf danger"></i>
                        <div>
                            <strong>Syllabus PDF:</strong>
                            <span>Should include:</span>
                            <ul class="sub-help-list">
                                <li>Course objectives</li>
                                <li>Topics covered</li>
                                <li>Learning outcomes</li>
                                <li>Assessment breakdown</li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <i class="fas fa-info-circle info"></i>
                        <div>
                            <span>One syllabus per course - shared across all programs.</span>
                        </div>
                    </li>
                </ul>
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
            backBtn.className = 'btn-back-small';
            backBtn.innerHTML = '<i class="fas fa-arrow-left"></i>Back to course list';
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
