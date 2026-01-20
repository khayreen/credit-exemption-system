@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-search me-2"></i>Check Registration Status</h4>
                </div>

                <div class="card-body">
                    {{-- Status Check Form --}}
                    @if(!isset($user))
                        <p class="text-muted mb-4">
                            Enter your email address to check the status of your staff registration.
                        </p>

                        @if(session('not_found'))
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>No registration found</strong> for <strong>{{ session('checked_email') }}</strong>.
                                <hr>
                                <small>
                                    This could mean:
                                    <ul class="mb-0 mt-2">
                                        <li>You haven't registered yet</li>
                                        <li>You registered with a different email address</li>
                                        <li>You registered as a student (student registrations don't require approval)</li>
                                    </ul>
                                </small>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('registration.status.check') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       id="email"
                                       name="email"
                                       value="{{ old('email', session('checked_email')) }}"
                                       placeholder="Enter the email you used to register"
                                       required
                                       autofocus>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-2"></i>Check Status
                                </button>
                                <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Login
                                </a>
                            </div>
                        </form>

                    {{-- Status Results --}}
                    @else
                        <div class="text-center mb-4">
                            <div class="status-icon mb-3">
                                <i class="{{ $statusInfo['status_icon'] }} fa-4x text-{{ $statusInfo['status_color'] }}"></i>
                            </div>
                            <h3 class="mb-2">{{ $user->name }}</h3>
                            <p class="text-muted mb-0">{{ $checked_email }}</p>
                        </div>

                        {{-- Status Badge --}}
                        <div class="text-center mb-4">
                            <span class="badge bg-{{ $statusInfo['status_color'] }} fs-5 px-4 py-2">
                                {{ $statusInfo['status_label'] }}
                            </span>
                        </div>

                        {{-- Status Details Card --}}
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-2">
                                            <strong><i class="fas fa-user-tag me-2"></i>Role Requested:</strong><br>
                                            <span class="ms-4">{{ $statusInfo['role_requested'] }}</span>
                                        </p>
                                        <p class="mb-2">
                                            <strong><i class="fas fa-calendar me-2"></i>Registered:</strong><br>
                                            <span class="ms-4">{{ $statusInfo['registered_at']->format('M j, Y g:i A') }}</span>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        @if($statusInfo['status'] === 'pending')
                                            <p class="mb-2">
                                                <strong><i class="fas fa-hourglass-half me-2"></i>Waiting:</strong><br>
                                                <span class="ms-4">
                                                    {{ $statusInfo['days_waiting'] }} {{ Str::plural('day', $statusInfo['days_waiting']) }}
                                                </span>
                                            </p>
                                        @endif

                                        @if($statusInfo['status'] === 'approved' && isset($statusInfo['approved_at']))
                                            <p class="mb-2">
                                                <strong><i class="fas fa-check me-2"></i>Approved:</strong><br>
                                                <span class="ms-4">{{ $statusInfo['approved_at']->format('M j, Y g:i A') }}</span>
                                            </p>
                                            <p class="mb-2">
                                                <strong><i class="fas fa-envelope me-2"></i>Email Verified:</strong><br>
                                                <span class="ms-4">
                                                    @if($statusInfo['email_verified'])
                                                        <span class="text-success"><i class="fas fa-check-circle"></i> Yes</span>
                                                    @else
                                                        <span class="text-warning"><i class="fas fa-clock"></i> Pending</span>
                                                    @endif
                                                </span>
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Rejection Reason (if rejected) --}}
                        @if($statusInfo['status'] === 'rejected' && !empty($statusInfo['rejection_reason']))
                            <div class="alert alert-danger">
                                <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Rejection Reason:</h6>
                                <p class="mb-0">{{ $statusInfo['rejection_reason'] }}</p>
                            </div>
                        @endif

                        {{-- Next Steps --}}
                        <div class="card border-{{ $statusInfo['status_color'] }}">
                            <div class="card-header bg-{{ $statusInfo['status_color'] }} {{ in_array($statusInfo['status_color'], ['warning']) ? 'text-dark' : 'text-white' }}">
                                <h6 class="mb-0"><i class="fas fa-arrow-right me-2"></i>Next Steps</h6>
                            </div>
                            <div class="card-body">
                                <ul class="mb-0">
                                    @foreach($statusInfo['next_steps'] as $step)
                                        <li class="mb-1">{{ $step }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="d-grid gap-2 mt-4">
                            @if($statusInfo['status'] === 'approved' && $statusInfo['email_verified'])
                                <a href="{{ route('login') }}" class="btn btn-success btn-lg">
                                    <i class="fas fa-sign-in-alt me-2"></i>Login to Your Account
                                </a>
                            @endif

                            <a href="{{ route('registration.status') }}" class="btn btn-outline-primary">
                                <i class="fas fa-search me-2"></i>Check Another Email
                            </a>

                            <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Login
                            </a>
                        </div>
                    @endif
                </div>

                <div class="card-footer bg-light">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Need help? Contact the HEA office for assistance with your registration.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.status-icon {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}
</style>
@endsection
