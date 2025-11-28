@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <a href="{{ route('coordinator.dashboard') }}" class="btn btn-light mb-3"><< Back to Dashboard</a>
            <div class="card">
                <div class="card-header">Manage Course Equivalency - {{ $application->student->user->name }}</div>

                <div class="card-body">
                    <!-- Student Info -->
                    <h5>Student Information</h5>
                    <table class="table table-bordered mb-4">
                        <tr>
                            <th>Student Name</th>
                            <td>{{ $application->student->user->name }}</td>
                            <th>Student ID</th>
                            <td>{{ $application->matric_no }}</td>
                        </tr>
                    </table>

                    <!-- OCR Processing Context -->
                    @if($application->current_program_code)
                    <div class="alert alert-info mb-4">
                        <i class="fas fa-robot"></i> <strong>OCR Processing Context:</strong> 
                        These courses were extracted from the student's transcript for program <strong>{{ $application->current_program_code }}</strong>.
                        @if($forwardedSubjects->where('extraction_method', 'ocr')->count() > 0)
                            {{ $forwardedSubjects->where('extraction_method', 'ocr')->count() }} course(s) were automatically extracted using OCR technology.
                        @endif
                    </div>
                    @endif

                    <h5>Subjects Requiring Equivalency Review</h5>
                    <p class="text-muted">These courses were forwarded by the academic advisor and require course equivalency determination.</p>
                    
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>Diploma Course Details</th>
                                    <th>OCR Information</th>
                                    <th>Previous OCR Analysis</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($forwardedSubjects as $subject)
                                @php
                                    $notes = $subject->notes ? json_decode($subject->notes, true) : null;
                                    $gradeMap = [
                                        4.00 => 'A', 3.67 => 'A-', 3.33 => 'B+', 3.00 => 'B', 2.67 => 'B-',
                                        2.33 => 'C+', 2.00 => 'C', 1.67 => 'C-', 1.33 => 'D+', 1.00 => 'D', 0.00 => 'F'
                                    ];
                                    $gradeLetter = $gradeMap[$subject->grade] ?? 'Unknown';
                                @endphp
                                <tr>
                                    <td>
                                        <strong>{{ $subject->course_code }}</strong> - {{ $subject->course_name }}<br>
                                        <small class="text-muted">
                                            Credits: {{ $subject->credit_hour }} | 
                                            Grade: <span class="badge bg-primary">{{ $gradeLetter }}</span> ({{ $subject->grade }})
                                        </small>
                                    </td>
                                    <td>
                                        @if($subject->extraction_method == 'ocr')
                                            <span class="badge bg-success mb-1">OCR Extracted</span><br>
                                            @if($subject->ocr_confidence_score)
                                                <small class="text-muted">Confidence: {{ number_format($subject->ocr_confidence_score, 1) }}%</small>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">Manual Entry</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($subject->exemption_reason)
                                            <small class="text-muted">{{ $subject->exemption_reason }}</small><br>
                                        @endif
                                        @if($notes && isset($notes['equivalent_course']))
                                            <small class="text-info">Previous Match: {{ $notes['equivalent_course'] }} ({{ $notes['match_percentage'] ?? 0 }}%)</small>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{-- Action buttons --}}
                                        <div class="d-flex flex-column gap-1">
                                            <a href="{{ route('coordinator.equivalency.create', ['application' => $application, 'subject' => $subject]) }}" class="btn btn-sm btn-success">
                                                <i class="fas fa-check"></i> Approve (Create Equivalency)
                                            </a>
                                            
                                            <form method="POST" action="{{ route('coordinator.subject.forward', ['subject' => $subject]) }}" style="display: inline-block;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-warning w-100">
                                                    <i class="fas fa-arrow-right"></i> Forward to Resource Person
                                                </button>
                                            </form>
                                            
                                            <form method="POST" action="{{ route('coordinator.subject.reject', ['subject' => $subject]) }}" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to reject this course?');">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger w-100">
                                                    <i class="fas fa-times"></i> Reject
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">
                                        <div class="alert alert-success">
                                            <i class="fas fa-check-circle"></i> All subjects have been processed! No further action required.
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
