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

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

    <style>
        :root {
            --uitm-primary: #1e3a8a;
            --uitm-secondary: #3b82f6;
            --uitm-accent: #f59e0b;
            --uitm-dark: #1f2937;
            --uitm-light: #f8fafc;
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
            padding: 2rem 0;
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

        .form-label {
            color: var(--uitm-dark);
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--uitm-secondary);
            box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.15);
        }

        .form-control.is-invalid, .form-select.is-invalid {
            border-color: #dc2626;
        }

        .invalid-feedback {
            color: #dc2626;
            font-weight: 500;
            font-size: 0.875rem;
            margin-top: 0.25rem;
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
            margin-bottom: 1rem;
        }

        .btn-register:hover {
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 12px 32px rgba(30, 58, 138, 0.4);
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

        .form-text {
            font-size: 0.875rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }

        .conditional-fields {
            background: rgba(59, 130, 246, 0.05);
            border: 2px solid rgba(59, 130, 246, 0.1);
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 1rem;
        }

        .conditional-fields h6 {
            color: var(--uitm-primary);
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .alert {
            border-radius: 12px;
            border: none;
            margin-top: 1rem;
            padding: 1rem;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .floating-elements {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }

        .floating-circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 6s ease-in-out infinite;
        }

        .floating-circle:nth-child(1) {
            width: 60px;
            height: 60px;
            top: 15%;
            left: 15%;
            animation-delay: 0s;
        }

        .floating-circle:nth-child(2) {
            width: 100px;
            height: 100px;
            top: 70%;
            right: 10%;
            animation-delay: 2s;
        }

        .floating-circle:nth-child(3) {
            width: 40px;
            height: 40px;
            bottom: 25%;
            left: 25%;
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-15px);
            }
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
    <!-- Floating Background Elements -->
    <div class="floating-elements">
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
    </div>

    <!-- Back to Home -->
    <a href="{{ route('login') }}" class="back-home d-none d-sm-flex">
        <i class="fas fa-arrow-left"></i>
        Back to Login
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
                <p class="register-subtitle">Register for UiTM CES account</p>
            </div>

            <!-- Registration Form -->
            <form method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf

                <!-- Name -->
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name *</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           id="name" name="name" value="{{ old('name') }}" required autofocus
                           placeholder="Enter your full name">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address *</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                           id="email" name="email" value="{{ old('email') }}" required
                           placeholder="Enter your email address">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password *</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                           id="password" name="password" required
                           placeholder="Enter your password">
                    <small class="form-text">Minimum 8 characters</small>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirm Password *</label>
                    <input type="password" class="form-control"
                           id="password_confirmation" name="password_confirmation" required
                           placeholder="Confirm your password">
                </div>

                <!-- Role Selection -->
                <div class="mb-3">
                    <label for="requested_role" class="form-label">I am registering as: *</label>
                    <select class="form-select @error('requested_role') is-invalid @enderror"
                            id="requested_role" name="requested_role" required>
                        <option value="">Select Role</option>
                        <option value="student" {{ old('requested_role') == 'student' ? 'selected' : '' }}>
                            Student
                        </option>
                        <option value="academic_advisor" {{ old('requested_role') == 'academic_advisor' ? 'selected' : '' }}>
                            Academic Advisor
                        </option>
                        <option value="coordinator" {{ old('requested_role') == 'coordinator' ? 'selected' : '' }}>
                            Program Coordinator
                        </option>
                        <option value="resource_person" {{ old('requested_role') == 'resource_person' ? 'selected' : '' }}>
                            Resource Person
                        </option>
                        <option value="hea_personnel" {{ old('requested_role') == 'hea_personnel' ? 'selected' : '' }}>
                            HEA Personnel
                        </option>
                    </select>
                    @error('requested_role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text">
                        <strong>Note:</strong> External lecturers will receive secure access links directly from resource persons.
                    </small>
                </div>

                <!-- Student Fields -->
                <div id="student-fields" style="display: none;">
                    <div class="conditional-fields">
                        <h6><i class="fas fa-user-graduate me-2"></i>Student Information</h6>

                        <div class="mb-3">
                            <label for="matric_no" class="form-label">Matric Number *</label>
                            <input type="text" class="form-control" id="matric_no" name="matric_no"
                                   value="{{ old('matric_no') }}" placeholder="Enter your matric number">
                        </div>

                        <div class="mb-0">
                            <label for="program_id" class="form-label">Current Degree Program *</label>
                            <select class="form-select" id="program_id" name="program_id">
                                <option value="">Select Degree Program</option>
                                @foreach($degreePrograms as $program)
                                    <option value="{{ $program->id }}">{{ $program->code }} - {{ $program->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Academic Advisor Fields -->
                <div id="academic-advisor-fields" style="display: none;">
                    <div class="conditional-fields">
                        <h6><i class="fas fa-user-tie me-2"></i>Academic Advisor Information</h6>

                        <div class="mb-3">
                            <label class="form-label">Programme & Groups You Will Manage <span class="text-danger">*</span></label>
                            <small class="form-text text-muted d-block mb-2">
                                <i class="fas fa-info-circle me-1"></i>Select one or more programme-group combinations you wish to manage
                            </small>

                            <!-- Selected Programme-Groups Display -->
                            <div id="aa-selected-groups" class="mb-2" style="min-height: 36px; padding: 8px; border: 1px solid #dee2e6; border-radius: 0.375rem; background: #f8f9fa;">
                                <small class="text-muted" id="aa-placeholder">No programme-groups selected</small>
                            </div>

                            <!-- Programme-Group Selection Cards (Dynamic from config) -->
                            <div class="row g-2">
                                @foreach($programGroups as $programCode => $programData)
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body py-2">
                                            <div class="form-check">
                                                <input class="form-check-input aa-program-check" type="checkbox" id="aa_prog_{{ $programCode }}" value="{{ $programCode }}">
                                                <label class="form-check-label fw-bold" for="aa_prog_{{ $programCode }}">
                                                    {{ $programCode }} - {{ $programData['name'] }}
                                                </label>
                                            </div>
                                            <div class="ms-4 mt-2 aa-groups" id="aa_groups_{{ $programCode }}" style="display: none;">
                                                <label class="form-label small">Select Groups:</label>
                                                <div class="d-flex flex-wrap gap-2">
                                                    @foreach($programData['groups'] as $group)
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input aa-group-check" type="checkbox" data-program="{{ $programCode }}" id="aa_group_{{ $group }}" value="{{ $group }}">
                                                        <label class="form-check-label" for="aa_group_{{ $group }}">{{ $group }}</label>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <!-- Hidden Input for Form Submission -->
                            <input type="hidden" name="program_groups" id="aa_program_groups" value="">
                            <small class="form-text text-danger" id="aa-validation-error" style="display: none;">
                                <i class="fas fa-exclamation-triangle me-1"></i>Please select at least one programme-group combination
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Program Coordinator Fields -->
                <div id="program-coordinator-fields" style="display: none;">
                    <div class="conditional-fields">
                        <h6><i class="fas fa-users-cog me-2"></i>Program Coordinator Information</h6>

                        <div class="mb-3">
                            <label class="form-label">Program Category <span class="text-danger">*</span></label>
                            <small class="form-text text-muted d-block mb-2">
                                <i class="fas fa-info-circle me-1"></i>Select ONE category to coordinate
                            </small>

                            <!-- Program Categories (Dynamic from config) -->
                            @foreach($coordinatorCategories as $categoryKey => $categoryData)
                            <div class="card {{ !$loop->last ? 'mb-3' : '' }}">
                                <div class="card-body">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="program_category" id="pc_{{ $categoryKey }}" value="{{ $categoryKey }}">
                                        <label class="form-check-label fw-bold" for="pc_{{ $categoryKey }}">
                                            {{ $categoryData['label'] }}: {{ $categoryData['description'] }}
                                        </label>
                                    </div>
                                    <div class="ms-4 mt-2">
                                        <small class="text-muted">
                                            @foreach($categoryData['programs'] as $programCode)
                                                <i class="fas fa-check-circle text-success me-1"></i>
                                                <strong>{{ $programCode }}</strong> - {{ $programGroups[$programCode]['name'] ?? 'Unknown Program' }}
                                                @if(!$loop->last)<br>@endif
                                            @endforeach
                                        </small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Resource Person Fields -->
                <div id="resource-person-fields" style="display: none;">
                    <div class="conditional-fields">
                        <h6><i class="fas fa-user-check me-2"></i>Resource Person Information</h6>

                        <div class="mb-3">
                            <label for="rp_degree_program" class="form-label">Degree Program to Support <span class="text-danger">*</span></label>
                            <select class="form-select" id="rp_degree_program" name="degree_program">
                                <option value="">-- Select Degree Program --</option>
                                @foreach($programGroups as $programCode => $programData)
                                <option value="{{ $programCode }}">{{ $programCode }} - {{ $programData['name'] }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Select the degree program you will provide resource support for</small>
                        </div>
                    </div>
                </div>

                <!-- HEA Note -->
                <div id="hea-note" style="display: none;">
                    <div class="alert" style="background: linear-gradient(135deg, #fef3c7, #fde68a); color: #92400e;">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>HEA Personnel Registration</strong><br>
                        Your registration will be reviewed by the system administrator.
                        You will receive an email once your request is processed.
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-register mt-3">
                    <i class="fas fa-user-plus me-2"></i>
                    Register
                </button>
            </form>

            <!-- Login Link -->
            <div class="login-link">
                <span class="text-muted">Already have an account?</span>
                <a href="{{ route('login') }}">
                    <i class="fas fa-sign-in-alt me-1"></i>Login
                </a>
            </div>

            <!-- Back to Login for Mobile -->
            <div class="text-center mt-3 d-sm-none">
                <a href="{{ route('login') }}" class="back-home">
                    <i class="fas fa-arrow-left"></i>
                    Back to Login
                </a>
            </div>
        </div>
    </div>

    <!-- jQuery (required for Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('requested_role');
        const studentFields = document.getElementById('student-fields');
        const academicAdvisorFields = document.getElementById('academic-advisor-fields');
        const programCoordinatorFields = document.getElementById('program-coordinator-fields');
        const resourcePersonFields = document.getElementById('resource-person-fields');
        const heaNote = document.getElementById('hea-note');

        // Academic Advisor - Programme-Group Selection Logic
        document.querySelectorAll('.aa-program-check').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const program = this.value;
                const groupsDiv = document.getElementById(`aa_groups_${program}`);

                if (this.checked) {
                    groupsDiv.style.display = 'block';
                } else {
                    groupsDiv.style.display = 'none';
                    // Uncheck all groups
                    document.querySelectorAll(`#aa_groups_${program} .aa-group-check`).forEach(gc => {
                        gc.checked = false;
                    });
                }

                updateAASelectedGroups();
            });
        });

        document.querySelectorAll('.aa-group-check').forEach(checkbox => {
            checkbox.addEventListener('change', updateAASelectedGroups);
        });

        function updateAASelectedGroups() {
            const selected = [];
            const programGroupMap = {};

            document.querySelectorAll('.aa-group-check:checked').forEach(checkbox => {
                const program = checkbox.getAttribute('data-program');
                const group = checkbox.value;

                if (!programGroupMap[program]) {
                    programGroupMap[program] = [];
                }
                programGroupMap[program].push(group);

                selected.push({
                    program_code: program,
                    group: group
                });
            });

            // Update display
            const displayDiv = document.getElementById('aa-selected-groups');
            const placeholder = document.getElementById('aa-placeholder');

            if (selected.length > 0) {
                placeholder.style.display = 'none';

                let html = '';
                for (const program in programGroupMap) {
                    const groups = programGroupMap[program];
                    html += `<span class="badge bg-primary me-1 mb-1">${program}: ${groups.join(', ')}</span>`;
                }
                displayDiv.innerHTML = html;
            } else {
                displayDiv.innerHTML = '<small class="text-muted" id="aa-placeholder">No programme-groups selected</small>';
            }

            // Update hidden input
            document.getElementById('aa_program_groups').value = JSON.stringify(selected);

            // Validation
            const errorMsg = document.getElementById('aa-validation-error');
            if (selected.length === 0) {
                errorMsg.style.display = 'block';
            } else {
                errorMsg.style.display = 'none';
            }
        }

        // Role change handler
        roleSelect.addEventListener('change', function() {
            const role = this.value;

            // Hide all conditional fields
            studentFields.style.display = 'none';
            academicAdvisorFields.style.display = 'none';
            programCoordinatorFields.style.display = 'none';
            resourcePersonFields.style.display = 'none';
            heaNote.style.display = 'none';

            // Disable all conditional fields
            document.querySelectorAll('#student-fields input, #student-fields select').forEach(el => {
                el.disabled = true;
                el.required = false;
            });
            document.querySelectorAll('#academic-advisor-fields input, #academic-advisor-fields select').forEach(el => {
                el.disabled = true;
                el.required = false;
            });
            document.querySelectorAll('#program-coordinator-fields input, #program-coordinator-fields select').forEach(el => {
                el.disabled = true;
                el.required = false;
            });
            document.querySelectorAll('#resource-person-fields input, #resource-person-fields select').forEach(el => {
                el.disabled = true;
                el.required = false;
            });

            // Show relevant fields based on role
            if (role === 'student') {
                studentFields.style.display = 'block';
                document.querySelectorAll('#student-fields input, #student-fields select').forEach(el => {
                    el.disabled = false;
                    if (el.id !== 'program_id') el.required = true;
                });
            } else if (role === 'academic_advisor') {
                academicAdvisorFields.style.display = 'block';
                document.querySelectorAll('#academic-advisor-fields input:not([type="checkbox"])').forEach(el => {
                    el.disabled = false;
                });
                // Enable checkboxes
                document.querySelectorAll('#academic-advisor-fields input[type="checkbox"]').forEach(el => {
                    el.disabled = false;
                });
            } else if (role === 'coordinator') {
                programCoordinatorFields.style.display = 'block';
                document.querySelectorAll('#program-coordinator-fields input, #program-coordinator-fields select').forEach(el => {
                    el.disabled = false;
                    el.required = true;
                });
            } else if (role === 'resource_person') {
                resourcePersonFields.style.display = 'block';
                document.querySelectorAll('#resource-person-fields input, #resource-person-fields select').forEach(el => {
                    el.disabled = false;
                    el.required = true;
                });
            } else if (role === 'hea_personnel') {
                heaNote.style.display = 'block';
            }
        });

        // Trigger on page load if old value exists
        if (roleSelect.value) {
            roleSelect.dispatchEvent(new Event('change'));
        }
    });
    </script>
</body>
</html>
