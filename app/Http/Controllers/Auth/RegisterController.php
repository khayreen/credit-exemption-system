<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\AcademicAdvisor;
use App\Models\ProgramCoordinator;
use App\Models\ResourcePerson;
use App\Models\HeaPersonnel;
use App\Models\ExternalLecturer;
use App\Models\Faculty;
use App\Models\Campus;
use App\Models\Program;
use App\Mail\UserRegistrationConfirmationMail;
use App\Mail\HeaRegistrationNotificationMail;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the registration form
     */
    public function showRegistrationForm()
    {
        $faculties = Faculty::orderBy('name')->get();
        $campuses = Campus::orderBy('name')->get();

        // Get only the 5 supported bachelor's degree programs
        $supportedProgramCodes = ['CDCS230', 'CDCS251', 'CDCS253', 'CDCS255', 'CDCS266'];
        $degreePrograms = Program::whereIn('code', $supportedProgramCodes)
            ->orderBy('code')
            ->get();

        // Get all programs for staff program assignment (AA/PC/RP)
        $allPrograms = Program::orderBy('code')->get();

        return view('auth.register', compact('faculties', 'campuses', 'degreePrograms', 'allPrograms'));
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        // Define supported program codes
        $supportedProgramCodes = ['CDCS230', 'CDCS251', 'CDCS253', 'CDCS255', 'CDCS266'];

        // Get IDs of supported programs for validation
        $supportedProgramIds = Program::whereIn('code', $supportedProgramCodes)->pluck('id')->toArray();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'requested_role' => 'required|in:student,academic_advisor,coordinator,resource_person,hea',

            // Conditional fields
            'matric_no' => 'required_if:requested_role,student',
            'program_id' => [
                'required_if:requested_role,student',
                'in:' . implode(',', $supportedProgramIds)
            ],
            'faculty_id' => 'required_if:requested_role,academic_advisor,coordinator,resource_person',
            'campus_id' => 'required_if:requested_role,academic_advisor,coordinator,resource_person',

            // Program requests for AA/PC/RP
            'requested_programs' => 'required_if:requested_role,academic_advisor,coordinator,resource_person|array|min:1',
            'requested_programs.*' => 'exists:programs,code',
        ], [
            'program_id.in' => 'Please select one of the supported bachelor\'s degree programs (CDCS230, CDCS251, CDCS253, CDCS255, CDCS266).',
            'requested_programs.required_if' => 'Please select at least one program you will manage.',
            'requested_programs.min' => 'Please select at least one program.',
        ]);

        // Determine approval workflow based on role
        $approvalWorkflow = $this->determineApprovalWorkflow($validated['requested_role']);

        $user = DB::transaction(function() use ($validated, $approvalWorkflow) {
            // Create user
            $user = User::create([
                'id' => Str::uuid(),
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $approvalWorkflow['current_role'] ?? 'pending', // Legacy field for backward compatibility
                'requested_role' => $validated['requested_role'],
                'requested_programs' => isset($validated['requested_programs'])
                    ? json_encode($validated['requested_programs'])
                    : null,
                'approval_status' => $approvalWorkflow['approval_status'],
                'current_role' => $approvalWorkflow['current_role'],
                'approved_at' => $approvalWorkflow['auto_approve'] ? now() : null,
                // Email verification removed - all users must verify their email
                'email_verified_at' => null,
            ]);

            // Create role-specific record if auto-approved
            if ($approvalWorkflow['auto_approve']) {
                $this->createRoleRecord($user, $validated['requested_role'], $validated);
            }

            return $user;
        });

        // Send appropriate notifications
        $this->sendNotifications($user, $approvalWorkflow);

        // Redirect with appropriate message
        return $this->redirectWithMessage($user, $approvalWorkflow);
    }

    /**
     * Determine approval workflow based on role
     */
    private function determineApprovalWorkflow(string $role): array
    {
        return match($role) {
            'student' => [
                'approval_status' => 'approved',
                'current_role' => $role,
                'auto_approve' => true,
                'notification_type' => 'auto_approved',
                'redirect_message' => 'Registration successful! Please check your email to verify your account.',
            ],

            'academic_advisor', 'coordinator', 'resource_person' => [
                'approval_status' => 'pending',
                'current_role' => null,
                'auto_approve' => false,
                'notification_type' => 'pending_hea',
                'redirect_message' => 'Registration submitted! HEA personnel will review your application and program requests. You will receive an email once approved.',
            ],

            'hea' => [
                'approval_status' => 'pending_admin',
                'current_role' => null,
                'auto_approve' => false,
                'notification_type' => 'pending_admin',
                'redirect_message' => 'HEA registration submitted! The system administrator will review your request. You will receive an email once approved.',
            ],
        };
    }

    /**
     * Create role-specific records for auto-approved users
     */
    private function createRoleRecord(User $user, string $role, array $data): void
    {
        $roleData = [
            'id' => Str::uuid(),
            'user_id' => $user->id,
        ];

        match($role) {
            'student' => Student::create(array_merge($roleData, [
                'matric_no' => $data['matric_no'],
                'program_name' => $this->getProgramName($data['program_id'] ?? null),
                'program_code' => $this->getProgramCode($data['program_id'] ?? null),
                'faculty_id' => $data['faculty_id'] ?? null,
                'ic_number' => null, // Optional field, can be updated later
                'campus' => null, // Optional field, can be updated later
                'intake_semester' => null, // Optional field, can be updated later
            ])),

            // External lecturers don't register - they use token-based access
            default => null,
        };
    }

    /**
     * Send appropriate notifications based on workflow
     */
    private function sendNotifications(User $user, array $workflow): void
    {
        try {
            match($workflow['notification_type']) {
                'auto_approved' => $this->sendAutoApprovalEmail($user),
                'pending_hea' => $this->sendPendingHeaEmail($user),
                'pending_admin' => $this->sendPendingAdminEmail($user),
            };
        } catch (\Exception $e) {
            Log::error('Failed to send registration email', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send auto-approval confirmation email with email verification
     */
    private function sendAutoApprovalEmail(User $user): void
    {
        // Send email verification notification
        $user->sendEmailVerificationNotification();
    }

    /**
     * Send pending HEA approval email
     */
    private function sendPendingHeaEmail(User $user): void
    {
        Mail::to($user->email)->send(new UserRegistrationConfirmationMail($user, 'pending_hea'));
        // HEA will see this in their dashboard automatically
    }

    /**
     * Send pending admin approval email (for HEA registrations)
     */
    private function sendPendingAdminEmail(User $user): void
    {
        // Send confirmation to user
        Mail::to($user->email)->send(new UserRegistrationConfirmationMail($user, 'pending_admin'));

        // Send notification to system admin
        $adminEmail = config('app.admin_email');
        if ($adminEmail) {
            Mail::to($adminEmail)->send(new HeaRegistrationNotificationMail($user));
        }
    }

    /**
     * Redirect with appropriate message
     */
    private function redirectWithMessage(User $user, array $workflow)
    {
        // For auto-approved users (students), log them in and redirect to email verification page
        if ($workflow['auto_approve']) {
            auth()->login($user);
            return redirect()->route('verification.notice');
        }

        // For pending approvals, redirect to login with message
        return redirect()->route('login')->with('success', $workflow['redirect_message']);
    }

    /**
     * Show QR code setup page after registration
     */
    public function showQrSetup()
    {
        // Check if there's a user ID in session
        if (!session()->has('registration_user_id')) {
            return redirect()->route('register')->with('error', 'Session expired. Please register again.');
        }

        $userId = session('registration_user_id');
        $user = User::find($userId);

        if (!$user) {
            session()->forget('registration_user_id');
            return redirect()->route('register')->with('error', 'User not found. Please register again.');
        }

        // Generate QR code
        $google2fa = new \PragmaRX\Google2FA\Google2FA();
        $qrCodeUrl = $this->generateQRCodeUrl($user, $google2fa);

        // Generate recovery codes
        $recoveryCodes = $this->generateRecoveryCodes();

        return view('auth.register-qr-setup', compact('qrCodeUrl', 'recoveryCodes', 'user'));
    }

    /**
     * Verify QR code and complete registration
     */
    public function verifyQr(Request $request)
    {
        $request->validate([
            'one_time_password' => 'required|numeric|digits:6',
            'recovery_codes_confirmed' => 'required|accepted',
        ], [
            'recovery_codes_confirmed.accepted' => 'You must confirm that you have saved your recovery codes.',
        ]);

        if (!session()->has('registration_user_id')) {
            return redirect()->route('register')->with('error', 'Session expired. Please register again.');
        }

        $userId = session('registration_user_id');
        $user = User::find($userId);

        if (!$user) {
            session()->forget('registration_user_id');
            return redirect()->route('register')->with('error', 'User not found.');
        }

        $google2fa = new \PragmaRX\Google2FA\Google2FA();

        // Verify OTP with time window tolerance (8 = 4 minutes before/after)
        $valid = $google2fa->verifyKey($user->google2fa_secret, $request->one_time_password, 8);

        if (!$valid) {
            return back()->withErrors(['one_time_password' => 'The verification code is incorrect. Please try again.']);
        }

        // Mark 2FA as verified
        $user->two_factor_verified_at = now();

        // Store recovery codes (encrypted)
        $recoveryCodes = session('recovery_codes');
        $user->two_factor_recovery_codes = encrypt(json_encode($recoveryCodes));
        $user->save();

        // Get approval workflow from session
        $approvalWorkflow = session('registration_approval_workflow');

        // Send appropriate notifications NOW
        $this->sendNotifications($user, $approvalWorkflow);

        // Clear session data
        session()->forget(['registration_user_id', 'registration_approval_workflow', 'recovery_codes']);

        // Redirect with success message
        return redirect()->route('login')->with('success', 'Registration successful! Two-factor authentication has been set up. Please check your email to verify your account.');
    }

    /**
     * Generate QR code URL for Google Authenticator
     */
    private function generateQRCodeUrl(User $user, $google2fa)
    {
        $companyName = config('app.name');
        $qrCodeUrl = $google2fa->getQRCodeUrl(
            $companyName,
            $user->email,
            $user->google2fa_secret
        );

        // Generate SVG QR code
        $renderer = new \BaconQrCode\Renderer\ImageRenderer(
            new \BaconQrCode\Renderer\RendererStyle\RendererStyle(200),
            new \BaconQrCode\Renderer\Image\SvgImageBackEnd()
        );

        $writer = new \BaconQrCode\Writer($renderer);
        $qrCodeImage = $writer->writeString($qrCodeUrl);

        return 'data:image/svg+xml;base64,' . base64_encode($qrCodeImage);
    }

    /**
     * Generate recovery codes for 2FA
     */
    private function generateRecoveryCodes()
    {
        $codes = [];
        for ($i = 0; $i < 8; $i++) {
            $codes[] = strtoupper(Str::random(4) . '-' . Str::random(4) . '-' . Str::random(4));
        }

        // Store in session temporarily
        session(['recovery_codes' => $codes]);

        return $codes;
    }

    /**
     * Get program name from program ID
     */
    private function getProgramName(?string $programId): string
    {
        if (!$programId) {
            return 'Not Set';
        }

        $program = \App\Models\Program::find($programId);
        return $program ? $program->name : 'Not Set';
    }

    /**
     * Get program code from program ID
     */
    private function getProgramCode(?string $programId): ?string
    {
        if (!$programId) {
            return null;
        }

        $program = \App\Models\Program::find($programId);
        return $program ? $program->code : null;
    }
}
