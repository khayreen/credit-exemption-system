@extends('layouts.app')

@section('content')
<div class="pc-requests">
    {{-- Page Header --}}
    <header class="page-header">
        <div class="header-content">
            <div class="header-info">
                <div class="header-icon">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                </div>
                <div>
                    <h1>Course Equivalency Requests</h1>
                    <p>Review and process student requests for course equivalency mappings</p>
                </div>
            </div>
            <a href="{{ route('program_coordinator.dashboard') }}" class="back-btn">
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

    @if($errors->any())
        <div class="industrial-alert error">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>@foreach($errors->all() as $error) {{ $error }} @endforeach</span>
            <button type="button" class="alert-close" data-bs-dismiss="alert">&times;</button>
        </div>
    @endif

    {{-- Statistics Cards --}}
    <section class="stats-grid">
        <div class="stat-card pending">
            <div class="stat-header">
                <span class="stat-label">Pending Review</span>
                <div class="stat-indicator {{ $stats['pending'] > 0 ? 'active' : '' }}"></div>
            </div>
            <div class="stat-value">{{ $stats['pending'] }}</div>
            <div class="stat-footer">
                <span class="stat-badge">{{ $stats['unique_courses'] }} unique course(s)</span>
            </div>
        </div>

        <div class="stat-card approved">
            <div class="stat-header">
                <span class="stat-label">Approved</span>
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-value success">{{ $stats['approved'] }}</div>
            <div class="stat-footer">Marked as equivalent</div>
        </div>

        <div class="stat-card rejected">
            <div class="stat-header">
                <span class="stat-label">Rejected</span>
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-value danger">{{ $stats['rejected'] }}</div>
            <div class="stat-footer">Not equivalent</div>
        </div>

        <div class="stat-card forwarded">
            <div class="stat-header">
                <span class="stat-label">Forwarded</span>
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                </svg>
            </div>
            <div class="stat-value info">{{ $stats['forwarded'] }}</div>
            <div class="stat-footer">Sent to Resource Person</div>
        </div>
    </section>

    {{-- Validation Warning --}}
    @if($stats['not_in_transcript'] > 0)
        <div class="validation-warning">
            <div class="warning-content">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <div class="warning-text">
                    <strong>Attention:</strong> {{ $stats['not_in_transcript'] }} request(s) are from students who <strong>did not take the course</strong> in their diploma. These should be rejected as the course is not in their transcript.
                </div>
            </div>
            <form action="{{ route('program_coordinator.bulk_reject_not_in_transcript') }}" method="POST">
                @csrf
                <button type="submit" class="bulk-reject-btn" onclick="return confirm('Are you sure you want to reject all {{ $stats['not_in_transcript'] }} requests where the course is not in the student\'s transcript?')">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                    </svg>
                    Bulk Reject All ({{ $stats['not_in_transcript'] }})
                </button>
            </form>
        </div>
    @endif

    {{-- Main Content Card --}}
    <section class="requests-card">
        {{-- Program Filter Tabs --}}
        <div class="card-header">
            <nav class="program-tabs">
                <button class="program-tab active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all-requests" type="button" role="tab">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    All Programs
                    @if($stats['pending'] > 0)
                        <span class="tab-badge">{{ $stats['pending'] }}</span>
                    @endif
                </button>
                @foreach($programBreakdown as $code => $program)
                    <button class="program-tab" id="tab-{{ $code }}" data-bs-toggle="tab" data-bs-target="#program-{{ $code }}" type="button" role="tab">
                        {{ $code }}
                        @if($program['pending_count'] > 0)
                            <span class="tab-badge warning">{{ $program['pending_count'] }}</span>
                        @endif
                    </button>
                @endforeach
            </nav>
        </div>

        <div class="tab-content">
            {{-- All Programs Tab --}}
            <div class="tab-pane fade show active" id="all-requests" role="tabpanel">
                @if($groupedRequests->isEmpty())
                    <div class="empty-state">
                        <div class="empty-icon">
                            <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                        </div>
                        <h4>No Pending Requests</h4>
                        <p>There are no course equivalency requests awaiting your review.</p>
                    </div>
                @else
                    <div class="table-wrapper">
                        <table class="requests-table">
                            <thead>
                                <tr>
                                    <th class="col-course">Diploma Course</th>
                                    <th class="col-institution">Source Institution</th>
                                    <th class="col-students">Students</th>
                                    <th class="col-lecturers">Lecturers</th>
                                    <th class="col-programs">Programs</th>
                                    <th class="col-actions">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($groupedRequests as $courseCode => $courseRequests)
                                    @php
                                        $firstRequest = $courseRequests->first();
                                        $uniqueLecturers = $courseRequests->pluck('external_lecturer_email')->unique()->filter();
                                        $uniquePrograms = $courseRequests->pluck('current_program_code')->unique();
                                        $verifiedCount = $courseRequests->filter(fn($r) => isset($r->transcript_validation['status']) && $r->transcript_validation['status'] === 'verified')->count();
                                        $notInTranscriptCount = $courseRequests->filter(fn($r) => isset($r->transcript_validation['status']) && $r->transcript_validation['status'] === 'not_in_transcript')->count();
                                        $noApplicationCount = $courseRequests->filter(fn($r) => isset($r->transcript_validation['status']) && $r->transcript_validation['status'] === 'no_application')->count();
                                    @endphp
                                    <tr class="{{ $notInTranscriptCount > 0 ? 'invalid-row' : '' }}">
                                        <td class="col-course">
                                            <div class="course-info">
                                                <div class="course-icon {{ $notInTranscriptCount > 0 ? 'danger' : '' }}">
                                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                    </svg>
                                                </div>
                                                <div class="course-details">
                                                    <span class="course-code">{{ $courseCode }}</span>
                                                    <span class="course-name">{{ Str::limit($firstRequest->diploma_course_name, 40) }}</span>
                                                    <div class="validation-badges">
                                                        @if($verifiedCount > 0)
                                                            <span class="v-badge success" title="Verified in transcript">
                                                                <svg width="10" height="10" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                                </svg>
                                                                {{ $verifiedCount }} verified
                                                            </span>
                                                        @endif
                                                        @if($notInTranscriptCount > 0)
                                                            <span class="v-badge danger" title="Not in student's transcript">
                                                                <svg width="10" height="10" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                                </svg>
                                                                {{ $notInTranscriptCount }} invalid
                                                            </span>
                                                        @endif
                                                        @if($noApplicationCount > 0)
                                                            <span class="v-badge warning" title="No credit exemption application">
                                                                <svg width="10" height="10" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                                                                </svg>
                                                                {{ $noApplicationCount }} no app
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="col-institution">
                                            <span class="institution-name">
                                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                </svg>
                                                {{ Str::limit($firstRequest->diploma_institution, 30) }}
                                            </span>
                                        </td>
                                        <td class="col-students">
                                            <span class="count-badge students">
                                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                                </svg>
                                                {{ $courseRequests->count() }}
                                            </span>
                                        </td>
                                        <td class="col-lecturers">
                                            <span class="count-badge lecturers">
                                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                                                </svg>
                                                {{ $uniqueLecturers->count() }}
                                            </span>
                                        </td>
                                        <td class="col-programs">
                                            <div class="program-badges">
                                                @foreach($uniquePrograms as $program)
                                                    <span class="program-badge">{{ $program }}</span>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="col-actions">
                                            <a href="{{ route('program_coordinator.course_requests', $courseCode) }}" class="review-btn">
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

            {{-- Individual Program Tabs --}}
            @foreach($programBreakdown as $code => $program)
                <div class="tab-pane fade" id="program-{{ $code }}" role="tabpanel">
                    @if($program['grouped']->isEmpty())
                        <div class="empty-state">
                            <div class="empty-icon success">
                                <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h4>All Clear!</h4>
                            <p>No pending requests for {{ $program['name'] }}</p>
                        </div>
                    @else
                        <div class="program-header">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M12 14l9-5-9-5-9 5 9 5z"/>
                                <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                            </svg>
                            <span>{{ $program['name'] }}</span>
                        </div>
                        <div class="table-wrapper">
                            <table class="requests-table">
                                <thead>
                                    <tr>
                                        <th class="col-course">Diploma Course</th>
                                        <th class="col-institution">Source Institution</th>
                                        <th class="col-students">Students</th>
                                        <th class="col-lecturers">Lecturers</th>
                                        <th class="col-actions">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($program['grouped'] as $courseCode => $courseRequests)
                                        @php
                                            $firstRequest = $courseRequests->first();
                                            $uniqueLecturers = $courseRequests->pluck('external_lecturer_email')->unique()->filter();
                                        @endphp
                                        <tr>
                                            <td class="col-course">
                                                <div class="course-info">
                                                    <div class="course-icon">
                                                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                        </svg>
                                                    </div>
                                                    <div class="course-details">
                                                        <span class="course-code">{{ $courseCode }}</span>
                                                        <span class="course-name">{{ Str::limit($firstRequest->diploma_course_name, 45) }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="col-institution">
                                                <span class="institution-name">
                                                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                    </svg>
                                                    {{ Str::limit($firstRequest->diploma_institution, 35) }}
                                                </span>
                                            </td>
                                            <td class="col-students">
                                                <span class="count-badge students">
                                                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                                    </svg>
                                                    {{ $courseRequests->count() }}
                                                </span>
                                            </td>
                                            <td class="col-lecturers">
                                                <span class="count-badge lecturers">
                                                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                                                    </svg>
                                                    {{ $uniqueLecturers->count() }}
                                                </span>
                                            </td>
                                            <td class="col-actions">
                                                <a href="{{ route('program_coordinator.course_requests', $courseCode) }}" class="review-btn">
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
            @endforeach
        </div>
    </section>

    {{-- Info Banner --}}
    <section class="info-banner">
        <div class="banner-content">
            <svg width="18" height="18" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <div class="banner-text">
                <h4>How to Review Requests</h4>
                <p>Click <strong>Review</strong> to see all student requests for a specific diploma course. You can mark courses as <span class="text-success">Equivalent</span>, <span class="text-danger">Not Equivalent</span>, or <span class="text-info">Forward to Resource Person</span> for expert evaluation.</p>
            </div>
        </div>
        <a href="{{ route('program_coordinator.course_equivalencies.view') }}" class="banner-btn">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            View All Course Mappings
        </a>
    </section>

    {{-- Recent Decisions --}}
    @if($recentDecisions->isNotEmpty())
    <section class="recent-decisions">
        <div class="section-header">
            <div class="header-info">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3>Recent Decisions</h3>
            </div>
            <a href="{{ route('program_coordinator.history') }}" class="view-all-btn">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
                View All History
            </a>
        </div>
        <div class="table-wrapper">
            <table class="decisions-table">
                <thead>
                    <tr>
                        <th>Diploma Course</th>
                        <th>Student</th>
                        <th>Decision</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentDecisions as $decision)
                        <tr>
                            <td>
                                <div class="decision-course">
                                    <span class="decision-icon {{ $decision->coordinator_decision === 'equivalent' ? 'success' : ($decision->coordinator_decision === 'not_equivalent' ? 'danger' : 'info') }}">
                                        @if($decision->coordinator_decision === 'equivalent')
                                            <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        @elseif($decision->coordinator_decision === 'not_equivalent')
                                            <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                        @else
                                            <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M15 8a3 3 0 10-2.977-2.63l-4.94 2.47a3 3 0 100 4.319l4.94 2.47a3 3 0 10.895-1.789l-4.94-2.47a3.027 3.027 0 000-.74l4.94-2.47C13.456 7.68 14.19 8 15 8z"/>
                                            </svg>
                                        @endif
                                    </span>
                                    <div class="course-details">
                                        <span class="course-code">{{ $decision->diploma_course_code }}</span>
                                        <span class="course-name">{{ Str::limit($decision->diploma_course_name, 25) }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="student-info">
                                    <span class="student-matric">{{ $decision->student->matric_no ?? 'N/A' }}</span>
                                    <span class="student-name">{{ Str::limit($decision->student->user->name ?? 'Unknown', 20) }}</span>
                                </div>
                            </td>
                            <td>
                                @if($decision->coordinator_decision === 'equivalent')
                                    <span class="decision-badge approved">Approved</span>
                                    @if($decision->approved_degree_course_code)
                                        <span class="mapping-code">&rarr; {{ $decision->approved_degree_course_code }}</span>
                                    @endif
                                @elseif($decision->coordinator_decision === 'not_equivalent')
                                    <span class="decision-badge rejected">Rejected</span>
                                @elseif($decision->coordinator_decision === 'forward_to_rp')
                                    <span class="decision-badge forwarded">Forwarded</span>
                                @endif
                            </td>
                            <td>
                                <div class="date-info">
                                    <span class="date-value">{{ $decision->coordinator_decided_at ? $decision->coordinator_decided_at->format('d M Y') : '-' }}</span>
                                    <span class="date-relative">{{ $decision->coordinator_decided_at ? $decision->coordinator_decided_at->diffForHumans() : '' }}</span>
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('program_coordinator.show_request', $decision->id) }}" class="view-btn" title="View Details">
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
    </section>
    @endif
</div>

<style>
/* ============================================
   INDUSTRIAL UI DESIGN SYSTEM
   Program Coordinator - Equivalency Requests
   ============================================ */

.pc-requests {
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
    --info: #0891b2;

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

.stat-value, .course-code, .student-matric {
    font-family: 'IBM Plex Mono', monospace;
}

/* ============================================
   PAGE HEADER
   ============================================ */
.page-header {
    background: linear-gradient(135deg, var(--uitm-blue) 0%, #1e40af 50%, var(--industrial-dark) 100%);
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
    background: radial-gradient(circle, rgba(245, 158, 11, 0.15) 0%, transparent 70%);
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
    transition: transform 0.2s, box-shadow 0.2s;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
}

.stat-card.pending::before { background: var(--uitm-amber); }
.stat-card.approved::before { background: var(--success); }
.stat-card.rejected::before { background: var(--danger); }
.stat-card.forwarded::before { background: var(--info); }

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

.stat-value.success { color: var(--success); }
.stat-value.danger { color: var(--danger); }
.stat-value.info { color: var(--info); }

.stat-footer {
    font-size: 0.8rem;
    color: #94a3b8;
}

.stat-badge {
    background: rgba(245, 158, 11, 0.1);
    color: #b45309;
    font-size: 0.7rem;
    font-weight: 600;
    padding: 0.2rem 0.5rem;
    border-radius: 4px;
}

/* ============================================
   VALIDATION WARNING
   ============================================ */
.validation-warning {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #fef2f2;
    border: 1px solid rgba(220, 38, 38, 0.2);
    border-left: 4px solid var(--danger);
    border-radius: 8px;
    padding: 1rem 1.25rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.warning-content {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    flex: 1;
}

.warning-content svg {
    color: var(--danger);
    flex-shrink: 0;
    margin-top: 0.125rem;
}

.warning-text {
    font-size: 0.875rem;
    color: #991b1b;
}

.bulk-reject-btn {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    background: var(--danger);
    color: white;
    padding: 0.5rem 0.875rem;
    border-radius: 6px;
    border: none;
    font-size: 0.8rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.bulk-reject-btn:hover {
    background: #b91c1c;
}

/* ============================================
   REQUESTS CARD
   ============================================ */
.requests-card {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 1.5rem;
}

.card-header {
    background: #fafbfc;
    border-bottom: 1px solid #e2e8f0;
    padding: 0;
    overflow-x: auto;
}

.program-tabs {
    display: flex;
    gap: 0;
}

.program-tab {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem 1.25rem;
    background: none;
    border: none;
    font-size: 0.875rem;
    font-weight: 500;
    color: #64748b;
    cursor: pointer;
    position: relative;
    white-space: nowrap;
    transition: all 0.2s;
}

.program-tab::after {
    content: '';
    position: absolute;
    left: 0;
    right: 0;
    bottom: 0;
    height: 2px;
    background: transparent;
}

.program-tab:hover {
    color: var(--uitm-blue);
    background: rgba(30, 58, 138, 0.03);
}

.program-tab.active {
    color: var(--uitm-blue);
}

.program-tab.active::after {
    background: var(--uitm-blue);
}

.tab-badge {
    font-family: 'IBM Plex Mono', monospace;
    font-size: 0.65rem;
    font-weight: 600;
    padding: 0.15rem 0.4rem;
    border-radius: 4px;
    background: var(--uitm-blue);
    color: white;
}

.tab-badge.warning {
    background: var(--uitm-amber);
    color: var(--industrial-dark);
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

.requests-table tr:hover {
    background: #f8fafc;
}

.requests-table tr.invalid-row {
    background: rgba(220, 38, 38, 0.03);
}

.requests-table tr.invalid-row:hover {
    background: rgba(220, 38, 38, 0.06);
}

.col-course { width: 30%; }
.col-institution { width: 20%; }
.col-students, .col-lecturers { width: 10%; text-align: center; }
.col-programs { width: 18%; }
.col-actions { width: 12%; text-align: center; }

/* Course Info */
.course-info {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
}

.course-icon {
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, var(--uitm-blue), #2563eb);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    flex-shrink: 0;
}

.course-icon.danger {
    background: linear-gradient(135deg, var(--danger), #b91c1c);
}

.course-details {
    display: flex;
    flex-direction: column;
    gap: 0.15rem;
    min-width: 0;
}

.course-code {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--industrial-dark);
}

.course-name {
    font-size: 0.8rem;
    color: #64748b;
}

.validation-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 0.35rem;
    margin-top: 0.35rem;
}

.v-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.2rem;
    font-size: 0.65rem;
    font-weight: 500;
    padding: 0.15rem 0.4rem;
    border-radius: 3px;
}

.v-badge.success {
    background: rgba(5, 150, 105, 0.1);
    color: var(--success);
}

.v-badge.danger {
    background: rgba(220, 38, 38, 0.1);
    color: var(--danger);
}

.v-badge.warning {
    background: rgba(245, 158, 11, 0.1);
    color: #b45309;
}

.institution-name {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.8rem;
    color: #64748b;
}

.institution-name svg {
    color: #94a3b8;
}

/* Count Badges */
.count-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.8rem;
    font-weight: 500;
    padding: 0.35rem 0.625rem;
    border-radius: 6px;
}

.count-badge.students {
    background: rgba(30, 58, 138, 0.1);
    color: var(--uitm-blue);
}

.count-badge.lecturers {
    background: rgba(100, 116, 139, 0.1);
    color: var(--industrial-gray);
}

/* Program Badges */
.program-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 0.35rem;
}

.program-badge {
    font-family: 'IBM Plex Mono', monospace;
    font-size: 0.7rem;
    font-weight: 500;
    background: #f1f5f9;
    color: var(--industrial-gray);
    padding: 0.2rem 0.5rem;
    border-radius: 4px;
    border: 1px solid #e2e8f0;
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

/* ============================================
   PROGRAM HEADER
   ============================================ */
.program-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.875rem 1rem;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--uitm-blue);
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

.empty-icon.success {
    background: rgba(5, 150, 105, 0.1);
    color: var(--success);
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
   INFO BANNER
   ============================================ */
.info-banner {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 1.25rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.banner-content {
    display: flex;
    gap: 0.75rem;
    flex: 1;
}

.banner-content svg {
    color: var(--teal);
    flex-shrink: 0;
    margin-top: 0.125rem;
}

.banner-text h4 {
    font-size: 0.9rem;
    margin: 0 0 0.25rem;
}

.banner-text p {
    font-size: 0.8rem;
    color: #64748b;
    margin: 0;
}

.text-success { color: var(--success); font-weight: 600; }
.text-danger { color: var(--danger); font-weight: 600; }
.text-info { color: var(--teal); font-weight: 600; }

.banner-btn {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    background: rgba(30, 58, 138, 0.05);
    color: var(--uitm-blue);
    font-size: 0.8rem;
    font-weight: 500;
    padding: 0.5rem 0.875rem;
    border-radius: 6px;
    text-decoration: none;
    border: 1px solid rgba(30, 58, 138, 0.15);
    transition: all 0.2s;
}

.banner-btn:hover {
    background: var(--uitm-blue);
    color: white;
    border-color: var(--uitm-blue);
}

/* ============================================
   RECENT DECISIONS
   ============================================ */
.recent-decisions {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.25rem;
    background: #fafbfc;
    border-bottom: 1px solid #e2e8f0;
}

.section-header .header-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.section-header .header-info svg {
    color: var(--uitm-blue);
}

.section-header h3 {
    font-size: 1rem;
    margin: 0;
}

.view-all-btn {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.8rem;
    font-weight: 500;
    color: var(--uitm-blue);
    background: rgba(30, 58, 138, 0.05);
    padding: 0.4rem 0.75rem;
    border-radius: 6px;
    text-decoration: none;
    transition: all 0.2s;
}

.view-all-btn:hover {
    background: var(--uitm-blue);
    color: white;
}

.decisions-table {
    width: 100%;
    border-collapse: collapse;
}

.decisions-table th {
    background: #fafbfc;
    padding: 0.75rem 1rem;
    text-align: left;
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #64748b;
    border-bottom: 1px solid #e2e8f0;
}

.decisions-table td {
    padding: 0.875rem 1rem;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
}

.decisions-table tr:last-child td {
    border-bottom: none;
}

.decision-course {
    display: flex;
    align-items: center;
    gap: 0.625rem;
}

.decision-icon {
    width: 28px;
    height: 28px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.decision-icon.success {
    background: rgba(5, 150, 105, 0.1);
    color: var(--success);
}

.decision-icon.danger {
    background: rgba(220, 38, 38, 0.1);
    color: var(--danger);
}

.decision-icon.info {
    background: rgba(8, 145, 178, 0.1);
    color: var(--info);
}

.student-info {
    display: flex;
    flex-direction: column;
}

.student-matric {
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--industrial-dark);
}

.student-name {
    font-size: 0.75rem;
    color: #64748b;
}

.decision-badge {
    display: inline-flex;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
}

.decision-badge.approved {
    background: rgba(5, 150, 105, 0.1);
    color: var(--success);
}

.decision-badge.rejected {
    background: rgba(220, 38, 38, 0.1);
    color: var(--danger);
}

.decision-badge.forwarded {
    background: rgba(8, 145, 178, 0.1);
    color: var(--info);
}

.mapping-code {
    display: block;
    font-size: 0.7rem;
    color: #64748b;
    margin-top: 0.25rem;
}

.date-info {
    display: flex;
    flex-direction: column;
}

.date-value {
    font-size: 0.8rem;
    color: var(--industrial-dark);
}

.date-relative {
    font-size: 0.7rem;
    color: #94a3b8;
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
   RESPONSIVE
   ============================================ */
@media (max-width: 767px) {
    .pc-requests {
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

    .validation-warning {
        flex-direction: column;
        align-items: flex-start;
    }

    .bulk-reject-btn {
        width: 100%;
        justify-content: center;
    }

    .info-banner {
        flex-direction: column;
        align-items: flex-start;
    }

    .banner-btn {
        width: 100%;
        justify-content: center;
    }
}
</style>
@endsection
