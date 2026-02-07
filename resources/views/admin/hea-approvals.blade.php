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

    .hea-approvals-page {
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
    .alert-success-custom {
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

    .alert-danger-custom {
        background: rgba(220, 38, 38, 0.08);
        border: 1px solid rgba(220, 38, 38, 0.2);
        border-left: 4px solid var(--danger-color);
        border-radius: 8px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        color: var(--danger-color);
    }

    .alert-danger-custom ul {
        margin: 0;
        padding-left: 1.25rem;
    }

    /* Main Card */
    .approvals-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .card-header-warning {
        background: linear-gradient(135deg, var(--uitm-amber) 0%, #d97706 100%);
        padding: 1.25rem 1.5rem;
    }

    .card-header-warning h5 {
        color: var(--industrial-dark);
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .card-body-custom {
        padding: 1.5rem;
    }

    /* Status Alerts */
    .status-alert {
        border-radius: 8px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .status-alert.info {
        background: rgba(13, 148, 136, 0.08);
        border: 1px solid rgba(13, 148, 136, 0.2);
        color: var(--info-color);
    }

    .status-alert.warning {
        background: rgba(245, 158, 11, 0.08);
        border: 1px solid rgba(245, 158, 11, 0.2);
        color: #b45309;
    }

    .status-alert i {
        font-size: 1.25rem;
    }

    .status-alert strong {
        font-weight: 600;
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

    .custom-table thead th:first-child {
        border-radius: 8px 0 0 0;
    }

    .custom-table thead th:last-child {
        border-radius: 0 8px 0 0;
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
        font-size: 0.9rem;
        color: var(--industrial-gray);
    }

    .registration-date {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.875rem;
        color: var(--industrial-gray);
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn-approve {
        background: rgba(5, 150, 105, 0.1);
        color: var(--success-color);
        border: 1px solid rgba(5, 150, 105, 0.2);
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-approve:hover {
        background: var(--success-color);
        color: #fff;
        border-color: var(--success-color);
    }

    .btn-reject {
        background: rgba(220, 38, 38, 0.1);
        color: var(--danger-color);
        border: 1px solid rgba(220, 38, 38, 0.2);
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-reject:hover {
        background: var(--danger-color);
        color: #fff;
        border-color: var(--danger-color);
    }

    /* Modal Styling */
    .modal-industrial .modal-content {
        border: none;
        border-radius: 16px;
        overflow: hidden;
    }

    .modal-industrial .modal-header {
        padding: 1.25rem 1.5rem;
        border-bottom: none;
    }

    .modal-industrial .modal-header.success {
        background: linear-gradient(135deg, var(--success-color) 0%, #047857 100%);
    }

    .modal-industrial .modal-header.danger {
        background: linear-gradient(135deg, var(--danger-color) 0%, #b91c1c 100%);
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

    .privileges-box {
        background: rgba(245, 158, 11, 0.08);
        border: 1px solid rgba(245, 158, 11, 0.2);
        border-radius: 8px;
        padding: 1rem 1.25rem;
    }

    .privileges-box strong {
        color: #b45309;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.75rem;
    }

    .privileges-box ul {
        margin: 0;
        padding-left: 1.25rem;
        color: var(--industrial-gray);
    }

    .privileges-box li {
        margin-bottom: 0.375rem;
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

    .btn-modal-approve {
        background: var(--success-color);
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

    .btn-modal-approve:hover {
        background: #047857;
    }

    .btn-modal-reject {
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

    .btn-modal-reject:hover {
        background: #b91c1c;
    }

    /* Form Controls */
    .form-group-custom {
        margin-bottom: 1rem;
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

    .form-group-custom textarea {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.9rem;
        resize: vertical;
        transition: all 0.2s ease;
    }

    .form-group-custom textarea:focus {
        outline: none;
        border-color: var(--uitm-blue);
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
    }

    .form-group-custom small {
        display: block;
        margin-top: 0.375rem;
        color: var(--industrial-gray);
        font-size: 0.8rem;
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

        .action-buttons {
            flex-direction: column;
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
<div class="hea-approvals-page">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1>HEA Personnel Approvals</h1>
            <p>Review and approve HEA staff registrations</p>
            <div class="header-actions">
                <a href="{{ route('admin.dashboard') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i>Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Main Card -->
        <div class="approvals-card">
            <div class="card-header-warning">
                <h5><i class="fas fa-user-clock"></i>Pending HEA Registrations</h5>
            </div>

            <div class="card-body-custom">
                @if(session('success'))
                    <div class="alert-success-custom">
                        <span><i class="fas fa-check-circle me-2"></i>{{ session('success') }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert-danger-custom">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if($pendingHeaUsers->isEmpty())
                    <div class="status-alert info">
                        <i class="fas fa-check-circle"></i>
                        <span>No pending HEA registration requests.</span>
                    </div>
                @else
                    <div class="status-alert warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span><strong>Action Required:</strong> {{ $pendingHeaUsers->count() }} pending HEA registration(s) awaiting review</span>
                    </div>

                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Registration Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingHeaUsers as $pendingUser)
                            <tr>
                                <td data-label="Name">
                                    <span class="user-name">{{ $pendingUser->name }}</span>
                                </td>
                                <td data-label="Email">
                                    <span class="user-email">{{ $pendingUser->email }}</span>
                                </td>
                                <td data-label="Registration Date">
                                    <span class="registration-date">{{ $pendingUser->created_at->format('M j, Y g:i A') }}</span>
                                </td>
                                <td data-label="Actions">
                                    <div class="action-buttons">
                                        <button type="button" class="btn-approve"
                                                data-bs-toggle="modal"
                                                data-bs-target="#approveModal{{ $pendingUser->id }}">
                                            <i class="fas fa-check"></i>Approve
                                        </button>
                                        <button type="button" class="btn-reject"
                                                data-bs-toggle="modal"
                                                data-bs-target="#rejectModal{{ $pendingUser->id }}">
                                            <i class="fas fa-times"></i>Reject
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Modals --}}
@if(isset($pendingHeaUsers) && $pendingHeaUsers->isNotEmpty())
    @foreach($pendingHeaUsers as $pendingUser)
    {{-- Approve Modal --}}
    <div class="modal modal-industrial" id="approveModal{{ $pendingUser->id }}" tabindex="-1" aria-labelledby="approveModalLabel{{ $pendingUser->id }}" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header success">
                    <h5 class="modal-title" id="approveModalLabel{{ $pendingUser->id }}">
                        <i class="fas fa-user-check me-2"></i>Approve HEA Registration
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Approve <strong>{{ $pendingUser->name }}</strong> ({{ $pendingUser->email }}) as HEA Personnel?</p>
                    <p class="text-muted">They will receive an email notification and can login immediately.</p>

                    <div class="privileges-box">
                        <strong><i class="fas fa-shield-alt"></i>HEA Personnel Privileges:</strong>
                        <ul>
                            <li>Approve/reject user registrations</li>
                            <li>Assign programs to staff</li>
                            <li>Manage equivalency lists</li>
                            <li>Full system access</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('admin.hea.approve', $pendingUser) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn-modal-approve">
                            <i class="fas fa-check"></i>Approve
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Reject Modal --}}
    <div class="modal modal-industrial" id="rejectModal{{ $pendingUser->id }}" tabindex="-1" aria-labelledby="rejectModalLabel{{ $pendingUser->id }}" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header danger">
                    <h5 class="modal-title" id="rejectModalLabel{{ $pendingUser->id }}">
                        <i class="fas fa-user-times me-2"></i>Reject HEA Registration
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.hea.reject', $pendingUser) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>Reject <strong>{{ $pendingUser->name }}</strong> ({{ $pendingUser->email }})?</p>

                        <div class="form-group-custom">
                            <label>Reason for Rejection <span class="required">*</span></label>
                            <textarea name="rejection_reason" rows="3" required
                                      placeholder="Provide a clear reason for rejection..."></textarea>
                            <small>This reason will be sent to the applicant.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-modal-reject">
                            <i class="fas fa-times"></i>Reject
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
@endif
@endsection
