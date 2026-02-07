@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600;700&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    :root {
        --uitm-primary: #1e3a8a;
        --uitm-primary-dark: #1e293b;
        --uitm-primary-light: #3b82f6;
        --uitm-red: #dc2626;
        --uitm-amber: #f59e0b;
        --uitm-green: #10b981;
        --uitm-cyan: #0ea5e9;
        --neutral-900: #171717;
        --neutral-800: #262626;
        --neutral-700: #404040;
        --neutral-600: #525252;
        --neutral-500: #737373;
        --neutral-400: #a3a3a3;
        --neutral-300: #d4d4d4;
        --neutral-200: #e5e5e5;
        --neutral-100: #f5f5f5;
        --neutral-50: #fafafa;
    }

    body {
        font-family: 'IBM Plex Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        background: var(--neutral-100);
    }

    /* Page Header */
    .page-header {
        position: relative;
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        border-radius: 16px;
        padding: 2rem;
        color: white;
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image:
            linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
        background-size: 32px 32px;
        pointer-events: none;
    }

    .page-header::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(245, 158, 11, 0.15) 0%, transparent 70%);
        pointer-events: none;
    }

    .page-header-content {
        position: relative;
        z-index: 1;
    }

    .page-header .eyebrow {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--uitm-amber);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .page-header .eyebrow::before {
        content: '';
        display: inline-block;
        width: 8px;
        height: 8px;
        background: var(--uitm-amber);
        border-radius: 2px;
    }

    .page-header h1 {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        letter-spacing: -0.025em;
    }

    .page-header .request-id {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.8rem;
        color: rgba(255, 255, 255, 0.7);
        background: rgba(255, 255, 255, 0.1);
        padding: 0.25rem 0.75rem;
        border-radius: 4px;
        display: inline-block;
    }

    .btn-header-back {
        background: rgba(255, 255, 255, 0.15);
        border: 2px solid rgba(255, 255, 255, 0.3);
        color: white;
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-header-back:hover {
        background: rgba(255, 255, 255, 0.25);
        border-color: rgba(255, 255, 255, 0.5);
        color: white;
        transform: translateY(-1px);
    }

    /* Alert Cards */
    .industrial-alert {
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }

    .industrial-alert.info {
        background: linear-gradient(135deg, rgba(14, 165, 233, 0.1) 0%, rgba(14, 165, 233, 0.05) 100%);
        border: 2px solid var(--uitm-cyan);
    }

    .industrial-alert.danger {
        background: linear-gradient(135deg, rgba(220, 38, 38, 0.1) 0%, rgba(220, 38, 38, 0.05) 100%);
        border: 2px solid var(--uitm-red);
    }

    .industrial-alert-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1.1rem;
    }

    .industrial-alert.info .industrial-alert-icon {
        background: var(--uitm-cyan);
        color: white;
    }

    .industrial-alert.danger .industrial-alert-icon {
        background: var(--uitm-red);
        color: white;
    }

    .industrial-alert-content h5 {
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.95rem;
        margin-bottom: 0.25rem;
    }

    .industrial-alert.info .industrial-alert-content h5 {
        color: #0284c7;
    }

    .industrial-alert.danger .industrial-alert-content h5 {
        color: var(--uitm-red);
    }

    .industrial-alert-content p {
        font-size: 0.875rem;
        color: var(--neutral-700);
        margin: 0;
    }

    /* Status Card */
    .status-card {
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 1.5rem;
        border: 2px solid;
    }

    .status-card.pending {
        border-color: var(--uitm-amber);
        background: linear-gradient(to right, rgba(245, 158, 11, 0.08) 0%, rgba(245, 158, 11, 0.02) 100%);
    }

    .status-card.under-review {
        border-color: var(--uitm-cyan);
        background: linear-gradient(to right, rgba(14, 165, 233, 0.08) 0%, rgba(14, 165, 233, 0.02) 100%);
    }

    .status-card.approved {
        border-color: var(--uitm-green);
        background: linear-gradient(to right, rgba(16, 185, 129, 0.08) 0%, rgba(16, 185, 129, 0.02) 100%);
    }

    .status-card.rejected {
        border-color: var(--uitm-red);
        background: linear-gradient(to right, rgba(220, 38, 38, 0.08) 0%, rgba(220, 38, 38, 0.02) 100%);
    }

    .status-card.syllabus-received {
        border-color: var(--uitm-green);
        background: linear-gradient(to right, rgba(16, 185, 129, 0.08) 0%, rgba(16, 185, 129, 0.02) 100%);
    }

    .status-card.awaiting {
        border-color: var(--uitm-cyan);
        background: linear-gradient(to right, rgba(14, 165, 233, 0.08) 0%, rgba(14, 165, 233, 0.02) 100%);
    }

    .status-card-header {
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: white;
    }

    .status-card.pending .status-card-header { background: linear-gradient(135deg, var(--uitm-amber) 0%, #d97706 100%); }
    .status-card.under-review .status-card-header { background: linear-gradient(135deg, var(--uitm-cyan) 0%, #0284c7 100%); }
    .status-card.approved .status-card-header { background: linear-gradient(135deg, var(--uitm-green) 0%, #059669 100%); }
    .status-card.rejected .status-card-header { background: linear-gradient(135deg, var(--uitm-red) 0%, #b91c1c 100%); }
    .status-card.syllabus-received .status-card-header { background: linear-gradient(135deg, var(--uitm-green) 0%, #059669 100%); }
    .status-card.awaiting .status-card-header { background: linear-gradient(135deg, var(--uitm-cyan) 0%, #0284c7 100%); }

    .status-card-header-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }

    .status-card-header h5 {
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 1rem;
        margin: 0;
    }

    .status-card-body {
        padding: 1.25rem 1.5rem;
    }

    .status-card-body p {
        font-size: 0.9rem;
        color: var(--neutral-700);
        margin-bottom: 0.5rem;
    }

    .status-card-body strong {
        font-weight: 600;
        color: var(--neutral-800);
    }

    .reviewer-notes-box {
        background: var(--neutral-50);
        border: 1px solid var(--neutral-200);
        border-radius: 8px;
        padding: 1rem;
        margin-top: 0.75rem;
        font-size: 0.9rem;
        color: var(--neutral-700);
    }

    /* Industrial Card */
    .industrial-card {
        background: white;
        border: 2px solid var(--neutral-200);
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .industrial-card-header {
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: white;
    }

    .industrial-card-header.primary {
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
    }

    .industrial-card-header.green {
        background: linear-gradient(135deg, var(--uitm-green) 0%, #059669 100%);
    }

    .industrial-card-header.amber {
        background: linear-gradient(135deg, var(--uitm-amber) 0%, #d97706 100%);
    }

    .industrial-card-header.cyan {
        background: linear-gradient(135deg, var(--uitm-cyan) 0%, #0284c7 100%);
    }

    .industrial-card-header.secondary {
        background: linear-gradient(135deg, var(--neutral-600) 0%, var(--neutral-700) 100%);
    }

    .industrial-card-header.dark {
        background: linear-gradient(135deg, var(--neutral-800) 0%, var(--neutral-900) 100%);
    }

    .industrial-card-header-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
    }

    .industrial-card-header h5 {
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.95rem;
        margin: 0;
    }

    .industrial-card-body {
        padding: 1.5rem;
    }

    /* Info Row */
    .info-row {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        margin-bottom: 1rem;
    }

    .info-row:last-child {
        margin-bottom: 0;
    }

    .info-item {
        flex: 1;
        min-width: 180px;
    }

    .info-label {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--neutral-500);
        margin-bottom: 0.35rem;
    }

    .info-value {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.95rem;
        color: var(--neutral-800);
    }

    .info-value.mono {
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 600;
        color: var(--uitm-primary);
    }

    /* Course Comparison Cards */
    .course-card {
        background: white;
        border: 2px solid var(--neutral-200);
        border-radius: 12px;
        overflow: hidden;
        height: 100%;
    }

    .course-card.diploma {
        border-color: var(--uitm-primary);
    }

    .course-card.degree {
        border-color: var(--uitm-green);
    }

    .course-card-header {
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.625rem;
        color: white;
    }

    .course-card.diploma .course-card-header {
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
    }

    .course-card.degree .course-card-header {
        background: linear-gradient(135deg, var(--uitm-green) 0%, #059669 100%);
    }

    .course-card-header-icon {
        width: 28px;
        height: 28px;
        border-radius: 6px;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
    }

    .course-card-header h6 {
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.85rem;
        margin: 0;
    }

    .course-card-body {
        padding: 1.25rem;
    }

    .course-code-display {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--uitm-primary);
        margin-bottom: 0.25rem;
    }

    .course-name-display {
        font-size: 0.9rem;
        color: var(--neutral-700);
        margin-bottom: 1rem;
    }

    .course-meta {
        font-size: 0.85rem;
        color: var(--neutral-600);
        margin-bottom: 0.5rem;
    }

    .course-meta:last-child {
        margin-bottom: 0;
    }

    .course-meta-label {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.65rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--neutral-500);
        display: block;
        margin-bottom: 0.15rem;
    }

    .badge-student {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        background: linear-gradient(135deg, var(--uitm-cyan) 0%, #0284c7 100%);
        color: white;
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.65rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        padding: 0.3rem 0.6rem;
        border-radius: 4px;
    }

    /* Verification Card */
    .verification-status {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        border-radius: 10px;
        margin-bottom: 1rem;
    }

    .verification-status.success {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.12) 0%, rgba(16, 185, 129, 0.05) 100%);
        border: 1px solid var(--uitm-green);
    }

    .verification-status.warning {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.12) 0%, rgba(245, 158, 11, 0.05) 100%);
        border: 1px solid var(--uitm-amber);
    }

    .verification-status.info {
        background: linear-gradient(135deg, rgba(14, 165, 233, 0.12) 0%, rgba(14, 165, 233, 0.05) 100%);
        border: 1px solid var(--uitm-cyan);
    }

    .verification-status-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1.1rem;
        color: white;
    }

    .verification-status.success .verification-status-icon { background: var(--uitm-green); }
    .verification-status.warning .verification-status-icon { background: var(--uitm-amber); }
    .verification-status.info .verification-status-icon { background: var(--uitm-cyan); }

    .verification-status-content strong {
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.9rem;
        display: block;
        margin-bottom: 0.15rem;
    }

    .verification-status.success .verification-status-content strong { color: var(--uitm-green); }
    .verification-status.warning .verification-status-content strong { color: #b45309; }
    .verification-status.info .verification-status-content strong { color: #0284c7; }

    .verification-status-content small {
        font-size: 0.8rem;
        color: var(--neutral-600);
    }

    /* Submitted Details Box */
    .submitted-details {
        background: var(--neutral-50);
        border: 1px solid var(--neutral-200);
        border-radius: 10px;
        padding: 1.25rem;
        margin-bottom: 1rem;
    }

    .submitted-details-header {
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.85rem;
        color: var(--neutral-700);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .submitted-details-header i {
        color: var(--neutral-500);
    }

    /* Action Buttons */
    .btn-industrial {
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.85rem;
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-industrial.primary {
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        border: none;
        color: white;
    }

    .btn-industrial.primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
        color: white;
    }

    .btn-industrial.success {
        background: linear-gradient(135deg, var(--uitm-green) 0%, #059669 100%);
        border: none;
        color: white;
    }

    .btn-industrial.success:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        color: white;
    }

    .btn-industrial.outline-success {
        background: white;
        border: 2px solid var(--uitm-green);
        color: var(--uitm-green);
    }

    .btn-industrial.outline-success:hover {
        background: var(--uitm-green);
        color: white;
        transform: translateY(-2px);
    }

    .btn-industrial.secondary {
        background: var(--neutral-100);
        border: 2px solid var(--neutral-300);
        color: var(--neutral-700);
    }

    .btn-industrial.secondary:hover {
        background: var(--neutral-200);
        border-color: var(--neutral-400);
        color: var(--neutral-800);
    }

    /* Quick Decision Card */
    .decision-card {
        background: white;
        border: 2px solid var(--uitm-amber);
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .decision-card-header {
        background: linear-gradient(135deg, var(--uitm-amber) 0%, #d97706 100%);
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: white;
    }

    .decision-card-header-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .decision-card-header h5 {
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.95rem;
        margin: 0;
    }

    .decision-card-body {
        padding: 1.5rem;
    }

    .decision-warning {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(245, 158, 11, 0.05) 100%);
        border: 1px solid var(--uitm-amber);
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        font-size: 0.875rem;
        color: var(--neutral-700);
    }

    .decision-warning i {
        color: var(--uitm-amber);
        margin-right: 0.5rem;
    }

    /* Form Elements */
    .form-label-industrial {
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.85rem;
        color: var(--neutral-700);
        margin-bottom: 0.5rem;
    }

    .form-control-industrial {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.9rem;
        border: 2px solid var(--neutral-200);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        transition: all 0.2s ease;
    }

    .form-control-industrial:focus {
        border-color: var(--uitm-primary);
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
        outline: none;
    }

    .input-group-industrial {
        display: flex;
    }

    .input-group-industrial .form-control-industrial {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }

    .input-group-industrial .input-group-text {
        background: var(--neutral-100);
        border: 2px solid var(--neutral-200);
        border-left: none;
        border-radius: 0 8px 8px 0;
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 600;
        color: var(--neutral-600);
    }

    /* Decision Buttons */
    .decision-btn-group {
        display: flex;
        gap: 0.5rem;
    }

    .decision-btn {
        flex: 1;
        padding: 1rem;
        border-radius: 10px;
        border: 2px solid;
        background: white;
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .decision-btn.approve {
        border-color: var(--uitm-green);
        color: var(--uitm-green);
    }

    .decision-btn.approve:hover,
    .decision-btn.approve.active {
        background: var(--uitm-green);
        color: white;
    }

    .decision-btn.reject {
        border-color: var(--uitm-red);
        color: var(--uitm-red);
    }

    .decision-btn.reject:hover,
    .decision-btn.reject.active {
        background: var(--uitm-red);
        color: white;
    }

    input[type="radio"]:checked + .decision-btn.approve {
        background: var(--uitm-green);
        color: white;
    }

    input[type="radio"]:checked + .decision-btn.reject {
        background: var(--uitm-red);
        color: white;
    }

    /* Sidebar Cards */
    .sidebar-card {
        background: white;
        border: 2px solid var(--neutral-200);
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .sidebar-card-header {
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.625rem;
        color: white;
    }

    .sidebar-card-header.dark {
        background: linear-gradient(135deg, var(--neutral-800) 0%, var(--neutral-900) 100%);
    }

    .sidebar-card-header.primary {
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
    }

    .sidebar-card-header.cyan {
        background: linear-gradient(135deg, var(--uitm-cyan) 0%, #0284c7 100%);
    }

    .sidebar-card-header-icon {
        width: 28px;
        height: 28px;
        border-radius: 6px;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
    }

    .sidebar-card-header h6 {
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.85rem;
        margin: 0;
    }

    .sidebar-card-body {
        padding: 1.25rem;
    }

    .sidebar-card-body p {
        font-size: 0.85rem;
        color: var(--neutral-700);
        margin-bottom: 0.75rem;
    }

    .sidebar-card-body p:last-child {
        margin-bottom: 0;
    }

    .sidebar-card-body strong {
        font-weight: 600;
        color: var(--neutral-800);
    }

    .program-badge {
        display: inline-flex;
        align-items: center;
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        color: white;
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        margin-top: 0.25rem;
    }

    /* Timeline */
    .timeline {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .timeline-item {
        position: relative;
        padding-left: 2rem;
        padding-bottom: 1.25rem;
    }

    .timeline-item:last-child {
        padding-bottom: 0;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: 0.5rem;
        top: 1.5rem;
        bottom: 0;
        width: 2px;
        background: var(--neutral-200);
    }

    .timeline-item:last-child::before {
        display: none;
    }

    .timeline-icon {
        position: absolute;
        left: 0;
        top: 0;
        width: 1.125rem;
        height: 1.125rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.55rem;
        color: white;
    }

    .timeline-icon.success { background: var(--uitm-green); }
    .timeline-icon.warning { background: var(--uitm-amber); }
    .timeline-icon.info { background: var(--uitm-cyan); }

    .timeline-content strong {
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.85rem;
        color: var(--neutral-800);
        display: block;
        margin-bottom: 0.15rem;
    }

    .timeline-content small {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.75rem;
        color: var(--neutral-500);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
        }

        .page-header h1 {
            font-size: 1.35rem;
        }

        .btn-header-back {
            width: 100%;
            justify-content: center;
            margin-top: 1rem;
        }

        .info-row {
            flex-direction: column;
            gap: 1rem;
        }

        .info-item {
            min-width: 100%;
        }

        .decision-btn-group {
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <div class="eyebrow">Equivalency Review</div>
                    <h1><i class="fas fa-file-signature me-2"></i>Review Equivalency Request</h1>
                    <span class="request-id">ID: {{ Str::limit($request->id, 20) }}</span>
                </div>
                <a href="{{ route('resource_person.equivalency_requests.index') }}" class="btn-header-back">
                    <i class="fas fa-arrow-left"></i>
                    Back to Requests
                </a>
            </div>
        </div>
    </div>

    <!-- Error Messages -->
    @if($errors->any())
        <div class="industrial-alert danger">
            <div class="industrial-alert-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="industrial-alert-content">
                <h5>Validation Errors</h5>
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <!-- Students Affected Notice -->
            @php
                $affectedStudents = \App\Models\CourseEquivalencyRequest::where('diploma_course_code', $request->diploma_course_code)
                    ->where('suggested_degree_course_code', $request->suggested_degree_course_code)
                    ->where('current_program_code', $request->current_program_code)
                    ->where('status', '!=', 'approved')
                    ->where('status', '!=', 'rejected')
                    ->count();
            @endphp
            @if($affectedStudents > 1)
                <div class="industrial-alert info">
                    <div class="industrial-alert-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="industrial-alert-content">
                        <h5>Multiple Students Affected</h5>
                        <p>
                            <strong>{{ $affectedStudents }} student(s)</strong> have requested this same equivalency
                            (<strong>{{ $request->diploma_course_code }} → {{ $request->suggested_degree_course_code }}</strong> for <strong>{{ $request->current_program_code }}</strong>).
                            Your decision will automatically apply to all of them.
                        </p>
                    </div>
                </div>
            @endif

            <!-- Status Card -->
            @php
                if ($request->syllabus_received_at !== null && !in_array($request->status, ['approved', 'rejected'])) {
                    $statusClass = 'syllabus-received';
                    $statusText = 'Syllabus Received - Ready for Review';
                    $statusIcon = 'check-circle';
                } elseif ($request->syllabus_request_sent_at && !$request->syllabus_received_at) {
                    $statusClass = 'awaiting';
                    $statusText = 'Awaiting Lecturer Response';
                    $statusIcon = 'clock';
                } else {
                    $statusClass = match($request->status) {
                        'pending' => 'pending',
                        'approved' => 'approved',
                        'rejected' => 'rejected',
                        default => 'under-review'
                    };
                    $statusText = strtoupper(str_replace('_', ' ', $request->status));
                    $statusIcon = match($request->status) {
                        'pending' => 'clock',
                        'approved' => 'check-circle',
                        'rejected' => 'times-circle',
                        default => 'sync'
                    };
                }
            @endphp

            <div class="status-card {{ $statusClass }}">
                <div class="status-card-header">
                    <div class="status-card-header-icon">
                        <i class="fas fa-{{ $statusIcon }}"></i>
                    </div>
                    <h5>Status: {{ $statusText }}</h5>
                </div>
                <div class="status-card-body">
                    <p><strong>Submitted:</strong> {{ $request->created_at->format('d F Y, h:i A') }}</p>
                    @if($request->syllabus_request_sent_at)
                        <p><strong>Syllabus Requested:</strong> {{ $request->syllabus_request_sent_at->format('d F Y, h:i A') }}</p>
                    @endif
                    @if($request->reviewed_at)
                        <p><strong>Reviewed:</strong> {{ $request->reviewed_at->format('d F Y, h:i A') }}</p>
                        <p><strong>Reviewed By:</strong> {{ $request->reviewer->user->name ?? 'N/A' }}</p>
                    @endif
                    @if($request->reviewer_notes)
                        <p class="mb-1 mt-3"><strong>Reviewer Notes:</strong></p>
                        <div class="reviewer-notes-box">{{ $request->reviewer_notes }}</div>
                    @endif
                </div>
            </div>

            <!-- Course Comparison - Side by Side -->
            <div class="row mb-4">
                <!-- Diploma Course Information -->
                <div class="col-lg-6 mb-3 mb-lg-0">
                    <div class="course-card diploma h-100">
                        <div class="course-card-header">
                            <div class="course-card-header-icon">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <h6>Diploma Course (From)</h6>
                        </div>
                        <div class="course-card-body">
                            <div class="course-code-display">{{ $request->diploma_course_code }}</div>
                            <div class="course-name-display">{{ $request->diploma_course_name }}</div>
                            <div class="course-meta">
                                <span class="course-meta-label">Institution</span>
                                {{ $request->diploma_institution }}
                            </div>
                            <div class="course-meta">
                                <span class="course-meta-label">Program</span>
                                {{ $request->diploma_program }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Student's Suggested Degree Course -->
                <div class="col-lg-6">
                    <div class="course-card degree h-100">
                        <div class="course-card-header">
                            <div class="course-card-header-icon">
                                <i class="fas fa-university"></i>
                            </div>
                            <h6>Degree Course (To)</h6>
                        </div>
                        <div class="course-card-body">
                            <div class="course-code-display" style="color: var(--uitm-green);">{{ $request->suggested_degree_course_code }}</div>
                            <div class="course-name-display">{{ $request->suggested_degree_course_name }}</div>
                            <div class="course-meta">
                                <span class="course-meta-label">Program</span>
                                {{ $request->current_program_code }} - {{ $request->current_program_name }}
                            </div>
                            <div class="course-meta">
                                <span class="course-meta-label">Suggested By</span>
                                <span class="badge-student"><i class="fas fa-user-graduate"></i> Student</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Approved Equivalency Result -->
            @if($request->status === 'approved' && $request->approved_degree_course_code)
                <div class="industrial-card" style="border-color: var(--uitm-green);">
                    <div class="industrial-card-header green">
                        <div class="industrial-card-header-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h5>Approved Equivalency</h5>
                    </div>
                    <div class="industrial-card-body">
                        <div class="info-row">
                            <div class="info-item">
                                <div class="info-label">Degree Course Code</div>
                                <div class="info-value mono">{{ $request->approved_degree_course_code }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Degree Course Name</div>
                                <div class="info-value">{{ $request->approved_degree_course_name }}</div>
                            </div>
                        </div>
                        @if($request->match_percentage)
                            <div class="info-row">
                                <div class="info-item">
                                    <div class="info-label">Match Percentage</div>
                                    <div class="info-value mono">{{ $request->match_percentage }}%</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- External Lecturer Verification (Combined Card) -->
            @php
                $lecturerName = $request->selected_lecturer_name ?? $request->external_lecturer_name;
                $lecturerEmail = $request->selected_lecturer_email ?? $request->external_lecturer_email;
                $lecturerSource = $request->selected_lecturer_name ? 'Selected by PC' : 'Provided by Student';
                $submission = $request->externalLecturerRequest?->submission;
            @endphp
            @if($lecturerName && $lecturerEmail)
            <div class="industrial-card" style="{{ $request->syllabus_received_at ? 'border-color: var(--uitm-green);' : '' }}">
                <div class="industrial-card-header {{ $request->syllabus_received_at ? 'green' : 'secondary' }}">
                    <div class="industrial-card-header-icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <h5>
                        External Lecturer Verification
                        @if($request->syllabus_received_at)
                            <span style="background: rgba(255,255,255,0.2); font-size: 0.7rem; padding: 0.25rem 0.5rem; border-radius: 4px; margin-left: 0.5rem;">
                                <i class="fas fa-check me-1"></i>Syllabus Received
                            </span>
                        @endif
                    </h5>
                </div>
                <div class="industrial-card-body">
                    <!-- Lecturer Info -->
                    <div class="info-row mb-4">
                        <div class="info-item">
                            <div class="info-label">Lecturer Name</div>
                            <div class="info-value">
                                <strong>{{ $lecturerName }}</strong>
                                <span style="background: var(--neutral-200); font-size: 0.65rem; font-weight: 600; padding: 0.2rem 0.5rem; border-radius: 4px; margin-left: 0.5rem; color: var(--neutral-600);">{{ $lecturerSource }}</span>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Lecturer Email</div>
                            <div class="info-value">{{ $lecturerEmail }}</div>
                        </div>
                    </div>

                    <!-- State-based Content -->
                    @if($request->syllabus_received_at && $submission)
                        {{-- STATE: Syllabus Received --}}
                        <div class="verification-status success">
                            <div class="verification-status-icon">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="verification-status-content">
                                <strong>Syllabus Received</strong>
                                <small>Received on {{ $request->syllabus_received_at->format('d M Y, h:i A') }}</small>
                            </div>
                        </div>

                        {{-- Submitted Course Details --}}
                        <div class="submitted-details">
                            <div class="submitted-details-header">
                                <i class="fas fa-file-alt"></i> Submitted Course Details
                            </div>
                            <div class="info-row">
                                <div class="info-item">
                                    <div class="info-label">Course Code</div>
                                    <div class="info-value mono">{{ $submission->course_code }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Course Name</div>
                                    <div class="info-value">{{ $submission->course_name }}</div>
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-item">
                                    <div class="info-label">Institution</div>
                                    <div class="info-value">{{ $submission->institution_name }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Credit Hours</div>
                                    <div class="info-value mono">{{ number_format($submission->credit_hours, 2) }}</div>
                                </div>
                            </div>
                            @if($submission->justification_notes)
                                <div class="info-row">
                                    <div class="info-item" style="flex: 100%;">
                                        <div class="info-label">Lecturer's Justification</div>
                                        <div class="info-value" style="font-style: italic; color: var(--neutral-600);">"{{ $submission->justification_notes }}"</div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Action Buttons --}}
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('resource_person.external_submission.view_syllabus', $submission) }}"
                               class="btn-industrial outline-success" target="_blank">
                                <i class="fas fa-file-pdf"></i>View Syllabus PDF
                            </a>
                            <a href="{{ route('resource_person.equivalency_requests.compare', $request) }}" class="btn-industrial success">
                                <i class="fas fa-columns"></i>Compare with Degree Syllabi
                            </a>
                        </div>

                    @elseif($request->syllabus_request_sent_at)
                        {{-- STATE: Awaiting Response --}}
                        <div class="verification-status info">
                            <div class="verification-status-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="verification-status-content">
                                <strong>Awaiting Lecturer Response</strong>
                                <small>Syllabus requested on {{ $request->syllabus_request_sent_at->format('d M Y, h:i A') }}</small>
                                <br><small>Waiting for lecturer to submit the official syllabus...</small>
                            </div>
                        </div>

                    @else
                        {{-- STATE: Not Requested --}}
                        <div class="verification-status warning">
                            <div class="verification-status-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="verification-status-content">
                                <strong>Syllabus Not Yet Requested</strong>
                                <small>Request the official course syllabus from the lecturer for verification.</small>
                            </div>
                        </div>
                        <a href="{{ route('resource_person.equivalency_requests.preview_email', $request) }}" class="btn-industrial primary">
                            <i class="fas fa-envelope-open-text"></i>Preview & Send Email to Lecturer
                        </a>
                    @endif
                </div>
            </div>
            @endif

            <!-- Review Form - Only show when syllabus NOT received (for quick reject without comparison) -->
            @if(!$request->syllabus_received_at && in_array($request->status, ['pending', 'under_review']))
                <div class="decision-card">
                    <div class="decision-card-header">
                        <div class="decision-card-header-icon">
                            <i class="fas fa-gavel"></i>
                        </div>
                        <h5>Quick Decision (No Syllabus)</h5>
                    </div>
                    <div class="decision-card-body">
                        <div class="decision-warning">
                            <i class="fas fa-info-circle"></i>
                            <strong>Note:</strong> No official syllabus has been received yet. You may reject this request if clearly not equivalent, or request the syllabus from the external lecturer for proper comparison.
                        </div>

                        <form action="{{ route('resource_person.equivalency_requests.process', $request) }}" method="POST" id="reviewForm">
                            @csrf

                            <!-- Course Summary -->
                            <div class="submitted-details mb-4">
                                <div class="submitted-details-header">
                                    <i class="fas fa-exchange-alt"></i> Course Mapping Summary
                                </div>
                                <div class="info-row">
                                    <div class="info-item">
                                        <div class="info-label">Diploma Course</div>
                                        <div class="info-value mono">{{ $request->diploma_course_code }}</div>
                                        <div style="font-size: 0.8rem; color: var(--neutral-600);">{{ $request->diploma_course_name }}</div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">Degree Course</div>
                                        <div class="info-value mono" style="color: var(--uitm-green);">{{ $request->suggested_degree_course_code }}</div>
                                        <div style="font-size: 0.8rem; color: var(--neutral-600);">{{ $request->suggested_degree_course_name }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Match Percentage Input -->
                            <div class="mb-4">
                                <label class="form-label-industrial">Match Percentage <span style="color: var(--uitm-red);">*</span></label>
                                <div class="input-group-industrial">
                                    <input type="number" name="match_percentage" class="form-control-industrial"
                                           value="{{ old('match_percentage', 0) }}" min="0" max="100" step="1" required>
                                    <span class="input-group-text">%</span>
                                </div>
                                <small style="font-size: 0.8rem; color: var(--neutral-500); display: block; margin-top: 0.5rem;">For rejection without syllabus comparison, typically enter a low percentage (0-50%).</small>
                            </div>

                            <!-- Decision -->
                            <div class="mb-4">
                                <label class="form-label-industrial">Decision <span style="color: var(--uitm-red);">*</span></label>
                                <div class="decision-btn-group">
                                    <input type="radio" class="btn-check" name="decision" id="decision_approve" value="approved" required style="display: none;">
                                    <label class="decision-btn approve" for="decision_approve">
                                        <i class="fas fa-check-circle"></i>Equivalent
                                    </label>

                                    <input type="radio" class="btn-check" name="decision" id="decision_reject" value="rejected" required style="display: none;">
                                    <label class="decision-btn reject" for="decision_reject">
                                        <i class="fas fa-times-circle"></i>Not Equivalent
                                    </label>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="d-flex gap-2 justify-content-end flex-wrap">
                                <a href="{{ route('resource_person.equivalency_requests.index') }}" class="btn-industrial secondary">
                                    <i class="fas fa-times"></i>Cancel
                                </a>
                                <button type="submit" class="btn-industrial primary" id="submitBtn" disabled>
                                    <i class="fas fa-paper-plane"></i>Submit Decision
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Program Information -->
            <div class="sidebar-card">
                <div class="sidebar-card-header dark">
                    <div class="sidebar-card-header-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h6>Program Information</h6>
                </div>
                <div class="sidebar-card-body">
                    <p><strong>Program Code:</strong></p>
                    <span class="program-badge">{{ $request->current_program_code }}</span>
                    <p class="mt-3"><strong>Program Name:</strong><br>
                        {{ $request->current_program_name }}
                    </p>
                </div>
            </div>

            <!-- Coordinator Information -->
            @if($request->coordinator)
            <div class="sidebar-card">
                <div class="sidebar-card-header primary">
                    <div class="sidebar-card-header-icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <h6>Forwarded By</h6>
                </div>
                <div class="sidebar-card-body">
                    <p><strong>Program Coordinator:</strong><br>{{ $request->coordinator->name }}</p>
                </div>
            </div>
            @endif

            <!-- Timeline -->
            <div class="sidebar-card">
                <div class="sidebar-card-header cyan">
                    <div class="sidebar-card-header-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h6>Timeline</h6>
                </div>
                <div class="sidebar-card-body">
                    <ul class="timeline">
                        <li class="timeline-item">
                            <div class="timeline-icon success">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="timeline-content">
                                <strong>Submitted</strong>
                                <small>{{ $request->created_at->format('d M Y, h:i A') }}</small>
                            </div>
                        </li>
                        @if($request->syllabus_request_sent_at)
                            <li class="timeline-item">
                                <div class="timeline-icon info">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="timeline-content">
                                    <strong>Syllabus Requested</strong>
                                    <small>{{ $request->syllabus_request_sent_at->format('d M Y, h:i A') }}</small>
                                </div>
                            </li>
                        @endif
                        @if($request->syllabus_received_at)
                            <li class="timeline-item">
                                <div class="timeline-icon success">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div class="timeline-content">
                                    <strong>Syllabus Received</strong>
                                    <small>{{ $request->syllabus_received_at->format('d M Y, h:i A') }}</small>
                                </div>
                            </li>
                        @endif
                        @if($request->reviewed_at)
                            <li class="timeline-item">
                                <div class="timeline-icon success">
                                    <i class="fas fa-gavel"></i>
                                </div>
                                <div class="timeline-content">
                                    <strong>Reviewed</strong>
                                    <small>{{ $request->reviewed_at->format('d M Y, h:i A') }}</small>
                                </div>
                            </li>
                        @else
                            <li class="timeline-item">
                                <div class="timeline-icon warning">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="timeline-content">
                                    <strong>Pending Review</strong>
                                    <small>Awaiting resource person decision</small>
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@if(!$request->syllabus_received_at && in_array($request->status, ['pending', 'under_review']))
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const approveRadio = document.getElementById('decision_approve');
    const rejectRadio = document.getElementById('decision_reject');
    const submitBtn = document.getElementById('submitBtn');
    const approveLabel = document.querySelector('label[for="decision_approve"]');
    const rejectLabel = document.querySelector('label[for="decision_reject"]');

    function updateButtonStates() {
        // Remove active class from both
        approveLabel.classList.remove('active');
        rejectLabel.classList.remove('active');

        // Add active class to selected
        if (approveRadio.checked) {
            approveLabel.classList.add('active');
        } else if (rejectRadio.checked) {
            rejectLabel.classList.add('active');
        }

        // Enable submit button when a decision is selected
        if (approveRadio.checked || rejectRadio.checked) {
            submitBtn.disabled = false;
        }
    }

    approveRadio.addEventListener('change', updateButtonStates);
    rejectRadio.addEventListener('change', updateButtonStates);

    // Click handlers for labels
    approveLabel.addEventListener('click', function() {
        approveRadio.checked = true;
        updateButtonStates();
    });

    rejectLabel.addEventListener('click', function() {
        rejectRadio.checked = true;
        updateButtonStates();
    });

    // Initialize on page load
    updateButtonStates();
});
</script>
@endpush
@endif
@endsection
