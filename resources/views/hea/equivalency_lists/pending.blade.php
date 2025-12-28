@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-hourglass-half text-warning me-2"></i>Pending Endorsement Requests</h2>
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
    <div class="col-md-4">
        <div class="card text-center border-info">
            <div class="card-body">
                <h3 class="mb-0 text-info">{{ $pendingCounts['total'] }}</h3>
                <p class="text-muted mb-0">Total Pending</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center border-primary">
            <div class="card-body">
                <h3 class="mb-0 text-primary">{{ $pendingCounts['internal'] }}</h3>
                <p class="text-muted mb-0">CS110 (UiTM Diploma)</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center border-success">
            <div class="card-body">
                <h3 class="mb-0 text-success">{{ $pendingCounts['external'] }}</h3>
                <p class="text-muted mb-0">External Institutions</p>
            </div>
        </div>
    </div>
</div>

<!-- Category Filter Tabs -->
<ul class="nav nav-tabs mb-4">
    <li class="nav-item">
        <a class="nav-link {{ $categoryFilter === 'all' ? 'active' : '' }}" href="{{ route('hea.equivalency_lists.pending', ['category' => 'all']) }}">
            All Pending <span class="badge bg-secondary">{{ $pendingCounts['total'] }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $categoryFilter === 'internal' ? 'active' : '' }}" href="{{ route('hea.equivalency_lists.pending', ['category' => 'internal']) }}">
            CS110 (UiTM Diploma) <span class="badge bg-primary">{{ $pendingCounts['internal'] }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $categoryFilter === 'external' ? 'active' : '' }}" href="{{ route('hea.equivalency_lists.pending', ['category' => 'external']) }}">
            External Institutions <span class="badge bg-success">{{ $pendingCounts['external'] }}</span>
        </a>
    </li>
</ul>

@if($pendingCounts['total'] === 0)
<div class="card">
    <div class="card-body text-center py-5">
        <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
        <h4>All Caught Up!</h4>
        <p class="text-muted">There are no pending equivalency lists waiting for endorsement.</p>
        <a href="{{ route('hea.equivalency_lists.published') }}" class="btn btn-outline-success">
            <i class="fas fa-book me-1"></i> View Published Lists
        </a>
    </div>
</div>
@else

<!-- CS110 (Internal) Lists Section -->
@if($categoryFilter === 'all' || $categoryFilter === 'internal')
@if($internalLists->count() > 0)
<div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <span class="me-2">CS110 (UiTM Diploma)</span>
            <span class="badge bg-light text-primary">{{ $internalLists->count() }} pending</span>
        </h5>
        <small>Mappings from UiTM's Diploma in Computer Science to degree programs</small>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Target Program</th>
                        <th>Semester</th>
                        <th>Submitted By</th>
                        <th>Submitted</th>
                        <th>Mappings</th>
                        <th>Status</th>
                        <th width="120">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($internalLists as $list)
                    <tr>
                        <td>
                            <div class="fw-bold">{{ $list->program_code }}</div>
                            <small class="text-muted">{{ $list->program_name }}</small>
                        </td>
                        <td>{{ $list->target_semester ?? $list->semester }}</td>
                        <td>{{ $list->creator->name ?? 'Unknown' }}</td>
                        <td>
                            <div>{{ $list->submitted_at?->format('d M Y') }}</div>
                            <small class="text-muted">{{ $list->submitted_at?->format('H:i') }}</small>
                        </td>
                        <td>
                            <strong>{{ $list->total_equivalencies }}</strong>
                            <span class="text-muted">({{ $list->eligible_count }} eligible)</span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $list->status_badge_class }}">{{ $list->status_display }}</span>
                        </td>
                        <td>
                            <a href="{{ route('hea.equivalency_lists.review', $list) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-clipboard-check me-1"></i> Review
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endif

<!-- External Institutions Lists Section -->
@if($categoryFilter === 'all' || $categoryFilter === 'external')
@if($externalLists->count() > 0)
<div class="card shadow-sm mb-4">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0">
            <span class="me-2">External Institutions</span>
            <span class="badge bg-light text-success">{{ $externalLists->count() }} pending</span>
        </h5>
        <small>Mappings from external diplomas (Politeknik, UTM, etc.) to degree programs</small>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Source Institution</th>
                        <th>Target Program</th>
                        <th>Semester</th>
                        <th>Submitted By</th>
                        <th>Submitted</th>
                        <th>Mappings</th>
                        <th>Status</th>
                        <th width="120">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($externalLists as $list)
                    <tr>
                        <td>
                            <span class="badge bg-success">{{ $list->source_institution }}</span>
                        </td>
                        <td>
                            <div class="fw-bold">{{ $list->program_code }}</div>
                            <small class="text-muted">{{ $list->program_name }}</small>
                        </td>
                        <td>{{ $list->target_semester ?? $list->semester }}</td>
                        <td>{{ $list->creator->name ?? 'Unknown' }}</td>
                        <td>
                            <div>{{ $list->submitted_at?->format('d M Y') }}</div>
                            <small class="text-muted">{{ $list->submitted_at?->format('H:i') }}</small>
                        </td>
                        <td>
                            <strong>{{ $list->total_equivalencies }}</strong>
                            <span class="text-muted">({{ $list->eligible_count }} eligible)</span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $list->status_badge_class }}">{{ $list->status_display }}</span>
                        </td>
                        <td>
                            <a href="{{ route('hea.equivalency_lists.review', $list) }}" class="btn btn-sm btn-success">
                                <i class="fas fa-clipboard-check me-1"></i> Review
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endif

@endif
@endsection
