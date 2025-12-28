@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Page Header -->
    <div class="aa-page-header mb-4">
        <div class="aa-header-icon">
            <i class="fas fa-tachometer-alt"></i>
        </div>
        <div class="aa-header-content">
            <h1 class="aa-header-title">DASHBOARD</h1>
            <p class="aa-header-subtitle">Overview of applications and exemption statistics</p>
        </div>
        <div class="aa-header-action">
            <a href="{{ route('academic_advisor.my_students') }}" class="btn btn-primary">
                <i class="fas fa-users me-2"></i>View My Students
            </a>
        </div>
    </div>

    <!-- Statistics Dashboard -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card-dashboard stat-card-orange">
                <div class="stat-icon-dashboard">
                    <i class="fas fa-inbox"></i>
                </div>
                <div class="stat-content-dashboard">
                    <h2 class="stat-number-dashboard">{{ $stats['pending_review'] }}</h2>
                    <p class="stat-label-dashboard">Pending Review</p>
                    <a href="#pending-section" class="stat-link-dashboard">Review now <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
                <div class="stat-decoration-dashboard"></div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card-dashboard stat-card-green">
                <div class="stat-icon-dashboard">
                    <i class="fas fa-check-double"></i>
                </div>
                <div class="stat-content-dashboard">
                    <h2 class="stat-number-dashboard">{{ $stats['total_reviewed'] }}</h2>
                    <p class="stat-label-dashboard">Applications Reviewed</p>
                    <span class="stat-badge-dashboard">All time</span>
                </div>
                <div class="stat-decoration-dashboard"></div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card-dashboard stat-card-blue">
                <div class="stat-icon-dashboard">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="stat-content-dashboard">
                    <h2 class="stat-number-dashboard">{{ $stats['exempted_courses'] }}</h2>
                    <p class="stat-label-dashboard">Courses Exempted</p>
                    <span class="stat-badge-dashboard">OCR Approved</span>
                </div>
                <div class="stat-decoration-dashboard"></div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card-dashboard stat-card-purple">
                <div class="stat-icon-dashboard">
                    <i class="fas fa-robot"></i>
                </div>
                <div class="stat-content-dashboard">
                    <h2 class="stat-number-dashboard">{{ $stats['total_courses'] }}</h2>
                    <p class="stat-label-dashboard">OCR Processed</p>
                    <span class="stat-badge-dashboard">Total Courses</span>
                </div>
                <div class="stat-decoration-dashboard"></div>
            </div>
        </div>
    </div>

    <!-- Pending Applications Section -->
    <div id="pending-section" class="applications-section">
        <div class="section-header mb-4">
            <div class="section-header-left">
                <h3 class="section-title">
                    <i class="fas fa-clipboard-list me-2"></i>Pending Applications
                </h3>
                <p class="section-subtitle">Applications awaiting your review and decision</p>
            </div>
            @if(!$applications->isEmpty())
            <div class="section-header-right">
                <span class="application-count-badge">{{ $applications->count() }} {{ Str::plural('application', $applications->count()) }}</span>
            </div>
            @endif
        </div>

        @if($applications->isEmpty())
            <!-- Empty State -->
            <div class="empty-state-dashboard">
                <div class="empty-state-icon-dashboard">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h4 class="empty-state-title-dashboard">All Caught Up!</h4>
                <p class="empty-state-text-dashboard">There are no applications pending your review at this time. Great work!</p>
                <a href="{{ route('academic_advisor.my_students') }}" class="btn-empty-action">
                    <i class="fas fa-users me-2"></i>View All Students
                </a>
            </div>
        @else
            <!-- Applications Table -->
            <div class="applications-table-card">
                <table class="table applications-table modern-table-dashboard">
                    <thead>
                        <tr>
                            <th style="width: 5%">#</th>
                            <th style="width: 30%">Student</th>
                            <th style="width: 15%">Submitted</th>
                            <th style="width: 15%">Program</th>
                            <th style="width: 20%">Status</th>
                            <th style="width: 15%" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications as $app)
                        <tr class="application-row">
                            <td class="row-number-dashboard">{{ $loop->iteration }}</td>
                            <td>
                                <div class="student-info-dashboard">
                                    <div class="student-avatar-dashboard">
                                        {{ strtoupper(substr($app->student_name ?? $app->name, 0, 1)) }}
                                    </div>
                                    <div class="student-details-dashboard">
                                        <div class="student-name-dashboard">{{ $app->student_name ?? $app->name }}</div>
                                        <div class="student-meta-dashboard">
                                            <span class="matric-no-dashboard">{{ $app->matric_no }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="submission-info">
                                    <div class="submission-date">{{ $app->created_at->format('d M Y') }}</div>
                                    <div class="submission-time">{{ $app->created_at->format('h:i A') }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="program-badge-dashboard">
                                    {{ $app->current_program_code ?? 'N/A' }}
                                </div>
                            </td>
                            <td>
                                <span class="status-badge-dashboard status-submitted">
                                    <i class="fas fa-clock"></i> {{ $app->status }}
                                </span>
                                @if($app->applicationSubjects->isNotEmpty())
                                    <div class="course-count-info">
                                        {{ $app->applicationSubjects->count() }} {{ Str::plural('course', $app->applicationSubjects->count()) }}
                                    </div>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('academic_advisor.application.show', $app) }}"
                                   class="btn-review-dashboard">
                                    <i class="fas fa-eye me-2"></i>Review
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<style>
/* ========================================
   ACADEMIC ADVISOR CONSISTENT HEADER
   Used across all AA pages
   ======================================== */

/* AA Page Header */
.aa-page-header {
    background: linear-gradient(to right, #f8f9fa 0%, #ffffff 100%);
    border-left: 5px solid #667eea;
    padding: 1.75rem 1.5rem;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 1.25rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    flex-wrap: wrap;
}

.aa-header-icon {
    width: 50px;
    height: 50px;
    background: #667eea;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
    flex-shrink: 0;
}

.aa-header-content {
    flex: 1;
}

.aa-header-title {
    font-size: 1.35rem;
    font-weight: 700;
    margin: 0;
    color: #1e293b;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    line-height: 1.3;
}

.aa-header-subtitle {
    font-size: 0.95rem;
    color: #64748b;
    margin: 0.35rem 0 0 0;
    font-weight: 500;
}

.aa-header-action {
    margin-left: auto;
}

@media (max-width: 768px) {
    .aa-page-header {
        flex-direction: column;
        text-align: center;
    }

    .aa-header-action {
        margin-left: 0;
        width: 100%;
    }

    .aa-header-action .btn {
        width: 100%;
    }
}

/* Enhanced Statistics Cards */
.stat-card-dashboard {
    position: relative;
    padding: 1.5rem;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    background: white;
    border: none;
    height: 100%;
}

.stat-card-dashboard:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
}

.stat-icon-dashboard {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    margin-bottom: 1rem;
}

.stat-card-orange .stat-icon-dashboard {
    background: linear-gradient(135deg, #ff9a56 0%, #ff6a00 100%);
    color: white;
}

.stat-card-green .stat-icon-dashboard {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.stat-card-blue .stat-icon-dashboard {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
}

.stat-card-purple .stat-icon-dashboard {
    background: linear-gradient(135deg, #a855f7 0%, #9333ea 100%);
    color: white;
}

.stat-number-dashboard {
    font-size: 1.75rem;
    font-weight: 700;
    margin: 0;
    color: #1e293b;
    line-height: 1;
}

.stat-label-dashboard {
    margin: 0.5rem 0;
    font-size: 0.875rem;
    color: #64748b;
    font-weight: 600;
}

.stat-link-dashboard {
    display: inline-flex;
    align-items: center;
    font-size: 0.8rem;
    color: #667eea;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
}

.stat-link-dashboard:hover {
    color: #764ba2;
    transform: translateX(3px);
}

.stat-badge-dashboard {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    background: #f1f5f9;
    border-radius: 6px;
    font-size: 0.8rem;
    color: #64748b;
    font-weight: 600;
}

.stat-decoration-dashboard {
    position: absolute;
    right: -30px;
    bottom: -30px;
    width: 120px;
    height: 120px;
    border-radius: 50%;
    opacity: 0.05;
}

.stat-card-orange .stat-decoration-dashboard {
    background: #ff6a00;
}

.stat-card-green .stat-decoration-dashboard {
    background: #10b981;
}

.stat-card-blue .stat-decoration-dashboard {
    background: #3b82f6;
}

.stat-card-purple .stat-decoration-dashboard {
    background: #a855f7;
}

/* Section Headers */
.applications-section {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 1rem;
}

.section-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
    display: flex;
    align-items: center;
}

.section-subtitle {
    font-size: 0.875rem;
    color: #64748b;
    margin: 0.5rem 0 0 0;
}

.application-count-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.4rem 1rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.85rem;
}

/* Empty State */
.empty-state-dashboard {
    text-align: center;
    padding: 4rem 2rem;
}

.empty-state-icon-dashboard {
    width: 80px;
    height: 80px;
    margin: 0 auto 1.5rem;
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 40px;
    color: #059669;
}

.empty-state-title-dashboard {
    font-size: 1.5rem;
    font-weight: 700;
    color: #334155;
    margin-bottom: 0.75rem;
}

.empty-state-text-dashboard {
    color: #64748b;
    font-size: 1rem;
    max-width: 500px;
    margin: 0 auto 2rem;
}

.btn-empty-action {
    display: inline-flex;
    align-items: center;
    padding: 0.75rem 2rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
}

.btn-empty-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    color: white;
}

/* Applications Table */
.applications-table-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid #f1f5f9;
}

.modern-table-dashboard {
    margin: 0;
    border-collapse: separate;
    border-spacing: 0;
}

.modern-table-dashboard thead {
    background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
    border-bottom: 2px solid #cbd5e1;
}

.modern-table-dashboard thead th {
    color: #475569 !important;
    font-weight: 700;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    padding: 1rem;
    border: none;
    background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
    white-space: nowrap;
}

.modern-table-dashboard tbody {
    background: white;
}

.application-row {
    border-bottom: 1px solid #f1f5f9;
    transition: all 0.2s ease;
}

.application-row:hover {
    background: #f8fafc;
    transform: scale(1.001);
}

.application-row td {
    padding: 1.25rem 1rem;
    vertical-align: middle;
    border: none;
}

.row-number-dashboard {
    color: #94a3b8;
    font-weight: 600;
    font-size: 0.9rem;
}

/* Student Info with Avatar */
.student-info-dashboard {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.student-avatar-dashboard {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.1rem;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
}

.student-name-dashboard {
    font-weight: 600;
    color: #1e293b;
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
}

.student-meta-dashboard {
    font-size: 0.8rem;
}

.matric-no-dashboard {
    font-family: 'Courier New', monospace;
    font-weight: 600;
    color: #64748b;
    font-size: 0.8rem;
}

/* Submission Info */
.submission-info {
    line-height: 1.4;
}

.submission-date {
    font-weight: 600;
    color: #475569;
    font-size: 0.875rem;
}

.submission-time {
    font-size: 0.75rem;
    color: #94a3b8;
    margin-top: 0.2rem;
}

/* Program Badge */
.program-badge-dashboard {
    display: inline-block;
    padding: 0.35rem 0.75rem;
    background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
    color: #075985;
    border-radius: 6px;
    font-weight: 700;
    font-size: 0.8rem;
}

/* Status Badge */
.status-badge-dashboard {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.4rem 0.85rem;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
}

.status-submitted {
    background: #fef3c7;
    color: #92400e;
}

.course-count-info {
    margin-top: 0.35rem;
    font-size: 0.75rem;
    color: #94a3b8;
}

/* Review Button */
.btn-review-dashboard {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.875rem;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.2);
}

.btn-review-dashboard:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    color: white;
}

/* Responsive */
@media (max-width: 768px) {
    .dashboard-welcome-header {
        text-align: center;
        flex-direction: column;
    }

    .welcome-title {
        font-size: 1.25rem;
    }

    .stat-number-dashboard {
        font-size: 1.5rem;
    }

    .section-title {
        font-size: 1.1rem;
    }

    .student-meta-dashboard {
        display: block;
    }
}
</style>
@endsection
