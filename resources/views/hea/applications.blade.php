@extends('layouts.app')

@push('styles')
<style>
    /* ============================================
       APPLICATION MONITORING - INDUSTRIAL DESIGN
       ============================================ */

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

    .app-monitoring {
        font-family: 'IBM Plex Sans', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    /* ---- Page Header ---- */
    .page-header-industrial {
        position: relative;
        background: linear-gradient(135deg, var(--industrial-dark) 0%, var(--uitm-blue) 50%, var(--industrial-gray) 100%);
        border-radius: 16px;
        padding: 2rem 2.25rem;
        margin-bottom: 1.75rem;
        overflow: hidden;
        color: #fff;
    }

    .page-header-industrial .header-grid-pattern {
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(rgba(255,255,255,0.04) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,0.04) 1px, transparent 1px);
        background-size: 24px 24px;
        pointer-events: none;
    }

    .page-header-industrial .header-amber-accent {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--uitm-amber), transparent 70%);
    }

    .page-header-industrial .header-inner {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .page-header-industrial .header-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .header-icon-box {
        width: 48px;
        height: 48px;
        background: rgba(245, 158, 11, 0.15);
        border: 1px solid rgba(245, 158, 11, 0.3);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--uitm-amber);
        font-size: 1.25rem;
    }

    .page-header-industrial h1 {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
        letter-spacing: -0.02em;
    }

    .page-header-industrial .header-subtitle {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.75rem;
        color: rgba(255,255,255,0.6);
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-top: 2px;
    }

    .btn-back-dashboard {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1.15rem;
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.8rem;
        font-weight: 600;
        color: rgba(255,255,255,0.85);
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-back-dashboard:hover {
        background: rgba(255,255,255,0.2);
        color: #fff;
        transform: translateY(-1px);
    }

    /* ---- Alert Messages ---- */
    .ind-alert {
        position: relative;
        padding: 1rem 1.25rem;
        border-radius: 10px;
        margin-bottom: 1rem;
        font-size: 0.875rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        border: none;
    }

    .ind-alert-success {
        background: #ecfdf5;
        color: #065f46;
        border-left: 4px solid var(--success);
    }

    .ind-alert-danger {
        background: #fef2f2;
        color: #991b1b;
        border-left: 4px solid var(--danger);
    }

    .ind-alert .btn-close {
        filter: none;
        opacity: 0.5;
    }

    .ind-alert .btn-close:hover {
        opacity: 1;
    }

    /* ---- Stats Grid ---- */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.25rem;
        margin-bottom: 1.75rem;
    }

    .stat-metric-card {
        background: #fff;
        border-radius: 12px;
        padding: 1.5rem;
        position: relative;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .stat-metric-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
    }

    .stat-metric-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
    }

    .stat-metric-card.stat-total::before { background: var(--uitm-blue); }
    .stat-metric-card.stat-pending::before { background: var(--uitm-amber); }
    .stat-metric-card.stat-review::before { background: var(--teal); }
    .stat-metric-card.stat-completed::before { background: var(--success); }

    .stat-metric-card .stat-icon-wrap {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        margin-bottom: 1rem;
    }

    .stat-total .stat-icon-wrap {
        background: rgba(30, 58, 138, 0.08);
        color: var(--uitm-blue);
    }

    .stat-pending .stat-icon-wrap {
        background: rgba(245, 158, 11, 0.1);
        color: var(--uitm-amber);
    }

    .stat-review .stat-icon-wrap {
        background: rgba(13, 148, 136, 0.08);
        color: var(--teal);
    }

    .stat-completed .stat-icon-wrap {
        background: rgba(5, 150, 105, 0.08);
        color: var(--success);
    }

    .stat-metric-card .stat-value {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 1.75rem;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 0.25rem;
    }

    .stat-total .stat-value { color: var(--uitm-blue); }
    .stat-pending .stat-value { color: var(--uitm-amber); }
    .stat-review .stat-value { color: var(--teal); }
    .stat-completed .stat-value { color: var(--success); }

    .stat-metric-card .stat-label {
        font-size: 0.8rem;
        font-weight: 500;
        color: #64748b;
    }

    /* ---- Filter Card ---- */
    .filter-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .filter-card-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem 1.5rem;
        background: var(--industrial-light);
        border-bottom: 1px solid #e2e8f0;
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--industrial-gray);
    }

    .filter-card-header i {
        color: var(--uitm-blue);
    }

    .filter-card-body {
        padding: 1.25rem 1.5rem;
    }

    .filter-card .ind-form-label {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #64748b;
        margin-bottom: 0.4rem;
    }

    .filter-card .ind-form-control,
    .filter-card .ind-form-select {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.85rem;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
        color: var(--industrial-dark);
        background-color: #fff;
        transition: border-color 0.2s, box-shadow 0.2s;
        width: 100%;
    }

    .filter-card .ind-form-control:focus,
    .filter-card .ind-form-select:focus {
        border-color: var(--uitm-blue-light);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
        outline: none;
    }

    .filter-card .input-group {
        display: flex;
    }

    .filter-card .input-group .input-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 0.75rem;
        background: var(--industrial-light);
        border: 1px solid #cbd5e1;
        border-right: none;
        border-radius: 8px 0 0 8px;
        color: #64748b;
        font-size: 0.85rem;
    }

    .filter-card .input-group .ind-form-control {
        border-radius: 0 8px 8px 0;
    }

    .btn-ind-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.5rem 1.15rem;
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.825rem;
        font-weight: 600;
        color: #fff;
        background: linear-gradient(135deg, var(--uitm-blue), var(--industrial-dark));
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-ind-primary:hover {
        box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
        transform: translateY(-1px);
    }

    .btn-ind-outline {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        font-size: 0.85rem;
        color: #64748b;
        background: #fff;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-ind-outline:hover {
        background: #fef2f2;
        border-color: var(--danger);
        color: var(--danger);
    }

    /* ---- Applications Table ---- */
    .table-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
    }

    .table-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 1.5rem;
        background: linear-gradient(135deg, var(--industrial-dark), var(--uitm-blue));
        color: #fff;
    }

    .table-card-header .table-title {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.95rem;
        font-weight: 600;
    }

    .table-card-header .table-count {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.3rem 0.75rem;
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 6px;
        color: rgba(255,255,255,0.9);
    }

    .ind-table {
        width: 100%;
        border-collapse: collapse;
    }

    .ind-table thead th {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #64748b;
        background: var(--industrial-light);
        padding: 0.85rem 1rem;
        border-bottom: 2px solid #e2e8f0;
        white-space: nowrap;
    }

    .ind-table tbody td {
        padding: 0.85rem 1rem;
        font-size: 0.85rem;
        color: var(--industrial-gray);
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .ind-table tbody tr {
        transition: background 0.15s ease;
    }

    .ind-table tbody tr:hover {
        background: rgba(59, 130, 246, 0.03);
    }

    .ind-table tbody tr:last-child td {
        border-bottom: none;
    }

    .row-number {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.75rem;
        color: #94a3b8;
    }

    .matric-code {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.8rem;
        font-weight: 500;
        color: var(--uitm-blue);
        background: rgba(30, 58, 138, 0.06);
        padding: 0.25rem 0.6rem;
        border-radius: 6px;
        border: 1px solid rgba(30, 58, 138, 0.1);
        display: inline-block;
    }

    .student-cell {
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .student-avatar {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.7rem;
        font-weight: 700;
        color: #fff;
        background: linear-gradient(135deg, var(--uitm-blue-light), var(--uitm-blue));
        flex-shrink: 0;
    }

    .student-name {
        font-weight: 600;
        color: var(--industrial-dark);
        font-size: 0.85rem;
    }

    .badge-program {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.7rem;
        font-weight: 600;
        padding: 0.2rem 0.55rem;
        border-radius: 5px;
        background: rgba(51, 65, 85, 0.08);
        color: var(--industrial-gray);
        border: 1px solid rgba(51, 65, 85, 0.12);
    }

    .badge-group {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.7rem;
        font-weight: 600;
        padding: 0.2rem 0.55rem;
        border-radius: 5px;
        background: rgba(13, 148, 136, 0.08);
        color: var(--teal);
        border: 1px solid rgba(13, 148, 136, 0.15);
    }

    .badge-semester {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.7rem;
        font-weight: 500;
        padding: 0.2rem 0.55rem;
        border-radius: 5px;
        background: var(--industrial-light);
        color: #64748b;
        border: 1px solid #e2e8f0;
    }

    .submitted-date {
        font-size: 0.8rem;
        color: var(--industrial-gray);
    }

    .submitted-time {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.7rem;
        color: #94a3b8;
    }

    /* Status Badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.725rem;
        font-weight: 600;
        padding: 0.3rem 0.7rem;
        border-radius: 6px;
        white-space: nowrap;
    }

    .status-badge i { font-size: 0.65rem; }

    .status-pending {
        background: rgba(245, 158, 11, 0.1);
        color: #b45309;
        border: 1px solid rgba(245, 158, 11, 0.2);
    }

    .status-review {
        background: rgba(13, 148, 136, 0.08);
        color: var(--teal);
        border: 1px solid rgba(13, 148, 136, 0.15);
    }

    .status-completed {
        background: rgba(5, 150, 105, 0.08);
        color: var(--success);
        border: 1px solid rgba(5, 150, 105, 0.15);
    }

    .status-rejected {
        background: rgba(220, 38, 38, 0.08);
        color: var(--danger);
        border: 1px solid rgba(220, 38, 38, 0.15);
    }

    .status-default {
        background: rgba(100, 116, 139, 0.08);
        color: #64748b;
        border: 1px solid rgba(100, 116, 139, 0.15);
    }

    /* View Button */
    .btn-view-details {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.4rem 0.85rem;
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.78rem;
        font-weight: 600;
        color: var(--uitm-blue);
        background: rgba(30, 58, 138, 0.06);
        border: 1px solid rgba(30, 58, 138, 0.15);
        border-radius: 7px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-view-details:hover {
        background: var(--uitm-blue);
        color: #fff;
        border-color: var(--uitm-blue);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(30, 58, 138, 0.2);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3.5rem 2rem;
    }

    .empty-state-icon {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        background: var(--industrial-light);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.25rem;
        color: #94a3b8;
        font-size: 1.75rem;
    }

    .empty-state h3 {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--industrial-gray);
        margin-bottom: 0.35rem;
    }

    .empty-state p {
        font-size: 0.85rem;
        color: #94a3b8;
    }

    /* Pagination Footer */
    .table-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 1.5rem;
        background: var(--industrial-light);
        border-top: 1px solid #e2e8f0;
    }

    .table-footer .footer-info {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.75rem;
        color: #64748b;
    }

    .table-footer .pagination {
        margin-bottom: 0;
    }

    .table-footer .pagination .page-link {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.8rem;
        padding: 0.35rem 0.65rem;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        color: var(--industrial-gray);
        margin: 0 2px;
        line-height: 1.5;
    }

    .table-footer .pagination .page-link svg {
        width: 1em;
        height: 1em;
        vertical-align: middle;
    }

    .table-footer .pagination .page-item.active .page-link {
        background: var(--uitm-blue);
        border-color: var(--uitm-blue);
        color: #fff;
    }

    .table-footer .pagination .page-link:hover {
        background: rgba(30, 58, 138, 0.06);
        border-color: var(--uitm-blue-light);
        color: var(--uitm-blue);
    }

    /* ---- Modal: Industrial Styling ---- */
    .ind-modal .modal-content {
        border: none;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 25px 60px rgba(0,0,0,0.2);
    }

    .ind-modal .modal-header {
        background: linear-gradient(135deg, var(--industrial-dark) 0%, var(--uitm-blue) 100%);
        padding: 1.25rem 1.5rem;
        border: none;
        position: relative;
    }

    .ind-modal .modal-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--uitm-amber), transparent 60%);
    }

    .ind-modal .modal-title {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 1rem;
        font-weight: 600;
        color: #fff;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .ind-modal .modal-title i {
        color: var(--uitm-amber);
    }

    .ind-modal .btn-close-white {
        filter: brightness(0) invert(1);
        opacity: 0.7;
    }

    .ind-modal .btn-close-white:hover {
        opacity: 1;
    }

    .ind-modal .modal-body {
        padding: 1.5rem;
    }

    .ind-modal .modal-footer {
        padding: 1rem 1.5rem;
        background: var(--industrial-light);
        border-top: 1px solid #e2e8f0;
    }

    /* Modal Section Headers */
    .modal-section-title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--uitm-blue);
        padding-bottom: 0.5rem;
        margin-bottom: 0.75rem;
        border-bottom: 2px solid rgba(30, 58, 138, 0.1);
    }

    .modal-section-title i {
        font-size: 0.85rem;
    }

    /* Modal Info Table */
    .modal-info-table {
        width: 100%;
        border-collapse: collapse;
    }

    .modal-info-table th {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.72rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #94a3b8;
        padding: 0.45rem 0;
        width: 38%;
        vertical-align: top;
    }

    .modal-info-table td {
        font-size: 0.85rem;
        color: var(--industrial-dark);
        padding: 0.45rem 0;
    }

    .modal-info-table code {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.8rem;
        font-weight: 600;
        background: rgba(30, 58, 138, 0.06);
        color: var(--uitm-blue);
        padding: 0.2rem 0.55rem;
        border-radius: 5px;
        border: 1px solid rgba(30, 58, 138, 0.1);
    }

    /* Modal Advisor Section */
    .modal-advisor-card {
        background: var(--industrial-light);
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.85rem 1rem;
        margin-top: 0.5rem;
    }

    .modal-advisor-avatar {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.75rem;
        font-weight: 700;
        color: #fff;
        background: linear-gradient(135deg, var(--success), #047857);
        flex-shrink: 0;
    }

    .modal-advisor-name {
        font-weight: 600;
        color: var(--industrial-dark);
        font-size: 0.85rem;
    }

    .modal-advisor-email {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.72rem;
        color: #64748b;
    }

    /* Modal Alert Styles */
    .modal-alert-warning {
        background: rgba(234, 88, 12, 0.06);
        border: 1px solid rgba(234, 88, 12, 0.15);
        border-left: 3px solid var(--warning);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-size: 0.82rem;
        color: #9a3412;
    }

    .modal-alert-warning a {
        color: var(--uitm-blue);
        font-weight: 600;
        text-decoration: none;
    }

    .modal-alert-warning a:hover {
        text-decoration: underline;
    }

    .modal-alert-success {
        background: rgba(5, 150, 105, 0.06);
        border: 1px solid rgba(5, 150, 105, 0.15);
        border-left: 3px solid var(--success);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-size: 0.82rem;
        color: #065f46;
    }

    .reviewed-at-label {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.72rem;
        color: #64748b;
    }

    /* Modal Buttons */
    .btn-modal-close {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.5rem 1.15rem;
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--industrial-gray);
        background: #fff;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-modal-close:hover {
        background: var(--industrial-light);
        border-color: #94a3b8;
    }

    .btn-modal-reminder {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.5rem 1.15rem;
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.82rem;
        font-weight: 600;
        color: #fff;
        background: linear-gradient(135deg, var(--uitm-amber), #d97706);
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-modal-reminder:hover {
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        transform: translateY(-1px);
    }

    /* Spinner */
    .ind-spinner {
        display: inline-block;
        width: 18px;
        height: 18px;
        border: 2px solid rgba(30, 58, 138, 0.15);
        border-top-color: var(--uitm-blue);
        border-radius: 50%;
        animation: ind-spin 0.6s linear infinite;
    }

    @keyframes ind-spin {
        to { transform: rotate(360deg); }
    }

    .text-muted-ind {
        color: #94a3b8;
        font-size: 0.82rem;
    }

    /* ---- Responsive ---- */
    @media (max-width: 1200px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .page-header-industrial .header-inner {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .table-footer {
            flex-direction: column;
            gap: 0.75rem;
            text-align: center;
        }

        .ind-table {
            font-size: 0.8rem;
        }
    }
</style>
@endpush

@section('content')
<div class="app-monitoring">

    {{-- Industrial Page Header --}}
    <header class="page-header-industrial">
        <div class="header-grid-pattern"></div>
        <div class="header-amber-accent"></div>
        <div class="header-inner">
            <div class="header-left">
                <div class="header-icon-box">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div>
                    <h1>Application Monitoring</h1>
                    <div class="header-subtitle">Monitor all credit exemption applications</div>
                </div>
            </div>
            <a href="{{ route('hea.dashboard') }}" class="btn-back-dashboard">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </header>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="ind-alert ind-alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="ind-alert ind-alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Statistics Grid --}}
    <div class="stats-grid">
        <div class="stat-metric-card stat-total">
            <div class="stat-icon-wrap">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="stat-value">{{ $stats['total'] }}</div>
            <div class="stat-label">Total Applications</div>
        </div>
        <div class="stat-metric-card stat-pending">
            <div class="stat-icon-wrap">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-value">{{ $stats['pending'] }}</div>
            <div class="stat-label">Pending Review</div>
        </div>
        <div class="stat-metric-card stat-review">
            <div class="stat-icon-wrap">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="stat-value">{{ $stats['reviewed'] }}</div>
            <div class="stat-label">Under Review</div>
        </div>
        <div class="stat-metric-card stat-completed">
            <div class="stat-icon-wrap">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-value">{{ $stats['completed'] }}</div>
            <div class="stat-label">Completed</div>
        </div>
    </div>

    {{-- Filters Card --}}
    <div class="filter-card">
        <div class="filter-card-header">
            <i class="fas fa-filter"></i>
            <span>Filters</span>
        </div>
        <div class="filter-card-body">
            <form method="GET" action="{{ route('hea.applications.index') }}" id="filterForm">
                <div class="row g-3">
                    {{-- Search --}}
                    <div class="col-md-3">
                        <label class="ind-form-label">Search</label>
                        <div class="input-group">
                            <span class="input-icon"><i class="fas fa-search"></i></span>
                            <input type="text" name="search" class="ind-form-control"
                                   placeholder="Name or Matric No."
                                   value="{{ $search }}">
                        </div>
                    </div>

                    {{-- Status Filter --}}
                    <div class="col-md-2">
                        <label class="ind-form-label">Status</label>
                        <select name="status_filter" class="ind-form-select" onchange="this.form.submit()">
                            <option value="all" {{ $statusFilter == 'all' ? 'selected' : '' }}>All Statuses</option>
                            <option value="pending" {{ $statusFilter == 'pending' ? 'selected' : '' }}>Pending Review</option>
                            <option value="reviewed" {{ $statusFilter == 'reviewed' ? 'selected' : '' }}>Under Review</option>
                            <option value="completed" {{ $statusFilter == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>

                    {{-- Program Filter --}}
                    <div class="col-md-3">
                        <label class="ind-form-label">Program</label>
                        <select name="program_filter" class="ind-form-select" onchange="this.form.submit()">
                            <option value="all" {{ $programFilter == 'all' ? 'selected' : '' }}>All Programs</option>
                            @foreach($supportedPrograms as $code => $name)
                                <option value="{{ $code }}" {{ $programFilter == $code ? 'selected' : '' }}>
                                    {{ $code }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Group Filter --}}
                    <div class="col-md-2">
                        <label class="ind-form-label">Student Group</label>
                        <select name="group_filter" class="ind-form-select" onchange="this.form.submit()">
                            <option value="all" {{ $groupFilter == 'all' ? 'selected' : '' }}>All Groups</option>
                            @foreach($studentGroups as $group)
                                <option value="{{ $group }}" {{ $groupFilter == $group ? 'selected' : '' }}>
                                    {{ $group }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Actions --}}
                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn-ind-primary">
                            <i class="fas fa-search"></i> Search
                        </button>
                        <a href="{{ route('hea.applications.index') }}" class="btn-ind-outline">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Applications Table --}}
    <div class="table-card">
        <div class="table-card-header">
            <div class="table-title">
                <i class="fas fa-clipboard-list"></i>
                <span>Applications</span>
            </div>
            <span class="table-count">
                {{ $applications->total() }} {{ Str::plural('application', $applications->total()) }}
            </span>
        </div>

        @if($applications->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-inbox"></i>
                </div>
                <h3>No Applications Found</h3>
                <p>No applications match your filter criteria.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="ind-table">
                    <thead>
                        <tr>
                            <th width="4%">#</th>
                            <th width="11%">Matric No.</th>
                            <th width="18%">Student Name</th>
                            <th width="10%">Program</th>
                            <th width="9%">Group</th>
                            <th width="8%">Semester</th>
                            <th width="14%">Submitted</th>
                            <th width="12%">Status</th>
                            <th width="10%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications as $index => $app)
                        <tr>
                            <td>
                                <span class="row-number">{{ $applications->firstItem() + $index }}</span>
                            </td>
                            <td>
                                <span class="matric-code">{{ $app->matric_no ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <div class="student-cell">
                                    <div class="student-avatar">
                                        {{ strtoupper(substr($app->student_name ?? 'N', 0, 1)) }}
                                    </div>
                                    <span class="student-name">{{ $app->student_name }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge-program">{{ $app->current_program_code ?? 'N/A' }}</span>
                            </td>
                            <td>
                                @if($app->student_group)
                                    <span class="badge-group">{{ $app->student_group }}</span>
                                @else
                                    <span class="text-muted-ind">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge-semester">Sem {{ $app->current_semester ?? '-' }}</span>
                            </td>
                            <td>
                                <div class="submitted-date">{{ $app->created_at->format('d M Y') }}</div>
                                <div class="submitted-time">{{ $app->created_at->format('h:i A') }}</div>
                            </td>
                            <td>
                                @php
                                    $statusClass = 'default';
                                    $statusIcon = 'circle';
                                    $statusText = $app->status;

                                    if (strtolower($app->status) === 'submitted') {
                                        $statusClass = 'pending';
                                        $statusIcon = 'clock';
                                        $statusText = 'Pending';
                                    } elseif (strtolower($app->status) === 'reviewed by academic advisor') {
                                        $statusClass = 'review';
                                        $statusIcon = 'user-check';
                                        $statusText = 'Under Review';
                                    } elseif (strtolower($app->status) === 'completed') {
                                        $statusClass = 'completed';
                                        $statusIcon = 'check-circle';
                                        $statusText = 'Completed';
                                    } elseif (str_contains(strtolower($app->status), 'rejected')) {
                                        $statusClass = 'rejected';
                                        $statusIcon = 'times-circle';
                                        $statusText = 'Rejected';
                                    }
                                @endphp
                                <span class="status-badge status-{{ $statusClass }}">
                                    <i class="fas fa-{{ $statusIcon }}"></i>{{ $statusText }}
                                </span>
                            </td>
                            <td>
                                <button type="button" class="btn-view-details view-details-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#detailsModal"
                                        data-id="{{ $app->id }}"
                                        data-matric="{{ $app->matric_no }}"
                                        data-name="{{ $app->student_name }}"
                                        data-program="{{ $app->current_program_code }}"
                                        data-program-name="{{ $supportedPrograms[$app->current_program_code] ?? '' }}"
                                        data-group="{{ $app->student_group }}"
                                        data-semester="{{ $app->current_semester }}"
                                        data-faculty="{{ $app->current_faculty }}"
                                        data-campus="{{ $app->current_campus }}"
                                        data-status="{{ $app->status }}"
                                        data-submitted="{{ $app->created_at->format('d M Y, h:i A') }}"
                                        data-previous-institution="{{ $app->previous_institution }}"
                                        data-previous-program="{{ $app->previous_program }}"
                                        data-advisor="{{ $app->reviewed_by_advisor?->name ?? '' }}">
                                    <i class="fas fa-eye"></i> View
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination Footer --}}
            <div class="table-footer">
                <span class="footer-info">
                    Showing {{ $applications->firstItem() }} to {{ $applications->lastItem() }} of {{ $applications->total() }} applications
                </span>
                {{ $applications->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Application Details Modal --}}
<div class="modal fade ind-modal" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailsModalLabel">
                    <i class="fas fa-file-alt"></i>
                    Application Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    {{-- Student Information --}}
                    <div class="col-md-6">
                        <div class="modal-section-title">
                            <i class="fas fa-user-graduate"></i>
                            Student Information
                        </div>
                        <table class="modal-info-table">
                            <tr>
                                <th>Matric No:</th>
                                <td><code id="modal-matric"></code></td>
                            </tr>
                            <tr>
                                <th>Name:</th>
                                <td><strong id="modal-name"></strong></td>
                            </tr>
                            <tr>
                                <th>Program:</th>
                                <td>
                                    <span id="modal-program" class="badge-program"></span>
                                    <small id="modal-program-name" class="d-block mt-1" style="color: #64748b; font-size: 0.75rem;"></small>
                                </td>
                            </tr>
                            <tr>
                                <th>Group:</th>
                                <td><span id="modal-group" class="badge-group"></span></td>
                            </tr>
                            <tr>
                                <th>Semester:</th>
                                <td id="modal-semester"></td>
                            </tr>
                            <tr>
                                <th>Faculty:</th>
                                <td id="modal-faculty"></td>
                            </tr>
                            <tr>
                                <th>Campus:</th>
                                <td id="modal-campus"></td>
                            </tr>
                        </table>
                    </div>

                    {{-- Application Information --}}
                    <div class="col-md-6">
                        <div class="modal-section-title">
                            <i class="fas fa-clipboard-list"></i>
                            Application Information
                        </div>
                        <table class="modal-info-table">
                            <tr>
                                <th>Status:</th>
                                <td><span id="modal-status" class="status-badge"></span></td>
                            </tr>
                            <tr>
                                <th>Submitted:</th>
                                <td id="modal-submitted"></td>
                            </tr>
                            <tr id="advisor-row" style="display: none;">
                                <th>Reviewed By:</th>
                                <td id="modal-advisor"></td>
                            </tr>
                        </table>

                        <div class="modal-section-title mt-4">
                            <i class="fas fa-university"></i>
                            Previous Education
                        </div>
                        <table class="modal-info-table">
                            <tr>
                                <th>Institution:</th>
                                <td id="modal-prev-institution"></td>
                            </tr>
                            <tr>
                                <th>Program:</th>
                                <td id="modal-prev-program"></td>
                            </tr>
                        </table>

                        {{-- Academic Advisor Section --}}
                        <div id="advisor-section" style="display: none;">
                            <div class="modal-section-title mt-4">
                                <i class="fas fa-user-tie"></i>
                                <span id="advisor-section-title">Assigned Academic Advisor</span>
                            </div>
                            <div id="advisor-loading" class="text-center py-2">
                                <span class="ind-spinner"></span>
                                <small class="ms-2" style="color: #64748b;">Loading advisor info...</small>
                            </div>
                            <div id="advisor-info" style="display: none;">
                                <div class="modal-advisor-card">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="modal-advisor-avatar">
                                            <span id="advisor-initial"></span>
                                        </div>
                                        <div>
                                            <div class="modal-advisor-name" id="advisor-name-display"></div>
                                            <div class="modal-advisor-email" id="advisor-email-display"></div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Reviewed At Info --}}
                                <div id="reviewed-at-info" class="reviewed-at-label mt-2" style="display: none;">
                                    <i class="fas fa-clock me-1"></i>Reviewed: <span id="reviewed-at-date"></span>
                                </div>
                            </div>
                            <div id="advisor-error" class="modal-alert-warning" style="display: none;">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <span id="advisor-error-message"></span>
                                <a href="{{ route('hea.program_groups.index') }}" class="d-block mt-1 small">
                                    <i class="fas fa-cog me-1"></i>Configure Program Groups
                                </a>
                            </div>
                            {{-- Reminder Status --}}
                            <div id="reminder-status" class="mt-3" style="display: none;">
                                <div id="reminder-sent" class="modal-alert-success" style="display: none;">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <strong>Reminder Sent</strong>
                                    <div class="small mt-1">
                                        <span id="reminder-sent-at"></span> by <span id="reminder-sent-by"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-modal-close" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Close
                </button>
                <form id="send-reminder-form" method="POST" style="display: none;">
                    @csrf
                    <button type="submit" class="btn-modal-reminder" id="send-reminder-btn">
                        <i class="fas fa-bell me-1"></i>Send Reminder to AA
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const detailsModal = document.getElementById('detailsModal');

    if (detailsModal) {
        detailsModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const applicationId = button.dataset.id;
            const status = button.dataset.status || '';
            const isPending = status.toLowerCase() === 'submitted';

            // Populate modal fields
            document.getElementById('modal-matric').textContent = button.dataset.matric || 'N/A';
            document.getElementById('modal-name').textContent = button.dataset.name || 'N/A';
            document.getElementById('modal-program').textContent = button.dataset.program || 'N/A';
            document.getElementById('modal-program-name').textContent = button.dataset.programName || '';

            const groupEl = document.getElementById('modal-group');
            if (button.dataset.group) {
                groupEl.textContent = button.dataset.group;
                groupEl.className = 'badge-group';
            } else {
                groupEl.textContent = 'Not assigned';
                groupEl.className = 'text-muted-ind';
            }

            document.getElementById('modal-semester').textContent = 'Semester ' + (button.dataset.semester || '-');
            document.getElementById('modal-faculty').textContent = button.dataset.faculty || 'N/A';
            document.getElementById('modal-campus').textContent = button.dataset.campus || 'N/A';
            document.getElementById('modal-submitted').textContent = button.dataset.submitted || 'N/A';
            document.getElementById('modal-prev-institution').textContent = button.dataset.previousInstitution || 'N/A';
            document.getElementById('modal-prev-program').textContent = button.dataset.previousProgram || 'N/A';

            // Status badge
            const statusEl = document.getElementById('modal-status');
            let statusClass = 'default';
            let statusText = status;

            if (isPending) {
                statusClass = 'pending';
                statusText = 'Pending Review';
            } else if (status.toLowerCase() === 'reviewed by academic advisor') {
                statusClass = 'review';
                statusText = 'Under Review';
            } else if (status.toLowerCase() === 'completed') {
                statusClass = 'completed';
                statusText = 'Completed';
            } else if (status.toLowerCase().includes('rejected')) {
                statusClass = 'rejected';
                statusText = 'Rejected';
            }

            statusEl.className = 'status-badge status-' + statusClass;
            statusEl.textContent = statusText;

            // Advisor info (for reviewed applications)
            const advisorRow = document.getElementById('advisor-row');
            const advisorEl = document.getElementById('modal-advisor');
            if (button.dataset.advisor) {
                advisorRow.style.display = 'table-row';
                advisorEl.textContent = button.dataset.advisor;
            } else {
                advisorRow.style.display = 'none';
            }

            // Handle advisor section for all applications with a group
            const advisorSection = document.getElementById('advisor-section');
            const advisorSectionTitle = document.getElementById('advisor-section-title');
            const advisorLoading = document.getElementById('advisor-loading');
            const advisorInfo = document.getElementById('advisor-info');
            const advisorError = document.getElementById('advisor-error');
            const sendReminderForm = document.getElementById('send-reminder-form');
            const reminderStatus = document.getElementById('reminder-status');
            const reminderSent = document.getElementById('reminder-sent');
            const reviewedAtInfo = document.getElementById('reviewed-at-info');

            if (button.dataset.group) {
                // Show advisor section for all applications with a group
                advisorSection.style.display = 'block';
                advisorLoading.style.display = 'block';
                advisorInfo.style.display = 'none';
                advisorError.style.display = 'none';
                sendReminderForm.style.display = 'none';
                reminderStatus.style.display = 'none';
                reminderSent.style.display = 'none';
                reviewedAtInfo.style.display = 'none';

                // Set title based on status
                if (isPending) {
                    advisorSectionTitle.textContent = 'Assigned Academic Advisor';
                } else {
                    advisorSectionTitle.textContent = 'Reviewed By';
                }

                // Fetch assigned advisor and status info
                fetch(`/hea/applications/${applicationId}/assigned-advisor`)
                    .then(response => response.json())
                    .then(data => {
                        advisorLoading.style.display = 'none';

                        if (data.success) {
                            // Check if this is a reviewed application (not pending)
                            if (data.is_pending === false) {
                                // For reviewed applications, show who reviewed it
                                if (data.reviewed_by) {
                                    advisorInfo.style.display = 'block';
                                    document.getElementById('advisor-initial').textContent = data.reviewed_by.name.charAt(0).toUpperCase();
                                    document.getElementById('advisor-name-display').textContent = data.reviewed_by.name;
                                    document.getElementById('advisor-email-display').textContent = data.reviewed_by.email;

                                    if (data.reviewed_by.reviewed_at) {
                                        reviewedAtInfo.style.display = 'block';
                                        document.getElementById('reviewed-at-date').textContent = data.reviewed_by.reviewed_at;
                                    }
                                } else {
                                    // No reviewer info found
                                    advisorInfo.style.display = 'block';
                                    document.getElementById('advisor-initial').textContent = '?';
                                    document.getElementById('advisor-name-display').textContent = 'Reviewer information not available';
                                    document.getElementById('advisor-email-display').textContent = '';
                                }
                            } else {
                                // For pending applications, show assigned advisor info
                                if (data.advisor) {
                                    advisorInfo.style.display = 'block';
                                    document.getElementById('advisor-initial').textContent = data.advisor.name.charAt(0).toUpperCase();
                                    document.getElementById('advisor-name-display').textContent = data.advisor.name;
                                    document.getElementById('advisor-email-display').textContent = data.advisor.email;

                                    // Show reminder status and button
                                    reminderStatus.style.display = 'block';

                                    if (data.reminder) {
                                        // Reminder was already sent
                                        reminderSent.style.display = 'block';
                                        document.getElementById('reminder-sent-at').textContent = data.reminder.sent_at;
                                        document.getElementById('reminder-sent-by').textContent = data.reminder.sent_by;

                                        // Still allow sending another reminder
                                        sendReminderForm.style.display = 'block';
                                        sendReminderForm.action = `/hea/applications/${applicationId}/send-reminder`;
                                        document.getElementById('send-reminder-btn').innerHTML = '<i class="fas fa-bell me-1"></i>Send Another Reminder';
                                    } else {
                                        // No reminder sent yet
                                        sendReminderForm.style.display = 'block';
                                        sendReminderForm.action = `/hea/applications/${applicationId}/send-reminder`;
                                        document.getElementById('send-reminder-btn').innerHTML = '<i class="fas fa-bell me-1"></i>Send Reminder to AA';
                                    }
                                }
                            }
                        } else {
                            // Show error (only for pending applications that can't find AA)
                            if (isPending) {
                                advisorError.style.display = 'block';
                                document.getElementById('advisor-error-message').textContent = data.message;
                            } else {
                                // For reviewed applications without reviewer info, show a neutral message
                                advisorInfo.style.display = 'block';
                                document.getElementById('advisor-initial').textContent = '?';
                                document.getElementById('advisor-name-display').textContent = 'Reviewer information not available';
                                document.getElementById('advisor-email-display').textContent = '';
                            }
                        }
                    })
                    .catch(error => {
                        advisorLoading.style.display = 'none';
                        advisorError.style.display = 'block';
                        document.getElementById('advisor-error-message').textContent = 'Failed to load advisor information';
                    });
            } else {
                // Hide advisor section for applications without a group
                advisorSection.style.display = 'none';
                sendReminderForm.style.display = 'none';
            }
        });
    }

    // Confirm before sending reminder
    const sendReminderForm = document.getElementById('send-reminder-form');
    if (sendReminderForm) {
        sendReminderForm.addEventListener('submit', function(e) {
            if (!confirm('Are you sure you want to send a reminder to the Academic Advisor?')) {
                e.preventDefault();
            }
        });
    }
});
</script>
@endpush
@endsection
