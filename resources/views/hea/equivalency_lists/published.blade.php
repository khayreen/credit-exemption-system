@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-book text-success me-2"></i>Published Equivalency Lists Archive</h2>
    <div>
        <a href="{{ route('hea.equivalency_lists.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-list me-1"></i> All Lists
        </a>
        <a href="{{ route('hea.dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Dashboard
        </a>
    </div>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-center border-success">
            <div class="card-body py-3">
                <h3 class="mb-0 text-success">{{ $publishedCounts['total'] }}</h3>
                <small class="text-muted">Total Published</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center border-primary">
            <div class="card-body py-3">
                <h3 class="mb-0 text-primary">{{ $publishedCounts['internal'] }}</h3>
                <small class="text-muted">CS110 (Internal)</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center border-info">
            <div class="card-body py-3">
                <h3 class="mb-0 text-info">{{ $publishedCounts['external'] }}</h3>
                <small class="text-muted">External Institutions</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center border-warning">
            <div class="card-body py-3">
                <h3 class="mb-0 text-warning">{{ $publishedCounts['active'] }}</h3>
                <small class="text-muted">Currently Active</small>
            </div>
        </div>
    </div>
</div>

<!-- Filter Card -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-light py-2">
        <form method="GET" action="{{ route('hea.equivalency_lists.published') }}" class="row g-2 align-items-center">
            <div class="col-auto">
                <label class="me-2">Program:</label>
                <select name="program" class="form-select form-select-sm d-inline-block w-auto" onchange="this.form.submit()">
                    <option value="all" {{ $programFilter === 'all' ? 'selected' : '' }}>All Programs</option>
                    @foreach($programs as $code => $name)
                        <option value="{{ $code }}" {{ $programFilter === $code ? 'selected' : '' }}>{{ $code }} - {{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <label class="me-2">Category:</label>
                <select name="category" class="form-select form-select-sm d-inline-block w-auto" onchange="this.form.submit()">
                    <option value="all" {{ $categoryFilter === 'all' ? 'selected' : '' }}>All Categories</option>
                    <option value="internal" {{ $categoryFilter === 'internal' ? 'selected' : '' }}>CS110 (UiTM Diploma)</option>
                    <option value="external" {{ $categoryFilter === 'external' ? 'selected' : '' }}>External Institutions</option>
                </select>
            </div>
            <div class="col-auto ms-auto">
                <a href="{{ route('hea.equivalency_lists.published') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-redo me-1"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

@if($lists->count() === 0)
<div class="card">
    <div class="card-body text-center py-5">
        <i class="fas fa-folder-open fa-4x text-muted mb-3"></i>
        <h4>No Published Lists Found</h4>
        <p class="text-muted">There are no published equivalency lists matching your filters.</p>
    </div>
</div>
@else

<!-- Lists by Program -->
@foreach($lists as $programCode => $programLists)
<div class="card shadow-sm mb-4">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0">{{ $programCode }} - {{ $programs[$programCode] ?? 'Unknown Program' }}</h5>
        <small>Resource Person: {{ $programLists->first()->creator->name ?? 'Unknown' }}</small>
    </div>
    <div class="card-body">
        @php
            $internalLists = $programLists->where('category', 'internal');
            $externalLists = $programLists->where('category', 'external')->groupBy('source_institution');
        @endphp

        <!-- CS110 (Internal) Lists -->
        @if($internalLists->count() > 0 && ($categoryFilter === 'all' || $categoryFilter === 'internal'))
        <div class="mb-4">
            <h6 class="text-primary"><i class="fas fa-home me-1"></i> CS110 (UiTM Diploma) Lists</h6>
            <div class="list-group">
                @foreach($internalLists->sortByDesc('published_at') as $list)
                <div class="list-group-item d-flex justify-content-between align-items-center {{ $list->is_active ? 'border-success' : '' }}">
                    <div>
                        <div class="d-flex align-items-center">
                            @if($list->is_active)
                                <span class="badge bg-success me-2">ACTIVE</span>
                            @else
                                <span class="badge bg-secondary me-2">Archived</span>
                            @endif
                            <strong>{{ $list->semester }}</strong>
                            <span class="mx-2 text-muted">|</span>
                            <span>{{ $list->total_equivalencies }} mappings</span>
                        </div>
                        <small class="text-muted">
                            Published: {{ $list->published_at?->format('d M Y, H:i') }}
                            @if($list->endorser)
                                by {{ $list->endorser->name }}
                            @endif
                        </small>
                    </div>
                    <div>
                        <a href="{{ route('hea.equivalency_lists.show', $list) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye me-1"></i> View
                        </a>
                        <a href="{{ route('hea.equivalency_lists.pdf', $list) }}" class="btn btn-sm btn-outline-secondary" target="_blank">
                            <i class="fas fa-file-pdf me-1"></i> PDF
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- External Institutions Lists -->
        @if($externalLists->count() > 0 && ($categoryFilter === 'all' || $categoryFilter === 'external'))
        <div class="mb-3">
            <h6 class="text-success"><i class="fas fa-globe me-1"></i> External Institution Lists</h6>

            @foreach($externalLists as $institution => $institutionLists)
            <div class="mb-3">
                <div class="d-flex align-items-center mb-2">
                    <span class="badge bg-success me-2">{{ $institution }}</span>
                    <small class="text-muted">{{ $institutionLists->count() }} semester(s)</small>
                </div>
                <div class="list-group">
                    @foreach($institutionLists->sortByDesc('published_at') as $list)
                    <div class="list-group-item d-flex justify-content-between align-items-center {{ $list->is_active ? 'border-success' : '' }}">
                        <div>
                            <div class="d-flex align-items-center">
                                @if($list->is_active)
                                    <span class="badge bg-success me-2">ACTIVE</span>
                                @else
                                    <span class="badge bg-secondary me-2">Archived</span>
                                @endif
                                <strong>{{ $list->semester }}</strong>
                                <span class="mx-2 text-muted">|</span>
                                <span>{{ $list->total_equivalencies }} mappings</span>
                            </div>
                            <small class="text-muted">
                                Published: {{ $list->published_at?->format('d M Y, H:i') }}
                                @if($list->endorser)
                                    by {{ $list->endorser->name }}
                                @endif
                            </small>
                        </div>
                        <div>
                            <a href="{{ route('hea.equivalency_lists.show', $list) }}" class="btn btn-sm btn-outline-success">
                                <i class="fas fa-eye me-1"></i> View
                            </a>
                            <a href="{{ route('hea.equivalency_lists.pdf', $list) }}" class="btn btn-sm btn-outline-secondary" target="_blank">
                                <i class="fas fa-file-pdf me-1"></i> PDF
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endforeach

@endif
@endsection
