@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500;600;700&display=swap" rel="stylesheet">
<style>
/* ============================================
   INDUSTRIAL UI DESIGN SYSTEM
   HEA Pending Endorsements
   ============================================ */

.hea-pending {
    --uitm-blue: #1e3a8a;
    --uitm-blue-light: #3b82f6;
    --uitm-amber: #f59e0b;
    --uitm-amber-light: #fbbf24;
    --industrial-dark: #0f172a;
    --industrial-gray: #334155;
    --industrial-light: #f1f5f9;
    --success: #059669;
    --danger: #dc2626;
    --warning: #ea580c;
    --teal: #0d9488;

    font-family: 'IBM Plex Sans', -apple-system, BlinkMacSystemFont, sans-serif;
    padding: 0 1.5rem 2rem;
    background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
    min-height: 100vh;
}

.hea-pending h1, .hea-pending h2, .hea-pending h3, .hea-pending h4, .hea-pending h5, .hea-pending h6 {
    font-family: 'IBM Plex Sans', sans-serif;
    font-weight: 600;
    color: var(--industrial-dark);
}

.hea-pending .mono-value {
    font-family: 'IBM Plex Mono', monospace;
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

.page-header .header-grid {
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px),
        linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px);
    background-size: 32px 32px;
}

.page-header .header-accent {
    position: absolute;
    top: -100px;
    right: -100px;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(245, 158, 11, 0.15) 0%, transparent 70%);
    border-radius: 50%;
}

.page-header .header-content {
    position: relative;
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
}

.page-header .header-left {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.page-header .role-badge {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
}

.page-header .badge-text {
    background: var(--uitm-amber);
    color: var(--industrial-dark);
    font-family: 'IBM Plex Mono', monospace;
    font-size: 0.75rem;
    font-weight: 700;
    padding: 0.4rem 0.6rem;
    border-radius: 4px;
    letter-spacing: 0.05em;
}

.page-header .badge-line {
    width: 2px;
    height: 20px;
    background: linear-gradient(to bottom, var(--uitm-amber), transparent);
}

.page-header .page-title h1 {
    color: white;
    font-size: 1.5rem;
    margin: 0 0 0.25rem;
}

.page-header .page-title p {
    color: rgba(255,255,255,0.6);
    font-size: 0.875rem;
    margin: 0;
}

.page-header .header-actions {
    display: flex;
    gap: 0.75rem;
}

.header-btn {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.625rem 1rem;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s;
    border: none;
    cursor: pointer;
}

.header-btn.primary {
    background: rgba(255,255,255,0.15);
    color: white;
    border: 1px solid rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
}

.header-btn.primary:hover {
    background: rgba(255,255,255,0.25);
    color: white;
}

.header-btn.secondary {
    background: rgba(255,255,255,0.08);
    color: rgba(255,255,255,0.7);
    border: 1px solid rgba(255,255,255,0.1);
}

.header-btn.secondary:hover {
    background: rgba(255,255,255,0.15);
    color: white;
}

/* ============================================
   STATS METRICS
   ============================================ */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}

@media (min-width: 992px) {
    .stats-grid {
        grid-template-columns: repeat(4, 1fr);
    }
}

.stat-card {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 1.25rem;
    position: relative;
    overflow: hidden;
    transition: all 0.2s ease;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
}

.stat-card.teal::before { background: var(--teal); }
.stat-card.amber::before { background: var(--uitm-amber); }
.stat-card.blue::before { background: var(--uitm-blue-light); }
.stat-card.red::before { background: var(--danger); }
.stat-card.success-border::before { background: var(--success); }

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    border-color: #cbd5e1;
}

.stat-card .stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.75rem;
}

.stat-card.teal .stat-icon { background: rgba(13, 148, 136, 0.1); color: var(--teal); }
.stat-card.amber .stat-icon { background: rgba(245, 158, 11, 0.1); color: var(--uitm-amber); }
.stat-card.blue .stat-icon { background: rgba(59, 130, 246, 0.1); color: var(--uitm-blue-light); }
.stat-card.red .stat-icon { background: rgba(220, 38, 38, 0.1); color: var(--danger); }
.stat-card.success-border .stat-icon { background: rgba(5, 150, 105, 0.1); color: var(--success); }

.stat-card .stat-value {
    font-family: 'IBM Plex Mono', monospace;
    font-size: 2rem;
    font-weight: 700;
    color: var(--industrial-dark);
    line-height: 1;
    display: block;
}

.stat-card .stat-label {
    font-size: 0.8rem;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.02em;
    display: block;
    margin-top: 0.25rem;
}

.stat-card .stat-alert {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.7rem;
    font-weight: 600;
    padding: 0.2rem 0.5rem;
    border-radius: 4px;
    margin-top: 0.5rem;
}

.stat-alert.danger {
    background: rgba(220, 38, 38, 0.1);
    color: var(--danger);
}

.stat-alert.success {
    background: rgba(5, 150, 105, 0.1);
    color: var(--success);
}

/* ============================================
   FILTER SECTION
   ============================================ */
.filter-section {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 1.25rem;
    margin-bottom: 1.5rem;
}

.filter-section .filter-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid #f1f5f9;
}

.filter-section .filter-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(30, 58, 138, 0.1);
    color: var(--uitm-blue);
}

.filter-section .filter-title {
    font-size: 0.9375rem;
    font-weight: 600;
    margin: 0;
    color: var(--industrial-dark);
}

.filter-form {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

@media (min-width: 992px) {
    .filter-form {
        grid-template-columns: repeat(4, 1fr);
    }
}

.filter-group label {
    display: block;
    font-size: 0.75rem;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.03em;
    margin-bottom: 0.4rem;
}

.filter-select {
    width: 100%;
    padding: 0.625rem 2rem 0.625rem 0.75rem;
    background: var(--industrial-light);
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-family: 'IBM Plex Sans', sans-serif;
    font-size: 0.8rem;
    font-weight: 500;
    color: var(--industrial-dark);
    appearance: none;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.5rem center;
    background-repeat: no-repeat;
    background-size: 1.25em 1.25em;
    cursor: pointer;
    transition: all 0.2s;
}

.filter-select:focus {
    outline: none;
    border-color: var(--uitm-blue-light);
    box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
    background-color: white;
}

.filter-select:hover {
    border-color: #cbd5e1;
    background-color: white;
}

/* ============================================
   EMPTY STATE
   ============================================ */
.empty-state-card {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    text-align: center;
    padding: 4rem 2rem;
}

.empty-state-card .empty-icon {
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

.empty-state-card h4 {
    font-size: 1.25rem;
    margin: 0 0 0.5rem;
}

.empty-state-card p {
    font-size: 0.9rem;
    color: #64748b;
    margin: 0 0 1.5rem;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
}

.empty-state-card .view-published-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    background: var(--success);
    color: white;
    font-size: 0.875rem;
    font-weight: 500;
    padding: 0.7rem 1.25rem;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.2s;
}

.empty-state-card .view-published-btn:hover {
    background: #047857;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
}

/* ============================================
   PROGRAM ACCORDION
   ============================================ */
.program-group {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    margin-bottom: 1rem;
    overflow: hidden;
    transition: box-shadow 0.2s;
}

.program-group:hover {
    box-shadow: 0 4px 16px rgba(0,0,0,0.06);
}

.program-group-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.25rem;
    background: white;
    cursor: pointer;
    border: none;
    width: 100%;
    text-align: left;
    font-family: inherit;
    transition: background 0.2s;
    border-bottom: 1px solid transparent;
}

.program-group-header:hover {
    background: #fafbfc;
}

.program-group-header.active {
    background: #fafbfc;
    border-bottom: 1px solid #e2e8f0;
}

.program-group-left {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.program-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: linear-gradient(135deg, rgba(30, 58, 138, 0.1) 0%, rgba(59, 130, 246, 0.1) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--uitm-blue);
}

.program-code {
    font-family: 'IBM Plex Mono', monospace;
    font-size: 0.875rem;
    font-weight: 700;
    color: var(--uitm-blue);
    background: rgba(30, 58, 138, 0.05);
    padding: 0.2rem 0.5rem;
    border-radius: 4px;
}

.program-name {
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--industrial-gray);
}

.program-group-right {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.pending-count-badge {
    font-family: 'IBM Plex Mono', monospace;
    font-size: 0.75rem;
    font-weight: 600;
    background: var(--uitm-blue);
    color: white;
    padding: 0.3rem 0.7rem;
    border-radius: 20px;
}

.chevron-icon {
    width: 20px;
    height: 20px;
    color: #94a3b8;
    transition: transform 0.3s ease;
}

.program-group-header.active .chevron-icon {
    transform: rotate(180deg);
}

.program-group-body {
    display: none;
}

.program-group-body.show {
    display: block;
}

/* ============================================
   LIST ITEMS
   ============================================ */
.list-item-card {
    padding: 1.5rem 1.25rem;
    border-bottom: 1px solid #f1f5f9;
    transition: background 0.15s;
}

.list-item-card:last-child {
    border-bottom: none;
}

.list-item-card:hover {
    background: #fafbfc;
}

.list-item-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.list-item-info {
    flex: 1;
}

.list-item-badges {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
    flex-wrap: wrap;
}

.urgency-tag {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    font-size: 0.7rem;
    font-weight: 700;
    padding: 0.3rem 0.6rem;
    border-radius: 4px;
    text-transform: uppercase;
    letter-spacing: 0.03em;
}

.urgency-tag.new {
    background: rgba(245, 158, 11, 0.15);
    color: #b45309;
}

.urgency-tag.review {
    background: rgba(59, 130, 246, 0.15);
    color: var(--uitm-blue);
}

.urgency-tag.critical {
    background: rgba(220, 38, 38, 0.15);
    color: var(--danger);
    animation: urgencyPulse 2s infinite;
}

@keyframes urgencyPulse {
    0%, 100% { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.3); }
    50% { box-shadow: 0 0 0 4px rgba(220, 38, 38, 0); }
}

.semester-label {
    font-size: 1rem;
    font-weight: 600;
    color: var(--industrial-dark);
}

.list-item-status {
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.35rem 0.75rem;
    border-radius: 6px;
}

.status-submitted {
    background: rgba(245, 158, 11, 0.12);
    color: #b45309;
    border: 1px solid rgba(245, 158, 11, 0.25);
}

.status-under-review {
    background: rgba(59, 130, 246, 0.12);
    color: var(--uitm-blue);
    border: 1px solid rgba(59, 130, 246, 0.25);
}

.list-item-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
    font-size: 0.8rem;
    color: #64748b;
}

.meta-detail {
    display: flex;
    align-items: center;
    gap: 0.35rem;
}

.meta-detail svg {
    flex-shrink: 0;
}

.waiting-days {
    font-family: 'IBM Plex Mono', monospace;
    font-weight: 600;
}

.waiting-days.success { color: var(--success); }
.waiting-days.warning { color: var(--warning); }
.waiting-days.danger { color: var(--danger); }

.reviewer-info {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.775rem;
    color: var(--teal);
    margin-top: 0.5rem;
    background: rgba(13, 148, 136, 0.06);
    padding: 0.3rem 0.6rem;
    border-radius: 6px;
    width: fit-content;
}

/* Statistics Row */
.list-statistics {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    margin-bottom: 0.75rem;
    flex-wrap: wrap;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 0.35rem;
}

.stat-item svg {
    flex-shrink: 0;
}

.stat-item .stat-num {
    font-family: 'IBM Plex Mono', monospace;
    font-weight: 600;
    color: var(--industrial-dark);
    font-size: 0.9rem;
}

.stat-item .stat-text {
    font-size: 0.8rem;
    color: #64748b;
}

/* Progress Bar */
.eligibility-progress {
    height: 6px;
    background: #e2e8f0;
    border-radius: 3px;
    overflow: hidden;
    margin-bottom: 1rem;
    max-width: 400px;
}

.eligibility-progress .progress-fill {
    height: 100%;
    border-radius: 3px;
    background: linear-gradient(90deg, var(--success) 0%, var(--teal) 100%);
    transition: width 0.6s ease;
}

/* Changes Section */
.changes-section {
    background: rgba(59, 130, 246, 0.04);
    border: 1px solid rgba(59, 130, 246, 0.1);
    border-radius: 8px;
    padding: 0.875rem 1rem;
    margin-bottom: 1rem;
}

.changes-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
}

.changes-header svg {
    color: var(--uitm-blue-light);
}

.changes-header .changes-title {
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--industrial-dark);
}

.changes-header .changes-prev {
    font-size: 0.75rem;
    color: #64748b;
}

.changes-badges {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.change-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.7rem;
    font-weight: 600;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
}

.change-badge.added {
    background: rgba(5, 150, 105, 0.1);
    color: var(--success);
}

.change-badge.modified {
    background: rgba(245, 158, 11, 0.1);
    color: #b45309;
}

.change-badge.removed {
    background: rgba(220, 38, 38, 0.1);
    color: var(--danger);
}

.change-badge.none {
    background: rgba(100, 116, 139, 0.1);
    color: #64748b;
}

.first-submission-note {
    background: rgba(100, 116, 139, 0.06);
    border: 1px solid rgba(100, 116, 139, 0.1);
    border-radius: 8px;
    padding: 0.75rem 1rem;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.8rem;
    color: #64748b;
}

.first-submission-note svg {
    flex-shrink: 0;
    color: #94a3b8;
}

/* Submission Notes */
.submission-notes {
    background: rgba(245, 158, 11, 0.04);
    border: 1px solid rgba(245, 158, 11, 0.12);
    border-radius: 8px;
    padding: 0.875rem 1rem;
    margin-bottom: 1rem;
}

.submission-notes .notes-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: #b45309;
    text-transform: uppercase;
    letter-spacing: 0.03em;
    margin-bottom: 0.35rem;
    display: flex;
    align-items: center;
    gap: 0.35rem;
}

.submission-notes .notes-content {
    font-size: 0.85rem;
    color: var(--industrial-dark);
    line-height: 1.5;
}

/* Action Buttons */
.list-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.action-btn-styled {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    font-family: 'IBM Plex Sans', sans-serif;
    font-size: 0.8rem;
    font-weight: 600;
    padding: 0.625rem 1rem;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.2s;
}

.action-btn-styled.review {
    background: var(--uitm-blue);
    color: white;
}

.action-btn-styled.review:hover {
    background: #1e40af;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
    color: white;
}

.action-btn-styled.endorse {
    background: var(--success);
    color: white;
}

.action-btn-styled.endorse:hover {
    background: #047857;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
}

.action-btn-styled.reject {
    background: white;
    color: var(--danger);
    border: 1px solid rgba(220, 38, 38, 0.3);
}

.action-btn-styled.reject:hover {
    background: var(--danger);
    color: white;
    border-color: var(--danger);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.25);
}

/* ============================================
   MODALS - INDUSTRIAL DESIGN
   ============================================ */
.industrial-modal .modal-content {
    border: none;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 25px 50px rgba(0,0,0,0.15);
}

.industrial-modal .modal-header {
    border-bottom: none;
    padding: 1.25rem 1.5rem;
}

.industrial-modal .modal-header.endorse-header {
    background: linear-gradient(135deg, var(--success) 0%, #047857 100%);
    color: white;
}

.industrial-modal .modal-header.reject-header {
    background: linear-gradient(135deg, var(--danger) 0%, #b91c1c 100%);
    color: white;
}

.industrial-modal .modal-title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-family: 'IBM Plex Sans', sans-serif;
    font-size: 1rem;
    font-weight: 600;
}

.industrial-modal .modal-body {
    padding: 1.5rem;
}

.modal-info-box {
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1.25rem;
    font-size: 0.875rem;
    display: flex;
    align-items: flex-start;
    gap: 0.5rem;
}

.modal-info-box.success {
    background: rgba(5, 150, 105, 0.06);
    border: 1px solid rgba(5, 150, 105, 0.15);
    color: var(--industrial-dark);
}

.modal-info-box.danger {
    background: rgba(220, 38, 38, 0.06);
    border: 1px solid rgba(220, 38, 38, 0.15);
    color: var(--industrial-dark);
}

.modal-info-box svg {
    flex-shrink: 0;
    margin-top: 1px;
}

.modal-details-table {
    width: 100%;
    margin-bottom: 1.25rem;
    border-collapse: collapse;
}

.modal-details-table td {
    padding: 0.625rem 0.75rem;
    font-size: 0.85rem;
    border-bottom: 1px solid #f1f5f9;
}

.modal-details-table td:first-child {
    color: #64748b;
    font-weight: 500;
    width: 130px;
}

.modal-details-table td:last-child {
    font-weight: 600;
    color: var(--industrial-dark);
}

.modal-details-table tr:last-child td {
    border-bottom: none;
}

.modal-form-group {
    margin-bottom: 1.25rem;
}

.modal-form-label {
    display: block;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--industrial-dark);
    margin-bottom: 0.5rem;
}

.modal-form-label .required-mark {
    color: var(--danger);
}

.modal-textarea {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-family: 'IBM Plex Sans', sans-serif;
    font-size: 0.85rem;
    resize: vertical;
    transition: all 0.2s;
    color: var(--industrial-dark);
}

.modal-textarea:focus {
    outline: none;
    border-color: var(--uitm-blue-light);
    box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
}

.modal-textarea::placeholder {
    color: #94a3b8;
}

.modal-hint {
    display: block;
    font-size: 0.75rem;
    color: #64748b;
    margin-top: 0.35rem;
}

.modal-checkbox-group {
    display: flex;
    align-items: flex-start;
    gap: 0.5rem;
    padding: 0.875rem;
    background: var(--industrial-light);
    border-radius: 8px;
}

.modal-checkbox-group input[type="checkbox"] {
    width: 18px;
    height: 18px;
    margin-top: 1px;
    accent-color: var(--success);
    flex-shrink: 0;
}

.modal-checkbox-group label {
    font-size: 0.85rem;
    color: var(--industrial-dark);
    line-height: 1.4;
    cursor: pointer;
}

.industrial-modal .modal-footer {
    border-top: 1px solid #e2e8f0;
    padding: 1rem 1.5rem;
    gap: 0.5rem;
}

.modal-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-family: 'IBM Plex Sans', sans-serif;
    font-size: 0.85rem;
    font-weight: 600;
    padding: 0.625rem 1.25rem;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
}

.modal-btn.cancel {
    background: var(--industrial-light);
    color: var(--industrial-gray);
}

.modal-btn.cancel:hover {
    background: #e2e8f0;
}

.modal-btn.endorse-confirm {
    background: var(--success);
    color: white;
}

.modal-btn.endorse-confirm:hover {
    background: #047857;
}

.modal-btn.reject-confirm {
    background: var(--danger);
    color: white;
}

.modal-btn.reject-confirm:hover {
    background: #b91c1c;
}

/* ============================================
   TOAST NOTIFICATIONS
   ============================================ */
.industrial-toast {
    position: fixed;
    bottom: 1.5rem;
    right: 1.5rem;
    z-index: 1100;
    min-width: 320px;
    max-width: 420px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    overflow: hidden;
    animation: toastSlideIn 0.4s ease;
}

@keyframes toastSlideIn {
    from { transform: translateY(20px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

.industrial-toast::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
}

.industrial-toast.success::before {
    background: var(--success);
}

.industrial-toast.error::before {
    background: var(--danger);
}

.toast-content {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 1rem 1.25rem;
}

.toast-icon {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.industrial-toast.success .toast-icon {
    background: rgba(5, 150, 105, 0.1);
    color: var(--success);
}

.industrial-toast.error .toast-icon {
    background: rgba(220, 38, 38, 0.1);
    color: var(--danger);
}

.toast-text {
    flex: 1;
}

.toast-title {
    font-size: 0.8rem;
    font-weight: 700;
    color: var(--industrial-dark);
    text-transform: uppercase;
    letter-spacing: 0.03em;
    margin-bottom: 0.15rem;
}

.toast-message {
    font-size: 0.85rem;
    color: #64748b;
}

.toast-close {
    background: none;
    border: none;
    color: #94a3b8;
    cursor: pointer;
    padding: 0.25rem;
    transition: color 0.2s;
}

.toast-close:hover {
    color: var(--industrial-dark);
}

/* ============================================
   RESPONSIVE
   ============================================ */
@media (max-width: 767px) {
    .hea-pending {
        padding: 0 1rem 1.5rem;
    }

    .page-header {
        margin: -1rem -1rem 1.25rem;
        padding: 1.5rem 1rem;
    }

    .page-header .header-content {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }

    .page-header .header-actions {
        width: 100%;
    }

    .page-header .header-btn {
        flex: 1;
        justify-content: center;
        font-size: 0.75rem;
    }

    .page-header .page-title h1 {
        font-size: 1.25rem;
    }

    .stats-grid {
        grid-template-columns: 1fr;
    }

    .stat-card .stat-value {
        font-size: 1.75rem;
    }

    .filter-form {
        grid-template-columns: 1fr;
    }

    .program-group-left {
        flex-wrap: wrap;
    }

    .program-name {
        width: 100%;
        margin-left: 0;
    }

    .list-item-top {
        flex-direction: column;
        gap: 0.75rem;
    }

    .list-statistics {
        flex-direction: column;
        gap: 0.5rem;
        align-items: flex-start;
    }

    .list-actions {
        flex-direction: column;
    }

    .list-actions .action-btn-styled {
        justify-content: center;
        width: 100%;
    }
}
</style>
@endpush

@section('content')
<div class="hea-pending">
    {{-- Industrial Page Header --}}
    <header class="page-header">
        <div class="header-grid"></div>
        <div class="header-accent"></div>
        <div class="header-content">
            <div class="header-left">
                <div class="role-badge">
                    <span class="badge-text">HEA</span>
                    <span class="badge-line"></span>
                </div>
                <div class="page-title">
                    <h1>Pending HEA Endorsement</h1>
                    <p>Review and endorse semester updates for CS110 Diploma to Degree program course equivalencies</p>
                </div>
            </div>
            <div class="header-actions">
                <a href="{{ route('hea.equivalency_lists.published_view') }}" class="header-btn primary">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    Published Lists
                </a>
                <a href="{{ route('hea.dashboard') }}" class="header-btn secondary">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Dashboard
                </a>
            </div>
        </div>
    </header>

    {{-- Stats Overview --}}
    <section class="stats-grid">
        <div class="stat-card teal">
            <div class="stat-icon">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <span class="stat-value">{{ $stats['total_pending'] }}</span>
            <span class="stat-label">Total Pending</span>
        </div>

        <div class="stat-card amber">
            <div class="stat-icon">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="stat-value">{{ $stats['submitted'] }}</span>
            <span class="stat-label">Awaiting Review</span>
        </div>

        <div class="stat-card blue">
            <div class="stat-icon">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
            </div>
            <span class="stat-value">{{ $stats['under_review'] }}</span>
            <span class="stat-label">Under Review</span>
        </div>

        <div class="stat-card {{ $stats['oldest_days'] > 30 ? 'red' : 'success-border' }}">
            <div class="stat-icon">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <span class="stat-value">{{ $stats['oldest_days'] }}</span>
            <span class="stat-label">Oldest (Days)</span>
            @if($stats['oldest_days'] > 30)
                <span class="stat-alert danger">
                    <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    Overdue
                </span>
            @else
                <span class="stat-alert success">
                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    On Track
                </span>
            @endif
        </div>
    </section>

    {{-- Filter Section --}}
    <div class="filter-section">
        <div class="filter-header">
            <div class="filter-icon">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
            </div>
            <h3 class="filter-title">Filters</h3>
        </div>
        <form method="GET" action="{{ route('hea.equivalency_lists.pending') }}" class="filter-form">
            <div class="filter-group">
                <label for="program">Target Program</label>
                <select name="program" id="program" class="filter-select" onchange="this.form.submit()">
                    <option value="all" {{ $programFilter === 'all' ? 'selected' : '' }}>All Programs</option>
                    @foreach($programs as $code => $name)
                        <option value="{{ $code }}" {{ $programFilter === $code ? 'selected' : '' }}>
                            {{ $code }} - {{ Str::limit($name, 30) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label for="semester">Semester</label>
                <select name="semester" id="semester" class="filter-select" onchange="this.form.submit()">
                    <option value="all" {{ $semesterFilter === 'all' ? 'selected' : '' }}>All Semesters</option>
                    @foreach($semesters as $semester)
                        <option value="{{ $semester }}" {{ $semesterFilter === $semester ? 'selected' : '' }}>
                            {{ $semester }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="filter-select" onchange="this.form.submit()">
                    <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>All Statuses</option>
                    <option value="submitted" {{ $statusFilter === 'submitted' ? 'selected' : '' }}>Submitted (Awaiting Review)</option>
                    <option value="under_review" {{ $statusFilter === 'under_review' ? 'selected' : '' }}>Under Review</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="sort">Sort By</label>
                <select name="sort" id="sort" class="filter-select" onchange="this.form.submit()">
                    <option value="oldest" {{ $sortBy === 'oldest' ? 'selected' : '' }}>Oldest First (Priority)</option>
                    <option value="newest" {{ $sortBy === 'newest' ? 'selected' : '' }}>Newest First</option>
                    <option value="program" {{ $sortBy === 'program' ? 'selected' : '' }}>By Program Code</option>
                </select>
            </div>
        </form>
    </div>

    @if($stats['total_pending'] === 0)
    {{-- Empty State --}}
    <div class="empty-state-card">
        <div class="empty-icon">
            <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h4>All Caught Up!</h4>
        <p>There are no CS110 equivalency lists waiting for endorsement. All submitted lists have been reviewed and processed.</p>
        <a href="{{ route('hea.equivalency_lists.published_view') }}" class="view-published-btn">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            View Published Lists
        </a>
    </div>
    @else

    {{-- Lists Grouped by Degree Program --}}
    @foreach($programs as $programCode => $programName)
        @php
            $programLists = $listsByProgram->get($programCode, collect());
            $isExpanded = $programLists->count() > 0 && ($programFilter === $programCode || $programFilter === 'all');
        @endphp

        @if($programLists->count() > 0)
        <div class="program-group">
            <button class="program-group-header {{ $isExpanded ? 'active' : '' }}"
                    type="button"
                    onclick="toggleProgramGroup(this, 'pgBody{{ $programCode }}')">
                <div class="program-group-left">
                    <div class="program-icon">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M12 14l9-5-9-5-9 5 9 5z"/>
                            <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zM12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zM12 14v7"/>
                        </svg>
                    </div>
                    <span class="program-code">{{ $programCode }}</span>
                    <span class="program-name">{{ $programName }}</span>
                </div>
                <div class="program-group-right">
                    <span class="pending-count-badge">{{ $programLists->count() }} pending</span>
                    <svg class="chevron-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </button>

            <div id="pgBody{{ $programCode }}" class="program-group-body {{ $isExpanded ? 'show' : '' }}">
                @foreach($programLists as $list)
                @php
                    $daysWaiting = $list->submitted_at ? now()->diffInDays($list->submitted_at) : 0;
                    $urgencyClass = $daysWaiting > 30 ? 'danger' : ($daysWaiting > 15 ? 'warning' : 'success');
                    $urgencyIcon = $daysWaiting > 30 ? 'exclamation-triangle' : ($daysWaiting > 15 ? 'clock' : 'check-circle');
                @endphp

                <div class="list-item-card">
                    {{-- Top Row: Badges + Status --}}
                    <div class="list-item-top">
                        <div class="list-item-info">
                            <div class="list-item-badges">
                                @if($list->status === 'submitted')
                                    <span class="urgency-tag {{ $daysWaiting > 30 ? 'critical' : 'new' }}">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if($daysWaiting > 30)
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                            @endif
                                        </svg>
                                        NEW
                                    </span>
                                @else
                                    <span class="urgency-tag review">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        REVIEW
                                    </span>
                                @endif
                                <span class="semester-label">{{ $list->semester }}</span>
                            </div>

                            <div class="list-item-meta">
                                <div class="meta-detail">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <span>{{ $list->creator->name ?? 'Unknown' }}</span>
                                </div>
                                <div class="meta-detail">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span>{{ $list->submitted_at?->format('d M Y') }}</span>
                                </div>
                                <div class="meta-detail">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="waiting-days {{ $urgencyClass }}">{{ $daysWaiting }} {{ Str::plural('day', $daysWaiting) }} ago</span>
                                </div>
                            </div>

                            @if($list->reviewer)
                            <div class="reviewer-info">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Currently reviewed by: <strong>{{ $list->reviewer->name }}</strong>
                            </div>
                            @endif
                        </div>
                        <span class="list-item-status {{ $list->status === 'submitted' ? 'status-submitted' : 'status-under-review' }}">
                            {{ $list->status_display }}
                        </span>
                    </div>

                    {{-- Summary Statistics --}}
                    <div class="list-statistics">
                        <div class="stat-item">
                            <svg width="16" height="16" fill="none" stroke="#64748b" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                            </svg>
                            <span class="stat-num mono-value">{{ $list->total_equivalencies }}</span>
                            <span class="stat-text">total mappings</span>
                        </div>
                        <div class="stat-item">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--success)">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="stat-num mono-value">{{ $list->eligible_count }}</span>
                            <span class="stat-text">eligible ({{ $list->total_equivalencies > 0 ? number_format(($list->eligible_count / $list->total_equivalencies) * 100, 1) : 0 }}%)</span>
                        </div>
                        <div class="stat-item">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--danger)">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="stat-num mono-value">{{ $list->not_eligible_count }}</span>
                            <span class="stat-text">not eligible</span>
                        </div>
                    </div>

                    {{-- Progress Bar --}}
                    @if($list->total_equivalencies > 0)
                    <div class="eligibility-progress">
                        <div class="progress-fill" style="width: {{ ($list->eligible_count / $list->total_equivalencies) * 100 }}%"></div>
                    </div>
                    @endif

                    {{-- Changes from Previous Semester --}}
                    @if($list->changes)
                    <div class="changes-section">
                        <div class="changes-header">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                            <span class="changes-title">Changes from Previous Semester</span>
                            @if($list->previousList)
                                <span class="changes-prev">({{ $list->previousList->semester }})</span>
                            @endif
                        </div>
                        <div class="changes-badges">
                            @if($list->changes['added_count'] > 0)
                            <span class="change-badge added">
                                <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/>
                                </svg>
                                {{ $list->changes['added_count'] }} New
                            </span>
                            @endif
                            @if($list->changes['modified_count'] > 0)
                            <span class="change-badge modified">
                                <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                {{ $list->changes['modified_count'] }} Modified
                            </span>
                            @endif
                            @if($list->changes['removed_count'] > 0)
                            <span class="change-badge removed">
                                <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4"/>
                                </svg>
                                {{ $list->changes['removed_count'] }} Removed
                            </span>
                            @endif
                            @if($list->changes['added_count'] === 0 && $list->changes['modified_count'] === 0 && $list->changes['removed_count'] === 0)
                            <span class="change-badge none">
                                <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4"/>
                                </svg>
                                No changes from previous semester
                            </span>
                            @endif
                        </div>
                    </div>
                    @else
                    <div class="first-submission-note">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span><strong>First Submission:</strong> No previous semester list available for comparison.</span>
                    </div>
                    @endif

                    {{-- Submission Notes --}}
                    @if($list->submission_notes)
                    <div class="submission-notes">
                        <div class="notes-label">
                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                            </svg>
                            Submission Notes
                        </div>
                        <div class="notes-content">{{ $list->submission_notes }}</div>
                    </div>
                    @endif

                    {{-- Action Buttons --}}
                    <div class="list-actions">
                        <a href="{{ route('hea.equivalency_lists.review', $list) }}" class="action-btn-styled review">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                            Review Details
                        </a>
                        <button type="button"
                                class="action-btn-styled endorse"
                                data-bs-toggle="modal"
                                data-bs-target="#quickEndorseModal{{ $list->id }}">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Quick Endorse
                        </button>
                        <button type="button"
                                class="action-btn-styled reject"
                                data-bs-toggle="modal"
                                data-bs-target="#quickRejectModal{{ $list->id }}">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Reject
                        </button>
                    </div>
                </div>

                {{-- Quick Endorse Modal --}}
                <div class="modal fade" id="quickEndorseModal{{ $list->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content industrial-modal">
                            <form action="{{ route('hea.equivalency_lists.endorse', $list) }}" method="POST">
                                @csrf
                                <div class="modal-header endorse-header">
                                    <h5 class="modal-title">
                                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Quick Endorse & Publish
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="modal-info-box success">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span>You are about to endorse and publish this list. This action will make the equivalency mappings available for student exemption processing.</span>
                                    </div>
                                    <table class="modal-details-table">
                                        <tr>
                                            <td>Program</td>
                                            <td><span class="mono-value">{{ $list->program_code }}</span></td>
                                        </tr>
                                        <tr>
                                            <td>Semester</td>
                                            <td>{{ $list->semester }}</td>
                                        </tr>
                                        <tr>
                                            <td>Total Mappings</td>
                                            <td><span class="mono-value">{{ $list->total_equivalencies }}</span></td>
                                        </tr>
                                        <tr>
                                            <td>Eligible</td>
                                            <td><span class="mono-value">{{ $list->eligible_count }}</span> ({{ $list->total_equivalencies > 0 ? number_format(($list->eligible_count / $list->total_equivalencies) * 100, 1) : 0 }}%)</td>
                                        </tr>
                                    </table>
                                    <div class="modal-form-group">
                                        <label class="modal-form-label">Endorsement Notes (Optional)</label>
                                        <textarea name="endorsement_notes" class="modal-textarea" rows="2" placeholder="Add any notes about this endorsement..."></textarea>
                                    </div>
                                    <div class="modal-checkbox-group">
                                        <input type="checkbox" name="confirm" value="1" id="confirmEndorse{{ $list->id }}" required>
                                        <label for="confirmEndorse{{ $list->id }}">
                                            I confirm I have reviewed this list and endorse it for publication.
                                        </label>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="modal-btn cancel" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="modal-btn endorse-confirm">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Endorse & Publish
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Quick Reject Modal --}}
                <div class="modal fade" id="quickRejectModal{{ $list->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content industrial-modal">
                            <form action="{{ route('hea.equivalency_lists.reject', $list) }}" method="POST">
                                @csrf
                                <div class="modal-header reject-header">
                                    <h5 class="modal-title">
                                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Reject List
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="modal-info-box danger">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                        </svg>
                                        <span>This list will be returned to the Resource Person for revision. They will be notified of the rejection reason.</span>
                                    </div>
                                    <div class="modal-form-group">
                                        <label class="modal-form-label">Reason for Rejection <span class="required-mark">*</span></label>
                                        <textarea name="review_notes" class="modal-textarea" rows="4" required minlength="10" placeholder="Please explain what needs to be corrected..."></textarea>
                                        <span class="modal-hint">Minimum 10 characters. This will be visible to the Resource Person.</span>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="modal-btn cancel" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="modal-btn reject-confirm">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Reject & Return
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    @endforeach

    @endif
</div>

{{-- Toast Notifications --}}
@if(session('success'))
<div class="industrial-toast success" id="successToast">
    <div class="toast-content">
        <div class="toast-icon">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="toast-text">
            <div class="toast-title">Success</div>
            <div class="toast-message">{{ session('success') }}</div>
        </div>
        <button class="toast-close" onclick="this.closest('.industrial-toast').remove()">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
</div>
@endif

@if(session('error'))
<div class="industrial-toast error" id="errorToast">
    <div class="toast-content">
        <div class="toast-icon">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="toast-text">
            <div class="toast-title">Error</div>
            <div class="toast-message">{{ session('error') }}</div>
        </div>
        <button class="toast-close" onclick="this.closest('.industrial-toast').remove()">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
</div>
@endif

<script>
function toggleProgramGroup(header, bodyId) {
    const body = document.getElementById(bodyId);
    const isActive = header.classList.contains('active');

    if (isActive) {
        header.classList.remove('active');
        body.classList.remove('show');
    } else {
        header.classList.add('active');
        body.classList.add('show');
    }
}

// Auto-dismiss toasts after 6 seconds
document.addEventListener('DOMContentLoaded', function() {
    const toasts = document.querySelectorAll('.industrial-toast');
    toasts.forEach(function(toast) {
        setTimeout(function() {
            toast.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(10px)';
            setTimeout(function() {
                toast.remove();
            }, 400);
        }, 6000);
    });
});
</script>

@endsection
