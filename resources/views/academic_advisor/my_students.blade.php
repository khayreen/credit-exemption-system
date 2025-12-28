@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Page Header -->
    <div class="aa-page-header mb-4" id="dynamic-group-header">
        <div class="aa-header-icon">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <div class="aa-header-content">
            <h1 class="aa-header-title" id="header-program-name">
                @if(!empty($groupData))
                    {{ $groupData[array_key_first($groupData)]['name'] }}
                @else
                    MY STUDENTS
                @endif
            </h1>
            <p class="aa-header-subtitle" id="header-group-info">
                @if(!empty($groupData))
                    Group: {{ $groupData[array_key_first($groupData)]['code'] }} • {{ $groupData[array_key_first($groupData)]['count'] }} {{ Str::plural('Student', $groupData[array_key_first($groupData)]['count']) }}
                @else
                    No groups assigned
                @endif
            </p>
        </div>
    </div>

    <!-- Statistics Dashboard -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card stat-card-primary">
                <div class="stat-icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div class="stat-content">
                    <h2 class="stat-number">{{ $stats['total_students'] }}</h2>
                    <p class="stat-label">Total Students</p>
                </div>
                <div class="stat-decoration"></div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card stat-card-info">
                <div class="stat-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="stat-content">
                    <h2 class="stat-number">{{ $stats['total_applications'] }}</h2>
                    <p class="stat-label">Total Applications</p>
                </div>
                <div class="stat-decoration"></div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card stat-card-warning">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <h2 class="stat-number">{{ $stats['pending_applications'] }}</h2>
                    <p class="stat-label">Pending Review</p>
                </div>
                <div class="stat-decoration"></div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card stat-card-success">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h2 class="stat-number">{{ $stats['reviewed_applications'] }}</h2>
                    <p class="stat-label">Reviewed</p>
                </div>
                <div class="stat-decoration"></div>
            </div>
        </div>
    </div>

    @if(empty($groupData))
        <!-- Empty State -->
        <div class="empty-state-card">
            <div class="empty-state-content">
                <div class="empty-state-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h4 class="empty-state-title">No Students Assigned</h4>
                <p class="empty-state-text">You don't have any student groups assigned to you yet. Please contact the administrator if this seems incorrect.</p>
            </div>
        </div>
    @else
        <!-- Group Navigation Tabs -->
        <div class="groups-section">
            <div class="nav-pills-wrapper mb-4">
                <ul class="nav nav-pills group-pills" id="groupTabs" role="tablist">
                    @foreach($groupData as $index => $group)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link group-pill {{ $loop->first ? 'active' : '' }}"
                                id="{{ strtolower($group['code']) }}-tab"
                                data-bs-toggle="tab"
                                data-bs-target="#{{ strtolower($group['code']) }}"
                                type="button"
                                role="tab">
                            <span class="pill-code">{{ $group['code'] }}</span>
                            <span class="pill-badge">{{ $group['count'] }}</span>
                        </button>
                    </li>
                    @endforeach
                </ul>
            </div>

            <div class="tab-content" id="groupTabsContent">
                @foreach($groupData as $index => $group)
                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                     id="{{ strtolower($group['code']) }}"
                     role="tabpanel">


                    @if($group['students']->isEmpty())
                        <div class="alert-info-modern">
                            <i class="fas fa-info-circle"></i>
                            <div>
                                <strong>No Students Yet</strong>
                                <p>No students have been enrolled in this group yet.</p>
                            </div>
                        </div>
                    @else
                        <!-- Search Bar -->
                        <div class="search-wrapper mb-4">
                            <div class="search-input-group">
                                <i class="fas fa-search search-icon"></i>
                                <input type="text"
                                       class="form-control search-input"
                                       placeholder="Search by name, matric number, or email..."
                                       data-program="{{ strtolower($group['code']) }}">
                            </div>
                        </div>

                        <!-- Students Table -->
                        <div class="students-table-card">
                            <table class="table students-table modern-table" id="table-{{ strtolower($group['code']) }}">
                                <thead>
                                    <tr>
                                        <th style="width: 4%">#</th>
                                        <th style="width: 28%">Student</th>
                                        <th style="width: 18%">Campus</th>
                                        <th style="width: 8%">Intake</th>
                                        <th style="width: 22%">Application Status</th>
                                        <th style="width: 20%" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($group['students'] as $student)
                                    <tr class="student-row">
                                        <td class="row-number">{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="student-info">
                                                <div class="student-avatar">
                                                    {{ strtoupper(substr($student['name'], 0, 1)) }}
                                                </div>
                                                <div class="student-details">
                                                    <div class="student-name">{{ $student['name'] }}</div>
                                                    <div class="student-meta">
                                                        <span class="matric-no">{{ $student['matric_no'] }}</span>
                                                        <span class="meta-separator">•</span>
                                                        <span class="student-email">{{ $student['email'] }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="campus-info">
                                                <i class="fas fa-map-marker-alt campus-icon"></i>
                                                <span>{{ $student['campus'] }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="intake-badge">{{ $student['intake_semester'] }}</span>
                                        </td>
                                        <td>
                                            @if($student['application_status'] === 'No Application')
                                                <span class="status-badge status-none">
                                                    <i class="fas fa-file-invoice"></i> No Application
                                                </span>
                                            @elseif($student['application_status'] === 'Submitted')
                                                <span class="status-badge status-pending">
                                                    <i class="fas fa-clock"></i> Pending Review
                                                </span>
                                            @elseif(str_contains($student['application_status'], 'Reviewed'))
                                                <span class="status-badge status-review">
                                                    <i class="fas fa-tasks"></i> {{ $student['application_status'] }}
                                                </span>
                                            @elseif($student['application_status'] === 'Approved')
                                                <span class="status-badge status-approved">
                                                    <i class="fas fa-check-circle"></i> Approved
                                                </span>
                                            @elseif($student['application_status'] === 'Rejected')
                                                <span class="status-badge status-rejected">
                                                    <i class="fas fa-times-circle"></i> Rejected
                                                </span>
                                            @else
                                                <span class="status-badge status-other">
                                                    <i class="fas fa-info-circle"></i> {{ $student['application_status'] }}
                                                </span>
                                            @endif
                                            @if($student['application_date'])
                                                <div class="application-date">{{ $student['application_date']->format('d M Y') }}</div>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($student['application_id'])
                                                <a href="{{ route('academic_advisor.application.show', $student['application_id']) }}"
                                                   class="btn-view-modern">
                                                    <i class="fas fa-eye"></i> View Application
                                                </a>
                                            @else
                                                <span class="no-action">No application yet</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<style>
/* ========================================
   MODERN MY STUDENTS PAGE STYLING
   Professional & Friendly Academic UI
   ======================================== */

/* AA Page Header - Consistent across all AA pages */
.aa-page-header {
    background: linear-gradient(to right, #f8f9fa 0%, #ffffff 100%);
    border-left: 5px solid #667eea;
    padding: 1.75rem 1.5rem;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 1.25rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
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

/* Statistics Cards */
.stat-card {
    position: relative;
    padding: 1.5rem;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    background: white;
    border: none;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    flex-shrink: 0;
}

.stat-card-primary .stat-icon {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.stat-card-info .stat-icon {
    background: linear-gradient(135deg, #0dcaf0 0%, #0891b2 100%);
    color: white;
}

.stat-card-warning .stat-icon {
    background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
    color: white;
}

.stat-card-success .stat-icon {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.stat-content {
    flex: 1;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
    color: #1e293b;
    line-height: 1;
}

.stat-label {
    margin: 0.25rem 0 0 0;
    font-size: 0.9rem;
    color: #64748b;
    font-weight: 500;
}

.stat-decoration {
    position: absolute;
    right: -20px;
    bottom: -20px;
    width: 100px;
    height: 100px;
    border-radius: 50%;
    opacity: 0.05;
}

.stat-card-primary .stat-decoration {
    background: #667eea;
}

.stat-card-info .stat-decoration {
    background: #0dcaf0;
}

.stat-card-warning .stat-decoration {
    background: #ffc107;
}

.stat-card-success .stat-decoration {
    background: #10b981;
}

/* Empty State */
.empty-state-card {
    background: white;
    border-radius: 12px;
    padding: 4rem 2rem;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.empty-state-icon {
    width: 100px;
    height: 100px;
    margin: 0 auto 1.5rem;
    background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 48px;
    color: #94a3b8;
}

.empty-state-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #334155;
    margin-bottom: 0.5rem;
}

.empty-state-text {
    color: #64748b;
    font-size: 1rem;
    max-width: 500px;
    margin: 0 auto;
}

/* Modern Pill Navigation */
.nav-pills-wrapper {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.group-pills {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    border: none;
}

.group-pill {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
    border-radius: 50px;
    background: #f8fafc;
    border: 2px solid transparent;
    color: #475569;
    font-weight: 600;
    transition: all 0.3s ease;
    font-size: 0.95rem;
}

.group-pill:hover {
    background: #f1f5f9;
    border-color: #cbd5e1;
    color: #1e293b;
    transform: translateY(-2px);
}

.group-pill.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-color: #667eea;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.pill-code {
    font-weight: 700;
}

.pill-badge {
    background: rgba(255, 255, 255, 0.3);
    padding: 0.2rem 0.6rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

.group-pill.active .pill-badge {
    background: rgba(255, 255, 255, 0.25);
}


/* Search Bar */
.search-wrapper {
    background: white;
    padding: 1rem;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.search-input-group {
    position: relative;
}

.search-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 1rem;
}

.search-input {
    padding: 0.75rem 1rem 0.75rem 3rem;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

.search-input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

/* Modern Alert */
.alert-info-modern {
    background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
    border: none;
    border-radius: 12px;
    padding: 1.5rem;
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    color: #075985;
}

.alert-info-modern i {
    font-size: 24px;
    margin-top: 0.2rem;
}

.alert-info-modern strong {
    display: block;
    margin-bottom: 0.25rem;
    font-size: 1.1rem;
}

.alert-info-modern p {
    margin: 0;
}

/* Students Table Card */
.students-table-card {
    background: white;
    border-radius: 12px;
    overflow: visible;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.modern-table {
    margin: 0;
    border-collapse: separate;
    border-spacing: 0;
}

.modern-table thead {
    background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
    position: sticky;
    top: 0;
    z-index: 10;
    border-bottom: 2px solid #cbd5e1;
}

.modern-table thead th {
    color: #475569 !important;
    font-weight: 700;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    padding: 1rem 1rem;
    border: none;
    background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
    white-space: nowrap;
}

.modern-table thead th:first-child {
    border-top-left-radius: 12px;
}

.modern-table thead th:last-child {
    border-top-right-radius: 12px;
}

.modern-table tbody {
    background: white;
}

.student-row {
    border-bottom: 1px solid #f1f5f9;
    transition: all 0.2s ease;
}

.student-row:hover {
    background: #f8fafc;
    transform: scale(1.002);
}

.student-row td {
    padding: 1.25rem 1rem;
    vertical-align: middle;
    border: none;
}

.row-number {
    color: #94a3b8;
    font-weight: 600;
    font-size: 0.9rem;
}

/* Student Info with Avatar */
.student-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.student-avatar {
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

.student-name {
    font-weight: 600;
    color: #1e293b;
    font-size: 0.95rem;
    margin-bottom: 0.25rem;
}

.student-meta {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    color: #64748b;
}

.matric-no {
    font-family: 'Courier New', monospace;
    font-weight: 600;
    color: #475569;
}

.meta-separator {
    color: #cbd5e1;
}

.student-email {
    color: #64748b;
}

/* Campus Info */
.campus-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #475569;
    font-size: 0.9rem;
}

.campus-icon {
    color: #94a3b8;
}

/* Intake Badge */
.intake-badge {
    display: inline-block;
    padding: 0.35rem 0.75rem;
    background: #f1f5f9;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.9rem;
    color: #475569;
}

/* Status Badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-size: 0.85rem;
    font-weight: 600;
    white-space: nowrap;
}

.status-badge i {
    font-size: 0.9rem;
}

.status-none {
    background: #f1f5f9;
    color: #64748b;
}

.status-pending {
    background: #fef3c7;
    color: #92400e;
}

.status-review {
    background: #dbeafe;
    color: #1e40af;
}

.status-approved {
    background: #d1fae5;
    color: #065f46;
}

.status-rejected {
    background: #fee2e2;
    color: #991b1b;
}

.status-other {
    background: #f3e8ff;
    color: #6b21a8;
}

.application-date {
    margin-top: 0.35rem;
    font-size: 0.8rem;
    color: #94a3b8;
}

/* View Button */
.btn-view-modern {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.6rem 1.25rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.9rem;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.2);
}

.btn-view-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    color: white;
}

.no-action {
    color: #cbd5e1;
    font-size: 0.85rem;
    font-style: italic;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .stat-number {
        font-size: 1.5rem;
    }

    .aa-header-title {
        font-size: 1.1rem;
    }

    .aa-header-subtitle {
        font-size: 0.85rem;
    }

    .aa-header-icon {
        width: 40px;
        height: 40px;
        font-size: 20px;
    }

    .student-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
    }

    .meta-separator {
        display: none;
    }

    .group-pills {
        justify-content: center;
    }

    .btn-view-modern {
        font-size: 0.8rem;
        padding: 0.5rem 0.9rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Group data for dynamic header updates
    const groupData = {
        @foreach($groupData as $code => $data)
        '{{ strtolower($code) }}': {
            name: '{{ $data['name'] }}',
            code: '{{ $data['code'] }}',
            count: {{ $data['count'] }}
        },
        @endforeach
    };

    // Update header when tab is clicked
    const groupTabs = document.querySelectorAll('.group-pill');
    groupTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const targetId = this.getAttribute('data-bs-target').replace('#', '');
            const groupInfo = groupData[targetId];

            if (groupInfo) {
                const titleElement = document.getElementById('header-program-name');
                const subtitleElement = document.getElementById('header-group-info');

                if (titleElement) titleElement.textContent = groupInfo.name;
                if (subtitleElement) {
                    subtitleElement.textContent = `Group: ${groupInfo.code} • ${groupInfo.count} ${groupInfo.count === 1 ? 'Student' : 'Students'}`;
                }
            }
        });
    });

    // Search functionality for each program tab
    const searchInputs = document.querySelectorAll('.search-input');

    searchInputs.forEach(input => {
        input.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const programCode = this.dataset.program;
            const table = document.getElementById('table-' + programCode);
            const rows = table.querySelectorAll('tbody .student-row');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
});
</script>
@endsection
