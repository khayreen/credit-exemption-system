@extends('layouts.app')

@push('styles')
<!-- IBM Plex Sans Typography -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600;700&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
/* ========================================
   INDUSTRIAL INSTITUTIONAL DESIGN SYSTEM
   UiTM Credit Exemption - Application Status
   ======================================== */

:root {
    --uitm-primary: #1e3a8a;
    --uitm-primary-dark: #1e293b;
    --uitm-primary-light: #3b82f6;
    --uitm-red: #dc2626;
    --uitm-red-light: #ef4444;
    --uitm-amber: #f59e0b;
    --uitm-amber-light: #fbbf24;
    --uitm-green: #10b981;
    --uitm-green-light: #34d399;
    --neutral-900: #171717;
    --neutral-800: #262626;
    --neutral-700: #404040;
    --neutral-600: #525252;
    --neutral-500: #737373;
    --neutral-400: #a3a3a3;
    --neutral-300: #d4d4d4;
    --neutral-200: #e5e5e5;
    --neutral-100: #f5f5f5;
    --neutral-50: #fafafa;
}

/* Typography Override */
.status-container,
.status-container * {
    font-family: 'IBM Plex Sans', -apple-system, BlinkMacSystemFont, sans-serif;
}

.mono-text,
code {
    font-family: 'IBM Plex Mono', monospace !important;
}

/* ========================================
   PAGE HEADER - Industrial Style
   ======================================== */
.page-header {
    position: relative;
    background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
    border-radius: 16px;
    padding: 2rem;
    color: white;
    overflow: hidden;
    margin-bottom: 1.5rem;
}

.page-header-pattern {
    position: absolute;
    inset: 0;
    opacity: 0.07;
    background-image:
        linear-gradient(rgba(255,255,255,0.1) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.1) 1px, transparent 1px);
    background-size: 40px 40px;
    pointer-events: none;
}

.page-header-glow {
    position: absolute;
    top: -50%;
    right: -20%;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(245, 158, 11, 0.15) 0%, transparent 70%);
    pointer-events: none;
}

.page-header-content {
    position: relative;
    z-index: 2;
}

.page-header-eyebrow {
    font-size: 0.7rem;
    font-weight: 600;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: var(--uitm-amber);
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.page-header-eyebrow::before {
    content: '';
    width: 24px;
    height: 2px;
    background: var(--uitm-amber);
}

.page-header-title {
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    line-height: 1.2;
}

.page-header-subtitle {
    font-size: 0.95rem;
    color: rgba(255, 255, 255, 0.85);
    margin-bottom: 0;
}

/* ========================================
   INDUSTRIAL CARDS
   ======================================== */
.industrial-card {
    background: white;
    border-radius: 12px;
    border: 2px solid var(--neutral-200);
    overflow: hidden;
    margin-bottom: 1.5rem;
    transition: all 0.3s ease;
}

.industrial-card:hover {
    border-color: var(--neutral-300);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
}

.card-header-industrial {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
    padding: 1.25rem 1.5rem;
    background: var(--neutral-50);
    border-bottom: 2px solid var(--neutral-200);
}

.card-header-left {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.card-header-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--uitm-primary), var(--uitm-primary-light));
    color: white;
    font-size: 1.25rem;
}

.card-header-text h5 {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--neutral-900);
    margin: 0 0 0.25rem 0;
}

.card-header-text p {
    font-size: 0.8rem;
    color: var(--neutral-500);
    margin: 0;
}

.card-header-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.card-body-industrial {
    padding: 1.5rem;
}

/* ========================================
   STATUS BADGES - Industrial Style
   ======================================== */
.status-badge {
    font-family: 'IBM Plex Sans', sans-serif;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 0.4rem 0.9rem;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    white-space: nowrap;
}

.status-badge-success {
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    color: #065f46;
}

.status-badge-warning {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    color: #92400e;
}

.status-badge-danger {
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    color: #991b1b;
}

.status-badge-info {
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    color: #1e40af;
}

.status-badge-secondary {
    background: linear-gradient(135deg, var(--neutral-200), var(--neutral-100));
    color: var(--neutral-600);
}

.status-badge-primary {
    background: linear-gradient(135deg, var(--uitm-primary), var(--uitm-primary-light));
    color: white;
}

/* ========================================
   STAT CARDS - Industrial Style
   ======================================== */
.stat-card {
    background: white;
    border-radius: 12px;
    border: 2px solid var(--neutral-200);
    padding: 1.5rem;
    text-align: center;
    height: 100%;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
}

.stat-card-success {
    border-color: var(--uitm-green);
    border-left: 4px solid var(--uitm-green);
}

.stat-card-warning {
    border-color: var(--uitm-amber);
    border-left: 4px solid var(--uitm-amber);
}

.stat-number {
    font-family: 'IBM Plex Mono', monospace;
    font-size: 2.5rem;
    font-weight: 700;
    line-height: 1;
    margin-bottom: 0.5rem;
}

.stat-number-success {
    color: var(--uitm-green);
}

.stat-number-warning {
    color: var(--uitm-amber);
}

.stat-label {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--neutral-700);
    margin-bottom: 0.25rem;
}

.stat-sublabel {
    font-size: 0.75rem;
    color: var(--neutral-500);
}

/* ========================================
   INDUSTRIAL TABLE
   ======================================== */
.industrial-table {
    width: 100%;
    border-collapse: collapse;
}

.industrial-table th {
    background: var(--neutral-50);
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.05em;
    color: var(--neutral-600);
    padding: 1rem;
    border-bottom: 2px solid var(--neutral-200);
    text-align: left;
}

.industrial-table th.text-center {
    text-align: center;
}

.industrial-table td {
    padding: 1rem;
    vertical-align: middle;
    border-bottom: 1px solid var(--neutral-100);
    color: var(--neutral-700);
    font-size: 0.9rem;
}

.industrial-table tbody tr:last-child td {
    border-bottom: none;
}

.industrial-table tbody tr:hover {
    background: var(--neutral-50);
}

.industrial-table code {
    font-family: 'IBM Plex Mono', monospace !important;
    font-size: 0.85rem;
    color: var(--uitm-primary);
    background: rgba(30, 58, 138, 0.08);
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
}

/* ========================================
   ALERT BOXES - Industrial Style
   ======================================== */
.alert-industrial {
    border-radius: 10px;
    border: none;
    padding: 1rem 1.25rem;
    margin-bottom: 1rem;
}

.alert-industrial-success {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(52, 211, 153, 0.05));
    border-left: 4px solid var(--uitm-green);
    color: var(--neutral-800);
}

.alert-industrial-warning {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(251, 191, 36, 0.05));
    border-left: 4px solid var(--uitm-amber);
    color: var(--neutral-800);
}

.alert-industrial-info {
    background: linear-gradient(135deg, rgba(30, 58, 138, 0.08), rgba(59, 130, 246, 0.05));
    border-left: 4px solid var(--uitm-primary);
    color: var(--neutral-800);
}

.alert-industrial-light {
    background: var(--neutral-50);
    border: 2px solid var(--neutral-200);
    color: var(--neutral-700);
}

/* ========================================
   BUTTONS - Industrial Style
   ======================================== */
.btn-industrial {
    padding: 0.6rem 1.25rem;
    font-weight: 600;
    font-size: 0.85rem;
    border-radius: 8px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
}

.btn-industrial-primary {
    background: linear-gradient(135deg, var(--uitm-primary), var(--uitm-primary-light));
    color: white;
    border: none;
}

.btn-industrial-primary:hover {
    background: linear-gradient(135deg, var(--uitm-primary-dark), var(--uitm-primary));
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
}

.btn-industrial-secondary {
    background: var(--neutral-100);
    color: var(--neutral-700);
    border: 2px solid var(--neutral-200);
}

.btn-industrial-secondary:hover {
    background: var(--neutral-200);
    color: var(--neutral-800);
    transform: translateY(-2px);
}

.btn-industrial-success {
    background: linear-gradient(135deg, var(--uitm-green), var(--uitm-green-light));
    color: white;
    border: none;
}

.btn-industrial-success:hover {
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    transform: translateY(-2px);
    color: white;
}

/* ========================================
   INFORMATION ROWS
   ======================================== */
.info-section {
    background: var(--neutral-50);
    border-radius: 10px;
    padding: 1.25rem;
    margin-bottom: 1rem;
}

.info-section h6 {
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: var(--uitm-primary);
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid var(--neutral-200);
}

.info-row {
    display: flex;
    margin-bottom: 0.5rem;
}

.info-row:last-child {
    margin-bottom: 0;
}

.info-label {
    font-weight: 600;
    color: var(--neutral-600);
    font-size: 0.85rem;
    min-width: 120px;
}

.info-value {
    color: var(--neutral-800);
    font-size: 0.9rem;
}

/* ========================================
   LEGEND SECTION
   ======================================== */
.legend-section {
    background: var(--neutral-50);
    border-radius: 10px;
    padding: 1.25rem;
    margin-top: 1.5rem;
}

.legend-section h6 {
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: var(--uitm-primary);
    margin-bottom: 1rem;
}

.legend-item {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
    font-size: 0.85rem;
}

.legend-item:last-child {
    margin-bottom: 0;
}

/* ========================================
   EMPTY STATE
   ======================================== */
.empty-state {
    text-align: center;
    padding: 3rem 2rem;
}

.empty-state-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: var(--neutral-100);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    color: var(--neutral-400);
    font-size: 2rem;
}

.empty-state h5 {
    font-weight: 700;
    color: var(--neutral-800);
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: var(--neutral-500);
    margin-bottom: 1.5rem;
}

/* ========================================
   SUMMARY BOX
   ======================================== */
.summary-box {
    background: var(--neutral-50);
    border: 2px solid var(--neutral-200);
    border-radius: 10px;
    padding: 1rem 1.25rem;
}

.summary-box h6 {
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--neutral-700);
}

.summary-box i {
    color: var(--uitm-primary);
}

/* ========================================
   RESPONSIVE DESIGN
   ======================================== */
@media (max-width: 991px) {
    .page-header {
        padding: 1.5rem;
    }

    .page-header-title {
        font-size: 1.5rem;
    }

    .card-header-industrial {
        flex-direction: column;
        align-items: flex-start;
    }

    .card-header-actions {
        width: 100%;
        justify-content: flex-start;
    }
}

@media (max-width: 767px) {
    .card-header-actions .btn-industrial {
        width: 100%;
        justify-content: center;
    }

    .info-row {
        flex-direction: column;
    }

    .info-label {
        margin-bottom: 0.25rem;
    }

    .stat-card {
        margin-bottom: 1rem;
    }
}
</style>
@endpush

@section('content')
<div class="status-container">
    {{-- Page Header --}}
    <div class="page-header">
        <div class="page-header-pattern"></div>
        <div class="page-header-glow"></div>
        <div class="page-header-content">
            <div class="page-header-eyebrow">Credit Exemption System</div>
            <h1 class="page-header-title">My Application Status</h1>
            <p class="page-header-subtitle">
                Track the progress of your credit exemption applications
            </p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-industrial alert-industrial-success">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    @if($applications->isEmpty())
        <div class="industrial-card">
            <div class="card-body-industrial">
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h5>No Applications Yet</h5>
                    <p>You haven't submitted any credit exemption applications.</p>
                    <a href="{{ route('student.application.create') }}" class="btn-industrial btn-industrial-primary">
                        <i class="fas fa-plus"></i> Apply Now
                    </a>
                </div>
            </div>
        </div>
    @else
        @foreach($applications as $app)
            <div class="industrial-card">
                <div class="card-header-industrial">
                    <div class="card-header-left">
                        <div class="card-header-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="card-header-text">
                            <h5>Application #{{ $loop->index + 1 }}</h5>
                            <p><i class="fas fa-calendar-alt me-1"></i> Submitted: {{ $app->created_at->format('d M Y, h:i A') }}</p>
                        </div>
                    </div>
                    <div class="card-header-actions">
                        @if($app->status === 'Reviewed by Academic Advisor')
                            <span class="status-badge status-badge-success">
                                <i class="fas fa-check-circle"></i> Ready for Registration
                            </span>
                        @else
                            <span class="status-badge status-badge-info">
                                <i class="fas fa-clock"></i> {{ $app->status }}
                            </span>
                        @endif
                        @if($app->transcript)
                            <a href="{{ route('student.application.transcript', $app) }}" target="_blank" class="btn-industrial btn-industrial-secondary">
                                <i class="fas fa-file-pdf"></i> Transcript
                            </a>
                        @endif
                        @if($app->status === 'Reviewed by Academic Advisor')
                            <a href="{{ route('student.application.validation', $app) }}" target="_blank" class="btn-industrial btn-industrial-primary">
                                <i class="fas fa-file-alt"></i> View Validation
                            </a>
                        @endif
                    </div>
                </div>
                <div class="card-body-industrial">
                    {{-- Application Details --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="info-section">
                                <h6><i class="fas fa-user me-2"></i>Student Information</h6>
                                <div class="info-row">
                                    <span class="info-label">Name:</span>
                                    <span class="info-value">{{ $app->student_name }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Matric No:</span>
                                    <span class="info-value mono-text">{{ $app->matric_no }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Program:</span>
                                    <span class="info-value">{{ $app->current_program_code ? $app->current_program_code . ' - ' : '' }}{{ $app->current_program }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Campus:</span>
                                    <span class="info-value">{{ $app->current_campus }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-section">
                                <h6><i class="fas fa-graduation-cap me-2"></i>Previous Education</h6>
                                <div class="info-row">
                                    <span class="info-label">Institution:</span>
                                    <span class="info-value">{{ $app->previous_institution }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Program:</span>
                                    <span class="info-value">{{ $app->previous_program }}</span>
                                </div>
                                @if($app->status === 'Reviewed by Academic Advisor' && $app->reviewer)
                                    <div class="info-row">
                                        <span class="info-label">Reviewed By:</span>
                                        <span class="info-value">{{ $app->reviewer->name }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- OCR Results Section --}}
                    @if($app->applicationSubjects->isNotEmpty())
                        {{-- Application Status Message --}}
                        @if($app->status === 'Reviewed by Academic Advisor')
                            <div class="alert-industrial alert-industrial-success">
                                <i class="fas fa-graduation-cap me-2"></i>
                                <strong>Review Complete!</strong> Your academic advisor has completed the review process.
                                You can now proceed to the <strong>Student e-Course Registration System</strong> to register your approved exempted courses.
                            </div>
                        @else
                            <div class="alert-industrial alert-industrial-warning">
                                <i class="fas fa-clock me-2"></i>
                                <strong>Under Review:</strong> Your application is currently under academic advisor review.
                                OCR-exempted courses are pending approval.
                            </div>
                        @endif

                        @if($app->current_program_code)
                            <div class="alert-industrial alert-industrial-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Program-Specific Analysis:</strong> Courses analyzed for <code class="mono-text">{{ $app->current_program_code }}</code> program equivalencies.
                                Only courses meeting ALL criteria (found + grade >= C + match > 80%) qualify for exemption.
                            </div>
                        @endif

                        {{-- Summary Statistics --}}
                        @php
                            $totalCourses = $app->applicationSubjects->count();

                            // Get exempted/approved diploma courses
                            $exemptedDiplomaCourses = $app->applicationSubjects->filter(function($subject) {
                                return $subject->status === 'exempted' || $subject->status === 'Approved';
                            });

                            // Count UNIQUE DEGREE COURSES (not diploma courses)
                            $academicDegreeCourses = [];
                            $coCurriculumSlots = 0;

                            foreach ($exemptedDiplomaCourses as $subject) {
                                $notes = $subject->notes ? json_decode($subject->notes, true) : null;
                                $equivalentCourse = $notes['equivalent_course'] ?? null;

                                if ($equivalentCourse === 'HXXXXX') {
                                    // Each co-curriculum course counts as separate degree slot
                                    $coCurriculumSlots++;
                                } elseif ($equivalentCourse && !in_array($equivalentCourse, $academicDegreeCourses)) {
                                    // Count unique academic degree courses only
                                    $academicDegreeCourses[] = $equivalentCourse;
                                }
                            }

                            // Total exempted DEGREE COURSES = unique academic + co-curriculum slots
                            $exemptedCourses = count($academicDegreeCourses) + $coCurriculumSlots;

                            // Count courses not available for exemption
                            $notAvailableForExemption = $totalCourses - $exemptedDiplomaCourses->count();
                        @endphp

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <div class="stat-card stat-card-success">
                                    <div class="stat-number stat-number-success">{{ $exemptedCourses }}</div>
                                    <div class="stat-label">Exempted</div>
                                    <div class="stat-sublabel">
                                        @if($app->status === 'Reviewed by Academic Advisor')
                                            Degree courses approved for exemption
                                        @else
                                            Degree courses eligible for exemption
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="stat-card stat-card-warning">
                                    <div class="stat-number stat-number-warning">{{ $notAvailableForExemption }}</div>
                                    <div class="stat-label">Not Available</div>
                                    <div class="stat-sublabel">Diploma courses not eligible for credit exemption</div>
                                </div>
                            </div>
                        </div>

                        <div class="summary-box mb-4">
                            <h6>
                                <i class="fas fa-chart-pie"></i>
                                Summary: <strong>{{ $exemptedCourses }} degree courses</strong> can be exempted from <strong>{{ $exemptedDiplomaCourses->count() }} diploma courses</strong>
                                ({{ count($academicDegreeCourses) }} academic + {{ $coCurriculumSlots }} co-curriculum)
                            </h6>
                        </div>

                        {{-- Detailed Course List --}}
                        <div class="table-responsive">
                            <table class="industrial-table">
                                <thead>
                                    <tr>
                                        <th>Course Code</th>
                                        <th>Course Name</th>
                                        <th class="text-center">Grade</th>
                                        <th>Equivalent Course</th>
                                        <th>OCR Analysis</th>
                                        <th>Workflow Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        // Define GPA conversion function once before the loop
                                        $convertGPAToLetterGrade = function($gpa) {
                                            $gpa = floatval($gpa);
                                            if ($gpa >= 4.00) return 'A';
                                            if ($gpa >= 3.67) return 'A-';
                                            if ($gpa >= 3.33) return 'B+';
                                            if ($gpa >= 3.00) return 'B';
                                            if ($gpa >= 2.67) return 'B-';
                                            if ($gpa >= 2.33) return 'C+';
                                            if ($gpa >= 2.00) return 'C';
                                            if ($gpa >= 1.67) return 'C-';
                                            if ($gpa >= 1.33) return 'D+';
                                            if ($gpa >= 1.00) return 'D';
                                            return 'F';
                                        };
                                    @endphp
                                    @foreach($app->applicationSubjects->sortBy('course_code') as $subject)
                                    <tr>
                                        <td><code>{{ $subject->course_code }}</code></td>
                                        <td>{{ $subject->course_name }}</td>
                                        <td class="text-center">
                                            @php
                                                // Check if course code contains combined courses
                                                $courseCode = $subject->course_code;
                                                $hasMultipleCourses = strpos($courseCode, '&') !== false || strpos($courseCode, '+') !== false;

                                                $grades = [];
                                                if ($hasMultipleCourses) {
                                                    // Extract individual course codes
                                                    $individualCodes = preg_split('/[\s&+]+/', $courseCode);
                                                    $individualCodes = array_filter(array_map('trim', $individualCodes));

                                                    // Look up each course's grade
                                                    foreach ($individualCodes as $code) {
                                                        $courseSubject = $app->applicationSubjects->firstWhere('course_code', $code);
                                                        if ($courseSubject && $courseSubject->grade !== null) {
                                                            $grades[] = $convertGPAToLetterGrade($courseSubject->grade);
                                                        }
                                                    }
                                                } else {
                                                    // Single course
                                                    if ($subject->grade !== null) {
                                                        $grades[] = $convertGPAToLetterGrade($subject->grade);
                                                    }
                                                }

                                                $displayGrade = !empty($grades) ? implode(' & ', $grades) : 'N/A';

                                                // Determine badge class
                                                $gradeBadgeClass = 'status-badge-secondary';
                                                if (in_array($subject->status, ['exempted'])) {
                                                    $gradeBadgeClass = 'status-badge-success';
                                                } elseif ($subject->status === 'not_eligible_grade') {
                                                    $gradeBadgeClass = 'status-badge-danger';
                                                }
                                            @endphp
                                            <span class="status-badge {{ $gradeBadgeClass }}">
                                                {{ $displayGrade }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $notes = $subject->notes ? json_decode($subject->notes, true) : null;
                                                $equivalentCourse = $notes['equivalent_course'] ?? null;

                                                // If notes are empty, try to find equivalent course directly from database
                                                if (!$equivalentCourse) {
                                                    $equivalency = \App\Models\CourseEquivalency::where('diploma_course_code', $subject->course_code)
                                                                                              ->where('program_code', $subject->exemptionApplication->current_program_code ?? 'CS251')
                                                                                              ->first();
                                                    $equivalentCourse = $equivalency ? $equivalency->degree_course_code : null;
                                                }
                                            @endphp
                                            @if($equivalentCourse)
                                                <code>{{ $equivalentCourse }}</code>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                // Show OCR analysis status
                                                $ocrStatus = in_array($subject->status, ['Approved', 'Rejected', 'Forward to Coordinator'])
                                                    ? (str_contains($subject->exemption_reason ?? '', 'All criteria met') ? 'exempted' : 'not_eligible')
                                                    : $subject->status;
                                            @endphp
                                            @switch($ocrStatus)
                                                @case('exempted')
                                                    <span class="status-badge status-badge-success">
                                                        <i class="fas fa-check"></i> EXEMPTED
                                                    </span>
                                                    @break
                                                @case('not_found')
                                                    <span class="status-badge status-badge-warning">
                                                        <i class="fas fa-search"></i> NOT FOUND
                                                    </span>
                                                    @break
                                                @case('not_eligible_grade')
                                                    <span class="status-badge status-badge-danger">
                                                        <i class="fas fa-times"></i> GRADE TOO LOW
                                                    </span>
                                                    @break
                                                @case('not_eligible_match')
                                                    <span class="status-badge status-badge-secondary">
                                                        <i class="fas fa-percentage"></i> LOW MATCH
                                                    </span>
                                                    @break
                                                @default
                                                    <span class="status-badge status-badge-secondary">
                                                        <i class="fas fa-ban"></i> NOT ELIGIBLE
                                                    </span>
                                            @endswitch
                                        </td>
                                        <td>
                                            @switch($subject->status)
                                                @case('exempted')
                                                    <span class="status-badge status-badge-warning">
                                                        <i class="fas fa-clock"></i> PENDING APPROVAL
                                                    </span>
                                                    @break
                                                @case('Approved')
                                                    <span class="status-badge status-badge-success">
                                                        <i class="fas fa-check-circle"></i> APPROVED
                                                    </span>
                                                    @break
                                                @case('Rejected')
                                                    <span class="status-badge status-badge-danger">
                                                        <i class="fas fa-times-circle"></i> REJECTED
                                                    </span>
                                                    @break
                                                @case('Forward to Coordinator')
                                                    <span class="status-badge status-badge-info">
                                                        <i class="fas fa-arrow-right"></i> COORDINATOR REVIEW
                                                    </span>
                                                    @break
                                                @default
                                                    <span class="status-badge status-badge-secondary">
                                                        <i class="fas fa-hourglass-half"></i> PENDING REVIEW
                                                    </span>
                                            @endswitch
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Legend --}}
                        <div class="legend-section">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6><i class="fas fa-robot me-2"></i>OCR Analysis Results</h6>
                                    <div class="legend-item">
                                        <span class="status-badge status-badge-success"><i class="fas fa-check"></i> EXEMPTED</span>
                                        <span>All criteria met: Course found + Grade >= C + Match > 80%</span>
                                    </div>
                                    <div class="legend-item">
                                        <span class="status-badge status-badge-warning"><i class="fas fa-search"></i> NOT FOUND</span>
                                        <span>Course not in {{ $app->current_program_code ?: 'program' }} equivalency database</span>
                                    </div>
                                    <div class="legend-item">
                                        <span class="status-badge status-badge-danger"><i class="fas fa-times"></i> GRADE TOO LOW</span>
                                        <span>Grade below C requirement</span>
                                    </div>
                                    <div class="legend-item">
                                        <span class="status-badge status-badge-secondary"><i class="fas fa-percentage"></i> LOW MATCH</span>
                                        <span>Course match <= 80%</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6><i class="fas fa-tasks me-2"></i>Workflow Status</h6>
                                    <div class="legend-item">
                                        <span class="status-badge status-badge-warning"><i class="fas fa-clock"></i> PENDING</span>
                                        <span>Awaiting academic advisor decision</span>
                                    </div>
                                    <div class="legend-item">
                                        <span class="status-badge status-badge-success"><i class="fas fa-check-circle"></i> APPROVED</span>
                                        <span>Ready for course registration</span>
                                    </div>
                                    <div class="legend-item">
                                        <span class="status-badge status-badge-danger"><i class="fas fa-times-circle"></i> REJECTED</span>
                                        <span>Not approved for exemption</span>
                                    </div>
                                    <div class="legend-item">
                                        <span class="status-badge status-badge-info"><i class="fas fa-arrow-right"></i> COORDINATOR</span>
                                        <span>Under additional review</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @else
                        <div class="alert-industrial alert-industrial-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>No Course Data Available</strong><br>
                            OCR processing may have failed or no courses were detected in your transcript.
                        </div>
                    @endif
                </div>
            </div>
        @endforeach

        <div class="text-center mt-4">
            <a href="{{ route('student.application.create') }}" class="btn-industrial btn-industrial-primary">
                <i class="fas fa-plus"></i> Submit Another Application
            </a>
        </div>
    @endif
</div>
@endsection
