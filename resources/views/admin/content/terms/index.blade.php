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

    .terms-page {
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

    /* Current Version Card */
    .current-version-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .current-header {
        background: linear-gradient(135deg, var(--success-color) 0%, #047857 100%);
        padding: 1rem 1.5rem;
    }

    .current-header h5 {
        color: #fff;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .current-body {
        padding: 1.5rem;
    }

    .version-info {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .info-item {
        display: flex;
        flex-direction: column;
    }

    .info-item.actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
    }

    .info-label {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--industrial-gray);
        margin-bottom: 0.375rem;
    }

    .info-value {
        font-weight: 600;
        color: var(--industrial-dark);
        font-family: 'IBM Plex Mono', monospace;
    }

    .divider {
        height: 1px;
        background: #e2e8f0;
        margin-bottom: 1.5rem;
    }

    .terms-preview {
        max-height: 300px;
        overflow-y: auto;
        padding: 1rem;
        background: var(--industrial-light);
        border-radius: 8px;
        font-size: 0.9rem;
        line-height: 1.6;
        color: var(--industrial-gray);
    }

    .terms-preview .truncated {
        color: var(--industrial-gray);
        font-style: italic;
        margin-top: 1rem;
    }

    .btn-edit {
        background: rgba(245, 158, 11, 0.1);
        color: #b45309;
        border: 1px solid rgba(245, 158, 11, 0.2);
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-edit:hover {
        background: var(--uitm-amber);
        color: var(--industrial-dark);
        border-color: var(--uitm-amber);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem;
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
    }

    .empty-icon i {
        font-size: 2rem;
        color: var(--industrial-gray);
    }

    .empty-state p {
        color: var(--industrial-gray);
        margin-bottom: 1.5rem;
    }

    .btn-create {
        background: var(--uitm-blue);
        color: #fff;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
    }

    .btn-create:hover {
        background: var(--uitm-blue-light);
        color: #fff;
    }

    /* History Card */
    .history-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .history-header {
        background: var(--industrial-light);
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .history-header h5 {
        color: var(--industrial-dark);
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .history-header h5 i {
        color: var(--uitm-blue);
    }

    /* Table */
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

    .version-number {
        font-weight: 600;
        font-family: 'IBM Plex Mono', monospace;
        color: var(--industrial-dark);
    }

    .date-value {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.875rem;
        color: var(--industrial-gray);
    }

    .status-badge {
        display: inline-block;
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .status-badge.current {
        background: rgba(5, 150, 105, 0.1);
        color: var(--success-color);
    }

    .status-badge.archived {
        background: var(--industrial-light);
        color: var(--industrial-gray);
    }

    .creator-name {
        font-size: 0.9rem;
        color: var(--industrial-dark);
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

    .btn-action.activate {
        border-color: var(--success-color);
        color: var(--success-color);
    }

    .btn-action.activate:hover {
        background: var(--success-color);
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

    /* Empty Table */
    .empty-table {
        text-align: center;
        padding: 3rem;
        color: var(--industrial-gray);
    }

    /* Responsive */
    @media (max-width: 992px) {
        .version-info {
            grid-template-columns: repeat(2, 1fr);
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

        .version-info {
            grid-template-columns: 1fr;
        }

        .info-item.actions {
            justify-content: flex-start;
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
<div class="terms-page">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1>Terms & Conditions</h1>
            <p>Manage terms and conditions versions</p>
            <div class="header-actions">
                <a href="{{ route('admin.dashboard') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i>Back
                </a>
                <a href="{{ route('admin.content.terms.create') }}" class="btn-new">
                    <i class="fas fa-plus"></i>New Version
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

        <!-- Current Version -->
        <div class="current-version-card">
            <div class="current-header">
                <h5><i class="fas fa-check-circle"></i>Current Active Version</h5>
            </div>
            <div class="current-body">
                @if($currentTerms)
                <div class="version-info">
                    <div class="info-item">
                        <span class="info-label">Version</span>
                        <span class="info-value">{{ $currentTerms->version }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Effective Date</span>
                        <span class="info-value">{{ $currentTerms->effective_date->format('M d, Y') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Last Updated</span>
                        <span class="info-value">{{ $currentTerms->updated_at->format('M d, Y H:i') }}</span>
                    </div>
                    <div class="info-item actions">
                        <a href="{{ route('admin.content.terms.edit', $currentTerms) }}" class="btn-edit">
                            <i class="fas fa-edit"></i>Edit
                        </a>
                    </div>
                </div>
                <div class="divider"></div>
                <div class="terms-preview">
                    {!! nl2br(e(Str::limit($currentTerms->content, 1000))) !!}
                    @if(strlen($currentTerms->content) > 1000)
                    <p class="truncated">... content truncated for preview</p>
                    @endif
                </div>
                @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <p>No active Terms & Conditions version. Create one to get started.</p>
                    <a href="{{ route('admin.content.terms.create') }}" class="btn-create">
                        <i class="fas fa-plus"></i>Create First Version
                    </a>
                </div>
                @endif
            </div>
        </div>

        <!-- Version History -->
        <div class="history-card">
            <div class="history-header">
                <h5><i class="fas fa-history"></i>Version History</h5>
            </div>
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Version</th>
                        <th>Effective Date</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($termsHistory as $terms)
                    <tr>
                        <td data-label="Version">
                            <span class="version-number">{{ $terms->version }}</span>
                        </td>
                        <td data-label="Effective Date">
                            <span class="date-value">{{ $terms->effective_date->format('M d, Y') }}</span>
                        </td>
                        <td data-label="Status">
                            @if($terms->is_current)
                            <span class="status-badge current">Current</span>
                            @else
                            <span class="status-badge archived">Archived</span>
                            @endif
                        </td>
                        <td data-label="Created By">
                            <span class="creator-name">{{ $terms->creator->name ?? 'System' }}</span>
                        </td>
                        <td data-label="Created At">
                            <span class="date-value">{{ $terms->created_at->format('M d, Y H:i') }}</span>
                        </td>
                        <td data-label="Actions">
                            <div class="action-buttons">
                                <a href="{{ route('admin.content.terms.edit', $terms) }}" class="btn-action edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if(!$terms->is_current)
                                <form action="{{ route('admin.content.terms.set-current', $terms) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn-action activate" title="Set as Current" onclick="return confirm('Set this as the current version?')">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.content.terms.destroy', $terms) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action delete" title="Delete" onclick="return confirm('Delete this version?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-table">No version history</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
