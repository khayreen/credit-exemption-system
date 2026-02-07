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
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--industrial-dark) 100%);
        border-radius: 0 0 24px 24px;
        padding: 2rem 2.5rem;
        margin: -1.5rem -1.5rem 2rem -1.5rem;
        color: white;
    }

    .page-header h2 {
        font-weight: 700;
        font-size: 1.75rem;
        margin-bottom: 0.25rem;
    }

    .breadcrumb {
        background: transparent;
        margin-bottom: 0.75rem;
    }

    .breadcrumb-item a {
        color: rgba(255,255,255,0.7);
        text-decoration: none;
    }

    .breadcrumb-item a:hover {
        color: var(--uitm-amber);
    }

    .breadcrumb-item.active {
        color: rgba(255,255,255,0.9);
    }

    .breadcrumb-item + .breadcrumb-item::before {
        color: rgba(255,255,255,0.5);
    }

    .btn-back {
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.3);
        color: white;
        padding: 0.5rem 1.25rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn-back:hover {
        background: rgba(255,255,255,0.25);
        color: white;
        transform: translateX(-3px);
    }

    .industrial-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .industrial-card-header {
        background: linear-gradient(135deg, var(--industrial-light) 0%, #e2e8f0 100%);
        padding: 1rem 1.5rem;
        border-bottom: 2px solid var(--uitm-blue);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .industrial-card-header h5 {
        margin: 0;
        font-weight: 600;
        color: var(--industrial-dark);
        font-size: 1rem;
    }

    .industrial-card-header .header-icon {
        width: 36px;
        height: 36px;
        background: var(--uitm-blue);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    .industrial-card-body {
        padding: 1.5rem;
    }

    .data-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--industrial-gray);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.35rem;
    }

    .data-value {
        font-size: 0.95rem;
        color: var(--industrial-dark);
        margin-bottom: 1rem;
    }

    .data-value.course-code {
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 600;
        font-size: 1.1rem;
        color: var(--uitm-blue);
    }

    .program-badge {
        display: inline-block;
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--uitm-blue-light) 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .decision-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .decision-badge.approved {
        background: linear-gradient(135deg, var(--success) 0%, #10b981 100%);
        color: white;
    }

    .decision-badge.rejected {
        background: linear-gradient(135deg, var(--danger) 0%, #ef4444 100%);
        color: white;
    }

    .decision-badge.forwarded {
        background: linear-gradient(135deg, var(--info) 0%, #14b8a6 100%);
        color: white;
    }

    .decision-badge.pending {
        background: linear-gradient(135deg, var(--warning) 0%, #f97316 100%);
        color: white;
    }

    .progress-industrial {
        height: 24px;
        background: var(--industrial-light);
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }

    .progress-industrial .progress-bar {
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 600;
        font-size: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .notes-box {
        background: linear-gradient(135deg, var(--industrial-light) 0%, #e2e8f0 100%);
        border-left: 4px solid var(--uitm-blue);
        padding: 1rem 1.25rem;
        border-radius: 0 12px 12px 0;
        font-size: 0.9rem;
        color: var(--industrial-gray);
    }

    .validation-status {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
    }

    .validation-status.verified {
        background: rgba(5, 150, 105, 0.1);
        border: 1px solid var(--success);
        color: var(--success);
    }

    .validation-status.not-verified {
        background: rgba(220, 38, 38, 0.1);
        border: 1px solid var(--danger);
        color: var(--danger);
    }

    .validation-status.warning {
        background: rgba(234, 88, 12, 0.1);
        border: 1px solid var(--warning);
        color: var(--warning);
    }

    .transcript-info {
        background: var(--industrial-light);
        border-radius: 12px;
        padding: 1rem 1.25rem;
    }

    .transcript-info-item {
        text-align: center;
    }

    .transcript-info-item .label {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--industrial-gray);
        margin-bottom: 0.25rem;
    }

    .transcript-info-item .value {
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 600;
        font-size: 0.95rem;
        color: var(--industrial-dark);
    }

    /* Sidebar Cards */
    .sidebar-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .sidebar-card-header {
        background: linear-gradient(135deg, var(--industrial-light) 0%, #e2e8f0 100%);
        padding: 1rem 1.25rem;
        border-bottom: 2px solid var(--uitm-blue);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .sidebar-card-header h5 {
        margin: 0;
        font-weight: 600;
        color: var(--industrial-dark);
        font-size: 0.9rem;
    }

    .sidebar-card-header i {
        color: var(--uitm-blue);
    }

    .sidebar-card-body {
        padding: 1.25rem;
    }

    .student-avatar {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--uitm-blue-light) 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.75rem;
        margin: 0 auto 1rem;
        box-shadow: 0 4px 15px rgba(30, 58, 138, 0.3);
    }

    .student-name {
        font-weight: 600;
        font-size: 1.1rem;
        color: var(--industrial-dark);
        text-align: center;
        margin-bottom: 0.25rem;
    }

    .student-matric {
        font-family: 'IBM Plex Mono', monospace;
        color: var(--industrial-gray);
        text-align: center;
        margin-bottom: 1rem;
    }

    .sidebar-info-row {
        margin-bottom: 0.75rem;
    }

    .sidebar-info-row .label {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--industrial-gray);
        margin-bottom: 0.15rem;
    }

    .sidebar-info-row .value {
        font-size: 0.9rem;
        color: var(--industrial-dark);
    }

    /* Timeline */
    .timeline-industrial {
        position: relative;
        padding-left: 28px;
    }

    .timeline-industrial::before {
        content: '';
        position: absolute;
        left: 7px;
        top: 5px;
        bottom: 5px;
        width: 2px;
        background: linear-gradient(to bottom, var(--uitm-blue), var(--industrial-light));
    }

    .timeline-item {
        position: relative;
        padding-bottom: 1.25rem;
    }

    .timeline-item:last-child {
        padding-bottom: 0;
    }

    .timeline-marker {
        position: absolute;
        left: -24px;
        top: 2px;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        border: 3px solid white;
        box-shadow: 0 0 0 2px currentColor, 0 2px 8px rgba(0,0,0,0.15);
    }

    .timeline-marker.primary {
        background: var(--uitm-blue);
        color: var(--uitm-blue);
    }

    .timeline-marker.success {
        background: var(--success);
        color: var(--success);
    }

    .timeline-marker.danger {
        background: var(--danger);
        color: var(--danger);
    }

    .timeline-marker.info {
        background: var(--info);
        color: var(--info);
    }

    .timeline-date {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.75rem;
        color: var(--industrial-gray);
        margin-bottom: 0.15rem;
    }

    .timeline-text {
        font-size: 0.85rem;
        color: var(--industrial-dark);
    }

    .divider {
        height: 1px;
        background: linear-gradient(to right, transparent, #e2e8f0, transparent);
        margin: 1rem 0;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('program_coordinator.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('program_coordinator.history') }}">Decision History</a></li>
                        <li class="breadcrumb-item active">Request Details</li>
                    </ol>
                </nav>
                <h2><i class="fas fa-file-alt me-2"></i>Request Details</h2>
            </div>
            <div>
                <a href="{{ route('program_coordinator.history') }}" class="btn btn-back">
                    <i class="fas fa-arrow-left me-2"></i>Back to History
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Course Information -->
            <div class="industrial-card">
                <div class="industrial-card-header">
                    <div class="header-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <h5>Course Information</h5>
                </div>
                <div class="industrial-card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="data-label">Diploma Course</div>
                            <div class="data-value course-code">{{ $request->diploma_course_code }}</div>
                            <div class="data-value" style="margin-top: -0.75rem;">{{ $request->diploma_course_name }}</div>

                            <div class="data-label">Credit Hours</div>
                            <div class="data-value">
                                <span class="badge bg-secondary px-3 py-2" style="font-family: 'IBM Plex Mono', monospace;">
                                    {{ $request->diploma_credit_hours ?? 'N/A' }} Credits
                                </span>
                            </div>

                            <div class="data-label">Source Institution</div>
                            <div class="data-value">{{ $request->diploma_institution }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="data-label">Suggested Degree Course</div>
                            <div class="data-value course-code">{{ $request->suggested_degree_course_code ?? 'Not specified' }}</div>
                            <div class="data-value" style="margin-top: -0.75rem;">{{ $request->suggested_degree_course_name ?? '' }}</div>

                            <div class="data-label">Target Program</div>
                            <div class="data-value">
                                <span class="program-badge">{{ $request->current_program_code }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Decision Information -->
            <div class="industrial-card">
                <div class="industrial-card-header">
                    <div class="header-icon">
                        <i class="fas fa-gavel"></i>
                    </div>
                    <h5>Decision Information</h5>
                </div>
                <div class="industrial-card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="data-label">Decision</div>
                            <div class="data-value">
                                @if($request->coordinator_decision === 'equivalent')
                                    <span class="decision-badge approved">
                                        <i class="fas fa-check-circle"></i>Approved as Equivalent
                                    </span>
                                @elseif($request->coordinator_decision === 'not_equivalent')
                                    <span class="decision-badge rejected">
                                        <i class="fas fa-times-circle"></i>Rejected - Not Equivalent
                                    </span>
                                @elseif($request->coordinator_decision === 'forward_to_rp')
                                    <span class="decision-badge forwarded">
                                        <i class="fas fa-share"></i>Forwarded to Resource Person
                                    </span>
                                @else
                                    <span class="decision-badge pending">
                                        <i class="fas fa-clock"></i>Pending
                                    </span>
                                @endif
                            </div>

                            <div class="data-label">Decision Date</div>
                            <div class="data-value">
                                @if($request->coordinator_decided_at)
                                    <span style="font-family: 'IBM Plex Mono', monospace;">
                                        {{ $request->coordinator_decided_at->format('d M Y, H:i') }}
                                    </span>
                                    <br><small class="text-muted">{{ $request->coordinator_decided_at->diffForHumans() }}</small>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            @if($request->coordinator_decision === 'equivalent')
                                <div class="data-label">Approved Degree Course</div>
                                <div class="data-value course-code">{{ $request->approved_degree_course_code ?? 'N/A' }}</div>
                                <div class="data-value" style="margin-top: -0.75rem;">{{ $request->approved_degree_course_name ?? '' }}</div>

                                <div class="data-label">Match Percentage</div>
                                <div class="data-value">
                                    <div class="progress-industrial">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $request->match_percentage ?? 0 }}%">
                                            {{ $request->match_percentage ?? 0 }}%
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($request->coordinator_decision === 'forward_to_rp')
                                <div class="data-label">Selected Lecturer</div>
                                <div class="data-value">
                                    <strong>{{ $request->selected_lecturer_name ?? 'N/A' }}</strong>
                                </div>
                                <div class="data-value text-muted" style="margin-top: -0.75rem;">
                                    {{ $request->selected_lecturer_email ?? '' }}
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($request->coordinator_notes)
                        <div class="divider"></div>
                        <div class="data-label">Decision Notes</div>
                        <div class="notes-box">
                            {{ $request->coordinator_notes }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Transcript Validation -->
            @if(isset($request->transcript_validation))
                <div class="industrial-card">
                    <div class="industrial-card-header">
                        <div class="header-icon">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <h5>Transcript Validation</h5>
                    </div>
                    <div class="industrial-card-body">
                        @php $validation = $request->transcript_validation; @endphp

                        <div class="mb-3">
                            @if($validation['status'] === 'verified')
                                <div class="validation-status verified">
                                    <i class="fas fa-check-circle"></i>
                                    <span><strong>Verified</strong> — {{ $validation['message'] }}</span>
                                </div>
                            @elseif($validation['status'] === 'not_in_transcript')
                                <div class="validation-status not-verified">
                                    <i class="fas fa-times-circle"></i>
                                    <span><strong>Not in Transcript</strong> — {{ $validation['message'] }}</span>
                                </div>
                            @elseif($validation['status'] === 'no_application')
                                <div class="validation-status warning">
                                    <i class="fas fa-question-circle"></i>
                                    <span><strong>No Application</strong> — {{ $validation['message'] }}</span>
                                </div>
                            @endif
                        </div>

                        @if(isset($validation['subject']) && $validation['subject'])
                            <div class="transcript-info">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="transcript-info-item">
                                            <div class="label">Course in Transcript</div>
                                            <div class="value">{{ $validation['subject']['course_code'] }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="transcript-info-item">
                                            <div class="label">Grade</div>
                                            <div class="value">{{ $validation['subject']['grade'] ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="transcript-info-item">
                                            <div class="label">Credit Hours</div>
                                            <div class="value">{{ $validation['subject']['credit_hour'] ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Student Information -->
            <div class="sidebar-card">
                <div class="sidebar-card-header">
                    <i class="fas fa-user-graduate"></i>
                    <h5>Student Information</h5>
                </div>
                <div class="sidebar-card-body">
                    <div class="student-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="student-name">{{ $request->student->user->name ?? 'Unknown' }}</div>
                    <div class="student-matric">{{ $request->student->matric_no ?? 'N/A' }}</div>

                    <div class="divider"></div>

                    <div class="sidebar-info-row">
                        <div class="label">Email</div>
                        <div class="value">{{ $request->student->user->email ?? 'N/A' }}</div>
                    </div>

                    <div class="sidebar-info-row">
                        <div class="label">Program</div>
                        <div class="value">
                            <span class="program-badge" style="font-size: 0.75rem; padding: 0.35rem 0.75rem;">
                                {{ $request->current_program_code }}
                            </span>
                        </div>
                    </div>

                    <div class="sidebar-info-row mb-0">
                        <div class="label">Request Submitted</div>
                        <div class="value" style="font-family: 'IBM Plex Mono', monospace; font-size: 0.85rem;">
                            {{ $request->created_at->format('d M Y, H:i') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- External Lecturer Information -->
            @if($request->external_lecturer_name || $request->external_lecturer_email)
                <div class="sidebar-card">
                    <div class="sidebar-card-header">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <h5>External Lecturer</h5>
                    </div>
                    <div class="sidebar-card-body">
                        <div class="sidebar-info-row">
                            <div class="label">Name</div>
                            <div class="value"><strong>{{ $request->external_lecturer_name ?? 'Not provided' }}</strong></div>
                        </div>
                        <div class="sidebar-info-row mb-0">
                            <div class="label">Email</div>
                            <div class="value">{{ $request->external_lecturer_email ?? 'Not provided' }}</div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Timeline -->
            <div class="sidebar-card">
                <div class="sidebar-card-header">
                    <i class="fas fa-stream"></i>
                    <h5>Timeline</h5>
                </div>
                <div class="sidebar-card-body">
                    <div class="timeline-industrial">
                        <div class="timeline-item">
                            <div class="timeline-marker primary"></div>
                            <div class="timeline-date">{{ $request->created_at->format('d M Y') }}</div>
                            <div class="timeline-text">Request submitted by student</div>
                        </div>

                        @if($request->coordinator_decided_at)
                            <div class="timeline-item">
                                <div class="timeline-marker
                                    @if($request->coordinator_decision === 'equivalent') success
                                    @elseif($request->coordinator_decision === 'not_equivalent') danger
                                    @else info @endif"></div>
                                <div class="timeline-date">{{ $request->coordinator_decided_at->format('d M Y') }}</div>
                                <div class="timeline-text">
                                    @if($request->coordinator_decision === 'equivalent')
                                        Approved as equivalent
                                    @elseif($request->coordinator_decision === 'not_equivalent')
                                        Rejected - not equivalent
                                    @elseif($request->coordinator_decision === 'forward_to_rp')
                                        Forwarded to Resource Person
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
