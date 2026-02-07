@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">Re-evaluation Details</h2>
                    <p class="text-muted mb-0">Review application subject for potential exemption</p>
                </div>
                <a href="{{ route('academic_advisor.reevaluations.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Back to Re-evaluations
                </a>
            </div>
        </div>
    </div>

    @php
        $subject = $reevaluation->applicationSubject;
        $application = $subject->exemptionApplication ?? null;
        $student = $application->student ?? null;
        $equivalency = $reevaluation->courseEquivalency;
    @endphp

    <div class="row">
        {{-- Main Content --}}
        <div class="col-lg-8">
            {{-- Course Mapping Card --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-arrow-left-right me-2"></i>New Course Equivalency Mapping</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5">
                            <h6 class="text-muted mb-2">Diploma Course</h6>
                            <div class="border rounded p-3 bg-light">
                                <h5 class="mb-1"><code>{{ $reevaluation->diploma_course_code }}</code></h5>
                                <p class="mb-0 text-muted">{{ $subject->course_name ?? 'N/A' }}</p>
                                @if($subject->credit_hour)
                                    <small class="text-muted">{{ $subject->credit_hour }} Credit Hours</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-2 d-flex align-items-center justify-content-center">
                            <div class="text-center">
                                <i class="bi bi-arrow-right fs-1 text-primary"></i>
                                <div class="mt-2">
                                    <span class="badge bg-success fs-6">{{ $reevaluation->match_percentage }}%</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <h6 class="text-muted mb-2">Degree Course</h6>
                            <div class="border rounded p-3 bg-light">
                                <h5 class="mb-1"><code>{{ $reevaluation->degree_course_code }}</code></h5>
                                <p class="mb-0 text-muted">{{ $equivalency->degree_course_name ?? 'N/A' }}</p>
                                @if($equivalency->degree_credit_hour)
                                    <small class="text-muted">{{ $equivalency->degree_credit_hour }} Credit Hours</small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Student Application Details --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-file-earmark-text me-2"></i>Application Details</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th width="200">Course Code:</th>
                            <td><code>{{ $subject->course_code }}</code></td>
                        </tr>
                        <tr>
                            <th>Course Name:</th>
                            <td>{{ $subject->course_name }}</td>
                        </tr>
                        <tr>
                            <th>Grade:</th>
                            <td>
                                <span class="badge bg-dark fs-6">{{ $subject->grade ?? 'N/A' }}</span>
                                @if($subject->grade)
                                    @php
                                        $gradeAcceptable = in_array(strtoupper($subject->grade), ['A+', 'A', 'A-', 'B+', 'B', 'B-', 'C+', 'C']);
                                    @endphp
                                    @if($gradeAcceptable)
                                        <span class="badge bg-success ms-2">Meets C Requirement</span>
                                    @else
                                        <span class="badge bg-danger ms-2">Below C</span>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Credit Hours:</th>
                            <td>{{ $subject->credit_hour ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Original Status:</th>
                            <td>
                                <span class="badge bg-secondary">{{ $reevaluation->original_subject_status }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Extraction Method:</th>
                            <td>
                                @if($subject->extraction_method === 'ocr')
                                    <span class="badge bg-info">OCR Extracted</span>
                                @else
                                    <span class="badge bg-secondary">Manual Entry</span>
                                @endif
                            </td>
                        </tr>
                        @if($subject->exemption_reason)
                            <tr>
                                <th>Previous Exemption Reason:</th>
                                <td class="text-muted">{{ $subject->exemption_reason }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>

            {{-- Decision Form --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-check2-square me-2"></i>Make Decision</h5>
                </div>
                <div class="card-body">
                    @if($reevaluation->isActionable())
                        <div class="row">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <form action="{{ route('academic_advisor.reevaluations.approve', $reevaluation) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Approval Notes (Optional)</label>
                                        <textarea name="notes" class="form-control" rows="3"
                                                  placeholder="Add any notes for this approval..."></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="bi bi-check-lg me-2"></i>Approve Exemption
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-6">
                                <form action="{{ route('academic_advisor.reevaluations.reject', $reevaluation) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Rejection Notes (Optional)</label>
                                        <textarea name="notes" class="form-control" rows="3"
                                                  placeholder="Explain why this exemption is rejected..."></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-danger w-100"
                                            onclick="return confirm('Are you sure you want to reject this re-evaluation?')">
                                        <i class="bi bi-x-lg me-2"></i>Reject (Keep Original Status)
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            <i class="bi bi-info-circle me-2"></i>
                            This re-evaluation has already been processed or has expired.
                            <strong>Status: {{ ucfirst($reevaluation->status) }}</strong>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            {{-- Student Info Card --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-person me-2"></i>Student Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <th>Name:</th>
                            <td>{{ $student->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Matric No:</th>
                            <td><code>{{ $application->matric_no ?? $student->matric_no ?? 'N/A' }}</code></td>
                        </tr>
                        <tr>
                            <th>IC No:</th>
                            <td>{{ $application->ic_no ?? $student->ic_no ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Program:</th>
                            <td>
                                <span class="badge bg-info">{{ $application->current_program_code ?? 'N/A' }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Previous Institution:</th>
                            <td>{{ $application->previous_institution ?? 'N/A' }}</td>
                        </tr>
                    </table>

                    @if($application)
                        <hr>
                        <a href="{{ route('academic_advisor.application.show', $application) }}"
                           class="btn btn-outline-primary btn-sm w-100">
                            <i class="bi bi-folder2-open me-1"></i> View Full Application
                        </a>
                    @endif
                </div>
            </div>

            {{-- Re-evaluation Status Card --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Re-evaluation Status</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <th>Status:</th>
                            <td>
                                @switch($reevaluation->status)
                                    @case('pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                        @break
                                    @case('approved')
                                        <span class="badge bg-success">Approved</span>
                                        @break
                                    @case('rejected')
                                        <span class="badge bg-danger">Rejected</span>
                                        @break
                                    @case('expired')
                                        <span class="badge bg-secondary">Expired</span>
                                        @break
                                @endswitch
                            </td>
                        </tr>
                        <tr>
                            <th>Created:</th>
                            <td>{{ $reevaluation->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                        @if($reevaluation->expires_at)
                            <tr>
                                <th>Expires:</th>
                                <td>
                                    @if($reevaluation->expires_at->isPast())
                                        <span class="text-danger">Expired</span>
                                    @elseif($reevaluation->expires_at->diffInDays(now()) <= 7)
                                        <span class="text-warning">{{ $reevaluation->expires_at->diffForHumans() }}</span>
                                    @else
                                        {{ $reevaluation->expires_at->format('d M Y') }}
                                    @endif
                                </td>
                            </tr>
                        @endif
                        @if($reevaluation->decided_at)
                            <tr>
                                <th>Decided:</th>
                                <td>{{ $reevaluation->decided_at->format('d M Y, H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Decided By:</th>
                                <td>{{ $reevaluation->decidedBy->name ?? 'System' }}</td>
                            </tr>
                        @endif
                        @if($reevaluation->decision_notes)
                            <tr>
                                <th>Notes:</th>
                                <td>{{ $reevaluation->decision_notes }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>

            {{-- Equivalency Info Card --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-link-45deg me-2"></i>Equivalency Details</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <th>Match %:</th>
                            <td>
                                <span class="badge bg-success fs-6">{{ $reevaluation->match_percentage }}%</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Program:</th>
                            <td>{{ $equivalency->program_code ?? 'N/A' }}</td>
                        </tr>
                        @if($equivalency->notes)
                            <tr>
                                <th>Notes:</th>
                                <td><small class="text-muted">{{ $equivalency->notes }}</small></td>
                            </tr>
                        @endif
                        <tr>
                            <th>Created:</th>
                            <td>{{ $equivalency->created_at ? $equivalency->created_at->format('d M Y') : 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
