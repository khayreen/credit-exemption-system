@extends('layouts.app')

@push('styles')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    /* Page Header */
    .page-header {
        background: var(--primary-gradient);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 60%;
        height: 200%;
        background: rgba(255,255,255,0.1);
        transform: rotate(25deg);
    }

    .student-avatar {
        width: 70px;
        height: 70px;
        background: rgba(255,255,255,0.2);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        font-weight: 700;
        color: white;
        border: 2px solid rgba(255,255,255,0.3);
    }

    .student-info-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.2rem;
        font-size: 0.85rem;
        opacity: 0.9;
    }

    .student-info-item i { width: 14px; opacity: 0.7; }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.4rem 0.9rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.8rem;
        background: rgba(255,193,7,0.2);
        color: #ffc107;
        border: 1px solid rgba(255,193,7,0.3);
    }

    /* Stats Row */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.25rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        border: 1px solid rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .stat-card .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        color: white;
        flex-shrink: 0;
    }

    .stat-card .stat-icon.success { background: var(--success-gradient); }
    .stat-card .stat-icon.warning { background: var(--warning-gradient); }
    .stat-card .stat-icon.info { background: var(--info-gradient); }
    .stat-card .stat-icon.primary { background: var(--primary-gradient); }

    .stat-card .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a1a2e;
        line-height: 1;
    }

    .stat-card .stat-label {
        color: #6b7280;
        font-size: 0.8rem;
        font-weight: 500;
        margin-top: 0.15rem;
    }

    /* Progress Bar */
    .review-progress {
        background: white;
        border-radius: 12px;
        padding: 1rem 1.25rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        border: 1px solid rgba(0,0,0,0.05);
        margin-bottom: 1.5rem;
    }

    .progress-bar-wrapper {
        background: #e9ecef;
        border-radius: 8px;
        height: 10px;
        overflow: hidden;
        margin-top: 0.5rem;
    }

    .progress-bar-fill {
        height: 100%;
        border-radius: 8px;
        background: var(--success-gradient);
        transition: width 0.5s ease;
    }

    /* Section Card */
    .section-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        border: 1px solid rgba(0,0,0,0.05);
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .section-header {
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #f0f0f0;
    }

    .section-header.success {
        background: linear-gradient(135deg, rgba(17,153,142,0.06) 0%, rgba(56,239,125,0.06) 100%);
        border-left: 4px solid #11998e;
    }

    .section-header.warning {
        background: linear-gradient(135deg, rgba(255,193,7,0.06) 0%, rgba(255,152,0,0.06) 100%);
        border-left: 4px solid #ffc107;
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        font-size: 1rem;
        font-weight: 600;
        color: #1a1a2e;
        margin: 0;
    }

    .section-title .icon-wrapper {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
    }

    .section-title .icon-wrapper.success {
        background: linear-gradient(135deg, rgba(17,153,142,0.15) 0%, rgba(56,239,125,0.15) 100%);
        color: #11998e;
    }

    .section-title .icon-wrapper.warning {
        background: linear-gradient(135deg, rgba(255,193,7,0.15) 0%, rgba(255,152,0,0.15) 100%);
        color: #ff9800;
    }

    .section-subtitle {
        color: #6b7280;
        font-size: 0.8rem;
        margin: 0.2rem 0 0 0;
    }

    /* Course Table */
    .course-table {
        width: 100%;
        border-collapse: collapse;
    }

    .course-table thead th {
        background: #f8f9fa;
        padding: 0.75rem 1rem;
        font-size: 0.75rem;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-align: left;
        border-bottom: 1px solid #e9ecef;
        white-space: nowrap;
    }

    .course-table tbody tr {
        border-bottom: 1px solid #f5f5f5;
        transition: background 0.15s ease;
    }

    .course-table tbody tr:last-child {
        border-bottom: none;
    }

    .course-table tbody tr:hover {
        background: #fafafa;
    }

    .course-table tbody td {
        padding: 0.875rem 1rem;
        vertical-align: middle;
        font-size: 0.875rem;
    }

    /* Column Widths */
    .col-code { width: 130px; }
    .col-name { min-width: 200px; }
    .col-grade { width: 80px; text-align: center; }
    .col-equivalent { width: 100px; text-align: center; }
    .col-match { width: 90px; text-align: center; }
    .col-credits { width: 70px; text-align: center; }
    .col-status { width: 120px; }
    .col-actions { width: 190px; text-align: right; white-space: nowrap; }

    /* Course Code Badge */
    .course-code-badge {
        background: var(--primary-gradient);
        color: white;
        padding: 0.4rem 0.6rem;
        border-radius: 6px;
        font-weight: 700;
        font-size: 0.75rem;
        display: inline-block;
        text-align: center;
        min-width: 90px;
    }

    .course-name {
        font-weight: 500;
        color: #1a1a2e;
        font-size: 0.85rem;
        line-height: 1.4;
    }

    /* Grade Badge */
    .grade-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.3rem 0.6rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8rem;
        min-width: 40px;
    }

    .grade-badge.good {
        background: linear-gradient(135deg, rgba(17,153,142,0.12) 0%, rgba(56,239,125,0.12) 100%);
        color: #0d7a72;
    }

    .grade-badge.low {
        background: linear-gradient(135deg, rgba(220,53,69,0.12) 0%, rgba(255,107,107,0.12) 100%);
        color: #c53030;
    }

    /* Equivalent Badge */
    .equivalent-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.3rem 0.6rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.75rem;
        background: linear-gradient(135deg, rgba(102,126,234,0.12) 0%, rgba(118,75,162,0.12) 100%);
        color: #5a51c5;
        min-width: 70px;
    }

    /* Match Badge */
    .match-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.3rem 0.6rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .match-badge.high {
        background: linear-gradient(135deg, rgba(79,172,254,0.12) 0%, rgba(0,242,254,0.12) 100%);
        color: #0369a1;
    }

    .match-badge.low {
        background: linear-gradient(135deg, rgba(255,193,7,0.12) 0%, rgba(255,152,0,0.12) 100%);
        color: #b45309;
    }

    /* Credits Badge */
    .credits-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.3rem 0.5rem;
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.8rem;
        background: #f3f4f6;
        color: #6b7280;
    }

    /* Status Reason Badge */
    .status-reason {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.3rem 0.6rem;
        border-radius: 6px;
        font-size: 0.7rem;
        font-weight: 500;
    }

    .status-reason.not-found {
        background: #e2e3e5;
        color: #495057;
    }

    .status-reason.low-grade {
        background: #f8d7da;
        color: #721c24;
    }

    .status-reason.low-match {
        background: #fff3cd;
        color: #856404;
    }

    /* Action Buttons */
    .action-buttons {
        display: inline-flex;
        gap: 0.5rem;
        align-items: center;
        flex-wrap: nowrap;
    }

    .btn-action {
        padding: 0.4rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.75rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.3rem;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
        white-space: nowrap;
        min-width: 75px;
    }

    .btn-action.approve {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
    }

    .btn-action.approve:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 10px rgba(17,153,142,0.35);
    }

    .btn-action.reject {
        background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);
        color: white;
    }

    .btn-action.reject:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 10px rgba(255,65,108,0.35);
    }

    .btn-action.undo {
        background: #f3f4f6;
        color: #6b7280;
        border: 1px solid #e5e7eb;
        min-width: 36px;
        padding: 0.4rem;
    }

    .btn-action.undo:hover {
        background: #e5e7eb;
    }

    .btn-bulk {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-bulk.approve-all {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
    }

    .btn-bulk.approve-all:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 12px rgba(17,153,142,0.35);
    }

    .btn-bulk.reject-all {
        background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);
        color: white;
    }

    .btn-bulk.reject-all:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 12px rgba(255,65,108,0.35);
    }

    /* Decision Badge */
    .decision-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.4rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.75rem;
    }

    .decision-badge.approved {
        background: linear-gradient(135deg, rgba(17,153,142,0.15) 0%, rgba(56,239,125,0.15) 100%);
        color: #0d7a72;
    }

    .decision-badge.rejected {
        background: linear-gradient(135deg, rgba(255,65,108,0.15) 0%, rgba(255,75,43,0.15) 100%);
        color: #dc2626;
    }

    /* Transcript Section */
    .transcript-section {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        border: 1px solid rgba(0,0,0,0.05);
        margin-bottom: 1.5rem;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .btn-view-transcript {
        background: var(--primary-gradient);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        text-decoration: none;
        transition: all 0.2s ease;
        border: none;
    }

    .btn-view-transcript:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 12px rgba(102,126,234,0.35);
        color: white;
    }

    /* Back Button */
    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        color: #6b7280;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.85rem;
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        transition: all 0.2s ease;
        background: white;
        border: 1px solid #e5e7eb;
        margin-bottom: 1.25rem;
    }

    .btn-back:hover {
        color: #1a1a2e;
        background: #f3f4f6;
    }

    /* Toast */
    .toast-notification {
        padding: 0.875rem 1.25rem;
        border-radius: 10px;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.6rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        animation: slideIn 0.3s ease;
        font-size: 0.875rem;
    }

    .toast-notification.success {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
    }

    .toast-notification.error {
        background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);
        color: white;
    }

    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: #6b7280;
    }

    .empty-state i {
        font-size: 2.5rem;
        margin-bottom: 0.75rem;
        opacity: 0.4;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .course-table { display: block; overflow-x: auto; }
    }

    @media (max-width: 768px) {
        .stats-row { grid-template-columns: repeat(2, 1fr); }
        .page-header { padding: 1.5rem; }
        .student-avatar { width: 55px; height: 55px; font-size: 1.4rem; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Back Button -->
    <a href="{{ route('academic_advisor.dashboard') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
    </a>

    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-3">
                    <div class="student-avatar">{{ strtoupper(substr($application->student_name, 0, 2)) }}</div>
                    <div>
                        <h4 class="mb-1 fw-bold">{{ $application->student_name }}</h4>
                        <div class="student-info-item">
                            <i class="fas fa-id-card"></i>
                            <span>{{ $application->matric_no }}</span>
                        </div>
                        <div class="student-info-item">
                            <i class="fas fa-university"></i>
                            <span>{{ $application->previous_institution }}</span>
                        </div>
                        <div class="student-info-item">
                            <i class="fas fa-graduation-cap"></i>
                            <span>{{ $application->previous_program }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <div class="status-badge">
                    <i class="fas fa-clock"></i> {{ $application->status }}
                </div>
                <div class="mt-2">
                    <small class="opacity-75">
                        <i class="fas fa-calendar-alt me-1"></i>
                        {{ $application->created_at->format('M d, Y') }}
                    </small>
                </div>
            </div>
        </div>
    </div>

    @if($application->applicationSubjects->isNotEmpty())
    <!-- Stats Row -->
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon success"><i class="fas fa-check-circle"></i></div>
            <div>
                <div class="stat-value">{{ $exemptedSubjects->count() }}</div>
                <div class="stat-label">Pre-Qualified</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon warning"><i class="fas fa-exclamation-triangle"></i></div>
            <div>
                <div class="stat-value">{{ $nonExemptedSubjects->count() }}</div>
                <div class="stat-label">Needs Review</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon info"><i class="fas fa-code-branch"></i></div>
            <div>
                <div class="stat-value">{{ $application->current_program_code ?? 'N/A' }}</div>
                <div class="stat-label">Program</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon primary"><i class="fas fa-book"></i></div>
            <div>
                <div class="stat-value">{{ $subjects->count() }}</div>
                <div class="stat-label">Total Courses</div>
            </div>
        </div>
    </div>

    <!-- Progress Bar -->
    @php
        $totalSubjects = $subjects->count();
        $decidedSubjects = $subjects->filter(fn($s) => in_array($s['status'], ['Approved', 'Rejected', 'Forward to Coordinator']))->count();
        $progressPercent = $totalSubjects > 0 ? round(($decidedSubjects / $totalSubjects) * 100) : 0;
    @endphp
    <div class="review-progress">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <span class="fw-bold">Review Progress</span>
                <small class="text-muted ms-2">{{ $decidedSubjects }} of {{ $totalSubjects }} courses</small>
            </div>
            <span class="fw-bold text-success">{{ $progressPercent }}%</span>
        </div>
        <div class="progress-bar-wrapper">
            <div class="progress-bar-fill" style="width: {{ $progressPercent }}%"></div>
        </div>
    </div>
    @endif

    <!-- Transcript Section -->
    <div class="transcript-section">
        <div class="d-flex align-items-center gap-2">
            <i class="fas fa-file-pdf text-danger"></i>
            <div>
                <span class="fw-bold">Academic Transcript</span>
                <small class="text-muted d-block">View original document</small>
            </div>
        </div>
        <a href="{{ route('academic_advisor.application.transcript', $application) }}" target="_blank" class="btn-view-transcript">
            <i class="fas fa-external-link-alt"></i> Open PDF
        </a>
    </div>

    <!-- Pre-Qualified Courses -->
    @if($exemptedSubjects->isNotEmpty())
    <div class="section-card">
        <div class="section-header success">
            <div>
                <h5 class="section-title">
                    <span class="icon-wrapper success"><i class="fas fa-check-circle"></i></span>
                    Pre-Qualified Courses
                    <span class="badge bg-success ms-2">{{ $exemptedSubjects->count() }}</span>
                </h5>
                <p class="section-subtitle">Met all criteria: Found in database + Grade C or above + Match >80%</p>
            </div>
            <button type="button" class="btn-bulk approve-all" id="approveAllBtn">
                <i class="fas fa-check-double"></i> Approve All
            </button>
        </div>
        <table class="course-table">
            <thead>
                <tr>
                    <th class="col-code">Course Code</th>
                    <th class="col-name">Course Name</th>
                    <th class="col-grade">Grade</th>
                    <th class="col-equivalent">Equivalent</th>
                    <th class="col-match">Match</th>
                    <th class="col-credits">Credits</th>
                    <th class="col-actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($exemptedSubjects as $subject)
                <tr data-subject-id="{{ $subject['id'] }}">
                    <td><span class="course-code-badge">{{ $subject['course_code'] }}</span></td>
                    <td><span class="course-name">{{ $subject['course_name'] }}</span></td>
                    <td>
                        @if($subject['is_combination'] && $subject['individual_grades'])
                            @php
                                $courseCodes = explode(' & ', $subject['course_code']);
                                $grades = [];
                                foreach($courseCodes as $code) {
                                    if(isset($subject['individual_grades'][$code])) {
                                        $grades[] = $subject['individual_grades'][$code];
                                    }
                                }
                            @endphp
                            @foreach($grades as $grade)
                                <span class="grade-badge good">{{ $grade }}</span>
                            @endforeach
                        @else
                            <span class="grade-badge good">{{ $subject['grade_letter'] }}</span>
                        @endif
                    </td>
                    <td>
                        @if($subject['equivalent_course'])
                            <span class="equivalent-badge">{{ $subject['equivalent_course'] }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($subject['match_percentage'])
                            <span class="match-badge high">{{ $subject['match_percentage'] }}%</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($subject['credit_hour'])
                            <span class="credits-badge">{{ $subject['credit_hour'] }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="action-buttons">
                        @if($subject['status'] === 'Approved')
                            <span class="decision-badge approved"><i class="fas fa-check"></i> Approved</span>
                            <button type="button" class="btn-action undo undo-btn"
                                    data-subject-id="{{ $subject['id'] }}"
                                    data-course-code="{{ $subject['course_code'] }}">
                                <i class="fas fa-undo"></i>
                            </button>
                        @else
                            <button type="button" class="btn-action approve decision-btn"
                                    data-subject-id="{{ $subject['id'] }}"
                                    data-decision="Approved"
                                    data-course-code="{{ $subject['course_code'] }}">
                                <i class="fas fa-check"></i> Approve
                            </button>
                            <button type="button" class="btn-action reject decision-btn"
                                    data-subject-id="{{ $subject['id'] }}"
                                    data-decision="Rejected"
                                    data-course-code="{{ $subject['course_code'] }}">
                                <i class="fas fa-times"></i> Reject
                            </button>
                        @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Manual Review Courses -->
    @if($nonExemptedSubjects->isNotEmpty())
    <div class="section-card">
        <div class="section-header warning">
            <div>
                <h5 class="section-title">
                    <span class="icon-wrapper warning"><i class="fas fa-exclamation-triangle"></i></span>
                    Requires Manual Review
                    <span class="badge bg-warning text-dark ms-2">{{ $nonExemptedSubjects->count() }}</span>
                </h5>
                <p class="section-subtitle">These courses did not meet OCR exemption criteria</p>
            </div>
            <button type="button" class="btn-bulk reject-all" id="rejectAllBtn">
                <i class="fas fa-times-circle"></i> Reject All
            </button>
        </div>
        <table class="course-table">
            <thead>
                <tr>
                    <th class="col-code">Course Code</th>
                    <th class="col-name">Course Name</th>
                    <th class="col-grade">Grade</th>
                    <th class="col-equivalent">Equivalent</th>
                    <th class="col-match">Match</th>
                    <th class="col-status">Status</th>
                    <th class="col-actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($nonExemptedSubjects as $subject)
                <tr data-subject-id="{{ $subject['id'] }}">
                    <td><span class="course-code-badge">{{ $subject['course_code'] }}</span></td>
                    <td><span class="course-name">{{ $subject['course_name'] }}</span></td>
                    <td>
                        @if($subject['is_combination'] && $subject['individual_grades'])
                            @php
                                $courseCodes = explode(' & ', $subject['course_code']);
                                $grades = [];
                                foreach($courseCodes as $code) {
                                    if(isset($subject['individual_grades'][$code])) {
                                        $grades[] = $subject['individual_grades'][$code];
                                    }
                                }
                                $isLowGrade = $subject['status'] == 'not_eligible_grade';
                            @endphp
                            @foreach($grades as $grade)
                                <span class="grade-badge {{ $isLowGrade ? 'low' : 'good' }}">{{ $grade }}</span>
                            @endforeach
                        @else
                            <span class="grade-badge {{ $subject['status'] == 'not_eligible_grade' ? 'low' : 'good' }}">{{ $subject['grade_letter'] }}</span>
                        @endif
                    </td>
                    <td>
                        @if($subject['equivalent_course'])
                            <span class="equivalent-badge">{{ $subject['equivalent_course'] }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($subject['match_percentage'])
                            <span class="match-badge {{ $subject['match_percentage'] > 80 ? 'high' : 'low' }}">{{ $subject['match_percentage'] }}%</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($subject['status'] == 'not_found')
                            <span class="status-reason not-found"><i class="fas fa-search"></i> Not Found</span>
                        @elseif($subject['status'] == 'not_eligible_grade')
                            <span class="status-reason low-grade"><i class="fas fa-arrow-down"></i> Low Grade</span>
                        @elseif($subject['status'] == 'not_eligible_match')
                            <span class="status-reason low-match"><i class="fas fa-percentage"></i> Low Match</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="action-buttons">
                        @if(in_array($subject['status'], ['Approved', 'Rejected', 'Forward to Coordinator']))
                            <span class="decision-badge {{ $subject['status'] === 'Approved' ? 'approved' : 'rejected' }}">
                                <i class="fas fa-{{ $subject['status'] === 'Approved' ? 'check' : 'times' }}"></i>
                                {{ $subject['status'] }}
                            </span>
                            <button type="button" class="btn-action undo undo-btn"
                                    data-subject-id="{{ $subject['id'] }}"
                                    data-course-code="{{ $subject['course_code'] }}">
                                <i class="fas fa-undo"></i>
                            </button>
                        @else
                            <button type="button" class="btn-action approve decision-btn"
                                    data-subject-id="{{ $subject['id'] }}"
                                    data-decision="Approved"
                                    data-course-code="{{ $subject['course_code'] }}">
                                <i class="fas fa-check"></i> Approve
                            </button>
                            <button type="button" class="btn-action reject decision-btn"
                                    data-subject-id="{{ $subject['id'] }}"
                                    data-decision="Rejected"
                                    data-course-code="{{ $subject['course_code'] }}">
                                <i class="fas fa-times"></i> Reject
                            </button>
                        @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Empty State -->
    @if($subjects->isEmpty())
    <div class="section-card">
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <h6>No Courses Found</h6>
            <p class="mb-0">No courses were extracted from the transcript.</p>
        </div>
    </div>
    @endif
</div>

<!-- Toast Container -->
<div id="toast-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const approveAllBtn = document.getElementById('approveAllBtn');
    const rejectAllBtn = document.getElementById('rejectAllBtn');

    // Approve All
    if (approveAllBtn) {
        approveAllBtn.addEventListener('click', function() {
            const section = this.closest('.section-card');
            const pending = section.querySelectorAll('.decision-btn').length / 2;

            if (pending === 0) {
                showToast('error', 'All courses already approved.');
                return;
            }

            if (confirm(`Approve all ${pending} pre-qualified course(s)?`)) {
                this.disabled = true;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

                fetch('{{ route('academic_advisor.application.bulk_approve_prequalified', ['application' => $application->id]) }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        showToast('success', data.message);
                        section.querySelectorAll('tr[data-subject-id]').forEach(row => {
                            const cell = row.querySelector('td:last-child');
                            if (cell.querySelector('.decision-btn')) {
                                const id = row.dataset.subjectId;
                                const code = cell.querySelector('.decision-btn').dataset.courseCode;
                                cell.innerHTML = `<div class="action-buttons"><span class="decision-badge approved"><i class="fas fa-check"></i> Approved</span>
                                    <button type="button" class="btn-action undo undo-btn" data-subject-id="${id}" data-course-code="${code}"><i class="fas fa-undo"></i></button></div>`;
                            }
                        });
                        this.style.display = 'none';
                        updateProgress();
                    } else throw new Error(data.message);
                })
                .catch(e => {
                    showToast('error', e.message || 'An error occurred.');
                    this.disabled = false;
                    this.innerHTML = '<i class="fas fa-check-double"></i> Approve All';
                });
            }
        });
    }

    // Reject All
    if (rejectAllBtn) {
        rejectAllBtn.addEventListener('click', function() {
            const section = this.closest('.section-card');
            const pending = section.querySelectorAll('.decision-btn').length / 2;

            if (pending === 0) {
                showToast('error', 'All courses already decided.');
                return;
            }

            if (confirm(`Reject all ${pending} course(s)?`)) {
                this.disabled = true;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

                fetch('{{ route('academic_advisor.application.bulk_reject_manual_review', ['application' => $application->id]) }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        showToast('success', data.message);
                        section.querySelectorAll('tr[data-subject-id]').forEach(row => {
                            const cell = row.querySelector('td:last-child');
                            if (cell.querySelector('.decision-btn')) {
                                const id = row.dataset.subjectId;
                                const code = cell.querySelector('.decision-btn').dataset.courseCode;
                                cell.innerHTML = `<div class="action-buttons"><span class="decision-badge rejected"><i class="fas fa-times"></i> Rejected</span>
                                    <button type="button" class="btn-action undo undo-btn" data-subject-id="${id}" data-course-code="${code}"><i class="fas fa-undo"></i></button></div>`;
                            }
                        });
                        this.style.display = 'none';
                        updateProgress();
                    } else throw new Error(data.message);
                })
                .catch(e => {
                    showToast('error', e.message || 'An error occurred.');
                    this.disabled = false;
                    this.innerHTML = '<i class="fas fa-times-circle"></i> Reject All';
                });
            }
        });
    }

    // Individual decisions
    document.addEventListener('click', function(e) {
        if (e.target.closest('.decision-btn')) {
            const btn = e.target.closest('.decision-btn');
            const { subjectId, decision, courseCode } = btn.dataset;
            const section = btn.closest('.section-card');
            const isPreQualified = section.querySelector('.section-header.success');

            if (decision === 'Rejected' && isPreQualified && !confirm(`Reject pre-qualified course ${courseCode}?`)) return;

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            fetch(`{{ route('academic_advisor.application.subject.decision', ['application' => $application->id, 'subject' => '__ID__']) }}`.replace('__ID__', subjectId), {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: JSON.stringify({ decision })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showToast('success', data.message);
                    const cell = btn.closest('td');
                    const badge = decision === 'Approved' ? 'approved' : 'rejected';
                    const icon = decision === 'Approved' ? 'check' : 'times';
                    cell.innerHTML = `<div class="action-buttons"><span class="decision-badge ${badge}"><i class="fas fa-${icon}"></i> ${decision}</span>
                        <button type="button" class="btn-action undo undo-btn" data-subject-id="${subjectId}" data-course-code="${courseCode}"><i class="fas fa-undo"></i></button></div>`;
                    updateProgress();
                } else throw new Error(data.message);
            })
            .catch(e => {
                showToast('error', 'An error occurred.');
                btn.disabled = false;
                btn.innerHTML = decision === 'Approved' ? '<i class="fas fa-check"></i> Approve' : '<i class="fas fa-times"></i> Reject';
            });
        }
    });

    // Undo
    document.addEventListener('click', function(e) {
        if (e.target.closest('.undo-btn')) {
            const btn = e.target.closest('.undo-btn');
            const { subjectId, courseCode } = btn.dataset;

            if (!confirm(`Undo decision for ${courseCode}?`)) return;

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            fetch(`{{ route('academic_advisor.application.subject.decision', ['application' => $application->id, 'subject' => '__ID__']) }}`.replace('__ID__', subjectId), {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: JSON.stringify({ decision: 'Undo' })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showToast('success', data.message);
                    const cell = btn.closest('td');
                    cell.innerHTML = `<div class="action-buttons"><button type="button" class="btn-action approve decision-btn" data-subject-id="${subjectId}" data-decision="Approved" data-course-code="${courseCode}"><i class="fas fa-check"></i> Approve</button>
                        <button type="button" class="btn-action reject decision-btn" data-subject-id="${subjectId}" data-decision="Rejected" data-course-code="${courseCode}"><i class="fas fa-times"></i> Reject</button></div>`;
                    if (approveAllBtn) approveAllBtn.style.display = '';
                    if (rejectAllBtn) rejectAllBtn.style.display = '';
                    updateProgress();
                } else throw new Error(data.message);
            })
            .catch(e => {
                showToast('error', 'An error occurred.');
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-undo"></i>';
            });
        }
    });

    function updateProgress() {
        const total = document.querySelectorAll('tr[data-subject-id]').length;
        const decided = document.querySelectorAll('.decision-badge').length;
        const pct = total > 0 ? Math.round((decided / total) * 100) : 0;

        const fill = document.querySelector('.progress-bar-fill');
        const text = document.querySelector('.review-progress .text-success');
        const sub = document.querySelector('.review-progress small');

        if (fill) fill.style.width = pct + '%';
        if (text) text.textContent = pct + '%';
        if (sub) sub.textContent = decided + ' of ' + total + ' courses';
    }

    function showToast(type, msg) {
        const container = document.getElementById('toast-container');
        const id = 'toast-' + Date.now();
        const toast = document.createElement('div');
        toast.id = id;
        toast.className = 'toast-notification ' + type;
        toast.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i><span>${msg}</span>`;
        container.appendChild(toast);
        setTimeout(() => {
            const el = document.getElementById(id);
            if (el) { el.style.opacity = '0'; el.style.transform = 'translateX(100%)'; setTimeout(() => el.remove(), 300); }
        }, 4000);
    }
});
</script>
@endpush
