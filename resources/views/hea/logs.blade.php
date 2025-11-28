@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
             <a href="{{ route('hea.dashboard') }}" class="btn btn-light mb-3"><< Back to Dashboard</a>
            <div class="card">
                <div class="card-header">System Logs (Audit Trail)</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Timestamp</th>
                                    <th>User</th>
                                    <th>Action</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($logs as $log)
                                <tr>
                                    <td>{{ $log->created_at->format('d M Y, h:i:s A') }}</td>
                                    <td>{{ $log->user->name ?? 'System' }}</td>
                                    <td>{{ $log->action }}</td>
                                    <td>
                                        @php
                                            $details = json_decode($log->details, true);
                                            if (is_array($details)) {
                                                echo '<div class="log-details">';
                                                
                                                // Format different types of actions
                                                if ($log->action == 'Lecturer Decision') {
                                                    echo '<strong>Decision:</strong> ' . ($details['decision'] ?? 'N/A') . '<br>';
                                                    echo '<strong>Course:</strong> ' . ($details['course_code'] ?? 'N/A') . ' - ' . ($details['course_name'] ?? 'N/A') . '<br>';
                                                    if (isset($details['program_code'])) {
                                                        echo '<strong>Program:</strong> ' . $details['program_code'] . '<br>';
                                                    }
                                                } 
                                                elseif ($log->action == 'Resource Person Decision') {
                                                    echo '<strong>Decision:</strong> ' . ($details['decision'] ?? 'N/A') . '<br>';
                                                    echo '<strong>Course:</strong> ' . ($details['course_code'] ?? 'N/A') . ' - ' . ($details['course_name'] ?? 'N/A') . '<br>';
                                                    if (isset($details['degree_course_code'])) {
                                                        echo '<strong>Equivalent Course:</strong> ' . $details['degree_course_code'] . '<br>';
                                                        echo '<strong>Match Percentage:</strong> ' . ($details['match_percentage'] ?? 'N/A') . '%<br>';
                                                    }
                                                    if (isset($details['equivalency_created']) && $details['equivalency_created'] == 'Yes') {
                                                        echo '<span class="badge bg-success">New Equivalency Created</span><br>';
                                                    }
                                                    if (isset($details['extraction_method']) && $details['extraction_method'] == 'ocr') {
                                                        echo '<span class="badge bg-info">OCR Extracted</span><br>';
                                                    }
                                                } 
                                                elseif ($log->action == 'Application Submitted') {
                                                    echo '<strong>Student:</strong> ' . ($details['student_name'] ?? 'N/A') . '<br>';
                                                    echo '<strong>Program:</strong> ' . ($details['current_program_code'] ?? 'N/A') . '<br>';
                                                    if (isset($details['total_courses'])) {
                                                        echo '<strong>Total Courses:</strong> ' . $details['total_courses'] . '<br>';
                                                    }
                                                } 
                                                else {
                                                    // Generic formatting for other actions
                                                    foreach ($details as $key => $value) {
                                                        if (!is_array($value) && !is_object($value)) {
                                                            $formattedKey = ucwords(str_replace('_', ' ', $key));
                                                            if ($key == 'course_code' || $key == 'course_name') {
                                                                echo '<strong>' . $formattedKey . ':</strong> ' . $value . '<br>';
                                                            } elseif (in_array($key, ['decision', 'status', 'finding', 'action'])) {
                                                                echo '<strong>' . $formattedKey . ':</strong> ' . $value . '<br>';
                                                            } elseif (strlen($value) < 100) {
                                                                echo '<strong>' . $formattedKey . ':</strong> ' . $value . '<br>';
                                                            }
                                                        }
                                                    }
                                                }
                                                echo '</div>';
                                            } else {
                                                echo '<div class="text-muted">Raw data: ' . substr($log->details, 0, 100) . '...</div>';
                                            }
                                        @endphp
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($logs->hasPages())
                        <div class="card-footer d-flex justify-content-center">
                            {{ $logs->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
