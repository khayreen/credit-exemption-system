@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">All Users</h2>
        <p class="text-muted mb-0">Manage user accounts and lock/unlock access</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
    </a>
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

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-0 bg-light">
            <div class="card-body text-center">
                <h4 class="mb-0">{{ $stats['total_users'] }}</h4>
                <small class="text-muted">Total Users</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 bg-success bg-opacity-10">
            <div class="card-body text-center">
                <h4 class="mb-0 text-success">{{ $stats['active_users'] }}</h4>
                <small class="text-muted">Active Users</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 bg-danger bg-opacity-10">
            <div class="card-body text-center">
                <h4 class="mb-0 text-danger">{{ $stats['locked_users'] }}</h4>
                <small class="text-muted">Locked Users</small>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('admin.security.all-users') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Name or email...">
            </div>
            <div class="col-md-3">
                <label class="form-label">Role</label>
                <select name="role" class="form-select">
                    <option value="">All Roles</option>
                    @foreach($roles as $value => $label)
                    <option value="{{ $value }}" {{ request('role') == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="locked" {{ request('status') == 'locked' ? 'selected' : '' }}>Locked</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="{{ route('admin.security.all-users') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Users Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge bg-secondary text-capitalize">
                                {{ str_replace('_', ' ', $user->current_role ?? '-') }}
                            </span>
                        </td>
                        <td>
                            @if($user->locked_at)
                            <span class="badge bg-danger">
                                <i class="fas fa-lock me-1"></i>Locked
                            </span>
                            @else
                            <span class="badge bg-success">
                                <i class="fas fa-check me-1"></i>Active
                            </span>
                            @endif
                        </td>
                        <td><small>{{ $user->created_at->format('M d, Y') }}</small></td>
                        <td>
                            @if($user->id !== auth()->id())
                                @if($user->locked_at)
                                <form action="{{ route('admin.security.accounts.unlock', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to unlock this account?')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="fas fa-unlock"></i> Unlock
                                    </button>
                                </form>
                                @else
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#lockModal{{ $user->id }}">
                                    <i class="fas fa-lock"></i> Lock
                                </button>
                                @endif
                            @else
                            <span class="text-muted small">(Your account)</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No users found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($users->hasPages())
    <div class="card-footer">
        {{ $users->links() }}
    </div>
    @endif
</div>

<!-- Lock Modals -->
@foreach($users as $user)
@if(!$user->locked_at && $user->id !== auth()->id())
<div class="modal fade" id="lockModal{{ $user->id }}" tabindex="-1" aria-labelledby="lockModalLabel{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.security.accounts.lock', $user) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="lockModalLabel{{ $user->id }}">Lock Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to lock the account for <strong>{{ $user->name }}</strong> ({{ $user->email }})?</p>
                    <p class="text-muted small">This user will not be able to log in until their account is unlocked.</p>
                    <div class="mb-3">
                        <label for="reason{{ $user->id }}" class="form-label">Lock Reason <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="reason{{ $user->id }}" name="reason" required placeholder="Enter reason for locking this account...">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-lock me-1"></i>Lock Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endforeach
@endsection
