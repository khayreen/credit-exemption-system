<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - UiTM Credit Exemption System</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --uitm-primary: #1e3a8a;
            --uitm-secondary: #3b82f6;
            --uitm-accent: #f59e0b;
            --uitm-dark: #1f2937;
            --uitm-light: #f8fafc;
            --uitm-red: #dc2626;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), 
                        url('https://malaysiabangkit.com/wp-content/uploads/2024/11/UITM-pelajar.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px 0;
        }
        
        .register-container {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 500px;
            padding: 1rem;
        }
        
        .register-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 24px;
            box-shadow: 0 32px 64px rgba(0, 0, 0, 0.1);
            padding: 3rem;
            transition: all 0.3s ease;
        }
        
        .register-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 40px 80px rgba(0, 0, 0, 0.15);
        }
        
        .logo-section {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--uitm-primary), var(--uitm-secondary));
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            font-weight: 700;
            box-shadow: 0 16px 32px rgba(30, 58, 138, 0.3);
            margin-bottom: 1rem;
        }
        
        .register-title {
            color: var(--uitm-dark);
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .register-subtitle {
            color: var(--uitm-secondary);
            font-size: 1rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
        }
        
        .form-floating {
            margin-bottom: 1.5rem;
        }
        
        .form-floating > .form-control,
        .form-floating > .form-select {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 1rem 1rem;
            height: auto;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-floating > .form-control:focus,
        .form-floating > .form-select:focus {
            border-color: var(--uitm-secondary);
            box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.15);
        }
        
        .form-floating > label {
            color: #6b7280;
            font-weight: 500;
        }
        
        .btn-register {
            background: linear-gradient(135deg, var(--uitm-primary), var(--uitm-secondary));
            color: white;
            border: none;
            padding: 1rem;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 12px;
            width: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 8px 24px rgba(30, 58, 138, 0.3);
            margin-bottom: 1.5rem;
        }
        
        .btn-register:hover {
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 12px 32px rgba(30, 58, 138, 0.4);
        }
        
        .btn-register:focus {
            color: white;
            box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
        }
        
        .back-home {
            position: absolute;
            top: 2rem;
            left: 2rem;
            color: white;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.1);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            backdrop-filter: blur(10px);
        }
        
        .back-home:hover {
            color: white;
            background: rgba(255, 255, 255, 0.2);
            transform: translateX(-2px);
        }
        
        .alert {
            border-radius: 12px;
            border: none;
            margin-bottom: 1.5rem;
            padding: 1rem;
            font-weight: 500;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: #065f46;
        }
        
        .alert-info {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            color: #1e40af;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #991b1b;
        }
        
        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
        }
        
        .login-link a {
            color: var(--uitm-secondary);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .login-link a:hover {
            color: var(--uitm-primary);
            text-decoration: underline;
        }
        
        .invalid-feedback {
            color: #dc2626;
            font-weight: 500;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }
        
        .form-control.is-invalid,
        .form-select.is-invalid {
            border-color: #dc2626;
        }
        
        @media (max-width: 576px) {
            .register-card {
                padding: 2rem;
                margin: 1rem;
            }
            
            .back-home {
                position: relative;
                top: auto;
                left: auto;
                margin-bottom: 1rem;
                display: inline-flex;
            }
        }
    </style>
</head>
<body>
    <!-- Back to Home -->
    <a href="{{ url('/') }}" class="back-home d-none d-sm-flex">
        <i class="fas fa-arrow-left"></i>
        Back to Home
    </a>

    <!-- Register Container -->
    <div class="register-container">
        <div class="register-card">
            <!-- Logo Section -->
            <div class="logo-section">
                <div class="logo">
                    <i class="fas fa-university"></i>
                </div>
                <h1 class="register-title">Create Account</h1>
                <p class="register-subtitle">Join UiTM Credit Exemption System</p>
            </div>

            <!-- Registration Form -->
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name Field -->
                <div class="form-floating">
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                           name="name" value="{{ old('name') }}" required autocomplete="name" autofocus
                           placeholder="Enter your full name">
                    <label for="name">
                        <i class="fas fa-user me-2"></i>{{ __('Name') }}
                    </label>
                    @error('name')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>

                <!-- Email Field -->
                <div class="form-floating">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                           name="email" value="{{ old('email') }}" required autocomplete="email"
                           placeholder="Enter your email address">
                    <label for="email">
                        <i class="fas fa-envelope me-2"></i>{{ __('Email Address') }}
                    </label>
                    @error('email')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>

                <!-- Phone Number Field -->
                <div class="form-floating">
                    <input id="phone_number" type="text" class="form-control @error('phone_number') is-invalid @enderror" 
                           name="phone_number" value="{{ old('phone_number') }}" required
                           placeholder="Enter your phone number">
                    <label for="phone_number">
                        <i class="fas fa-phone me-2"></i>{{ __('Phone Number') }}
                    </label>
                    @error('phone_number')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>

                <!-- Role Selection -->
                <div class="form-floating">
                    <select id="role" class="form-select @error('role') is-invalid @enderror" name="role" required>
                        <option value="" disabled selected>-- Select a Role --</option>
                        <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
                        <option value="academic_advisor" {{ old('role') == 'academic_advisor' ? 'selected' : '' }}>Academic Advisor</option>
                        <option value="coordinator" {{ old('role') == 'coordinator' ? 'selected' : '' }}>Coordinator</option>
                        <option value="resource_person" {{ old('role') == 'resource_person' ? 'selected' : '' }}>Resource Person</option>
                        <option value="hea_personnel" {{ old('role') == 'hea_personnel' ? 'selected' : '' }}>HEA Personnel</option>
                        <option value="external_lecturer" {{ old('role') == 'external_lecturer' ? 'selected' : '' }}>External Lecturer</option>
                    </select>
                    <label for="role">
                        <i class="fas fa-user-tag me-2"></i>{{ __('Register as') }}
                    </label>
                    @error('role')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>

                <!-- Resource Person Program Selection (shown only when resource_person is selected) -->
                <div id="program-selection" style="display: none;" class="mb-4">
                    <label class="form-label fw-bold text-dark">
                        <i class="fas fa-graduation-cap me-2"></i>Assigned Program Codes
                    </label>
                    <div class="card border-2" style="border-color: #e5e7eb; border-radius: 12px; padding: 1.5rem;">
                        <p class="text-muted small mb-3">Select the program code(s) you will be assigned to manage:</p>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="assigned_programs[]" value="CDCS251" id="program_cdcs251"
                                   {{ is_array(old('assigned_programs')) && in_array('CDCS251', old('assigned_programs')) ? 'checked' : '' }}>
                            <label class="form-check-label" for="program_cdcs251">
                                <strong>CDCS251</strong> - Computer Science Program
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="assigned_programs[]" value="CDCS255" id="program_cdcs255"
                                   {{ is_array(old('assigned_programs')) && in_array('CDCS255', old('assigned_programs')) ? 'checked' : '' }}>
                            <label class="form-check-label" for="program_cdcs255">
                                <strong>CDCS255</strong> - Software Engineering Program
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="assigned_programs[]" value="CDCS266" id="program_cdcs266"
                                   {{ is_array(old('assigned_programs')) && in_array('CDCS266', old('assigned_programs')) ? 'checked' : '' }}>
                            <label class="form-check-label" for="program_cdcs266">
                                <strong>CDCS266</strong> - Data Science Program
                            </label>
                        </div>
                    </div>
                    @error('assigned_programs')
                        <div class="text-danger small mt-2">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="form-floating">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                           name="password" required autocomplete="new-password"
                           placeholder="Enter your password">
                    <label for="password">
                        <i class="fas fa-lock me-2"></i>{{ __('Password') }}
                    </label>
                    @error('password')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>

                <!-- Confirm Password Field -->
                <div class="form-floating">
                    <input id="password-confirm" type="password" class="form-control" 
                           name="password_confirmation" required autocomplete="new-password"
                           placeholder="Confirm your password">
                    <label for="password-confirm">
                        <i class="fas fa-lock me-2"></i>{{ __('Confirm Password') }}
                    </label>
                </div>

                <!-- Register Button -->
                <button type="submit" class="btn btn-register">
                    <i class="fas fa-user-plus me-2"></i>
                    {{ __('Create Account') }}
                </button>
            </form>

            <!-- Login Link -->
            <div class="login-link">
                <span class="text-muted">Already have an account?</span>
                <a href="{{ route('login') }}">
                    <i class="fas fa-sign-in-alt me-1"></i>Sign In
                </a>
            </div>

            <!-- Back to Home for Mobile -->
            <div class="text-center mt-3 d-sm-none">
                <a href="{{ url('/') }}" class="back-home">
                    <i class="fas fa-arrow-left"></i>
                    Back to Home
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Show/Hide Program Selection based on Role -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('role');
            const programSelection = document.getElementById('program-selection');

            // Function to toggle program selection visibility
            function toggleProgramSelection() {
                if (roleSelect.value === 'resource_person') {
                    programSelection.style.display = 'block';
                } else {
                    programSelection.style.display = 'none';
                }
            }

            // Check on page load (for old values after validation error)
            toggleProgramSelection();

            // Check when role changes
            roleSelect.addEventListener('change', toggleProgramSelection);
        });
    </script>
</body>
</html>