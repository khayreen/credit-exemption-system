@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Security Events</h2>
        <p class="text-muted mb-0">Monitor security threats and blocked attacks</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
    </a>
</div>

<!-- Statistics -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-0 bg-danger bg-opacity-10">
            <div class="card-body text-center">
                <h4 class="mb-0 text-danger">{{ $stats['total_events'] }}</h4>
                <small class="text-muted">Total Events</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 bg-warning bg-opacity-10">
            <div class="card-body text-center">
                <h4 class="mb-0 text-warning">{{ $stats['events_24h'] }}</h4>
                <small class="text-muted">Events (24h)</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 bg-success bg-opacity-10">
            <div class="card-body text-center">
                <h4 class="mb-0 text-success">{{ $stats['blocked_24h'] }}</h4>
                <small class="text-muted">Blocked (24h)</small>
            </div>
        </div>
    </div>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('admin.security.security-events') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Event Type</label>
                <select name="event_type" class="form-select">
                    <option value="">All Types</option>
                    @foreach($eventTypes as $value => $label)
                    <option value="{{ $value }}" {{ request('event_type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter me-1"></i>Filter
                </button>
                <a href="{{ route('admin.security.security-events') }}" class="btn btn-outline-secondary">Reset</a>
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
                        <th>Event Type</th>
                        <th>User</th>
                        <th>IP Address</th>
                        <th>Route</th>
                        <th>Blocked</th>
                        <th>Details</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($securityEvents as $event)
                    <tr>
                        <td><span class="badge bg-{{ $event->badge_color }}">{{ $event->event_type_label }}</span></td>
                        <td>
                            @if($event->user)
                            {{ $event->user->name }}
                            @else
                            <span class="text-muted">Guest</span>
                            @endif
                        </td>
                        <td><code>{{ $event->ip_address }}</code></td>
                        <td><code>{{ $event->route }}</code></td>
                        <td>
                            @if($event->blocked)
                            <span class="badge bg-success">Yes</span>
                            @else
                            <span class="badge bg-warning">No</span>
                            @endif
                        </td>
                        <td><small>{{ Str::limit($event->details, 50) }}</small></td>
                        <td><small>{{ $event->created_at->format('M d, Y H:i') }}</small></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-shield-alt fa-3x text-success mb-3"></i>
                            <p class="text-muted mb-0">No security events recorded</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($securityEvents->hasPages())
    <div class="card-footer">
        {{ $securityEvents->links() }}
    </div>
    @endif
</div>
@endsection
