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
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        color: white;
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
        background: linear-gradient(135deg, transparent 0%, rgba(255,255,255,0.1) 100%);
        clip-path: polygon(100% 0, 0% 100%, 100% 100%);
    }

    .page-header h2 {
        font-weight: 700;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .page-header p {
        opacity: 0.9;
        margin: 0;
    }

    .header-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
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
        background: var(--industrial-light);
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .info-card-header h5 {
        font-weight: 700;
        color: var(--industrial-dark);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-card-body {
        padding: 1.5rem;
    }

    .info-table {
        width: 100%;
    }

    .info-table th {
        color: var(--industrial-gray);
        font-weight: 600;
        padding: 0.5rem 0;
        width: 150px;
        vertical-align: top;
    }

    .info-table td {
        padding: 0.5rem 0;
        color: var(--industrial-dark);
    }

    /* Main Card */
    .main-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .main-card-header {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, #1e40af 100%);
        padding: 1.25rem 1.5rem;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .main-card-header h5 {
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Custom Table */
    .custom-table {
        width: 100%;
        border-collapse: collapse;
    }

    .custom-table thead th {
        background: var(--industrial-light);
        color: var(--industrial-dark);
        font-weight: 600;
        padding: 1rem;
        text-align: left;
        border-bottom: 2px solid #e2e8f0;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .custom-table tbody td {
        padding: 1rem;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
    }

    .custom-table tbody tr:hover {
        background: rgba(30,58,138,0.02);
    }

    .custom-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Course Info */
    .course-code {
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 600;
        color: var(--industrial-dark);
    }

    .course-name {
        color: var(--industrial-gray);
        font-size: 0.85rem;
        margin-top: 0.25rem;
    }

    .course-meta {
        color: var(--industrial-gray);
        font-size: 0.8rem;
        margin-top: 0.25rem;
    }

    /* Stats Footer */
    .stats-footer {
        background: var(--industrial-light);
        padding: 1rem 1.5rem;
        border-top: 1px solid #e2e8f0;
    }

    .stat-item {
        text-align: center;
    }

    .stat-label {
        font-weight: 600;
        color: var(--industrial-dark);
        font-size: 0.9rem;
    }

    /* Badges */
    .badge-primary {
        background: var(--uitm-blue);
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .badge-internal {
        background: rgba(30,58,138,0.15);
        color: var(--uitm-blue);
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .badge-external {
        background: rgba(13,148,136,0.15);
        color: var(--info);
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .badge-draft {
        background: rgba(245,158,11,0.15);
        color: var(--warning);
    }

    .badge-published {
        background: rgba(5,150,105,0.15);
        color: var(--success);
    }

    .badge-archived {
        background: rgba(100,116,139,0.15);
        color: #64748b;
    }

    .badge-success {
        background: var(--success);
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .badge-warning {
        background: var(--warning);
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .match-badge {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.9rem;
        padding: 0.4rem 0.75rem;
    }

    /* Status Badges */
    .status-badge-lg {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
        border-radius: 8px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .status-published-active {
        background: var(--success);
        color: white;
    }

    .status-published {
        background: var(--info);
        color: white;
    }

    .status-archived {
        background: #64748b;
        color: white;
    }

    /* Buttons */
    .btn-industrial {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-back {
        background: rgba(255,255,255,0.2);
        color: white;
        border: 1px solid rgba(255,255,255,0.3);
    }

    .btn-back:hover {
        background: rgba(255,255,255,0.3);
        color: white;
    }

    .btn-edit {
        background: var(--uitm-amber);
        color: white;
        border: none;
    }

    .btn-edit:hover {
        background: #d97706;
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

    .btn-light-sm {
        background: white;
        color: var(--uitm-blue);
        border: none;
        padding: 0.4rem 0.75rem;
        font-size: 0.8rem;
    }

    .btn-light-sm:hover {
        background: var(--industrial-light);
        color: var(--uitm-blue);
    }

    .btn-outline-industrial {
        background: transparent;
        color: var(--uitm-blue);
        border: 1px solid var(--uitm-blue);
    }

    .btn-outline-industrial:hover {
        background: var(--uitm-blue);
        color: white;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-state-icon {
        width: 80px;
        height: 80px;
        background: var(--industrial-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }

    .empty-state-icon i {
        font-size: 2rem;
        color: var(--industrial-gray);
    }

    .empty-state h5 {
        font-weight: 700;
        color: var(--industrial-dark);
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: var(--industrial-gray);
        margin-bottom: 1.5rem;
    }

    /* Info Alert */
    .info-alert {
        background: rgba(13,148,136,0.08);
        border: 1px solid rgba(13,148,136,0.2);
        border-left: 4px solid var(--info);
        border-radius: 8px;
        padding: 1.25rem 1.5rem;
        margin-top: 1.5rem;
    }

    .info-alert h5 {
        font-weight: 700;
        color: var(--info);
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-alert p {
        color: var(--industrial-gray);
        margin: 0;
    }

    .info-alert hr {
        border-color: rgba(13,148,136,0.2);
        margin: 1rem 0;
    }

    /* Credit Flow */
    .credit-flow {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.8rem;
        color: var(--industrial-gray);
    }

    /* Toast Notifications */
    .toast-container {
        position: fixed;
        bottom: 1.5rem;
        right: 1.5rem;
        z-index: 1100;
    }

    .toast-custom {
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.15);
        border: 1px solid #e2e8f0;
        overflow: hidden;
        min-width: 320px;
    }

    .toast-header-success {
        background: var(--success);
        color: white;
        padding: 0.75rem 1rem;
    }

    .toast-header-error {
        background: var(--danger);
        color: white;
        padding: 0.75rem 1rem;
    }

    .toast-body {
        padding: 1rem;
        color: var(--industrial-dark);
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
        }

        .header-actions {
            margin-top: 1rem;
        }

        .info-card-body {
            padding: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex flex-wrap justify-content-between align-items-start">
            <div>
                <h2>
                    @if($list->category === 'internal')
                        <i class="fas fa-building"></i>
                    @else
                        <i class="fas fa-university"></i>
                    @endif
                    {{ $list->program_code }} Equivalency List
                </h2>
                <p>
                    {{ $list->semester }}
                    @if($list->category === 'external')
                        | {{ $list->source_institution }}
                    @else
                        | UiTM CS110 (Internal)
                    @endif
                </p>
            </div>
            <div class="header-actions">
                <a href="{{ Auth::user()->role == 'academic_advisor' ? route('academic_advisor.equivalency_lists.index') : route('program_coordinator.equivalency_lists.index') }}"
                   class="btn btn-industrial btn-back">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
                @if(!isset($isReadOnly) && $list->status === 'draft')
                    <a href="{{ route('program_coordinator.equivalency_lists.edit', $list) }}"
                       class="btn btn-industrial btn-edit">
                        <i class="fas fa-edit"></i> Edit List
                    </a>
                    <form action="{{ route('program_coordinator.equivalency_lists.publish', $list) }}"
                          method="POST"
                          class="d-inline"
                          onsubmit="return confirm('Are you sure you want to publish this list? Once published, you cannot edit the mappings.');">
                        @csrf
                        <button type="submit" class="btn btn-industrial btn-success-industrial">
                            <i class="fas fa-rocket"></i> Publish List
                        </button>
                    </form>
                @elseif($list->status === 'published' && $list->is_active)
                    <span class="status-badge-lg status-published-active">
                        <i class="fas fa-check-circle"></i> PUBLISHED & ACTIVE
                    </span>
                @elseif($list->status === 'published')
                    <span class="status-badge-lg status-published">
                        <i class="fas fa-check-circle"></i> PUBLISHED
                    </span>
                @elseif($list->status === 'archived')
                    <span class="status-badge-lg status-archived">
                        <i class="fas fa-archive"></i> ARCHIVED
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- List Information Card -->
    <div class="info-card">
        <div class="info-card-header">
            <h5><i class="fas fa-info-circle"></i> List Information</h5>
        </div>
        <div class="info-card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="info-table">
                        <tr>
                            <th>Program Code:</th>
                            <td><span class="badge-primary font-mono">{{ $list->program_code }}</span></td>
                        </tr>
                        <tr>
                            <th>Program Name:</th>
                            <td>{{ $list->program_name }}</td>
                        </tr>
                        <tr>
                            <th>Category:</th>
                            <td>
                                @if($list->category === 'internal')
                                    <span class="badge-internal">
                                        <i class="fas fa-building me-1"></i>Internal (CS110)
                                    </span>
                                @else
                                    <span class="badge-external">
                                        <i class="fas fa-university me-1"></i>External Institution
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @if($list->category === 'external')
                            <tr>
                                <th>Source Institution:</th>
                                <td>{{ $list->source_institution }}</td>
                            </tr>
                        @endif
                        <tr>
                            <th>Semester:</th>
                            <td>{{ $list->semester }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="info-table">
                        <tr>
                            <th>Status:</th>
                            <td>
                                @if($list->status === 'draft')
                                    <span class="badge-primary badge-draft">
                                        <i class="fas fa-file-alt me-1"></i>Draft
                                    </span>
                                @elseif($list->status === 'published')
                                    <span class="badge-primary badge-published">
                                        <i class="fas fa-check-circle me-1"></i>Published
                                    </span>
                                @elseif($list->status === 'archived')
                                    <span class="badge-primary badge-archived">
                                        <i class="fas fa-archive me-1"></i>Archived
                                    </span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Total Mappings:</th>
                            <td><strong class="font-mono">{{ $list->total_mappings }}</strong> course(s)</td>
                        </tr>
                        <tr>
                            <th>Created By:</th>
                            <td>{{ $list->creator->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Created At:</th>
                            <td>{{ $list->created_at->format('d M Y, h:i A') }}</td>
                        </tr>
                        @if($list->published_at)
                            <tr>
                                <th>Published By:</th>
                                <td>{{ $list->publisher->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Published At:</th>
                                <td>{{ $list->published_at->format('d M Y, h:i A') }}</td>
                            </tr>
                        @endif
                        <tr>
                            <th>Active List:</th>
                            <td>
                                @if($list->is_active)
                                    <span class="badge-success">
                                        <i class="fas fa-check-circle me-1"></i>Yes
                                    </span>
                                @else
                                    <span class="text-muted">No</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Course Mappings -->
    <div class="main-card">
        <div class="main-card-header">
            <h5><i class="fas fa-exchange-alt"></i> Course Equivalency Mappings</h5>
            @if(!isset($isReadOnly) && $list->status === 'draft')
                <a href="{{ route('program_coordinator.equivalency_lists.edit', $list) }}"
                   class="btn btn-industrial btn-light-sm">
                    <i class="fas fa-plus"></i> Add Mappings
                </a>
            @endif
        </div>

        @if($list->courseEquivalencies->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-inbox"></i>
                </div>
                <h5>No Course Mappings</h5>
                <p>This equivalency list does not have any course mappings yet.</p>
                @if(!isset($isReadOnly) && $list->status === 'draft')
                    <a href="{{ route('program_coordinator.equivalency_lists.edit', $list) }}"
                       class="btn btn-industrial btn-success-industrial">
                        <i class="fas fa-plus"></i> Add Course Mappings
                    </a>
                @endif
            </div>
        @else
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th width="5%" class="text-center">#</th>
                            <th width="35%">Diploma Course</th>
                            <th width="35%">Degree Course</th>
                            <th width="10%" class="text-center">Match %</th>
                            <th width="10%" class="text-center">Credits</th>
                            <th width="5%" class="text-center">Eligible</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list->courseEquivalencies->sortBy('diploma_course_code') as $index => $eq)
                        <tr>
                            <td class="text-center text-muted">{{ $index + 1 }}</td>
                            <td>
                                <div class="course-code">{{ $eq->diploma_course_code }}</div>
                                <div class="course-name">{{ $eq->diploma_course_name }}</div>
                                <div class="course-meta">
                                    <i class="fas fa-university me-1"></i>{{ $eq->diploma_institution }}
                                </div>
                            </td>
                            <td>
                                <div class="course-code">{{ $eq->degree_course_code }}</div>
                                <div class="course-name">{{ $eq->degree_course_name }}</div>
                            </td>
                            <td class="text-center">
                                <span class="match-badge badge-{{ $eq->match_percentage >= 80 ? 'success' : 'warning' }}">
                                    {{ number_format($eq->match_percentage, 0) }}%
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="credit-flow">
                                    {{ $eq->diploma_credit_hour }} → {{ $eq->degree_credit_hour }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($eq->is_eligible)
                                    <i class="fas fa-check-circle text-success fs-5"></i>
                                @else
                                    <i class="fas fa-times-circle text-danger fs-5"></i>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Statistics Footer -->
            <div class="stats-footer">
                <div class="row">
                    <div class="col-md-4 stat-item">
                        <span class="stat-label">Total Mappings:</span>
                        <span class="badge-primary ms-2 font-mono">{{ $list->courseEquivalencies->count() }}</span>
                    </div>
                    <div class="col-md-4 stat-item">
                        <span class="stat-label">Eligible (≥80%):</span>
                        <span class="badge-success ms-2 font-mono">{{ $list->courseEquivalencies->where('is_eligible', true)->count() }}</span>
                    </div>
                    <div class="col-md-4 stat-item">
                        <span class="stat-label">Not Eligible (&lt;80%):</span>
                        <span class="badge-warning ms-2 font-mono">{{ $list->courseEquivalencies->where('is_eligible', false)->count() }}</span>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @if($list->status === 'draft')
        <!-- Publishing Information -->
        <div class="info-alert">
            <h5><i class="fas fa-info-circle"></i> Ready to Publish?</h5>
            <p>
                Once you have added all course mappings, you can <strong>publish this list directly</strong>.
                No HEA approval is required. Published lists will become visible to students immediately.
            </p>
            <hr>
            <p>
                <strong>Note:</strong> After publishing, you cannot edit the course mappings.
                Make sure all mappings are correct before publishing.
            </p>
        </div>
    @endif
</div>

<!-- Toast Notifications -->
@if(session('success'))
    <div class="toast-container">
        <div class="toast-custom show" role="alert">
            <div class="toast-header-success d-flex justify-content-between align-items-center">
                <span><i class="fas fa-check-circle me-2"></i><strong>Success</strong></span>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                {{ session('success') }}
            </div>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="toast-container">
        <div class="toast-custom show" role="alert">
            <div class="toast-header-error d-flex justify-content-between align-items-center">
                <span><i class="fas fa-exclamation-circle me-2"></i><strong>Error</strong></span>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                {{ session('error') }}
            </div>
        </div>
    </div>
@endif
@endsection

@push('scripts')
<script>
    // Auto-hide toasts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const toasts = document.querySelectorAll('.toast-custom');
        toasts.forEach(toast => {
            setTimeout(() => {
                toast.classList.remove('show');
                toast.classList.add('fade');
            }, 5000);
        });
    });
</script>
@endpush
