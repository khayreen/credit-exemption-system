@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Announcements</h2>
        <p class="text-muted mb-0">Manage system-wide announcements and banners</p>
    </div>
    <div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary me-2">
            <i class="fas fa-arrow-left me-1"></i>Back
        </a>
        <a href="{{ route('admin.content.announcements.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>New Announcement
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Target</th>
                        <th>Schedule</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($announcements as $announcement)
                    <tr>
                        <td>
                            <strong>{{ $announcement->title }}</strong>
                            <br><small class="text-muted">{{ Str::limit($announcement->content, 50) }}</small>
                        </td>
                        <td>
                            <span class="badge bg-{{ $announcement->type === 'danger' ? 'danger' : ($announcement->type === 'warning' ? 'warning' : ($announcement->type === 'success' ? 'success' : 'info')) }}">
                                {{ ucfirst($announcement->type) }}
                            </span>
                        </td>
                        <td>
                            @if($announcement->target_roles)
                                @foreach($announcement->target_roles as $role)
                                <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $role)) }}</span>
                                @endforeach
                            @else
                            <span class="badge bg-primary">All Users</span>
                            @endif
                        </td>
                        <td>
                            @if($announcement->starts_at || $announcement->ends_at)
                            <small>
                                {{ $announcement->starts_at ? $announcement->starts_at->format('M d') : 'Now' }}
                                -
                                {{ $announcement->ends_at ? $announcement->ends_at->format('M d') : 'Forever' }}
                            </small>
                            @else
                            <small class="text-muted">Always</small>
                            @endif
                        </td>
                        <td>
                            @if($announcement->is_active)
                            <span class="badge bg-success">Active</span>
                            @else
                            <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.content.announcements.edit', $announcement) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.content.announcements.toggle', $announcement) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-{{ $announcement->is_active ? 'warning' : 'success' }}" title="{{ $announcement->is_active ? 'Deactivate' : 'Activate' }}">
                                    <i class="fas fa-{{ $announcement->is_active ? 'pause' : 'play' }}"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.content.announcements.destroy', $announcement) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this announcement?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No announcements found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($announcements->hasPages())
    <div class="card-footer">
        {{ $announcements->links() }}
    </div>
    @endif
</div>
@endsection
