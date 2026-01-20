@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">System Administration</h2>
        <p class="text-muted mb-0">Welcome back, {{ auth()->user()->name }}</p>
    </div>
    <div class="text-end">
        <span class="badge bg-dark"><i class="fas fa-shield-alt me-1"></i> Administrator</span>
        <br><small class="text-muted">{{ now()->format('l, F d, Y') }}</small>
    </div>
</div>

<!-- Security Alerts Row -->
@if($lockedAccountsCount > 0 || $securityEventsLast24h > 0)
<div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
    <i class="fas fa-exclamation-triangle me-2"></i>
    <strong>Security Alert:</strong>
    @if($lockedAccountsCount > 0)
        {{ $lockedAccountsCount }} locked account(s).
    @endif
    @if($securityEventsLast24h > 0)
        {{ $securityEventsLast24h }} security event(s) in the last 24 hours.
    @endif
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- Quick Stats Row -->
<div class="row mb-4">
    <!-- Pending HEA Approvals -->
    <div class="col-md-3 mb-3">
        <a href="{{ route('admin.hea.approvals') }}" class="text-decoration-none">
            <div class="card stat-card border-warning h-100 hover-shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="stat-icon bg-warning me-3">
                            <i class="fas fa-user-clock text-white"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 text-warning">{{ $pendingHeaCount }}</h3>
                        </div>
                    </div>
                    <p class="card-text text-muted mb-0 fw-bold">Pending HEA Approvals</p>
                    <small class="text-muted">Awaiting review</small>
                </div>
            </div>
        </a>
    </div>

    <!-- Failed Logins (24h) -->
    <div class="col-md-3 mb-3">
        <a href="{{ route('admin.security.login-attempts') }}" class="text-decoration-none">
            <div class="card stat-card border-danger h-100 hover-shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="stat-icon bg-danger me-3">
                            <i class="fas fa-shield-alt text-white"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 text-danger">{{ $failedLoginsLast24h }}</h3>
                        </div>
                    </div>
                    <p class="card-text text-muted mb-0 fw-bold">Failed Logins (24h)</p>
                    <small class="text-muted">{{ $failedLoginsLast7d }} in last 7 days</small>
                </div>
            </div>
        </a>
    </div>

    <!-- Active Sessions -->
    <div class="col-md-3 mb-3">
        <a href="{{ route('admin.security.sessions') }}" class="text-decoration-none">
            <div class="card stat-card border-info h-100 hover-shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="stat-icon bg-info me-3">
                            <i class="fas fa-users-cog text-white"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 text-info">{{ $activeSessionsCount }}</h3>
                        </div>
                    </div>
                    <p class="card-text text-muted mb-0 fw-bold">Active Sessions</p>
                    <small class="text-muted">Online in last 30 min</small>
                </div>
            </div>
        </a>
    </div>

    <!-- Locked Accounts -->
    <div class="col-md-3 mb-3">
        <a href="{{ route('admin.security.locked-accounts') }}" class="text-decoration-none">
            <div class="card stat-card border-dark h-100 hover-shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="stat-icon bg-dark me-3">
                            <i class="fas fa-lock text-white"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 text-dark">{{ $lockedAccountsCount }}</h3>
                        </div>
                    </div>
                    <p class="card-text text-muted mb-0 fw-bold">Locked Accounts</p>
                    <small class="text-muted">Requires attention</small>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Content & User Stats Row -->
<div class="row mb-4">
    <!-- Content Stats -->
    <div class="col-md-4 mb-3">
        <div class="card h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-file-alt me-2 text-primary"></i>Content Management</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span><i class="fas fa-bullhorn me-2 text-info"></i>Active Announcements</span>
                    <span class="badge bg-info">{{ $announcementsCount }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span><i class="fas fa-question-circle me-2 text-success"></i>FAQ Items</span>
                    <span class="badge bg-success">{{ $faqCount }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-book me-2 text-warning"></i>Help Articles</span>
                    <span class="badge bg-warning text-dark">{{ $helpArticlesCount }}</span>
                </div>
                <hr>
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.content.terms.index') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-file-contract me-1"></i>Manage Terms & Conditions
                    </a>
                    <a href="{{ route('admin.content.announcements.index') }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-bullhorn me-1"></i>Manage Announcements
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- User Distribution -->
    <div class="col-md-4 mb-3">
        <div class="card h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-users me-2 text-success"></i>User Statistics</h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Total Users: <strong>{{ $totalUsers }}</strong></p>
                @foreach($usersByRole as $role => $count)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-capitalize">{{ str_replace('_', ' ', $role) }}</span>
                    <span class="badge bg-secondary">{{ $count }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-md-4 mb-3">
        <div class="card h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-bolt me-2 text-warning"></i>Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.hea.approvals') }}" class="btn btn-warning">
                        <i class="fas fa-user-check me-2"></i>Review HEA Registrations
                        @if($pendingHeaCount > 0)
                        <span class="badge bg-dark ms-2">{{ $pendingHeaCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.security.login-attempts') }}" class="btn btn-outline-danger">
                        <i class="fas fa-history me-2"></i>View Login History
                    </a>
                    <a href="{{ route('admin.security.security-events') }}" class="btn btn-outline-dark">
                        <i class="fas fa-shield-virus me-2"></i>Security Events
                    </a>
                    <a href="{{ route('admin.content.faq.index') }}" class="btn btn-outline-success">
                        <i class="fas fa-question-circle me-2"></i>Manage FAQ
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity Row -->
<div class="row">
    <!-- Recent Failed Logins -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-exclamation-circle me-2 text-danger"></i>Recent Failed Logins</h5>
                <a href="{{ route('admin.security.login-attempts', ['status' => 'failed']) }}" class="btn btn-sm btn-outline-danger">View All</a>
            </div>
            <div class="card-body p-0">
                @if($recentFailedLogins->isEmpty())
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-check-circle fa-2x mb-2 text-success"></i>
                    <p class="mb-0">No recent failed login attempts</p>
                </div>
                @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Email</th>
                                <th>IP Address</th>
                                <th>Reason</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentFailedLogins as $attempt)
                            <tr>
                                <td>{{ Str::limit($attempt->email, 25) }}</td>
                                <td><code>{{ $attempt->ip_address }}</code></td>
                                <td><span class="badge bg-danger">{{ $attempt->failure_reason ?? 'Unknown' }}</span></td>
                                <td><small>{{ $attempt->attempted_at->diffForHumans() }}</small></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Security Events -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-shield-virus me-2 text-warning"></i>Recent Security Events</h5>
                <a href="{{ route('admin.security.security-events') }}" class="btn btn-sm btn-outline-warning">View All</a>
            </div>
            <div class="card-body p-0">
                @if($recentSecurityEvents->isEmpty())
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-shield-alt fa-2x mb-2 text-success"></i>
                    <p class="mb-0">No recent security events</p>
                </div>
                @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Event Type</th>
                                <th>IP Address</th>
                                <th>Status</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentSecurityEvents as $event)
                            <tr>
                                <td><span class="badge bg-{{ $event->badge_color }}">{{ $event->event_type_label }}</span></td>
                                <td><code>{{ $event->ip_address }}</code></td>
                                <td>
                                    @if($event->blocked)
                                    <span class="badge bg-success">Blocked</span>
                                    @else
                                    <span class="badge bg-warning">Allowed</span>
                                    @endif
                                </td>
                                <td><small>{{ $event->created_at->diffForHumans() }}</small></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .stat-card {
        transition: all 0.3s ease;
        border-left: 4px solid;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }
    .hover-shadow:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
</style>
@endpush
