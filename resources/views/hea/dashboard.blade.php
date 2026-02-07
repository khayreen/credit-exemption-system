@extends('layouts.app')

@section('content')
<div class="hea-dashboard">
    {{-- Industrial Header --}}
    <header class="dashboard-header">
        <div class="header-grid"></div>
        <div class="header-accent"></div>
        <div class="header-content">
            <div class="header-left">
                <div class="role-badge">
                    <span class="badge-text">HEA</span>
                    <span class="badge-line"></span>
                </div>
                <div class="welcome-info">
                    <h1>HEA Dashboard</h1>
                    <p>Welcome back, {{ auth()->user()->name }}</p>
                </div>
            </div>
            <div class="header-right">
                <div class="date-widget">
                    <span class="date-day">{{ now()->format('d') }}</span>
                    <div class="date-meta">
                        <span class="date-month">{{ now()->format('F Y') }}</span>
                        <span class="date-weekday">{{ now()->format('l') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- Key Metrics --}}
    <section class="metrics-grid">
        <a href="{{ route('hea.equivalency_lists.pending') }}" class="metric-card endorsements">
            <div class="metric-icon">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
            </div>
            <div class="metric-data">
                <span class="metric-value">{{ $stats['pending_endorsements'] }}</span>
                <span class="metric-label">Pending Endorsements</span>
            </div>
            <div class="metric-breakdown">
                <span class="breakdown-item primary">
                    <span class="breakdown-value">{{ $stats['pending_internal'] }}</span>
                    <span class="breakdown-label">CS110</span>
                </span>
                @if($stats['pending_external'] > 0)
                <span class="breakdown-item success">
                    <span class="breakdown-value">{{ $stats['pending_external'] }}</span>
                    <span class="breakdown-label">External</span>
                </span>
                @endif
            </div>
            <span class="metric-arrow">&rarr;</span>
        </a>

        <a href="{{ route('hea.equivalency_lists.published_view') }}" class="metric-card published">
            <div class="metric-icon">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="metric-data">
                <span class="metric-value">{{ $stats['published_lists'] }}</span>
                <span class="metric-label">Published Lists</span>
            </div>
            <span class="metric-subtitle">Equivalency lists endorsed</span>
            <span class="metric-arrow">&rarr;</span>
        </a>

        <a href="{{ route('hea.applications.index') }}" class="metric-card applications">
            <div class="metric-icon">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="metric-data">
                <span class="metric-value">{{ $stats['pending_applications'] }}</span>
                <span class="metric-label">Active Applications</span>
            </div>
            <span class="metric-subtitle">In progress or pending</span>
            <span class="metric-arrow">&rarr;</span>
        </a>

        <a href="{{ route('hea.users.index') }}" class="metric-card users">
            <div class="metric-icon">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
            <div class="metric-data">
                <span class="metric-value">{{ $stats['total_users'] }}</span>
                <span class="metric-label">Total Users</span>
            </div>
            @if($stats['pending_user_approvals'] > 0)
            <div class="metric-alert">
                <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                </svg>
                <span>{{ $stats['pending_user_approvals'] }} pending approval</span>
            </div>
            @else
            <span class="metric-subtitle">Registered in system</span>
            @endif
            <span class="metric-arrow">&rarr;</span>
        </a>
    </section>

    {{-- Notifications Section --}}
    @if(isset($unreadNotifications) && $unreadCount > 0)
    <section class="notifications-section">
        <div class="section-header">
            <div class="header-title">
                <div class="notification-bell">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span class="bell-badge">{{ $unreadCount }}</span>
                </div>
                <h2>Notifications</h2>
            </div>
            <form action="{{ route('hea.notifications.mark-all-read') }}" method="POST">
                @csrf
                <button type="submit" class="mark-all-btn">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Mark All Read
                </button>
            </form>
        </div>
        <div class="notifications-list">
            @foreach($unreadNotifications as $notification)
            @php
                $data = $notification->data;
                $timeAgo = $notification->created_at->diffForHumans();
            @endphp
            <div class="notification-item">
                <div class="notification-icon {{ ($data['type'] ?? '') === 'new_staff_registration' ? 'primary' : 'info' }}">
                    @if(($data['type'] ?? '') === 'new_staff_registration')
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    @else
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    @endif
                </div>
                <div class="notification-content">
                    <p class="notification-message">{{ $data['message'] ?? 'New notification' }}</p>
                    <div class="notification-meta">
                        @if(isset($data['role_display']))
                        <span class="meta-badge">{{ $data['role_display'] }}</span>
                        @endif
                        @if(isset($data['user_email']))
                        <span class="meta-email">
                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            {{ $data['user_email'] }}
                        </span>
                        @endif
                    </div>
                    @if(isset($data['program_info']) && $data['program_info'] !== 'Not specified')
                    <span class="notification-program">
                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M12 14l9-5-9-5-9 5 9 5z"/>
                            <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                        </svg>
                        {{ $data['program_info'] }}
                    </span>
                    @endif
                </div>
                <div class="notification-actions">
                    <span class="notification-time">{{ $timeAgo }}</span>
                    <div class="action-buttons">
                        <form action="{{ route('hea.notifications.read', $notification->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="action-btn mark-read" title="Mark as read">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </button>
                        </form>
                        @if(isset($data['action_url']))
                        <a href="{{ $data['action_url'] }}" class="action-btn view" title="View">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @if($unreadCount > 10)
        <div class="notifications-footer">
            Showing 10 of {{ $unreadCount }} unread notifications
        </div>
        @endif
    </section>
    @endif

    {{-- Main Content Grid --}}
    <div class="content-grid">
        {{-- Pending Endorsements --}}
        <section class="content-card endorsements-card">
            @if(isset($pendingLists) && $pendingLists->count() > 0)
            <div class="card-header">
                <div class="header-info">
                    <div class="header-icon blue">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <h3>Pending Endorsements</h3>
                </div>
                <a href="{{ route('hea.equivalency_lists.pending') }}" class="view-all-btn">
                    View All ({{ $stats['pending_endorsements'] }})
                </a>
            </div>
            <div class="card-content">
                @foreach($pendingLists as $list)
                @php
                    $daysWaiting = $list->submitted_at ? now()->diffInDays($list->submitted_at) : 0;
                @endphp
                <div class="list-item">
                    <div class="list-info">
                        <div class="list-badges">
                            @if($list->isInternal())
                            <span class="list-badge primary">CS110</span>
                            @else
                            <span class="list-badge success">{{ Str::limit($list->source_institution, 15) }}</span>
                            @endif
                            <span class="list-program">{{ $list->program_code }}</span>
                            <span class="list-divider">|</span>
                            <span class="list-semester">{{ $list->semester }}</span>
                            <span class="list-divider">|</span>
                            <span class="list-mappings">{{ $list->total_equivalencies }} mappings</span>
                        </div>
                        @if($daysWaiting > 15)
                        <span class="urgency-badge {{ $daysWaiting > 30 ? 'danger' : 'warning' }}">
                            <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            {{ $daysWaiting }} days
                        </span>
                        @endif
                    </div>
                    <div class="list-meta">
                        <span class="meta-item">
                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            {{ $list->creator->name ?? 'Unknown' }}
                        </span>
                        <span class="meta-item">
                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $list->submitted_at?->format('d M Y, H:i') }}
                        </span>
                    </div>
                    <a href="{{ route('hea.equivalency_lists.review', $list) }}" class="review-btn">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        Review
                    </a>
                </div>
                @endforeach
            </div>
            @if($pendingLists->count() < $stats['pending_endorsements'])
            <div class="card-footer">
                Showing {{ $pendingLists->count() }} of {{ $stats['pending_endorsements'] }} pending lists
            </div>
            @endif
            @else
            <div class="empty-state">
                <div class="empty-icon success">
                    <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h4>All Caught Up!</h4>
                <p>There are no equivalency lists waiting for endorsement at the moment.</p>
            </div>
            @endif
        </section>

        {{-- Pending User Approvals --}}
        <section class="content-card approvals-card">
            @if(isset($pendingUserApprovals) && $pendingUserApprovals->count() > 0)
            <div class="card-header">
                <div class="header-info">
                    <div class="header-icon amber">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                    </div>
                    <h3>Pending User Approvals</h3>
                </div>
                <a href="{{ route('hea.users.index') }}" class="view-all-btn amber">
                    View All ({{ $stats['pending_user_approvals'] }})
                </a>
            </div>
            <div class="card-content">
                @foreach($pendingUserApprovals as $user)
                @php
                    $daysWaiting = $user->created_at ? now()->diffInDays($user->created_at) : 0;
                    $roleBadge = match($user->requested_role) {
                        'academic_advisor' => 'AA',
                        'program_coordinator' => 'PC',
                        'resource_person' => 'RP',
                        default => '?'
                    };
                    $roleLabel = match($user->requested_role) {
                        'academic_advisor' => 'Academic Advisor',
                        'program_coordinator' => 'Program Coordinator',
                        'resource_person' => 'Resource Person',
                        default => 'Unknown'
                    };
                    $requestedPrograms = $user->requested_programs ?? [];
                @endphp
                <div class="user-item">
                    <div class="user-info">
                        <div class="user-header">
                            <span class="role-tag">{{ $roleBadge }}</span>
                            <span class="user-name">{{ $user->name }}</span>
                            @if($daysWaiting > 3)
                            <span class="urgency-badge {{ $daysWaiting > 7 ? 'danger' : 'warning' }}">
                                <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                                {{ $daysWaiting }} days
                            </span>
                            @endif
                        </div>
                        <div class="user-details">
                            <span class="detail-item">
                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                {{ $user->email }}
                            </span>
                            <span class="detail-item">
                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                {{ $roleLabel }}
                            </span>
                        </div>
                        @if(!empty($requestedPrograms))
                        <div class="user-programs">
                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M12 14l9-5-9-5-9 5 9 5z"/>
                            </svg>
                            @php
                                $programs = is_array($requestedPrograms) ? $requestedPrograms : [];
                            @endphp
                            @foreach($programs as $program)
                                @if(is_string($program))
                                    <span class="program-badge">{{ $program }}</span>
                                @elseif(is_array($program) && isset($program['program_code']))
                                    <span class="program-badge">{{ $program['program_code'] }}: {{ $program['group'] ?? '' }}</span>
                                @endif
                            @endforeach
                        </div>
                        @endif
                        <span class="user-date">
                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Requested {{ $user->created_at?->format('d M Y') }}
                        </span>
                    </div>
                    <div class="user-actions">
                        <form action="{{ route('hea.users.approve', $user) }}" method="POST" class="action-form">
                            @csrf
                            <button type="submit" class="user-btn approve" onclick="return confirm('✅ Approve {{ $user->name }} as {{ $roleLabel }}?\n\nThis will grant immediate access with their requested program assignments.')">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Approve
                            </button>
                        </form>
                        <button type="button" class="user-btn edit" data-bs-toggle="modal" data-bs-target="#editModal{{ $user->id }}">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </button>
                        <button type="button" class="user-btn reject" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $user->id }}">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Reject
                        </button>
                    </div>
                </div>

                {{-- Edit Modal --}}
                <div class="modal fade" id="editModal{{ $user->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content industrial-modal">
                            <div class="modal-header amber">
                                <h5 class="modal-title">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit & Approve: {{ $user->name }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('hea.users.approve', $user) }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="info-alert">
                                        <strong>Current Request:</strong><br>
                                        <span class="text-muted">{{ $roleLabel }}</span>
                                    </div>
                                    <p class="modal-note">
                                        Edit functionality coming soon. For now, please approve or reject as-is.
                                    </p>
                                    <div class="warning-alert">
                                        To modify program assignments, reject this request and ask the user to re-register with the correct information.
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="modal-btn secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="modal-btn success">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Approve As-Is
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Reject Modal --}}
                <div class="modal fade" id="rejectModal{{ $user->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content industrial-modal">
                            <div class="modal-header danger">
                                <h5 class="modal-title">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Reject Registration: {{ $user->name }}
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('hea.users.approve', $user) }}" method="POST">
                                @csrf
                                <input type="hidden" name="reject" value="1">
                                <div class="modal-body">
                                    <div class="warning-alert">
                                        You are about to reject the registration request from <strong>{{ $user->name }}</strong>.
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">
                                            Reason for Rejection: <span class="required">*</span>
                                        </label>
                                        <textarea name="rejection_reason" class="form-textarea" rows="4" required placeholder="Please provide a clear reason for rejection (e.g., Invalid credentials, Duplicate account, Incorrect program selection, etc.)"></textarea>
                                        <span class="form-hint">This reason will be sent to the user via email.</span>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="modal-btn secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="modal-btn danger">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Confirm Rejection
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @if($pendingUserApprovals->count() < $stats['pending_user_approvals'])
            <div class="card-footer">
                Showing {{ $pendingUserApprovals->count() }} of {{ $stats['pending_user_approvals'] }} pending approvals
            </div>
            @endif
            @else
            <div class="empty-state">
                <div class="empty-icon success">
                    <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h4>No Pending Approvals!</h4>
                <p>There are no user registrations waiting for approval at the moment.</p>
            </div>
            @endif
        </section>
    </div>

    {{-- Info Cards --}}
    <div class="info-grid">
        <section class="info-card">
            <div class="info-header">
                <div class="info-icon blue">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3>Your Responsibilities</h3>
            </div>
            <ul class="responsibility-list">
                <li>
                    <span class="check success">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </span>
                    <div>
                        <strong>Endorse Equivalency Lists:</strong>
                        <span>Review and approve CS110 course mappings submitted by Resource Persons</span>
                    </div>
                </li>
                <li>
                    <span class="check blue">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                    </span>
                    <div>
                        <strong>Approve User Registrations:</strong>
                        <span>Review and approve Academic Advisors, Program Coordinators, and Resource Persons</span>
                    </div>
                </li>
                <li>
                    <span class="check teal">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </span>
                    <div>
                        <strong>Monitor System Activity:</strong>
                        <span>Track applications, review audit logs, and ensure system integrity</span>
                    </div>
                </li>
            </ul>
        </section>

        <section class="info-card">
            <div class="info-header">
                <div class="info-icon amber">
                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1h4v1a2 2 0 11-4 0zM12 14c.015-.34.208-.646.477-.859a4 4 0 10-4.954 0c.27.213.462.519.476.859h4.002z"/>
                    </svg>
                </div>
                <h3>Quick Tips</h3>
            </div>
            <ul class="tips-list">
                <li>
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    Use the <strong>Pending Endorsements</strong> page to review equivalency lists in detail
                </li>
                <li>
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    Check <strong>User Management</strong> regularly for pending registration approvals
                </li>
                <li>
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    Review <strong>System Logs</strong> to track important actions and changes
                </li>
            </ul>
        </section>
    </div>
</div>

<style>
/* ============================================
   INDUSTRIAL UI DESIGN SYSTEM
   HEA Dashboard
   ============================================ */

.hea-dashboard {
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

h1, h2, h3, h4, h5, h6 {
    font-family: 'IBM Plex Sans', sans-serif;
    font-weight: 600;
    color: var(--industrial-dark);
}

.metric-value, .breakdown-value {
    font-family: 'IBM Plex Mono', monospace;
}

/* ============================================
   HEADER
   ============================================ */
.dashboard-header {
    position: relative;
    background: linear-gradient(135deg, var(--industrial-dark) 0%, #1e293b 50%, var(--uitm-blue) 100%);
    margin: -1rem -1.5rem 1.5rem;
    padding: 2rem;
    overflow: hidden;
}

.header-grid {
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px),
        linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px);
    background-size: 32px 32px;
}

.header-accent {
    position: absolute;
    top: -100px;
    right: -100px;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(245, 158, 11, 0.2) 0%, transparent 70%);
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
    gap: 1rem;
}

.role-badge {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
}

.badge-text {
    background: var(--uitm-amber);
    color: var(--industrial-dark);
    font-family: 'IBM Plex Mono', monospace;
    font-size: 0.75rem;
    font-weight: 700;
    padding: 0.4rem 0.6rem;
    border-radius: 4px;
    letter-spacing: 0.05em;
}

.badge-line {
    width: 2px;
    height: 20px;
    background: linear-gradient(to bottom, var(--uitm-amber), transparent);
}

.welcome-info h1 {
    color: white;
    font-size: 1.5rem;
    margin: 0 0 0.25rem;
}

.welcome-info p {
    color: rgba(255,255,255,0.6);
    font-size: 0.875rem;
    margin: 0;
}

.header-right {
    display: none;
}

@media (min-width: 768px) {
    .header-right {
        display: block;
    }
}

.date-widget {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(10px);
    padding: 0.75rem 1rem;
    border-radius: 8px;
    border: 1px solid rgba(255,255,255,0.1);
}

.date-day {
    font-family: 'IBM Plex Mono', monospace;
    font-size: 1.75rem;
    font-weight: 600;
    color: white;
    line-height: 1;
}

.date-meta {
    display: flex;
    flex-direction: column;
}

.date-month {
    color: rgba(255,255,255,0.9);
    font-size: 0.8rem;
    font-weight: 500;
}

.date-weekday {
    color: rgba(255,255,255,0.5);
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

/* ============================================
   METRICS GRID
   ============================================ */
.metrics-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}

@media (min-width: 992px) {
    .metrics-grid {
        grid-template-columns: repeat(4, 1fr);
    }
}

.metric-card {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 1.25rem;
    text-decoration: none;
    transition: all 0.2s ease;
    display: flex;
    flex-direction: column;
    position: relative;
    overflow: hidden;
}

.metric-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
}

.metric-card.endorsements::before { background: var(--teal); }
.metric-card.published::before { background: var(--success); }
.metric-card.applications::before { background: var(--warning); }
.metric-card.users::before { background: var(--uitm-blue); }

.metric-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    border-color: #cbd5e1;
}

.metric-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.75rem;
}

.metric-card.endorsements .metric-icon { background: rgba(13, 148, 136, 0.1); color: var(--teal); }
.metric-card.published .metric-icon { background: rgba(5, 150, 105, 0.1); color: var(--success); }
.metric-card.applications .metric-icon { background: rgba(234, 88, 12, 0.1); color: var(--warning); }
.metric-card.users .metric-icon { background: rgba(30, 58, 138, 0.1); color: var(--uitm-blue); }

.metric-data {
    margin-bottom: 0.5rem;
}

.metric-value {
    font-size: 2rem;
    font-weight: 700;
    color: var(--industrial-dark);
    line-height: 1;
    display: block;
}

.metric-label {
    font-size: 0.8rem;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.02em;
}

.metric-breakdown {
    display: flex;
    gap: 0.5rem;
    margin-top: auto;
}

.breakdown-item {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.75rem;
    padding: 0.2rem 0.5rem;
    border-radius: 4px;
}

.breakdown-item.primary {
    background: rgba(30, 58, 138, 0.1);
    color: var(--uitm-blue);
}

.breakdown-item.success {
    background: rgba(5, 150, 105, 0.1);
    color: var(--success);
}

.breakdown-value {
    font-weight: 600;
}

.breakdown-label {
    font-weight: 500;
}

.metric-subtitle {
    font-size: 0.75rem;
    color: #94a3b8;
    margin-top: auto;
}

.metric-alert {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.75rem;
    color: var(--warning);
    background: rgba(234, 88, 12, 0.1);
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    margin-top: auto;
}

.metric-arrow {
    position: absolute;
    top: 1rem;
    right: 1rem;
    color: #cbd5e1;
    font-size: 1rem;
    transition: transform 0.2s, color 0.2s;
}

.metric-card:hover .metric-arrow {
    transform: translateX(3px);
    color: var(--uitm-blue);
}

/* ============================================
   NOTIFICATIONS SECTION
   ============================================ */
.notifications-section {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    margin-bottom: 1.5rem;
    overflow: hidden;
}

.notifications-section .section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.25rem;
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
}

.header-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.notification-bell {
    position: relative;
}

.notification-bell svg {
    color: white;
}

.bell-badge {
    position: absolute;
    top: -6px;
    right: -6px;
    background: var(--danger);
    color: white;
    font-family: 'IBM Plex Mono', monospace;
    font-size: 0.65rem;
    font-weight: 700;
    padding: 2px 5px;
    border-radius: 10px;
    min-width: 16px;
    text-align: center;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.5); }
    50% { box-shadow: 0 0 0 6px rgba(220, 38, 38, 0); }
}

.header-title h2 {
    color: white;
    font-size: 1rem;
    margin: 0;
}

.mark-all-btn {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    background: rgba(255,255,255,0.2);
    color: white;
    border: none;
    padding: 0.5rem 0.75rem;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.2s;
}

.mark-all-btn:hover {
    background: rgba(255,255,255,0.3);
}

.notifications-list {
    max-height: 400px;
    overflow-y: auto;
}

.notification-item {
    display: flex;
    align-items: flex-start;
    gap: 0.875rem;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #f1f5f9;
    transition: background 0.2s;
}

.notification-item:hover {
    background: #f8fafc;
}

.notification-item:last-child {
    border-bottom: none;
}

.notification-icon {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.notification-icon.primary {
    background: rgba(30, 58, 138, 0.1);
    color: var(--uitm-blue);
}

.notification-icon.info {
    background: rgba(13, 148, 136, 0.1);
    color: var(--teal);
}

.notification-content {
    flex: 1;
    min-width: 0;
}

.notification-message {
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--industrial-dark);
    margin: 0 0 0.35rem;
}

.notification-meta {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.meta-badge {
    font-size: 0.7rem;
    font-weight: 600;
    background: var(--uitm-blue);
    color: white;
    padding: 0.15rem 0.4rem;
    border-radius: 3px;
}

.meta-email {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.75rem;
    color: #64748b;
}

.notification-program {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.75rem;
    color: #64748b;
    margin-top: 0.25rem;
}

.notification-actions {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 0.5rem;
}

.notification-time {
    font-size: 0.7rem;
    color: #94a3b8;
}

.action-buttons {
    display: flex;
    gap: 0.35rem;
}

.action-btn {
    width: 30px;
    height: 30px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
}

.action-btn.mark-read {
    background: #f1f5f9;
    color: #64748b;
}

.action-btn.mark-read:hover {
    background: var(--success);
    color: white;
}

.action-btn.view {
    background: var(--uitm-blue);
    color: white;
    text-decoration: none;
}

.action-btn.view:hover {
    background: #1e40af;
}

.notifications-footer {
    padding: 0.75rem;
    text-align: center;
    font-size: 0.75rem;
    color: #64748b;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
}

/* ============================================
   CONTENT GRID
   ============================================ */
.content-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

@media (min-width: 992px) {
    .content-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

.content-card {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #e2e8f0;
    background: #fafbfc;
}

.header-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.header-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.header-icon.blue {
    background: rgba(30, 58, 138, 0.1);
    color: var(--uitm-blue);
}

.header-icon.amber {
    background: rgba(245, 158, 11, 0.1);
    color: var(--uitm-amber);
}

.card-header h3 {
    font-size: 0.9375rem;
    margin: 0;
}

.view-all-btn {
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

.view-all-btn.amber {
    color: var(--industrial-dark);
    background: rgba(245, 158, 11, 0.1);
}

.view-all-btn.amber:hover {
    background: var(--uitm-amber);
    color: var(--industrial-dark);
}

.card-content {
    max-height: 400px;
    overflow-y: auto;
}

/* List Items */
.list-item {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 0.75rem;
    transition: background 0.2s;
}

.list-item:hover {
    background: #f8fafc;
}

.list-item:last-child {
    border-bottom: none;
}

.list-info {
    flex: 1;
    min-width: 200px;
}

.list-badges {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin-bottom: 0.35rem;
}

.list-badge {
    font-size: 0.7rem;
    font-weight: 600;
    padding: 0.2rem 0.5rem;
    border-radius: 4px;
}

.list-badge.primary {
    background: rgba(30, 58, 138, 0.1);
    color: var(--uitm-blue);
}

.list-badge.success {
    background: rgba(5, 150, 105, 0.1);
    color: var(--success);
}

.list-program {
    font-weight: 600;
    color: var(--industrial-dark);
    font-size: 0.875rem;
}

.list-divider {
    color: #cbd5e1;
}

.list-semester,
.list-mappings {
    font-size: 0.8rem;
    color: #64748b;
}

.urgency-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.7rem;
    font-weight: 600;
    padding: 0.2rem 0.5rem;
    border-radius: 4px;
}

.urgency-badge.warning {
    background: rgba(245, 158, 11, 0.1);
    color: var(--warning);
}

.urgency-badge.danger {
    background: rgba(220, 38, 38, 0.1);
    color: var(--danger);
}

.list-meta {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.75rem;
    color: #64748b;
}

.review-btn {
    display: flex;
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
    margin-left: auto;
}

.review-btn:hover {
    background: #1e40af;
    transform: translateY(-1px);
}

/* User Items */
.user-item {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #f1f5f9;
}

.user-item:last-child {
    border-bottom: none;
}

.user-info {
    margin-bottom: 0.75rem;
}

.user-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
    flex-wrap: wrap;
}

.role-tag {
    background: var(--uitm-blue);
    color: white;
    font-family: 'IBM Plex Mono', monospace;
    font-size: 0.65rem;
    font-weight: 700;
    padding: 0.2rem 0.4rem;
    border-radius: 3px;
}

.user-name {
    font-weight: 600;
    color: var(--industrial-dark);
}

.user-details {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.8rem;
    color: #64748b;
}

.user-programs {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    margin-top: 0.5rem;
    flex-wrap: wrap;
    color: #64748b;
}

.program-badge {
    font-size: 0.7rem;
    font-weight: 500;
    background: #f1f5f9;
    color: var(--industrial-gray);
    padding: 0.15rem 0.4rem;
    border-radius: 3px;
}

.user-date {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.75rem;
    color: #94a3b8;
    margin-top: 0.5rem;
}

.user-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.user-btn {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.8rem;
    font-weight: 500;
    padding: 0.5rem 0.75rem;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
}

.user-btn.approve {
    background: var(--success);
    color: white;
}

.user-btn.approve:hover {
    background: #047857;
}

.user-btn.edit {
    background: var(--uitm-amber);
    color: var(--industrial-dark);
}

.user-btn.edit:hover {
    background: #d97706;
}

.user-btn.reject {
    background: white;
    color: var(--danger);
    border: 1px solid var(--danger);
}

.user-btn.reject:hover {
    background: var(--danger);
    color: white;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem 2rem;
}

.empty-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
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

.card-footer {
    padding: 0.75rem 1.25rem;
    text-align: center;
    font-size: 0.75rem;
    color: #64748b;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
}

/* ============================================
   INFO GRID
   ============================================ */
.info-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.5rem;
}

@media (min-width: 768px) {
    .info-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

.info-card {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 1.25rem;
}

.info-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid #f1f5f9;
}

.info-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.info-icon.blue {
    background: rgba(30, 58, 138, 0.1);
    color: var(--uitm-blue);
}

.info-icon.amber {
    background: rgba(245, 158, 11, 0.1);
    color: var(--uitm-amber);
}

.info-header h3 {
    font-size: 0.9375rem;
    margin: 0;
}

.responsibility-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.responsibility-list li {
    display: flex;
    gap: 0.75rem;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f1f5f9;
}

.responsibility-list li:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.check {
    width: 24px;
    height: 24px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.check.success {
    background: rgba(5, 150, 105, 0.1);
    color: var(--success);
}

.check.blue {
    background: rgba(30, 58, 138, 0.1);
    color: var(--uitm-blue);
}

.check.teal {
    background: rgba(13, 148, 136, 0.1);
    color: var(--teal);
}

.responsibility-list li div strong {
    display: block;
    font-size: 0.875rem;
    margin-bottom: 0.15rem;
}

.responsibility-list li div span {
    font-size: 0.8rem;
    color: #64748b;
}

.tips-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.tips-list li {
    display: flex;
    align-items: flex-start;
    gap: 0.5rem;
    padding: 0.625rem 0;
    font-size: 0.875rem;
    color: #64748b;
    border-bottom: 1px solid #f1f5f9;
}

.tips-list li:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.tips-list li svg {
    color: #94a3b8;
    flex-shrink: 0;
    margin-top: 0.15rem;
}

.tips-list li strong {
    color: var(--industrial-dark);
}

/* ============================================
   MODALS
   ============================================ */
.industrial-modal .modal-header {
    border-bottom: none;
    padding: 1.25rem;
}

.industrial-modal .modal-header.amber {
    background: linear-gradient(135deg, var(--uitm-amber) 0%, #d97706 100%);
    color: var(--industrial-dark);
}

.industrial-modal .modal-header.danger {
    background: linear-gradient(135deg, var(--danger) 0%, #b91c1c 100%);
    color: white;
}

.industrial-modal .modal-title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1rem;
}

.industrial-modal .modal-body {
    padding: 1.25rem;
}

.info-alert {
    background: rgba(30, 58, 138, 0.05);
    border: 1px solid rgba(30, 58, 138, 0.1);
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
}

.warning-alert {
    background: rgba(245, 158, 11, 0.1);
    border: 1px solid rgba(245, 158, 11, 0.2);
    border-radius: 8px;
    padding: 1rem;
    font-size: 0.875rem;
    color: var(--industrial-dark);
}

.modal-note {
    font-size: 0.875rem;
    color: #64748b;
    margin-bottom: 1rem;
}

.form-group {
    margin-bottom: 1rem;
}

.form-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.required {
    color: var(--danger);
}

.form-textarea {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-family: inherit;
    font-size: 0.875rem;
    resize: vertical;
    transition: border-color 0.2s;
}

.form-textarea:focus {
    outline: none;
    border-color: var(--uitm-blue);
    box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
}

.form-hint {
    display: block;
    font-size: 0.75rem;
    color: #64748b;
    margin-top: 0.35rem;
}

.industrial-modal .modal-footer {
    border-top: 1px solid #e2e8f0;
    padding: 1rem 1.25rem;
    gap: 0.5rem;
}

.modal-btn {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.875rem;
    font-weight: 500;
    padding: 0.625rem 1rem;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
}

.modal-btn.secondary {
    background: #f1f5f9;
    color: var(--industrial-gray);
}

.modal-btn.secondary:hover {
    background: #e2e8f0;
}

.modal-btn.success {
    background: var(--success);
    color: white;
}

.modal-btn.success:hover {
    background: #047857;
}

.modal-btn.danger {
    background: var(--danger);
    color: white;
}

.modal-btn.danger:hover {
    background: #b91c1c;
}

/* ============================================
   RESPONSIVE
   ============================================ */
@media (max-width: 767px) {
    .hea-dashboard {
        padding: 0 1rem 1.5rem;
    }

    .dashboard-header {
        margin: -1rem -1rem 1.25rem;
        padding: 1.5rem 1rem;
    }

    .welcome-info h1 {
        font-size: 1.25rem;
    }

    .metrics-grid {
        grid-template-columns: 1fr;
    }

    .metric-value {
        font-size: 1.75rem;
    }

    .list-item {
        flex-direction: column;
        align-items: flex-start;
    }

    .review-btn {
        margin-left: 0;
        margin-top: 0.5rem;
    }
}
</style>
@endsection
