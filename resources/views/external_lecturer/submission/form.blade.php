<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UiTM Credit Exemption - Syllabus Submission</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .submission-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
        }
        .card-custom {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .form-label {
            font-weight: 600;
            color: #495057;
        }
        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
            padding: 12px 30px;
            font-weight: 600;
        }
    </style>
</head>
<body class="bg-light">
    <div class="submission-header">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center">
                    <h1><i class="fas fa-university me-3"></i>UiTM Credit Exemption System</h1>
                    <p class="lead mb-0">External Lecturer Syllabus Submission</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                @if(session('error'))
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                    </div>
                @endif

                <div class="card card-custom mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Submission Request Details</h5>
                    </div>
                    <div class="card-body">
                        @if($requestType === 'application_subject')
                            {{-- ApplicationSubject Request --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Requested Course:</strong> {{ $applicationSubject->course_code }} - {{ $applicationSubject->course_name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Student Program:</strong> {{ $exemptionApplication->current_program_code ?? 'N/A' }}</p>
                                    <p><strong>Request Date:</strong> {{ $request->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                        @else
                            {{-- CourseEquivalencyRequest --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Requested Course:</strong> {{ $equivalencyRequest->diploma_course_code }} - {{ $equivalencyRequest->diploma_course_name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Student:</strong> {{ $equivalencyRequest->student->user->name }}</p>
                                    <p><strong>Current Program:</strong> {{ $equivalencyRequest->current_program_code }}</p>
                                    <p><strong>Request Date:</strong> {{ $request->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                            <div class="alert alert-warning">
                                <strong><i class="fas fa-exclamation-triangle me-2"></i>Important:</strong>
                                This is an official course equivalency verification request. Please submit the complete and authentic course syllabus for <strong>{{ $equivalencyRequest->diploma_course_code }}</strong> from your institution.
                            </div>
                        @endif

                        @if($request->request_notes)
                            <div class="alert alert-info">
                                <strong>Additional Notes from Resource Person:</strong><br>
                                {{ $request->request_notes }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card card-custom">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-file-upload me-2"></i>Complete Syllabus Submission Form</h5>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('external.lecturer.submission.submit', $request->access_token) }}" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="external_lecturer_name" class="form-label">Your Full Name <span class="text-danger">*</span></label>
                                        <input type="text" name="external_lecturer_name" id="external_lecturer_name" 
                                               class="form-control @error('external_lecturer_name') is-invalid @enderror" 
                                               value="{{ old('external_lecturer_name') }}" required>
                                        @error('external_lecturer_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="institution_name" class="form-label">Institution Name <span class="text-danger">*</span></label>
                                        <input type="text" name="institution_name" id="institution_name" 
                                               class="form-control @error('institution_name') is-invalid @enderror" 
                                               value="{{ old('institution_name') }}" required>
                                        @error('institution_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="course_code" class="form-label">Course Code <span class="text-danger">*</span></label>
                                        <input type="text" name="course_code" id="course_code"
                                               class="form-control @error('course_code') is-invalid @enderror"
                                               value="{{ old('course_code', $requestType === 'application_subject' ? $applicationSubject->course_code : $equivalencyRequest->diploma_course_code) }}" required>
                                        @error('course_code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="course_name" class="form-label">Course Name <span class="text-danger">*</span></label>
                                        <input type="text" name="course_name" id="course_name"
                                               class="form-control @error('course_name') is-invalid @enderror"
                                               value="{{ old('course_name', $requestType === 'application_subject' ? $applicationSubject->course_name : $equivalencyRequest->diploma_course_name) }}" required>
                                        @error('course_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label for="credit_hours" class="form-label">Credit Hours <span class="text-danger">*</span></label>
                                        <select name="credit_hours" id="credit_hours"
                                                class="form-select @error('credit_hours') is-invalid @enderror" required>
                                            <option value="">Select</option>
                                            @for ($i = 1; $i <= 10; $i += 0.5)
                                                <option value="{{ number_format($i, 2, '.', '') }}" {{ old('credit_hours') == number_format($i, 2, '.', '') ? 'selected' : '' }}>
                                                    {{ number_format($i, 2) }}
                                                </option>
                                            @endfor
                                        </select>
                                        @error('credit_hours')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="justification_notes" class="form-label">Justification / Notes <span class="text-danger">*</span></label>
                                <textarea name="justification_notes" id="justification_notes" 
                                          class="form-control @error('justification_notes') is-invalid @enderror" 
                                          rows="4" required>{{ old('justification_notes') }}</textarea>
                                <div class="form-text">Please provide detailed notes explaining the course content, learning outcomes, and any relevant information for credit exemption evaluation.</div>
                                @error('justification_notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="syllabus_file" class="form-label">Complete Course Syllabus <span class="text-danger">*</span></label>
                                <input type="file" name="syllabus_file" id="syllabus_file" 
                                       class="form-control @error('syllabus_file') is-invalid @enderror" 
                                       accept=".pdf" required>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Please upload a complete course syllabus in PDF format (max 5MB). The syllabus should include course objectives, topics covered, assessment methods, and learning outcomes.
                                </div>
                                @error('syllabus_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-submit btn-lg text-white">
                                    <i class="fas fa-paper-plane me-2"></i>Submit Complete Syllabus
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card card-custom mt-4">
                    <div class="card-body text-center py-3">
                        <small class="text-muted">
                            <i class="fas fa-lock me-1"></i>
                            This is a secure submission. Your data is protected and will only be used for credit exemption evaluation purposes.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>