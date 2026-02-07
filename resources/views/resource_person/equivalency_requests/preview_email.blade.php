@extends('layouts.app')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap');

    :root {
        --uitm-blue: #1e3a8a;
        --uitm-blue-light: #3b82f6;
        --uitm-amber: #f59e0b;
        --industrial-dark: #0f172a;
        --industrial-gray: #334155;
        --industrial-light: #f1f5f9;
        --success-color: #059669;
        --danger-color: #dc2626;
        --warning-color: #ea580c;
        --info-color: #0d9488;
    }

    .email-preview-page {
        font-family: 'IBM Plex Sans', sans-serif;
        background: var(--industrial-light);
        min-height: 100vh;
        padding: 2rem 0;
    }

    /* Page Header */
    .page-header {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--industrial-dark) 100%);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 300px;
        height: 100%;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='4'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    .page-header h1 {
        color: #fff;
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .page-header h1 i {
        color: var(--uitm-amber);
    }

    .page-header p {
        color: rgba(255, 255, 255, 0.8);
        margin: 0;
        font-size: 1rem;
    }

    .header-actions {
        position: absolute;
        top: 50%;
        right: 2rem;
        transform: translateY(-50%);
    }

    .btn-back {
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
    }

    .btn-back:hover {
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
    }

    /* Editor Card */
    .editor-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        position: sticky;
        top: 1.5rem;
    }

    .editor-card-header {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--uitm-blue-light) 100%);
        padding: 1.25rem 1.5rem;
        border-bottom: none;
    }

    .editor-card-header h5 {
        color: #fff;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .editor-card-body {
        padding: 1.5rem;
    }

    /* Form Elements */
    .form-label {
        font-weight: 600;
        color: var(--industrial-dark);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
    }

    .form-label .text-danger {
        color: var(--danger-color) !important;
    }

    .form-control {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        font-family: 'IBM Plex Sans', sans-serif;
        transition: all 0.2s ease;
    }

    .form-control:focus {
        border-color: var(--uitm-blue);
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
        outline: none;
    }

    .form-control[readonly] {
        background: var(--industrial-light);
        color: var(--industrial-gray);
    }

    textarea.form-control {
        font-family: 'IBM Plex Sans', sans-serif;
        line-height: 1.6;
        resize: vertical;
    }

    .form-text {
        font-size: 0.8rem;
        color: var(--industrial-gray);
        margin-top: 0.5rem;
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
    }

    .form-text i {
        color: var(--info-color);
        margin-top: 2px;
    }

    .text-danger.small {
        color: var(--danger-color) !important;
        font-size: 0.8rem;
    }

    /* Input Groups */
    .input-group {
        margin-bottom: 0.75rem;
    }

    .input-group .input-group-text {
        background: var(--industrial-light);
        border: 2px solid #e2e8f0;
        border-right: none;
        border-radius: 10px 0 0 10px;
        color: var(--uitm-blue);
    }

    .input-group .form-control {
        border-radius: 0 10px 10px 0;
    }

    /* Character Counter */
    .char-counter {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.8rem;
        color: var(--industrial-gray);
        text-align: right;
        margin-top: 0.5rem;
    }

    .char-counter.warning {
        color: var(--warning-color);
    }

    .char-counter.danger {
        color: var(--danger-color);
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        margin-top: 1.5rem;
    }

    .btn-send {
        background: linear-gradient(135deg, var(--success-color) 0%, #10b981 100%);
        color: #fff;
        border: none;
        padding: 1rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 1rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
        box-shadow: 0 4px 14px 0 rgba(5, 150, 105, 0.3);
    }

    .btn-send:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px 0 rgba(5, 150, 105, 0.4);
        color: #fff;
    }

    .btn-cancel {
        background: #fff;
        color: var(--industrial-gray);
        border: 2px solid #e2e8f0;
        padding: 0.875rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.95rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-cancel:hover {
        background: var(--industrial-light);
        border-color: var(--industrial-gray);
        color: var(--industrial-dark);
    }

    /* Preview Card */
    .preview-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .preview-card-header {
        background: var(--industrial-light);
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .preview-card-header h5 {
        color: var(--industrial-dark);
        font-weight: 600;
        margin: 0 0 0.25rem 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .preview-card-header h5 i {
        color: var(--uitm-blue);
    }

    .preview-card-header small {
        color: var(--industrial-gray);
        font-size: 0.8rem;
    }

    .preview-card-body {
        padding: 0;
    }

    /* Email Preview Container */
    .email-preview-wrapper {
        background: linear-gradient(180deg, #e2e8f0 0%, var(--industrial-light) 100%);
        padding: 1.5rem;
    }

    .email-preview-container {
        max-width: 600px;
        margin: 0 auto;
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    }

    /* Email Header */
    .email-header {
        background: linear-gradient(135deg, var(--uitm-blue) 0%, var(--industrial-dark) 100%);
        color: #fff;
        padding: 2rem 1.5rem;
        text-align: center;
        position: relative;
    }

    .email-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--uitm-amber), #fbbf24, var(--uitm-amber));
    }

    .email-header-icon {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 1.5rem;
    }

    .email-header h2 {
        margin: 0 0 0.5rem 0;
        font-size: 1.25rem;
        font-weight: 700;
    }

    .email-header p {
        margin: 0;
        opacity: 0.9;
        font-size: 0.9rem;
    }

    /* Email Body */
    .email-body {
        padding: 2rem 1.5rem;
    }

    .email-greeting {
        font-size: 1rem;
        color: var(--industrial-dark);
        margin-bottom: 1.25rem;
    }

    .email-greeting strong {
        color: var(--uitm-blue);
    }

    /* Message Preview Box */
    .message-preview-box {
        background: var(--industrial-light);
        border-left: 4px solid var(--uitm-blue);
        padding: 1.25rem;
        margin: 1.25rem 0;
        border-radius: 0 8px 8px 0;
        white-space: pre-wrap;
        line-height: 1.7;
        color: var(--industrial-gray);
        font-size: 0.95rem;
    }

    /* Course Info Section */
    .course-info-section {
        margin: 1.5rem 0;
    }

    .course-info-header {
        color: var(--uitm-blue);
        font-size: 1rem;
        font-weight: 700;
        margin: 0 0 1rem 0;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--uitm-blue);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .course-info-table {
        width: 100%;
    }

    .course-info-table tr td {
        padding: 0.625rem 0;
        vertical-align: top;
    }

    .course-info-table tr td:first-child {
        font-weight: 600;
        color: var(--industrial-gray);
        width: 40%;
        font-size: 0.9rem;
    }

    .course-info-table tr td:last-child {
        color: var(--industrial-dark);
        font-size: 0.9rem;
    }

    .course-code {
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 700;
        color: var(--uitm-blue);
        font-size: 1rem;
    }

    /* CTA Section */
    .email-cta-section {
        text-align: center;
        margin: 2rem 0;
        padding: 1.5rem;
        background: rgba(59, 130, 246, 0.05);
        border-radius: 12px;
        border: 1px solid rgba(59, 130, 246, 0.1);
    }

    .email-cta-section p {
        margin: 0 0 1rem 0;
        font-weight: 600;
        color: var(--industrial-dark);
        font-size: 0.95rem;
    }

    .email-cta-button {
        display: inline-block;
        padding: 1rem 2rem;
        background: linear-gradient(135deg, var(--success-color) 0%, #10b981 100%);
        color: #fff;
        text-decoration: none;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.95rem;
        box-shadow: 0 4px 14px rgba(5, 150, 105, 0.3);
    }

    /* Warning Box */
    .email-warning-box {
        background: rgba(245, 158, 11, 0.1);
        border: 2px solid var(--uitm-amber);
        border-radius: 10px;
        padding: 1rem 1.25rem;
        margin: 1.5rem 0;
        text-align: center;
    }

    .email-warning-box strong {
        color: #92400e;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        font-size: 0.95rem;
    }

    .email-warning-box .expiry-date {
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 700;
        color: var(--industrial-dark);
    }

    /* Email Footer */
    .email-footer {
        background: var(--industrial-light);
        text-align: center;
        padding: 1.25rem 1.5rem;
        border-top: 1px solid #e2e8f0;
    }

    .email-footer p {
        margin: 0.25rem 0;
        font-size: 0.75rem;
        color: var(--industrial-gray);
    }

    .email-footer p:first-child {
        font-weight: 600;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .editor-card {
            position: static;
        }
    }

    @media (max-width: 992px) {
        .page-header {
            padding: 1.5rem;
        }

        .header-actions {
            position: static;
            transform: none;
            margin-top: 1rem;
        }
    }

    @media (max-width: 768px) {
        .email-preview-page {
            padding: 1rem 0;
        }

        .editor-card-body,
        .email-body {
            padding: 1rem;
        }

        .email-preview-wrapper {
            padding: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="email-preview-page">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1><i class="fas fa-envelope"></i>Preview & Edit Syllabus Request</h1>
            <p>Review the email content and make any necessary changes before sending</p>
            <div class="header-actions">
                <a href="{{ route('resource_person.equivalency_requests.review', $request) }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i>Back to Request
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Email Editor Form (Left Side) -->
            <div class="col-lg-5 mb-4">
                <div class="editor-card">
                    <div class="editor-card-header">
                        <h5><i class="fas fa-edit"></i>Edit Email Content</h5>
                    </div>
                    <div class="editor-card-body">
                        <form method="POST" action="{{ route('resource_person.equivalency_requests.send_email', $request) }}" id="emailForm">
                            @csrf

                            <!-- Recipient Info (Read-only) -->
                            <div class="mb-4">
                                <label class="form-label">Recipient</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" value="{{ $emailData['lecturer_name'] }}" readonly>
                                </div>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control" value="{{ $emailData['lecturer_email'] }}" readonly>
                                </div>
                            </div>

                            <!-- Editable Greeting -->
                            <div class="mb-4">
                                <label for="email_greeting" class="form-label">
                                    Email Greeting <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="email_greeting" id="email_greeting" class="form-control"
                                       value="{{ old('email_greeting', 'Dear ' . $emailData['lecturer_name']) }}"
                                       required maxlength="200"
                                       placeholder="e.g., Dear Dr. {{ explode(' ', $emailData['lecturer_name'])[0] }}">
                                @error('email_greeting')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <i class="fas fa-lightbulb"></i>
                                    <span>Examples: "Dear Dr. Smith,", "Dear Prof. Ahmad,"</span>
                                </div>
                            </div>

                            <!-- Editable Subject -->
                            <div class="mb-4">
                                <label for="email_subject" class="form-label">
                                    Email Subject <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="email_subject" id="email_subject" class="form-control"
                                       value="{{ old('email_subject', $emailData['subject']) }}" required maxlength="255">
                                @error('email_subject')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Editable Message -->
                            <div class="mb-4">
                                <label for="email_message" class="form-label">
                                    Custom Message <span class="text-danger">*</span>
                                </label>
                                <textarea name="email_message" id="email_message" class="form-control" rows="8"
                                          required maxlength="5000">{{ old('email_message', $emailData['message']) }}</textarea>
                                <div class="char-counter" id="charCountWrapper">
                                    <span id="charCount">{{ strlen($emailData['message']) }}</span>/5000 characters
                                </div>
                                @error('email_message')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <i class="fas fa-info-circle"></i>
                                    <span>This message appears at the top of the email. Course details are added automatically below.</span>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="action-buttons">
                                <button type="submit" class="btn-send" onclick="return confirm('Send this email to {{ $emailData['lecturer_email'] }}?')">
                                    <i class="fas fa-paper-plane"></i>Send Email Now
                                </button>
                                <a href="{{ route('resource_person.equivalency_requests.review', $request) }}" class="btn-cancel">
                                    <i class="fas fa-times"></i>Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Email Preview (Right Side) -->
            <div class="col-lg-7">
                <div class="preview-card">
                    <div class="preview-card-header">
                        <h5><i class="fas fa-eye"></i>Email Preview</h5>
                        <small>This is how the email will look to the recipient</small>
                    </div>
                    <div class="preview-card-body">
                        <div class="email-preview-wrapper">
                            <div class="email-preview-container">
                                <!-- Email Header -->
                                <div class="email-header">
                                    <div class="email-header-icon">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <h2>Course Syllabus Request</h2>
                                    <p>UiTM Credit Exemption Management System</p>
                                </div>

                                <!-- Email Body -->
                                <div class="email-body">
                                    <p class="email-greeting">
                                        <span id="greetingPreview">Dear <strong>{{ $emailData['lecturer_name'] }}</strong>,</span>
                                    </p>

                                    <!-- Custom Message Preview -->
                                    <div class="message-preview-box" id="messagePreview">{{ $emailData['message'] }}</div>

                                    <!-- Diploma Course Information -->
                                    <div class="course-info-section">
                                        <h3 class="course-info-header">
                                            <i class="fas fa-book"></i>Diploma Course Information
                                        </h3>
                                        <table class="course-info-table">
                                            <tr>
                                                <td>Course Code:</td>
                                                <td><span class="course-code">{{ $emailData['diploma_course_code'] }}</span></td>
                                            </tr>
                                            <tr>
                                                <td>Course Name:</td>
                                                <td><strong>{{ $emailData['diploma_course_name'] }}</strong></td>
                                            </tr>
                                            <tr>
                                                <td>Credit Hours:</td>
                                                <td>{{ $emailData['diploma_credit_hours'] }} credit hours</td>
                                            </tr>
                                            <tr>
                                                <td>Institution:</td>
                                                <td>{{ $emailData['diploma_institution'] }}</td>
                                            </tr>
                                            <tr>
                                                <td>Program:</td>
                                                <td>{{ $emailData['diploma_program'] }}</td>
                                            </tr>
                                        </table>
                                    </div>

                                    <!-- CTA Section -->
                                    <div class="email-cta-section">
                                        <p>Click the button below to securely submit the syllabus:</p>
                                        <a href="{{ $emailData['submission_url'] }}" class="email-cta-button">
                                            <i class="fas fa-upload"></i> Submit Course Syllabus
                                        </a>
                                    </div>

                                    <!-- Expiration Warning -->
                                    <div class="email-warning-box">
                                        <strong>
                                            <i class="fas fa-exclamation-triangle"></i>
                                            Important Notice
                                        </strong>
                                        <p style="margin: 0.5rem 0 0 0; font-size: 0.9rem; color: #92400e;">
                                            This submission link will expire on <span class="expiry-date">{{ $emailData['token_expires_at'] }}</span>
                                        </p>
                                    </div>
                                </div>

                                <!-- Email Footer -->
                                <div class="email-footer">
                                    <p>This is an automated email from UiTM Credit Exemption Management System.</p>
                                    <p>&copy; {{ date('Y') }} Universiti Teknologi MARA (UiTM). All rights reserved.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const greetingInput = document.getElementById('email_greeting');
    const greetingPreview = document.getElementById('greetingPreview');
    const messageTextarea = document.getElementById('email_message');
    const messagePreview = document.getElementById('messagePreview');
    const charCount = document.getElementById('charCount');
    const charCountWrapper = document.getElementById('charCountWrapper');

    // Update greeting preview as user types
    if (greetingInput && greetingPreview) {
        greetingInput.addEventListener('input', function() {
            const greetingText = this.value.trim();
            if (greetingText) {
                greetingPreview.innerHTML = greetingText;
            }
        });
    }

    // Update message preview and character count as user types
    if (messageTextarea && messagePreview && charCount) {
        messageTextarea.addEventListener('input', function() {
            messagePreview.textContent = this.value;
            const length = this.value.length;
            charCount.textContent = length;

            // Update character counter styling based on length
            charCountWrapper.classList.remove('warning', 'danger');
            if (length > 4500) {
                charCountWrapper.classList.add('danger');
            } else if (length > 4000) {
                charCountWrapper.classList.add('warning');
            }
        });
    }
});
</script>
@endpush
