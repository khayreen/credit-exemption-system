@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">
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
        --info: #0d9488;
    }

    body {
        font-family: 'IBM Plex Sans', sans-serif;
        background-color: var(--industrial-light);
    }

    .page-header {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--industrial-dark) 100%);
        border-radius: 0 0 24px 24px;
        padding: 2rem 2.5rem;
        margin: -1.5rem -1.5rem 2rem -1.5rem;
        color: white;
        display: flex;
        align-items: center;
        gap: 1.25rem;
    }

    .header-icon {
        width: 56px;
        height: 56px;
        background: rgba(255,255,255,0.15);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .header-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .header-subtitle {
        font-size: 0.95rem;
        color: rgba(255,255,255,0.8);
        margin: 0.35rem 0 0 0;
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.1);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        flex-shrink: 0;
    }

    .stat-card.primary .stat-icon {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--uitm-blue-light) 100%);
    }

    .stat-card.info .stat-icon {
        background: linear-gradient(135deg, var(--info) 0%, #14b8a6 100%);
    }

    .stat-card.warning .stat-icon {
        background: linear-gradient(135deg, var(--warning) 0%, #f97316 100%);
    }

    .stat-card.success .stat-icon {
        background: linear-gradient(135deg, var(--success) 0%, #10b981 100%);
    }

    .stat-number {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 2rem;
        font-weight: 700;
        color: var(--industrial-dark);
        line-height: 1;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.85rem;
        color: var(--industrial-gray);
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

    .stat-card.primary .stat-decoration { background: var(--uitm-blue); }
    .stat-card.info .stat-decoration { background: var(--info); }
    .stat-card.warning .stat-decoration { background: var(--warning); }
    .stat-card.success .stat-decoration { background: var(--success); }

    /* Empty State */
    .empty-state-card {
        background: white;
        border-radius: 16px;
        padding: 4rem 2rem;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    }

    .empty-state-icon {
        width: 100px;
        height: 100px;
        margin: 0 auto 1.5rem;
        background: linear-gradient(135deg, var(--industrial-light) 0%, #e2e8f0 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: #94a3b8;
    }

    .empty-state-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--industrial-dark);
        margin-bottom: 0.5rem;
    }

    .empty-state-text {
        color: var(--industrial-gray);
        max-width: 500px;
        margin: 0 auto;
    }

    /* Program Tabs */
    .program-tabs-wrapper {
        background: white;
        padding: 1rem;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        margin-bottom: 1.5rem;
    }

    .program-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .program-pill {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.25rem;
        border-radius: 50px;
        background: var(--industrial-light);
        border: 2px solid transparent;
        color: var(--industrial-gray);
        font-weight: 600;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .program-pill:hover {
        background: #e2e8f0;
        border-color: #cbd5e1;
        color: var(--uitm-blue);
        transform: translateY(-2px);
    }

    .program-pill.active {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--uitm-blue-light) 100%);
        color: white;
        border-color: var(--uitm-blue);
        box-shadow: 0 4px 15px rgba(30, 58, 138, 0.3);
    }

    .pill-code {
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 700;
    }

    .pill-badge {
        background: rgba(255,255,255,0.25);
        padding: 0.2rem 0.6rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .program-pill:not(.active) .pill-badge {
        background: rgba(51, 65, 85, 0.1);
    }

    /* Search Bar */
    .search-wrapper {
        background: white;
        padding: 1rem;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.04);
        margin-bottom: 1.5rem;
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
    }

    .search-input {
        width: 100%;
        padding: 0.875rem 1rem 0.875rem 3rem;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--uitm-blue);
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
    }

    /* Alert */
    .alert-info-industrial {
        background: linear-gradient(135deg, rgba(13, 148, 136, 0.1) 0%, rgba(20, 184, 166, 0.1) 100%);
        border: 1px solid rgba(13, 148, 136, 0.2);
        border-radius: 12px;
        padding: 1.25rem;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        color: var(--info);
    }

    .alert-info-industrial i {
        font-size: 1.5rem;
        margin-top: 0.15rem;
    }

    .alert-info-industrial strong {
        display: block;
        margin-bottom: 0.25rem;
        font-size: 1.05rem;
    }

    .alert-info-industrial p {
        margin: 0;
        opacity: 0.9;
    }

    /* Students Table */
    .students-table-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    }

    .students-table {
        width: 100%;
        margin: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .students-table thead {
        background: linear-gradient(135deg, var(--industrial-light) 0%, #e2e8f0 100%);
    }

    .students-table thead th {
        color: var(--industrial-gray);
        font-weight: 700;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        padding: 1rem;
        border: none;
        border-bottom: 2px solid var(--uitm-blue);
    }

    .students-table tbody tr {
        border-bottom: 1px solid #f1f5f9;
        transition: all 0.2s ease;
    }

    .students-table tbody tr:hover {
        background: rgba(30, 58, 138, 0.02);
    }

    .students-table tbody td {
        padding: 1.25rem 1rem;
        vertical-align: middle;
        border: none;
    }

    .row-number {
        color: #94a3b8;
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 600;
        font-size: 0.9rem;
    }

    /* Student Info */
    .student-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .student-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--uitm-blue-light) 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.1rem;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(30, 58, 138, 0.25);
    }

    .student-name {
        font-weight: 600;
        color: var(--industrial-dark);
        margin-bottom: 0.25rem;
    }

    .student-meta {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        color: var(--industrial-gray);
    }

    .matric-no {
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 600;
        color: var(--uitm-blue);
    }

    .meta-separator {
        color: #cbd5e1;
    }

    /* Campus Info */
    .campus-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--industrial-gray);
        font-size: 0.9rem;
    }

    .campus-icon {
        color: var(--uitm-amber);
    }

    /* Intake Badge */
    .intake-badge {
        display: inline-block;
        padding: 0.4rem 0.875rem;
        background: var(--industrial-light);
        border-radius: 8px;
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 600;
        font-size: 0.85rem;
        color: var(--industrial-dark);
    }

    /* Status Badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .status-none {
        background: var(--industrial-light);
        color: var(--industrial-gray);
    }

    .status-pending {
        background: rgba(234, 88, 12, 0.1);
        color: var(--warning);
        border: 1px solid rgba(234, 88, 12, 0.2);
    }

    .status-review {
        background: rgba(30, 58, 138, 0.1);
        color: var(--uitm-blue);
        border: 1px solid rgba(30, 58, 138, 0.2);
    }

    .status-approved {
        background: rgba(5, 150, 105, 0.1);
        color: var(--success);
        border: 1px solid rgba(5, 150, 105, 0.2);
    }

    .status-rejected {
        background: rgba(220, 38, 38, 0.1);
        color: var(--danger);
        border: 1px solid rgba(220, 38, 38, 0.2);
    }

    .status-other {
        background: rgba(13, 148, 136, 0.1);
        color: var(--info);
        border: 1px solid rgba(13, 148, 136, 0.2);
    }

    .application-date {
        margin-top: 0.35rem;
        font-size: 0.75rem;
        font-family: 'IBM Plex Mono', monospace;
        color: #94a3b8;
    }

    /* View Button */
    .btn-view {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--uitm-blue-light) 100%);
        color: white;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.85rem;
        text-decoration: none;
        transition: all 0.2s ease;
        border: none;
    }

    .btn-view:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(30, 58, 138, 0.3);
        color: white;
    }

    .no-action {
        color: #cbd5e1;
        font-size: 0.85rem;
        font-style: italic;
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
            margin: -1rem -1rem 1.5rem -1rem;
            border-radius: 0 0 16px 16px;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .stat-number {
            font-size: 1.5rem;
        }

        .program-pills {
            justify-content: center;
        }

        .student-meta {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.25rem;
        }

        .meta-separator {
            display: none;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Page Header -->
    <div class="page-header" id="dynamic-group-header">
        <div class="header-icon">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <div>
            <h1 class="header-title" id="header-program-name">
                @if(!empty($groupData))
                    {{ $groupData[array_key_first($groupData)]['name'] }}
                @else
                    MY STUDENTS
                @endif
            </h1>
            <p class="header-subtitle" id="header-group-info">
                @if(!empty($groupData))
                    Group: {{ $groupData[array_key_first($groupData)]['code'] }} • {{ $groupData[array_key_first($groupData)]['count'] }} {{ Str::plural('Student', $groupData[array_key_first($groupData)]['count']) }}
                @else
                    No groups assigned
                @endif
            </p>
        </div>
    </div>

    <!-- Statistics Dashboard -->
    <div class="stats-grid">
        <div class="stat-card primary">
            <div class="stat-icon">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div>
                <div class="stat-number">{{ $stats['total_students'] }}</div>
                <div class="stat-label">Total Students</div>
            </div>
            <div class="stat-decoration"></div>
        </div>
        <div class="stat-card info">
            <div class="stat-icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <div>
                <div class="stat-number">{{ $stats['total_applications'] }}</div>
                <div class="stat-label">Total Applications</div>
            </div>
            <div class="stat-decoration"></div>
        </div>
        <div class="stat-card warning">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div>
                <div class="stat-number">{{ $stats['pending_applications'] }}</div>
                <div class="stat-label">Pending Review</div>
            </div>
            <div class="stat-decoration"></div>
        </div>
        <div class="stat-card success">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div>
                <div class="stat-number">{{ $stats['reviewed_applications'] }}</div>
                <div class="stat-label">Reviewed</div>
            </div>
            <div class="stat-decoration"></div>
        </div>
    </div>

    @if(empty($groupData))
        <!-- Empty State -->
        <div class="empty-state-card">
            <div class="empty-state-icon">
                <i class="fas fa-users"></i>
            </div>
            <h4 class="empty-state-title">No Students Assigned</h4>
            <p class="empty-state-text">You don't have any student groups assigned to you yet. Please contact the administrator if this seems incorrect.</p>
        </div>
    @else
        <!-- Group Navigation Tabs -->
        <div class="program-tabs-wrapper">
            <div class="program-pills" id="groupTabs" role="tablist">
                @foreach($groupData as $index => $group)
                <button class="program-pill {{ $loop->first ? 'active' : '' }}"
                        id="{{ strtolower($group['code']) }}-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#{{ strtolower($group['code']) }}"
                        type="button"
                        role="tab">
                    <span class="pill-code">{{ $group['code'] }}</span>
                    <span class="pill-badge">{{ $group['count'] }}</span>
                </button>
                @endforeach
            </div>
        </div>

        <div class="tab-content" id="groupTabsContent">
            @foreach($groupData as $index => $group)
            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                 id="{{ strtolower($group['code']) }}"
                 role="tabpanel">

                @if($group['students']->isEmpty())
                    <div class="alert-info-industrial">
                        <i class="fas fa-info-circle"></i>
                        <div>
                            <strong>No Students Yet</strong>
                            <p>No students have been enrolled in this group yet.</p>
                        </div>
                    </div>
                @else
                    <!-- Search Bar -->
                    <div class="search-wrapper">
                        <div class="search-input-group">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text"
                                   class="search-input"
                                   placeholder="Search by name, matric number, or email..."
                                   data-program="{{ strtolower($group['code']) }}">
                        </div>
                    </div>

                    <!-- Students Table -->
                    <div class="students-table-card">
                        <table class="students-table" id="table-{{ strtolower($group['code']) }}">
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
                                            <div>
                                                <div class="student-name">{{ $student['name'] }}</div>
                                                <div class="student-meta">
                                                    <span class="matric-no">{{ $student['matric_no'] }}</span>
                                                    <span class="meta-separator">•</span>
                                                    <span>{{ $student['email'] }}</span>
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
                                               class="btn-view">
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
    @endif
</div>

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
    const groupTabs = document.querySelectorAll('.program-pill');
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
