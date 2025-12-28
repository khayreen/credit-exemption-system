@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">My Application Status</h2>
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($applications->isEmpty())
                <div class="card text-center">
                    <div class="card-body py-5">
                        <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                        <h5>No Applications Yet</h5>
                        <p class="text-muted">You haven't submitted any credit exemption applications.</p>
                        <a href="{{ route('student.application.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Apply Now
                        </a>
                    </div>
                </div>
            @else
                @foreach($applications as $app)
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h5 class="mb-0">Application #{{ $loop->index + 1 }}</h5>
                                    <small class="text-muted">Submitted: {{ $app->created_at->format('d M Y, h:i A') }}</small>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    @if($app->status === 'Reviewed by Academic Advisor')
                                        <span class="btn btn-sm bg-success text-white ms-2" style="font-size: 0.875rem; cursor: default;">
                                            <i class="fas fa-check-circle"></i> Ready for Course Registration
                                        </span>
                                    @else
                                        <span class="btn btn-sm bg-info text-dark ms-2" style="font-size: 0.875rem; cursor: default;">{{ $app->status }}</span>
                                    @endif
                                    @if($app->transcript)
                                        <a href="{{ route('student.application.transcript', $app) }}" target="_blank" class="btn btn-sm btn-secondary ms-2" style="font-size: 0.875rem;">
                                            <i class="fas fa-file-pdf"></i> View Transcript
                                        </a>
                                    @endif
                                    @if($app->status === 'Reviewed by Academic Advisor')
                                        <a href="{{ route('student.application.validation', $app) }}" target="_blank" class="btn btn-sm btn-primary ms-2" style="font-size: 0.875rem;">
                                            <i class="fas fa-file-alt"></i> View Course Validation
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            {{-- Application Details --}}
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h6>Student Information</h6>
                                    <p class="mb-1"><strong>Name:</strong> {{ $app->student_name }}</p>
                                    <p class="mb-1"><strong>Matric No:</strong> {{ $app->matric_no }}</p>
                                    <p class="mb-1"><strong>Program:</strong> {{ $app->current_program_code ? $app->current_program_code . ' - ' : '' }}{{ $app->current_program }}</p>
                                    <p class="mb-0"><strong>Campus:</strong> {{ $app->current_campus }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6>Previous Education</h6>
                                    <p class="mb-1"><strong>Institution:</strong> {{ $app->previous_institution }}</p>
                                    <p class="mb-1"><strong>Program:</strong> {{ $app->previous_program }}</p>
                                    @if($app->status === 'Reviewed by Academic Advisor' && $app->reviewer)
                                        <p class="mb-0"><strong>Reviewed By:</strong> {{ $app->reviewer->name }}</p>
                                    @endif
                                </div>
                            </div>

                            {{-- OCR Results Section --}}
                            @if($app->applicationSubjects->isNotEmpty())
                                <hr>
                                <h6>Course Analysis Results</h6>
                                
                                {{-- Application Status Message --}}
                                @if($app->status === 'Reviewed by Academic Advisor')
                                    <div class="alert alert-success mb-3">
                                        <i class="fas fa-graduation-cap"></i>
                                        <strong>Review Complete!</strong> Your academic advisor has completed the review process.
                                        You can now proceed to the <strong>Student e-Course Registration System</strong> to register your approved exempted courses.
                                    </div>
                                @else
                                    <div class="alert alert-warning mb-3">
                                        <i class="fas fa-clock"></i>
                                        <strong>Under Review:</strong> Your application is currently under academic advisor review.
                                        OCR-exempted courses are pending approval.
                                    </div>
                                @endif
                                
                                @if($app->current_program_code)
                                    <div class="alert alert-info mb-3">
                                        <i class="fas fa-info-circle"></i> 
                                        <strong>Program-Specific Analysis:</strong> Courses analyzed for {{ $app->current_program_code }} program equivalencies.
                                        Only courses meeting ALL criteria (found + grade ≥ C + match > 80%) qualify for exemption.
                                    </div>
                                @endif

                                {{-- Summary Statistics --}}
                                @php
                                    $totalCourses = $app->applicationSubjects->count();

                                    // Get exempted/approved diploma courses
                                    $exemptedDiplomaCourses = $app->applicationSubjects->filter(function($subject) {
                                        return $subject->status === 'exempted' || $subject->status === 'Approved';
                                    });

                                    // Count UNIQUE DEGREE COURSES (not diploma courses)
                                    $academicDegreeCourses = [];
                                    $coCurriculumSlots = 0;

                                    foreach ($exemptedDiplomaCourses as $subject) {
                                        $notes = $subject->notes ? json_decode($subject->notes, true) : null;
                                        $equivalentCourse = $notes['equivalent_course'] ?? null;

                                        if ($equivalentCourse === 'HXXXXX') {
                                            // Each co-curriculum course counts as separate degree slot
                                            $coCurriculumSlots++;
                                        } elseif ($equivalentCourse && !in_array($equivalentCourse, $academicDegreeCourses)) {
                                            // Count unique academic degree courses only
                                            $academicDegreeCourses[] = $equivalentCourse;
                                        }
                                    }

                                    // Total exempted DEGREE COURSES = unique academic + co-curriculum slots
                                    $exemptedCourses = count($academicDegreeCourses) + $coCurriculumSlots;

                                    // Count courses not available for exemption
                                    $notAvailableForExemption = $totalCourses - $exemptedDiplomaCourses->count();
                                @endphp

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="card border-success shadow-sm" style="min-height: 120px;">
                                            <div class="card-body py-3 d-flex flex-column justify-content-center text-center">
                                                <h2 class="mb-1 text-success fw-bold">{{ $exemptedCourses }}</h2>
                                                <h6 class="mb-1 text-success">Exempted</h6>
                                                <small class="text-muted">
                                                    @if($app->status === 'Reviewed by Academic Advisor')
                                                        Degree courses approved for exemption
                                                    @else
                                                        Degree courses eligible for exemption
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card border-warning shadow-sm" style="min-height: 120px;">
                                            <div class="card-body py-3 d-flex flex-column justify-content-center text-center">
                                                <h2 class="mb-1 text-warning fw-bold">{{ $notAvailableForExemption }}</h2>
                                                <h6 class="mb-1 text-warning">Not Available for Exemption</h6>
                                                <small class="text-muted">Diploma courses not eligible for credit exemption</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-light">
                                    <h6 class="mb-0">
                                        <i class="fas fa-chart-pie text-primary"></i>
                                        Summary: <strong>{{ $exemptedCourses }} degree courses</strong> can be exempted from <strong>{{ $exemptedDiplomaCourses->count() }} diploma courses</strong>
                                        ({{ count($academicDegreeCourses) }} academic + {{ $coCurriculumSlots }} co-curriculum)
                                    </h6>
                                </div>

                                {{-- Detailed Course List --}}
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Course Code</th>
                                                <th>Course Name</th>
                                                <th class="text-center">Grade</th>
                                                <th>Equivalent Course</th>
                                                <th>OCR Analysis</th>
                                                <th>Workflow Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                // Define GPA conversion function once before the loop
                                                $convertGPAToLetterGrade = function($gpa) {
                                                    $gpa = floatval($gpa);
                                                    if ($gpa >= 4.00) return 'A';
                                                    if ($gpa >= 3.67) return 'A-';
                                                    if ($gpa >= 3.33) return 'B+';
                                                    if ($gpa >= 3.00) return 'B';
                                                    if ($gpa >= 2.67) return 'B-';
                                                    if ($gpa >= 2.33) return 'C+';
                                                    if ($gpa >= 2.00) return 'C';
                                                    if ($gpa >= 1.67) return 'C-';
                                                    if ($gpa >= 1.33) return 'D+';
                                                    if ($gpa >= 1.00) return 'D';
                                                    return 'F';
                                                };
                                            @endphp
                                            @foreach($app->applicationSubjects->sortBy('course_code') as $subject)
                                            <tr>
                                                <td><code>{{ $subject->course_code }}</code></td>
                                                <td>{{ $subject->course_name }}</td>
                                                <td class="text-center">
                                                    @php
                                                        // Check if course code contains combined courses
                                                        $courseCode = $subject->course_code;
                                                        $hasMultipleCourses = strpos($courseCode, '&') !== false || strpos($courseCode, '+') !== false;

                                                        $grades = [];
                                                        if ($hasMultipleCourses) {
                                                            // Extract individual course codes
                                                            $individualCodes = preg_split('/[\s&+]+/', $courseCode);
                                                            $individualCodes = array_filter(array_map('trim', $individualCodes));

                                                            // Look up each course's grade
                                                            foreach ($individualCodes as $code) {
                                                                $courseSubject = $app->applicationSubjects->firstWhere('course_code', $code);
                                                                if ($courseSubject && $courseSubject->grade !== null) {
                                                                    $grades[] = $convertGPAToLetterGrade($courseSubject->grade);
                                                                }
                                                            }
                                                        } else {
                                                            // Single course
                                                            if ($subject->grade !== null) {
                                                                $grades[] = $convertGPAToLetterGrade($subject->grade);
                                                            }
                                                        }

                                                        $displayGrade = !empty($grades) ? implode(' & ', $grades) : 'N/A';
                                                    @endphp
                                                    <span class="badge
                                                        @if(in_array($subject->status, ['exempted'])) bg-success
                                                        @elseif($subject->status === 'not_eligible_grade') bg-danger
                                                        @else bg-secondary
                                                        @endif">
                                                        {{ $displayGrade }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @php
                                                        $notes = $subject->notes ? json_decode($subject->notes, true) : null;
                                                        $equivalentCourse = $notes['equivalent_course'] ?? null;
                                                        
                                                        // If notes are empty, try to find equivalent course directly from database
                                                        if (!$equivalentCourse) {
                                                            $equivalency = \App\Models\CourseEquivalency::where('diploma_course_code', $subject->course_code)
                                                                                                      ->where('program_code', $subject->exemptionApplication->current_program_code ?? 'CS251')
                                                                                                      ->first();
                                                            $equivalentCourse = $equivalency ? $equivalency->degree_course_code : null;
                                                        }
                                                    @endphp
                                                    @if($equivalentCourse)
                                                        <code>{{ $equivalentCourse }}</code>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @php
                                                        // Show OCR analysis status
                                                        $ocrStatus = in_array($subject->status, ['Approved', 'Rejected', 'Forward to Coordinator']) 
                                                            ? (str_contains($subject->exemption_reason ?? '', 'All criteria met') ? 'exempted' : 'not_eligible') 
                                                            : $subject->status;
                                                    @endphp
                                                    @switch($ocrStatus)
                                                        @case('exempted')
                                                            <span class="badge bg-success">
                                                                <i class="fas fa-check"></i> EXEMPTED
                                                            </span>
                                                            @break
                                                        @case('not_found')
                                                            <span class="badge bg-warning text-dark">
                                                                <i class="fas fa-search"></i> NOT FOUND
                                                            </span>
                                                            @break
                                                        @case('not_eligible_grade')
                                                            <span class="badge bg-danger">
                                                                <i class="fas fa-times"></i> GRADE TOO LOW
                                                            </span>
                                                            @break
                                                        @case('not_eligible_match')
                                                            <span class="badge bg-secondary">
                                                                <i class="fas fa-percentage"></i> LOW MATCH
                                                            </span>
                                                            @break
                                                        @default
                                                            <span class="badge bg-secondary">
                                                                <i class="fas fa-ban"></i> NOT ELIGIBLE
                                                            </span>
                                                    @endswitch
                                                </td>
                                                <td>
                                                    @switch($subject->status)
                                                        @case('exempted')
                                                            <span class="badge bg-warning">
                                                                <i class="fas fa-clock"></i> PENDING APPROVAL
                                                            </span>
                                                            @break
                                                        @case('Approved')
                                                            <span class="badge bg-success">
                                                                <i class="fas fa-check-circle"></i> APPROVED
                                                            </span>
                                                            @break
                                                        @case('Rejected')
                                                            <span class="badge bg-danger">
                                                                <i class="fas fa-times-circle"></i> REJECTED
                                                            </span>
                                                            @break
                                                        @case('Forward to Coordinator')
                                                            <span class="badge bg-info">
                                                                <i class="fas fa-arrow-right"></i> UNDER COORDINATOR REVIEW
                                                            </span>
                                                            @break
                                                        @default
                                                            <span class="badge bg-secondary">
                                                                <i class="fas fa-hourglass-half"></i> PENDING REVIEW
                                                            </span>
                                                    @endswitch
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                {{-- Legend --}}
                                <div class="mt-3">
                                    <h6>Status Legend:</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="small text-muted">OCR Analysis Results:</h6>
                                            <ul class="list-unstyled small">
                                                <li><span class="badge bg-success me-2"><i class="fas fa-check"></i> EXEMPTED</span> All criteria met: Course found + Grade ≥ C + Match > 80%</li>
                                                <li><span class="badge bg-warning text-dark me-2"><i class="fas fa-search"></i> NOT FOUND</span> Course not in {{ $app->current_program_code ?: 'program' }} equivalency database</li>
                                                <li><span class="badge bg-danger me-2"><i class="fas fa-times"></i> GRADE TOO LOW</span> Grade below C requirement</li>
                                                <li><span class="badge bg-secondary me-2"><i class="fas fa-percentage"></i> LOW MATCH</span> Course match ≤ 80%</li>
                                                <li><span class="badge bg-secondary me-2"><i class="fas fa-ban"></i> NOT ELIGIBLE</span> Course does not meet exemption criteria</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="small text-muted">Workflow Status:</h6>
                                            <ul class="list-unstyled small">
                                                <li><span class="badge bg-warning me-2"><i class="fas fa-clock"></i> PENDING APPROVAL</span> Awaiting academic advisor decision</li>
                                                <li><span class="badge bg-success me-2"><i class="fas fa-check-circle"></i> APPROVED</span> Ready for course registration</li>
                                                <li><span class="badge bg-danger me-2"><i class="fas fa-times-circle"></i> REJECTED</span> Not approved for exemption</li>
                                                <li><span class="badge bg-info me-2"><i class="fas fa-arrow-right"></i> UNDER COORDINATOR REVIEW</span> Under additional review</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                            @else
                                <hr>
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i> 
                                    <strong>No Course Data Available</strong><br>
                                    OCR processing may have failed or no courses were detected in your transcript.
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach

                <div class="text-center mt-4">
                    <a href="{{ route('student.application.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Submit Another Application
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.badge {
    font-size: 0.8em;
}
.table td {
    vertical-align: middle;
}
code {
    font-size: 0.9em;
}
</style>
@endpush