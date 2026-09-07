@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<style>
    :root {
        --uitm-blue: #1e3a8a;
        --uitm-blue-light: #3b82f6;
        --uitm-amber: #f59e0b;
        --uitm-amber-light: #fbbf24;
        --industrial-dark: #0f172a;
        --industrial-gray: #334155;
        --industrial-light: #f1f5f9;
        --success-green: #059669;
        --danger-red: #dc2626;
        --warning-orange: #ea580c;
        --teal: #0d9488;
    }

    body { font-family: 'IBM Plex Sans', -apple-system, BlinkMacSystemFont, sans-serif; }
    .font-mono { font-family: 'IBM Plex Mono', monospace; }

    /* ── Page Header ── */
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
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(245, 158, 11, 0.15) 0%, transparent 70%);
        border-radius: 50%;
    }

    .page-header::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: 20%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(59, 130, 246, 0.1) 0%, transparent 70%);
        border-radius: 50%;
    }

    .page-header h1 {
        color: #fff;
        font-weight: 700;
        font-size: 1.6rem;
        margin-bottom: 0.25rem;
        position: relative;
        z-index: 1;
    }

    .page-header .header-subtitle {
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.875rem;
        margin-bottom: 0;
        position: relative;
        z-index: 1;
    }

    .header-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 0.75rem;
        position: relative;
        z-index: 1;
    }

    .hdr-badge {
        padding: 0.35rem 0.85rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    .hdr-badge.draft {
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.25);
        color: #fff;
    }

    .hdr-badge.target {
        background: linear-gradient(135deg, var(--uitm-amber) 0%, var(--warning-orange) 100%);
        color: #fff;
        box-shadow: 0 3px 12px rgba(245, 158, 11, 0.3);
    }

    .hdr-badge.published {
        background: linear-gradient(135deg, var(--success-green) 0%, #10b981 100%);
        color: #fff;
        box-shadow: 0 3px 12px rgba(5, 150, 105, 0.3);
    }

    .btn-back {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #fff;
        padding: 0.5rem 1.1rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        text-decoration: none;
        position: relative;
        z-index: 1;
    }

    .btn-back:hover {
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
        transform: translateX(-3px);
    }

    /* ── Alerts ── */
    .alert-industrial {
        border-radius: 12px;
        border: none;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .alert-industrial.success {
        background: linear-gradient(135deg, rgba(5, 150, 105, 0.1) 0%, rgba(16, 185, 129, 0.05) 100%);
        border-left: 4px solid var(--success-green);
        color: #065f46;
    }

    .alert-industrial.danger {
        background: linear-gradient(135deg, rgba(220, 38, 38, 0.1) 0%, rgba(239, 68, 68, 0.05) 100%);
        border-left: 4px solid var(--danger-red);
        color: #991b1b;
    }

    .alert-industrial .alert-close {
        margin-left: auto;
        background: none;
        border: none;
        font-size: 1.25rem;
        line-height: 1;
        color: inherit;
        opacity: 0.5;
        cursor: pointer;
    }

    .alert-industrial .alert-close:hover { opacity: 1; }

    /* ── Rejection Alert ── */
    .rejection-banner {
        background: linear-gradient(135deg, rgba(220, 38, 38, 0.08) 0%, rgba(239, 68, 68, 0.03) 100%);
        border: 1px solid rgba(220, 38, 38, 0.15);
        border-left: 4px solid var(--danger-red);
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.5rem;
    }

    .rejection-banner h6 {
        color: var(--danger-red);
        font-weight: 700;
        margin-bottom: 0.4rem;
        font-size: 0.875rem;
    }

    .rejection-banner p { color: #7f1d1d; font-size: 0.85rem; margin-bottom: 0.15rem; }
    .rejection-banner small { color: #991b1b; }

    /* ── Info Card ── */
    .info-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        margin-bottom: 1.5rem;
        transition: box-shadow 0.3s ease;
    }

    .info-card:hover { box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05); }

    .info-card-header {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, #1e40af 100%);
        padding: 0.875rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .info-card-header i { color: var(--uitm-amber-light); font-size: 1rem; }
    .info-card-header h5 { margin: 0; color: #fff; font-weight: 600; font-size: 0.9rem; }

    .info-card-body { padding: 1.5rem; }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
    }

    @media (max-width: 992px) { .info-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 576px) { .info-grid { grid-template-columns: 1fr; } }

    .info-item label {
        display: block;
        color: #94a3b8;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        margin-bottom: 0.35rem;
    }

    .info-item .info-value {
        color: var(--industrial-dark);
        font-weight: 600;
        font-size: 0.875rem;
        line-height: 1.4;
    }

    .inline-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.25rem 0.6rem;
        border-radius: 6px;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.02em;
    }

    .inline-badge.blue { background: rgba(30, 58, 138, 0.1); color: var(--uitm-blue); }
    .inline-badge.teal { background: rgba(13, 148, 136, 0.1); color: var(--teal); }
    .inline-badge.gray { background: var(--industrial-light); color: var(--industrial-gray); }
    .inline-badge.amber { background: rgba(245, 158, 11, 0.12); color: #b45309; }
    .inline-badge.green { background: rgba(5, 150, 105, 0.1); color: var(--success-green); }

    /* ── Mappings Card ── */
    .mappings-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        margin-bottom: 1.5rem;
        transition: box-shadow 0.3s ease;
    }

    .mappings-card:hover { box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05); }

    .mappings-header {
        background: linear-gradient(135deg, var(--success-green) 0%, #047857 100%);
        padding: 0.875rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .mappings-header-left {
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .mappings-header i { color: rgba(255, 255, 255, 0.85); font-size: 1rem; }
    .mappings-header h5 { margin: 0; color: #fff; font-weight: 600; font-size: 0.9rem; }

    .btn-add-mapping {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: #fff;
        padding: 0.4rem 0.9rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.775rem;
        transition: all 0.2s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    .btn-add-mapping:hover {
        background: rgba(255, 255, 255, 0.35);
        color: #fff;
        transform: translateY(-1px);
    }

    /* ── Data Table ── */
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table thead th {
        background: var(--industrial-light);
        padding: 0.8rem 1.25rem;
        text-align: left;
        font-weight: 600;
        font-size: 0.675rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #64748b;
        border-bottom: 2px solid #e2e8f0;
    }

    .data-table tbody td {
        padding: 0.875rem 1.25rem;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        font-size: 0.875rem;
    }

    .data-table tbody tr:last-child td { border-bottom: none; }
    .data-table tbody tr:hover { background: rgba(30, 58, 138, 0.015); }

    .course-code {
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 600;
        color: var(--uitm-blue);
        font-size: 0.85rem;
    }

    .course-name {
        color: #64748b;
        font-size: 0.775rem;
        line-height: 1.3;
    }

    .credit-chip {
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 600;
        font-size: 0.8rem;
        color: var(--industrial-gray);
        text-align: center;
    }

    .mapping-arrow {
        color: #94a3b8;
        font-size: 1.1rem;
    }

    .match-badge {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.75rem;
        padding: 0.3rem 0.6rem;
        border-radius: 6px;
        font-weight: 700;
    }

    .match-badge.high { background: rgba(5, 150, 105, 0.12); color: var(--success-green); }
    .match-badge.low { background: rgba(245, 158, 11, 0.12); color: #b45309; }

    .eligible-badge {
        padding: 0.25rem 0.55rem;
        border-radius: 6px;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.02em;
    }

    .eligible-badge.yes { background: rgba(5, 150, 105, 0.12); color: var(--success-green); }
    .eligible-badge.no { background: rgba(51, 65, 85, 0.1); color: var(--industrial-gray); }

    /* ── Action Buttons ── */
    .action-group {
        display: flex;
        gap: 0.4rem;
        justify-content: center;
    }

    .btn-action {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        font-size: 0.85rem;
        cursor: pointer;
    }

    .btn-action.edit {
        background: rgba(30, 58, 138, 0.1);
        color: var(--uitm-blue);
    }

    .btn-action.edit:hover {
        background: var(--uitm-blue);
        color: #fff;
        transform: scale(1.08);
    }

    .btn-action.delete {
        background: rgba(220, 38, 38, 0.1);
        color: var(--danger-red);
    }

    .btn-action.delete:hover {
        background: var(--danger-red);
        color: #fff;
        transform: scale(1.08);
    }

    /* ── Empty State ── */
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
    }

    .empty-state-icon {
        width: 72px;
        height: 72px;
        background: linear-gradient(135deg, rgba(5, 150, 105, 0.1) 0%, rgba(16, 185, 129, 0.05) 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.25rem;
        font-size: 2rem;
        color: var(--success-green);
    }

    .empty-state h5 { color: var(--industrial-dark); font-weight: 600; margin-bottom: 0.4rem; }
    .empty-state p { color: #94a3b8; font-size: 0.875rem; }

    /* ── Submit Action Card ── */
    .submit-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.5rem;
    }

    .submit-card .submit-info {
        color: var(--industrial-gray);
        font-size: 0.85rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-submit-hea {
        background: linear-gradient(135deg, var(--success-green) 0%, #10b981 100%);
        color: #fff;
        border: none;
        padding: 0.6rem 1.25rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.25s ease;
        box-shadow: 0 4px 15px rgba(5, 150, 105, 0.25);
        cursor: pointer;
    }

    .btn-submit-hea:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(5, 150, 105, 0.35);
        color: #fff;
    }

    .btn-submit-hea:disabled {
        opacity: 0.45;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
        background: #94a3b8;
    }

    /* ── History Card ── */
    .history-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .history-header {
        background: var(--industrial-gray);
        padding: 0.875rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .history-header i { color: rgba(255, 255, 255, 0.7); }
    .history-header h5 { margin: 0; color: #fff; font-weight: 600; font-size: 0.9rem; }

    .history-body { padding: 1.5rem; }

    .history-note {
        color: #94a3b8;
        font-size: 0.8rem;
        margin-bottom: 1rem;
    }

    .history-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.8125rem;
    }

    .history-table thead th {
        background: var(--industrial-light);
        padding: 0.65rem 1rem;
        font-weight: 600;
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
        border-bottom: 1px solid #e2e8f0;
    }

    .history-table tbody td {
        padding: 0.6rem 1rem;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .action-tag {
        padding: 0.2rem 0.5rem;
        border-radius: 4px;
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .action-tag.deleted { background: rgba(220, 38, 38, 0.1); color: var(--danger-red); }
    .action-tag.replaced { background: rgba(245, 158, 11, 0.1); color: #b45309; }

    /* ── Modal Styling ── */
    .modal-content {
        border: none;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 25px 60px rgba(0, 0, 0, 0.15);
    }

    .modal-header {
        padding: 1.15rem 1.5rem;
        border-bottom: none;
    }

    .modal-header.header-blue {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, #1e40af 100%);
        color: #fff;
    }

    .modal-header.header-green {
        background: linear-gradient(135deg, var(--success-green) 0%, #047857 100%);
        color: #fff;
    }

    .modal-title {
        font-weight: 700;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .modal-body { padding: 1.5rem; }

    .modal-body .form-label {
        font-weight: 600;
        font-size: 0.8rem;
        color: var(--industrial-dark);
        margin-bottom: 0.35rem;
    }

    .modal-body .form-control,
    .modal-body .form-select {
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 0.55rem 0.85rem;
        font-size: 0.85rem;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .modal-body .form-control:focus,
    .modal-body .form-select:focus {
        border-color: var(--uitm-blue-light);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .modal-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid #f1f5f9;
        gap: 0.5rem;
    }

    .modal-footer .btn {
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.8rem;
        padding: 0.5rem 1.15rem;
    }

    .modal-info-box {
        background: rgba(59, 130, 246, 0.06);
        border: 1px solid rgba(59, 130, 246, 0.15);
        border-radius: 10px;
        padding: 0.85rem 1rem;
        margin-bottom: 1.25rem;
        font-size: 0.8125rem;
        color: var(--uitm-blue);
    }

    .modal-info-box i { color: var(--uitm-blue-light); }

    .section-label {
        font-weight: 700;
        font-size: 0.775rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 0.75rem;
        padding-bottom: 0.4rem;
        border-bottom: 2px solid;
    }

    .section-label.primary { color: var(--uitm-blue); border-color: rgba(30, 58, 138, 0.2); }
    .section-label.success { color: var(--success-green); border-color: rgba(5, 150, 105, 0.2); }

    .modal-alert {
        border-radius: 10px;
        border: none;
        padding: 0.8rem 1rem;
        font-size: 0.8rem;
    }

    .modal-alert.alert-info {
        background: rgba(30, 58, 138, 0.06);
        border-left: 3px solid var(--uitm-blue);
        color: #1e40af;
    }

    @media (max-width: 768px) {
        .page-header h1 { font-size: 1.3rem; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">

    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <h1><i class="fas fa-edit me-2"></i>Edit CS110 Equivalency List - <span class="font-mono">{{ $list->program_code }}</span></h1>
                <p class="header-subtitle"><i class="fas fa-building me-1"></i>ONE continuous list per program &mdash; Edit anytime, submit for each semester</p>
                <div class="header-badges">
                    <span class="hdr-badge draft"><i class="fas fa-file-alt"></i> {{ $list->status_display }}</span>
                    @if($list->target_semester)
                        <span class="hdr-badge target"><i class="fas fa-bullseye"></i> Target: {{ $list->target_semester }}</span>
                    @endif
                    @if($list->semester)
                        <span class="hdr-badge published"><i class="fas fa-check-circle"></i> Last Published: {{ $list->semester }}</span>
                    @endif
                </div>
            </div>
            <a href="{{ route('resource_person.equivalency_lists.published') }}" class="btn-back">
                <i class="fas fa-arrow-left me-2"></i>Back to Lists
            </a>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert-industrial success">
            <i class="fas fa-check-circle" style="margin-top: 2px;"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="alert-close" onclick="this.parentElement.remove()">&times;</button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert-industrial danger">
            <i class="fas fa-exclamation-triangle" style="margin-top: 2px;"></i>
            <div>
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
            <button type="button" class="alert-close" onclick="this.parentElement.remove()">&times;</button>
        </div>
    @endif

    <!-- Rejection Banner -->
    @if($list->isRejected())
        <div class="rejection-banner">
            <h6><i class="fas fa-times-circle me-1"></i>This list was rejected by HEA</h6>
            <p><strong>Reason:</strong> {{ $list->review_notes }}</p>
            <small>Please make the necessary changes and resubmit.</small>
        </div>
    @endif

    <!-- List Information Card -->
    <div class="info-card">
        <div class="info-card-header">
            <i class="fas fa-info-circle"></i>
            <h5>List Information</h5>
        </div>
        <div class="info-card-body">
            <div class="info-grid">
                <div class="info-item">
                    <label>Program</label>
                    <div class="info-value">{{ $list->program_name }}</div>
                    <div style="margin-top: 0.4rem;">
                        <span class="inline-badge blue"><i class="fas fa-code"></i> {{ $list->program_code }}</span>
                    </div>
                </div>
                <div class="info-item">
                    <label>Category &amp; Status</label>
                    <div style="display: flex; flex-direction: column; gap: 0.4rem;">
                        <span class="inline-badge teal"><i class="fas fa-layer-group"></i> Internal (CS110)</span>
                        <span class="inline-badge gray"><i class="fas fa-file-alt"></i> {{ $list->status_display }}</span>
                    </div>
                </div>
                <div class="info-item">
                    <label>Publication</label>
                    @if($list->semester)
                        <div class="info-value">{{ $list->semester }}</div>
                        <div class="course-name" style="margin-top: 0.15rem;">{{ $list->published_at ? $list->published_at->format('d M Y') : '-' }}</div>
                    @else
                        <div class="info-value" style="color: #94a3b8;">Never</div>
                    @endif
                </div>
                <div class="info-item">
                    <label>Target Semester</label>
                    @if($list->target_semester)
                        <span class="inline-badge amber"><i class="fas fa-bullseye"></i> {{ $list->target_semester }}</span>
                    @else
                        <div class="course-name">Set when submitting</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Course Mappings Section -->
    <div class="mappings-card">
        <div class="mappings-header">
            <div class="mappings-header-left">
                <i class="fas fa-exchange-alt"></i>
                <h5>Course Mappings ({{ $list->total_equivalencies }})</h5>
            </div>
            <button type="button" class="btn-add-mapping" data-bs-toggle="modal" data-bs-target="#addMappingModal">
                <i class="fas fa-plus-circle"></i> Add Mapping
            </button>
        </div>

        @if($list->courseEquivalencies->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <h5>No Course Mappings Yet</h5>
                <p class="mb-3">Add course mappings from CS110 diploma courses to <span class="font-mono fw-bold">{{ $list->program_code }}</span> degree courses.</p>
                <button type="button" class="btn-submit-hea" data-bs-toggle="modal" data-bs-target="#addMappingModal">
                    <i class="fas fa-plus-circle"></i> Add First Mapping
                </button>
            </div>
        @else
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>CS110 Diploma Course</th>
                            <th style="text-align: center;">Credit</th>
                            <th style="width: 40px;"></th>
                            <th>{{ $list->program_code }} Degree Course</th>
                            <th style="text-align: center;">Credit</th>
                            <th style="text-align: center;">Match %</th>
                            <th style="text-align: center;">Eligible</th>
                            <th style="text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list->courseEquivalencies->sortBy('diploma_course_code') as $equiv)
                        <tr>
                            <td>
                                <div class="course-code">{{ $equiv->diploma_course_code }}</div>
                                <div class="course-name">{{ $equiv->diploma_course_name }}</div>
                            </td>
                            <td class="credit-chip" style="text-align: center;">{{ $equiv->diploma_credit_hour }}</td>
                            <td style="text-align: center;">
                                <i class="fas fa-arrow-circle-right mapping-arrow"></i>
                            </td>
                            <td>
                                <div class="course-code">{{ $equiv->degree_course_code }}</div>
                                <div class="course-name">{{ $equiv->degree_course_name }}</div>
                            </td>
                            <td class="credit-chip" style="text-align: center;">{{ $equiv->degree_credit_hour }}</td>
                            <td style="text-align: center;">
                                <span class="match-badge {{ $equiv->match_percentage >= 80 ? 'high' : 'low' }}">
                                    {{ number_format($equiv->match_percentage, 0) }}%
                                </span>
                            </td>
                            <td style="text-align: center;">
                                @if($equiv->is_eligible)
                                    <span class="eligible-badge yes">Yes</span>
                                @else
                                    <span class="eligible-badge no">No</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-group">
                                    <button class="btn-action edit" onclick="editMapping('{{ $equiv->id }}', {{ json_encode($equiv) }})" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn-action delete" onclick="deleteMapping('{{ $equiv->id }}')" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Submit Action Card -->
    <div class="submit-card">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <p class="submit-info">
                <i class="fas fa-info-circle"></i>
                @if($list->canBeSubmitted())
                    Ready to submit to HEA for review and endorsement
                @else
                    Add at least one course mapping to submit
                @endif
            </p>
            @if($list->canBeSubmitted())
                <button type="button" class="btn-submit-hea" data-bs-toggle="modal" data-bs-target="#submitModal">
                    <i class="fas fa-paper-plane"></i> Submit to HEA
                </button>
            @else
                <button type="button" class="btn-submit-hea" disabled>
                    <i class="fas fa-paper-plane"></i> Submit to HEA
                </button>
            @endif
        </div>
    </div>

    <!-- Course Mapping History -->
    @if($list->courseEquivalencyHistory && $list->courseEquivalencyHistory->count() > 0)
    <div class="history-card">
        <div class="history-header">
            <i class="fas fa-history"></i>
            <h5>Course Mapping History ({{ $list->courseEquivalencyHistory->count() }})</h5>
        </div>
        <div class="history-body">
            <p class="history-note">
                <i class="fas fa-info-circle me-1"></i>
                Previously deleted or replaced course mappings are archived here for record-keeping.
            </p>
            <div class="table-responsive">
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Diploma Course</th>
                            <th>Degree Course</th>
                            <th>Match %</th>
                            <th>Archived By</th>
                            <th>Archived At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list->courseEquivalencyHistory->sortByDesc('archived_at') as $history)
                        <tr>
                            <td>
                                <span class="action-tag {{ $history->action }}">
                                    {{ ucfirst($history->action) }}
                                </span>
                            </td>
                            <td>
                                <span class="font-mono fw-bold" style="font-size: 0.8rem; color: var(--uitm-blue);">{{ $history->diploma_course_code }}</span><br>
                                <span class="course-name">{{ Str::limit($history->diploma_course_name, 30) }}</span>
                            </td>
                            <td>
                                <span class="font-mono fw-bold" style="font-size: 0.8rem; color: var(--uitm-blue);">{{ $history->degree_course_code }}</span><br>
                                <span class="course-name">{{ Str::limit($history->degree_course_name, 30) }}</span>
                            </td>
                            <td><span class="font-mono" style="font-size: 0.8rem;">{{ number_format($history->match_percentage, 0) }}%</span></td>
                            <td><span style="font-size: 0.8rem;">{{ $history->archivedBy->name ?? 'System' }}</span></td>
                            <td><span class="course-name">{{ $history->archived_at->format('d M Y H:i') }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Add Mapping Modal -->
<div class="modal fade" id="addMappingModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header header-green">
                <h5 class="modal-title"><i class="fas fa-plus-circle"></i> Add Course Mapping</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('resource_person.equivalency_lists.add_mapping', $programCode) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="modal-info-box">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>CS110 Internal Mapping:</strong> Map CS110 diploma courses to <span class="font-mono fw-bold">{{ $list->program_code }}</span> degree courses
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="section-label primary">CS110 Diploma Course</div>
                            <div class="mb-3">
                                <label class="form-label">Course Code <span class="text-danger">*</span></label>
                                <input type="text" name="diploma_course_code" class="form-control" placeholder="CSC126" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Course Name <span class="text-danger">*</span></label>
                                <input type="text" name="diploma_course_name" class="form-control" placeholder="Fundamentals of Algorithms" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Credit Hours <span class="text-danger">*</span></label>
                                <input type="number" name="diploma_credit_hour" class="form-control font-mono" min="1" max="10" value="3" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="section-label success">{{ $list->program_code }} Degree Course</div>
                            <div class="mb-3">
                                <label class="form-label">Course Code <span class="text-danger">*</span></label>
                                <input type="text" name="degree_course_code" class="form-control" placeholder="CSC402" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Course Name <span class="text-danger">*</span></label>
                                <input type="text" name="degree_course_name" class="form-control" placeholder="Programming I" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Credit Hours <span class="text-danger">*</span></label>
                                <input type="number" name="degree_credit_hour" class="form-control font-mono" min="1" max="10" value="3" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Match Percentage <span class="text-danger">*</span></label>
                                <input type="number" name="match_percentage" class="form-control font-mono" min="0" max="100" value="85" required>
                                <small class="text-muted">Recommended: &ge;80% for eligibility</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Eligible for Exemption</label>
                                <select name="is_eligible" class="form-select" required>
                                    <option value="1">Yes - Eligible</option>
                                    <option value="0">No - Not Eligible</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-plus-circle me-1"></i>Add Mapping</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Submit to HEA Modal -->
<div class="modal fade" id="submitModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header header-green">
                <h5 class="modal-title"><i class="fas fa-paper-plane"></i> Submit to HEA</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('resource_person.equivalency_lists.submit', $programCode) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="modal-info-box">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong><span class="font-mono">{{ $list->total_equivalencies }}</span> course mappings</strong> will be submitted to HEA for review and endorsement.
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Target Semester <span class="text-danger">*</span></label>
                        <input type="text" name="target_semester" class="form-control" placeholder="e.g., SESI 2 2025/2026" required>
                        <small class="text-muted">Format: SESI [number] [Academic Year] (e.g., SESI 2 2025/2026)</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Submission Notes (Optional)</label>
                        <textarea name="submission_notes" class="form-control" rows="3" placeholder="Add any notes or comments for HEA review..."></textarea>
                    </div>

                    <div class="modal-alert alert-info">
                        <strong>After submission:</strong>
                        <ol class="mb-0 mt-1" style="padding-left: 1.25rem;">
                            <li>HEA will review your mappings for the <strong>target semester</strong></li>
                            <li>HEA will endorse or reject the list</li>
                            <li>If endorsed, HEA will publish as PDF for the specified semester</li>
                            <li>After publication, list reverts to DRAFT for next semester&rsquo;s updates</li>
                        </ol>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-paper-plane me-1"></i>Submit to HEA
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Mapping Modal -->
<div class="modal fade" id="editMappingModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header header-blue">
                <h5 class="modal-title"><i class="fas fa-edit"></i> Edit Course Mapping</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editMappingForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="modal-info-box">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Update CS110 Mapping:</strong> Modify the course equivalency details
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="section-label primary">CS110 Diploma Course</div>
                            <div class="mb-3">
                                <label class="form-label">Course Code <span class="text-danger">*</span></label>
                                <input type="text" id="edit_diploma_course_code" name="diploma_course_code" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Course Name <span class="text-danger">*</span></label>
                                <input type="text" id="edit_diploma_course_name" name="diploma_course_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Credit Hours <span class="text-danger">*</span></label>
                                <input type="number" id="edit_diploma_credit_hour" name="diploma_credit_hour" class="form-control font-mono" min="1" max="10" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="section-label success">{{ $list->program_code }} Degree Course</div>
                            <div class="mb-3">
                                <label class="form-label">Course Code <span class="text-danger">*</span></label>
                                <input type="text" id="edit_degree_course_code" name="degree_course_code" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Course Name <span class="text-danger">*</span></label>
                                <input type="text" id="edit_degree_course_name" name="degree_course_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Credit Hours <span class="text-danger">*</span></label>
                                <input type="number" id="edit_degree_credit_hour" name="degree_credit_hour" class="form-control font-mono" min="1" max="10" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Match Percentage <span class="text-danger">*</span></label>
                                <input type="number" id="edit_match_percentage" name="match_percentage" class="form-control font-mono" min="0" max="100" required>
                                <small class="text-muted">Recommended: &ge;80% for eligibility</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Eligible for Exemption</label>
                                <select id="edit_is_eligible" name="is_eligible" class="form-select" required>
                                    <option value="1">Yes - Eligible</option>
                                    <option value="0">No - Not Eligible</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Update Mapping</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function editMapping(mappingId, mapping) {
    document.getElementById('edit_diploma_course_code').value = mapping.diploma_course_code;
    document.getElementById('edit_diploma_course_name').value = mapping.diploma_course_name;
    document.getElementById('edit_diploma_credit_hour').value = mapping.diploma_credit_hour;
    document.getElementById('edit_degree_course_code').value = mapping.degree_course_code;
    document.getElementById('edit_degree_course_name').value = mapping.degree_course_name;
    document.getElementById('edit_degree_credit_hour').value = mapping.degree_credit_hour;
    document.getElementById('edit_match_percentage').value = mapping.match_percentage;
    document.getElementById('edit_is_eligible').value = mapping.is_eligible ? '1' : '0';

    document.getElementById('editMappingForm').action = `/resource-person/cs110-lists/{{ $programCode }}/mappings/${mappingId}`;

    const editModal = new bootstrap.Modal(document.getElementById('editMappingModal'));
    editModal.show();
}

function deleteMapping(mappingId) {
    if (confirm('Are you sure you want to delete this course mapping?\n\nThis mapping will be moved to history and can be viewed later.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/resource-person/cs110-lists/{{ $programCode }}/mappings/${mappingId}`;

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';

        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';

        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
