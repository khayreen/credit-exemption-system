@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">HEA - Equivalency Lists Monitoring</h2>
            <p class="text-muted mb-0 mt-2">
                <i class="fas fa-eye me-1"></i>
                Monitor all equivalency lists across all programs (Read-Only)
            </p>
        </div>
        <div>
            <a href="{{ route('hea.equivalency_lists.grouped') }}" class="btn btn-outline-primary me-2">
                <i class="fas fa-th-list"></i> Grouped View
            </a>
            <a href="{{ route('hea.equivalency_lists.statistics') }}" class="btn btn-primary me-2">
                <i class="fas fa-chart-bar"></i> View Statistics
            </a>
            <a href="{{ route('hea.pending_mappings.index') }}" class="btn btn-info">
                <i class="fas fa-inbox"></i> Pending Mappings
                @if($stats['pending_mappings'] > 0)
                    <span class="badge bg-danger ms-1">{{ $stats['pending_mappings'] }}</span>
                @endif
            </a>
        </div>
    </div>

    <!-- Statistics Overview -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stat-card border-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon icon-blue me-3">
                            <i class="fas fa-list-alt"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $stats['total'] }}</h3>
                            <p class="text-muted mb-0">Total Lists</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card border-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon icon-green me-3">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $stats['published'] }}</h3>
                            <p class="text-muted mb-0">Published</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card border-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon icon-orange me-3">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $stats['draft'] }}</h3>
                            <p class="text-muted mb-0">Drafts</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card border-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon icon-purple me-3">
                            <i class="fas fa-paper-plane"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $stats['pending_mappings'] }}</h3>
                            <p class="text-muted mb-0">Pending Mappings</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h6 class="mb-0"><i class="fas fa-filter me-2"></i>Filter Lists</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('hea.equivalency_lists.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>All Statuses</option>
                            <option value="draft" {{ $statusFilter === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ $statusFilter === 'published' ? 'selected' : '' }}>Published</option>
                            <option value="archived" {{ $statusFilter === 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select">
                            <option value="all" {{ $categoryFilter === 'all' ? 'selected' : '' }}>All Categories</option>
                            <option value="internal" {{ $categoryFilter === 'internal' ? 'selected' : '' }}>Internal (CS110)</option>
                            <option value="external" {{ $categoryFilter === 'external' ? 'selected' : '' }}>External</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Program</label>
                        <select name="program" class="form-select">
                            <option value="all" {{ $programFilter === 'all' ? 'selected' : '' }}>All Programs</option>
                            @foreach($programs as $code => $name)
                                <option value="{{ $code }}" {{ $programFilter === $code ? 'selected' : '' }}>
                                    {{ $code }} - {{ Str::limit($name, 30) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-1"></i>Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Equivalency Lists Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>Equivalency Lists
                @if($statusFilter !== 'all' || $categoryFilter !== 'all' || $programFilter !== 'all')
                    <span class="badge bg-light text-dark ms-2">Filtered</span>
                @endif
            </h5>
        </div>
        <div class="card-body p-0">
            @if($lists->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">No Lists Found</h5>
                    <p class="text-muted mb-0">No equivalency lists match your current filters.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Program</th>
                                <th>Category</th>
                                <th>Source</th>
                                <th>Semester</th>
                                <th>Status</th>
                                <th>Mappings</th>
                                <th>Created By</th>
                                <th>Published By</th>
                                <th>Active</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lists as $list)
                            <tr>
                                <td>
                                    <span class="badge bg-primary">{{ $list->program_code }}</span>
                                </td>
                                <td>
                                    @if($list->category === 'internal')
                                        <span class="badge bg-primary"><i class="fas fa-building me-1"></i>Internal</span>
                                    @else
                                        <span class="badge bg-info"><i class="fas fa-university me-1"></i>External</span>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ $list->category === 'internal' ? 'CS110' : Str::limit($list->source_institution, 25) }}</small>
                                </td>
                                <td>{{ $list->semester }}</td>
                                <td>
                                    @if($list->published_at)
                                        <span class="badge bg-success">Published</span>
                                        <br><small class="text-muted">{{ $list->published_at->format('d M Y') }}</small>
                                    @elseif($list->status === 'draft')
                                        <span class="badge bg-warning text-dark">Draft</span>
                                    @elseif($list->status === 'archived')
                                        <span class="badge bg-secondary">Archived</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $list->total_equivalencies }}</span>
                                </td>
                                <td><small>{{ $list->creator->name ?? 'N/A' }}</small></td>
                                <td><small>{{ $list->publisher->name ?? '-' }}</small></td>
                                <td>
                                    @if($list->is_active)
                                        <span class="badge bg-success"><i class="fas fa-check-circle"></i></span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('hea.equivalency_lists.show', $list) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if(!$list->published_at && !$list->endorsed_at)
                                        <form action="{{ route('hea.equivalency_lists.destroy', $list) }}" method="POST" class="d-inline ms-1" onsubmit="return confirm('Are you sure you want to delete this draft list for {{ $list->semester }}? This action cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Draft">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($lists->hasPages())
                    <div class="card-footer">
                        {{ $lists->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>

    <!-- Quick Links -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-file-alt fa-3x text-warning mb-3"></i>
                    <h6>Draft Lists</h6>
                    <p class="text-muted small mb-3">View lists being prepared by Program Coordinators</p>
                    <a href="{{ route('hea.equivalency_lists.drafts') }}" class="btn btn-sm btn-warning">
                        View Drafts ({{ $stats['draft'] }})
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                    <h6>Published Lists</h6>
                    <p class="text-muted small mb-3">View archive of all published equivalency lists</p>
                    <a href="{{ route('hea.equivalency_lists.published') }}" class="btn btn-sm btn-success">
                        View Published ({{ $stats['published'] }})
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-chart-bar fa-3x text-primary mb-3"></i>
                    <h6>Statistics & Reports</h6>
                    <p class="text-muted small mb-3">View comprehensive statistics and reports</p>
                    <a href="{{ route('hea.equivalency_lists.statistics') }}" class="btn btn-sm btn-primary">
                        View Statistics
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success/Error Toast Notifications -->
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
