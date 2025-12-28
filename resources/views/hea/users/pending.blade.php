@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">⏳ Pending User Approvals</h4>
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

                    @if($pendingUsers->isEmpty())
                        <div class="alert alert-info">
                            ✅ No pending approvals at this time.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Requested Role</th>
                                        <th>Requested Programs</th>
                                        <th>Registration Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingUsers as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                {{ ucwords(str_replace('_', ' ', $user->requested_role)) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($user->requested_programs)
                                                @php
                                                    $programs = is_string($user->requested_programs)
                                                        ? json_decode($user->requested_programs, true)
                                                        : $user->requested_programs;
                                                @endphp
                                                @if($programs && count($programs) > 0)
                                                    <div class="d-flex flex-wrap gap-1">
                                                        @foreach($programs as $programCode)
                                                            <span class="badge bg-primary">{{ $programCode }}</span>
                                                        @endforeach
                                                    </div>
                                                    <small class="text-muted">({{ count($programs) }} programs)</small>
                                                @else
                                                    <em class="text-muted">None</em>
                                                @endif
                                            @else
                                                <em class="text-muted">N/A</em>
                                            @endif
                                        </td>
                                        <td>{{ $user->created_at->format('M j, Y g:i A') }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#reviewModal{{ $user->id }}">
                                                <i class="bi bi-eye"></i> Review
                                            </button>
                                        </td>
                                    </tr>

                                    {{-- Review Modal --}}
                                    <div class="modal fade" id="reviewModal{{ $user->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title">Review User Registration</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('hea.users.approve', $user) }}" method="POST" id="approvalForm{{ $user->id }}">
                                                    @csrf
                                                    <div class="modal-body">
                                                        {{-- User Details --}}
                                                        <h6>👤 User Details</h6>
                                                        <div class="card mb-3">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <strong>Name:</strong> {{ $user->name }}<br>
                                                                        <strong>Email:</strong> {{ $user->email }}
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <strong>Requested Role:</strong>
                                                                        <span class="badge bg-secondary">
                                                                            {{ ucwords(str_replace('_', ' ', $user->requested_role)) }}
                                                                        </span><br>
                                                                        <strong>Registered:</strong> {{ $user->created_at->format('M j, Y g:i A') }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        {{-- Confirm Role --}}
                                                        <h6>⚙️ Confirm Role Assignment</h6>
                                                        <div class="mb-3">
                                                            <label class="form-label">Assign as:</label>
                                                            <select name="role" class="form-select" required>
                                                                <option value="{{ $user->requested_role }}" selected>
                                                                    {{ ucwords(str_replace('_', ' ', $user->requested_role)) }} (as requested)
                                                                </option>
                                                                @if($user->requested_role !== 'academic_advisor')
                                                                    <option value="academic_advisor">Academic Advisor (change)</option>
                                                                @endif
                                                                @if($user->requested_role !== 'coordinator')
                                                                    <option value="coordinator">Program Coordinator (change)</option>
                                                                @endif
                                                                @if($user->requested_role !== 'resource_person')
                                                                    <option value="resource_person">Resource Person (change)</option>
                                                                @endif
                                                            </select>
                                                        </div>

                                                        {{-- Program Assignment --}}
                                                        <h6>📚 Program Assignment</h6>

                                                        @if($user->requested_programs)
                                                            @php
                                                                $requestedPrograms = is_string($user->requested_programs)
                                                                    ? json_decode($user->requested_programs, true)
                                                                    : $user->requested_programs;
                                                            @endphp
                                                            @if($requestedPrograms && count($requestedPrograms) > 0)
                                                                <div class="alert alert-info">
                                                                    <strong>User Requested These Programs:</strong><br>
                                                                    <div class="mt-2">
                                                                        @foreach($requestedPrograms as $programCode)
                                                                            <span class="badge bg-primary me-1">{{ $programCode }}</span>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endif

                                                        <div class="mb-3">
                                                            <label class="form-label">
                                                                Final Assigned Programs: *
                                                                <small class="text-muted">(You can modify the user's request)</small>
                                                            </label>
                                                            <select name="programs[]" class="form-select select2-programs-{{ $user->id }}"
                                                                    multiple required style="width: 100%;">
                                                                @foreach($allPrograms as $program)
                                                                    @php
                                                                        $requestedPrograms = $user->requested_programs
                                                                            ? (is_string($user->requested_programs)
                                                                                ? json_decode($user->requested_programs, true)
                                                                                : $user->requested_programs)
                                                                            : [];
                                                                    @endphp
                                                                    <option value="{{ $program->code }}"
                                                                        {{ in_array($program->code, $requestedPrograms) ? 'selected' : '' }}>
                                                                        {{ $program->code }} - {{ $program->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <small class="form-text text-muted">
                                                                Pre-filled with user's request. You can add or remove programs.
                                                            </small>
                                                        </div>

                                                        <hr>

                                                        {{-- Rejection Option --}}
                                                        <div class="form-check mb-3">
                                                            <input class="form-check-input reject-checkbox" type="checkbox"
                                                                   id="rejectCheckbox{{ $user->id }}"
                                                                   name="reject" value="1">
                                                            <label class="form-check-label text-danger" for="rejectCheckbox{{ $user->id }}">
                                                                Reject this registration instead
                                                            </label>
                                                        </div>

                                                        <div id="rejectionReason{{ $user->id }}" style="display: none;">
                                                            <label class="form-label">Reason for Rejection:</label>
                                                            <textarea name="rejection_reason" class="form-control" rows="3"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-success submit-btn" id="submitBtn{{ $user->id }}">
                                                            ✅ Approve & Assign Programs
                                                        </button>
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

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2 for each modal when it opens
    @foreach($pendingUsers as $user)
        $('#reviewModal{{ $user->id }}').on('shown.bs.modal', function () {
            $('.select2-programs-{{ $user->id }}').select2({
                dropdownParent: $('#reviewModal{{ $user->id }}'),
                placeholder: 'Search and select programs...',
                allowClear: true,
                width: '100%',
                theme: 'bootstrap-5'
            });
        });

        // Handle rejection checkbox
        document.getElementById('rejectCheckbox{{ $user->id }}').addEventListener('change', function() {
            const reasonDiv = document.getElementById('rejectionReason{{ $user->id }}');
            const submitBtn = document.getElementById('submitBtn{{ $user->id }}');

            if (this.checked) {
                reasonDiv.style.display = 'block';
                submitBtn.textContent = '❌ Reject Registration';
                submitBtn.classList.remove('btn-success');
                submitBtn.classList.add('btn-danger');
            } else {
                reasonDiv.style.display = 'none';
                submitBtn.textContent = '✅ Approve & Assign Programs';
                submitBtn.classList.remove('btn-danger');
                submitBtn.classList.add('btn-success');
            }
        });
    @endforeach
});
</script>
@endpush
@endsection
