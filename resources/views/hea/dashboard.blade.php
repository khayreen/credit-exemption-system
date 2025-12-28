@extends('layouts.app')

@section('content')
<h2 class="mb-4">HEA Dashboard</h2>

<!-- Stat Cards Row 1 -->
<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon icon-blue"><i class="fas fa-users"></i></div>
                <div>
                    <h5 class="card-title mb-0">{{ $stats['total_users'] }}</h5>
                    <p class="card-text text-muted">Total Users</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon icon-green"><i class="fas fa-file-alt"></i></div>
                <div>
                    <h5 class="card-title mb-0">{{ $stats['total_applications'] }}</h5>
                    <p class="card-text text-muted">Total Applications</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon icon-orange"><i class="fas fa-hourglass-half"></i></div>
                <div>
                    <h5 class="card-title mb-0">{{ $stats['pending_applications'] }}</h5>
                    <p class="card-text text-muted">Pending Applications</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon icon-red"><i class="fas fa-history"></i></div>
                <div>
                    <h5 class="card-title mb-0">{{ $stats['total_logs'] }}</h5>
                    <p class="card-text text-muted">Audit Log Entries</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Equivalency Lists Stats Row -->
<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card stat-card border-info">
            <div class="d-flex align-items-center">
                <div class="stat-icon" style="background-color: #17a2b8;"><i class="fas fa-clipboard-check"></i></div>
                <div>
                    <h5 class="card-title mb-0">{{ $stats['pending_endorsements'] }}</h5>
                    <p class="card-text text-muted">Pending Endorsements</p>
                    <small class="text-muted">{{ $stats['pending_internal'] }} Internal / {{ $stats['pending_external'] }} External</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card stat-card border-success">
            <div class="d-flex align-items-center">
                <div class="stat-icon icon-green"><i class="fas fa-check-circle"></i></div>
                <div>
                    <h5 class="card-title mb-0">{{ $stats['published_lists'] }}</h5>
                    <p class="card-text text-muted">Published Lists</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pending Endorsement Requests Section -->
@if(isset($pendingLists) && $pendingLists->count() > 0)
<div class="card shadow-sm mb-4">
    <div class="card-header bg-info text-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-bell me-2"></i>Pending Endorsement Requests</h5>
        <a href="{{ route('hea.equivalency_lists.pending') }}" class="btn btn-sm btn-light">View All</a>
    </div>
    <div class="card-body p-0">
        <div class="list-group list-group-flush">
            @foreach($pendingLists as $list)
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <div class="d-flex align-items-center mb-1">
                        @if($list->isInternal())
                            <span class="badge bg-primary me-2" title="Internal - UiTM Diploma">CS110</span>
                        @else
                            <span class="badge bg-success me-2" title="External Institution">{{ $list->source_institution }}</span>
                        @endif
                        <strong>{{ $list->program_code }}</strong>
                        <span class="mx-2 text-muted">|</span>
                        <span>{{ $list->semester }}</span>
                        <span class="mx-2 text-muted">|</span>
                        <span class="text-muted">{{ $list->total_equivalencies }} mappings</span>
                    </div>
                    <small class="text-muted">
                        Submitted by: {{ $list->creator->name ?? 'Unknown' }}
                        <span class="mx-1">|</span>
                        {{ $list->submitted_at?->format('d M Y, H:i') }}
                    </small>
                </div>
                <a href="{{ route('hea.equivalency_lists.review', $list) }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-eye me-1"></i>Review
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Quick Actions Card -->
<div class="card shadow-sm">
    <div class="card-header bg-light py-3">
        <h5 class="mb-0">System Management</h5>
    </div>
    <div class="card-body">
        <p>Select an option below to manage users, monitor applications, review equivalency lists, or review system activity.</p>
        <div class="list-group">
            <a href="{{ route('hea.equivalency_lists.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                <span>
                    <i class="fas fa-clipboard-list me-2 text-info"></i>
                    Course Equivalency Lists
                </span>
                @if($stats['pending_endorsements'] > 0)
                    <span class="badge bg-info">{{ $stats['pending_endorsements'] }} pending</span>
                @else
                    <i class="fas fa-check text-success"></i>
                @endif
            </a>
            <a href="{{ route('hea.equivalency_lists.pending') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                <span>
                    <i class="fas fa-hourglass-half me-2 text-warning"></i>
                    Pending Endorsements
                </span>
                <span class="badge bg-warning text-dark">{{ $stats['pending_endorsements'] }}</span>
            </a>
            <a href="{{ route('hea.equivalency_lists.published') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                <span>
                    <i class="fas fa-book me-2 text-success"></i>
                    Published Lists Archive
                </span>
                <span class="badge bg-success">{{ $stats['published_lists'] }}</span>
            </a>
            <hr class="my-2">
            <a href="{{ route('hea.users.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                User Management <i class="fas fa-users-cog"></i>
            </a>
            <a href="{{ route('hea.applications.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                Application Monitoring <i class="fas fa-file-signature"></i>
            </a>
            <a href="{{ route('hea.logs.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                System Logs (Audit Trail) <i class="fas fa-clipboard-list"></i>
            </a>
            <a href="{{ route('hea.settings') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                System Settings <i class="fas fa-cogs"></i>
            </a>
        </div>
    </div>
</div>
@endsection
