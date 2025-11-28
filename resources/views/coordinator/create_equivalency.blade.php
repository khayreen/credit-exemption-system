@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            {{-- Link to go back to the main application view --}}
            <a href="{{ route('coordinator.application.show', $application) }}" class="btn btn-light mb-3"><< Back to Application</a>
            <div class="card">
                <div class="card-header">Create New Course Equivalency</div>

                <div class="card-body">
                    <h5>Diploma Course</h5>
                    <table class="table table-sm table-bordered">
                        <tr>
                            <th>Code</th>
                            <td>{{ $subject->course_code }}</td>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <td>{{ $subject->course_name }}</td>
                        </tr>
                    </table>

                    <hr>

                    {{-- Form to create the equivalency --}}
                    <form method="POST" action="{{ route('coordinator.equivalency.store', ['application' => $application, 'subject' => $subject]) }}">
                        @csrf
                        <div class="mb-3">
                            <label for="degree_course_code" class="form-label"><strong>Equivalent Degree Course</strong></label>
                            <select name="degree_course_code" id="degree_course_code" class="form-select" required>
                                <option value="" selected disabled>-- Select a Degree Course --</option>
                                @foreach($degreeCourses as $course)
                                    <option value="{{ $course->code }}">{{ $course->code }} - {{ $course->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="match_percentage" class="form-label"><strong>Match Percentage (%)</strong></label>
                            <input type="number" name="match_percentage" id="match_percentage" class="form-control" min="0" max="100" step="0.1" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Save Equivalency</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
