@extends('layouts.app')

@section('title', 'Submit Syllabus')

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

    .submission-wrapper {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
    }

    .submission-card {
        background: white;
        border: 2px solid var(--neutral-200);
        border-radius: 20px;
        max-width: 600px;
        width: 100%;
        overflow: hidden;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    }

    .submission-card-header {
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--industrial-dark) 100%);
        padding: 2rem;
        text-align: center;
        position: relative;
    }

    .submission-card-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(245, 158, 11, 0.2) 0%, transparent 70%);
        border-radius: 50%;
    }

    .header-icon {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        position: relative;
        z-index: 1;
    }

    .header-icon i {
        font-size: 1.5rem;
        color: var(--uitm-amber);
    }

    .submission-title {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: white;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 1;
    }

    .submission-subtitle {
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.9rem;
        position: relative;
        z-index: 1;
    }

    .submission-card-body {
        padding: 2rem;
    }

    /* Success State */
    .success-state {
        text-align: center;
        padding: 2rem;
    }

    .success-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: var(--success-light);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        animation: pulse-success 2s ease-in-out infinite;
    }

    @keyframes pulse-success {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    .success-icon i {
        font-size: 2.5rem;
        color: var(--success);
    }

    .success-title {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--success);
        margin-bottom: 0.5rem;
    }

    .success-text {
        color: var(--neutral-600);
        font-size: 0.95rem;
    }

    /* Course Details Table */
    .course-details-card {
        background: var(--neutral-50);
        border: 2px solid var(--neutral-200);
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .course-details-header {
        background: white;
        padding: 1rem 1.25rem;
        border-bottom: 2px solid var(--neutral-200);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .course-details-header i {
        color: var(--uitm-amber);
        font-size: 1.1rem;
    }

    .course-details-title {
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.95rem;
        color: var(--industrial-dark);
        margin: 0;
    }

    .course-details-body {
        padding: 0;
    }

    .detail-row {
        display: flex;
        border-bottom: 1px solid var(--neutral-200);
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        width: 40%;
        padding: 0.875rem 1.25rem;
        background: var(--neutral-100);
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--neutral-600);
        border-right: 1px solid var(--neutral-200);
    }

    .detail-value {
        width: 60%;
        padding: 0.875rem 1.25rem;
        font-size: 0.9rem;
        color: var(--industrial-dark);
        background: white;
    }

    .detail-value .course-code {
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 600;
        color: var(--uitm-primary);
        background: rgba(30, 58, 138, 0.08);
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.85rem;
    }

    /* Form Styles */
    .form-section {
        margin-bottom: 1.5rem;
    }

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

    .form-label-industrial {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--neutral-700);
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-control-industrial {
        border: 2px solid var(--neutral-200);
        border-radius: 10px;
        padding: 0.75rem 1rem;
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        width: 100%;
    }

    .form-control-industrial:focus {
        border-color: var(--uitm-primary);
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
        outline: none;
    }

    .form-control-industrial::placeholder {
        color: var(--neutral-400);
    }

    /* File Upload */
    .file-upload-area {
        border: 2px dashed var(--neutral-300);
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.2s ease;
        background: var(--neutral-50);
        cursor: pointer;
        position: relative;
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
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        border: 2px solid var(--neutral-200);
    }

    .file-upload-icon i {
        font-size: 1.25rem;
        color: var(--uitm-primary);
    }

    .file-upload-text {
        font-size: 0.9rem;
        color: var(--neutral-600);
        margin-bottom: 0.25rem;
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
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
        cursor: pointer;
    }

    .btn-primary-industrial:hover {
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(30, 58, 138, 0.3);
    }

    /* Responsive */
    @media (max-width: 576px) {
        .submission-wrapper {
            padding: 1rem;
        }

        .submission-card-header {
            padding: 1.5rem;
        }

        .submission-title {
            font-size: 1.25rem;
        }

        .submission-card-body {
            padding: 1.5rem;
        }

        .detail-row {
            flex-direction: column;
        }

        .detail-label,
        .detail-value {
            width: 100%;
            border-right: none;
        }

        .detail-label {
            border-bottom: none;
            padding-bottom: 0.5rem;
        }

        .detail-value {
            padding-top: 0;
        }
    }
</style>
@endpush

@section('content')
<div class="submission-wrapper">
    <div class="submission-card">
        <div class="submission-card-header">
            <div class="header-icon">
                <i class="fas fa-file-upload"></i>
            </div>
            <h1 class="submission-title">Syllabus Submission</h1>
            <p class="submission-subtitle">Submit the requested course syllabus</p>
        </div>

        <div class="submission-card-body">
            @if(session('success'))
                <div class="success-state">
                    <div class="success-icon">
                        <i class="fas fa-check"></i>
                    </div>
                    <h5 class="success-title">Submission Successful</h5>
                    <p class="success-text">{{ session('success') }}</p>
                </div>
            @else
                <p class="text-center mb-4" style="color: var(--neutral-600); font-size: 0.95rem;">
                    You have been requested to provide the complete syllabus for the following course:
                </p>

                <!-- Course Details -->
                <div class="course-details-card">
                    <div class="course-details-header">
                        <i class="fas fa-book"></i>
                        <h6 class="course-details-title">Syllabus Request Details</h6>
                    </div>
                    <div class="course-details-body">
                        <div class="detail-row">
                            <div class="detail-label">Course Code</div>
                            <div class="detail-value">
                                <span class="course-code">{{ $subject->course_code }}</span>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Course Name</div>
                            <div class="detail-value">{{ $subject->course_name }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Original Institution</div>
                            <div class="detail-value">{{ $subject->exemptionApplication->previous_institution }}</div>
                        </div>
                    </div>
                </div>

                <!-- Submission Form -->
                <form method="POST" action="{{ route('syllabus.store', $subject) }}" enctype="multipart/form-data">
                    @csrf

                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="fas fa-user"></i>
                            Your Details
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="submitter_name" class="form-label-industrial">Your Name *</label>
                                <input type="text"
                                       name="submitter_name"
                                       id="submitter_name"
                                       class="form-control-industrial"
                                       placeholder="Enter your full name"
                                       required>
                            </div>
                            <div class="col-md-6">
                                <label for="submitter_email" class="form-label-industrial">Your Email *</label>
                                <input type="email"
                                       name="submitter_email"
                                       id="submitter_email"
                                       class="form-control-industrial"
                                       placeholder="Enter your email address"
                                       required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="submitter_institution" class="form-label-industrial">Your Institution Name *</label>
                            <input type="text"
                                   name="submitter_institution"
                                   id="submitter_institution"
                                   class="form-control-industrial"
                                   placeholder="Enter your institution name"
                                   required>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="fas fa-file-pdf"></i>
                            Syllabus Document
                        </div>

                        <div class="file-upload-area" id="fileUploadArea">
                            <div class="file-upload-icon">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <div class="file-upload-text" id="fileUploadText">
                                Click to upload or drag and drop
                            </div>
                            <div class="file-upload-hint">
                                PDF file only (max 5MB)
                            </div>
                            <input type="file"
                                   name="syllabus_file"
                                   id="syllabus_file"
                                   class="file-input-hidden"
                                   accept=".pdf"
                                   required>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary-industrial">
                        <i class="fas fa-paper-plane"></i>
                        Submit Syllabus
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
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
