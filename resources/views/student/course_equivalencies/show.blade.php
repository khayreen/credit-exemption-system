@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-clipboard-list me-2"></i>Course Equivalency List</h2>
    <div>
        <a href="{{ route('student.course_equivalencies.pdf', $list) }}" class="btn btn-outline-secondary" target="_blank">
            <i class="fas fa-file-pdf me-1"></i> Download PDF
        </a>
        <a href="{{ route('student.course_equivalencies.index', ['program' => $list->program_code]) }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<!-- List Information Card -->
<div class="card shadow-sm mb-4">
    <div class="card-header {{ $list->isInternal() ? 'bg-primary' : 'bg-success' }} text-white">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-1">
                    @if($list->isInternal())
                        <i class="fas fa-home me-2"></i>CS110 (UiTM Diploma in Computer Science)
                    @else
                        <i class="fas fa-globe me-2"></i>{{ $list->source_institution }}
                    @endif
                </h5>
                <small>
                    <i class="fas fa-arrow-right me-1"></i>
                    {{ $list->program_code }} - {{ $list->program_name }}
                </small>
            </div>
            <div class="text-end">
                <span class="badge bg-light text-dark">{{ $list->semester }}</span>
                @if($list->is_active)
                    <span class="badge bg-warning text-dark ms-1">CURRENT</span>
                @endif
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted" width="150">Source:</td>
                        <td>{{ $list->source_display }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Target Program:</td>
                        <td><strong>{{ $list->program_code }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Semester:</td>
                        <td>{{ $list->semester }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted" width="150">Published:</td>
                        <td>{{ $list->published_at?->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Endorsed By:</td>
                        <td>{{ $list->endorser->name ?? 'HEA Unit' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        @if($list->isInternal())
        <div class="alert alert-primary mt-3 mb-0">
            <i class="fas fa-star me-1"></i>
            <strong>Highest Similarity:</strong> CS110 students typically qualify for the most credit exemptions due to high curriculum similarity with {{ $list->program_code }}.
        </div>
        @endif
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-center border-primary">
            <div class="card-body py-3">
                <h3 class="mb-0 text-primary">{{ $list->total_equivalencies }}</h3>
                <small class="text-muted">Total Course Mappings</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center border-success">
            <div class="card-body py-3">
                <h3 class="mb-0 text-success">{{ $list->eligible_count }}</h3>
                <small class="text-muted">Eligible for Exemption</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center border-warning">
            <div class="card-body py-3">
                <h3 class="mb-0 text-warning">{{ $list->not_eligible_count }}</h3>
                <small class="text-muted">Not Eligible</small>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filter -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('student.course_equivalencies.show', ['category' => $category, 'source' => $source]) }}" class="row g-3 align-items-center">
            <input type="hidden" name="program" value="{{ $list->program_code }}">
            <div class="col-md-8">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Search by course code or name..." value="{{ $search }}">
                </div>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-search me-1"></i> Search
                </button>
                @if($search)
                <a href="{{ route('student.course_equivalencies.show', ['category' => $category, 'source' => $source, 'program' => $list->program_code]) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-1"></i> Clear
                </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Equivalency Table -->
<div class="card shadow-sm">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="fas fa-list me-1"></i> Course Equivalencies</h6>
        <span class="badge bg-secondary">
            @if($search)
                {{ $equivalencies->count() }} of {{ $list->total_equivalencies }} results
            @else
                {{ $equivalencies->count() }} courses
            @endif
        </span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="50">#</th>
                        <th>
                            Your Diploma Course
                            <br><small class="text-muted">({{ $list->isInternal() ? 'CS110' : $list->source_institution }})</small>
                        </th>
                        <th>
                            Equivalent Degree Course
                            <br><small class="text-muted">({{ $list->program_code }})</small>
                        </th>
                        <th width="150" class="text-center">Exemption Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($equivalencies as $index => $eq)
                    <tr>
                        <td class="text-muted">{{ $index + 1 }}</td>
                        <td>
                            <div class="fw-bold">{{ $eq->diploma_course_code }}</div>
                            <small class="text-muted">{{ $eq->diploma_course_name }}</small>
                            <br><small class="badge bg-light text-dark">{{ $eq->diploma_credit_hour }} credit hours</small>
                        </td>
                        <td>
                            <div class="fw-bold">{{ $eq->degree_course_code }}</div>
                            <small class="text-muted">{{ $eq->degree_course_name }}</small>
                            <br><small class="badge bg-light text-dark">{{ $eq->degree_credit_hour }} credit hours</small>
                        </td>
                        <td class="text-center">
                            @if($eq->is_eligible)
                                <span class="badge bg-success fs-6">
                                    <i class="fas fa-check-circle me-1"></i> ELIGIBLE FOR EXEMPTION
                                </span>
                            @else
                                <span class="badge bg-danger fs-6">
                                    <i class="fas fa-times-circle me-1"></i> NOT ELIGIBLE
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4">
                            @if($search)
                                <i class="fas fa-search fa-2x text-muted mb-2"></i>
                                <p class="text-muted mb-0">No courses found matching "{{ $search }}"</p>
                            @else
                                <p class="text-muted mb-0">No course equivalencies available.</p>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-light">
        <div class="row">
            <div class="col-md-12 text-center">
                <small class="text-muted">
                    <i class="fas fa-info-circle"></i> <strong>Note:</strong> To qualify for exemption, you must have achieved grade <strong>C or above</strong> in the diploma course
                </small>
            </div>
        </div>
    </div>
</div>

<!-- Important Notice -->
<div class="card shadow-sm mt-4">
    <div class="card-header bg-warning text-dark">
        <h6 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Important Notice</h6>
    </div>
    <div class="card-body">
        <ul class="mb-0">
            <li>This list shows course equivalencies based on curriculum comparison and approval by Resource Persons and Program Coordinators.</li>
            <li><strong>To qualify for credit exemption, you must:</strong>
                <ul>
                    <li>Have a course marked as "ELIGIBLE FOR EXEMPTION"</li>
                    <li>Have achieved grade <strong>C or above</strong> in the diploma course</li>
                </ul>
            </li>
            <li>Submit your official transcript when applying for credit exemption.</li>
            <li>Contact your academic advisor if you have questions about specific courses.</li>
        </ul>
    </div>
</div>
@endsection
