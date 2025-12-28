@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-2"><i class="fas fa-envelope me-2"></i>Preview & Edit Syllabus Request Email</h2>
                    <p class="text-muted">Review the email content and make any necessary changes before sending</p>
                </div>
                <a href="{{ route('resource_person.equivalency_requests.review', $request) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Request
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Email Editor Form (Left Side) -->
        <div class="col-lg-5">
            <div class="card shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Email Content</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('resource_person.equivalency_requests.send_email', $request) }}" id="emailForm">
                        @csrf

                        <!-- Recipient Info (Read-only) -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Recipient</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" value="{{ $emailData['lecturer_name'] }}" readonly>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control" value="{{ $emailData['lecturer_email'] }}" readonly>
                            </div>
                        </div>

                        <!-- Editable Greeting -->
                        <div class="mb-3">
                            <label for="email_greeting" class="form-label fw-bold">Email Greeting <span class="text-danger">*</span></label>
                            <input type="text" name="email_greeting" id="email_greeting" class="form-control"
                                   value="{{ old('email_greeting', 'Dear ' . $emailData['lecturer_name']) }}"
                                   required maxlength="200"
                                   placeholder="e.g., Dear Dr. {{ explode(' ', $emailData['lecturer_name'])[0] }}, or Dear Prof. {{ $emailData['lecturer_name'] }},">
                            @error('email_greeting')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="fas fa-info-circle"></i> Examples: "Dear Dr. Smith,", "Dear Prof. Ahmad,", "Dear Mr. Johnson,"
                            </div>
                        </div>

                        <!-- Editable Subject -->
                        <div class="mb-3">
                            <label for="email_subject" class="form-label fw-bold">Email Subject <span class="text-danger">*</span></label>
                            <input type="text" name="email_subject" id="email_subject" class="form-control"
                                   value="{{ old('email_subject', $emailData['subject']) }}" required maxlength="255">
                            @error('email_subject')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Editable Message -->
                        <div class="mb-3">
                            <label for="email_message" class="form-label fw-bold">Custom Message <span class="text-danger">*</span></label>
                            <textarea name="email_message" id="email_message" class="form-control" rows="10"
                                      required maxlength="5000">{{ old('email_message', $emailData['message']) }}</textarea>
                            <small class="text-muted">
                                <span id="charCount">{{ strlen($emailData['message']) }}</span>/5000 characters
                            </small>
                            @error('email_message')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="fas fa-info-circle"></i> This message will appear at the top of the email.
                                All course and student details will be included automatically below.
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg" onclick="return confirm('Send this email to {{ $emailData['lecturer_email'] }}?')">
                                <i class="fas fa-paper-plane me-2"></i>Send Email Now
                            </button>
                            <a href="{{ route('resource_person.equivalency_requests.review', $request) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Email Preview (Right Side) -->
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-eye me-2"></i>Email Preview</h5>
                    <small class="text-muted">This is how the email will look to the recipient</small>
                </div>
                <div class="card-body p-0">
                    <!-- Email Preview Container -->
                    <div style="background-color: #f5f5f5; padding: 20px;">
                        <div style="max-width: 700px; margin: 0 auto; background-color: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                            <!-- Email Header -->
                            <div style="background: linear-gradient(135deg, #003366 0%, #004d99 100%); color: white; padding: 30px 20px; text-align: center;">
                                <h1 style="margin: 0 0 10px 0; font-size: 24px;">📚 Course Syllabus Request</h1>
                                <p style="margin: 0; opacity: 0.9;">UiTM Credit Exemption Management System</p>
                            </div>

                            <!-- Email Content -->
                            <div style="padding: 30px;">
                                <p style="font-size: 16px;"><span id="greetingPreview">Dear <strong>{{ $emailData['lecturer_name'] }}</strong>,</span></p>

                                <!-- Custom Message Preview -->
                                <div id="messagePreview" style="background-color: #f9f9f9; padding: 20px; border-left: 4px solid #003366; margin: 20px 0; white-space: pre-wrap; line-height: 1.8;">
                                    {{ $emailData['message'] }}
                                </div>

                                <!-- Diploma Course Information -->
                                <div style="margin: 25px 0;">
                                    <h3 style="color: #003366; margin: 0 0 15px 0; padding-bottom: 10px; border-bottom: 2px solid #003366; font-size: 18px;">
                                        📖 Diploma Course Information
                                    </h3>
                                    <table style="width: 100%;">
                                        <tr>
                                            <td style="font-weight: bold; color: #666; padding: 8px 15px 8px 0; width: 40%;">Course Code:</td>
                                            <td style="padding: 8px 0;"><strong style="color: #003366; font-size: 16px;">{{ $emailData['diploma_course_code'] }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold; color: #666; padding: 8px 15px 8px 0;">Course Name:</td>
                                            <td style="padding: 8px 0;"><strong>{{ $emailData['diploma_course_name'] }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold; color: #666; padding: 8px 15px 8px 0;">Credit Hours:</td>
                                            <td style="padding: 8px 0;">{{ $emailData['diploma_credit_hours'] }} credit hours</td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold; color: #666; padding: 8px 15px 8px 0;">Institution:</td>
                                            <td style="padding: 8px 0;">{{ $emailData['diploma_institution'] }}</td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold; color: #666; padding: 8px 15px 8px 0;">Program:</td>
                                            <td style="padding: 8px 0;">{{ $emailData['diploma_program'] }}</td>
                                        </tr>
                                    </table>
                                </div>

                                <!-- Submit Button -->
                                <div style="text-align: center; margin: 30px 0; padding: 20px; background-color: #f0f7ff; border-radius: 5px;">
                                    <p style="margin: 0 0 15px 0; font-size: 16px;"><strong>Click the button below to securely submit the syllabus:</strong></p>
                                    <a href="{{ $emailData['submission_url'] }}" style="display: inline-block; padding: 15px 40px; background-color: #28a745; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 16px;">
                                        📤 Submit Course Syllabus
                                    </a>
                                </div>

                                <!-- Expiration Warning -->
                                <div style="background-color: #fff3cd; border: 2px solid #ffc107; padding: 15px; margin: 20px 0; border-radius: 5px; text-align: center;">
                                    <strong style="color: #856404; font-size: 16px;">⚠️ Important:</strong> This submission link will expire on <strong>{{ $emailData['token_expires_at'] }}</strong>.
                                </div>
                            </div>

                            <!-- Email Footer -->
                            <div style="background-color: #f8f9fa; text-align: center; padding: 20px; border-top: 1px solid #dee2e6; color: #666; font-size: 12px;">
                                <p style="margin: 5px 0;"><strong>This is an automated email from UiTM Credit Exemption Management System.</strong></p>
                                <p style="margin: 5px 0;">&copy; {{ date('Y') }} Universiti Teknologi MARA (UiTM). All rights reserved.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const greetingInput = document.getElementById('email_greeting');
    const greetingPreview = document.getElementById('greetingPreview');
    const messageTextarea = document.getElementById('email_message');
    const messagePreview = document.getElementById('messagePreview');
    const charCount = document.getElementById('charCount');

    // Update greeting preview as user types
    greetingInput.addEventListener('input', function() {
        // Preserve the formatting by wrapping in proper HTML
        const greetingText = this.value;
        // Check if there's a comma at the end, if not it's just the greeting
        if (greetingText.trim()) {
            greetingPreview.innerHTML = greetingText;
        }
    });

    // Update message preview as user types
    messageTextarea.addEventListener('input', function() {
        messagePreview.textContent = this.value;
        charCount.textContent = this.value.length;
    });

    // Character count
    messageTextarea.addEventListener('input', function() {
        charCount.textContent = this.value.length;
        if (this.value.length > 5000) {
            charCount.style.color = 'red';
        } else {
            charCount.style.color = '#666';
        }
    });
});
</script>
@endpush
@endsection
