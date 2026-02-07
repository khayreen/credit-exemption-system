@extends('layouts.app')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap');

    :root {
        --uitm-blue: #1e3a8a;
        --uitm-blue-light: #3b82f6;
        --uitm-amber: #f59e0b;
        --industrial-dark: #0f172a;
        --industrial-gray: #334155;
        --industrial-light: #f1f5f9;
        --success-color: #059669;
        --danger-color: #dc2626;
        --warning-color: #ea580c;
        --info-color: #0d9488;
    }

    .grouped-lists-page {
        font-family: 'IBM Plex Sans', sans-serif;
        background: var(--industrial-light);
        min-height: 100vh;
        padding: 2rem 0;
    }

    /* Page Header */
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
        top: 0;
        right: 0;
        width: 300px;
        height: 100%;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='4'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    .page-header h1 {
        color: #fff;
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
    }

    .page-header p {
        color: rgba(255, 255, 255, 0.8);
        margin: 0;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .header-actions {
        position: absolute;
        top: 50%;
        right: 2rem;
        transform: translateY(-50%);
        display: flex;
        gap: 0.75rem;
    }

    .btn-header {
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
        font-size: 0.9rem;
    }

    .btn-header-outline {
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .btn-header-outline:hover {
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
    }

    .btn-header-info {
        background: var(--info-color);
        color: #fff;
        border: none;
    }

    .btn-header-info:hover {
        background: #0f766e;
        color: #fff;
    }

    .pending-badge {
        background: var(--danger-color);
        color: #fff;
        padding: 0.125rem 0.5rem;
        border-radius: 10px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    /* Filter Card */
    .filter-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid var(--uitm-blue);
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
    }

    .filter-row {
        display: flex;
        align-items: flex-end;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .filter-group {
        min-width: 200px;
    }

    .filter-group label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--industrial-dark);
        margin-bottom: 0.5rem;
    }

    .filter-group label i {
        color: var(--uitm-blue);
        margin-right: 0.375rem;
    }

    .filter-group select {
        width: 100%;
        padding: 0.5rem 0.75rem;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.875rem;
        background: #fff;
        color: var(--industrial-dark);
        transition: all 0.2s ease;
    }

    .filter-group select:focus {
        outline: none;
        border-color: var(--uitm-blue);
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
    }

    .filter-actions {
        flex: 1;
        text-align: right;
    }

    .btn-clear-filter {
        background: var(--industrial-light);
        color: var(--industrial-gray);
        border: 1px solid #e2e8f0;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.875rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        transition: all 0.2s ease;
    }

    .btn-clear-filter:hover {
        background: #e2e8f0;
        color: var(--industrial-dark);
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        background: #fff;
        border-radius: 12px;
        padding: 1.25rem;
        border: 1px solid #e2e8f0;
        transition: all 0.2s ease;
    }

    .stat-card.clickable {
        cursor: pointer;
    }

    .stat-card.clickable:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .stat-card.active {
        background: var(--industrial-light);
        border-color: var(--uitm-blue);
    }

    .stat-content {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .stat-icon.blue {
        background: rgba(30, 58, 138, 0.1);
        color: var(--uitm-blue);
    }

    .stat-icon.green {
        background: rgba(5, 150, 105, 0.1);
        color: var(--success-color);
    }

    .stat-icon.purple {
        background: rgba(139, 92, 246, 0.1);
        color: #8b5cf6;
    }

    .stat-icon.orange {
        background: rgba(234, 88, 12, 0.1);
        color: var(--warning-color);
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        font-family: 'IBM Plex Mono', monospace;
        color: var(--industrial-dark);
        line-height: 1;
    }

    .stat-label {
        font-size: 0.875rem;
        color: var(--industrial-gray);
        margin-top: 0.25rem;
    }

    /* Main Content Card */
    .content-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .content-body {
        padding: 1.5rem;
    }

    /* Custom Tabs */
    .custom-tabs {
        display: flex;
        gap: 0.5rem;
        padding: 0 1.5rem;
        background: var(--industrial-light);
        border-bottom: 1px solid #e2e8f0;
    }

    .custom-tab {
        padding: 1rem 1.5rem;
        font-weight: 500;
        color: var(--industrial-gray);
        background: none;
        border: none;
        border-bottom: 3px solid transparent;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
    }

    .custom-tab:hover {
        color: var(--uitm-blue);
    }

    .custom-tab.active {
        color: var(--uitm-blue);
        border-bottom-color: var(--uitm-blue);
    }

    .tab-badge {
        padding: 0.25rem 0.625rem;
        border-radius: 10px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .tab-badge.success {
        background: rgba(5, 150, 105, 0.1);
        color: var(--success-color);
    }

    .tab-badge.secondary {
        background: var(--industrial-light);
        color: var(--industrial-gray);
    }

    /* Search Box */
    .search-box {
        position: relative;
        margin-bottom: 1.25rem;
    }

    .search-box i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--industrial-gray);
    }

    .search-box input {
        width: 100%;
        padding: 0.75rem 1rem 0.75rem 2.75rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }

    .search-box input:focus {
        outline: none;
        border-color: var(--uitm-blue);
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-icon {
        width: 80px;
        height: 80px;
        background: var(--industrial-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }

    .empty-icon i {
        font-size: 2rem;
        color: var(--industrial-gray);
    }

    .empty-state h5 {
        color: var(--industrial-dark);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: var(--industrial-gray);
        margin: 0;
    }

    /* Accordion */
    .custom-accordion {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .accordion-item {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
    }

    .accordion-header {
        margin: 0;
    }

    .accordion-trigger {
        width: 100%;
        padding: 1rem 1.25rem;
        background: #fff;
        border: none;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .accordion-trigger:hover {
        background: var(--industrial-light);
    }

    .accordion-trigger.expanded {
        background: var(--industrial-light);
    }

    .accordion-trigger-left {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .accordion-trigger-left i {
        color: var(--uitm-blue);
    }

    .accordion-trigger-left i.success {
        color: var(--success-color);
    }

    .accordion-trigger-left i.info {
        color: var(--info-color);
    }

    .program-code {
        font-weight: 600;
        color: var(--industrial-dark);
    }

    .program-name {
        color: var(--industrial-gray);
        margin-left: 0.5rem;
    }

    .accordion-trigger-right {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .count-badge {
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 500;
        font-family: 'IBM Plex Mono', monospace;
    }

    .count-badge.info {
        background: rgba(13, 148, 136, 0.1);
        color: var(--info-color);
    }

    .count-badge.secondary {
        background: var(--industrial-light);
        color: var(--industrial-gray);
    }

    .count-badge.success {
        background: rgba(5, 150, 105, 0.1);
        color: var(--success-color);
    }

    .count-badge.primary {
        background: rgba(30, 58, 138, 0.1);
        color: var(--uitm-blue);
    }

    .accordion-chevron {
        color: var(--industrial-gray);
        transition: transform 0.2s ease;
    }

    .accordion-trigger.expanded .accordion-chevron {
        transform: rotate(180deg);
    }

    .accordion-content {
        display: none;
        padding: 0 1.25rem 1.25rem;
    }

    .accordion-content.expanded {
        display: block;
    }

    /* Tables */
    .custom-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .custom-table thead th {
        background: var(--industrial-light);
        padding: 0.75rem 1rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--industrial-gray);
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }

    .custom-table thead th:first-child {
        border-radius: 8px 0 0 0;
    }

    .custom-table thead th:last-child {
        border-radius: 0 8px 0 0;
        text-align: right;
    }

    .custom-table.success-header thead th {
        background: rgba(5, 150, 105, 0.08);
    }

    .custom-table tbody td {
        padding: 0.875rem 1rem;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .custom-table tbody tr:last-child td {
        border-bottom: none;
    }

    .custom-table tbody tr.highlighted {
        background: rgba(5, 150, 105, 0.05);
    }

    .semester-cell {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .semester-cell strong {
        font-family: 'IBM Plex Mono', monospace;
    }

    .semester-cell .published-icon {
        color: var(--success-color);
    }

    .endorsed-badge {
        background: var(--success-color);
        color: #fff;
        padding: 0.125rem 0.375rem;
        border-radius: 4px;
        font-size: 0.7rem;
    }

    .status-badge {
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .status-badge.success {
        background: rgba(5, 150, 105, 0.1);
        color: var(--success-color);
    }

    .status-badge.warning {
        background: rgba(245, 158, 11, 0.1);
        color: #b45309;
    }

    .status-badge.secondary {
        background: var(--industrial-light);
        color: var(--industrial-gray);
    }

    .status-date {
        font-size: 0.75rem;
        color: var(--industrial-gray);
        margin-top: 0.25rem;
    }

    .endorser-info {
        font-size: 0.85rem;
    }

    .endorser-date {
        font-size: 0.75rem;
        color: var(--industrial-gray);
    }

    .active-badge {
        background: rgba(5, 150, 105, 0.1);
        color: var(--success-color);
        padding: 0.375rem 0.625rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
    }

    .program-badge {
        background: var(--uitm-blue);
        color: #fff;
        padding: 0.375rem 0.625rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 500;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        justify-content: flex-end;
        gap: 0.5rem;
    }

    .btn-action {
        width: 34px;
        height: 34px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid;
        background: #fff;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-action.view {
        border-color: var(--uitm-blue);
        color: var(--uitm-blue);
    }

    .btn-action.view:hover {
        background: var(--uitm-blue);
        color: #fff;
    }

    .btn-action.pdf {
        border-color: var(--industrial-gray);
        color: var(--industrial-gray);
    }

    .btn-action.pdf:hover {
        background: var(--industrial-gray);
        color: #fff;
    }

    .btn-action.delete {
        border-color: var(--danger-color);
        color: var(--danger-color);
    }

    .btn-action.delete:hover {
        background: var(--danger-color);
        color: #fff;
    }

    /* Toast Notifications */
    .toast-container {
        position: fixed;
        bottom: 1.5rem;
        right: 1.5rem;
        z-index: 9999;
    }

    .custom-toast {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        min-width: 300px;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(100%);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .toast-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .toast-icon.success {
        background: rgba(5, 150, 105, 0.1);
        color: var(--success-color);
    }

    .toast-icon.error {
        background: rgba(220, 38, 38, 0.1);
        color: var(--danger-color);
    }

    .toast-content {
        flex: 1;
    }

    .toast-title {
        font-weight: 600;
        color: var(--industrial-dark);
        font-size: 0.9rem;
    }

    .toast-message {
        color: var(--industrial-gray);
        font-size: 0.85rem;
        margin-top: 0.125rem;
    }

    .toast-close {
        background: none;
        border: none;
        color: var(--industrial-gray);
        cursor: pointer;
        padding: 0.25rem;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .header-actions {
            position: static;
            transform: none;
            margin-top: 1rem;
        }
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .custom-tabs {
            overflow-x: auto;
            padding: 0 1rem;
        }

        .custom-tab {
            white-space: nowrap;
            padding: 0.75rem 1rem;
        }

        .filter-row {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-actions {
            text-align: left;
            margin-top: 0.5rem;
        }

        .custom-table {
            font-size: 0.85rem;
        }

        .custom-table thead th,
        .custom-table tbody td {
            padding: 0.625rem 0.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="grouped-lists-page">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1>Equivalency Lists</h1>
            <p>
                <i class="fas fa-clipboard-list"></i>
                View all equivalency lists grouped by program and institution
            </p>
            <div class="header-actions">
                <a href="{{ route('hea.equivalency_lists.index') }}" class="btn-header btn-header-outline">
                    <i class="fas fa-table"></i>Table View
                </a>
                <a href="{{ route('hea.pending_mappings.index') }}" class="btn-header btn-header-info">
                    <i class="fas fa-inbox"></i>Pending Mappings
                    @if($stats['pending_mappings'] > 0)
                        <span class="pending-badge">{{ $stats['pending_mappings'] }}</span>
                    @endif
                </a>
            </div>
        </div>

        <!-- Endorser Filter -->
        <div class="filter-card">
            <form method="GET" action="{{ route('hea.equivalency_lists.grouped') }}">
                <div class="filter-row">
                    <div class="filter-group">
                        <label for="endorser"><i class="fas fa-filter"></i>Filter by Endorser</label>
                        <select name="endorser" id="endorser" onchange="this.form.submit()">
                            <option value="all" {{ $endorserFilter === 'all' ? 'selected' : '' }}>All Lists</option>
                            <option value="me" {{ $endorserFilter === 'me' ? 'selected' : '' }}>Lists I Endorsed</option>
                            <option value="endorsed" {{ $endorserFilter === 'endorsed' ? 'selected' : '' }}>All Endorsed Lists</option>
                        </select>
                    </div>
                    <div class="filter-actions">
                        @if($endorserFilter !== 'all')
                            <a href="{{ route('hea.equivalency_lists.grouped') }}" class="btn-clear-filter">
                                <i class="fas fa-times"></i>Clear Filter
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-content">
                    <div class="stat-icon blue">
                        <i class="fas fa-list-alt"></i>
                    </div>
                    <div>
                        <div class="stat-value">{{ $stats['total_lists'] }}</div>
                        <div class="stat-label">Total Lists</div>
                    </div>
                </div>
            </div>
            <a href="#" onclick="event.preventDefault(); document.querySelector('[data-tab=\"published\"]').click();" class="text-decoration-none">
                <div class="stat-card clickable">
                    <div class="stat-content">
                        <div class="stat-icon green">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <div class="stat-value">{{ $stats['published_lists'] }}</div>
                            <div class="stat-label">Published</div>
                        </div>
                    </div>
                </div>
            </a>
            <a href="{{ route('hea.equivalency_lists.grouped', ['endorser' => 'me']) }}" class="text-decoration-none">
                <div class="stat-card clickable {{ $endorserFilter === 'me' ? 'active' : '' }}">
                    <div class="stat-content">
                        <div class="stat-icon purple">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div>
                            <div class="stat-value">{{ $stats['endorsed_by_me'] }}</div>
                            <div class="stat-label">Endorsed by Me</div>
                        </div>
                    </div>
                </div>
            </a>
            <div class="stat-card">
                <div class="stat-content">
                    <div class="stat-icon orange">
                        <i class="fas fa-paper-plane"></i>
                    </div>
                    <div>
                        <div class="stat-value">{{ $stats['pending_mappings'] }}</div>
                        <div class="stat-label">Pending from RP</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Card with Tabs -->
        <div class="content-card">
            <!-- Custom Tabs -->
            <div class="custom-tabs">
                <button class="custom-tab active" data-tab="published" onclick="switchTab('published')">
                    <i class="fas fa-check-circle"></i>Published Lists
                    <span class="tab-badge success">{{ $stats['published_lists'] }}</span>
                </button>
                <button class="custom-tab" data-tab="internal" onclick="switchTab('internal')">
                    <i class="fas fa-building"></i>Internal Lists (CS110)
                    <span class="tab-badge secondary">{{ $stats['wip_internal'] }}</span>
                </button>
                <button class="custom-tab" data-tab="external" onclick="switchTab('external')">
                    <i class="fas fa-university"></i>External Lists
                    <span class="tab-badge secondary">{{ $stats['wip_external'] }}</span>
                </button>
            </div>

            <div class="content-body">
                <!-- Published Lists Tab -->
                <div class="tab-content active" id="tab-published">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" id="search_published" placeholder="Search by program code or name...">
                    </div>

                    @if($publishedLists->isEmpty())
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-inbox"></i>
                            </div>
                            <h5>No Published Lists</h5>
                            <p>No equivalency lists have been published yet.</p>
                        </div>
                    @else
                        <div class="custom-accordion" id="publishedAccordion">
                            @foreach($publishedLists as $programCode => $lists)
                                @php
                                    $totalCourses = $lists->sum('total_equivalencies');
                                    $accordionId = 'published-' . str_replace(' ', '-', $programCode);
                                    $firstList = $lists->first();
                                @endphp
                                <div class="accordion-item published-item" data-program-code="{{ strtolower($programCode) }}" data-program-name="{{ strtolower($firstList->program_name ?? $programCode) }}">
                                    <h2 class="accordion-header">
                                        <button class="accordion-trigger" onclick="toggleAccordion(this, 'collapse-{{ $accordionId }}')">
                                            <div class="accordion-trigger-left">
                                                <i class="fas fa-check-circle success"></i>
                                                <span class="program-code">{{ $programCode }}</span>
                                                <span class="program-name">{{ $firstList->program_name ?? '' }}</span>
                                            </div>
                                            <div class="accordion-trigger-right">
                                                <span class="count-badge success">{{ $totalCourses }} courses</span>
                                                <span class="count-badge primary">{{ $lists->count() }} semester(s)</span>
                                                <i class="fas fa-chevron-down accordion-chevron"></i>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="collapse-{{ $accordionId }}" class="accordion-content">
                                        <table class="custom-table success-header">
                                            <thead>
                                                <tr>
                                                    <th style="width:14%">Semester</th>
                                                    <th style="width:11%">Status</th>
                                                    <th style="width:10%">Mappings</th>
                                                    <th style="width:13%">Endorsed By</th>
                                                    <th style="width:12%">Published By</th>
                                                    <th style="width:15%">Published Date</th>
                                                    <th style="width:10%">Active</th>
                                                    <th style="width:15%">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($lists as $list)
                                                @php
                                                    $effectiveEndorser = $list->endorser ?? $list->publisher;
                                                @endphp
                                                <tr>
                                                    <td>
                                                        <div class="semester-cell">
                                                            <i class="fas fa-check-circle published-icon"></i>
                                                            <strong>{{ $list->semester }}</strong>
                                                        </div>
                                                    </td>
                                                    <td><span class="status-badge success">Published</span></td>
                                                    <td><span class="count-badge info">{{ $list->total_equivalencies }} courses</span></td>
                                                    <td>
                                                        @if($effectiveEndorser)
                                                            <div class="endorser-info">{{ $effectiveEndorser->name }}</div>
                                                            @if($list->endorsed_at ?? $list->published_at)
                                                                <div class="endorser-date">{{ ($list->endorsed_at ?? $list->published_at)->format('d M Y') }}</div>
                                                            @endif
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td><div class="endorser-info">{{ $list->publisher->name ?? '-' }}</div></td>
                                                    <td><div class="endorser-info">{{ $list->published_at->format('d M Y, H:i') }}</div></td>
                                                    <td>
                                                        @if($list->is_active)
                                                            <span class="active-badge"><i class="fas fa-check-circle"></i>Active</span>
                                                        @else
                                                            <span class="text-muted">Inactive</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="action-buttons">
                                                            <a href="{{ route('hea.equivalency_lists.show', $list) }}" class="btn-action view" title="View Details">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <a href="{{ route('hea.equivalency_lists.pdf', $list) }}" class="btn-action pdf" target="_blank" title="Download PDF">
                                                                <i class="fas fa-file-pdf"></i>
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
                        </div>
                    @endif
                </div>

                <!-- Internal Lists Tab -->
                <div class="tab-content" id="tab-internal" style="display: none;">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" id="search_internal" placeholder="Search by program code or name...">
                    </div>

                    @if($internalLists->isEmpty())
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-inbox"></i>
                            </div>
                            <h5>No Internal Lists</h5>
                            <p>No internal equivalency lists have been created yet.</p>
                        </div>
                    @else
                        <div class="custom-accordion" id="internalAccordion">
                            @foreach($internalLists as $programCode => $lists)
                                @php
                                    $totalCourses = $lists->sum('total_equivalencies');
                                    $accordionId = 'internal-' . str_replace(' ', '-', $programCode);
                                @endphp
                                <div class="accordion-item internal-item" data-program-code="{{ strtolower($programCode) }}" data-program-name="{{ strtolower($lists->first()->program_name ?? $programCode) }}">
                                    <h2 class="accordion-header">
                                        <button class="accordion-trigger" onclick="toggleAccordion(this, 'collapse-{{ $accordionId }}')">
                                            <div class="accordion-trigger-left">
                                                <i class="fas fa-graduation-cap"></i>
                                                <span class="program-code">{{ $programCode }}</span>
                                                <span class="program-name">{{ $lists->first()->program_name ?? '' }}</span>
                                            </div>
                                            <div class="accordion-trigger-right">
                                                <span class="count-badge info">{{ $totalCourses }} courses</span>
                                                <span class="count-badge secondary">{{ $lists->count() }} list(s)</span>
                                                <i class="fas fa-chevron-down accordion-chevron"></i>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="collapse-{{ $accordionId }}" class="accordion-content">
                                        <table class="custom-table">
                                            <thead>
                                                <tr>
                                                    <th>Semester</th>
                                                    <th>Status</th>
                                                    <th>Mappings</th>
                                                    <th>Endorsed By</th>
                                                    <th>Active</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($lists as $list)
                                                @php
                                                    $effectiveEndorser = $list->endorser ?? $list->publisher;
                                                    $effectiveEndorserId = $list->endorsed_by_user_id ?? $list->published_by_user_id;
                                                    $isEndorsedByMe = $effectiveEndorserId === auth()->id();
                                                @endphp
                                                <tr class="{{ $isEndorsedByMe ? 'highlighted' : '' }}">
                                                    <td>
                                                        <div class="semester-cell">
                                                            @if($list->published_at)
                                                                <i class="fas fa-check-circle published-icon" title="Published on {{ $list->published_at->format('d M Y') }}"></i>
                                                            @endif
                                                            <strong>{{ $list->semester }}</strong>
                                                            @if($isEndorsedByMe)
                                                                <span class="endorsed-badge" title="Endorsed by you"><i class="fas fa-user-check"></i></span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if($list->published_at)
                                                            <span class="status-badge success">Published</span>
                                                            <div class="status-date">{{ $list->published_at->format('d M Y') }}</div>
                                                        @elseif($list->status === 'draft')
                                                            <span class="status-badge warning">Draft</span>
                                                        @elseif($list->status === 'archived')
                                                            <span class="status-badge secondary">Archived</span>
                                                        @endif
                                                    </td>
                                                    <td><span class="count-badge info">{{ $list->total_equivalencies }} courses</span></td>
                                                    <td>
                                                        @if($effectiveEndorser)
                                                            <div class="endorser-info">{{ $effectiveEndorser->name }}</div>
                                                            @if($list->endorsed_at ?? $list->published_at)
                                                                <div class="endorser-date">{{ ($list->endorsed_at ?? $list->published_at)->format('d M Y') }}</div>
                                                            @endif
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($list->is_active)
                                                            <span class="active-badge"><i class="fas fa-check-circle"></i>Active</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="action-buttons">
                                                            <a href="{{ route('hea.equivalency_lists.show', $list) }}" class="btn-action view" title="View Details">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            @if(!$list->published_at && !$list->endorsed_at)
                                                                <form action="{{ route('hea.equivalency_lists.destroy', $list) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this draft list for {{ $list->semester }}? This action cannot be undone.');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn-action delete" title="Delete Draft">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- External Lists Tab -->
                <div class="tab-content" id="tab-external" style="display: none;">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" id="search_external" placeholder="Search by institution name or program code...">
                    </div>

                    @if($externalLists->isEmpty())
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-inbox"></i>
                            </div>
                            <h5>No External Lists</h5>
                            <p>No external equivalency lists have been created yet.</p>
                        </div>
                    @else
                        <div class="custom-accordion" id="externalAccordion">
                            @foreach($externalLists as $institution => $lists)
                                @php
                                    $totalCourses = $lists->sum('total_equivalencies');
                                    $accordionId = 'external-' . md5($institution);
                                @endphp
                                <div class="accordion-item external-item" data-institution="{{ strtolower($institution) }}">
                                    <h2 class="accordion-header">
                                        <button class="accordion-trigger" onclick="toggleAccordion(this, 'collapse-{{ $accordionId }}')">
                                            <div class="accordion-trigger-left">
                                                <i class="fas fa-university info"></i>
                                                <span class="program-code">{{ $institution }}</span>
                                            </div>
                                            <div class="accordion-trigger-right">
                                                <span class="count-badge info">{{ $totalCourses }} courses</span>
                                                <span class="count-badge secondary">{{ $lists->count() }} list(s)</span>
                                                <i class="fas fa-chevron-down accordion-chevron"></i>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="collapse-{{ $accordionId }}" class="accordion-content">
                                        <table class="custom-table">
                                            <thead>
                                                <tr>
                                                    <th>Program</th>
                                                    <th>Semester</th>
                                                    <th>Status</th>
                                                    <th>Mappings</th>
                                                    <th>Endorsed By</th>
                                                    <th>Active</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($lists as $list)
                                                @php
                                                    $effectiveEndorser = $list->endorser ?? $list->publisher;
                                                    $effectiveEndorserId = $list->endorsed_by_user_id ?? $list->published_by_user_id;
                                                    $isEndorsedByMe = $effectiveEndorserId === auth()->id();
                                                @endphp
                                                <tr data-program-code="{{ strtolower($list->program_code) }}" class="{{ $isEndorsedByMe ? 'highlighted' : '' }}">
                                                    <td><span class="program-badge">{{ $list->program_code }}</span></td>
                                                    <td>
                                                        <div class="semester-cell">
                                                            @if($list->published_at)
                                                                <i class="fas fa-check-circle published-icon" title="Published on {{ $list->published_at->format('d M Y') }}"></i>
                                                            @endif
                                                            <strong>{{ $list->semester }}</strong>
                                                            @if($isEndorsedByMe)
                                                                <span class="endorsed-badge" title="Endorsed by you"><i class="fas fa-user-check"></i></span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if($list->published_at)
                                                            <span class="status-badge success">Published</span>
                                                            <div class="status-date">{{ $list->published_at->format('d M Y') }}</div>
                                                        @elseif($list->status === 'draft')
                                                            <span class="status-badge warning">Draft</span>
                                                        @elseif($list->status === 'archived')
                                                            <span class="status-badge secondary">Archived</span>
                                                        @endif
                                                    </td>
                                                    <td><span class="count-badge info">{{ $list->total_equivalencies }} courses</span></td>
                                                    <td>
                                                        @if($effectiveEndorser)
                                                            <div class="endorser-info">{{ $effectiveEndorser->name }}</div>
                                                            @if($list->endorsed_at ?? $list->published_at)
                                                                <div class="endorser-date">{{ ($list->endorsed_at ?? $list->published_at)->format('d M Y') }}</div>
                                                            @endif
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($list->is_active)
                                                            <span class="active-badge"><i class="fas fa-check-circle"></i>Active</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="action-buttons">
                                                            <a href="{{ route('hea.equivalency_lists.show', $list) }}" class="btn-action view" title="View Details">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            @if(!$list->published_at && !$list->endorsed_at)
                                                                <form action="{{ route('hea.equivalency_lists.destroy', $list) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this draft list for {{ $list->semester }}? This action cannot be undone.');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn-action delete" title="Delete Draft">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
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
        <div class="custom-toast" id="successToast">
            <div class="toast-icon success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="toast-content">
                <div class="toast-title">Success</div>
                <div class="toast-message">{{ session('success') }}</div>
            </div>
            <button class="toast-close" onclick="document.getElementById('successToast').remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="toast-container">
        <div class="custom-toast" id="errorToast">
            <div class="toast-icon error">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <div class="toast-content">
                <div class="toast-title">Error</div>
                <div class="toast-message">{{ session('error') }}</div>
            </div>
            <button class="toast-close" onclick="document.getElementById('errorToast').remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
@endif
@endsection

@push('scripts')
<script>
// Tab Switching
function switchTab(tabName) {
    // Update tab buttons
    document.querySelectorAll('.custom-tab').forEach(tab => {
        tab.classList.remove('active');
    });
    document.querySelector(`[data-tab="${tabName}"]`).classList.add('active');

    // Update tab content
    document.querySelectorAll('.tab-content').forEach(content => {
        content.style.display = 'none';
    });
    document.getElementById(`tab-${tabName}`).style.display = 'block';
}

// Accordion Toggle
function toggleAccordion(button, contentId) {
    const content = document.getElementById(contentId);
    const isExpanded = button.classList.contains('expanded');

    if (isExpanded) {
        button.classList.remove('expanded');
        content.classList.remove('expanded');
    } else {
        button.classList.add('expanded');
        content.classList.add('expanded');
    }
}

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

    // Published Lists Search
    const searchPublished = document.getElementById('search_published');
    if (searchPublished) {
        searchPublished.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const items = document.querySelectorAll('.published-item');

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

    // Auto-dismiss toasts after 5 seconds
    setTimeout(function() {
        const toasts = document.querySelectorAll('.custom-toast');
        toasts.forEach(toast => {
            toast.style.animation = 'slideOut 0.3s ease forwards';
            setTimeout(() => toast.remove(), 300);
        });
    }, 5000);
});
</script>
@endpush
