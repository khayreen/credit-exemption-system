@extends('layouts.app')

@section('title', 'External Lecturer Dashboard')

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

    .page-header::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: 10%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(59, 130, 246, 0.1) 0%, transparent 70%);
        border-radius: 50%;
    }

    .page-header-content {
        position: relative;
        z-index: 1;
    }

    .page-title {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 1.75rem;
        font-weight: 700;
        color: white;
        margin-bottom: 0.5rem;
    }

    .page-subtitle {
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.95rem;
        margin-bottom: 0;
    }

    .welcome-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 8px;
        padding: 0.5rem 1rem;
        color: white;
        font-size: 0.875rem;
        margin-bottom: 1rem;
    }

    .welcome-badge i {
        color: var(--uitm-amber);
    }

    .btn-upload-header {
        background: var(--uitm-amber);
        border: none;
        color: var(--industrial-dark);
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.9rem;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-upload-header:hover {
        background: var(--uitm-amber-dark);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(245, 158, 11, 0.3);
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border: 2px solid var(--neutral-200);
        border-radius: 16px;
        padding: 1.5rem;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
    }

    .stat-card.pending::before {
        background: linear-gradient(90deg, var(--warning), var(--uitm-amber));
    }

    .stat-card.completed::before {
        background: linear-gradient(90deg, var(--success), var(--teal));
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .stat-card.pending .stat-icon {
        background: var(--warning-light);
        color: var(--warning);
    }

    .stat-card.completed .stat-icon {
        background: var(--success-light);
        color: var(--success);
    }

    .stat-icon i {
        font-size: 1.25rem;
    }

    .stat-value {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--industrial-dark);
        line-height: 1;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 0.875rem;
        color: var(--neutral-600);
        font-weight: 500;
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

    .industrial-card-header.pending {
        border-left: 4px solid var(--warning);
    }

    .industrial-card-header.completed {
        border-left: 4px solid var(--success);
    }

    .industrial-card-header i {
        font-size: 1.1rem;
    }

    .industrial-card-header.pending i {
        color: var(--warning);
    }

    .industrial-card-header.completed i {
        color: var(--success);
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

    /* Table Styles */
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

    .date-text {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.8rem;
        color: var(--neutral-500);
    }

    /* Badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .status-badge.success {
        background: var(--success-light);
        color: var(--success);
    }

    .status-badge.pending {
        background: var(--warning-light);
        color: var(--warning);
    }

    /* Buttons */
    .btn-submit-syllabus {
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        border: none;
        color: white;
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.8rem;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
    }

    .btn-submit-syllabus:hover {
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
    }

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
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-secondary-industrial:hover {
        background: var(--neutral-50);
        border-color: var(--neutral-400);
        color: var(--neutral-600);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
    }

    .empty-state-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--neutral-100) 0%, var(--neutral-50) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }

    .empty-state-icon i {
        font-size: 2rem;
        color: var(--neutral-400);
    }

    .empty-state-title {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--industrial-dark);
        margin-bottom: 0.5rem;
    }

    .empty-state-text {
        color: var(--neutral-500);
        font-size: 0.95rem;
        margin-bottom: 1.5rem;
        max-width: 400px;
        margin-left: auto;
        margin-right: auto;
    }

    /* Modal Styles */
    .modal-content {
        border: none;
        border-radius: 16px;
        overflow: hidden;
    }

    .modal-header {
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--industrial-dark) 100%);
        border: none;
        padding: 1.5rem;
    }

    .modal-title {
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        color: white;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .modal-title i {
        color: var(--uitm-amber);
    }

    .modal-header .btn-close {
        filter: brightness(0) invert(1);
        opacity: 0.8;
    }

    .modal-header .btn-close:hover {
        opacity: 1;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        background: var(--neutral-50);
        border-top: 2px solid var(--neutral-200);
        padding: 1.25rem 1.5rem;
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
        padding: 1.5rem;
        text-align: center;
        transition: all 0.2s ease;
        background: var(--neutral-50);
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
        margin-bottom: 0.5rem;
    }

    .file-upload-hint {
        font-size: 0.8rem;
        color: var(--neutral-500);
    }

    .file-input {
        position: absolute;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
        top: 0;
        left: 0;
    }

    .file-upload-wrapper {
        position: relative;
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

    .alert-industrial-content ul {
        margin: 0;
        padding-left: 1.25rem;
        font-size: 0.85rem;
    }

    .alert-industrial.info .alert-industrial-content ul {
        color: #115e59;
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
        }

        .page-title {
            font-size: 1.5rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .stat-value {
            font-size: 2rem;
        }

        .industrial-table {
            font-size: 0.85rem;
        }

        .industrial-table thead th,
        .industrial-table tbody td {
            padding: 0.75rem 0.5rem;
        }

        .modal-dialog {
            margin: 0.5rem;
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
                    <div class="welcome-badge">
                        <i class="fas fa-user-tie"></i>
                        <span>Welcome, <strong>{{ Auth::user()->name }}</strong></span>
                    </div>
                    <h1 class="page-title">External Lecturer Dashboard</h1>
                    <p class="page-subtitle">Submit course syllabi to help UiTM evaluate credit exemption applications</p>
                </div>
                <button type="button" class="btn-upload-header" data-bs-toggle="modal" data-bs-target="#uploadModal">
                    <i class="fas fa-cloud-upload-alt"></i>
                    Upload Syllabus
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card pending">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-value">{{ $stats['pending_requests'] }}</div>
            <div class="stat-label">Pending Requests</div>
        </div>
        <div class="stat-card completed">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-value">{{ $stats['completed_submissions'] }}</div>
            <div class="stat-label">Completed Submissions</div>
        </div>
    </div>

    <!-- Pending Syllabus Requests -->
    @if($pendingRequests->count() > 0)
    <div class="industrial-card">
        <div class="industrial-card-header pending">
            <i class="fas fa-clock"></i>
            <h5 class="industrial-card-title">Pending Syllabus Requests</h5>
        </div>
        <div class="industrial-card-body p-0">
            <div class="table-responsive">
                <table class="industrial-table">
                    <thead>
                        <tr>
                            <th>Course Code</th>
                            <th>Course Name</th>
                            <th>Student</th>
                            <th>Requested Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingRequests as $request)
                        <tr>
                            <td><span class="course-code">{{ $request->course_code }}</span></td>
                            <td><span class="course-name">{{ $request->course_name }}</span></td>
                            <td><span class="student-name">{{ $request->exemptionApplication->student_name ?? 'N/A' }}</span></td>
                            <td><span class="date-text">{{ $request->updated_at->format('M d, Y') }}</span></td>
                            <td>
                                <button type="button"
                                    class="btn-submit-syllabus fill-course-btn"
                                    data-bs-toggle="modal"
                                    data-bs-target="#uploadModal"
                                    data-course-code="{{ $request->course_code }}"
                                    data-course-name="{{ $request->course_name }}"
                                    data-credit-hours="{{ $request->credit_hour }}">
                                    <i class="fas fa-upload"></i>
                                    Submit
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Recent Submissions -->
    @if($completedSubmissions->count() > 0)
    <div class="industrial-card">
        <div class="industrial-card-header completed">
            <i class="fas fa-check-circle"></i>
            <h5 class="industrial-card-title">Recent Submissions</h5>
        </div>
        <div class="industrial-card-body p-0">
            <div class="table-responsive">
                <table class="industrial-table">
                    <thead>
                        <tr>
                            <th>Course Code</th>
                            <th>Course Name</th>
                            <th>Student</th>
                            <th>Submitted Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($completedSubmissions as $submission)
                        <tr>
                            <td><span class="course-code">{{ $submission->course_code }}</span></td>
                            <td><span class="course-name">{{ $submission->course_name }}</span></td>
                            <td><span class="student-name">{{ $submission->exemptionApplication->student_name ?? 'General Submission' }}</span></td>
                            <td><span class="date-text">{{ $submission->updated_at->format('M d, Y') }}</span></td>
                            <td>
                                <span class="status-badge success">
                                    <i class="fas fa-check"></i>
                                    {{ $submission->status }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Empty State -->
    @if($pendingRequests->count() == 0 && $completedSubmissions->count() == 0)
    <div class="industrial-card">
        <div class="industrial-card-body">
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-file-upload"></i>
                </div>
                <h5 class="empty-state-title">No syllabus requests yet</h5>
                <p class="empty-state-text">You can proactively upload course syllabi to help UiTM evaluate credit exemption applications.</p>
                <button type="button" class="btn-primary-industrial" data-bs-toggle="modal" data-bs-target="#uploadModal">
                    <i class="fas fa-cloud-upload-alt"></i>
                    Upload Syllabus
                </button>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Upload Syllabus Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">
                    <i class="fas fa-cloud-upload-alt"></i>
                    Upload Course Syllabus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('external_lecturer.store_general') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    @if(session('success'))
                        <div class="alert-industrial success mb-3">
                            <div class="alert-industrial-icon">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="alert-industrial-content">
                                <h6>Success</h6>
                                <p class="mb-0" style="font-size: 0.875rem;">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert-industrial danger mb-3">
                            <div class="alert-industrial-icon">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="alert-industrial-content">
                                <h6>Please correct the following errors</h6>
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

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
                                       id="modal_course_code"
                                       name="course_code"
                                       value="{{ old('course_code') }}"
                                       placeholder="Course Code"
                                       required>
                                <label for="modal_course_code">Course Code *</label>
                                @error('course_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="number"
                                       class="form-control @error('credit_hours') is-invalid @enderror"
                                       id="modal_credit_hours"
                                       name="credit_hours"
                                       value="{{ old('credit_hours') }}"
                                       placeholder="Credit Hours"
                                       min="1"
                                       max="6"
                                       required>
                                <label for="modal_credit_hours">Credit Hours *</label>
                                @error('credit_hours')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text"
                               class="form-control @error('course_name') is-invalid @enderror"
                               id="modal_course_name"
                               name="course_name"
                               value="{{ old('course_name') }}"
                               placeholder="Course Name"
                               required>
                        <label for="modal_course_name">Course Name *</label>
                        @error('course_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text"
                               class="form-control @error('institution_name') is-invalid @enderror"
                               id="modal_institution_name"
                               name="institution_name"
                               value="{{ old('institution_name', Auth::user()->externalLecturer->institution_name ?? '') }}"
                               placeholder="Institution Name"
                               required>
                        <label for="modal_institution_name">Institution Name *</label>
                        @error('institution_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating mb-4">
                        <textarea class="form-control @error('course_description') is-invalid @enderror"
                                  id="modal_course_description"
                                  name="course_description"
                                  placeholder="Course Description"
                                  style="height: 80px">{{ old('course_description') }}</textarea>
                        <label for="modal_course_description">Course Description (Optional)</label>
                        @error('course_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- File Upload -->
                    <div class="form-section-title">
                        <i class="fas fa-file-pdf"></i>
                        Syllabus Document
                    </div>

                    <div class="file-upload-wrapper mb-4">
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
                                   class="file-input @error('syllabus_file') is-invalid @enderror"
                                   id="modal_syllabus_file"
                                   name="syllabus_file"
                                   accept=".pdf"
                                   required>
                        </div>
                        @error('syllabus_file')
                            <div class="text-danger mt-2" style="font-size: 0.875rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submission Guidelines -->
                    <div class="alert-industrial info">
                        <div class="alert-industrial-icon">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <div class="alert-industrial-content">
                            <h6>Submission Guidelines</h6>
                            <ul>
                                <li>Ensure the syllabus includes learning outcomes and course prerequisites</li>
                                <li>Include assessment breakdown and weekly topic coverage</li>
                                <li>Document should be officially formatted and institution-branded</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary-industrial" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i>
                        Cancel
                    </button>
                    <button type="submit" class="btn-primary-industrial">
                        <i class="fas fa-cloud-upload-alt"></i>
                        Upload Syllabus
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
    // Fill form from available courses
    document.querySelectorAll('.fill-course-btn').forEach(button => {
        button.addEventListener('click', function() {
            const courseCode = this.dataset.courseCode;
            const courseName = this.dataset.courseName;
            const creditHours = this.dataset.creditHours;

            document.getElementById('modal_course_code').value = courseCode;
            document.getElementById('modal_course_name').value = courseName;
            document.getElementById('modal_credit_hours').value = creditHours;
        });
    });

    // File upload handling
    const fileInput = document.getElementById('modal_syllabus_file');
    const fileUploadArea = document.getElementById('fileUploadArea');
    const fileUploadText = document.getElementById('fileUploadText');

    if (fileInput) {
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
    }

    // Clear form when modal is hidden
    const uploadModal = document.getElementById('uploadModal');
    if (uploadModal) {
        uploadModal.addEventListener('hidden.bs.modal', function() {
            this.querySelector('form').reset();
            fileUploadArea.classList.remove('has-file');
            fileUploadText.textContent = 'Click to upload or drag and drop';
        });
    }

    // Drag and drop functionality
    if (fileUploadArea) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            fileUploadArea.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            fileUploadArea.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            fileUploadArea.addEventListener(eventName, unhighlight, false);
        });

        function highlight() {
            fileUploadArea.style.borderColor = 'var(--uitm-primary)';
            fileUploadArea.style.background = 'rgba(30, 58, 138, 0.05)';
        }

        function unhighlight() {
            fileUploadArea.style.borderColor = '';
            fileUploadArea.style.background = '';
        }

        fileUploadArea.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files.length > 0 && files[0].type === 'application/pdf') {
                fileInput.files = files;
                fileInput.dispatchEvent(new Event('change'));
            }
        }
    }
});
</script>
@endpush
