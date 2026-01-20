<?php

namespace App\Http\Controllers\Hea;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AcademicAdvisor;
use App\Models\ProgramCoordinator;
use App\Models\ResourcePerson;
use App\Models\Program;
use App\Models\AuditTrail;
use App\Mail\UserApprovedMail;
use App\Mail\UserRejectedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UserManagementController extends Controller
{
    /**
     * Display user management dashboard
     */
    public function index()
    {
        $pendingCount = User::where('approval_status', 'pending')
                           ->whereIn('requested_role', ['academic_advisor', 'coordinator', 'resource_person'])
                           ->count();

        $approvedCount = User::where('approval_status', 'approved')
                            ->whereIn('current_role', ['academic_advisor', 'coordinator', 'resource_person'])
                            ->count();

        $rejectedCount = User::where('approval_status', 'rejected')
                            ->whereIn('requested_role', ['academic_advisor', 'coordinator', 'resource_person'])
                            ->count();

        $totalCount = User::whereIn('requested_role', ['academic_advisor', 'coordinator', 'resource_person'])
                         ->orWhereIn('current_role', ['academic_advisor', 'coordinator', 'resource_person'])
                         ->count();

        return view('hea.users.index', compact('pendingCount', 'approvedCount', 'rejectedCount', 'totalCount'));
    }

    /**
     * Display pending user approvals
     */
    public function pending()
    {
        $pendingUsers = User::where('approval_status', 'pending')
                           ->whereIn('requested_role', ['academic_advisor', 'coordinator', 'resource_person'])
                           ->orderBy('created_at', 'desc')
                           ->get();

        $allPrograms = Program::orderBy('code')->get();

        // Pass config data for editing program assignments
        $programGroups = config('programs.program_groups');
        $coordinatorCategories = config('programs.coordinator_categories');
        $supportedPrograms = config('programs.supported_programs');

        return view('hea.users.pending', compact(
            'pendingUsers',
            'allPrograms',
            'programGroups',
            'coordinatorCategories',
            'supportedPrograms'
        ));
    }

    /**
     * Display active users
     */
    public function active()
    {
        $activeUsers = User::where('approval_status', 'approved')
                          ->whereIn('current_role', ['academic_advisor', 'coordinator', 'resource_person'])
                          ->with(['academicAdvisor', 'programCoordinator', 'resourcePerson'])
                          ->orderBy('name')
                          ->get();

        $allPrograms = Program::orderBy('code')->get();

        return view('hea.users.active', compact('activeUsers', 'allPrograms'));
    }

    /**
     * Approve or reject a user
     */
    public function approve(Request $request, User $user)
    {
        // Check if rejection
        if ($request->has('reject') && $request->reject == '1') {
            $validated = $request->validate([
                'rejection_reason' => 'required|string|max:500',
            ]);

            $user->update([
                'approval_status' => 'rejected',
                'rejection_reason' => $validated['rejection_reason'],
                'approved_by' => Auth::id(),
            ]);

            // Create audit trail
            AuditTrail::create([
                'id' => Str::uuid(),
                'user_id' => Auth::id(),
                'action' => 'user_rejected',
                'details' => json_encode([
                    'rejected_user_id' => $user->id,
                    'rejected_user_email' => $user->email,
                    'requested_role' => $user->requested_role,
                    'rejection_reason' => $validated['rejection_reason'],
                ]),
            ]);

            Mail::to($user->email)->queue(new UserRejectedMail($user, $validated['rejection_reason']));

            return back()->with('success', "User {$user->name} rejected.");
        }

        // Approval flow - check if HEA modified the program assignment
        $role = $user->requested_role;

        // Determine the program data to use (modified by HEA or original request)
        $requestedProgramsData = $this->getApprovalProgramData($request, $user, $role);

        // Update user's requested_programs if HEA modified it (for record keeping)
        if ($request->has('modified_programs') && $request->modified_programs) {
            $user->requested_programs = is_array($requestedProgramsData)
                ? json_encode($requestedProgramsData)
                : $requestedProgramsData;
            $user->save();
        }

        DB::transaction(function() use ($user, $role, $requestedProgramsData) {
            // Update user
            $user->update([
                'role' => $role, // Legacy field for backward compatibility
                'current_role' => $role,
                'approval_status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            // Create role-specific record with appropriate data
            $this->createRoleRecord($user, $role, $requestedProgramsData);

            // Create audit trail
            AuditTrail::create([
                'id' => Str::uuid(),
                'user_id' => Auth::id(),
                'action' => 'user_approved',
                'details' => json_encode([
                    'approved_user_id' => $user->id,
                    'approved_user_email' => $user->email,
                    'assigned_role' => $role,
                    'program_data' => $requestedProgramsData,
                ]),
            ]);
        });

        // Send email verification notification outside transaction
        // This ensures the user is approved even if email fails
        $emailSent = true;
        try {
            $user->sendEmailVerificationNotification();
        } catch (\Exception $e) {
            $emailSent = false;
            Log::error('Failed to send verification email after approval', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'error' => $e->getMessage(),
            ]);
        }

        if ($emailSent) {
            return back()->with('success', "User {$user->name} approved successfully! Verification email sent.");
        } else {
            return back()->with('warning', "User {$user->name} approved, but verification email failed to send. Please ask the user to request a new verification link.");
        }
    }

    /**
     * Manage programs for a user
     */
    public function managePrograms(User $user)
    {
        $allPrograms = Program::orderBy('code')->get();
        $userPrograms = $this->getUserPrograms($user);

        return view('hea.users.manage-programs', compact('user', 'allPrograms', 'userPrograms'));
    }

    /**
     * Update programs for a user
     */
    public function updatePrograms(Request $request, User $user)
    {
        $validated = $request->validate([
            'programs' => 'required|array|min:1',
            'programs.*' => 'exists:programs,code',
        ], [
            'programs.required' => 'Please assign at least one program.',
            'programs.min' => 'Please assign at least one program.',
        ]);

        DB::transaction(function() use ($user, $validated) {
            $this->assignPrograms($user, $user->current_role, $validated['programs']);

            // Create audit trail
            AuditTrail::create([
                'id' => Str::uuid(),
                'user_id' => Auth::id(),
                'action' => 'programs_updated',
                'details' => json_encode([
                    'target_user_id' => $user->id,
                    'target_user_email' => $user->email,
                    'new_programs' => $validated['programs'],
                ]),
            ]);
        });

        return back()->with('success', "Programs updated for {$user->name}!");
    }

    /**
     * Resend verification email to an approved user who hasn't verified yet
     */
    public function resendVerification(User $user)
    {
        // Only allow resending for approved users who haven't verified
        if ($user->approval_status !== 'approved') {
            return back()->with('error', 'Can only resend verification to approved users.');
        }

        if ($user->hasVerifiedEmail()) {
            return back()->with('info', "{$user->name} has already verified their email.");
        }

        try {
            $user->sendEmailVerificationNotification();

            // Log the action
            AuditTrail::create([
                'id' => Str::uuid(),
                'user_id' => Auth::id(),
                'action' => 'verification_email_resent',
                'details' => json_encode([
                    'target_user_id' => $user->id,
                    'target_user_email' => $user->email,
                ]),
            ]);

            return back()->with('success', "Verification email resent to {$user->name}.");
        } catch (\Exception $e) {
            Log::error('Failed to resend verification email', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to send verification email. Please try again.');
        }
    }

    /**
     * Deactivate a user
     */
    public function deactivate(User $user)
    {
        $user->update([
            'approval_status' => 'rejected',
            'rejection_reason' => 'Account deactivated by HEA personnel',
            'approved_by' => Auth::id(),
        ]);

        // Create audit trail
        AuditTrail::create([
            'id' => Str::uuid(),
            'user_id' => Auth::id(),
            'action' => 'user_deactivated',
            'details' => json_encode([
                'deactivated_user_id' => $user->id,
                'deactivated_user_email' => $user->email,
            ]),
        ]);

        return back()->with('success', "User {$user->name} has been deactivated.");
    }

    /**
     * Create role-specific record when approving user
     */
    private function createRoleRecord(User $user, string $role, ?array $requestedProgramsData): void
    {
        $roleData = [
            'id' => Str::uuid(),
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ];

        match($role) {
            'academic_advisor' => AcademicAdvisor::create(array_merge($roleData, [
                'program_groups' => is_string($requestedProgramsData)
                    ? $requestedProgramsData
                    : json_encode($requestedProgramsData),
                'assigned_programs' => null, // Legacy field
            ])),

            'coordinator' => ProgramCoordinator::create(array_merge($roleData, [
                'program_category' => $requestedProgramsData['category'] ?? null,
                'program_codes' => null, // Legacy field
            ])),

            'resource_person' => ResourcePerson::create(array_merge($roleData, [
                'assigned_program' => $requestedProgramsData['program'] ?? null,
                'assigned_programs' => null, // Legacy field
                'expertise_area' => null, // Legacy field
            ])),

            default => null,
        };
    }

    /**
     * Assign programs to role-specific table (for legacy updatePrograms method)
     */
    private function assignPrograms(User $user, string $role, array $programs): void
    {
        $programsJson = json_encode($programs);

        match($role) {
            'academic_advisor' => AcademicAdvisor::updateOrCreate(
                ['user_id' => $user->id],
                ['id' => $user->academicAdvisor->id ?? Str::uuid(), 'assigned_programs' => $programsJson]
            ),
            'coordinator' => ProgramCoordinator::updateOrCreate(
                ['user_id' => $user->id],
                ['id' => $user->programCoordinator->id ?? Str::uuid(), 'assigned_programs' => $programsJson]
            ),
            'resource_person' => ResourcePerson::updateOrCreate(
                ['user_id' => $user->id],
                ['id' => $user->resourcePerson->id ?? Str::uuid(), 'assigned_programs' => $programsJson]
            ),
            default => null,
        };
    }

    /**
     * Get assigned programs for a user
     */
    private function getUserPrograms(User $user): array
    {
        $programs = match($user->current_role) {
            'academic_advisor' => $user->academicAdvisor?->assigned_programs,
            'coordinator' => $user->programCoordinator?->assigned_programs,
            'resource_person' => $user->resourcePerson?->assigned_programs,
            default => null,
        };

        return $programs ? json_decode($programs, true) : [];
    }

    /**
     * Get program data for approval - either modified by HEA or original request
     */
    private function getApprovalProgramData(Request $request, User $user, string $role): mixed
    {
        // If HEA didn't modify, return original requested data
        if (!$request->has('modified_programs') || !$request->modified_programs) {
            return $user->requested_programs;
        }

        // HEA modified the assignment - process based on role
        return match($role) {
            'academic_advisor' => $this->processAcademicAdvisorModification($request),
            'coordinator' => $this->processCoordinatorModification($request),
            'resource_person' => $this->processResourcePersonModification($request),
            default => $user->requested_programs,
        };
    }

    /**
     * Process Academic Advisor program group modifications
     */
    private function processAcademicAdvisorModification(Request $request): array
    {
        $programGroups = $request->input('program_groups', []);

        // Convert flat array to structured format: [{"program_code": "X", "group": "Y"}, ...]
        $structured = [];
        foreach ($programGroups as $groupCode) {
            // Extract program code from group code (e.g., CDCS2301B -> CDCS230)
            if (preg_match('/^(CDCS\d{3})/', $groupCode, $matches)) {
                $structured[] = [
                    'program_code' => $matches[1],
                    'group' => $groupCode,
                ];
            }
        }

        return $structured;
    }

    /**
     * Process Coordinator category modification
     */
    private function processCoordinatorModification(Request $request): array
    {
        return [
            'category' => $request->input('program_category'),
        ];
    }

    /**
     * Process Resource Person program modification
     */
    private function processResourcePersonModification(Request $request): array
    {
        return [
            'program' => $request->input('assigned_program'),
        ];
    }
}
