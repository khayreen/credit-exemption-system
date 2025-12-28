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

        return view('hea.users.pending', compact('pendingUsers', 'allPrograms'));
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

            Mail::to($user->email)->send(new UserRejectedMail($user, $validated['rejection_reason']));

            return back()->with('success', "User {$user->name} rejected.");
        }

        // Approval flow
        $validated = $request->validate([
            'role' => 'required|in:academic_advisor,coordinator,resource_person',
            'programs' => 'required|array|min:1',
            'programs.*' => 'exists:programs,code',
        ], [
            'programs.required' => 'Please assign at least one program.',
            'programs.min' => 'Please assign at least one program.',
        ]);

        DB::transaction(function() use ($user, $validated) {
            // Update user
            $user->update([
                'role' => $validated['role'], // Legacy field for backward compatibility
                'current_role' => $validated['role'],
                'approval_status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            // Assign programs to role-specific table
            $this->assignPrograms($user, $validated['role'], $validated['programs']);

            // Create audit trail
            AuditTrail::create([
                'id' => Str::uuid(),
                'user_id' => Auth::id(),
                'action' => 'user_approved',
                'details' => json_encode([
                    'approved_user_id' => $user->id,
                    'approved_user_email' => $user->email,
                    'assigned_role' => $validated['role'],
                    'requested_programs' => json_decode($user->requested_programs),
                    'final_programs' => $validated['programs'],
                ]),
            ]);

            // Send email verification notification for approved user
            $user->sendEmailVerificationNotification();
        });

        return back()->with('success', "User {$user->name} approved with " . count($validated['programs']) . " programs assigned!");
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
     * Assign programs to role-specific table
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
}
