@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Locked Accounts</h2>
        <p class="text-muted mb-0">Manage accounts that have been locked</p>
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

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Lock Reason</th>
                        <th>Locked At</th>
                        <th>Failed Attempts</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lockedUsers as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td><span class="badge bg-secondary text-capitalize">{{ str_replace('_', ' ', $user->current_role ?? '-') }}</span></td>
                        <td>{{ $user->lock_reason ?? 'Not specified' }}</td>
                        <td><small>{{ $user->locked_at->format('M d, Y H:i:s') }}</small></td>
                        <td><span class="badge bg-danger">{{ $user->failed_login_attempts }}</span></td>
                        <td>
                            <form action="{{ route('admin.security.accounts.unlock', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to unlock this account?')">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="fas fa-unlock"></i> Unlock
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <p class="text-muted mb-0">No locked accounts</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($lockedUsers->hasPages())
    <div class="card-footer">
        {{ $lockedUsers->links() }}
    </div>
    @endif
</div>
@endsection
