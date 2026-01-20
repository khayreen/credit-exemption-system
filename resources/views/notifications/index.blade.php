@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-2"><i class="fas fa-bell me-2"></i>Notifications</h2>
                    <p class="text-muted mb-0">View all your notifications</p>
                </div>
                @if($notifications->where('is_read', false)->count() > 0)
                    <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="fas fa-check-double me-2"></i>Mark All as Read
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Notifications List -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            @forelse($notifications as $notification)
                <a href="{{ route('notifications.read', $notification) }}"
                   class="notification-list-item d-flex align-items-start p-4 border-bottom text-decoration-none {{ !$notification->is_read ? 'bg-light-blue' : '' }}">
                    <div class="notification-icon me-3">
                        @switch($notification->type)
                            @case('request_reviewed')
                                <span class="badge bg-success rounded-circle p-3">
                                    <i class="fas fa-check fa-lg"></i>
                                </span>
                                @break
                            @case('new_mapping')
                                <span class="badge bg-info rounded-circle p-3">
                                    <i class="fas fa-link fa-lg"></i>
                                </span>
                                @break
                            @case('syllabus_received')
                                <span class="badge bg-primary rounded-circle p-3">
                                    <i class="fas fa-file-pdf fa-lg"></i>
                                </span>
                                @break
                            @case('new_request')
                                <span class="badge bg-warning rounded-circle p-3">
                                    <i class="fas fa-inbox fa-lg"></i>
                                </span>
                                @break
                            @default
                                <span class="badge bg-secondary rounded-circle p-3">
                                    <i class="fas fa-info fa-lg"></i>
                                </span>
                        @endswitch
                    </div>
                    <div class="notification-content flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <h5 class="mb-0 {{ !$notification->is_read ? 'fw-bold' : '' }}">
                                {{ $notification->title }}
                                @if(!$notification->is_read)
                                    <span class="badge bg-primary ms-2">New</span>
                                @endif
                            </h5>
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>{{ $notification->created_at->diffForHumans() }}
                            </small>
                        </div>
                        <p class="mb-0 text-muted">{{ $notification->message }}</p>
                    </div>
                </a>
            @empty
                <div class="text-center py-5">
                    <i class="fas fa-bell-slash fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No notifications</h4>
                    <p class="text-muted mb-0">You're all caught up! Check back later for updates.</p>
                </div>
            @endforelse
        </div>

        @if($notifications->hasPages())
            <div class="card-footer bg-white">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    .notification-list-item {
        transition: background-color 0.2s ease;
        color: inherit;
    }

    .notification-list-item:hover {
        background-color: #f8f9fa !important;
    }

    .notification-list-item.bg-light-blue {
        background-color: #e7f3ff;
    }

    .notification-list-item.bg-light-blue:hover {
        background-color: #d4e9ff !important;
    }

    .notification-icon .badge {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endsection
