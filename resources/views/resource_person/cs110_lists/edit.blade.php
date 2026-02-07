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

    .page-header p {
        color: var(--industrial-gray);
        margin: 0.35rem 0 0 0;
        font-size: 0.9rem;
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

    .status-badge.draft { background: var(--industrial-gray); color: white; }
    .status-badge.target { background: var(--uitm-amber); color: white; }
    .status-badge.published { background: var(--success); color: white; }

    /* Rejection Alert */
    .rejection-alert {
        background: rgba(220,38,38,0.05);
        border: 1px solid rgba(220,38,38,0.2);
        border-left: 4px solid var(--danger);
        border-radius: 8px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
    }

    .rejection-alert h5 {
        color: var(--danger);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

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

    /* Mappings Card */
    .mappings-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .mappings-header {
        background: linear-gradient(135deg, var(--success) 0%, #047857 100%);
        padding: 1rem 1.25rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
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

    /* Action Card */
    .action-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
    }

    .action-card .info-text {
        color: var(--industrial-gray);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* History Card */
    .history-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .history-header {
        background: var(--industrial-gray);
        padding: 1rem 1.25rem;
        color: white;
    }

    .history-header h5 {
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .history-body {
        padding: 1.25rem;
    }

    .history-table {
        font-size: 0.875rem;
        margin: 0;
    }

    .history-table thead th {
        background: var(--industrial-light);
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .action-badge {
        padding: 0.2rem 0.5rem;
        border-radius: 4px;
        font-size: 0.7rem;
        font-weight: 600;
    }

    .action-badge.deleted { background: rgba(220,38,38,0.15); color: var(--danger); }
    .action-badge.replaced { background: rgba(245,158,11,0.15); color: var(--warning); }

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

    .btn-danger-industrial {
        background: var(--danger);
        color: white;
        border: none;
    }

    .btn-danger-industrial:hover {
        background: #b91c1c;
        color: white;
    }

    /* Modal Styling */
    .modal-industrial .modal-header {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, #1e40af 100%);
        color: white;
        border: none;
        padding: 1.25rem 1.5rem;
    }

    .modal-industrial .modal-title {
        font-weight: 600;
    }

    .modal-industrial.modal-success .modal-header {
        background: linear-gradient(135deg, var(--success) 0%, #047857 100%);
    }

    .modal-industrial .modal-body {
        padding: 1.5rem;
    }

    .modal-industrial .modal-footer {
        border-top: 1px solid #e2e8f0;
        padding: 1rem 1.5rem;
    }

    /* Info Box */
    .info-box {
        background: rgba(59,130,246,0.08);
        border: 1px solid rgba(59,130,246,0.2);
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1.25rem;
    }

    .info-box i { color: var(--uitm-blue-light); }

    /* Section Title */
    .section-title {
        font-weight: 600;
        margin-bottom: 0.75rem;
    }

    .section-title.primary { color: var(--uitm-blue); }
    .section-title.success { color: var(--success); }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2><i class="fas fa-edit me-2" style="color: var(--uitm-blue);"></i>Edit CS110 Equivalency List - <span class="font-mono">{{ $list->program_code }}</span></h2>
            <p><i class="fas fa-building me-1"></i>ONE continuous list per program - Edit anytime, submit for each semester</p>
            <div class="page-header-badges">
                <span class="status-badge draft">{{ $list->status_display }}</span>
                @if($list->target_semester)
                    <span class="status-badge target">Target: {{ $list->target_semester }}</span>
                @endif
                @if($list->semester)
                    <span class="status-badge published">Last Published: {{ $list->semester }}</span>
                @endif
            </div>
        </div>
        <div>
            <a href="{{ route('resource_person.equivalency_lists.published') }}" class="btn btn-outline-secondary btn-industrial">
                <i class="fas fa-arrow-left me-1"></i>Back to Lists
            </a>
        </div>
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
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($list->isRejected())
        <div class="rejection-alert">
            <h5><i class="fas fa-times-circle me-2"></i>This list was rejected by HEA</h5>
            <p class="mb-0"><strong>Reason:</strong> {{ $list->review_notes }}</p>
            <small class="text-muted">Please make the necessary changes and resubmit.</small>
        </div>
    @endif

    <!-- List Information Card -->
    <div class="info-card">
        <div class="info-card-header">
            <h5><i class="fas fa-info-circle"></i>List Information</h5>
        </div>
        <div class="info-card-body">
            <div class="row">
                <div class="col-md-3">
                    <table class="table table-borderless info-table">
                        <tr>
                            <th>Program:</th>
                            <td>{{ $list->program_name }}</td>
                        </tr>
                        <tr>
                            <th>Code:</th>
                            <td><span class="badge" style="background: var(--uitm-blue);">{{ $list->program_code }}</span></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-3">
                    <table class="table table-borderless info-table">
                        <tr>
                            <th>Category:</th>
                            <td><span class="badge" style="background: var(--info);">Internal (CS110)</span></td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td><span class="status-badge draft">{{ $list->status_display }}</span></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-3">
                    @if($list->semester)
                        <table class="table table-borderless info-table">
                            <tr>
                                <th>Last Published:</th>
                                <td>{{ $list->semester }}</td>
                            </tr>
                            <tr>
                                <th>Published On:</th>
                                <td>{{ $list->published_at ? $list->published_at->format('d M Y') : '-' }}</td>
                            </tr>
                        </table>
                    @else
                        <table class="table table-borderless info-table">
                            <tr>
                                <th>Last Published:</th>
                                <td>Never</td>
                            </tr>
                            <tr>
                                <th>Published On:</th>
                                <td>-</td>
                            </tr>
                        </table>
                    @endif
                </div>
                <div class="col-md-3">
                    @if($list->target_semester)
                        <table class="table table-borderless info-table">
                            <tr>
                                <th>Target Semester:</th>
                                <td><span class="status-badge target">{{ $list->target_semester }}</span></td>
                            </tr>
                        </table>
                    @else
                        <table class="table table-borderless info-table">
                            <tr>
                                <th>Target Semester:</th>
                                <td><small class="text-muted">Set when submitting</small></td>
                            </tr>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Course Mappings Section -->
    <div class="mappings-card">
        <div class="mappings-header">
            <h5><i class="fas fa-exchange-alt"></i>Course Mappings ({{ $list->total_equivalencies }})</h5>
            <button type="button" class="btn btn-light btn-sm btn-industrial" data-bs-toggle="modal" data-bs-target="#addMappingModal">
                <i class="fas fa-plus-circle me-1"></i>Add Mapping
            </button>
        </div>
        <div class="mappings-body">
            @if($list->courseEquivalencies->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-exchange-alt"></i>
                    <h5>No Course Mappings Yet</h5>
                    <p class="mb-3">Add course mappings from CS110 diploma courses to <span class="font-mono">{{ $list->program_code }}</span> degree courses.</p>
                    <button type="button" class="btn btn-success-industrial btn-industrial" data-bs-toggle="modal" data-bs-target="#addMappingModal">
                        <i class="fas fa-plus-circle me-1"></i>Add First Mapping
                    </button>
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
                                <th class="text-center">Actions</th>
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
                                <td class="text-center">
                                    <button class="btn btn-sm btn-primary-industrial me-1" onclick="editMapping('{{ $equiv->id }}', {{ json_encode($equiv) }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger-industrial" onclick="deleteMapping('{{ $equiv->id }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Action Card -->
    <div class="action-card">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <p class="info-text">
                <i class="fas fa-info-circle"></i>
                @if($list->canBeSubmitted())
                    Ready to submit to HEA for review
                @else
                    Add at least one course mapping to submit
                @endif
            </p>
            <div>
                @if($list->canBeSubmitted())
                    <button type="button" class="btn btn-success-industrial btn-industrial" data-bs-toggle="modal" data-bs-target="#submitModal">
                        <i class="fas fa-paper-plane me-1"></i>Submit to HEA
                    </button>
                @else
                    <button type="button" class="btn btn-secondary btn-industrial" disabled>
                        <i class="fas fa-paper-plane me-1"></i>Submit to HEA
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Course Mapping History -->
    @if($list->courseEquivalencyHistory && $list->courseEquivalencyHistory->count() > 0)
    <div class="history-card">
        <div class="history-header">
            <h5><i class="fas fa-history"></i>Course Mapping History ({{ $list->courseEquivalencyHistory->count() }})</h5>
        </div>
        <div class="history-body">
            <p class="text-muted mb-3">
                <i class="fas fa-info-circle me-1"></i>
                Previously deleted or replaced course mappings are archived here for record-keeping.
            </p>
            <div class="table-responsive">
                <table class="table table-sm table-hover history-table">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Diploma Course</th>
                            <th>Degree Course</th>
                            <th>Match %</th>
                            <th>Archived By</th>
                            <th>Archived At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list->courseEquivalencyHistory->sortByDesc('archived_at') as $history)
                        <tr>
                            <td>
                                <span class="action-badge {{ $history->action }}">
                                    {{ ucfirst($history->action) }}
                                </span>
                            </td>
                            <td>
                                <strong class="font-mono">{{ $history->diploma_course_code }}</strong><br>
                                <small class="text-muted">{{ Str::limit($history->diploma_course_name, 30) }}</small>
                            </td>
                            <td>
                                <strong class="font-mono">{{ $history->degree_course_code }}</strong><br>
                                <small class="text-muted">{{ Str::limit($history->degree_course_name, 30) }}</small>
                            </td>
                            <td class="font-mono">{{ number_format($history->match_percentage, 0) }}%</td>
                            <td>{{ $history->archivedBy->name ?? 'System' }}</td>
                            <td>{{ $history->archived_at->format('d M Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Add Mapping Modal -->
<div class="modal fade modal-industrial modal-success" id="addMappingModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Add Course Mapping</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('resource_person.equivalency_lists.add_mapping', $programCode) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="info-box">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>CS110 Internal Mapping:</strong> Map CS110 diploma courses to <span class="font-mono fw-bold">{{ $list->program_code }}</span> degree courses
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="section-title primary">CS110 Diploma Course</h6>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Course Code <span class="text-danger">*</span></label>
                                <input type="text" name="diploma_course_code" class="form-control" placeholder="CSC126" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Course Name <span class="text-danger">*</span></label>
                                <input type="text" name="diploma_course_name" class="form-control" placeholder="Fundamentals of Algorithms" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Credit Hours <span class="text-danger">*</span></label>
                                <input type="number" name="diploma_credit_hour" class="form-control font-mono" min="1" max="10" value="3" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h6 class="section-title success">{{ $list->program_code }} Degree Course</h6>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Course Code <span class="text-danger">*</span></label>
                                <input type="text" name="degree_course_code" class="form-control" placeholder="CSC402" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Course Name <span class="text-danger">*</span></label>
                                <input type="text" name="degree_course_name" class="form-control" placeholder="Programming I" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Credit Hours <span class="text-danger">*</span></label>
                                <input type="number" name="degree_credit_hour" class="form-control font-mono" min="1" max="10" value="3" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-medium">Match Percentage <span class="text-danger">*</span></label>
                                <input type="number" name="match_percentage" class="form-control font-mono" min="0" max="100" value="85" required>
                                <small class="text-muted">Recommended: ≥80% for eligibility</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-medium">Eligible for Exemption</label>
                                <select name="is_eligible" class="form-select" required>
                                    <option value="1">Yes - Eligible</option>
                                    <option value="0">No - Not Eligible</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success-industrial"><i class="fas fa-plus-circle me-1"></i>Add Mapping</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Submit to HEA Modal -->
<div class="modal fade modal-industrial modal-success" id="submitModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-paper-plane me-2"></i>Submit to HEA</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('resource_person.equivalency_lists.submit', $programCode) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="info-box">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong><span class="font-mono">{{ $list->total_equivalencies }}</span> course mappings</strong> will be submitted to HEA for review and endorsement.
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Target Semester <span class="text-danger">*</span></label>
                        <input type="text" name="target_semester" class="form-control" placeholder="e.g., 2025/2026-1" required>
                        <small class="text-muted">Format: Academic Year - Semester (e.g., 2025/2026-1 for Semester 1 of 2025/2026)</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Submission Notes (Optional)</label>
                        <textarea name="submission_notes" class="form-control" rows="3" placeholder="Add any notes or comments for HEA review..."></textarea>
                    </div>

                    <p class="mb-0 fw-medium">After submission:</p>
                    <ol class="mb-0 text-muted">
                        <li>HEA will review your mappings for <strong class="text-primary">target semester</strong></li>
                        <li>HEA will endorse or reject the list</li>
                        <li>If endorsed, HEA will publish as PDF for the specified semester</li>
                        <li>After publication, list reverts to DRAFT for next semester's updates</li>
                    </ol>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success-industrial">
                        <i class="fas fa-paper-plane me-1"></i>Submit to HEA
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Mapping Modal -->
<div class="modal fade modal-industrial" id="editMappingModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Course Mapping</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editMappingForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="info-box">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Update CS110 Mapping:</strong> Modify the course equivalency details
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="section-title primary">CS110 Diploma Course</h6>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Course Code <span class="text-danger">*</span></label>
                                <input type="text" id="edit_diploma_course_code" name="diploma_course_code" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Course Name <span class="text-danger">*</span></label>
                                <input type="text" id="edit_diploma_course_name" name="diploma_course_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Credit Hours <span class="text-danger">*</span></label>
                                <input type="number" id="edit_diploma_credit_hour" name="diploma_credit_hour" class="form-control font-mono" min="1" max="10" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h6 class="section-title success">{{ $list->program_code }} Degree Course</h6>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Course Code <span class="text-danger">*</span></label>
                                <input type="text" id="edit_degree_course_code" name="degree_course_code" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Course Name <span class="text-danger">*</span></label>
                                <input type="text" id="edit_degree_course_name" name="degree_course_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Credit Hours <span class="text-danger">*</span></label>
                                <input type="number" id="edit_degree_credit_hour" name="degree_credit_hour" class="form-control font-mono" min="1" max="10" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-medium">Match Percentage <span class="text-danger">*</span></label>
                                <input type="number" id="edit_match_percentage" name="match_percentage" class="form-control font-mono" min="0" max="100" required>
                                <small class="text-muted">Recommended: ≥80% for eligibility</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-medium">Eligible for Exemption</label>
                                <select id="edit_is_eligible" name="is_eligible" class="form-select" required>
                                    <option value="1">Yes - Eligible</option>
                                    <option value="0">No - Not Eligible</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary-industrial"><i class="fas fa-save me-1"></i>Update Mapping</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function editMapping(mappingId, mapping) {
    document.getElementById('edit_diploma_course_code').value = mapping.diploma_course_code;
    document.getElementById('edit_diploma_course_name').value = mapping.diploma_course_name;
    document.getElementById('edit_diploma_credit_hour').value = mapping.diploma_credit_hour;
    document.getElementById('edit_degree_course_code').value = mapping.degree_course_code;
    document.getElementById('edit_degree_course_name').value = mapping.degree_course_name;
    document.getElementById('edit_degree_credit_hour').value = mapping.degree_credit_hour;
    document.getElementById('edit_match_percentage').value = mapping.match_percentage;
    document.getElementById('edit_is_eligible').value = mapping.is_eligible ? '1' : '0';

    document.getElementById('editMappingForm').action = `/resource-person/cs110-lists/{{ $programCode }}/mappings/${mappingId}`;

    const editModal = new bootstrap.Modal(document.getElementById('editMappingModal'));
    editModal.show();
}

function deleteMapping(mappingId) {
    if (confirm('Are you sure you want to delete this course mapping?\n\nThis mapping will be moved to history and can be viewed later.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/resource-person/cs110-lists/{{ $programCode }}/mappings/${mappingId}`;

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';

        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';

        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
