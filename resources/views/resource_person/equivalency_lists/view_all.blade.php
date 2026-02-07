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
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .page-header h2 {
        font-weight: 700;
        font-size: 1.75rem;
        margin-bottom: 0.35rem;
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
        padding: 0.5rem 1.25rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        border: none;
    }

    .btn-header.info {
        background: var(--info);
        color: white;
    }

    .btn-header.info:hover {
        background: #0f766e;
        color: white;
    }

    .btn-header.primary {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--uitm-blue-light) 100%);
        color: white;
    }

    .btn-header.primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(30, 58, 138, 0.3);
        color: white;
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
        align-items: center;
        gap: 1rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .stat-icon.primary {
        background: rgba(30, 58, 138, 0.1);
        color: var(--uitm-blue);
    }

    .stat-icon.success {
        background: rgba(5, 150, 105, 0.1);
        color: var(--success);
    }

    .stat-icon.warning {
        background: rgba(234, 88, 12, 0.1);
        color: var(--warning);
    }

    .stat-icon.info {
        background: rgba(13, 148, 136, 0.1);
        color: var(--info);
    }

    .stat-content h3 {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--industrial-dark);
        margin: 0;
    }

    .stat-content p {
        color: var(--industrial-gray);
        margin: 0;
        font-size: 0.9rem;
    }

    /* Main Card */
    .main-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        overflow: hidden;
    }

    .main-card-body {
        padding: 1.5rem;
    }

    /* Nav Tabs */
    .nav-tabs-industrial {
        border-bottom: 2px solid #e2e8f0;
        margin-bottom: 1.5rem;
    }

    .nav-tabs-industrial .nav-link {
        border: none;
        border-bottom: 3px solid transparent;
        color: var(--industrial-gray);
        font-weight: 500;
        padding: 1rem 1.5rem;
        margin-bottom: -2px;
        transition: all 0.2s ease;
    }

    .nav-tabs-industrial .nav-link:hover {
        color: var(--uitm-blue);
        border-bottom-color: rgba(30, 58, 138, 0.3);
    }

    .nav-tabs-industrial .nav-link.active {
        color: var(--uitm-blue);
        border-bottom-color: var(--uitm-blue);
        font-weight: 600;
    }

    .nav-tabs-industrial .badge {
        margin-left: 0.5rem;
        font-weight: 600;
    }

    /* Search Box */
    .search-box {
        margin-bottom: 1rem;
    }

    .search-box .input-group {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
    }

    .search-box .input-group-text {
        background: var(--industrial-light);
        border: none;
        color: var(--industrial-gray);
    }

    .search-box .form-control {
        border: none;
        padding: 0.75rem 1rem;
    }

    .search-box .form-control:focus {
        box-shadow: none;
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

    /* Accordion */
    .accordion-industrial .accordion-item {
        border: 1px solid #e2e8f0;
        border-radius: 12px !important;
        margin-bottom: 0.75rem;
        overflow: hidden;
    }

    .accordion-industrial .accordion-button {
        padding: 1rem 1.25rem;
        font-weight: 500;
        background: white;
    }

    .accordion-industrial .accordion-button:not(.collapsed) {
        background: var(--industrial-light);
        color: var(--industrial-dark);
        box-shadow: none;
    }

    .accordion-industrial .accordion-button:focus {
        box-shadow: none;
        border-color: transparent;
    }

    .accordion-industrial .accordion-body {
        padding: 0;
    }

    .program-badge {
        display: inline-block;
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--uitm-blue-light) 100%);
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.85rem;
    }

    .count-badge {
        display: inline-block;
        padding: 0.25rem 0.625rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .count-badge.info {
        background: rgba(13, 148, 136, 0.1);
        color: var(--info);
    }

    .count-badge.secondary {
        background: var(--industrial-light);
        color: var(--industrial-gray);
    }

    /* Inner Table */
    .inner-table {
        width: 100%;
        margin: 0;
    }

    .inner-table thead th {
        background: var(--industrial-light);
        color: var(--industrial-gray);
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 0.875rem 1rem;
        border-bottom: 2px solid var(--uitm-blue);
    }

    .inner-table tbody td {
        padding: 0.875rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .inner-table tbody tr:hover {
        background: rgba(30, 58, 138, 0.02);
    }

    .status-badge {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .status-badge.published {
        background: rgba(5, 150, 105, 0.1);
        color: var(--success);
    }

    .status-badge.draft {
        background: rgba(234, 88, 12, 0.1);
        color: var(--warning);
    }

    .status-badge.archived {
        background: var(--industrial-light);
        color: var(--industrial-gray);
    }

    .active-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8rem;
        background: rgba(5, 150, 105, 0.1);
        color: var(--success);
    }

    .btn-view {
        border: 2px solid var(--uitm-blue);
        color: var(--uitm-blue);
        padding: 0.35rem 0.875rem;
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.85rem;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    .btn-view:hover {
        background: var(--uitm-blue);
        color: white;
    }

    /* Toast */
    .toast-container {
        position: fixed;
        bottom: 1.5rem;
        right: 1.5rem;
        z-index: 1050;
    }

    .toast-industrial {
        background: white;
        border-radius: 12px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        overflow: hidden;
    }

    .toast-industrial .toast-header {
        padding: 0.75rem 1rem;
    }

    .toast-industrial .toast-header.success {
        background: var(--success);
        color: white;
    }

    .toast-industrial .toast-header.error {
        background: var(--danger);
        color: white;
    }

    .toast-industrial .toast-body {
        padding: 1rem;
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
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2><i class="fas fa-clipboard-list me-2"></i>All Equivalency Lists</h2>
            <p><i class="fas fa-list-alt me-2"></i>View all published and draft equivalency lists across all programs</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('resource_person.course_equivalencies.view') }}" class="btn-header info">
                <i class="fas fa-exchange-alt"></i> All Course Mappings
            </a>
            <a href="{{ route('resource_person.equivalency_lists.index') }}" class="btn-header primary">
                <i class="fas fa-list-alt"></i> My CS110 Lists
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon primary">
                <i class="fas fa-list-alt"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $stats['total_lists'] }}</h3>
                <p>Total Lists</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $stats['published_lists'] }}</h3>
                <p>Published</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $stats['draft_lists'] }}</h3>
                <p>Drafts</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon info">
                <i class="fas fa-paper-plane"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $stats['pending_mappings'] }}</h3>
                <p>Pending Mappings</p>
            </div>
        </div>
    </div>

    <!-- Tabbed Interface -->
    <div class="main-card">
        <div class="main-card-body">
            <!-- Navigation Tabs -->
            <ul class="nav nav-tabs nav-tabs-industrial" id="equivalencyTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="internal-tab" data-bs-toggle="tab" data-bs-target="#internal" type="button" role="tab">
                        <i class="fas fa-building me-2"></i>Internal Lists (CS110)
                        <span class="badge bg-primary">{{ $internalLists->flatten()->count() }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="external-tab" data-bs-toggle="tab" data-bs-target="#external" type="button" role="tab">
                        <i class="fas fa-university me-2"></i>External Lists
                        <span class="badge" style="background: var(--info);">{{ $externalLists->flatten()->count() }}</span>
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="equivalencyTabContent">

                <!-- INTERNAL LISTS TAB -->
                <div class="tab-pane fade show active" id="internal" role="tabpanel">
                    <!-- Search Box -->
                    <div class="search-box">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" id="search_internal" class="form-control" placeholder="Search by program code or name...">
                        </div>
                    </div>

                    @if($internalLists->isEmpty())
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <h5>No Internal Lists</h5>
                            <p>No internal equivalency lists available yet.</p>
                        </div>
                    @else
                        <!-- Accordion for Programs -->
                        <div class="accordion accordion-industrial" id="internalAccordion">
                            @foreach($internalLists as $programCode => $lists)
                                @php
                                    $totalCourses = $lists->sum('total_equivalencies');
                                    $accordionId = 'internal-' . str_replace(' ', '-', $programCode);
                                @endphp
                                <div class="accordion-item internal-item" data-program-code="{{ strtolower($programCode) }}" data-program-name="{{ strtolower($lists->first()->program_name ?? $programCode) }}">
                                    <h2 class="accordion-header" id="heading-{{ $accordionId }}">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $accordionId }}">
                                            <div class="d-flex justify-content-between align-items-center w-100 pe-3">
                                                <div>
                                                    <i class="fas fa-graduation-cap me-2" style="color: var(--uitm-blue);"></i>
                                                    <strong>{{ $programCode }}</strong>
                                                    <span class="text-muted ms-2">{{ $lists->first()->program_name ?? '' }}</span>
                                                </div>
                                                <div>
                                                    <span class="count-badge info me-2">{{ $totalCourses }} courses</span>
                                                    <span class="count-badge secondary">{{ $lists->count() }} list(s)</span>
                                                </div>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="collapse-{{ $accordionId }}" class="accordion-collapse collapse" data-bs-parent="#internalAccordion">
                                        <div class="accordion-body">
                                            <div class="table-responsive">
                                                <table class="inner-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Semester</th>
                                                            <th>Status</th>
                                                            <th>Mappings</th>
                                                            <th>Published By</th>
                                                            <th>Active</th>
                                                            <th class="text-end">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($lists as $list)
                                                        <tr>
                                                            <td><strong>{{ $list->semester }}</strong></td>
                                                            <td>
                                                                @if($list->published_at)
                                                                    <span class="status-badge published">Published</span>
                                                                    <br><small class="text-muted">{{ $list->published_at->format('d M Y') }}</small>
                                                                @elseif($list->status === 'draft')
                                                                    <span class="status-badge draft">Draft</span>
                                                                @elseif($list->status === 'archived')
                                                                    <span class="status-badge archived">Archived</span>
                                                                @endif
                                                            </td>
                                                            <td><span class="count-badge info">{{ $list->total_equivalencies }} courses</span></td>
                                                            <td><small>{{ $list->publisher->name ?? '-' }}</small></td>
                                                            <td>
                                                                @if($list->is_active)
                                                                    <span class="active-badge"><i class="fas fa-check-circle"></i> Active</span>
                                                                @else
                                                                    <span class="text-muted">-</span>
                                                                @endif
                                                            </td>
                                                            <td class="text-end">
                                                                <a href="{{ route('resource_person.equivalency_lists.show', $list) }}" class="btn-view" title="View">
                                                                    <i class="fas fa-eye"></i> View
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- EXTERNAL LISTS TAB -->
                <div class="tab-pane fade" id="external" role="tabpanel">
                    <!-- Search Box -->
                    <div class="search-box">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" id="search_external" class="form-control" placeholder="Search by institution name or program code...">
                        </div>
                    </div>

                    @if($externalLists->isEmpty())
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <h5>No External Lists</h5>
                            <p>No external equivalency lists available yet.</p>
                        </div>
                    @else
                        <!-- Accordion for Institutions -->
                        <div class="accordion accordion-industrial" id="externalAccordion">
                            @foreach($externalLists as $institution => $lists)
                                @php
                                    $totalCourses = $lists->sum('total_equivalencies');
                                    $accordionId = 'external-' . md5($institution);
                                @endphp
                                <div class="accordion-item external-item" data-institution="{{ strtolower($institution) }}">
                                    <h2 class="accordion-header" id="heading-{{ $accordionId }}">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $accordionId }}">
                                            <div class="d-flex justify-content-between align-items-center w-100 pe-3">
                                                <div>
                                                    <i class="fas fa-university me-2" style="color: var(--info);"></i>
                                                    <strong>{{ $institution }}</strong>
                                                </div>
                                                <div>
                                                    <span class="count-badge info me-2">{{ $totalCourses }} courses</span>
                                                    <span class="count-badge secondary">{{ $lists->count() }} list(s)</span>
                                                </div>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="collapse-{{ $accordionId }}" class="accordion-collapse collapse" data-bs-parent="#externalAccordion">
                                        <div class="accordion-body">
                                            <div class="table-responsive">
                                                <table class="inner-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Program</th>
                                                            <th>Semester</th>
                                                            <th>Status</th>
                                                            <th>Mappings</th>
                                                            <th>Published By</th>
                                                            <th>Active</th>
                                                            <th class="text-end">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($lists as $list)
                                                        <tr data-program-code="{{ strtolower($list->program_code) }}">
                                                            <td><span class="program-badge">{{ $list->program_code }}</span></td>
                                                            <td><strong>{{ $list->semester }}</strong></td>
                                                            <td>
                                                                @if($list->published_at)
                                                                    <span class="status-badge published">Published</span>
                                                                    <br><small class="text-muted">{{ $list->published_at->format('d M Y') }}</small>
                                                                @elseif($list->status === 'draft')
                                                                    <span class="status-badge draft">Draft</span>
                                                                @elseif($list->status === 'archived')
                                                                    <span class="status-badge archived">Archived</span>
                                                                @endif
                                                            </td>
                                                            <td><span class="count-badge info">{{ $list->total_equivalencies }} courses</span></td>
                                                            <td><small>{{ $list->publisher->name ?? '-' }}</small></td>
                                                            <td>
                                                                @if($list->is_active)
                                                                    <span class="active-badge"><i class="fas fa-check-circle"></i> Active</span>
                                                                @else
                                                                    <span class="text-muted">-</span>
                                                                @endif
                                                            </td>
                                                            <td class="text-end">
                                                                <a href="{{ route('resource_person.equivalency_lists.show', $list) }}" class="btn-view" title="View">
                                                                    <i class="fas fa-eye"></i> View
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notifications -->
@if(session('success'))
    <div class="toast-container">
        <div class="toast toast-industrial show" role="alert">
            <div class="toast-header success">
                <i class="fas fa-check-circle me-2"></i>
                <strong class="me-auto">Success</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                {{ session('success') }}
            </div>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="toast-container">
        <div class="toast toast-industrial show" role="alert">
            <div class="toast-header error">
                <i class="fas fa-exclamation-circle me-2"></i>
                <strong class="me-auto">Error</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                {{ session('error') }}
            </div>
        </div>
    </div>
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Internal Lists Search
    const searchInternal = document.getElementById('search_internal');
    if (searchInternal) {
        searchInternal.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const items = document.querySelectorAll('.internal-item');

            items.forEach(item => {
                const programCode = item.dataset.programCode || '';
                const programName = item.dataset.programName || '';

                if (programCode.includes(searchTerm) || programName.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }

    // External Lists Search
    const searchExternal = document.getElementById('search_external');
    if (searchExternal) {
        searchExternal.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const items = document.querySelectorAll('.external-item');

            items.forEach(item => {
                const institution = item.dataset.institution || '';

                // Also search within program codes in the table
                const rows = item.querySelectorAll('tbody tr');
                let hasMatch = institution.includes(searchTerm);

                if (!hasMatch) {
                    rows.forEach(row => {
                        const programCode = row.dataset.programCode || '';
                        if (programCode.includes(searchTerm)) {
                            hasMatch = true;
                        }
                    });
                }

                if (hasMatch) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }
});
</script>
@endpush
@endsection
