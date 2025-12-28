@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">System Logs (Audit Trail)</h2>
            <p class="text-muted mb-0 mt-2">
                <i class="fas fa-history me-1"></i>
                Track all system activities and user actions
            </p>
        </div>
        <div>
            <a href="{{ route('hea.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <!-- System Logs Card -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0">
                <i class="fas fa-clipboard-list me-2"></i>Audit Trail Logs
            </h5>
        </div>
        <div class="card-body">
            @if($logs->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-history fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">No Logs Found</h5>
                    <p class="text-muted mb-0">No system logs have been recorded yet.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="18%">Timestamp</th>
                                <th width="15%">User</th>
                                <th width="20%">Action</th>
                                <th width="47%">Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($logs as $log)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-clock fa-lg text-primary me-2"></i>
                                        <span>{{ $log->created_at->format('d M Y, h:i:s A') }}</span>
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ $log->user->name ?? 'System' }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $log->action }}</span>
                                </td>
                                <td>
                                    @php
                                        $details = json_decode($log->details, true);
                                        if (is_array($details)) {
                                            echo '<div class="log-details small">';

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
                                            elseif ($log->action == 'Endorsed and Published Equivalency List') {
                                                echo '<strong>Program Code:</strong> ' . ($details['Program Code'] ?? 'N/A') . '<br>';
                                                echo '<strong>Category:</strong> ' . ($details['Category'] ?? 'N/A') . '<br>';
                                                echo '<strong>Semester:</strong> ' . ($details['Semester'] ?? 'N/A') . '<br>';
                                                echo '<strong>Total Equivalencies:</strong> ' . ($details['Total Equivalencies'] ?? 'N/A') . '<br>';
                                                if (isset($details['Endorsement Notes'])) {
                                                    echo '<strong>Endorsement Notes:</strong> ' . $details['Endorsement Notes'] . '<br>';
                                                }
                                            }
                                            elseif ($log->action == 'Deleted Draft Equivalency List') {
                                                echo '<strong>Program Code:</strong> ' . ($details['Program Code'] ?? 'N/A') . '<br>';
                                                echo '<strong>Semester:</strong> ' . ($details['Semester'] ?? 'N/A') . '<br>';
                                                echo '<strong>Category:</strong> ' . ($details['Category'] ?? 'N/A') . '<br>';
                                                echo '<strong>Total Equivalencies:</strong> ' . ($details['Total Equivalencies'] ?? 'N/A') . '<br>';
                                            }
                                            elseif ($log->action == 'Academic Advisor Bulk Rejection') {
                                                echo '<strong>Decision:</strong> ' . ($details['Decision'] ?? 'N/A') . '<br>';
                                                echo '<strong>Course Code:</strong> ' . ($details['Course Code'] ?? 'N/A') . '<br>';
                                                echo '<strong>Course Name:</strong> ' . ($details['Course Name'] ?? 'N/A') . '<br>';
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
                                            echo '<div class="text-muted small">Raw data: ' . substr($log->details, 0, 100) . '...</div>';
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
                    <div class="card-footer bg-light d-flex justify-content-center">
                        {{ $logs->links() }}
                    </div>
                @endif

                <!-- Statistics Footer (if no pagination) -->
                @if(!$logs->hasPages())
                    <div class="card-footer bg-light">
                        <div class="row text-center">
                            <div class="col-md-12">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Total Logs: <strong>{{ $logs->count() }}</strong>
                                </small>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
