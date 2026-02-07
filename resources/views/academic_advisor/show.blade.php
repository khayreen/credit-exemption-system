@extends('layouts.app')

@push('styles')
<style>
/* ============================================
   APPLICATION REVIEW PAGE - INDUSTRIAL UI
   ============================================ */

.aa-review-page {
    position: relative;
    padding: 2rem;
    min-height: 100vh;
}

.aa-review-page::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image:
        linear-gradient(rgba(30, 58, 138, 0.015) 1px, transparent 1px),
        linear-gradient(90deg, rgba(30, 58, 138, 0.015) 1px, transparent 1px);
    background-size: 40px 40px;
    pointer-events: none;
    z-index: -1;
}

/* Back Navigation */
.aa-back-nav {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.6rem 1rem;
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    color: #64748b;
    font-weight: 600;
    font-size: 0.875rem;
    text-decoration: none;
    margin-bottom: 1.5rem;
    transition: all 0.2s ease;
}

.aa-back-nav:hover {
    background: #f8fafc;
    color: #1e293b;
    transform: translateX(-2px);
}

/* Student Header Card */
.aa-student-header {
    background: linear-gradient(135deg, #1e3a8a 0%, #1e293b 100%);
    border-radius: 16px;
    padding: 2rem 2.5rem;
    margin-bottom: 1.5rem;
    position: relative;
    overflow: hidden;
    box-shadow:
        0 4px 6px -1px rgba(0, 0, 0, 0.1),
        0 20px 40px -10px rgba(30, 58, 138, 0.3);
}

.aa-student-header::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 50%;
    height: 100%;
    background: linear-gradient(135deg, transparent 0%, rgba(255, 255, 255, 0.03) 100%);
    transform: skewX(-20deg);
    transform-origin: top right;
}

.aa-student-header::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #f59e0b 0%, #fbbf24 50%, #f59e0b 100%);
}

.aa-student-grid {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 2rem;
    align-items: center;
    position: relative;
    z-index: 1;
}

.aa-student-main {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.aa-student-avatar-lg {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0.1) 100%);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: 700;
    color: white;
    border: 2px solid rgba(255, 255, 255, 0.2);
    flex-shrink: 0;
}

.aa-student-details h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: white;
    margin: 0 0 0.75rem;
}

.aa-student-meta {
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
}

.aa-student-meta-item {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    font-size: 0.875rem;
    color: rgba(255, 255, 255, 0.8);
}

.aa-student-meta-item i {
    width: 16px;
    opacity: 0.7;
}

.aa-header-status {
    text-align: right;
}

.aa-status-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: rgba(251, 191, 36, 0.2);
    border: 1px solid rgba(251, 191, 36, 0.3);
    color: #fbbf24;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.85rem;
}

.aa-date-info {
    margin-top: 0.75rem;
    font-size: 0.8rem;
    color: rgba(255, 255, 255, 0.6);
}

/* Stats Row */
.aa-stats-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.aa-mini-stat {
    background: white;
    border-radius: 12px;
    padding: 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 4px 12px rgba(0, 0, 0, 0.05);
}

.aa-mini-stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    color: white;
    flex-shrink: 0;
}

.aa-mini-stat-icon.success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
.aa-mini-stat-icon.warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
.aa-mini-stat-icon.info { background: linear-gradient(135deg, #3b82f6 0%, #1e3a8a 100%); }
.aa-mini-stat-icon.primary { background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%); }

.aa-mini-stat-value {
    font-family: 'IBM Plex Mono', monospace;
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
    line-height: 1;
}

.aa-mini-stat-label {
    font-size: 0.8rem;
    color: #64748b;
    margin-top: 0.2rem;
}

/* Progress Card */
.aa-progress-card {
    background: white;
    border-radius: 12px;
    padding: 1.25rem 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 4px 12px rgba(0, 0, 0, 0.05);
}

.aa-progress-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.75rem;
}

.aa-progress-title {
    font-weight: 700;
    color: #1e293b;
}

.aa-progress-percent {
    font-family: 'IBM Plex Mono', monospace;
    font-weight: 700;
    color: #10b981;
}

.aa-progress-bar {
    height: 10px;
    background: #e2e8f0;
    border-radius: 5px;
    overflow: hidden;
}

.aa-progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #10b981 0%, #34d399 100%);
    border-radius: 5px;
    transition: width 0.5s ease;
}

/* Transcript Card */
.aa-transcript-card {
    background: white;
    border-radius: 12px;
    padding: 1.25rem 1.5rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 4px 12px rgba(0, 0, 0, 0.05);
    border-left: 4px solid #dc2626;
}

.aa-transcript-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.aa-transcript-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: #dc2626;
}

.aa-transcript-title {
    font-weight: 700;
    color: #1e293b;
}

.aa-transcript-subtitle {
    font-size: 0.8rem;
    color: #64748b;
}

.aa-btn-transcript {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
    background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
    color: white;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.875rem;
    text-decoration: none;
    transition: all 0.2s ease;
    box-shadow: 0 4px 12px rgba(30, 58, 138, 0.2);
}

.aa-btn-transcript:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(30, 58, 138, 0.3);
    color: white;
}

/* Course Section */
.aa-course-section {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    margin-bottom: 1.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 4px 12px rgba(0, 0, 0, 0.05);
}

.aa-course-header {
    padding: 1.25rem 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid #e2e8f0;
}

.aa-course-header.success {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.08) 0%, rgba(5, 150, 105, 0.08) 100%);
    border-left: 4px solid #10b981;
}

.aa-course-header.warning {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.08) 0%, rgba(217, 119, 6, 0.08) 100%);
    border-left: 4px solid #f59e0b;
}

.aa-course-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 1rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
}

.aa-course-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
}

.aa-course-icon.success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.aa-course-icon.warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
}

.aa-course-badge {
    padding: 0.35rem 0.75rem;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 700;
}

.aa-course-badge.success {
    background: #10b981;
    color: white;
}

.aa-course-badge.warning {
    background: #f59e0b;
    color: white;
}

.aa-course-subtitle {
    font-size: 0.8rem;
    color: #64748b;
    margin: 0.25rem 0 0 0;
}

/* Bulk Action Buttons */
.aa-bulk-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.6rem 1.1rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.8rem;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
}

.aa-bulk-btn.approve {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.aa-bulk-btn.approve:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
}

.aa-bulk-btn.reject {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

.aa-bulk-btn.reject:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
}

/* Course Table */
.aa-course-table {
    width: 100%;
    border-collapse: collapse;
}

.aa-course-table thead {
    background: #f8fafc;
}

.aa-course-table th {
    padding: 0.875rem 1rem;
    font-family: 'IBM Plex Mono', monospace;
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #64748b;
    text-align: left;
    border-bottom: 1px solid #e2e8f0;
    white-space: nowrap;
}

.aa-course-table tbody tr {
    border-bottom: 1px solid #f1f5f9;
    transition: background 0.15s ease;
}

.aa-course-table tbody tr:last-child {
    border-bottom: none;
}

.aa-course-table tbody tr:hover {
    background: #fafbfc;
}

.aa-course-table td {
    padding: 1rem;
    vertical-align: middle;
    font-size: 0.875rem;
}

/* Column Classes */
.col-code { width: 130px; }
.col-name { min-width: 200px; }
.col-grade { width: 80px; text-align: center; }
.col-equivalent { width: 100px; text-align: center; }
.col-match { width: 90px; text-align: center; }
.col-credits { width: 70px; text-align: center; }
.col-status { width: 120px; }
.col-actions { width: 190px; text-align: right; }

/* Course Code Badge */
.aa-code-badge {
    display: inline-block;
    padding: 0.4rem 0.6rem;
    background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
    color: white;
    border-radius: 6px;
    font-family: 'IBM Plex Mono', monospace;
    font-weight: 700;
    font-size: 0.75rem;
    min-width: 90px;
    text-align: center;
}

.aa-course-name {
    font-weight: 500;
    color: #1e293b;
    line-height: 1.4;
}

/* Grade Badge */
.aa-grade-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.35rem 0.6rem;
    border-radius: 6px;
    font-weight: 700;
    font-size: 0.8rem;
    min-width: 40px;
}

.aa-grade-badge.good {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(5, 150, 105, 0.15) 100%);
    color: #059669;
}

.aa-grade-badge.low {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.15) 0%, rgba(220, 38, 38, 0.15) 100%);
    color: #dc2626;
}

/* Equivalent Badge */
.aa-equiv-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.35rem 0.6rem;
    background: linear-gradient(135deg, rgba(30, 58, 138, 0.1) 0%, rgba(59, 130, 246, 0.1) 100%);
    color: #1e3a8a;
    border-radius: 6px;
    font-family: 'IBM Plex Mono', monospace;
    font-weight: 600;
    font-size: 0.75rem;
}

/* Match Badge */
.aa-match-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.35rem 0.6rem;
    border-radius: 6px;
    font-weight: 700;
    font-size: 0.8rem;
}

.aa-match-badge.high {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.15) 0%, rgba(30, 58, 138, 0.15) 100%);
    color: #1e3a8a;
}

.aa-match-badge.low {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(217, 119, 6, 0.15) 100%);
    color: #b45309;
}

/* Credits Badge */
.aa-credits-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.3rem 0.5rem;
    background: #f1f5f9;
    border-radius: 6px;
    font-weight: 500;
    font-size: 0.8rem;
    color: #64748b;
}

/* Status Reason */
.aa-status-reason {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.35rem 0.6rem;
    border-radius: 6px;
    font-size: 0.7rem;
    font-weight: 600;
}

.aa-status-reason.not-found {
    background: #e2e8f0;
    color: #475569;
}

.aa-status-reason.low-grade {
    background: #fee2e2;
    color: #991b1b;
}

.aa-status-reason.low-match {
    background: #fef3c7;
    color: #92400e;
}

/* Action Buttons */
.aa-action-group {
    display: inline-flex;
    gap: 0.5rem;
    align-items: center;
}

.aa-action-btn {
    padding: 0.45rem 0.8rem;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.75rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.3rem;
    transition: all 0.2s ease;
    border: none;
    cursor: pointer;
    min-width: 80px;
}

.aa-action-btn.approve {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.aa-action-btn.approve:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.35);
}

.aa-action-btn.reject {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
}

.aa-action-btn.reject:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.35);
}

.aa-action-btn.undo {
    background: #f1f5f9;
    color: #64748b;
    border: 1px solid #e2e8f0;
    min-width: 36px;
    padding: 0.45rem;
}

.aa-action-btn.undo:hover {
    background: #e2e8f0;
}

/* Decision Badge */
.aa-decision-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.4rem 0.75rem;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.75rem;
}

.aa-decision-badge.approved {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(5, 150, 105, 0.15) 100%);
    color: #059669;
}

.aa-decision-badge.rejected {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.15) 0%, rgba(220, 38, 38, 0.15) 100%);
    color: #dc2626;
}

/* Empty State */
.aa-empty-section {
    text-align: center;
    padding: 3rem 2rem;
    color: #64748b;
}

.aa-empty-section i {
    font-size: 2.5rem;
    margin-bottom: 0.75rem;
    opacity: 0.4;
}

/* Toast */
.aa-toast-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
}

.aa-toast {
    padding: 1rem 1.5rem;
    border-radius: 10px;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    animation: slideIn 0.3s ease;
    font-weight: 500;
}

.aa-toast.success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.aa-toast.error {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
}

@keyframes slideIn {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}

/* Responsive */
@media (max-width: 1200px) {
    .aa-course-table { display: block; overflow-x: auto; }
}

@media (max-width: 992px) {
    .aa-stats-row { grid-template-columns: repeat(2, 1fr); }
    .aa-student-grid { grid-template-columns: 1fr; text-align: center; }
    .aa-header-status { text-align: center; }
}

@media (max-width: 768px) {
    .aa-review-page { padding: 1rem; }
    .aa-student-header { padding: 1.5rem; }
    .aa-student-main { flex-direction: column; }
    .aa-stats-row { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')
<div class="aa-review-page">
    <!-- Back Navigation -->
    <a href="{{ route('academic_advisor.dashboard') }}" class="aa-back-nav">
        <i class="fas fa-arrow-left"></i>
        <span>Back to Dashboard</span>
    </a>

    <!-- Student Header -->
    <div class="aa-student-header">
        <div class="aa-student-grid">
            <div class="aa-student-main">
                <div class="aa-student-avatar-lg">{{ strtoupper(substr($application->student_name, 0, 2)) }}</div>
                <div class="aa-student-details">
                    <h2>{{ $application->student_name }}</h2>
                    <div class="aa-student-meta">
                        <div class="aa-student-meta-item">
                            <i class="fas fa-id-card"></i>
                            <span>{{ $application->matric_no }}</span>
                        </div>
                        <div class="aa-student-meta-item">
                            <i class="fas fa-university"></i>
                            <span>{{ $application->previous_institution }}</span>
                        </div>
                        <div class="aa-student-meta-item">
                            <i class="fas fa-graduation-cap"></i>
                            <span>{{ $application->previous_program }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="aa-header-status">
                <div class="aa-status-chip">
                    <i class="fas fa-clock"></i>
                    {{ $application->status }}
                </div>
                <div class="aa-date-info">
                    <i class="fas fa-calendar-alt"></i>
                    {{ $application->created_at->format('M d, Y') }}
                </div>
            </div>
        </div>
    </div>

    @if($application->applicationSubjects->isNotEmpty())
    <!-- Stats Row -->
    <div class="aa-stats-row">
        <div class="aa-mini-stat">
            <div class="aa-mini-stat-icon success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div>
                <div class="aa-mini-stat-value">{{ $exemptedSubjects->count() }}</div>
                <div class="aa-mini-stat-label">Pre-Qualified</div>
            </div>
        </div>
        <div class="aa-mini-stat">
            <div class="aa-mini-stat-icon warning">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div>
                <div class="aa-mini-stat-value">{{ $nonExemptedSubjects->count() }}</div>
                <div class="aa-mini-stat-label">Needs Review</div>
            </div>
        </div>
        <div class="aa-mini-stat">
            <div class="aa-mini-stat-icon info">
                <i class="fas fa-code-branch"></i>
            </div>
            <div>
                <div class="aa-mini-stat-value">{{ $application->current_program_code ?? 'N/A' }}</div>
                <div class="aa-mini-stat-label">Program</div>
            </div>
        </div>
        <div class="aa-mini-stat">
            <div class="aa-mini-stat-icon primary">
                <i class="fas fa-book"></i>
            </div>
            <div>
                <div class="aa-mini-stat-value">{{ $subjects->count() }}</div>
                <div class="aa-mini-stat-label">Total Courses</div>
            </div>
        </div>
    </div>

    <!-- Progress Card -->
    @php
        $totalSubjects = $subjects->count();
        $decidedSubjects = $subjects->filter(fn($s) => in_array($s['status'], ['Approved', 'Rejected']))->count();
        $progressPercent = $totalSubjects > 0 ? round(($decidedSubjects / $totalSubjects) * 100) : 0;
    @endphp
    <div class="aa-progress-card">
        <div class="aa-progress-header">
            <div>
                <span class="aa-progress-title">Review Progress</span>
                <small class="text-muted ms-2">{{ $decidedSubjects }} of {{ $totalSubjects }} courses</small>
            </div>
            <span class="aa-progress-percent">{{ $progressPercent }}%</span>
        </div>
        <div class="aa-progress-bar">
            <div class="aa-progress-fill" style="width: {{ $progressPercent }}%"></div>
        </div>
    </div>
    @endif

    <!-- Transcript Card -->
    <div class="aa-transcript-card">
        <div class="aa-transcript-info">
            <div class="aa-transcript-icon">
                <i class="fas fa-file-pdf"></i>
            </div>
            <div>
                <div class="aa-transcript-title">Academic Transcript</div>
                <div class="aa-transcript-subtitle">View original document</div>
            </div>
        </div>
        <a href="{{ route('academic_advisor.application.transcript', $application) }}" target="_blank" class="aa-btn-transcript">
            <i class="fas fa-external-link-alt"></i>
            <span>Open PDF</span>
        </a>
    </div>

    <!-- Pre-Qualified Courses -->
    @if($exemptedSubjects->isNotEmpty())
    <div class="aa-course-section">
        <div class="aa-course-header success">
            <div>
                <h3 class="aa-course-title">
                    <span class="aa-course-icon success"><i class="fas fa-check-circle"></i></span>
                    Pre-Qualified Courses
                    <span class="aa-course-badge success">{{ $exemptedSubjects->count() }}</span>
                </h3>
                <p class="aa-course-subtitle">Met all criteria: Found in database + Grade C or above + Match >80%</p>
            </div>
            <button type="button" class="aa-bulk-btn approve" id="approveAllBtn">
                <i class="fas fa-check-double"></i> Approve All
            </button>
        </div>
        <table class="aa-course-table">
            <thead>
                <tr>
                    <th class="col-code">Course Code</th>
                    <th class="col-name">Course Name</th>
                    <th class="col-grade">Grade</th>
                    <th class="col-equivalent">Equivalent</th>
                    <th class="col-match">Match</th>
                    <th class="col-credits">Credits</th>
                    <th class="col-actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($exemptedSubjects as $subject)
                <tr data-subject-id="{{ $subject['id'] }}">
                    <td><span class="aa-code-badge">{{ $subject['course_code'] }}</span></td>
                    <td><span class="aa-course-name">{{ $subject['course_name'] }}</span></td>
                    <td class="text-center">
                        @if($subject['is_combination'] && $subject['individual_grades'])
                            @php
                                $courseCodes = explode(' & ', $subject['course_code']);
                                $grades = [];
                                foreach($courseCodes as $code) {
                                    if(isset($subject['individual_grades'][$code])) {
                                        $grades[] = $subject['individual_grades'][$code];
                                    }
                                }
                            @endphp
                            @foreach($grades as $grade)
                                <span class="aa-grade-badge good">{{ $grade }}</span>
                            @endforeach
                        @else
                            <span class="aa-grade-badge good">{{ $subject['grade_letter'] }}</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($subject['equivalent_course'])
                            <span class="aa-equiv-badge">{{ $subject['equivalent_course'] }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($subject['match_percentage'])
                            <span class="aa-match-badge high">{{ $subject['match_percentage'] }}%</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($subject['credit_hour'])
                            <span class="aa-credits-badge">{{ $subject['credit_hour'] }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="aa-action-group">
                        @if($subject['status'] === 'Approved')
                            <span class="aa-decision-badge approved"><i class="fas fa-check"></i> Approved</span>
                            <button type="button" class="aa-action-btn undo undo-btn"
                                    data-subject-id="{{ $subject['id'] }}"
                                    data-course-code="{{ $subject['course_code'] }}">
                                <i class="fas fa-undo"></i>
                            </button>
                        @else
                            <button type="button" class="aa-action-btn approve decision-btn"
                                    data-subject-id="{{ $subject['id'] }}"
                                    data-decision="Approved"
                                    data-course-code="{{ $subject['course_code'] }}">
                                <i class="fas fa-check"></i> Approve
                            </button>
                            <button type="button" class="aa-action-btn reject decision-btn"
                                    data-subject-id="{{ $subject['id'] }}"
                                    data-decision="Rejected"
                                    data-course-code="{{ $subject['course_code'] }}">
                                <i class="fas fa-times"></i> Reject
                            </button>
                        @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Manual Review Courses -->
    @if($nonExemptedSubjects->isNotEmpty())
    <div class="aa-course-section">
        <div class="aa-course-header warning">
            <div>
                <h3 class="aa-course-title">
                    <span class="aa-course-icon warning"><i class="fas fa-exclamation-triangle"></i></span>
                    Requires Manual Review
                    <span class="aa-course-badge warning">{{ $nonExemptedSubjects->count() }}</span>
                </h3>
                <p class="aa-course-subtitle">These courses did not meet OCR exemption criteria</p>
            </div>
            <button type="button" class="aa-bulk-btn reject" id="rejectAllBtn">
                <i class="fas fa-times-circle"></i> Reject All
            </button>
        </div>
        <table class="aa-course-table">
            <thead>
                <tr>
                    <th class="col-code">Course Code</th>
                    <th class="col-name">Course Name</th>
                    <th class="col-grade">Grade</th>
                    <th class="col-equivalent">Equivalent</th>
                    <th class="col-match">Match</th>
                    <th class="col-status">Status</th>
                    <th class="col-actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($nonExemptedSubjects as $subject)
                <tr data-subject-id="{{ $subject['id'] }}">
                    <td><span class="aa-code-badge">{{ $subject['course_code'] }}</span></td>
                    <td><span class="aa-course-name">{{ $subject['course_name'] }}</span></td>
                    <td class="text-center">
                        @if($subject['is_combination'] && $subject['individual_grades'])
                            @php
                                $courseCodes = explode(' & ', $subject['course_code']);
                                $grades = [];
                                foreach($courseCodes as $code) {
                                    if(isset($subject['individual_grades'][$code])) {
                                        $grades[] = $subject['individual_grades'][$code];
                                    }
                                }
                                $isLowGrade = $subject['status'] == 'not_eligible_grade';
                            @endphp
                            @foreach($grades as $grade)
                                <span class="aa-grade-badge {{ $isLowGrade ? 'low' : 'good' }}">{{ $grade }}</span>
                            @endforeach
                        @else
                            <span class="aa-grade-badge {{ $subject['status'] == 'not_eligible_grade' ? 'low' : 'good' }}">{{ $subject['grade_letter'] }}</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($subject['equivalent_course'])
                            <span class="aa-equiv-badge">{{ $subject['equivalent_course'] }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($subject['match_percentage'])
                            <span class="aa-match-badge {{ $subject['match_percentage'] > 80 ? 'high' : 'low' }}">{{ $subject['match_percentage'] }}%</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($subject['status'] == 'not_found')
                            <span class="aa-status-reason not-found"><i class="fas fa-search"></i> Not Found</span>
                        @elseif($subject['status'] == 'not_eligible_grade')
                            <span class="aa-status-reason low-grade"><i class="fas fa-arrow-down"></i> Low Grade</span>
                        @elseif($subject['status'] == 'not_eligible_match')
                            <span class="aa-status-reason low-match"><i class="fas fa-percentage"></i> Low Match</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="aa-action-group">
                        @if(in_array($subject['status'], ['Approved', 'Rejected']))
                            <span class="aa-decision-badge {{ $subject['status'] === 'Approved' ? 'approved' : 'rejected' }}">
                                <i class="fas fa-{{ $subject['status'] === 'Approved' ? 'check' : 'times' }}"></i>
                                {{ $subject['status'] }}
                            </span>
                            <button type="button" class="aa-action-btn undo undo-btn"
                                    data-subject-id="{{ $subject['id'] }}"
                                    data-course-code="{{ $subject['course_code'] }}">
                                <i class="fas fa-undo"></i>
                            </button>
                        @else
                            <button type="button" class="aa-action-btn approve decision-btn"
                                    data-subject-id="{{ $subject['id'] }}"
                                    data-decision="Approved"
                                    data-course-code="{{ $subject['course_code'] }}">
                                <i class="fas fa-check"></i> Approve
                            </button>
                            <button type="button" class="aa-action-btn reject decision-btn"
                                    data-subject-id="{{ $subject['id'] }}"
                                    data-decision="Rejected"
                                    data-course-code="{{ $subject['course_code'] }}">
                                <i class="fas fa-times"></i> Reject
                            </button>
                        @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Empty State -->
    @if($subjects->isEmpty())
    <div class="aa-course-section">
        <div class="aa-empty-section">
            <i class="fas fa-inbox"></i>
            <h6>No Courses Found</h6>
            <p class="mb-0">No courses were extracted from the transcript.</p>
        </div>
    </div>
    @endif
</div>

<!-- Toast Container -->
<div class="aa-toast-container" id="toast-container"></div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const approveAllBtn = document.getElementById('approveAllBtn');
    const rejectAllBtn = document.getElementById('rejectAllBtn');

    // Approve All
    if (approveAllBtn) {
        approveAllBtn.addEventListener('click', function() {
            const section = this.closest('.aa-course-section');
            const pending = section.querySelectorAll('.decision-btn').length / 2;

            if (pending === 0) {
                showToast('error', 'All courses already approved.');
                return;
            }

            if (confirm(`Approve all ${pending} pre-qualified course(s)?`)) {
                this.disabled = true;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

                fetch('{{ route('academic_advisor.application.bulk_approve_prequalified', ['application' => $application->id]) }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        showToast('success', data.message);
                        section.querySelectorAll('tr[data-subject-id]').forEach(row => {
                            const cell = row.querySelector('td:last-child');
                            if (cell.querySelector('.decision-btn')) {
                                const id = row.dataset.subjectId;
                                const code = cell.querySelector('.decision-btn').dataset.courseCode;
                                cell.innerHTML = `<div class="aa-action-group"><span class="aa-decision-badge approved"><i class="fas fa-check"></i> Approved</span>
                                    <button type="button" class="aa-action-btn undo undo-btn" data-subject-id="${id}" data-course-code="${code}"><i class="fas fa-undo"></i></button></div>`;
                            }
                        });
                        this.style.display = 'none';
                        updateProgress();
                    } else throw new Error(data.message);
                })
                .catch(e => {
                    showToast('error', e.message || 'An error occurred.');
                    this.disabled = false;
                    this.innerHTML = '<i class="fas fa-check-double"></i> Approve All';
                });
            }
        });
    }

    // Reject All
    if (rejectAllBtn) {
        rejectAllBtn.addEventListener('click', function() {
            const section = this.closest('.aa-course-section');
            const pending = section.querySelectorAll('.decision-btn').length / 2;

            if (pending === 0) {
                showToast('error', 'All courses already decided.');
                return;
            }

            if (confirm(`Reject all ${pending} course(s)?`)) {
                this.disabled = true;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

                fetch('{{ route('academic_advisor.application.bulk_reject_manual_review', ['application' => $application->id]) }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        showToast('success', data.message);
                        section.querySelectorAll('tr[data-subject-id]').forEach(row => {
                            const cell = row.querySelector('td:last-child');
                            if (cell.querySelector('.decision-btn')) {
                                const id = row.dataset.subjectId;
                                const code = cell.querySelector('.decision-btn').dataset.courseCode;
                                cell.innerHTML = `<div class="aa-action-group"><span class="aa-decision-badge rejected"><i class="fas fa-times"></i> Rejected</span>
                                    <button type="button" class="aa-action-btn undo undo-btn" data-subject-id="${id}" data-course-code="${code}"><i class="fas fa-undo"></i></button></div>`;
                            }
                        });
                        this.style.display = 'none';
                        updateProgress();
                    } else throw new Error(data.message);
                })
                .catch(e => {
                    showToast('error', e.message || 'An error occurred.');
                    this.disabled = false;
                    this.innerHTML = '<i class="fas fa-times-circle"></i> Reject All';
                });
            }
        });
    }

    // Individual decisions
    document.addEventListener('click', function(e) {
        if (e.target.closest('.decision-btn')) {
            const btn = e.target.closest('.decision-btn');
            const { subjectId, decision, courseCode } = btn.dataset;
            const section = btn.closest('.aa-course-section');
            const isPreQualified = section.querySelector('.aa-course-header.success');

            if (decision === 'Rejected' && isPreQualified && !confirm(`Reject pre-qualified course ${courseCode}?`)) return;

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            fetch(`{{ route('academic_advisor.application.subject.decision', ['application' => $application->id, 'subject' => '__ID__']) }}`.replace('__ID__', subjectId), {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: JSON.stringify({ decision })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showToast('success', data.message);
                    const cell = btn.closest('td');
                    const badge = decision === 'Approved' ? 'approved' : 'rejected';
                    const icon = decision === 'Approved' ? 'check' : 'times';
                    cell.innerHTML = `<div class="aa-action-group"><span class="aa-decision-badge ${badge}"><i class="fas fa-${icon}"></i> ${decision}</span>
                        <button type="button" class="aa-action-btn undo undo-btn" data-subject-id="${subjectId}" data-course-code="${courseCode}"><i class="fas fa-undo"></i></button></div>`;
                    updateProgress();
                } else throw new Error(data.message);
            })
            .catch(e => {
                showToast('error', 'An error occurred.');
                btn.disabled = false;
                btn.innerHTML = decision === 'Approved' ? '<i class="fas fa-check"></i> Approve' : '<i class="fas fa-times"></i> Reject';
            });
        }
    });

    // Undo
    document.addEventListener('click', function(e) {
        if (e.target.closest('.undo-btn')) {
            const btn = e.target.closest('.undo-btn');
            const { subjectId, courseCode } = btn.dataset;

            if (!confirm(`Undo decision for ${courseCode}?`)) return;

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            fetch(`{{ route('academic_advisor.application.subject.decision', ['application' => $application->id, 'subject' => '__ID__']) }}`.replace('__ID__', subjectId), {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: JSON.stringify({ decision: 'Undo' })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showToast('success', data.message);
                    const cell = btn.closest('td');
                    cell.innerHTML = `<div class="aa-action-group"><button type="button" class="aa-action-btn approve decision-btn" data-subject-id="${subjectId}" data-decision="Approved" data-course-code="${courseCode}"><i class="fas fa-check"></i> Approve</button>
                        <button type="button" class="aa-action-btn reject decision-btn" data-subject-id="${subjectId}" data-decision="Rejected" data-course-code="${courseCode}"><i class="fas fa-times"></i> Reject</button></div>`;
                    if (approveAllBtn) approveAllBtn.style.display = '';
                    if (rejectAllBtn) rejectAllBtn.style.display = '';
                    updateProgress();
                } else throw new Error(data.message);
            })
            .catch(e => {
                showToast('error', 'An error occurred.');
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-undo"></i>';
            });
        }
    });

    function updateProgress() {
        const total = document.querySelectorAll('tr[data-subject-id]').length;
        const decided = document.querySelectorAll('.aa-decision-badge').length;
        const pct = total > 0 ? Math.round((decided / total) * 100) : 0;

        const fill = document.querySelector('.aa-progress-fill');
        const text = document.querySelector('.aa-progress-percent');
        const sub = document.querySelector('.aa-progress-card small');

        if (fill) fill.style.width = pct + '%';
        if (text) text.textContent = pct + '%';
        if (sub) sub.textContent = decided + ' of ' + total + ' courses';
    }

    function showToast(type, msg) {
        const container = document.getElementById('toast-container');
        const id = 'toast-' + Date.now();
        const toast = document.createElement('div');
        toast.id = id;
        toast.className = 'aa-toast ' + type;
        toast.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i><span>${msg}</span>`;
        container.appendChild(toast);
        setTimeout(() => {
            const el = document.getElementById(id);
            if (el) { el.style.opacity = '0'; el.style.transform = 'translateX(100%)'; setTimeout(() => el.remove(), 300); }
        }, 4000);
    }
});
</script>
@endpush
