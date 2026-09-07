@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
    :root {
        --uitm-blue: #1e3a8a;
        --uitm-blue-light: #3b82f6;
        --uitm-amber: #f59e0b;
        --uitm-amber-light: #fbbf24;
        --industrial-dark: #0f172a;
        --industrial-gray: #334155;
        --industrial-light: #f1f5f9;
        --success-green: #059669;
        --danger-red: #dc2626;
        --warning-orange: #ea580c;
    }

    body { font-family: 'IBM Plex Sans', -apple-system, BlinkMacSystemFont, sans-serif; }
    .font-mono { font-family: 'IBM Plex Mono', monospace; }

    /* ── Page Header ── */
    .page-header {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--industrial-dark) 100%);
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
        left: 20%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(59, 130, 246, 0.1) 0%, transparent 70%);
        border-radius: 50%;
    }

    .page-header h1 {
        color: #fff;
        font-weight: 700;
        font-size: 1.75rem;
        margin-bottom: 0.25rem;
        position: relative;
        z-index: 1;
    }

    .page-header .course-subtitle {
        color: rgba(255, 255, 255, 0.65);
        font-size: 0.95rem;
        margin-bottom: 0;
        position: relative;
        z-index: 1;
    }

    .course-code-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.18);
        padding: 0.3rem 0.85rem;
        border-radius: 6px;
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 600;
        font-size: 0.8rem;
        color: var(--uitm-amber-light);
        letter-spacing: 0.04em;
        position: relative;
        z-index: 1;
    }

    .header-badge {
        background: linear-gradient(135deg, var(--uitm-amber) 0%, var(--warning-orange) 100%);
        color: #fff;
        padding: 0.4rem 1rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
        position: relative;
        z-index: 1;
    }

    .btn-back {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #fff;
        padding: 0.5rem 1.1rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        text-decoration: none;
        position: relative;
        z-index: 1;
    }

    .btn-back:hover {
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
        transform: translateX(-3px);
    }

    /* ── Stats Grid ── */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    @media (max-width: 1200px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 576px) { .stats-grid { grid-template-columns: 1fr; } }

    .stat-card {
        background: #fff;
        border-radius: 14px;
        padding: 1.25rem 1.5rem;
        position: relative;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
    }

    .stat-card.total::before { background: linear-gradient(90deg, var(--uitm-blue), var(--uitm-blue-light)); }
    .stat-card.verified::before { background: linear-gradient(90deg, var(--success-green), #10b981); }
    .stat-card.invalid::before { background: linear-gradient(90deg, var(--danger-red), #ef4444); }
    .stat-card.credits::before { background: linear-gradient(90deg, var(--uitm-amber), var(--uitm-amber-light)); }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-bottom: 0.75rem;
    }

    .stat-card.total .stat-icon { background: rgba(30, 58, 138, 0.1); color: var(--uitm-blue); }
    .stat-card.verified .stat-icon { background: rgba(5, 150, 105, 0.1); color: var(--success-green); }
    .stat-card.invalid .stat-icon { background: rgba(220, 38, 38, 0.1); color: var(--danger-red); }
    .stat-card.credits .stat-icon { background: rgba(245, 158, 11, 0.1); color: var(--uitm-amber); }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 0.2rem;
    }

    .stat-card.total .stat-value { color: var(--uitm-blue); }
    .stat-card.verified .stat-value { color: var(--success-green); }
    .stat-card.invalid .stat-value { color: var(--danger-red); }
    .stat-card.credits .stat-value { color: var(--uitm-amber); }

    .stat-label {
        color: #64748b;
        font-size: 0.8125rem;
        font-weight: 500;
    }

    /* ── Alerts ── */
    .alert-industrial {
        border-radius: 12px;
        border: none;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .alert-industrial.success {
        background: linear-gradient(135deg, rgba(5, 150, 105, 0.1) 0%, rgba(16, 185, 129, 0.05) 100%);
        border-left: 4px solid var(--success-green);
        color: #065f46;
    }

    .alert-industrial.danger {
        background: linear-gradient(135deg, rgba(220, 38, 38, 0.1) 0%, rgba(239, 68, 68, 0.05) 100%);
        border-left: 4px solid var(--danger-red);
        color: #991b1b;
    }

    .alert-industrial.warning {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(251, 191, 36, 0.05) 100%);
        border-left: 4px solid var(--uitm-amber);
        color: #92400e;
    }

    .alert-industrial.info {
        background: linear-gradient(135deg, rgba(30, 58, 138, 0.08) 0%, rgba(59, 130, 246, 0.04) 100%);
        border-left: 4px solid var(--uitm-blue);
        color: #1e40af;
    }

    .alert-industrial .alert-actions {
        margin-left: auto;
        flex-shrink: 0;
    }

    .btn-reject-invalid {
        background: linear-gradient(135deg, var(--danger-red) 0%, #ef4444 100%);
        color: #fff;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.8rem;
        transition: all 0.2s ease;
        box-shadow: 0 3px 10px rgba(220, 38, 38, 0.2);
    }

    .btn-reject-invalid:hover {
        transform: translateY(-1px);
        box-shadow: 0 5px 15px rgba(220, 38, 38, 0.3);
        color: #fff;
    }

    /* ── Students Card ── */
    .students-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        margin-bottom: 1.5rem;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .students-card:hover {
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
    }

    .students-header {
        background: linear-gradient(135deg, var(--industrial-dark) 0%, var(--uitm-blue) 100%);
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .students-header i { color: var(--uitm-amber); font-size: 1.1rem; }

    .students-header h5 {
        margin: 0;
        color: #fff;
        font-weight: 600;
        font-size: 0.95rem;
    }

    /* ── Data Table ── */
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table thead th {
        background: var(--industrial-light);
        padding: 0.875rem 1.25rem;
        text-align: left;
        font-weight: 600;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #64748b;
        border-bottom: 1px solid #e2e8f0;
    }

    .data-table tbody td {
        padding: 0.875rem 1.25rem;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        font-size: 0.875rem;
    }

    .data-table tbody tr:last-child td { border-bottom: none; }
    .data-table tbody tr:hover { background: rgba(30, 58, 138, 0.02); }
    .data-table tbody tr.row-invalid { background: rgba(220, 38, 38, 0.04); }
    .data-table tbody tr.row-invalid:hover { background: rgba(220, 38, 38, 0.07); }

    .student-name {
        font-weight: 600;
        color: var(--industrial-dark);
        font-size: 0.875rem;
    }

    .validation-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.3rem 0.65rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.7rem;
        letter-spacing: 0.02em;
    }

    .validation-badge.verified {
        background: rgba(5, 150, 105, 0.12);
        color: var(--success-green);
    }

    .validation-badge.not-in-transcript {
        background: rgba(220, 38, 38, 0.12);
        color: var(--danger-red);
    }

    .validation-badge.no-application {
        background: rgba(245, 158, 11, 0.12);
        color: #b45309;
    }

    .validation-badge.unknown {
        background: var(--industrial-light);
        color: var(--industrial-gray);
    }

    .grade-chip {
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 600;
        font-size: 0.75rem;
        color: #64748b;
        margin-top: 0.25rem;
    }

    .program-tag {
        background: linear-gradient(135deg, rgba(30, 58, 138, 0.1) 0%, rgba(59, 130, 246, 0.05) 100%);
        color: var(--uitm-blue);
        padding: 0.3rem 0.65rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.7rem;
        font-family: 'IBM Plex Mono', monospace;
        letter-spacing: 0.02em;
    }

    .lecturer-name {
        font-weight: 600;
        color: var(--industrial-dark);
        font-size: 0.8125rem;
    }

    .lecturer-email {
        color: #94a3b8;
        font-size: 0.75rem;
    }

    .degree-code {
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 600;
        color: var(--uitm-blue);
        font-size: 0.8125rem;
    }

    .degree-name {
        color: #64748b;
        font-size: 0.75rem;
        line-height: 1.3;
    }

    .date-text {
        color: #94a3b8;
        font-size: 0.75rem;
    }

    .invalid-warning {
        color: var(--danger-red);
        font-size: 0.7rem;
        font-weight: 500;
        margin-top: 0.2rem;
    }

    /* ── Decision Card ── */
    .decision-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .decision-header {
        background: linear-gradient(135deg, var(--industrial-light) 0%, #fff 100%);
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .decision-header i { color: var(--uitm-amber); font-size: 1.1rem; }

    .decision-header h5 {
        margin: 0;
        font-weight: 600;
        color: var(--industrial-dark);
        font-size: 0.95rem;
    }

    .decision-body { padding: 1.5rem; }

    .instructions-block {
        background: linear-gradient(135deg, rgba(30, 58, 138, 0.06) 0%, rgba(59, 130, 246, 0.02) 100%);
        border-left: 3px solid var(--uitm-blue);
        border-radius: 0 10px 10px 0;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
    }

    .instructions-block .instructions-title {
        font-weight: 700;
        color: var(--uitm-blue);
        font-size: 0.8125rem;
        margin-bottom: 0.5rem;
    }

    .instructions-block ul {
        margin: 0;
        padding-left: 1.25rem;
        font-size: 0.8125rem;
        color: var(--industrial-gray);
        line-height: 1.7;
    }

    .instructions-block ul strong { color: var(--industrial-dark); }

    /* Decision Buttons */
    .decision-actions {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }

    @media (max-width: 768px) { .decision-actions { grid-template-columns: 1fr; } }

    .btn-decision {
        padding: 0.75rem 1.25rem;
        border-radius: 10px;
        border: none;
        font-weight: 600;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: all 0.25s ease;
        cursor: pointer;
        width: 100%;
    }

    .btn-decision:disabled {
        opacity: 0.45;
        cursor: not-allowed;
        transform: none !important;
        box-shadow: none !important;
    }

    .btn-decision.approve {
        background: linear-gradient(135deg, var(--success-green) 0%, #10b981 100%);
        color: #fff;
        box-shadow: 0 4px 15px rgba(5, 150, 105, 0.25);
    }

    .btn-decision.approve:not(:disabled):hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(5, 150, 105, 0.35);
    }

    .btn-decision.reject {
        background: linear-gradient(135deg, var(--danger-red) 0%, #ef4444 100%);
        color: #fff;
        box-shadow: 0 4px 15px rgba(220, 38, 38, 0.25);
    }

    .btn-decision.reject:not(:disabled):hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(220, 38, 38, 0.35);
    }

    .btn-decision.forward {
        background: linear-gradient(135deg, var(--uitm-amber) 0%, var(--warning-orange) 100%);
        color: #fff;
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.25);
    }

    .btn-decision.forward:not(:disabled):hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(245, 158, 11, 0.35);
    }

    /* ── Modals ── */
    .modal-content {
        border: none;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 25px 60px rgba(0, 0, 0, 0.15);
    }

    .modal-header {
        padding: 1.25rem 1.5rem;
        border-bottom: none;
    }

    .modal-header.header-success {
        background: linear-gradient(135deg, var(--success-green) 0%, #10b981 100%);
        color: #fff;
    }

    .modal-header.header-danger {
        background: linear-gradient(135deg, var(--danger-red) 0%, #ef4444 100%);
        color: #fff;
    }

    .modal-header.header-warning {
        background: linear-gradient(135deg, var(--uitm-amber) 0%, var(--warning-orange) 100%);
        color: #fff;
    }

    .modal-title {
        font-weight: 700;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-body p {
        color: var(--industrial-gray);
        font-size: 0.875rem;
        margin-bottom: 1.25rem;
    }

    .modal-body .form-label {
        font-weight: 600;
        font-size: 0.8125rem;
        color: var(--industrial-dark);
        margin-bottom: 0.4rem;
    }

    .modal-body .form-control,
    .modal-body .form-select {
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 0.6rem 0.85rem;
        font-size: 0.875rem;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .modal-body .form-control:focus,
    .modal-body .form-select:focus {
        border-color: var(--uitm-blue-light);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .modal-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid #f1f5f9;
        gap: 0.5rem;
    }

    .modal-footer .btn {
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.8125rem;
        padding: 0.5rem 1.25rem;
    }

    .modal-alert {
        border-radius: 10px;
        border: none;
        padding: 0.875rem 1rem;
        font-size: 0.8125rem;
    }

    .modal-alert.alert-warning {
        background: rgba(245, 158, 11, 0.1);
        border-left: 3px solid var(--uitm-amber);
        color: #92400e;
    }

    .modal-alert.alert-info {
        background: rgba(30, 58, 138, 0.06);
        border-left: 3px solid var(--uitm-blue);
        color: #1e40af;
    }

    /* ── Custom checkbox ── */
    .form-check-input {
        border-radius: 4px;
        border: 2px solid #cbd5e1;
        cursor: pointer;
    }

    .form-check-input:checked {
        background-color: var(--uitm-blue);
        border-color: var(--uitm-blue);
    }

    .form-check-input:focus {
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.15);
        border-color: var(--uitm-blue-light);
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">

    @php
        $verifiedCount = $requests->filter(fn($r) => isset($r->transcript_validation['status']) && $r->transcript_validation['status'] === 'verified')->count();
        $notInTranscriptCount = $requests->filter(fn($r) => isset($r->transcript_validation['status']) && $r->transcript_validation['status'] === 'not_in_transcript')->count();
        $noApplicationCount = $requests->filter(fn($r) => isset($r->transcript_validation['status']) && $r->transcript_validation['status'] === 'no_application')->count();
        $invalidRequestIds = $requests->filter(fn($r) => isset($r->transcript_validation['status']) && $r->transcript_validation['status'] === 'not_in_transcript')->pluck('id')->toArray();
    @endphp

    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <div class="d-flex align-items-center gap-3 mb-2">
                    <h1><i class="fas fa-book me-2"></i>{{ $diplomaCourseCode }}</h1>
                    <span class="header-badge">
                        <i class="fas fa-users"></i>
                        {{ $requests->count() }} Request{{ $requests->count() !== 1 ? 's' : '' }}
                    </span>
                </div>
                <p class="course-subtitle">{{ $requests->first()->diploma_course_name }}</p>
            </div>
            <a href="{{ route('program_coordinator.dashboard') }}" class="btn-back">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card total">
            <div class="stat-icon">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <div class="stat-value">{{ $requests->count() }}</div>
            <div class="stat-label">Total Requests</div>
        </div>

        <div class="stat-card verified">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-value">{{ $verifiedCount }}</div>
            <div class="stat-label">Verified</div>
        </div>

        <div class="stat-card invalid">
            <div class="stat-icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <div class="stat-value">{{ $notInTranscriptCount }}</div>
            <div class="stat-label">Not in Transcript</div>
        </div>

        <div class="stat-card credits">
            <div class="stat-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="stat-value">{{ $requests->first()->diploma_credit_hours }}</div>
            <div class="stat-label">Credit Hours</div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert-industrial success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="alert-industrial danger">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Validation Warning -->
    @if($notInTranscriptCount > 0)
        <div class="alert-industrial danger">
            <div>
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Warning:</strong> {{ $notInTranscriptCount }} request(s) are from students who <strong>never took this course</strong>.
                These should be rejected.
            </div>
            <div class="alert-actions">
                <form action="{{ route('program_coordinator.reject_not_in_transcript') }}" method="POST" class="d-inline">
                    @csrf
                    @foreach($invalidRequestIds as $invalidId)
                        <input type="hidden" name="request_ids[]" value="{{ $invalidId }}">
                    @endforeach
                    <button type="submit" class="btn-reject-invalid"
                            onclick="return confirm('Reject {{ $notInTranscriptCount }} invalid request(s)?')">
                        <i class="fas fa-ban me-1"></i>Reject Invalid ({{ $notInTranscriptCount }})
                    </button>
                </form>
            </div>
        </div>
    @endif

    <!-- Students List -->
    <div class="students-card">
        <div class="students-header">
            <i class="fas fa-users"></i>
            <h5>Students Requesting This Course</h5>
        </div>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 40px;">
                            <input type="checkbox" id="selectAll" class="form-check-input">
                        </th>
                        <th>Student</th>
                        <th>Validation</th>
                        <th>Program</th>
                        <th>Lecturer Contact</th>
                        <th>Suggested Degree Course</th>
                        <th>Submitted</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $request)
                        @php
                            $validation = $request->transcript_validation ?? ['status' => 'unknown', 'message' => 'Unknown'];
                            $isInvalid = $validation['status'] === 'not_in_transcript';
                            $isVerified = $validation['status'] === 'verified';
                            $noApp = $validation['status'] === 'no_application';
                        @endphp
                        <tr class="{{ $isInvalid ? 'row-invalid' : '' }}">
                            <td>
                                <input type="checkbox" name="request_ids[]" value="{{ $request->id }}"
                                       class="form-check-input request-checkbox {{ $isInvalid ? 'invalid-request' : '' }}"
                                       data-valid="{{ $isVerified ? 'true' : 'false' }}">
                            </td>
                            <td>
                                <span class="student-name">{{ $request->student->user->name }}</span>
                            </td>
                            <td>
                                @if($isVerified)
                                    <span class="validation-badge verified" title="{{ $validation['message'] }}">
                                        <i class="fas fa-check-circle"></i> Verified
                                    </span>
                                    @if(isset($validation['subject']))
                                        <div class="grade-chip">Grade: {{ $validation['subject']['grade'] }}</div>
                                    @endif
                                @elseif($isInvalid)
                                    <span class="validation-badge not-in-transcript" title="{{ $validation['message'] }}">
                                        <i class="fas fa-times-circle"></i> Not in Transcript
                                    </span>
                                    <div class="invalid-warning">
                                        <i class="fas fa-exclamation-triangle"></i> Student never took this course
                                    </div>
                                @elseif($noApp)
                                    <span class="validation-badge no-application" title="{{ $validation['message'] }}">
                                        <i class="fas fa-question-circle"></i> No Application
                                    </span>
                                    <div class="grade-chip">Cannot verify</div>
                                @else
                                    <span class="validation-badge unknown">Unknown</span>
                                @endif
                            </td>
                            <td>
                                <span class="program-tag">{{ $request->current_program_code }}</span>
                            </td>
                            <td>
                                <div class="lecturer-name">{{ $request->external_lecturer_name }}</div>
                                <div class="lecturer-email">{{ $request->external_lecturer_email }}</div>
                            </td>
                            <td>
                                <div class="degree-code">{{ $request->suggested_degree_course_code }}</div>
                                <div class="degree-name">{{ $request->suggested_degree_course_name }}</div>
                            </td>
                            <td>
                                <span class="date-text">{{ $request->created_at->format('d M Y') }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Decision Interface -->
    <div class="decision-card">
        <div class="decision-header">
            <i class="fas fa-gavel"></i>
            <h5>Make Decision</h5>
        </div>
        <div class="decision-body">
            <div class="instructions-block">
                <div class="instructions-title"><i class="fas fa-info-circle me-1"></i> Instructions</div>
                <ul>
                    <li><strong>Mark as Equivalent:</strong> If this course is in your Excel spreadsheet and matches a degree course</li>
                    <li><strong>Mark as Not Equivalent:</strong> If this course is in your Excel spreadsheet but has no match</li>
                    <li><strong>Forward to Resource Person:</strong> If this course is NOT in your spreadsheet, select a lecturer and forward for detailed review</li>
                </ul>
            </div>

            <div class="decision-actions">
                <button type="button" class="btn-decision approve" data-bs-toggle="modal" data-bs-target="#equivalentModal" id="btnEquivalent" disabled>
                    <i class="fas fa-check-circle"></i> Mark as Equivalent
                </button>
                <button type="button" class="btn-decision reject" data-bs-toggle="modal" data-bs-target="#notEquivalentModal" id="btnNotEquivalent" disabled>
                    <i class="fas fa-times-circle"></i> Mark as Not Equivalent
                </button>
                <button type="button" class="btn-decision forward" data-bs-toggle="modal" data-bs-target="#forwardModal" id="btnForward" disabled>
                    <i class="fas fa-share"></i> Forward to RP
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Equivalent Modal -->
<div class="modal fade" id="equivalentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('program_coordinator.make_decision') }}" method="POST">
                @csrf
                <input type="hidden" name="decision" value="equivalent">
                <div id="equivalentRequestIds"></div>

                <div class="modal-header header-success">
                    <h5 class="modal-title"><i class="fas fa-check-circle"></i> Mark as Equivalent</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>You are approving this course as equivalent to a degree course. Please provide the equivalent degree course details:</p>

                    <div class="mb-3">
                        <label class="form-label">Degree Course Code <span class="text-danger">*</span></label>
                        <input type="text" name="degree_course_code" class="form-control" placeholder="e.g., CS132" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Degree Course Name <span class="text-danger">*</span></label>
                        <input type="text" name="degree_course_name" class="form-control" placeholder="e.g., COMPUTER ARCHITECTURE" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Match Percentage <span class="text-danger">*</span></label>
                        <input type="number" name="match_percentage" class="form-control" min="0" max="100" value="100" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes (Optional)</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Add any additional comments..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-1"></i>Approve as Equivalent
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Not Equivalent Modal -->
<div class="modal fade" id="notEquivalentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('program_coordinator.make_decision') }}" method="POST">
                @csrf
                <input type="hidden" name="decision" value="not_equivalent">
                <div id="notEquivalentRequestIds"></div>

                <div class="modal-header header-danger">
                    <h5 class="modal-title"><i class="fas fa-times-circle"></i> Mark as Not Equivalent</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>You are rejecting this course as not equivalent to any degree course in your program.</p>

                    <div class="mb-3">
                        <label class="form-label">Reason for Rejection (Optional)</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Explain why this course is not equivalent..."></textarea>
                    </div>

                    <div class="modal-alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Note:</strong> Selected students will be notified that their request has been rejected.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times me-1"></i>Reject Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Forward to RP Modal -->
<div class="modal fade" id="forwardModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('program_coordinator.forward_to_rp') }}" method="POST">
                @csrf
                <div id="forwardRequestIds"></div>

                <div class="modal-header header-warning">
                    <h5 class="modal-title"><i class="fas fa-share"></i> Forward to Resource Person</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Select which lecturer contact to use for syllabus verification:</p>

                    <div class="mb-3">
                        <label class="form-label">Select Lecturer <span class="text-danger">*</span></label>
                        <select class="form-select" id="lecturerSelect" required>
                            <option value="">Choose lecturer...</option>
                            @foreach($requests->unique('external_lecturer_email') as $request)
                                <option value="{{ $request->external_lecturer_email }}"
                                        data-name="{{ $request->external_lecturer_name }}">
                                    {{ $request->external_lecturer_name }} ({{ $request->external_lecturer_email }})
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="selected_lecturer_name" id="selectedLecturerName">
                        <input type="hidden" name="selected_lecturer_email" id="selectedLecturerEmail">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes for Resource Person (Optional)</label>
                        <textarea name="notes" class="form-control" rows="3"
                                  placeholder="Add any context or special instructions..."></textarea>
                    </div>

                    <div class="modal-alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Note:</strong> The Resource Person will review the course and request the official syllabus from the selected lecturer.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-share me-1"></i>Forward to RP
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.request-checkbox');
    const btnEquivalent = document.getElementById('btnEquivalent');
    const btnNotEquivalent = document.getElementById('btnNotEquivalent');
    const btnForward = document.getElementById('btnForward');

    selectAll.addEventListener('change', function() {
        checkboxes.forEach(cb => cb.checked = this.checked);
        updateButtons();
    });

    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateButtons);
    });

    function updateButtons() {
        const checked = document.querySelectorAll('.request-checkbox:checked');
        const checkedCount = checked.length;
        const disabled = checkedCount === 0;

        const invalidSelected = Array.from(checked).filter(cb => cb.classList.contains('invalid-request')).length;
        const validSelected = checkedCount - invalidSelected;

        btnEquivalent.disabled = disabled || validSelected === 0;
        btnNotEquivalent.disabled = disabled;
        btnForward.disabled = disabled || validSelected === 0;

        if (invalidSelected > 0 && validSelected > 0) {
            btnEquivalent.innerHTML = '<i class="fas fa-check-circle"></i> Mark as Equivalent (' + validSelected + ' valid)';
            btnForward.innerHTML = '<i class="fas fa-share"></i> Forward to RP (' + validSelected + ' valid)';
        } else {
            btnEquivalent.innerHTML = '<i class="fas fa-check-circle"></i> Mark as Equivalent';
            btnForward.innerHTML = '<i class="fas fa-share"></i> Forward to RP';
        }

        if (invalidSelected > 0 && validSelected === 0) {
            btnEquivalent.title = 'Cannot approve requests for courses not in transcript';
            btnForward.title = 'Cannot forward requests for courses not in transcript';
        } else {
            btnEquivalent.title = '';
            btnForward.title = '';
        }
    }

    document.getElementById('equivalentModal').addEventListener('show.bs.modal', function() {
        const invalidSelected = Array.from(document.querySelectorAll('.request-checkbox:checked'))
            .filter(cb => cb.classList.contains('invalid-request')).length;

        if (invalidSelected > 0) {
            alert('Note: ' + invalidSelected + ' invalid request(s) (course not in transcript) will be excluded from this action.');
        }

        updateModalInputs('equivalentRequestIds', true);
    });

    document.getElementById('notEquivalentModal').addEventListener('show.bs.modal', function() {
        updateModalInputs('notEquivalentRequestIds', false);
    });

    document.getElementById('forwardModal').addEventListener('show.bs.modal', function() {
        const invalidSelected = Array.from(document.querySelectorAll('.request-checkbox:checked'))
            .filter(cb => cb.classList.contains('invalid-request')).length;

        if (invalidSelected > 0) {
            alert('Note: ' + invalidSelected + ' invalid request(s) (course not in transcript) will be excluded from this action.');
        }

        updateModalInputs('forwardRequestIds', true);
    });

    function updateModalInputs(containerId, excludeInvalid) {
        const container = document.getElementById(containerId);
        container.innerHTML = '';

        document.querySelectorAll('.request-checkbox:checked').forEach(cb => {
            if (excludeInvalid && cb.classList.contains('invalid-request')) {
                return;
            }

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'request_ids[]';
            input.value = cb.value;
            container.appendChild(input);
        });
    }

    const lecturerSelect = document.getElementById('lecturerSelect');
    if (lecturerSelect) {
        lecturerSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            document.getElementById('selectedLecturerEmail').value = this.value;
            document.getElementById('selectedLecturerName').value = selectedOption.dataset.name || '';
        });
    }
});
</script>
@endpush
@endsection
