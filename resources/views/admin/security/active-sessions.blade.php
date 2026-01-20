@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Active Sessions</h2>
        <p class="text-muted mb-0">Monitor and manage user sessions</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
    </a>
</div>

<!-- Statistics -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card border-0 bg-info bg-opacity-10">
            <div class="card-body text-center">
                <h4 class="mb-0 text-info">{{ $stats['total_active'] }}</h4>
                <small class="text-muted">Total Active Sessions</small>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 bg-success bg-opacity-10">
            <div class="card-body text-center">
                <h4 class="mb-0 text-success">{{ $stats['active_30min'] }}</h4>
                <small class="text-muted">Active in Last 30 Minutes</small>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Sessions Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>IP Address</th>
                        <th>Last Activity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sessions as $session)
                    <tr class="{{ $session->is_current ? 'table-info' : '' }}">
                        <td>
                            {{ $session->user_name ?? 'Unknown' }}
                            @if($session->is_current)
                            <span class="badge bg-primary ms-1">Current</span>
                            @endif
                        </td>
                        <td>{{ $session->user_email ?? '-' }}</td>
                        <td><span class="badge bg-secondary text-capitalize">{{ str_replace('_', ' ', $session->current_role ?? '-') }}</span></td>
                        <td><code>{{ $session->ip_address }}</code></td>
                        <td>
                            <small>{{ $session->last_activity_at->diffForHumans() }}</small>
                            <br><small class="text-muted">{{ $session->last_activity_at->format('M d, H:i') }}</small>
                        </td>
                        <td>
                            @if(!$session->is_current)
                            <form action="{{ route('admin.security.sessions.terminate', $session->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to terminate this session?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-times"></i> Terminate
                                </button>
                            </form>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No active sessions found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($sessions->hasPages())
    <div class="card-footer">
        {{ $sessions->links() }}
    </div>
    @endif
</div>
@endsection
