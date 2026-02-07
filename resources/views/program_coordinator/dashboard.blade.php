@extends('layouts.app')

@section('content')
<div class="pc-dashboard">
    {{-- Industrial Header with Geometric Pattern --}}
    <header class="dashboard-header">
        <div class="header-pattern"></div>
        <div class="header-content">
            <div class="header-left">
                <div class="role-indicator">
                    <span class="role-tag">PC</span>
                    <div class="role-line"></div>
                </div>
                <div class="welcome-text">
                    <h1>Welcome back, {{ explode(' ', Auth::user()->name)[0] }}</h1>
                    <p class="program-info">
                        <span class="label">Program Coordinator</span>
                        <span class="divider">&mdash;</span>
                        <span class="programs">{{ implode(' · ', $coordinator->program_codes) }}</span>
                    </p>
                </div>
            </div>
            <div class="header-right">
                <div class="date-display">
                    <span class="day">{{ now()->format('d') }}</span>
                    <div class="date-details">
                        <span class="month">{{ now()->format('F Y') }}</span>
                        <span class="weekday">{{ now()->format('l') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="industrial-alert success">
            <div class="alert-icon">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <span class="alert-message">{{ session('success') }}</span>
            <button type="button" class="alert-close" data-bs-dismiss="alert">&times;</button>
        </div>
    @endif

    @if($errors->any())
        <div class="industrial-alert error">
            <div class="alert-icon">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <span class="alert-message">
                @foreach($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </span>
            <button type="button" class="alert-close" data-bs-dismiss="alert">&times;</button>
        </div>
    @endif

    {{-- Quick Actions Command Bar --}}
    <section class="command-bar">
        <a href="{{ route('program_coordinator.equivalency_requests.index') }}" class="command-action primary">
            <div class="action-content">
                <div class="action-icon">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                </div>
                <div class="action-text">
                    <span class="action-title">Review Requests</span>
                    <span class="action-subtitle">{{ $stats['total_requests'] }} pending review</span>
                </div>
            </div>
            @if($stats['total_requests'] > 0)
                <span class="action-badge">{{ $stats['total_requests'] }}</span>
            @endif
            <span class="action-arrow">&rarr;</span>
        </a>

        <a href="{{ route('program_coordinator.equivalency_lists.index') }}" class="command-action secondary">
            <div class="action-content">
                <div class="action-icon">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                </div>
                <div class="action-text">
                    <span class="action-title">Published Lists</span>
                    <span class="action-subtitle">{{ $stats['total_lists'] }} equivalency lists</span>
                </div>
            </div>
            <span class="action-arrow">&rarr;</span>
        </a>

        <a href="{{ route('program_coordinator.course_equivalencies.view') }}" class="command-action tertiary">
            <div class="action-content">
                <div class="action-icon">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <div class="action-text">
                    <span class="action-title">Course Mappings</span>
                    <span class="action-subtitle">Search & browse all</span>
                </div>
            </div>
            <span class="action-arrow">&rarr;</span>
        </a>
    </section>

    {{-- Statistics Grid --}}
    <section class="stats-grid">
        <div class="stat-card requests">
            <div class="stat-header">
                <span class="stat-label">Pending Requests</span>
                <div class="stat-indicator {{ $stats['total_requests'] > 0 ? 'active' : '' }}"></div>
            </div>
            <div class="stat-value">{{ $stats['total_requests'] }}</div>
            <div class="stat-footer">
                @if($stats['total_requests'] > 0)
                    <a href="{{ route('program_coordinator.equivalency_requests.index') }}" class="stat-action">
                        Review Now <span>&rarr;</span>
                    </a>
                @else
                    <span class="stat-status success">
                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        All Clear
                    </span>
                @endif
            </div>
        </div>

        <div class="stat-card lists">
            <div class="stat-header">
                <span class="stat-label">Equivalency Lists</span>
                <div class="stat-icon-mini">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
            </div>
            <div class="stat-value">{{ $stats['total_lists'] }}</div>
            <div class="stat-footer">
                <span class="stat-meta">All programs combined</span>
            </div>
        </div>

        <div class="stat-card mappings">
            <div class="stat-header">
                <span class="stat-label">Course Mappings</span>
                <div class="stat-icon-mini">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                </div>
            </div>
            <div class="stat-value">{{ $stats['total_mappings'] }}</div>
            <div class="stat-footer">
                <span class="stat-meta">Published equivalencies</span>
            </div>
        </div>
    </section>

    {{-- Programs Overview --}}
    <section class="programs-section">
        <div class="section-header">
            <h2>
                <span class="section-icon">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M12 14l9-5-9-5-9 5 9 5z"/>
                        <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/>
                    </svg>
                </span>
                Programs Overview
            </h2>
            <span class="program-count">{{ count($programBreakdown) }} Program{{ count($programBreakdown) > 1 ? 's' : '' }}</span>
        </div>

        <div class="programs-grid">
            @foreach($programBreakdown as $index => $program)
                <article class="program-card" style="--delay: {{ $index * 0.1 }}s">
                    <div class="program-header">
                        <div class="program-code-badge">
                            <span class="code-prefix">{{ substr($program['code'], 0, -3) }}</span>
                            <span class="code-number">{{ substr($program['code'], -3) }}</span>
                        </div>
                        <div class="program-info">
                            <h3 class="program-code">{{ $program['code'] }}</h3>
                            <p class="program-name">{{ $program['name'] }}</p>
                        </div>
                    </div>

                    <div class="program-stats">
                        <div class="program-stat">
                            <span class="stat-number">{{ $program['lists_count'] }}</span>
                            <span class="stat-name">Lists</span>
                        </div>
                        <div class="program-stat">
                            <span class="stat-number">{{ $program['mappings_count'] }}</span>
                            <span class="stat-name">Mappings</span>
                        </div>
                        <div class="program-stat {{ $program['requests_count'] > 0 ? 'highlight' : '' }}">
                            <span class="stat-number">{{ $program['requests_count'] }}</span>
                            <span class="stat-name">Requests</span>
                        </div>
                    </div>

                    <div class="program-actions">
                        <a href="{{ route('program_coordinator.equivalency_lists.index') }}" class="program-btn">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Lists
                        </a>
                        <a href="{{ route('program_coordinator.course_equivalencies.view') }}" class="program-btn">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Mappings
                        </a>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    {{-- Bottom Grid: Tips & Quick Links --}}
    <div class="bottom-grid">
        {{-- Quick Tips --}}
        <section class="tips-section">
            <div class="section-header compact">
                <h2>
                    <span class="section-icon amber">
                        <svg width="18" height="18" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1h4v1a2 2 0 11-4 0zM12 14c.015-.34.208-.646.477-.859a4 4 0 10-4.954 0c.27.213.462.519.476.859h4.002z"/>
                        </svg>
                    </span>
                    Quick Tips
                </h2>
            </div>
            <div class="tips-grid">
                <div class="tip-item">
                    <div class="tip-icon blue">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                    </div>
                    <div class="tip-content">
                        <h4>Review Requests</h4>
                        <p>Process student requests for new course equivalencies. Approve, reject, or forward to Resource Person.</p>
                    </div>
                </div>
                <div class="tip-item">
                    <div class="tip-icon teal">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                    </div>
                    <div class="tip-content">
                        <h4>Published Lists</h4>
                        <p>View and download official equivalency lists that have been endorsed by HEA.</p>
                    </div>
                </div>
                <div class="tip-item">
                    <div class="tip-icon green">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <div class="tip-content">
                        <h4>Course Mappings</h4>
                        <p>Search and browse all course equivalency mappings across all programs.</p>
                    </div>
                </div>
                <div class="tip-item">
                    <div class="tip-icon gray">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                        </svg>
                    </div>
                    <div class="tip-content">
                        <h4>Forward to RP</h4>
                        <p>Uncertain about a request? Forward it to a Resource Person for expert evaluation.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Quick Links --}}
        <aside class="quick-links-section">
            <div class="section-header compact">
                <h2>
                    <span class="section-icon amber">
                        <svg width="18" height="18" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                        </svg>
                    </span>
                    Quick Links
                </h2>
            </div>
            <nav class="quick-links-list">
                <a href="{{ route('program_coordinator.equivalency_requests.index') }}" class="quick-link">
                    <span class="link-icon blue">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                    </span>
                    <span class="link-text">Equivalency Requests</span>
                    @if($stats['total_requests'] > 0)
                        <span class="link-badge">{{ $stats['total_requests'] }}</span>
                    @endif
                </a>
                <a href="{{ route('program_coordinator.equivalency_lists.index') }}" class="quick-link">
                    <span class="link-icon teal">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                    </span>
                    <span class="link-text">Published Lists</span>
                </a>
                <a href="{{ route('program_coordinator.course_equivalencies.view') }}" class="quick-link">
                    <span class="link-icon green">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <span class="link-text">Search Mappings</span>
                </a>
                <a href="{{ route('profile.show') }}" class="quick-link">
                    <span class="link-icon gray">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </span>
                    <span class="link-text">My Profile</span>
                </a>
            </nav>
        </aside>
    </div>
</div>

<style>
/* ============================================
   INDUSTRIAL UI DESIGN SYSTEM
   Program Coordinator Dashboard
   ============================================ */

/* CSS Variables */
.pc-dashboard {
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

/* Typography */
h1, h2, h3, h4, h5, h6 {
    font-family: 'IBM Plex Sans', sans-serif;
    font-weight: 600;
    color: var(--industrial-dark);
}

.stat-value, .stat-number, .code-number {
    font-family: 'IBM Plex Mono', monospace;
}

/* ============================================
   HEADER SECTION
   ============================================ */
.dashboard-header {
    position: relative;
    background: linear-gradient(135deg, var(--uitm-blue) 0%, #1e40af 50%, var(--industrial-dark) 100%);
    margin: -1rem -1.5rem 1.5rem;
    padding: 2rem 2rem;
    overflow: hidden;
}

.header-pattern {
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px),
        linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px);
    background-size: 40px 40px;
}

.header-pattern::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(245, 158, 11, 0.15) 0%, transparent 70%);
    border-radius: 50%;
}

.header-content {
    position: relative;
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
}

.header-left {
    display: flex;
    align-items: center;
    gap: 1.25rem;
}

.role-indicator {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
}

.role-tag {
    background: var(--uitm-amber);
    color: var(--industrial-dark);
    font-family: 'IBM Plex Mono', monospace;
    font-size: 0.75rem;
    font-weight: 700;
    padding: 0.35rem 0.6rem;
    border-radius: 4px;
    letter-spacing: 0.05em;
}

.role-line {
    width: 2px;
    height: 24px;
    background: linear-gradient(to bottom, var(--uitm-amber), transparent);
}

.welcome-text h1 {
    color: white;
    font-size: 1.5rem;
    font-weight: 600;
    margin: 0 0 0.35rem;
}

.program-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: rgba(255,255,255,0.7);
    font-size: 0.875rem;
    margin: 0;
}

.program-info .label {
    color: rgba(255,255,255,0.6);
}

.program-info .divider {
    color: rgba(255,255,255,0.3);
}

.program-info .programs {
    font-family: 'IBM Plex Mono', monospace;
    color: var(--uitm-amber-light);
    font-weight: 500;
}

.header-right {
    display: none;
}

@media (min-width: 768px) {
    .header-right {
        display: block;
    }
}

.date-display {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(10px);
    padding: 0.75rem 1rem;
    border-radius: 8px;
    border: 1px solid rgba(255,255,255,0.1);
}

.date-display .day {
    font-family: 'IBM Plex Mono', monospace;
    font-size: 1.75rem;
    font-weight: 600;
    color: white;
    line-height: 1;
}

.date-details {
    display: flex;
    flex-direction: column;
}

.date-details .month {
    color: rgba(255,255,255,0.9);
    font-size: 0.8rem;
    font-weight: 500;
}

.date-details .weekday {
    color: rgba(255,255,255,0.5);
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

/* ============================================
   ALERTS
   ============================================ */
.industrial-alert {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.875rem 1rem;
    border-radius: 6px;
    margin-bottom: 1rem;
    border-left: 4px solid;
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
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

.alert-icon {
    flex-shrink: 0;
}

.alert-message {
    flex: 1;
    font-size: 0.875rem;
}

.alert-close {
    background: none;
    border: none;
    font-size: 1.25rem;
    cursor: pointer;
    opacity: 0.5;
    transition: opacity 0.2s;
}

.alert-close:hover {
    opacity: 1;
}

/* ============================================
   COMMAND BAR
   ============================================ */
.command-bar {
    display: grid;
    grid-template-columns: 1fr;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
}

@media (min-width: 768px) {
    .command-bar {
        grid-template-columns: repeat(3, 1fr);
    }
}

.command-action {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 1.25rem;
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    text-decoration: none;
    transition: all 0.2s ease;
    position: relative;
    overflow: hidden;
}

.command-action::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 3px;
    background: transparent;
    transition: background 0.2s;
}

.command-action.primary::before {
    background: var(--uitm-blue);
}

.command-action:hover {
    border-color: #cbd5e1;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    transform: translateY(-2px);
}

.command-action.primary:hover {
    border-color: var(--uitm-blue);
    background: linear-gradient(to right, rgba(30, 58, 138, 0.03), white);
}

.command-action.secondary:hover::before {
    background: var(--teal);
}

.command-action.tertiary:hover::before {
    background: var(--industrial-gray);
}

.action-content {
    display: flex;
    align-items: center;
    gap: 0.875rem;
    flex: 1;
}

.action-icon {
    width: 44px;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    flex-shrink: 0;
}

.command-action.primary .action-icon {
    background: linear-gradient(135deg, var(--uitm-blue), #2563eb);
    color: white;
}

.command-action.secondary .action-icon {
    background: rgba(13, 148, 136, 0.1);
    color: var(--teal);
}

.command-action.tertiary .action-icon {
    background: var(--industrial-light);
    color: var(--industrial-gray);
}

.action-text {
    display: flex;
    flex-direction: column;
}

.action-title {
    font-weight: 600;
    color: var(--industrial-dark);
    font-size: 0.9375rem;
}

.action-subtitle {
    font-size: 0.8rem;
    color: #64748b;
}

.action-badge {
    background: var(--danger);
    color: white;
    font-family: 'IBM Plex Mono', monospace;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    min-width: 24px;
    text-align: center;
}

.action-arrow {
    color: #94a3b8;
    font-size: 1.125rem;
    transition: transform 0.2s;
}

.command-action:hover .action-arrow {
    transform: translateX(3px);
    color: var(--uitm-blue);
}

/* ============================================
   STATISTICS GRID
   ============================================ */
.stats-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

@media (min-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(3, 1fr);
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
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
}

.stat-card.requests::before {
    background: linear-gradient(90deg, var(--uitm-blue), var(--uitm-blue-light));
}

.stat-card.lists::before {
    background: linear-gradient(90deg, var(--teal), #14b8a6);
}

.stat-card.mappings::before {
    background: linear-gradient(90deg, var(--success), #10b981);
}

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
    box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.2);
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.stat-icon-mini {
    color: #94a3b8;
}

.stat-value {
    font-size: 2.25rem;
    font-weight: 700;
    color: var(--industrial-dark);
    line-height: 1;
    margin-bottom: 0.75rem;
}

.stat-footer {
    display: flex;
    align-items: center;
}

.stat-action {
    font-size: 0.8125rem;
    font-weight: 600;
    color: var(--uitm-blue);
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.25rem;
    transition: gap 0.2s;
}

.stat-action:hover {
    gap: 0.5rem;
}

.stat-status {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.8125rem;
    font-weight: 500;
}

.stat-status.success {
    color: var(--success);
}

.stat-meta {
    font-size: 0.8125rem;
    color: #94a3b8;
}

/* ============================================
   PROGRAMS SECTION
   ============================================ */
.programs-section {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    margin-bottom: 1.5rem;
    overflow: hidden;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #e2e8f0;
    background: #fafbfc;
}

.section-header h2 {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1rem;
    margin: 0;
}

.section-icon {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(30, 58, 138, 0.1);
    color: var(--uitm-blue);
    border-radius: 8px;
}

.section-icon.amber {
    background: rgba(245, 158, 11, 0.1);
    color: var(--uitm-amber);
}

.program-count {
    font-family: 'IBM Plex Mono', monospace;
    font-size: 0.75rem;
    font-weight: 500;
    color: var(--uitm-blue);
    background: rgba(30, 58, 138, 0.1);
    padding: 0.25rem 0.625rem;
    border-radius: 4px;
}

.programs-grid {
    display: grid;
    grid-template-columns: 1fr;
}

@media (min-width: 768px) {
    .programs-grid {
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    }
}

.program-card {
    padding: 1.5rem;
    border-bottom: 1px solid #e2e8f0;
    transition: background 0.2s;
    animation: fadeInUp 0.4s ease forwards;
    animation-delay: var(--delay);
    opacity: 0;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.program-card:last-child {
    border-bottom: none;
}

@media (min-width: 768px) {
    .program-card {
        border-bottom: none;
        border-right: 1px solid #e2e8f0;
    }

    .program-card:last-child {
        border-right: none;
    }
}

.program-card:hover {
    background: #f8fafc;
}

.program-header {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    margin-bottom: 1.25rem;
}

.program-code-badge {
    width: 52px;
    height: 52px;
    background: linear-gradient(135deg, var(--uitm-blue), #1e40af);
    border-radius: 10px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(30, 58, 138, 0.25);
}

.code-prefix {
    font-size: 0.5rem;
    color: rgba(255,255,255,0.7);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.code-number {
    font-size: 1rem;
    font-weight: 700;
    color: white;
    line-height: 1;
}

.program-info {
    flex: 1;
    min-width: 0;
}

.program-code {
    font-size: 1rem;
    font-weight: 700;
    color: var(--uitm-blue);
    margin: 0 0 0.25rem;
}

.program-name {
    font-size: 0.8rem;
    color: #64748b;
    margin: 0;
    line-height: 1.4;
}

.program-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.5rem;
    margin-bottom: 1.25rem;
}

.program-stat {
    text-align: center;
    padding: 0.75rem 0.5rem;
    background: var(--industrial-light);
    border-radius: 8px;
    transition: all 0.2s;
}

.program-stat.highlight {
    background: rgba(234, 88, 12, 0.1);
}

.program-stat.highlight .stat-number {
    color: var(--warning);
}

.program-stat .stat-number {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--industrial-dark);
    display: block;
    line-height: 1;
    margin-bottom: 0.25rem;
}

.program-stat .stat-name {
    font-size: 0.7rem;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.03em;
}

.program-actions {
    display: flex;
    gap: 0.5rem;
}

.program-btn {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.375rem;
    padding: 0.5rem 0.75rem;
    font-size: 0.8125rem;
    font-weight: 500;
    color: var(--uitm-blue);
    background: rgba(30, 58, 138, 0.05);
    border: 1px solid rgba(30, 58, 138, 0.15);
    border-radius: 6px;
    text-decoration: none;
    transition: all 0.2s;
}

.program-btn:hover {
    background: var(--uitm-blue);
    color: white;
    border-color: var(--uitm-blue);
}

/* ============================================
   BOTTOM GRID
   ============================================ */
.bottom-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.5rem;
}

@media (min-width: 992px) {
    .bottom-grid {
        grid-template-columns: 2fr 1fr;
    }
}

.tips-section,
.quick-links-section {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
}

.section-header.compact {
    padding: 0.875rem 1.25rem;
}

.section-header.compact h2 {
    font-size: 0.9375rem;
}

.tips-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 0;
}

@media (min-width: 576px) {
    .tips-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

.tip-item {
    display: flex;
    gap: 0.875rem;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #f1f5f9;
    transition: background 0.2s;
}

.tip-item:hover {
    background: #f8fafc;
}

@media (min-width: 576px) {
    .tip-item:nth-child(odd) {
        border-right: 1px solid #f1f5f9;
    }

    .tip-item:nth-last-child(-n+2) {
        border-bottom: none;
    }
}

.tip-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    flex-shrink: 0;
}

.tip-icon.blue {
    background: rgba(30, 58, 138, 0.1);
    color: var(--uitm-blue);
}

.tip-icon.teal {
    background: rgba(13, 148, 136, 0.1);
    color: var(--teal);
}

.tip-icon.green {
    background: rgba(5, 150, 105, 0.1);
    color: var(--success);
}

.tip-icon.gray {
    background: var(--industrial-light);
    color: var(--industrial-gray);
}

.tip-content h4 {
    font-size: 0.875rem;
    font-weight: 600;
    margin: 0 0 0.25rem;
}

.tip-content p {
    font-size: 0.8rem;
    color: #64748b;
    margin: 0;
    line-height: 1.5;
}

/* Quick Links */
.quick-links-list {
    display: flex;
    flex-direction: column;
}

.quick-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.875rem 1.25rem;
    text-decoration: none;
    border-bottom: 1px solid #f1f5f9;
    transition: all 0.2s;
}

.quick-link:last-child {
    border-bottom: none;
}

.quick-link:hover {
    background: #f8fafc;
    padding-left: 1.5rem;
}

.link-icon {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    flex-shrink: 0;
}

.link-icon.blue {
    background: rgba(30, 58, 138, 0.1);
    color: var(--uitm-blue);
}

.link-icon.teal {
    background: rgba(13, 148, 136, 0.1);
    color: var(--teal);
}

.link-icon.green {
    background: rgba(5, 150, 105, 0.1);
    color: var(--success);
}

.link-icon.gray {
    background: var(--industrial-light);
    color: var(--industrial-gray);
}

.link-text {
    flex: 1;
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--industrial-dark);
}

.link-badge {
    background: var(--danger);
    color: white;
    font-family: 'IBM Plex Mono', monospace;
    font-size: 0.7rem;
    font-weight: 600;
    padding: 0.2rem 0.45rem;
    border-radius: 4px;
    min-width: 20px;
    text-align: center;
}

/* ============================================
   RESPONSIVE ADJUSTMENTS
   ============================================ */
@media (max-width: 767px) {
    .pc-dashboard {
        padding: 0 1rem 1.5rem;
    }

    .dashboard-header {
        margin: -1rem -1rem 1.25rem;
        padding: 1.5rem 1rem;
    }

    .welcome-text h1 {
        font-size: 1.25rem;
    }

    .stat-value {
        font-size: 1.75rem;
    }
}
</style>
@endsection
