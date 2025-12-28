@extends('layouts.app')

@push('styles')
<style>
/* ========================================
   ACCESSIBILITY & RESPONSIVE STYLES
   ======================================== */

/* Skip to main content link for screen readers */
.skip-to-main {
    position: absolute;
    left: -9999px;
    z-index: 999;
}

.skip-to-main:focus {
    left: 50%;
    transform: translateX(-50%);
    background: #667eea;
    color: white;
    padding: 1rem 2rem;
    border-radius: 0 0 8px 8px;
}

/* Focus visible for keyboard navigation */
a:focus-visible,
button:focus-visible,
.btn:focus-visible {
    outline: 3px solid #667eea;
    outline-offset: 2px;
}

/* ========================================
   WELCOME CARD
   ======================================== */
.welcome-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 24px;
    padding: 2rem;
    color: white;
    box-shadow: 0 20px 60px rgba(102, 126, 234, 0.3);
    position: relative;
    overflow: hidden;
}

.welcome-card::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    border-radius: 50%;
}

.welcome-title {
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.welcome-subtitle {
    font-size: 1rem;
    margin-bottom: 0.75rem;
    opacity: 0.95;
}

.welcome-description {
    font-size: 0.9375rem;
    line-height: 1.5;
    margin-bottom: 1rem;
    opacity: 0.9;
    max-width: 600px;
}

.welcome-stats .stat-item {
    font-size: 0.8125rem;
    opacity: 0.85;
}

.welcome-illustration {
    font-size: 10rem;
    opacity: 0.15;
    animation: float 6s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-20px); }
}

/* ========================================
   STATISTICS CARDS
   ======================================== */
.stat-card {
    border-radius: 20px;
    border: none;
    background: white;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    height: 100%;
}

.stat-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.15);
}

.stat-card-body {
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
    text-align: center;
}

.stat-card-icon {
    width: 60px;
    height: 60px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
    flex-shrink: 0;
}

.stat-card-primary .stat-card-icon {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}

.stat-card-warning .stat-card-icon {
    background: linear-gradient(135deg, #f093fb, #f5576c);
    color: white;
}

.stat-card-success .stat-card-icon {
    background: linear-gradient(135deg, #4facfe, #00f2fe);
    color: white;
}

.stat-card-info .stat-card-icon {
    background: linear-gradient(135deg, #43e97b, #38f9d7);
    color: white;
}

.stat-card-content {
    flex: 1;
}

.stat-number {
    font-size: 2.25rem;
    font-weight: 700;
    margin-bottom: 0.4rem;
    color: #2d3748;
    line-height: 1;
}

.stat-label {
    font-size: 0.9375rem;
    color: #718096;
    margin-bottom: 0.35rem;
    font-weight: 500;
}

.stat-trend {
    font-size: 0.8125rem;
    color: #a0aec0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* ========================================
   DASHBOARD CARDS
   ======================================== */
.dashboard-card {
    border-radius: 20px;
    border: none;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    height: 100%;
    background: white;
}

.dashboard-card .card-body {
    padding: 1.25rem 1.5rem;
}

.dashboard-card:hover {
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
}

.dashboard-card .card-header {
    background: white;
    border-bottom: 1px solid #e2e8f0;
    border-radius: 20px 20px 0 0;
    padding: 1.25rem 1.5rem;
}

.dashboard-card .card-title {
    color: #2d3748;
    font-weight: 600;
    font-size: 1.125rem;
    margin-bottom: 0.15rem;
}

.dashboard-card .card-subtitle {
    color: #718096;
    font-size: 0.8125rem;
    margin: 0;
}

/* ========================================
   ACTION CARDS
   ======================================== */
.action-card {
    padding: 1.25rem;
    border-radius: 16px;
    background: #f8fafc;
    border: 2px solid #e2e8f0;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    height: 100%;
    display: flex;
    flex-direction: column;
}

.action-card:hover {
    background: white;
    border-color: #cbd5e0;
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.1);
}

.action-card-primary:hover { border-color: #667eea; }
.action-card-info:hover { border-color: #38b2ac; }
.action-card-success:hover { border-color: #48bb78; }

.action-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin-bottom: 0.75rem;
    margin-left: auto;
    margin-right: auto;
}

.action-card-primary .action-icon {
    background: rgba(102, 126, 234, 0.1);
    color: #667eea;
}

.action-card-info .action-icon {
    background: rgba(56, 178, 172, 0.1);
    color: #38b2ac;
}

.action-card-success .action-icon {
    background: rgba(72, 187, 120, 0.1);
    color: #48bb78;
}

.action-title {
    font-size: 1rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.35rem;
}

.action-description {
    color: #718096;
    font-size: 0.8125rem;
    margin-bottom: 0.75rem;
    flex-grow: 1;
    line-height: 1.4;
}

/* Action button styles */
.action-btn {
    border-radius: 10px;
    font-weight: 600;
    padding: 0.625rem 1rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border-width: 2px;
}

.action-btn:hover {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-color: #667eea;
    color: white !important;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
}

.action-btn:active {
    transform: translateY(0);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
}

/* ========================================
   APPLICATION STATUS CARD
   ======================================== */
.application-status-card {
    padding: 0.75rem;
    background: #f8fafc;
    border-radius: 12px;
}

.application-id {
    font-family: 'Courier New', monospace;
    font-weight: 600;
    color: #2d3748;
    font-size: 1.125rem;
}

.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-badge-sm {
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-weight: 500;
    font-size: 0.7rem;
}

.status-pending,
.status-pending-academic-advisor,
.status-pending-resource-person,
.status-under-review {
    background: #fef3c7;
    color: #92400e;
}

.status-approved {
    background: #d1fae5;
    color: #065f46;
}

.status-rejected {
    background: #fee2e2;
    color: #991b1b;
}

/* ========================================
   TIMELINE
   ======================================== */
.activity-timeline {
    position: relative;
}

.timeline-item {
    display: flex;
    gap: 0.85rem;
    margin-bottom: 1.25rem;
    position: relative;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-item:not(:last-child)::after {
    content: '';
    position: absolute;
    left: 17px;
    top: 36px;
    width: 2px;
    height: calc(100% + 0.5rem);
    background: #e2e8f0;
}

.timeline-marker {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    position: relative;
    z-index: 1;
    background: white;
}

.timeline-marker-success {
    background: rgba(72, 187, 120, 0.1);
    color: #48bb78;
    border: 2px solid #48bb78;
}

.timeline-marker-warning {
    background: rgba(237, 137, 54, 0.1);
    color: #ed8936;
    border: 2px solid #ed8936;
}

.timeline-marker-danger {
    background: rgba(245, 101, 101, 0.1);
    color: #f56565;
    border: 2px solid #f56565;
}

.timeline-content {
    flex: 1;
    padding-top: 0.25rem;
}

.timeline-title {
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.25rem;
    font-size: 0.875rem;
}

.timeline-date {
    font-size: 0.75rem;
    color: #a0aec0;
    margin-bottom: 0.5rem;
}

/* ========================================
   RESOURCE LINKS
   ======================================== */
.resource-links {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.resource-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    border-radius: 10px;
    background: #f8fafc;
    color: #4a5568;
    text-decoration: none;
    transition: all 0.2s ease;
    border: 1px solid transparent;
}

.resource-link:hover {
    background: #edf2f7;
    color: #2d3748;
    text-decoration: none;
    border-color: #cbd5e0;
    transform: translateX(4px);
}

.resource-link:focus-visible {
    outline: 2px solid #667eea;
    outline-offset: 2px;
}

.resource-link i:first-child {
    font-size: 1.125rem;
    width: 20px;
    text-align: center;
}

.resource-link span {
    flex: 1;
    font-weight: 500;
    font-size: 0.9375rem;
}

.resource-link i:last-child {
    font-size: 0.75rem;
    color: #a0aec0;
}

/* ========================================
   EMPTY STATE
   ======================================== */
.empty-state {
    text-align: center;
    padding: 2.5rem 1.5rem;
}

.empty-state-icon {
    font-size: 3.5rem;
    color: #cbd5e0;
    margin-bottom: 1rem;
}

.empty-state-title {
    color: #2d3748;
    font-weight: 600;
    font-size: 1.25rem;
    margin-bottom: 0.5rem;
}

.empty-state-description {
    color: #718096;
    font-size: 0.9375rem;
    margin-bottom: 1.5rem;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
}

/* ========================================
   RESPONSIVE DESIGN
   ======================================== */
@media (max-width: 991px) {
    .welcome-title {
        font-size: 1.5rem;
    }

    .stat-number {
        font-size: 2rem;
    }

    .welcome-card {
        padding: 1.75rem;
    }
}

@media (max-width: 767px) {
    .welcome-title {
        font-size: 1.35rem;
    }

    .welcome-subtitle {
        font-size: 0.9375rem;
    }

    .welcome-card {
        padding: 1.5rem;
    }

    .stat-card-body {
        padding: 1.25rem;
        text-align: center;
    }

    .stat-number {
        font-size: 1.75rem;
    }

    .stat-card-icon {
        width: 50px;
        height: 50px;
        font-size: 1.35rem;
    }

    .action-card {
        text-align: center;
        padding: 1rem;
    }

    .action-icon {
        margin-left: auto;
        margin-right: auto;
    }
}

/* ========================================
   PRINT STYLES
   ======================================== */
@media print {
    .welcome-card,
    .action-card,
    .btn {
        box-shadow: none !important;
    }

    .stat-card:hover,
    .action-card:hover {
        transform: none !important;
    }
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .stat-card,
    .dashboard-card,
    .action-card {
        border: 2px solid currentColor;
    }
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
    *,
    *::before,
    *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }

    .welcome-illustration {
        animation: none;
    }
}
</style>
@endpush

@section('content')
<!-- Dashboard Compact Styles v5.0 - Standard Fonts, Compact Spacing -->
<style id="dashboard-compact-styles">
/* COMPACT SPACING WITH STANDARD FONTS - v5.0 */

/* CRITICAL: Remove height stretching that creates empty space */
.dashboard-card {
    height: auto !important;
    min-height: 0 !important;
}
.stat-card {
    height: auto !important;
    min-height: 0 !important;
}
.action-card {
    height: auto !important;
    min-height: 0 !important;
}

/* Welcome section - standard fonts, compact spacing */
.welcome-card { padding: 1.5rem !important; margin-bottom: 1rem !important; }
.welcome-title { margin-bottom: 0.5rem !important; } /* Keep h1 size */
.welcome-subtitle { margin-bottom: 0.5rem !important; } /* Keep standard size */
.welcome-description { margin-bottom: 0.75rem !important; line-height: 1.5 !important; }
.welcome-stats .stat-item { font-size: 0.875rem !important; }

/* Stat cards - standard fonts, reduced padding */
.stat-card-body { padding: 1.25rem !important; gap: 1rem !important; text-align: center !important; }
.stat-card-icon { width: 55px !important; height: 55px !important; }
.stat-number { margin-bottom: 0.25rem !important; } /* Keep h3 size */
.stat-label { margin-bottom: 0.25rem !important; } /* Keep standard size */
.stat-trend { font-size: 0.875rem !important; }

/* Dashboard cards - standard fonts, compact padding */
.dashboard-card .card-header { padding: 1rem 1.5rem !important; }
.dashboard-card .card-body { padding: 1rem 1.5rem !important; min-height: 0 !important; }
.dashboard-card .card-title { margin-bottom: 0.25rem !important; } /* Keep h2/h5 size */
.dashboard-card .card-subtitle { margin-bottom: 0 !important; }

/* Action cards - standard fonts, compact padding */
.action-card { padding: 1.25rem !important; }
.action-icon { width: 50px !important; height: 50px !important; margin-bottom: 0.75rem !important; }
.action-title { margin-bottom: 0.5rem !important; } /* Keep h3/h6 size */
.action-description { margin-bottom: 0.75rem !important; line-height: 1.4 !important; }

/* Application status */
.application-status-card { padding: 0.75rem !important; }

/* Timeline - compact */
.timeline-item { gap: 0.75rem !important; margin-bottom: 1rem !important; }
.timeline-marker { width: 36px !important; height: 36px !important; }
.timeline-item:not(:last-child)::after { left: 17px !important; top: 36px !important; }
.timeline-title { margin-bottom: 0.25rem !important; }
.timeline-date { margin-bottom: 0.5rem !important; }

/* Resource links - compact */
.resource-link { gap: 0.75rem !important; padding: 0.75rem 1rem !important; }

/* Empty state */
.empty-state { padding: 2rem 1.5rem !important; }
.empty-state-icon { font-size: 3.5rem !important; margin-bottom: 1rem !important; }
.empty-state-title { margin-bottom: 0.5rem !important; }
.empty-state-description { margin-bottom: 1.5rem !important; }

/* Compact margins */
.row.mb-4, .row.mb-3 { margin-bottom: 0.75rem !important; }
.col-xl-3.mb-4, .col-lg-6.mb-4, .col-md-6.mb-4,
.col-xl-3.mb-3, .col-lg-6.mb-3, .col-md-6.mb-3 { margin-bottom: 0.75rem !important; }
.col-lg-8.mb-4, .col-lg-4.mb-4,
.col-lg-8.mb-3, .col-lg-4.mb-3 { margin-bottom: 0.75rem !important; }
.dashboard-card.mb-4, .dashboard-card.mb-3 { margin-bottom: 0.75rem !important; }

/* Progress bar */
.progress { height: 8px !important; }

/* Perfect alignment for cards */
.row.align-items-start {
    align-items: flex-start !important;
}

/* Ensure columns don't stretch */
.row.align-items-start > [class*='col-'] {
    display: flex;
    flex-direction: column;
}
</style>

<!-- Welcome Header -->
<div class="row mb-3">
    <div class="col-12">
        <div class="welcome-card" role="banner">
            <div class="row align-items-center">
                <div class="col-lg-9">
                    <div class="welcome-content">
                        <h1 class="welcome-title">Welcome Back, {{ Auth::user()->name }}!</h1>
                        <p class="welcome-subtitle">
                            <i class="fas fa-university me-2" aria-hidden="true"></i>
                            UiTM Credit Exemption Management System
                        </p>
                        <p class="welcome-description">
                            Track your credit exemption applications, submit new requests, and monitor your academic progress
                            all in one place.
                        </p>
                        <div class="welcome-stats">
                            <span class="stat-item">
                                <i class="fas fa-calendar me-1" aria-hidden="true"></i>
                                <strong>{{ \Carbon\Carbon::now()->format('l, F j, Y') }}</strong>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 text-end d-none d-lg-block">
                    <div class="welcome-illustration" aria-hidden="true">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Overview -->
<div class="row mb-3">
    <!-- Total Applications -->
    <div class="col-xl-4 col-lg-4 col-md-6 mb-3">
        <div class="stat-card stat-card-primary">
            <div class="stat-card-body">
                <div class="d-flex align-items-center justify-content-center gap-3 mb-2">
                    <div class="stat-card-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h3 class="stat-number mb-0">{{ $stats['total'] }}</h3>
                </div>
                <p class="stat-label">Total Applications</p>
                <div class="stat-trend">
                    <i class="fas fa-layer-group"></i>
                    <span>All submissions</span>
                </div>
            </div>
        </div>
    </div>

    <!-- In Progress -->
    <div class="col-xl-4 col-lg-4 col-md-6 mb-3">
        <div class="stat-card stat-card-warning">
            <div class="stat-card-body">
                <div class="d-flex align-items-center justify-content-center gap-3 mb-2">
                    <div class="stat-card-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3 class="stat-number mb-0">{{ $stats['in_progress'] }}</h3>
                </div>
                <p class="stat-label">Under Review</p>
                <div class="stat-trend">
                    <i class="fas fa-hourglass-half"></i>
                    <span>Pending approval</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Courses Exempted -->
    <div class="col-xl-4 col-lg-4 col-md-6 mb-3">
        <div class="stat-card stat-card-info">
            <div class="stat-card-body">
                <div class="d-flex align-items-center justify-content-center gap-3 mb-2">
                    <div class="stat-card-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3 class="stat-number mb-0">{{ $courseStats['exempted'] }}</h3>
                </div>
                <p class="stat-label">Courses Exempted</p>
                <div class="stat-trend">
                    <i class="fas fa-check-circle"></i>
                    <span>Credits granted</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Row -->
<div class="row align-items-start">
    <!-- Left Column: Quick Actions & Latest Application -->
    <div class="col-lg-8 mb-3">
        <!-- Quick Actions -->
        <div class="card dashboard-card mb-3">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-bolt me-2" aria-hidden="true"></i>Quick Actions
                </h2>
                <p class="card-subtitle">Most commonly used features</p>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <!-- New Application -->
                    <div class="col-md-4">
                        <div class="action-card action-card-primary text-center">
                            <div class="action-icon">
                                <i class="fas fa-plus-circle"></i>
                            </div>
                            <h3 class="action-title">New Application</h3>
                            <p class="action-description">Submit a new credit exemption request</p>
                            <a href="{{ route('student.application.create') }}"
                               class="btn btn-outline-primary btn-sm w-100 action-btn"
                               aria-label="Create new credit exemption application">
                                <i class="fas fa-plus me-1"></i>Apply Now
                            </a>
                        </div>
                    </div>

                    <!-- Track Status -->
                    <div class="col-md-4">
                        <div class="action-card action-card-info text-center">
                            <div class="action-icon">
                                <i class="fas fa-search"></i>
                            </div>
                            <h3 class="action-title">Track Status</h3>
                            <p class="action-description">View all your application statuses</p>
                            <a href="{{ route('student.application.status') }}"
                               class="btn btn-outline-primary btn-sm w-100 action-btn"
                               aria-label="View application status">
                                <i class="fas fa-eye me-1"></i>View Status
                            </a>
                        </div>
                    </div>

                    <!-- View Equivalencies -->
                    <div class="col-md-4">
                        <div class="action-card action-card-success text-center">
                            <div class="action-icon">
                                <i class="fas fa-list-check"></i>
                            </div>
                            <h3 class="action-title">Course Equivalencies</h3>
                            <p class="action-description">Browse approved course equivalencies</p>
                            <a href="{{ route('student.course_equivalencies.index') }}"
                               class="btn btn-outline-primary btn-sm w-100 action-btn"
                               aria-label="View course equivalencies">
                                <i class="fas fa-book me-1"></i>Browse List
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Latest Application Status -->
        @if($latestApplication)
        <div class="card dashboard-card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-star me-2" aria-hidden="true"></i>Latest Application Status
                </h2>
                <p class="card-subtitle">Track your most recent submission</p>
            </div>
            <div class="card-body">
                <div class="application-status-card">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="application-info">
                                <div class="d-flex align-items-center mb-3">
                                    <span class="application-id">Application #{{ substr($latestApplication->id, 0, 8) }}</span>
                                    <span class="badge status-badge status-{{ strtolower(str_replace(' ', '-', $latestApplication->status)) }} ms-2">
                                        {{ $latestApplication->status }}
                                    </span>
                                </div>
                                <p class="text-muted mb-2">
                                    <i class="fas fa-calendar me-2"></i>
                                    Submitted {{ $latestApplication->created_at->format('F j, Y') }}
                                </p>
                                <p class="text-muted mb-3">
                                    <i class="fas fa-graduation-cap me-2"></i>
                                    {{ $latestApplication->current_program_code ?? 'N/A' }}
                                </p>

                                @php
                                    $subjects = $latestApplication->applicationSubjects;
                                    $exempted = $subjects->whereIn('status', ['exempted', 'Approved'])->count();
                                    $pending = $subjects->whereIn('status', ['pending', 'under_review', 'Pending Academic Advisor', 'Pending Resource Person', 'Forward to Coordinator'])->count();
                                    $rejected = $subjects->whereIn('status', ['Rejected', 'not_found', 'not_eligible_grade', 'not_eligible_match'])->count();
                                    $total = $subjects->count();
                                    $exemptionRate = $total > 0 ? round(($exempted / $total) * 100) : 0;
                                @endphp

                                <div class="course-results mb-3">
                                    <!-- Header -->
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <h6 class="mb-0 fw-semibold" style="color: #2d3748;">Evaluation Results</h6>
                                            <i class="fas fa-info-circle text-muted"
                                               data-bs-toggle="tooltip"
                                               title="Results based on HEA criteria: course match, grade requirement (C+), and equivalency percentage (≥80%)"
                                               style="font-size: 0.75rem; cursor: help;"></i>
                                        </div>
                                        <p class="small mb-0" style="color: #718096;">{{ $total }} courses evaluated</p>
                                    </div>

                                    <!-- Results Breakdown -->
                                    <div class="results-grid">
                                        <!-- Exempted -->
                                        <div class="result-item" style="background-color: #f0fdf4; border-left: 3px solid #48bb78; padding: 0.75rem; border-radius: 8px; margin-bottom: 0.5rem;">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="fas fa-check-circle" style="color: #48bb78; font-size: 1.25rem;"></i>
                                                    <div>
                                                        <div class="fw-semibold" style="color: #166534; font-size: 0.9rem;">Exempted</div>
                                                        <div class="small" style="color: #15803d;">Meets all HEA criteria</div>
                                                    </div>
                                                </div>
                                                <div class="text-end">
                                                    <div class="fw-bold" style="color: #166534; font-size: 1.5rem;">{{ $exempted }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        @if($pending > 0)
                                        <!-- Under Review -->
                                        <div class="result-item" style="background-color: #fffbeb; border-left: 3px solid #ed8936; padding: 0.75rem; border-radius: 8px; margin-bottom: 0.5rem;">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="fas fa-clock" style="color: #ed8936; font-size: 1.25rem;"></i>
                                                    <div>
                                                        <div class="fw-semibold" style="color: #92400e; font-size: 0.9rem;">Under Review</div>
                                                        <div class="small" style="color: #b45309;">Being evaluated</div>
                                                    </div>
                                                </div>
                                                <div class="text-end">
                                                    <div class="fw-bold" style="color: #92400e; font-size: 1.5rem;">{{ $pending }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('student.application.status') }}"
                               class="btn btn-primary">
                                <i class="fas fa-arrow-right me-1"></i>View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="card dashboard-card">
            <div class="card-body">
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-file-circle-plus"></i>
                    </div>
                    <h3 class="empty-state-title">No Applications Yet</h3>
                    <p class="empty-state-description">Start your credit exemption journey by submitting your first application.</p>
                    <a href="{{ route('student.application.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Create First Application
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Right Column: Recent Activity & Resources -->
    <div class="col-lg-4 mb-3">
        <!-- Recent Activity -->
        <div class="card dashboard-card mb-3">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-history me-2" aria-hidden="true"></i>Recent Activity
                </h2>
                <p class="card-subtitle">Latest submissions</p>
            </div>
            <div class="card-body">
                @if($recentApplications->count() > 0)
                    <div class="activity-timeline">
                        @foreach($recentApplications as $application)
                        <div class="timeline-item">
                            <div class="timeline-marker timeline-marker-{{
                                $application->status === 'approved' ? 'success' :
                                ($application->status === 'rejected' ? 'danger' : 'warning')
                            }}">
                                <i class="fas {{
                                    $application->status === 'approved' ? 'fa-check' :
                                    ($application->status === 'rejected' ? 'fa-times' : 'fa-clock')
                                }}"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">
                                    Application #{{ substr($application->id, 0, 8) }}
                                </h6>
                                <p class="timeline-date">{{ $application->created_at->diffForHumans() }}</p>
                                <span class="badge badge-sm status-badge-sm status-{{ strtolower(str_replace(' ', '-', $application->status)) }}">
                                    {{ $application->status }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('student.application.status') }}" class="btn btn-link btn-sm">
                            View All Applications <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-inbox text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-3 mb-0">No recent activity</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Help & Resources -->
        <div class="card dashboard-card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-info-circle me-2" aria-hidden="true"></i>Help & Resources
                </h2>
                <p class="card-subtitle">Useful information</p>
            </div>
            <div class="card-body">
                <div class="resource-links">
                    <a href="#termsModal"
                       class="resource-link"
                       data-bs-toggle="modal"
                       data-bs-target="#termsModal"
                       aria-label="Read terms and conditions">
                        <i class="fas fa-file-contract text-primary"></i>
                        <span>Terms & Conditions</span>
                        <i class="fas fa-chevron-right ms-auto"></i>
                    </a>

                    <a href="{{ \App\Models\SystemSetting::get('academic_calendar_url', '#') }}"
                       class="resource-link"
                       target="_blank"
                       rel="noopener noreferrer"
                       aria-label="View academic calendar (opens in new window)">
                        <i class="fas fa-calendar-alt text-info"></i>
                        <span>Academic Calendar</span>
                        <i class="fas fa-external-link-alt ms-auto"></i>
                    </a>

                    <a href="{{ route('student.course_equivalencies.index') }}"
                       class="resource-link"
                       aria-label="Browse course equivalencies">
                        <i class="fas fa-book-open text-success"></i>
                        <span>Course Equivalencies</span>
                        <i class="fas fa-chevron-right ms-auto"></i>
                    </a>

                    <a href="{{ route('profile.show') }}"
                       class="resource-link"
                       aria-label="Manage your profile">
                        <i class="fas fa-user-cog text-warning"></i>
                        <span>Profile Settings</span>
                        <i class="fas fa-chevron-right ms-auto"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Terms and Conditions Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="termsModalLabel">
                    <i class="fas fa-file-contract me-2"></i>Terms and Conditions
                </h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @php
                    $termsContent = \App\Models\SystemSetting::get('terms_and_conditions', 'Terms and conditions content will appear here.');
                    $termsContent = nl2br(e($termsContent));
                @endphp
                {!! $termsContent !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Initialize Bootstrap tooltips
document.addEventListener('DOMContentLoaded', function() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush

@endsection
