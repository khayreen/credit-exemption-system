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
        margin-bottom: 0.5rem;
    }

    .page-header p {
        color: rgba(255,255,255,0.8);
        margin: 0;
    }

    .header-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .btn-header {
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.3);
        color: white;
        padding: 0.5rem 1.25rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-header:hover {
        background: rgba(255,255,255,0.25);
        color: white;
    }

    .btn-header.primary {
        background: var(--uitm-amber);
        border-color: var(--uitm-amber);
        color: var(--industrial-dark);
    }

    .btn-header.primary:hover {
        background: #fbbf24;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.25rem;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        border: 1px solid #e2e8f0;
        transition: all 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 25px rgba(0,0,0,0.1);
    }

    .stat-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--industrial-gray);
        font-weight: 600;
        margin-bottom: 0.35rem;
    }

    .stat-number {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 2rem;
        font-weight: 700;
        line-height: 1;
    }

    .stat-number.primary { color: var(--uitm-blue); }
    .stat-number.success { color: var(--success); }
    .stat-number.danger { color: var(--danger); }
    .stat-number.info { color: var(--info); }

    .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .stat-icon.primary {
        background: rgba(30, 58, 138, 0.1);
        color: var(--uitm-blue);
    }

    .stat-icon.success {
        background: rgba(5, 150, 105, 0.1);
        color: var(--success);
    }

    .stat-icon.danger {
        background: rgba(220, 38, 38, 0.1);
        color: var(--danger);
    }

    .stat-icon.info {
        background: rgba(13, 148, 136, 0.1);
        color: var(--info);
    }

    /* Filter Card */
    .filter-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        margin-bottom: 1.5rem;
    }

    .filter-card .form-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--industrial-gray);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .filter-card .form-control,
    .filter-card .form-select {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.625rem 1rem;
        transition: all 0.2s ease;
    }

    .filter-card .form-control:focus,
    .filter-card .form-select:focus {
        border-color: var(--uitm-blue);
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
    }

    .btn-filter {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--uitm-blue-light) 100%);
        border: none;
        color: white;
        padding: 0.625rem 1.25rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(30, 58, 138, 0.3);
        color: white;
    }

    /* Results Card */
    .results-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        overflow: hidden;
    }

    .results-header {
        background: linear-gradient(135deg, var(--industrial-light) 0%, #e2e8f0 100%);
        padding: 1rem 1.5rem;
        border-bottom: 2px solid var(--uitm-blue);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .results-header h5 {
        margin: 0;
        font-weight: 600;
        color: var(--industrial-dark);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .results-header h5 i {
        color: var(--uitm-blue);
    }

    .count-badge {
        background: var(--industrial-gray);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .btn-clear-filters {
        background: white;
        border: 2px solid var(--industrial-gray);
        color: var(--industrial-gray);
        padding: 0.4rem 1rem;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn-clear-filters:hover {
        background: var(--industrial-gray);
        color: white;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-state i {
        font-size: 4rem;
        color: #cbd5e1;
        margin-bottom: 1rem;
    }

    .empty-state h5 {
        color: var(--industrial-gray);
        font-weight: 500;
    }

    .empty-state p {
        color: #94a3b8;
    }

    /* Table */
    .results-table {
        width: 100%;
        margin: 0;
    }

    .results-table thead th {
        background: var(--industrial-light);
        color: var(--industrial-gray);
        font-weight: 600;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 1rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .results-table tbody td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .results-table tbody tr:hover {
        background: rgba(30, 58, 138, 0.02);
    }

    .course-cell {
        display: flex;
        align-items: center;
        gap: 0.875rem;
    }

    .course-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.9rem;
    }

    .course-icon.approved {
        background: linear-gradient(135deg, var(--success) 0%, #10b981 100%);
    }

    .course-icon.rejected {
        background: linear-gradient(135deg, var(--danger) 0%, #ef4444 100%);
    }

    .course-icon.forwarded {
        background: linear-gradient(135deg, var(--info) 0%, #14b8a6 100%);
    }

    .course-code {
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 600;
        color: var(--industrial-dark);
    }

    .course-name {
        font-size: 0.85rem;
        color: var(--industrial-gray);
    }

    .student-matric {
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 600;
        color: var(--industrial-dark);
    }

    .student-name {
        font-size: 0.85rem;
        color: var(--industrial-gray);
    }

    .program-badge {
        display: inline-block;
        background: rgba(30, 58, 138, 0.1);
        color: var(--uitm-blue);
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .decision-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .decision-badge.approved {
        background: rgba(5, 150, 105, 0.1);
        color: var(--success);
        border: 1px solid rgba(5, 150, 105, 0.2);
    }

    .decision-badge.rejected {
        background: rgba(220, 38, 38, 0.1);
        color: var(--danger);
        border: 1px solid rgba(220, 38, 38, 0.2);
    }

    .decision-badge.forwarded {
        background: rgba(13, 148, 136, 0.1);
        color: var(--info);
        border: 1px solid rgba(13, 148, 136, 0.2);
    }

    .approved-course {
        font-size: 0.8rem;
        color: var(--industrial-gray);
        margin-top: 0.25rem;
    }

    .date-cell {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.85rem;
    }

    .date-relative {
        font-size: 0.75rem;
        color: #94a3b8;
    }

    .notes-cell {
        max-width: 150px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        color: var(--industrial-gray);
        font-size: 0.85rem;
    }

    .btn-view {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        background: white;
        border: 2px solid var(--uitm-blue);
        color: var(--uitm-blue);
        padding: 0.4rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.8rem;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-view:hover {
        background: var(--uitm-blue);
        color: white;
    }

    /* Pagination */
    .results-footer {
        background: var(--industrial-light);
        padding: 1rem 1.5rem;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .pagination-info {
        font-size: 0.85rem;
        color: var(--industrial-gray);
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
            margin: -1rem -1rem 1.5rem -1rem;
            border-radius: 0 0 16px 16px;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2><i class="fas fa-history me-2"></i>Decision History</h2>
                <p>View all past decisions on course equivalency requests</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('program_coordinator.equivalency_requests.index') }}" class="btn-header primary">
                    <i class="fas fa-inbox me-2"></i>Pending Requests
                </a>
                <a href="{{ route('program_coordinator.dashboard') }}" class="btn-header">
                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div>
                <div class="stat-label">Total Processed</div>
                <div class="stat-number primary">{{ $stats['total'] }}</div>
            </div>
            <div class="stat-icon primary">
                <i class="fas fa-check-double"></i>
            </div>
        </div>
        <div class="stat-card">
            <div>
                <div class="stat-label">Approved</div>
                <div class="stat-number success">{{ $stats['approved'] }}</div>
            </div>
            <div class="stat-icon success">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
        <div class="stat-card">
            <div>
                <div class="stat-label">Rejected</div>
                <div class="stat-number danger">{{ $stats['rejected'] }}</div>
            </div>
            <div class="stat-icon danger">
                <i class="fas fa-times-circle"></i>
            </div>
        </div>
        <div class="stat-card">
            <div>
                <div class="stat-label">Forwarded to RP</div>
                <div class="stat-number info">{{ $stats['forwarded'] }}</div>
            </div>
            <div class="stat-icon info">
                <i class="fas fa-share"></i>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filter-card">
        <form action="{{ route('program_coordinator.history') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Course code, student name..." value="{{ $search ?? '' }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="all" {{ ($status ?? 'all') === 'all' ? 'selected' : '' }}>All Decisions</option>
                    <option value="approved" {{ ($status ?? '') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ ($status ?? '') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="forwarded" {{ ($status ?? '') === 'forwarded' ? 'selected' : '' }}>Forwarded to RP</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Program</label>
                <select name="program" class="form-select">
                    <option value="">All Programs</option>
                    @foreach($coordinator->program_codes as $code)
                        <option value="{{ $code }}" {{ ($program ?? '') === $code ? 'selected' : '' }}>{{ $code }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">From Date</label>
                <input type="date" name="date_from" class="form-control" value="{{ $dateFrom ?? '' }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">To Date</label>
                <input type="date" name="date_to" class="form-control" value="{{ $dateTo ?? '' }}">
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="submit" class="btn-filter w-100">
                    <i class="fas fa-filter"></i>
                </button>
            </div>
        </form>
    </div>

    <!-- Results Table -->
    <div class="results-card">
        <div class="results-header">
            <h5>
                <i class="fas fa-list"></i>
                Decision Records
                <span class="count-badge">{{ $requests->total() }}</span>
            </h5>
            @if($search || ($status ?? 'all') !== 'all' || $program || $dateFrom || $dateTo)
                <a href="{{ route('program_coordinator.history') }}" class="btn-clear-filters">
                    <i class="fas fa-times me-1"></i>Clear Filters
                </a>
            @endif
        </div>

        @if($requests->isEmpty())
            <div class="empty-state">
                <i class="fas fa-folder-open"></i>
                <h5>No Records Found</h5>
                <p>
                    @if($search || ($status ?? 'all') !== 'all' || $program || $dateFrom || $dateTo)
                        Try adjusting your filters to find what you're looking for.
                    @else
                        You haven't processed any course equivalency requests yet.
                    @endif
                </p>
            </div>
        @else
            <div class="table-responsive">
                <table class="results-table">
                    <thead>
                        <tr>
                            <th class="ps-4">Diploma Course</th>
                            <th>Student</th>
                            <th>Program</th>
                            <th class="text-center">Decision</th>
                            <th>Decision Date</th>
                            <th>Notes</th>
                            <th class="text-center pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requests as $request)
                            <tr>
                                <td class="ps-4">
                                    <div class="course-cell">
                                        <div class="course-icon
                                            @if($request->coordinator_decision === 'equivalent') approved
                                            @elseif($request->coordinator_decision === 'not_equivalent') rejected
                                            @else forwarded @endif">
                                            <i class="fas fa-book"></i>
                                        </div>
                                        <div>
                                            <div class="course-code">{{ $request->diploma_course_code }}</div>
                                            <div class="course-name">{{ Str::limit($request->diploma_course_name, 30) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="student-matric">{{ $request->student->matric_no ?? 'N/A' }}</div>
                                    <div class="student-name">{{ $request->student->user->name ?? 'Unknown' }}</div>
                                </td>
                                <td>
                                    <span class="program-badge">{{ $request->current_program_code }}</span>
                                </td>
                                <td class="text-center">
                                    @if($request->coordinator_decision === 'equivalent')
                                        <span class="decision-badge approved">
                                            <i class="fas fa-check-circle"></i>Approved
                                        </span>
                                        @if($request->approved_degree_course_code)
                                            <div class="approved-course">→ {{ $request->approved_degree_course_code }}</div>
                                        @endif
                                    @elseif($request->coordinator_decision === 'not_equivalent')
                                        <span class="decision-badge rejected">
                                            <i class="fas fa-times-circle"></i>Rejected
                                        </span>
                                    @elseif($request->coordinator_decision === 'forward_to_rp')
                                        <span class="decision-badge forwarded">
                                            <i class="fas fa-share"></i>Forwarded
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($request->coordinator_decided_at)
                                        <div class="date-cell">{{ $request->coordinator_decided_at->format('d M Y') }}</div>
                                        <div class="date-relative">{{ $request->coordinator_decided_at->diffForHumans() }}</div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($request->coordinator_notes)
                                        <span class="notes-cell" title="{{ $request->coordinator_notes }}">
                                            {{ $request->coordinator_notes }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-center pe-4">
                                    <a href="{{ route('program_coordinator.show_request', $request->id) }}" class="btn-view">
                                        <i class="fas fa-eye"></i>View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($requests->hasPages())
                <div class="results-footer">
                    <div class="pagination-info">
                        Showing {{ $requests->firstItem() }} to {{ $requests->lastItem() }} of {{ $requests->total() }} records
                    </div>
                    {{ $requests->withQueryString()->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
