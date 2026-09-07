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

    /* Header Section */
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

    .header-badge {
        background: linear-gradient(135deg, var(--uitm-amber) 0%, var(--warning-orange) 100%);
        color: #fff;
        padding: 0.5rem 1.25rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
    }

    .btn-back {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #fff;
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-back:hover {
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
        transform: translateX(-3px);
    }

    /* Stats Cards */
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

    .stat-card.pending::before { background: linear-gradient(90deg, var(--uitm-blue), var(--uitm-blue-light)); }
    .stat-card.approved::before { background: linear-gradient(90deg, var(--success-green), #10b981); }
    .stat-card.rejected::before { background: linear-gradient(90deg, var(--danger-red), #ef4444); }
    .stat-card.expiring::before { background: linear-gradient(90deg, var(--uitm-amber), var(--uitm-amber-light)); }

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

    .stat-card.pending .stat-icon { background: rgba(30, 58, 138, 0.1); color: var(--uitm-blue); }
    .stat-card.approved .stat-icon { background: rgba(5, 150, 105, 0.1); color: var(--success-green); }
    .stat-card.rejected .stat-icon { background: rgba(220, 38, 38, 0.1); color: var(--danger-red); }
    .stat-card.expiring .stat-icon { background: rgba(245, 158, 11, 0.1); color: var(--uitm-amber); }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 0.25rem;
    }

    .stat-card.pending .stat-value { color: var(--uitm-blue); }
    .stat-card.approved .stat-value { color: var(--success-green); }
    .stat-card.rejected .stat-value { color: var(--danger-red); }
    .stat-card.expiring .stat-value { color: var(--uitm-amber); }

    .stat-label {
        color: #64748b;
        font-size: 0.875rem;
        font-weight: 500;
    }

    /* Alert Styles */
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
        background: linear-gradient(135deg, rgba(30, 58, 138, 0.1) 0%, rgba(59, 130, 246, 0.05) 100%);
        border-left: 4px solid var(--uitm-blue);
        color: #1e40af;
    }

    /* Empty State */
    .empty-state {
        background: #fff;
        border-radius: 16px;
        border: 2px dashed #e2e8f0;
        padding: 4rem 2rem;
        text-align: center;
    }

    .empty-state-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, rgba(5, 150, 105, 0.1) 0%, rgba(16, 185, 129, 0.05) 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 2.5rem;
        color: var(--success-green);
    }

    .empty-state h4 {
        color: var(--industrial-dark);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #64748b;
        margin-bottom: 0;
    }

    /* Equivalency Group Card */
    .equivalency-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        margin-bottom: 1.5rem;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .equivalency-card:hover {
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
    }

    .equivalency-header {
        background: linear-gradient(135deg, var(--industrial-light) 0%, #fff 100%);
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .course-mapping {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .course-badge {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .course-badge.diploma {
        background: var(--industrial-gray);
        color: #fff;
    }

    .course-badge.degree {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--uitm-blue-light) 100%);
        color: #fff;
    }

    .mapping-arrow {
        color: #94a3b8;
        font-size: 1.25rem;
    }

    .mapping-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        color: #64748b;
        font-size: 0.875rem;
    }

    .match-badge {
        background: linear-gradient(135deg, var(--success-green) 0%, #10b981 100%);
        color: #fff;
        padding: 0.375rem 0.75rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.75rem;
    }

    .btn-approve-all {
        background: linear-gradient(135deg, var(--success-green) 0%, #10b981 100%);
        color: #fff;
        border: none;
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
        box-shadow: 0 4px 15px rgba(5, 150, 105, 0.2);
    }

    .btn-approve-all:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(5, 150, 105, 0.3);
        color: #fff;
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
        font-size: 0.75rem;
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

    .student-name {
        font-weight: 600;
        color: var(--industrial-dark);
    }

    .matric-code {
        font-family: 'IBM Plex Mono', monospace;
        background: var(--industrial-light);
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.8125rem;
        color: var(--industrial-gray);
    }

    .program-badge {
        background: linear-gradient(135deg, rgba(30, 58, 138, 0.1) 0%, rgba(59, 130, 246, 0.05) 100%);
        color: var(--uitm-blue);
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.75rem;
    }

    .status-badge {
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.75rem;
    }

    .status-badge.secondary {
        background: var(--industrial-light);
        color: var(--industrial-gray);
    }

    .grade-badge {
        background: var(--industrial-dark);
        color: #fff;
        padding: 0.375rem 0.625rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.75rem;
        font-family: 'IBM Plex Mono', monospace;
    }

    .expiry-badge {
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.75rem;
    }

    .expiry-badge.warning {
        background: rgba(245, 158, 11, 0.15);
        color: #b45309;
    }

    /* Action Buttons */
    .action-group {
        display: flex;
        gap: 0.5rem;
        justify-content: flex-end;
    }

    .btn-action {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        font-size: 1rem;
    }

    .btn-action.approve {
        background: rgba(5, 150, 105, 0.1);
        color: var(--success-green);
    }

    .btn-action.approve:hover {
        background: var(--success-green);
        color: #fff;
        transform: scale(1.1);
    }

    .btn-action.reject {
        background: rgba(220, 38, 38, 0.1);
        color: var(--danger-red);
    }

    .btn-action.reject:hover {
        background: var(--danger-red);
        color: #fff;
        transform: scale(1.1);
    }

    .btn-action.view {
        background: rgba(30, 58, 138, 0.1);
        color: var(--uitm-blue);
        text-decoration: none;
    }

    .btn-action.view:hover {
        background: var(--uitm-blue);
        color: #fff;
        transform: scale(1.1);
    }

    /* Recent Decisions Card */
    .decisions-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        margin-top: 2rem;
    }

    .decisions-header {
        background: linear-gradient(135deg, var(--industrial-light) 0%, #fff 100%);
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .decisions-header i {
        color: var(--uitm-amber);
        font-size: 1.25rem;
    }

    .decisions-header h5 {
        margin: 0;
        font-weight: 600;
        color: var(--industrial-dark);
    }

    .decision-approved {
        background: rgba(5, 150, 105, 0.1);
        color: var(--success-green);
    }

    .decision-rejected {
        background: rgba(220, 38, 38, 0.1);
        color: var(--danger-red);
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <div class="d-flex align-items-center gap-3 mb-2">
                    <h1><i class="fas fa-sync-alt me-2"></i>Pending Re-evaluations</h1>
                    <span class="header-badge">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $stats['total_pending'] }} Pending
                    </span>
                </div>
                <p>Review applications affected by new course equivalency mappings</p>
            </div>
            <a href="{{ route('academic_advisor.dashboard') }}" class="btn-back">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card pending">
            <div class="stat-icon">
                <i class="fas fa-history"></i>
            </div>
            <div class="stat-value">{{ $stats['total_pending'] }}</div>
            <div class="stat-label">Pending Review</div>
        </div>

        <div class="stat-card approved">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-value">{{ $stats['approved_today'] }}</div>
            <div class="stat-label">Approved Today</div>
        </div>

        <div class="stat-card rejected">
            <div class="stat-icon">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="stat-value">{{ $stats['rejected_today'] }}</div>
            <div class="stat-label">Rejected Today</div>
        </div>

        <div class="stat-card expiring">
            <div class="stat-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-value">{{ $stats['expiring_soon'] }}</div>
            <div class="stat-label">Expiring Soon</div>
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

    @if(session('warning'))
        <div class="alert-industrial warning">
            <i class="fas fa-exclamation-triangle"></i>
            <span>{{ session('warning') }}</span>
        </div>
    @endif

    @if(session('info'))
        <div class="alert-industrial info">
            <i class="fas fa-info-circle"></i>
            <span>{{ session('info') }}</span>
        </div>
    @endif

    <!-- Pending Re-evaluations -->
    @if($pendingReevaluations->isEmpty())
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-inbox"></i>
            </div>
            <h4>No Pending Re-evaluations</h4>
            <p>All applications are up to date with the latest equivalency mappings.</p>
        </div>
    @else
        <!-- Grouped by Equivalency Mapping -->
        @foreach($groupedByEquivalency as $key => $reevaluations)
            @php
                $parts = explode('|', $key);
                $diplomaCourse = $parts[0] ?? '';
                $degreeCourse = $parts[1] ?? '';
                $firstReevaluation = $reevaluations->first();
            @endphp
            <div class="equivalency-card">
                <div class="equivalency-header">
                    <div class="d-flex flex-column gap-2">
                        <div class="course-mapping">
                            <span class="course-badge diploma">{{ $diplomaCourse }}</span>
                            <i class="fas fa-arrow-circle-right mapping-arrow"></i>
                            <span class="course-badge degree">{{ $degreeCourse }}</span>
                        </div>
                        <div class="mapping-info">
                            <span><i class="fas fa-users me-1"></i>{{ $reevaluations->count() }} application(s) may qualify</span>
                            <span class="match-badge"><i class="fas fa-chart-line me-1"></i>{{ $firstReevaluation->match_percentage }}% Match</span>
                        </div>
                    </div>
                    <form action="{{ route('academic_advisor.reevaluations.approve_by_equivalency') }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="diploma_course_code" value="{{ $diplomaCourse }}">
                        <input type="hidden" name="degree_course_code" value="{{ $degreeCourse }}">
                        <button type="submit" class="btn-approve-all"
                                onclick="return confirm('Approve all {{ $reevaluations->count() }} application(s) for this mapping?')">
                            <i class="fas fa-check-double"></i>
                            Approve All ({{ $reevaluations->count() }})
                        </button>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Matric No</th>
                                <th>Program</th>
                                <th>Original Status</th>
                                <th>Grade</th>
                                <th>Expires</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reevaluations as $reevaluation)
                                @php
                                    $subject = $reevaluation->applicationSubject;
                                    $application = $subject->exemptionApplication ?? null;
                                    $student = $application->student ?? null;
                                @endphp
                                <tr>
                                    <td>
                                        <span class="student-name">{{ $application->student_name ?? ($student->user->name ?? 'N/A') }}</span>
                                    </td>
                                    <td>
                                        <code class="matric-code">{{ $application->matric_no ?? $student->matric_no ?? 'N/A' }}</code>
                                    </td>
                                    <td>
                                        <span class="program-badge">{{ $application->current_program_code ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <span class="status-badge secondary">{{ $reevaluation->original_subject_status }}</span>
                                    </td>
                                    <td>
                                        <span class="grade-badge">{{ $subject->grade ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        @if($reevaluation->expires_at)
                                            @if($reevaluation->expires_at->diffInDays(now()) <= 7)
                                                <span class="expiry-badge warning">
                                                    <i class="fas fa-clock me-1"></i>{{ $reevaluation->expires_at->diffForHumans() }}
                                                </span>
                                            @else
                                                <small class="text-muted">{{ $reevaluation->expires_at->format('d M Y') }}</small>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-group">
                                            <form action="{{ route('academic_advisor.reevaluations.approve', $reevaluation) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn-action approve" title="Approve">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('academic_advisor.reevaluations.reject', $reevaluation) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn-action reject" title="Reject"
                                                        onclick="return confirm('Reject this re-evaluation? The student\'s original status will be maintained.')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                            <a href="{{ route('academic_advisor.reevaluations.show', $reevaluation) }}"
                                               class="btn-action view" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    @endif

    <!-- Recent Decisions -->
    @if($recentDecisions->isNotEmpty())
        <div class="decisions-card">
            <div class="decisions-header">
                <i class="fas fa-history"></i>
                <h5>Recent Decisions (Last 7 Days)</h5>
            </div>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Course Mapping</th>
                            <th>Decision</th>
                            <th>Decided By</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentDecisions as $decision)
                            @php
                                $subject = $decision->applicationSubject;
                                $application = $subject->exemptionApplication ?? null;
                                $student = $application->student ?? null;
                            @endphp
                            <tr>
                                <td>
                                    <span class="student-name">{{ $application->student_name ?? ($student->user->name ?? 'N/A') }}</span>
                                </td>
                                <td>
                                    <div class="course-mapping" style="gap: 0.5rem;">
                                        <code class="matric-code">{{ $decision->diploma_course_code }}</code>
                                        <i class="fas fa-arrow-right text-muted"></i>
                                        <code class="matric-code">{{ $decision->degree_course_code }}</code>
                                    </div>
                                </td>
                                <td>
                                    @if($decision->status === 'approved')
                                        <span class="status-badge decision-approved">
                                            <i class="fas fa-check-circle me-1"></i>Approved
                                        </span>
                                    @else
                                        <span class="status-badge decision-rejected">
                                            <i class="fas fa-times-circle me-1"></i>Rejected
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $decision->decidedBy->name ?? 'System' }}</td>
                                <td>
                                    <small class="text-muted">{{ $decision->decided_at ? $decision->decided_at->format('d M Y, H:i') : '-' }}</small>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
