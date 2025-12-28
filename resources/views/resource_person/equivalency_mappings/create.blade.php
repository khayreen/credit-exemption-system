@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white py-3">
                    <h4 class="mb-0">
                        <i class="fas fa-paper-plane me-2"></i>Forward Course Mapping to Program Coordinator
                    </h4>
                </div>
                <div class="card-body p-4">
                    <!-- Explanation -->
                    <div class="alert alert-info">
                        <h6 class="alert-heading">
                            <i class="fas fa-info-circle me-2"></i>About Forwarding Mappings
                        </h6>
                        <p class="mb-0">
                            After evaluating a course syllabus, you can create a course equivalency mapping and forward it to the Program Coordinator.
                            The Program Coordinator will review your mapping and decide whether to add it to the official equivalency list.
                        </p>
                    </div>

                    <form action="{{ route('resource_person.equivalency_mappings.store') }}" method="POST">
                        @csrf

                        @if($studentRequest)
                            <!-- Student Request Context -->
                            <div class="alert alert-primary">
                                <h6 class="alert-heading">
                                    <i class="fas fa-user-graduate me-2"></i>Student Equivalency Request
                                </h6>
                                <p class="mb-2">
                                    <strong>Student:</strong> {{ $studentRequest->student->user->name ?? 'N/A' }}<br>
                                    <strong>Diploma Course:</strong> {{ $studentRequest->diploma_course_code }} - {{ $studentRequest->diploma_course_name }}<br>
                                    <strong>Suggested Degree Course:</strong> {{ $studentRequest->suggested_degree_course_code }}
                                </p>
                                <small class="text-muted">This mapping is in response to a student's equivalency request.</small>
                            </div>
                            <input type="hidden" name="course_equivalency_request_id" value="{{ $studentRequest->id }}">
                        @endif

                        <!-- Program Selection -->
                        <div class="mb-4">
                            <label for="program_code" class="form-label fw-bold">
                                Target Degree Program <span class="text-danger">*</span>
                            </label>
                            <select name="program_code" id="program_code" class="form-select @error('program_code') is-invalid @enderror" required>
                                <option value="">-- Select Degree Program --</option>
                                @foreach($programs as $code => $name)
                                    <option value="{{ $code }}"
                                            {{ old('program_code', $studentRequest->current_program_code ?? '') == $code ? 'selected' : '' }}>
                                        {{ $code }} - {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Select the degree program this equivalency applies to</small>
                            @error('program_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <!-- Diploma Course Section -->
                        <h5 class="text-primary mb-3">
                            <i class="fas fa-graduation-cap me-2"></i>Diploma Course Information
                        </h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="diploma_course_code" class="form-label fw-bold">Course Code</label>
                                <input type="text"
                                       name="diploma_course_code"
                                       id="diploma_course_code"
                                       class="form-control @error('diploma_course_code') is-invalid @enderror"
                                       placeholder="e.g., DCS210"
                                       value="{{ old('diploma_course_code', $studentRequest->diploma_course_code ?? '') }}"
                                       required>
                                @error('diploma_course_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="diploma_credit_hour" class="form-label fw-bold">Credit Hours</label>
                                <input type="number"
                                       name="diploma_credit_hour"
                                       id="diploma_credit_hour"
                                       class="form-control @error('diploma_credit_hour') is-invalid @enderror"
                                       min="1"
                                       max="10"
                                       value="{{ old('diploma_credit_hour', $studentRequest->diploma_credit_hours ?? 3) }}"
                                       required>
                                @error('diploma_credit_hour')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="diploma_course_name" class="form-label fw-bold">Course Name</label>
                            <input type="text"
                                   name="diploma_course_name"
                                   id="diploma_course_name"
                                   class="form-control @error('diploma_course_name') is-invalid @enderror"
                                   placeholder="e.g., Advanced Database Systems"
                                   value="{{ old('diploma_course_name', $studentRequest->diploma_course_name ?? '') }}"
                                   required>
                            @error('diploma_course_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="diploma_institution" class="form-label fw-bold">Source Institution</label>
                            <select name="diploma_institution" id="diploma_institution" class="form-select @error('diploma_institution') is-invalid @enderror" required>
                                <option value="">-- Select Institution --</option>
                                @foreach($institutions as $key => $name)
                                    <option value="{{ $name }}"
                                            {{ old('diploma_institution', $studentRequest->diploma_institution ?? '') == $name ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('diploma_institution')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <!-- Degree Course Section -->
                        <h5 class="text-info mb-3">
                            <i class="fas fa-university me-2"></i>Equivalent Degree Course
                        </h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="degree_course_code" class="form-label fw-bold">Course Code</label>
                                <input type="text"
                                       name="degree_course_code"
                                       id="degree_course_code"
                                       class="form-control @error('degree_course_code') is-invalid @enderror"
                                       placeholder="e.g., CS210"
                                       value="{{ old('degree_course_code', $studentRequest->suggested_degree_course_code ?? '') }}"
                                       required>
                                @error('degree_course_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="degree_credit_hour" class="form-label fw-bold">Credit Hours</label>
                                <input type="number"
                                       name="degree_credit_hour"
                                       id="degree_credit_hour"
                                       class="form-control @error('degree_credit_hour') is-invalid @enderror"
                                       min="1"
                                       max="10"
                                       value="{{ old('degree_credit_hour', 3) }}"
                                       required>
                                @error('degree_credit_hour')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="degree_course_name" class="form-label fw-bold">Course Name</label>
                            <input type="text"
                                   name="degree_course_name"
                                   id="degree_course_name"
                                   class="form-control @error('degree_course_name') is-invalid @enderror"
                                   placeholder="e.g., Database Management Systems"
                                   value="{{ old('degree_course_name', $studentRequest->suggested_degree_course_name ?? '') }}"
                                   required>
                            @error('degree_course_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <!-- Equivalency Assessment -->
                        <h5 class="text-warning mb-3">
                            <i class="fas fa-percentage me-2"></i>Equivalency Assessment
                        </h5>

                        <div class="mb-3">
                            <label for="match_percentage" class="form-label fw-bold">
                                Match Percentage <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number"
                                       name="match_percentage"
                                       id="match_percentage"
                                       class="form-control @error('match_percentage') is-invalid @enderror"
                                       min="0"
                                       max="100"
                                       step="0.01"
                                       value="{{ old('match_percentage', 85) }}"
                                       required>
                                <span class="input-group-text">%</span>
                                @error('match_percentage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">
                                Based on your syllabus evaluation, estimate the percentage match between these courses.
                                Courses with ≥80% match are typically eligible for exemption.
                            </small>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label fw-bold">Notes / Justification (Optional)</label>
                            <textarea name="notes"
                                      id="notes"
                                      class="form-control @error('notes') is-invalid @enderror"
                                      rows="4"
                                      placeholder="Add any notes about this mapping, such as similarities in learning outcomes, teaching methods, or assessment criteria..."
                                      >{{ old('notes', $studentRequest->justification_notes ?? '') }}</textarea>
                            <small class="text-muted">Maximum 1000 characters</small>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Error Messages -->
                        @if($errors->any() && !$errors->has('program_code') && !$errors->has('diploma_course_code') && !$errors->has('diploma_course_name'))
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('resource_person.equivalency_lists.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-paper-plane me-2"></i>Forward to Program Coordinator
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Information Panel -->
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>What Happens Next?</h6>
                </div>
                <div class="card-body">
                    <ol class="mb-0">
                        <li>Your course mapping will be sent to the Program Coordinator for review</li>
                        <li>The Program Coordinator will evaluate your mapping</li>
                        <li>They can either:
                            <ul>
                                <li><strong>Add it</strong> to the official equivalency list for the program</li>
                                <li><strong>Reject it</strong> with a reason for rejection</li>
                            </ul>
                        </li>
                        <li>You can track the status in your dashboard under "Forwarded Mappings"</li>
                        <li>If added, the mapping becomes part of the published equivalency list for students</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
