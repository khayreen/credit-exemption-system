@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Access Logs</h2>
        <p class="text-muted mb-0">Monitor unauthorized access attempts</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
    </a>
</div>

<!-- Statistics -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card border-0 bg-danger bg-opacity-10">
            <div class="card-body text-center">
                <h4 class="mb-0 text-danger">{{ $stats['total_denied'] }}</h4>
                <small class="text-muted">Total Denied Access</small>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 bg-warning bg-opacity-10">
            <div class="card-body text-center">
                <h4 class="mb-0 text-warning">{{ $stats['denied_24h'] }}</h4>
                <small class="text-muted">Denied (Last 24h)</small>
            </div>
        </div>
    </div>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('admin.security.access-logs') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All</option>
                    <option value="denied" {{ request('status') == 'denied' ? 'selected' : '' }}>Denied</option>
                    <option value="allowed" {{ request('status') == 'allowed' ? 'selected' : '' }}>Allowed</option>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter me-1"></i>Filter
                </button>
                <a href="{{ route('admin.security.access-logs') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>User</th>
                        <th>Route</th>
                        <th>Method</th>
                        <th>IP Address</th>
                        <th>Status</th>
                        <th>Denial Reason</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($accessLogs as $log)
                    <tr>
                        <td>
                            @if($log->user)
                            {{ $log->user->name }}
                            @else
                            <span class="text-muted">Guest</span>
                            @endif
                        </td>
                        <td><code>{{ $log->route }}</code></td>
                        <td><span class="badge bg-secondary">{{ $log->method }}</span></td>
                        <td><code>{{ $log->ip_address }}</code></td>
                        <td>
                            @if($log->status === 'allowed')
                            <span class="badge bg-success">Allowed</span>
                            @else
                            <span class="badge bg-danger">Denied</span>
                            @endif
                        </td>
                        <td>{{ $log->denial_reason ?? '-' }}</td>
                        <td><small>{{ $log->created_at->format('M d, Y H:i') }}</small></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No access logs found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($accessLogs->hasPages())
    <div class="card-footer">
        {{ $accessLogs->links() }}
    </div>
    @endif
</div>
@endsection
