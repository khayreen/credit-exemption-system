@extends('layouts.app')

@section('content')
<div class="active-staff-page">
    {{-- Industrial Page Header --}}
    <div class="page-header">
        <div class="header-pattern"></div>
        <div class="header-content">
            <div class="header-left">
                <div class="header-icon">
                    <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div>
                    <h1>Active Staff Members</h1>
                    <p>Manage verified and active personnel accounts</p>
                </div>
            </div>
            <div class="header-right">
                <a href="{{ route('hea.users.pending') }}" class="btn-header-action">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    View Pending
                </a>
            </div>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert-industrial success">
            <div class="alert-content">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" class="alert-close" data-bs-dismiss="alert">&times;</button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert-industrial info">
            <div class="alert-content">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('info') }}</span>
            </div>
            <button type="button" class="alert-close" data-bs-dismiss="alert">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert-industrial danger">
            <div class="alert-content">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" class="alert-close" data-bs-dismiss="alert">&times;</button>
        </div>
    @endif

    {{-- Stats Grid --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon total">
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div class="stat-data">
                <span class="stat-value total">{{ $activeUsers->count() }}</span>
                <span class="stat-label">Total Active</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon verified">
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76"/>
                </svg>
            </div>
            <div class="stat-data">
                <span class="stat-value verified">{{ $activeUsers->filter(fn($u) => $u->hasVerifiedEmail())->count() }}</span>
                <span class="stat-label">Email Verified</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon pending">
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-data">
                <span class="stat-value pending">{{ $activeUsers->filter(fn($u) => !$u->hasVerifiedEmail())->count() }}</span>
                <span class="stat-label">Pending Verification</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon tfa">
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <div class="stat-data">
                <span class="stat-value tfa">{{ $activeUsers->filter(fn($u) => $u->two_factor_verified_at)->count() }}</span>
                <span class="stat-label">2FA Enabled</span>
            </div>
        </div>
    </div>

    {{-- Staff Table --}}
    @if($activeUsers->isEmpty())
        <div class="empty-state">
            <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <p>No active staff members found.</p>
        </div>
    @else
        <div class="table-card">
            <div class="table-header">
                <div class="table-title">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    <span>Staff Directory</span>
                </div>
                <span class="table-count">{{ $activeUsers->count() }} {{ Str::plural('member', $activeUsers->count()) }}</span>
            </div>
            <div class="table-responsive">
                <table class="industrial-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Program Assignment</th>
                            <th>Status</th>
                            <th>Approved Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activeUsers as $user)
                        <tr>
                            <td>
                                <div class="user-cell">
                                    <div class="user-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                                    <span class="user-name">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="user-email">{{ $user->email }}</span>
                            </td>
                            <td>
                                <span class="role-tag bg-{{ $user->role_color }}">
                                    {{ $user->requested_role_badge }} - {{ $user->requested_role_label }}
                                </span>
                            </td>
                            <td>
                                <span class="program-info">{{ $user->formatted_program_info }}</span>
                            </td>
                            <td>
                                <div class="status-group">
                                    @if($user->hasVerifiedEmail())
                                        <span class="status-pill verified">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Verified
                                        </span>
                                    @else
                                        <span class="status-pill pending">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Pending
                                        </span>
                                    @endif

                                    @if($user->two_factor_verified_at)
                                        <span class="tfa-badge" title="2FA Enabled">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                            </svg>
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="date-mono">{{ $user->approved_at?->format('M j, Y') ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <div class="action-group">
                                    <a href="{{ route('hea.staff_assignments.edit', $user) }}" class="action-btn edit" title="Edit Assignment">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>

                                    @if(!$user->hasVerifiedEmail())
                                        <form action="{{ route('hea.users.resend-verification', $user) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="action-btn resend" title="Resend Verification Email"
                                                    onclick="return confirm('Resend verification email to {{ $user->name }}?')">
                                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif

                                    <button type="button" class="action-btn details view-details-btn" title="View Details"
                                            data-user-id="{{ $user->id }}"
                                            data-user-name="{{ $user->name }}"
                                            data-user-email="{{ $user->email }}"
                                            data-user-role="{{ $user->requested_role_label }}"
                                            data-user-role-color="{{ $user->role_color }}"
                                            data-user-program="{{ $user->formatted_program_info }}"
                                            data-user-verified="{{ $user->hasVerifiedEmail() ? 'yes' : 'no' }}"
                                            data-user-verified-at="{{ $user->email_verified_at?->format('M j, Y g:i A') ?? '' }}"
                                            data-user-2fa="{{ $user->two_factor_verified_at ? 'yes' : 'no' }}"
                                            data-user-registered="{{ $user->created_at->format('M j, Y g:i A') }}"
                                            data-user-approved="{{ $user->approved_at?->format('M j, Y g:i A') ?? 'N/A' }}"
                                            data-user-approved-by="{{ $user->approvedBy?->name ?? '' }}"
                                            data-resend-url="{{ route('hea.users.resend-verification', $user) }}">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>

                                    <form action="{{ route('hea.users.deactivate', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="action-btn deactivate" title="Deactivate"
                                                onclick="return confirm('Deactivate {{ $user->name }}? They will lose access to the system.')">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

{{-- Details Modal --}}
<div class="modal" id="detailsModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content industrial-modal">
            <div class="modal-header-industrial">
                <div class="modal-header-left">
                    <div class="modal-icon">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h5 class="modal-title-industrial"><span id="modalUserName"></span></h5>
                </div>
                <button type="button" class="modal-close-btn" data-bs-dismiss="modal">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="modal-body-industrial">
                <div class="detail-grid">
                    <div class="detail-row">
                        <span class="detail-label">Email</span>
                        <span class="detail-value" id="modalUserEmail"></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Role</span>
                        <span class="detail-value"><span id="modalUserRole" class="badge"></span></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Program</span>
                        <span class="detail-value" id="modalUserProgram"></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Email Verified</span>
                        <span class="detail-value" id="modalUserVerified"></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">2FA Status</span>
                        <span class="detail-value" id="modalUser2FA"></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Registered</span>
                        <span class="detail-value" id="modalUserRegistered"></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Approved</span>
                        <span class="detail-value" id="modalUserApproved"></span>
                    </div>
                    <div class="detail-row" id="approvedByRow" style="display: none;">
                        <span class="detail-label">Approved By</span>
                        <span class="detail-value" id="modalUserApprovedBy"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer-industrial">
                <form id="resendForm" method="POST" style="display: none;">
                    @csrf
                    <button type="submit" class="btn-modal-action primary">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Resend Verification Email
                    </button>
                </form>
                <button type="button" class="btn-modal-action secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

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
        --success: #059669;
        --danger: #dc2626;
        --warning: #ea580c;
        --teal: #0d9488;
    }

    .active-staff-page {
        font-family: 'IBM Plex Sans', sans-serif;
        padding: 0;
    }

    /* ========== Page Header ========== */
    .page-header {
        background: linear-gradient(135deg, var(--industrial-dark) 0%, var(--uitm-blue) 50%, #1e40af 100%);
        border-radius: 16px;
        padding: 2rem 2.5rem;
        margin-bottom: 1.75rem;
        position: relative;
        overflow: hidden;
    }

    .header-pattern {
        position: absolute;
        inset: 0;
        background-image:
            radial-gradient(circle at 20% 50%, rgba(59, 130, 246, 0.15) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(245, 158, 11, 0.1) 0%, transparent 40%);
        pointer-events: none;
    }

    .header-pattern::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 350px;
        height: 100%;
        background: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M20 20h20v20H20z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    .header-content {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .header-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--uitm-amber);
    }

    .header-left h1 {
        color: #fff;
        font-size: 1.6rem;
        font-weight: 700;
        margin: 0;
        letter-spacing: -0.01em;
    }

    .header-left p {
        color: rgba(255, 255, 255, 0.65);
        margin: 0.25rem 0 0 0;
        font-size: 0.9rem;
    }

    .btn-header-action {
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.875rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
        font-family: 'IBM Plex Sans', sans-serif;
    }

    .btn-header-action:hover {
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
        text-decoration: none;
    }

    /* ========== Alert Messages ========== */
    .alert-industrial {
        border-radius: 10px;
        padding: 0.875rem 1.25rem;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border: 1px solid transparent;
        font-size: 0.9rem;
    }

    .alert-content {
        display: flex;
        align-items: center;
        gap: 0.625rem;
    }

    .alert-industrial.success {
        background: rgba(5, 150, 105, 0.06);
        border-color: rgba(5, 150, 105, 0.15);
        border-left: 4px solid var(--success);
        color: var(--success);
    }

    .alert-industrial.info {
        background: rgba(13, 148, 136, 0.06);
        border-color: rgba(13, 148, 136, 0.15);
        border-left: 4px solid var(--teal);
        color: var(--teal);
    }

    .alert-industrial.danger {
        background: rgba(220, 38, 38, 0.06);
        border-color: rgba(220, 38, 38, 0.15);
        border-left: 4px solid var(--danger);
        color: var(--danger);
    }

    .alert-close {
        background: none;
        border: none;
        font-size: 1.25rem;
        line-height: 1;
        cursor: pointer;
        color: inherit;
        opacity: 0.6;
        transition: opacity 0.2s;
    }

    .alert-close:hover {
        opacity: 1;
    }

    /* ========== Stats Grid ========== */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 1.75rem;
    }

    .stat-card {
        background: #fff;
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.2s ease;
    }

    .stat-card:hover {
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
        border-color: #cbd5e1;
    }

    .stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .stat-icon.total {
        background: rgba(30, 58, 138, 0.08);
        color: var(--uitm-blue);
    }

    .stat-icon.verified {
        background: rgba(5, 150, 105, 0.08);
        color: var(--success);
    }

    .stat-icon.pending {
        background: rgba(234, 88, 12, 0.08);
        color: var(--warning);
    }

    .stat-icon.tfa {
        background: rgba(13, 148, 136, 0.08);
        color: var(--teal);
    }

    .stat-data {
        display: flex;
        flex-direction: column;
    }

    .stat-value {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 1.75rem;
        font-weight: 700;
        line-height: 1;
    }

    .stat-value.total { color: var(--uitm-blue); }
    .stat-value.verified { color: var(--success); }
    .stat-value.pending { color: var(--warning); }
    .stat-value.tfa { color: var(--teal); }

    .stat-label {
        font-size: 0.8rem;
        color: var(--industrial-gray);
        margin-top: 0.35rem;
        font-weight: 500;
    }

    /* ========== Empty State ========== */
    .empty-state {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding: 3rem;
        text-align: center;
        color: var(--industrial-gray);
    }

    .empty-state svg {
        margin-bottom: 1rem;
        opacity: 0.4;
    }

    .empty-state p {
        margin: 0;
        font-size: 0.95rem;
    }

    /* ========== Table Card ========== */
    .table-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .table-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: var(--industrial-light);
    }

    .table-title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--industrial-dark);
    }

    .table-title svg {
        color: var(--uitm-blue-light);
    }

    .table-count {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.8rem;
        color: var(--industrial-gray);
        background: #fff;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
    }

    .industrial-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .industrial-table thead th {
        background: #f8fafc;
        padding: 0.875rem 1.25rem;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--industrial-gray);
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }

    .industrial-table tbody td {
        padding: 0.875rem 1.25rem;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        font-size: 0.9rem;
    }

    .industrial-table tbody tr:last-child td {
        border-bottom: none;
    }

    .industrial-table tbody tr {
        transition: background-color 0.15s ease;
    }

    .industrial-table tbody tr:hover {
        background: rgba(30, 58, 138, 0.02);
    }

    /* ========== Table Cell Styles ========== */
    .user-cell {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .user-avatar {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--uitm-blue-light) 100%);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        font-weight: 700;
        flex-shrink: 0;
    }

    .user-name {
        font-weight: 600;
        color: var(--industrial-dark);
        font-size: 0.9rem;
    }

    .user-email {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.8rem;
        color: var(--industrial-gray);
    }

    .role-tag {
        display: inline-block;
        padding: 0.3rem 0.65rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        color: #fff;
        white-space: nowrap;
    }

    .program-info {
        font-size: 0.825rem;
        color: var(--industrial-gray);
        max-width: 180px;
        display: block;
    }

    .status-group {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        flex-wrap: wrap;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.3rem 0.65rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .status-pill.verified {
        background: rgba(5, 150, 105, 0.08);
        color: var(--success);
    }

    .status-pill.pending {
        background: rgba(234, 88, 12, 0.08);
        color: var(--warning);
    }

    .tfa-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: rgba(13, 148, 136, 0.08);
        color: var(--teal);
    }

    .date-mono {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.8rem;
        color: var(--industrial-gray);
    }

    /* ========== Action Buttons ========== */
    .action-group {
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        border: 1px solid transparent;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        background: transparent;
        text-decoration: none;
    }

    .action-btn.edit {
        color: var(--uitm-blue);
        background: rgba(30, 58, 138, 0.06);
        border-color: rgba(30, 58, 138, 0.12);
    }

    .action-btn.edit:hover {
        background: rgba(30, 58, 138, 0.12);
        color: var(--uitm-blue);
    }

    .action-btn.resend {
        color: var(--teal);
        background: rgba(13, 148, 136, 0.06);
        border-color: rgba(13, 148, 136, 0.12);
    }

    .action-btn.resend:hover {
        background: rgba(13, 148, 136, 0.12);
    }

    .action-btn.details {
        color: var(--industrial-gray);
        background: rgba(51, 65, 85, 0.06);
        border-color: rgba(51, 65, 85, 0.12);
    }

    .action-btn.details:hover {
        background: rgba(51, 65, 85, 0.12);
    }

    .action-btn.deactivate {
        color: var(--danger);
        background: rgba(220, 38, 38, 0.06);
        border-color: rgba(220, 38, 38, 0.12);
    }

    .action-btn.deactivate:hover {
        background: rgba(220, 38, 38, 0.12);
    }

    /* ========== Industrial Modal ========== */
    .industrial-modal {
        border: none;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 25px 60px rgba(15, 23, 42, 0.15);
    }

    .modal-header-industrial {
        background: linear-gradient(135deg, var(--industrial-dark) 0%, var(--uitm-blue) 100%);
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .modal-header-left {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .modal-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--uitm-amber);
    }

    .modal-title-industrial {
        color: #fff;
        font-size: 1.1rem;
        font-weight: 600;
        margin: 0;
    }

    .modal-close-btn {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.15);
        color: rgba(255, 255, 255, 0.7);
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .modal-close-btn:hover {
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
    }

    .modal-body-industrial {
        padding: 1.5rem;
    }

    .detail-grid {
        display: flex;
        flex-direction: column;
        gap: 0;
    }

    .detail-row {
        display: flex;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        width: 40%;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--industrial-gray);
    }

    .detail-value {
        width: 60%;
        font-size: 0.9rem;
        color: var(--industrial-dark);
    }

    .modal-footer-industrial {
        padding: 1rem 1.5rem;
        border-top: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 0.625rem;
        background: #f8fafc;
    }

    .btn-modal-action {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
        font-family: 'IBM Plex Sans', sans-serif;
        cursor: pointer;
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }

    .btn-modal-action.primary {
        background: var(--uitm-blue);
        color: #fff;
        border-color: var(--uitm-blue);
    }

    .btn-modal-action.primary:hover {
        background: var(--uitm-blue-light);
        border-color: var(--uitm-blue-light);
    }

    .btn-modal-action.secondary {
        background: #fff;
        color: var(--industrial-gray);
        border-color: #e2e8f0;
    }

    .btn-modal-action.secondary:hover {
        background: var(--industrial-light);
        color: var(--industrial-dark);
    }

    /* ========== Modal Anti-Flickering ========== */
    #detailsModal,
    #detailsModal *,
    #detailsModal::before,
    #detailsModal::after {
        -webkit-transition: none !important;
        -moz-transition: none !important;
        transition: none !important;
        -webkit-animation: none !important;
        -moz-animation: none !important;
        animation: none !important;
    }

    #detailsModal .modal-dialog {
        -webkit-transform: none !important;
        transform: none !important;
        margin: 1.75rem auto !important;
    }

    .modal-backdrop {
        -webkit-transition: none !important;
        transition: none !important;
        opacity: 0.5 !important;
    }

    /* ========== Responsive ========== */
    @media (max-width: 1200px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
        }

        .header-content {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .industrial-table thead th,
        .industrial-table tbody td {
            padding: 0.75rem 0.875rem;
        }

        .action-group {
            flex-wrap: wrap;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modalEl = document.getElementById('detailsModal');
    let bsModal = null;

    try {
        bsModal = bootstrap.Modal.getOrCreateInstance(modalEl, {
            backdrop: 'static',
            keyboard: false
        });
    } catch(e) {
        console.error('Modal init error:', e);
    }

    // Handle View Details button clicks
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.view-details-btn');
        if (btn) {
            e.preventDefault();
            e.stopPropagation();

            // Populate modal
            document.getElementById('modalUserName').textContent = btn.dataset.userName;
            document.getElementById('modalUserEmail').textContent = btn.dataset.userEmail;

            const roleSpan = document.getElementById('modalUserRole');
            roleSpan.textContent = btn.dataset.userRole;
            roleSpan.className = 'badge bg-' + btn.dataset.userRoleColor;

            document.getElementById('modalUserProgram').textContent = btn.dataset.userProgram;
            document.getElementById('modalUserRegistered').textContent = btn.dataset.userRegistered;
            document.getElementById('modalUserApproved').textContent = btn.dataset.userApproved;

            // Email verified status
            const verifiedEl = document.getElementById('modalUserVerified');
            if (btn.dataset.userVerified === 'yes') {
                verifiedEl.innerHTML = '<span class="text-success"><i class="fas fa-check-circle"></i> ' + btn.dataset.userVerifiedAt + '</span>';
            } else {
                verifiedEl.innerHTML = '<span class="text-warning"><i class="fas fa-clock"></i> Not verified</span>';
            }

            // 2FA status
            const tfaEl = document.getElementById('modalUser2FA');
            if (btn.dataset.user2fa === 'yes') {
                tfaEl.innerHTML = '<span class="text-success"><i class="fas fa-shield-alt"></i> Enabled</span>';
            } else {
                tfaEl.innerHTML = '<span class="text-muted"><i class="fas fa-shield-alt"></i> Not set up</span>';
            }

            // Approved by
            const approvedByRow = document.getElementById('approvedByRow');
            const approvedByEl = document.getElementById('modalUserApprovedBy');
            if (btn.dataset.userApprovedBy) {
                approvedByRow.style.display = 'flex';
                approvedByEl.textContent = btn.dataset.userApprovedBy;
            } else {
                approvedByRow.style.display = 'none';
            }

            // Resend form
            const resendForm = document.getElementById('resendForm');
            if (btn.dataset.userVerified === 'no') {
                resendForm.style.display = 'block';
                resendForm.action = btn.dataset.resendUrl;
            } else {
                resendForm.style.display = 'none';
            }

            // Show modal
            if (bsModal) {
                bsModal.show();
            }
        }
    });
});
</script>
@endpush
@endsection
