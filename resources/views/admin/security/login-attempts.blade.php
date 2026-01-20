@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Login Attempts</h2>
        <p class="text-muted mb-0">Monitor authentication activity</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
    </a>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-0 bg-light">
            <div class="card-body text-center">
                <h4 class="mb-0">{{ $stats['total_attempts'] }}</h4>
                <small class="text-muted">Total Attempts</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 bg-danger bg-opacity-10">
            <div class="card-body text-center">
                <h4 class="mb-0 text-danger">{{ $stats['failed_24h'] }}</h4>
                <small class="text-muted">Failed (24h)</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 bg-success bg-opacity-10">
            <div class="card-body text-center">
                <h4 class="mb-0 text-success">{{ $stats['success_24h'] }}</h4>
                <small class="text-muted">Successful (24h)</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 bg-info bg-opacity-10">
            <div class="card-body text-center">
                <h4 class="mb-0 text-info">{{ $stats['unique_ips_24h'] }}</h4>
                <small class="text-muted">Unique IPs (24h)</small>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('admin.security.login-attempts') }}" method="GET" class="row g-3">
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Success</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Email</label>
                <input type="text" name="email" class="form-control" value="{{ request('email') }}" placeholder="Search email...">
            </div>
            <div class="col-md-2">
                <label class="form-label">IP Address</label>
                <input type="text" name="ip" class="form-control" value="{{ request('ip') }}" placeholder="IP...">
            </div>
            <div class="col-md-2">
                <label class="form-label">From Date</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">To Date</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Login Attempts Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Email</th>
                        <th>User</th>
                        <th>IP Address</th>
                        <th>Status</th>
                        <th>Reason</th>
                        <th>User Agent</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loginAttempts as $attempt)
                    <tr>
                        <td>{{ $attempt->email }}</td>
                        <td>
                            @if($attempt->user)
                            <span class="badge bg-secondary">{{ $attempt->user->name }}</span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td><code>{{ $attempt->ip_address }}</code></td>
                        <td>
                            @if($attempt->status === 'success')
                            <span class="badge bg-success">Success</span>
                            @else
                            <span class="badge bg-danger">Failed</span>
                            @endif
                        </td>
                        <td>{{ $attempt->failure_reason ?? '-' }}</td>
                        <td><small class="text-muted" title="{{ $attempt->user_agent }}">{{ Str::limit($attempt->user_agent, 30) }}</small></td>
                        <td><small>{{ $attempt->attempted_at->format('M d, Y H:i:s') }}</small></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No login attempts found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($loginAttempts->hasPages())
    <div class="card-footer">
        {{ $loginAttempts->links() }}
    </div>
    @endif
</div>
@endsection
