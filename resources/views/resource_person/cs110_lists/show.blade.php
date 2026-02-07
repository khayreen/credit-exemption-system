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
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .page-header h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--industrial-dark);
        margin: 0;
    }

    .page-header-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }

    /* Status Badges */
    .status-badge {
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .status-badge.primary { background: var(--uitm-blue); color: white; }
    .status-badge.info { background: var(--info); color: white; }
    .status-badge.success { background: var(--success); color: white; }
    .status-badge.draft { background: var(--industrial-gray); color: white; }
    .status-badge.rejected { background: var(--danger); color: white; }

    /* Info Card */
    .info-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .info-card-header {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, #1e40af 100%);
        padding: 1rem 1.25rem;
        color: white;
    }

    .info-card-header h5 {
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-card-body {
        padding: 1.25rem;
    }

    .info-table {
        margin: 0;
    }

    .info-table th {
        color: var(--industrial-gray);
        font-weight: 500;
        padding: 0.5rem 0;
        width: 140px;
        font-size: 0.9rem;
    }

    .info-table td {
        padding: 0.5rem 0;
    }

    /* Alert Boxes */
    .alert-success-custom {
        background: rgba(5,150,105,0.08);
        border: 1px solid rgba(5,150,105,0.2);
        border-left: 4px solid var(--success);
        border-radius: 8px;
        padding: 1rem 1.25rem;
        margin-top: 1rem;
    }

    .alert-danger-custom {
        background: rgba(220,38,38,0.08);
        border: 1px solid rgba(220,38,38,0.2);
        border-left: 4px solid var(--danger);
        border-radius: 8px;
        padding: 1rem 1.25rem;
        margin-top: 1rem;
    }

    .alert-info-custom {
        background: rgba(59,130,246,0.08);
        border: 1px solid rgba(59,130,246,0.2);
        border-left: 4px solid var(--uitm-blue-light);
        border-radius: 8px;
        padding: 1rem 1.25rem;
        margin-top: 1rem;
    }

    /* Mappings Card */
    .mappings-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .mappings-header {
        background: linear-gradient(135deg, var(--success) 0%, #047857 100%);
        padding: 1rem 1.25rem;
        color: white;
    }

    .mappings-header h5 {
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .mappings-body {
        padding: 0;
    }

    .mappings-table {
        margin: 0;
    }

    .mappings-table thead th {
        background: var(--industrial-light);
        font-weight: 600;
        color: var(--industrial-dark);
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0.875rem 1rem;
        border-bottom: 2px solid #e2e8f0;
    }

    .mappings-table tbody td {
        padding: 1rem;
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

    .course-name {
        color: var(--industrial-gray);
        font-size: 0.85rem;
    }

    .match-badge {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.8rem;
        padding: 0.3rem 0.6rem;
        border-radius: 4px;
        font-weight: 600;
    }

    .match-badge.high { background: rgba(5,150,105,0.15); color: var(--success); }
    .match-badge.low { background: rgba(245,158,11,0.15); color: var(--warning); }

    .eligible-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .eligible-badge.yes { background: rgba(5,150,105,0.15); color: var(--success); }
    .eligible-badge.no { background: rgba(51,65,85,0.15); color: var(--industrial-gray); }

    /* Stats Row */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .stat-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 1.25rem;
        text-align: center;
    }

    .stat-card h3 {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--industrial-dark);
        margin: 0;
    }

    .stat-card small {
        color: var(--industrial-gray);
        font-size: 0.85rem;
    }

    .stat-card.primary { border-color: var(--uitm-blue); }
    .stat-card.success { border-color: var(--success); }
    .stat-card.secondary { border-color: var(--industrial-gray); }

    /* Empty State */
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
    }

    .empty-state i {
        font-size: 3rem;
        color: #cbd5e1;
        margin-bottom: 1rem;
    }

    .empty-state h5 {
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

    .btn-primary-industrial {
        background: var(--uitm-blue);
        color: white;
        border: none;
    }

    .btn-primary-industrial:hover {
        background: #1e40af;
        color: white;
        transform: translateY(-1px);
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
        }

        .stats-row {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2><i class="fas fa-clipboard-list me-2" style="color: var(--uitm-blue);"></i>CS110 Equivalency List Details</h2>
            <div class="page-header-badges">
                <span class="status-badge primary font-mono">{{ $list->program_code }}</span>
                <span class="status-badge info">{{ $list->semester }}</span>
                @if($list->published_at)
                    <span class="status-badge success">Published</span>
                @else
                    <span class="status-badge {{ $list->status === 'rejected' ? 'rejected' : 'draft' }}">{{ $list->status_display }}</span>
                @endif
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('resource_person.equivalency_lists.index') }}" class="btn btn-outline-secondary btn-industrial">
                <i class="fas fa-arrow-left me-1"></i>Back to Lists
            </a>
            @if($list->canBeEdited())
                <a href="{{ route('resource_person.equivalency_lists.edit', $list->program_code) }}" class="btn btn-primary-industrial btn-industrial">
                    <i class="fas fa-edit me-1"></i>Edit List
                </a>
            @endif
        </div>
    </div>

    <!-- List Information Card -->
    <div class="info-card">
        <div class="info-card-header">
            <h5><i class="fas fa-info-circle"></i>List Information</h5>
        </div>
        <div class="info-card-body">
            <div class="row">
                <div class="col-md-4">
                    <table class="table table-borderless info-table">
                        <tr>
                            <th>Program:</th>
                            <td>{{ $list->program_name }}</td>
                        </tr>
                        <tr>
                            <th>Program Code:</th>
                            <td><span class="status-badge primary font-mono">{{ $list->program_code }}</span></td>
                        </tr>
                        <tr>
                            <th>Category:</th>
                            <td><span class="status-badge info">Internal (CS110)</span></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-4">
                    <table class="table table-borderless info-table">
                        <tr>
                            <th>Semester:</th>
                            <td>{{ $list->semester }}</td>
                        </tr>
                        <tr>
                            <th>Academic Year:</th>
                            <td>{{ $list->academic_year }}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                @if($list->published_at)
                                    <span class="status-badge success">Published</span>
                                @else
                                    <span class="status-badge {{ $list->status === 'rejected' ? 'rejected' : 'draft' }}">{{ $list->status_display }}</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-4">
                    <table class="table table-borderless info-table">
                        <tr>
                            <th>Created By:</th>
                            <td>{{ $list->creator->name }}</td>
                        </tr>
                        <tr>
                            <th>Created Date:</th>
                            <td>{{ $list->created_at->format('d M Y') }}</td>
                        </tr>
                        @if($list->submitted_at)
                            <tr>
                                <th>Submitted:</th>
                                <td>{{ $list->submitted_at->format('d M Y') }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>

            @if($list->submission_notes)
                <div class="alert-info-custom">
                    <strong><i class="fas fa-comment me-2"></i>Submission Notes:</strong><br>
                    {{ $list->submission_notes }}
                </div>
            @endif

            @if($list->isEndorsed() || $list->isPublished())
                <div class="alert-success-custom">
                    <strong><i class="fas fa-check-circle me-2"></i>Endorsed by HEA:</strong> {{ $list->endorser->name ?? 'N/A' }}<br>
                    <strong>Endorsed Date:</strong> {{ $list->endorsed_at ? $list->endorsed_at->format('d M Y, h:i A') : 'N/A' }}
                    @if($list->endorsement_notes)
                        <br><strong>Notes:</strong> {{ $list->endorsement_notes }}
                    @endif
                </div>
            @endif

            @if($list->isPublished())
                <div class="alert-success-custom">
                    <strong><i class="fas fa-book me-2"></i>Published:</strong> {{ $list->publisher->name ?? 'N/A' }}<br>
                    <strong>Published Date:</strong> {{ $list->published_at ? $list->published_at->format('d M Y, h:i A') : 'N/A' }}<br>
                    <strong>Active:</strong> {{ $list->is_active ? 'Yes' : 'No' }}
                </div>
            @endif

            @if($list->isRejected())
                <div class="alert-danger-custom">
                    <strong><i class="fas fa-times-circle me-2"></i>Rejected by HEA:</strong> {{ $list->reviewer->name ?? 'N/A' }}<br>
                    <strong>Rejection Date:</strong> {{ $list->reviewed_at ? $list->reviewed_at->format('d M Y, h:i A') : 'N/A' }}<br>
                    <strong>Reason:</strong> {{ $list->review_notes }}
                </div>
            @endif
        </div>
    </div>

    <!-- Course Mappings Card -->
    <div class="mappings-card">
        <div class="mappings-header">
            <h5><i class="fas fa-exchange-alt"></i>Course Mappings ({{ $list->total_equivalencies }})</h5>
        </div>
        <div class="mappings-body">
            @if($list->courseEquivalencies->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-exchange-alt"></i>
                    <h5>No Course Mappings</h5>
                    <p>This list doesn't have any course mappings yet.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table mappings-table">
                        <thead>
                            <tr>
                                <th>CS110 Diploma Course</th>
                                <th>Credit</th>
                                <th>{{ $list->program_code }} Degree Course</th>
                                <th>Credit</th>
                                <th class="text-center">Match %</th>
                                <th class="text-center">Eligible</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($list->courseEquivalencies->sortBy('diploma_course_code') as $equiv)
                            <tr>
                                <td>
                                    <span class="course-code">{{ $equiv->diploma_course_code }}</span><br>
                                    <span class="course-name">{{ $equiv->diploma_course_name }}</span>
                                </td>
                                <td class="font-mono">{{ $equiv->diploma_credit_hour }}</td>
                                <td>
                                    <span class="course-code">{{ $equiv->degree_course_code }}</span><br>
                                    <span class="course-name">{{ $equiv->degree_course_name }}</span>
                                </td>
                                <td class="font-mono">{{ $equiv->degree_credit_hour }}</td>
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

                <!-- Statistics -->
                <div class="stats-row px-4 pb-4">
                    <div class="stat-card primary">
                        <h3>{{ $list->total_equivalencies }}</h3>
                        <small>Total Mappings</small>
                    </div>
                    <div class="stat-card success">
                        <h3>{{ $list->eligible_count }}</h3>
                        <small>Eligible Courses</small>
                    </div>
                    <div class="stat-card secondary">
                        <h3>{{ $list->not_eligible_count }}</h3>
                        <small>Not Eligible</small>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
