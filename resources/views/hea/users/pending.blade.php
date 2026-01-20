@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">Pending User Approvals</h4>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('warning'))
                        <div class="alert alert-warning alert-dismissible fade show">
                            {{ session('warning') }}
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
                            No pending approvals at this time.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered">
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
                                            <span class="badge bg-{{ $user->role_color }}">
                                                {{ $user->requested_role_label }}
                                            </span>
                                        </td>
                                        <td>{{ $user->formatted_program_info }}</td>
                                        <td>{{ $user->created_at->format('M j, Y g:i A') }}</td>
                                        <td>
                                            <button type="button"
                                                    class="btn btn-sm btn-primary review-btn"
                                                    data-user-id="{{ $user->id }}"
                                                    data-user-name="{{ $user->name }}"
                                                    data-user-email="{{ $user->email }}"
                                                    data-user-role="{{ $user->requested_role }}"
                                                    data-user-role-label="{{ $user->requested_role_label }}"
                                                    data-user-programs="{{ $user->formatted_program_info }}"
                                                    data-user-date="{{ $user->created_at->format('M j, Y g:i A') }}"
                                                    data-user-programs-raw="{{ is_array($user->requested_programs) ? json_encode($user->requested_programs) : $user->requested_programs }}">
                                                <i class="fas fa-eye"></i> Review
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

{{-- Single Shared Modal - NO fade class to prevent flickering --}}
<div class="modal" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="reviewModalLabel">Review User Registration</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="approvalForm" method="POST" action="">
                @csrf
                <input type="hidden" name="modified_programs" id="modifiedPrograms" value="0">

                <div class="modal-body">
                    {{-- User Info Section --}}
                    <div class="row mb-4">
                        <div class="col-md-2 text-center">
                            <div id="userAvatar" class="rounded-circle d-inline-flex align-items-center justify-content-center text-white fw-bold"
                                 style="width: 60px; height: 60px; font-size: 1.5rem; background: #667eea;">
                            </div>
                        </div>
                        <div class="col-md-10">
                            <h5 id="modalUserName" class="mb-1"></h5>
                            <p id="modalUserEmail" class="text-muted mb-2"></p>
                            <span id="modalUserRole" class="badge bg-primary"></span>
                            <small id="modalUserDate" class="text-muted ms-2"></small>
                        </div>
                    </div>

                    {{-- Program Assignment Section --}}
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center py-2">
                            <span><i class="fas fa-clipboard-list me-2"></i>Program Assignment</span>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="toggleEditBtn">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </div>
                        <div class="card-body">
                            {{-- View Mode --}}
                            <div id="viewModeSection">
                                <strong>Assigned:</strong>
                                <span id="modalUserPrograms" class="badge bg-info ms-2"></span>
                            </div>

                            {{-- Edit Mode --}}
                            <div id="editModeSection" style="display: none;">
                                <div class="alert alert-warning py-2 mb-3">
                                    <small><i class="fas fa-info-circle"></i> Modify the program assignment below</small>
                                </div>

                                {{-- Academic Advisor Edit --}}
                                <div id="editAcademicAdvisor" style="display: none;">
                                    <label class="form-label fw-bold">Select Program Groups:</label>
                                    @foreach($programGroups as $programCode => $programData)
                                        <div class="mb-2">
                                            <small class="text-primary fw-bold">{{ $programCode }}</small>
                                            <div class="d-flex flex-wrap gap-2 mt-1">
                                                @foreach($programData['groups'] as $group)
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox"
                                                               name="program_groups[]"
                                                               value="{{ $group }}"
                                                               id="grp_{{ $group }}">
                                                        <label class="form-check-label" for="grp_{{ $group }}">{{ $group }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Coordinator Edit --}}
                                <div id="editCoordinator" style="display: none;">
                                    <label class="form-label fw-bold">Select Category:</label>
                                    @foreach($coordinatorCategories as $categoryKey => $categoryData)
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio"
                                                   name="program_category"
                                                   value="{{ $categoryKey }}"
                                                   id="cat_{{ $categoryKey }}">
                                            <label class="form-check-label" for="cat_{{ $categoryKey }}">
                                                <strong>{{ $categoryData['label'] }}</strong> - {{ implode(', ', $categoryData['programs']) }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Resource Person Edit --}}
                                <div id="editResourcePerson" style="display: none;">
                                    <label class="form-label fw-bold">Select Program:</label>
                                    <select name="assigned_program" class="form-select" id="resourcePersonProgram">
                                        @foreach($supportedPrograms as $code => $name)
                                            <option value="{{ $code }}">{{ $code }} - {{ Str::limit($name, 40) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Action Selection --}}
                    <div class="card">
                        <div class="card-header py-2">
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="actionType" id="actionApprove" value="approve">
                                <label class="btn btn-outline-success" for="actionApprove">
                                    <i class="fas fa-check me-1"></i> Approve
                                </label>

                                <input type="radio" class="btn-check" name="actionType" id="actionReject" value="reject">
                                <label class="btn btn-outline-danger" for="actionReject">
                                    <i class="fas fa-times me-1"></i> Reject
                                </label>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="noSelectionInfo">
                                <div class="text-muted">
                                    <i class="fas fa-hand-pointer me-1"></i>
                                    Please select an action above to proceed.
                                </div>
                            </div>
                            <div id="approveInfo" style="display: none;">
                                <div class="text-success">
                                    <i class="fas fa-info-circle me-1"></i>
                                    User will receive an email verification link after approval.
                                </div>
                            </div>
                            <div id="rejectInfo" style="display: none;">
                                <input type="hidden" name="reject" id="rejectInput" value="0">
                                <label class="form-label text-danger fw-bold">Reason for Rejection *</label>
                                <textarea name="rejection_reason" class="form-control" rows="3"
                                          placeholder="Please provide a reason..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                        <i class="fas fa-arrow-right me-1"></i> Select Action
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* AGGRESSIVE fix for modal flickering - disable ALL animations and transitions */
    #reviewModal,
    #reviewModal *,
    #reviewModal::before,
    #reviewModal::after,
    #reviewModal *::before,
    #reviewModal *::after {
        -webkit-transition: none !important;
        -moz-transition: none !important;
        -ms-transition: none !important;
        -o-transition: none !important;
        transition: none !important;
        -webkit-animation: none !important;
        -moz-animation: none !important;
        -ms-animation: none !important;
        -o-animation: none !important;
        animation: none !important;
        -webkit-transform: none !important;
        -moz-transform: none !important;
        -ms-transform: none !important;
        -o-transform: none !important;
    }

    /* Disable backdrop animation */
    .modal-backdrop {
        -webkit-transition: none !important;
        transition: none !important;
        opacity: 0.5 !important;
    }

    /* Override Bootstrap's show animation */
    #reviewModal.show {
        display: block !important;
    }

    /* Cards inside modal - no hover effects at all */
    #reviewModal .card,
    #reviewModal .card:hover,
    #reviewModal .card:focus,
    #reviewModal .card:active {
        box-shadow: 0 1px 3px rgba(0,0,0,0.1) !important;
        border: 1px solid #dee2e6 !important;
        transform: none !important;
    }

    /* Buttons inside modal - no hover transforms */
    #reviewModal .btn,
    #reviewModal .btn:hover,
    #reviewModal .btn:focus,
    #reviewModal .btn:active {
        transform: none !important;
        box-shadow: none !important;
    }

    /* Modal dialog positioning - no centering animation */
    #reviewModal .modal-dialog {
        margin: 1.75rem auto !important;
        transform: none !important;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modalEl = document.getElementById('reviewModal');
    let bsModal = null;
    let currentUserRole = null;

    // Initialize modal once - reuse same instance
    try {
        bsModal = bootstrap.Modal.getOrCreateInstance(modalEl, {
            backdrop: 'static',
            keyboard: false
        });
    } catch(e) {
        console.error('Modal init error:', e);
    }

    // Helper function to populate modal
    function populateModal(btn) {
        const userId = btn.dataset.userId;
        currentUserRole = btn.dataset.userRole;

        // Update form action
        document.getElementById('approvalForm').action = '/hea/users/' + userId + '/approve';

        // Update modal content
        const nameParts = btn.dataset.userName.split(' ');
        const initials = nameParts.length >= 2
            ? (nameParts[0][0] + nameParts[nameParts.length-1][0]).toUpperCase()
            : btn.dataset.userName.substring(0, 2).toUpperCase();

        document.getElementById('userAvatar').textContent = initials;
        document.getElementById('modalUserName').textContent = btn.dataset.userName;
        document.getElementById('modalUserEmail').textContent = btn.dataset.userEmail;
        document.getElementById('modalUserRole').textContent = btn.dataset.userRoleLabel;
        document.getElementById('modalUserDate').textContent = 'Registered: ' + btn.dataset.userDate;
        document.getElementById('modalUserPrograms').textContent = btn.dataset.userPrograms;

        // Reset form state
        document.getElementById('viewModeSection').style.display = 'block';
        document.getElementById('editModeSection').style.display = 'none';
        document.getElementById('toggleEditBtn').innerHTML = '<i class="fas fa-edit"></i> Edit';
        document.getElementById('modifiedPrograms').value = '0';

        // Reset action selection - no default
        document.getElementById('actionApprove').checked = false;
        document.getElementById('actionReject').checked = false;
        document.getElementById('noSelectionInfo').style.display = 'block';
        document.getElementById('approveInfo').style.display = 'none';
        document.getElementById('rejectInfo').style.display = 'none';
        document.getElementById('rejectInput').value = '0';
        document.getElementById('submitBtn').className = 'btn btn-primary';
        document.getElementById('submitBtn').innerHTML = '<i class="fas fa-arrow-right me-1"></i> Select Action';
        document.getElementById('submitBtn').disabled = true;

        // Hide all edit sections first
        document.getElementById('editAcademicAdvisor').style.display = 'none';
        document.getElementById('editCoordinator').style.display = 'none';
        document.getElementById('editResourcePerson').style.display = 'none';

        // Setup role-specific edit section
        if (currentUserRole === 'academic_advisor') {
            document.getElementById('editAcademicAdvisor').style.display = 'block';
            document.querySelectorAll('input[name="program_groups[]"]').forEach(function(cb) { cb.checked = false; });
            try {
                const programs = JSON.parse(btn.dataset.userProgramsRaw || '[]');
                if (Array.isArray(programs)) {
                    programs.forEach(function(p) {
                        const cb = document.getElementById('grp_' + (p.group || ''));
                        if (cb) cb.checked = true;
                    });
                }
            } catch(e) {}
        } else if (currentUserRole === 'coordinator') {
            document.getElementById('editCoordinator').style.display = 'block';
            try {
                const data = JSON.parse(btn.dataset.userProgramsRaw || '{}');
                const cat = data.category || '';
                const radio = document.getElementById('cat_' + cat);
                if (radio) radio.checked = true;
            } catch(e) {}
        } else if (currentUserRole === 'resource_person') {
            document.getElementById('editResourcePerson').style.display = 'block';
            try {
                const data = JSON.parse(btn.dataset.userProgramsRaw || '{}');
                document.getElementById('resourcePersonProgram').value = data.program || '';
            } catch(e) {}
        }
    }

    // Handle Review button clicks using event delegation
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.review-btn');
        if (btn) {
            e.preventDefault();
            e.stopPropagation();
            populateModal(btn);
            if (bsModal) {
                bsModal.show();
            }
        }
    });

    // Toggle Edit Mode
    document.getElementById('toggleEditBtn').addEventListener('click', function(e) {
        e.preventDefault();
        const viewMode = document.getElementById('viewModeSection');
        const editMode = document.getElementById('editModeSection');

        if (editMode.style.display === 'none') {
            viewMode.style.display = 'none';
            editMode.style.display = 'block';
            this.innerHTML = '<i class="fas fa-eye"></i> View';
            document.getElementById('modifiedPrograms').value = '1';
        } else {
            viewMode.style.display = 'block';
            editMode.style.display = 'none';
            this.innerHTML = '<i class="fas fa-edit"></i> Edit';
            document.getElementById('modifiedPrograms').value = '0';
        }
    });

    // Handle Approve/Reject toggle
    document.getElementById('actionApprove').addEventListener('change', function() {
        if (this.checked) {
            document.getElementById('noSelectionInfo').style.display = 'none';
            document.getElementById('approveInfo').style.display = 'block';
            document.getElementById('rejectInfo').style.display = 'none';
            document.getElementById('rejectInput').value = '0';
            document.getElementById('submitBtn').className = 'btn btn-success';
            document.getElementById('submitBtn').innerHTML = '<i class="fas fa-check me-1"></i> Approve Registration';
            document.getElementById('submitBtn').disabled = false;
        }
    });

    document.getElementById('actionReject').addEventListener('change', function() {
        if (this.checked) {
            document.getElementById('noSelectionInfo').style.display = 'none';
            document.getElementById('approveInfo').style.display = 'none';
            document.getElementById('rejectInfo').style.display = 'block';
            document.getElementById('rejectInput').value = '1';
            document.getElementById('submitBtn').className = 'btn btn-danger';
            document.getElementById('submitBtn').innerHTML = '<i class="fas fa-times me-1"></i> Reject Registration';
            document.getElementById('submitBtn').disabled = false;
        }
    });
});
</script>
@endpush
@endsection
