@extends('layouts.app')

@push('styles')
<!-- IBM Plex Sans Typography -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600;700&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
/* ========================================
   INDUSTRIAL INSTITUTIONAL DESIGN SYSTEM
   UiTM Credit Exemption - Application Form
   ======================================== */

:root {
    --uitm-primary: #1e3a8a;
    --uitm-primary-dark: #1e293b;
    --uitm-primary-light: #3b82f6;
    --uitm-red: #dc2626;
    --uitm-red-light: #ef4444;
    --uitm-amber: #f59e0b;
    --uitm-amber-light: #fbbf24;
    --uitm-green: #10b981;
    --uitm-green-light: #34d399;
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

/* Typography Override */
.application-container,
.application-container * {
    font-family: 'IBM Plex Sans', -apple-system, BlinkMacSystemFont, sans-serif !important;
}

.mono-text {
    font-family: 'IBM Plex Mono', monospace !important;
}

/* ========================================
   PAGE HEADER - Industrial Style
   ======================================== */
.page-header {
    position: relative;
    background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
    border-radius: 16px;
    padding: 2rem;
    color: white;
    overflow: hidden;
    margin-bottom: 1.5rem;
}

.page-header-pattern {
    position: absolute;
    inset: 0;
    opacity: 0.07;
    background-image:
        linear-gradient(rgba(255,255,255,0.1) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.1) 1px, transparent 1px);
    background-size: 40px 40px;
    pointer-events: none;
}

.page-header-glow {
    position: absolute;
    top: -50%;
    right: -20%;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(245, 158, 11, 0.15) 0%, transparent 70%);
    pointer-events: none;
}

.page-header-content {
    position: relative;
    z-index: 2;
}

.page-header-eyebrow {
    font-size: 0.7rem;
    font-weight: 600;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: var(--uitm-amber);
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.page-header-eyebrow::before {
    content: '';
    width: 24px;
    height: 2px;
    background: var(--uitm-amber);
}

.page-header-title {
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    line-height: 1.2;
}

.page-header-subtitle {
    font-size: 0.95rem;
    color: rgba(255, 255, 255, 0.85);
    margin-bottom: 0;
    max-width: 600px;
}

/* ========================================
   BREADCRUMB - Industrial Style
   ======================================== */
.industrial-breadcrumb {
    background: white;
    padding: 1rem 1.25rem;
    border-radius: 10px;
    margin-bottom: 1.5rem;
    border: 2px solid var(--neutral-200);
}

.industrial-breadcrumb .breadcrumb {
    margin-bottom: 0;
    background-color: transparent;
    padding: 0;
}

.industrial-breadcrumb .breadcrumb-item {
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--neutral-600);
}

.industrial-breadcrumb .breadcrumb-item a {
    color: var(--uitm-primary);
    text-decoration: none;
    transition: color 0.2s ease;
}

.industrial-breadcrumb .breadcrumb-item a:hover {
    color: var(--uitm-primary-light);
}

.industrial-breadcrumb .breadcrumb-item.active {
    color: var(--uitm-primary);
    font-weight: 600;
}

/* ========================================
   MULTI-STEP PROGRESS - Industrial Style
   ======================================== */
.step-progress {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: white;
    border-radius: 12px;
    border: 2px solid var(--neutral-200);
}

.step-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    flex: 1;
    max-width: 200px;
}

.step-dot {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    background-color: var(--neutral-100);
    border: 2px solid var(--neutral-200);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.1rem;
    color: var(--neutral-500);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    z-index: 2;
}

.step-item.active .step-dot {
    background: linear-gradient(135deg, var(--uitm-primary), var(--uitm-primary-light));
    border-color: var(--uitm-primary);
    color: white;
    box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
    transform: scale(1.05);
}

.step-item.completed .step-dot {
    background: linear-gradient(135deg, var(--uitm-green), var(--uitm-green-light));
    border-color: var(--uitm-green);
    color: white;
}

.step-label {
    margin-top: 0.75rem;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--neutral-500);
    text-align: center;
}

.step-item.active .step-label {
    color: var(--uitm-primary);
}

.step-item.completed .step-label {
    color: var(--uitm-green);
}

.step-line {
    position: absolute;
    top: 25px;
    left: 50%;
    width: 100%;
    height: 3px;
    background-color: var(--neutral-200);
    z-index: 1;
}

.step-item.completed .step-line {
    background: linear-gradient(90deg, var(--uitm-green), var(--uitm-green-light));
}

.step-item:last-child .step-line {
    display: none;
}

/* ========================================
   INDUSTRIAL CARDS
   ======================================== */
.industrial-card {
    background: white;
    border-radius: 12px;
    border: 2px solid var(--neutral-200);
    overflow: hidden;
    margin-bottom: 1.5rem;
    transition: all 0.3s ease;
}

.industrial-card:hover {
    border-color: var(--neutral-300);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
}

.card-header-industrial {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1.25rem 1.5rem;
    background: var(--neutral-50);
    border-bottom: 2px solid var(--neutral-200);
}

.card-header-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--uitm-primary), var(--uitm-primary-light));
    color: white;
    font-size: 1rem;
}

.card-header-text h5 {
    font-size: 1rem;
    font-weight: 700;
    color: var(--neutral-900);
    margin: 0;
}

.card-header-text p {
    font-size: 0.75rem;
    color: var(--neutral-500);
    margin: 0;
}

.card-body-industrial {
    padding: 1.5rem;
}

/* ========================================
   FORM STYLING - Industrial
   ======================================== */
.section-title {
    font-size: 0.9rem;
    font-weight: 700;
    color: var(--uitm-primary);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid var(--neutral-200);
}

.form-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--neutral-700);
    margin-bottom: 0.5rem;
}

.form-control,
.form-select {
    border: 2px solid var(--neutral-200);
    border-radius: 8px;
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
    transition: all 0.2s ease;
}

.form-control:focus,
.form-select:focus {
    border-color: var(--uitm-primary);
    box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
}

.form-control.is-invalid,
.form-select.is-invalid {
    border-color: var(--uitm-red);
}

.form-control.is-invalid:focus {
    box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
}

.invalid-feedback {
    font-size: 0.8rem;
    color: var(--uitm-red);
}

textarea.form-control {
    min-height: 80px;
}

/* Radio buttons styling */
.form-check-input {
    width: 1.25rem;
    height: 1.25rem;
    border: 2px solid var(--neutral-300);
    cursor: pointer;
}

.form-check-input:checked {
    background-color: var(--uitm-primary);
    border-color: var(--uitm-primary);
}

.form-check-input:focus {
    box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
}

.form-check-label {
    font-weight: 500;
    color: var(--neutral-700);
    cursor: pointer;
}

/* ========================================
   NAVIGATION BUTTONS - Industrial
   ======================================== */
.step-navigation {
    display: flex;
    justify-content: space-between;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 2px solid var(--neutral-200);
}

.btn-industrial {
    padding: 0.875rem 1.75rem;
    font-weight: 600;
    font-size: 0.9rem;
    border-radius: 8px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-industrial-primary {
    background: linear-gradient(135deg, var(--uitm-primary), var(--uitm-primary-light));
    color: white;
    border: none;
}

.btn-industrial-primary:hover {
    background: linear-gradient(135deg, var(--uitm-primary-dark), var(--uitm-primary));
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
}

.btn-industrial-secondary {
    background: var(--neutral-100);
    color: var(--neutral-700);
    border: 2px solid var(--neutral-200);
}

.btn-industrial-secondary:hover {
    background: var(--neutral-200);
    color: var(--neutral-800);
    transform: translateY(-2px);
}

/* ========================================
   ALERT BOXES - Industrial
   ======================================== */
.alert-industrial {
    border-radius: 10px;
    border: none;
    padding: 1rem 1.25rem;
    margin-bottom: 1rem;
}

.alert-industrial-info {
    background: linear-gradient(135deg, rgba(30, 58, 138, 0.08), rgba(59, 130, 246, 0.05));
    border-left: 4px solid var(--uitm-primary);
    color: var(--neutral-800);
}

.alert-industrial-warning {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(251, 191, 36, 0.05));
    border-left: 4px solid var(--uitm-amber);
    color: var(--neutral-800);
}

.alert-industrial-danger {
    background: linear-gradient(135deg, rgba(220, 38, 38, 0.08), rgba(239, 68, 68, 0.05));
    border-left: 4px solid var(--uitm-red);
    color: var(--neutral-800);
}

/* ========================================
   SEARCHABLE DROPDOWN - Industrial
   ======================================== */
.searchable-dropdown-wrapper {
    position: relative;
    width: 100%;
}

.searchable-dropdown-input {
    width: 100%;
    padding: 0.75rem 2.5rem 0.75rem 1rem;
    font-size: 0.95rem;
    border: 2px solid var(--neutral-200);
    border-radius: 8px;
    background-color: white;
    cursor: pointer;
    transition: all 0.2s ease;
}

.searchable-dropdown-input:focus {
    border-color: var(--uitm-primary);
    outline: 0;
    box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
}

.searchable-dropdown-arrow {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    pointer-events: none;
    color: var(--neutral-500);
    font-size: 0.75rem;
}

.searchable-dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    z-index: 1050;
    background: white;
    border: 2px solid var(--neutral-200);
    border-radius: 8px;
    margin-top: 4px;
    max-height: 280px;
    overflow-y: auto;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    display: none;
}

.searchable-dropdown-menu.show {
    display: block;
}

.searchable-dropdown-option {
    padding: 0.75rem 1rem;
    cursor: pointer;
    font-size: 0.9rem;
    transition: background-color 0.15s ease;
    color: var(--neutral-700);
}

.searchable-dropdown-option:hover {
    background-color: var(--neutral-50);
}

.searchable-dropdown-option.selected {
    background: linear-gradient(135deg, var(--uitm-primary), var(--uitm-primary-light));
    color: white;
}

.searchable-dropdown-option.no-results {
    padding: 1rem;
    text-align: center;
    color: var(--neutral-500);
    cursor: default;
}

.searchable-dropdown-option mark {
    background-color: rgba(245, 158, 11, 0.3);
    padding: 0 2px;
    font-weight: 600;
}

/* Optgroup header styling */
.searchable-dropdown-group-header {
    padding: 0.75rem 1rem;
    font-size: 0.8rem;
    font-weight: 700;
    color: var(--uitm-primary);
    background-color: var(--neutral-50);
    border-bottom: 1px solid var(--neutral-200);
    cursor: default;
    position: sticky;
    top: 0;
    z-index: 1;
    text-transform: uppercase;
    letter-spacing: 0.03em;
}

/* Grouped option indentation */
.searchable-dropdown-option.grouped-option {
    padding-left: 1.75rem;
    font-size: 0.9rem;
    position: relative;
}

.searchable-dropdown-option.grouped-option::before {
    content: "└─";
    position: absolute;
    left: 0.75rem;
    color: var(--neutral-400);
    font-size: 0.8rem;
}

/* Hide original select */
.searchable-select {
    display: none;
}

/* ========================================
   FLYOUT MENU - Industrial Style
   ======================================== */
.flyout-dropdown-wrapper {
    position: relative !important;
    width: 100% !important;
    overflow: visible !important;
}

.flyout-dropdown-input {
    width: 100%;
    padding: 0.75rem 2.5rem 0.75rem 1rem;
    font-size: 0.95rem;
    border: 2px solid var(--neutral-200);
    border-radius: 8px;
    background-color: white;
    cursor: pointer;
    transition: all 0.2s ease;
}

.flyout-dropdown-input:focus {
    border-color: var(--uitm-primary);
    outline: 0;
    box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
}

.flyout-dropdown-arrow {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    pointer-events: none;
    color: var(--neutral-500);
    font-size: 0.75rem;
    transition: all 0.2s ease;
}

.flyout-dropdown-wrapper .flyout-dropdown-menu.show ~ .flyout-dropdown-arrow,
.flyout-dropdown-arrow.open {
    transform: translateY(-50%) rotate(180deg);
    color: var(--uitm-primary);
}

/* Main Dropdown Menu */
.flyout-dropdown-menu {
    position: absolute !important;
    top: 100% !important;
    left: 0 !important;
    width: 100% !important;
    max-width: 100% !important;
    z-index: 9999 !important;
    background: white !important;
    border: 2px solid var(--neutral-200) !important;
    border-radius: 8px !important;
    margin-top: 4px !important;
    max-height: 320px !important;
    overflow-y: auto !important;
    overflow-x: visible !important;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12) !important;
    display: none !important;
    visibility: hidden !important;
    opacity: 0 !important;
}

.flyout-dropdown-menu.show {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
}

.flyout-menu-column {
    width: 100% !important;
    display: block !important;
}

/* Menu Items */
.flyout-menu-item {
    position: relative;
    padding: 0.875rem 1rem;
    cursor: pointer;
    font-size: 0.95rem;
    color: var(--neutral-700);
    background-color: white;
    transition: background-color 0.15s ease;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid var(--neutral-100);
}

.flyout-menu-item:hover,
.flyout-menu-item.active {
    background-color: var(--neutral-50);
}

.flyout-item-text {
    flex: 1;
    color: var(--neutral-700);
    font-weight: 500;
}

.flyout-item-label {
    line-height: 1.5;
}

.flyout-chevron {
    font-size: 0.7rem;
    color: var(--neutral-400);
    margin-left: 8px;
    transition: transform 0.2s ease;
}

.flyout-menu-item:hover .flyout-chevron,
.flyout-menu-item.active .flyout-chevron {
    color: var(--uitm-primary);
}

/* Submenu */
.flyout-submenu {
    display: none;
    background-color: var(--neutral-50);
    border-top: 1px solid var(--neutral-200);
    padding: 0.25rem 0;
}

.flyout-submenu.expanded {
    display: block;
}

.flyout-menu-item.expanded {
    background-color: var(--neutral-50);
}

.flyout-menu-item.expanded .flyout-chevron {
    transform: rotate(90deg);
    color: var(--uitm-primary);
}

/* Group items */
.flyout-group-item {
    padding: 0.625rem 1rem 0.625rem 2rem;
    cursor: pointer;
    font-size: 0.875rem;
    color: var(--neutral-600);
    background-color: transparent;
    transition: all 0.15s ease;
    border-left: 3px solid transparent;
}

.flyout-group-item:hover {
    background-color: white;
    border-left-color: var(--uitm-primary);
    color: var(--uitm-primary);
    padding-left: 2.25rem;
}

.flyout-group-item.selected {
    background: linear-gradient(135deg, var(--uitm-primary), var(--uitm-primary-light));
    color: white;
    border-left-color: var(--uitm-primary-dark);
    font-weight: 600;
}

.flyout-group-item.selected:hover {
    padding-left: 2rem;
}

/* Ensure parent containers don't clip */
.col-md-6:has(.flyout-dropdown-wrapper) {
    overflow: visible !important;
}

.row:has(.flyout-dropdown-wrapper) {
    overflow: visible !important;
}

.card-body:has(.flyout-dropdown-wrapper),
.card-body-industrial:has(.flyout-dropdown-wrapper) {
    overflow: visible !important;
}

.card:has(.flyout-dropdown-wrapper),
.industrial-card:has(.flyout-dropdown-wrapper) {
    overflow: visible !important;
}

.form-step:has(.flyout-dropdown-wrapper) {
    overflow: visible !important;
}

/* ========================================
   TRANSCRIPT UPLOAD - Industrial
   ======================================== */
.upload-section {
    background: var(--neutral-50);
    border: 2px dashed var(--neutral-300);
    border-radius: 12px;
    padding: 2rem;
    text-align: center;
    transition: all 0.3s ease;
}

.upload-section:hover {
    border-color: var(--uitm-primary);
    background: rgba(30, 58, 138, 0.02);
}

.upload-icon {
    width: 64px;
    height: 64px;
    border-radius: 16px;
    background: linear-gradient(135deg, var(--uitm-primary), var(--uitm-primary-light));
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    color: white;
    font-size: 1.5rem;
}

/* ========================================
   MANUAL ENTRY TABLE - Industrial
   ======================================== */
.industrial-table {
    border: 2px solid var(--neutral-200);
    border-radius: 10px;
    overflow: hidden;
}

.industrial-table th {
    background: var(--neutral-50);
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.05em;
    color: var(--neutral-600);
    padding: 0.875rem 1rem;
    border-bottom: 2px solid var(--neutral-200);
}

.industrial-table td {
    padding: 0.875rem 1rem;
    vertical-align: middle;
    border-bottom: 1px solid var(--neutral-100);
}

.industrial-table tbody tr:last-child td {
    border-bottom: none;
}

.industrial-table code {
    font-family: 'IBM Plex Mono', monospace !important;
    font-size: 0.85rem;
    color: var(--uitm-primary);
    background: rgba(30, 58, 138, 0.08);
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
}

/* ========================================
   STATUS BADGES
   ======================================== */
.status-badge {
    font-family: 'IBM Plex Sans', sans-serif;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 0.4rem 0.75rem;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
}

.status-badge-success {
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    color: #065f46;
}

.status-badge-warning {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    color: #92400e;
}

.status-badge-danger {
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    color: #991b1b;
}

.status-badge-info {
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    color: #1e40af;
}

.status-badge-secondary {
    background: linear-gradient(135deg, var(--neutral-200), var(--neutral-100));
    color: var(--neutral-600);
}

/* ========================================
   FORM STEPS
   ======================================== */
.form-step {
    display: none;
}

.form-step.active {
    display: block;
}

/* ========================================
   POPOVER - Industrial
   ======================================== */
.semester-guide-popover {
    max-width: 350px;
}

.semester-guide-popover .popover-header {
    background: linear-gradient(135deg, var(--uitm-primary), var(--uitm-primary-light));
    color: white;
    font-weight: 600;
    border-bottom: none;
}

.semester-guide-popover .popover-body {
    padding: 12px 15px;
    font-size: 0.9rem;
    line-height: 1.6;
}

.semester-guide-popover .popover-body ul {
    list-style-type: disc;
}

.semester-guide-popover .popover-body li {
    margin-bottom: 6px;
}

/* ========================================
   RESPONSIVE DESIGN
   ======================================== */
@media (max-width: 991px) {
    .page-header {
        padding: 1.5rem;
    }

    .page-header-title {
        font-size: 1.5rem;
    }
}

@media (max-width: 767px) {
    .step-progress {
        flex-direction: column;
        gap: 1rem;
    }

    .step-item {
        flex-direction: row;
        max-width: 100%;
        gap: 1rem;
    }

    .step-line {
        display: none;
    }

    .step-navigation {
        flex-direction: column;
        gap: 1rem;
    }

    .btn-industrial {
        width: 100%;
        justify-content: center;
    }
}

/* ========================================
   ACCESSIBILITY
   ======================================== */
@media (prefers-reduced-motion: reduce) {
    *,
    *::before,
    *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}

a:focus-visible,
button:focus-visible,
.btn:focus-visible {
    outline: 3px solid var(--uitm-amber);
    outline-offset: 2px;
}
</style>
@endpush

@section('content')
<div class="application-container">
    {{-- Page Header --}}
    <div class="page-header">
        <div class="page-header-pattern"></div>
        <div class="page-header-glow"></div>
        <div class="page-header-content">
            <div class="page-header-eyebrow">Credit Exemption Application</div>
            <h1 class="page-header-title">Submit New Application</h1>
            <p class="page-header-subtitle">
                Complete the form below to apply for credit exemption based on your previous diploma courses.
            </p>
        </div>
    </div>

    {{-- Breadcrumb --}}
    <div class="industrial-breadcrumb">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}"><i class="fas fa-home"></i> Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page" id="breadcrumb-step">Student Details</li>
            </ol>
        </nav>
    </div>

    {{-- Display validation errors --}}
    @if($errors->any())
        <div class="alert-industrial alert-industrial-danger">
            <h6 class="fw-bold mb-2"><i class="fas fa-exclamation-triangle me-2"></i>Please correct the following errors:</h6>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Display warning messages --}}
    @if(session('warning'))
        <div class="alert-industrial alert-industrial-warning">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('warning') }}
        </div>
    @endif

    {{-- Multi-Step Progress Indicator --}}
    <div class="step-progress">
        <div class="step-item active" data-step="1">
            <div class="step-dot">1</div>
            <div class="step-label">Student Details</div>
            <div class="step-line"></div>
        </div>
        <div class="step-item" data-step="2">
            <div class="step-dot">2</div>
            <div class="step-label">Upload Transcript</div>
        </div>
    </div>

    <form method="POST" action="{{ route('student.application.store') }}" enctype="multipart/form-data" id="multiStepForm">
        @csrf

        {{-- STEP 1: Student Details --}}
        <div class="form-step active" id="step-1">
            <div class="industrial-card">
                <div class="card-header-industrial">
                    <div class="card-header-icon">
                        <i class="fas fa-user-edit"></i>
                    </div>
                    <div class="card-header-text">
                        <h5>Fill in Your Details</h5>
                        <p>Personal and academic information</p>
                    </div>
                </div>
                <div class="card-body-industrial">
                    <h6 class="section-title">Student Information</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name') }}" required>
                            @error('full_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Student ID <span class="text-danger">*</span></label>
                            <input type="text" name="student_id" class="form-control @error('student_id') is-invalid @enderror" value="{{ old('student_id') }}" required>
                            @error('student_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">IC Number<span class="text-danger">*</span></label>
                            <input type="text" name="ic_number" class="form-control @error('ic_number') is-invalid @enderror" value="{{ old('ic_number') }}" placeholder="000000-00-0000" required>
                            @error('ic_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Home Address <span class="text-danger">*</span></label>
                            <textarea name="home_address" class="form-control @error('home_address') is-invalid @enderror" rows="2" required>{{ old('home_address') }}</textarea>
                            @error('home_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <h6 class="section-title mt-4">Current UiTM Details</h6>
                    <div class="row">
                        {{-- PROGRAMME & GROUP --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Programme & Group <span class="text-danger">*</span></label>

                            <!-- Flyout Menu Dropdown -->
                            <div class="flyout-dropdown-wrapper">
                                <input type="text"
                                       class="flyout-dropdown-input form-control"
                                       id="flyout_selected_display"
                                       placeholder="Select Your Programme and Group"
                                       readonly
                                       autocomplete="off"
                                       required
                                       style="cursor: pointer;">
                                <span class="flyout-dropdown-arrow">▼</span>

                                <!-- Main Dropdown Menu -->
                                <div class="flyout-dropdown-menu" id="flyout_menu">
                                    <div class="flyout-menu-column">
                                        <div class="flyout-menu-item" data-code="CDCS230" data-name="BACHELOR OF COMPUTER SCIENCE (HONS.)" data-groups='["CDCS2301B","CDCS2303B","CDCS2303C"]'>
                                            <span class="flyout-item-text">CDCS230 - Bachelor of Computer Science (Hons.)</span>
                                            <i class="fas fa-caret-right flyout-chevron"></i>
                                            <div class="flyout-submenu">
                                                <div class="flyout-group-item" data-group="CDCS2301B" data-code="CDCS230" data-name="BACHELOR OF COMPUTER SCIENCE (HONS.)">CDCS2301B</div>
                                                <div class="flyout-group-item" data-group="CDCS2303B" data-code="CDCS230" data-name="BACHELOR OF COMPUTER SCIENCE (HONS.)">CDCS2303B</div>
                                                <div class="flyout-group-item" data-group="CDCS2303C" data-code="CDCS230" data-name="BACHELOR OF COMPUTER SCIENCE (HONS.)">CDCS2303C</div>
                                            </div>
                                        </div>
                                        <div class="flyout-menu-item" data-code="CDCS251" data-name="BACHELOR OF COMPUTER SCIENCE (HONS.) NETCENTRIC COMPUTING" data-groups='["CDCS2513A"]'>
                                            <span class="flyout-item-text">CDCS251 - Bachelor of Computer Science (Hons.) Netcentric Computing</span>
                                            <i class="fas fa-caret-right flyout-chevron"></i>
                                            <div class="flyout-submenu">
                                                <div class="flyout-group-item" data-group="CDCS2513A" data-code="CDCS251" data-name="BACHELOR OF COMPUTER SCIENCE (HONS.) NETCENTRIC COMPUTING">CDCS2513A</div>
                                            </div>
                                        </div>
                                        <div class="flyout-menu-item" data-code="CDCS253" data-name="BACHELOR OF COMPUTER SCIENCE (HONS.) MULTIMEDIA COMPUTING" data-groups='["CDCS2531A","CDCS2533B"]'>
                                            <span class="flyout-item-text">CDCS253 - Bachelor of Computer Science (Hons.) Multimedia Computing</span>
                                            <i class="fas fa-caret-right flyout-chevron"></i>
                                            <div class="flyout-submenu">
                                                <div class="flyout-group-item" data-group="CDCS2531A" data-code="CDCS253" data-name="BACHELOR OF COMPUTER SCIENCE (HONS.) MULTIMEDIA COMPUTING">CDCS2531A</div>
                                                <div class="flyout-group-item" data-group="CDCS2533B" data-code="CDCS253" data-name="BACHELOR OF COMPUTER SCIENCE (HONS.) MULTIMEDIA COMPUTING">CDCS2533B</div>
                                            </div>
                                        </div>
                                        <div class="flyout-menu-item" data-code="CDCS255" data-name="BACHELOR OF COMPUTER SCIENCE (HONS.) COMPUTER NETWORKS" data-groups='["CDCS2551A","CDCS2553B"]'>
                                            <span class="flyout-item-text">CDCS255 - Bachelor of Computer Science (Hons.) Computer Networks</span>
                                            <i class="fas fa-caret-right flyout-chevron"></i>
                                            <div class="flyout-submenu">
                                                <div class="flyout-group-item" data-group="CDCS2551A" data-code="CDCS255" data-name="BACHELOR OF COMPUTER SCIENCE (HONS.) COMPUTER NETWORKS">CDCS2551A</div>
                                                <div class="flyout-group-item" data-group="CDCS2553B" data-code="CDCS255" data-name="BACHELOR OF COMPUTER SCIENCE (HONS.) COMPUTER NETWORKS">CDCS2553B</div>
                                            </div>
                                        </div>
                                        <div class="flyout-menu-item" data-code="CDCS266" data-name="BACHELOR OF INFORMATION SYSTEMS (HONS.) INFORMATION SYSTEMS ENGINEERING" data-groups='["CDCS2663A"]'>
                                            <span class="flyout-item-text">CDCS266 - Bachelor of Information Systems (Hons.) Information Systems Engineering</span>
                                            <i class="fas fa-caret-right flyout-chevron"></i>
                                            <div class="flyout-submenu">
                                                <div class="flyout-group-item" data-group="CDCS2663A" data-code="CDCS266" data-name="BACHELOR OF INFORMATION SYSTEMS (HONS.) INFORMATION SYSTEMS ENGINEERING">CDCS2663A</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden Inputs for Form Submission -->
                            <input type="hidden" name="student_group" id="student_group" value="{{ old('student_group', '') }}" required>
                            <input type="hidden" name="program_name" id="program_name" value="{{ old('program_name', '') }}">
                            <input type="hidden" name="program_code" id="program_code" value="{{ old('program_code', '') }}">
                            <input type="hidden" name="faculty" value="FAKULTI SAINS KOMPUTER DAN MATEMATIK">
                            <input type="hidden" name="faculty_id" value="">

                            @error('student_group')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- CURRENT CAMPUS --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Current Campus <span class="text-danger">*</span></label>
                            <select name="campus" id="campus_select" class="form-select searchable-select" required>
                                <option value="" disabled selected>Select Your Campus</option>
                                @foreach($campuses as $campus)
                                    <option value="{{ $campus->name }}" {{ old('campus') == $campus->name ? 'selected' : '' }}>{{ $campus->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Current Semester <span class="text-danger">*</span>
                                <i class="fas fa-info-circle text-primary"
                                   style="cursor: pointer; margin-left: 5px;"
                                   data-bs-toggle="popover"
                                   data-bs-placement="right"
                                   data-bs-trigger="hover focus"
                                   data-bs-html="true"
                                   data-bs-title="<strong>Semester Selection Guide</strong>"
                                   data-bs-content="<div style='text-align: left;'><p style='margin-bottom: 8px;'><strong>If you are a Diploma graduate from:</strong></p><ul style='margin-bottom: 0; padding-left: 20px;'><li>the same faculty — choose <strong>3</strong></li><li>another UiTM faculty — choose <strong>1 or 2</strong></li><li>another institution (IPT) — choose <strong>1</strong></li></ul></div>"></i>
                            </label>
                            <select name="current_semester" class="form-select @error('current_semester') is-invalid @enderror" required>
                                <option value="" disabled {{ old('current_semester') ? '' : 'selected' }}>Select your semester</option>
                                @for($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}" {{ old('current_semester') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                            @error('current_semester')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3"></div>
                    </div>

                    <h6 class="section-title mt-4">Previous Institution (IPT)</h6>

                    <!-- Institution Type Selection -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="institution_type" id="uitm_previous" value="uitm" onchange="toggleInstitutionFields()">
                                <label class="form-check-label" for="uitm_previous">
                                    UiTM (Previous Campus/Program)
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="institution_type" id="non_uitm_previous" value="non_uitm" onchange="toggleInstitutionFields()">
                                <label class="form-check-label" for="non_uitm_previous">
                                    Non-UiTM Institution
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- UiTM Previous Institution Fields -->
                    <div id="uitm_fields" style="display: none;">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 mb-3" style="float: left; width: 50%; padding-right: 15px;">
                                <label class="form-label">Previous UiTM Campus</label>
                                <select name="previous_uitm_campus" class="form-select searchable-select">
                                    <option value="" disabled selected>Select Your Previous Campus</option>
                                    @foreach($campuses as $campus)
                                        <option value="{{ $campus->name }}">{{ $campus->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 col-sm-6 mb-3" style="float: left; width: 50%; padding-left: 15px;">
                                <label class="form-label">Previous UiTM Diploma Program</label>
                                <select name="previous_uitm_program" class="form-select searchable-select">
                                    <option value="" disabled selected>Select Your Diploma Program</option>
                                    @foreach($uitmDiplomaPrograms as $program)
                                        <option value="{{ $program->name }}">
                                            {{ $program->code ? $program->code . ' - ' : '' }}{{ $program->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Non-UiTM Institution Fields -->
                    <div id="non_uitm_fields" style="display: none;">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Diploma Institution</label>
                                <select name="previous_institution" class="form-select searchable-select">
                                    <option value="" disabled selected>Select Your Institution</option>
                                    @foreach($nonUitmInstitutions as $institution)
                                        <option value="{{ $institution->name }}">{{ $institution->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Diploma Programme Code</label>
                                <input type="text" name="previous_program_code" class="form-control text-uppercase @error('previous_program_code') is-invalid @enderror" value="{{ old('previous_program_code') }}" placeholder="e.g., CS110" maxlength="10">
                                <small class="text-muted">Enter your diploma programme code if available</small>
                                @error('previous_program_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Diploma Programme Name</label>
                                <input type="text" name="previous_program" class="form-control @error('previous_program') is-invalid @enderror" value="{{ old('previous_program') }}" placeholder="e.g., Diploma in Computer Science">
                                @error('previous_program')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Step 1 Navigation --}}
                    <div class="step-navigation">
                        <div></div>
                        <button type="button" class="btn-industrial btn-industrial-primary" id="nextToStep2">
                            Upload Transcript <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- STEP 2: Upload Transcript or Manual Entry --}}
        <div class="form-step" id="step-2">
            <div class="industrial-card">
                <div class="card-header-industrial">
                    <div class="card-header-icon" style="background: linear-gradient(135deg, var(--uitm-amber), var(--uitm-amber-light));">
                        <i class="fas fa-file-upload"></i>
                    </div>
                    <div class="card-header-text">
                        <h5>Submit Transcript Information</h5>
                        <p>Upload your official transcript for processing</p>
                    </div>
                </div>
                <div class="card-body-industrial">
                    <div class="row justify-content-center">
                        <div class="col-lg-10">
                            {{-- Entry Method Selection --}}
                            <div class="mb-4" id="entry_method_selection">
                                {{-- For UiTM Previous Institution - OCR Only --}}
                                <div id="uitm_entry_options">
                                    <div class="alert-industrial alert-industrial-info">
                                        <div class="d-flex align-items-start">
                                            <i class="fas fa-robot me-3 mt-1" style="font-size: 2rem; color: var(--uitm-primary);"></i>
                                            <div>
                                                <h6 class="mb-2 fw-bold">OCR Automatic Transcript Processing</h6>
                                                <p class="mb-2">For UiTM diploma students, we use OCR (Optical Character Recognition) technology to automatically extract your course information from your official transcript.</p>
                                                <p class="mb-0 text-muted" style="font-size: 0.9rem;">
                                                    <i class="fas fa-check-circle me-1" style="color: var(--uitm-green);"></i> Fast and accurate course extraction<br>
                                                    <i class="fas fa-check-circle me-1" style="color: var(--uitm-green);"></i> Automatic grade and credit hour detection<br>
                                                    <i class="fas fa-check-circle me-1" style="color: var(--uitm-green);"></i> Instant exemption eligibility analysis
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="entry_method" id="entry_method_ocr" value="ocr">
                                </div>

                                {{-- For Non-UiTM Previous Institution - Manual Entry Only --}}
                                <div id="non_uitm_entry_options" style="display: none;">
                                    <div class="alert-industrial alert-industrial-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Manual Entry Required:</strong> OCR automatic extraction is only available for UiTM transcripts. For students from non-UiTM institution, please enter your diploma course details manually.
                                    </div>
                                    <input type="hidden" name="entry_method" id="entry_method_manual_hidden" value="manual" disabled>
                                </div>
                            </div>

                            {{-- OCR Upload Section --}}
                            <div id="ocr_section">
                                <div class="text-center mb-4">
                                    <h5 class="fw-bold mb-2">Upload Your Official UiTM Transcript</h5>
                                    <p class="text-muted">Upload a computer-generated transcript for best accuracy. This transcript will be used for OCR processing to extract your course information automatically.</p>
                                </div>

                                <div class="mb-4">
                                    <label for="transcript_file" class="form-label">Transcript (PDF only, Max 5MB) <span class="text-danger">*</span></label>
                                    <input class="form-control form-control-lg @error('transcript_file') is-invalid @enderror" type="file" id="transcript_file" name="transcript_file" accept=".pdf">
                                    @error('transcript_file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Declaration Checkbox --}}
                                <div class="alert-industrial alert-industrial-warning">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="transcript_declaration" name="transcript_declaration" style="width: 18px; height: 18px; margin-top: 0.25em;">
                                        <label class="form-check-label" for="transcript_declaration" style="margin-left: 8px; line-height: 1.6; font-size: 0.95rem;">
                                            I confirm this is my official, unaltered transcript. I understand that submitting falsified or edited documents is a serious offense and may result in disciplinary action, including rejection of my application and further sanctions.
                                        </label>
                                    </div>
                                </div>
                            </div>

                            {{-- Manual Entry Section --}}
                            <div id="manual_entry_section" style="display: none;">
                                <div class="alert-industrial alert-industrial-info">
                                    <i class="fas fa-info-circle me-2"></i><strong>Manual Entry Instructions:</strong>
                                    <ul class="mb-0 mt-2">
                                        <li>Enter each course from your diploma transcript</li>
                                        <li>Provide course code, name, grade, and credit hours</li>
                                        <li>System will automatically check for course equivalencies</li>
                                        <li>The exemption status shown (EXEMPTED, NOT FOUND, etc.) is <strong>preliminary only</strong></li>
                                        <li>Your application will be reviewed by your academic advisor</li>
                                        <li>Make sure to enter all courses before submitting</li>
                                    </ul>
                                </div>

                                {{-- Course Entry Form --}}
                                <div class="industrial-card mb-3">
                                    <div class="card-header-industrial" style="background: linear-gradient(135deg, var(--uitm-primary), var(--uitm-primary-light)); color: white;">
                                        <div class="card-header-icon" style="background: rgba(255,255,255,0.2);">
                                            <i class="fas fa-plus-circle"></i>
                                        </div>
                                        <div class="card-header-text">
                                            <h5 style="color: white;">Add Course</h5>
                                        </div>
                                    </div>
                                    <div class="card-body-industrial">
                                        <div class="row g-3">
                                            <div class="col-md-3">
                                                <label class="form-label">Course Code <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control text-uppercase" id="manual_course_code" placeholder="e.g., CSC159" maxlength="10" pattern="[A-Z]{2,4}\d{3}" title="Format: 2-4 letters + 3 digits (e.g., CSC159)">
                                                <small class="text-muted">Format: ABC123</small>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Course Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="manual_course_name" placeholder="e.g., COMPUTER ORGANIZATION" maxlength="255">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Grade <span class="text-danger">*</span></label>
                                                <select class="form-select" id="manual_grade">
                                                    <option value="">Select</option>
                                                    <option value="A+">A+</option>
                                                    <option value="A">A</option>
                                                    <option value="A-">A-</option>
                                                    <option value="B+">B+</option>
                                                    <option value="B">B</option>
                                                    <option value="B-">B-</option>
                                                    <option value="C+">C+</option>
                                                    <option value="C">C</option>
                                                    <option value="C-">C-</option>
                                                    <option value="D+">D+</option>
                                                    <option value="D">D</option>
                                                    <option value="F">F</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Credit Hours <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" id="manual_credit_hours" placeholder="3" min="1" max="6" step="0.5" value="3">
                                            </div>
                                            <div class="col-md-1">
                                                <label class="form-label" style="visibility: hidden;">Action</label>
                                                <button type="button" class="btn-industrial btn-industrial-primary w-100" onclick="addManualCourse()" style="padding: 0.75rem;">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Courses List --}}
                                <div class="industrial-card">
                                    <div class="card-header-industrial" style="background: var(--neutral-600); color: white;">
                                        <div class="card-header-icon" style="background: rgba(255,255,255,0.2);">
                                            <i class="fas fa-list"></i>
                                        </div>
                                        <div class="card-header-text">
                                            <h5 style="color: white;">Added Courses</h5>
                                        </div>
                                        <span class="status-badge status-badge-secondary ms-auto" id="course_count">0 courses</span>
                                    </div>
                                    <div class="card-body-industrial p-0">
                                        <div id="manual_courses_list" class="table-responsive">
                                            <table class="table industrial-table mb-0">
                                                <thead>
                                                    <tr>
                                                        <th width="15%">Course Code</th>
                                                        <th width="40%">Course Name</th>
                                                        <th width="10%">Grade</th>
                                                        <th width="12%">Credit Hours</th>
                                                        <th width="18%">Exemption Status</th>
                                                        <th width="5%">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="manual_courses_tbody">
                                                    <tr class="text-center text-muted">
                                                        <td colspan="6" class="py-4">
                                                            <i class="fas fa-inbox fa-2x mb-2" style="color: var(--neutral-300);"></i>
                                                            <p class="mb-0">No courses added yet. Use the form above to add courses.</p>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                {{-- Hidden input to store manual courses as JSON --}}
                                <input type="hidden" name="manual_courses" id="manual_courses_data" value="[]">

                                {{-- Manual Entry Declaration --}}
                                <div class="alert-industrial alert-industrial-warning mt-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="manual_declaration" name="manual_declaration" style="width: 18px; height: 18px; margin-top: 0.25em;">
                                        <label class="form-check-label" for="manual_declaration" style="margin-left: 8px; line-height: 1.6; font-size: 0.95rem;">
                                            I confirm that all the course information I have entered is accurate and matches my official transcript. I understand that providing false information is a serious offense and may result in disciplinary action.
                                        </label>
                                    </div>
                                </div>
                            </div>

                            {{-- Step 2 Navigation --}}
                            <div class="step-navigation mt-4">
                                <button type="button" class="btn-industrial btn-industrial-secondary" id="backToStep1">
                                    <i class="fas fa-arrow-left me-2"></i> Previous
                                </button>
                                <button type="submit" class="btn-industrial btn-industrial-primary" id="submitBtn">
                                    <i class="fas fa-paper-plane me-2"></i> Submit Application
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
// Multi-Step Form Navigation
let currentStep = 1;
const totalSteps = 2;

// Simple Searchable Dropdown Implementation
$(document).ready(function() {
    console.log('Initializing simple searchable dropdowns...');

    // Initialize Bootstrap popovers
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl, {
            container: 'body',
            customClass: 'semester-guide-popover'
        });
    });

    // Initialize searchable dropdowns
    $('.searchable-select').each(function() {
        initSearchableDropdown($(this));
    });

    // Initialize other form behaviors
    toggleInstitutionFields();

    // Multi-step navigation buttons
    $('#nextToStep2').on('click', function() {
        if (validateStep1()) {
            // Check institution type and configure Step 2 entry methods accordingly
            updateEntryMethodOptions();
            goToStep(2);
        }
    });

    $('#backToStep1').on('click', function() {
        goToStep(1);
    });

    // Form validation on submit (Step 2 only - file upload check)
    $('form').on('submit', function(e) {
        // Check if transcript file is uploaded
        const transcriptFile = document.querySelector('input[name="transcript_file"]');
        if (!transcriptFile || !transcriptFile.files || transcriptFile.files.length === 0) {
            e.preventDefault();
            alert('Upload your official transcript to proceed.');
            transcriptFile.classList.add('is-invalid');
            transcriptFile.focus();
            return false;
        }

        // Check file size
        if (transcriptFile.files[0].size > 5 * 1024 * 1024) {
            e.preventDefault();
            alert('File size must not exceed 5MB.');
            transcriptFile.classList.add('is-invalid');
            return false;
        }

        // Check if declaration checkbox is checked
        const declarationCheckbox = document.getElementById('transcript_declaration');
        if (!declarationCheckbox || !declarationCheckbox.checked) {
            e.preventDefault();
            alert('Please confirm that you are submitting an official, unaltered transcript by checking the declaration box.');
            declarationCheckbox.focus();
            const alertBox = declarationCheckbox.closest('.alert-industrial');
            if (alertBox) {
                alertBox.style.border = '2px solid var(--uitm-red)';
                setTimeout(() => {
                    alertBox.style.border = '';
                }, 3000);
            }
            return false;
        }

        return true;
    });

    // Remove error styling when user interacts with inputs
    $('input, textarea, select').on('input change', function() {
        $(this).removeClass('is-invalid');
    });

    // Specifically handle file input to remove error when file is selected
    $('#transcript_file').on('change', function() {
        if (this.files && this.files.length > 0) {
            $(this).removeClass('is-invalid');
        }
    });

    // Handle declaration checkbox to remove error border when checked
    $('#transcript_declaration').on('change', function() {
        const alertBox = this.closest('.alert-industrial');
        if (this.checked && alertBox) {
            alertBox.style.border = '';
        }
    });

    console.log('✓ All dropdowns initialized!');
});

// Initialize a single searchable dropdown
function initSearchableDropdown($select) {
    var options = [];
    var selectedValue = '';
    var selectedText = $.trim($select.find('option:first').text());

    // Collect all options (trim whitespace) and preserve optgroup structure
    $select.find('option').each(function() {
        var value = $(this).val();
        var text = $.trim($(this).text());
        var $optgroup = $(this).parent('optgroup');
        var groupLabel = $optgroup.length ? $optgroup.attr('label') : null;

        if (value) {
            options.push({
                value: value,
                text: text,
                group: groupLabel
            });
        }
    });

    // Create wrapper
    var $wrapper = $('<div class="searchable-dropdown-wrapper"></div>');
    var $input = $('<input type="text" class="searchable-dropdown-input" placeholder="' + selectedText + '" readonly>');
    var $arrow = $('<span class="searchable-dropdown-arrow">▼</span>');
    var $menu = $('<div class="searchable-dropdown-menu"></div>');

    $wrapper.append($input);
    $wrapper.append($arrow);
    $wrapper.append($menu);

    // Insert after select and hide select
    $select.after($wrapper);

    // Populate menu
    function populateMenu(searchTerm) {
        $menu.empty();
        var filteredOptions = options;

        if (searchTerm) {
            searchTerm = searchTerm.toLowerCase();
            filteredOptions = options.filter(function(opt) {
                return opt.text.toLowerCase().indexOf(searchTerm) > -1 ||
                       (opt.group && opt.group.toLowerCase().indexOf(searchTerm) > -1);
            });
        }

        if (filteredOptions.length === 0) {
            $menu.append('<div class="searchable-dropdown-option no-results">No results found</div>');
        } else {
            var currentGroup = null;

            filteredOptions.forEach(function(opt) {
                // Add group header if this is a new group
                if (opt.group && opt.group !== currentGroup) {
                    var $groupHeader = $('<div class="searchable-dropdown-group-header"></div>');
                    $groupHeader.text(opt.group);
                    $menu.append($groupHeader);
                    currentGroup = opt.group;
                }

                var $option = $('<div class="searchable-dropdown-option" data-value="' + opt.value + '"></div>');

                // Add indentation for grouped options
                if (opt.group) {
                    $option.addClass('grouped-option');
                }

                // Highlight matching text
                if (searchTerm) {
                    var idx = opt.text.toLowerCase().indexOf(searchTerm);
                    if (idx > -1) {
                        var before = opt.text.substring(0, idx);
                        var match = opt.text.substring(idx, idx + searchTerm.length);
                        var after = opt.text.substring(idx + searchTerm.length);
                        $option.html(before + '<mark>' + match + '</mark>' + after);
                    } else {
                        $option.text(opt.text);
                    }
                } else {
                    $option.text(opt.text);
                }

                if (opt.value === selectedValue) {
                    $option.addClass('selected');
                }

                $menu.append($option);
            });
        }
    }

    // Open dropdown
    $input.on('click', function() {
        $input.removeAttr('readonly');
        $input.val('');
        $input.focus();
        $menu.addClass('show');
        populateMenu('');
    });

    // Search as you type
    $input.on('input', function() {
        populateMenu($input.val());
    });

    // Select option
    $menu.on('click', '.searchable-dropdown-option:not(.no-results)', function() {
        var value = $(this).data('value');

        // Get the original text from options array (not from DOM which may have HTML)
        var selectedOption = options.find(function(opt) { return opt.value === value; });
        var text = selectedOption ? selectedOption.text : $(this).text();

        selectedValue = value;
        $select.val(value).trigger('change');
        $input.val(text);
        $input.attr('readonly', 'readonly');
        $menu.removeClass('show');
    });

    // Close on click outside
    $(document).on('click', function(e) {
        if (!$wrapper[0].contains(e.target)) {
            $input.attr('readonly', 'readonly');
            $menu.removeClass('show');
            if (selectedValue) {
                var selectedOpt = options.find(function(opt) { return opt.value === selectedValue; });
                if (selectedOpt) {
                    $input.val(selectedOpt.text);
                }
            } else {
                $input.val('');
                $input.attr('placeholder', selectedText);
            }
        }
    });
}

function toggleInstitutionFields() {
    const uitmRadio = document.getElementById('uitm_previous');
    const nonUitmRadio = document.getElementById('non_uitm_previous');
    const uitmFields = document.getElementById('uitm_fields');
    const nonUitmFields = document.getElementById('non_uitm_fields');

    // Clear required attributes from all fields first
    const allFields = document.querySelectorAll('#uitm_fields select, #non_uitm_fields select, #non_uitm_fields input');
    allFields.forEach(field => {
        field.removeAttribute('required');
        field.value = '';
        field.disabled = true;
    });

    // Remove all existing searchable dropdown wrappers
    $('#uitm_fields .searchable-dropdown-wrapper, #non_uitm_fields .searchable-dropdown-wrapper').remove();

    if (uitmRadio.checked) {
        uitmFields.style.display = 'block';
        nonUitmFields.style.display = 'none';

        document.querySelector('select[name="previous_uitm_campus"]').disabled = false;
        document.querySelector('select[name="previous_uitm_campus"]').setAttribute('required', 'required');
        document.querySelector('select[name="previous_uitm_program"]').disabled = false;
        document.querySelector('select[name="previous_uitm_program"]').setAttribute('required', 'required');

        const uitmEntryOptions = document.getElementById('uitm_entry_options');
        const nonUitmEntryOptions = document.getElementById('non_uitm_entry_options');
        if (uitmEntryOptions) uitmEntryOptions.style.display = 'block';
        if (nonUitmEntryOptions) {
            nonUitmEntryOptions.style.display = 'none';
            const manualHiddenInput = document.getElementById('entry_method_manual_hidden');
            if (manualHiddenInput) manualHiddenInput.disabled = true;
        }

        setTimeout(function() {
            $('#uitm_fields .searchable-select').each(function() {
                initSearchableDropdown($(this));
            });
        }, 100);

    } else if (nonUitmRadio.checked) {
        uitmFields.style.display = 'none';
        nonUitmFields.style.display = 'block';

        document.querySelector('select[name="previous_institution"]').disabled = false;
        document.querySelector('select[name="previous_institution"]').setAttribute('required', 'required');
        document.querySelector('input[name="previous_program"]').disabled = false;
        document.querySelector('input[name="previous_program"]').setAttribute('required', 'required');
        document.querySelector('input[name="previous_program_code"]').disabled = false;

        const uitmEntryOptions = document.getElementById('uitm_entry_options');
        const nonUitmEntryOptions = document.getElementById('non_uitm_entry_options');
        if (uitmEntryOptions) uitmEntryOptions.style.display = 'none';
        if (nonUitmEntryOptions) {
            nonUitmEntryOptions.style.display = 'block';
            const manualHiddenInput = document.getElementById('entry_method_manual_hidden');
            if (manualHiddenInput) manualHiddenInput.disabled = false;
        }

        toggleEntryMethod(true);

        setTimeout(function() {
            $('#non_uitm_fields .searchable-select').each(function() {
                initSearchableDropdown($(this));
            });
        }, 100);

    } else {
        uitmFields.style.display = 'none';
        nonUitmFields.style.display = 'none';

        const uitmEntryOptions = document.getElementById('uitm_entry_options');
        const nonUitmEntryOptions = document.getElementById('non_uitm_entry_options');
        if (uitmEntryOptions) uitmEntryOptions.style.display = 'none';
        if (nonUitmEntryOptions) nonUitmEntryOptions.style.display = 'none';
    }
}

// ========================================
// FLYOUT MENU FUNCTIONALITY
// ========================================
document.addEventListener('DOMContentLoaded', function() {
    const flyoutInput = document.getElementById('flyout_selected_display');
    const flyoutMenu = document.getElementById('flyout_menu');
    const flyoutArrow = document.querySelector('.flyout-dropdown-arrow');
    let selectedGroup = '';
    let selectedProgramCode = '';
    let selectedProgramName = '';

    // Open/Close menu on input click
    if (flyoutInput && flyoutMenu) {
        const wrapper = document.querySelector('.flyout-dropdown-wrapper');

        const toggleMenu = function(e) {
            e.preventDefault();
            e.stopPropagation();
            flyoutMenu.classList.toggle('show');
            if (flyoutArrow) flyoutArrow.classList.toggle('open');
        };

        flyoutInput.addEventListener('click', toggleMenu);
        if (wrapper) {
            wrapper.addEventListener('click', function(e) {
                if (e.target === wrapper || e.target === flyoutInput || e.target === flyoutArrow) {
                    toggleMenu(e);
                }
            });
        }
    }

    // Handle programme item click - expand/collapse groups
    document.addEventListener('click', function(e) {
        const programmeItem = e.target.closest('.flyout-menu-item');

        if (programmeItem && !e.target.classList.contains('flyout-group-item')) {
            e.stopPropagation();

            const submenu = programmeItem.querySelector('.flyout-submenu');
            const isExpanded = programmeItem.classList.contains('expanded');

            document.querySelectorAll('.flyout-menu-item.expanded').forEach(item => {
                if (item !== programmeItem) {
                    item.classList.remove('expanded');
                    const otherSubmenu = item.querySelector('.flyout-submenu');
                    if (otherSubmenu) otherSubmenu.classList.remove('expanded');
                }
            });

            if (isExpanded) {
                programmeItem.classList.remove('expanded');
                if (submenu) submenu.classList.remove('expanded');
            } else {
                programmeItem.classList.add('expanded');
                if (submenu) submenu.classList.add('expanded');
            }
        }
    });

    // Handle group selection
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('flyout-group-item')) {
            e.stopPropagation();

            const groupItem = e.target;
            const group = groupItem.getAttribute('data-group');
            const code = groupItem.getAttribute('data-code');
            const name = groupItem.getAttribute('data-name');

            selectedGroup = group;
            selectedProgramCode = code;
            selectedProgramName = name;

            flyoutInput.value = code + ' - ' + group;

            document.getElementById('student_group').value = group;
            document.getElementById('program_code').value = code;
            document.getElementById('program_name').value = name;

            document.querySelectorAll('.flyout-group-item').forEach(item => {
                item.classList.remove('selected');
            });
            groupItem.classList.add('selected');

            document.querySelectorAll('.flyout-menu-item.expanded').forEach(item => {
                item.classList.remove('expanded');
                const submenu = item.querySelector('.flyout-submenu');
                if (submenu) submenu.classList.remove('expanded');
            });

            flyoutMenu.classList.remove('show');
            if (flyoutArrow) flyoutArrow.classList.remove('open');
        }
    });

    // Close menu on outside click
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.flyout-dropdown-wrapper')) {
            document.querySelectorAll('.flyout-menu-item.expanded').forEach(item => {
                item.classList.remove('expanded');
                const submenu = item.querySelector('.flyout-submenu');
                if (submenu) submenu.classList.remove('expanded');
            });

            flyoutMenu.classList.remove('show');
            if (flyoutArrow) flyoutArrow.classList.remove('open');
        }
    });

    // Keyboard navigation (ESC to close)
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && flyoutMenu.classList.contains('show')) {
            document.querySelectorAll('.flyout-menu-item.expanded').forEach(item => {
                item.classList.remove('expanded');
                const submenu = item.querySelector('.flyout-submenu');
                if (submenu) submenu.classList.remove('expanded');
            });

            flyoutMenu.classList.remove('show');
            if (flyoutArrow) flyoutArrow.classList.remove('open');
        }
    });

    // Restore previously selected value
    const oldGroup = document.getElementById('student_group').value;
    const oldCode = document.getElementById('program_code').value;

    if (oldGroup && oldCode) {
        selectedGroup = oldGroup;
        selectedProgramCode = oldCode;
        selectedProgramName = document.getElementById('program_name').value;
        flyoutInput.value = oldCode + ' - ' + oldGroup;

        document.querySelectorAll('.flyout-group-item').forEach(function(item) {
            if (item.getAttribute('data-group') === oldGroup &&
                item.getAttribute('data-code') === oldCode) {
                item.classList.add('selected');
            }
        });
    }
});

// Multi-Step Navigation Functions
function goToStep(stepNumber) {
    $('.form-step').removeClass('active');
    $('#step-' + stepNumber).addClass('active');

    $('.step-item').removeClass('active completed');

    for (let i = 1; i <= totalSteps; i++) {
        const $stepItem = $('.step-item[data-step="' + i + '"]');
        if (i < stepNumber) {
            $stepItem.addClass('completed');
        } else if (i === stepNumber) {
            $stepItem.addClass('active');
        }
    }

    const breadcrumbTexts = {
        1: 'Student Details',
        2: 'Upload Transcript'
    };
    $('#breadcrumb-step').text(breadcrumbTexts[stepNumber]);

    currentStep = stepNumber;

    $('html, body').animate({ scrollTop: 0 }, 400);
}

function validateStep1() {
    let isValid = true;
    const errors = [];

    const requiredFields = [
        { name: 'full_name', label: 'Full Name' },
        { name: 'student_id', label: 'Student ID' },
        { name: 'ic_number', label: 'IC Number' },
        { name: 'home_address', label: 'Home Address' },
        { name: 'current_semester', label: 'Current Semester' }
    ];

    requiredFields.forEach(field => {
        const input = document.querySelector(`[name="${field.name}"]`);
        if (!input || !input.value.trim()) {
            isValid = false;
            errors.push(field.label);
            if (input) {
                input.classList.add('is-invalid');
            }
        } else {
            if (input) {
                input.classList.remove('is-invalid');
            }
        }
    });

    const campusSelect = document.querySelector('select[name="campus"]');
    if (!campusSelect || !campusSelect.value) {
        isValid = false;
        errors.push('Current Campus');
        if (campusSelect) campusSelect.classList.add('is-invalid');
    } else {
        if (campusSelect) campusSelect.classList.remove('is-invalid');
    }

    const studentGroupInput = document.getElementById('student_group');
    const flyoutDisplayInput = document.getElementById('flyout_selected_display');
    if (!studentGroupInput || !studentGroupInput.value) {
        isValid = false;
        errors.push('Programme & Group');
        if (flyoutDisplayInput) flyoutDisplayInput.classList.add('is-invalid');
    } else {
        if (flyoutDisplayInput) flyoutDisplayInput.classList.remove('is-invalid');
    }

    const institutionType = document.querySelector('input[name="institution_type"]:checked');
    if (!institutionType) {
        isValid = false;
        errors.push('Institution Type (UiTM or Non-UiTM)');
    } else {
        if (institutionType.value === 'uitm') {
            const uitmCampus = document.querySelector('select[name="previous_uitm_campus"]');
            const uitmProgram = document.querySelector('select[name="previous_uitm_program"]');

            if (!uitmCampus || !uitmCampus.value) {
                isValid = false;
                errors.push('Previous UiTM Campus');
                if (uitmCampus) uitmCampus.classList.add('is-invalid');
            }

            if (!uitmProgram || !uitmProgram.value) {
                isValid = false;
                errors.push('Previous UiTM Diploma Program');
                if (uitmProgram) uitmProgram.classList.add('is-invalid');
            }
        } else if (institutionType.value === 'non_uitm') {
            const nonUitmInstitution = document.querySelector('select[name="previous_institution"]');
            const nonUitmProgram = document.querySelector('input[name="previous_program"]');

            if (!nonUitmInstitution || !nonUitmInstitution.value) {
                isValid = false;
                errors.push('Previous Institution');
                if (nonUitmInstitution) nonUitmInstitution.classList.add('is-invalid');
            }

            if (!nonUitmProgram || !nonUitmProgram.value.trim()) {
                isValid = false;
                errors.push('Previous Program Name');
                if (nonUitmProgram) nonUitmProgram.classList.add('is-invalid');
            }
        }
    }

    const semesterSelect = document.querySelector('select[name="current_semester"]');
    if (semesterSelect && !semesterSelect.value) {
        isValid = false;
        errors.push('Current Semester');
        semesterSelect.classList.add('is-invalid');
    }

    if (!isValid) {
        alert('Please fill in the following required fields:\n\n• ' + errors.join('\n• '));
        const firstErrorField = document.querySelector('.is-invalid');
        if (firstErrorField) {
            firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstErrorField.focus();
        }
    }

    return isValid;
}

// ========================================
// MANUAL TRANSCRIPT ENTRY FUNCTIONS
// ========================================
let manualCourses = [];

function updateEntryMethodOptions() {
    const institutionType = document.querySelector('input[name="institution_type"]:checked');
    const uitmEntryOptions = document.getElementById('uitm_entry_options');
    const nonUitmEntryOptions = document.getElementById('non_uitm_entry_options');
    const hiddenEntryMethod = document.getElementById('entry_method_manual_hidden');

    if (!institutionType) {
        return;
    }

    if (institutionType.value === 'non_uitm') {
        uitmEntryOptions.style.display = 'none';
        nonUitmEntryOptions.style.display = 'block';

        if (hiddenEntryMethod) hiddenEntryMethod.disabled = false;

        toggleEntryMethod(true);
    } else {
        uitmEntryOptions.style.display = 'block';
        nonUitmEntryOptions.style.display = 'none';

        if (hiddenEntryMethod) hiddenEntryMethod.disabled = true;

        toggleEntryMethod(false);
    }
}

function toggleEntryMethod(forceManual = false) {
    const ocrSection = document.getElementById('ocr_section');
    const manualSection = document.getElementById('manual_entry_section');
    const transcriptFile = document.getElementById('transcript_file');
    const transcriptDeclaration = document.getElementById('transcript_declaration');
    const manualDeclaration = document.getElementById('manual_declaration');

    const ocrHiddenInput = document.querySelector('#uitm_entry_options input[name="entry_method"][value="ocr"]');

    if (ocrHiddenInput && ocrHiddenInput.type === 'hidden') {
        ocrSection.style.display = 'block';
        manualSection.style.display = 'none';
        transcriptFile.setAttribute('required', 'required');
        transcriptDeclaration.setAttribute('required', 'required');
        if (manualDeclaration) manualDeclaration.removeAttribute('required');
        return;
    }

    let manualMode = forceManual;

    if (!forceManual) {
        const ocrRadio = document.getElementById('entry_method_ocr');
        const manualRadioUitm = document.getElementById('entry_method_manual_uitm');

        if (ocrRadio && ocrRadio.type === 'radio' && ocrRadio.checked) {
            manualMode = false;
        } else if (manualRadioUitm && manualRadioUitm.checked) {
            manualMode = true;
        }
    }

    if (manualMode) {
        ocrSection.style.display = 'none';
        manualSection.style.display = 'block';

        transcriptFile.removeAttribute('required');
        transcriptDeclaration.removeAttribute('required');
        if (manualDeclaration) manualDeclaration.setAttribute('required', 'required');
    } else {
        ocrSection.style.display = 'block';
        manualSection.style.display = 'none';

        transcriptFile.setAttribute('required', 'required');
        transcriptDeclaration.setAttribute('required', 'required');
        if (manualDeclaration) manualDeclaration.removeAttribute('required');
    }
}

function addManualCourse() {
    const courseCode = document.getElementById('manual_course_code').value.trim().toUpperCase();
    const courseName = document.getElementById('manual_course_name').value.trim();
    const grade = document.getElementById('manual_grade').value;
    const creditHours = parseFloat(document.getElementById('manual_credit_hours').value);

    if (!courseCode || !courseName || !grade || !creditHours) {
        alert('Please fill in all course fields before adding.');
        return;
    }

    const courseCodePattern = /^[A-Z]{2,4}\d{3}$/;
    if (!courseCodePattern.test(courseCode)) {
        alert('Invalid course code format. Use format like CSC159 (2-4 letters + 3 digits).');
        document.getElementById('manual_course_code').focus();
        return;
    }

    if (manualCourses.some(course => course.code === courseCode)) {
        alert('This course code has already been added.');
        return;
    }

    const programCode = document.getElementById('program_code').value;

    checkCourseEquivalency(courseCode, courseName, grade, creditHours, programCode);
}

function checkCourseEquivalency(courseCode, courseName, grade, creditHours, programCode) {
    const addButton = document.querySelector('button[onclick="addManualCourse()"]');
    const originalButtonHtml = addButton.innerHTML;
    addButton.disabled = true;
    addButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

    const institutionType = document.querySelector('input[name="institution_type"]:checked')?.value || 'uitm';
    let institution = null;
    if (institutionType === 'non_uitm') {
        institution = document.querySelector('select[name="previous_institution"]')?.value || null;
    } else {
        institution = document.querySelector('select[name="previous_uitm_campus"]')?.value || 'UiTM';
    }

    fetch('{{ route("student.application.check-equivalency") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            course_code: courseCode,
            program_code: programCode || 'CDCS251',
            institution: institution,
            institution_type: institutionType
        })
    })
    .then(response => response.json())
    .then(data => {
        const gradeGPA = gradeToGPA(grade);

        let status = 'pending';
        let statusBadge = '';
        let exemptionReason = '';

        if (data.found) {
            const gradeAcceptable = isGradeAcceptable(grade);
            const matchPercentageOK = data.match_percentage > 80;

            if (gradeAcceptable && matchPercentageOK) {
                status = 'exempted';
                statusBadge = '<span class="status-badge status-badge-success"><i class="fas fa-check-circle"></i> EXEMPTED</span>';
                exemptionReason = `All criteria met: Course found, grade ${grade} ≥ C, match ${data.match_percentage}% > 80%`;
            } else if (!gradeAcceptable) {
                status = 'not_eligible_grade';
                statusBadge = '<span class="status-badge status-badge-danger"><i class="fas fa-times-circle"></i> GRADE TOO LOW</span>';
                exemptionReason = `Grade ${grade} is below minimum requirement (C required)`;
            } else if (!matchPercentageOK) {
                status = 'not_eligible_match';
                statusBadge = '<span class="status-badge status-badge-warning"><i class="fas fa-exclamation-circle"></i> LOW MATCH</span>';
                exemptionReason = `Match percentage ${data.match_percentage}% is below 80% threshold`;
            }
        } else {
            status = 'not_found';
            statusBadge = '<span class="status-badge status-badge-secondary"><i class="fas fa-question-circle"></i> NOT FOUND</span>';
            exemptionReason = data.message || `Course not found in ${programCode} equivalency database`;
        }

        const course = {
            code: courseCode,
            name: courseName,
            grade: grade,
            gradeGPA: gradeGPA,
            creditHours: creditHours,
            status: status,
            exemptionReason: exemptionReason,
            equivalentCourse: data.degree_course_code || null,
            matchPercentage: data.match_percentage || 0
        };

        manualCourses.push(course);

        document.getElementById('manual_courses_data').value = JSON.stringify(manualCourses);

        addCourseToTable(course, statusBadge);

        clearManualEntryForm();

        addButton.disabled = false;
        addButton.innerHTML = originalButtonHtml;

        updateCourseCount();
    })
    .catch(error => {
        console.error('Error checking equivalency:', error);
        alert('An error occurred while checking course equivalency. The course will be added for manual review.');

        const course = {
            code: courseCode,
            name: courseName,
            grade: grade,
            gradeGPA: gradeToGPA(grade),
            creditHours: creditHours,
            status: 'pending',
            exemptionReason: 'Pending equivalency verification',
            equivalentCourse: null,
            matchPercentage: 0
        };

        manualCourses.push(course);
        document.getElementById('manual_courses_data').value = JSON.stringify(manualCourses);

        const statusBadge = '<span class="status-badge status-badge-info"><i class="fas fa-clock"></i> PENDING</span>';
        addCourseToTable(course, statusBadge);
        clearManualEntryForm();

        addButton.disabled = false;
        addButton.innerHTML = originalButtonHtml;
        updateCourseCount();
    });
}

function addCourseToTable(course, statusBadge) {
    const tbody = document.getElementById('manual_courses_tbody');

    if (tbody.querySelector('.text-muted')) {
        tbody.innerHTML = '';
    }

    const row = document.createElement('tr');
    row.setAttribute('data-course-code', course.code);
    row.innerHTML = `
        <td><code>${course.code}</code></td>
        <td>${course.name}</td>
        <td>${course.grade}</td>
        <td>${course.creditHours}</td>
        <td>${statusBadge}</td>
        <td>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeManualCourse('${course.code}')">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;

    tbody.appendChild(row);
}

function removeManualCourse(courseCode) {
    if (!confirm(`Remove course ${courseCode}?`)) {
        return;
    }

    manualCourses = manualCourses.filter(course => course.code !== courseCode);

    document.getElementById('manual_courses_data').value = JSON.stringify(manualCourses);

    const row = document.querySelector(`tr[data-course-code="${courseCode}"]`);
    if (row) {
        row.remove();
    }

    updateCourseCount();

    const tbody = document.getElementById('manual_courses_tbody');
    if (tbody.children.length === 0) {
        tbody.innerHTML = `
            <tr class="text-center text-muted">
                <td colspan="6" class="py-4">
                    <i class="fas fa-inbox fa-2x mb-2" style="color: var(--neutral-300);"></i>
                    <p class="mb-0">No courses added yet. Use the form above to add courses.</p>
                </td>
            </tr>
        `;
    }
}

function clearManualEntryForm() {
    document.getElementById('manual_course_code').value = '';
    document.getElementById('manual_course_name').value = '';
    document.getElementById('manual_grade').value = '';
    document.getElementById('manual_credit_hours').value = '3';
    document.getElementById('manual_course_code').focus();
}

function updateCourseCount() {
    const count = manualCourses.length;
    document.getElementById('course_count').textContent = count + (count === 1 ? ' course' : ' courses');
}

function gradeToGPA(grade) {
    const gradeMap = {
        'A+': 4.00, 'A': 4.00, 'A-': 3.67,
        'B+': 3.33, 'B': 3.00, 'B-': 2.67,
        'C+': 2.33, 'C': 2.00, 'C-': 1.67,
        'D+': 1.33, 'D': 1.00, 'F': 0.00
    };
    return gradeMap[grade] || 0.00;
}

function isGradeAcceptable(grade) {
    const acceptableGrades = ['A+', 'A', 'A-', 'B+', 'B', 'B-', 'C+', 'C'];
    return acceptableGrades.includes(grade);
}

$(document).ready(function() {
    const originalFormSubmit = $('form').off('submit').on('submit', function(e) {
        let entryMethod = document.querySelector('input[name="entry_method"]:checked');

        if (!entryMethod) {
            const uitmOcrHidden = document.querySelector('#uitm_entry_options input[name="entry_method"][value="ocr"]');
            if (uitmOcrHidden && uitmOcrHidden.type === 'hidden') {
                entryMethod = { value: 'ocr' };
            }

            const nonUitmManualHidden = document.getElementById('entry_method_manual_hidden');
            if (nonUitmManualHidden && !nonUitmManualHidden.disabled) {
                entryMethod = { value: 'manual' };
            }
        }

        if (entryMethod && entryMethod.value === 'manual') {
            if (manualCourses.length === 0) {
                e.preventDefault();
                alert('Please add at least one course before submitting.');
                return false;
            }

            const manualDeclaration = document.getElementById('manual_declaration');
            if (manualDeclaration && !manualDeclaration.checked) {
                e.preventDefault();
                alert('Please confirm that you have entered accurate course information by checking the declaration box.');
                manualDeclaration.focus();
                const alertBox = manualDeclaration.closest('.alert-industrial');
                if (alertBox) {
                    alertBox.style.border = '2px solid var(--uitm-red)';
                    setTimeout(() => {
                        alertBox.style.border = '';
                    }, 3000);
                }
                return false;
            }

            return true;
        }

        return true;
    });
});
</script>
@endpush
