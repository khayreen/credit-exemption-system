@extends('layouts.app')

@section('content')
<div class="terms-container">
    <!-- Header Section -->
    <div class="terms-header">
        <div class="row align-items-center">
            <div class="col-lg-9">
                <h1 class="terms-title">Terms & Conditions</h1>
                <p class="terms-subtitle">
                    <i class="fas fa-university me-2"></i>
                    UiTM Credit Exemption Management System
                </p>
                <p class="terms-description">
                    Please read these terms and conditions carefully before using our credit exemption services.
                </p>
            </div>
            <div class="col-lg-3 text-end">
                <div class="terms-illustration">
                    <i class="fas fa-file-contract"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Navigation -->
    <div class="card mb-4" style="border-radius: 16px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.08);">
        <div class="card-body" style="padding: 1.5rem;">
            <h5 class="mb-3" style="color: #2d3748; font-weight: 600;">
                <i class="fas fa-list-ul me-2" style="color: #667eea;"></i>Quick Navigation
            </h5>
            <div class="row g-2">
                <div class="col-md-3 col-6">
                    <a href="#section-1" class="nav-link-card">
                        <i class="fas fa-info-circle"></i> Overview
                    </a>
                </div>
                <div class="col-md-3 col-6">
                    <a href="#section-2" class="nav-link-card">
                        <i class="fas fa-check-circle"></i> Eligibility
                    </a>
                </div>
                <div class="col-md-3 col-6">
                    <a href="#section-3" class="nav-link-card">
                        <i class="fas fa-file-upload"></i> Application
                    </a>
                </div>
                <div class="col-md-3 col-6">
                    <a href="#section-4" class="nav-link-card">
                        <i class="fas fa-graduation-cap"></i> Academic Policy
                    </a>
                </div>
                <div class="col-md-3 col-6">
                    <a href="#section-5" class="nav-link-card">
                        <i class="fas fa-balance-scale"></i> Rights
                    </a>
                </div>
                <div class="col-md-3 col-6">
                    <a href="#section-6" class="nav-link-card">
                        <i class="fas fa-shield-alt"></i> Privacy
                    </a>
                </div>
                <div class="col-md-3 col-6">
                    <a href="#section-7" class="nav-link-card">
                        <i class="fas fa-exclamation-triangle"></i> Disclaimer
                    </a>
                </div>
                <div class="col-md-3 col-6">
                    <a href="#section-8" class="nav-link-card">
                        <i class="fas fa-phone-alt"></i> Contact
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Last Updated -->
    <div class="alert alert-info mb-4" style="border-radius: 12px; border-left: 4px solid #38b2ac;">
        <i class="fas fa-calendar-check me-2"></i>
        <strong>Last Updated:</strong> December 27, 2025 | <strong>Effective Date:</strong> Semester I 2025/2026
    </div>

    <!-- Section 1: Overview -->
    <div class="terms-card" id="section-1">
        <div class="terms-card-header">
            <div class="icon-badge bg-primary">
                <i class="fas fa-info-circle"></i>
            </div>
            <div>
                <h3>1. Overview & Acceptance</h3>
                <p class="mb-0">Understanding the credit exemption system</p>
            </div>
        </div>
        <div class="terms-card-body">
            <p class="lead-text">By accessing and using the UiTM Credit Exemption Management System, you acknowledge that you have read, understood, and agree to be bound by these terms and conditions.</p>

            <div class="info-box">
                <h6><i class="fas fa-bullhorn me-2"></i>What is Credit Exemption?</h6>
                <p>Credit exemption allows students who have previously completed equivalent courses at diploma level to be exempted from retaking similar courses in their bachelor's degree program. This recognition is based on <strong>course content equivalency, academic performance, and HEA-approved criteria</strong>.</p>
            </div>

            <ul class="styled-list">
                <li>All information provided must be <strong>accurate, complete, and truthful</strong></li>
                <li>You consent to the university's verification of all submitted documents</li>
                <li>Misrepresentation or fraudulent information may result in <strong>application rejection and disciplinary action</strong></li>
                <li>These terms are subject to updates in accordance with UiTM academic regulations</li>
            </ul>
        </div>
    </div>

    <!-- Section 2: Eligibility Criteria -->
    <div class="terms-card" id="section-2">
        <div class="terms-card-header">
            <div class="icon-badge bg-success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div>
                <h3>2. Eligibility Criteria</h3>
                <p class="mb-0">Requirements for credit exemption qualification</p>
            </div>
        </div>
        <div class="terms-card-body">
            <p class="lead-text">Credit exemption eligibility is determined by the Higher Education Authority (HEA) based on strict academic criteria. All three conditions below must be met:</p>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="criteria-card">
                        <div class="criteria-number">1</div>
                        <h6>Course Match</h6>
                        <p>Diploma course must be listed in the approved equivalency database for your degree program</p>
                        <span class="criteria-badge"><i class="fas fa-book"></i> HEA Approved</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="criteria-card">
                        <div class="criteria-number">2</div>
                        <h6>Grade Requirement</h6>
                        <p>Minimum grade of <strong>C (2.00 GPA)</strong> or higher in the diploma course</p>
                        <span class="criteria-badge"><i class="fas fa-chart-line"></i> C or Above</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="criteria-card">
                        <div class="criteria-number">3</div>
                        <h6>Equivalency Match</h6>
                        <p>Course content similarity must be <strong>≥80%</strong> as determined by HEA assessment</p>
                        <span class="criteria-badge"><i class="fas fa-percentage"></i> ≥80% Match</span>
                    </div>
                </div>
            </div>

            <div class="warning-box">
                <h6><i class="fas fa-exclamation-triangle me-2"></i>Important Notice</h6>
                <ul class="mb-0">
                    <li>Grades below C (including C-, D+, D, F) do <strong>not qualify</strong> for exemption</li>
                    <li>Courses not listed in the HEA-endorsed equivalency database require special review by Academic Advisors and Resource Persons</li>
                    <li>External institution courses (non-UiTM) undergo additional verification processes</li>
                    <li>Final decisions are subject to faculty and HEA approval</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Section 3: Application Process -->
    <div class="terms-card" id="section-3">
        <div class="terms-card-header">
            <div class="icon-badge bg-warning">
                <i class="fas fa-file-upload"></i>
            </div>
            <div>
                <h3>3. Application Procedures & Responsibilities</h3>
                <p class="mb-0">Student obligations and submission requirements</p>
            </div>
        </div>
        <div class="terms-card-body">
            <h6 class="section-subtitle"><i class="fas fa-clipboard-check me-2"></i>Required Documents</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="document-item">
                        <i class="fas fa-file-pdf"></i>
                        <div>
                            <strong>Official Academic Transcript</strong>
                            <p>Must show course codes, names, credit hours, and grades. Scanned copies must be clear and legible for OCR processing.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="document-item">
                        <i class="fas fa-book-open"></i>
                        <div>
                            <strong>Course Syllabus (if required)</strong>
                            <p>Detailed course outline may be requested for courses not in the equivalency database. Resource Persons may contact external lecturers for verification.</p>
                        </div>
                    </div>
                </div>
            </div>

            <h6 class="section-subtitle"><i class="fas fa-user-check me-2"></i>Student Responsibilities</h6>
            <ul class="styled-list">
                <li><strong>Document Authenticity:</strong> All submitted documents must be genuine, unaltered, and officially issued by the awarding institution</li>
                <li><strong>Timely Submission:</strong> Applications must be submitted within the designated period for each semester (refer to Academic Calendar)</li>
                <li><strong>Complete Information:</strong> Provide accurate personal details, program information, campus, faculty, and previous institution data</li>
                <li><strong>Response to Queries:</strong> Respond promptly to any requests for additional information from Academic Advisors or Resource Persons</li>
                <li><strong>Status Monitoring:</strong> Regularly check application status through the dashboard for updates and required actions</li>
            </ul>

            <div class="info-box">
                <h6><i class="fas fa-robot me-2"></i>OCR Technology & Verification</h6>
                <p>Our system uses <strong>Optical Character Recognition (OCR)</strong> powered by Google Cloud Vision API to automatically extract course information from transcripts. While this technology is highly accurate, all extracted data undergoes human verification by academic staff. Students are responsible for reviewing OCR-extracted courses for accuracy.</p>
            </div>
        </div>
    </div>

    <!-- Section 4: Academic Policies -->
    <div class="terms-card" id="section-4">
        <div class="terms-card-header">
            <div class="icon-badge bg-info">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div>
                <h3>4. Academic Policies & Regulations</h3>
                <p class="mb-0">University regulations governing credit exemptions</p>
            </div>
        </div>
        <div class="terms-card-body">
            <h6 class="section-subtitle"><i class="fas fa-gavel me-2"></i>UiTM Credit Exemption Policies (2025)</h6>
            <ul class="styled-list">
                <li><strong>Program Eligibility:</strong> Credit exemptions are currently available for five (5) Bachelor's degree programs in Computer Science and Information Systems (CDCS230, CDCS251, CDCS253, CDCS255, CDCS266)</li>
                <li><strong>Maximum Credit Exemption:</strong> The total exempted credit hours must comply with UiTM Senate regulations and faculty-specific limits</li>
                <li><strong>Exemption Validity:</strong> Approved exemptions are valid only for the program and semester specified in the endorsement letter</li>
                <li><strong>Transfer Students:</strong> Students transferring from other UiTM campuses must reapply for exemption verification at the new campus</li>
                <li><strong>Course Retakes:</strong> If an exempted course is voluntarily retaken, the new grade will replace the exemption status</li>
                <li><strong>GPA Calculation:</strong> Exempted courses typically do not contribute to GPA calculation (marked as "Exempt" or "Ex" on transcripts)</li>
            </ul>

            <h6 class="section-subtitle"><i class="fas fa-clock me-2"></i>Processing Timeline</h6>
            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <div class="timeline-card">
                        <div class="timeline-step">1</div>
                        <strong>Submission</strong>
                        <p>Student submits application</p>
                        <span class="time-badge">Day 0</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="timeline-card">
                        <div class="timeline-step">2</div>
                        <strong>Academic Advisor</strong>
                        <p>Initial review & validation</p>
                        <span class="time-badge">3-5 days</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="timeline-card">
                        <div class="timeline-step">3</div>
                        <strong>Resource Person</strong>
                        <p>Course content verification</p>
                        <span class="time-badge">5-10 days</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="timeline-card">
                        <div class="timeline-step">4</div>
                        <strong>HEA Approval</strong>
                        <p>Final endorsement</p>
                        <span class="time-badge">14-21 days</span>
                    </div>
                </div>
            </div>
            <p class="text-muted small"><i class="fas fa-info-circle me-1"></i>Timelines are estimates and may vary depending on application volume and complexity. Peak periods (start of semester) may experience longer processing times.</p>
        </div>
    </div>

    <!-- Section 5: Student Rights & Appeal -->
    <div class="terms-card" id="section-5">
        <div class="terms-card-header">
            <div class="icon-badge" style="background: linear-gradient(135deg, #f093fb, #f5576c);">
                <i class="fas fa-balance-scale"></i>
            </div>
            <div>
                <h3>5. Student Rights & Appeal Process</h3>
                <p class="mb-0">Your rights and recourse options</p>
            </div>
        </div>
        <div class="terms-card-body">
            <h6 class="section-subtitle"><i class="fas fa-hand-holding-heart me-2"></i>Your Rights</h6>
            <ul class="styled-list">
                <li><strong>Transparency:</strong> Right to understand the evaluation criteria and decision-making process</li>
                <li><strong>Fair Review:</strong> All applications are reviewed objectively based on HEA-approved criteria</li>
                <li><strong>Status Updates:</strong> Right to track application progress through the online system</li>
                <li><strong>Feedback:</strong> Right to request clarification on rejected courses</li>
                <li><strong>Appeal:</strong> Right to appeal decisions within the specified timeframe (14 days from notification)</li>
            </ul>

            <div class="info-box">
                <h6><i class="fas fa-redo me-2"></i>Appeal Procedure</h6>
                <ol class="mb-0">
                    <li>Submit a written appeal to your Academic Advisor within <strong>14 days</strong> of receiving the decision</li>
                    <li>Provide supporting evidence (updated syllabus, course descriptions, lecturer recommendations)</li>
                    <li>Appeals are reviewed by a panel consisting of the Program Coordinator, Resource Person, and HEA representative</li>
                    <li>Final appeal decisions are communicated within <strong>21 working days</strong></li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Section 6: Data Privacy & Security -->
    <div class="terms-card" id="section-6">
        <div class="terms-card-header">
            <div class="icon-badge" style="background: linear-gradient(135deg, #4facfe, #00f2fe);">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div>
                <h3>6. Data Privacy & Security</h3>
                <p class="mb-0">How we protect your information</p>
            </div>
        </div>
        <div class="terms-card-body">
            <h6 class="section-subtitle"><i class="fas fa-lock me-2"></i>Information We Collect</h6>
            <ul class="styled-list">
                <li><strong>Personal Data:</strong> Name, IC number, matric number, contact information, campus, faculty, program</li>
                <li><strong>Academic Records:</strong> Transcripts, course codes, grades, credit hours, previous institution details</li>
                <li><strong>Application Data:</strong> Submission dates, status updates, decisions, justifications</li>
                <li><strong>System Logs:</strong> Login activities, file uploads, document access (for security and audit purposes)</li>
            </ul>

            <h6 class="section-subtitle"><i class="fas fa-user-shield me-2"></i>Data Protection Measures</h6>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <div class="security-item">
                        <i class="fas fa-key"></i>
                        <div>
                            <strong>Two-Factor Authentication (2FA)</strong>
                            <p>Google Authenticator required for account security</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="security-item">
                        <i class="fas fa-fingerprint"></i>
                        <div>
                            <strong>UUID-Based Encryption</strong>
                            <p>All records use unique identifiers for enhanced security</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="security-item">
                        <i class="fas fa-file-signature"></i>
                        <div>
                            <strong>Digital Signatures</strong>
                            <p>All uploaded documents are cryptographically signed (SHA256)</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="security-item">
                        <i class="fas fa-database"></i>
                        <div>
                            <strong>Secure Storage</strong>
                            <p>Data stored in encrypted databases with access controls</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="warning-box">
                <h6><i class="fas fa-user-secret me-2"></i>Your Privacy Responsibilities</h6>
                <ul class="mb-0">
                    <li>Keep your login credentials and 2FA codes confidential</li>
                    <li>Do not share your account with others</li>
                    <li>Log out after each session, especially on shared computers</li>
                    <li>Report any suspected unauthorized access immediately to HEA</li>
                </ul>
            </div>

            <p class="text-muted small mt-3"><i class="fas fa-gavel me-1"></i>UiTM complies with the Personal Data Protection Act 2010 (PDPA). Your data is used solely for academic purposes and will not be shared with third parties without your consent, except as required by law.</p>
        </div>
    </div>

    <!-- Section 7: Disclaimer & Limitations -->
    <div class="terms-card" id="section-7">
        <div class="terms-card-header">
            <div class="icon-badge" style="background: linear-gradient(135deg, #fa709a, #fee140);">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div>
                <h3>7. Disclaimer & Limitations</h3>
                <p class="mb-0">Important legal information</p>
            </div>
        </div>
        <div class="terms-card-body">
            <ul class="styled-list">
                <li><strong>No Guarantee of Approval:</strong> Submission of an application does not guarantee credit exemption approval. All decisions are based on academic merit and HEA criteria</li>
                <li><strong>System Availability:</strong> While we strive for 24/7 availability, the system may be temporarily unavailable for maintenance. Planned downtime will be announced in advance</li>
                <li><strong>OCR Accuracy:</strong> OCR technology is provided as a convenience. The university is not liable for extraction errors. Students must verify all extracted information</li>
                <li><strong>External Links:</strong> Links to external websites (Academic Calendar, institution databases) are provided for convenience. UiTM is not responsible for external content</li>
                <li><strong>Policy Changes:</strong> UiTM reserves the right to modify credit exemption policies in accordance with Senate decisions. Changes will be communicated through official channels</li>
                <li><strong>Force Majeure:</strong> The university is not liable for delays or failures due to circumstances beyond reasonable control (natural disasters, system failures, etc.)</li>
            </ul>

            <div class="info-box">
                <h6><i class="fas fa-scroll me-2"></i>Governing Law</h6>
                <p class="mb-0">These terms and conditions are governed by the laws of Malaysia and UiTM Academic Regulations. Any disputes shall be resolved in accordance with UiTM's established grievance procedures.</p>
            </div>
        </div>
    </div>

    <!-- Section 8: Contact Information -->
    <div class="terms-card contact-section" id="section-8">
        <div class="terms-card-header">
            <div class="icon-badge" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                <i class="fas fa-phone-alt"></i>
            </div>
            <div>
                <h3>8. Contact & Support</h3>
                <p class="mb-0">Get help with your credit exemption application</p>
            </div>
        </div>
        <div class="terms-card-body">
            <p class="lead-text">For questions, assistance, or concerns regarding credit exemption, please contact the appropriate office:</p>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div>
                            <h6>Academic Advisor</h6>
                            <p class="text-muted mb-1">For application queries and initial consultation</p>
                            <p class="mb-0"><strong>Contact through your faculty's Academic Office</strong></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <div>
                            <h6>Higher Education Authority (HEA)</h6>
                            <p class="text-muted mb-1">For policy inquiries and final approvals</p>
                            <p class="mb-0"><strong>hea@uitm.edu.my</strong></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <div>
                            <h6>Technical Support</h6>
                            <p class="text-muted mb-1">For system issues and login problems</p>
                            <p class="mb-0"><strong>helpdesk@uitm.edu.my</strong></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div>
                            <h6>Academic Calendar</h6>
                            <p class="text-muted mb-1">Check important dates and deadlines</p>
                            <p class="mb-0">
                                <a href="{{ \App\Models\SystemSetting::get('academic_calendar_url', 'https://uitm.edu.my/index.php/en/academic-calendar') }}" target="_blank" style="color: #667eea; font-weight: 600;">
                                    View Calendar <i class="fas fa-external-link-alt ms-1"></i>
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Acknowledgment Section -->
    <div class="acknowledgment-card">
        <div class="d-flex align-items-start">
            <div style="font-size: 3rem; color: #48bb78; margin-right: 1.5rem;">
                <i class="fas fa-check-circle"></i>
            </div>
            <div style="flex: 1;">
                <h4 style="color: #2d3748; font-weight: 700; margin-bottom: 1rem;">Acknowledgment of Terms</h4>
                <p style="color: #4a5568; font-size: 1.1rem; line-height: 1.7; margin-bottom: 0;">
                    By continuing to use the UiTM Credit Exemption Management System, you acknowledge that you have read, understood, and agreed to these terms and conditions. If you do not agree with any part of these terms, please refrain from using the system and contact your Academic Advisor for alternative procedures.
                </p>
            </div>
        </div>
    </div>

    <!-- Back to Dashboard Button -->
    <div class="text-center mt-4 mb-5">
        <a href="{{ route('student.dashboard') }}" class="btn btn-lg" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border-radius: 12px; padding: 1rem 3rem; font-weight: 600; border: none; box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3); transition: all 0.3s ease;">
            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
        </a>
    </div>
</div>

<!-- Scroll to Top Button -->
<button id="scrollToTopBtn" class="scroll-to-top-btn" aria-label="Scroll to top">
    <i class="fas fa-arrow-up"></i>
</button>

<style>
/* Terms Container */
.terms-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 1rem;
}

/* Header Section */
.terms-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    padding: 3rem;
    color: white;
    margin-bottom: 2rem;
    box-shadow: 0 20px 40px rgba(102, 126, 234, 0.15);
}

.terms-title {
    font-size: 2.75rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.terms-subtitle {
    font-size: 1.25rem;
    margin-bottom: 1rem;
    opacity: 0.95;
}

.terms-description {
    font-size: 1.15rem;
    line-height: 1.6;
    opacity: 0.9;
}

.terms-illustration {
    font-size: 6rem;
    opacity: 0.2;
    text-align: center;
}

/* Quick Navigation */
.nav-link-card {
    display: flex;
    align-items: center;
    padding: 0.75rem 1rem;
    background: #f8fafc;
    border-radius: 10px;
    color: #4a5568;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.nav-link-card:hover {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(102, 126, 234, 0.3);
}

.nav-link-card i {
    margin-right: 0.5rem;
    font-size: 1rem;
}

/* Terms Cards */
.terms-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.08);
    margin-bottom: 2rem;
    overflow: hidden;
    border: 1px solid #e2e8f0;
}

.terms-card-header {
    display: flex;
    align-items: center;
    padding: 2rem;
    background: linear-gradient(135deg, #f7fafc, #edf2f7);
    border-bottom: 2px solid #e2e8f0;
}

.icon-badge {
    width: 60px;
    height: 60px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
    color: white;
    margin-right: 1.5rem;
    flex-shrink: 0;
}

.icon-badge.bg-primary { background: linear-gradient(135deg, #667eea, #764ba2); }
.icon-badge.bg-success { background: linear-gradient(135deg, #48bb78, #38a169); }
.icon-badge.bg-warning { background: linear-gradient(135deg, #ed8936, #dd6b20); }
.icon-badge.bg-info { background: linear-gradient(135deg, #38b2ac, #319795); }

.terms-card-header h3 {
    color: #2d3748;
    font-weight: 700;
    font-size: 1.5rem;
    margin-bottom: 0.25rem;
}

.terms-card-header p {
    color: #718096;
    font-size: 0.95rem;
}

.terms-card-body {
    padding: 2.5rem;
}

/* Content Styles */
.lead-text {
    font-size: 1.15rem;
    color: #2d3748;
    line-height: 1.8;
    margin-bottom: 1.5rem;
    font-weight: 500;
}

.section-subtitle {
    color: #2d3748;
    font-weight: 700;
    margin-top: 2rem;
    margin-bottom: 1rem;
    font-size: 1.2rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #e2e8f0;
}

.styled-list {
    list-style: none;
    padding-left: 0;
}

.styled-list li {
    padding-left: 2rem;
    margin-bottom: 1rem;
    color: #4a5568;
    line-height: 1.7;
    position: relative;
}

.styled-list li::before {
    content: "\f00c";
    font-family: "Font Awesome 6 Free";
    font-weight: 900;
    position: absolute;
    left: 0;
    color: #48bb78;
    font-size: 0.9rem;
}

/* Info & Warning Boxes */
.info-box {
    background: linear-gradient(135deg, #ebf8ff, #e6fffa);
    border-left: 4px solid #38b2ac;
    border-radius: 12px;
    padding: 1.5rem;
    margin: 1.5rem 0;
}

.info-box h6 {
    color: #2c7a7b;
    font-weight: 700;
    margin-bottom: 0.75rem;
}

.info-box p {
    color: #234e52;
    margin-bottom: 0;
    line-height: 1.7;
}

.warning-box {
    background: linear-gradient(135deg, #fffbeb, #fef3c7);
    border-left: 4px solid #f59e0b;
    border-radius: 12px;
    padding: 1.5rem;
    margin: 1.5rem 0;
}

.warning-box h6 {
    color: #92400e;
    font-weight: 700;
    margin-bottom: 0.75rem;
}

.warning-box ul {
    margin-bottom: 0;
    padding-left: 1.5rem;
}

.warning-box li {
    color: #78350f;
    margin-bottom: 0.5rem;
}

/* Criteria Cards */
.criteria-card {
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: 15px;
    padding: 1.5rem;
    text-align: center;
    height: 100%;
    transition: all 0.3s ease;
}

.criteria-card:hover {
    border-color: #667eea;
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.15);
}

.criteria-number {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: 700;
    margin: 0 auto 1rem;
}

.criteria-card h6 {
    color: #2d3748;
    font-weight: 700;
    margin-bottom: 0.75rem;
}

.criteria-card p {
    color: #718096;
    font-size: 0.95rem;
    margin-bottom: 1rem;
    line-height: 1.6;
}

.criteria-badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    background: linear-gradient(135deg, #f0f4ff, #e8eeff);
    color: #667eea;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

/* Timeline Cards */
.timeline-card {
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 1.25rem;
    text-align: center;
    height: 100%;
}

.timeline-step {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    margin: 0 auto 0.75rem;
}

.timeline-card strong {
    display: block;
    color: #2d3748;
    font-size: 1rem;
    margin-bottom: 0.5rem;
}

.timeline-card p {
    color: #718096;
    font-size: 0.9rem;
    margin-bottom: 0.75rem;
}

.time-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    background: #edf2f7;
    color: #4a5568;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 600;
}

/* Document Items */
.document-item {
    display: flex;
    align-items: start;
    padding: 1.25rem;
    background: #f8fafc;
    border-radius: 12px;
    border: 2px solid #e2e8f0;
}

.document-item i {
    font-size: 2rem;
    color: #667eea;
    margin-right: 1rem;
    flex-shrink: 0;
    margin-top: 0.25rem;
}

.document-item strong {
    display: block;
    color: #2d3748;
    font-size: 1.05rem;
    margin-bottom: 0.5rem;
}

.document-item p {
    color: #718096;
    font-size: 0.95rem;
    margin: 0;
    line-height: 1.6;
}

/* Security Items */
.security-item {
    display: flex;
    align-items: center;
    padding: 1.25rem;
    background: #f8fafc;
    border-radius: 12px;
    border: 2px solid #e2e8f0;
}

.security-item i {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin-right: 1rem;
    flex-shrink: 0;
}

.security-item strong {
    display: block;
    color: #2d3748;
    font-size: 1rem;
    margin-bottom: 0.25rem;
}

.security-item p {
    color: #718096;
    font-size: 0.9rem;
    margin: 0;
}

/* Contact Cards */
.contact-card {
    display: flex;
    align-items: start;
    padding: 1.5rem;
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: 15px;
    transition: all 0.3s ease;
    height: 100%;
}

.contact-card:hover {
    border-color: #667eea;
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.1);
    transform: translateY(-3px);
}

.contact-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
    margin-right: 1.25rem;
    flex-shrink: 0;
}

.contact-card h6 {
    color: #2d3748;
    font-weight: 700;
    margin-bottom: 0.5rem;
    font-size: 1.1rem;
}

/* Acknowledgment Card */
.acknowledgment-card {
    background: linear-gradient(135deg, #f0fdf4, #dcfce7);
    border: 2px solid #86efac;
    border-radius: 20px;
    padding: 2.5rem;
    margin-top: 3rem;
}

/* Contact Section Special Styling */
.contact-section {
    background: linear-gradient(135deg, #f7fafc, #edf2f7);
}

/* Responsive Design */
@media (max-width: 768px) {
    .terms-header {
        padding: 2rem 1.5rem;
        text-align: center;
    }

    .terms-title {
        font-size: 2rem;
    }

    .terms-illustration {
        font-size: 4rem;
        margin-top: 1rem;
    }

    .terms-card-header {
        flex-direction: column;
        text-align: center;
    }

    .icon-badge {
        margin-right: 0;
        margin-bottom: 1rem;
    }

    .terms-card-body {
        padding: 1.5rem;
    }

    .nav-link-card {
        font-size: 0.9rem;
        padding: 0.6rem 0.8rem;
    }
}

/* Scroll to Top Button */
.scroll-to-top-btn {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 55px;
    height: 55px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border: none;
    border-radius: 50%;
    font-size: 1.5rem;
    cursor: pointer;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    z-index: 1000;
    opacity: 0;
    visibility: hidden;
    transform: translateY(20px);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.scroll-to-top-btn.show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.scroll-to-top-btn:hover {
    background: linear-gradient(135deg, #5a67d8, #6b46c1);
    transform: translateY(-5px);
    box-shadow: 0 12px 35px rgba(102, 126, 234, 0.5);
}

.scroll-to-top-btn:active {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
}

.scroll-to-top-btn i {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
}

/* Print Styles */
@media print {
    .terms-header,
    .nav-link-card,
    .btn,
    .scroll-to-top-btn {
        display: none;
    }

    .terms-card {
        box-shadow: none;
        border: 1px solid #ccc;
        page-break-inside: avoid;
    }
}

/* Mobile Responsive for Scroll Button */
@media (max-width: 768px) {
    .scroll-to-top-btn {
        bottom: 20px;
        right: 20px;
        width: 50px;
        height: 50px;
        font-size: 1.3rem;
    }
}
</style>

<script>
// Scroll to Top Functionality
document.addEventListener('DOMContentLoaded', function() {
    const scrollToTopBtn = document.getElementById('scrollToTopBtn');

    // Show/hide button based on scroll position
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            scrollToTopBtn.classList.add('show');
        } else {
            scrollToTopBtn.classList.remove('show');
        }
    });

    // Smooth scroll to top when clicked
    scrollToTopBtn.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    // Smooth scroll for quick navigation links
    document.querySelectorAll('.nav-link-card').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);

            if (targetElement) {
                const offsetTop = targetElement.offsetTop - 20; // 20px offset from top
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        });
    });
});
</script>
@endsection
