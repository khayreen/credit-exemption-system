@extends('layouts.app')

@section('content')
<div class="terms-container">
    <!-- Header Section -->
    <div class="terms-header">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="terms-title">Terms & Conditions</h1>
                <p class="terms-subtitle">
                    <i class="fas fa-university me-2"></i>
                    UiTM Credit Exemption Management System
                </p>
                <p class="terms-description">
                    Please read these terms and conditions carefully before using our credit exemption services.
                </p>
            </div>
            <div class="col-lg-4 text-end">
                <div class="terms-illustration">
                    <i class="fas fa-file-contract"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Terms Content -->
    <div class="card terms-card">
        <div class="card-body">
            <div class="terms-content">
                
                <!-- Last Updated -->
                <div class="alert alert-info mb-4">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Last Updated:</strong> {{ \Carbon\Carbon::now()->format('F d, Y') }}
                </div>

                <!-- Dynamic Terms Content -->
                <div class="terms-section">
                    <div class="terms-content-dynamic">
                        @php
                            $termsContent = \App\Models\SystemSetting::get('terms_and_conditions', 
                                'Terms and conditions content is not available at the moment. Please contact the administration.');
                            // Convert line breaks to HTML
                            $termsContent = nl2br(e($termsContent));
                        @endphp
                        
                        {!! $termsContent !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Terms Container */
.terms-container {
    max-width: 1000px;
    margin: 0 auto;
}

/* Header Section */
.terms-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    padding: 3rem;
    color: white;
    margin-bottom: 2rem;
    box-shadow: 0 20px 40px rgba(102, 126, 234, 0.1);
}

.terms-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.terms-subtitle {
    font-size: 1.2rem;
    margin-bottom: 1rem;
    opacity: 0.9;
}

.terms-description {
    font-size: 1.1rem;
    line-height: 1.6;
    opacity: 0.9;
}

.terms-illustration {
    font-size: 8rem;
    opacity: 0.2;
    text-align: center;
}

/* Terms Card */
.terms-card {
    border-radius: 20px;
    border: none;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    margin-bottom: 2rem;
}

/* Content Sections */
.terms-content {
    padding: 2rem;
    line-height: 1.7;
}

.terms-section {
    margin-bottom: 3rem;
    padding-bottom: 2rem;
    border-bottom: 2px solid #e2e8f0;
}

.terms-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.terms-section h3 {
    color: #2d3748;
    font-weight: 700;
    margin-bottom: 1.5rem;
    font-size: 1.5rem;
    display: flex;
    align-items: center;
}

.terms-section h3 i {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    margin-right: 1rem;
}

.subsection {
    margin-bottom: 2rem;
}

.subsection h4 {
    color: #4a5568;
    font-weight: 600;
    margin-bottom: 1rem;
    font-size: 1.2rem;
}

.terms-section ul {
    margin-left: 1.5rem;
    margin-bottom: 1rem;
}

.terms-section li {
    margin-bottom: 0.75rem;
    color: #4a5568;
}

.terms-section p {
    color: #4a5568;
    margin-bottom: 1rem;
}

/* Contact Section */
.contact-section {
    background: linear-gradient(135deg, #f7fafc, #edf2f7);
    border-radius: 15px;
    padding: 2rem;
    border: none !important;
}

.contact-info {
    margin-top: 1.5rem;
}

.contact-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    background: white;
    border-radius: 10px;
    margin-bottom: 1rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.contact-item i {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
}

.contact-item strong {
    color: #2d3748;
    font-size: 1.1rem;
    display: block;
    margin-bottom: 0.25rem;
}

.contact-item p {
    color: #667eea;
    margin: 0;
    font-weight: 600;
}

/* Acknowledgment Section */
.acknowledgment-section {
    margin-top: 2rem;
}

.acknowledgment-section .alert {
    border-radius: 15px;
    border: none;
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    color: #065f46;
    padding: 2rem;
}

.acknowledgment-section h4 {
    margin-bottom: 1rem;
    font-weight: 700;
}

/* Action Buttons */
.terms-actions {
    margin-top: 2rem;
    padding: 2rem;
    background: #f7fafc;
    border-radius: 15px;
}

.terms-actions .btn {
    padding: 1rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.terms-actions .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

/* Print Styles */
@media print {
    .terms-header,
    .terms-actions {
        display: none;
    }
    
    .terms-card {
        box-shadow: none;
        border: 1px solid #ccc;
    }
    
    .terms-section {
        page-break-inside: avoid;
    }
}

/* Dynamic Terms Content */
.terms-content-dynamic {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #2d3748;
}

.terms-content-dynamic p {
    margin-bottom: 1.5rem;
}

.terms-content-dynamic strong {
    color: #1a202c;
    font-weight: 600;
}

/* Contact Section Enhancement */
.contact-section {
    border-top: 3px solid #667eea;
    margin-top: 2rem;
    padding-top: 2rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .terms-header {
        padding: 2rem;
        text-align: center;
    }
    
    .terms-title {
        font-size: 2rem;
    }
    
    .terms-illustration {
        font-size: 4rem;
        margin-top: 1rem;
    }
    
    .terms-content {
        padding: 1.5rem;
    }
    
    .terms-actions {
        text-align: center;
    }
    
    .terms-actions .col-md-6 {
        margin-bottom: 1rem;
    }
    
    .terms-content-dynamic {
        font-size: 1rem;
        line-height: 1.6;
    }
}
</style>
@endsection