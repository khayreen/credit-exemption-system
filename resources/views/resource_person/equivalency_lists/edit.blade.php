@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-0">Edit Equivalency List</h2>
        <p class="text-muted mb-0 mt-2">
            <span class="me-1">{{ $list->category_icon }}</span>
            {{ $list->source_display }} &rarr; {{ $list->program_code }}
            <span class="badge bg-secondary ms-2">{{ $list->semester }}</span>
        </p>
    </div>
    <div>
        <a href="{{ route('resource_person.equivalency_lists.index') }}" class="btn btn-outline-secondary me-2">
            <i class="fas fa-arrow-left"></i> Back to Lists
        </a>
        <a href="{{ route('resource_person.equivalency_lists.preview', $list) }}" class="btn btn-info me-2">
            <i class="fas fa-eye"></i> Preview
        </a>
        @if($equivalencies->count() > 0)
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#submitModal">
            <i class="fas fa-paper-plane"></i> Submit for Endorsement
        </button>
        @endif
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- List Info Card -->
<div class="card shadow-sm mb-4">
    <div class="card-header {{ $list->isInternal() ? 'bg-primary' : 'bg-success' }} text-white">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <span class="me-2" style="font-size: 1.5em;">{{ $list->category_icon }}</span>
                <div>
                    <h5 class="mb-0">{{ $list->isInternal() ? 'CS110 (UiTM Diploma)' : $list->source_institution }}</h5>
                    <small class="opacity-75">{{ $list->program_code }} - {{ $list->program_name }}</small>
                </div>
            </div>
            <div class="text-end">
                @include('resource_person.equivalency_lists.partials.status_badge', ['list' => $list])
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 text-center border-end">
                <h3 class="mb-0 text-primary">{{ $list->total_equivalencies }}</h3>
                <small class="text-muted">Total Mappings</small>
            </div>
            <div class="col-md-3 text-center border-end">
                <h3 class="mb-0 text-success">{{ $list->eligible_count }}</h3>
                <small class="text-muted">Eligible (&ge;80%)</small>
            </div>
            <div class="col-md-3 text-center border-end">
                <h3 class="mb-0 text-danger">{{ $list->not_eligible_count }}</h3>
                <small class="text-muted">Not Eligible (&lt;80%)</small>
            </div>
            <div class="col-md-3 text-center">
                <h3 class="mb-0 text-info">{{ $list->total_equivalencies > 0 ? round(($list->eligible_count / $list->total_equivalencies) * 100, 1) : 0 }}%</h3>
                <small class="text-muted">Eligibility Rate</small>
            </div>
        </div>
    </div>
</div>

<!-- Add New Mapping Form -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Add New Course Mapping</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('resource_person.equivalency_lists.add_mapping', $list) }}" method="POST">
            @csrf
            <div class="row">
                <!-- Diploma Course -->
                <div class="col-md-6">
                    <h6 class="text-muted mb-3">
                        <span class="me-1">{{ $list->category_icon }}</span>
                        Diploma Course ({{ $list->isInternal() ? 'CS110' : $list->source_institution }})
                    </h6>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Course Code <span class="text-danger">*</span></label>
                            <input type="text" name="diploma_course_code" class="form-control" placeholder="e.g., CSC118" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Course Name <span class="text-danger">*</span></label>
                            <input type="text" name="diploma_course_name" class="form-control" placeholder="e.g., Introduction to Computers" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Credits <span class="text-danger">*</span></label>
                            <input type="number" name="diploma_credit_hour" class="form-control" min="1" max="10" placeholder="3" required>
                        </div>
                    </div>
                </div>

                <!-- Arrow Separator -->
                <div class="col-md-1 d-flex align-items-center justify-content-center">
                    <i class="fas fa-arrow-right fa-2x text-muted"></i>
                </div>

                <!-- Degree Course -->
                <div class="col-md-5">
                    <h6 class="text-muted mb-3">
                        <i class="fas fa-graduation-cap me-1"></i>
                        Degree Course ({{ $list->program_code }})
                    </h6>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Course Code <span class="text-danger">*</span></label>
                            <input type="text" name="degree_course_code" class="form-control" placeholder="e.g., CSC650" required>
                        </div>
                        <div class="col-md-5 mb-3">
                            <label class="form-label">Course Name <span class="text-danger">*</span></label>
                            <input type="text" name="degree_course_name" class="form-control" placeholder="e.g., Computer Architecture" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Credits <span class="text-danger">*</span></label>
                            <input type="number" name="degree_credit_hour" class="form-control" min="1" max="10" placeholder="3" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Match Percentage <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" name="match_percentage" class="form-control" min="0" max="100" step="0.1" placeholder="85" required>
                        <span class="input-group-text">%</span>
                    </div>
                    <small class="text-muted">&ge;80% = Eligible for exemption</small>
                </div>
                <div class="col-md-9 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Add Mapping
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Existing Mappings Table -->
<div class="card shadow-sm">
    <div class="card-header bg-light">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Course Mappings ({{ $equivalencies->count() }})</h5>
        </div>
    </div>
    <div class="card-body p-0">
        @if($equivalencies->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">No Course Mappings Yet</h5>
                <p class="text-muted">Use the form above to add course mappings to this list.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Diploma Course</th>
                            <th></th>
                            <th>Degree Course</th>
                            <th class="text-center">Match %</th>
                            <th class="text-center">Eligible</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($equivalencies as $index => $mapping)
                        <tr id="mapping-{{ $mapping->id }}">
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $mapping->diploma_course_code }}</strong><br>
                                <small class="text-muted">{{ Str::limit($mapping->diploma_course_name, 30) }}</small><br>
                                <small class="text-muted">{{ $mapping->diploma_credit_hour }} credits</small>
                            </td>
                            <td class="text-center align-middle">
                                <i class="fas fa-arrow-right text-muted"></i>
                            </td>
                            <td>
                                <strong>{{ $mapping->degree_course_code }}</strong><br>
                                <small class="text-muted">{{ Str::limit($mapping->degree_course_name, 30) }}</small><br>
                                <small class="text-muted">{{ $mapping->degree_credit_hour }} credits</small>
                            </td>
                            <td class="text-center align-middle">
                                <span class="badge {{ $mapping->match_percentage >= 80 ? 'bg-success' : 'bg-danger' }} fs-6">
                                    {{ $mapping->match_percentage }}%
                                </span>
                            </td>
                            <td class="text-center align-middle">
                                @if($mapping->is_eligible)
                                    <i class="fas fa-check-circle text-success fa-lg" title="Eligible"></i>
                                @else
                                    <i class="fas fa-times-circle text-danger fa-lg" title="Not Eligible"></i>
                                @endif
                            </td>
                            <td class="text-end align-middle">
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal-{{ $mapping->id }}" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('resource_person.equivalency_lists.delete_mapping', [$list, $mapping]) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this mapping?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Modal for this mapping -->
                        <div class="modal fade" id="editModal-{{ $mapping->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form action="{{ route('resource_person.equivalency_lists.update_mapping', [$list, $mapping]) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Course Mapping</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6 class="text-muted mb-3">Diploma Course</h6>
                                                    <div class="mb-3">
                                                        <label class="form-label">Course Code</label>
                                                        <input type="text" name="diploma_course_code" class="form-control" value="{{ $mapping->diploma_course_code }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Course Name</label>
                                                        <input type="text" name="diploma_course_name" class="form-control" value="{{ $mapping->diploma_course_name }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Credits</label>
                                                        <input type="number" name="diploma_credit_hour" class="form-control" value="{{ $mapping->diploma_credit_hour }}" min="1" max="10" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6 class="text-muted mb-3">Degree Course</h6>
                                                    <div class="mb-3">
                                                        <label class="form-label">Course Code</label>
                                                        <input type="text" name="degree_course_code" class="form-control" value="{{ $mapping->degree_course_code }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Course Name</label>
                                                        <input type="text" name="degree_course_name" class="form-control" value="{{ $mapping->degree_course_name }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Credits</label>
                                                        <input type="number" name="degree_credit_hour" class="form-control" value="{{ $mapping->degree_credit_hour }}" min="1" max="10" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="form-label">Match Percentage</label>
                                                    <div class="input-group">
                                                        <input type="number" name="match_percentage" class="form-control" value="{{ $mapping->match_percentage }}" min="0" max="100" step="0.1" required>
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Save Changes</button>
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

<!-- Submit Modal -->
<div class="modal fade" id="submitModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('resource_person.equivalency_lists.submit', $list) }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fas fa-paper-plane me-2"></i>Submit for HEA Endorsement</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        <strong>Important:</strong> Once submitted, you cannot edit this list until HEA reviews it.
                    </div>

                    <div class="card mb-3">
                        <div class="card-body">
                            <p class="mb-1"><strong>Category:</strong> {{ $list->category_icon }} {{ $list->source_display }}</p>
                            <p class="mb-1"><strong>Target:</strong> {{ $list->program_code }} - {{ $list->program_name }}</p>
                            <p class="mb-1"><strong>Semester:</strong> {{ $list->semester }}</p>
                            <p class="mb-1"><strong>Total Mappings:</strong> {{ $list->total_equivalencies }}</p>
                            <p class="mb-0"><strong>Eligible:</strong> {{ $list->eligible_count }} | <strong>Not Eligible:</strong> {{ $list->not_eligible_count }}</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="submission_notes" class="form-label">Submission Notes (Optional)</label>
                        <textarea name="submission_notes" id="submission_notes" class="form-control" rows="3" placeholder="Any notes for HEA reviewer..."></textarea>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" name="confirm" id="confirm" class="form-check-input" value="1" required>
                        <label for="confirm" class="form-check-label">
                            I confirm that all equivalency mappings have been reviewed and are accurate for the selected semester.
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-paper-plane me-1"></i> Submit for Endorsement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
