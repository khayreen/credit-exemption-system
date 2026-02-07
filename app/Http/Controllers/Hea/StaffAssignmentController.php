<?php

namespace App\Http\Controllers\Hea;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AcademicAdvisor;
use App\Models\ProgramCoordinator;
use App\Models\ResourcePerson;
use App\Models\ProgramGroupConfig;
use App\Models\AuditTrail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StaffAssignmentController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');

        $query = User::where('approval_status', 'approved')
            ->whereIn('current_role', ['academic_advisor', 'program_coordinator', 'resource_person'])
            ->with(['academicAdvisor', 'programCoordinator', 'resourcePerson']);

        if ($filter !== 'all') {
            $roleMap = [
                'academic_advisors' => 'academic_advisor',
                'coordinators' => 'program_coordinator',
                'resource_persons' => 'resource_person',
            ];
            $query->where('current_role', $roleMap[$filter] ?? $filter);
        }

        $staff = $query->orderBy('name')->get();

        $stats = [
            'total' => $staff->count(),
            'academic_advisors' => User::where('approval_status', 'approved')
                ->where('current_role', 'academic_advisor')->count(),
            'coordinators' => User::where('approval_status', 'approved')
                ->where('current_role', 'program_coordinator')->count(),
            'resource_persons' => User::where('approval_status', 'approved')
                ->where('current_role', 'resource_person')->count(),
        ];

        $supportedPrograms = ProgramGroupConfig::getSupportedPrograms();
        $coordinatorCategories = config('programs.coordinator_categories');

        return view('hea.staff_assignments.index', compact(
            'staff',
            'filter',
            'stats',
            'supportedPrograms',
            'coordinatorCategories'
        ));
    }

    public function edit(Request $request, User $user)
    {
        if (!in_array($user->current_role, ['academic_advisor', 'program_coordinator', 'resource_person'])) {
            return back()->with('error', 'Invalid staff member for assignment editing.');
        }

        // Get current academic session or use defaults
        $academicYear = $request->get('academic_year', ProgramGroupConfig::getCurrentAcademicYear());
        $intake = $request->get('intake', ProgramGroupConfig::getCurrentIntake());

        // Get active groups for the selected academic session
        $programGroups = ProgramGroupConfig::active()
            ->forSession($academicYear, $intake)
            ->orderBy('program_code')
            ->orderBy('semester')
            ->orderBy('group_letter')
            ->get()
            ->groupBy('program_code');

        $supportedPrograms = ProgramGroupConfig::getSupportedPrograms();
        $coordinatorCategories = config('programs.coordinator_categories');
        $academicYears = ProgramGroupConfig::getAcademicYears();
        $intakes = ProgramGroupConfig::getIntakes();

        $currentAssignment = $this->getCurrentAssignment($user);

        return view('hea.staff_assignments.edit', compact(
            'user',
            'programGroups',
            'supportedPrograms',
            'coordinatorCategories',
            'currentAssignment',
            'academicYear',
            'intake',
            'academicYears',
            'intakes'
        ));
    }

    public function update(Request $request, User $user)
    {
        if (!in_array($user->current_role, ['academic_advisor', 'program_coordinator', 'resource_person'])) {
            return back()->with('error', 'Invalid staff member for assignment editing.');
        }

        $oldAssignment = $this->getCurrentAssignment($user);

        DB::transaction(function () use ($request, $user) {
            match ($user->current_role) {
                'academic_advisor' => $this->updateAcademicAdvisorAssignment($request, $user),
                'program_coordinator' => $this->updateCoordinatorAssignment($request, $user),
                'resource_person' => $this->updateResourcePersonAssignment($request, $user),
            };
        });

        $newAssignment = $this->getCurrentAssignment($user);

        AuditTrail::create([
            'id' => Str::uuid(),
            'user_id' => Auth::id(),
            'action' => 'staff_assignment_updated',
            'details' => json_encode([
                'target_user_id' => $user->id,
                'target_user_name' => $user->name,
                'role' => $user->current_role,
                'old_assignment' => $oldAssignment,
                'new_assignment' => $newAssignment,
            ]),
        ]);

        return redirect()->route('hea.users.active')
            ->with('success', "Assignment updated for {$user->name}.");
    }

    private function getCurrentAssignment(User $user): array
    {
        return match ($user->current_role) {
            'academic_advisor' => [
                'type' => 'program_groups',
                'data' => $this->getAcademicAdvisorGroups($user),
            ],
            'program_coordinator' => [
                'type' => 'category',
                'data' => $user->programCoordinator?->program_category,
            ],
            'resource_person' => [
                'type' => 'program',
                'data' => $user->resourcePerson?->assigned_program,
            ],
            default => ['type' => 'unknown', 'data' => null],
        };
    }

    private function getAcademicAdvisorGroups(User $user): array
    {
        // Get groups from ProgramGroupConfig where this user is assigned
        $assignedGroups = ProgramGroupConfig::where('assigned_user_id', $user->id)
            ->get()
            ->map(function ($config) {
                return [
                    'program_code' => $config->program_code,
                    'semester' => $config->semester,
                    'group_letter' => $config->group_letter,
                    'group' => $config->group_code,
                ];
            })
            ->toArray();

        // If no groups found in ProgramGroupConfig, fall back to AcademicAdvisor model
        if (empty($assignedGroups) && $user->academicAdvisor?->program_groups) {
            return $user->academicAdvisor->program_groups;
        }

        return $assignedGroups;
    }

    private function updateAcademicAdvisorAssignment(Request $request, User $user): void
    {
        $validated = $request->validate([
            'program_groups' => 'nullable|array',
            'program_groups.*' => 'string',
        ]);

        $programGroups = $validated['program_groups'] ?? [];

        $structuredGroups = [];
        foreach ($programGroups as $groupCode) {
            if (preg_match('/^(CDCS\d{3})(\d)([A-E])$/', $groupCode, $matches)) {
                $structuredGroups[] = [
                    'program_code' => $matches[1],
                    'semester' => (int) $matches[2],
                    'group_letter' => $matches[3],
                    'group' => $groupCode,
                ];
            }
        }

        // Update AcademicAdvisor model
        if ($user->academicAdvisor) {
            $user->academicAdvisor->update([
                'program_groups' => $structuredGroups,
            ]);
        } else {
            AcademicAdvisor::create([
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'program_groups' => $structuredGroups,
            ]);
        }

        // Also update ProgramGroupConfig assignments
        // First, unassign this user from all groups they were previously assigned to
        ProgramGroupConfig::where('assigned_user_id', $user->id)->update(['assigned_user_id' => null]);

        // Then assign the user to the selected groups
        foreach ($programGroups as $groupCode) {
            ProgramGroupConfig::where('group_code', $groupCode)->update(['assigned_user_id' => $user->id]);
        }
    }

    private function updateCoordinatorAssignment(Request $request, User $user): void
    {
        $validated = $request->validate([
            'program_category' => 'required|in:category_1,category_2',
        ]);

        if ($user->programCoordinator) {
            $user->programCoordinator->update([
                'program_category' => $validated['program_category'],
            ]);
        } else {
            ProgramCoordinator::create([
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'program_category' => $validated['program_category'],
            ]);
        }
    }

    private function updateResourcePersonAssignment(Request $request, User $user): void
    {
        $validated = $request->validate([
            'assigned_program' => 'required|in:' . implode(',', array_keys(ProgramGroupConfig::getSupportedPrograms())),
        ]);

        if ($user->resourcePerson) {
            $user->resourcePerson->update([
                'assigned_program' => $validated['assigned_program'],
            ]);
        } else {
            ResourcePerson::create([
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'assigned_program' => $validated['assigned_program'],
            ]);
        }
    }
}
