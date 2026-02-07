@extends('layouts.app')

@section('content')
<div class="aa-equiv-page">
    <!-- Background Pattern -->
    <div class="aa-bg-pattern"></div>

    <!-- Page Header -->
    <header class="aa-header">
        <div class="aa-header-grid">
            <div class="aa-header-main">
                <div class="aa-header-badge">
                    <div class="badge-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                </div>
                <div class="aa-header-text">
                    <div class="aa-header-eyebrow">Course Equivalencies</div>
                    <h1 class="aa-header-title">Published Equivalency Lists</h1>
                    <p class="aa-header-subtitle">View published course equivalency lists organized by degree program</p>
                </div>
            </div>
            <div class="aa-header-actions">
                <a href="{{ Auth::user()->role == 'academic_advisor' ? route('academic_advisor.course_equivalencies.view') : route('program_coordinator.course_equivalencies.view') }}" class="aa-btn aa-btn-primary">
                    <i class="fas fa-search"></i>
                    <span>All Course Mappings</span>
                </a>
                <a href="{{ route('academic_advisor.dashboard') }}" class="aa-btn aa-btn-outline">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back to Dashboard</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Program Tabs -->
    <div class="aa-tabs-container">
        <div class="aa-tabs-wrapper">
            <ul class="aa-tabs" id="programTabs" role="tablist">
                @foreach($programs as $index => $programCode)
                <li class="aa-tab-item" role="presentation">
                    <button class="aa-tab-btn {{ $latestPublishedProgram === $programCode ? 'active' : '' }}"
                            id="{{ strtolower($programCode) }}-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#{{ strtolower($programCode) }}"
                            type="button"
                            role="tab">
                        <span class="aa-tab-code">{{ $programCode }}</span>
                    </button>
                </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- Tab Content -->
    <div class="tab-content" id="programTabsContent">
        @foreach($programs as $index => $programCode)
        <div class="tab-pane fade {{ $latestPublishedProgram === $programCode ? 'show active' : '' }}"
             id="{{ strtolower($programCode) }}"
             role="tabpanel">

            <!-- Program Title Card -->
            <div class="aa-program-title-card">
                <div class="aa-program-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="aa-program-info">
                    <div class="aa-program-code">{{ $programCode }}</div>
                    <div class="aa-program-name">{{ $programData[$programCode]['name'] }}</div>
                </div>
            </div>

            @if($programData[$programCode]['current'] || $programData[$programCode]['history']->isNotEmpty())

                <!-- Current Active List -->
                @if($programData[$programCode]['current'])
                @php $list = $programData[$programCode]['current']; @endphp
                <div class="aa-list-section">
                    <div class="aa-list-header">
                        <div class="aa-list-header-left">
                            <div class="aa-list-icon active">
                                <i class="fas fa-star"></i>
                            </div>
                            <div>
                                <h3 class="aa-list-title">Current Active List</h3>
                                <p class="aa-list-subtitle">This list is currently in use for credit exemption processing</p>
                            </div>
                        </div>
                        <a href="{{ route('academic_advisor.equivalency_lists.pdf', $list) }}"
                           class="aa-btn aa-btn-pdf"
                           target="_blank">
                            <i class="fas fa-file-pdf"></i>
                            <span>View as PDF</span>
                        </a>
                    </div>

                    <!-- List Info Grid -->
                    <div class="aa-list-info-grid">
                        <div class="aa-info-card">
                            <div class="aa-info-label">Program Code</div>
                            <div class="aa-info-value">
                                <span class="aa-code-badge">{{ $list->program_code }}</span>
                            </div>
                        </div>
                        <div class="aa-info-card">
                            <div class="aa-info-label">Category</div>
                            <div class="aa-info-value">
                                @if($list->category === 'internal')
                                    <span class="aa-category-badge internal">Internal (CS110)</span>
                                @else
                                    <span class="aa-category-badge external">External Institution</span>
                                @endif
                            </div>
                        </div>
                        <div class="aa-info-card">
                            <div class="aa-info-label">Semester</div>
                            <div class="aa-info-value">{{ $list->semester }}</div>
                        </div>
                        <div class="aa-info-card">
                            <div class="aa-info-label">Total Mappings</div>
                            <div class="aa-info-value">
                                <span class="aa-count-highlight">{{ $list->courseEquivalencies->where('is_eligible', true)->count() }}</span>
                                <span class="aa-count-suffix">eligible courses</span>
                            </div>
                        </div>
                        <div class="aa-info-card">
                            <div class="aa-info-label">Status</div>
                            <div class="aa-info-value">
                                <span class="aa-status-badge published">
                                    <i class="fas fa-check-circle"></i> Published & Active
                                </span>
                            </div>
                        </div>
                        <div class="aa-info-card">
                            <div class="aa-info-label">Published By</div>
                            <div class="aa-info-value">{{ $list->publisher->name ?? 'N/A' }}</div>
                        </div>
                        <div class="aa-info-card">
                            <div class="aa-info-label">Created At</div>
                            <div class="aa-info-value">{{ $list->created_at->format('d M Y, h:i A') }}</div>
                        </div>
                        <div class="aa-info-card">
                            <div class="aa-info-label">Published At</div>
                            <div class="aa-info-value">{{ $list->published_at ? $list->published_at->format('d M Y, h:i A') : 'N/A' }}</div>
                        </div>
                    </div>

                    <!-- Course Mappings Table -->
                    <div class="aa-mappings-section">
                        <div class="aa-mappings-header">
                            <i class="fas fa-exchange-alt"></i>
                            <span>Course Equivalency Mappings</span>
                        </div>

                        @php
                            $eligibleMappings = $list->courseEquivalencies->where('is_eligible', true)->sortBy('diploma_course_code');
                        @endphp

                        @if($eligibleMappings->isEmpty())
                        <div class="aa-empty-mappings">
                            <i class="fas fa-inbox"></i>
                            <h5>No Eligible Course Mappings</h5>
                            <p>This equivalency list does not have any eligible course mappings (≥80% match) yet.</p>
                        </div>
                        @else
                        <div class="aa-table-wrapper">
                            <table class="aa-mappings-table">
                                <thead>
                                    <tr>
                                        <th class="col-num">#</th>
                                        <th class="col-diploma">Diploma Course</th>
                                        <th class="col-degree">Degree Course</th>
                                        <th class="col-match">Match %</th>
                                        <th class="col-credits">Credits</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($eligibleMappings as $index => $eq)
                                    <tr>
                                        <td class="col-num">
                                            <span class="aa-row-num">{{ $loop->iteration }}</span>
                                        </td>
                                        <td class="col-diploma">
                                            <div class="aa-course-cell">
                                                <span class="aa-course-code">{{ $eq->diploma_course_code }}</span>
                                                <span class="aa-course-name">{{ $eq->diploma_course_name }}</span>
                                            </div>
                                        </td>
                                        <td class="col-degree">
                                            <div class="aa-course-cell">
                                                <span class="aa-course-code">{{ $eq->degree_course_code }}</span>
                                                <span class="aa-course-name">{{ $eq->degree_course_name }}</span>
                                            </div>
                                        </td>
                                        <td class="col-match">
                                            <span class="aa-match-badge">{{ number_format($eq->match_percentage, 0) }}%</span>
                                        </td>
                                        <td class="col-credits">
                                            <span class="aa-credits-flow">
                                                {{ $eq->diploma_credit_hour }} <i class="fas fa-arrow-right"></i> {{ $eq->degree_credit_hour }}
                                            </span>
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
                <div class="aa-history-section">
                    <div class="aa-history-header">
                        <i class="fas fa-history"></i>
                        <span>Previous Published Lists</span>
                    </div>

                    @foreach($programData[$programCode]['history'] as $list)
                    <div class="aa-history-card">
                        <div class="aa-history-main">
                            <div class="aa-history-icon">
                                <i class="fas fa-archive"></i>
                            </div>
                            <div class="aa-history-info">
                                <div class="aa-history-semester">{{ $list->semester }}</div>
                                <div class="aa-history-meta">
                                    <span class="aa-history-badge {{ $list->category === 'internal' ? 'internal' : 'external' }}">
                                        {{ $list->category === 'internal' ? 'UiTM CS110' : 'External - ' . $list->source_institution }}
                                    </span>
                                    <span class="aa-history-courses">
                                        <i class="fas fa-book"></i>
                                        {{ $list->courseEquivalencies->where('is_eligible', true)->count() }} eligible courses
                                    </span>
                                </div>
                                <div class="aa-history-publisher">
                                    <i class="fas fa-user"></i>
                                    Published by {{ $list->publisher->name ?? 'N/A' }}
                                    on {{ $list->published_at ? $list->published_at->format('d M Y') : 'N/A' }}
                                </div>
                            </div>
                        </div>
                        <div class="aa-history-actions">
                            <span class="aa-archived-badge">
                                <i class="fas fa-archive"></i> Archived
                            </span>
                            <a href="{{ Auth::user()->role == 'academic_advisor' ? route('academic_advisor.equivalency_lists.show', $list) : route('program_coordinator.equivalency_lists.show', $list) }}"
                               class="aa-btn aa-btn-view">
                                <i class="fas fa-eye"></i>
                                <span>View Details</span>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

            @else
            <!-- Empty State -->
            <div class="aa-empty-program">
                <div class="aa-empty-icon">
                    <i class="fas fa-inbox"></i>
                </div>
                <h4>No Published Lists Yet</h4>
                <p>No equivalency lists have been published for this program yet.</p>
            </div>
            @endif
        </div>
        @endforeach
    </div>
</div>

<style>
/* ============================================
   EQUIVALENCY LISTS PAGE - INDUSTRIAL UI
   ============================================ */

.aa-equiv-page {
    position: relative;
    padding: 2rem;
    min-height: 100vh;
}

.aa-bg-pattern {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image:
        linear-gradient(rgba(30, 58, 138, 0.02) 1px, transparent 1px),
        linear-gradient(90deg, rgba(30, 58, 138, 0.02) 1px, transparent 1px);
    background-size: 40px 40px;
    pointer-events: none;
    z-index: -1;
}

/* Header */
.aa-header {
    background: linear-gradient(135deg, #1e3a8a 0%, #1e293b 100%);
    border-radius: 16px;
    padding: 2rem 2.5rem;
    margin-bottom: 1.5rem;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 20px 40px -10px rgba(30, 58, 138, 0.3);
}

.aa-header::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #f59e0b 0%, #fbbf24 50%, #f59e0b 100%);
}

.aa-header-grid {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 2rem;
    position: relative;
    z-index: 1;
}

.aa-header-main {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.aa-header-badge {
    position: relative;
}

.badge-icon {
    width: 72px;
    height: 72px;
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
    color: white;
    box-shadow: 0 8px 24px rgba(245, 158, 11, 0.4);
}

.aa-header-eyebrow {
    font-family: 'IBM Plex Mono', monospace;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: #fbbf24;
    margin-bottom: 0.25rem;
}

.aa-header-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: white;
    margin: 0;
}

.aa-header-subtitle {
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.7);
    margin: 0.5rem 0 0;
}

.aa-header-actions {
    display: flex;
    gap: 0.75rem;
}

/* Buttons */
.aa-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.875rem;
    text-decoration: none;
    transition: all 0.2s ease;
    border: none;
    cursor: pointer;
}

.aa-btn-primary {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
}

.aa-btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4);
    color: white;
}

.aa-btn-outline {
    background: rgba(255, 255, 255, 0.1);
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.aa-btn-outline:hover {
    background: rgba(255, 255, 255, 0.2);
    color: white;
}

.aa-btn-pdf {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
}

.aa-btn-pdf:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4);
    color: white;
}

.aa-btn-view {
    background: linear-gradient(135deg, #64748b 0%, #475569 100%);
    color: white;
}

.aa-btn-view:hover {
    transform: translateY(-2px);
    color: white;
}

/* Tabs */
.aa-tabs-container {
    background: white;
    border-radius: 12px;
    padding: 0.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.aa-tabs-wrapper {
    overflow-x: auto;
}

.aa-tabs {
    display: flex;
    gap: 0.5rem;
    list-style: none;
    margin: 0;
    padding: 0;
}

.aa-tab-btn {
    padding: 0.75rem 1.25rem;
    background: transparent;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.875rem;
    color: #64748b;
    cursor: pointer;
    transition: all 0.2s ease;
    white-space: nowrap;
}

.aa-tab-btn:hover {
    background: #f1f5f9;
    color: #1e293b;
}

.aa-tab-btn.active {
    background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
}

.aa-tab-code {
    font-family: 'IBM Plex Mono', monospace;
    font-weight: 700;
}

/* Program Title Card */
.aa-program-title-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.25rem 1.5rem;
    background: linear-gradient(135deg, #dbeafe 0%, #e0f2fe 100%);
    border-radius: 12px;
    border-left: 4px solid #3b82f6;
    margin-bottom: 1.5rem;
}

.aa-program-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #3b82f6 0%, #1e3a8a 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: white;
}

.aa-program-code {
    font-family: 'IBM Plex Mono', monospace;
    font-size: 0.8rem;
    font-weight: 700;
    color: #1e3a8a;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.aa-program-name {
    font-size: 1.1rem;
    font-weight: 600;
    color: #1e293b;
}

/* List Section */
.aa-list-section {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    margin-bottom: 1.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 4px 12px rgba(0, 0, 0, 0.05);
}

.aa-list-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.25rem 1.5rem;
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.08) 0%, rgba(5, 150, 105, 0.08) 100%);
    border-bottom: 1px solid #e2e8f0;
    border-left: 4px solid #10b981;
}

.aa-list-header-left {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.aa-list-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
}

.aa-list-icon.active {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
}

.aa-list-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
}

.aa-list-subtitle {
    font-size: 0.8rem;
    color: #64748b;
    margin: 0.25rem 0 0;
}

/* Info Grid */
.aa-list-info-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    padding: 1.5rem;
    background: #f8fafc;
}

.aa-info-card {
    padding: 1rem;
    background: white;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
}

.aa-info-label {
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #64748b;
    margin-bottom: 0.5rem;
}

.aa-info-value {
    font-weight: 600;
    color: #1e293b;
    font-size: 0.9rem;
}

.aa-code-badge {
    display: inline-block;
    padding: 0.35rem 0.75rem;
    background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
    color: white;
    border-radius: 6px;
    font-family: 'IBM Plex Mono', monospace;
    font-size: 0.8rem;
    font-weight: 700;
}

.aa-category-badge {
    display: inline-block;
    padding: 0.35rem 0.75rem;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
}

.aa-category-badge.internal {
    background: linear-gradient(135deg, rgba(30, 58, 138, 0.1) 0%, rgba(59, 130, 246, 0.1) 100%);
    color: #1e3a8a;
}

.aa-category-badge.external {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(14, 165, 233, 0.1) 100%);
    color: #0369a1;
}

.aa-count-highlight {
    font-family: 'IBM Plex Mono', monospace;
    font-size: 1.25rem;
    font-weight: 700;
    color: #10b981;
}

.aa-count-suffix {
    font-size: 0.75rem;
    color: #64748b;
    margin-left: 0.25rem;
}

.aa-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.4rem 0.75rem;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
}

.aa-status-badge.published {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(5, 150, 105, 0.15) 100%);
    color: #059669;
}

/* Mappings Section */
.aa-mappings-section {
    border-top: 1px solid #e2e8f0;
}

.aa-mappings-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 1.5rem;
    background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
    color: white;
    font-weight: 600;
}

.aa-table-wrapper {
    overflow-x: auto;
}

.aa-mappings-table {
    width: 100%;
    border-collapse: collapse;
}

.aa-mappings-table th {
    padding: 0.875rem 1rem;
    background: #f8fafc;
    font-family: 'IBM Plex Mono', monospace;
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #64748b;
    text-align: left;
    border-bottom: 1px solid #e2e8f0;
}

.aa-mappings-table th.col-num { width: 60px; text-align: center; }
.aa-mappings-table th.col-diploma { min-width: 250px; }
.aa-mappings-table th.col-degree { min-width: 250px; }
.aa-mappings-table th.col-match { width: 100px; text-align: center; }
.aa-mappings-table th.col-credits { width: 100px; text-align: center; }

.aa-mappings-table tbody tr {
    border-bottom: 1px solid #f1f5f9;
    transition: background 0.15s ease;
}

.aa-mappings-table tbody tr:hover {
    background: #fafbfc;
}

.aa-mappings-table td {
    padding: 1rem;
    vertical-align: middle;
}

.aa-row-num {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    background: #f1f5f9;
    border-radius: 6px;
    font-family: 'IBM Plex Mono', monospace;
    font-size: 0.75rem;
    font-weight: 600;
    color: #64748b;
}

.aa-course-cell {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.aa-course-code {
    font-family: 'IBM Plex Mono', monospace;
    font-weight: 700;
    color: #1e293b;
    font-size: 0.875rem;
}

.aa-course-name {
    font-size: 0.8rem;
    color: #64748b;
}

.aa-match-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.4rem 0.75rem;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    border-radius: 6px;
    font-weight: 700;
    font-size: 0.875rem;
}

.aa-credits-flow {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    color: #64748b;
}

.aa-credits-flow i {
    font-size: 0.65rem;
    color: #94a3b8;
}

.aa-empty-mappings {
    text-align: center;
    padding: 3rem 2rem;
    color: #64748b;
}

.aa-empty-mappings i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.3;
}

.aa-empty-mappings h5 {
    font-weight: 600;
    color: #475569;
    margin-bottom: 0.5rem;
}

/* History Section */
.aa-history-section {
    margin-top: 1.5rem;
}

.aa-history-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 0;
    font-weight: 700;
    color: #1e293b;
    font-size: 1rem;
    border-bottom: 2px solid #e2e8f0;
    margin-bottom: 1rem;
}

.aa-history-card {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.25rem 1.5rem;
    background: white;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    margin-bottom: 1rem;
    transition: all 0.2s ease;
}

.aa-history-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transform: translateY(-2px);
}

.aa-history-main {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.aa-history-icon {
    width: 44px;
    height: 44px;
    background: #f1f5f9;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    color: #64748b;
}

.aa-history-semester {
    font-weight: 700;
    color: #1e293b;
    font-size: 1rem;
    margin-bottom: 0.35rem;
}

.aa-history-meta {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.35rem;
}

.aa-history-badge {
    display: inline-block;
    padding: 0.3rem 0.6rem;
    border-radius: 4px;
    font-size: 0.7rem;
    font-weight: 600;
}

.aa-history-badge.internal {
    background: rgba(30, 58, 138, 0.1);
    color: #1e3a8a;
}

.aa-history-badge.external {
    background: rgba(59, 130, 246, 0.1);
    color: #0369a1;
}

.aa-history-courses {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.8rem;
    color: #64748b;
}

.aa-history-publisher {
    font-size: 0.8rem;
    color: #94a3b8;
}

.aa-history-publisher i {
    margin-right: 0.35rem;
}

.aa-history-actions {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.aa-archived-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.4rem 0.75rem;
    background: #f1f5f9;
    color: #64748b;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
}

/* Empty Program State */
.aa-empty-program {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.aa-empty-icon {
    width: 80px;
    height: 80px;
    background: #f1f5f9;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 2.5rem;
    color: #94a3b8;
}

.aa-empty-program h4 {
    font-weight: 700;
    color: #475569;
    margin-bottom: 0.5rem;
}

.aa-empty-program p {
    color: #64748b;
    margin: 0;
}

/* Responsive */
@media (max-width: 1200px) {
    .aa-list-info-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 992px) {
    .aa-header-grid {
        flex-direction: column;
        text-align: center;
    }

    .aa-header-main {
        flex-direction: column;
    }

    .aa-header-actions {
        width: 100%;
        justify-content: center;
    }

    .aa-list-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }

    .aa-list-header-left {
        flex-direction: column;
    }

    .aa-history-card {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }

    .aa-history-main {
        flex-direction: column;
    }
}

@media (max-width: 768px) {
    .aa-equiv-page {
        padding: 1rem;
    }

    .aa-header {
        padding: 1.5rem;
    }

    .aa-header-title {
        font-size: 1.25rem;
    }

    .aa-list-info-grid {
        grid-template-columns: 1fr;
    }

    .aa-btn {
        width: 100%;
        justify-content: center;
    }
}
</style>
@endsection
