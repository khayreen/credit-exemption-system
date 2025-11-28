@extends('layouts.app')

@section('content')
<!-- Welcome Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="welcome-card">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="welcome-content">
                        <h1 class="welcome-title">Welcome Back, {{ Auth::user()->name }}!</h1>
                        <p class="welcome-subtitle">
                            <i class="fas fa-university me-2"></i>
                            UiTM Credit Exemption Management System
                        </p>
                        <p class="welcome-description">
                            Manage your credit exemption applications efficiently. Track your progress, submit new applications, 
                            and stay updated with the latest status of your academic credits.
                        </p>
                        <div class="welcome-stats">
                            <span class="stat-item">
                                <i class="fas fa-calendar me-1"></i>
                                <strong>{{ \Carbon\Carbon::now()->format('l, F j, Y') }}</strong>
                            </span>
                            <span class="stat-item ms-4">
                                <i class="fas fa-clock me-1"></i>
                                Last login: {{ Auth::user()->last_login_at ? Auth::user()->last_login_at->diffForHumans() : 'First time' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 text-end">
                    <div class="welcome-illustration">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards
<div class="row mb-4">
    <div class="col-lg-6 col-md-6 mb-4">
        <div class="stat-card-modern stat-warning">
            <div class="stat-card-body">
                <div class="stat-card-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-card-content">
                    <h3 class="stat-number">{{ $stats['pending'] }}</h3>
                    <p class="stat-label">Pending Review</p>
                    <div class="stat-trend">
                        <i class="fas fa-hourglass-half"></i>
                        <span>In progress</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6 col-md-6 mb-4">
        <div class="stat-card-modern stat-success">
            <div class="stat-card-body">
                <div class="stat-card-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-card-content">
                    <h3 class="stat-number">{{ $stats['completed'] }}</h3>
                    <p class="stat-label">Completed</p>
                    <div class="stat-trend">
                        <i class="fas fa-check"></i>
                        <span>Approved</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->

<!-- Main Content Row -->
<div class="row">
    <!-- Quick Actions -->
    <div class="col-12 mb-4">
        <div class="card dashboard-card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bolt me-2"></i>Quick Actions
                </h5>
                <p class="card-subtitle">Most commonly used features</p>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="action-item">
                            <div class="action-icon action-icon-primary">
                                <i class="fas fa-plus-circle"></i>
                            </div>
                            <div class="action-content">
                                <h6>New Application</h6>
                                <p>Submit a new credit exemption application</p>
                                <a href="{{ route('student.application.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus me-1"></i>Apply Now
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="action-item">
                            <div class="action-icon action-icon-info">
                                <i class="fas fa-search"></i>
                            </div>
                            <div class="action-content">
                                <h6>Track Status</h6>
                                <p>View the status of your applications</p>
                                <a href="{{ route('student.application.status') }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i>View Status
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="action-item">
                            <div class="action-icon action-icon-success">
                                <i class="fas fa-user-edit"></i>
                            </div>
                            <div class="action-content">
                                <h6>Update Profile</h6>
                                <p>Keep your academic profile up to date</p>
                                <a href="{{ route('profile.show') }}" class="btn btn-outline-success btn-sm">
                                    <i class="fas fa-edit me-1"></i>Edit Profile
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Helpful Resources -->
<div class="row mb-4">
    <div class="col-12 mb-4">
        <div class="card dashboard-card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>Important Information
                </h5>
                <p class="card-subtitle">Terms and academic resources</p>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="resource-item mb-3">
                            <h6><i class="fas fa-file-contract me-2 text-primary"></i>Terms & Conditions</h6>
                            <p class="text-muted small">{{ \Str::limit(\App\Models\SystemSetting::get('terms_and_conditions', 'Please review the terms and conditions...'), 100) }}</p>
                            <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#termsModal">
                                <i class="fas fa-eye me-1"></i>Read Full Terms
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="resource-item">
                            <h6><i class="fas fa-calendar-alt me-2 text-info"></i>Academic Calendar</h6>
                            <p class="text-muted small">View important academic dates and deadlines</p>
                            <a href="{{ \App\Models\SystemSetting::get('academic_calendar_url', '#') }}" 
                               target="_blank" 
                               class="btn btn-outline-info btn-sm">
                                <i class="fas fa-external-link-alt me-1"></i>View Calendar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>

<!-- Recent Activity -->
<div class="row">
    <div class="col-12">
        <div class="card dashboard-card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-history me-2"></i>Recent Activity
                </h5>
                <p class="card-subtitle">Your latest application activities</p>
            </div>
            <div class="card-body">
                @if(isset($recentApplications) && $recentApplications->count() > 0)
                    <div class="activity-timeline">
                        @foreach($recentApplications as $application)
                            <div class="timeline-item">
                                <div class="timeline-marker {{ $application->status === 'completed' ? 'timeline-marker-success' : ($application->status === 'pending' ? 'timeline-marker-warning' : 'timeline-marker-primary') }}">
                                    <i class="fas {{ $application->status === 'completed' ? 'fa-check' : ($application->status === 'pending' ? 'fa-clock' : 'fa-file') }}"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6>Application #{{ $application->id }}</h6>
                                    <p class="text-muted">{{ $application->created_at->format('M d, Y \a\t g:i A') }}</p>
                                    <span class="badge badge-{{ $application->status === 'completed' ? 'success' : ($application->status === 'pending' ? 'warning' : 'primary') }}">
                                        {{ ucfirst($application->status) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <h6>No Recent Activity</h6>
                        <p class="text-muted">Your recent application activities will appear here</p>
                        <a href="{{ route('student.application.create') }}" class="btn btn-primary">Create Your First Application</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
/* Welcome Card Styles */
.welcome-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    padding: 2rem;
    color: white;
    margin-bottom: 0;
    box-shadow: 0 20px 40px rgba(102, 126, 234, 0.1);
}

.welcome-title {
    font-size: 2.2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.welcome-subtitle {
    font-size: 1.1rem;
    margin-bottom: 1rem;
    opacity: 0.9;
}

.welcome-description {
    font-size: 1rem;
    line-height: 1.6;
    margin-bottom: 1.5rem;
    opacity: 0.9;
}

.welcome-stats .stat-item {
    font-size: 0.9rem;
    opacity: 0.8;
}

.welcome-illustration {
    font-size: 8rem;
    opacity: 0.2;
}

/* Modern Stat Cards */
.stat-card-modern {
    border-radius: 20px;
    border: none;
    transition: all 0.3s ease;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
}

.stat-card-modern:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.12);
}

.stat-card-body {
    padding: 2rem;
    display: flex;
    align-items: center;
}

.stat-card-icon {
    width: 80px;
    height: 80px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    margin-right: 1.5rem;
}

.stat-primary .stat-card-icon { background: linear-gradient(135deg, #667eea, #764ba2); color: white; }
.stat-warning .stat-card-icon { background: linear-gradient(135deg, #f093fb, #f5576c); color: white; }
.stat-success .stat-card-icon { background: linear-gradient(135deg, #4facfe, #00f2fe); color: white; }
.stat-info .stat-card-icon { background: linear-gradient(135deg, #43e97b, #38f9d7); color: white; }

.stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: #2d3748;
}

.stat-label {
    font-size: 1rem;
    color: #718096;
    margin-bottom: 0.5rem;
}

.stat-trend {
    font-size: 0.875rem;
    color: #a0aec0;
}

/* Dashboard Cards */
.dashboard-card {
    border-radius: 20px;
    border: none;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}

.dashboard-card:hover {
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
}

.dashboard-card .card-header {
    background: white;
    border-bottom: 1px solid #e2e8f0;
    border-radius: 20px 20px 0 0;
    padding: 1.5rem 2rem;
}

.dashboard-card .card-title {
    color: #2d3748;
    font-weight: 600;
}

.dashboard-card .card-subtitle {
    color: #718096;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

/* Action Items */
.action-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    border-radius: 15px;
    background: #f7fafc;
    transition: all 0.3s ease;
}

.action-item:hover {
    background: #edf2f7;
    transform: translateY(-2px);
}

.action-icon {
    width: 60px;
    height: 60px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin-right: 1rem;
}

.action-icon-primary { background: rgba(102, 126, 234, 0.1); color: #667eea; }
.action-icon-info { background: rgba(56, 178, 172, 0.1); color: #38b2ac; }
.action-icon-success { background: rgba(72, 187, 120, 0.1); color: #48bb78; }
.action-icon-warning { background: rgba(237, 137, 54, 0.1); color: #ed8936; }

.action-content h6 {
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.25rem;
}

.action-content p {
    color: #718096;
    font-size: 0.875rem;
    margin-bottom: 0.75rem;
}

/* Announcements */
.announcement-item {
    display: flex;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid #e2e8f0;
}

.announcement-item:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

.announcement-date {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 15px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    color: white;
}

.announcement-date .day {
    font-size: 1.25rem;
    font-weight: 700;
    line-height: 1;
}

.announcement-date .month {
    font-size: 0.75rem;
    text-transform: uppercase;
}

.announcement-content h6 {
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.25rem;
}

/* Quick Links */
.quick-links {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.quick-link-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    border-radius: 12px;
    background: #f7fafc;
    color: #4a5568;
    text-decoration: none;
    transition: all 0.3s ease;
}

.quick-link-item:hover {
    background: #edf2f7;
    color: #2d3748;
    text-decoration: none;
    transform: translateX(5px);
}

.quick-link-item i {
    width: 20px;
    margin-right: 0.75rem;
    color: #667eea;
}

/* Timeline */
.activity-timeline {
    position: relative;
}

.timeline-item {
    display: flex;
    margin-bottom: 2rem;
    position: relative;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-marker {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    flex-shrink: 0;
}

.timeline-marker-primary { background: rgba(102, 126, 234, 0.1); color: #667eea; }
.timeline-marker-warning { background: rgba(237, 137, 54, 0.1); color: #ed8936; }
.timeline-marker-success { background: rgba(72, 187, 120, 0.1); color: #48bb78; }

.timeline-content h6 {
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.25rem;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem 2rem;
}

.empty-state i {
    font-size: 4rem;
    color: #cbd5e0;
    margin-bottom: 1rem;
}

.empty-state h6 {
    color: #4a5568;
    margin-bottom: 0.5rem;
}

/* Badge Styles */
.badge-primary { background: #667eea; }
.badge-warning { background: #ed8936; }
.badge-success { background: #48bb78; }

/* Resource Item Styles */
.resource-item {
    padding: 1rem 0;
    border-bottom: 1px solid #f1f1f1;
}

.resource-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.resource-item h6 {
    color: #2d3748;
    margin-bottom: 0.5rem;
}
</style>

<!-- Terms and Conditions Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="termsModalLabel">
                    <i class="fas fa-file-contract me-2"></i>Terms and Conditions
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div style="max-height: 400px; overflow-y: auto;">
                    @php
                        $termsContent = \App\Models\SystemSetting::get('terms_and_conditions', 'Terms and conditions content will appear here.');
                        // Convert line breaks to HTML
                        $termsContent = nl2br(e($termsContent));
                    @endphp
                    {!! $termsContent !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="window.print()">
                    <i class="fas fa-print me-1"></i>Print
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
