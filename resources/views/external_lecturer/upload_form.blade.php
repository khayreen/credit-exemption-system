@extends('layouts.app')

@section('title', 'Upload Course Syllabus')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600;700&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
    :root {
        --uitm-primary: #1e3a8a;
        --uitm-primary-dark: #1e293b;
        --uitm-primary-light: #3b82f6;
        --uitm-amber: #f59e0b;
        --uitm-amber-dark: #d97706;
        --industrial-dark: #0f172a;
        --industrial-gray: #334155;
        --industrial-light: #f1f5f9;
        --success: #059669;
        --success-light: #d1fae5;
        --danger: #dc2626;
        --danger-light: #fee2e2;
        --warning: #ea580c;
        --warning-light: #ffedd5;
        --teal: #0d9488;
        --teal-light: #ccfbf1;
        --neutral-700: #404040;
        --neutral-600: #525252;
        --neutral-500: #737373;
        --neutral-400: #a3a3a3;
        --neutral-300: #d4d4d4;
        --neutral-200: #e5e5e5;
        --neutral-100: #f5f5f5;
        --neutral-50: #fafafa;
    }

    body {
        font-family: 'IBM Plex Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        background: var(--industrial-light);
    }

    /* Page Header */
    .page-header {
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--industrial-dark) 100%);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(245, 158, 11, 0.15) 0%, transparent 70%);
        border-radius: 50%;
    }

    .page-header-content {
        position: relative;
        z-index: 1;
    }

    .page-title {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: white;
        margin-bottom: 0.5rem;
    }

    .page-subtitle {
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.9rem;
        margin-bottom: 0;
    }

    .btn-back {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-back:hover {
        background: rgba(255, 255, 255, 0.25);
        color: white;
        transform: translateY(-2px);
    }

    /* Industrial Card */
    .industrial-card {
        background: white;
        border: 2px solid var(--neutral-200);
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .industrial-card-header {
        background: linear-gradient(135deg, var(--neutral-50) 0%, white 100%);
        border-bottom: 2px solid var(--neutral-200);
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .industrial-card-header i {
        font-size: 1.1rem;
        color: var(--uitm-amber);
    }

    .industrial-card-title {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 1rem;
        font-weight: 600;
        color: var(--industrial-dark);
        margin: 0;
    }

    .industrial-card-body {
        padding: 1.5rem;
    }

    /* Form Styles */
    .form-section-title {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--uitm-primary);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--neutral-100);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-section-title i {
        color: var(--uitm-amber);
    }

    .form-floating > .form-control {
        border: 2px solid var(--neutral-200);
        border-radius: 10px;
        padding: 1rem 0.875rem;
        font-family: 'IBM Plex Sans', sans-serif;
        transition: all 0.2s ease;
    }

    .form-floating > .form-control:focus {
        border-color: var(--uitm-primary);
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
    }

    .form-floating > label {
        font-family: 'IBM Plex Sans', sans-serif;
        color: var(--neutral-500);
    }

    .form-control.is-invalid {
        border-color: var(--danger);
    }

    .form-control:focus.is-invalid {
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    }

    /* File Upload */
    .file-upload-area {
        border: 2px dashed var(--neutral-300);
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        transition: all 0.2s ease;
        background: var(--neutral-50);
        position: relative;
        cursor: pointer;
    }

    .file-upload-area:hover {
        border-color: var(--uitm-primary);
        background: rgba(30, 58, 138, 0.02);
    }

    .file-upload-area.has-file {
        border-color: var(--success);
        background: var(--success-light);
    }

    .file-upload-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        border: 2px solid var(--neutral-200);
    }

    .file-upload-icon i {
        font-size: 1.5rem;
        color: var(--uitm-primary);
    }

    .file-upload-text {
        font-size: 0.95rem;
        color: var(--neutral-600);
        margin-bottom: 0.5rem;
    }

    .file-upload-hint {
        font-size: 0.8rem;
        color: var(--neutral-500);
    }

    .file-input-hidden {
        position: absolute;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
        top: 0;
        left: 0;
    }

    /* Alert Styles */
    .alert-industrial {
        border: none;
        border-radius: 12px;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .alert-industrial.info {
        background: var(--teal-light);
        border-left: 4px solid var(--teal);
    }

    .alert-industrial.success {
        background: var(--success-light);
        border-left: 4px solid var(--success);
    }

    .alert-industrial.danger {
        background: var(--danger-light);
        border-left: 4px solid var(--danger);
    }

    .alert-industrial-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .alert-industrial.info .alert-industrial-icon {
        background: var(--teal);
        color: white;
    }

    .alert-industrial.success .alert-industrial-icon {
        background: var(--success);
        color: white;
    }

    .alert-industrial.danger .alert-industrial-icon {
        background: var(--danger);
        color: white;
    }

    .alert-industrial-content h6 {
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }

    .alert-industrial.info .alert-industrial-content h6 {
        color: #0f766e;
    }

    .alert-industrial.success .alert-industrial-content h6 {
        color: #047857;
    }

    .alert-industrial.danger .alert-industrial-content h6 {
        color: #b91c1c;
    }

    .alert-industrial-content p,
    .alert-industrial-content ul {
        margin: 0;
        font-size: 0.85rem;
    }

    .alert-industrial.info .alert-industrial-content p,
    .alert-industrial.info .alert-industrial-content ul {
        color: #115e59;
    }

    .alert-industrial-content ul {
        padding-left: 1.25rem;
    }

    /* Buttons */
    .btn-primary-industrial {
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        border: none;
        color: white;
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.9rem;
        padding: 0.875rem 1.75rem;
        border-radius: 10px;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary-industrial:hover {
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(30, 58, 138, 0.3);
    }

    .btn-secondary-industrial {
        background: white;
        border: 2px solid var(--neutral-300);
        color: var(--neutral-700);
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.9rem;
        padding: 0.875rem 1.75rem;
        border-radius: 10px;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-secondary-industrial:hover {
        background: var(--neutral-50);
        border-color: var(--neutral-400);
        color: var(--neutral-600);
    }

    .btn-fill-form {
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        border: none;
        color: white;
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.8rem;
        padding: 0.5rem 0.875rem;
        border-radius: 8px;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
    }

    .btn-fill-form:hover {
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
    }

    /* Available Courses Table */
    .courses-card-header {
        border-left: 4px solid var(--warning);
    }

    .courses-card-header i {
        color: var(--warning);
    }

    .industrial-table {
        width: 100%;
        border-collapse: collapse;
    }

    .industrial-table thead th {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--neutral-500);
        background: var(--neutral-50);
        padding: 0.875rem 1rem;
        border-bottom: 2px solid var(--neutral-200);
        text-align: left;
    }

    .industrial-table tbody td {
        padding: 1rem;
        border-bottom: 1px solid var(--neutral-100);
        font-size: 0.9rem;
        color: var(--neutral-700);
        vertical-align: middle;
    }

    .industrial-table tbody tr:hover {
        background: var(--neutral-50);
    }

    .industrial-table tbody tr:last-child td {
        border-bottom: none;
    }

    .course-code {
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 600;
        color: var(--uitm-primary);
        background: rgba(30, 58, 138, 0.08);
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.85rem;
    }

    .course-name {
        font-weight: 500;
        color: var(--industrial-dark);
    }

    .student-name {
        color: var(--neutral-600);
        font-size: 0.85rem;
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
        }

        .page-title {
            font-size: 1.25rem;
        }

        .industrial-card-body {
            padding: 1rem;
        }

        .industrial-table {
            font-size: 0.85rem;
        }

        .industrial-table thead th,
        .industrial-table tbody td {
            padding: 0.75rem 0.5rem;
        }

        .d-md-flex {
            flex-direction: column;
        }

        .d-md-flex .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <div>
                    <h1 class="page-title">Upload Course Syllabus</h1>
                    <p class="page-subtitle">Submit course syllabi to help UiTM evaluate credit exemption applications</p>
                </div>
                <a href="{{ route('external_lecturer.dashboard') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i>
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Upload Form Card -->
            <div class="industrial-card">
                <div class="industrial-card-header">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <h5 class="industrial-card-title">Syllabus Upload Form</h5>
                </div>
                <div class="industrial-card-body">
                    @if(session('success'))
                        <div class="alert-industrial success mb-4">
                            <div class="alert-industrial-icon">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="alert-industrial-content">
                                <h6>Success</h6>
                                <p>{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert-industrial danger mb-4">
                            <div class="alert-industrial-icon">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="alert-industrial-content">
                                <h6>Please correct the following errors</h6>
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('external_lecturer.store_general') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Course Information -->
                        <div class="form-section-title">
                            <i class="fas fa-book"></i>
                            Course Information
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text"
                                           class="form-control @error('course_code') is-invalid @enderror"
                                           id="course_code"
                                           name="course_code"
                                           value="{{ old('course_code', request('course')) }}"
                                           placeholder="Course Code"
                                           required>
                                    <label for="course_code">Course Code *</label>
                                    @error('course_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="number"
                                           class="form-control @error('credit_hours') is-invalid @enderror"
                                           id="credit_hours"
                                           name="credit_hours"
                                           value="{{ old('credit_hours') }}"
                                           placeholder="Credit Hours"
                                           min="1"
                                           max="6"
                                           required>
                                    <label for="credit_hours">Credit Hours *</label>
                                    @error('credit_hours')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text"
                                   class="form-control @error('course_name') is-invalid @enderror"
                                   id="course_name"
                                   name="course_name"
                                   value="{{ old('course_name') }}"
                                   placeholder="Course Name"
                                   required>
                            <label for="course_name">Course Name *</label>
                            @error('course_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text"
                                   class="form-control @error('institution_name') is-invalid @enderror"
                                   id="institution_name"
                                   name="institution_name"
                                   value="{{ old('institution_name', Auth::user()->externalLecturer->institution_name ?? '') }}"
                                   placeholder="Institution Name"
                                   required>
                            <label for="institution_name">Institution Name *</label>
                            @error('institution_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating mb-4">
                            <textarea class="form-control @error('course_description') is-invalid @enderror"
                                      id="course_description"
                                      name="course_description"
                                      placeholder="Course Description"
                                      style="height: 100px">{{ old('course_description') }}</textarea>
                            <label for="course_description">Course Description (Optional)</label>
                            @error('course_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- File Upload -->
                        <div class="form-section-title">
                            <i class="fas fa-file-pdf"></i>
                            Syllabus Document
                        </div>

                        <div class="file-upload-area mb-4" id="fileUploadArea">
                            <div class="file-upload-icon">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <div class="file-upload-text" id="fileUploadText">
                                Click to upload or drag and drop
                            </div>
                            <div class="file-upload-hint">
                                PDF file only (max 5MB). Include course objectives, topics covered, and assessment methods.
                            </div>
                            <input type="file"
                                   class="file-input-hidden @error('syllabus_file') is-invalid @enderror"
                                   id="syllabus_file"
                                   name="syllabus_file"
                                   accept=".pdf"
                                   required>
                        </div>
                        @error('syllabus_file')
                            <div class="text-danger mb-3" style="font-size: 0.875rem; margin-top: -1rem;">{{ $message }}</div>
                        @enderror

                        <!-- Submission Guidelines -->
                        <div class="alert-industrial info mb-4">
                            <div class="alert-industrial-icon">
                                <i class="fas fa-lightbulb"></i>
                            </div>
                            <div class="alert-industrial-content">
                                <h6>Submission Guidelines</h6>
                                <ul>
                                    <li>Ensure the syllabus is comprehensive and includes learning outcomes</li>
                                    <li>Include course prerequisites and assessment breakdown</li>
                                    <li>Provide detailed weekly topic coverage if available</li>
                                    <li>The document should be officially formatted and institution-branded</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex flex-column flex-md-row justify-content-end gap-2">
                            <a href="{{ route('external_lecturer.dashboard') }}" class="btn-secondary-industrial">
                                <i class="fas fa-times"></i>
                                Cancel
                            </a>
                            <button type="submit" class="btn-primary-industrial">
                                <i class="fas fa-cloud-upload-alt"></i>
                                Upload Syllabus
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Available Courses Card -->
            @if(isset($availableCourses) && $availableCourses->count() > 0)
            <div class="industrial-card">
                <div class="industrial-card-header courses-card-header">
                    <i class="fas fa-list"></i>
                    <h5 class="industrial-card-title">Courses Requesting Syllabi</h5>
                </div>
                <div class="industrial-card-body p-0">
                    <div class="table-responsive">
                        <table class="industrial-table">
                            <thead>
                                <tr>
                                    <th>Course</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($availableCourses as $course)
                                <tr>
                                    <td>
                                        <div>
                                            <span class="course-code">{{ $course->course_code }}</span>
                                        </div>
                                        <div class="course-name mt-1">{{ $course->course_name }}</div>
                                        <div class="student-name mt-1">
                                            <i class="fas fa-user-graduate me-1" style="font-size: 0.75rem;"></i>
                                            {{ $course->exemptionApplication->student_name ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td>
                                        <button type="button"
                                                class="btn-fill-form fill-course-btn"
                                                data-course-code="{{ $course->course_code }}"
                                                data-course-name="{{ $course->course_name }}"
                                                data-credit-hours="{{ $course->credit_hour }}">
                                            <i class="fas fa-fill"></i>
                                            Fill
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @else
            <div class="industrial-card">
                <div class="industrial-card-body text-center py-4">
                    <div style="width: 60px; height: 60px; border-radius: 50%; background: var(--neutral-100); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                        <i class="fas fa-inbox" style="font-size: 1.5rem; color: var(--neutral-400);"></i>
                    </div>
                    <p style="color: var(--neutral-500); font-size: 0.9rem; margin: 0;">No pending syllabus requests</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fill form from available courses
    document.querySelectorAll('.fill-course-btn').forEach(button => {
        button.addEventListener('click', function() {
            const courseCode = this.dataset.courseCode;
            const courseName = this.dataset.courseName;
            const creditHours = this.dataset.creditHours;

            document.getElementById('course_code').value = courseCode;
            document.getElementById('course_name').value = courseName;
            document.getElementById('credit_hours').value = creditHours;

            // Scroll to form
            document.querySelector('.industrial-card').scrollIntoView({ behavior: 'smooth' });
        });
    });

    // File upload handling
    const fileInput = document.getElementById('syllabus_file');
    const fileUploadArea = document.getElementById('fileUploadArea');
    const fileUploadText = document.getElementById('fileUploadText');

    if (fileInput && fileUploadArea) {
        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                fileUploadArea.classList.add('has-file');
                fileUploadText.innerHTML = `<i class="fas fa-file-pdf text-danger me-2"></i>${file.name} <span style="color: var(--neutral-500);">(${(file.size / 1024 / 1024).toFixed(2)} MB)</span>`;
            } else {
                fileUploadArea.classList.remove('has-file');
                fileUploadText.textContent = 'Click to upload or drag and drop';
            }
        });

        // Drag and drop
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            fileUploadArea.addEventListener(eventName, function(e) {
                e.preventDefault();
                e.stopPropagation();
            }, false);
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            fileUploadArea.addEventListener(eventName, function() {
                fileUploadArea.style.borderColor = 'var(--uitm-primary)';
                fileUploadArea.style.background = 'rgba(30, 58, 138, 0.05)';
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            fileUploadArea.addEventListener(eventName, function() {
                fileUploadArea.style.borderColor = '';
                fileUploadArea.style.background = '';
            }, false);
        });

        fileUploadArea.addEventListener('drop', function(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files.length > 0 && files[0].type === 'application/pdf') {
                fileInput.files = files;
                fileInput.dispatchEvent(new Event('change'));
            }
        }, false);
    }
});
</script>
@endpush
