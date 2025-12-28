@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0">🚨 HEA Personnel Registration Approvals</h4>
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

                                    {{-- Approve Modal --}}
                                    <div class="modal fade" id="approveModal{{ $pendingUser->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-success text-white">
                                                    <h5 class="modal-title">Approve HEA Registration</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Approve <strong>{{ $pendingUser->name }}</strong> ({{ $pendingUser->email }}) as HEA Personnel?</p>
                                                    <p class="text-muted">They will receive an email notification and can login immediately.</p>

                                                    <div class="alert alert-warning">
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
                                    <div class="modal fade" id="rejectModal{{ $pendingUser->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title">Reject HEA Registration</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
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
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
