@extends('layouts.app')

@section('content')
<div class="aa-dashboard">
    <!-- Geometric Background Pattern -->
    <div class="aa-bg-pattern"></div>

    <!-- Page Header -->
    <header class="aa-header">
        <div class="aa-header-grid">
            <div class="aa-header-main">
                <div class="aa-header-badge">
                    <div class="badge-icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="badge-pulse"></div>
                </div>
                <div class="aa-header-text">
                    <div class="aa-header-eyebrow">Academic Advisor Console</div>
                    <h1 class="aa-header-title">Dashboard</h1>
                    <p class="aa-header-subtitle">Credit exemption application review and management</p>
                </div>
            </div>
            <div class="aa-header-actions">
                <a href="{{ route('academic_advisor.my_students') }}" class="aa-btn aa-btn-primary">
                    <i class="fas fa-users"></i>
                    <span>My Students</span>
                </a>
                <a href="{{ route('academic_advisor.equivalency_lists.index') }}" class="aa-btn aa-btn-outline">
                    <i class="fas fa-clipboard-list"></i>
                    <span>Equivalency Lists</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Assigned Groups Alert -->
    @if(isset($assignedGroupCodes) && count($assignedGroupCodes) > 0)
    <div class="aa-alert aa-alert-info">
        <div class="aa-alert-icon">
            <i class="fas fa-layer-group"></i>
        </div>
        <div class="aa-alert-content">
            <div class="aa-alert-title">Assigned Program Groups</div>
            <p class="aa-alert-text">You are reviewing applications from the following groups:</p>
            <div class="aa-group-tags">
                @foreach($assignedGroupCodes as $groupCode)
                    <span class="aa-group-tag">{{ $groupCode }}</span>
                @endforeach
            </div>
        </div>
    </div>
    @elseif(isset($assignedGroupCodes) && count($assignedGroupCodes) === 0)
    <div class="aa-alert aa-alert-warning">
        <div class="aa-alert-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="aa-alert-content">
            <div class="aa-alert-title">No Groups Assigned</div>
            <p class="aa-alert-text">Contact HEA personnel to configure your group assignments for the current academic session.</p>
        </div>
    </div>
    @endif

    <!-- Statistics Grid -->
    <section class="aa-stats-section">
        <div class="aa-stats-grid">
            <div class="aa-stat-card aa-stat-pending">
                <div class="aa-stat-visual">
                    <div class="aa-stat-ring">
                        <svg viewBox="0 0 36 36">
                            <circle cx="18" cy="18" r="15.9" fill="none" stroke="currentColor" stroke-width="2" opacity="0.2"/>
                            <circle cx="18" cy="18" r="15.9" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-dasharray="{{ min($stats['pending_review'] * 10, 100) }}, 100"
                                    transform="rotate(-90 18 18)"/>
                        </svg>
                        <div class="aa-stat-icon">
                            <i class="fas fa-inbox"></i>
                        </div>
                    </div>
                </div>
                <div class="aa-stat-data">
                    <div class="aa-stat-number">{{ $stats['pending_review'] }}</div>
                    <div class="aa-stat-label">Pending Review</div>
                    <a href="#pending-section" class="aa-stat-link">
                        Review now <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="aa-stat-accent"></div>
            </div>

            <div class="aa-stat-card aa-stat-reviewed">
                <div class="aa-stat-visual">
                    <div class="aa-stat-ring">
                        <svg viewBox="0 0 36 36">
                            <circle cx="18" cy="18" r="15.9" fill="none" stroke="currentColor" stroke-width="2" opacity="0.2"/>
                            <circle cx="18" cy="18" r="15.9" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-dasharray="100, 100"
                                    transform="rotate(-90 18 18)"/>
                        </svg>
                        <div class="aa-stat-icon">
                            <i class="fas fa-check-double"></i>
                        </div>
                    </div>
                </div>
                <div class="aa-stat-data">
                    <div class="aa-stat-number">{{ $stats['total_reviewed'] }}</div>
                    <div class="aa-stat-label">Applications Reviewed</div>
                    <span class="aa-stat-badge">All Time</span>
                </div>
                <div class="aa-stat-accent"></div>
            </div>

            <div class="aa-stat-card aa-stat-exempted">
                <div class="aa-stat-visual">
                    <div class="aa-stat-ring">
                        <svg viewBox="0 0 36 36">
                            <circle cx="18" cy="18" r="15.9" fill="none" stroke="currentColor" stroke-width="2" opacity="0.2"/>
                            <circle cx="18" cy="18" r="15.9" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-dasharray="85, 100"
                                    transform="rotate(-90 18 18)"/>
                        </svg>
                        <div class="aa-stat-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                    </div>
                </div>
                <div class="aa-stat-data">
                    <div class="aa-stat-number">{{ $stats['exempted_courses'] }}</div>
                    <div class="aa-stat-label">Courses Exempted</div>
                    <span class="aa-stat-badge aa-stat-badge-success">OCR Approved</span>
                </div>
                <div class="aa-stat-accent"></div>
            </div>

            <div class="aa-stat-card aa-stat-ocr">
                <div class="aa-stat-visual">
                    <div class="aa-stat-ring">
                        <svg viewBox="0 0 36 36">
                            <circle cx="18" cy="18" r="15.9" fill="none" stroke="currentColor" stroke-width="2" opacity="0.2"/>
                            <circle cx="18" cy="18" r="15.9" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-dasharray="70, 100"
                                    transform="rotate(-90 18 18)"/>
                        </svg>
                        <div class="aa-stat-icon">
                            <i class="fas fa-robot"></i>
                        </div>
                    </div>
                </div>
                <div class="aa-stat-data">
                    <div class="aa-stat-number">{{ $stats['total_courses'] }}</div>
                    <div class="aa-stat-label">OCR Processed</div>
                    <span class="aa-stat-badge">Total Courses</span>
                </div>
                <div class="aa-stat-accent"></div>
            </div>
        </div>
    </section>

    <!-- Pending Applications Section -->
    <section id="pending-section" class="aa-applications-section">
        <div class="aa-section-header">
            <div class="aa-section-title-group">
                <div class="aa-section-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <h2 class="aa-section-title">Pending Applications</h2>
                    <p class="aa-section-subtitle">Applications awaiting your review and decision</p>
                </div>
            </div>
            @if(!$applications->isEmpty())
            <div class="aa-section-meta">
                <span class="aa-count-badge">
                    <span class="aa-count-number">{{ $applications->count() }}</span>
                    <span class="aa-count-label">{{ Str::plural('application', $applications->count()) }}</span>
                </span>
            </div>
            @endif
        </div>

        @if($applications->isEmpty())
        <div class="aa-empty-state">
            <div class="aa-empty-visual">
                <div class="aa-empty-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="aa-empty-rings">
                    <div class="ring ring-1"></div>
                    <div class="ring ring-2"></div>
                    <div class="ring ring-3"></div>
                </div>
            </div>
            <h3 class="aa-empty-title">All Caught Up!</h3>
            <p class="aa-empty-text">There are no applications pending your review at this time.</p>
            <a href="{{ route('academic_advisor.my_students') }}" class="aa-btn aa-btn-primary">
                <i class="fas fa-users"></i>
                <span>View All Students</span>
            </a>
        </div>
        @else
        <div class="aa-table-container">
            <table class="aa-table">
                <thead>
                    <tr>
                        <th class="col-num">#</th>
                        <th class="col-student">Student</th>
                        <th class="col-date">Submitted</th>
                        <th class="col-program">Program</th>
                        <th class="col-status">Status</th>
                        <th class="col-action">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($applications as $app)
                    <tr class="aa-table-row">
                        <td class="col-num">
                            <span class="aa-row-number">{{ $loop->iteration }}</span>
                        </td>
                        <td class="col-student">
                            <div class="aa-student-cell">
                                <div class="aa-student-avatar">
                                    {{ strtoupper(substr($app->student_name ?? $app->name, 0, 1)) }}
                                </div>
                                <div class="aa-student-info">
                                    <div class="aa-student-name">{{ $app->student_name ?? $app->name }}</div>
                                    <div class="aa-student-matric">{{ $app->matric_no }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="col-date">
                            <div class="aa-date-cell">
                                <div class="aa-date-primary">{{ $app->created_at->format('d M Y') }}</div>
                                <div class="aa-date-secondary">{{ $app->created_at->format('h:i A') }}</div>
                            </div>
                        </td>
                        <td class="col-program">
                            <span class="aa-program-badge">{{ $app->current_program_code ?? 'N/A' }}</span>
                        </td>
                        <td class="col-status">
                            <div class="aa-status-cell">
                                <span class="aa-status-badge aa-status-pending">
                                    <i class="fas fa-clock"></i>
                                    {{ $app->status }}
                                </span>
                                @if($app->applicationSubjects->isNotEmpty())
                                <span class="aa-course-count">
                                    {{ $app->applicationSubjects->count() }} {{ Str::plural('course', $app->applicationSubjects->count()) }}
                                </span>
                                @endif
                            </div>
                        </td>
                        <td class="col-action">
                            <a href="{{ route('academic_advisor.application.show', $app) }}" class="aa-btn aa-btn-review">
                                <i class="fas fa-eye"></i>
                                <span>Review</span>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </section>
</div>

<style>
/* ============================================
   ACADEMIC ADVISOR DASHBOARD - INDUSTRIAL UI
   Design: Bold geometric, institutional feel
   Typography: IBM Plex Sans/Mono
   Colors: UiTM institutional palette
   ============================================ */

.aa-dashboard {
    position: relative;
    padding: 2rem;
    min-height: 100vh;
}

/* Background Pattern */
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

/* ============================================
   HEADER STYLES
   ============================================ */
.aa-header {
    background: linear-gradient(135deg, #1e3a8a 0%, #1e293b 100%);
    border-radius: 16px;
    padding: 2rem 2.5rem;
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
    box-shadow:
        0 4px 6px -1px rgba(0, 0, 0, 0.1),
        0 20px 40px -10px rgba(30, 58, 138, 0.3);
}

.aa-header::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 400px;
    height: 100%;
    background: linear-gradient(135deg, transparent 0%, rgba(255, 255, 255, 0.05) 100%);
    transform: skewX(-15deg);
    transform-origin: top right;
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
    width: 72px;
    height: 72px;
}

.badge-icon {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
    color: white;
    box-shadow: 0 8px 24px rgba(245, 158, 11, 0.4);
    position: relative;
    z-index: 2;
}

.badge-pulse {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 100%;
    height: 100%;
    background: #f59e0b;
    border-radius: 16px;
    animation: pulse 2s ease-out infinite;
    z-index: 1;
}

@keyframes pulse {
    0% { opacity: 0.6; transform: translate(-50%, -50%) scale(1); }
    100% { opacity: 0; transform: translate(-50%, -50%) scale(1.5); }
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
    font-size: 2rem;
    font-weight: 700;
    color: white;
    margin: 0;
    line-height: 1.2;
}

.aa-header-subtitle {
    font-size: 0.95rem;
    color: rgba(255, 255, 255, 0.7);
    margin: 0.5rem 0 0;
}

.aa-header-actions {
    display: flex;
    gap: 0.75rem;
}

/* ============================================
   BUTTON STYLES
   ============================================ */
.aa-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
    border-radius: 8px;
    font-family: 'IBM Plex Sans', sans-serif;
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

.aa-btn-review {
    background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(30, 58, 138, 0.2);
}

.aa-btn-review:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
    color: white;
}

/* ============================================
   ALERT STYLES
   ============================================ */
.aa-alert {
    display: flex;
    gap: 1rem;
    padding: 1.25rem 1.5rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    border-left: 4px solid;
}

.aa-alert-info {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.08) 0%, rgba(30, 58, 138, 0.08) 100%);
    border-left-color: #3b82f6;
}

.aa-alert-warning {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.08) 0%, rgba(217, 119, 6, 0.08) 100%);
    border-left-color: #f59e0b;
}

.aa-alert-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
}

.aa-alert-info .aa-alert-icon {
    background: linear-gradient(135deg, #3b82f6 0%, #1e3a8a 100%);
    color: white;
}

.aa-alert-warning .aa-alert-icon {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
}

.aa-alert-title {
    font-weight: 700;
    font-size: 0.95rem;
    color: #1e293b;
    margin-bottom: 0.25rem;
}

.aa-alert-text {
    font-size: 0.875rem;
    color: #64748b;
    margin: 0 0 0.75rem;
}

.aa-group-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.aa-group-tag {
    display: inline-block;
    padding: 0.35rem 0.75rem;
    background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
    color: white;
    border-radius: 6px;
    font-family: 'IBM Plex Mono', monospace;
    font-size: 0.75rem;
    font-weight: 600;
}

/* ============================================
   STATISTICS SECTION
   ============================================ */
.aa-stats-section {
    margin-bottom: 2rem;
}

.aa-stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.5rem;
}

.aa-stat-card {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1.25rem;
    position: relative;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 4px 12px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.aa-stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05), 0 12px 24px rgba(0, 0, 0, 0.1);
}

.aa-stat-visual {
    flex-shrink: 0;
}

.aa-stat-ring {
    position: relative;
    width: 64px;
    height: 64px;
}

.aa-stat-ring svg {
    width: 100%;
    height: 100%;
    transform: rotate(0deg);
}

.aa-stat-ring circle:last-child {
    stroke-linecap: round;
    transition: stroke-dasharray 1s ease;
}

.aa-stat-icon {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    color: white;
}

.aa-stat-pending { --stat-color: #f59e0b; }
.aa-stat-reviewed { --stat-color: #10b981; }
.aa-stat-exempted { --stat-color: #3b82f6; }
.aa-stat-ocr { --stat-color: #7c3aed; }

.aa-stat-card .aa-stat-ring svg { color: var(--stat-color); }
.aa-stat-card .aa-stat-icon { background: linear-gradient(135deg, var(--stat-color) 0%, color-mix(in srgb, var(--stat-color) 70%, black) 100%); }

.aa-stat-accent {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--stat-color), transparent);
}

.aa-stat-number {
    font-family: 'IBM Plex Mono', monospace;
    font-size: 1.75rem;
    font-weight: 700;
    color: #1e293b;
    line-height: 1;
}

.aa-stat-label {
    font-size: 0.875rem;
    color: #64748b;
    font-weight: 500;
    margin: 0.25rem 0 0.5rem;
}

.aa-stat-link {
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--stat-color);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    transition: gap 0.2s ease;
}

.aa-stat-link:hover {
    gap: 0.6rem;
    color: var(--stat-color);
}

.aa-stat-badge {
    display: inline-block;
    padding: 0.25rem 0.6rem;
    background: #f1f5f9;
    border-radius: 4px;
    font-size: 0.7rem;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.aa-stat-badge-success {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(5, 150, 105, 0.1) 100%);
    color: #059669;
}

/* ============================================
   APPLICATIONS SECTION
   ============================================ */
.aa-applications-section {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 4px 12px rgba(0, 0, 0, 0.05);
}

.aa-section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem 2rem;
    border-bottom: 1px solid #e2e8f0;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
}

.aa-section-title-group {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.aa-section-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: white;
    box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
}

.aa-section-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
}

.aa-section-subtitle {
    font-size: 0.875rem;
    color: #64748b;
    margin: 0.25rem 0 0;
}

.aa-count-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
    border-radius: 50px;
    color: white;
}

.aa-count-number {
    font-family: 'IBM Plex Mono', monospace;
    font-weight: 700;
    font-size: 1rem;
}

.aa-count-label {
    font-size: 0.8rem;
    font-weight: 500;
}

/* ============================================
   EMPTY STATE
   ============================================ */
.aa-empty-state {
    text-align: center;
    padding: 4rem 2rem;
}

.aa-empty-visual {
    position: relative;
    width: 120px;
    height: 120px;
    margin: 0 auto 1.5rem;
}

.aa-empty-icon {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: white;
    z-index: 2;
    box-shadow: 0 8px 24px rgba(16, 185, 129, 0.3);
}

.aa-empty-rings .ring {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    border: 2px solid #10b981;
    border-radius: 50%;
    opacity: 0;
    animation: ringPulse 3s ease-out infinite;
}

.ring-1 { width: 90px; height: 90px; animation-delay: 0s; }
.ring-2 { width: 105px; height: 105px; animation-delay: 1s; }
.ring-3 { width: 120px; height: 120px; animation-delay: 2s; }

@keyframes ringPulse {
    0% { opacity: 0.6; transform: translate(-50%, -50%) scale(0.8); }
    100% { opacity: 0; transform: translate(-50%, -50%) scale(1.2); }
}

.aa-empty-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.aa-empty-text {
    font-size: 1rem;
    color: #64748b;
    margin-bottom: 1.5rem;
}

/* ============================================
   TABLE STYLES
   ============================================ */
.aa-table-container {
    overflow-x: auto;
}

.aa-table {
    width: 100%;
    border-collapse: collapse;
}

.aa-table thead {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
}

.aa-table th {
    padding: 1rem 1.25rem;
    font-family: 'IBM Plex Mono', monospace;
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #64748b;
    text-align: left;
    border-bottom: 2px solid #e2e8f0;
    white-space: nowrap;
}

.aa-table th.col-num { width: 60px; text-align: center; }
.aa-table th.col-student { min-width: 250px; }
.aa-table th.col-date { width: 140px; }
.aa-table th.col-program { width: 120px; }
.aa-table th.col-status { width: 180px; }
.aa-table th.col-action { width: 120px; text-align: center; }

.aa-table-row {
    border-bottom: 1px solid #f1f5f9;
    transition: background 0.2s ease;
}

.aa-table-row:hover {
    background: #fafbfc;
}

.aa-table td {
    padding: 1.25rem;
    vertical-align: middle;
}

.aa-row-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    background: #f1f5f9;
    border-radius: 8px;
    font-family: 'IBM Plex Mono', monospace;
    font-size: 0.8rem;
    font-weight: 600;
    color: #64748b;
}

/* Student Cell */
.aa-student-cell {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.aa-student-avatar {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    font-weight: 700;
    color: white;
    flex-shrink: 0;
    box-shadow: 0 4px 8px rgba(30, 58, 138, 0.2);
}

.aa-student-name {
    font-weight: 600;
    color: #1e293b;
    font-size: 0.95rem;
}

.aa-student-matric {
    font-family: 'IBM Plex Mono', monospace;
    font-size: 0.8rem;
    color: #64748b;
    margin-top: 0.2rem;
}

/* Date Cell */
.aa-date-cell {
    line-height: 1.4;
}

.aa-date-primary {
    font-weight: 600;
    color: #1e293b;
    font-size: 0.9rem;
}

.aa-date-secondary {
    font-size: 0.75rem;
    color: #94a3b8;
}

/* Program Badge */
.aa-program-badge {
    display: inline-block;
    padding: 0.4rem 0.75rem;
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    color: #1e3a8a;
    border-radius: 6px;
    font-family: 'IBM Plex Mono', monospace;
    font-size: 0.75rem;
    font-weight: 700;
}

/* Status Cell */
.aa-status-cell {
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
}

.aa-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.4rem 0.75rem;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
    width: fit-content;
}

.aa-status-pending {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    color: #92400e;
}

.aa-course-count {
    font-size: 0.75rem;
    color: #94a3b8;
}

.col-action {
    text-align: center;
}

/* ============================================
   RESPONSIVE
   ============================================ */
@media (max-width: 1200px) {
    .aa-stats-grid {
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
}

@media (max-width: 768px) {
    .aa-dashboard {
        padding: 1rem;
    }

    .aa-header {
        padding: 1.5rem;
    }

    .aa-header-title {
        font-size: 1.5rem;
    }

    .aa-stats-grid {
        grid-template-columns: 1fr;
    }

    .aa-section-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }

    .aa-section-title-group {
        flex-direction: column;
    }
}
</style>
@endsection
