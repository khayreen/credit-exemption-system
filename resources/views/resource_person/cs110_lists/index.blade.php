@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
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

    body { font-family: 'IBM Plex Sans', sans-serif; }
    .font-mono { font-family: 'IBM Plex Mono', monospace; }

    /* Page Header */
    .page-header {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, #1e40af 100%);
        border-radius: 12px;
        padding: 1.75rem;
        margin-bottom: 1.5rem;
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 300px;
        height: 100%;
        background: linear-gradient(135deg, transparent 0%, rgba(245,158,11,0.1) 100%);
        pointer-events: none;
    }

    .page-header h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: white;
        margin: 0;
        letter-spacing: -0.025em;
    }

    .page-header p {
        color: rgba(255,255,255,0.85);
        margin: 0.35rem 0 0 0;
        font-size: 0.9rem;
    }

    /* Program Card */
    .program-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .program-card-header {
        padding: 1.25rem;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .program-card-header.draft { background: linear-gradient(135deg, var(--industrial-gray) 0%, #1e293b 100%); }
    .program-card-header.submitted { background: linear-gradient(135deg, var(--info) 0%, #0f766e 100%); }
    .program-card-header.endorsed { background: linear-gradient(135deg, var(--success) 0%, #047857 100%); }
    .program-card-header.published { background: linear-gradient(135deg, var(--uitm-blue) 0%, #1e40af 100%); }

    .program-card-header h5 {
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .program-card-body {
        padding: 1.25rem;
    }

    /* Stats Row */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 1.25rem;
    }

    .stat-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 1rem;
        text-align: center;
    }

    .stat-card h3 {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--industrial-dark);
        margin: 0;
    }

    .stat-card small {
        color: var(--industrial-gray);
        font-size: 0.8rem;
    }

    .stat-card.primary { border-color: var(--uitm-blue); }
    .stat-card.success { border-color: var(--success); }
    .stat-card.info { border-color: var(--info); }
    .stat-card.warning { border-color: var(--uitm-amber); }

    /* Status Badges */
    .status-badge {
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .status-badge.editable { background: rgba(51,65,85,0.15); color: var(--industrial-gray); }
    .status-badge.awaiting { background: rgba(13,148,136,0.15); color: var(--info); }
    .status-badge.under-review { background: rgba(30,58,138,0.15); color: var(--uitm-blue); }
    .status-badge.endorsed { background: rgba(5,150,105,0.15); color: var(--success); }

    /* Action Row */
    .action-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #e2e8f0;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    /* Mappings Table */
    .mappings-section {
        margin-top: 1.5rem;
    }

    .mappings-section h6 {
        font-weight: 600;
        color: var(--industrial-dark);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .mappings-table {
        font-size: 0.875rem;
        margin: 0;
    }

    .mappings-table thead th {
        background: var(--industrial-light);
        font-weight: 600;
        color: var(--industrial-dark);
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0.75rem;
        border-bottom: 2px solid #e2e8f0;
    }

    .mappings-table tbody td {
        padding: 0.75rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .mappings-table tbody tr:hover {
        background: #fafbfc;
    }

    .course-code {
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 600;
        color: var(--uitm-blue);
    }

    .match-badge {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.8rem;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-weight: 600;
    }

    .match-badge.high { background: rgba(5,150,105,0.15); color: var(--success); }
    .match-badge.low { background: rgba(245,158,11,0.15); color: var(--warning); }

    .eligible-badge {
        padding: 0.2rem 0.5rem;
        border-radius: 4px;
        font-size: 0.7rem;
        font-weight: 600;
    }

    .eligible-badge.yes { background: rgba(5,150,105,0.15); color: var(--success); }
    .eligible-badge.no { background: rgba(51,65,85,0.15); color: var(--industrial-gray); }

    /* History Alert */
    .history-alert {
        background: rgba(13,148,136,0.08);
        border: 1px solid rgba(13,148,136,0.2);
        border-left: 4px solid var(--info);
        border-radius: 8px;
        padding: 0.875rem 1rem;
        margin-top: 1rem;
    }

    /* Info Box */
    .info-box {
        background: white;
        border-radius: 12px;
        border: 2px solid var(--uitm-blue);
        overflow: hidden;
        margin-top: 1.5rem;
    }

    .info-box-header {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, #1e40af 100%);
        color: white;
        padding: 1rem 1.25rem;
    }

    .info-box-header h6 {
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-box-body {
        padding: 1.25rem;
    }

    .info-box-body h6 {
        color: var(--uitm-blue);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .info-box-body ul {
        margin: 0;
        padding-left: 1.25rem;
        font-size: 0.875rem;
        color: var(--industrial-gray);
    }

    .info-box-body ul li {
        margin-bottom: 0.25rem;
    }

    /* Empty State */
    .empty-state {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        padding: 4rem 2rem;
        text-align: center;
    }

    .empty-state i {
        font-size: 4rem;
        color: #cbd5e1;
        margin-bottom: 1rem;
    }

    .empty-state h4 {
        color: var(--industrial-gray);
        font-weight: 600;
    }

    .empty-state p {
        color: #94a3b8;
    }

    /* Buttons */
    .btn-industrial {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .btn-success-industrial {
        background: var(--success);
        color: white;
        border: none;
    }

    .btn-success-industrial:hover {
        background: #047857;
        color: white;
        transform: translateY(-1px);
    }

    @media (max-width: 768px) {
        .stats-row {
            grid-template-columns: repeat(2, 1fr);
        }

        .page-header {
            padding: 1.25rem;
        }

        .page-header h2 {
            font-size: 1.25rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Page Header -->
    <div class="page-header">
        <h2><i class="fas fa-building me-2"></i>CS110 Internal Equivalency Lists</h2>
        <p>
            <i class="fas fa-info-circle me-1"></i>ONE continuous list per program - Edit anytime, submit for each semester
        </p>
        @if(!empty($assignedPrograms))
            <p class="mt-2">
                <strong>Assigned Programs:</strong>
                @foreach($assignedPrograms as $program)
                    <span class="badge bg-white text-primary fw-bold ms-1">{{ $program }}</span>
                @endforeach
            </p>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" style="border-left: 4px solid var(--success) !important;">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" style="border-left: 4px solid var(--danger) !important;">
            <i class="fas fa-exclamation-triangle me-2"></i>
            @foreach($errors->all() as $error)
                {{ $error }}
            @endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Program Lists -->
    @foreach($programLists as $programCode => $data)
        <div class="program-card">
            <div class="program-card-header {{ $data['status'] }}">
                <h5>
                    <i class="fas fa-graduation-cap"></i>
                    <span class="font-mono">{{ $programCode }}</span> - {{ $data['program_name'] }}
                </h5>
                <div class="text-end">
                    @if($data['list'])
                        @if($data['list']->published_at)
                            <span class="badge bg-light text-success fw-bold">Published</span>
                            <br><small>{{ $data['list']->published_at->format('d M Y') }}</small>
                        @else
                            <span class="badge bg-light text-dark fw-bold">
                                Status: {{ $data['list']->status_display }}
                            </span>
                        @endif
                    @else
                        <span class="badge bg-light text-dark fw-bold">Not Created</span>
                    @endif
                </div>
            </div>
            <div class="program-card-body">
                <div class="stats-row">
                    <div class="stat-card primary">
                        <h3>{{ $data['total_mappings'] }}</h3>
                        <small>Course Mappings</small>
                    </div>
                    <div class="stat-card success">
                        <h3>{{ $data['last_semester'] ?? '-' }}</h3>
                        <small>Last Published</small>
                    </div>
                    <div class="stat-card info">
                        <h3>
                            @if($data['last_published'])
                                {{ $data['last_published']->format('d M Y') }}
                            @else
                                Never
                            @endif
                        </h3>
                        <small>Last Publication Date</small>
                    </div>
                    <div class="stat-card warning">
                        @if($data['list'] && $data['list']->target_semester)
                            <h3>{{ $data['list']->target_semester }}</h3>
                            <small>Target Semester</small>
                        @else
                            <h3>-</h3>
                            <small>No Target Set</small>
                        @endif
                    </div>
                </div>

                <div class="action-row">
                    <div>
                        @if($data['list'])
                            @if($data['status'] === 'draft' || $data['status'] === 'rejected')
                                <span class="status-badge editable">
                                    <i class="fas fa-pencil-alt me-1"></i>Editable
                                </span>
                            @endif
                            @if($data['status'] === 'submitted')
                                <span class="status-badge awaiting">
                                    <i class="fas fa-hourglass-half me-1"></i>Awaiting HEA Review
                                </span>
                            @endif
                            @if($data['status'] === 'under_review')
                                <span class="status-badge under-review">
                                    <i class="fas fa-search me-1"></i>Under HEA Review
                                </span>
                            @endif
                            @if($data['status'] === 'endorsed')
                                <span class="status-badge endorsed">
                                    <i class="fas fa-check-circle me-1"></i>Endorsed - Awaiting Publication
                                </span>
                            @endif
                        @endif
                    </div>
                    <div>
                        <a href="{{ route('resource_person.equivalency_lists.edit', $programCode) }}" class="btn btn-success-industrial btn-industrial">
                            <i class="fas fa-edit me-1"></i>Edit CS110 List
                        </a>
                    </div>
                </div>

                <!-- Course Mappings Table -->
                @if($data['list'] && $data['list']->courseEquivalencies->count() > 0)
                    <div class="mappings-section">
                        <h6><i class="fas fa-list"></i>Course Mappings ({{ $data['list']->courseEquivalencies->count() }})</h6>
                        <div class="table-responsive">
                            <table class="table mappings-table">
                                <thead>
                                    <tr>
                                        <th>CS110 Diploma Course</th>
                                        <th class="text-center">Credit</th>
                                        <th>{{ $programCode }} Degree Course</th>
                                        <th class="text-center">Credit</th>
                                        <th class="text-center">Match %</th>
                                        <th class="text-center">Eligible</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data['list']->courseEquivalencies->sortBy('diploma_course_code') as $equiv)
                                    <tr>
                                        <td>
                                            <span class="course-code">{{ $equiv->diploma_course_code }}</span><br>
                                            <small class="text-muted">{{ Str::limit($equiv->diploma_course_name, 40) }}</small>
                                        </td>
                                        <td class="text-center font-mono">{{ $equiv->diploma_credit_hour }}</td>
                                        <td>
                                            <span class="course-code">{{ $equiv->degree_course_code }}</span><br>
                                            <small class="text-muted">{{ Str::limit($equiv->degree_course_name, 40) }}</small>
                                        </td>
                                        <td class="text-center font-mono">{{ $equiv->degree_credit_hour }}</td>
                                        <td class="text-center">
                                            <span class="match-badge {{ $equiv->match_percentage >= 80 ? 'high' : 'low' }}">
                                                {{ number_format($equiv->match_percentage, 0) }}%
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if($equiv->is_eligible)
                                                <span class="eligible-badge yes">Yes</span>
                                            @else
                                                <span class="eligible-badge no">No</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                @if($data['list'] && $data['list']->courseEquivalencyHistory->count() > 0)
                    <div class="history-alert">
                        <i class="fas fa-history me-2"></i>
                        <strong>History:</strong> {{ $data['list']->courseEquivalencyHistory->count() }} archived course mappings
                    </div>
                @endif
            </div>
        </div>
    @endforeach

    @if(empty($programLists))
        <div class="empty-state">
            <i class="fas fa-graduation-cap"></i>
            <h4>No Programs Assigned</h4>
            <p>You don't have any programs assigned yet. Contact HEA for program assignment.</p>
        </div>
    @endif

    <!-- Info Box -->
    <div class="info-box">
        <div class="info-box-header">
            <h6><i class="fas fa-info-circle"></i>How CS110 Lists Work</h6>
        </div>
        <div class="info-box-body">
            <div class="row">
                <div class="col-md-6 mb-3 mb-md-0">
                    <h6><i class="fas fa-pencil-alt me-2"></i>Continuous Editing</h6>
                    <ul>
                        <li>ONE list per program (e.g., CDCS251)</li>
                        <li>Edit anytime to add/update/delete mappings</li>
                        <li>Changes are tracked in history</li>
                        <li>Always in DRAFT status when editable</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6 style="color: var(--success);"><i class="fas fa-book me-2"></i>Semester Publication</h6>
                    <ul>
                        <li>Submit to HEA when ready for semester publication</li>
                        <li>Specify target semester (e.g., 2025/2026-1)</li>
                        <li>HEA reviews, endorses, and publishes PDF</li>
                        <li>After publication, reverts to DRAFT for next semester</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
