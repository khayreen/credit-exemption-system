@extends('layouts.app')

@section('content')
<div class="forms-container">
    <!-- Header Section -->
    <div class="forms-header">
        <div class="row align-items-center">
            <div class="col-lg-9">
                <h1 class="forms-title">Download Forms</h1>
                <p class="forms-subtitle">
                    <i class="fas fa-file-download me-2"></i>
                    Credit Exemption Application Forms
                </p>
                <p class="forms-description">
                    Access all required forms and documents for your credit exemption application.
                </p>
            </div>
            <div class="col-lg-3 text-end">
                <div class="forms-illustration">
                    <i class="fas fa-folder-open"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Available Forms -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="form-card">
                <div class="form-card-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                    <i class="fas fa-file-pdf"></i>
                </div>
                <div class="form-card-content">
                    <h5>Credit Exemption Application Form</h5>
                    <p class="text-muted">The main application form for requesting credit exemption. Required for all applications.</p>
                    <div class="form-meta">
                        <span><i class="fas fa-file me-1"></i> PDF Format</span>
                        <span><i class="fas fa-calendar me-1"></i> Updated Jan 2026</span>
                    </div>
                    <a href="https://hea.uitm.edu.my" target="_blank" class="btn btn-primary mt-3">
                        <i class="fas fa-external-link-alt me-1"></i> Download from HEA
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-card">
                <div class="form-card-icon" style="background: linear-gradient(135deg, #48bb78, #38a169);">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="form-card-content">
                    <h5>Course Equivalency Request Form</h5>
                    <p class="text-muted">Form for requesting evaluation of courses not in the equivalency database.</p>
                    <div class="form-meta">
                        <span><i class="fas fa-file me-1"></i> PDF Format</span>
                        <span><i class="fas fa-calendar me-1"></i> Updated Jan 2026</span>
                    </div>
                    <a href="https://hea.uitm.edu.my" target="_blank" class="btn btn-success mt-3">
                        <i class="fas fa-external-link-alt me-1"></i> Download from HEA
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-card">
                <div class="form-card-icon" style="background: linear-gradient(135deg, #ed8936, #dd6b20);">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <div class="form-card-content">
                    <h5>Appeal Form</h5>
                    <p class="text-muted">Form for appealing rejected credit exemption applications.</p>
                    <div class="form-meta">
                        <span><i class="fas fa-file me-1"></i> PDF Format</span>
                        <span><i class="fas fa-calendar me-1"></i> Updated Jan 2026</span>
                    </div>
                    <a href="https://hea.uitm.edu.my" target="_blank" class="btn btn-warning mt-3">
                        <i class="fas fa-external-link-alt me-1"></i> Download from HEA
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-card">
                <div class="form-card-icon" style="background: linear-gradient(135deg, #38b2ac, #319795);">
                    <i class="fas fa-book"></i>
                </div>
                <div class="form-card-content">
                    <h5>Guidelines & Procedures</h5>
                    <p class="text-muted">Complete guide to the credit exemption process and requirements.</p>
                    <div class="form-meta">
                        <span><i class="fas fa-file me-1"></i> PDF Format</span>
                        <span><i class="fas fa-calendar me-1"></i> Updated Jan 2026</span>
                    </div>
                    <a href="https://hea.uitm.edu.my" target="_blank" class="btn btn-info mt-3">
                        <i class="fas fa-external-link-alt me-1"></i> Download from HEA
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- HEA Forms Portal -->
    <div class="hea-portal-card">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h4><i class="fas fa-university me-2"></i>HEA Forms Portal</h4>
                <p class="mb-0">Access additional forms and official documents from the Higher Education Authority (HEA) website. All forms are regularly updated to reflect the latest requirements.</p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <a href="https://hea.uitm.edu.my" target="_blank" class="btn btn-light btn-lg">
                    <i class="fas fa-external-link-alt me-1"></i> Visit HEA Portal
                </a>
            </div>
        </div>
    </div>

    <!-- Tips Section -->
    <div class="tips-card mt-4">
        <h5><i class="fas fa-lightbulb me-2"></i>Tips for Completing Forms</h5>
        <div class="row g-3 mt-2">
            <div class="col-md-4">
                <div class="tip-item">
                    <div class="tip-number">1</div>
                    <div>
                        <h6>Use Latest Versions</h6>
                        <p>Always download the most recent version of forms from the HEA website.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="tip-item">
                    <div class="tip-number">2</div>
                    <div>
                        <h6>Complete All Fields</h6>
                        <p>Fill in all required fields accurately. Incomplete forms may delay processing.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="tip-item">
                    <div class="tip-number">3</div>
                    <div>
                        <h6>Attach Documents</h6>
                        <p>Include all supporting documents such as transcripts and course syllabi.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Back to Dashboard Button -->
    <div class="text-center mt-4 mb-5">
        <a href="{{ route('student.dashboard') }}" class="btn btn-lg" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border-radius: 12px; padding: 1rem 3rem; font-weight: 600; border: none; box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);">
            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
        </a>
    </div>
</div>

<style>
.forms-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
}

.forms-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    padding: 3rem;
    color: white;
    margin-bottom: 2rem;
    box-shadow: 0 20px 40px rgba(102, 126, 234, 0.15);
}

.forms-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.forms-subtitle {
    font-size: 1.15rem;
    margin-bottom: 1rem;
    opacity: 0.95;
}

.forms-description {
    font-size: 1.1rem;
    line-height: 1.6;
    opacity: 0.9;
}

.forms-illustration {
    font-size: 5rem;
    opacity: 0.2;
    text-align: center;
}

/* Form Cards */
.form-card {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    border: 1px solid #e2e8f0;
    height: 100%;
    display: flex;
    align-items: start;
    transition: all 0.3s ease;
}

.form-card:hover {
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    transform: translateY(-3px);
}

.form-card-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
    color: white;
    margin-right: 1.25rem;
    flex-shrink: 0;
}

.form-card-content {
    flex: 1;
}

.form-card-content h5 {
    color: #2d3748;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.form-card-content p {
    font-size: 0.95rem;
    margin-bottom: 0.75rem;
}

.form-meta {
    display: flex;
    gap: 1rem;
    font-size: 0.85rem;
    color: #718096;
}

/* HEA Portal Card */
.hea-portal-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 16px;
    padding: 2rem;
    color: white;
}

.hea-portal-card h4 {
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.hea-portal-card p {
    opacity: 0.9;
}

.hea-portal-card .btn-light {
    background: white;
    color: #667eea;
    border: none;
    font-weight: 600;
}

/* Tips Card */
.tips-card {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    border: 1px solid #e2e8f0;
}

.tips-card h5 {
    color: #2d3748;
    font-weight: 700;
}

.tip-item {
    display: flex;
    align-items: start;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 12px;
    height: 100%;
}

.tip-number {
    width: 35px;
    height: 35px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    margin-right: 1rem;
    flex-shrink: 0;
}

.tip-item h6 {
    color: #2d3748;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.tip-item p {
    color: #718096;
    font-size: 0.9rem;
    margin: 0;
}

/* Responsive */
@media (max-width: 768px) {
    .forms-header {
        padding: 2rem 1.5rem;
        text-align: center;
    }

    .forms-title {
        font-size: 1.8rem;
    }

    .forms-illustration {
        font-size: 3rem;
        margin-top: 1rem;
    }

    .form-card {
        flex-direction: column;
        text-align: center;
    }

    .form-card-icon {
        margin: 0 auto 1rem;
    }

    .form-meta {
        justify-content: center;
        flex-wrap: wrap;
    }
}
</style>
@endsection
