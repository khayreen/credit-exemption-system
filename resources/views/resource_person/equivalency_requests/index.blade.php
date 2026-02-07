@extends('layouts.app')

@section('content')
<div class="rp-requests">
    {{-- Page Header --}}
    <header class="page-header">
        <div class="header-content">
            <div class="header-info">
                <div class="header-icon">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h1>Course Equivalency Requests</h1>
                    <p>Review and process student equivalency requests</p>
                </div>
            </div>
            <a href="{{ route('resource_person.dashboard') }}" class="back-btn">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Dashboard
            </a>
        </div>
    </header>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="industrial-alert success">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('success') }}</span>
            <button type="button" class="alert-close" data-bs-dismiss="alert">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="industrial-alert error">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('error') }}</span>
            <button type="button" class="alert-close" data-bs-dismiss="alert">&times;</button>
        </div>
    @endif

    {{-- Statistics Cards --}}
    <section class="stats-grid">
        <div class="stat-card pending">
            <div class="stat-header">
                <span class="stat-label">Pending Review</span>
                <div class="stat-indicator {{ $groupedByEquivalency->count() > 0 ? 'active' : '' }}"></div>
            </div>
            <div class="stat-value">{{ $groupedByEquivalency->count() }}</div>
            <div class="stat-footer">Awaiting your decision</div>
        </div>

        <div class="stat-card syllabus">
            <div class="stat-header">
                <span class="stat-label">Syllabus Received</span>
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div class="stat-value">{{ $groupedByEquivalency->filter(function($group) { return $group->first()->syllabus_received_at !== null; })->count() }}</div>
            <div class="stat-footer">Ready for review</div>
        </div>

        <div class="stat-card approved">
            <div class="stat-header">
                <span class="stat-label">Approved</span>
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-value">{{ $decisionStats['approved'] }}</div>
            <div class="stat-footer">Marked as equivalent</div>
        </div>

        <div class="stat-card rejected">
            <div class="stat-header">
                <span class="stat-label">Rejected</span>
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-value">{{ $decisionStats['rejected'] }}</div>
            <div class="stat-footer">Not equivalent</div>
        </div>
    </section>

    {{-- Tabs Navigation --}}
    <section class="tabs-section">
        <nav class="tabs-nav">
            <button class="tab-btn active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Pending Review
                @if($groupedByEquivalency->count() > 0)
                    <span class="tab-badge">{{ $groupedByEquivalency->count() }}</span>
                @endif
            </button>
            <button class="tab-btn" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Decision History
                <span class="tab-badge secondary">{{ $decisionStats['total'] }}</span>
            </button>
        </nav>

        <div class="tab-content" id="requestTabsContent">
            {{-- Pending Review Tab --}}
            <div class="tab-pane fade show active" id="pending" role="tabpanel">
                <div class="content-card">
                    <div class="card-header">
                        <div class="header-info">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                            <div>
                                <h3>Equivalencies for Review</h3>
                                <p>Each row represents a unique equivalency request. Multiple students may request the same equivalency.</p>
                            </div>
                        </div>
                    </div>

                    @if($groupedByEquivalency->isEmpty())
                        <div class="empty-state">
                            <div class="empty-icon">
                                <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                            </div>
                            <h4>No Pending Requests</h4>
                            <p>No equivalency requests pending review. All requests have been processed!</p>
                        </div>
                    @else
                        <div class="table-wrapper">
                            <table class="requests-table">
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
                                        <tr class="{{ $hasSyllabus ? 'has-syllabus' : '' }}">
                                            <td>
                                                <div class="course-info">
                                                    <span class="course-code">{{ $representative->diploma_course_code }}</span>
                                                    <span class="course-name">{{ Str::limit($representative->diploma_course_name, 40) }}</span>
                                                    <span class="course-institution">{{ $representative->diploma_institution }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="course-info">
                                                    <span class="course-code">{{ $representative->suggested_degree_course_code }}</span>
                                                    <span class="course-name">{{ Str::limit($representative->suggested_degree_course_name, 40) }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="program-badge">{{ $representative->current_program_code }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="student-count">
                                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                                    </svg>
                                                    {{ $studentCount }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($hasSyllabus)
                                                    <div class="status-badge success">
                                                        <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                        </svg>
                                                        Syllabus Received
                                                    </div>
                                                    <span class="status-hint success">Ready for Review!</span>
                                                @elseif($representative->syllabus_request_sent_at)
                                                    <div class="status-badge info">
                                                        <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                        </svg>
                                                        Awaiting Lecturer
                                                    </div>
                                                    <span class="status-hint">Sent {{ $representative->syllabus_request_sent_at->diffForHumans() }}</span>
                                                @elseif($representative->status === 'pending')
                                                    <div class="status-badge warning">
                                                        <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                        </svg>
                                                        Pending
                                                    </div>
                                                @elseif($representative->status === 'under_review')
                                                    <div class="status-badge primary">
                                                        <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                        </svg>
                                                        Under Review
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('resource_person.equivalency_requests.review', $representative->id) }}" class="review-btn">
                                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
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

            {{-- Decision History Tab --}}
            <div class="tab-pane fade" id="history" role="tabpanel">
                <div class="content-card">
                    <div class="card-header">
                        <div class="header-info">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <h3>Decision History</h3>
                                <p>Your past decisions on equivalency requests</p>
                            </div>
                        </div>
                        <div class="header-stats">
                            <span class="header-stat success">
                                <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                {{ $decisionStats['approved'] }} Approved
                            </span>
                            <span class="header-stat danger">
                                <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                                {{ $decisionStats['rejected'] }} Rejected
                            </span>
                        </div>
                    </div>

                    @if($groupedDecidedByEquivalency->isEmpty())
                        <div class="empty-state">
                            <div class="empty-icon">
                                <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <h4>No Decisions Yet</h4>
                            <p>Your decision history will appear here after you review requests.</p>
                        </div>
                    @else
                        <div class="table-wrapper">
                            <table class="requests-table">
                                <thead>
                                    <tr>
                                        <th>Diploma Course</th>
                                        <th>Degree Course</th>
                                        <th>Program</th>
                                        <th class="text-center">Match %</th>
                                        <th class="text-center">Students</th>
                                        <th>Decision</th>
                                        <th>Date</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($groupedDecidedByEquivalency as $key => $equivalencyGroup)
                                        @php
                                            $representative = $equivalencyGroup->first();
                                            $studentCount = $equivalencyGroup->count();
                                            $isApproved = $representative->status === 'approved';
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="course-info compact">
                                                    <span class="course-code">{{ $representative->diploma_course_code }}</span>
                                                    <span class="course-name">{{ Str::limit($representative->diploma_course_name, 35) }}</span>
                                                    <span class="course-institution">{{ Str::limit($representative->diploma_institution, 30) }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="course-info compact">
                                                    <span class="course-code">{{ $representative->approved_degree_course_code ?? $representative->suggested_degree_course_code }}</span>
                                                    <span class="course-name">{{ Str::limit($representative->approved_degree_course_name ?? $representative->suggested_degree_course_name, 35) }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="program-badge">{{ $representative->current_program_code }}</span>
                                            </td>
                                            <td class="text-center">
                                                @if($representative->match_percentage)
                                                    <span class="match-badge {{ $representative->match_percentage >= 80 ? 'high' : 'medium' }}">
                                                        {{ $representative->match_percentage }}%
                                                    </span>
                                                @else
                                                    <span class="no-value">&mdash;</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="student-count small">{{ $studentCount }}</span>
                                            </td>
                                            <td>
                                                @if($isApproved)
                                                    <span class="decision-badge approved">
                                                        <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                        </svg>
                                                        Approved
                                                    </span>
                                                @else
                                                    <span class="decision-badge rejected">
                                                        <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                        </svg>
                                                        Rejected
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($representative->reviewed_at)
                                                    <div class="date-info">
                                                        <span class="date-value">{{ $representative->reviewed_at->format('d M Y') }}</span>
                                                        <span class="date-time">{{ $representative->reviewed_at->format('h:i A') }}</span>
                                                    </div>
                                                @else
                                                    <span class="no-value">&mdash;</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('resource_person.equivalency_requests.review', $representative->id) }}" class="view-btn" title="View Details">
                                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Summary Footer --}}
                        <div class="summary-footer">
                            <div class="summary-item">
                                <span class="summary-value">{{ $decisionStats['total'] }}</span>
                                <span class="summary-label">Total Decisions</span>
                            </div>
                            <div class="summary-item success">
                                <span class="summary-value">{{ $decisionStats['approved'] }}</span>
                                <span class="summary-label">Equivalencies Created</span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-value">{{ $decisionStats['students_affected'] }}</span>
                                <span class="summary-label">Students Affected</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>

<style>
/* ============================================
   INDUSTRIAL UI DESIGN SYSTEM
   Resource Person - Equivalency Requests
   ============================================ */

.rp-requests {
    --uitm-blue: #1e3a8a;
    --uitm-blue-light: #3b82f6;
    --uitm-amber: #f59e0b;
    --uitm-amber-light: #fbbf24;
    --industrial-dark: #0f172a;
    --industrial-gray: #334155;
    --industrial-light: #f1f5f9;
    --success: #059669;
    --danger: #dc2626;
    --warning: #ea580c;
    --teal: #0d9488;

    font-family: 'IBM Plex Sans', -apple-system, BlinkMacSystemFont, sans-serif;
    padding: 0 1.5rem 2rem;
    background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
    min-height: 100vh;
}

h1, h2, h3, h4 {
    font-family: 'IBM Plex Sans', sans-serif;
    font-weight: 600;
    color: var(--industrial-dark);
}

.stat-value, .summary-value, .match-badge {
    font-family: 'IBM Plex Mono', monospace;
}

/* ============================================
   PAGE HEADER
   ============================================ */
.page-header {
    background: linear-gradient(135deg, var(--industrial-gray) 0%, var(--industrial-dark) 100%);
    margin: -1rem -1.5rem 1.5rem;
    padding: 1.5rem 2rem;
    position: relative;
    overflow: hidden;
}

.page-header::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px),
        linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px);
    background-size: 32px 32px;
}

.page-header::after {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(245, 158, 11, 0.12) 0%, transparent 70%);
    border-radius: 50%;
}

.header-content {
    position: relative;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.header-icon {
    width: 48px;
    height: 48px;
    background: rgba(255,255,255,0.1);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.header-info h1 {
    color: white;
    font-size: 1.375rem;
    margin: 0 0 0.25rem;
}

.header-info p {
    color: rgba(255,255,255,0.6);
    font-size: 0.875rem;
    margin: 0;
}

.back-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(255,255,255,0.1);
    color: white;
    padding: 0.625rem 1rem;
    border-radius: 8px;
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
    border: 1px solid rgba(255,255,255,0.15);
    transition: all 0.2s;
}

.back-btn:hover {
    background: rgba(255,255,255,0.2);
    color: white;
}

/* ============================================
   ALERTS
   ============================================ */
.industrial-alert {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.875rem 1rem;
    border-radius: 8px;
    margin-bottom: 1rem;
    border-left: 4px solid;
}

.industrial-alert.success {
    background: #ecfdf5;
    border-color: var(--success);
    color: #065f46;
}

.industrial-alert.error {
    background: #fef2f2;
    border-color: var(--danger);
    color: #991b1b;
}

.alert-close {
    margin-left: auto;
    background: none;
    border: none;
    font-size: 1.25rem;
    cursor: pointer;
    opacity: 0.5;
}

.alert-close:hover {
    opacity: 1;
}

/* ============================================
   STATISTICS GRID
   ============================================ */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}

@media (min-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(4, 1fr);
    }
}

.stat-card {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 1.25rem;
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
}

.stat-card.pending::before { background: var(--uitm-blue); }
.stat-card.syllabus::before { background: var(--uitm-amber); }
.stat-card.approved::before { background: var(--success); }
.stat-card.rejected::before { background: var(--danger); }

.stat-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #64748b;
    font-weight: 600;
}

.stat-indicator {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #e2e8f0;
}

.stat-indicator.active {
    background: var(--uitm-amber);
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.4); }
    50% { box-shadow: 0 0 0 6px rgba(245, 158, 11, 0); }
}

.stat-header svg {
    color: #94a3b8;
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    color: var(--industrial-dark);
    line-height: 1;
    margin-bottom: 0.5rem;
}

.stat-footer {
    font-size: 0.8rem;
    color: #94a3b8;
}

/* ============================================
   TABS
   ============================================ */
.tabs-section {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
}

.tabs-nav {
    display: flex;
    border-bottom: 1px solid #e2e8f0;
    background: #fafbfc;
}

.tab-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem 1.5rem;
    background: none;
    border: none;
    font-size: 0.875rem;
    font-weight: 500;
    color: #64748b;
    cursor: pointer;
    position: relative;
    transition: all 0.2s;
}

.tab-btn::after {
    content: '';
    position: absolute;
    left: 0;
    right: 0;
    bottom: -1px;
    height: 2px;
    background: transparent;
    transition: background 0.2s;
}

.tab-btn:hover {
    color: var(--uitm-blue);
}

.tab-btn.active {
    color: var(--uitm-blue);
}

.tab-btn.active::after {
    background: var(--uitm-blue);
}

.tab-badge {
    font-family: 'IBM Plex Mono', monospace;
    font-size: 0.7rem;
    font-weight: 600;
    padding: 0.15rem 0.45rem;
    border-radius: 4px;
    background: var(--uitm-blue);
    color: white;
}

.tab-badge.secondary {
    background: #94a3b8;
}

/* ============================================
   CONTENT CARD
   ============================================ */
.content-card {
    border-top: none;
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 1rem 1.5rem;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    flex-wrap: wrap;
    gap: 1rem;
}

.header-info {
    display: flex;
    gap: 0.75rem;
}

.header-info svg {
    color: var(--uitm-blue);
    flex-shrink: 0;
    margin-top: 0.125rem;
}

.header-info h3 {
    font-size: 0.9375rem;
    margin: 0 0 0.25rem;
}

.header-info p {
    font-size: 0.8rem;
    color: #64748b;
    margin: 0;
}

.header-stats {
    display: flex;
    gap: 0.75rem;
}

.header-stat {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.8rem;
    font-weight: 500;
    padding: 0.35rem 0.625rem;
    border-radius: 6px;
}

.header-stat.success {
    background: rgba(5, 150, 105, 0.1);
    color: var(--success);
}

.header-stat.danger {
    background: rgba(220, 38, 38, 0.1);
    color: var(--danger);
}

/* ============================================
   TABLE
   ============================================ */
.table-wrapper {
    overflow-x: auto;
}

.requests-table {
    width: 100%;
    border-collapse: collapse;
}

.requests-table th {
    background: #fafbfc;
    padding: 0.875rem 1rem;
    text-align: left;
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #64748b;
    border-bottom: 1px solid #e2e8f0;
}

.requests-table td {
    padding: 1rem;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
}

.requests-table tr:last-child td {
    border-bottom: none;
}

.requests-table tr.has-syllabus {
    background: linear-gradient(to right, rgba(5, 150, 105, 0.05), transparent);
}

.requests-table tr:hover {
    background: #f8fafc;
}

.text-center {
    text-align: center;
}

/* Course Info */
.course-info {
    display: flex;
    flex-direction: column;
    gap: 0.2rem;
}

.course-info.compact {
    gap: 0.15rem;
}

.course-code {
    font-family: 'IBM Plex Mono', monospace;
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--industrial-dark);
}

.course-name {
    font-size: 0.8rem;
    color: #64748b;
}

.course-institution {
    font-size: 0.75rem;
    color: #94a3b8;
    font-style: italic;
}

/* Badges */
.program-badge {
    font-family: 'IBM Plex Mono', monospace;
    font-size: 0.75rem;
    font-weight: 600;
    background: rgba(30, 58, 138, 0.1);
    color: var(--uitm-blue);
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
}

.student-count {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.8rem;
    font-weight: 500;
    background: rgba(13, 148, 136, 0.1);
    color: var(--teal);
    padding: 0.35rem 0.625rem;
    border-radius: 6px;
}

.student-count.small {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

/* Status Badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.35rem 0.625rem;
    border-radius: 6px;
}

.status-badge.success {
    background: rgba(5, 150, 105, 0.1);
    color: var(--success);
}

.status-badge.info {
    background: rgba(13, 148, 136, 0.1);
    color: var(--teal);
}

.status-badge.warning {
    background: rgba(245, 158, 11, 0.1);
    color: #b45309;
}

.status-badge.primary {
    background: rgba(30, 58, 138, 0.1);
    color: var(--uitm-blue);
}

.status-hint {
    display: block;
    font-size: 0.7rem;
    color: #94a3b8;
    margin-top: 0.25rem;
}

.status-hint.success {
    color: var(--success);
    font-weight: 500;
}

/* Decision Badge */
.decision-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.35rem 0.625rem;
    border-radius: 6px;
}

.decision-badge.approved {
    background: rgba(5, 150, 105, 0.1);
    color: var(--success);
}

.decision-badge.rejected {
    background: rgba(220, 38, 38, 0.1);
    color: var(--danger);
}

/* Match Badge */
.match-badge {
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
}

.match-badge.high {
    background: rgba(5, 150, 105, 0.1);
    color: var(--success);
}

.match-badge.medium {
    background: rgba(245, 158, 11, 0.1);
    color: #b45309;
}

/* Date Info */
.date-info {
    display: flex;
    flex-direction: column;
}

.date-value {
    font-size: 0.8rem;
    color: var(--industrial-dark);
}

.date-time {
    font-size: 0.7rem;
    color: #94a3b8;
}

.no-value {
    color: #cbd5e1;
}

/* Buttons */
.review-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    background: var(--uitm-blue);
    color: white;
    font-size: 0.8rem;
    font-weight: 500;
    padding: 0.5rem 0.875rem;
    border-radius: 6px;
    text-decoration: none;
    transition: all 0.2s;
}

.review-btn:hover {
    background: #1e40af;
    color: white;
    transform: translateY(-1px);
}

.view-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    background: #f1f5f9;
    color: #64748b;
    border-radius: 6px;
    text-decoration: none;
    transition: all 0.2s;
}

.view-btn:hover {
    background: var(--uitm-blue);
    color: white;
}

/* ============================================
   EMPTY STATE
   ============================================ */
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
    color: #94a3b8;
}

.empty-state h4 {
    font-size: 1.125rem;
    margin: 0 0 0.5rem;
}

.empty-state p {
    font-size: 0.875rem;
    color: #64748b;
    margin: 0;
}

/* ============================================
   SUMMARY FOOTER
   ============================================ */
.summary-footer {
    display: flex;
    justify-content: center;
    gap: 3rem;
    padding: 1.5rem;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
}

.summary-item {
    text-align: center;
}

.summary-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--industrial-dark);
    display: block;
    line-height: 1;
    margin-bottom: 0.35rem;
}

.summary-item.success .summary-value {
    color: var(--success);
}

.summary-label {
    font-size: 0.75rem;
    color: #64748b;
}

/* ============================================
   RESPONSIVE
   ============================================ */
@media (max-width: 767px) {
    .rp-requests {
        padding: 0 1rem 1.5rem;
    }

    .page-header {
        margin: -1rem -1rem 1.25rem;
        padding: 1.25rem 1rem;
    }

    .header-content {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }

    .back-btn {
        width: 100%;
        justify-content: center;
    }

    .header-info h1 {
        font-size: 1.125rem;
    }

    .stat-value {
        font-size: 1.5rem;
    }

    .tabs-nav {
        overflow-x: auto;
    }

    .tab-btn {
        white-space: nowrap;
        padding: 0.875rem 1rem;
    }

    .summary-footer {
        flex-direction: column;
        gap: 1.5rem;
    }
}
</style>
@endsection
