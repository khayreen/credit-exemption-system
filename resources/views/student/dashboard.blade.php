@extends('layouts.app')

@push('styles')
<!-- IBM Plex Sans Typography -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600;700&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
/* ========================================
   INDUSTRIAL INSTITUTIONAL DESIGN SYSTEM
   UiTM Credit Exemption - Student Dashboard
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
.dashboard-container,
.dashboard-container * {
    font-family: 'IBM Plex Sans', -apple-system, BlinkMacSystemFont, sans-serif !important;
}

.mono-text {
    font-family: 'IBM Plex Mono', monospace !important;
}

/* Skip to main content link for screen readers */
.skip-to-main {
    position: absolute;
    left: -9999px;
    z-index: 999;
}

.skip-to-main:focus {
    left: 50%;
    transform: translateX(-50%);
    background: var(--uitm-primary);
    color: white;
    padding: 1rem 2rem;
    border-radius: 0 0 8px 8px;
}

/* Focus visible for keyboard navigation */
a:focus-visible,
button:focus-visible,
.btn:focus-visible {
    outline: 3px solid var(--uitm-amber);
    outline-offset: 2px;
}

/* ========================================
   WELCOME HEADER - Industrial Style
   ======================================== */
.welcome-header {
    position: relative;
    background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
    border-radius: 16px;
    padding: 2.5rem;
    color: white;
    overflow: hidden;
    margin-bottom: 1.5rem;
}

.welcome-pattern {
    position: absolute;
    inset: 0;
    opacity: 0.07;
    background-image:
        linear-gradient(rgba(255,255,255,0.1) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.1) 1px, transparent 1px);
    background-size: 40px 40px;
    pointer-events: none;
}

.welcome-glow {
    position: absolute;
    top: -50%;
    right: -20%;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(245, 158, 11, 0.15) 0%, transparent 70%);
    pointer-events: none;
}

.welcome-content {
    position: relative;
    z-index: 2;
}

.welcome-eyebrow {
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

.welcome-eyebrow::before {
    content: '';
    width: 24px;
    height: 2px;
    background: var(--uitm-amber);
}

.welcome-title {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    line-height: 1.2;
}

.welcome-subtitle {
    font-size: 1rem;
    color: rgba(255, 255, 255, 0.85);
    margin-bottom: 1rem;
    max-width: 600px;
    line-height: 1.6;
}

.welcome-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid rgba(255, 255, 255, 0.15);
}

.welcome-meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.8);
}

.welcome-meta-item i {
    color: var(--uitm-amber);
    font-size: 0.9rem;
}

.welcome-illustration {
    position: absolute;
    right: 2rem;
    bottom: -1rem;
    font-size: 12rem;
    opacity: 0.08;
    color: white;
    animation: floatIcon 6s ease-in-out infinite;
}

@keyframes floatIcon {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-15px) rotate(3deg); }
}

/* ========================================
   STATISTICS GRID - Industrial Cards
   ======================================== */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    position: relative;
    overflow: hidden;
    border: 2px solid var(--neutral-200);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--stat-accent, var(--uitm-primary));
}

.stat-card:hover {
    border-color: var(--stat-accent, var(--uitm-primary));
    transform: translateY(-4px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
}

.stat-card-primary { --stat-accent: var(--uitm-primary); }
.stat-card-amber { --stat-accent: var(--uitm-amber); }
.stat-card-green { --stat-accent: var(--uitm-green); }
.stat-card-red { --stat-accent: var(--uitm-red); }

.stat-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 1rem;
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    background: linear-gradient(135deg, var(--stat-accent), color-mix(in srgb, var(--stat-accent) 70%, white));
    color: white;
}

.stat-trend {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.25rem 0.5rem;
    border-radius: 20px;
    background: rgba(16, 185, 129, 0.1);
    color: var(--uitm-green);
}

.stat-trend.pending {
    background: rgba(245, 158, 11, 0.1);
    color: var(--uitm-amber);
}

.stat-number {
    font-family: 'IBM Plex Mono', monospace !important;
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--neutral-900);
    line-height: 1;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--neutral-600);
    margin-bottom: 0.25rem;
}

.stat-sublabel {
    font-size: 0.75rem;
    color: var(--neutral-400);
}

/* ========================================
   MAIN CONTENT GRID
   ======================================== */
.content-grid {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 1.5rem;
}

/* ========================================
   DASHBOARD CARDS - Industrial Style
   ======================================== */
.dashboard-card {
    background: white;
    border-radius: 12px;
    border: 2px solid var(--neutral-200);
    overflow: hidden;
    transition: all 0.3s ease;
}

.dashboard-card:hover {
    border-color: var(--neutral-300);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
}

.card-header-industrial {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.25rem 1.5rem;
    background: var(--neutral-50);
    border-bottom: 2px solid var(--neutral-200);
}

.card-header-left {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.card-header-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--uitm-primary), var(--uitm-primary-light));
    color: white;
    font-size: 0.9rem;
}

.card-header-text h2 {
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

.card-header-action {
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--uitm-primary);
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.card-header-action:hover {
    background: rgba(30, 58, 138, 0.05);
    color: var(--uitm-primary);
}

.card-body-industrial {
    padding: 1.5rem;
}

/* ========================================
   QUICK ACTIONS - Industrial Buttons
   ======================================== */
.quick-actions-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
}

.action-card {
    background: var(--neutral-50);
    border: 2px solid var(--neutral-200);
    border-radius: 10px;
    padding: 1.5rem;
    text-align: center;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    text-decoration: none;
    display: block;
}

.action-card:hover {
    background: white;
    border-color: var(--action-color, var(--uitm-primary));
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
}

.action-card-primary { --action-color: var(--uitm-primary); }
.action-card-amber { --action-color: var(--uitm-amber); }
.action-card-green { --action-color: var(--uitm-green); }

.action-icon {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 1.5rem;
    background: linear-gradient(135deg, rgba(30, 58, 138, 0.08), rgba(30, 58, 138, 0.04));
    color: var(--action-color, var(--uitm-primary));
    transition: all 0.3s ease;
}

.action-card:hover .action-icon {
    background: linear-gradient(135deg, var(--action-color), color-mix(in srgb, var(--action-color) 70%, white));
    color: white;
    transform: scale(1.05);
}

.action-title {
    font-size: 0.95rem;
    font-weight: 700;
    color: var(--neutral-900);
    margin-bottom: 0.5rem;
}

.action-description {
    font-size: 0.8rem;
    color: var(--neutral-500);
    line-height: 1.5;
    margin-bottom: 1rem;
}

.action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    width: 100%;
    padding: 0.75rem 1rem;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.03em;
    border: 2px solid var(--action-color, var(--uitm-primary));
    border-radius: 8px;
    background: transparent;
    color: var(--action-color, var(--uitm-primary));
    transition: all 0.2s ease;
}

.action-card:hover .action-btn {
    background: var(--action-color, var(--uitm-primary));
    color: white;
}

/* ========================================
   APPLICATION STATUS CARD
   ======================================== */
.application-status-section {
    margin-top: 1.5rem;
}

.latest-app-card {
    background: linear-gradient(135deg, rgba(30, 58, 138, 0.03), rgba(59, 130, 246, 0.03));
    border: 2px solid rgba(30, 58, 138, 0.1);
    border-radius: 10px;
    padding: 1.5rem;
}

.app-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid rgba(30, 58, 138, 0.1);
}

.app-id-badge {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.app-id {
    font-family: 'IBM Plex Mono', monospace !important;
    font-size: 1rem;
    font-weight: 700;
    color: var(--uitm-primary);
}

.status-badge {
    padding: 0.4rem 0.9rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.status-pending,
.status-pending-academic-advisor,
.status-pending-resource-person,
.status-under-review {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    color: #92400e;
}

.status-approved,
.status-exempted {
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    color: #065f46;
}

.status-rejected {
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    color: #991b1b;
}

.app-meta {
    display: flex;
    gap: 2rem;
    margin-bottom: 1.5rem;
}

.app-meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    color: var(--neutral-600);
}

.app-meta-item i {
    color: var(--uitm-primary);
    font-size: 0.9rem;
}

.evaluation-results {
    background: white;
    border-radius: 8px;
    padding: 1rem;
    border: 1px solid var(--neutral-200);
}

.evaluation-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1rem;
}

.evaluation-header h3 {
    font-size: 0.85rem;
    font-weight: 700;
    color: var(--neutral-900);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.evaluation-header h3 i {
    color: var(--uitm-primary);
}

.evaluation-count {
    font-size: 0.75rem;
    color: var(--neutral-500);
    background: var(--neutral-100);
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
}

.results-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.75rem;
}

.result-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.875rem 1rem;
    border-radius: 8px;
    border-left: 3px solid;
}

.result-item.exempted {
    background: linear-gradient(90deg, #f0fdf4, #dcfce7);
    border-left-color: var(--uitm-green);
}

.result-item.pending {
    background: linear-gradient(90deg, #fffbeb, #fef3c7);
    border-left-color: var(--uitm-amber);
}

.result-item.rejected {
    background: linear-gradient(90deg, #fef2f2, #fee2e2);
    border-left-color: var(--uitm-red);
}

.result-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.result-info i {
    font-size: 1.1rem;
}

.result-item.exempted .result-info i { color: var(--uitm-green); }
.result-item.pending .result-info i { color: var(--uitm-amber); }
.result-item.rejected .result-info i { color: var(--uitm-red); }

.result-label {
    font-size: 0.8rem;
    font-weight: 600;
}

.result-item.exempted .result-label { color: #166534; }
.result-item.pending .result-label { color: #92400e; }
.result-item.rejected .result-label { color: #991b1b; }

.result-number {
    font-family: 'IBM Plex Mono', monospace !important;
    font-size: 1.5rem;
    font-weight: 700;
}

.result-item.exempted .result-number { color: #166534; }
.result-item.pending .result-number { color: #92400e; }
.result-item.rejected .result-number { color: #991b1b; }

.view-details-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 1rem;
    padding: 0.75rem 1.5rem;
    font-size: 0.85rem;
    font-weight: 600;
    background: var(--uitm-primary);
    color: white;
    border: none;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.2s ease;
}

.view-details-btn:hover {
    background: var(--uitm-primary-dark);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
}

/* ========================================
   EMPTY STATE
   ======================================== */
.empty-state {
    text-align: center;
    padding: 3rem 1.5rem;
}

.empty-state-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--neutral-100), var(--neutral-50));
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
}

.empty-state-icon i {
    font-size: 2rem;
    color: var(--neutral-400);
}

.empty-state-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--neutral-900);
    margin-bottom: 0.5rem;
}

.empty-state-description {
    font-size: 0.9rem;
    color: var(--neutral-500);
    margin-bottom: 1.5rem;
    max-width: 300px;
    margin-left: auto;
    margin-right: auto;
}

.empty-state-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.875rem 1.5rem;
    font-size: 0.875rem;
    font-weight: 600;
    background: var(--uitm-primary);
    color: white;
    border: none;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.2s ease;
}

.empty-state-btn:hover {
    background: var(--uitm-primary-dark);
    color: white;
    transform: translateY(-2px);
}

/* ========================================
   SIDEBAR WIDGETS
   ======================================== */
.sidebar-widgets {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

/* ========================================
   ACTIVITY TIMELINE
   ======================================== */
.activity-timeline {
    padding: 0;
}

.timeline-item {
    display: flex;
    gap: 1rem;
    padding: 1rem 0;
    border-bottom: 1px solid var(--neutral-100);
}

.timeline-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.timeline-item:first-child {
    padding-top: 0;
}

.timeline-marker {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 0.9rem;
}

.timeline-marker-success {
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    color: var(--uitm-green);
}

.timeline-marker-warning {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    color: var(--uitm-amber);
}

.timeline-marker-danger {
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    color: var(--uitm-red);
}

.timeline-content {
    flex: 1;
    min-width: 0;
}

.timeline-title {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--neutral-900);
    margin-bottom: 0.25rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.timeline-app-id {
    font-family: 'IBM Plex Mono', monospace !important;
    font-size: 0.8rem;
    color: var(--uitm-primary);
}

.timeline-date {
    font-size: 0.75rem;
    color: var(--neutral-400);
    margin-bottom: 0.5rem;
}

.timeline-status {
    display: inline-block;
    padding: 0.25rem 0.6rem;
    border-radius: 12px;
    font-size: 0.65rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.03em;
}

/* ========================================
   RESOURCE LINKS
   ======================================== */
.resource-links {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.resource-link {
    display: flex;
    align-items: center;
    gap: 0.875rem;
    padding: 0.875rem 1rem;
    border-radius: 8px;
    background: var(--neutral-50);
    border: 1px solid transparent;
    color: var(--neutral-700);
    text-decoration: none;
    transition: all 0.2s ease;
}

.resource-link:hover {
    background: white;
    border-color: var(--neutral-200);
    color: var(--uitm-primary);
    transform: translateX(4px);
}

.resource-link-icon {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.85rem;
    flex-shrink: 0;
}

.resource-link-icon.primary {
    background: rgba(30, 58, 138, 0.1);
    color: var(--uitm-primary);
}

.resource-link-icon.amber {
    background: rgba(245, 158, 11, 0.1);
    color: var(--uitm-amber);
}

.resource-link-icon.green {
    background: rgba(16, 185, 129, 0.1);
    color: var(--uitm-green);
}

.resource-link-icon.red {
    background: rgba(220, 38, 38, 0.1);
    color: var(--uitm-red);
}

.resource-link span {
    flex: 1;
    font-size: 0.875rem;
    font-weight: 500;
}

.resource-link i.fa-chevron-right,
.resource-link i.fa-external-link-alt {
    font-size: 0.7rem;
    color: var(--neutral-400);
}

/* ========================================
   PROGRESS INDICATOR
   ======================================== */
.progress-section {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.05), rgba(52, 211, 153, 0.05));
    border: 2px solid rgba(16, 185, 129, 0.15);
    border-radius: 10px;
    padding: 1.25rem;
}

.progress-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1rem;
}

.progress-header h3 {
    font-size: 0.85rem;
    font-weight: 700;
    color: var(--neutral-900);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.progress-header h3 i {
    color: var(--uitm-green);
}

.progress-percentage {
    font-family: 'IBM Plex Mono', monospace !important;
    font-size: 1rem;
    font-weight: 700;
    color: var(--uitm-green);
}

.progress-bar-wrapper {
    height: 8px;
    background: rgba(16, 185, 129, 0.15);
    border-radius: 4px;
    overflow: hidden;
    margin-bottom: 0.75rem;
}

.progress-bar-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--uitm-green), var(--uitm-green-light));
    border-radius: 4px;
    transition: width 0.5s ease;
}

.progress-text {
    font-size: 0.75rem;
    color: var(--neutral-500);
}

/* ========================================
   ANIMATIONS
   ======================================== */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-up {
    animation: fadeInUp 0.5s ease forwards;
}

.delay-1 { animation-delay: 0.1s; opacity: 0; }
.delay-2 { animation-delay: 0.2s; opacity: 0; }
.delay-3 { animation-delay: 0.3s; opacity: 0; }
.delay-4 { animation-delay: 0.4s; opacity: 0; }

/* ========================================
   ICON HOVER ANIMATIONS
   ======================================== */
/* Stat card icon pulse on hover */
.stat-card:hover .stat-icon i {
    animation: iconPulse 0.3s ease;
}

@keyframes iconPulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.15); }
    100% { transform: scale(1); }
}

/* Action card icon rotation on hover */
.action-card:hover .action-icon i {
    transform: rotate(5deg) scale(1.1);
    transition: transform 0.3s ease;
}

.action-icon i {
    transition: transform 0.3s ease;
}

/* Card header icon subtle animation */
.card-header-icon i {
    transition: transform 0.3s ease;
}

.dashboard-card:hover .card-header-icon i {
    transform: scale(1.1);
}

/* ========================================
   RESPONSIVE DESIGN
   ======================================== */
@media (max-width: 1200px) {
    .content-grid {
        grid-template-columns: 1fr;
    }

    .sidebar-widgets {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
}

@media (max-width: 991px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .quick-actions-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .welcome-title {
        font-size: 1.5rem;
    }

    .welcome-illustration {
        display: none;
    }
}

@media (max-width: 767px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }

    .quick-actions-grid {
        grid-template-columns: 1fr;
    }

    .sidebar-widgets {
        grid-template-columns: 1fr;
    }

    .welcome-header {
        padding: 1.5rem;
    }

    .welcome-title {
        font-size: 1.35rem;
    }

    .welcome-meta {
        flex-direction: column;
        gap: 0.75rem;
    }

    .app-meta {
        flex-direction: column;
        gap: 0.75rem;
    }

    .results-grid {
        grid-template-columns: 1fr;
    }
}

/* ========================================
   PRINT STYLES
   ======================================== */
@media print {
    .welcome-header,
    .stat-card,
    .dashboard-card,
    .action-card {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
    }

    .stat-card:hover,
    .action-card:hover {
        transform: none !important;
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

    .welcome-illustration {
        animation: none;
    }
}

@media (prefers-contrast: high) {
    .stat-card,
    .dashboard-card,
    .action-card {
        border-width: 3px;
    }
}
</style>
@endpush

@section('content')
<div class="dashboard-container">
    <!-- Welcome Header -->
    <div class="welcome-header animate-fade-in-up" role="banner">
        <div class="welcome-pattern"></div>
        <div class="welcome-glow"></div>
        <div class="welcome-illustration" aria-hidden="true">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <div class="welcome-content">
            <div class="welcome-eyebrow">Student Dashboard</div>
            <h1 class="welcome-title">Welcome back, {{ Auth::user()->name }}!</h1>
            <p class="welcome-subtitle">
                Track your credit exemption applications, submit new requests, and monitor your academic progress through the UiTM Credit Exemption Management System.
            </p>
            <div class="welcome-meta">
                <div class="welcome-meta-item">
                    <i class="fas fa-calendar-alt"></i>
                    <span>{{ \Carbon\Carbon::now()->format('l, F j, Y') }}</span>
                </div>
                <div class="welcome-meta-item">
                    <i class="fas fa-clock"></i>
                    <span>{{ \Carbon\Carbon::now()->format('g:i A') }}</span>
                </div>
                @if(Auth::user()->role_model && Auth::user()->role_model->program)
                <div class="welcome-meta-item">
                    <i class="fas fa-graduation-cap"></i>
                    <span>{{ Auth::user()->role_model->program->code ?? 'N/A' }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Statistics Grid -->
    <div class="stats-grid">
        <!-- Total Applications -->
        <div class="stat-card stat-card-primary animate-fade-in-up delay-1">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div class="stat-trend">
                    <i class="fas fa-layer-group"></i>
                    All time
                </div>
            </div>
            <div class="stat-number mono-text">{{ $stats['total'] }}</div>
            <div class="stat-label">Total Applications</div>
            <div class="stat-sublabel">Submissions to date</div>
        </div>

        <!-- Under Review -->
        <div class="stat-card stat-card-amber animate-fade-in-up delay-2">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-clock-rotate-left"></i>
                </div>
                <div class="stat-trend pending">
                    <i class="fas fa-clock"></i>
                    Active
                </div>
            </div>
            <div class="stat-number mono-text">{{ $stats['in_progress'] }}</div>
            <div class="stat-label">Under Review</div>
            <div class="stat-sublabel">Awaiting decision</div>
        </div>

        <!-- Courses Exempted -->
        <div class="stat-card stat-card-green animate-fade-in-up delay-3">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-medal"></i>
                </div>
                <div class="stat-trend">
                    <i class="fas fa-award"></i>
                    Approved
                </div>
            </div>
            <div class="stat-number mono-text">{{ $courseStats['exempted'] }}</div>
            <div class="stat-label">Courses Exempted</div>
            <div class="stat-sublabel">Credits granted</div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="content-grid">
        <!-- Left Column -->
        <div class="main-content">
            <!-- Quick Actions -->
            <div class="dashboard-card animate-fade-in-up delay-2">
                <div class="card-header-industrial">
                    <div class="card-header-left">
                        <div class="card-header-icon">
                            <i class="fas fa-rocket"></i>
                        </div>
                        <div class="card-header-text">
                            <h2>Quick Actions</h2>
                            <p>Common tasks and shortcuts</p>
                        </div>
                    </div>
                </div>
                <div class="card-body-industrial">
                    <div class="quick-actions-grid">
                        <!-- New Application -->
                        <a href="{{ route('student.application.create') }}" class="action-card action-card-primary">
                            <div class="action-icon">
                                <i class="fas fa-file-circle-plus"></i>
                            </div>
                            <h3 class="action-title">New Application</h3>
                            <p class="action-description">Submit a new credit exemption request for your courses</p>
                            <span class="action-btn">
                                <i class="fas fa-arrow-right"></i>
                                Apply Now
                            </span>
                        </a>

                        <!-- Track Status -->
                        <a href="{{ route('student.application.status') }}" class="action-card action-card-amber">
                            <div class="action-icon">
                                <i class="fas fa-magnifying-glass-chart"></i>
                            </div>
                            <h3 class="action-title">Track Status</h3>
                            <p class="action-description">View and monitor all your application statuses</p>
                            <span class="action-btn">
                                <i class="fas fa-eye"></i>
                                View Status
                            </span>
                        </a>

                        <!-- Course Equivalencies -->
                        <a href="{{ route('student.course_equivalencies.index') }}" class="action-card action-card-green">
                            <div class="action-icon">
                                <i class="fas fa-scale-balanced"></i>
                            </div>
                            <h3 class="action-title">Equivalencies</h3>
                            <p class="action-description">Browse approved course equivalency mappings</p>
                            <span class="action-btn">
                                <i class="fas fa-list"></i>
                                Browse List
                            </span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Latest Application Status -->
            <div class="application-status-section">
                @if($latestApplication)
                <div class="dashboard-card animate-fade-in-up delay-3">
                    <div class="card-header-industrial">
                        <div class="card-header-left">
                            <div class="card-header-icon" style="background: linear-gradient(135deg, var(--uitm-amber), var(--uitm-amber-light));">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="card-header-text">
                                <h2>Latest Application</h2>
                                <p>Your most recent submission</p>
                            </div>
                        </div>
                        <a href="{{ route('student.application.status') }}" class="card-header-action">
                            View All <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    <div class="card-body-industrial">
                        <div class="latest-app-card">
                            <div class="app-header">
                                <div class="app-id-badge">
                                    <span class="app-id mono-text">#{{ substr($latestApplication->id, 0, 8) }}</span>
                                    <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $latestApplication->status)) }}">
                                        {{ $latestApplication->status }}
                                    </span>
                                </div>
                            </div>

                            <div class="app-meta">
                                <div class="app-meta-item">
                                    <i class="fas fa-calendar"></i>
                                    <span>Submitted {{ $latestApplication->created_at->format('M j, Y') }}</span>
                                </div>
                                <div class="app-meta-item">
                                    <i class="fas fa-graduation-cap"></i>
                                    <span>{{ $latestApplication->current_program_code ?? 'N/A' }}</span>
                                </div>
                            </div>

                            @php
                                $subjects = $latestApplication->applicationSubjects;
                                $exempted = $subjects->whereIn('status', ['exempted', 'Approved'])->count();
                                $pending = $subjects->whereIn('status', ['pending', 'under_review', 'Pending Academic Advisor', 'Pending Resource Person', 'Forward to Coordinator'])->count();
                                $rejected = $subjects->whereIn('status', ['Rejected', 'not_found', 'not_eligible_grade', 'not_eligible_match'])->count();
                                $total = $subjects->count();
                                $exemptionRate = $total > 0 ? round(($exempted / $total) * 100) : 0;
                            @endphp

                            <div class="evaluation-results">
                                <div class="evaluation-header">
                                    <h3><i class="fas fa-chart-pie"></i> Evaluation Results</h3>
                                    <span class="evaluation-count">{{ $total }} courses evaluated</span>
                                </div>
                                <div class="results-grid">
                                    <div class="result-item exempted">
                                        <div class="result-info">
                                            <i class="fas fa-check-circle"></i>
                                            <span class="result-label">Exempted</span>
                                        </div>
                                        <span class="result-number mono-text">{{ $exempted }}</span>
                                    </div>
                                    @if($pending > 0)
                                    <div class="result-item pending">
                                        <div class="result-info">
                                            <i class="fas fa-clock"></i>
                                            <span class="result-label">Under Review</span>
                                        </div>
                                        <span class="result-number mono-text">{{ $pending }}</span>
                                    </div>
                                    @endif
                                    @if($rejected > 0)
                                    <div class="result-item rejected">
                                        <div class="result-info">
                                            <i class="fas fa-times-circle"></i>
                                            <span class="result-label">Not Eligible</span>
                                        </div>
                                        <span class="result-number mono-text">{{ $rejected }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <a href="{{ route('student.application.status') }}" class="view-details-btn">
                                <i class="fas fa-arrow-right"></i>
                                View Full Details
                            </a>
                        </div>
                    </div>
                </div>
                @else
                <div class="dashboard-card animate-fade-in-up delay-3">
                    <div class="card-body-industrial">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-file-circle-plus"></i>
                            </div>
                            <h3 class="empty-state-title">No Applications Yet</h3>
                            <p class="empty-state-description">
                                Start your credit exemption journey by submitting your first application.
                            </p>
                            <a href="{{ route('student.application.create') }}" class="empty-state-btn">
                                <i class="fas fa-plus"></i>
                                Create First Application
                            </a>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Right Column - Sidebar Widgets -->
        <div class="sidebar-widgets">
            <!-- Recent Activity -->
            <div class="dashboard-card animate-fade-in-up delay-3">
                <div class="card-header-industrial">
                    <div class="card-header-left">
                        <div class="card-header-icon" style="background: linear-gradient(135deg, var(--uitm-green), var(--uitm-green-light));">
                            <i class="fas fa-timeline"></i>
                        </div>
                        <div class="card-header-text">
                            <h2>Recent Activity</h2>
                            <p>Latest submissions</p>
                        </div>
                    </div>
                </div>
                <div class="card-body-industrial">
                    @if($recentApplications->count() > 0)
                    <div class="activity-timeline">
                        @foreach($recentApplications as $application)
                        <div class="timeline-item">
                            <div class="timeline-marker timeline-marker-{{
                                $application->status === 'approved' ? 'success' :
                                ($application->status === 'rejected' ? 'danger' : 'warning')
                            }}">
                                <i class="fas {{
                                    $application->status === 'approved' ? 'fa-check' :
                                    ($application->status === 'rejected' ? 'fa-times' : 'fa-clock')
                                }}"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-title">
                                    Application
                                    <span class="timeline-app-id">#{{ substr($application->id, 0, 8) }}</span>
                                </div>
                                <div class="timeline-date">{{ $application->created_at->diffForHumans() }}</div>
                                <span class="timeline-status status-badge status-{{ strtolower(str_replace(' ', '-', $application->status)) }}">
                                    {{ $application->status }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div style="text-align: center; margin-top: 1rem;">
                        <a href="{{ route('student.application.status') }}" class="card-header-action" style="display: inline-flex;">
                            View All Applications <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    @else
                    <div class="empty-state" style="padding: 2rem 1rem;">
                        <div class="empty-state-icon" style="width: 60px; height: 60px;">
                            <i class="fas fa-inbox" style="font-size: 1.5rem;"></i>
                        </div>
                        <p class="empty-state-description" style="margin-bottom: 0;">No recent activity</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Exemption Progress -->
            @if($latestApplication && isset($exemptionRate))
            <div class="dashboard-card animate-fade-in-up delay-4">
                <div class="card-body-industrial">
                    <div class="progress-section">
                        <div class="progress-header">
                            <h3><i class="fas fa-chart-line"></i> Exemption Rate</h3>
                            <span class="progress-percentage mono-text">{{ $exemptionRate }}%</span>
                        </div>
                        <div class="progress-bar-wrapper">
                            <div class="progress-bar-fill" style="width: {{ $exemptionRate }}%;"></div>
                        </div>
                        <p class="progress-text">{{ $exempted }} of {{ $total }} courses exempted</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Help & Resources -->
            <div class="dashboard-card animate-fade-in-up delay-4">
                <div class="card-header-industrial">
                    <div class="card-header-left">
                        <div class="card-header-icon" style="background: linear-gradient(135deg, var(--uitm-red), var(--uitm-red-light));">
                            <i class="fas fa-life-ring"></i>
                        </div>
                        <div class="card-header-text">
                            <h2>Help & Resources</h2>
                            <p>Useful information</p>
                        </div>
                    </div>
                </div>
                <div class="card-body-industrial">
                    <div class="resource-links">
                        <a href="#termsModal" class="resource-link" data-bs-toggle="modal" data-bs-target="#termsModal">
                            <div class="resource-link-icon primary">
                                <i class="fas fa-file-contract"></i>
                            </div>
                            <span>Terms & Conditions</span>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                        <a href="{{ \App\Models\SystemSetting::get('academic_calendar_url', '#') }}" class="resource-link" target="_blank" rel="noopener noreferrer">
                            <div class="resource-link-icon amber">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <span>Academic Calendar</span>
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                        <a href="{{ route('student.course_equivalencies.index') }}" class="resource-link">
                            <div class="resource-link-icon green">
                                <i class="fas fa-book-open"></i>
                            </div>
                            <span>Course Equivalencies</span>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                        <a href="{{ route('profile.show') }}" class="resource-link">
                            <div class="resource-link-icon red">
                                <i class="fas fa-user-cog"></i>
                            </div>
                            <span>Profile Settings</span>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Terms and Conditions Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 16px; border: none;">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--uitm-primary), var(--uitm-primary-dark)); color: white; border-radius: 16px 16px 0 0; padding: 1.5rem;">
                <h3 class="modal-title" id="termsModalLabel" style="font-weight: 700; font-size: 1.1rem;">
                    <i class="fas fa-file-contract me-2"></i>Terms and Conditions
                </h3>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 2rem; font-size: 0.9rem; line-height: 1.7; color: var(--neutral-700);">
                @php
                    $termsContent = \App\Models\SystemSetting::get('terms_and_conditions', 'Terms and conditions content will appear here.');
                    $termsContent = nl2br(e($termsContent));
                @endphp
                {!! $termsContent !!}
            </div>
            <div class="modal-footer" style="border-top: 2px solid var(--neutral-200); padding: 1rem 1.5rem;">
                <button type="button" class="btn" style="background: var(--neutral-200); color: var(--neutral-700); font-weight: 600; padding: 0.75rem 1.5rem; border-radius: 8px;" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Initialize Bootstrap tooltips
document.addEventListener('DOMContentLoaded', function() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Add staggered animation to stat cards on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    document.querySelectorAll('.stat-card, .dashboard-card, .action-card').forEach(card => {
        observer.observe(card);
    });
});
</script>
@endpush
