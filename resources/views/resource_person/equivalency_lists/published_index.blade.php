@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Page Header -->
    <div class="aa-page-header mb-4">
        <div class="aa-header-icon">
            <i class="fas fa-clipboard-list"></i>
        </div>
        <div class="aa-header-content">
            <h1 class="aa-header-title">CS110 EQUIVALENCY LISTS</h1>
            <p class="aa-header-subtitle">
                View and manage course equivalency lists organized by degree program
                @if(!empty($assignedPrograms))
                    <br><small class="text-primary">
                        <i class="fas fa-star me-1"></i>Your assigned program(s):
                        @foreach($assignedPrograms as $program)
                            <span class="badge bg-primary">{{ $program }}</span>
                        @endforeach
                    </small>
                @endif
            </p>
        </div>
        <div class="aa-header-action">
            <a href="{{ route('resource_person.course_equivalencies.view') }}" class="btn btn-primary">
                <i class="fas fa-search me-2"></i>All Course Mappings
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

    <!-- Program Tabs -->
    <ul class="nav nav-tabs mb-4" id="programTabs" role="tablist">
        @foreach($programs as $index => $programCode)
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $defaultProgram === $programCode ? 'active' : '' }} {{ in_array($programCode, $assignedPrograms) ? 'assigned-tab' : '' }}"
                    id="{{ strtolower($programCode) }}-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#{{ strtolower($programCode) }}"
                    type="button"
                    role="tab">
                @if(in_array($programCode, $assignedPrograms))
                    <i class="fas fa-star text-warning me-1" title="Your assigned program"></i>
                @endif
                <strong>{{ $programCode }}</strong>
            </button>
        </li>
        @endforeach
    </ul>

    <div class="tab-content" id="programTabsContent">
        @foreach($programs as $index => $programCode)
        <div class="tab-pane fade {{ $defaultProgram === $programCode ? 'show active' : '' }}"
             id="{{ strtolower($programCode) }}"
             role="tabpanel">

            <!-- Program Name & Status -->
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h4 class="mb-1 text-primary">
                        <i class="fas fa-graduation-cap me-2"></i>{{ $programData[$programCode]['name'] }}
                    </h4>
                    @if($programData[$programCode]['current'])
                        @php $list = $programData[$programCode]['current']; @endphp
                        <div class="mt-2">
                            <span class="badge bg-{{ $list->status_badge_class ?? 'secondary' }} me-1">
                                {{ $list->status_display ?? ucfirst($list->status) }}
                            </span>
                            @if($list->target_semester)
                                <span class="badge bg-warning text-dark me-1">Target: {{ $list->target_semester }}</span>
                            @endif
                            @if($list->semester)
                                <span class="badge bg-success me-1">Last Published: {{ $list->semester }}</span>
                            @endif
                            @if($list->is_active)
                                <span class="badge bg-info">Active</span>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="d-flex gap-2">
                    @if($programData[$programCode]['isAssigned'])
                        <!-- Edit & Submit buttons for assigned programs -->
                        @if($programData[$programCode]['current'])
                            @if($programData[$programCode]['canEdit'])
                                <a href="{{ route('resource_person.equivalency_lists.edit', $programCode) }}" class="btn btn-warning">
                                    <i class="fas fa-edit me-1"></i>Edit List
                                </a>
                            @endif
                            @if($programData[$programCode]['canSubmit'])
                                <button type="button" class="btn btn-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#submitModal"
                                        onclick="setSubmitProgram('{{ $programCode }}', {{ $programData[$programCode]['current']->courseEquivalencies->count() ?? 0 }})">
                                    <i class="fas fa-paper-plane me-1"></i>Submit to HEA
                                </button>
                            @endif
                        @else
                            <!-- No list exists yet - create one -->
                            <a href="{{ route('resource_person.equivalency_lists.edit', $programCode) }}" class="btn btn-success">
                                <i class="fas fa-plus-circle me-1"></i>Create List
                            </a>
                        @endif
                    @endif
                    @if($programData[$programCode]['current'] && $programData[$programCode]['current']->status === 'published')
                        <a href="{{ route('resource_person.equivalency_lists.pdf', $programData[$programCode]['current']) }}"
                           class="btn btn-danger"
                           target="_blank">
                            <i class="fas fa-file-pdf me-1"></i>View as PDF
                        </a>
                    @endif
                </div>
            </div>

            @if($programData[$programCode]['current'])
                @php $list = $programData[$programCode]['current']; @endphp

                <!-- Rejection Alert -->
                @if($list->status === 'rejected')
                    <div class="alert alert-danger">
                        <h6><i class="fas fa-times-circle me-2"></i>This list was rejected by HEA</h6>
                        <p class="mb-0"><strong>Reason:</strong> {{ $list->review_notes }}</p>
                        <small class="text-muted">Please make the necessary changes and resubmit.</small>
                    </div>
                @endif

                <!-- List Information Card -->
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i>List Information
                            </h6>
                            @if($programData[$programCode]['isAssigned'])
                                <span class="badge bg-warning text-dark px-3 py-2">
                                    <i class="fas fa-star me-1"></i>YOUR ASSIGNED PROGRAM
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <th width="150">Program Code:</th>
                                        <td><span class="badge bg-primary">{{ $list->program_code }}</span></td>
                                    </tr>
                                    <tr>
                                        <th>Program Name:</th>
                                        <td>{{ $list->program_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Category:</th>
                                        <td><span class="badge bg-primary">Internal (CS110)</span></td>
                                    </tr>
                                    <tr>
                                        <th>Semester:</th>
                                        <td>{{ $list->semester ?? 'Not published yet' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <th width="150">Status:</th>
                                        <td><span class="badge bg-{{ $list->status_badge_class ?? 'secondary' }}">{{ $list->status_display ?? ucfirst($list->status) }}</span></td>
                                    </tr>
                                    <tr>
                                        <th>Total Mappings:</th>
                                        <td><strong>{{ $list->courseEquivalencies->count() }}</strong> course(s)</td>
                                    </tr>
                                    <tr>
                                        <th>Created By:</th>
                                        <td>{{ $list->creator->name ?? 'N/A' }}</td>
                                    </tr>
                                    @if($list->published_at)
                                        <tr>
                                            <th>Published At:</th>
                                            <td>{{ $list->published_at->format('d M Y, h:i A') }}</td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Course Mappings -->
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                <i class="fas fa-exchange-alt me-2"></i>Course Equivalency Mappings ({{ $list->courseEquivalencies->count() }})
                            </h6>
                            @if($programData[$programCode]['isAssigned'] && $programData[$programCode]['canEditInline'])
                                <button type="button" class="btn btn-light btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#addMappingModal"
                                        onclick="setAddMappingProgram('{{ $programCode }}')">
                                    <i class="fas fa-plus-circle me-1"></i>Add Mapping
                                </button>
                            @endif
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if($list->courseEquivalencies->isEmpty())
                            <div class="text-center py-5">
                                <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                                <h5 class="text-muted">No Course Mappings</h5>
                                <p class="text-muted mb-0">This equivalency list does not have any course mappings yet.</p>
                                @if($programData[$programCode]['isAssigned'] && $programData[$programCode]['canEditInline'])
                                    <button type="button" class="btn btn-success mt-3"
                                            data-bs-toggle="modal"
                                            data-bs-target="#addMappingModal"
                                            onclick="setAddMappingProgram('{{ $programCode }}')">
                                        <i class="fas fa-plus-circle me-1"></i>Add First Mapping
                                    </button>
                                @elseif($programData[$programCode]['isAssigned'] && $programData[$programCode]['canEdit'])
                                    <p class="text-muted mt-3">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Click <strong>"Edit List"</strong> button above to add mappings.
                                    </p>
                                @endif
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="5%" class="text-center">#</th>
                                            <th width="30%">Diploma Course</th>
                                            <th width="30%">Degree Course</th>
                                            <th width="10%" class="text-center">Match %</th>
                                            <th width="10%" class="text-center">Credit Hours</th>
                                            <th width="5%" class="text-center">Eligible</th>
                                            @if($programData[$programCode]['isAssigned'] && $programData[$programCode]['canEditInline'])
                                                <th width="10%" class="text-center">Actions</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($list->courseEquivalencies->sortBy('diploma_course_code') as $idx => $eq)
                                        <tr>
                                            <td class="text-center text-muted">{{ $idx + 1 }}</td>
                                            <td>
                                                <strong>{{ $eq->diploma_course_code }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $eq->diploma_course_name }}</small>
                                                <br>
                                                <small class="text-muted">
                                                    <i class="fas fa-university me-1"></i>{{ $eq->diploma_institution ?? 'CS110' }}
                                                </small>
                                            </td>
                                            <td>
                                                <strong>{{ $eq->degree_course_code }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $eq->degree_course_name }}</small>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge fs-6 bg-{{ $eq->match_percentage >= 80 ? 'success' : 'warning' }}">
                                                    {{ number_format($eq->match_percentage, 0) }}%
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <small class="text-muted">
                                                    {{ $eq->diploma_credit_hour }} <i class="fas fa-arrow-right"></i> {{ $eq->degree_credit_hour }}
                                                </small>
                                            </td>
                                            <td class="text-center">
                                                @if($eq->is_eligible)
                                                    <i class="fas fa-check-circle text-success fs-5"></i>
                                                @else
                                                    <i class="fas fa-times-circle text-danger fs-5"></i>
                                                @endif
                                            </td>
                                            @if($programData[$programCode]['isAssigned'] && $programData[$programCode]['canEditInline'])
                                                <td class="text-center">
                                                    <button class="btn btn-sm btn-primary me-1" onclick="editMapping('{{ $programCode }}', '{{ $eq->id }}', {{ json_encode($eq) }})">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger" onclick="deleteMapping('{{ $programCode }}', '{{ $eq->id }}')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            @endif
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Statistics Footer -->
                            <div class="card-footer bg-light">
                                <div class="row text-center">
                                    <div class="col-md-4">
                                        <strong>Total Mappings:</strong>
                                        <span class="badge bg-primary ms-2">{{ $list->courseEquivalencies->count() }}</span>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Eligible (>=80%):</strong>
                                        <span class="badge bg-success ms-2">{{ $list->courseEquivalencies->where('is_eligible', true)->count() }}</span>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Not Eligible (<80%):</strong>
                                        <span class="badge bg-warning text-dark ms-2">{{ $list->courseEquivalencies->where('is_eligible', false)->count() }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Course Mapping History (for assigned programs) -->
                @if($programData[$programCode]['isAssigned'] && $list->courseEquivalencyHistory && $list->courseEquivalencyHistory->count() > 0)
                <div class="card shadow-sm mt-4">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="mb-0"><i class="fas fa-history me-2"></i>Course Mapping History ({{ $list->courseEquivalencyHistory->count() }})</h6>
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

                <!-- History -->
                @if($programData[$programCode]['history']->isNotEmpty())
                <div class="mt-4">
                    <h5 class="mb-3">
                        <i class="fas fa-history me-2"></i>Previous Published Lists
                    </h5>
                    @foreach($programData[$programCode]['history'] as $historyList)
                    <div class="card mb-3 border-secondary">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h6 class="mb-2">
                                        <i class="fas fa-calendar-alt me-2 text-muted"></i>{{ $historyList->semester }}
                                    </h6>
                                    <p class="mb-1">
                                        <span class="badge bg-primary bg-opacity-75">
                                            UiTM CS110
                                        </span>
                                        <span class="badge bg-secondary ms-2">
                                            <i class="fas fa-book me-1"></i>{{ $historyList->courseEquivalencies->count() }} courses
                                        </span>
                                    </p>
                                    <p class="text-muted mb-0">
                                        <small>
                                            <i class="fas fa-user me-1"></i>Published by {{ $historyList->publisher->name ?? 'N/A' }}
                                            on {{ $historyList->published_at ? $historyList->published_at->format('d M Y') : 'N/A' }}
                                        </small>
                                    </p>
                                </div>
                                <div class="col-md-6 text-end">
                                    <span class="badge bg-secondary fs-6 px-3 py-2 me-2">
                                        <i class="fas fa-archive"></i> ARCHIVED
                                    </span>
                                    <a href="{{ route('resource_person.equivalency_lists.show', $historyList) }}"
                                       class="btn btn-outline-secondary">
                                        <i class="fas fa-eye me-1"></i>View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

            @else
                <!-- No list exists yet -->
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">No Equivalency List Yet</h5>
                    <p class="text-muted">No equivalency list has been created for this program yet.</p>
                    @if($programData[$programCode]['isAssigned'])
                        <a href="{{ route('resource_person.equivalency_lists.edit', $programCode) }}" class="btn btn-success mt-2">
                            <i class="fas fa-plus-circle me-1"></i>Create List for {{ $programCode }}
                        </a>
                    @endif
                </div>
            @endif
        </div>
        @endforeach
    </div>
</div>

<!-- Add Mapping Modal -->
<div class="modal fade" id="addMappingModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Add Course Mapping</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="addMappingForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>CS110 Internal Mapping:</strong> Map CS110 diploma courses to <span id="addMappingProgramDisplay"></span> degree courses
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
                            <h6 class="text-success mb-3"><span id="addMappingDegreeLabel"></span> Degree Course</h6>
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
                                <small class="text-muted">Recommended: >=80% for eligibility</small>
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
                            <h6 class="text-success mb-3"><span id="editMappingDegreeLabel"></span> Degree Course</h6>
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
                                <small class="text-muted">Recommended: >=80% for eligibility</small>
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

<!-- Submit to HEA Modal -->
<div class="modal fade" id="submitModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-paper-plane me-2"></i>Submit to HEA</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="submitForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong><span id="submitMappingCount">0</span> course mappings</strong> will be submitted to HEA for review and endorsement.
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
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-1"></i>Submit to HEA
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Form (Hidden) -->
<form id="deleteMappingForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<style>
/* AA Page Header - Consistent across all AA pages */
.aa-page-header {
    background: linear-gradient(to right, #f8f9fa 0%, #ffffff 100%);
    border-left: 5px solid #667eea;
    padding: 1.75rem 1.5rem;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 1.25rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    flex-wrap: wrap;
}

.aa-header-icon {
    width: 50px;
    height: 50px;
    background: #667eea;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
    flex-shrink: 0;
}

.aa-header-content {
    flex: 1;
}

.aa-header-title {
    font-size: 1.35rem;
    font-weight: 700;
    margin: 0;
    color: #1e293b;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    line-height: 1.3;
}

.aa-header-subtitle {
    font-size: 0.95rem;
    color: #64748b;
    margin: 0.35rem 0 0 0;
    font-weight: 500;
}

.aa-header-action {
    margin-left: auto;
}

@media (max-width: 768px) {
    .aa-page-header {
        flex-direction: column;
        text-align: center;
    }

    .aa-header-action {
        margin-left: 0;
        width: 100%;
    }

    .aa-header-action .btn {
        width: 100%;
    }

    .aa-header-title {
        font-size: 1.1rem;
    }

    .aa-header-subtitle {
        font-size: 0.85rem;
    }

    .aa-header-icon {
        width: 40px;
        height: 40px;
        font-size: 20px;
    }
}

.nav-tabs .nav-link {
    color: #495057;
    font-weight: 500;
}

.nav-tabs .nav-link.active {
    color: #0d6efd;
    font-weight: 600;
    border-bottom: 3px solid #0d6efd;
}

.nav-tabs .nav-link.assigned-tab {
    background-color: #fff3cd;
    border-color: #ffc107 #ffc107 transparent;
}

.nav-tabs .nav-link.assigned-tab.active {
    background-color: #fff;
    border-bottom: 3px solid #ffc107;
}

.card {
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-2px);
}
</style>

<script>
function setAddMappingProgram(programCode) {
    document.getElementById('addMappingForm').action = `/resource-person/cs110-lists/${programCode}/mappings`;
    document.getElementById('addMappingProgramDisplay').textContent = programCode;
    document.getElementById('addMappingDegreeLabel').textContent = programCode;
}

function editMapping(programCode, mappingId, mapping) {
    // Populate the edit form with existing data
    document.getElementById('edit_diploma_course_code').value = mapping.diploma_course_code;
    document.getElementById('edit_diploma_course_name').value = mapping.diploma_course_name;
    document.getElementById('edit_diploma_credit_hour').value = mapping.diploma_credit_hour;
    document.getElementById('edit_degree_course_code').value = mapping.degree_course_code;
    document.getElementById('edit_degree_course_name').value = mapping.degree_course_name;
    document.getElementById('edit_degree_credit_hour').value = mapping.degree_credit_hour;
    document.getElementById('edit_match_percentage').value = mapping.match_percentage;
    document.getElementById('edit_is_eligible').value = mapping.is_eligible ? '1' : '0';
    document.getElementById('editMappingDegreeLabel').textContent = programCode;

    // Set the form action URL
    document.getElementById('editMappingForm').action = `/resource-person/cs110-lists/${programCode}/mappings/${mappingId}`;

    // Show the modal
    const editModal = new bootstrap.Modal(document.getElementById('editMappingModal'));
    editModal.show();
}

function deleteMapping(programCode, mappingId) {
    if (confirm('Are you sure you want to delete this course mapping?\n\nThis mapping will be moved to history and can be viewed later.')) {
        const form = document.getElementById('deleteMappingForm');
        form.action = `/resource-person/cs110-lists/${programCode}/mappings/${mappingId}`;
        form.submit();
    }
}

function setSubmitProgram(programCode, mappingCount) {
    document.getElementById('submitForm').action = `/resource-person/cs110-lists/${programCode}/submit`;
    document.getElementById('submitMappingCount').textContent = mappingCount;
}
</script>
@endsection
