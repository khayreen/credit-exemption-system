@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-0">Preview Equivalency List</h2>
        <p class="text-muted mb-0 mt-2">Review before submitting for HEA endorsement</p>
    </div>
    <div>
        <a href="{{ route('resource_person.equivalency_lists.edit', $list) }}" class="btn btn-outline-secondary me-2">
            <i class="fas fa-arrow-left"></i> Back to Edit
        </a>
        <button type="button" class="btn btn-success" onclick="window.print()">
            <i class="fas fa-print"></i> Print
        </button>
    </div>
</div>

<!-- Printable Content -->
<div class="card shadow-sm" id="printable-content">
    <div class="card-header {{ $list->isInternal() ? 'bg-primary' : 'bg-success' }} text-white">
        <div class="text-center py-3">
            <h4 class="mb-1">COURSE EQUIVALENCY LIST</h4>
            <h5 class="mb-0">{{ $list->program_code }} - {{ $list->program_name }}</h5>
        </div>
    </div>

    <div class="card-body">
        <!-- Header Info -->
        <div class="row mb-4">
            <div class="col-md-6">
                <table class="table table-borderless table-sm">
                    <tr>
                        <td width="150"><strong>Category:</strong></td>
                        <td>
                            {{ $list->category_icon }}
                            {{ $list->isInternal() ? 'Internal (CS110 - UiTM Diploma)' : 'External Institution' }}
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Source:</strong></td>
                        <td>{{ $list->source_display }}</td>
                    </tr>
                    <tr>
                        <td><strong>Target Program:</strong></td>
                        <td>{{ $list->program_code }} - {{ $list->program_name }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless table-sm">
                    <tr>
                        <td width="150"><strong>Semester:</strong></td>
                        <td>{{ $list->semester }}</td>
                    </tr>
                    <tr>
                        <td><strong>Academic Year:</strong></td>
                        <td>{{ $list->academic_year }}</td>
                    </tr>
                    <tr>
                        <td><strong>Prepared By:</strong></td>
                        <td>{{ $list->creator->name ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Statistics Summary -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-light">
                    <div class="card-body text-center py-3">
                        <h3 class="mb-0 text-primary">{{ $list->total_equivalencies }}</h3>
                        <small class="text-muted">Total Mappings</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-light">
                    <div class="card-body text-center py-3">
                        <h3 class="mb-0 text-success">{{ $list->eligible_count }}</h3>
                        <small class="text-muted">Eligible for Exemption (&ge;80%)</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-light">
                    <div class="card-body text-center py-3">
                        <h3 class="mb-0 text-danger">{{ $list->not_eligible_count }}</h3>
                        <small class="text-muted">Not Eligible (&lt;80%)</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Course Mappings Table -->
        <h5 class="mb-3">Course Equivalency Mappings</h5>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" width="5%">#</th>
                        <th colspan="3" class="text-center bg-info text-white">
                            {{ $list->isInternal() ? 'CS110 Diploma Course' : $list->source_institution . ' Diploma Course' }}
                        </th>
                        <th colspan="3" class="text-center bg-primary text-white">
                            {{ $list->program_code }} Degree Course
                        </th>
                        <th class="text-center" width="8%">Match %</th>
                        <th class="text-center" width="8%">Eligible</th>
                    </tr>
                    <tr class="table-secondary">
                        <th></th>
                        <th>Code</th>
                        <th>Course Name</th>
                        <th width="6%">Cr</th>
                        <th>Code</th>
                        <th>Course Name</th>
                        <th width="6%">Cr</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($list->courseEquivalencies->sortBy('diploma_course_code') as $index => $mapping)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td><strong>{{ $mapping->diploma_course_code }}</strong></td>
                        <td>{{ $mapping->diploma_course_name }}</td>
                        <td class="text-center">{{ $mapping->diploma_credit_hour }}</td>
                        <td><strong>{{ $mapping->degree_course_code }}</strong></td>
                        <td>{{ $mapping->degree_course_name }}</td>
                        <td class="text-center">{{ $mapping->degree_credit_hour }}</td>
                        <td class="text-center">
                            <span class="badge {{ $mapping->match_percentage >= 80 ? 'bg-success' : 'bg-danger' }}">
                                {{ $mapping->match_percentage }}%
                            </span>
                        </td>
                        <td class="text-center">
                            @if($mapping->is_eligible)
                                <i class="fas fa-check-circle text-success"></i> Yes
                            @else
                                <i class="fas fa-times-circle text-danger"></i> No
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">No course mappings available</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Legend -->
        <div class="mt-4 p-3 bg-light rounded">
            <h6>Legend & Notes:</h6>
            <ul class="mb-0 small">
                <li><strong>Match %:</strong> Percentage of curriculum similarity between diploma and degree courses</li>
                <li><strong>Eligible:</strong> Courses with match &ge;80% qualify for credit exemption (subject to grade requirements)</li>
                <li><strong>Cr:</strong> Credit hours</li>
            </ul>
        </div>

        <!-- Footer -->
        <div class="mt-4 pt-3 border-top">
            <div class="row">
                <div class="col-md-6">
                    <p class="small text-muted mb-0">
                        <strong>Document Generated:</strong> {{ now()->format('d M Y, H:i') }}
                    </p>
                </div>
                <div class="col-md-6 text-end">
                    <p class="small text-muted mb-0">
                        <strong>Status:</strong>
                        @include('resource_person.equivalency_lists.partials.status_badge', ['list' => $list])
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .btn, nav, .d-flex.justify-content-between.align-items-center.mb-4 > div:last-child {
        display: none !important;
    }
    .card {
        border: 1px solid #000 !important;
    }
    .card-header {
        background-color: #f8f9fa !important;
        color: #000 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    .badge {
        border: 1px solid #000;
    }
}
</style>
@endsection
