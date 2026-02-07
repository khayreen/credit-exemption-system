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

    .announcements-page {
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
        display: flex;
        gap: 0.75rem;
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

    .btn-new {
        background: var(--uitm-amber);
        color: var(--industrial-dark);
        border: none;
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
    }

    .btn-new:hover {
        background: #d97706;
        color: var(--industrial-dark);
    }

    /* Alert Messages */
    .alert-custom {
        background: rgba(5, 150, 105, 0.08);
        border: 1px solid rgba(5, 150, 105, 0.2);
        border-left: 4px solid var(--success-color);
        border-radius: 8px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: var(--success-color);
    }

    /* Table Card */
    .table-card {
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

    .announcement-title {
        font-weight: 600;
        color: var(--industrial-dark);
        margin-bottom: 0.25rem;
    }

    .announcement-preview {
        font-size: 0.85rem;
        color: var(--industrial-gray);
    }

    /* Type Badges */
    .type-badge {
        display: inline-block;
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 500;
        text-transform: capitalize;
    }

    .type-badge.danger {
        background: rgba(220, 38, 38, 0.1);
        color: var(--danger-color);
    }

    .type-badge.warning {
        background: rgba(245, 158, 11, 0.1);
        color: #b45309;
    }

    .type-badge.success {
        background: rgba(5, 150, 105, 0.1);
        color: var(--success-color);
    }

    .type-badge.info {
        background: rgba(13, 148, 136, 0.1);
        color: var(--info-color);
    }

    /* Target Badges */
    .target-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 0.375rem;
    }

    .role-badge {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 500;
        text-transform: capitalize;
        background: var(--industrial-light);
        color: var(--industrial-gray);
    }

    .role-badge.all-users {
        background: rgba(30, 58, 138, 0.1);
        color: var(--uitm-blue);
    }

    /* Schedule */
    .schedule-text {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.85rem;
        color: var(--industrial-gray);
    }

    .schedule-text.always {
        color: var(--industrial-gray);
        font-style: italic;
    }

    /* Status Badge */
    .status-badge {
        display: inline-block;
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .status-badge.active {
        background: rgba(5, 150, 105, 0.1);
        color: var(--success-color);
    }

    .status-badge.inactive {
        background: var(--industrial-light);
        color: var(--industrial-gray);
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn-action {
        width: 34px;
        height: 34px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid;
        background: #fff;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-action.edit {
        border-color: var(--uitm-blue);
        color: var(--uitm-blue);
    }

    .btn-action.edit:hover {
        background: var(--uitm-blue);
        color: #fff;
    }

    .btn-action.toggle-on {
        border-color: var(--success-color);
        color: var(--success-color);
    }

    .btn-action.toggle-on:hover {
        background: var(--success-color);
        color: #fff;
    }

    .btn-action.toggle-off {
        border-color: var(--warning-color);
        color: var(--warning-color);
    }

    .btn-action.toggle-off:hover {
        background: var(--warning-color);
        color: #fff;
    }

    .btn-action.delete {
        border-color: var(--danger-color);
        color: var(--danger-color);
    }

    .btn-action.delete:hover {
        background: var(--danger-color);
        color: #fff;
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

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
        }

        .header-actions {
            position: static;
            transform: none;
            margin-top: 1rem;
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
<div class="announcements-page">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1>Announcements</h1>
            <p>Manage system-wide announcements and banners</p>
            <div class="header-actions">
                <a href="{{ route('admin.dashboard') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i>Back
                </a>
                <a href="{{ route('admin.content.announcements.create') }}" class="btn-new">
                    <i class="fas fa-plus"></i>New Announcement
                </a>
            </div>
        </div>

        @if(session('success'))
        <div class="alert-custom">
            <span><i class="fas fa-check-circle me-2"></i>{{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <!-- Announcements Table -->
        <div class="table-card">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Target</th>
                        <th>Schedule</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($announcements as $announcement)
                    <tr>
                        <td data-label="Title">
                            <div class="announcement-title">{{ $announcement->title }}</div>
                            <div class="announcement-preview">{{ Str::limit($announcement->content, 50) }}</div>
                        </td>
                        <td data-label="Type">
                            <span class="type-badge {{ $announcement->type }}">
                                {{ ucfirst($announcement->type) }}
                            </span>
                        </td>
                        <td data-label="Target">
                            <div class="target-badges">
                                @if($announcement->target_roles)
                                    @foreach($announcement->target_roles as $role)
                                    <span class="role-badge">{{ ucfirst(str_replace('_', ' ', $role)) }}</span>
                                    @endforeach
                                @else
                                <span class="role-badge all-users">All Users</span>
                                @endif
                            </div>
                        </td>
                        <td data-label="Schedule">
                            @if($announcement->starts_at || $announcement->ends_at)
                            <span class="schedule-text">
                                {{ $announcement->starts_at ? $announcement->starts_at->format('M d') : 'Now' }}
                                -
                                {{ $announcement->ends_at ? $announcement->ends_at->format('M d') : 'Forever' }}
                            </span>
                            @else
                            <span class="schedule-text always">Always</span>
                            @endif
                        </td>
                        <td data-label="Status">
                            @if($announcement->is_active)
                            <span class="status-badge active">Active</span>
                            @else
                            <span class="status-badge inactive">Inactive</span>
                            @endif
                        </td>
                        <td data-label="Actions">
                            <div class="action-buttons">
                                <a href="{{ route('admin.content.announcements.edit', $announcement) }}" class="btn-action edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.content.announcements.toggle', $announcement) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn-action {{ $announcement->is_active ? 'toggle-off' : 'toggle-on' }}" title="{{ $announcement->is_active ? 'Deactivate' : 'Activate' }}">
                                        <i class="fas fa-{{ $announcement->is_active ? 'pause' : 'play' }}"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.content.announcements.destroy', $announcement) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action delete" title="Delete" onclick="return confirm('Delete this announcement?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="fas fa-bullhorn"></i>
                                <p>No announcements found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            @if($announcements->hasPages())
            <div class="pagination-wrapper">
                {{ $announcements->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
