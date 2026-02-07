@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
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

    body { font-family: 'IBM Plex Sans', sans-serif; }
    .font-mono { font-family: 'IBM Plex Mono', monospace; }

    /* Breadcrumb */
    .breadcrumb-industrial {
        background: white;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        margin-bottom: 1rem;
    }

    .breadcrumb-industrial .breadcrumb {
        margin: 0;
    }

    .breadcrumb-industrial .breadcrumb-item a {
        color: var(--uitm-blue);
        text-decoration: none;
        font-weight: 500;
    }

    .breadcrumb-industrial .breadcrumb-item.active {
        color: var(--industrial-gray);
    }

    /* Page Header */
    .page-header-simple {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .page-header-simple h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--industrial-dark);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .page-header-simple h2 i {
        color: var(--uitm-blue);
    }

    /* Comparison Cards */
    .comparison-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .comparison-header {
        padding: 0.875rem 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .comparison-header.diploma {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, #1e40af 100%);
        color: white;
    }

    .comparison-header.degree {
        background: linear-gradient(135deg, var(--success) 0%, #047857 100%);
        color: white;
    }

    .comparison-header h6 {
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .course-info-bar {
        padding: 0.75rem 1rem;
        background: var(--industrial-light);
        border-bottom: 1px solid #e2e8f0;
    }

    .course-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .course-badge .code {
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 600;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.85rem;
    }

    .course-badge.diploma .code {
        background: var(--uitm-blue);
        color: white;
    }

    .course-badge.degree .code {
        background: var(--success);
        color: white;
    }

    .course-badge .name {
        font-weight: 600;
        color: var(--industrial-dark);
    }

    .course-badge .credits {
        background: var(--industrial-gray);
        color: white;
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.75rem;
        padding: 0.2rem 0.5rem;
        border-radius: 4px;
    }

    .pdf-frame-container {
        flex: 1;
        min-height: 600px;
    }

    .pdf-frame-container iframe {
        width: 100%;
        height: 100%;
        border: none;
    }

    .pdf-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        min-height: 600px;
        background: var(--industrial-light);
        text-align: center;
        padding: 2rem;
    }

    .pdf-placeholder i {
        font-size: 4rem;
        color: #cbd5e1;
        margin-bottom: 1rem;
    }

    .pdf-placeholder h5 {
        color: var(--industrial-gray);
        font-weight: 600;
    }

    .pdf-placeholder p {
        color: #94a3b8;
    }

    /* Course Selector */
    .course-selector {
        margin-top: 0.75rem;
    }

    .course-selector label {
        font-size: 0.8rem;
        color: var(--industrial-gray);
        font-weight: 500;
        margin-bottom: 0.25rem;
    }

    .course-selector select {
        font-size: 0.875rem;
    }

    .degree-course-meta {
        margin-top: 0.75rem;
        padding: 0.5rem 0.75rem;
        background: rgba(5,150,105,0.08);
        border-radius: 6px;
        font-size: 0.85rem;
    }

    /* Recommendations Card */
    .recommendations-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .recommendations-header {
        background: var(--industrial-dark);
        color: white;
        padding: 0.875rem 1.25rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
    }

    .recommendations-header span {
        font-weight: 600;
    }

    .recommendations-table {
        margin: 0;
        font-size: 0.875rem;
    }

    .recommendations-table thead th {
        background: var(--industrial-light);
        font-weight: 600;
        color: var(--industrial-dark);
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0.75rem 1rem;
        border-bottom: 2px solid #e2e8f0;
    }

    .recommendations-table tbody td {
        padding: 0.75rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .recommendations-table tbody tr:hover {
        background: #fafbfc;
    }

    .recommendations-table tbody tr.table-warning {
        background: rgba(245,158,11,0.08);
    }

    /* Similarity Badge */
    .similarity-badge {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.8rem;
        padding: 0.3rem 0.6rem;
        border-radius: 4px;
        font-weight: 600;
    }

    .similarity-badge.high { background: rgba(5,150,105,0.15); color: var(--success); }
    .similarity-badge.medium { background: rgba(13,148,136,0.15); color: var(--info); }
    .similarity-badge.low { background: rgba(245,158,11,0.15); color: var(--warning); }
    .similarity-badge.very-low { background: rgba(51,65,85,0.15); color: var(--industrial-gray); }

    /* Action Buttons */
    .action-btn {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        font-size: 0.85rem;
    }

    /* Decision Card */
    .decision-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 2px solid var(--uitm-blue);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .decision-header {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, #1e40af 100%);
        color: white;
        padding: 1rem 1.25rem;
    }

    .decision-header h5 {
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .decision-body {
        padding: 1.5rem;
    }

    /* Decision Buttons */
    .btn-group-decision {
        display: flex;
        gap: 0;
    }

    .btn-group-decision .btn-check:checked + .btn-outline-success {
        background: var(--success);
        border-color: var(--success);
        color: white;
    }

    .btn-group-decision .btn-check:checked + .btn-outline-danger {
        background: var(--danger);
        border-color: var(--danger);
        color: white;
    }

    .btn-group-decision .btn {
        padding: 0.625rem 1rem;
        font-weight: 600;
    }

    /* Buttons */
    .btn-industrial {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .btn-primary-industrial {
        background: var(--uitm-blue);
        color: white;
        border: none;
    }

    .btn-primary-industrial:hover {
        background: #1e40af;
        color: white;
        transform: translateY(-1px);
    }

    .btn-primary-industrial:disabled {
        background: #94a3b8;
        cursor: not-allowed;
        transform: none;
    }

    /* Syllabus Status Badge */
    .syllabus-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-weight: 600;
    }

    .syllabus-badge.available {
        background: rgba(5,150,105,0.1);
        color: var(--success);
    }

    .syllabus-badge.missing {
        background: rgba(245,158,11,0.1);
        color: var(--warning);
    }

    /* No Syllabus Badge */
    .no-syllabus-badge {
        background: rgba(245,158,11,0.1);
        color: var(--warning);
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    /* Alert Warning */
    .alert-warning-industrial {
        background: rgba(234,88,12,0.08);
        border: 1px solid rgba(234,88,12,0.2);
        border-left: 4px solid var(--warning);
        border-radius: 8px;
        padding: 0.875rem 1rem;
    }

    @media (max-width: 992px) {
        .comparison-cards {
            flex-direction: column;
        }

        .pdf-frame-container {
            min-height: 400px;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Breadcrumb -->
    <div class="breadcrumb-industrial">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('resource_person.equivalency_requests.index') }}">Equivalency Requests</a></li>
                <li class="breadcrumb-item"><a href="{{ route('resource_person.equivalency_requests.review', $request) }}">Review Request</a></li>
                <li class="breadcrumb-item active">Syllabus Comparison</li>
            </ol>
        </nav>
    </div>

    <!-- Page Header -->
    <div class="page-header-simple">
        <h2><i class="fas fa-columns"></i>Side-by-Side Syllabus Comparison</h2>
        <a href="{{ route('resource_person.equivalency_requests.review', $request) }}" class="btn btn-outline-secondary btn-industrial">
            <i class="fas fa-arrow-left me-1"></i>Back to Review
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-3" style="border-left: 4px solid var(--success) !important;">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Main Side-by-Side PDF Comparison -->
    <div class="row mb-3">
        <!-- Left Side: Diploma Course Syllabus -->
        <div class="col-lg-6 mb-3 mb-lg-0">
            <div class="comparison-card">
                <div class="comparison-header diploma">
                    <h6><i class="fas fa-graduation-cap"></i>Diploma Course Syllabus</h6>
                    <a href="{{ route('resource_person.external_submission.view_syllabus', $submission) }}"
                       class="btn btn-sm btn-light" target="_blank" title="Open in new tab">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
                <div class="course-info-bar">
                    <div class="course-badge diploma">
                        <span class="code">{{ $submission->course_code }}</span>
                        <span class="name">{{ $submission->course_name }}</span>
                        <span class="credits">{{ number_format($submission->credit_hours, 1) }} CR</span>
                    </div>
                    <div class="mt-2">
                        <small class="text-muted"><i class="fas fa-university me-1"></i>{{ $submission->institution_name }}</small>
                    </div>
                </div>
                <div class="pdf-frame-container">
                    @if($submission->syllabus_file_path)
                        <iframe
                            src="{{ route('resource_person.external_submission.view_syllabus', $submission) }}"
                            title="Diploma Course Syllabus">
                        </iframe>
                    @else
                        <div class="pdf-placeholder">
                            <div>
                                <i class="fas fa-file-pdf"></i>
                                <h5>No Syllabus Available</h5>
                                <p>No syllabus file has been uploaded for this course.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Side: Degree Course Syllabus -->
        <div class="col-lg-6">
            <div class="comparison-card">
                <div class="comparison-header degree">
                    <h6><i class="fas fa-university"></i>UiTM Degree Course Syllabus</h6>
                    <a href="#" id="openDegreeNewTab" class="btn btn-sm btn-light d-none" target="_blank" title="Open in new tab">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
                <div class="course-info-bar">
                    <!-- Student's Requested Course Info -->
                    <div class="course-badge degree">
                        <span class="code">{{ $request->suggested_degree_course_code }}</span>
                        <span class="name">{{ $request->suggested_degree_course_name }}</span>
                        <span class="badge" style="background: var(--info); color: white; font-size: 0.7rem;">Requested by Student</span>
                    </div>
                    <div class="mt-2">
                        <small class="text-muted">
                            <i class="fas fa-graduation-cap me-1"></i>Program:
                            <span class="font-mono fw-bold">{{ $request->current_program_code }}</span> - {{ $request->current_program_name }}
                        </small>
                    </div>

                    @if($recommendations->isNotEmpty())
                        <div class="course-selector">
                            <label class="form-label">Select degree course to compare:</label>
                            <select class="form-select form-select-sm" id="degreeCourseSelect">
                                <option value="">-- Select a degree course --</option>
                                @foreach($recommendations as $index => $rec)
                                    @php
                                        $hasSyllabus = $rec['has_syllabus'] ?? false;
                                        $similarity = $rec['similarity'];
                                        $isSuggested = $rec['is_suggested'] ?? false;
                                        $pdfUrl = $hasSyllabus && $rec['syllabus'] ? route('resource_person.syllabi.view_pdf', $rec['syllabus']) : '';
                                    @endphp
                                    <option value="{{ $pdfUrl }}"
                                            data-code="{{ $rec['course_code'] }}"
                                            data-name="{{ $rec['course_name'] }}"
                                            data-credits="{{ $rec['credit_hours'] }}"
                                            data-similarity="{{ $similarity }}"
                                            data-has-syllabus="{{ $hasSyllabus ? '1' : '0' }}"
                                            data-source="{{ $rec['source'] ?? 'unknown' }}"
                                            {{ $isSuggested ? 'selected' : '' }}>
                                        {{ $rec['course_code'] }} - {{ $rec['course_name'] }} ({{ $similarity }}% match){{ $isSuggested ? ' ★' : '' }}{{ !$hasSyllabus ? ' [No PDF]' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div id="degreeCourseMeta" class="degree-course-meta d-none">
                            <small class="text-muted">Viewing:</small>
                            <span class="badge" style="background: var(--success); color: white;" id="degreeCourseCode"></span>
                            <span class="fw-bold" id="degreeCourseName"></span>
                            <span class="badge bg-secondary font-mono" id="degreeCourseCredits"></span>
                            <span class="badge" style="background: var(--info); color: white;" id="degreeSimilarity"></span>
                            <span class="no-syllabus-badge d-none" id="noSyllabusBadge"><i class="fas fa-exclamation-triangle me-1"></i>No PDF</span>
                        </div>
                    @else
                        <div class="alert-warning-industrial mt-3">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>No degree courses found.</strong><br>
                            <small>No equivalencies exist for <span class="font-mono">{{ $request->current_program_code }}</span> yet. Upload a syllabus for <strong>{{ $request->suggested_degree_course_code }}</strong> to begin.</small>
                        </div>
                    @endif
                </div>
                <div class="pdf-frame-container">
                    @if($recommendations->isNotEmpty())
                        <div id="degreePdfPlaceholder" class="pdf-placeholder">
                            <div>
                                <i class="fas fa-hand-pointer"></i>
                                <h5>Select a Degree Course Above</h5>
                                <p>The syllabus PDF will appear here</p>
                            </div>
                        </div>
                        <iframe
                            id="degreePdfFrame"
                            src=""
                            style="display: none;"
                            title="Degree Course Syllabus">
                        </iframe>
                    @else
                        <div class="pdf-placeholder">
                            <div>
                                <i class="fas fa-upload"></i>
                                <h5>No Syllabi Available</h5>
                                <p class="text-muted mb-2">Student requested equivalency for:</p>
                                <p class="mb-3">
                                    <span class="badge" style="background: var(--success); color: white; font-size: 0.9rem;">{{ $request->suggested_degree_course_code }}</span><br>
                                    <span class="mt-2 d-inline-block">{{ $request->suggested_degree_course_name }}</span>
                                </p>
                                <a href="{{ route('resource_person.syllabi.create', [
                                    'course_code' => $request->suggested_degree_course_code,
                                    'course_name' => $request->suggested_degree_course_name,
                                    'program_code' => $request->current_program_code,
                                    'return_to' => route('resource_person.equivalency_requests.compare', $request)
                                ]) }}" class="btn btn-success btn-industrial">
                                    <i class="fas fa-plus me-1"></i>Upload Degree Syllabus
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recommended Courses Quick Reference -->
    @if($recommendations->isNotEmpty())
    @php
        $withSyllabi = $recommendations->filter(fn($r) => $r['has_syllabus'] ?? false);
        $withoutSyllabi = $recommendations->filter(fn($r) => !($r['has_syllabus'] ?? false));
    @endphp
    <div class="recommendations-card">
        <div class="recommendations-header"
             data-bs-toggle="collapse" data-bs-target="#recommendationsCollapse">
            <span>
                <i class="fas fa-list-ol me-2"></i>Degree Courses Similar to <strong class="font-mono">{{ $request->suggested_degree_course_code }}</strong>
                <small class="ms-2">({{ $withSyllabi->count() }} with PDF, {{ $withoutSyllabi->count() }} without)</small>
            </span>
            <i class="fas fa-chevron-up" id="collapseIcon"></i>
        </div>
        <div class="collapse show" id="recommendationsCollapse">
            <div class="table-responsive">
                <table class="table recommendations-table">
                    <thead>
                        <tr>
                            <th class="px-3">Similarity</th>
                            <th>Course Code</th>
                            <th>Course Name</th>
                            <th class="text-center">Credits</th>
                            <th class="text-center">Syllabus</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recommendations as $rec)
                            @php
                                $hasSyllabus = $rec['has_syllabus'] ?? false;
                                $similarity = $rec['similarity'];
                                $isSuggested = $rec['is_suggested'] ?? false;
                                $source = $rec['source'] ?? 'unknown';
                                $pdfUrl = $hasSyllabus && $rec['syllabus'] ? route('resource_person.syllabi.view_pdf', $rec['syllabus']) : '';
                                $badgeClass = match(true) {
                                    $similarity >= 80 => 'high',
                                    $similarity >= 60 => 'medium',
                                    $similarity >= 40 => 'low',
                                    default => 'very-low'
                                };
                            @endphp
                            <tr class="{{ $isSuggested ? 'table-warning' : '' }}">
                                <td class="px-3">
                                    <span class="similarity-badge {{ $badgeClass }}">{{ $similarity }}%</span>
                                </td>
                                <td>
                                    <span class="badge font-mono" style="background: var(--uitm-blue);">{{ $rec['course_code'] }}</span>
                                    @if($isSuggested)
                                        <i class="fas fa-star text-warning ms-1" title="Student's suggested course"></i>
                                    @endif
                                </td>
                                <td>
                                    {{ $rec['course_name'] }}
                                    @if($source === 'suggested')
                                        <small class="text-muted">(Student suggested)</small>
                                    @endif
                                </td>
                                <td class="text-center font-mono">{{ $rec['credit_hours'] }}</td>
                                <td class="text-center">
                                    @if($hasSyllabus)
                                        <span class="syllabus-badge available"><i class="fas fa-file-pdf me-1"></i>Available</span>
                                    @else
                                        <span class="syllabus-badge missing"><i class="fas fa-exclamation-triangle me-1"></i>Not Uploaded</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex gap-1 justify-content-center">
                                        @if($hasSyllabus)
                                            <button type="button" class="btn btn-outline-success action-btn compare-btn"
                                                    data-url="{{ $pdfUrl }}"
                                                    data-code="{{ $rec['course_code'] }}"
                                                    data-name="{{ $rec['course_name'] }}"
                                                    data-credits="{{ $rec['credit_hours'] }}"
                                                    data-similarity="{{ $similarity }}"
                                                    data-has-syllabus="1"
                                                    title="View PDF in comparison panel">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        @else
                                            <a href="{{ route('resource_person.syllabi.create', [
                                                'course_code' => $rec['course_code'],
                                                'course_name' => $rec['course_name'],
                                                'program_code' => $request->current_program_code,
                                                'credit_hours' => $rec['credit_hours'],
                                                'return_to' => route('resource_person.equivalency_requests.compare', $request)
                                            ]) }}" class="btn btn-outline-warning action-btn"
                                               title="Upload syllabus PDF">
                                                <i class="fas fa-upload"></i>
                                            </a>
                                        @endif
                                        <button type="button" class="btn btn-outline-primary action-btn select-course-btn"
                                                data-course-code="{{ $rec['course_code'] }}"
                                                data-similarity="{{ $similarity }}"
                                                title="Select for decision">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Decision Form -->
    <div class="decision-card">
        <div class="decision-header">
            <h5><i class="fas fa-clipboard-check"></i>Make Your Decision</h5>
        </div>
        <div class="decision-body">
            <form action="{{ route('resource_person.equivalency_requests.process', $request) }}" method="POST" id="comparisonDecisionForm">
                @csrf

                <div class="row mb-4">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <label class="form-label fw-bold">Approved Degree Course Code <span class="text-danger">*</span></label>
                        <input type="text" name="approved_degree_course_code" id="approvedCourseCode"
                               class="form-control font-mono" value="{{ old('approved_degree_course_code', $request->suggested_degree_course_code) }}" required>
                    </div>
                    <div class="col-md-4 mb-3 mb-md-0">
                        <label class="form-label fw-bold">Match Percentage <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="match_percentage" id="matchPercentage"
                                   class="form-control font-mono" value="{{ old('match_percentage', 85) }}" min="0" max="100" step="1" required>
                            <span class="input-group-text">%</span>
                        </div>
                        <small class="text-muted">Your assessment (>80% = eligible for exemption)</small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Decision <span class="text-danger">*</span></label>
                        <div class="btn-group btn-group-decision w-100" role="group">
                            <input type="radio" class="btn-check" name="decision" id="decision_approve" value="approved" required>
                            <label class="btn btn-outline-success" for="decision_approve">
                                <i class="fas fa-check me-1"></i>Equivalent
                            </label>
                            <input type="radio" class="btn-check" name="decision" id="decision_reject" value="rejected" required>
                            <label class="btn btn-outline-danger" for="decision_reject">
                                <i class="fas fa-times me-1"></i>Not Equivalent
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <label class="form-label fw-bold">Reviewer Notes <span class="text-muted fw-normal">(Optional)</span></label>
                        <textarea name="reviewer_notes" class="form-control" rows="2" placeholder="Any additional notes about your decision...">{{ old('reviewer_notes') }}</textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('resource_person.equivalency_requests.review', $request) }}" class="btn btn-outline-secondary btn-industrial">
                        <i class="fas fa-arrow-left me-1"></i>Back
                    </a>
                    <button type="submit" class="btn btn-primary-industrial" id="submitDecisionBtn" disabled>
                        <i class="fas fa-paper-plane me-1"></i>Submit Decision
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tooltips
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el));

    const degreeCourseSelect = document.getElementById('degreeCourseSelect');
    const degreePdfFrame = document.getElementById('degreePdfFrame');
    const degreePdfPlaceholder = document.getElementById('degreePdfPlaceholder');
    const degreeCourseMeta = document.getElementById('degreeCourseMeta');
    const degreeCourseCode = document.getElementById('degreeCourseCode');
    const degreeCourseName = document.getElementById('degreeCourseName');
    const degreeCourseCredits = document.getElementById('degreeCourseCredits');
    const degreeSimilarity = document.getElementById('degreeSimilarity');
    const noSyllabusBadge = document.getElementById('noSyllabusBadge');
    const openDegreeNewTab = document.getElementById('openDegreeNewTab');
    const approvedCourseCode = document.getElementById('approvedCourseCode');
    const matchPercentage = document.getElementById('matchPercentage');
    const submitDecisionBtn = document.getElementById('submitDecisionBtn');
    const approveRadio = document.getElementById('decision_approve');
    const rejectRadio = document.getElementById('decision_reject');

    const uploadUrlBase = "{{ route('resource_person.syllabi.create') }}";
    const returnUrl = "{{ route('resource_person.equivalency_requests.compare', $request) }}";
    const programCode = "{{ $request->current_program_code }}";

    function showUploadPrompt(code, name, credits) {
        if (degreePdfFrame) degreePdfFrame.style.display = 'none';
        if (degreePdfPlaceholder) {
            const uploadUrl = `${uploadUrlBase}?course_code=${encodeURIComponent(code)}&course_name=${encodeURIComponent(name)}&program_code=${encodeURIComponent(programCode)}&credit_hours=${credits}&return_to=${encodeURIComponent(returnUrl)}`;
            degreePdfPlaceholder.innerHTML = `
                <div>
                    <i class="fas fa-file-upload" style="font-size: 4rem; color: #f59e0b; margin-bottom: 1rem;"></i>
                    <h5>No Syllabus Uploaded</h5>
                    <p class="mb-2">
                        <span class="badge" style="background: #1e3a8a; color: white; font-size: 0.9rem;">${code}</span><br>
                        <span class="mt-2 d-inline-block">${name}</span>
                    </p>
                    <p class="text-muted mb-3">This course exists in the equivalency database but has no uploaded syllabus PDF.</p>
                    <a href="${uploadUrl}" class="btn btn-warning">
                        <i class="fas fa-upload me-1"></i>Upload Syllabus Now
                    </a>
                </div>
            `;
            degreePdfPlaceholder.style.display = 'flex';
        }

        if (degreeCourseMeta) {
            degreeCourseMeta.classList.remove('d-none');
            degreeCourseCode.textContent = code;
            degreeCourseName.textContent = name;
            degreeCourseCredits.textContent = credits + ' CR';
            degreeSimilarity.textContent = '';
            if (noSyllabusBadge) noSyllabusBadge.classList.remove('d-none');
        }

        if (openDegreeNewTab) openDegreeNewTab.classList.add('d-none');
    }

    function loadDegreePdf(url, code, name, credits, similarity, hasSyllabus) {
        if (noSyllabusBadge) noSyllabusBadge.classList.add('d-none');

        if (!hasSyllabus || !url) {
            showUploadPrompt(code, name, credits);
            if (degreeCourseMeta && similarity) {
                degreeSimilarity.textContent = similarity + '% match';
            }
            return;
        }

        if (degreePdfFrame && url) {
            degreePdfFrame.src = url;
            degreePdfFrame.style.display = 'block';
            if (degreePdfPlaceholder) degreePdfPlaceholder.style.display = 'none';

            if (degreeCourseMeta) {
                degreeCourseMeta.classList.remove('d-none');
                degreeCourseCode.textContent = code;
                degreeCourseName.textContent = name;
                degreeCourseCredits.textContent = credits + ' CR';
                degreeSimilarity.textContent = similarity + '% match';
            }

            if (openDegreeNewTab) {
                openDegreeNewTab.href = url;
                openDegreeNewTab.classList.remove('d-none');
            }
        }
    }

    if (degreeCourseSelect) {
        degreeCourseSelect.addEventListener('change', function() {
            const opt = this.options[this.selectedIndex];
            if (opt && opt.dataset.code) {
                const hasSyllabus = opt.dataset.hasSyllabus === '1';
                loadDegreePdf(
                    this.value,
                    opt.dataset.code,
                    opt.dataset.name,
                    opt.dataset.credits,
                    opt.dataset.similarity,
                    hasSyllabus
                );
            } else {
                if (degreePdfFrame) degreePdfFrame.style.display = 'none';
                if (degreePdfPlaceholder) {
                    degreePdfPlaceholder.innerHTML = `
                        <div>
                            <i class="fas fa-hand-pointer" style="font-size: 4rem; color: #cbd5e1; margin-bottom: 1rem;"></i>
                            <h5>Select a Degree Course Above</h5>
                            <p>The syllabus PDF will appear here</p>
                        </div>
                    `;
                    degreePdfPlaceholder.style.display = 'flex';
                }
                if (degreeCourseMeta) degreeCourseMeta.classList.add('d-none');
                if (openDegreeNewTab) openDegreeNewTab.classList.add('d-none');
            }
        });

        if (degreeCourseSelect.selectedIndex > 0) {
            const opt = degreeCourseSelect.options[degreeCourseSelect.selectedIndex];
            const hasSyllabus = opt.dataset.hasSyllabus === '1';
            loadDegreePdf(
                degreeCourseSelect.value,
                opt.dataset.code,
                opt.dataset.name,
                opt.dataset.credits,
                opt.dataset.similarity,
                hasSyllabus
            );
        }
    }

    document.querySelectorAll('.compare-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const url = this.dataset.url;
            const code = this.dataset.code;
            const name = this.dataset.name;
            const credits = this.dataset.credits;
            const similarity = this.dataset.similarity;
            const hasSyllabus = this.dataset.hasSyllabus === '1';

            loadDegreePdf(url, code, name, credits, similarity, hasSyllabus);

            if (degreeCourseSelect) {
                for (let opt of degreeCourseSelect.options) {
                    if (opt.dataset.code === code) {
                        degreeCourseSelect.value = opt.value;
                        break;
                    }
                }
            }

            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });

    document.querySelectorAll('.select-course-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const courseCode = this.dataset.courseCode;
            const similarity = this.dataset.similarity;

            approvedCourseCode.value = courseCode;
            matchPercentage.value = similarity;

            document.querySelectorAll('.select-course-btn').forEach(b => {
                b.classList.remove('btn-primary');
                b.classList.add('btn-outline-primary');
            });
            this.classList.remove('btn-outline-primary');
            this.classList.add('btn-primary');

            document.getElementById('comparisonDecisionForm').scrollIntoView({ behavior: 'smooth', block: 'center' });
        });
    });

    function enableSubmitButton() {
        if (approveRadio.checked || rejectRadio.checked) {
            submitDecisionBtn.disabled = false;
        }
    }

    approveRadio.addEventListener('change', enableSubmitButton);
    rejectRadio.addEventListener('change', enableSubmitButton);

    if (approveRadio.checked || rejectRadio.checked) {
        enableSubmitButton();
    }

    const recommendationsCollapse = document.getElementById('recommendationsCollapse');
    const collapseIcon = document.getElementById('collapseIcon');
    if (recommendationsCollapse && collapseIcon) {
        recommendationsCollapse.addEventListener('show.bs.collapse', function() {
            collapseIcon.classList.remove('fa-chevron-down');
            collapseIcon.classList.add('fa-chevron-up');
        });
        recommendationsCollapse.addEventListener('hide.bs.collapse', function() {
            collapseIcon.classList.remove('fa-chevron-up');
            collapseIcon.classList.add('fa-chevron-down');
        });
    }
});
</script>
@endpush
