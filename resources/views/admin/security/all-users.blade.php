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

    .all-users-page {
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
    }

    .header-actions {
        position: absolute;
        top: 50%;
        right: 2rem;
        transform: translateY(-50%);
    }

    .btn-back {
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
    }

    .btn-back:hover {
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
    }

    /* Alert Messages */
    .alert-custom {
        border-radius: 8px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .alert-custom.success {
        background: rgba(5, 150, 105, 0.08);
        border: 1px solid rgba(5, 150, 105, 0.2);
        border-left: 4px solid var(--success-color);
        color: var(--success-color);
    }

    .alert-custom.danger {
        background: rgba(220, 38, 38, 0.08);
        border: 1px solid rgba(220, 38, 38, 0.2);
        border-left: 4px solid var(--danger-color);
        color: var(--danger-color);
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        background: #fff;
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid #e2e8f0;
        text-align: center;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        font-family: 'IBM Plex Mono', monospace;
        line-height: 1;
    }

    .stat-value.total {
        color: var(--uitm-blue);
    }

    .stat-value.active {
        color: var(--success-color);
    }

    .stat-value.locked {
        color: var(--danger-color);
    }

    .stat-label {
        font-size: 0.875rem;
        color: var(--industrial-gray);
        margin-top: 0.5rem;
    }

    /* Filter Card */
    .filter-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
    }

    .filter-row {
        display: flex;
        gap: 1rem;
        align-items: flex-end;
        flex-wrap: wrap;
    }

    .filter-group {
        flex: 1;
        min-width: 150px;
    }

    .filter-group.search {
        flex: 2;
    }

    .filter-group label {
        display: block;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--industrial-gray);
        margin-bottom: 0.5rem;
    }

    .filter-group input,
    .filter-group select {
        width: 100%;
        padding: 0.625rem 0.875rem;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.9rem;
        background: #fff;
        color: var(--industrial-dark);
        transition: all 0.2s ease;
    }

    .filter-group input:focus,
    .filter-group select:focus {
        outline: none;
        border-color: var(--uitm-blue);
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
    }

    .filter-actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn-filter {
        background: var(--uitm-blue);
        color: #fff;
        border: none;
        padding: 0.625rem 1rem;
        border-radius: 6px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-filter:hover {
        background: var(--uitm-blue-light);
    }

    .btn-clear {
        background: var(--industrial-light);
        color: var(--industrial-gray);
        border: 1px solid #e2e8f0;
        padding: 0.625rem 0.875rem;
        border-radius: 6px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: all 0.2s ease;
    }

    .btn-clear:hover {
        background: #e2e8f0;
        color: var(--industrial-dark);
    }

    /* Users Table Card */
    .users-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .custom-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .custom-table thead th {
        background: var(--industrial-light);
        padding: 1rem 1.25rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--industrial-gray);
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }

    .custom-table tbody td {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .custom-table tbody tr:last-child td {
        border-bottom: none;
    }

    .custom-table tbody tr:hover {
        background: rgba(30, 58, 138, 0.02);
    }

    .user-name {
        font-weight: 600;
        color: var(--industrial-dark);
    }

    .user-email {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.875rem;
        color: var(--industrial-gray);
    }

    .role-badge {
        display: inline-block;
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 500;
        text-transform: capitalize;
        background: var(--industrial-light);
        color: var(--industrial-gray);
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .status-badge.active {
        background: rgba(5, 150, 105, 0.1);
        color: var(--success-color);
    }

    .status-badge.locked {
        background: rgba(220, 38, 38, 0.1);
        color: var(--danger-color);
    }

    .created-date {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.85rem;
        color: var(--industrial-gray);
    }

    /* Action Buttons */
    .btn-unlock {
        background: rgba(5, 150, 105, 0.1);
        color: var(--success-color);
        border: 1px solid rgba(5, 150, 105, 0.2);
        padding: 0.5rem 0.875rem;
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-unlock:hover {
        background: var(--success-color);
        color: #fff;
        border-color: var(--success-color);
    }

    .btn-lock {
        background: rgba(220, 38, 38, 0.1);
        color: var(--danger-color);
        border: 1px solid rgba(220, 38, 38, 0.2);
        padding: 0.5rem 0.875rem;
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-lock:hover {
        background: var(--danger-color);
        color: #fff;
        border-color: var(--danger-color);
    }

    .your-account {
        color: var(--industrial-gray);
        font-size: 0.85rem;
        font-style: italic;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem;
        color: var(--industrial-gray);
    }

    .empty-state i {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    /* Pagination */
    .pagination-wrapper {
        padding: 1rem 1.25rem;
        border-top: 1px solid #e2e8f0;
        background: var(--industrial-light);
    }

    /* Modal Styling */
    .modal-industrial .modal-content {
        border: none;
        border-radius: 16px;
        overflow: hidden;
    }

    .modal-industrial .modal-header {
        background: linear-gradient(135deg, var(--danger-color) 0%, #b91c1c 100%);
        padding: 1.25rem 1.5rem;
        border-bottom: none;
    }

    .modal-industrial .modal-title {
        color: #fff;
        font-weight: 600;
    }

    .modal-industrial .modal-body {
        padding: 1.5rem;
    }

    .modal-industrial .modal-body p {
        color: var(--industrial-dark);
        margin-bottom: 1rem;
    }

    .modal-industrial .modal-body .text-muted {
        color: var(--industrial-gray) !important;
    }

    .form-group-custom {
        margin-bottom: 0;
    }

    .form-group-custom label {
        display: block;
        font-weight: 500;
        color: var(--industrial-dark);
        margin-bottom: 0.5rem;
    }

    .form-group-custom label .required {
        color: var(--danger-color);
    }

    .form-group-custom input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }

    .form-group-custom input:focus {
        outline: none;
        border-color: var(--uitm-blue);
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
    }

    .modal-industrial .modal-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid #e2e8f0;
        background: var(--industrial-light);
    }

    .btn-modal-cancel {
        background: #fff;
        color: var(--industrial-gray);
        border: 1px solid #e2e8f0;
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-modal-cancel:hover {
        background: var(--industrial-light);
        color: var(--industrial-dark);
    }

    .btn-modal-lock {
        background: var(--danger-color);
        color: #fff;
        border: none;
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-modal-lock:hover {
        background: #b91c1c;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .stats-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
        }

        .header-actions {
            position: static;
            transform: none;
            margin-top: 1rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .filter-row {
            flex-direction: column;
        }

        .filter-group {
            width: 100%;
        }

        .custom-table thead {
            display: none;
        }

        .custom-table tbody tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
        }

        .custom-table tbody td {
            display: block;
            padding: 0.75rem 1rem;
            text-align: left;
        }

        .custom-table tbody td::before {
            content: attr(data-label);
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            color: var(--industrial-gray);
            display: block;
            margin-bottom: 0.25rem;
        }
    }
</style>
@endpush

@section('content')
<div class="all-users-page">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1>All Users</h1>
            <p>Manage user accounts and lock/unlock access</p>
            <div class="header-actions">
                <a href="{{ route('admin.dashboard') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i>Back to Dashboard
                </a>
            </div>
        </div>

        @if(session('success'))
        <div class="alert-custom success">
            <span><i class="fas fa-check-circle me-2"></i>{{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert-custom danger">
            <span><i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value total">{{ $stats['total_users'] }}</div>
                <div class="stat-label">Total Users</div>
            </div>
            <div class="stat-card">
                <div class="stat-value active">{{ $stats['active_users'] }}</div>
                <div class="stat-label">Active Users</div>
            </div>
            <div class="stat-card">
                <div class="stat-value locked">{{ $stats['locked_users'] }}</div>
                <div class="stat-label">Locked Users</div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filter-card">
            <form action="{{ route('admin.security.all-users') }}" method="GET">
                <div class="filter-row">
                    <div class="filter-group search">
                        <label>Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or email...">
                    </div>
                    <div class="filter-group">
                        <label>Role</label>
                        <select name="role">
                            <option value="">All Roles</option>
                            @foreach($roles as $value => $label)
                            <option value="{{ $value }}" {{ request('role') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Status</label>
                        <select name="status">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="locked" {{ request('status') == 'locked' ? 'selected' : '' }}>Locked</option>
                        </select>
                    </div>
                    <div class="filter-actions">
                        <button type="submit" class="btn-filter">
                            <i class="fas fa-filter"></i>Filter
                        </button>
                        <a href="{{ route('admin.security.all-users') }}" class="btn-clear">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Users Table -->
        <div class="users-card">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td data-label="Name">
                            <span class="user-name">{{ $user->name }}</span>
                        </td>
                        <td data-label="Email">
                            <span class="user-email">{{ $user->email }}</span>
                        </td>
                        <td data-label="Role">
                            <span class="role-badge">{{ str_replace('_', ' ', $user->current_role ?? '-') }}</span>
                        </td>
                        <td data-label="Status">
                            @if($user->locked_at)
                            <span class="status-badge locked">
                                <i class="fas fa-lock"></i>Locked
                            </span>
                            @else
                            <span class="status-badge active">
                                <i class="fas fa-check"></i>Active
                            </span>
                            @endif
                        </td>
                        <td data-label="Created">
                            <span class="created-date">{{ $user->created_at->format('M d, Y') }}</span>
                        </td>
                        <td data-label="Actions">
                            @if($user->id !== auth()->id())
                                @if($user->locked_at)
                                <form action="{{ route('admin.security.accounts.unlock', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to unlock this account?')">
                                    @csrf
                                    <button type="submit" class="btn-unlock">
                                        <i class="fas fa-unlock"></i>Unlock
                                    </button>
                                </form>
                                @else
                                <button type="button" class="btn-lock" data-bs-toggle="modal" data-bs-target="#lockModal{{ $user->id }}">
                                    <i class="fas fa-lock"></i>Lock
                                </button>
                                @endif
                            @else
                            <span class="your-account">(Your account)</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="fas fa-users"></i>
                                <p>No users found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            @if($users->hasPages())
            <div class="pagination-wrapper">
                {{ $users->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Lock Modals -->
@foreach($users as $user)
@if(!$user->locked_at && $user->id !== auth()->id())
<div class="modal fade modal-industrial" id="lockModal{{ $user->id }}" tabindex="-1" aria-labelledby="lockModalLabel{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.security.accounts.lock', $user) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="lockModalLabel{{ $user->id }}">
                        <i class="fas fa-lock me-2"></i>Lock Account
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to lock the account for <strong>{{ $user->name }}</strong> ({{ $user->email }})?</p>
                    <p class="text-muted small">This user will not be able to log in until their account is unlocked.</p>
                    <div class="form-group-custom">
                        <label for="reason{{ $user->id }}">Lock Reason <span class="required">*</span></label>
                        <input type="text" id="reason{{ $user->id }}" name="reason" required placeholder="Enter reason for locking this account...">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-modal-lock">
                        <i class="fas fa-lock"></i>Lock Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endforeach
@endsection
