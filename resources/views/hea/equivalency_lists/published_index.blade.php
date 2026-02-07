@extends('layouts.app')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    :root {
        --uitm-primary: #1e3a8a;
        --uitm-primary-dark: #1e2d5b;
        --uitm-primary-light: #dbeafe;
        --uitm-amber: #f59e0b;
        --uitm-amber-light: #fef3c7;
        --uitm-green: #10b981;
        --uitm-green-light: #d1fae5;
        --uitm-red: #dc2626;
        --slate-50: #f8fafc;
        --slate-100: #f1f5f9;
        --slate-200: #e2e8f0;
        --slate-300: #cbd5e1;
        --slate-400: #94a3b8;
        --slate-500: #64748b;
        --slate-600: #475569;
        --slate-700: #334155;
        --slate-800: #1e293b;
        --font-sans: 'IBM Plex Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        --font-mono: 'IBM Plex Mono', monospace;
    }

    body {
        font-family: var(--font-sans);
    }

    /* ========== PAGE HEADER ========== */
    .page-header {
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        border-radius: 16px;
        position: relative;
        padding: 2rem 2.5rem;
        margin-bottom: 2rem;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image:
            linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px),
            linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px);
        background-size: 24px 24px;
        pointer-events: none;
    }

    .page-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--uitm-amber), var(--uitm-green), var(--uitm-amber));
    }

    .header-content {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1.5rem;
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 1.25rem;
    }

    .header-icon {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .header-icon i {
        font-size: 1.75rem;
        color: white;
    }

    .header-text .eyebrow {
        font-family: var(--font-mono);
        font-size: 0.7rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.2em;
        color: var(--uitm-amber);
        margin-bottom: 0.25rem;
    }

    .header-text h1 {
        font-size: 1.75rem;
        font-weight: 700;
        color: white;
        margin: 0;
        letter-spacing: -0.02em;
    }

    .header-text p {
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.75);
        margin: 0.25rem 0 0 0;
    }

    .header-action .btn-header {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: rgba(255, 255, 255, 0.15);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        text-decoration: none;
        transition: all 0.2s ease;
        backdrop-filter: blur(8px);
    }

    .header-action .btn-header:hover {
        background: rgba(255, 255, 255, 0.25);
        color: white;
        transform: translateY(-1px);
    }

    /* ========== PROGRAM TABS ========== */
    .program-tabs-wrapper {
        background: white;
        border-radius: 12px;
        padding: 0.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--slate-200);
    }

    .program-tabs {
        display: flex;
        gap: 0.25rem;
        flex-wrap: wrap;
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .program-tabs .nav-item {
        flex: 1;
        min-width: 140px;
    }

    .program-tabs .nav-link {
        display: block;
        padding: 0.875rem 1rem;
        text-align: center;
        color: var(--slate-600);
        font-weight: 600;
        font-size: 0.875rem;
        font-family: var(--font-mono);
        background: transparent;
        border: none;
        border-radius: 8px;
        transition: all 0.2s ease;
        width: 100%;
    }

    .program-tabs .nav-link:hover {
        background: var(--slate-100);
        color: var(--uitm-primary);
    }

    .program-tabs .nav-link.active {
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(30, 58, 138, 0.25);
    }

    /* ========== PROGRAM NAME BANNER ========== */
    .program-banner {
        background: linear-gradient(135deg, var(--uitm-primary-light) 0%, white 100%);
        border: 1px solid rgba(30, 58, 138, 0.15);
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .program-banner-icon {
        width: 44px;
        height: 44px;
        background: var(--uitm-primary);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .program-banner-icon i {
        color: white;
        font-size: 1.25rem;
    }

    .program-banner h4 {
        margin: 0;
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--uitm-primary);
    }

    /* ========== SECTION HEADER ========== */
    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.25rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin: 0;
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--slate-800);
    }

    .section-title-icon {
        width: 32px;
        height: 32px;
        background: var(--uitm-amber);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .section-title-icon i {
        color: white;
        font-size: 0.875rem;
    }

    .section-title-icon.history {
        background: var(--slate-500);
    }

    .btn-pdf {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        background: linear-gradient(135deg, var(--uitm-red) 0%, #b91c1c 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-pdf:hover {
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
    }

    /* ========== LIST INFO CARD ========== */
    .list-info-card {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--slate-200);
        overflow: hidden;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .list-info-header {
        background: var(--slate-50);
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--slate-200);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .list-info-title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--slate-700);
        margin: 0;
    }

    .list-info-title i {
        color: var(--uitm-primary);
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.375rem 0.875rem;
        background: linear-gradient(135deg, var(--uitm-green) 0%, #059669 100%);
        color: white;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        font-family: var(--font-mono);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .list-info-body {
        padding: 1.5rem;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }

    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
        }
    }

    .info-table {
        width: 100%;
        font-size: 0.875rem;
    }

    .info-table tr {
        border-bottom: 1px solid var(--slate-100);
    }

    .info-table tr:last-child {
        border-bottom: none;
    }

    .info-table th {
        padding: 0.625rem 0;
        font-weight: 500;
        color: var(--slate-500);
        text-align: left;
        width: 140px;
        vertical-align: top;
    }

    .info-table td {
        padding: 0.625rem 0;
        color: var(--slate-800);
        font-weight: 500;
    }

    .badge-program {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.625rem;
        background: var(--uitm-primary);
        color: white;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
        font-family: var(--font-mono);
    }

    .badge-category {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.625rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-category.internal {
        background: var(--uitm-primary-light);
        color: var(--uitm-primary);
    }

    .badge-category.external {
        background: #e0f2fe;
        color: #0369a1;
    }

    .badge-status-published {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.625rem;
        background: var(--uitm-green-light);
        color: #059669;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    /* ========== COURSE MAPPINGS TABLE ========== */
    .mappings-card {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--slate-200);
        overflow: hidden;
        margin-bottom: 2rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .mappings-header {
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .mappings-header h6 {
        margin: 0;
        font-size: 0.9375rem;
        font-weight: 600;
        color: white;
    }

    .mappings-header i {
        color: var(--uitm-amber);
    }

    .mappings-table {
        width: 100%;
        font-size: 0.875rem;
    }

    .mappings-table thead {
        background: var(--slate-50);
    }

    .mappings-table thead th {
        padding: 1rem 1.25rem;
        font-weight: 600;
        color: var(--slate-600);
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.08em;
        font-family: var(--font-mono);
        border-bottom: 1px solid var(--slate-200);
    }

    .mappings-table tbody tr {
        border-bottom: 1px solid var(--slate-100);
        transition: background 0.15s ease;
    }

    .mappings-table tbody tr:last-child {
        border-bottom: none;
    }

    .mappings-table tbody tr:hover {
        background: var(--slate-50);
    }

    .mappings-table td {
        padding: 1rem 1.25rem;
        vertical-align: middle;
    }

    .row-number {
        color: var(--slate-400);
        font-family: var(--font-mono);
        font-size: 0.8125rem;
    }

    .course-code {
        font-family: var(--font-mono);
        font-weight: 600;
        color: var(--slate-800);
        font-size: 0.875rem;
    }

    .course-name {
        color: var(--slate-500);
        font-size: 0.8125rem;
        margin-top: 0.125rem;
    }

    .match-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.375rem 0.75rem;
        background: linear-gradient(135deg, var(--uitm-green) 0%, #059669 100%);
        color: white;
        border-radius: 20px;
        font-size: 0.8125rem;
        font-weight: 700;
        font-family: var(--font-mono);
    }

    .credit-arrow {
        color: var(--slate-400);
        font-family: var(--font-mono);
        font-size: 0.8125rem;
    }

    /* ========== EMPTY STATE ========== */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-state-icon {
        width: 80px;
        height: 80px;
        background: var(--slate-100);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }

    .empty-state-icon i {
        font-size: 2rem;
        color: var(--slate-400);
    }

    .empty-state h5 {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--slate-600);
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: var(--slate-500);
        font-size: 0.9375rem;
        margin: 0;
    }

    /* ========== HISTORY SECTION ========== */
    .history-card {
        background: white;
        border: 1px solid var(--slate-200);
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 1rem;
        transition: all 0.2s ease;
    }

    .history-card:hover {
        border-color: var(--slate-300);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
    }

    .history-card-body {
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .history-info {
        flex: 1;
        min-width: 200px;
    }

    .history-semester {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1rem;
        font-weight: 600;
        color: var(--slate-800);
        margin-bottom: 0.5rem;
    }

    .history-semester i {
        color: var(--slate-400);
    }

    .history-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .history-meta {
        display: flex;
        align-items: center;
        gap: 0.375rem;
        font-size: 0.8125rem;
        color: var(--slate-500);
    }

    .history-actions {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .badge-archived {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.5rem 1rem;
        background: var(--slate-100);
        color: var(--slate-600);
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        font-family: var(--font-mono);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .btn-view-details {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.5rem 1rem;
        background: white;
        color: var(--slate-600);
        border: 1px solid var(--slate-300);
        border-radius: 6px;
        font-size: 0.8125rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-view-details:hover {
        background: var(--slate-50);
        color: var(--uitm-primary);
        border-color: var(--uitm-primary);
    }

    .badge-courses {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.625rem;
        background: var(--slate-100);
        color: var(--slate-600);
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    /* ========== RESPONSIVE ========== */
    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
        }

        .header-content {
            flex-direction: column;
            text-align: center;
        }

        .header-left {
            flex-direction: column;
        }

        .header-text h1 {
            font-size: 1.375rem;
        }

        .section-header {
            flex-direction: column;
            align-items: stretch;
        }

        .history-card-body {
            flex-direction: column;
            align-items: stretch;
        }

        .history-actions {
            justify-content: flex-end;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Page Header -->
    <header class="page-header">
        <div class="header-content">
            <div class="header-left">
                <div class="header-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div class="header-text">
                    <div class="eyebrow">Higher Education Authority</div>
                    <h1>Published Equivalency Lists</h1>
                    <p>View published course equivalency lists organized by degree program</p>
                </div>
            </div>
            <div class="header-action">
                <a href="{{ route('hea.course_equivalencies.view') }}" class="btn-header">
                    <i class="fas fa-search"></i>
                    All Course Mappings
                </a>
            </div>
        </div>
    </header>

    <!-- Program Tabs -->
    <div class="program-tabs-wrapper">
        <ul class="program-tabs nav" id="programTabs" role="tablist">
            @foreach($programs as $index => $programCode)
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $latestPublishedProgram === $programCode ? 'active' : '' }}"
                        id="{{ strtolower($programCode) }}-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#{{ strtolower($programCode) }}"
                        type="button"
                        role="tab">
                    {{ $programCode }}
                </button>
            </li>
            @endforeach
        </ul>
    </div>

    <!-- Tab Content -->
    <div class="tab-content" id="programTabsContent">
        @foreach($programs as $index => $programCode)
        <div class="tab-pane fade {{ $latestPublishedProgram === $programCode ? 'show active' : '' }}"
             id="{{ strtolower($programCode) }}"
             role="tabpanel">

            <!-- Program Name Banner -->
            <div class="program-banner">
                <div class="program-banner-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h4>{{ $programData[$programCode]['name'] }}</h4>
            </div>

            @if($programData[$programCode]['current'] || $programData[$programCode]['history']->isNotEmpty())

                <!-- Current Active List -->
                @if($programData[$programCode]['current'])
                @php $list = $programData[$programCode]['current']; @endphp
                <div class="mb-4">
                    <!-- Section Header -->
                    <div class="section-header">
                        <h5 class="section-title">
                            <span class="section-title-icon">
                                <i class="fas fa-star"></i>
                            </span>
                            Current Active List
                        </h5>
                        <a href="{{ route('hea.equivalency_lists.pdf', $list) }}"
                           class="btn-pdf"
                           target="_blank">
                            <i class="fas fa-file-pdf"></i>
                            View as PDF
                        </a>
                    </div>

                    <!-- List Information Card -->
                    <div class="list-info-card">
                        <div class="list-info-header">
                            <h6 class="list-info-title">
                                <i class="fas fa-info-circle"></i>
                                List Information
                            </h6>
                            @if($list->is_active)
                                <span class="status-badge">
                                    <i class="fas fa-check-circle"></i>
                                    Published & Active
                                </span>
                            @endif
                        </div>
                        <div class="list-info-body">
                            <div class="info-grid">
                                <table class="info-table">
                                    <tr>
                                        <th>Program Code:</th>
                                        <td><span class="badge-program">{{ $list->program_code }}</span></td>
                                    </tr>
                                    <tr>
                                        <th>Program Name:</th>
                                        <td>{{ $list->program_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Category:</th>
                                        <td>
                                            @if($list->category === 'internal')
                                                <span class="badge-category internal">Internal (CS110)</span>
                                            @else
                                                <span class="badge-category external">External Institution</span>
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
                                        <td><strong>{{ $list->semester }}</strong></td>
                                    </tr>
                                </table>
                                <table class="info-table">
                                    <tr>
                                        <th>Status:</th>
                                        <td>
                                            <span class="badge-status-published">
                                                <i class="fas fa-check"></i> Published
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Total Mappings:</th>
                                        <td>
                                            <strong>{{ $list->courseEquivalencies->where('is_eligible', true)->count() }}</strong> course(s)
                                            <small class="text-success">(eligible only)</small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Resource Person:</th>
                                        <td>
                                            @php
                                                $resourcePersons = [
                                                    'CDCS253' => 'Norshahidatul Hasana Binti Ishak',
                                                    'CDCS255' => 'Ts. Nurul Najwa Binti Abdul Rahid',
                                                    'CDCS266' => 'Noor Afni Binti Deraman',
                                                    'CDCS230' => 'Fadzlin Binti Ahmadon',
                                                    'CDCS251' => 'Nor Aimuni Binti Md Rashid',
                                                ];
                                                $resourcePersonName = $resourcePersons[$list->program_code] ?? ($list->creator->name ?? 'N/A');
                                            @endphp
                                            <strong>{{ $resourcePersonName }}</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Created At:</th>
                                        <td>{{ $list->created_at->format('d M Y, h:i A') }}</td>
                                    </tr>
                                    @if($list->published_at)
                                        <tr>
                                            <th>Published By:</th>
                                            <td>{{ $list->publisher->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Published At:</th>
                                            <td>{{ $list->published_at->format('d M Y, h:i A') }}</td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Course Mappings -->
                    <div class="mappings-card">
                        <div class="mappings-header">
                            <i class="fas fa-exchange-alt"></i>
                            <h6>Course Equivalency Mappings</h6>
                        </div>
                        @php
                            $eligibleMappings = $list->courseEquivalencies->where('is_eligible', true)->sortBy('diploma_course_code');
                        @endphp
                        @if($eligibleMappings->isEmpty())
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-inbox"></i>
                                </div>
                                <h5>No Eligible Course Mappings</h5>
                                <p>This equivalency list does not have any eligible course mappings (≥80% match) yet.</p>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="mappings-table">
                                    <thead>
                                        <tr>
                                            <th width="5%" class="text-center">#</th>
                                            <th width="38%">Diploma Course</th>
                                            <th width="38%">Degree Course</th>
                                            <th width="12%" class="text-center">Match %</th>
                                            <th width="7%" class="text-center">Credits</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($eligibleMappings as $index => $eq)
                                        <tr>
                                            <td class="text-center">
                                                <span class="row-number">{{ $loop->iteration }}</span>
                                            </td>
                                            <td>
                                                <div class="course-code">{{ $eq->diploma_course_code }}</div>
                                                <div class="course-name">{{ $eq->diploma_course_name }}</div>
                                            </td>
                                            <td>
                                                <div class="course-code">{{ $eq->degree_course_code }}</div>
                                                <div class="course-name">{{ $eq->degree_course_name }}</div>
                                            </td>
                                            <td class="text-center">
                                                <span class="match-badge">{{ number_format($eq->match_percentage, 0) }}%</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="credit-arrow">{{ $eq->diploma_credit_hour }} → {{ $eq->degree_credit_hour }}</span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- History Section -->
                @if($programData[$programCode]['history']->isNotEmpty())
                <div class="mb-4">
                    <!-- Section Header -->
                    <div class="section-header">
                        <h5 class="section-title">
                            <span class="section-title-icon history">
                                <i class="fas fa-history"></i>
                            </span>
                            History (Previous Published Lists)
                        </h5>
                    </div>

                    @foreach($programData[$programCode]['history'] as $list)
                    <div class="history-card">
                        <div class="history-card-body">
                            <div class="history-info">
                                <div class="history-semester">
                                    <i class="fas fa-calendar-alt"></i>
                                    {{ $list->semester }}
                                </div>
                                <div class="history-badges">
                                    <span class="badge-category {{ $list->category === 'internal' ? 'internal' : 'external' }}">
                                        {{ $list->category === 'internal' ? 'UiTM CS110' : 'External - ' . $list->source_institution }}
                                    </span>
                                    <span class="badge-courses">
                                        <i class="fas fa-book"></i>
                                        {{ $list->courseEquivalencies->count() }} courses
                                    </span>
                                </div>
                                <div class="history-meta">
                                    <i class="fas fa-user"></i>
                                    Published by {{ $list->publisher->name ?? 'N/A' }}
                                    on {{ $list->published_at ? $list->published_at->format('d M Y') : 'N/A' }}
                                </div>
                            </div>
                            <div class="history-actions">
                                <span class="badge-archived">
                                    <i class="fas fa-archive"></i>
                                    Archived
                                </span>
                                <a href="{{ route('hea.equivalency_lists.show', $list) }}"
                                   class="btn-view-details">
                                    <i class="fas fa-eye"></i>
                                    View Details
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
                    <div class="empty-state-icon">
                        <i class="fas fa-inbox"></i>
                    </div>
                    <h5>No Published Lists Yet</h5>
                    <p>No equivalency lists have been published for this program yet.</p>
                </div>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endsection
