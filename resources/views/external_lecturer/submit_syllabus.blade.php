@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Syllabus Submission Form</div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @else
                        <h5>Syllabus Request Details</h5>
                        <p>You have been requested to provide the complete syllabus for the following course:</p>
                        <table class="table table-bordered">
                            <tr>
                                <th>Course Code</th>
                                <td>{{ $subject->course_code }}</td>
                            </tr>
                            <tr>
                                <th>Course Name</th>
                                <td>{{ $subject->course_name }}</td>
                            </tr>
                             <tr>
                                <th>Original Institution</th>
                                <td>{{ $subject->exemptionApplication->previous_institution }}</td>
                            </tr>
                        </table>

                        <hr>

                        <form method="POST" action="{{ route('syllabus.store', $subject) }}" enctype="multipart/form-data">
                            @csrf
                            
                            <h5>Your Details</h5>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="submitter_name" class="form-label"><strong>Your Name</strong></label>
                                    <input type="text" name="submitter_name" id="submitter_name" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="submitter_email" class="form-label"><strong>Your Email</strong></label>
                                    <input type="email" name="submitter_email" id="submitter_email" class="form-control" required>
                                </div>
                            </div>
                            
                            {{-- New field for submitter's institution --}}
                            <div class="mb-3">
                                <label for="submitter_institution" class="form-label"><strong>Your Institution Name</strong></label>
                                <input type="text" name="submitter_institution" id="submitter_institution" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="syllabus_file" class="form-label"><strong>Upload Syllabus File (PDF only)</strong></label>
                                <input type="file" name="syllabus_file" id="syllabus_file" class="form-control" required accept=".pdf">
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Submit Syllabus</button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
