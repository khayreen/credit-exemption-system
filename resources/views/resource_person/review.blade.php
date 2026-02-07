@extends('layouts.app')

@section('title', 'Subject Review')

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
        --info: #0284c7;
        --info-light: #e0f2fe;
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

    .btn-back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--uitm-primary);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 1.5rem;
        transition: all 0.2s ease;
    }

    .btn-back-link:hover {
        color: var(--uitm-primary-light);
        transform: translateX(-4px);
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
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--industrial-dark) 100%);
        padding: 1.5rem;
        position: relative;
        overflow: hidden;
    }

    .industrial-card-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(245, 158, 11, 0.2) 0%, transparent 70%);
        border-radius: 50%;
    }

    .industrial-card-header h5 {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 1.25rem;
        font-weight: 700;
        color: white;
        margin: 0;
        position: relative;
        z-index: 1;
    }

    .industrial-card-body {
        padding: 1.5rem;
    }

    /* Context Alerts */
    .context-alert {
        border: none;
        border-radius: 12px;
        padding: 1rem 1.25rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .context-alert.info {
        background: var(--info-light);
        border-left: 4px solid var(--info);
    }

    .context-alert.success {
        background: var(--success-light);
        border-left: 4px solid var(--success);
    }

    .context-alert.warning {
        background: var(--warning-light);
        border-left: 4px solid var(--warning);
    }

    .context-alert-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .context-alert.info .context-alert-icon {
        background: var(--info);
        color: white;
    }

    .context-alert.success .context-alert-icon {
        background: var(--success);
        color: white;
    }

    .context-alert.warning .context-alert-icon {
        background: var(--warning);
        color: white;
    }

    .context-alert-content {
        flex: 1;
    }

    .context-alert-content strong {
        display: block;
        margin-bottom: 0.25rem;
    }

    .context-alert.info .context-alert-content {
        color: #0369a1;
    }

    .context-alert.success .context-alert-content {
        color: #047857;
    }

    .context-alert.warning .context-alert-content {
        color: #c2410c;
    }

    /* Course Details Table */
    .details-section-title {
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

    .details-section-title i {
        color: var(--uitm-amber);
    }

    .details-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 1.5rem;
    }

    .details-table tr {
        border-bottom: 1px solid var(--neutral-100);
    }

    .details-table tr:last-child {
        border-bottom: none;
    }

    .details-table th {
        width: 35%;
        padding: 0.875rem 1rem;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--neutral-600);
        background: var(--neutral-50);
        text-align: left;
        vertical-align: middle;
    }

    .details-table td {
        padding: 0.875rem 1rem;
        font-size: 0.9rem;
        color: var(--industrial-dark);
        vertical-align: middle;
    }

    .course-code-display {
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 700;
        color: var(--uitm-primary);
        font-size: 1rem;
    }

    .badge-grade {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.375rem 0.75rem;
        border-radius: 8px;
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.85rem;
        font-weight: 600;
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        color: white;
    }

    .badge-ocr {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-ocr.extracted {
        background: var(--success-light);
        color: var(--success);
    }

    .badge-ocr.manual {
        background: var(--neutral-100);
        color: var(--neutral-600);
    }

    /* Form Section */
    .form-section-title {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 1rem;
        font-weight: 600;
        color: var(--industrial-dark);
        margin-bottom: 0.5rem;
    }

    .form-section-subtitle {
        color: var(--neutral-500);
        font-size: 0.875rem;
        margin-bottom: 1.5rem;
    }

    .form-label-industrial {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--neutral-700);
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-control-industrial,
    .form-select-industrial {
        border: 2px solid var(--neutral-200);
        border-radius: 10px;
        padding: 0.75rem 1rem;
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        width: 100%;
    }

    .form-control-industrial:focus,
    .form-select-industrial:focus {
        border-color: var(--uitm-primary);
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
        outline: none;
    }

    .form-text-industrial {
        font-size: 0.8rem;
        color: var(--neutral-500);
        margin-top: 0.375rem;
    }

    /* Buttons */
    .btn-primary-industrial {
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        border: none;
        color: white;
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 1rem;
        padding: 1rem 2rem;
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
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(30, 58, 138, 0.3);
    }

    .btn-warning-industrial {
        background: linear-gradient(135deg, var(--warning) 0%, var(--uitm-amber-dark) 100%);
        border: none;
        color: white;
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 1rem;
        padding: 1rem 2rem;
        border-radius: 10px;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
        cursor: pointer;
    }

    .btn-warning-industrial:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(234, 88, 12, 0.3);
    }

    .btn-secondary-industrial {
        background: white;
        border: 2px solid var(--neutral-300);
        color: var(--neutral-700);
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.9rem;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        transition: all 0.2s ease;
    }

    .btn-secondary-industrial:hover {
        background: var(--neutral-50);
        border-color: var(--neutral-400);
    }

    /* Modal Styles */
    .modal-content {
        border: none;
        border-radius: 16px;
        overflow: hidden;
    }

    .modal-header {
        background: linear-gradient(135deg, var(--warning) 0%, var(--uitm-amber-dark) 100%);
        border: none;
        padding: 1.25rem 1.5rem;
    }

    .modal-title {
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        color: white;
    }

    .modal-header .btn-close {
        filter: brightness(0) invert(1);
        opacity: 0.8;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        background: var(--neutral-50);
        border-top: 2px solid var(--neutral-200);
        padding: 1rem 1.5rem;
    }

    .btn-modal-warning {
        background: linear-gradient(135deg, var(--warning) 0%, var(--uitm-amber-dark) 100%);
        border: none;
        color: white;
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .btn-modal-warning:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(234, 88, 12, 0.3);
    }

    @media (max-width: 768px) {
        .industrial-card-body {
            padding: 1rem;
        }

        .details-table th,
        .details-table td {
            padding: 0.75rem;
        }

        .btn-primary-industrial,
        .btn-warning-industrial {
            padding: 0.875rem 1.5rem;
            font-size: 0.9rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <a href="{{ route('resource_person.dashboard') }}" class="btn-back-link">
        <i class="fas fa-arrow-left"></i>
        Back to Dashboard
    </a>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="industrial-card">
                <div class="industrial-card-header">
                    <h5><i class="fas fa-microscope me-2"></i>Resource Person Subject Review</h5>
                </div>
                <div class="industrial-card-body">
                    {{-- Syllabus Received Alert --}}
                    @if($subject->status === 'Syllabus Received')
                        <div class="context-alert info">
                            <div class="context-alert-icon">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div class="context-alert-content">
                                <strong>Syllabus Submitted</strong>
                                A new syllabus has been submitted for this course.
                                <a href="{{ route('resource_person.subject.view_syllabus', $subject) }}" target="_blank" style="color: var(--uitm-primary); font-weight: 600;">
                                    <i class="fas fa-external-link-alt me-1"></i>View Submitted Syllabus
                                </a>
                            </div>
                        </div>
                    @endif

                    {{-- OCR Context --}}
                    @if($subject->extraction_method == 'ocr')
                        <div class="context-alert success">
                            <div class="context-alert-icon">
                                <i class="fas fa-robot"></i>
                            </div>
                            <div class="context-alert-content">
                                <strong>OCR Extracted Course</strong>
                                This course was automatically extracted from the student's transcript using OCR technology.
                                @if($subject->ocr_confidence_score)
                                    OCR Confidence: <strong>{{ number_format($subject->ocr_confidence_score, 1) }}%</strong>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Program Context --}}
                    @if($subject->exemptionApplication->current_program_code)
                        <div class="context-alert info">
                            <div class="context-alert-icon">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div class="context-alert-content">
                                <strong>Student Program</strong>
                                {{ $subject->exemptionApplication->current_program_code }} - {{ $subject->exemptionApplication->current_program }}
                            </div>
                        </div>
                    @endif

                    {{-- Previous OCR Analysis --}}
                    @if($subject->exemption_reason)
                        @php
                            $notes = $subject->notes ? json_decode($subject->notes, true) : null;
                            $gradeMap = [
                                4.00 => 'A', 3.67 => 'A-', 3.33 => 'B+', 3.00 => 'B', 2.67 => 'B-',
                                2.33 => 'C+', 2.00 => 'C', 1.67 => 'C-', 1.33 => 'D+', 1.00 => 'D', 0.00 => 'F'
                            ];
                            $gradeLetter = $gradeMap[$subject->grade] ?? 'Unknown';
                        @endphp
                        <div class="context-alert warning">
                            <div class="context-alert-icon">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="context-alert-content">
                                <strong>Previous OCR Analysis</strong>
                                {{ $subject->exemption_reason }}
                                @if($notes && isset($notes['equivalent_course']))
                                    <br><small>Previous Match Found: <strong>{{ $notes['equivalent_course'] }}</strong> with {{ $notes['match_percentage'] ?? 0 }}% similarity</small>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Diploma Course Details --}}
                    <div class="details-section-title">
                        <i class="fas fa-book"></i>
                        Diploma Course Details
                    </div>

                    <table class="details-table">
                        <tr>
                            <th>Course Code</th>
                            <td><span class="course-code-display">{{ $subject->course_code }}</span></td>
                        </tr>
                        <tr>
                            <th>Course Name</th>
                            <td>{{ $subject->course_name }}</td>
                        </tr>
                        <tr>
                            <th>Credit Hours</th>
                            <td>{{ $subject->credit_hour }}</td>
                        </tr>
                        <tr>
                            <th>Student Grade</th>
                            <td>
                                @php
                                    $gradeLetter = $gradeMap[$subject->grade] ?? 'Unknown';
                                @endphp
                                <span class="badge-grade">
                                    {{ $gradeLetter }} <small>({{ $subject->grade }} GPA)</small>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Institution</th>
                            <td>{{ $subject->exemptionApplication->previous_institution }}</td>
                        </tr>
                        <tr>
                            <th>Extraction Method</th>
                            <td>
                                @if($subject->extraction_method == 'ocr')
                                    <span class="badge-ocr extracted">
                                        <i class="fas fa-robot"></i> OCR Extracted
                                    </span>
                                @else
                                    <span class="badge-ocr manual">
                                        <i class="fas fa-keyboard"></i> Manual Entry
                                    </span>
                                @endif
                            </td>
                        </tr>
                    </table>

                    {{-- Main Form --}}
                    <form method="POST" action="{{ route('resource_person.subject.process', $subject) }}">
                        @csrf
                        <div class="form-section-title">Finding & Equivalency Creation</div>
                        <p class="form-section-subtitle">Find the closest equivalent degree course, determine the match percentage, and submit your final recommendation.</p>

                        <div class="mb-4">
                            <label for="degree_course_code" class="form-label-industrial">
                                <span class="text-uitm-amber me-1">1.</span> Equivalent Degree Course
                            </label>
                            <select name="degree_course_code" id="degree_course_code" class="form-select-industrial" required>
                                <option value="" selected disabled>-- Select a Degree Course --</option>
                                @foreach($degreeCourses as $course)
                                    <option value="{{ $course->code }}">{{ $course->code }} - {{ $course->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="match_percentage" class="form-label-industrial">
                                <span class="text-uitm-amber me-1">2.</span> Match Percentage (%)
                            </label>
                            <input type="number" name="match_percentage" id="match_percentage" class="form-control-industrial"
                                   min="0" max="100" step="0.1" placeholder="e.g., 85.5" required>
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="form-label-industrial">
                                <span class="text-uitm-amber me-1">3.</span> Justification / Notes (Optional)
                            </label>
                            <textarea name="notes" id="notes" class="form-control-industrial" rows="3"
                                      placeholder="Provide additional justification for your decision (optional)..."></textarea>
                            <div class="form-text-industrial">An automatic remark will be generated based on your decision.</div>
                        </div>

                        <div class="mb-4">
                            <label for="decision" class="form-label-industrial">
                                <span class="text-uitm-amber me-1">4.</span> Final Recommendation
                            </label>
                            <select name="decision" id="decision" class="form-select-industrial" required>
                                <option value="" selected disabled>-- Select Decision --</option>
                                <option value="Equivalent">Equivalent</option>
                                <option value="Not Equivalent">Not Equivalent</option>
                            </select>
                        </div>

                        <div class="row mt-4 g-3">
                            <div class="col-md-6">
                                <button type="submit" class="btn-primary-industrial">
                                    <i class="fas fa-paper-plane"></i>
                                    Submit Finding to Coordinator
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="btn-warning-industrial" data-bs-toggle="modal" data-bs-target="#requestSyllabusModal">
                                    <i class="fas fa-file-alt"></i>
                                    Request Complete Syllabus
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Request Syllabus Modal -->
<div class="modal fade" id="requestSyllabusModal" tabindex="-1" aria-labelledby="requestSyllabusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('resource_person.subject.request_syllabus', $subject) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="requestSyllabusModalLabel">
                        <i class="fas fa-file-alt me-2"></i>Request Complete Syllabus
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p style="color: var(--neutral-600); font-size: 0.9rem;">
                        Request a complete syllabus for <strong>{{ $subject->course_code }} - {{ $subject->course_name }}</strong> from an external lecturer.
                    </p>

                    <div class="mb-3">
                        <label for="external_lecturer_name" class="form-label-industrial">
                            External Lecturer Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="external_lecturer_name" id="external_lecturer_name"
                               class="form-control-industrial" required placeholder="e.g., Dr. Sarah Johnson">
                        <div class="form-text-industrial">Include appropriate title (Dr., Prof., etc.) if known.</div>
                    </div>

                    <div class="mb-3">
                        <label for="external_lecturer_email" class="form-label-industrial">
                            External Lecturer Email <span class="text-danger">*</span>
                        </label>
                        <input type="email" name="external_lecturer_email" id="external_lecturer_email"
                               class="form-control-industrial" required placeholder="Enter external lecturer's email address">
                        <div class="form-text-industrial">The external lecturer will receive an email with a secure submission link.</div>
                    </div>

                    <div class="mb-3">
                        <label for="request_notes" class="form-label-industrial">Additional Notes (Optional)</label>
                        <textarea name="request_notes" id="request_notes" class="form-control-industrial" rows="3"
                                  placeholder="Any specific requirements or notes for the external lecturer..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary-industrial" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-modal-warning">
                        <i class="fas fa-paper-plane me-1"></i>Send Syllabus Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
