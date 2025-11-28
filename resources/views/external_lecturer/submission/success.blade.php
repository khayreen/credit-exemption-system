<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submission Successful - UiTM Credit Exemption</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .success-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 3rem 0;
        }
        .card-custom {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .check-circle {
            font-size: 4rem;
            color: #28a745;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body class="bg-light">
    <div class="success-header">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center">
                    <h1><i class="fas fa-university me-3"></i>UiTM Credit Exemption System</h1>
                    <p class="lead mb-0">Syllabus Submission Successful</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-custom">
                    <div class="card-body p-5 text-center">
                        <i class="fas fa-check-circle check-circle"></i>
                        <h2 class="text-success mb-4">Submission Successful!</h2>
                        <p class="lead">Thank you, <strong>{{ $externalRequest->external_lecturer_name }}</strong>, for submitting the complete syllabus.</p>
                        
                        <div class="alert alert-success">
                            <h5><i class="fas fa-info-circle me-2"></i>Submission Details</h5>
                            <div class="row text-start mt-3">
                                <div class="col-md-6">
                                    <p><strong>Course Code:</strong> {{ $submission->course_code }}</p>
                                    <p><strong>Course Name:</strong> {{ $submission->course_name }}</p>
                                    <p><strong>Credit Hours:</strong> {{ $submission->credit_hours }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Institution:</strong> {{ $submission->institution_name }}</p>
                                    <p><strong>Submitted At:</strong> {{ $externalRequest->submitted_at->format('M d, Y H:i') }}</p>
                                    <p><strong>File:</strong> {{ $submission->syllabus_file_original_name }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <h6><i class="fas fa-clock me-2"></i>What happens next?</h6>
                            <p class="mb-0">Your syllabus submission has been forwarded to the Resource Person for review. The credit exemption evaluation process will continue, and the student will be notified of any updates regarding their application.</p>
                        </div>

                        <hr class="my-4">
                        <p class="text-muted">
                            <i class="fas fa-lock me-1"></i>
                            Your submission has been securely stored and digitally signed for authenticity.
                        </p>
                        <small class="text-muted">
                            If you have any questions about this submission, please contact the UiTM Credit Exemption Office.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>