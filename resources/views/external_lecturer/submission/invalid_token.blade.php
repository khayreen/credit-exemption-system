<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invalid Link - UiTM Credit Exemption</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .error-header {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            padding: 3rem 0;
        }
        .card-custom {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .error-icon {
            font-size: 4rem;
            color: #dc3545;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body class="bg-light">
    <div class="error-header">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center">
                    <h1><i class="fas fa-university me-3"></i>UiTM Credit Exemption System</h1>
                    <p class="lead mb-0">Invalid Submission Link</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-custom">
                    <div class="card-body p-5 text-center">
                        <i class="fas fa-exclamation-triangle error-icon"></i>
                        <h2 class="text-danger mb-4">Invalid Submission Link</h2>
                        <p class="lead">The submission link you're trying to access is invalid or does not exist.</p>
                        
                        <div class="alert alert-warning">
                            <h6><i class="fas fa-info-circle me-2"></i>Possible reasons:</h6>
                            <ul class="text-start mb-0">
                                <li>The link may have been copied incorrectly</li>
                                <li>This submission request may have been cancelled</li>
                                <li>The link may be corrupted or modified</li>
                            </ul>
                        </div>

                        <div class="alert alert-info">
                            <h6><i class="fas fa-lightbulb me-2"></i>What to do:</h6>
                            <p class="mb-0">Please contact the Resource Person who sent you this link to request a new submission link, or verify that you have the correct link.</p>
                        </div>

                        <hr class="my-4">
                        <small class="text-muted">
                            If you believe this is an error, please contact the UiTM Credit Exemption Office for assistance.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>