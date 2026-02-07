@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">
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
        background: linear-gradient(135deg, var(--success) 0%, #10b981 100%);
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
        color: rgba(255,255,255,0.9);
        margin: 0;
    }

    /* Info Banner */
    .info-banner {
        background: rgba(13, 148, 136, 0.1);
        border: 1px solid rgba(13, 148, 136, 0.2);
        border-left: 4px solid var(--info);
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        color: var(--info);
        margin-bottom: 1.5rem;
    }

    .info-banner h6 {
        color: var(--info);
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-banner p {
        margin: 0;
        color: var(--industrial-gray);
    }

    /* Student Request Context */
    .request-context {
        background: rgba(30, 58, 138, 0.1);
        border: 1px solid rgba(30, 58, 138, 0.2);
        border-left: 4px solid var(--uitm-blue);
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.5rem;
    }

    .request-context h6 {
        color: var(--uitm-blue);
        font-weight: 600;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .request-context p {
        margin: 0.35rem 0;
        color: var(--industrial-gray);
    }

    .request-context strong {
        color: var(--industrial-dark);
    }

    .request-context small {
        color: #94a3b8;
    }

    /* Main Card */
    .main-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        padding: 2rem;
        margin-bottom: 1.5rem;
    }

    .section-title {
        font-weight: 600;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-title.primary {
        color: var(--uitm-blue);
    }

    .section-title.info {
        color: var(--info);
    }

    .section-title.warning {
        color: var(--warning);
    }

    .form-label {
        font-weight: 600;
        color: var(--industrial-dark);
        margin-bottom: 0.5rem;
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

    .input-group-text {
        background: var(--industrial-light);
        border: 2px solid #e2e8f0;
        border-left: none;
        border-radius: 0 10px 10px 0;
        color: var(--industrial-gray);
    }

    .input-group .form-control {
        border-right: none;
        border-radius: 10px 0 0 10px;
    }

    /* Alert Styles */
    .alert-industrial {
        border-radius: 12px;
        border: none;
        padding: 1rem 1.25rem;
    }

    .alert-industrial.alert-danger {
        background: rgba(220, 38, 38, 0.1);
        border-left: 4px solid var(--danger);
        color: var(--danger);
    }

    /* Buttons */
    .btn-cancel {
        background: var(--industrial-light);
        color: var(--industrial-gray);
        border: none;
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
        background: #e2e8f0;
        color: var(--industrial-dark);
    }

    .btn-submit {
        background: linear-gradient(135deg, var(--success) 0%, #10b981 100%);
        color: white;
        border: none;
        padding: 0.875rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(5, 150, 105, 0.3);
        color: white;
    }

    /* Help Card */
    .help-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        overflow: hidden;
    }

    .help-card-header {
        background: var(--info);
        color: white;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
    }

    .help-card-body {
        padding: 1.25rem;
    }

    .help-card-body ol {
        margin: 0;
        padding-left: 1.25rem;
    }

    .help-card-body li {
        padding: 0.5rem 0;
        color: var(--industrial-gray);
    }

    .help-card-body li strong {
        color: var(--industrial-dark);
    }

    .help-card-body ul {
        margin-top: 0.5rem;
        margin-bottom: 0;
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
        <h2><i class="fas fa-paper-plane me-2"></i>Forward Course Mapping to Program Coordinator</h2>
        <p><i class="fas fa-exchange-alt me-2"></i>Submit a new course equivalency mapping for review and approval</p>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Explanation -->
            <div class="info-banner">
                <h6><i class="fas fa-info-circle"></i>About Forwarding Mappings</h6>
                <p>After evaluating a course syllabus, you can create a course equivalency mapping and forward it to the Program Coordinator. The Program Coordinator will review your mapping and decide whether to add it to the official equivalency list.</p>
            </div>

            <div class="main-card">
                <form action="{{ route('resource_person.equivalency_mappings.store') }}" method="POST">
                    @csrf

                    @if($studentRequest)
                        <!-- Student Request Context -->
                        <div class="request-context">
                            <h6><i class="fas fa-user-graduate"></i>Student Equivalency Request</h6>
                            <p><strong>Student:</strong> {{ $studentRequest->student->user->name ?? 'N/A' }}</p>
                            <p><strong>Diploma Course:</strong> {{ $studentRequest->diploma_course_code }} - {{ $studentRequest->diploma_course_name }}</p>
                            <p><strong>Suggested Degree Course:</strong> {{ $studentRequest->suggested_degree_course_code }}</p>
                            <small>This mapping is in response to a student's equivalency request.</small>
                        </div>
                        <input type="hidden" name="course_equivalency_request_id" value="{{ $studentRequest->id }}">
                    @endif

                    <!-- Program Selection -->
                    <div class="mb-4">
                        <label for="program_code" class="form-label">Target Degree Program <span class="text-danger">*</span></label>
                        <select name="program_code" id="program_code" class="form-select @error('program_code') is-invalid @enderror" required>
                            <option value="">-- Select Degree Program --</option>
                            @foreach($programs as $code => $name)
                                <option value="{{ $code }}" {{ old('program_code', $studentRequest->current_program_code ?? '') == $code ? 'selected' : '' }}>
                                    {{ $code }} - {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text">Select the degree program this equivalency applies to</small>
                        @error('program_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-4">

                    <!-- Diploma Course Section -->
                    <h5 class="section-title primary"><i class="fas fa-graduation-cap"></i>Diploma Course Information</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="diploma_course_code" class="form-label">Course Code <span class="text-danger">*</span></label>
                            <input type="text" name="diploma_course_code" id="diploma_course_code" class="form-control @error('diploma_course_code') is-invalid @enderror" placeholder="e.g., DCS210" value="{{ old('diploma_course_code', $studentRequest->diploma_course_code ?? '') }}" required>
                            @error('diploma_course_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="diploma_credit_hour" class="form-label">Credit Hours <span class="text-danger">*</span></label>
                            <input type="number" name="diploma_credit_hour" id="diploma_credit_hour" class="form-control @error('diploma_credit_hour') is-invalid @enderror" min="1" max="10" value="{{ old('diploma_credit_hour', $studentRequest->diploma_credit_hours ?? 3) }}" required>
                            @error('diploma_credit_hour')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="diploma_course_name" class="form-label">Course Name <span class="text-danger">*</span></label>
                        <input type="text" name="diploma_course_name" id="diploma_course_name" class="form-control @error('diploma_course_name') is-invalid @enderror" placeholder="e.g., Advanced Database Systems" value="{{ old('diploma_course_name', $studentRequest->diploma_course_name ?? '') }}" required>
                        @error('diploma_course_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="diploma_institution" class="form-label">Source Institution <span class="text-danger">*</span></label>
                        <select name="diploma_institution" id="diploma_institution" class="form-select @error('diploma_institution') is-invalid @enderror" required>
                            <option value="">-- Select Institution --</option>
                            @foreach($institutions as $key => $name)
                                <option value="{{ $name }}" {{ old('diploma_institution', $studentRequest->diploma_institution ?? '') == $name ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        @error('diploma_institution')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-4">

                    <!-- Degree Course Section -->
                    <h5 class="section-title info"><i class="fas fa-university"></i>Equivalent Degree Course</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="degree_course_code" class="form-label">Course Code <span class="text-danger">*</span></label>
                            <input type="text" name="degree_course_code" id="degree_course_code" class="form-control @error('degree_course_code') is-invalid @enderror" placeholder="e.g., CS210" value="{{ old('degree_course_code', $studentRequest->suggested_degree_course_code ?? '') }}" required>
                            @error('degree_course_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="degree_credit_hour" class="form-label">Credit Hours <span class="text-danger">*</span></label>
                            <input type="number" name="degree_credit_hour" id="degree_credit_hour" class="form-control @error('degree_credit_hour') is-invalid @enderror" min="1" max="10" value="{{ old('degree_credit_hour', 3) }}" required>
                            @error('degree_credit_hour')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="degree_course_name" class="form-label">Course Name <span class="text-danger">*</span></label>
                        <input type="text" name="degree_course_name" id="degree_course_name" class="form-control @error('degree_course_name') is-invalid @enderror" placeholder="e.g., Database Management Systems" value="{{ old('degree_course_name', $studentRequest->suggested_degree_course_name ?? '') }}" required>
                        @error('degree_course_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-4">

                    <!-- Equivalency Assessment -->
                    <h5 class="section-title warning"><i class="fas fa-percentage"></i>Equivalency Assessment</h5>

                    <div class="mb-3">
                        <label for="match_percentage" class="form-label">Match Percentage <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="match_percentage" id="match_percentage" class="form-control @error('match_percentage') is-invalid @enderror" min="0" max="100" step="0.01" value="{{ old('match_percentage', 85) }}" required>
                            <span class="input-group-text">%</span>
                            @error('match_percentage')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="form-text">Based on your syllabus evaluation, estimate the percentage match between these courses. Courses with ≥80% match are typically eligible for exemption.</small>
                    </div>

                    <div class="mb-4">
                        <label for="notes" class="form-label">Notes / Justification (Optional)</label>
                        <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="4" placeholder="Add any notes about this mapping, such as similarities in learning outcomes, teaching methods, or assessment criteria...">{{ old('notes', $studentRequest->justification_notes ?? '') }}</textarea>
                        <small class="form-text">Maximum 1000 characters</small>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Error Messages -->
                    @if($errors->any() && !$errors->has('program_code') && !$errors->has('diploma_course_code') && !$errors->has('diploma_course_name'))
                        <div class="alert alert-industrial alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('resource_person.equivalency_lists.index') }}" class="btn-cancel">
                            <i class="fas fa-times"></i>Cancel
                        </a>
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-paper-plane"></i>Forward to Program Coordinator
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Information Panel -->
            <div class="help-card">
                <div class="help-card-header">
                    <i class="fas fa-info-circle"></i>What Happens Next?
                </div>
                <div class="help-card-body">
                    <ol>
                        <li>Your course mapping will be sent to the <strong>Program Coordinator</strong> for review</li>
                        <li>The Program Coordinator will evaluate your mapping</li>
                        <li>They can either:
                            <ul>
                                <li><strong>Add it</strong> to the official equivalency list for the program</li>
                                <li><strong>Reject it</strong> with a reason for rejection</li>
                            </ul>
                        </li>
                        <li>You can track the status in your dashboard under "<strong>Forwarded Mappings</strong>"</li>
                        <li>If added, the mapping becomes part of the published equivalency list for students</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
