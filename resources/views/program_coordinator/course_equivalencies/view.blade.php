@extends('layouts.app')

@push('styles')
<!-- IBM Plex Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<style>
    /* ========================================
       INDUSTRIAL INSTITUTIONAL DESIGN SYSTEM
       All Course Mappings View
       ======================================== */

    :root {
        /* UiTM Color Palette */
        --uitm-primary: #1e3a8a;
        --uitm-primary-dark: #1e2d5b;
        --uitm-primary-light: #3b5998;
        --uitm-amber: #f59e0b;
        --uitm-amber-dark: #d97706;
        --uitm-green: #10b981;
        --uitm-green-dark: #059669;
        --uitm-red: #dc2626;
        --uitm-red-light: #fef2f2;

        /* Neutral Palette */
        --slate-50: #f8fafc;
        --slate-100: #f1f5f9;
        --slate-200: #e2e8f0;
        --slate-300: #cbd5e1;
        --slate-400: #94a3b8;
        --slate-500: #64748b;
        --slate-600: #475569;
        --slate-700: #334155;
        --slate-800: #1e293b;
        --slate-900: #0f172a;

        /* Typography */
        --font-sans: 'IBM Plex Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        --font-mono: 'IBM Plex Mono', monospace;

        /* Shadows */
        --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        --shadow-industrial: 0 4px 20px rgba(30, 58, 138, 0.15);
    }

    .course-mappings-page {
        font-family: var(--font-sans);
        background: var(--slate-100);
        min-height: 100vh;
        padding-bottom: 3rem;
    }

    /* ========================================
       PAGE HEADER - Industrial Style
       ======================================== */

    .page-header-industrial {
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        position: relative;
        padding: 2rem 0;
        margin-bottom: 2rem;
        overflow: hidden;
    }

    .page-header-industrial::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image:
            linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px),
            linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px);
        background-size: 20px 20px;
        pointer-events: none;
    }

    .page-header-industrial::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--uitm-amber), var(--uitm-green), var(--uitm-amber));
    }

    .header-content {
        position: relative;
        z-index: 1;
    }

    .eyebrow-text {
        font-family: var(--font-mono);
        font-size: 0.75rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        color: var(--uitm-amber);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .eyebrow-text::before {
        content: '';
        width: 24px;
        height: 2px;
        background: var(--uitm-amber);
    }

    .page-title-main {
        font-family: var(--font-sans);
        font-size: 1.875rem;
        font-weight: 700;
        color: white;
        margin: 0 0 0.5rem 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .page-title-main i {
        font-size: 1.5rem;
        opacity: 0.9;
    }

    .page-subtitle {
        font-size: 0.95rem;
        color: rgba(255, 255, 255, 0.75);
        margin: 0;
    }

    .header-actions {
        display: flex;
        gap: 0.75rem;
        align-items: center;
    }

    .btn-back {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-back:hover {
        background: rgba(255, 255, 255, 0.2);
        border-color: rgba(255, 255, 255, 0.3);
        color: white;
        transform: translateX(-2px);
    }

    .btn-add-mapping-industrial {
        background: linear-gradient(135deg, var(--uitm-amber) 0%, var(--uitm-amber-dark) 100%);
        color: white;
        font-weight: 600;
        font-size: 0.9rem;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        position: relative;
        overflow: hidden;
    }

    .btn-add-mapping-industrial::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.5s;
    }

    .btn-add-mapping-industrial:hover::before {
        left: 100%;
    }

    .btn-add-mapping-industrial:hover {
        background: linear-gradient(135deg, var(--uitm-amber-dark) 0%, #b45309 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(245, 158, 11, 0.5);
        color: white;
    }

    /* ========================================
       STATISTICS CARDS
       ======================================== */

    .stats-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--slate-200);
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
    }

    .stat-card.stat-total::before {
        background: linear-gradient(90deg, var(--uitm-primary), var(--uitm-primary-light));
    }

    .stat-card.stat-eligible::before {
        background: linear-gradient(90deg, var(--uitm-green), var(--uitm-green-dark));
    }

    .stat-card.stat-not-eligible::before {
        background: linear-gradient(90deg, var(--uitm-amber), var(--uitm-amber-dark));
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .stat-total .stat-icon {
        background: rgba(30, 58, 138, 0.1);
        color: var(--uitm-primary);
    }

    .stat-eligible .stat-icon {
        background: rgba(16, 185, 129, 0.1);
        color: var(--uitm-green);
    }

    .stat-not-eligible .stat-icon {
        background: rgba(245, 158, 11, 0.1);
        color: var(--uitm-amber);
    }

    .stat-icon i {
        font-size: 1.25rem;
    }

    .stat-value {
        font-family: var(--font-mono);
        font-size: 2rem;
        font-weight: 600;
        line-height: 1;
        margin-bottom: 0.25rem;
    }

    .stat-total .stat-value { color: var(--uitm-primary); }
    .stat-eligible .stat-value { color: var(--uitm-green); }
    .stat-not-eligible .stat-value { color: var(--uitm-amber); }

    .stat-label {
        font-size: 0.875rem;
        color: var(--slate-500);
        font-weight: 500;
    }

    /* ========================================
       FILTER PANEL
       ======================================== */

    .filter-panel {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--slate-200);
    }

    .filter-panel-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.25rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--slate-200);
    }

    .filter-panel-header i {
        color: var(--uitm-primary);
        font-size: 1.1rem;
    }

    .filter-panel-title {
        font-weight: 600;
        font-size: 1rem;
        color: var(--slate-800);
        margin: 0;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: 1fr 1fr auto;
        gap: 1.25rem;
        align-items: end;
    }

    .filter-group label {
        display: block;
        font-size: 0.8125rem;
        font-weight: 600;
        color: var(--slate-700);
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .filter-group label i {
        margin-right: 0.375rem;
        color: var(--slate-400);
    }

    .filter-select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid var(--slate-200);
        border-radius: 8px;
        font-family: var(--font-sans);
        font-size: 0.9375rem;
        color: var(--slate-700);
        background: white;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .filter-select:focus {
        outline: none;
        border-color: var(--uitm-primary);
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
    }

    .btn-reset {
        background: var(--slate-100);
        border: 2px solid var(--slate-200);
        color: var(--slate-600);
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9375rem;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }

    .btn-reset:hover {
        background: var(--slate-200);
        border-color: var(--slate-300);
        color: var(--slate-700);
    }

    /* ========================================
       DATA TABLE PANEL
       ======================================== */

    .table-panel {
        background: white;
        border-radius: 12px;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--slate-200);
        overflow: hidden;
    }

    .table-panel-header {
        background: linear-gradient(135deg, var(--slate-800) 0%, var(--slate-900) 100%);
        padding: 1.25rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .table-panel-title {
        color: white;
        font-weight: 600;
        font-size: 1.0625rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .table-panel-title i {
        color: var(--uitm-amber);
    }

    .table-panel-subtitle {
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.8125rem;
        margin: 0.25rem 0 0 0;
    }

    .search-container {
        position: relative;
        width: 320px;
    }

    .search-input {
        width: 100%;
        padding: 0.625rem 1rem 0.625rem 2.75rem;
        border: none;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.1);
        color: white;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .search-input::placeholder {
        color: rgba(255, 255, 255, 0.5);
    }

    .search-input:focus {
        outline: none;
        background: rgba(255, 255, 255, 0.15);
        box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.3);
    }

    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: rgba(255, 255, 255, 0.5);
        font-size: 0.875rem;
    }

    .table-body {
        padding: 0;
    }

    /* ========================================
       DATA TABLE STYLES
       ======================================== */

    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.875rem;
    }

    .data-table thead {
        background: var(--slate-50);
        border-bottom: 2px solid var(--slate-200);
    }

    .data-table thead th {
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--slate-600);
        white-space: nowrap;
    }

    .data-table thead th.text-center {
        text-align: center;
    }

    .data-table tbody tr {
        border-bottom: 1px solid var(--slate-100);
        transition: background 0.15s ease;
    }

    .data-table tbody tr:hover {
        background: var(--slate-50);
    }

    .data-table tbody td {
        padding: 1rem;
        vertical-align: middle;
        color: var(--slate-700);
    }

    .data-table tbody td.text-center {
        text-align: center;
    }

    .row-number {
        font-family: var(--font-mono);
        font-size: 0.8125rem;
        color: var(--slate-400);
        font-weight: 500;
    }

    .course-code {
        font-family: var(--font-mono);
        font-weight: 600;
        color: var(--slate-800);
        font-size: 0.875rem;
    }

    .course-name {
        color: var(--slate-600);
        font-size: 0.8125rem;
        line-height: 1.4;
    }

    .credit-cell {
        font-family: var(--font-mono);
        font-weight: 500;
        color: var(--slate-600);
    }

    .institution-name {
        color: var(--slate-600);
        font-size: 0.8125rem;
        margin-bottom: 0.25rem;
    }

    /* Badges */
    .badge-program {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.625rem;
        background: var(--slate-800);
        color: white;
        border-radius: 4px;
        font-family: var(--font-mono);
        font-size: 0.6875rem;
        font-weight: 600;
        letter-spacing: 0.03em;
    }

    .badge-category {
        display: inline-flex;
        align-items: center;
        padding: 0.1875rem 0.5rem;
        border-radius: 4px;
        font-size: 0.6875rem;
        font-weight: 600;
    }

    .badge-category.internal {
        background: rgba(30, 58, 138, 0.1);
        color: var(--uitm-primary);
    }

    .badge-category.external {
        background: rgba(14, 165, 233, 0.1);
        color: #0ea5e9;
    }

    .badge-match {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-family: var(--font-mono);
        font-size: 0.8125rem;
        font-weight: 600;
    }

    .badge-match.high {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(16, 185, 129, 0.1) 100%);
        color: var(--uitm-green-dark);
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    .badge-match.low {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(245, 158, 11, 0.1) 100%);
        color: var(--uitm-amber-dark);
        border: 1px solid rgba(245, 158, 11, 0.2);
    }

    .eligible-icon {
        font-size: 1.25rem;
    }

    .eligible-icon.yes {
        color: var(--uitm-green);
    }

    .eligible-icon.no {
        color: var(--uitm-red);
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 0.375rem;
        justify-content: center;
    }

    .btn-action {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8125rem;
        border: 1px solid;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .btn-action.edit {
        background: rgba(30, 58, 138, 0.05);
        border-color: rgba(30, 58, 138, 0.2);
        color: var(--uitm-primary);
    }

    .btn-action.edit:hover {
        background: var(--uitm-primary);
        border-color: var(--uitm-primary);
        color: white;
        transform: translateY(-1px);
    }

    .btn-action.delete {
        background: rgba(220, 38, 38, 0.05);
        border-color: rgba(220, 38, 38, 0.2);
        color: var(--uitm-red);
    }

    .btn-action.delete:hover {
        background: var(--uitm-red);
        border-color: var(--uitm-red);
        color: white;
        transform: translateY(-1px);
    }

    /* ========================================
       EXPORT BUTTONS
       ======================================== */

    .export-section {
        padding: 1.25rem 1.5rem;
        background: var(--slate-50);
        border-top: 1px solid var(--slate-200);
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
    }

    .btn-export {
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
        cursor: pointer;
        border: none;
    }

    .btn-export.excel {
        background: var(--uitm-green);
        color: white;
    }

    .btn-export.excel:hover {
        background: var(--uitm-green-dark);
        transform: translateY(-1px);
    }

    .btn-export.print {
        background: var(--slate-600);
        color: white;
    }

    .btn-export.print:hover {
        background: var(--slate-700);
        transform: translateY(-1px);
    }

    /* ========================================
       LOADING & EMPTY STATES
       ======================================== */

    .loading-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .loading-spinner {
        width: 48px;
        height: 48px;
        border: 3px solid var(--slate-200);
        border-top-color: var(--uitm-primary);
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 1rem;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .loading-text {
        color: var(--slate-500);
        font-size: 0.9375rem;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-icon {
        width: 80px;
        height: 80px;
        background: var(--slate-100);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }

    .empty-icon i {
        font-size: 2rem;
        color: var(--slate-400);
    }

    .empty-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--slate-700);
        margin-bottom: 0.5rem;
    }

    .empty-text {
        color: var(--slate-500);
        font-size: 0.9375rem;
    }

    /* ========================================
       INFO ALERT
       ======================================== */

    .info-alert {
        background: linear-gradient(135deg, rgba(30, 58, 138, 0.08) 0%, rgba(30, 58, 138, 0.04) 100%);
        border: 1px solid rgba(30, 58, 138, 0.15);
        border-radius: 10px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: flex-start;
        gap: 0.875rem;
    }

    .info-alert-icon {
        color: var(--uitm-primary);
        font-size: 1.125rem;
        margin-top: 0.125rem;
    }

    .info-alert-content strong {
        color: var(--uitm-primary);
    }

    .info-alert-content span {
        color: var(--slate-600);
    }

    /* ========================================
       MODALS - Industrial Style
       ======================================== */

    .modal-content {
        border: none;
        border-radius: 16px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        overflow: hidden;
    }

    .modal-header-industrial {
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        padding: 1.25rem 1.5rem;
        border: none;
        position: relative;
    }

    .modal-header-industrial::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--uitm-amber);
    }

    .modal-header-industrial.add-mode {
        background: linear-gradient(135deg, var(--uitm-green) 0%, var(--uitm-green-dark) 100%);
    }

    .modal-header-industrial.add-mode::after {
        background: var(--uitm-amber);
    }

    .modal-header-industrial .modal-title {
        color: white;
        font-weight: 600;
        font-size: 1.125rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .modal-header-industrial .btn-close {
        filter: brightness(0) invert(1);
        opacity: 0.8;
    }

    .modal-header-industrial .btn-close:hover {
        opacity: 1;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .form-section {
        margin-bottom: 1.5rem;
    }

    .form-section:last-child {
        margin-bottom: 0;
    }

    .form-section-title {
        font-size: 0.8125rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--slate-500);
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid var(--slate-200);
    }

    .form-label-industrial {
        font-size: 0.8125rem;
        font-weight: 600;
        color: var(--slate-700);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .form-label-industrial .required {
        color: var(--uitm-red);
    }

    .form-control-industrial {
        padding: 0.75rem 1rem;
        border: 2px solid var(--slate-200);
        border-radius: 8px;
        font-family: var(--font-sans);
        font-size: 0.9375rem;
        transition: all 0.2s ease;
    }

    .form-control-industrial:focus {
        outline: none;
        border-color: var(--uitm-primary);
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
    }

    .form-hint {
        font-size: 0.75rem;
        color: var(--slate-500);
        margin-top: 0.375rem;
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }

    .modal-footer-industrial {
        background: var(--slate-50);
        padding: 1rem 1.5rem;
        border-top: 1px solid var(--slate-200);
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
    }

    .btn-modal-cancel {
        background: white;
        border: 2px solid var(--slate-300);
        color: var(--slate-600);
        padding: 0.625rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn-modal-cancel:hover {
        background: var(--slate-100);
        border-color: var(--slate-400);
    }

    .btn-modal-submit {
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        border: none;
        color: white;
        padding: 0.625rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
    }

    .btn-modal-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
        color: white;
    }

    .btn-modal-submit.save-mode {
        background: linear-gradient(135deg, var(--uitm-green) 0%, var(--uitm-green-dark) 100%);
    }

    .btn-modal-submit.save-mode:hover {
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    /* ========================================
       HIGHLIGHT ANIMATION
       ======================================== */

    .newly-added-highlight {
        animation: highlightRow 3s ease-in-out;
    }

    @keyframes highlightRow {
        0% {
            background-color: rgba(16, 185, 129, 0.3);
            transform: scale(1.01);
        }
        10% {
            background-color: rgba(16, 185, 129, 0.15);
            transform: scale(1);
        }
        100% {
            background-color: transparent;
        }
    }

    /* ========================================
       PRINT STYLES
       ======================================== */

    @media print {
        .page-header-industrial,
        .filter-panel,
        .header-actions,
        .export-section,
        .btn-action,
        .search-container,
        .btn-reset {
            display: none !important;
        }

        .course-mappings-page {
            background: white;
            padding: 0;
        }

        .table-panel {
            box-shadow: none;
            border: 1px solid #ddd;
        }

        .table-panel-header {
            background: #333;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }

    /* ========================================
       RESPONSIVE ADJUSTMENTS
       ======================================== */

    @media (max-width: 992px) {
        .stats-row {
            grid-template-columns: 1fr;
        }

        .filter-grid {
            grid-template-columns: 1fr;
        }

        .table-panel-header {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch;
        }

        .search-container {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="course-mappings-page">
    <!-- Page Header -->
    <div class="page-header-industrial">
        <div class="container-fluid">
            <div class="header-content d-flex justify-content-between align-items-center">
                <div>
                    <div class="eyebrow-text">Course Management</div>
                    <h1 class="page-title-main">
                        <i class="fas fa-clipboard-list"></i>
                        All Course Equivalencies
                    </h1>
                    <p class="page-subtitle">Comprehensive view of all course mappings across programs</p>
                </div>
                <div class="header-actions">
                    <a href="{{ $backRoute ?? route('program_coordinator.equivalency_lists.index') }}" class="btn-back">
                        <i class="fas fa-arrow-left"></i>
                        Back
                    </a>
                    @if(in_array(Auth::user()->role, ['program_coordinator', 'resource_person']))
                        <button type="button" class="btn-add-mapping-industrial" data-bs-toggle="modal" data-bs-target="#addMappingModal">
                            <i class="fas fa-plus-circle"></i>
                            Add New Mapping
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Info Alert -->
        <div class="info-alert">
            <i class="fas fa-info-circle info-alert-icon"></i>
            <div class="info-alert-content">
                <strong>Comprehensive View:</strong>
                <span id="filter_description">Showing all published course equivalencies (current and historical data).</span>
            </div>
        </div>

        <!-- Filter Panel -->
        <div class="filter-panel">
            <div class="filter-panel-header">
                <i class="fas fa-filter"></i>
                <h3 class="filter-panel-title">Filter Options</h3>
            </div>
            <div class="filter-grid">
                <div class="filter-group">
                    <label for="degree_program_select">
                        <i class="fas fa-graduation-cap"></i>
                        Select Degree Program
                    </label>
                    <select id="degree_program_select" class="filter-select" required>
                        <option value="ALL" data-name="All Programs" selected>ALL PROGRAMS (View All)</option>
                        @foreach($programs as $program)
                            <option value="{{ $program->code }}" data-name="{{ $program->name }}">
                                {{ $program->code }} - {{ $program->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label for="institution_filter">
                        <i class="fas fa-university"></i>
                        Institution
                    </label>
                    <select id="institution_filter" class="filter-select">
                        <option value="" selected>All Institutions</option>
                        @foreach($institutions ?? [] as $institution)
                            <option value="{{ $institution }}">{{ $institution }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <button type="button" id="reset_filters_btn" class="btn-reset">
                        <i class="fas fa-redo"></i>
                        Reset
                    </button>
                </div>
            </div>
        </div>

        <!-- Hidden filters -->
        <input type="hidden" id="status_filter" value="include_drafts">
        <input type="hidden" id="category_filter" value="">
        <input type="hidden" id="semester_filter" value="">

        <!-- Statistics Cards -->
        <div class="stats-row" id="stats_section" style="display: none;">
            <div class="stat-card stat-total">
                <div class="stat-icon">
                    <i class="fas fa-list-alt"></i>
                </div>
                <div class="stat-value" id="total_equivalencies">0</div>
                <div class="stat-label">Total Mappings</div>
            </div>
            <div class="stat-card stat-eligible">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-value" id="eligible_count">0</div>
                <div class="stat-label">Eligible for Exemption</div>
            </div>
            <div class="stat-card stat-not-eligible">
                <div class="stat-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="stat-value" id="not_eligible_count">0</div>
                <div class="stat-label">Not Eligible</div>
            </div>
        </div>

        <!-- Loading Indicator -->
        <div id="loading_indicator" style="display: none;">
            <div class="table-panel">
                <div class="loading-state">
                    <div class="loading-spinner"></div>
                    <p class="loading-text">Loading course equivalencies...</p>
                </div>
            </div>
        </div>

        <!-- Equivalencies Table -->
        <div id="equivalencies_section" style="display: none;">
            <div class="table-panel">
                <div class="table-panel-header">
                    <div>
                        <h3 class="table-panel-title">
                            <i class="fas fa-table"></i>
                            <span id="program_title">All Programs - Comprehensive View</span>
                        </h3>
                        <p class="table-panel-subtitle" id="table_subtitle">Showing all equivalencies for this program</p>
                    </div>
                    <div class="search-container">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" id="search_input" class="search-input" placeholder="Search course code, name, institution...">
                    </div>
                </div>
                <div class="table-body">
                    <div class="table-responsive">
                        <table class="data-table" id="equivalencies_table">
                            <thead id="table_header">
                                <tr id="header_row">
                                    <th style="width: 5%">#</th>
                                    <th style="width: 12%">Diploma Course</th>
                                    <th style="width: 20%">Diploma Course Name</th>
                                    <th style="width: 6%" class="text-center">Cr</th>
                                    <th style="width: 18%">Institution</th>
                                    <th style="width: 10%">Degree Course</th>
                                    <th style="width: 18%">Degree Course Name</th>
                                    <th style="width: 6%" class="text-center">Cr</th>
                                    <th style="width: 8%" class="text-center">Match %</th>
                                    <th style="width: 7%" class="text-center">Eligible</th>
                                    @if(in_array(Auth::user()->role, ['program_coordinator', 'resource_person']))
                                        <th style="width: 100px; min-width: 100px;" class="text-center">Actions</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody id="equivalencies_tbody">
                                <!-- Dynamic content will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="export-section">
                    <button type="button" class="btn-export excel" onclick="exportToExcel()">
                        <i class="fas fa-file-excel"></i>
                        Export to Excel
                    </button>
                    <button type="button" class="btn-export print" onclick="window.print()">
                        <i class="fas fa-print"></i>
                        Print
                    </button>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div id="empty_state" style="display: none;">
            <div class="table-panel">
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h4 class="empty-title">No Course Equivalencies Found</h4>
                    <p class="empty-text">No equivalencies have been published for the selected degree program yet.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add New Mapping Modal -->
@if(in_array(Auth::user()->role, ['program_coordinator', 'resource_person']))
<div class="modal fade" id="addMappingModal" tabindex="-1" aria-labelledby="addMappingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="addMappingForm">
                @csrf
                <div class="modal-header-industrial add-mode">
                    <h5 class="modal-title" id="addMappingModalLabel">
                        <i class="fas fa-plus-circle"></i>
                        Add New Course Mapping
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Degree Program Section -->
                    <div class="form-section">
                        <div class="form-section-title">Program Information</div>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="add_program_code" class="form-label-industrial">
                                    Degree Program <span class="required">*</span>
                                </label>
                                <select class="form-control form-control-industrial" id="add_program_code" name="program_code" required>
                                    <option value="">Select Degree Program</option>
                                    <option value="CDCS230">CDCS230 - Bachelor of Computer Science (Hons.)</option>
                                    <option value="CDCS251">CDCS251 - Bachelor of Computer Science (Hons.) Netcentric Computing</option>
                                    <option value="CDCS253">CDCS253 - Bachelor of Computer Science (Hons.) Multimedia Computing</option>
                                    <option value="CDCS255">CDCS255 - Bachelor of Computer Science (Hons.) Computer Networking</option>
                                    <option value="CDCS266">CDCS266 - Bachelor of Information Systems (Hons.) Information Systems Engineering</option>
                                </select>
                                <div class="form-hint">
                                    <i class="fas fa-info-circle"></i>
                                    Select the degree program for this course mapping
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Diploma Course Section -->
                    <div class="form-section">
                        <div class="form-section-title">Diploma Course Information</div>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="add_diploma_institution" class="form-label-industrial">
                                    Diploma Institution <span class="required">*</span>
                                </label>
                                <select class="form-control form-control-industrial" id="add_diploma_institution" name="diploma_institution" required>
                                    <option value="">Select or type institution name</option>
                                    @foreach($institutions as $institution)
                                        <option value="{{ $institution }}">{{ $institution }}</option>
                                    @endforeach
                                </select>
                                <div class="form-hint">
                                    <i class="fas fa-info-circle"></i>
                                    Select from existing institutions or type a new one
                                </div>
                            </div>
                            <div class="col-md-8">
                                <label for="add_diploma_course_code" class="form-label-industrial">
                                    Diploma Course Code <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control form-control-industrial" id="add_diploma_course_code" name="diploma_course_code" required placeholder="e.g., CSC138">
                            </div>
                            <div class="col-md-4">
                                <label for="add_diploma_credit_hour" class="form-label-industrial">
                                    Credits <span class="required">*</span>
                                </label>
                                <select class="form-control form-control-industrial" id="add_diploma_credit_hour" name="diploma_credit_hour" required>
                                    <option value="">Select</option>
                                    @for ($i = 1; $i <= 10; $i += 0.5)
                                        <option value="{{ number_format($i, 2, '.', '') }}">{{ number_format($i, 2) }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label for="add_diploma_course_name" class="form-label-industrial">
                                    Diploma Course Name <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control form-control-industrial" id="add_diploma_course_name" name="diploma_course_name" required placeholder="e.g., Data Structures and Algorithms">
                            </div>
                        </div>
                    </div>

                    <!-- Degree Course Section -->
                    <div class="form-section">
                        <div class="form-section-title">Degree Course Information</div>
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label for="add_degree_course_code" class="form-label-industrial">
                                    Degree Course Code <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control form-control-industrial" id="add_degree_course_code" name="degree_course_code" required placeholder="e.g., CSC424">
                            </div>
                            <div class="col-md-4">
                                <label for="add_degree_credit_hour" class="form-label-industrial">
                                    Credits <span class="required">*</span>
                                </label>
                                <select class="form-control form-control-industrial" id="add_degree_credit_hour" name="degree_credit_hour" required>
                                    <option value="">Select</option>
                                    @for ($i = 1; $i <= 10; $i += 0.5)
                                        <option value="{{ number_format($i, 2, '.', '') }}">{{ number_format($i, 2) }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label for="add_degree_course_name" class="form-label-industrial">
                                    Degree Course Name <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control form-control-industrial" id="add_degree_course_name" name="degree_course_name" required placeholder="e.g., Advanced Data Structures">
                            </div>
                        </div>
                    </div>

                    <!-- Match Information -->
                    <div class="form-section">
                        <div class="form-section-title">Match Information</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="add_match_percentage" class="form-label-industrial">
                                    Match Percentage <span class="required">*</span>
                                </label>
                                <input type="number" class="form-control form-control-industrial" id="add_match_percentage" name="match_percentage" min="0" max="100" step="0.01" required placeholder="e.g., 85">
                            </div>
                            <div class="col-md-6">
                                <label for="add_is_eligible" class="form-label-industrial">
                                    Eligible for Exemption <span class="required">*</span>
                                </label>
                                <select class="form-control form-control-industrial" id="add_is_eligible" name="is_eligible" required>
                                    <option value="1">Yes (≥80%)</option>
                                    <option value="0">No (&lt;80%)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer-industrial">
                    <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-modal-submit save-mode">
                        <i class="fas fa-save"></i>
                        Save Mapping
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Mapping Modal -->
<div class="modal fade" id="editMappingModal" tabindex="-1" aria-labelledby="editMappingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editMappingForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_mapping_id" name="mapping_id">
                <div class="modal-header-industrial">
                    <h5 class="modal-title" id="editMappingModalLabel">
                        <i class="fas fa-edit"></i>
                        Edit Course Mapping
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Program Section (Read-only) -->
                    <div class="form-section">
                        <div class="form-section-title">Program Information</div>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="edit_program_code" class="form-label-industrial">Degree Program</label>
                                <input type="text" class="form-control form-control-industrial" id="edit_program_code" name="program_code" readonly style="background: var(--slate-100);">
                                <div class="form-hint">
                                    <i class="fas fa-lock"></i>
                                    Program code cannot be changed after creation
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Diploma Course Section -->
                    <div class="form-section">
                        <div class="form-section-title">Diploma Course Information</div>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="edit_diploma_institution" class="form-label-industrial">
                                    Diploma Institution <span class="required">*</span>
                                </label>
                                <select class="form-control form-control-industrial" id="edit_diploma_institution" name="diploma_institution" required>
                                    <option value="">Select or type institution name</option>
                                    @foreach($institutions as $institution)
                                        <option value="{{ $institution }}">{{ $institution }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-8">
                                <label for="edit_diploma_course_code" class="form-label-industrial">
                                    Diploma Course Code <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control form-control-industrial" id="edit_diploma_course_code" name="diploma_course_code" required>
                            </div>
                            <div class="col-md-4">
                                <label for="edit_diploma_credit_hour" class="form-label-industrial">
                                    Credits <span class="required">*</span>
                                </label>
                                <select class="form-control form-control-industrial" id="edit_diploma_credit_hour" name="diploma_credit_hour" required>
                                    <option value="">Select</option>
                                    @for ($i = 1; $i <= 10; $i += 0.5)
                                        <option value="{{ number_format($i, 2, '.', '') }}">{{ number_format($i, 2) }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label for="edit_diploma_course_name" class="form-label-industrial">
                                    Diploma Course Name <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control form-control-industrial" id="edit_diploma_course_name" name="diploma_course_name" required>
                            </div>
                        </div>
                    </div>

                    <!-- Degree Course Section -->
                    <div class="form-section">
                        <div class="form-section-title">Degree Course Information</div>
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label for="edit_degree_course_code" class="form-label-industrial">
                                    Degree Course Code <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control form-control-industrial" id="edit_degree_course_code" name="degree_course_code" required>
                            </div>
                            <div class="col-md-4">
                                <label for="edit_degree_credit_hour" class="form-label-industrial">
                                    Credits <span class="required">*</span>
                                </label>
                                <select class="form-control form-control-industrial" id="edit_degree_credit_hour" name="degree_credit_hour" required>
                                    <option value="">Select</option>
                                    @for ($i = 1; $i <= 10; $i += 0.5)
                                        <option value="{{ number_format($i, 2, '.', '') }}">{{ number_format($i, 2) }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label for="edit_degree_course_name" class="form-label-industrial">
                                    Degree Course Name <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control form-control-industrial" id="edit_degree_course_name" name="degree_course_name" required>
                            </div>
                        </div>
                    </div>

                    <!-- Match Information -->
                    <div class="form-section">
                        <div class="form-section-title">Match Information</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="edit_match_percentage" class="form-label-industrial">
                                    Match Percentage <span class="required">*</span>
                                </label>
                                <input type="number" class="form-control form-control-industrial" id="edit_match_percentage" name="match_percentage" min="0" max="100" step="0.01" required>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_is_eligible" class="form-label-industrial">
                                    Eligible for Exemption <span class="required">*</span>
                                </label>
                                <select class="form-control form-control-industrial" id="edit_is_eligible" name="is_eligible" required>
                                    <option value="1">Yes (≥80%)</option>
                                    <option value="0">No (&lt;80%)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer-industrial">
                    <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-modal-submit">
                        <i class="fas fa-save"></i>
                        Update Mapping
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
let allEquivalencies = [];
let filteredEquivalencies = [];
let currentProgramCode = '';
let availableSemesters = [];
let availableInstitutions = [];

document.addEventListener('DOMContentLoaded', function() {
    const programSelect = document.getElementById('degree_program_select');
    const statusFilter = document.getElementById('status_filter');
    const categoryFilter = document.getElementById('category_filter');
    const semesterFilter = document.getElementById('semester_filter');
    const institutionFilter = document.getElementById('institution_filter');
    const loadingIndicator = document.getElementById('loading_indicator');
    const equivalenciesSection = document.getElementById('equivalencies_section');
    const emptyState = document.getElementById('empty_state');
    const statsSection = document.getElementById('stats_section');
    const programTitle = document.getElementById('program_title');
    const totalEquivalencies = document.getElementById('total_equivalencies');
    const eligibleCount = document.getElementById('eligible_count');
    const notEligibleCount = document.getElementById('not_eligible_count');
    const equivalenciesTbody = document.getElementById('equivalencies_tbody');
    const searchInput = document.getElementById('search_input');
    const headerRow = document.getElementById('header_row');
    const filterDescription = document.getElementById('filter_description');

    // Load equivalencies automatically when program or institution is selected
    programSelect.addEventListener('change', function() {
        loadEquivalencies();
    });

    institutionFilter.addEventListener('change', function() {
        loadEquivalencies();
    });

    // Reset button event listener
    const resetBtn = document.getElementById('reset_filters_btn');
    resetBtn.addEventListener('click', function() {
        resetFilters();
    });

    function loadEquivalencies() {
        const programCode = programSelect.value;
        const programName = programSelect.options[programSelect.selectedIndex].dataset.name;
        const status = statusFilter.value;
        const category = categoryFilter.value;
        const semester = semesterFilter.value;
        const institution = institutionFilter.value;

        currentProgramCode = programCode;

        if (!programCode) {
            equivalenciesSection.style.display = 'none';
            emptyState.style.display = 'none';
            statsSection.style.display = 'none';
            return;
        }

        // Update table header based on selection
        updateTableHeader(programCode);

        // Show loading state
        loadingIndicator.style.display = 'block';
        equivalenciesSection.style.display = 'none';
        emptyState.style.display = 'none';
        statsSection.style.display = 'none';

        // Set program title
        if (programCode === 'ALL') {
            programTitle.textContent = 'All Programs - Comprehensive View';
        } else {
            programTitle.textContent = `${programCode} - ${programName}`;
        }

        // Build query parameters
        let queryParams = `program_code=${programCode}&status=${status}`;
        if (category) queryParams += `&category=${category}`;
        if (semester) queryParams += `&semester=${encodeURIComponent(semester)}`;
        if (institution) queryParams += `&institution=${encodeURIComponent(institution)}`;

        // Fetch equivalencies via API
        fetch(`{{ $apiRoute ?? route('program_coordinator.api.existing_equivalencies') }}?${queryParams}`)
            .then(response => response.json())
            .then(data => {
                allEquivalencies = data;
                filteredEquivalencies = data;

                // Extract unique semesters and institutions for filter dropdowns
                extractFilterOptions(data);

                loadingIndicator.style.display = 'none';

                if (data.length === 0) {
                    emptyState.style.display = 'block';
                } else {
                    updateTableSubtitle();
                    renderEquivalencies(data, programCode);
                    equivalenciesSection.style.display = 'block';
                    statsSection.style.display = 'grid';
                }
            })
            .catch(error => {
                console.error('Error loading equivalencies:', error);
                loadingIndicator.style.display = 'none';
                alert('Failed to load course equivalencies. Please try again.');
            });
    }

    function extractFilterOptions(data) {
        const semesters = [...new Set(data.map(eq => eq.list_semester).filter(s => s))];
        if (semesters.length > 0 && JSON.stringify(semesters.sort()) !== JSON.stringify(availableSemesters.sort())) {
            availableSemesters = semesters.sort().reverse();
            populateSemesterFilter();
        }
    }

    function populateSemesterFilter() {
        const currentValue = semesterFilter.value;
        semesterFilter.innerHTML = '<option value="">All Semesters</option>';
        availableSemesters.forEach(semester => {
            const option = document.createElement('option');
            option.value = semester;
            option.textContent = semester;
            if (semester === currentValue) option.selected = true;
            semesterFilter.appendChild(option);
        });
    }

    function populateInstitutionFilter() {
        // Institution dropdown is now pre-populated from server-side
    }

    function updateFilterDescription() {
        filterDescription.textContent = 'Showing all published course equivalencies (current and historical data).';
    }

    function updateTableSubtitle() {
        const tableSubtitle = document.getElementById('table_subtitle');
        const institution = institutionFilter.value;

        let parts = ['all published equivalencies'];
        if (institution) {
            parts.push(`institution: ${institution}`);
        }

        const subtitle = `Showing ${parts.join(' | ')}`;
        tableSubtitle.textContent = subtitle;
    }

    // Search functionality
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();

        filteredEquivalencies = allEquivalencies.filter(eq => {
            return eq.diploma_course_code.toLowerCase().includes(searchTerm) ||
                   eq.diploma_course_name.toLowerCase().includes(searchTerm) ||
                   eq.diploma_institution.toLowerCase().includes(searchTerm) ||
                   eq.degree_course_code.toLowerCase().includes(searchTerm) ||
                   eq.degree_course_name.toLowerCase().includes(searchTerm) ||
                   (eq.program_code && eq.program_code.toLowerCase().includes(searchTerm));
        });

        renderEquivalencies(filteredEquivalencies, currentProgramCode);
    });

    function updateTableHeader(programCode) {
        const hasActions = @json(in_array(Auth::user()->role, ['program_coordinator', 'resource_person']));

        if (programCode === 'ALL') {
            let html = `
                <th style="width: 4%">#</th>
                <th style="width: 8%">Program</th>
                <th style="width: 10%">Diploma Course</th>
                <th style="width: 18%">Diploma Course Name</th>
                <th style="width: 5%" class="text-center">Cr</th>
                <th style="width: 16%">Institution</th>
                <th style="width: 9%">Degree Course</th>
                <th style="width: 16%">Degree Course Name</th>
                <th style="width: 5%" class="text-center">Cr</th>
                <th style="width: 6%" class="text-center">Match %</th>
                <th style="width: 5%" class="text-center">Eligible</th>
            `;
            if (hasActions) {
                html += `<th style="width: 100px; min-width: 100px;" class="text-center">Actions</th>`;
            }
            headerRow.innerHTML = html;
        } else {
            let html = `
                <th style="width: 5%">#</th>
                <th style="width: 12%">Diploma Course</th>
                <th style="width: 20%">Diploma Course Name</th>
                <th style="width: 6%" class="text-center">Cr</th>
                <th style="width: 18%">Institution</th>
                <th style="width: 10%">Degree Course</th>
                <th style="width: 18%">Degree Course Name</th>
                <th style="width: 6%" class="text-center">Cr</th>
                <th style="width: 8%" class="text-center">Match %</th>
                <th style="width: 7%" class="text-center">Eligible</th>
            `;
            if (hasActions) {
                html += `<th style="width: 100px; min-width: 100px;" class="text-center">Actions</th>`;
            }
            headerRow.innerHTML = html;
        }
    }

    function renderEquivalencies(data, programCode) {
        equivalenciesTbody.innerHTML = '';
        const hasActions = @json(in_array(Auth::user()->role, ['program_coordinator', 'resource_person']));

        let eligible = 0;
        let notEligible = 0;

        data.forEach((eq, index) => {
            if (eq.is_eligible) {
                eligible++;
            } else {
                notEligible++;
            }

            const row = document.createElement('tr');
            row.setAttribute('data-mapping-id', eq.id);
            let actionsHtml = '';

            if (hasActions) {
                actionsHtml = `
                    <td class="text-center">
                        <div class="action-buttons">
                            <button class="btn-action edit" onclick="editMapping('${eq.id}')" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-action delete" onclick="deleteMapping('${eq.id}', '${eq.diploma_course_code}')" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                `;
            }

            const matchBadgeClass = eq.match_percentage >= 80 ? 'high' : 'low';
            const categoryClass = eq.list_category === 'internal' ? 'internal' : 'external';
            const categoryLabel = eq.list_category === 'internal' ? 'CS110' : 'External';

            if (programCode === 'ALL') {
                row.innerHTML = `
                    <td><span class="row-number">${index + 1}</span></td>
                    <td><span class="badge-program">${eq.program_code || 'N/A'}</span></td>
                    <td><span class="course-code">${eq.diploma_course_code}</span></td>
                    <td><span class="course-name">${eq.diploma_course_name}</span></td>
                    <td class="text-center"><span class="credit-cell">${eq.diploma_credit_hour}</span></td>
                    <td>
                        <div class="institution-name">${eq.diploma_institution}</div>
                        <span class="badge-category ${categoryClass}">${categoryLabel}</span>
                    </td>
                    <td><span class="course-code">${eq.degree_course_code}</span></td>
                    <td><span class="course-name">${eq.degree_course_name}</span></td>
                    <td class="text-center"><span class="credit-cell">${eq.degree_credit_hour}</span></td>
                    <td class="text-center">
                        <span class="badge-match ${matchBadgeClass}">${parseFloat(eq.match_percentage).toFixed(0)}%</span>
                    </td>
                    <td class="text-center">
                        ${eq.is_eligible ?
                            '<i class="fas fa-check-circle eligible-icon yes" title="Eligible"></i>' :
                            '<i class="fas fa-times-circle eligible-icon no" title="Not Eligible"></i>'}
                    </td>
                    ${actionsHtml}
                `;
            } else {
                row.innerHTML = `
                    <td><span class="row-number">${index + 1}</span></td>
                    <td><span class="course-code">${eq.diploma_course_code}</span></td>
                    <td><span class="course-name">${eq.diploma_course_name}</span></td>
                    <td class="text-center"><span class="credit-cell">${eq.diploma_credit_hour}</span></td>
                    <td>
                        <div class="institution-name">${eq.diploma_institution}</div>
                        <span class="badge-category ${categoryClass}">${categoryLabel}</span>
                    </td>
                    <td><span class="course-code">${eq.degree_course_code}</span></td>
                    <td><span class="course-name">${eq.degree_course_name}</span></td>
                    <td class="text-center"><span class="credit-cell">${eq.degree_credit_hour}</span></td>
                    <td class="text-center">
                        <span class="badge-match ${matchBadgeClass}">${parseFloat(eq.match_percentage).toFixed(0)}%</span>
                    </td>
                    <td class="text-center">
                        ${eq.is_eligible ?
                            '<i class="fas fa-check-circle eligible-icon yes" title="Eligible"></i>' :
                            '<i class="fas fa-times-circle eligible-icon no" title="Not Eligible"></i>'}
                    </td>
                    ${actionsHtml}
                `;
            }
            equivalenciesTbody.appendChild(row);
        });

        totalEquivalencies.textContent = data.length;
        eligibleCount.textContent = eligible;
        notEligibleCount.textContent = notEligible;

        // Scroll to and highlight newly added mapping
        if (window.newlyAddedMappingId) {
            setTimeout(() => {
                const newRow = document.querySelector(`tr[data-mapping-id="${window.newlyAddedMappingId}"]`);
                if (newRow) {
                    newRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    newRow.classList.add('newly-added-highlight');
                    setTimeout(() => {
                        newRow.classList.remove('newly-added-highlight');
                    }, 3000);
                }
                window.newlyAddedMappingId = null;
            }, 500);
        }
    }

    function resetFilters() {
        document.getElementById('institution_filter').value = '';
        document.getElementById('degree_program_select').value = 'ALL';
        loadEquivalencies();
    }

    // Initialize filter description
    updateFilterDescription();

    // Auto-load ALL PROGRAMS view on page load
    loadEquivalencies();

    // Make loadEquivalencies globally accessible
    window.loadEquivalencies = loadEquivalencies;
});

function exportToExcel() {
    if (filteredEquivalencies.length === 0) {
        alert('No data to export');
        return;
    }

    let csv = 'Diploma Course Code,Diploma Course Name,Diploma Credits,Institution,Degree Course Code,Degree Course Name,Degree Credits,Match %,Eligible\n';

    filteredEquivalencies.forEach(eq => {
        csv += `"${eq.diploma_course_code}","${eq.diploma_course_name}",${eq.diploma_credit_hour},"${eq.diploma_institution}","${eq.degree_course_code}","${eq.degree_course_name}",${eq.degree_credit_hour},${eq.match_percentage},"${eq.is_eligible ? 'Yes' : 'No'}"\n`;
    });

    const blob = new Blob([csv], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `course_equivalencies_${document.getElementById('degree_program_select').value}_${new Date().toISOString().split('T')[0]}.csv`;
    a.click();
    window.URL.revokeObjectURL(url);
}

// CRUD Operations for PC and RP
@if(in_array(Auth::user()->role, ['program_coordinator', 'resource_person']))

document.getElementById('addMappingForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const role = '{{ Auth::user()->role }}';
    const url = role === 'program_coordinator'
        ? '{{ route("program_coordinator.course_equivalencies.store") }}'
        : '{{ route("resource_person.course_equivalencies.store") }}';

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('addMappingModal')).hide();

            window.newlyAddedMappingId = data.mapping.id;
            const newMappingProgramCode = data.mapping.program_code;

            document.getElementById('search_input').value = '';
            document.getElementById('degree_program_select').value = newMappingProgramCode;
            document.getElementById('addMappingForm').reset();

            alert(data.message || 'Course mapping added successfully!');
            loadEquivalencies();
        } else {
            alert('Failed to add mapping: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        alert('An error occurred while adding the mapping: ' + error.message);
    });
});

function editMapping(mappingId) {
    const role = '{{ Auth::user()->role }}';
    const url = role === 'program_coordinator'
        ? `/program-coordinator/course-equivalencies/${mappingId}`
        : `/resource-person/course-equivalencies/${mappingId}`;

    fetch(url, {
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const mapping = data.mapping;

            document.getElementById('edit_mapping_id').value = mapping.id;
            document.getElementById('edit_program_code').value = mapping.program_code;

            const $editInstitution = $('#edit_diploma_institution');
            if ($editInstitution.find("option[value='" + mapping.diploma_institution + "']").length === 0) {
                const newOption = new Option(mapping.diploma_institution, mapping.diploma_institution, true, true);
                $editInstitution.append(newOption);
            }
            $editInstitution.val(mapping.diploma_institution).trigger('change');

            document.getElementById('edit_diploma_course_code').value = mapping.diploma_course_code;
            document.getElementById('edit_diploma_course_name').value = mapping.diploma_course_name;
            $('#edit_diploma_credit_hour').val(mapping.diploma_credit_hour).trigger('change');
            document.getElementById('edit_degree_course_code').value = mapping.degree_course_code;
            document.getElementById('edit_degree_course_name').value = mapping.degree_course_name;
            $('#edit_degree_credit_hour').val(mapping.degree_credit_hour).trigger('change');
            document.getElementById('edit_match_percentage').value = mapping.match_percentage;
            document.getElementById('edit_is_eligible').value = mapping.is_eligible ? '1' : '0';

            new bootstrap.Modal(document.getElementById('editMappingModal')).show();
        } else {
            alert(data.message || 'Failed to load mapping details');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while loading mapping details');
    });
}

document.getElementById('editMappingForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const mappingId = document.getElementById('edit_mapping_id').value;
    const formData = new FormData(this);
    const role = '{{ Auth::user()->role }}';
    const url = role === 'program_coordinator'
        ? `/program-coordinator/course-equivalencies/${mappingId}`
        : `/resource-person/course-equivalencies/${mappingId}`;

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('editMappingModal')).hide();
            alert(data.message || 'Course mapping updated successfully!');
            loadEquivalencies();
        } else {
            alert(data.message || 'Failed to update mapping');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the mapping');
    });
});

function deleteMapping(mappingId, courseCode) {
    if (!confirm(`Are you sure you want to delete the mapping for ${courseCode}?\n\nThis action cannot be undone.`)) {
        return;
    }

    const role = '{{ Auth::user()->role }}';
    const url = role === 'program_coordinator'
        ? `/program-coordinator/course-equivalencies/${mappingId}`
        : `/resource-person/course-equivalencies/${mappingId}`;

    fetch(url, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message || 'Course mapping deleted successfully!');
            loadEquivalencies();
        } else {
            alert(data.message || 'Failed to delete mapping');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while deleting the mapping');
    });
}

@endif

// Initialize Select2 for institution dropdowns
$(document).ready(function() {
    $('#add_diploma_institution').select2({
        theme: 'bootstrap-5',
        placeholder: 'Select or type institution name',
        allowClear: true,
        tags: true,
        dropdownParent: $('#addMappingModal'),
        width: '100%',
        createTag: function (params) {
            var term = $.trim(params.term);
            if (term === '') {
                return null;
            }
            return {
                id: term,
                text: term,
                newTag: true
            };
        }
    });

    $('#edit_diploma_institution').select2({
        theme: 'bootstrap-5',
        placeholder: 'Select or type institution name',
        allowClear: true,
        tags: true,
        dropdownParent: $('#editMappingModal'),
        width: '100%',
        createTag: function (params) {
            var term = $.trim(params.term);
            if (term === '') {
                return null;
            }
            return {
                id: term,
                text: term,
                newTag: true
            };
        }
    });

    $('#addMappingModal').on('hidden.bs.modal', function () {
        $('#add_diploma_institution').val(null).trigger('change');
    });
});
</script>
@endsection
