@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">
                <i class="fas fa-file-alt me-2"></i>Draft Equivalency Lists
            </h2>
            <p class="text-muted mb-0 mt-2">
                <i class="fas fa-info-circle me-1"></i>
                Manage draft equivalency lists across all degree programs
            </p>
        </div>
        <div>
            <a href="{{ route('program_coordinator.equivalency_lists.index') }}" class="btn btn-info">
                <i class="fas fa-clipboard-list me-2"></i>Published Lists
            </a>
            <a href="{{ route('program_coordinator.equivalency_lists.create') }}" class="btn btn-success ms-2">
                <i class="fas fa-plus-circle me-2"></i>Create New List
            </a>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <h3 class="mb-0">{{ $stats['total_drafts'] }}</h3>
                    <p class="mb-0">Total Draft Lists</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h3 class="mb-0">{{ $stats['total_published'] }}</h3>
                    <p class="mb-0">Published Lists</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Program Tabs -->
    <div class="card shadow-sm">
        <div class="card-body">
            <ul class="nav nav-tabs mb-4" id="programTabs" role="tablist">
                @foreach($programs as $index => $programCode)
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $index === 0 ? 'active' : '' }}"
                            id="{{ strtolower($programCode) }}-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#{{ strtolower($programCode) }}"
                            type="button"
                            role="tab">
                        <strong>{{ $programCode }}</strong>
                        @if($programData[$programCode]['count'] > 0)
                            <span class="badge bg-warning text-dark ms-2">{{ $programData[$programCode]['count'] }}</span>
                        @endif
                    </button>
                </li>
                @endforeach
            </ul>

            <div class="tab-content" id="programTabsContent">
                @foreach($programs as $index => $programCode)
                <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}"
                     id="{{ strtolower($programCode) }}"
                     role="tabpanel">

                    <!-- Program Name -->
                    <h4 class="mb-3 text-primary">
                        <i class="fas fa-graduation-cap me-2"></i>{{ $programData[$programCode]['name'] }}
                    </h4>

                    @if($programData[$programCode]['drafts']->isNotEmpty())
                        <!-- Draft Lists -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-file-alt text-warning me-2"></i>Draft Lists ({{ $programData[$programCode]['count'] }})
                            </h5>
                            @foreach($programData[$programCode]['drafts'] as $list)
                            <div class="card mb-3 border-warning">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <h5 class="mb-2">
                                                <i class="fas fa-calendar-alt me-2 text-primary"></i>{{ $list->semester }}
                                            </h5>
                                            <p class="mb-1">
                                                <span class="badge bg-{{ $list->category === 'internal' ? 'primary' : 'info' }}">
                                                    {{ $list->category === 'internal' ? 'UiTM CS110' : 'External - ' . $list->source_institution }}
                                                </span>
                                                <span class="badge bg-secondary ms-2">
                                                    <i class="fas fa-book me-1"></i>{{ $list->courseEquivalencies->count() }} courses
                                                </span>
                                            </p>
                                            <p class="text-muted mb-0">
                                                <small>
                                                    <i class="fas fa-user me-1"></i>Created by {{ $list->creator->name ?? 'N/A' }}
                                                    on {{ $list->created_at->format('d M Y') }}
                                                </small>
                                            </p>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <span class="badge bg-warning text-dark fs-6 px-3 py-2 me-2">
                                                <i class="fas fa-file-alt"></i> DRAFT
                                            </span>
                                            <a href="{{ route('program_coordinator.equivalency_lists.show', $list) }}"
                                               class="btn btn-info btn-sm me-1">
                                                <i class="fas fa-eye me-1"></i>View
                                            </a>
                                            <a href="{{ route('program_coordinator.equivalency_lists.edit', $list) }}"
                                               class="btn btn-primary btn-sm me-1">
                                                <i class="fas fa-edit me-1"></i>Edit
                                            </a>
                                            <button type="button"
                                                    class="btn btn-danger btn-sm"
                                                    onclick="confirmDelete('{{ $list->id }}', '{{ $list->semester }}', '{{ $list->category === 'internal' ? 'CS110' : $list->source_institution }}')">
                                                <i class="fas fa-trash me-1"></i>Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                    @else
                        <!-- Empty State -->
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">No Draft Lists</h5>
                            <p class="text-muted">No draft equivalency lists have been created for this program yet.</p>
                            <a href="{{ route('program_coordinator.equivalency_lists.create') }}?program_code={{ $programCode }}" class="btn btn-primary mt-3">
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
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirm Deletion
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2">Are you sure you want to delete this draft list?</p>
                <div class="alert alert-warning">
                    <strong>Semester:</strong> <span id="delete_semester"></span><br>
                    <strong>Category:</strong> <span id="delete_category"></span>
                </div>
                <p class="text-danger mb-0">
                    <i class="fas fa-exclamation-circle me-1"></i>
                    <strong>Warning:</strong> This action cannot be undone. All course mappings in this draft will be permanently deleted.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>Yes, Delete Draft
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.nav-tabs .nav-link {
    color: #495057;
    font-weight: 500;
}

.nav-tabs .nav-link.active {
    color: #0d6efd;
    font-weight: 600;
    border-bottom: 3px solid #0d6efd;
}

.card {
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-2px);
}
</style>

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

@if(session('success'))
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div class="toast show" role="alert">
            <div class="toast-header bg-success text-white">
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
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div class="toast show" role="alert">
            <div class="toast-header bg-danger text-white">
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
@endsection
