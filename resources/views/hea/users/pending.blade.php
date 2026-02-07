@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
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

    .pending-approvals-page {
        font-family: 'IBM Plex Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
        min-height: 100vh;
        padding: 0 1.5rem 2rem;
    }

    .pending-approvals-page h1,
    .pending-approvals-page h2,
    .pending-approvals-page h3,
    .pending-approvals-page h4,
    .pending-approvals-page h5,
    .pending-approvals-page h6 {
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        color: var(--industrial-dark);
    }

    /* ============================================
       PAGE HEADER
       ============================================ */
    .page-header {
        position: relative;
        background: linear-gradient(135deg, var(--industrial-dark) 0%, #1e293b 50%, var(--uitm-blue) 100%);
        margin: -1rem -1.5rem 1.5rem;
        padding: 2rem;
        overflow: hidden;
    }

    .page-header-grid {
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px),
            linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px);
        background-size: 32px 32px;
    }

    .page-header-accent {
        position: absolute;
        top: -100px;
        right: -100px;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(245, 158, 11, 0.15) 0%, transparent 70%);
        border-radius: 50%;
    }

    .page-header-content {
        position: relative;
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
    }

    .page-header-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .page-header-badge {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
    }

    .page-header-badge-text {
        background: var(--uitm-amber);
        color: var(--industrial-dark);
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 0.4rem 0.6rem;
        border-radius: 4px;
        letter-spacing: 0.05em;
    }

    .page-header-badge-line {
        width: 2px;
        height: 20px;
        background: linear-gradient(to bottom, var(--uitm-amber), transparent);
    }

    .page-header-info h1 {
        color: white;
        font-size: 1.5rem;
        margin: 0 0 0.25rem;
    }

    .page-header-info p {
        color: rgba(255,255,255,0.6);
        font-size: 0.875rem;
        margin: 0;
    }

    .page-header-right {
        display: none;
    }

    @media (min-width: 768px) {
        .page-header-right {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
    }

    .header-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(10px);
        color: white;
        padding: 0.625rem 1rem;
        border-radius: 8px;
        border: 1px solid rgba(255,255,255,0.15);
        font-size: 0.85rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
    }

    .header-link:hover {
        background: rgba(255,255,255,0.2);
        color: white;
        text-decoration: none;
    }

    .header-link svg {
        width: 16px;
        height: 16px;
    }

    /* ============================================
       TOAST MESSAGES
       ============================================ */
    .toast-container {
        position: fixed;
        top: 1.5rem;
        right: 1.5rem;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .toast-msg {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem 1.25rem;
        border-radius: 10px;
        min-width: 320px;
        max-width: 480px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.15);
        animation: toastSlideIn 0.4s ease;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .toast-msg.success {
        background: white;
        border-left: 4px solid var(--success);
        color: var(--industrial-dark);
    }

    .toast-msg.warning {
        background: white;
        border-left: 4px solid var(--uitm-amber);
        color: var(--industrial-dark);
    }

    .toast-msg.error {
        background: white;
        border-left: 4px solid var(--danger);
        color: var(--industrial-dark);
    }

    .toast-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .toast-msg.success .toast-icon { background: rgba(5, 150, 105, 0.1); color: var(--success); }
    .toast-msg.warning .toast-icon { background: rgba(245, 158, 11, 0.1); color: var(--uitm-amber); }
    .toast-msg.error .toast-icon { background: rgba(220, 38, 38, 0.1); color: var(--danger); }

    .toast-content { flex: 1; }

    .toast-close {
        background: none;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        padding: 0.25rem;
        border-radius: 4px;
        transition: color 0.2s;
    }

    .toast-close:hover { color: var(--industrial-dark); }

    @keyframes toastSlideIn {
        from { opacity: 0; transform: translateX(100px); }
        to { opacity: 1; transform: translateX(0); }
    }

    @keyframes toastSlideOut {
        from { opacity: 1; transform: translateX(0); }
        to { opacity: 0; transform: translateX(100px); }
    }

    /* ============================================
       STATS SECTION
       ============================================ */
    .stats-bar {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.5rem;
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stat-icon.amber {
        background: rgba(245, 158, 11, 0.1);
        color: var(--uitm-amber);
    }

    .stat-icon svg {
        width: 24px;
        height: 24px;
    }

    .stat-data {
        display: flex;
        flex-direction: column;
    }

    .stat-value {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--industrial-dark);
        line-height: 1;
    }

    .stat-label {
        font-size: 0.8rem;
        font-weight: 500;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        margin-top: 0.15rem;
    }

    .stat-divider {
        width: 1px;
        height: 40px;
        background: #e2e8f0;
    }

    .stat-breakdown {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .stat-breakdown-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.35rem 0.65rem;
        border-radius: 6px;
    }

    .stat-breakdown-chip.aa { background: rgba(30, 58, 138, 0.08); color: var(--uitm-blue); }
    .stat-breakdown-chip.pc { background: rgba(13, 148, 136, 0.08); color: var(--teal); }
    .stat-breakdown-chip.rp { background: rgba(234, 88, 12, 0.08); color: var(--warning); }

    .stat-breakdown-chip .chip-count {
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 700;
    }

    /* ============================================
       TABLE CARD
       ============================================ */
    .table-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
    }

    .table-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        background: #fafbfc;
    }

    .table-card-header-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .table-card-header-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(245, 158, 11, 0.1);
        color: var(--uitm-amber);
    }

    .table-card-header-icon svg {
        width: 18px;
        height: 18px;
    }

    .table-card-header h3 {
        font-size: 0.9375rem;
        margin: 0;
    }

    .table-card-header-count {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.75rem;
        font-weight: 600;
        background: var(--uitm-amber);
        color: var(--industrial-dark);
        padding: 0.2rem 0.5rem;
        border-radius: 10px;
        margin-left: 0.5rem;
    }

    /* Industrial Table */
    .industrial-table {
        width: 100%;
        border-collapse: collapse;
    }

    .industrial-table thead th {
        padding: 0.75rem 1rem;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #64748b;
        background: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
        white-space: nowrap;
    }

    .industrial-table tbody tr {
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.15s;
    }

    .industrial-table tbody tr:hover {
        background: #f8fafc;
    }

    .industrial-table tbody tr:last-child {
        border-bottom: none;
    }

    .industrial-table tbody td {
        padding: 0.875rem 1rem;
        font-size: 0.875rem;
        vertical-align: middle;
    }

    /* Cell styles */
    .user-name-cell {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .user-avatar {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.75rem;
        font-weight: 700;
        color: white;
        flex-shrink: 0;
    }

    .user-avatar.aa { background: linear-gradient(135deg, var(--uitm-blue), #2563eb); }
    .user-avatar.pc { background: linear-gradient(135deg, var(--teal), #14b8a6); }
    .user-avatar.rp { background: linear-gradient(135deg, var(--warning), #f97316); }
    .user-avatar.default { background: linear-gradient(135deg, var(--industrial-gray), #475569); }

    .user-name-text {
        font-weight: 600;
        color: var(--industrial-dark);
    }

    .user-email-text {
        font-size: 0.8rem;
        color: #64748b;
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 400;
    }

    /* Role badges */
    .role-badge-industrial {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.3rem 0.65rem;
        border-radius: 6px;
        white-space: nowrap;
    }

    .role-badge-industrial.aa {
        background: rgba(30, 58, 138, 0.08);
        color: var(--uitm-blue);
        border: 1px solid rgba(30, 58, 138, 0.15);
    }

    .role-badge-industrial.pc {
        background: rgba(13, 148, 136, 0.08);
        color: var(--teal);
        border: 1px solid rgba(13, 148, 136, 0.15);
    }

    .role-badge-industrial.rp {
        background: rgba(234, 88, 12, 0.08);
        color: var(--warning);
        border: 1px solid rgba(234, 88, 12, 0.15);
    }

    .role-badge-industrial.default {
        background: rgba(51, 65, 85, 0.08);
        color: var(--industrial-gray);
        border: 1px solid rgba(51, 65, 85, 0.15);
    }

    .role-badge-code {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.65rem;
        font-weight: 700;
        padding: 0.1rem 0.3rem;
        border-radius: 3px;
        background: rgba(0,0,0,0.06);
    }

    /* Program info */
    .program-info-cell {
        font-size: 0.8rem;
        color: #64748b;
        max-width: 200px;
    }

    .program-info-cell .program-chip {
        display: inline-flex;
        align-items: center;
        font-size: 0.72rem;
        font-weight: 500;
        padding: 0.2rem 0.5rem;
        border-radius: 4px;
        background: #f1f5f9;
        color: var(--industrial-gray);
        margin: 0.15rem 0.15rem 0.15rem 0;
    }

    /* Date cell */
    .date-cell {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.8rem;
        color: #64748b;
    }

    .date-cell .date-relative {
        display: block;
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.72rem;
        color: #94a3b8;
        margin-top: 0.15rem;
    }

    /* Action button */
    .review-action-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: var(--uitm-blue);
        color: white;
        font-size: 0.8rem;
        font-weight: 600;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }

    .review-action-btn:hover {
        background: #1e40af;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
    }

    .review-action-btn svg {
        width: 14px;
        height: 14px;
    }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-state-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.25rem;
        background: rgba(5, 150, 105, 0.1);
        color: var(--success);
    }

    .empty-state-icon svg {
        width: 40px;
        height: 40px;
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

    /* ============================================
       MODAL - INDUSTRIAL DESIGN
       ============================================ */

    /* AGGRESSIVE fix for modal flickering - disable ALL animations and transitions */
    #reviewModal,
    #reviewModal *,
    #reviewModal::before,
    #reviewModal::after,
    #reviewModal *::before,
    #reviewModal *::after {
        -webkit-transition: none !important;
        -moz-transition: none !important;
        -ms-transition: none !important;
        -o-transition: none !important;
        transition: none !important;
        -webkit-animation: none !important;
        -moz-animation: none !important;
        -ms-animation: none !important;
        -o-animation: none !important;
        animation: none !important;
        -webkit-transform: none !important;
        -moz-transform: none !important;
        -ms-transform: none !important;
        -o-transform: none !important;
    }

    /* Disable backdrop animation */
    .modal-backdrop {
        -webkit-transition: none !important;
        transition: none !important;
        opacity: 0.5 !important;
    }

    /* Override Bootstrap's show animation */
    #reviewModal.show {
        display: block !important;
    }

    /* Cards inside modal - no hover effects at all */
    #reviewModal .card,
    #reviewModal .card:hover,
    #reviewModal .card:focus,
    #reviewModal .card:active {
        box-shadow: 0 1px 3px rgba(0,0,0,0.1) !important;
        border: 1px solid #dee2e6 !important;
        transform: none !important;
    }

    /* Buttons inside modal - no hover transforms */
    #reviewModal .btn,
    #reviewModal .btn:hover,
    #reviewModal .btn:focus,
    #reviewModal .btn:active {
        transform: none !important;
        box-shadow: none !important;
    }

    /* Modal dialog positioning - no centering animation */
    #reviewModal .modal-dialog {
        margin: 1.75rem auto !important;
        transform: none !important;
    }

    /* Modal industrial styling */
    #reviewModal .modal-content {
        border: none;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    }

    .modal-header-industrial {
        background: linear-gradient(135deg, var(--industrial-dark) 0%, var(--uitm-blue) 100%);
        padding: 1.25rem 1.5rem;
        border-bottom: none;
        position: relative;
        overflow: hidden;
    }

    .modal-header-industrial::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px),
            linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px);
        background-size: 20px 20px;
    }

    .modal-header-industrial .modal-title {
        color: white;
        font-size: 1rem;
        font-weight: 600;
        position: relative;
    }

    .modal-header-industrial .btn-close-white {
        filter: brightness(0) invert(1);
        opacity: 0.7;
        position: relative;
    }

    .modal-header-industrial .btn-close-white:hover {
        opacity: 1;
    }

    /* Modal User Profile Section */
    .modal-user-profile {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.25rem 1.5rem;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }

    .modal-avatar {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'IBM Plex Mono', monospace;
        font-size: 1.125rem;
        font-weight: 700;
        color: white;
        background: linear-gradient(135deg, #667eea, #764ba2);
        flex-shrink: 0;
    }

    .modal-user-details {
        flex: 1;
    }

    .modal-user-name {
        font-weight: 600;
        font-size: 1rem;
        color: var(--industrial-dark);
        margin: 0 0 0.2rem;
    }

    .modal-user-email {
        font-size: 0.8rem;
        color: #64748b;
        font-family: 'IBM Plex Mono', monospace;
        margin: 0 0 0.4rem;
    }

    .modal-user-meta {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .modal-role-badge {
        font-size: 0.72rem;
        font-weight: 600;
        padding: 0.2rem 0.5rem;
        border-radius: 5px;
        background: var(--uitm-blue);
        color: white;
    }

    .modal-date-text {
        font-size: 0.72rem;
        color: #94a3b8;
    }

    /* Modal body sections */
    .modal-body-industrial {
        padding: 1.25rem 1.5rem;
    }

    /* Program Assignment Card */
    .program-assignment-card {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 1.25rem;
    }

    .program-assignment-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 1rem;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }

    .program-assignment-header-left {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--industrial-dark);
    }

    .program-assignment-header-left svg {
        width: 16px;
        height: 16px;
        color: var(--uitm-blue);
    }

    .toggle-edit-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.35rem 0.65rem;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        background: white;
        color: var(--industrial-gray);
        cursor: pointer;
    }

    .toggle-edit-btn:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
    }

    .program-assignment-body {
        padding: 1rem;
    }

    .program-view-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.8rem;
        font-weight: 500;
        padding: 0.4rem 0.75rem;
        border-radius: 6px;
        background: rgba(13, 148, 136, 0.08);
        color: var(--teal);
        border: 1px solid rgba(13, 148, 136, 0.15);
    }

    .edit-alert {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.78rem;
        padding: 0.65rem 0.85rem;
        border-radius: 8px;
        background: rgba(245, 158, 11, 0.08);
        border: 1px solid rgba(245, 158, 11, 0.15);
        color: var(--industrial-dark);
        margin-bottom: 1rem;
    }

    .edit-alert svg {
        width: 14px;
        height: 14px;
        color: var(--uitm-amber);
        flex-shrink: 0;
    }

    /* Edit sections form styling */
    .edit-section-label {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--industrial-dark);
        margin-bottom: 0.5rem;
    }

    .program-assignment-body .form-check {
        padding: 0.5rem 0.75rem;
        border-radius: 6px;
        margin-bottom: 0.25rem;
    }

    .program-assignment-body .form-check:hover {
        background: #f8fafc;
    }

    .program-assignment-body .form-check-label {
        font-size: 0.8rem;
        color: var(--industrial-dark);
    }

    .program-assignment-body .form-select {
        font-size: 0.85rem;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 0.5rem 0.75rem;
    }

    .program-assignment-body .form-select:focus {
        border-color: var(--uitm-blue);
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
    }

    .program-code-label {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.72rem;
        font-weight: 700;
        color: var(--uitm-blue);
        margin-bottom: 0.25rem;
    }

    .checkbox-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 0.35rem;
        margin-top: 0.25rem;
        margin-bottom: 0.75rem;
    }

    /* Action Selection Card */
    .action-selection-card {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
    }

    .action-toggle-group {
        display: grid;
        grid-template-columns: 1fr 1fr;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }

    .action-toggle-group .btn-check + label {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.75rem;
        font-size: 0.85rem;
        font-weight: 600;
        border: none;
        border-radius: 0;
        cursor: pointer;
        background: transparent;
        color: #64748b;
    }

    .action-toggle-group .btn-check:checked + .action-label-approve {
        background: rgba(5, 150, 105, 0.08);
        color: var(--success);
        border-bottom: 3px solid var(--success);
    }

    .action-toggle-group .btn-check:checked + .action-label-reject {
        background: rgba(220, 38, 38, 0.08);
        color: var(--danger);
        border-bottom: 3px solid var(--danger);
    }

    .action-body {
        padding: 1rem;
    }

    .action-placeholder {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        color: #94a3b8;
    }

    .action-placeholder svg {
        width: 16px;
        height: 16px;
    }

    .approve-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        color: var(--success);
        background: rgba(5, 150, 105, 0.05);
        padding: 0.75rem 1rem;
        border-radius: 8px;
        border: 1px solid rgba(5, 150, 105, 0.12);
    }

    .approve-info svg {
        width: 16px;
        height: 16px;
        flex-shrink: 0;
    }

    .reject-section .reject-label {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--danger);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .reject-section .reject-label .required {
        color: var(--danger);
    }

    .reject-textarea {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.85rem;
        resize: vertical;
        min-height: 80px;
    }

    .reject-textarea:focus {
        outline: none;
        border-color: var(--danger);
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    }

    /* Modal footer */
    .modal-footer-industrial {
        display: flex;
        justify-content: flex-end;
        gap: 0.65rem;
        padding: 1rem 1.5rem;
        border-top: 1px solid #e2e8f0;
        background: #fafbfc;
    }

    .modal-btn-cancel {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.85rem;
        font-weight: 500;
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        background: white;
        color: var(--industrial-gray);
        cursor: pointer;
    }

    .modal-btn-cancel:hover {
        background: #f1f5f9;
    }

    .modal-btn-submit {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        border: none;
        cursor: pointer;
    }

    .modal-btn-submit.primary {
        background: var(--uitm-blue);
        color: white;
    }

    .modal-btn-submit.success {
        background: var(--success);
        color: white;
    }

    .modal-btn-submit.danger {
        background: var(--danger);
        color: white;
    }

    .modal-btn-submit:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .modal-btn-submit svg {
        width: 14px;
        height: 14px;
    }

    /* ============================================
       RESPONSIVE
       ============================================ */
    @media (max-width: 767px) {
        .pending-approvals-page {
            padding: 0 1rem 1.5rem;
        }

        .page-header {
            margin: -1rem -1rem 1.25rem;
            padding: 1.5rem 1rem;
        }

        .page-header-info h1 {
            font-size: 1.25rem;
        }

        .stats-bar {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .stat-divider {
            width: 100%;
            height: 1px;
        }

        .industrial-table thead {
            display: none;
        }

        .industrial-table tbody tr {
            display: block;
            padding: 1rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .industrial-table tbody td {
            display: block;
            padding: 0.35rem 0;
            text-align: left;
        }

        .industrial-table tbody td::before {
            content: attr(data-label);
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #94a3b8;
            display: block;
            margin-bottom: 0.15rem;
        }
    }
</style>
@endpush

@section('content')
<div class="pending-approvals-page">
    {{-- Industrial Page Header --}}
    <header class="page-header">
        <div class="page-header-grid"></div>
        <div class="page-header-accent"></div>
        <div class="page-header-content">
            <div class="page-header-left">
                <div class="page-header-badge">
                    <span class="page-header-badge-text">HEA</span>
                    <span class="page-header-badge-line"></span>
                </div>
                <div class="page-header-info">
                    <h1>Pending User Approvals</h1>
                    <p>Review and manage staff registration requests</p>
                </div>
            </div>
            <div class="page-header-right">
                <a href="{{ route('hea.users.active') }}" class="header-link">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Active Users
                </a>
                <a href="{{ route('hea.dashboard') }}" class="header-link">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                </a>
            </div>
        </div>
    </header>

    {{-- Toast Messages --}}
    @if(session('success') || session('warning') || $errors->any())
    <div class="toast-container" id="toastContainer">
        @if(session('success'))
        <div class="toast-msg success" role="alert">
            <div class="toast-icon">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div class="toast-content">{{ session('success') }}</div>
            <button type="button" class="toast-close" onclick="this.parentElement.style.animation='toastSlideOut 0.3s ease forwards';setTimeout(()=>this.parentElement.remove(),300)">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        @endif

        @if(session('warning'))
        <div class="toast-msg warning" role="alert">
            <div class="toast-icon">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.832c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            </div>
            <div class="toast-content">{{ session('warning') }}</div>
            <button type="button" class="toast-close" onclick="this.parentElement.style.animation='toastSlideOut 0.3s ease forwards';setTimeout(()=>this.parentElement.remove(),300)">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        @endif

        @if($errors->any())
        <div class="toast-msg error" role="alert">
            <div class="toast-icon">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="toast-content">
                @foreach($errors->all() as $error)
                    {{ $error }}@if(!$loop->last)<br>@endif
                @endforeach
            </div>
            <button type="button" class="toast-close" onclick="this.parentElement.style.animation='toastSlideOut 0.3s ease forwards';setTimeout(()=>this.parentElement.remove(),300)">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        @endif
    </div>
    @endif

    {{-- Stats Bar --}}
    <div class="stats-bar">
        <div class="stat-item">
            <div class="stat-icon amber">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-data">
                <span class="stat-value">{{ $pendingUsers->count() }}</span>
                <span class="stat-label">Pending Registrations</span>
            </div>
        </div>

        @if($pendingUsers->isNotEmpty())
        <div class="stat-divider"></div>
        <div class="stat-breakdown">
            @php
                $aaCnt = $pendingUsers->where('requested_role', 'academic_advisor')->count();
                $pcCnt = $pendingUsers->where('requested_role', 'program_coordinator')->count();
                $rpCnt = $pendingUsers->where('requested_role', 'resource_person')->count();
            @endphp
            @if($aaCnt > 0)
            <span class="stat-breakdown-chip aa">
                <span class="chip-count">{{ $aaCnt }}</span> Academic Advisor{{ $aaCnt > 1 ? 's' : '' }}
            </span>
            @endif
            @if($pcCnt > 0)
            <span class="stat-breakdown-chip pc">
                <span class="chip-count">{{ $pcCnt }}</span> Coordinator{{ $pcCnt > 1 ? 's' : '' }}
            </span>
            @endif
            @if($rpCnt > 0)
            <span class="stat-breakdown-chip rp">
                <span class="chip-count">{{ $rpCnt }}</span> Resource Person{{ $rpCnt > 1 ? 's' : '' }}
            </span>
            @endif
        </div>
        @endif
    </div>

    {{-- Pending Users Table --}}
    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-header-info">
                <div class="table-card-header-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
                <h3>Registration Queue
                    @if($pendingUsers->isNotEmpty())
                    <span class="table-card-header-count">{{ $pendingUsers->count() }}</span>
                    @endif
                </h3>
            </div>
        </div>

        @if($pendingUsers->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h4>All Clear</h4>
                <p>No pending registration approvals at this time.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="industrial-table">
                    <thead>
                        <tr>
                            <th>Applicant</th>
                            <th>Email</th>
                            <th>Requested Role</th>
                            <th>Requested Programs</th>
                            <th>Registration Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingUsers as $user)
                        @php
                            $nameParts = explode(' ', $user->name);
                            $initials = count($nameParts) >= 2
                                ? strtoupper($nameParts[0][0] . $nameParts[count($nameParts)-1][0])
                                : strtoupper(substr($user->name, 0, 2));
                            $roleClass = match($user->requested_role) {
                                'academic_advisor' => 'aa',
                                'program_coordinator' => 'pc',
                                'resource_person' => 'rp',
                                default => 'default'
                            };
                            $roleCode = match($user->requested_role) {
                                'academic_advisor' => 'AA',
                                'program_coordinator' => 'PC',
                                'resource_person' => 'RP',
                                default => '?'
                            };
                        @endphp
                        <tr>
                            <td data-label="Applicant">
                                <div class="user-name-cell">
                                    <div class="user-avatar {{ $roleClass }}">{{ $initials }}</div>
                                    <span class="user-name-text">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td data-label="Email">
                                <span class="user-email-text">{{ $user->email }}</span>
                            </td>
                            <td data-label="Role">
                                <span class="role-badge-industrial {{ $roleClass }}">
                                    <span class="role-badge-code">{{ $roleCode }}</span>
                                    {{ $user->requested_role_label }}
                                </span>
                            </td>
                            <td data-label="Programs">
                                <div class="program-info-cell">
                                    {{ $user->formatted_program_info }}
                                </div>
                            </td>
                            <td data-label="Date">
                                <div class="date-cell">
                                    {{ $user->created_at->format('d M Y') }}
                                    <span class="date-relative">{{ $user->created_at->diffForHumans() }}</span>
                                </div>
                            </td>
                            <td data-label="Actions">
                                <button type="button"
                                        class="review-action-btn review-btn"
                                        data-user-id="{{ $user->id }}"
                                        data-user-name="{{ $user->name }}"
                                        data-user-email="{{ $user->email }}"
                                        data-user-role="{{ $user->requested_role }}"
                                        data-user-role-label="{{ $user->requested_role_label }}"
                                        data-user-programs="{{ $user->formatted_program_info }}"
                                        data-user-date="{{ $user->created_at->format('M j, Y g:i A') }}"
                                        data-user-programs-raw="{{ is_array($user->requested_programs) ? json_encode($user->requested_programs) : $user->requested_programs }}">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Review
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

{{-- Single Shared Modal - NO fade class to prevent flickering --}}
<div class="modal" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            {{-- Industrial Modal Header --}}
            <div class="modal-header modal-header-industrial">
                <h5 class="modal-title" id="reviewModalLabel">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right: 0.35rem;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    Review User Registration
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="approvalForm" method="POST" action="">
                @csrf
                <input type="hidden" name="modified_programs" id="modifiedPrograms" value="0">

                {{-- User Profile Section --}}
                <div class="modal-user-profile">
                    <div class="modal-avatar" id="userAvatar"></div>
                    <div class="modal-user-details">
                        <h5 id="modalUserName" class="modal-user-name"></h5>
                        <p id="modalUserEmail" class="modal-user-email"></p>
                        <div class="modal-user-meta">
                            <span id="modalUserRole" class="modal-role-badge"></span>
                            <span id="modalUserDate" class="modal-date-text"></span>
                        </div>
                    </div>
                </div>

                <div class="modal-body modal-body-industrial">
                    {{-- Program Assignment Section --}}
                    <div class="program-assignment-card">
                        <div class="program-assignment-header">
                            <div class="program-assignment-header-left">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                Program Assignment
                            </div>
                            <button type="button" class="toggle-edit-btn" id="toggleEditBtn">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </div>
                        <div class="program-assignment-body">
                            {{-- View Mode --}}
                            <div id="viewModeSection">
                                <span style="font-size: 0.8rem; font-weight: 500; color: #64748b; margin-right: 0.5rem;">Assigned:</span>
                                <span id="modalUserPrograms" class="program-view-badge"></span>
                            </div>

                            {{-- Edit Mode --}}
                            <div id="editModeSection" style="display: none;">
                                <div class="edit-alert">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Modify the program assignment below before approving
                                </div>

                                {{-- Academic Advisor Edit --}}
                                <div id="editAcademicAdvisor" style="display: none;">
                                    <div class="edit-section-label">Select Program Groups:</div>
                                    @foreach($programGroups as $programCode => $programData)
                                        <div class="mb-2">
                                            <div class="program-code-label">{{ $programCode }}</div>
                                            <div class="checkbox-grid">
                                                @foreach($programData['groups'] as $group)
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox"
                                                               name="program_groups[]"
                                                               value="{{ $group }}"
                                                               id="grp_{{ $group }}">
                                                        <label class="form-check-label" for="grp_{{ $group }}">{{ $group }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Coordinator Edit --}}
                                <div id="editCoordinator" style="display: none;">
                                    <div class="edit-section-label">Select Category:</div>
                                    @foreach($coordinatorCategories as $categoryKey => $categoryData)
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio"
                                                   name="program_category"
                                                   value="{{ $categoryKey }}"
                                                   id="cat_{{ $categoryKey }}">
                                            <label class="form-check-label" for="cat_{{ $categoryKey }}">
                                                <strong>{{ $categoryData['label'] }}</strong> - {{ implode(', ', $categoryData['programs']) }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Resource Person Edit --}}
                                <div id="editResourcePerson" style="display: none;">
                                    <div class="edit-section-label">Select Program:</div>
                                    <select name="assigned_program" class="form-select" id="resourcePersonProgram">
                                        @foreach($supportedPrograms as $code => $name)
                                            <option value="{{ $code }}">{{ $code }} - {{ Str::limit($name, 40) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Action Selection --}}
                    <div class="action-selection-card">
                        <div class="action-toggle-group" role="group">
                            <input type="radio" class="btn-check" name="actionType" id="actionApprove" value="approve">
                            <label class="action-label-approve" for="actionApprove">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Approve
                            </label>

                            <input type="radio" class="btn-check" name="actionType" id="actionReject" value="reject">
                            <label class="action-label-reject" for="actionReject">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Reject
                            </label>
                        </div>
                        <div class="action-body">
                            <div id="noSelectionInfo">
                                <div class="action-placeholder">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11"/>
                                    </svg>
                                    Select an action above to proceed
                                </div>
                            </div>
                            <div id="approveInfo" style="display: none;">
                                <div class="approve-info">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    User will receive an email verification link after approval.
                                </div>
                            </div>
                            <div id="rejectInfo" style="display: none;">
                                <input type="hidden" name="reject" id="rejectInput" value="0">
                                <div class="reject-section">
                                    <label class="reject-label">
                                        Reason for Rejection <span class="required">*</span>
                                    </label>
                                    <textarea name="rejection_reason" class="reject-textarea" rows="3"
                                              placeholder="Please provide a reason for rejection..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer modal-footer-industrial">
                    <button type="button" class="modal-btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="modal-btn-submit primary" id="submitBtn" disabled>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                        Select Action
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modalEl = document.getElementById('reviewModal');
    let bsModal = null;
    let currentUserRole = null;

    // Initialize modal once - reuse same instance
    try {
        bsModal = bootstrap.Modal.getOrCreateInstance(modalEl, {
            backdrop: 'static',
            keyboard: false
        });
    } catch(e) {
        console.error('Modal init error:', e);
    }

    // Helper function to populate modal
    function populateModal(btn) {
        const userId = btn.dataset.userId;
        currentUserRole = btn.dataset.userRole;

        // Update form action
        document.getElementById('approvalForm').action = '/hea/users/' + userId + '/approve';

        // Update modal content
        const nameParts = btn.dataset.userName.split(' ');
        const initials = nameParts.length >= 2
            ? (nameParts[0][0] + nameParts[nameParts.length-1][0]).toUpperCase()
            : btn.dataset.userName.substring(0, 2).toUpperCase();

        document.getElementById('userAvatar').textContent = initials;
        document.getElementById('modalUserName').textContent = btn.dataset.userName;
        document.getElementById('modalUserEmail').textContent = btn.dataset.userEmail;
        document.getElementById('modalUserRole').textContent = btn.dataset.userRoleLabel;
        document.getElementById('modalUserDate').textContent = 'Registered: ' + btn.dataset.userDate;
        document.getElementById('modalUserPrograms').textContent = btn.dataset.userPrograms;

        // Reset form state
        document.getElementById('viewModeSection').style.display = 'block';
        document.getElementById('editModeSection').style.display = 'none';
        document.getElementById('toggleEditBtn').innerHTML = '<i class="fas fa-edit"></i> Edit';
        document.getElementById('modifiedPrograms').value = '0';

        // Reset action selection - no default
        document.getElementById('actionApprove').checked = false;
        document.getElementById('actionReject').checked = false;
        document.getElementById('noSelectionInfo').style.display = 'block';
        document.getElementById('approveInfo').style.display = 'none';
        document.getElementById('rejectInfo').style.display = 'none';
        document.getElementById('rejectInput').value = '0';
        document.getElementById('submitBtn').className = 'modal-btn-submit primary';
        document.getElementById('submitBtn').innerHTML = '<svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg> Select Action';
        document.getElementById('submitBtn').disabled = true;

        // Hide all edit sections first
        document.getElementById('editAcademicAdvisor').style.display = 'none';
        document.getElementById('editCoordinator').style.display = 'none';
        document.getElementById('editResourcePerson').style.display = 'none';

        // Setup role-specific edit section
        if (currentUserRole === 'academic_advisor') {
            document.getElementById('editAcademicAdvisor').style.display = 'block';
            document.querySelectorAll('input[name="program_groups[]"]').forEach(function(cb) { cb.checked = false; });
            try {
                const programs = JSON.parse(btn.dataset.userProgramsRaw || '[]');
                if (Array.isArray(programs)) {
                    programs.forEach(function(p) {
                        const cb = document.getElementById('grp_' + (p.group || ''));
                        if (cb) cb.checked = true;
                    });
                }
            } catch(e) {}
        } else if (currentUserRole === 'program_coordinator') {
            document.getElementById('editCoordinator').style.display = 'block';
            try {
                const data = JSON.parse(btn.dataset.userProgramsRaw || '{}');
                const cat = data.category || '';
                const radio = document.getElementById('cat_' + cat);
                if (radio) radio.checked = true;
            } catch(e) {}
        } else if (currentUserRole === 'resource_person') {
            document.getElementById('editResourcePerson').style.display = 'block';
            try {
                const data = JSON.parse(btn.dataset.userProgramsRaw || '{}');
                document.getElementById('resourcePersonProgram').value = data.program || '';
            } catch(e) {}
        }
    }

    // Handle Review button clicks using event delegation
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.review-btn');
        if (btn) {
            e.preventDefault();
            e.stopPropagation();
            populateModal(btn);
            if (bsModal) {
                bsModal.show();
            }
        }
    });

    // Toggle Edit Mode
    document.getElementById('toggleEditBtn').addEventListener('click', function(e) {
        e.preventDefault();
        const viewMode = document.getElementById('viewModeSection');
        const editMode = document.getElementById('editModeSection');

        if (editMode.style.display === 'none') {
            viewMode.style.display = 'none';
            editMode.style.display = 'block';
            this.innerHTML = '<i class="fas fa-eye"></i> View';
            document.getElementById('modifiedPrograms').value = '1';
        } else {
            viewMode.style.display = 'block';
            editMode.style.display = 'none';
            this.innerHTML = '<i class="fas fa-edit"></i> Edit';
            document.getElementById('modifiedPrograms').value = '0';
        }
    });

    // Handle Approve/Reject toggle
    document.getElementById('actionApprove').addEventListener('change', function() {
        if (this.checked) {
            document.getElementById('noSelectionInfo').style.display = 'none';
            document.getElementById('approveInfo').style.display = 'block';
            document.getElementById('rejectInfo').style.display = 'none';
            document.getElementById('rejectInput').value = '0';
            document.getElementById('submitBtn').className = 'modal-btn-submit success';
            document.getElementById('submitBtn').innerHTML = '<svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Approve Registration';
            document.getElementById('submitBtn').disabled = false;
        }
    });

    document.getElementById('actionReject').addEventListener('change', function() {
        if (this.checked) {
            document.getElementById('noSelectionInfo').style.display = 'none';
            document.getElementById('approveInfo').style.display = 'none';
            document.getElementById('rejectInfo').style.display = 'block';
            document.getElementById('rejectInput').value = '1';
            document.getElementById('submitBtn').className = 'modal-btn-submit danger';
            document.getElementById('submitBtn').innerHTML = '<svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg> Reject Registration';
            document.getElementById('submitBtn').disabled = false;
        }
    });

    // Auto-dismiss toasts after 6 seconds
    setTimeout(function() {
        var toasts = document.querySelectorAll('.toast-msg');
        toasts.forEach(function(toast) {
            toast.style.animation = 'toastSlideOut 0.3s ease forwards';
            setTimeout(function() { toast.remove(); }, 300);
        });
    }, 6000);
});
</script>
@endpush
