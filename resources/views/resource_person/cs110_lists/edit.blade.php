@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Edit CS110 Equivalency List - {{ $list->program_code }}</h2>
            <p class="text-muted mb-0 mt-2">
                <i class="fas fa-building me-1"></i> ONE continuous list per program - Edit anytime, submit for each semester
            </p>
            <p class="text-muted mb-0">
                <span class="badge bg-{{ $list->status_badge_class }}">{{ $list->status_display }}</span>
                @if($list->target_semester)
                    <span class="badge bg-warning text-dark">Target: {{ $list->target_semester }}</span>
                @endif
                @if($list->semester)
                    <span class="badge bg-success">Last Published: {{ $list->semester }}</span>
                @endif
            </p>
        </div>
        <div>
            <a href="{{ route('resource_person.equivalency_lists.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Lists
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($list->isRejected())
        <div class="alert alert-danger">
            <h5><i class="fas fa-times-circle me-2"></i>This list was rejected by HEA</h5>
            <p class="mb-0"><strong>Reason:</strong> {{ $list->review_notes }}</p>
            <small class="text-muted">Please make the necessary changes and resubmit.</small>
        </div>
    @endif

    <!-- List Information Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>List Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <p class="mb-2"><strong>Program:</strong> {{ $list->program_name }}</p>
                    <p class="mb-2"><strong>Code:</strong> <span class="badge bg-primary">{{ $list->program_code }}</span></p>
                </div>
                <div class="col-md-3">
                    <p class="mb-2"><strong>Category:</strong> <span class="badge bg-info">Internal (CS110)</span></p>
                    <p class="mb-2"><strong>Status:</strong> <span class="badge bg-{{ $list->status_badge_class }}">{{ $list->status_display }}</span></p>
                </div>
                <div class="col-md-3">
                    @if($list->semester)
                        <p class="mb-2"><strong>Last Published:</strong> {{ $list->semester }}</p>
                        <p class="mb-2"><strong>Published On:</strong> {{ $list->published_at ? $list->published_at->format('d M Y') : '-' }}</p>
                    @else
                        <p class="mb-2"><strong>Last Published:</strong> Never</p>
                        <p class="mb-2"><strong>Published On:</strong> -</p>
                    @endif
                </div>
                <div class="col-md-3">
                    @if($list->target_semester)
                        <p class="mb-2"><strong>Target Semester:</strong></p>
                        <p class="mb-2"><span class="badge bg-warning text-dark">{{ $list->target_semester }}</span></p>
                    @else
                        <p class="mb-2"><strong>Target Semester:</strong></p>
                        <p class="mb-2 text-muted"><small>Set when submitting</small></p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Course Mappings Section -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-exchange-alt me-2"></i>Course Mappings ({{ $list->total_equivalencies }})</h5>
            <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addMappingModal">
                <i class="fas fa-plus-circle"></i> Add Mapping
            </button>
        </div>
        <div class="card-body">
            @if($list->courseEquivalencies->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-exchange-alt fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No Course Mappings Yet</h5>
                    <p class="text-muted mb-3">Add course mappings from CS110 diploma courses to {{ $list->program_code }} degree courses.</p>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addMappingModal">
                        <i class="fas fa-plus-circle me-1"></i>Add First Mapping
                    </button>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>CS110 Diploma Course</th>
                                <th>Credit</th>
                                <th>{{ $list->program_code }} Degree Course</th>
                                <th>Credit</th>
                                <th class="text-center">Match %</th>
                                <th class="text-center">Eligible</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($list->courseEquivalencies->sortBy('diploma_course_code') as $equiv)
                            <tr>
                                <td>
                                    <strong>{{ $equiv->diploma_course_code }}</strong><br>
                                    <small class="text-muted">{{ $equiv->diploma_course_name }}</small>
                                </td>
                                <td>{{ $equiv->diploma_credit_hour }}</td>
                                <td>
                                    <strong>{{ $equiv->degree_course_code }}</strong><br>
                                    <small class="text-muted">{{ $equiv->degree_course_name }}</small>
                                </td>
                                <td>{{ $equiv->degree_credit_hour }}</td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $equiv->match_percentage >= 80 ? 'success' : 'warning' }}">
                                        {{ number_format($equiv->match_percentage, 0) }}%
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($equiv->is_eligible)
                                        <span class="badge bg-success">Yes</span>
                                    @else
                                        <span class="badge bg-secondary">No</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-primary me-1" onclick="editMapping('{{ $equiv->id }}', {{ json_encode($equiv) }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteMapping('{{ $equiv->id }}')">
                                        <i class="fas fa-trash"></i>
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

    <!-- Action Buttons -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="mb-0 text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        @if($list->canBeSubmitted())
                            Ready to submit to HEA for review
                        @else
                            Add at least one course mapping to submit
                        @endif
                    </p>
                </div>
                <div>
                    @if($list->canBeSubmitted())
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#submitModal">
                            <i class="fas fa-paper-plane me-1"></i>Submit to HEA
                        </button>
                    @else
                        <button type="button" class="btn btn-secondary" disabled>
                            <i class="fas fa-paper-plane me-1"></i>Submit to HEA
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Course Mapping History -->
    @if($list->courseEquivalencyHistory && $list->courseEquivalencyHistory->count() > 0)
    <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0"><i class="fas fa-history me-2"></i>Course Mapping History ({{ $list->courseEquivalencyHistory->count() }})</h5>
        </div>
        <div class="card-body">
            <p class="text-muted mb-3">
                <i class="fas fa-info-circle me-1"></i>
                Previously deleted or replaced course mappings are archived here for record-keeping.
            </p>
            <div class="table-responsive">
                <table class="table table-sm table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Action</th>
                            <th>Diploma Course</th>
                            <th>Degree Course</th>
                            <th>Match %</th>
                            <th>Archived By</th>
                            <th>Archived At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list->courseEquivalencyHistory->sortByDesc('archived_at') as $history)
                        <tr>
                            <td>
                                <span class="badge bg-{{ $history->action === 'deleted' ? 'danger' : ($history->action === 'replaced' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($history->action) }}
                                </span>
                            </td>
                            <td>
                                <strong>{{ $history->diploma_course_code }}</strong><br>
                                <small class="text-muted">{{ Str::limit($history->diploma_course_name, 30) }}</small>
                            </td>
                            <td>
                                <strong>{{ $history->degree_course_code }}</strong><br>
                                <small class="text-muted">{{ Str::limit($history->degree_course_name, 30) }}</small>
                            </td>
                            <td>{{ number_format($history->match_percentage, 0) }}%</td>
                            <td>
                                @if($history->archivedBy)
                                    {{ $history->archivedBy->name }}
                                @else
                                    System
                                @endif
                            </td>
                            <td>{{ $history->archived_at->format('d M Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Add Mapping Modal -->
<div class="modal fade" id="addMappingModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Add Course Mapping</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('resource_person.equivalency_lists.add_mapping', $programCode) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>CS110 Internal Mapping:</strong> Map CS110 diploma courses to {{ $list->program_code }} degree courses
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">CS110 Diploma Course</h6>
                            <div class="mb-3">
                                <label class="form-label">Course Code <span class="text-danger">*</span></label>
                                <input type="text" name="diploma_course_code" class="form-control" placeholder="CSC126" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Course Name <span class="text-danger">*</span></label>
                                <input type="text" name="diploma_course_name" class="form-control" placeholder="Fundamentals of Algorithms" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Credit Hours <span class="text-danger">*</span></label>
                                <input type="number" name="diploma_credit_hour" class="form-control" min="1" max="10" value="3" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-success mb-3">{{ $list->program_code }} Degree Course</h6>
                            <div class="mb-3">
                                <label class="form-label">Course Code <span class="text-danger">*</span></label>
                                <input type="text" name="degree_course_code" class="form-control" placeholder="CSC402" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Course Name <span class="text-danger">*</span></label>
                                <input type="text" name="degree_course_name" class="form-control" placeholder="Programming I" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Credit Hours <span class="text-danger">*</span></label>
                                <input type="number" name="degree_credit_hour" class="form-control" min="1" max="10" value="3" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Match Percentage <span class="text-danger">*</span></label>
                                <input type="number" name="match_percentage" class="form-control" min="0" max="100" value="85" required>
                                <small class="text-muted">Recommended: ≥80% for eligibility</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Eligible for Exemption</label>
                                <select name="is_eligible" class="form-select" required>
                                    <option value="1">Yes - Eligible</option>
                                    <option value="0">No - Not Eligible</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-plus-circle me-1"></i>Add Mapping</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Submit to HEA Modal -->
<div class="modal fade" id="submitModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-paper-plane me-2"></i>Submit to HEA</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('resource_person.equivalency_lists.submit', $programCode) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>{{ $list->total_equivalencies }} course mappings</strong> will be submitted to HEA for review and endorsement.
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Target Semester <span class="text-danger">*</span></label>
                        <input type="text" name="target_semester" class="form-control" placeholder="e.g., 2025/2026-1" required>
                        <small class="text-muted">Format: Academic Year - Semester (e.g., 2025/2026-1 for Semester 1 of 2025/2026)</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Submission Notes (Optional)</label>
                        <textarea name="submission_notes" class="form-control" rows="3" placeholder="Add any notes or comments for HEA review..."></textarea>
                    </div>

                    <p class="mb-0"><strong>After submission:</strong></p>
                    <ol class="mb-0">
                        <li>HEA will review your mappings for <strong class="text-primary">target semester</strong></li>
                        <li>HEA will endorse or reject the list</li>
                        <li>If endorsed, HEA will publish as PDF for the specified semester</li>
                        <li>After publication, list reverts to DRAFT for next semester's updates</li>
                    </ol>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-paper-plane me-1"></i>Submit to HEA
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Mapping Modal -->
<div class="modal fade" id="editMappingModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Course Mapping</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editMappingForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Update CS110 Mapping:</strong> Modify the course equivalency details
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">CS110 Diploma Course</h6>
                            <div class="mb-3">
                                <label class="form-label">Course Code <span class="text-danger">*</span></label>
                                <input type="text" id="edit_diploma_course_code" name="diploma_course_code" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Course Name <span class="text-danger">*</span></label>
                                <input type="text" id="edit_diploma_course_name" name="diploma_course_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Credit Hours <span class="text-danger">*</span></label>
                                <input type="number" id="edit_diploma_credit_hour" name="diploma_credit_hour" class="form-control" min="1" max="10" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-success mb-3">{{ $list->program_code }} Degree Course</h6>
                            <div class="mb-3">
                                <label class="form-label">Course Code <span class="text-danger">*</span></label>
                                <input type="text" id="edit_degree_course_code" name="degree_course_code" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Course Name <span class="text-danger">*</span></label>
                                <input type="text" id="edit_degree_course_name" name="degree_course_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Credit Hours <span class="text-danger">*</span></label>
                                <input type="number" id="edit_degree_credit_hour" name="degree_credit_hour" class="form-control" min="1" max="10" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Match Percentage <span class="text-danger">*</span></label>
                                <input type="number" id="edit_match_percentage" name="match_percentage" class="form-control" min="0" max="100" required>
                                <small class="text-muted">Recommended: ≥80% for eligibility</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Eligible for Exemption</label>
                                <select id="edit_is_eligible" name="is_eligible" class="form-select" required>
                                    <option value="1">Yes - Eligible</option>
                                    <option value="0">No - Not Eligible</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Update Mapping</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editMapping(mappingId, mapping) {
    // Populate the edit form with existing data
    document.getElementById('edit_diploma_course_code').value = mapping.diploma_course_code;
    document.getElementById('edit_diploma_course_name').value = mapping.diploma_course_name;
    document.getElementById('edit_diploma_credit_hour').value = mapping.diploma_credit_hour;
    document.getElementById('edit_degree_course_code').value = mapping.degree_course_code;
    document.getElementById('edit_degree_course_name').value = mapping.degree_course_name;
    document.getElementById('edit_degree_credit_hour').value = mapping.degree_credit_hour;
    document.getElementById('edit_match_percentage').value = mapping.match_percentage;
    document.getElementById('edit_is_eligible').value = mapping.is_eligible ? '1' : '0';

    // Set the form action URL
    document.getElementById('editMappingForm').action = `/resource-person/cs110-lists/{{ $programCode }}/mappings/${mappingId}`;

    // Show the modal
    const editModal = new bootstrap.Modal(document.getElementById('editMappingModal'));
    editModal.show();
}

function deleteMapping(mappingId) {
    if (confirm('Are you sure you want to delete this course mapping?\n\nThis mapping will be moved to history and can be viewed later.')) {
        // Create a form to delete the mapping
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/resource-person/cs110-lists/{{ $programCode }}/mappings/${mappingId}`;

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';

        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';

        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
