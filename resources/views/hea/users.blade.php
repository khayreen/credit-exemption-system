@extends('layouts.app')

@section('content')
<div class="users-management">
    {{-- Page Header --}}
    <header class="page-header">
        <div class="header-content">
            <div class="header-info">
                <div class="header-icon">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div>
                    <h1>User Management</h1>
                    <p>View and manage all system users</p>
                </div>
            </div>
            <a href="{{ route('hea.dashboard') }}" class="back-btn">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Dashboard
            </a>
        </div>
    </header>

    {{-- Main Card --}}
    <section class="users-card">
        <div class="card-header">
            <div class="header-title">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
                <h2>All Registered Users</h2>
            </div>
            <div class="header-filters">
                <form method="GET" action="{{ route('hea.users.index') }}" class="filter-form" id="userFilterForm">
                    <div class="filter-group">
                        <label class="filter-label">Filter by Role:</label>
                        <select name="role_filter" class="filter-select" onchange="this.form.submit()">
                            <option value="all" {{ ($roleFilter ?? 'all') == 'all' ? 'selected' : '' }}>All Roles</option>
                            <option value="hea_personnel" {{ ($roleFilter ?? '') == 'hea_personnel' ? 'selected' : '' }}>HEA Personnel</option>
                            <option value="program_coordinator" {{ ($roleFilter ?? '') == 'program_coordinator' ? 'selected' : '' }}>Program Coordinator</option>
                            <option value="resource_person" {{ ($roleFilter ?? '') == 'resource_person' ? 'selected' : '' }}>Resource Person</option>
                            <option value="academic_advisor" {{ ($roleFilter ?? '') == 'academic_advisor' ? 'selected' : '' }}>Academic Advisor</option>
                            <option value="external_lecturer" {{ ($roleFilter ?? '') == 'external_lecturer' ? 'selected' : '' }}>External Lecturer</option>
                            <option value="student" {{ ($roleFilter ?? '') == 'student' ? 'selected' : '' }}>Student</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">Sort by:</label>
                        <select name="sort_order" class="filter-select" onchange="this.form.submit()">
                            <option value="latest" {{ ($sortOrder ?? 'latest') == 'latest' ? 'selected' : '' }}>Latest to Oldest</option>
                            <option value="oldest" {{ ($sortOrder ?? '') == 'oldest' ? 'selected' : '' }}>Oldest to Latest</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <div class="card-content">
            @if($users->isEmpty())
                <div class="empty-state">
                    <div class="empty-icon">
                        <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <h3>No Users Found</h3>
                    @if(($roleFilter ?? 'all') !== 'all')
                        <p>No users found with the role: <strong>{{ ucfirst(str_replace('_', ' ', $roleFilter)) }}</strong></p>
                        <a href="{{ route('hea.users.index') }}" class="clear-filter-btn">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Clear Filters
                        </a>
                    @else
                        <p>There are no registered users in the system.</p>
                    @endif
                </div>
            @else
                <div class="table-wrapper">
                    <table class="users-table">
                        <thead>
                            <tr>
                                <th class="col-name">Name</th>
                                <th class="col-email">Email</th>
                                <th class="col-role">Role</th>
                                <th class="col-assignments">Department & Program Assignments</th>
                                <th class="col-date">Registered On</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            @php
                                $displayRole = $user->current_role ?? $user->requested_role;
                            @endphp
                            <tr class="user-row">
                                <td class="col-name">
                                    <div class="user-info">
                                        <div class="user-avatar">
                                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                        <span class="user-name">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="col-email">
                                    <span class="email-text">{{ $user->email }}</span>
                                </td>
                                <td class="col-role">
                                    @php
                                        $roleConfig = match($displayRole) {
                                            'hea_personnel' => ['class' => 'hea', 'label' => 'HEA Personnel'],
                                            'program_coordinator' => ['class' => 'pc', 'label' => 'Program Coordinator'],
                                            'resource_person' => ['class' => 'rp', 'label' => 'Resource Person'],
                                            'academic_advisor' => ['class' => 'aa', 'label' => 'Academic Advisor'],
                                            'external_lecturer' => ['class' => 'el', 'label' => 'External Lecturer'],
                                            'student' => ['class' => 'student', 'label' => 'Student'],
                                            default => ['class' => 'default', 'label' => ucfirst(str_replace('_', ' ', $displayRole ?? 'Unknown'))]
                                        };
                                    @endphp
                                    <span class="role-badge {{ $roleConfig['class'] }}">{{ $roleConfig['label'] }}</span>
                                </td>
                                <td class="col-assignments">
                                    @if($user->current_role === 'academic_advisor' && $user->academicAdvisor)
                                        @if($user->academicAdvisor->assigned_programs && count($user->academicAdvisor->assigned_programs) > 0)
                                            <div class="program-tags">
                                                @foreach($user->academicAdvisor->assigned_programs as $program)
                                                    <span class="program-tag aa">{{ $program }}</span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="no-assignment">No programs assigned</span>
                                        @endif
                                    @elseif($user->current_role === 'program_coordinator' && $user->programCoordinator)
                                        @if($user->programCoordinator->program_codes && count($user->programCoordinator->program_codes) > 0)
                                            <div class="program-tags">
                                                @foreach($user->programCoordinator->program_codes as $code)
                                                    <span class="program-tag pc">{{ $code }}</span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="no-assignment">No programs assigned</span>
                                        @endif
                                    @elseif($user->current_role === 'resource_person' && $user->resourcePerson)
                                        @if($user->resourcePerson->assigned_programs && count($user->resourcePerson->assigned_programs) > 0)
                                            <div class="program-tags">
                                                @foreach($user->resourcePerson->assigned_programs as $program)
                                                    <span class="program-tag rp">{{ $program }}</span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="no-assignment">No programs assigned</span>
                                        @endif
                                    @elseif($user->current_role === 'external_lecturer')
                                        <div class="external-info">
                                            @if($user->externalLecturer)
                                                <div class="info-row">
                                                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                    </svg>
                                                    <span>{{ $user->externalLecturer->institution_name ?? 'N/A' }}</span>
                                                </div>
                                            @endif
                                            @if(isset($user->external_submissions) && $user->external_submissions->count() > 0)
                                                <div class="submissions-info">
                                                    <span class="submissions-label">Syllabus Submitted:</span>
                                                    <div class="program-tags">
                                                        @foreach($user->external_submissions as $submission)
                                                            <span class="program-tag el" title="{{ $submission->course_name }}">{{ $submission->course_code }}</span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @elseif(!$user->externalLecturer)
                                                <span class="no-assignment">No info available</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="no-assignment">&mdash;</span>
                                    @endif
                                </td>
                                <td class="col-date">
                                    <span class="date-text">{{ $user->created_at->format('d M Y') }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Footer Stats --}}
                <div class="card-footer">
                    <div class="footer-stats">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        @if(($roleFilter ?? 'all') !== 'all' || ($sortOrder ?? 'latest') !== 'latest')
                            <span>
                                Showing <strong>{{ $users->count() }}</strong> user(s)
                                @if(($roleFilter ?? 'all') !== 'all')
                                    <span class="filter-badge">
                                        <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                        </svg>
                                        {{ ucfirst(str_replace('_', ' ', $roleFilter)) }}
                                    </span>
                                @endif
                                @if(($sortOrder ?? 'latest') !== 'latest')
                                    <span class="sort-badge">
                                        <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/>
                                        </svg>
                                        {{ $sortOrder === 'oldest' ? 'Oldest First' : 'Latest First' }}
                                    </span>
                                @endif
                            </span>
                        @else
                            <span>Total Users: <strong>{{ $users->count() }}</strong></span>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </section>
</div>

<style>
/* ============================================
   INDUSTRIAL UI DESIGN SYSTEM
   HEA Users Management
   ============================================ */

.users-management {
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
    --purple: #7c3aed;

    font-family: 'IBM Plex Sans', -apple-system, BlinkMacSystemFont, sans-serif;
    padding: 0 1.5rem 2rem;
    background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
    min-height: 100vh;
}

h1, h2, h3 {
    font-family: 'IBM Plex Sans', sans-serif;
    font-weight: 600;
    color: var(--industrial-dark);
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
        linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px),
        linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px);
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
    width: 100%;
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
    font-size: 1.5rem;
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
   USERS CARD
   ============================================ */
.users-card {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.5rem;
    background: linear-gradient(135deg, var(--uitm-blue) 0%, #2563eb 100%);
    flex-wrap: wrap;
    gap: 1rem;
}

.header-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: white;
}

.header-title svg {
    opacity: 0.8;
}

.header-title h2 {
    color: white;
    font-size: 1rem;
    margin: 0;
}

.header-filters {
    display: flex;
    align-items: center;
}

.filter-form {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.filter-group {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.filter-label {
    color: rgba(255,255,255,0.8);
    font-size: 0.8rem;
    white-space: nowrap;
}

.filter-select {
    background: rgba(255,255,255,0.15);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 6px;
    color: white;
    font-size: 0.8rem;
    padding: 0.5rem 0.75rem;
    min-width: 160px;
    cursor: pointer;
    transition: all 0.2s;
}

.filter-select:hover {
    background: rgba(255,255,255,0.25);
}

.filter-select:focus {
    outline: none;
    border-color: rgba(255,255,255,0.5);
}

.filter-select option {
    background: var(--industrial-dark);
    color: white;
}

.card-content {
    padding: 0;
}

/* ============================================
   TABLE
   ============================================ */
.table-wrapper {
    overflow-x: auto;
}

.users-table {
    width: 100%;
    border-collapse: collapse;
}

.users-table th {
    background: #f8fafc;
    padding: 0.875rem 1rem;
    text-align: left;
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #64748b;
    border-bottom: 1px solid #e2e8f0;
    white-space: nowrap;
}

.users-table td {
    padding: 1rem;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
}

.user-row {
    transition: background 0.2s;
}

.user-row:hover {
    background: #f8fafc;
}

.user-row:last-child td {
    border-bottom: none;
}

/* Column Widths */
.col-name { width: 20%; }
.col-email { width: 22%; }
.col-role { width: 15%; }
.col-assignments { width: 28%; }
.col-date { width: 15%; }

/* User Info */
.user-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.user-avatar {
    width: 36px;
    height: 36px;
    background: var(--industrial-light);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #94a3b8;
    flex-shrink: 0;
}

.user-name {
    font-weight: 600;
    color: var(--industrial-dark);
    font-size: 0.9rem;
}

.email-text {
    font-size: 0.875rem;
    color: #64748b;
}

/* Role Badges */
.role-badge {
    display: inline-flex;
    align-items: center;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.35rem 0.625rem;
    border-radius: 6px;
    white-space: nowrap;
}

.role-badge.hea {
    background: rgba(220, 38, 38, 0.1);
    color: var(--danger);
}

.role-badge.pc {
    background: rgba(13, 148, 136, 0.1);
    color: var(--teal);
}

.role-badge.rp {
    background: rgba(100, 116, 139, 0.1);
    color: var(--industrial-gray);
}

.role-badge.aa {
    background: rgba(245, 158, 11, 0.1);
    color: #b45309;
}

.role-badge.el {
    background: rgba(124, 58, 237, 0.1);
    color: var(--purple);
}

.role-badge.student {
    background: rgba(5, 150, 105, 0.1);
    color: var(--success);
}

.role-badge.default {
    background: var(--industrial-light);
    color: var(--industrial-gray);
}

/* Program Tags */
.program-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 0.35rem;
}

.program-tag {
    font-family: 'IBM Plex Mono', monospace;
    font-size: 0.7rem;
    font-weight: 500;
    padding: 0.2rem 0.5rem;
    border-radius: 4px;
}

.program-tag.aa {
    background: rgba(245, 158, 11, 0.1);
    color: #b45309;
}

.program-tag.pc {
    background: rgba(13, 148, 136, 0.1);
    color: var(--teal);
}

.program-tag.rp {
    background: rgba(100, 116, 139, 0.1);
    color: var(--industrial-gray);
}

.program-tag.el {
    background: rgba(124, 58, 237, 0.1);
    color: var(--purple);
}

.no-assignment {
    font-size: 0.8rem;
    color: #94a3b8;
    font-style: italic;
}

/* External Lecturer Info */
.external-info {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.info-row {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.8rem;
    color: #64748b;
}

.info-row svg {
    color: var(--purple);
    flex-shrink: 0;
}

.submissions-info {
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
}

.submissions-label {
    font-size: 0.75rem;
    color: #94a3b8;
}

/* Date */
.date-text {
    font-family: 'IBM Plex Mono', monospace;
    font-size: 0.8rem;
    color: #64748b;
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

.empty-state h3 {
    font-size: 1.125rem;
    margin: 0 0 0.5rem;
}

.empty-state p {
    font-size: 0.875rem;
    color: #64748b;
    margin: 0 0 1rem;
}

.clear-filter-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    background: var(--uitm-blue);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    text-decoration: none;
    font-size: 0.8rem;
    font-weight: 500;
    transition: all 0.2s;
}

.clear-filter-btn:hover {
    background: #1e40af;
    color: white;
}

/* ============================================
   FOOTER
   ============================================ */
.card-footer {
    padding: 1rem 1.5rem;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
}

.footer-stats {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.8rem;
    color: #64748b;
    flex-wrap: wrap;
}

.footer-stats svg {
    color: #94a3b8;
}

.footer-stats strong {
    color: var(--industrial-dark);
}

.filter-badge,
.sort-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.7rem;
    font-weight: 500;
    padding: 0.2rem 0.5rem;
    border-radius: 4px;
    margin-left: 0.5rem;
}

.filter-badge {
    background: rgba(30, 58, 138, 0.1);
    color: var(--uitm-blue);
}

.sort-badge {
    background: rgba(13, 148, 136, 0.1);
    color: var(--teal);
}

/* ============================================
   RESPONSIVE
   ============================================ */
@media (max-width: 991px) {
    .card-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .header-filters {
        width: 100%;
    }

    .filter-form {
        width: 100%;
    }

    .filter-group {
        flex: 1;
        min-width: 200px;
    }

    .filter-select {
        width: 100%;
    }
}

@media (max-width: 767px) {
    .users-management {
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
        font-size: 1.25rem;
    }

    .users-table th,
    .users-table td {
        padding: 0.75rem;
    }

    .col-assignments,
    .col-date {
        display: none;
    }
}
</style>
@endsection
