@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">HEA Personnel Approvals</h2>
        <p class="text-muted mb-0">Review and approve HEA staff registrations</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
    </a>
</div>

<div class="container-fluid px-0">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-warning">
                    <h5 class="mb-0"><i class="fas fa-user-clock me-2"></i>Pending HEA Registrations</h5>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($pendingHeaUsers->isEmpty())
                        <div class="alert alert-info">
                            ✅ No pending HEA registration requests.
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <strong>⚠️ Action Required:</strong> {{ $pendingHeaUsers->count() }} pending HEA registration(s)
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Registration Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingHeaUsers as $pendingUser)
                                    <tr>
                                        <td>{{ $pendingUser->name }}</td>
                                        <td>{{ $pendingUser->email }}</td>
                                        <td>{{ $pendingUser->created_at->format('M j, Y g:i A') }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-success"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#approveModal{{ $pendingUser->id }}">
                                                ✅ Approve
                                            </button>

                                            <button type="button" class="btn btn-sm btn-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#rejectModal{{ $pendingUser->id }}">
                                                ❌ Reject
                                            </button>
                                        </td>
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
</div>

{{-- Modals placed completely outside all cards to prevent hover effect conflicts --}}
@if(isset($pendingHeaUsers) && $pendingHeaUsers->isNotEmpty())
    @foreach($pendingHeaUsers as $pendingUser)
    {{-- Approve Modal --}}
    <div class="modal" id="approveModal{{ $pendingUser->id }}" tabindex="-1" aria-labelledby="approveModalLabel{{ $pendingUser->id }}" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="transform: none !important;">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="approveModalLabel{{ $pendingUser->id }}">Approve HEA Registration</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Approve <strong>{{ $pendingUser->name }}</strong> ({{ $pendingUser->email }}) as HEA Personnel?</p>
                    <p class="text-muted">They will receive an email notification and can login immediately.</p>

                    <div class="alert alert-warning mb-0">
                        <strong>⚠️ HEA Personnel Privileges:</strong>
                        <ul class="mb-0">
                            <li>Approve/reject user registrations</li>
                            <li>Assign programs to staff</li>
                            <li>Manage equivalency lists</li>
                            <li>Full system access</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('admin.hea.approve', $pendingUser) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success">✅ Approve</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Reject Modal --}}
    <div class="modal" id="rejectModal{{ $pendingUser->id }}" tabindex="-1" aria-labelledby="rejectModalLabel{{ $pendingUser->id }}" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="transform: none !important;">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="rejectModalLabel{{ $pendingUser->id }}">Reject HEA Registration</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.hea.reject', $pendingUser) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>Reject <strong>{{ $pendingUser->name }}</strong> ({{ $pendingUser->email }})?</p>

                        <div class="mb-3">
                            <label class="form-label">Reason for Rejection *</label>
                            <textarea name="rejection_reason" class="form-control" rows="3" required
                                      placeholder="Provide a clear reason for rejection..."></textarea>
                            <small class="text-muted">This reason will be sent to the applicant.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">❌ Reject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
@endif
@endsection
