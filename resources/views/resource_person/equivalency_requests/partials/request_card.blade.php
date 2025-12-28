<div class="card shadow-sm mb-3">
    <div class="card-header @php
        echo match($status) {
            'pending' => 'bg-warning text-dark',
            'syllabus_received' => 'bg-info text-white',
            'approved' => 'bg-success text-white',
            'rejected' => 'bg-danger text-white',
            default => 'bg-secondary text-white'
        };
    @endphp">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="mb-0">
                <i class="fas fa-file-signature me-2"></i>
                Request ID: {{ $request->id }}
            </h6>
            <span class="badge @php
                echo match($status) {
                    'pending' => 'bg-dark',
                    'syllabus_received' => 'bg-light text-info',
                    'approved' => 'bg-light text-success',
                    'rejected' => 'bg-light text-danger',
                    default => 'bg-secondary'
                };
            @endphp">
                @if($status === 'syllabus_received')
                    <i class="fas fa-file-alt me-1"></i>READY FOR REVIEW
                @else
                    {{ strtoupper(str_replace('_', ' ', $status)) }}
                @endif
            </span>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- Student Information -->
            <div class="col-md-4 mb-3">
                <h6 class="text-muted mb-2"><i class="fas fa-user me-1"></i>Student Information</h6>
                <p class="mb-1"><strong>Name:</strong> {{ $request->student->user->name }}</p>
                <p class="mb-1"><strong>Matric No:</strong> {{ $request->student->matric_no ?? 'N/A' }}</p>
                <p class="mb-1"><strong>Program:</strong> <span class="badge bg-primary">{{ $request->current_program_code }}</span></p>
                <p class="mb-0"><strong>Submitted:</strong> {{ $request->created_at->format('d M Y, h:i A') }}</p>
            </div>

            <!-- Diploma Course Information -->
            <div class="col-md-4 mb-3">
                <h6 class="text-muted mb-2"><i class="fas fa-graduation-cap me-1"></i>Diploma Course</h6>
                <p class="mb-1"><strong>Code:</strong> {{ $request->diploma_course_code }}</p>
                <p class="mb-1"><strong>Name:</strong> {{ $request->diploma_course_name }}</p>
                <p class="mb-1"><strong>Institution:</strong> {{ $request->diploma_institution }}</p>
                <p class="mb-0"><strong>Credit Hours:</strong> {{ $request->diploma_credit_hours }}</p>
            </div>

            <!-- Suggested/Approved Degree Course -->
            <div class="col-md-4 mb-3">
                @if($status === 'approved')
                    <h6 class="text-muted mb-2"><i class="fas fa-check-circle me-1"></i>Approved Equivalency</h6>
                    <p class="mb-1"><strong>Degree Course:</strong> {{ $request->approved_degree_course_code }}</p>
                    <p class="mb-1"><strong>Course Name:</strong> {{ $request->approved_degree_course_name }}</p>
                    <p class="mb-1"><strong>Match:</strong> {{ $request->match_percentage }}%</p>
                    <p class="mb-1"><strong>Reviewed By:</strong> {{ $request->reviewer->user->name ?? 'N/A' }}</p>
                    <p class="mb-0"><strong>Reviewed:</strong> {{ $request->reviewed_at ? $request->reviewed_at->format('d M Y') : 'N/A' }}</p>
                @elseif($status === 'rejected')
                    <h6 class="text-muted mb-2"><i class="fas fa-times-circle me-1"></i>Rejection Details</h6>
                    <p class="mb-1"><strong>Reviewed By:</strong> {{ $request->reviewer->user->name ?? 'N/A' }}</p>
                    <p class="mb-1"><strong>Reviewed:</strong> {{ $request->reviewed_at ? $request->reviewed_at->format('d M Y') : 'N/A' }}</p>
                    @if($request->reviewer_notes)
                        <p class="mb-0"><strong>Notes:</strong> {{ Str::limit($request->reviewer_notes, 100) }}</p>
                    @endif
                @else
                    <h6 class="text-muted mb-2"><i class="fas fa-lightbulb me-1"></i>Suggested Degree Course</h6>
                    <p class="mb-1"><strong>Code:</strong> {{ $request->suggested_degree_course_code }}</p>
                    <p class="mb-0"><strong>Name:</strong> {{ $request->suggested_degree_course_name }}</p>
                @endif
            </div>
        </div>

        <!-- Justification -->
        <div class="row">
            <div class="col-12">
                <h6 class="text-muted mb-2"><i class="fas fa-file-alt me-1"></i>Justification</h6>
                <p class="mb-0 text-justify" style="white-space: pre-wrap;">{{ Str::limit($request->justification, 200) }}</p>
            </div>
        </div>

        <!-- Verification Status -->
        <div class="row mt-3">
            <div class="col-12">
                <h6 class="text-muted mb-2"><i class="fas fa-user-tie me-1"></i>Lecturer Contact</h6>
                <p class="mb-1"><strong>Name:</strong> {{ $request->external_lecturer_name }}</p>
                <p class="mb-2"><strong>Email:</strong> {{ $request->external_lecturer_email }}</p>

                @if($request->syllabus_received_at)
                    <span class="badge bg-success">
                        <i class="fas fa-check-circle me-1"></i>Syllabus Received
                    </span>
                @elseif($request->syllabus_request_sent_at)
                    <span class="badge bg-warning text-dark">
                        <i class="fas fa-clock me-1"></i>Awaiting Syllabus
                    </span>
                @else
                    <span class="badge bg-secondary">
                        <i class="fas fa-hourglass-half me-1"></i>Not Requested
                    </span>
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row mt-3">
            <div class="col-12 text-end">
                @if(in_array($status, ['pending', 'syllabus_received']))
                    <a href="{{ route('resource_person.equivalency_requests.review', $request) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-eye me-1"></i>Review Request
                    </a>
                @else
                    <a href="{{ route('resource_person.equivalency_requests.review', $request) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-eye me-1"></i>View Details
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
