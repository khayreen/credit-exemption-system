@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">
                <i class="fas fa-edit text-warning me-2"></i>Edit Equivalency List
            </h2>
            <p class="text-muted mb-0">
                {{ $list->program_code }} | {{ $list->semester }}
                @if($list->category === 'external')
                    | {{ $list->source_institution }}
                @else
                    | UiTM CS110 (Internal)
                @endif
            </p>
        </div>
        <div>
            <a href="{{ route('program_coordinator.equivalency_lists.show', $list) }}" class="btn btn-secondary me-2">
                <i class="fas fa-eye"></i> View Details
            </a>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#publishModal">
                <i class="fas fa-rocket"></i> Publish List
            </button>
        </div>
    </div>

    <!-- Add New Mapping Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">
                <i class="fas fa-plus-circle me-2"></i>Add New Course Mapping
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('program_coordinator.equivalency_lists.addMapping', $list) }}" method="POST">
                @csrf

                <div class="row">
                    <!-- Diploma Course Section -->
                    <div class="col-md-5">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-graduation-cap me-2"></i>Diploma Course Details
                        </h6>

                        <div class="mb-3">
                            <label for="diploma_course_code" class="form-label fw-bold">Course Code</label>
                            <input type="text"
                                   name="diploma_course_code"
                                   id="diploma_course_code"
                                   class="form-control @error('diploma_course_code') is-invalid @enderror"
                                   placeholder="e.g., DCS110"
                                   value="{{ old('diploma_course_code') }}"
                                   required>
                            @error('diploma_course_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="diploma_course_name" class="form-label fw-bold">Course Name</label>
                            <input type="text"
                                   name="diploma_course_name"
                                   id="diploma_course_name"
                                   class="form-control @error('diploma_course_name') is-invalid @enderror"
                                   placeholder="e.g., Introduction to Computer Science"
                                   value="{{ old('diploma_course_name') }}"
                                   required>
                            @error('diploma_course_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="diploma_credit_hour" class="form-label fw-bold">Credit Hours</label>
                            <input type="number"
                                   name="diploma_credit_hour"
                                   id="diploma_credit_hour"
                                   class="form-control @error('diploma_credit_hour') is-invalid @enderror"
                                   min="1"
                                   max="10"
                                   value="{{ old('diploma_credit_hour', 3) }}"
                                   required>
                            @error('diploma_credit_hour')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Arrow -->
                    <div class="col-md-1 d-flex align-items-center justify-content-center">
                        <i class="fas fa-arrow-right fa-3x text-muted"></i>
                    </div>

                    <!-- Degree Course Section -->
                    <div class="col-md-5">
                        <h6 class="text-info mb-3">
                            <i class="fas fa-university me-2"></i>Degree Course Details
                        </h6>

                        <div class="mb-3">
                            <label for="degree_course_code" class="form-label fw-bold">Course Code</label>
                            <input type="text"
                                   name="degree_course_code"
                                   id="degree_course_code"
                                   class="form-control @error('degree_course_code') is-invalid @enderror"
                                   placeholder="e.g., CS110"
                                   value="{{ old('degree_course_code') }}"
                                   required>
                            @error('degree_course_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="degree_course_name" class="form-label fw-bold">Course Name</label>
                            <input type="text"
                                   name="degree_course_name"
                                   id="degree_course_name"
                                   class="form-control @error('degree_course_name') is-invalid @enderror"
                                   placeholder="e.g., Programming Fundamentals"
                                   value="{{ old('degree_course_name') }}"
                                   required>
                            @error('degree_course_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="degree_credit_hour" class="form-label fw-bold">Credit Hours</label>
                            <input type="number"
                                   name="degree_credit_hour"
                                   id="degree_credit_hour"
                                   class="form-control @error('degree_credit_hour') is-invalid @enderror"
                                   min="1"
                                   max="10"
                                   value="{{ old('degree_credit_hour', 3) }}"
                                   required>
                            @error('degree_credit_hour')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Match Percentage -->
                    <div class="col-md-1 d-flex align-items-center">
                        <div class="w-100">
                            <label for="match_percentage" class="form-label fw-bold small">Match %</label>
                            <input type="number"
                                   name="match_percentage"
                                   id="match_percentage"
                                   class="form-control @error('match_percentage') is-invalid @enderror"
                                   min="0"
                                   max="100"
                                   step="0.01"
                                   value="{{ old('match_percentage', 85) }}"
                                   required>
                            @error('match_percentage')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-plus-circle me-2"></i>Add Course Mapping
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Existing Mappings -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>Existing Course Mappings ({{ $equivalencies->count() }})
            </h5>
        </div>
        <div class="card-body p-0">
            @if($equivalencies->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">No Course Mappings Yet</h5>
                    <p class="text-muted mb-0">Use the form above to add your first course mapping.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="5%" class="text-center">#</th>
                                <th width="30%">Diploma Course</th>
                                <th width="30%">Degree Course</th>
                                <th width="8%" class="text-center">Match %</th>
                                <th width="10%" class="text-center">Credit Hours</th>
                                <th width="7%" class="text-center">Eligible</th>
                                <th width="10%" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($equivalencies as $index => $eq)
                            <tr id="mapping-{{ $eq->id }}">
                                <td class="text-center text-muted">{{ $index + 1 }}</td>
                                <td>
                                    <strong>{{ $eq->diploma_course_code }}</strong>
                                    <br>
                                    <small class="text-muted">{{ Str::limit($eq->diploma_course_name, 50) }}</small>
                                </td>
                                <td>
                                    <strong>{{ $eq->degree_course_code }}</strong>
                                    <br>
                                    <small class="text-muted">{{ Str::limit($eq->degree_course_name, 50) }}</small>
                                </td>
                                <td class="text-center">
                                    <span class="badge fs-6 bg-{{ $eq->match_percentage >= 80 ? 'success' : 'warning' }}">
                                        {{ number_format($eq->match_percentage, 0) }}%
                                    </span>
                                </td>
                                <td class="text-center">
                                    <small>{{ $eq->diploma_credit_hour }} → {{ $eq->degree_credit_hour }}</small>
                                </td>
                                <td class="text-center">
                                    @if($eq->is_eligible)
                                        <i class="fas fa-check-circle text-success fs-5"></i>
                                    @else
                                        <i class="fas fa-times-circle text-danger fs-5"></i>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button type="button"
                                            class="btn btn-sm btn-outline-warning me-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editMappingModal-{{ $eq->id }}"
                                            title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('program_coordinator.equivalency_lists.deleteMapping', [$list, $eq]) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this mapping?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Modal for this mapping -->
                            <div class="modal fade" id="editMappingModal-{{ $eq->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form action="{{ route('program_coordinator.equivalency_lists.updateMapping', [$list, $eq]) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header bg-warning">
                                                <h5 class="modal-title">Edit Course Mapping</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <h6 class="text-primary mb-3">Diploma Course</h6>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Course Code</label>
                                                            <input type="text" name="diploma_course_code" class="form-control" value="{{ $eq->diploma_course_code }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Course Name</label>
                                                            <input type="text" name="diploma_course_name" class="form-control" value="{{ $eq->diploma_course_name }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Credit Hours</label>
                                                            <input type="number" name="diploma_credit_hour" class="form-control" value="{{ $eq->diploma_credit_hour }}" min="1" max="10" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h6 class="text-info mb-3">Degree Course</h6>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Course Code</label>
                                                            <input type="text" name="degree_course_code" class="form-control" value="{{ $eq->degree_course_code }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Course Name</label>
                                                            <input type="text" name="degree_course_name" class="form-control" value="{{ $eq->degree_course_name }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Credit Hours</label>
                                                            <input type="number" name="degree_credit_hour" class="form-control" value="{{ $eq->degree_credit_hour }}" min="1" max="10" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Match Percentage</label>
                                                    <input type="number" name="match_percentage" class="form-control" value="{{ $eq->match_percentage }}" min="0" max="100" step="0.01" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-warning">
                                                    <i class="fas fa-save me-2"></i>Update Mapping
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

                <!-- Statistics Footer -->
                <div class="card-footer bg-light">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <strong>Total Mappings:</strong>
                            <span class="badge bg-primary ms-2">{{ $equivalencies->count() }}</span>
                        </div>
                        <div class="col-md-4">
                            <strong>Eligible (≥80%):</strong>
                            <span class="badge bg-success ms-2">{{ $equivalencies->where('is_eligible', true)->count() }}</span>
                        </div>
                        <div class="col-md-4">
                            <strong>Not Eligible (<80%):</strong>
                            <span class="badge bg-warning text-dark ms-2">{{ $equivalencies->where('is_eligible', false)->count() }}</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Publish Confirmation Modal -->
<div class="modal fade" id="publishModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-rocket me-2"></i>Publish Equivalency List
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to publish this equivalency list?</p>
                <div class="alert alert-warning">
                    <strong>Note:</strong> Once published:
                    <ul class="mb-0">
                        <li>The list will become visible to students</li>
                        <li>You cannot edit the course mappings</li>
                        <li>Any previous active list for this program/category will be deactivated</li>
                    </ul>
                </div>
                <p class="mb-0"><strong>Current mappings:</strong> {{ $equivalencies->count() }} course(s)</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('program_coordinator.equivalency_lists.publish', $list) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-rocket me-2"></i>Publish Now
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div class="toast show" role="alert">
            <div class="toast-header bg-success text-white">
                <i class="fas fa-check-circle me-2"></i>
                <strong class="me-auto">Success</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                {{ session('success') }}
            </div>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div class="toast show" role="alert">
            <div class="toast-header bg-danger text-white">
                <i class="fas fa-exclamation-circle me-2"></i>
                <strong class="me-auto">Error</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                {{ session('error') }}
            </div>
        </div>
    </div>
@endif
@endsection
