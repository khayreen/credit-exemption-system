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
        background: linear-gradient(135deg, var(--uitm-amber) 0%, #d97706 100%);
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
        background: linear-gradient(135deg, transparent 0%, rgba(255,255,255,0.1) 100%);
        pointer-events: none;
    }

    .page-header h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: white;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .page-header p {
        color: rgba(255,255,255,0.9);
        margin: 0.35rem 0 0 0;
        font-size: 0.9rem;
    }

    /* Stats Row */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border-left: 4px solid;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .stat-card.drafts { border-left-color: var(--uitm-amber); }
    .stat-card.published { border-left-color: var(--uitm-blue); }

    .stat-card-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .stat-card.drafts .stat-card-icon { background: rgba(245,158,11,0.15); color: var(--uitm-amber); }
    .stat-card.published .stat-card-icon { background: rgba(30,58,138,0.15); color: var(--uitm-blue); }

    .stat-card h3 {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--industrial-dark);
        margin: 0;
    }

    .stat-card small {
        color: var(--industrial-gray);
        font-size: 0.875rem;
    }

    /* Main Card */
    .main-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .main-card-body {
        padding: 1.5rem;
    }

    /* Program Tabs */
    .program-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        padding: 0.5rem;
        background: var(--industrial-light);
        border-radius: 10px;
        margin-bottom: 1.5rem;
    }

    .program-tab {
        padding: 0.625rem 1rem;
        border-radius: 8px;
        border: none;
        background: transparent;
        color: var(--industrial-gray);
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .program-tab:hover {
        background: white;
        color: var(--uitm-blue);
    }

    .program-tab.active {
        background: var(--uitm-blue);
        color: white;
    }

    .program-tab .badge {
        font-size: 0.7rem;
        padding: 0.2rem 0.4rem;
    }

    /* Program Title */
    .program-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--uitm-blue);
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Section Title */
    .section-title {
        font-weight: 600;
        color: var(--industrial-dark);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-title i { color: var(--uitm-amber); }

    /* Draft Card */
    .draft-card {
        background: white;
        border: 2px solid rgba(245,158,11,0.3);
        border-radius: 10px;
        margin-bottom: 1rem;
        overflow: hidden;
        transition: all 0.2s ease;
    }

    .draft-card:hover {
        border-color: var(--uitm-amber);
        box-shadow: 0 4px 12px rgba(245,158,11,0.15);
    }

    .draft-card-body {
        padding: 1.25rem;
    }

    .draft-card h5 {
        font-weight: 600;
        color: var(--uitm-blue);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .draft-card .meta-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .draft-card .meta-info {
        color: var(--industrial-gray);
        font-size: 0.85rem;
    }

    .draft-badge {
        background: linear-gradient(135deg, var(--uitm-amber) 0%, #d97706 100%);
        color: white;
        padding: 0.4rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .category-badge {
        padding: 0.3rem 0.6rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .category-badge.internal { background: rgba(30,58,138,0.15); color: var(--uitm-blue); }
    .category-badge.external { background: rgba(13,148,136,0.15); color: var(--info); }

    .count-badge {
        background: var(--industrial-light);
        color: var(--industrial-gray);
        padding: 0.3rem 0.6rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    /* Empty State */
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
    }

    .empty-state i {
        font-size: 4rem;
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

    .btn-success-industrial {
        background: var(--success);
        color: white;
        border: none;
    }

    .btn-success-industrial:hover {
        background: #047857;
        color: white;
    }

    .btn-info-industrial {
        background: var(--info);
        color: white;
        border: none;
    }

    .btn-info-industrial:hover {
        background: #0f766e;
        color: white;
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
        background: linear-gradient(135deg, var(--danger) 0%, #b91c1c 100%);
        color: white;
        border: none;
        padding: 1.25rem 1.5rem;
    }

    .modal-industrial .modal-title {
        font-weight: 600;
    }

    .modal-industrial .modal-body {
        padding: 1.5rem;
    }

    .modal-industrial .modal-footer {
        border-top: 1px solid #e2e8f0;
        padding: 1rem 1.5rem;
    }

    /* Toast */
    .toast-industrial {
        border-radius: 10px;
        border: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .toast-industrial.success .toast-header { background: var(--success); color: white; }
    .toast-industrial.error .toast-header { background: var(--danger); color: white; }

    @media (max-width: 768px) {
        .stats-row {
            grid-template-columns: 1fr;
        }

        .program-tabs {
            flex-direction: column;
        }

        .program-tab {
            width: 100%;
            justify-content: center;
        }

        .page-header h2 {
            font-size: 1.25rem;
        }

        .draft-card-body {
            padding: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <h2><i class="fas fa-file-alt"></i>Draft Equivalency Lists</h2>
                <p><i class="fas fa-info-circle me-1"></i>Manage draft equivalency lists across all degree programs</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('program_coordinator.equivalency_lists.index') }}" class="btn btn-light btn-industrial">
                    <i class="fas fa-clipboard-list me-2"></i>Published Lists
                </a>
                <a href="{{ route('program_coordinator.equivalency_lists.create') }}" class="btn btn-light btn-industrial">
                    <i class="fas fa-plus-circle me-2"></i>Create New List
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="stats-row">
        <div class="stat-card drafts">
            <div class="stat-card-icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <div>
                <h3>{{ $stats['total_drafts'] }}</h3>
                <small>Total Draft Lists</small>
            </div>
        </div>
        <div class="stat-card published">
            <div class="stat-card-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div>
                <h3>{{ $stats['total_published'] }}</h3>
                <small>Published Lists</small>
            </div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="main-card">
        <div class="main-card-body">
            <!-- Program Tabs -->
            <div class="program-tabs" role="tablist">
                @foreach($programs as $index => $programCode)
                <button class="program-tab {{ $index === 0 ? 'active' : '' }}"
                        id="{{ strtolower($programCode) }}-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#{{ strtolower($programCode) }}"
                        type="button"
                        role="tab">
                    <span class="font-mono">{{ $programCode }}</span>
                    @if($programData[$programCode]['count'] > 0)
                        <span class="badge bg-warning text-dark">{{ $programData[$programCode]['count'] }}</span>
                    @endif
                </button>
                @endforeach
            </div>

            <div class="tab-content" id="programTabsContent">
                @foreach($programs as $index => $programCode)
                <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}"
                     id="{{ strtolower($programCode) }}"
                     role="tabpanel">

                    <!-- Program Name -->
                    <h4 class="program-title">
                        <i class="fas fa-graduation-cap"></i>{{ $programData[$programCode]['name'] }}
                    </h4>

                    @if($programData[$programCode]['drafts']->isNotEmpty())
                        <!-- Draft Lists -->
                        <h5 class="section-title">
                            <i class="fas fa-file-alt"></i>Draft Lists ({{ $programData[$programCode]['count'] }})
                        </h5>

                        @foreach($programData[$programCode]['drafts'] as $list)
                        <div class="draft-card">
                            <div class="draft-card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-7">
                                        <h5>
                                            <i class="fas fa-calendar-alt text-muted"></i>{{ $list->semester }}
                                        </h5>
                                        <div class="meta-badges">
                                            <span class="category-badge {{ $list->category }}">
                                                {{ $list->category === 'internal' ? 'UiTM CS110' : 'External - ' . $list->source_institution }}
                                            </span>
                                            <span class="count-badge">
                                                <i class="fas fa-book me-1"></i>{{ $list->courseEquivalencies->count() }} courses
                                            </span>
                                        </div>
                                        <p class="meta-info mb-0">
                                            <i class="fas fa-user me-1"></i>Created by {{ $list->creator->name ?? 'N/A' }}
                                            on {{ $list->created_at->format('d M Y') }}
                                        </p>
                                    </div>
                                    <div class="col-md-5 text-md-end mt-3 mt-md-0">
                                        <span class="draft-badge me-2">
                                            <i class="fas fa-file-alt me-1"></i>DRAFT
                                        </span>
                                        <div class="mt-2 d-flex gap-2 justify-content-md-end flex-wrap">
                                            <a href="{{ route('program_coordinator.equivalency_lists.show', $list) }}"
                                               class="btn btn-sm btn-info-industrial btn-industrial">
                                                <i class="fas fa-eye me-1"></i>View
                                            </a>
                                            <a href="{{ route('program_coordinator.equivalency_lists.edit', $list) }}"
                                               class="btn btn-sm btn-primary-industrial btn-industrial">
                                                <i class="fas fa-edit me-1"></i>Edit
                                            </a>
                                            <button type="button"
                                                    class="btn btn-sm btn-danger-industrial btn-industrial"
                                                    onclick="confirmDelete('{{ $list->id }}', '{{ $list->semester }}', '{{ $list->category === 'internal' ? 'CS110' : $list->source_institution }}')">
                                                <i class="fas fa-trash me-1"></i>Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach

                    @else
                        <!-- Empty State -->
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <h5>No Draft Lists</h5>
                            <p>No draft equivalency lists have been created for this program yet.</p>
                            <a href="{{ route('program_coordinator.equivalency_lists.create') }}?program_code={{ $programCode }}" class="btn btn-primary-industrial btn-industrial mt-3">
                                <i class="fas fa-plus-circle me-2"></i>Create First Draft for {{ $programCode }}
                            </a>
                        </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade modal-industrial" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirm Deletion
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">Are you sure you want to delete this draft list?</p>
                <div class="alert alert-warning border-0" style="background: rgba(245,158,11,0.1);">
                    <strong>Semester:</strong> <span id="delete_semester" class="font-mono"></span><br>
                    <strong>Category:</strong> <span id="delete_category"></span>
                </div>
                <p class="text-danger mb-0">
                    <i class="fas fa-exclamation-circle me-1"></i>
                    <strong>Warning:</strong> This action cannot be undone. All course mappings in this draft will be permanently deleted.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger-industrial">
                        <i class="fas fa-trash me-1"></i>Yes, Delete Draft
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(listId, semester, category) {
    document.getElementById('delete_semester').textContent = semester;
    document.getElementById('delete_category').textContent = category;

    const form = document.getElementById('deleteForm');
    form.action = `/program-coordinator/equivalency-lists/${listId}`;

    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
@endpush

@if(session('success'))
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100">
    <div class="toast show toast-industrial success" role="alert">
        <div class="toast-header">
            <i class="fas fa-check-circle me-2"></i>
            <strong class="me-auto">Success</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body">
            {{ session('success') }}
        </div>
    </div>
</div>
@endif

@if(session('error'))
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100">
    <div class="toast show toast-industrial error" role="alert">
        <div class="toast-header">
            <i class="fas fa-exclamation-circle me-2"></i>
            <strong class="me-auto">Error</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body">
            {{ session('error') }}
        </div>
    </div>
</div>
@endif
