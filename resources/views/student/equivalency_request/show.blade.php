@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600;700&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    :root {
        --uitm-primary: #1e3a8a;
        --uitm-primary-dark: #1e293b;
        --uitm-primary-light: #3b82f6;
        --uitm-red: #dc2626;
        --uitm-amber: #f59e0b;
        --uitm-green: #10b981;
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

    body {
        font-family: 'IBM Plex Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        background: var(--neutral-100);
    }

    /* Page Header */
    .page-header {
        position: relative;
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        border-radius: 16px;
        padding: 2rem;
        color: white;
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image:
            linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
        background-size: 32px 32px;
        pointer-events: none;
    }

    .page-header::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(245, 158, 11, 0.15) 0%, transparent 70%);
        pointer-events: none;
    }

    .page-header-content {
        position: relative;
        z-index: 1;
    }

    .page-header .eyebrow {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--uitm-amber);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .page-header .eyebrow::before {
        content: '';
        display: inline-block;
        width: 8px;
        height: 8px;
        background: var(--uitm-amber);
        border-radius: 2px;
    }

    .page-header h1 {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        letter-spacing: -0.025em;
    }

    .page-header .request-id {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.8rem;
        color: rgba(255, 255, 255, 0.7);
        background: rgba(255, 255, 255, 0.1);
        padding: 0.25rem 0.75rem;
        border-radius: 4px;
        display: inline-block;
    }

    .btn-header-back {
        background: rgba(255, 255, 255, 0.15);
        border: 2px solid rgba(255, 255, 255, 0.3);
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

    .btn-header-back:hover {
        background: rgba(255, 255, 255, 0.25);
        border-color: rgba(255, 255, 255, 0.5);
        color: white;
        transform: translateY(-1px);
    }

    /* Status Card */
    .status-card {
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .status-card.pending {
        background: linear-gradient(to right, rgba(245, 158, 11, 0.08) 0%, rgba(245, 158, 11, 0.02) 100%);
        border: 2px solid var(--uitm-amber);
    }

    .status-card.under-review {
        background: linear-gradient(to right, rgba(14, 165, 233, 0.08) 0%, rgba(14, 165, 233, 0.02) 100%);
        border: 2px solid #0ea5e9;
    }

    .status-card.equivalent {
        background: linear-gradient(to right, rgba(16, 185, 129, 0.08) 0%, rgba(16, 185, 129, 0.02) 100%);
        border: 2px solid var(--uitm-green);
    }

    .status-card.not-equivalent {
        background: linear-gradient(to right, rgba(115, 115, 115, 0.08) 0%, rgba(115, 115, 115, 0.02) 100%);
        border: 2px solid var(--neutral-500);
    }

    .status-card-header {
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .status-card-header.pending {
        background: linear-gradient(135deg, var(--uitm-amber) 0%, #d97706 100%);
        color: white;
    }

    .status-card-header.under-review {
        background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
        color: white;
    }

    .status-card-header.equivalent {
        background: linear-gradient(135deg, var(--uitm-green) 0%, #059669 100%);
        color: white;
    }

    .status-card-header.not-equivalent {
        background: linear-gradient(135deg, var(--neutral-500) 0%, var(--neutral-600) 100%);
        color: white;
    }

    .status-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }

    .status-card-header h5 {
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 700;
        font-size: 1rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .status-card-body {
        padding: 1.25rem 1.5rem;
    }

    .status-card-body p {
        font-size: 0.9rem;
        color: var(--neutral-700);
        margin-bottom: 0.5rem;
    }

    .status-card-body strong {
        font-weight: 600;
        color: var(--neutral-800);
    }

    .reviewer-notes-box {
        background: var(--neutral-50);
        border: 1px solid var(--neutral-200);
        border-radius: 8px;
        padding: 1rem;
        margin-top: 0.75rem;
        font-size: 0.9rem;
        color: var(--neutral-700);
    }

    /* Industrial Card */
    .industrial-card {
        background: white;
        border: 2px solid var(--neutral-200);
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .industrial-card-header {
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .industrial-card-header.primary {
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        color: white;
    }

    .industrial-card-header.green {
        background: linear-gradient(135deg, var(--uitm-green) 0%, #059669 100%);
        color: white;
    }

    .industrial-card-header.amber {
        background: linear-gradient(135deg, var(--uitm-amber) 0%, #d97706 100%);
        color: white;
    }

    .industrial-card-header.info {
        background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
        color: white;
    }

    .industrial-card-header.secondary {
        background: linear-gradient(135deg, var(--neutral-600) 0%, var(--neutral-700) 100%);
        color: white;
    }

    .industrial-card-header.dark {
        background: linear-gradient(135deg, var(--neutral-800) 0%, var(--neutral-900) 100%);
        color: white;
    }

    .industrial-card-header-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
    }

    .industrial-card-header h5 {
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.95rem;
        margin: 0;
    }

    .industrial-card-body {
        padding: 1.5rem;
    }

    /* Info Row */
    .info-row {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        margin-bottom: 1rem;
    }

    .info-row:last-child {
        margin-bottom: 0;
    }

    .info-item {
        flex: 1;
        min-width: 200px;
    }

    .info-label {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        color: var(--neutral-500);
        margin-bottom: 0.35rem;
    }

    .info-value {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.95rem;
        color: var(--neutral-800);
    }

    .info-value.mono {
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 600;
        color: var(--uitm-primary);
    }

    /* Justification Box */
    .justification-box {
        background: var(--neutral-50);
        border: 1px solid var(--neutral-200);
        border-radius: 8px;
        padding: 1rem;
        white-space: pre-wrap;
        font-size: 0.9rem;
        color: var(--neutral-700);
        line-height: 1.6;
    }

    /* Approved Equivalency Card */
    .approved-card {
        background: linear-gradient(to right, rgba(16, 185, 129, 0.08) 0%, rgba(16, 185, 129, 0.02) 100%);
        border: 2px solid var(--uitm-green);
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .approved-card-header {
        background: linear-gradient(135deg, var(--uitm-green) 0%, #059669 100%);
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: white;
    }

    .approved-card-body {
        padding: 1.5rem;
    }

    /* Alert Boxes */
    .alert-industrial {
        border-radius: 10px;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        margin-top: 0.75rem;
    }

    .alert-industrial.success {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.12) 0%, rgba(16, 185, 129, 0.05) 100%);
        border: 1px solid var(--uitm-green);
    }

    .alert-industrial.warning {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.12) 0%, rgba(245, 158, 11, 0.05) 100%);
        border: 1px solid var(--uitm-amber);
    }

    .alert-industrial.neutral {
        background: var(--neutral-50);
        border: 1px solid var(--neutral-200);
    }

    .alert-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .alert-icon.success {
        background: var(--uitm-green);
        color: white;
    }

    .alert-icon.warning {
        background: var(--uitm-amber);
        color: white;
    }

    .alert-icon.neutral {
        background: var(--neutral-300);
        color: var(--neutral-600);
    }

    .alert-content {
        flex: 1;
    }

    .alert-content strong {
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.9rem;
        display: block;
        margin-bottom: 0.25rem;
    }

    .alert-content.success strong {
        color: var(--uitm-green);
    }

    .alert-content.warning strong {
        color: var(--uitm-amber);
    }

    .alert-content.neutral strong {
        color: var(--neutral-700);
    }

    .alert-content small {
        font-size: 0.8rem;
        color: var(--neutral-600);
    }

    /* Sidebar Cards */
    .sidebar-card {
        background: white;
        border: 2px solid var(--neutral-200);
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .sidebar-card-header {
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .sidebar-card-header.secondary {
        background: linear-gradient(135deg, var(--neutral-600) 0%, var(--neutral-700) 100%);
        color: white;
    }

    .sidebar-card-header.dark {
        background: linear-gradient(135deg, var(--neutral-800) 0%, var(--neutral-900) 100%);
        color: white;
    }

    .sidebar-card-header-icon {
        width: 28px;
        height: 28px;
        border-radius: 6px;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
    }

    .sidebar-card-header h6 {
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.85rem;
        margin: 0;
    }

    .sidebar-card-body {
        padding: 1.25rem;
    }

    .sidebar-card-body p {
        font-size: 0.85rem;
        color: var(--neutral-700);
        margin-bottom: 0.5rem;
    }

    .sidebar-card-body p:last-child {
        margin-bottom: 0;
    }

    .sidebar-card-body strong {
        font-weight: 600;
        color: var(--neutral-800);
    }

    /* Timeline */
    .timeline {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .timeline-item {
        position: relative;
        padding-left: 2rem;
        padding-bottom: 1.25rem;
    }

    .timeline-item:last-child {
        padding-bottom: 0;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: 0.5rem;
        top: 1.5rem;
        bottom: 0;
        width: 2px;
        background: var(--neutral-200);
    }

    .timeline-item:last-child::before {
        display: none;
    }

    .timeline-icon {
        position: absolute;
        left: 0;
        top: 0;
        width: 1.125rem;
        height: 1.125rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.6rem;
        color: white;
    }

    .timeline-icon.success {
        background: var(--uitm-green);
    }

    .timeline-icon.warning {
        background: var(--uitm-amber);
    }

    .timeline-content strong {
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.85rem;
        color: var(--neutral-800);
        display: block;
        margin-bottom: 0.25rem;
    }

    .timeline-content small {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.75rem;
        color: var(--neutral-500);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
        }

        .page-header h1 {
            font-size: 1.35rem;
        }

        .info-row {
            flex-direction: column;
            gap: 1rem;
        }

        .info-item {
            min-width: 100%;
        }

        .btn-header-back {
            width: 100%;
            justify-content: center;
            margin-top: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <div class="eyebrow">Request Details</div>
                    <h1><i class="fas fa-file-alt me-2"></i>Equivalency Request</h1>
                    <span class="request-id">ID: {{ $request->id }}</span>
                </div>
                <a href="{{ route('student.equivalency.request.index') }}" class="btn-header-back">
                    <i class="fas fa-arrow-left"></i>
                    Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Status Card -->
            @php
                // Determine status based on coordinator_decision first, then fall back to status field
                $coordDecision = $request->coordinator_decision ?? 'pending';

                if ($coordDecision === 'not_equivalent') {
                    $statusClass = 'not-equivalent';
                    $statusLabel = 'REJECTED BY COORDINATOR';
                    $statusIcon = 'times-circle';
                } elseif ($coordDecision === 'equivalent') {
                    $statusClass = 'equivalent';
                    $statusLabel = 'APPROVED - EQUIVALENT';
                    $statusIcon = 'check-circle';
                } elseif ($coordDecision === 'forward_to_rp') {
                    // Check RP decision if forwarded
                    if ($request->status === 'approved') {
                        $statusClass = 'equivalent';
                        $statusLabel = 'APPROVED - EQUIVALENT';
                        $statusIcon = 'check-circle';
                    } elseif ($request->status === 'rejected') {
                        $statusClass = 'not-equivalent';
                        $statusLabel = 'REJECTED BY RESOURCE PERSON';
                        $statusIcon = 'times-circle';
                    } else {
                        $statusClass = 'under-review';
                        $statusLabel = 'UNDER REVIEW BY RESOURCE PERSON';
                        $statusIcon = 'sync';
                    }
                } else {
                    // Pending coordinator review
                    $statusClass = 'pending';
                    $statusLabel = 'PENDING COORDINATOR REVIEW';
                    $statusIcon = 'clock';
                }
            @endphp

            <div class="status-card {{ $statusClass }}">
                <div class="status-card-header {{ $statusClass }}">
                    <div class="status-icon">
                        <i class="fas fa-{{ $statusIcon }}"></i>
                    </div>
                    <h5>
                        Status: {{ $statusLabel }}
                    </h5>
                </div>
                <div class="status-card-body">
                    <p><strong>Submitted:</strong> {{ $request->created_at->format('d F Y, h:i A') }}</p>

                    @if($request->coordinator_decided_at)
                        <p><strong>Coordinator Decision:</strong> {{ $request->coordinator_decided_at->format('d F Y, h:i A') }}</p>
                    @endif

                    @if($request->reviewed_at)
                        <p><strong>Resource Person Review:</strong> {{ $request->reviewed_at->format('d F Y, h:i A') }}</p>
                    @endif

                    @if($request->coordinator_notes && $coordDecision === 'not_equivalent')
                        <p class="mb-2"><strong>Rejection Reason:</strong></p>
                        <div class="reviewer-notes-box">{{ $request->coordinator_notes }}</div>
                    @elseif($request->reviewer_notes)
                        <p class="mb-2"><strong>Reviewer Notes:</strong></p>
                        <div class="reviewer-notes-box">{{ $request->reviewer_notes }}</div>
                    @endif
                </div>
            </div>

            <!-- Diploma Course Information -->
            <div class="industrial-card">
                <div class="industrial-card-header primary">
                    <div class="industrial-card-header-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h5>Diploma Course Information</h5>
                </div>
                <div class="industrial-card-body">
                    <div class="info-row">
                        <div class="info-item">
                            <div class="info-label">Course Code</div>
                            <div class="info-value mono">{{ $request->diploma_course_code }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Course Name</div>
                            <div class="info-value">{{ $request->diploma_course_name }}</div>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-item">
                            <div class="info-label">Institution</div>
                            <div class="info-value">{{ $request->diploma_institution }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Program</div>
                            <div class="info-value">{{ $request->diploma_program }}</div>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-item">
                            <div class="info-label">Credit Hours</div>
                            <div class="info-value mono">{{ $request->diploma_credit_hours }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Suggested Degree Course -->
            <div class="industrial-card">
                <div class="industrial-card-header green">
                    <div class="industrial-card-header-icon">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <h5>Your Suggested Degree Course</h5>
                </div>
                <div class="industrial-card-body">
                    <div class="info-row">
                        <div class="info-item">
                            <div class="info-label">Course Code</div>
                            <div class="info-value mono">{{ $request->suggested_degree_course_code }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Course Name</div>
                            <div class="info-value">{{ $request->suggested_degree_course_name }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Approved Equivalency Result -->
            @if($request->status === 'approved' && $request->approved_degree_course_code)
                <div class="approved-card">
                    <div class="approved-card-header">
                        <div class="industrial-card-header-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h5 style="font-weight: 600; margin: 0;">Approved Equivalency</h5>
                    </div>
                    <div class="approved-card-body">
                        <div class="info-row">
                            <div class="info-item">
                                <div class="info-label">Degree Course Code</div>
                                <div class="info-value mono">{{ $request->approved_degree_course_code }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Degree Course Name</div>
                                <div class="info-value">{{ $request->approved_degree_course_name }}</div>
                            </div>
                        </div>
                        @if($request->match_percentage)
                            <div class="info-row">
                                <div class="info-item">
                                    <div class="info-label">Match Percentage</div>
                                    <div class="info-value mono">{{ $request->match_percentage }}%</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Justification -->
            @if($request->justification)
            <div class="industrial-card">
                <div class="industrial-card-header amber">
                    <div class="industrial-card-header-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h5>Your Justification</h5>
                </div>
                <div class="industrial-card-body">
                    <div class="justification-box">{{ $request->justification }}</div>
                </div>
            </div>
            @endif

            <!-- Decision Details (if rejected by coordinator) -->
            @if($coordDecision === 'not_equivalent' && $request->coordinator_notes)
            <div class="industrial-card" style="border-color: var(--uitm-red);">
                <div class="industrial-card-header" style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); color: white;">
                    <div class="industrial-card-header-icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <h5>Coordinator Decision</h5>
                </div>
                <div class="industrial-card-body">
                    <div class="info-row">
                        <div class="info-item">
                            <div class="info-label">Decision</div>
                            <div class="info-value" style="color: var(--uitm-red); font-weight: 600;">Rejected - Not Equivalent</div>
                        </div>
                        @if($request->coordinator_decided_at)
                        <div class="info-item">
                            <div class="info-label">Decision Date</div>
                            <div class="info-value">{{ $request->coordinator_decided_at->format('d F Y, h:i A') }}</div>
                        </div>
                        @endif
                    </div>
                    <div class="info-row">
                        <div class="info-item" style="flex: 100%;">
                            <div class="info-label">Reason</div>
                            <div class="justification-box" style="background: #fef2f2; border-color: #fecaca;">{{ $request->coordinator_notes }}</div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- External Lecturer Verification - Only show if forwarded to RP or still pending -->
            @if($coordDecision === 'forward_to_rp' || $coordDecision === 'pending')
            <div class="industrial-card">
                <div class="industrial-card-header info">
                    <div class="industrial-card-header-icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <h5>External Lecturer Information</h5>
                </div>
                <div class="industrial-card-body">
                    <div class="info-row">
                        <div class="info-item">
                            <div class="info-label">Lecturer Name</div>
                            <div class="info-value">{{ $request->external_lecturer_name }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Lecturer Email</div>
                            <div class="info-value">{{ $request->external_lecturer_email }}</div>
                        </div>
                    </div>

                    @if($request->syllabus_received_at)
                        <div class="alert-industrial success">
                            <div class="alert-icon success">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="alert-content success">
                                <strong>Official Syllabus Received</strong>
                                <small>Received on: {{ $request->syllabus_received_at->format('d M Y, h:i A') }}</small>
                            </div>
                        </div>
                    @elseif($request->syllabus_request_sent_at)
                        <div class="alert-industrial warning">
                            <div class="alert-icon warning">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="alert-content warning">
                                <strong>Syllabus Request Sent to Lecturer</strong>
                                <small>Sent on: {{ $request->syllabus_request_sent_at->format('d M Y, h:i A') }}</small>
                                <br>
                                <small class="text-muted">The resource person has requested the official syllabus from your lecturer.</small>
                            </div>
                        </div>
                    @elseif($coordDecision === 'forward_to_rp')
                        <div class="alert-industrial neutral">
                            <div class="alert-icon neutral">
                                <i class="fas fa-info"></i>
                            </div>
                            <div class="alert-content neutral">
                                <strong>Forwarded to Resource Person</strong>
                                <small>Your request has been forwarded for expert evaluation. The resource person may contact your lecturer for the official syllabus.</small>
                            </div>
                        </div>
                    @else
                        <div class="alert-industrial neutral">
                            <div class="alert-icon neutral">
                                <i class="fas fa-info"></i>
                            </div>
                            <div class="alert-content neutral">
                                <strong>Awaiting Coordinator Review</strong>
                                <small>Your request is being reviewed by the Program Coordinator.</small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Current Program -->
            <div class="sidebar-card">
                <div class="sidebar-card-header secondary">
                    <div class="sidebar-card-header-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <h6>Your Current Program</h6>
                </div>
                <div class="sidebar-card-body">
                    <p><strong>Program Code:</strong><br><span style="font-family: 'IBM Plex Mono', monospace; color: var(--uitm-primary); font-weight: 600;">{{ $request->current_program_code }}</span></p>
                    <p><strong>Program Name:</strong><br>{{ $request->current_program_name }}</p>
                </div>
            </div>

            <!-- Timeline -->
            <div class="sidebar-card">
                <div class="sidebar-card-header dark">
                    <div class="sidebar-card-header-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h6>Timeline</h6>
                </div>
                <div class="sidebar-card-body">
                    <ul class="timeline">
                        {{-- Step 1: Submitted --}}
                        <li class="timeline-item">
                            <div class="timeline-icon success">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="timeline-content">
                                <strong>Submitted</strong>
                                <small>{{ $request->created_at->format('d M Y, h:i A') }}</small>
                            </div>
                        </li>

                        {{-- Step 2: Coordinator Review --}}
                        @if($request->coordinator_decided_at)
                            <li class="timeline-item">
                                @if($coordDecision === 'not_equivalent')
                                    <div class="timeline-icon" style="background: var(--uitm-red);">
                                        <i class="fas fa-times"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <strong>Rejected by Coordinator</strong>
                                        <small>{{ $request->coordinator_decided_at->format('d M Y, h:i A') }}</small>
                                    </div>
                                @elseif($coordDecision === 'equivalent')
                                    <div class="timeline-icon success">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <strong>Approved by Coordinator</strong>
                                        <small>{{ $request->coordinator_decided_at->format('d M Y, h:i A') }}</small>
                                    </div>
                                @elseif($coordDecision === 'forward_to_rp')
                                    <div class="timeline-icon success">
                                        <i class="fas fa-share"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <strong>Forwarded to Resource Person</strong>
                                        <small>{{ $request->coordinator_decided_at->format('d M Y, h:i A') }}</small>
                                    </div>
                                @endif
                            </li>
                        @else
                            <li class="timeline-item">
                                <div class="timeline-icon warning">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="timeline-content">
                                    <strong>Pending Coordinator Review</strong>
                                    <small>Awaiting Program Coordinator</small>
                                </div>
                            </li>
                        @endif

                        {{-- Step 3: Resource Person Review (only if forwarded) --}}
                        @if($coordDecision === 'forward_to_rp')
                            @if($request->reviewed_at)
                                <li class="timeline-item">
                                    @if($request->status === 'approved')
                                        <div class="timeline-icon success">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <strong>Approved by Resource Person</strong>
                                            <small>{{ $request->reviewed_at->format('d M Y, h:i A') }}</small>
                                        </div>
                                    @elseif($request->status === 'rejected')
                                        <div class="timeline-icon" style="background: var(--uitm-red);">
                                            <i class="fas fa-times"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <strong>Rejected by Resource Person</strong>
                                            <small>{{ $request->reviewed_at->format('d M Y, h:i A') }}</small>
                                        </div>
                                    @else
                                        <div class="timeline-icon success">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <strong>Reviewed</strong>
                                            <small>{{ $request->reviewed_at->format('d M Y, h:i A') }}</small>
                                        </div>
                                    @endif
                                </li>
                            @else
                                <li class="timeline-item">
                                    <div class="timeline-icon warning">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <strong>Pending RP Review</strong>
                                        <small>Awaiting Resource Person</small>
                                    </div>
                                </li>
                            @endif
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
