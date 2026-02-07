@extends('layouts.app')

@section('title', 'Equivalency Lists')

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

    /* Page Header */
    .page-header {
        margin-bottom: 2rem;
    }

    .page-title {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--industrial-dark);
        margin-bottom: 0.5rem;
    }

    .page-subtitle {
        color: var(--neutral-600);
        font-size: 0.9rem;
        margin-bottom: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .page-subtitle i {
        color: var(--uitm-amber);
    }

    .assigned-programs {
        margin-top: 0.75rem;
    }

    .assigned-programs strong {
        color: var(--neutral-600);
        font-size: 0.85rem;
    }

    .badge-program {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.625rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        color: white;
        margin-left: 0.375rem;
    }

    .btn-success-industrial {
        background: linear-gradient(135deg, var(--success) 0%, #047857 100%);
        border: none;
        color: white;
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 0.75rem 1.25rem;
        border-radius: 10px;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-success-industrial:hover {
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border: 2px solid var(--neutral-200);
        border-radius: 12px;
        padding: 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
    }

    .stat-card.warning {
        border-left: 4px solid var(--warning);
    }

    .stat-card.success {
        border-left: 4px solid var(--success);
    }

    .stat-card.danger {
        border-left: 4px solid var(--danger);
    }

    .stat-card.info {
        border-left: 4px solid var(--info);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .stat-card.warning .stat-icon {
        background: var(--warning-light);
        color: var(--warning);
    }

    .stat-card.success .stat-icon {
        background: var(--success-light);
        color: var(--success);
    }

    .stat-card.danger .stat-icon {
        background: var(--danger-light);
        color: var(--danger);
    }

    .stat-card.info .stat-icon {
        background: var(--info-light);
        color: var(--info);
    }

    .stat-icon i {
        font-size: 1.25rem;
    }

    .stat-content h3 {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--industrial-dark);
        line-height: 1;
        margin-bottom: 0.25rem;
    }

    .stat-content p {
        font-size: 0.8rem;
        color: var(--neutral-500);
        margin: 0;
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
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        border-bottom: 2px solid var(--neutral-200);
    }

    .industrial-card-header.info {
        background: linear-gradient(135deg, var(--info) 0%, #0369a1 100%);
    }

    .industrial-card-header.primary {
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--industrial-dark) 100%);
    }

    .industrial-card-header h5 {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 1rem;
        font-weight: 600;
        color: white;
        margin: 0;
    }

    .industrial-card-header i {
        color: rgba(255, 255, 255, 0.9);
    }

    .industrial-card-body {
        padding: 0;
    }

    /* Tabs */
    .industrial-tabs {
        display: flex;
        background: var(--neutral-50);
        border-bottom: 2px solid var(--neutral-200);
    }

    .industrial-tab {
        padding: 1rem 1.5rem;
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--neutral-500);
        background: none;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
    }

    .industrial-tab:hover {
        color: var(--uitm-primary);
        background: rgba(30, 58, 138, 0.05);
    }

    .industrial-tab.active {
        color: var(--uitm-primary);
        background: white;
    }

    .industrial-tab.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--uitm-primary);
    }

    .tab-content {
        padding: 1.5rem;
    }

    .tab-pane {
        display: none;
    }

    .tab-pane.active {
        display: block;
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
        font-size: 0.85rem;
    }

    .course-name {
        color: var(--neutral-600);
        font-size: 0.8rem;
        margin-top: 0.25rem;
    }

    .badge-match {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 0.625rem;
        border-radius: 6px;
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .badge-match.high {
        background: var(--success-light);
        color: var(--success);
    }

    .badge-match.medium {
        background: var(--warning-light);
        color: var(--warning);
    }

    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.375rem 0.625rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-status.success {
        background: var(--success-light);
        color: var(--success);
    }

    .time-text {
        font-size: 0.8rem;
        color: var(--neutral-500);
    }

    .btn-view {
        background: white;
        border: 2px solid var(--uitm-primary);
        color: var(--uitm-primary);
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.8rem;
        padding: 0.4rem 0.75rem;
        border-radius: 6px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        transition: all 0.2s ease;
    }

    .btn-view:hover {
        background: var(--uitm-primary);
        color: white;
    }

    .btn-link-reason {
        background: none;
        border: none;
        color: var(--uitm-primary);
        font-size: 0.8rem;
        cursor: pointer;
        padding: 0;
        text-decoration: underline;
    }

    .btn-link-reason:hover {
        color: var(--uitm-primary-light);
    }

    /* Section Title */
    .section-title {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 1rem;
        font-weight: 600;
        color: var(--uitm-primary);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-title i {
        color: var(--uitm-amber);
    }

    .section-title.info-color {
        color: var(--teal);
    }

    .section-title.info-color i {
        color: var(--teal);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
    }

    .empty-state-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: var(--neutral-100);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
    }

    .empty-state-icon i {
        font-size: 1.5rem;
        color: var(--neutral-400);
    }

    .empty-state p {
        color: var(--neutral-500);
        font-size: 0.9rem;
        margin: 0;
    }

    /* Modal */
    .modal-content {
        border: none;
        border-radius: 12px;
    }

    .modal-header.danger {
        background: var(--danger);
        color: white;
        border-radius: 12px 12px 0 0;
    }

    .modal-header .btn-close-white {
        filter: brightness(0) invert(1);
    }

    /* Toast */
    .toast-container {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9999;
    }

    .toast-industrial {
        background: var(--success);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 10px;
        font-family: 'IBM Plex Sans', sans-serif;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    @media (max-width: 992px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 576px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .page-header .d-flex {
            flex-direction: column;
            gap: 1rem;
        }

        .industrial-tabs {
            overflow-x: auto;
        }

        .industrial-tab {
            padding: 0.75rem 1rem;
            white-space: nowrap;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-clipboard-list me-2" style="color: var(--uitm-amber);"></i>
                    Equivalency Lists
                </h1>
                <p class="page-subtitle">
                    <i class="fas fa-user-tie"></i>
                    View published equivalency lists and manage your forwarded mappings
                </p>
                @if(!empty($assignedPrograms))
                    <div class="assigned-programs">
                        <strong>Assigned Programs:</strong>
                        @foreach($assignedPrograms as $program)
                            <span class="badge-program">{{ $program }}</span>
                        @endforeach
                    </div>
                @endif
            </div>
            <a href="{{ route('resource_person.course_equivalencies.view') }}" class="btn-success-industrial">
                <i class="fas fa-exchange-alt"></i> All Course Mappings
            </a>
        </div>
    </div>

    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-card warning">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $stats['pending_count'] }}</h3>
                <p>Pending Review</p>
            </div>
        </div>
        <div class="stat-card success">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $stats['added_count'] }}</h3>
                <p>Added to Lists</p>
            </div>
        </div>
        <div class="stat-card danger">
            <div class="stat-icon">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $stats['rejected_count'] }}</h3>
                <p>Rejected</p>
            </div>
        </div>
        <div class="stat-card info">
            <div class="stat-icon">
                <i class="fas fa-paper-plane"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $stats['total_forwarded'] }}</h3>
                <p>Total Forwarded</p>
            </div>
        </div>
    </div>

    <!-- Your Forwarded Mappings -->
    <div class="industrial-card">
        <div class="industrial-card-header info">
            <i class="fas fa-paper-plane"></i>
            <h5>Your Forwarded Mappings</h5>
        </div>
        <div class="industrial-card-body">
            <div class="industrial-tabs">
                <button class="industrial-tab active" data-tab="pending">
                    Pending ({{ $pendingMappings->count() }})
                </button>
                <button class="industrial-tab" data-tab="added">
                    Added ({{ $addedMappings->count() }})
                </button>
                <button class="industrial-tab" data-tab="rejected">
                    Rejected ({{ $rejectedMappings->count() }})
                </button>
            </div>

            <div class="tab-content">
                <!-- Pending Tab -->
                <div class="tab-pane active" id="pending">
                    @if($pendingMappings->isEmpty())
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-inbox"></i>
                            </div>
                            <p>No pending mappings</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="industrial-table">
                                <thead>
                                    <tr>
                                        <th>Diploma Course</th>
                                        <th>Degree Course</th>
                                        <th>Program</th>
                                        <th class="text-center">Match %</th>
                                        <th>Forwarded</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingMappings as $mapping)
                                    <tr>
                                        <td>
                                            <span class="course-code">{{ $mapping->diploma_course_code }}</span>
                                            <div class="course-name">{{ Str::limit($mapping->diploma_course_name, 40) }}</div>
                                        </td>
                                        <td>
                                            <span class="course-code">{{ $mapping->degree_course_code }}</span>
                                            <div class="course-name">{{ Str::limit($mapping->degree_course_name, 40) }}</div>
                                        </td>
                                        <td><span class="badge-program">{{ $mapping->program_code }}</span></td>
                                        <td class="text-center">
                                            <span class="badge-match {{ $mapping->match_percentage >= 80 ? 'high' : 'medium' }}">
                                                {{ number_format($mapping->match_percentage, 0) }}%
                                            </span>
                                        </td>
                                        <td><span class="time-text">{{ $mapping->created_at->diffForHumans() }}</span></td>
                                        <td class="text-center">
                                            <a href="{{ route('resource_person.equivalency_mappings.show', $mapping) }}" class="btn-view">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <!-- Added Tab -->
                <div class="tab-pane" id="added">
                    @if($addedMappings->isEmpty())
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-inbox"></i>
                            </div>
                            <p>No mappings have been added to equivalency lists yet</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="industrial-table">
                                <thead>
                                    <tr>
                                        <th>Diploma Course</th>
                                        <th>Degree Course</th>
                                        <th>Program</th>
                                        <th class="text-center">Match %</th>
                                        <th>Added By</th>
                                        <th>Added Date</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($addedMappings as $mapping)
                                    <tr>
                                        <td>
                                            <span class="course-code">{{ $mapping->diploma_course_code }}</span>
                                            <div class="course-name">{{ Str::limit($mapping->diploma_course_name, 40) }}</div>
                                        </td>
                                        <td>
                                            <span class="course-code">{{ $mapping->degree_course_code }}</span>
                                            <div class="course-name">{{ Str::limit($mapping->degree_course_name, 40) }}</div>
                                        </td>
                                        <td><span class="badge-program">{{ $mapping->program_code }}</span></td>
                                        <td class="text-center">
                                            <span class="badge-match high">{{ number_format($mapping->match_percentage, 0) }}%</span>
                                        </td>
                                        <td><span class="time-text">{{ $mapping->coordinator->name ?? 'N/A' }}</span></td>
                                        <td><span class="time-text">{{ $mapping->added_at ? $mapping->added_at->format('d M Y') : '-' }}</span></td>
                                        <td class="text-center">
                                            <span class="badge-status success">
                                                <i class="fas fa-check-circle"></i> Added
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <!-- Rejected Tab -->
                <div class="tab-pane" id="rejected">
                    @if($rejectedMappings->isEmpty())
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-inbox"></i>
                            </div>
                            <p>No rejected mappings</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="industrial-table">
                                <thead>
                                    <tr>
                                        <th>Diploma Course</th>
                                        <th>Degree Course</th>
                                        <th>Program</th>
                                        <th>Rejected By</th>
                                        <th>Reason</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rejectedMappings as $mapping)
                                    <tr>
                                        <td>
                                            <span class="course-code">{{ $mapping->diploma_course_code }}</span>
                                            <div class="course-name">{{ Str::limit($mapping->diploma_course_name, 40) }}</div>
                                        </td>
                                        <td>
                                            <span class="course-code">{{ $mapping->degree_course_code }}</span>
                                            <div class="course-name">{{ Str::limit($mapping->degree_course_name, 40) }}</div>
                                        </td>
                                        <td><span class="badge-program">{{ $mapping->program_code }}</span></td>
                                        <td><span class="time-text">{{ $mapping->coordinator->name ?? 'N/A' }}</span></td>
                                        <td>
                                            <button type="button" class="btn-link-reason" data-bs-toggle="modal" data-bs-target="#reasonModal-{{ $mapping->id }}">
                                                <i class="fas fa-comment me-1"></i>View reason
                                            </button>
                                        </td>
                                        <td><span class="time-text">{{ $mapping->updated_at->format('d M Y') }}</span></td>
                                    </tr>

                                    <!-- Rejection Reason Modal -->
                                    <div class="modal fade" id="reasonModal-{{ $mapping->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header danger">
                                                    <h6 class="modal-title">Rejection Reason</h6>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="mb-0">{{ $mapping->rejection_reason }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Published Equivalency Lists -->
    <div class="industrial-card">
        <div class="industrial-card-header primary">
            <i class="fas fa-list"></i>
            <h5>Published Equivalency Lists (Read-Only)</h5>
        </div>
        <div class="industrial-card-body" style="padding: 1.5rem;">
            <!-- Internal Lists -->
            @if(!$internalLists->isEmpty())
                <div class="section-title">
                    <i class="fas fa-building"></i>
                    Internal Lists (CS110)
                </div>
                <div class="table-responsive mb-4">
                    <table class="industrial-table">
                        <thead>
                            <tr>
                                <th>Program</th>
                                <th>Semester</th>
                                <th>Mappings</th>
                                <th>Published By</th>
                                <th>Published Date</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($internalLists as $list)
                            <tr>
                                <td><span class="badge-program">{{ $list->program_code }}</span></td>
                                <td>{{ $list->semester }}</td>
                                <td>
                                    <span style="background: var(--info-light); color: var(--info); padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem; font-weight: 600;">
                                        {{ $list->total_mappings }} courses
                                    </span>
                                </td>
                                <td><span class="time-text">{{ $list->publisher->name ?? 'N/A' }}</span></td>
                                <td><span class="time-text">{{ $list->published_at ? $list->published_at->format('d M Y') : '-' }}</span></td>
                                <td class="text-center">
                                    <a href="{{ route('resource_person.equivalency_lists.show', $list) }}" class="btn-view">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <!-- External Lists -->
            @if(!$externalLists->isEmpty())
                <div class="section-title info-color">
                    <i class="fas fa-university"></i>
                    External Lists (Other Institutions)
                </div>
                <div class="table-responsive">
                    <table class="industrial-table">
                        <thead>
                            <tr>
                                <th>Program</th>
                                <th>Institution</th>
                                <th>Semester</th>
                                <th>Mappings</th>
                                <th>Published By</th>
                                <th>Published Date</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($externalLists as $list)
                            <tr>
                                <td><span class="badge-program">{{ $list->program_code }}</span></td>
                                <td><span class="time-text">{{ Str::limit($list->source_institution, 30) }}</span></td>
                                <td>{{ $list->semester }}</td>
                                <td>
                                    <span style="background: var(--info-light); color: var(--info); padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem; font-weight: 600;">
                                        {{ $list->total_mappings }} courses
                                    </span>
                                </td>
                                <td><span class="time-text">{{ $list->publisher->name ?? 'N/A' }}</span></td>
                                <td><span class="time-text">{{ $list->published_at ? $list->published_at->format('d M Y') : '-' }}</span></td>
                                <td class="text-center">
                                    <a href="{{ route('resource_person.equivalency_lists.show', $list) }}" class="btn-view">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            @if($internalLists->isEmpty() && $externalLists->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon" style="width: 80px; height: 80px;">
                        <i class="fas fa-inbox" style="font-size: 2rem;"></i>
                    </div>
                    <h5 style="color: var(--neutral-600); margin-bottom: 0.5rem;">No Published Lists</h5>
                    <p>No published equivalency lists are available for your assigned programs.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@if(session('success'))
<div class="toast-container">
    <div class="toast-industrial">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching
    const tabs = document.querySelectorAll('.industrial-tab');
    const panes = document.querySelectorAll('.tab-pane');

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const targetId = this.dataset.tab;

            // Remove active class from all tabs and panes
            tabs.forEach(t => t.classList.remove('active'));
            panes.forEach(p => p.classList.remove('active'));

            // Add active class to clicked tab and corresponding pane
            this.classList.add('active');
            document.getElementById(targetId).classList.add('active');
        });
    });

    // Auto-hide toast after 3 seconds
    const toast = document.querySelector('.toast-container');
    if (toast) {
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.3s ease';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
});
</script>
@endpush
