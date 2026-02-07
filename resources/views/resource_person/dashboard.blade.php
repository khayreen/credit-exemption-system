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
        --info-purple: #7c3aed;
    }

    body { font-family: 'IBM Plex Sans', -apple-system, BlinkMacSystemFont, sans-serif; }
    .font-mono { font-family: 'IBM Plex Mono', monospace; }

    /* Page Header */
    .page-header {
        background: linear-gradient(135deg, var(--info-purple) 0%, var(--uitm-blue) 50%, var(--industrial-dark) 100%);
        border-radius: 20px;
        padding: 2.5rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: -80px;
        right: -50px;
        width: 350px;
        height: 350px;
        background: radial-gradient(circle, rgba(245, 158, 11, 0.15) 0%, transparent 70%);
        border-radius: 50%;
        animation: float-glow 6s ease-in-out infinite;
    }

    @keyframes float-glow {
        0%, 100% { transform: translateY(0) scale(1); opacity: 0.5; }
        50% { transform: translateY(-20px) scale(1.05); opacity: 0.8; }
    }

    .page-header::after {
        content: '';
        position: absolute;
        bottom: -60px;
        left: 20%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(59, 130, 246, 0.1) 0%, transparent 70%);
        border-radius: 50%;
    }

    .page-header h1 {
        color: #fff;
        font-weight: 700;
        font-size: 2rem;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 1;
    }

    .page-header p {
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 0;
        position: relative;
        z-index: 1;
    }

    .program-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 1rem;
        position: relative;
        z-index: 1;
    }

    .program-badge {
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.25);
        color: #fff;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.8125rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .program-badge i {
        color: var(--uitm-amber);
    }

    .header-actions {
        position: relative;
        z-index: 1;
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .btn-edit-list {
        background: linear-gradient(135deg, var(--success-green) 0%, #10b981 100%);
        color: #fff;
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        transition: all 0.2s ease;
        border: none;
    }

    .btn-edit-list:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(5, 150, 105, 0.3);
        color: #fff;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    @media (max-width: 1200px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 576px) {
        .stats-grid { grid-template-columns: 1fr; }
    }

    .stat-card {
        background: #fff;
        border-radius: 16px;
        padding: 1.5rem;
        position: relative;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.08);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
    }

    .stat-card.orange::before { background: linear-gradient(90deg, var(--uitm-amber), var(--uitm-amber-light)); }
    .stat-card.blue::before { background: linear-gradient(90deg, var(--uitm-blue), var(--uitm-blue-light)); }
    .stat-card.purple::before { background: linear-gradient(90deg, var(--info-purple), #a78bfa); }
    .stat-card.green::before { background: linear-gradient(90deg, var(--success-green), #10b981); }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .stat-card.orange .stat-icon { background: rgba(245, 158, 11, 0.15); color: var(--uitm-amber); }
    .stat-card.blue .stat-icon { background: rgba(30, 58, 138, 0.1); color: var(--uitm-blue); }
    .stat-card.purple .stat-icon { background: rgba(124, 58, 237, 0.1); color: var(--info-purple); }
    .stat-card.green .stat-icon { background: rgba(5, 150, 105, 0.15); color: var(--success-green); }

    .stat-value {
        font-size: 2.25rem;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 0.25rem;
    }

    .stat-card.orange .stat-value { color: var(--uitm-amber); }
    .stat-card.blue .stat-value { color: var(--uitm-blue); }
    .stat-card.purple .stat-value { color: var(--info-purple); }
    .stat-card.green .stat-value { color: var(--success-green); }

    .stat-label {
        color: #64748b;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .stat-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: linear-gradient(135deg, var(--success-green) 0%, #10b981 100%);
        color: #fff;
        padding: 0.25rem 0.625rem;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.6875rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    /* Panel Cards */
    .panel-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .panel-header {
        background: linear-gradient(135deg, var(--industrial-light) 0%, #fff 100%);
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .panel-header-left {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .panel-header-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.125rem;
    }

    .panel-header-icon.orange {
        background: linear-gradient(135deg, var(--uitm-amber), var(--uitm-amber-light));
        color: #fff;
    }

    .panel-header-icon.purple {
        background: linear-gradient(135deg, var(--info-purple), #a78bfa);
        color: #fff;
    }

    .panel-header h5 {
        margin: 0;
        font-weight: 600;
        color: var(--industrial-dark);
    }

    .btn-view-all {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.8125rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-view-all.primary {
        background: rgba(30, 58, 138, 0.1);
        color: var(--uitm-blue);
    }

    .btn-view-all.primary:hover {
        background: var(--uitm-blue);
        color: #fff;
    }

    /* Empty State */
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
    }

    .empty-state-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--industrial-light) 0%, #e2e8f0 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 2rem;
        color: #94a3b8;
    }

    .empty-state h5 {
        color: #64748b;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #94a3b8;
        margin-bottom: 0;
    }

    /* Data Table */
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table thead th {
        background: var(--industrial-light);
        padding: 1rem 1.25rem;
        text-align: left;
        font-weight: 600;
        font-size: 0.6875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
        border-bottom: 1px solid #e2e8f0;
    }

    .data-table tbody td {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .data-table tbody tr:last-child td {
        border-bottom: none;
    }

    .data-table tbody tr:hover {
        background: rgba(30, 58, 138, 0.02);
    }

    .course-info strong {
        color: var(--industrial-dark);
        font-weight: 600;
    }

    .course-info small {
        display: block;
        color: #64748b;
        font-size: 0.8125rem;
        margin-top: 0.25rem;
    }

    .student-name {
        font-weight: 500;
        color: var(--industrial-dark);
    }

    .btn-review {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--uitm-blue-light) 100%);
        color: #fff;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8125rem;
        text-decoration: none;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
    }

    .btn-review:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(30, 58, 138, 0.25);
        color: #fff;
    }

    /* Status Badges */
    .status-badge {
        padding: 0.375rem 0.75rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.6875rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
    }

    .status-badge.success {
        background: linear-gradient(135deg, rgba(5, 150, 105, 0.15) 0%, rgba(16, 185, 129, 0.08) 100%);
        color: var(--success-green);
    }

    .status-badge.info {
        background: linear-gradient(135deg, rgba(8, 145, 178, 0.15) 0%, rgba(34, 211, 238, 0.08) 100%);
        color: #0891b2;
    }

    .status-badge.warning {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(251, 191, 36, 0.08) 100%);
        color: var(--uitm-amber);
    }

    .status-badge.primary {
        background: linear-gradient(135deg, rgba(30, 58, 138, 0.1) 0%, rgba(59, 130, 246, 0.05) 100%);
        color: var(--uitm-blue);
    }

    .status-badge.danger {
        background: linear-gradient(135deg, rgba(220, 38, 38, 0.15) 0%, rgba(239, 68, 68, 0.08) 100%);
        color: var(--danger-red);
    }

    .program-badge-sm {
        background: linear-gradient(135deg, rgba(30, 58, 138, 0.1) 0%, rgba(59, 130, 246, 0.05) 100%);
        color: var(--uitm-blue);
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.75rem;
    }

    .student-count-badge {
        background: linear-gradient(135deg, rgba(8, 145, 178, 0.15) 0%, rgba(34, 211, 238, 0.08) 100%);
        color: #0891b2;
        padding: 0.375rem 0.75rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.75rem;
    }

    .syllabus-ready-row {
        background: linear-gradient(90deg, rgba(5, 150, 105, 0.05) 0%, rgba(16, 185, 129, 0.02) 100%) !important;
    }

    .status-meta {
        font-size: 0.75rem;
        color: #64748b;
        margin-top: 0.25rem;
    }

    .status-meta.success {
        color: var(--success-green);
    }

    .header-note {
        padding: 1rem 1.5rem;
        background: var(--industrial-light);
        border-bottom: 1px solid #e2e8f0;
        font-size: 0.8125rem;
        color: #64748b;
    }

    .header-note i {
        color: var(--uitm-amber);
        margin-right: 0.5rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <h1><i class="fas fa-user-check me-2"></i>Resource Person Dashboard</h1>
                <p>Manage course equivalencies and syllabus requests</p>
                @if(!empty($stats['assigned_programs']))
                <div class="program-badges">
                    @foreach($stats['assigned_programs'] as $program)
                    <span class="program-badge">
                        <i class="fas fa-graduation-cap"></i>
                        {{ $program }}
                    </span>
                    @endforeach
                </div>
                @endif
            </div>
            @if(!empty($stats['assigned_programs']))
            <div class="header-actions">
                @foreach($stats['assigned_programs'] as $program)
                <a href="{{ route('resource_person.equivalency_lists.edit', $program) }}" class="btn-edit-list">
                    <i class="fas fa-edit"></i>
                    Edit CS110 List ({{ $program }})
                </a>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card orange">
            <div class="stat-icon">
                <i class="fas fa-book-open"></i>
            </div>
            <div class="stat-value">{{ $stats['subjects_for_review'] }}</div>
            <div class="stat-label">Subjects for Review</div>
        </div>

        <div class="stat-card blue">
            <div class="stat-icon">
                <i class="fas fa-paper-plane"></i>
            </div>
            <div class="stat-value">{{ $stats['syllabus_requests'] }}</div>
            <div class="stat-label">Syllabus Requests Sent</div>
        </div>

        <div class="stat-card purple">
            <div class="stat-icon">
                <i class="fas fa-file-signature"></i>
            </div>
            <div class="stat-value">{{ $stats['equivalency_requests'] }}</div>
            <div class="stat-label">Equivalency Requests</div>
        </div>

        @if($stats['equivalency_syllabus_received'] > 0)
        <div class="stat-card green">
            <span class="stat-badge">New</span>
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-value">{{ $stats['equivalency_syllabus_received'] }}</div>
            <div class="stat-label">Syllabus Received</div>
        </div>
        @endif
    </div>

    <!-- Pending Subjects Table -->
    <div class="panel-card">
        <div class="panel-header">
            <div class="panel-header-left">
                <div class="panel-header-icon orange">
                    <i class="fas fa-book-open"></i>
                </div>
                <h5>Subjects Pending Your Expertise</h5>
            </div>
        </div>

        @if($subjects->isEmpty())
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-inbox"></i>
            </div>
            <h5>No Pending Subjects</h5>
            <p>There are no subjects currently pending your review.</p>
        </div>
        @else
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Diploma Course Details</th>
                        <th>Student Name</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($subjects as $subject)
                    <tr>
                        <td>
                            <div class="course-info">
                                <strong>{{ $subject->course_code }}</strong> - {{ $subject->course_name }}
                                <small>Institution: {{ $subject->exemptionApplication->previous_institution }}</small>
                            </div>
                        </td>
                        <td>
                            <span class="student-name">{{ $subject->exemptionApplication->student->user->name }}</span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('resource_person.subject.review', $subject) }}" class="btn-review">
                                <i class="fas fa-eye"></i>
                                Review Subject
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    <!-- Equivalency Requests -->
    <div class="panel-card">
        <div class="panel-header">
            <div class="panel-header-left">
                <div class="panel-header-icon purple">
                    <i class="fas fa-file-signature"></i>
                </div>
                <h5>Course Equivalency Requests</h5>
            </div>
            <a href="{{ route('resource_person.equivalency_requests.index') }}" class="btn-view-all primary">
                <i class="fas fa-list"></i>
                View All Requests
            </a>
        </div>

        @if($groupedByEquivalency->isEmpty())
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-inbox"></i>
            </div>
            <h5>No Equivalency Requests</h5>
            <p>There are no course equivalency requests awaiting your review.</p>
        </div>
        @else
        <div class="header-note">
            <i class="fas fa-info-circle"></i>
            Each row represents a unique equivalency. Multiple students may request the same equivalency.
        </div>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Diploma Course</th>
                        <th>Suggested Degree Course</th>
                        <th>Program</th>
                        <th class="text-center">Students</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($groupedByEquivalency as $key => $equivalencyGroup)
                        @php
                            $representative = $equivalencyGroup->first();
                            $studentCount = $equivalencyGroup->count();
                            $hasSyllabus = $representative->syllabus_received_at !== null;
                        @endphp
                        <tr class="{{ $hasSyllabus ? 'syllabus-ready-row' : '' }}">
                            <td>
                                <div class="course-info">
                                    <strong>{{ $representative->diploma_course_code }}</strong>
                                    <small>{{ Str::limit($representative->diploma_course_name, 40) }}</small>
                                    <small>{{ $representative->diploma_institution }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="course-info">
                                    <strong>{{ $representative->suggested_degree_course_code }}</strong>
                                    <small>{{ Str::limit($representative->suggested_degree_course_name, 40) }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="program-badge-sm">{{ $representative->current_program_code }}</span>
                            </td>
                            <td class="text-center">
                                <span class="student-count-badge">{{ $studentCount }} student(s)</span>
                            </td>
                            <td>
                                @if($hasSyllabus)
                                    <span class="status-badge success">
                                        <i class="fas fa-check-circle"></i>
                                        Syllabus Received
                                    </span>
                                    <div class="status-meta success">Ready for Review!</div>
                                @elseif($representative->syllabus_request_sent_at)
                                    <span class="status-badge info">Awaiting Lecturer</span>
                                    <div class="status-meta">Email sent {{ $representative->syllabus_request_sent_at->diffForHumans() }}</div>
                                @elseif($representative->status === 'pending')
                                    <span class="status-badge warning">Pending</span>
                                @elseif($representative->status === 'under_review')
                                    <span class="status-badge primary">Under Review</span>
                                @elseif($representative->status === 'approved')
                                    <span class="status-badge success">Approved</span>
                                @elseif($representative->status === 'rejected')
                                    <span class="status-badge danger">Rejected</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('resource_person.equivalency_requests.review', $representative->id) }}" class="btn-review">
                                    <i class="fas fa-eye"></i>
                                    Review
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection
