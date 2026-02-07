<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Syllabus Submission - UiTM Credit Exemption System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --uitm-primary: #1e3a8a;
            --uitm-primary-dark: #1e2d5b;
            --uitm-primary-light: #3b5998;
            --uitm-amber: #f59e0b;
            --uitm-amber-dark: #d97706;
            --uitm-green: #10b981;
            --uitm-green-dark: #059669;
            --uitm-red: #dc2626;
            --slate-50: #f8fafc;
            --slate-100: #f1f5f9;
            --slate-200: #e2e8f0;
            --slate-300: #cbd5e1;
            --slate-400: #94a3b8;
            --slate-500: #64748b;
            --slate-600: #475569;
            --slate-700: #334155;
            --slate-800: #1e293b;
            --slate-900: #0f172a;
            --font-sans: 'IBM Plex Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            --font-mono: 'IBM Plex Mono', monospace;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-sans);
            background: var(--slate-100);
            min-height: 100vh;
            color: var(--slate-700);
        }

        /* ========== PAGE HEADER ========== */
        .page-header {
            background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
            position: relative;
            padding: 2.5rem 0;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image:
                linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px),
                linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 24px 24px;
            pointer-events: none;
        }

        .page-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--uitm-amber), var(--uitm-green), var(--uitm-amber));
        }

        .header-content {
            position: relative;
            z-index: 1;
        }

        .eyebrow-text {
            font-family: var(--font-mono);
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            color: var(--uitm-amber);
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .eyebrow-text::before {
            content: '';
            width: 32px;
            height: 2px;
            background: var(--uitm-amber);
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: white;
            margin: 0 0 0.5rem 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .page-title i {
            font-size: 1.75rem;
            opacity: 0.9;
        }

        .page-subtitle {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.7);
            margin: 0;
        }

        .uitm-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 0.5rem 1rem;
            border-radius: 6px;
            color: white;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .uitm-badge img {
            height: 24px;
            width: auto;
        }

        /* ========== MAIN CONTENT ========== */
        .main-content {
            padding: 2.5rem 0 4rem;
        }

        /* ========== CARDS ========== */
        .card-industrial {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--slate-200);
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .card-header-industrial {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--slate-200);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .card-header-industrial.primary {
            background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
            color: white;
            border-bottom: 3px solid var(--uitm-amber);
        }

        .card-header-industrial.info {
            background: var(--slate-50);
        }

        .card-header-industrial h5 {
            margin: 0;
            font-weight: 600;
            font-size: 1.0625rem;
        }

        .card-header-industrial i {
            font-size: 1.125rem;
        }

        .card-header-industrial.primary i {
            color: var(--uitm-amber);
        }

        .card-header-industrial.info i {
            color: var(--uitm-primary);
        }

        .card-body-industrial {
            padding: 1.5rem;
        }

        /* ========== REQUEST DETAILS ========== */
        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.25rem;
        }

        @media (max-width: 768px) {
            .detail-grid {
                grid-template-columns: 1fr;
            }
        }

        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .detail-label {
            font-family: var(--font-mono);
            font-size: 0.6875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--slate-500);
        }

        .detail-value {
            font-size: 0.9375rem;
            font-weight: 500;
            color: var(--slate-800);
        }

        .detail-value.highlight {
            color: var(--uitm-primary);
            font-weight: 600;
        }

        .alert-industrial {
            border-radius: 8px;
            padding: 1rem 1.25rem;
            display: flex;
            align-items: flex-start;
            gap: 0.875rem;
            margin-top: 1.25rem;
        }

        .alert-industrial.warning {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(245, 158, 11, 0.05) 100%);
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .alert-industrial.warning i {
            color: var(--uitm-amber);
        }

        .alert-industrial.info {
            background: linear-gradient(135deg, rgba(30, 58, 138, 0.08) 0%, rgba(30, 58, 138, 0.04) 100%);
            border: 1px solid rgba(30, 58, 138, 0.15);
        }

        .alert-industrial.info i {
            color: var(--uitm-primary);
        }

        .alert-industrial i {
            font-size: 1.125rem;
            margin-top: 0.125rem;
        }

        .alert-industrial-content {
            flex: 1;
        }

        .alert-industrial-content strong {
            display: block;
            margin-bottom: 0.25rem;
            color: var(--slate-800);
        }

        .alert-industrial-content p {
            margin: 0;
            font-size: 0.875rem;
            color: var(--slate-600);
            line-height: 1.5;
        }

        /* ========== FORM STYLES ========== */
        .form-section {
            margin-bottom: 2rem;
        }

        .form-section:last-child {
            margin-bottom: 0;
        }

        .form-section-title {
            font-family: var(--font-mono);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--slate-500);
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--slate-200);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-section-title i {
            color: var(--uitm-primary);
        }

        .form-label-industrial {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--slate-700);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .form-label-industrial .required {
            color: var(--uitm-red);
        }

        .form-control-industrial {
            padding: 0.75rem 1rem;
            border: 2px solid var(--slate-200);
            border-radius: 8px;
            font-family: var(--font-sans);
            font-size: 0.9375rem;
            color: var(--slate-700);
            transition: all 0.2s ease;
            background: white;
        }

        .form-control-industrial:focus {
            outline: none;
            border-color: var(--uitm-primary);
            box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
        }

        .form-control-industrial.is-invalid {
            border-color: var(--uitm-red);
        }

        .form-control-industrial.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }

        .invalid-feedback {
            font-size: 0.8125rem;
            color: var(--uitm-red);
            margin-top: 0.375rem;
        }

        .form-hint {
            font-size: 0.8125rem;
            color: var(--slate-500);
            margin-top: 0.5rem;
            display: flex;
            align-items: flex-start;
            gap: 0.375rem;
            line-height: 1.5;
        }

        .form-hint i {
            margin-top: 0.125rem;
            color: var(--slate-400);
        }

        textarea.form-control-industrial {
            resize: vertical;
            min-height: 120px;
        }

        /* File Upload */
        .file-upload-area {
            border: 2px dashed var(--slate-300);
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            background: var(--slate-50);
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .file-upload-area:hover {
            border-color: var(--uitm-primary);
            background: rgba(30, 58, 138, 0.02);
        }

        .file-upload-area.dragover {
            border-color: var(--uitm-primary);
            background: rgba(30, 58, 138, 0.05);
        }

        .file-upload-icon {
            width: 48px;
            height: 48px;
            background: white;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .file-upload-icon i {
            font-size: 1.5rem;
            color: var(--uitm-primary);
        }

        .file-upload-text {
            font-weight: 500;
            color: var(--slate-700);
            margin-bottom: 0.25rem;
        }

        .file-upload-hint {
            font-size: 0.8125rem;
            color: var(--slate-500);
        }

        .file-upload-input {
            display: none;
        }

        .selected-file {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.2);
            border-radius: 8px;
            margin-top: 1rem;
        }

        .selected-file i {
            color: var(--uitm-green);
            font-size: 1.25rem;
        }

        .selected-file-name {
            flex: 1;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--slate-700);
        }

        .selected-file-remove {
            background: none;
            border: none;
            color: var(--slate-400);
            cursor: pointer;
            padding: 0.25rem;
            transition: color 0.2s ease;
        }

        .selected-file-remove:hover {
            color: var(--uitm-red);
        }

        /* ========== SUBMIT BUTTON ========== */
        .btn-submit-industrial {
            background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
            color: white;
            border: none;
            padding: 1rem 2.5rem;
            border-radius: 10px;
            font-family: var(--font-sans);
            font-size: 1rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
        }

        .btn-submit-industrial:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(30, 58, 138, 0.4);
        }

        .btn-submit-industrial:active {
            transform: translateY(0);
        }

        .btn-submit-industrial i {
            font-size: 1.125rem;
        }

        /* ========== SECURITY FOOTER ========== */
        .security-footer {
            background: var(--slate-800);
            border-radius: 10px;
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 2rem;
        }

        .security-icon {
            width: 40px;
            height: 40px;
            background: rgba(16, 185, 129, 0.15);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .security-icon i {
            color: var(--uitm-green);
            font-size: 1.125rem;
        }

        .security-text {
            flex: 1;
        }

        .security-text strong {
            display: block;
            color: white;
            font-size: 0.875rem;
            margin-bottom: 0.125rem;
        }

        .security-text span {
            color: var(--slate-400);
            font-size: 0.8125rem;
        }

        /* ========== ERROR ALERT ========== */
        .error-alert {
            background: linear-gradient(135deg, rgba(220, 38, 38, 0.1) 0%, rgba(220, 38, 38, 0.05) 100%);
            border: 1px solid rgba(220, 38, 38, 0.2);
            border-radius: 10px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .error-alert i {
            color: var(--uitm-red);
            font-size: 1.25rem;
        }

        .error-alert span {
            color: var(--uitm-red);
            font-weight: 500;
        }

        /* ========== ANIMATIONS ========== */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-industrial {
            animation: fadeInUp 0.5s ease forwards;
        }

        .card-industrial:nth-child(1) { animation-delay: 0.1s; }
        .card-industrial:nth-child(2) { animation-delay: 0.2s; }
    </style>
</head>
<body>
    <!-- Page Header -->
    <header class="page-header">
        <div class="container">
            <div class="header-content">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <div class="eyebrow-text">External Submission Portal</div>
                        <h1 class="page-title">
                            <i class="fas fa-file-upload"></i>
                            Syllabus Submission
                        </h1>
                        <p class="page-subtitle">Submit your course syllabus for credit exemption evaluation</p>
                    </div>
                    <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                        <div class="uitm-badge">
                            <i class="fas fa-university"></i>
                            UiTM Credit Exemption System
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">

                    @if(session('error'))
                        <div class="error-alert">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif

                    <!-- Request Details Card -->
                    <div class="card-industrial">
                        <div class="card-header-industrial info">
                            <i class="fas fa-info-circle"></i>
                            <h5>Submission Request Details</h5>
                        </div>
                        <div class="card-body-industrial">
                            @if($requestType === 'application_subject')
                                {{-- ApplicationSubject Request --}}
                                <div class="detail-grid">
                                    <div class="detail-item">
                                        <span class="detail-label">Requested Course</span>
                                        <span class="detail-value highlight">{{ $applicationSubject->course_code }} - {{ $applicationSubject->course_name }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Student Program</span>
                                        <span class="detail-value">{{ $exemptionApplication->current_program_code ?? 'N/A' }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Request Date</span>
                                        <span class="detail-value">{{ $request->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>
                            @else
                                {{-- CourseEquivalencyRequest --}}
                                <div class="detail-grid">
                                    <div class="detail-item">
                                        <span class="detail-label">Requested Course</span>
                                        <span class="detail-value highlight">{{ $equivalencyRequest->diploma_course_code }} - {{ $equivalencyRequest->diploma_course_name }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Student</span>
                                        <span class="detail-value">{{ $equivalencyRequest->student->user->name }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Current Program</span>
                                        <span class="detail-value">{{ $equivalencyRequest->current_program_code }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Request Date</span>
                                        <span class="detail-value">{{ $request->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>

                                <div class="alert-industrial warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <div class="alert-industrial-content">
                                        <strong>Important Notice</strong>
                                        <p>This is an official course equivalency verification request. Please submit the complete and authentic course syllabus for <strong>{{ $equivalencyRequest->diploma_course_code }}</strong> from your institution.</p>
                                    </div>
                                </div>
                            @endif

                            @if($request->request_notes)
                                <div class="alert-industrial info">
                                    <i class="fas fa-comment-alt"></i>
                                    <div class="alert-industrial-content">
                                        <strong>Notes from Resource Person</strong>
                                        <p>{{ $request->request_notes }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Submission Form Card -->
                    <div class="card-industrial">
                        <div class="card-header-industrial primary">
                            <i class="fas fa-edit"></i>
                            <h5>Complete Syllabus Submission Form</h5>
                        </div>
                        <div class="card-body-industrial">
                            <form method="POST" action="{{ route('external.lecturer.submission.submit', $request->access_token) }}" enctype="multipart/form-data" id="syllabusForm">
                                @csrf

                                <!-- Personal Information Section -->
                                <div class="form-section">
                                    <div class="form-section-title">
                                        <i class="fas fa-user"></i>
                                        Personal Information
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="external_lecturer_name" class="form-label-industrial">
                                                Your Full Name <span class="required">*</span>
                                            </label>
                                            <input type="text"
                                                   name="external_lecturer_name"
                                                   id="external_lecturer_name"
                                                   class="form-control form-control-industrial @error('external_lecturer_name') is-invalid @enderror"
                                                   value="{{ old('external_lecturer_name') }}"
                                                   placeholder="Enter your full name"
                                                   required>
                                            @error('external_lecturer_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="institution_name" class="form-label-industrial">
                                                Institution Name <span class="required">*</span>
                                            </label>
                                            <input type="text"
                                                   name="institution_name"
                                                   id="institution_name"
                                                   class="form-control form-control-industrial @error('institution_name') is-invalid @enderror"
                                                   value="{{ old('institution_name') }}"
                                                   placeholder="Enter your institution name"
                                                   required>
                                            @error('institution_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Course Information Section -->
                                <div class="form-section">
                                    <div class="form-section-title">
                                        <i class="fas fa-book"></i>
                                        Course Information
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label for="course_code" class="form-label-industrial">
                                                Course Code <span class="required">*</span>
                                            </label>
                                            <input type="text"
                                                   name="course_code"
                                                   id="course_code"
                                                   class="form-control form-control-industrial @error('course_code') is-invalid @enderror"
                                                   value="{{ old('course_code', $requestType === 'application_subject' ? $applicationSubject->course_code : $equivalencyRequest->diploma_course_code) }}"
                                                   placeholder="e.g., CSC123"
                                                   required>
                                            @error('course_code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="course_name" class="form-label-industrial">
                                                Course Name <span class="required">*</span>
                                            </label>
                                            <input type="text"
                                                   name="course_name"
                                                   id="course_name"
                                                   class="form-control form-control-industrial @error('course_name') is-invalid @enderror"
                                                   value="{{ old('course_name', $requestType === 'application_subject' ? $applicationSubject->course_name : $equivalencyRequest->diploma_course_name) }}"
                                                   placeholder="Enter course name"
                                                   required>
                                            @error('course_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-2">
                                            <label for="credit_hours" class="form-label-industrial">
                                                Credits <span class="required">*</span>
                                            </label>
                                            <select name="credit_hours"
                                                    id="credit_hours"
                                                    class="form-control form-control-industrial @error('credit_hours') is-invalid @enderror"
                                                    required>
                                                <option value="">Select</option>
                                                @for ($i = 1; $i <= 10; $i += 0.5)
                                                    <option value="{{ number_format($i, 2, '.', '') }}" {{ old('credit_hours') == number_format($i, 2, '.', '') ? 'selected' : '' }}>
                                                        {{ number_format($i, 2) }}
                                                    </option>
                                                @endfor
                                            </select>
                                            @error('credit_hours')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Justification Section -->
                                <div class="form-section">
                                    <div class="form-section-title">
                                        <i class="fas fa-align-left"></i>
                                        Justification & Notes
                                    </div>
                                    <div class="mb-3">
                                        <label for="justification_notes" class="form-label-industrial">
                                            Justification / Notes <span class="required">*</span>
                                        </label>
                                        <textarea name="justification_notes"
                                                  id="justification_notes"
                                                  class="form-control form-control-industrial @error('justification_notes') is-invalid @enderror"
                                                  rows="5"
                                                  placeholder="Please provide detailed notes explaining the course content, learning outcomes, and any relevant information for credit exemption evaluation."
                                                  required>{{ old('justification_notes') }}</textarea>
                                        <div class="form-hint">
                                            <i class="fas fa-lightbulb"></i>
                                            Include course objectives, topics covered, assessment methods, and learning outcomes for better evaluation.
                                        </div>
                                        @error('justification_notes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- File Upload Section -->
                                <div class="form-section">
                                    <div class="form-section-title">
                                        <i class="fas fa-file-pdf"></i>
                                        Syllabus Document
                                    </div>
                                    <label class="form-label-industrial">
                                        Complete Course Syllabus <span class="required">*</span>
                                    </label>
                                    <div class="file-upload-area" id="fileUploadArea">
                                        <div class="file-upload-icon">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                        </div>
                                        <div class="file-upload-text">Click to upload or drag and drop</div>
                                        <div class="file-upload-hint">PDF format only (max 5MB)</div>
                                        <input type="file"
                                               name="syllabus_file"
                                               id="syllabus_file"
                                               class="file-upload-input @error('syllabus_file') is-invalid @enderror"
                                               accept=".pdf"
                                               required>
                                    </div>
                                    <div id="selectedFile" class="selected-file" style="display: none;">
                                        <i class="fas fa-file-pdf"></i>
                                        <span class="selected-file-name"></span>
                                        <button type="button" class="selected-file-remove" onclick="removeFile()">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <div class="form-hint">
                                        <i class="fas fa-info-circle"></i>
                                        The syllabus should include course objectives, topics covered, assessment methods, and learning outcomes.
                                    </div>
                                    @error('syllabus_file')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Submit Button -->
                                <div class="text-center mt-4">
                                    <button type="submit" class="btn-submit-industrial">
                                        <i class="fas fa-paper-plane"></i>
                                        Submit Complete Syllabus
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Security Footer -->
                    <div class="security-footer">
                        <div class="security-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="security-text">
                            <strong>Secure Submission</strong>
                            <span>Your data is encrypted and will only be used for credit exemption evaluation purposes. All submissions are digitally signed for authenticity.</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // File Upload Handling
        const fileUploadArea = document.getElementById('fileUploadArea');
        const fileInput = document.getElementById('syllabus_file');
        const selectedFileDiv = document.getElementById('selectedFile');
        const selectedFileName = document.querySelector('.selected-file-name');

        fileUploadArea.addEventListener('click', () => fileInput.click());

        fileUploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            fileUploadArea.classList.add('dragover');
        });

        fileUploadArea.addEventListener('dragleave', () => {
            fileUploadArea.classList.remove('dragover');
        });

        fileUploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            fileUploadArea.classList.remove('dragover');
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                showSelectedFile(files[0]);
            }
        });

        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                showSelectedFile(e.target.files[0]);
            }
        });

        function showSelectedFile(file) {
            if (file.type === 'application/pdf') {
                selectedFileName.textContent = file.name;
                selectedFileDiv.style.display = 'flex';
                fileUploadArea.style.display = 'none';
            } else {
                alert('Please select a PDF file.');
                fileInput.value = '';
            }
        }

        function removeFile() {
            fileInput.value = '';
            selectedFileDiv.style.display = 'none';
            fileUploadArea.style.display = 'block';
        }
    </script>
</body>
</html>
