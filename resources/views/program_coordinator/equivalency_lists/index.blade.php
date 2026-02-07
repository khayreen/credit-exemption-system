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
        margin-bottom: 0;
    }

    .btn-search {
        background: var(--uitm-amber);
        border: none;
        color: var(--industrial-dark);
        padding: 0.625rem 1.25rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .btn-search:hover {
        background: #fbbf24;
        color: var(--industrial-dark);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4);
    }

    /* Program Tabs */
    .program-tabs {
        background: white;
        border-radius: 16px;
        padding: 0.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .program-tabs .nav-link {
        background: var(--industrial-light);
        border: 2px solid transparent;
        color: var(--industrial-gray);
        font-weight: 600;
        font-family: 'IBM Plex Mono', monospace;
        padding: 0.75rem 1.25rem;
        border-radius: 10px;
        transition: all 0.2s ease;
    }

    .program-tabs .nav-link:hover {
        background: #e2e8f0;
        color: var(--uitm-blue);
    }

    .program-tabs .nav-link.active {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--uitm-blue-light) 100%);
        color: white;
        border-color: transparent;
        box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
    }

    .program-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        padding: 1rem 1.25rem;
        background: white;
        border-radius: 12px;
        border-left: 4px solid var(--uitm-blue);
        box-shadow: 0 2px 10px rgba(0,0,0,0.04);
    }

    .program-title i {
        color: var(--uitm-amber);
        font-size: 1.25rem;
    }

    .program-title h4 {
        margin: 0;
        color: var(--uitm-blue);
        font-weight: 600;
    }

    /* Section Headers */
    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--uitm-amber);
    }

    .section-header h5 {
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        color: var(--industrial-dark);
    }

    .section-header h5 i {
        color: var(--uitm-amber);
    }

    /* List Information Card */
    .list-info-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .list-info-header {
        background: linear-gradient(135deg, var(--industrial-light) 0%, #e2e8f0 100%);
        padding: 1rem 1.25rem;
        border-bottom: 2px solid var(--uitm-blue);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .list-info-header h6 {
        margin: 0;
        font-weight: 600;
        color: var(--industrial-dark);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .list-info-header h6 i {
        color: var(--uitm-blue);
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .status-badge.published {
        background: linear-gradient(135deg, var(--success) 0%, #10b981 100%);
        color: white;
    }

    .list-info-body {
        padding: 1.25rem;
    }

    .info-table {
        width: 100%;
    }

    .info-table tr {
        border-bottom: 1px solid #f1f5f9;
    }

    .info-table tr:last-child {
        border-bottom: none;
    }

    .info-table th {
        color: var(--industrial-gray);
        font-weight: 500;
        font-size: 0.85rem;
        padding: 0.5rem 0;
        width: 150px;
    }

    .info-table td {
        color: var(--industrial-dark);
        padding: 0.5rem 0;
    }

    .category-badge {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .category-badge.internal {
        background: rgba(30, 58, 138, 0.1);
        color: var(--uitm-blue);
        border: 1px solid var(--uitm-blue);
    }

    .category-badge.external {
        background: rgba(13, 148, 136, 0.1);
        color: var(--info);
        border: 1px solid var(--info);
    }

    .program-code-badge {
        display: inline-block;
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--uitm-blue-light) 100%);
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 600;
        font-size: 0.8rem;
    }

    /* Mappings Card */
    .mappings-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .mappings-header {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--industrial-dark) 100%);
        color: white;
        padding: 1rem 1.25rem;
    }

    .mappings-header h6 {
        margin: 0;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .mappings-table {
        width: 100%;
        margin: 0;
    }

    .mappings-table thead th {
        background: var(--industrial-light);
        color: var(--industrial-gray);
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 0.875rem 1rem;
        border-bottom: 2px solid #e2e8f0;
    }

    .mappings-table tbody td {
        padding: 1rem;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .mappings-table tbody tr:hover {
        background: rgba(30, 58, 138, 0.02);
    }

    .course-code {
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 600;
        color: var(--uitm-blue);
    }

    .course-name {
        font-size: 0.85rem;
        color: var(--industrial-gray);
    }

    .institution-info {
        font-size: 0.8rem;
        color: var(--industrial-gray);
    }

    .match-badge {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .match-badge.high {
        background: rgba(5, 150, 105, 0.1);
        color: var(--success);
        border: 1px solid var(--success);
    }

    .match-badge.low {
        background: rgba(234, 88, 12, 0.1);
        color: var(--warning);
        border: 1px solid var(--warning);
    }

    .credit-info {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.85rem;
        color: var(--industrial-gray);
    }

    .eligible-icon {
        font-size: 1.25rem;
    }

    .mappings-footer {
        background: var(--industrial-light);
        padding: 1rem 1.25rem;
        border-top: 1px solid #e2e8f0;
    }

    .stats-row {
        display: flex;
        justify-content: center;
        gap: 3rem;
        flex-wrap: wrap;
    }

    .stat-item {
        text-align: center;
    }

    .stat-item .label {
        font-size: 0.8rem;
        color: var(--industrial-gray);
        margin-bottom: 0.25rem;
    }

    .stat-item .count {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 6px;
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 600;
    }

    /* History Card */
    .history-card {
        background: white;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding: 1.25rem;
        margin-bottom: 1rem;
        transition: all 0.2s ease;
    }

    .history-card:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transform: translateY(-2px);
    }

    .history-semester {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        color: var(--industrial-dark);
        margin-bottom: 0.5rem;
    }

    .history-semester i {
        color: var(--industrial-gray);
    }

    .history-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .history-meta {
        font-size: 0.8rem;
        color: var(--industrial-gray);
    }

    .archived-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.4rem 0.875rem;
        background: var(--industrial-gray);
        color: white;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .btn-view-details {
        background: white;
        border: 2px solid var(--industrial-gray);
        color: var(--industrial-gray);
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn-view-details:hover {
        background: var(--industrial-gray);
        color: white;
    }

    .btn-pdf {
        background: var(--danger);
        border: none;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn-pdf:hover {
        background: #b91c1c;
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
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #94a3b8;
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
            margin: -1rem -1rem 1.5rem -1rem;
            border-radius: 0 0 16px 16px;
        }

        .program-tabs {
            flex-direction: column;
        }

        .program-tabs .nav-link {
            text-align: center;
        }

        .stats-row {
            gap: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2><i class="fas fa-clipboard-list me-2"></i>Published Equivalency Lists</h2>
                <p><i class="fas fa-info-circle me-2"></i>View published course equivalency lists organized by degree program</p>
            </div>
            <div>
                <a href="{{ route('program_coordinator.course_equivalencies.view') }}" class="btn btn-search">
                    <i class="fas fa-search me-2"></i>Search All Course Mappings
                </a>
            </div>
        </div>
    </div>

    <!-- Program Tabs -->
    <div class="program-tabs" id="programTabs" role="tablist">
        @foreach($programs as $index => $programCode)
            <button class="nav-link {{ $latestPublishedProgram === $programCode ? 'active' : '' }}"
                    id="{{ strtolower($programCode) }}-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#{{ strtolower($programCode) }}"
                    type="button"
                    role="tab">
                {{ $programCode }}
            </button>
        @endforeach
    </div>

    <div class="tab-content" id="programTabsContent">
        @foreach($programs as $index => $programCode)
        <div class="tab-pane fade {{ $latestPublishedProgram === $programCode ? 'show active' : '' }}"
             id="{{ strtolower($programCode) }}"
             role="tabpanel">

            <!-- Program Title -->
            <div class="program-title">
                <i class="fas fa-graduation-cap"></i>
                <h4>{{ $programData[$programCode]['name'] }}</h4>
            </div>

            @if($programData[$programCode]['current'] || $programData[$programCode]['history']->isNotEmpty())

                <!-- Current Active List -->
                @if($programData[$programCode]['current'])
                <div class="mb-4">
                    <div class="section-header">
                        <h5><i class="fas fa-star"></i> Current Active List</h5>
                        @php $list = $programData[$programCode]['current']; @endphp
                        <a href="{{ route('program_coordinator.equivalency_lists.pdf', $list) }}"
                           class="btn btn-pdf"
                           target="_blank">
                            <i class="fas fa-file-pdf me-2"></i>View as PDF
                        </a>
                    </div>

                    <!-- List Information Card -->
                    <div class="list-info-card">
                        <div class="list-info-header">
                            <h6><i class="fas fa-info-circle"></i> List Information</h6>
                            @if($list->is_active)
                                <span class="status-badge published">
                                    <i class="fas fa-check-circle"></i> PUBLISHED & ACTIVE
                                </span>
                            @endif
                        </div>
                        <div class="list-info-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="info-table">
                                        <tr>
                                            <th>Program Code:</th>
                                            <td><span class="program-code-badge">{{ $list->program_code }}</span></td>
                                        </tr>
                                        <tr>
                                            <th>Program Name:</th>
                                            <td>{{ $list->program_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Category:</th>
                                            <td>
                                                @if($list->category === 'internal')
                                                    <span class="category-badge internal">Internal (CS110)</span>
                                                @else
                                                    <span class="category-badge external">External Institution</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @if($list->category === 'external')
                                            <tr>
                                                <th>Source Institution:</th>
                                                <td>{{ $list->source_institution }}</td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <th>Semester:</th>
                                            <td>{{ $list->semester }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="info-table">
                                        <tr>
                                            <th>Status:</th>
                                            <td><span class="status-badge published" style="font-size: 0.75rem; padding: 0.35rem 0.75rem;">Published</span></td>
                                        </tr>
                                        <tr>
                                            <th>Total Mappings:</th>
                                            <td><strong style="font-family: 'IBM Plex Mono', monospace;">{{ $list->total_mappings }}</strong> course(s)</td>
                                        </tr>
                                        <tr>
                                            <th>Created By:</th>
                                            <td>{{ $list->creator->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Created At:</th>
                                            <td style="font-family: 'IBM Plex Mono', monospace; font-size: 0.9rem;">{{ $list->created_at->format('d M Y, h:i A') }}</td>
                                        </tr>
                                        @if($list->published_at)
                                            <tr>
                                                <th>Published By:</th>
                                                <td>{{ $list->publisher->name ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Published At:</th>
                                                <td style="font-family: 'IBM Plex Mono', monospace; font-size: 0.9rem;">{{ $list->published_at->format('d M Y, h:i A') }}</td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Course Mappings -->
                    <div class="mappings-card">
                        <div class="mappings-header">
                            <h6><i class="fas fa-exchange-alt"></i> Course Equivalency Mappings</h6>
                        </div>
                        @if($list->courseEquivalencies->isEmpty())
                            <div class="empty-state">
                                <i class="fas fa-inbox"></i>
                                <h5>No Course Mappings</h5>
                                <p>This equivalency list does not have any course mappings yet.</p>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="mappings-table">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 50px;">#</th>
                                            <th>Diploma Course</th>
                                            <th>Degree Course</th>
                                            <th class="text-center">Match %</th>
                                            <th class="text-center">Credit Hours</th>
                                            <th class="text-center">Eligible</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($list->courseEquivalencies->sortBy('diploma_course_code') as $index => $eq)
                                        <tr>
                                            <td class="text-center text-muted">{{ $index + 1 }}</td>
                                            <td>
                                                <div class="course-code">{{ $eq->diploma_course_code }}</div>
                                                <div class="course-name">{{ $eq->diploma_course_name }}</div>
                                                <div class="institution-info">
                                                    <i class="fas fa-university me-1"></i>{{ $eq->diploma_institution }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="course-code">{{ $eq->degree_course_code }}</div>
                                                <div class="course-name">{{ $eq->degree_course_name }}</div>
                                            </td>
                                            <td class="text-center">
                                                <span class="match-badge {{ $eq->match_percentage >= 80 ? 'high' : 'low' }}">
                                                    {{ number_format($eq->match_percentage, 0) }}%
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="credit-info">
                                                    {{ $eq->diploma_credit_hour }} → {{ $eq->degree_credit_hour }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @if($eq->is_eligible)
                                                    <i class="fas fa-check-circle eligible-icon" style="color: var(--success);"></i>
                                                @else
                                                    <i class="fas fa-times-circle eligible-icon" style="color: var(--danger);"></i>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Statistics Footer -->
                            <div class="mappings-footer">
                                <div class="stats-row">
                                    <div class="stat-item">
                                        <div class="label">Total Mappings</div>
                                        <span class="count" style="background: rgba(30, 58, 138, 0.1); color: var(--uitm-blue);">{{ $list->courseEquivalencies->count() }}</span>
                                    </div>
                                    <div class="stat-item">
                                        <div class="label">Eligible (≥80%)</div>
                                        <span class="count" style="background: rgba(5, 150, 105, 0.1); color: var(--success);">{{ $list->courseEquivalencies->where('is_eligible', true)->count() }}</span>
                                    </div>
                                    <div class="stat-item">
                                        <div class="label">Not Eligible (&lt;80%)</div>
                                        <span class="count" style="background: rgba(234, 88, 12, 0.1); color: var(--warning);">{{ $list->courseEquivalencies->where('is_eligible', false)->count() }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- History -->
                @if($programData[$programCode]['history']->isNotEmpty())
                <div class="mb-4">
                    <div class="section-header">
                        <h5><i class="fas fa-history"></i> History (Previous Published Lists)</h5>
                    </div>
                    @foreach($programData[$programCode]['history'] as $list)
                    <div class="history-card">
                        <div class="row align-items-center">
                            <div class="col-md-7">
                                <div class="history-semester">
                                    <i class="fas fa-calendar-alt"></i>
                                    {{ $list->semester }}
                                </div>
                                <div class="history-badges">
                                    <span class="category-badge {{ $list->category === 'internal' ? 'internal' : 'external' }}">
                                        {{ $list->category === 'internal' ? 'UiTM CS110' : 'External - ' . $list->source_institution }}
                                    </span>
                                    <span class="category-badge" style="background: var(--industrial-light); color: var(--industrial-gray); border: 1px solid #e2e8f0;">
                                        <i class="fas fa-book me-1"></i>{{ $list->courseEquivalencies->count() }} courses
                                    </span>
                                </div>
                                <div class="history-meta">
                                    <i class="fas fa-user me-1"></i>Published by {{ $list->publisher->name ?? 'N/A' }}
                                    on {{ $list->published_at ? $list->published_at->format('d M Y') : 'N/A' }}
                                </div>
                            </div>
                            <div class="col-md-5 text-md-end mt-3 mt-md-0">
                                <span class="archived-badge me-2">
                                    <i class="fas fa-archive"></i> ARCHIVED
                                </span>
                                <a href="{{ route('program_coordinator.equivalency_lists.show', $list) }}"
                                   class="btn btn-view-details">
                                    <i class="fas fa-eye me-1"></i>View Details
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

            @else
                <!-- Empty State -->
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h5>No Published Lists Yet</h5>
                    <p>No equivalency lists have been published for this program yet.</p>
                </div>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endsection
