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

    .reminder-page {
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
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .page-header h1 i {
        color: var(--uitm-amber);
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
        border-radius: 12px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
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

    .alert-custom.success {
        background: rgba(5, 150, 105, 0.08);
        border: 1px solid rgba(5, 150, 105, 0.2);
        border-left: 4px solid var(--success-color);
        color: var(--success-color);
    }

    .alert-custom.warning {
        background: rgba(234, 88, 12, 0.08);
        border: 1px solid rgba(234, 88, 12, 0.2);
        border-left: 4px solid var(--warning-color);
        color: var(--warning-color);
    }

    .alert-custom.danger {
        background: rgba(220, 38, 38, 0.08);
        border: 1px solid rgba(220, 38, 38, 0.2);
        border-left: 4px solid var(--danger-color);
        color: var(--danger-color);
    }

    .alert-custom ul {
        margin: 0.5rem 0 0 0;
        padding-left: 1.25rem;
    }

    .alert-custom .btn-close {
        margin-left: auto;
        opacity: 0.6;
    }

    /* Main Card */
    .main-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .main-card-header {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--uitm-blue-light) 100%);
        padding: 1.25rem 1.5rem;
        border-bottom: none;
    }

    .main-card-header h5 {
        color: #fff;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .main-card-body {
        padding: 1.5rem;
    }

    /* Form Elements */
    .form-label {
        font-weight: 600;
        color: var(--industrial-dark);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-label i {
        color: var(--uitm-blue);
    }

    .form-label .text-danger {
        color: var(--danger-color) !important;
    }

    .form-control {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.875rem 1rem;
        font-size: 0.95rem;
        font-family: 'IBM Plex Sans', sans-serif;
        transition: all 0.2s ease;
    }

    .form-control:focus {
        border-color: var(--uitm-blue);
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
        outline: none;
    }

    .form-control.is-invalid {
        border-color: var(--danger-color);
    }

    .form-control.is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    }

    .form-text {
        font-size: 0.8rem;
        color: var(--industrial-gray);
        margin-top: 0.5rem;
    }

    .invalid-feedback {
        color: var(--danger-color);
        font-size: 0.8rem;
        margin-top: 0.25rem;
    }

    /* Selection Section */
    .selection-section {
        margin-top: 1.5rem;
    }

    .select-all-wrapper {
        background: var(--industrial-light);
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 1rem 1.25rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .select-all-wrapper:hover {
        border-color: var(--uitm-blue-light);
        background: rgba(59, 130, 246, 0.05);
    }

    .form-check-input {
        width: 1.25rem;
        height: 1.25rem;
        border: 2px solid #cbd5e1;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .form-check-input:checked {
        background-color: var(--uitm-blue);
        border-color: var(--uitm-blue);
    }

    .form-check-input:focus {
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.15);
    }

    .form-check-input:indeterminate {
        background-color: var(--uitm-blue-light);
        border-color: var(--uitm-blue-light);
    }

    .form-check-label {
        font-weight: 600;
        color: var(--industrial-dark);
        cursor: pointer;
    }

    /* Resource Person Table */
    .rp-table-wrapper {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
    }

    .rp-table {
        margin: 0;
        font-size: 0.9rem;
    }

    .rp-table thead {
        background: var(--industrial-light);
    }

    .rp-table thead th {
        font-weight: 600;
        color: var(--industrial-dark);
        padding: 1rem;
        border-bottom: 2px solid #e2e8f0;
        white-space: nowrap;
    }

    .rp-table tbody tr {
        transition: background 0.15s ease;
    }

    .rp-table tbody tr:hover {
        background: rgba(59, 130, 246, 0.04);
    }

    .rp-table tbody td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .rp-table tbody tr:last-child td {
        border-bottom: none;
    }

    .rp-name {
        font-weight: 600;
        color: var(--industrial-dark);
        cursor: pointer;
    }

    .rp-name:hover {
        color: var(--uitm-blue);
    }

    .rp-email {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.8rem;
        color: var(--industrial-gray);
    }

    /* Badges */
    .badge-program {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--uitm-blue-light) 100%);
        color: #fff;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        font-family: 'IBM Plex Mono', monospace;
    }

    .badge-secondary {
        background: var(--industrial-gray);
        color: #fff;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .badge-verified {
        background: rgba(5, 150, 105, 0.1);
        color: var(--success-color);
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    .badge-unverified {
        background: rgba(234, 88, 12, 0.1);
        color: var(--warning-color);
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    /* Selection Counter */
    .selection-counter {
        background: var(--industrial-light);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        margin-top: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        color: var(--industrial-gray);
    }

    .selection-counter i {
        color: var(--info-color);
    }

    .selection-counter strong {
        color: var(--uitm-blue);
        font-family: 'IBM Plex Mono', monospace;
    }

    /* Email Preview */
    .preview-section {
        margin-top: 2rem;
    }

    .preview-card {
        background: var(--industrial-light);
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
    }

    .preview-card-header {
        background: #fff;
        padding: 1rem 1.25rem;
        border-bottom: 2px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .preview-card-header h6 {
        margin: 0;
        font-weight: 600;
        color: var(--industrial-dark);
    }

    .preview-card-header i {
        color: var(--uitm-blue);
    }

    .preview-card-body {
        padding: 1.5rem;
        background: #fff;
        margin: 1rem;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }

    .preview-subject {
        font-weight: 600;
        color: var(--industrial-dark);
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px dashed #e2e8f0;
    }

    .preview-subject span {
        color: var(--uitm-blue);
    }

    .preview-content {
        color: var(--industrial-gray);
        line-height: 1.7;
    }

    .preview-content p {
        margin-bottom: 0.75rem;
    }

    .preview-highlight {
        color: var(--uitm-blue);
        font-weight: 600;
    }

    .preview-note {
        background: rgba(245, 158, 11, 0.1);
        border-left: 3px solid var(--uitm-amber);
        padding: 0.75rem 1rem;
        margin-top: 1rem;
        border-radius: 0 6px 6px 0;
        font-size: 0.875rem;
        font-style: italic;
        color: var(--industrial-gray);
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e2e8f0;
    }

    .btn-cancel {
        background: #fff;
        color: var(--industrial-gray);
        border: 2px solid #e2e8f0;
        padding: 0.875rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.95rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-cancel:hover {
        background: var(--industrial-light);
        border-color: var(--industrial-gray);
        color: var(--industrial-dark);
    }

    .btn-submit {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--uitm-blue-light) 100%);
        color: #fff;
        border: none;
        padding: 0.875rem 2rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.95rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
        box-shadow: 0 4px 14px 0 rgba(30, 58, 138, 0.3);
    }

    .btn-submit:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px 0 rgba(30, 58, 138, 0.4);
        color: #fff;
    }

    .btn-submit:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    /* Side Panel Cards */
    .side-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .side-card-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .side-card-header.info {
        background: rgba(13, 148, 136, 0.08);
    }

    .side-card-header.info h6 {
        color: var(--info-color);
    }

    .side-card-header.info i {
        color: var(--info-color);
    }

    .side-card-header.stats {
        background: var(--industrial-light);
    }

    .side-card-header.stats h6 {
        color: var(--industrial-dark);
    }

    .side-card-header.stats i {
        color: var(--uitm-blue);
    }

    .side-card-header h6 {
        margin: 0;
        font-weight: 600;
    }

    .side-card-body {
        padding: 1.25rem;
    }

    .side-card-body p {
        color: var(--industrial-gray);
        font-size: 0.9rem;
        line-height: 1.6;
        margin-bottom: 1rem;
    }

    .side-card-body h6 {
        font-weight: 600;
        color: var(--industrial-dark);
        margin-bottom: 0.75rem;
        font-size: 0.9rem;
    }

    .side-card-body ul {
        color: var(--industrial-gray);
        font-size: 0.875rem;
        padding-left: 1.25rem;
        margin-bottom: 1rem;
    }

    .side-card-body ul li {
        margin-bottom: 0.5rem;
    }

    .side-card-body ul:last-child {
        margin-bottom: 0;
    }

    /* Stats Row */
    .stats-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .stats-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .stats-row:first-child {
        padding-top: 0;
    }

    .stats-label {
        color: var(--industrial-gray);
        font-size: 0.875rem;
    }

    .stats-value {
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 600;
        font-size: 1rem;
    }

    .stats-value.total {
        color: var(--industrial-dark);
    }

    .stats-value.verified {
        color: var(--success-color);
    }

    .stats-value.unverified {
        color: var(--warning-color);
    }

    /* Empty State */
    .empty-state {
        background: rgba(234, 88, 12, 0.05);
        border: 2px dashed rgba(234, 88, 12, 0.3);
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
    }

    .empty-state i {
        font-size: 2.5rem;
        color: var(--warning-color);
        margin-bottom: 1rem;
    }

    .empty-state p {
        color: var(--industrial-gray);
        margin: 0;
    }

    /* Responsive */
    @media (max-width: 992px) {
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

        .action-buttons .btn-cancel,
        .action-buttons .btn-submit {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 768px) {
        .reminder-page {
            padding: 1rem 0;
        }

        .main-card-body {
            padding: 1rem;
        }

        .rp-table-wrapper {
            margin: 0 -1rem;
            border-radius: 0;
            border-left: none;
            border-right: none;
        }

        .rp-table thead th,
        .rp-table tbody td {
            padding: 0.75rem;
        }
    }
</style>
@endpush

@section('content')
<div class="reminder-page">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1><i class="fas fa-bell"></i>Semester Reminder</h1>
            <p>Send reminders to Resource Persons to submit updated equivalency lists</p>
            <div class="header-actions">
                <a href="{{ route('hea.dashboard') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i>Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
        <div class="alert-custom success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        @if(session('warning'))
        <div class="alert-custom warning">
            <i class="fas fa-exclamation-triangle"></i>
            <span>{{ session('warning') }}</span>
        </div>
        @endif

        @if(session('error'))
        <div class="alert-custom danger">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
        </div>
        @endif

        @if($errors->any())
        <div class="alert-custom danger">
            <div>
                <i class="fas fa-exclamation-circle"></i>
                <strong>Please fix the following errors:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        <div class="row">
            <!-- Main Form Column -->
            <div class="col-lg-8">
                <div class="main-card">
                    <div class="main-card-header">
                        <h5><i class="fas fa-paper-plane"></i>Send Semester Reminder</h5>
                    </div>
                    <div class="main-card-body">
                        <form action="{{ route('hea.semester_reminder.send') }}" method="POST" id="reminderForm">
                            @csrf

                            <!-- Semester Input -->
                            <div class="mb-4">
                                <label for="semester" class="form-label">
                                    <i class="fas fa-calendar-alt"></i>Semester <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       class="form-control @error('semester') is-invalid @enderror"
                                       id="semester"
                                       name="semester"
                                       value="{{ old('semester', $semesterSuggestion) }}"
                                       placeholder="e.g., Semester 1 2025/2026"
                                       required>
                                <div class="form-text">
                                    Enter the semester for which Resource Persons should submit their equivalency lists.
                                </div>
                                @error('semester')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Resource Persons Selection -->
                            <div class="selection-section">
                                <label class="form-label">
                                    <i class="fas fa-users"></i>Select Resource Persons <span class="text-danger">*</span>
                                </label>

                                @if($resourcePersons->isEmpty())
                                    <div class="empty-state">
                                        <i class="fas fa-user-slash"></i>
                                        <p><strong>No approved Resource Persons found.</strong><br>
                                        Please approve Resource Person registrations first.</p>
                                    </div>
                                @else
                                    <!-- Select All Checkbox -->
                                    <div class="select-all-wrapper">
                                        <input class="form-check-input" type="checkbox" id="selectAll">
                                        <label class="form-check-label" for="selectAll">
                                            Select All Resource Persons
                                        </label>
                                    </div>

                                    <!-- Resource Persons Table -->
                                    <div class="rp-table-wrapper">
                                        <table class="table rp-table">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" style="width: 50px;">
                                                        <i class="fas fa-check"></i>
                                                    </th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Program</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($resourcePersons as $rp)
                                                @php
                                                    $assignedProgram = $rp->assigned_program;
                                                    if (!$assignedProgram && is_array($rp->assigned_programs)) {
                                                        $assignedProgram = $rp->assigned_programs[0] ?? null;
                                                    }
                                                @endphp
                                                <tr>
                                                    <td class="text-center">
                                                        <input class="form-check-input rp-checkbox"
                                                               type="checkbox"
                                                               name="resource_persons[]"
                                                               value="{{ $rp->id }}"
                                                               id="rp_{{ $rp->id }}"
                                                               {{ in_array($rp->id, old('resource_persons', [])) ? 'checked' : '' }}>
                                                    </td>
                                                    <td>
                                                        <label for="rp_{{ $rp->id }}" class="rp-name">
                                                            {{ $rp->user->name ?? 'N/A' }}
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <span class="rp-email">{{ $rp->user->email ?? $rp->email ?? 'N/A' }}</span>
                                                    </td>
                                                    <td>
                                                        @if($assignedProgram)
                                                            <span class="badge-program">{{ $assignedProgram }}</span>
                                                        @else
                                                            <span class="badge-secondary">Not assigned</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($rp->user && $rp->user->hasVerifiedEmail())
                                                            <span class="badge-verified">
                                                                <i class="fas fa-check-circle"></i>Verified
                                                            </span>
                                                        @else
                                                            <span class="badge-unverified">
                                                                <i class="fas fa-clock"></i>Unverified
                                                            </span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="selection-counter">
                                        <i class="fas fa-info-circle"></i>
                                        <span>Selected: <strong id="selectedCount">0</strong> of {{ $resourcePersons->count() }} Resource Person(s)</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Email Preview -->
                            <div class="preview-section">
                                <div class="preview-card">
                                    <div class="preview-card-header">
                                        <i class="fas fa-eye"></i>
                                        <h6>Email Preview</h6>
                                    </div>
                                    <div class="preview-card-body">
                                        <div class="preview-subject">
                                            <strong>Subject:</strong> Action Required: Submit CS110 Equivalency List for [Program Code] - <span class="semester-preview">{{ $semesterSuggestion }}</span>
                                        </div>
                                        <div class="preview-content">
                                            <p>Dear [Resource Person Name],</p>
                                            <p>This is a reminder from the <span class="preview-highlight">Higher Education Authority (HEA)</span> regarding the upcoming semester.</p>
                                            <p><strong>Action Required:</strong> Please submit the updated CS110 Course Equivalency List for your assigned program.</p>
                                            <p><strong>Semester:</strong> <span class="preview-highlight semester-preview">{{ $semesterSuggestion }}</span></p>
                                            <div class="preview-note">
                                                Even if there are no changes from the previous semester, please submit the list to confirm it remains current.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            @if($resourcePersons->isNotEmpty())
                            <div class="action-buttons">
                                <button type="button" class="btn-cancel" onclick="window.history.back()">
                                    <i class="fas fa-times"></i>Cancel
                                </button>
                                <button type="submit" class="btn-submit" id="submitBtn" disabled>
                                    <i class="fas fa-paper-plane"></i>Send Reminders
                                </button>
                            </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>

            <!-- Side Panel Column -->
            <div class="col-lg-4">
                <!-- Info Card -->
                <div class="side-card">
                    <div class="side-card-header info">
                        <i class="fas fa-info-circle"></i>
                        <h6>About This Feature</h6>
                    </div>
                    <div class="side-card-body">
                        <p>Use this feature to remind Resource Persons to submit updated CS110 equivalency lists at the beginning of each semester.</p>

                        <h6>When to send reminders:</h6>
                        <ul>
                            <li>At the start of each new semester</li>
                            <li>Before the deadline for list submissions</li>
                            <li>When lists need urgent updates</li>
                        </ul>

                        <h6>What happens:</h6>
                        <ul>
                            <li>Selected Resource Persons receive an email notification</li>
                            <li>They also get an in-app notification</li>
                            <li>The action is logged in the audit trail</li>
                        </ul>
                    </div>
                </div>

                <!-- Quick Stats Card -->
                <div class="side-card">
                    <div class="side-card-header stats">
                        <i class="fas fa-chart-bar"></i>
                        <h6>Quick Stats</h6>
                    </div>
                    <div class="side-card-body">
                        <div class="stats-row">
                            <span class="stats-label">Total Resource Persons</span>
                            <span class="stats-value total">{{ $resourcePersons->count() }}</span>
                        </div>
                        <div class="stats-row">
                            <span class="stats-label">With Verified Email</span>
                            <span class="stats-value verified">{{ $resourcePersons->filter(fn($rp) => $rp->user && $rp->user->hasVerifiedEmail())->count() }}</span>
                        </div>
                        <div class="stats-row">
                            <span class="stats-label">Unverified Email</span>
                            <span class="stats-value unverified">{{ $resourcePersons->filter(fn($rp) => !$rp->user || !$rp->user->hasVerifiedEmail())->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const rpCheckboxes = document.querySelectorAll('.rp-checkbox');
    const selectedCountSpan = document.getElementById('selectedCount');
    const submitBtn = document.getElementById('submitBtn');
    const semesterInput = document.getElementById('semester');
    const semesterPreviews = document.querySelectorAll('.semester-preview');

    // Update selected count and button state
    function updateSelectionState() {
        const checkedCount = document.querySelectorAll('.rp-checkbox:checked').length;

        if (selectedCountSpan) {
            selectedCountSpan.textContent = checkedCount;
        }

        // Enable/disable submit button based on selection
        if (submitBtn) {
            if (checkedCount > 0) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i>Send Reminders (' + checkedCount + ')';
            } else {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i>Send Reminders';
            }
        }

        // Update "Select All" checkbox state
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = checkedCount === rpCheckboxes.length && rpCheckboxes.length > 0;
            selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < rpCheckboxes.length;
        }
    }

    // Select All functionality
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            rpCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateSelectionState();
        });
    }

    // Individual checkbox change
    rpCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectionState);
    });

    // Update semester preview
    if (semesterInput) {
        semesterInput.addEventListener('input', function() {
            semesterPreviews.forEach(preview => {
                preview.textContent = this.value || 'Semester';
            });
        });
    }

    // Initial state
    updateSelectionState();

    // Form submission confirmation
    const reminderForm = document.getElementById('reminderForm');
    if (reminderForm) {
        reminderForm.addEventListener('submit', function(e) {
            const checkedCount = document.querySelectorAll('.rp-checkbox:checked').length;
            const semester = semesterInput ? semesterInput.value : '';

            if (!confirm('Are you sure you want to send reminders to ' + checkedCount + ' Resource Person(s) for ' + semester + '?')) {
                e.preventDefault();
            }
        });
    }
});
</script>
@endpush
