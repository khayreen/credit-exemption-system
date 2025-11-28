<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Already Submitted - UiTM Credit Exemption</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .info-header {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
            padding: 3rem 0;
        }
        .card-custom {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .info-icon {
            font-size: 4rem;
            color: #17a2b8;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body class="bg-light">
    <div class="info-header">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center">
                    <h1><i class="fas fa-university me-3"></i>UiTM Credit Exemption System</h1>
                    <p class="lead mb-0">Submission Already Completed</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-custom">
                    <div class="card-body p-5 text-center">
                        <i class="fas fa-check-circle info-icon"></i>
                        <h2 class="text-info mb-4">Submission Already Completed</h2>
                        <p class="lead">This syllabus has already been submitted and cannot be submitted again.</p>
                        
                        <div class="alert alert-success">
                            <h6><i class="fas fa-info-circle me-2"></i>Submission Details:</h6>
                            <div class="text-start">
                                <p><strong>Submitted by:</strong> {{ $request->external_lecturer_name ?? 'External Lecturer' }}</p>
                                <p><strong>Submission Date:</strong> {{ $request->submitted_at ? $request->submitted_at->format('M d, Y H:i') : 'N/A' }}</p>
                                <p><strong>Status:</strong> <span class="badge bg-success">Submitted</span></p>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <h6><i class="fas fa-lightbulb me-2"></i>What's next:</h6>
                            <p class="mb-0">Your syllabus submission has been received and is being reviewed by the Resource Person. The credit exemption evaluation process will continue accordingly.</p>
                        </div>

                        <hr class="my-4">
                        <small class="text-muted">
                            If you need to make changes to your submission or have questions, please contact the UiTM Credit Exemption Office.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>