@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fas fa-user-check me-2"></i>Active Staff Members</h4>
                    <a href="{{ route('hea.users.pending') }}" class="btn btn-sm btn-light">
                        <i class="fas fa-clock me-1"></i>View Pending
                    </a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('info'))
                        <div class="alert alert-info alert-dismissible fade show">
                            <i class="fas fa-info-circle me-2"></i>{{ session('info') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($activeUsers->isEmpty())
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>No active staff members found.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Program Assignment</th>
                                        <th>Status</th>
                                        <th>Approved Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($activeUsers as $user)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $user->name }}</div>
                                        </td>
                                        <td>
                                            <small>{{ $user->email }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $user->role_color }}">
                                                {{ $user->requested_role_badge }} - {{ $user->requested_role_label }}
                                            </span>
                                        </td>
                                        <td>
                                            <small>{{ $user->formatted_program_info }}</small>
                                        </td>
                                        <td>
                                            @if($user->hasVerifiedEmail())
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-circle me-1"></i>Verified
                                                </span>
                                            @else
                                                <span class="badge bg-warning text-dark">
                                                    <i class="fas fa-clock me-1"></i>Pending Verification
                                                </span>
                                            @endif

                                            @if($user->two_factor_verified_at)
                                                <span class="badge bg-info ms-1" title="2FA Enabled">
                                                    <i class="fas fa-shield-alt"></i>
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <small>{{ $user->approved_at?->format('M j, Y') ?? 'N/A' }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                @if(!$user->hasVerifiedEmail())
                                                    <form action="{{ route('hea.users.resend-verification', $user) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-primary" title="Resend Verification Email"
                                                                onclick="return confirm('Resend verification email to {{ $user->name }}?')">
                                                            <i class="fas fa-envelope"></i>
                                                        </button>
                                                    </form>
                                                @endif

                                                <button type="button" class="btn btn-outline-secondary view-details-btn" title="View Details"
                                                        data-user-id="{{ $user->id }}"
                                                        data-user-name="{{ $user->name }}"
                                                        data-user-email="{{ $user->email }}"
                                                        data-user-role="{{ $user->requested_role_label }}"
                                                        data-user-role-color="{{ $user->role_color }}"
                                                        data-user-program="{{ $user->formatted_program_info }}"
                                                        data-user-verified="{{ $user->hasVerifiedEmail() ? 'yes' : 'no' }}"
                                                        data-user-verified-at="{{ $user->email_verified_at?->format('M j, Y g:i A') ?? '' }}"
                                                        data-user-2fa="{{ $user->two_factor_verified_at ? 'yes' : 'no' }}"
                                                        data-user-registered="{{ $user->created_at->format('M j, Y g:i A') }}"
                                                        data-user-approved="{{ $user->approved_at?->format('M j, Y g:i A') ?? 'N/A' }}"
                                                        data-user-approved-by="{{ $user->approvedBy?->name ?? '' }}"
                                                        data-resend-url="{{ route('hea.users.resend-verification', $user) }}">
                                                    <i class="fas fa-eye"></i>
                                                </button>

                                                <form action="{{ route('hea.users.deactivate', $user) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-danger" title="Deactivate"
                                                            onclick="return confirm('Deactivate {{ $user->name }}? They will lose access to the system.')">
                                                        <i class="fas fa-ban"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Summary Stats --}}
                        <div class="row mt-4">
                            <div class="col-md-3">
                                <div class="card bg-light">
                                    <div class="card-body text-center py-3">
                                        <h4 class="mb-0">{{ $activeUsers->count() }}</h4>
                                        <small class="text-muted">Total Active Staff</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-light">
                                    <div class="card-body text-center py-3">
                                        <h4 class="mb-0 text-success">{{ $activeUsers->filter(fn($u) => $u->hasVerifiedEmail())->count() }}</h4>
                                        <small class="text-muted">Email Verified</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-light">
                                    <div class="card-body text-center py-3">
                                        <h4 class="mb-0 text-warning">{{ $activeUsers->filter(fn($u) => !$u->hasVerifiedEmail())->count() }}</h4>
                                        <small class="text-muted">Pending Verification</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-light">
                                    <div class="card-body text-center py-3">
                                        <h4 class="mb-0 text-info">{{ $activeUsers->filter(fn($u) => $u->two_factor_verified_at)->count() }}</h4>
                                        <small class="text-muted">2FA Enabled</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Single Shared Modal - Outside the table to prevent flickering --}}
<div class="modal" id="detailsModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-user me-2"></i><span id="modalUserName"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <th width="40%">Email:</th>
                        <td id="modalUserEmail"></td>
                    </tr>
                    <tr>
                        <th>Role:</th>
                        <td><span id="modalUserRole" class="badge"></span></td>
                    </tr>
                    <tr>
                        <th>Program:</th>
                        <td id="modalUserProgram"></td>
                    </tr>
                    <tr>
                        <th>Email Verified:</th>
                        <td id="modalUserVerified"></td>
                    </tr>
                    <tr>
                        <th>2FA Status:</th>
                        <td id="modalUser2FA"></td>
                    </tr>
                    <tr>
                        <th>Registered:</th>
                        <td id="modalUserRegistered"></td>
                    </tr>
                    <tr>
                        <th>Approved:</th>
                        <td id="modalUserApproved"></td>
                    </tr>
                    <tr id="approvedByRow" style="display: none;">
                        <th>Approved By:</th>
                        <td id="modalUserApprovedBy"></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <form id="resendForm" method="POST" style="display: none;">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-envelope me-1"></i>Resend Verification Email
                    </button>
                </form>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Fix modal flickering - disable ALL animations and transitions */
    #detailsModal,
    #detailsModal *,
    #detailsModal::before,
    #detailsModal::after {
        -webkit-transition: none !important;
        -moz-transition: none !important;
        transition: none !important;
        -webkit-animation: none !important;
        -moz-animation: none !important;
        animation: none !important;
    }

    #detailsModal .modal-dialog {
        -webkit-transform: none !important;
        transform: none !important;
        margin: 1.75rem auto !important;
    }

    .modal-backdrop {
        -webkit-transition: none !important;
        transition: none !important;
        opacity: 0.5 !important;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modalEl = document.getElementById('detailsModal');
    let bsModal = null;

    try {
        bsModal = bootstrap.Modal.getOrCreateInstance(modalEl, {
            backdrop: 'static',
            keyboard: false
        });
    } catch(e) {
        console.error('Modal init error:', e);
    }

    // Handle View Details button clicks
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.view-details-btn');
        if (btn) {
            e.preventDefault();
            e.stopPropagation();

            // Populate modal
            document.getElementById('modalUserName').textContent = btn.dataset.userName;
            document.getElementById('modalUserEmail').textContent = btn.dataset.userEmail;

            const roleSpan = document.getElementById('modalUserRole');
            roleSpan.textContent = btn.dataset.userRole;
            roleSpan.className = 'badge bg-' + btn.dataset.userRoleColor;

            document.getElementById('modalUserProgram').textContent = btn.dataset.userProgram;
            document.getElementById('modalUserRegistered').textContent = btn.dataset.userRegistered;
            document.getElementById('modalUserApproved').textContent = btn.dataset.userApproved;

            // Email verified status
            const verifiedEl = document.getElementById('modalUserVerified');
            if (btn.dataset.userVerified === 'yes') {
                verifiedEl.innerHTML = '<span class="text-success"><i class="fas fa-check-circle"></i> ' + btn.dataset.userVerifiedAt + '</span>';
            } else {
                verifiedEl.innerHTML = '<span class="text-warning"><i class="fas fa-clock"></i> Not verified</span>';
            }

            // 2FA status
            const tfaEl = document.getElementById('modalUser2FA');
            if (btn.dataset.user2fa === 'yes') {
                tfaEl.innerHTML = '<span class="text-success"><i class="fas fa-shield-alt"></i> Enabled</span>';
            } else {
                tfaEl.innerHTML = '<span class="text-muted"><i class="fas fa-shield-alt"></i> Not set up</span>';
            }

            // Approved by
            const approvedByRow = document.getElementById('approvedByRow');
            const approvedByEl = document.getElementById('modalUserApprovedBy');
            if (btn.dataset.userApprovedBy) {
                approvedByRow.style.display = 'table-row';
                approvedByEl.textContent = btn.dataset.userApprovedBy;
            } else {
                approvedByRow.style.display = 'none';
            }

            // Resend form
            const resendForm = document.getElementById('resendForm');
            if (btn.dataset.userVerified === 'no') {
                resendForm.style.display = 'block';
                resendForm.action = btn.dataset.resendUrl;
            } else {
                resendForm.style.display = 'none';
            }

            // Show modal
            if (bsModal) {
                bsModal.show();
            }
        }
    });
});
</script>
@endpush
@endsection
