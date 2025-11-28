@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <a href="{{ route('resource_person.dashboard') }}" class="btn btn-light mb-3"><< Back to Dashboard</a>
            
            <div class="card shadow-sm">
                <div class="card-header bg-light py-3">
                    <h5 class="mb-0">Resource Person Subject Review</h5>
                </div>
                <div class="card-body p-4">
                    {{-- Display submitted syllabus if available --}}
                    @if($subject->status === 'Syllabus Received')
                        <div class="alert alert-info">
                            A new syllabus has been submitted for this course. 
                            <a href="{{ route('resource_person.subject.view_syllabus', $subject) }}" target="_blank" class="alert-link">View Submitted Syllabus</a>.
                        </div>
                    @endif

                    {{-- OCR Processing Context --}}
                    @if($subject->extraction_method == 'ocr')
                    <div class="alert alert-success mb-4">
                        <i class="fas fa-robot"></i> <strong>OCR Extracted Course:</strong> This course was automatically extracted from the student's transcript using OCR technology.
                        @if($subject->ocr_confidence_score)
                            OCR Confidence: <strong>{{ number_format($subject->ocr_confidence_score, 1) }}%</strong>
                        @endif
                    </div>
                    @endif

                    {{-- Program Context --}}
                    @if($subject->exemptionApplication->current_program_code)
                    <div class="alert alert-info mb-4">
                        <i class="fas fa-graduation-cap"></i> <strong>Student Program:</strong> {{ $subject->exemptionApplication->current_program_code }} - {{ $subject->exemptionApplication->current_program }}
                    </div>
                    @endif

                    {{-- Previous OCR Analysis --}}
                    @if($subject->exemption_reason)
                    <div class="alert alert-warning mb-4">
                        <i class="fas fa-exclamation-triangle"></i> <strong>Previous OCR Analysis:</strong> {{ $subject->exemption_reason }}
                        @php
                            $notes = $subject->notes ? json_decode($subject->notes, true) : null;
                            $gradeMap = [
                                4.00 => 'A', 3.67 => 'A-', 3.33 => 'B+', 3.00 => 'B', 2.67 => 'B-',
                                2.33 => 'C+', 2.00 => 'C', 1.67 => 'C-', 1.33 => 'D+', 1.00 => 'D', 0.00 => 'F'
                            ];
                            $gradeLetter = $gradeMap[$subject->grade] ?? 'Unknown';
                        @endphp
                        @if($notes && isset($notes['equivalent_course']))
                            <br><small>Previous Match Found: <strong>{{ $notes['equivalent_course'] }}</strong> with {{ $notes['match_percentage'] ?? 0 }}% similarity</small>
                        @endif
                    </div>
                    @endif

                    {{-- Diploma Course Details --}}
                    <h6>Diploma Course Details</h6>
                    <table class="table table-bordered mb-4">
                        <tr><th>Course Code</th><td><strong>{{ $subject->course_code }}</strong></td></tr>
                        <tr><th>Course Name</th><td>{{ $subject->course_name }}</td></tr>
                        <tr><th>Credit Hours</th><td>{{ $subject->credit_hour }}</td></tr>
                        <tr><th>Student Grade</th><td>
                            @php
                                $gradeLetter = $gradeMap[$subject->grade] ?? 'Unknown';
                            @endphp
                            <span class="badge bg-primary">{{ $gradeLetter }}</span> ({{ $subject->grade }} GPA)
                        </td></tr>
                        <tr><th>Institution</th><td>{{ $subject->exemptionApplication->previous_institution }}</td></tr>
                        <tr><th>Extraction Method</th><td>
                            @if($subject->extraction_method == 'ocr')
                                <span class="badge bg-success">OCR Extracted</span>
                            @else
                                <span class="badge bg-secondary">Manual Entry</span>
                            @endif
                        </td></tr>
                    </table>

                    {{-- Main Form for Finding --}}
                    <form method="POST" action="{{ route('resource_person.subject.process', $subject) }}">
                        @csrf
                        <h5>Finding & Equivalency Creation</h5>
                        <p class="text-muted">Find the closest equivalent degree course, determine the match percentage, and then submit your final recommendation (Approve/Reject).</p>
                        
                        <div class="mb-3">
                            <label for="degree_course_code" class="form-label"><strong>1. Equivalent Degree Course</strong></label>
                            <select name="degree_course_code" id="degree_course_code" class="form-select" required>
                                <option value="" selected disabled>-- Select a Degree Course --</option>
                                @foreach($degreeCourses as $course)
                                    <option value="{{ $course->code }}">{{ $course->code }} - {{ $course->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="match_percentage" class="form-label"><strong>2. Match Percentage (%)</strong></label>
                            <input type="number" name="match_percentage" id="match_percentage" class="form-control" min="0" max="100" step="0.1" placeholder="e.g., 85.5" required>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label"><strong>3. Justification / Notes</strong></label>
                            <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Provide justification for your decision..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="decision" class="form-label"><strong>4. Final Recommendation</strong></label>
                            <select name="decision" id="decision" class="form-select" required>
                                <option value="Approved">Approve</option>
                                <option value="Rejected">Reject</option>
                            </select>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg">Submit Finding to Coordinator</button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-grid">
                                    <button type="button" class="btn btn-warning btn-lg" data-bs-toggle="modal" data-bs-target="#requestSyllabusModal">
                                        <i class="fas fa-file-alt me-2"></i>Request Complete Syllabus
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Request Syllabus Modal -->
<div class="modal fade" id="requestSyllabusModal" tabindex="-1" aria-labelledby="requestSyllabusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('resource_person.subject.request_syllabus', $subject) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="requestSyllabusModalLabel">Request Complete Syllabus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted">Request a complete syllabus for <strong>{{ $subject->course_code }} - {{ $subject->course_name }}</strong> from an external lecturer.</p>
                    
                    <div class="mb-3">
                        <label for="external_lecturer_email" class="form-label">External Lecturer Email <span class="text-danger">*</span></label>
                        <input type="email" name="external_lecturer_email" id="external_lecturer_email" 
                               class="form-control" required 
                               placeholder="Enter external lecturer's email address">
                        <div class="form-text">The external lecturer will receive an email with instructions to register and submit the syllabus.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="request_notes" class="form-label">Additional Notes (Optional)</label>
                        <textarea name="request_notes" id="request_notes" class="form-control" rows="3" 
                                  placeholder="Any specific requirements or notes for the external lecturer..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Send Syllabus Request</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
