@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<style>
    :root {
        --uitm-blue: #1e3a8a;
        --uitm-blue-light: #3b82f6;
        --uitm-amber: #f59e0b;
        --industrial-dark: #0f172a;
        --industrial-gray: #334155;
        --industrial-light: #f1f5f9;
        --success: #059669;
        --danger: #dc2626;
        --warning: #ea580c;
        --info: #0d9488;
    }

    body {
        font-family: 'IBM Plex Sans', sans-serif;
        background-color: var(--industrial-light);
    }

    .page-header {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--industrial-dark) 100%);
        border-radius: 0 0 24px 24px;
        padding: 2rem 2.5rem;
        margin: -1.5rem -1.5rem 2rem -1.5rem;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .page-header h2 {
        font-weight: 700;
        font-size: 1.75rem;
        margin-bottom: 0.35rem;
    }

    .page-header p {
        color: rgba(255,255,255,0.8);
        margin: 0;
    }

    .btn-back {
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.3);
        color: white;
        padding: 0.5rem 1.25rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-back:hover {
        background: rgba(255,255,255,0.25);
        color: white;
        transform: translateX(-3px);
    }

    /* Logs Card */
    .logs-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        overflow: hidden;
    }

    .logs-header {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--uitm-blue-light) 100%);
        color: white;
        padding: 1.25rem 1.5rem;
    }

    .logs-header h5 {
        margin: 0;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-state i {
        font-size: 4rem;
        color: #cbd5e1;
        margin-bottom: 1rem;
    }

    .empty-state h5 {
        color: var(--industrial-gray);
        font-weight: 500;
    }

    .empty-state p {
        color: #94a3b8;
    }

    /* Logs Table */
    .logs-table {
        width: 100%;
        margin: 0;
    }

    .logs-table thead th {
        background: var(--industrial-light);
        color: var(--industrial-gray);
        font-weight: 600;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 1rem;
        border-bottom: 2px solid var(--uitm-blue);
    }

    .logs-table tbody td {
        padding: 1rem;
        vertical-align: top;
        border-bottom: 1px solid #f1f5f9;
    }

    .logs-table tbody tr:hover {
        background: rgba(30, 58, 138, 0.02);
    }

    .timestamp-cell {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .timestamp-icon {
        width: 36px;
        height: 36px;
        background: rgba(30, 58, 138, 0.1);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--uitm-blue);
        flex-shrink: 0;
    }

    .timestamp-text {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.85rem;
        color: var(--industrial-dark);
    }

    .user-name {
        font-weight: 600;
        color: var(--industrial-dark);
    }

    .action-badge {
        display: inline-block;
        padding: 0.4rem 0.875rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.8rem;
        background: rgba(13, 148, 136, 0.1);
        color: var(--info);
        border: 1px solid rgba(13, 148, 136, 0.2);
    }

    .log-details {
        font-size: 0.85rem;
        color: var(--industrial-gray);
        line-height: 1.6;
    }

    .log-details strong {
        color: var(--industrial-dark);
    }

    .log-details .new-badge {
        display: inline-block;
        background: rgba(5, 150, 105, 0.1);
        color: var(--success);
        padding: 0.2rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-top: 0.25rem;
    }

    .log-details .ocr-badge {
        display: inline-block;
        background: rgba(13, 148, 136, 0.1);
        color: var(--info);
        padding: 0.2rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-top: 0.25rem;
    }

    .raw-data {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.8rem;
        color: #94a3b8;
    }

    /* Footer */
    .logs-footer {
        background: var(--industrial-light);
        padding: 1rem 1.5rem;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .logs-footer.stats {
        justify-content: center;
    }

    .total-count {
        font-size: 0.85rem;
        color: var(--industrial-gray);
    }

    .total-count strong {
        font-family: 'IBM Plex Mono', monospace;
        color: var(--uitm-blue);
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
            margin: -1rem -1rem 1.5rem -1rem;
            border-radius: 0 0 16px 16px;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2><i class="fas fa-clipboard-list me-2"></i>System Logs (Audit Trail)</h2>
            <p><i class="fas fa-history me-2"></i>Track all system activities and user actions</p>
        </div>
        <a href="{{ route('hea.dashboard') }}" class="btn-back">
            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
        </a>
    </div>

    <!-- Logs Card -->
    <div class="logs-card">
        <div class="logs-header">
            <h5><i class="fas fa-clipboard-list"></i>Audit Trail Logs</h5>
        </div>

        @if($logs->isEmpty())
            <div class="empty-state">
                <i class="fas fa-history"></i>
                <h5>No Logs Found</h5>
                <p>No system logs have been recorded yet.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="logs-table">
                    <thead>
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
                                <div class="timestamp-cell">
                                    <div class="timestamp-icon">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <span class="timestamp-text">{{ $log->created_at->format('d M Y, h:i:s A') }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="user-name">{{ $log->user->name ?? 'System' }}</span>
                            </td>
                            <td>
                                <span class="action-badge">{{ $log->action }}</span>
                            </td>
                            <td>
                                @php
                                    $details = json_decode($log->details, true);
                                @endphp
                                @if(is_array($details))
                                    <div class="log-details">
                                        @if($log->action == 'Lecturer Decision')
                                            <strong>Decision:</strong> {{ $details['decision'] ?? 'N/A' }}<br>
                                            <strong>Course:</strong> {{ $details['course_code'] ?? 'N/A' }} - {{ $details['course_name'] ?? 'N/A' }}<br>
                                            @if(isset($details['program_code']))
                                                <strong>Program:</strong> {{ $details['program_code'] }}<br>
                                            @endif
                                        @elseif($log->action == 'Resource Person Decision')
                                            <strong>Decision:</strong> {{ $details['decision'] ?? 'N/A' }}<br>
                                            <strong>Course:</strong> {{ $details['course_code'] ?? 'N/A' }} - {{ $details['course_name'] ?? 'N/A' }}<br>
                                            @if(isset($details['degree_course_code']))
                                                <strong>Equivalent Course:</strong> {{ $details['degree_course_code'] }}<br>
                                                <strong>Match Percentage:</strong> {{ $details['match_percentage'] ?? 'N/A' }}%<br>
                                            @endif
                                            @if(isset($details['equivalency_created']) && $details['equivalency_created'] == 'Yes')
                                                <span class="new-badge">New Equivalency Created</span><br>
                                            @endif
                                            @if(isset($details['extraction_method']) && $details['extraction_method'] == 'ocr')
                                                <span class="ocr-badge">OCR Extracted</span><br>
                                            @endif
                                        @elseif($log->action == 'Application Submitted')
                                            <strong>Student:</strong> {{ $details['student_name'] ?? 'N/A' }}<br>
                                            <strong>Program:</strong> {{ $details['current_program_code'] ?? 'N/A' }}<br>
                                            @if(isset($details['total_courses']))
                                                <strong>Total Courses:</strong> {{ $details['total_courses'] }}<br>
                                            @endif
                                        @elseif($log->action == 'Endorsed and Published Equivalency List')
                                            <strong>Program Code:</strong> {{ $details['Program Code'] ?? 'N/A' }}<br>
                                            <strong>Category:</strong> {{ $details['Category'] ?? 'N/A' }}<br>
                                            <strong>Semester:</strong> {{ $details['Semester'] ?? 'N/A' }}<br>
                                            <strong>Total Equivalencies:</strong> {{ $details['Total Equivalencies'] ?? 'N/A' }}<br>
                                            @if(isset($details['Endorsement Notes']))
                                                <strong>Endorsement Notes:</strong> {{ $details['Endorsement Notes'] }}<br>
                                            @endif
                                        @elseif($log->action == 'Deleted Draft Equivalency List')
                                            <strong>Program Code:</strong> {{ $details['Program Code'] ?? 'N/A' }}<br>
                                            <strong>Semester:</strong> {{ $details['Semester'] ?? 'N/A' }}<br>
                                            <strong>Category:</strong> {{ $details['Category'] ?? 'N/A' }}<br>
                                            <strong>Total Equivalencies:</strong> {{ $details['Total Equivalencies'] ?? 'N/A' }}<br>
                                        @elseif($log->action == 'Academic Advisor Bulk Rejection')
                                            <strong>Decision:</strong> {{ $details['Decision'] ?? 'N/A' }}<br>
                                            <strong>Course Code:</strong> {{ $details['Course Code'] ?? 'N/A' }}<br>
                                            <strong>Course Name:</strong> {{ $details['Course Name'] ?? 'N/A' }}<br>
                                        @else
                                            @foreach($details as $key => $value)
                                                @if(!is_array($value) && !is_object($value) && strlen($value) < 100)
                                                    <strong>{{ ucwords(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}<br>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                @else
                                    <div class="raw-data">Raw data: {{ substr($log->details, 0, 100) }}...</div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($logs->hasPages())
                <div class="logs-footer">
                    {{ $logs->links() }}
                </div>
            @else
                <div class="logs-footer stats">
                    <span class="total-count">
                        <i class="fas fa-info-circle me-1"></i>
                        Total Logs: <strong>{{ $logs->count() }}</strong>
                    </span>
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
