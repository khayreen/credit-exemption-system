@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-clipboard-list me-2"></i>Course Equivalency Lists</h2>
    <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
    </a>
</div>

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if($selectedProgram)

<!-- Program Info Banner -->
<div class="alert alert-info mb-4">
    <i class="fas fa-info-circle me-2"></i>
    <strong>Your Enrolled Program:</strong> {{ $selectedProgram }} - {{ $programName }}
</div>

<!-- Important Notice for Non-CS110 Students -->
<div class="alert alert-warning border-warning mb-4" style="background-color: #fff9e6; border-left: 4px solid #ffc107;">
    <div class="d-flex align-items-start">
        <div style="font-size: 2rem; color: #ffc107; margin-right: 1rem;">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div style="flex: 1;">
            <h6 class="mb-2 fw-bold" style="color: #856404;">
                <i class="fas fa-info-circle me-1"></i>Important Notice
            </h6>
            <p class="mb-0" style="color: #856404; font-size: 0.95rem; line-height: 1.6;">
                <strong>For students from other institutions or those who did not complete CS110 (UiTM Diploma in Computer Science):</strong>
                The equivalency lists shown here may not apply to your diploma courses. Please contact your <strong>Academic Advisor</strong> to discuss course equivalency options for your specific institution and diploma program.
            </p>
        </div>
    </div>
</div>

<!-- Published Equivalency Lists (HEA Endorsed) -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0"><i class="fas fa-check-circle text-success me-2"></i>Published Course Equivalency Lists (HEA Endorsed)</h5>
    </div>
    <div class="card-body">
        <p class="text-muted mb-4">These equivalency lists have been endorsed by the Higher Education Authority (HEA) for {{ $selectedProgram }} - {{ $programName }}.</p>

        @php
            $publishedLists = collect([$internalList])->merge($externalLists->flatten())->filter();
        @endphp

        @if($publishedLists->count() > 0)
            @foreach($publishedLists as $list)
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
                                <div class="mb-2">
                                    <a href="{{ route('student.course_equivalencies.pdf', $list) }}" class="btn btn-sm btn-light" target="_blank">
                                        <i class="fas fa-file-pdf me-1"></i> View as PDF
                                    </a>
                                </div>
                                <div>
                                    <span class="badge bg-light text-dark">{{ $list->semester }}</span>
                                    @if($list->is_active)
                                        <span class="badge bg-warning text-dark ms-1">CURRENT</span>
                                    @endif
                                </div>
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
                    </div>
                </div>

                <!-- Statistics Cards -->
                @php
                    $totalDegreeCredits = $list->courseEquivalencies->sum('degree_credit_hour');
                    $totalDiplomaCredits = $list->courseEquivalencies->sum('diploma_credit_hour');
                @endphp
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
                                <h3 class="mb-0 text-success">{{ $totalDegreeCredits }}</h3>
                                <small class="text-muted">Total Degree Credit Hours</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center border-info">
                            <div class="card-body py-3">
                                <h3 class="mb-0 text-info">{{ $totalDiplomaCredits }}</h3>
                                <small class="text-muted">Total Diploma Credit Hours</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Equivalency Table -->
                <div class="card shadow-sm mb-5">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><i class="fas fa-list me-1"></i> Course Equivalencies</h6>
                        <span class="badge bg-secondary">{{ $list->courseEquivalencies->count() }} courses</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="50">#</th>
                                        <th width="40%">Your Diploma Course<br><small class="text-muted">({{ $list->isInternal() ? 'CS110' : $list->source_institution }})</small></th>
                                        <th width="10%" class="text-center">Diploma<br>Credit Hours</th>
                                        <th width="40%">Equivalent Degree Course<br><small class="text-muted">({{ $list->program_code }})</small></th>
                                        <th width="10%" class="text-center">Degree<br>Credit Hours</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($list->courseEquivalencies->sortBy('diploma_course_code') as $eq)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <strong>{{ $eq->diploma_course_code }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $eq->diploma_course_name }}</small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info">{{ $eq->diploma_credit_hour }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ $eq->degree_course_code }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $eq->degree_course_name }}</small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info">{{ $eq->degree_credit_hour }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="alert alert-warning mb-0">
                <i class="fas fa-exclamation-triangle me-1"></i>
                No published equivalency lists available for this program yet.
            </div>
        @endif
    </div>
</div>

<!-- Information Section -->
<div class="card shadow-sm">
    <div class="card-header bg-light">
        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Important Information</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6><i class="fas fa-check-circle text-success me-1"></i> Eligibility Criteria</h6>
                <ul class="text-muted small">
                    <li><strong>Match Percentage:</strong> Course equivalency must be >= 80%</li>
                    <li><strong>Grade Requirement:</strong> You must have achieved grade C or above in the diploma course</li>
                    <li>Both criteria must be met for credit exemption eligibility</li>
                </ul>
            </div>
            <div class="col-md-6">
                <h6><i class="fas fa-lightbulb text-warning me-1"></i> Tips</h6>
                <ul class="text-muted small">
                    <li>Check if your diploma courses are in the equivalency list before applying</li>
                    <li>Prepare your official transcript showing grades</li>
                    <li>Contact your academic advisor if you have questions about specific courses</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@else

<!-- No Program Registered -->
<div class="card">
    <div class="card-body text-center py-5">
        <i class="fas fa-exclamation-triangle fa-4x text-warning mb-3"></i>
        <h4>No Program Registered</h4>
        <p class="text-muted">You need to have a registered degree program to view course equivalency lists.</p>
        <p class="text-muted">Please contact your academic advisor or the HEA office to register your program.</p>
    </div>
</div>

@endif
@endsection
