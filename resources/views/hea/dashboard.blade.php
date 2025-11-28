@extends('layouts.app')

@section('content')
<h2 class="mb-4">HEA Dashboard</h2>

<!-- Stat Cards -->
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

<!-- Quick Actions Card -->
<div class="card shadow-sm">
    <div class="card-header bg-light py-3">
        <h5 class="mb-0">System Management</h5>
    </div>
    <div class="card-body">
        <p>Select an option below to manage users, monitor applications, or review system activity.</p>
        <div class="list-group">
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
