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

    .page-header p {
        font-size: 0.95rem;
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 0;
    }

    .btn-header-action {
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

    .btn-header-action:hover {
        background: rgba(255, 255, 255, 0.25);
        border-color: rgba(255, 255, 255, 0.5);
        color: white;
        transform: translateY(-1px);
    }

    /* Industrial Alert */
    .industrial-alert {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(16, 185, 129, 0.05) 100%);
        border: 2px solid var(--uitm-green);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .industrial-alert .alert-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: var(--uitm-green);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        flex-shrink: 0;
    }

    .industrial-alert .alert-content {
        flex: 1;
        font-size: 0.9rem;
        color: var(--neutral-700);
    }

    /* Industrial Card */
    .industrial-card {
        background: white;
        border: 2px solid var(--neutral-200);
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.2s ease;
    }

    .industrial-card:hover {
        border-color: var(--neutral-300);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    /* Industrial Table */
    .industrial-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
    }

    .industrial-table thead {
        background: var(--neutral-50);
        border-bottom: 2px solid var(--neutral-200);
    }

    .industrial-table th {
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--neutral-600);
        padding: 1rem 1.25rem;
        text-align: left;
    }

    .industrial-table td {
        padding: 1rem 1.25rem;
        color: var(--neutral-700);
        border-bottom: 1px solid var(--neutral-100);
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
        font-size: 0.85rem;
        color: var(--uitm-primary);
    }

    .course-name {
        font-size: 0.8rem;
        color: var(--neutral-500);
        margin-top: 0.25rem;
    }

    .institution-text {
        font-size: 0.85rem;
        color: var(--neutral-700);
    }

    /* Status Badges */
    .status-badge {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0.4rem 0.75rem;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    .status-badge.pending {
        background: linear-gradient(135deg, var(--uitm-amber), #d97706);
        color: white;
    }

    .status-badge.under-review {
        background: linear-gradient(135deg, #0ea5e9, #0284c7);
        color: white;
    }

    .status-badge.equivalent {
        background: linear-gradient(135deg, var(--uitm-green), #059669);
        color: white;
    }

    .status-badge.not-equivalent {
        background: linear-gradient(135deg, var(--neutral-500), var(--neutral-600));
        color: white;
    }

    .date-text {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.8rem;
        color: var(--neutral-500);
    }

    /* View Button */
    .btn-view {
        background: white;
        border: 2px solid var(--uitm-primary);
        color: var(--uitm-primary);
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.75rem;
        padding: 0.4rem 0.875rem;
        border-radius: 6px;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    .btn-view:hover {
        background: var(--uitm-primary);
        color: white;
        transform: translateY(-1px);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-state-icon {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        background: var(--neutral-100);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }

    .empty-state-icon i {
        font-size: 2rem;
        color: var(--neutral-400);
    }

    .empty-state h5 {
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        color: var(--neutral-700);
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        font-size: 0.9rem;
        color: var(--neutral-500);
        margin-bottom: 1.5rem;
    }

    .btn-primary-industrial {
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        border: none;
        color: white;
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary-industrial:hover {
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
        }

        .page-header h1 {
            font-size: 1.35rem;
        }

        .industrial-table th,
        .industrial-table td {
            padding: 0.75rem;
        }

        .btn-header-action {
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
                    <div class="eyebrow">Course Equivalency</div>
                    <h1><i class="fas fa-list-ul me-2"></i>My Equivalency Requests</h1>
                    <p>Track the status of your course equivalency requests</p>
                </div>
                <a href="{{ route('student.equivalency.request.create') }}" class="btn-header-action">
                    <i class="fas fa-plus-circle"></i>
                    New Request
                </a>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="industrial-alert">
            <div class="alert-icon">
                <i class="fas fa-check"></i>
            </div>
            <div class="alert-content">{{ session('success') }}</div>
        </div>
    @endif

    <!-- Requests List -->
    <div class="row">
        <div class="col-12">
            @if($requests->count() > 0)
                <div class="industrial-card">
                    <div class="table-responsive">
                        <table class="industrial-table">
                            <thead>
                                <tr>
                                    <th>Diploma Course</th>
                                    <th>Institution</th>
                                    <th>Suggested Degree Course</th>
                                    <th>Status</th>
                                    <th>Submitted</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($requests as $request)
                                    <tr>
                                        <td>
                                            <div class="course-code">{{ $request->diploma_course_code }}</div>
                                            <div class="course-name">{{ $request->diploma_course_name }}</div>
                                        </td>
                                        <td>
                                            <span class="institution-text">{{ $request->diploma_institution }}</span>
                                        </td>
                                        <td>
                                            <div class="course-code">{{ $request->suggested_degree_course_code }}</div>
                                            <div class="course-name">{{ $request->suggested_degree_course_name }}</div>
                                        </td>
                                        <td>
                                            @php
                                                $statusClass = match($request->status) {
                                                    'pending' => 'pending',
                                                    'under_review', 'syllabus_received' => 'under-review',
                                                    'approved' => 'equivalent',
                                                    'rejected' => 'not-equivalent',
                                                    default => 'not-equivalent'
                                                };

                                                $statusLabel = match($request->status) {
                                                    'approved' => 'Equivalent',
                                                    'rejected' => 'Not Equivalent',
                                                    'syllabus_received' => 'Under Review',
                                                    default => ucwords(str_replace('_', ' ', $request->status))
                                                };

                                                $statusIcon = match($request->status) {
                                                    'pending' => 'clock',
                                                    'under_review', 'syllabus_received' => 'sync',
                                                    'approved' => 'check-circle',
                                                    'rejected' => 'times-circle',
                                                    default => 'question-circle'
                                                };
                                            @endphp
                                            <span class="status-badge {{ $statusClass }}">
                                                <i class="fas fa-{{ $statusIcon }}"></i>
                                                {{ $statusLabel }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="date-text">{{ $request->created_at->format('d M Y') }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('student.equivalency.request.show', $request->id) }}" class="btn-view">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="industrial-card">
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-inbox"></i>
                        </div>
                        <h5>No Equivalency Requests Yet</h5>
                        <p>You haven't submitted any course equivalency requests.</p>
                        <a href="{{ route('student.equivalency.request.create') }}" class="btn-primary-industrial">
                            <i class="fas fa-plus-circle"></i>
                            Submit Your First Request
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
