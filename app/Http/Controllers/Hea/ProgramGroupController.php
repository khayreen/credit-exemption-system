<?php

namespace App\Http\Controllers\Hea;

use App\Http\Controllers\Controller;
use App\Models\ProgramGroupConfig;
use App\Models\User;
use App\Models\AcademicAdvisor;
use App\Models\AuditTrail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProgramGroupController extends Controller
{
    /**
     * Display the program group configuration page with dropdowns
     */
    public function index(Request $request)
    {
        // Get filter values from request or use defaults
        $academicYear = $request->get('academic_year', ProgramGroupConfig::getCurrentAcademicYear());
        $intake = $request->get('intake', ProgramGroupConfig::getCurrentIntake());
        $programCode = $request->get('program_code', array_key_first(ProgramGroupConfig::getSupportedPrograms()));
        $semester = $request->get('semester', 1);

        // Get data for dropdowns
        $academicYears = ProgramGroupConfig::getAcademicYears();
        $intakes = ProgramGroupConfig::getIntakes();
        $supportedPrograms = ProgramGroupConfig::getSupportedPrograms();
        $semesters = ProgramGroupConfig::getSemesters();
        $groupLetters = ProgramGroupConfig::getGroupLetters();

        // Get configured groups for the selected context with assigned user
        $configuredGroups = ProgramGroupConfig::forProgram($programCode)
            ->forSemester($semester)
            ->forSession($academicYear, $intake)
            ->with('assignedUser')
            ->get()
            ->keyBy('group_letter');

        // Get all approved Academic Advisors for dropdown
        $academicAdvisors = User::where('approval_status', 'approved')
            ->where('current_role', 'academic_advisor')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        // Get summary stats for sidebar
        $sessionStats = $this->getSessionStats($academicYear, $intake);

        // Get all active groups for current session (for the overview section)
        $allActiveGroups = ProgramGroupConfig::active()
            ->forSession($academicYear, $intake)
            ->with('assignedUser')
            ->orderBy('program_code')
            ->orderBy('semester')
            ->orderBy('group_letter')
            ->get()
            ->groupBy('program_code');

        return view('hea.program_groups.index', compact(
            'academicYear',
            'intake',
            'programCode',
            'semester',
            'academicYears',
            'intakes',
            'supportedPrograms',
            'semesters',
            'groupLetters',
            'configuredGroups',
            'academicAdvisors',
            'sessionStats',
            'allActiveGroups'
        ));
    }

    /**
     * Save the group configuration for selected context
     */
    public function saveConfiguration(Request $request)
    {
        $validated = $request->validate([
            'academic_year' => 'required|string|max:9',
            'intake' => 'required|in:sesi_1,sesi_2',
            'program_code' => 'required|in:' . implode(',', array_keys(ProgramGroupConfig::getSupportedPrograms())),
            'semester' => 'required|integer|min:1|max:7',
            'groups' => 'nullable|array',
            'groups.*' => 'in:' . implode(',', ProgramGroupConfig::getGroupLetters()),
            'assignments' => 'nullable|array',
            'assignments.*' => 'nullable|uuid|exists:users,id',
        ]);

        $academicYear = $validated['academic_year'];
        $intake = $validated['intake'];
        $programCode = $validated['program_code'];
        $semester = $validated['semester'];
        $selectedGroups = $validated['groups'] ?? [];
        $assignments = $validated['assignments'] ?? [];

        $created = 0;
        $activated = 0;
        $deactivated = 0;
        $assigned = 0;
        $affectedUserIds = [];

        DB::transaction(function () use ($programCode, $semester, $academicYear, $intake, $selectedGroups, $assignments, &$created, &$activated, &$deactivated, &$assigned, &$affectedUserIds) {
            foreach (ProgramGroupConfig::getGroupLetters() as $letter) {
                $isSelected = in_array($letter, $selectedGroups);
                $assignedUserId = $assignments[$letter] ?? null;
                // Convert empty string to null
                if ($assignedUserId === '') {
                    $assignedUserId = null;
                }

                $existing = ProgramGroupConfig::forProgram($programCode)
                    ->forSemester($semester)
                    ->forSession($academicYear, $intake)
                    ->where('group_letter', $letter)
                    ->first();

                if ($existing) {
                    // Track affected users for sync
                    if ($existing->assigned_user_id && $existing->assigned_user_id !== $assignedUserId) {
                        $affectedUserIds[] = $existing->assigned_user_id;
                    }
                    if ($assignedUserId && $existing->assigned_user_id !== $assignedUserId) {
                        $affectedUserIds[] = $assignedUserId;
                    }

                    // Update existing record
                    $changes = [];

                    if ($existing->is_active !== $isSelected) {
                        $changes['is_active'] = $isSelected;
                        if ($isSelected) {
                            $activated++;
                        } else {
                            $deactivated++;
                        }
                    }

                    // Update assignment if group is active
                    if ($isSelected && $existing->assigned_user_id !== $assignedUserId) {
                        $changes['assigned_user_id'] = $assignedUserId;
                        if ($assignedUserId) {
                            $assigned++;
                        }
                    }

                    // Clear assignment if group is deactivated
                    if (!$isSelected && $existing->assigned_user_id) {
                        $changes['assigned_user_id'] = null;
                    }

                    if (!empty($changes)) {
                        $existing->update($changes);
                    }
                } elseif ($isSelected) {
                    // Track new assignment
                    if ($assignedUserId) {
                        $affectedUserIds[] = $assignedUserId;
                    }

                    // Create new record only if selected
                    ProgramGroupConfig::create([
                        'program_code' => $programCode,
                        'semester' => $semester,
                        'group_letter' => $letter,
                        'academic_year' => $academicYear,
                        'intake' => $intake,
                        'is_active' => true,
                        'assigned_user_id' => $assignedUserId,
                        'created_by' => Auth::id(),
                    ]);
                    $created++;
                    if ($assignedUserId) {
                        $assigned++;
                    }
                }
            }
        });

        // Sync AcademicAdvisor.program_groups for all affected users
        $this->syncAcademicAdvisorGroups(array_unique($affectedUserIds));

        // Create audit trail
        AuditTrail::create([
            'id' => Str::uuid(),
            'user_id' => Auth::id(),
            'action' => 'program_groups_configured',
            'details' => json_encode([
                'program_code' => $programCode,
                'semester' => $semester,
                'academic_year' => $academicYear,
                'intake' => $intake,
                'selected_groups' => $selectedGroups,
                'assignments' => $assignments,
                'created' => $created,
                'activated' => $activated,
                'deactivated' => $deactivated,
                'assigned' => $assigned,
            ]),
        ]);

        $message = "Configuration saved for {$programCode} Semester {$semester} ({$intake} {$academicYear}).";
        if ($created > 0) $message .= " Created: {$created}.";
        if ($activated > 0) $message .= " Activated: {$activated}.";
        if ($deactivated > 0) $message .= " Deactivated: {$deactivated}.";
        if ($assigned > 0) $message .= " Assigned: {$assigned}.";

        return redirect()->route('hea.program_groups.index', [
            'academic_year' => $academicYear,
            'intake' => $intake,
            'program_code' => $programCode,
            'semester' => $semester,
        ])->with('success', $message);
    }

    /**
     * Display the group assignments overview page
     */
    public function assignments(Request $request)
    {
        $academicYear = $request->get('academic_year', ProgramGroupConfig::getCurrentAcademicYear());
        $intake = $request->get('intake', ProgramGroupConfig::getCurrentIntake());

        $academicYears = ProgramGroupConfig::getAcademicYears();
        $intakes = ProgramGroupConfig::getIntakes();
        $supportedPrograms = ProgramGroupConfig::getSupportedPrograms();

        // Get all active groups with their assigned users, grouped by program
        $allGroups = ProgramGroupConfig::active()
            ->forSession($academicYear, $intake)
            ->with('assignedUser')
            ->orderBy('program_code')
            ->orderBy('semester')
            ->orderBy('group_letter')
            ->get();

        // Group by program code with stats
        $assignmentsByProgram = $allGroups->groupBy('program_code')->map(function ($groups, $programCode) {
            return [
                'groups' => $groups,
                'stats' => [
                    'total' => $groups->count(),
                    'assigned' => $groups->whereNotNull('assigned_user_id')->count(),
                    'unassigned' => $groups->whereNull('assigned_user_id')->count(),
                ],
            ];
        });

        // Summary statistics
        $summary = [
            'total_groups' => $allGroups->count(),
            'assigned' => $allGroups->whereNotNull('assigned_user_id')->count(),
            'unassigned' => $allGroups->whereNull('assigned_user_id')->count(),
            'total_advisors' => $allGroups->whereNotNull('assigned_user_id')->pluck('assigned_user_id')->unique()->count(),
        ];

        // Advisor workload - how many groups each advisor has
        $advisorWorkload = $allGroups->whereNotNull('assigned_user_id')
            ->groupBy('assigned_user_id')
            ->map(function ($groups) {
                $user = $groups->first()->assignedUser;
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'count' => $groups->count(),
                    'groups' => $groups->pluck('group_code')->toArray(),
                ];
            })
            ->sortByDesc('count')
            ->values();

        return view('hea.program_groups.assignments', compact(
            'academicYear',
            'intake',
            'academicYears',
            'intakes',
            'supportedPrograms',
            'assignmentsByProgram',
            'summary',
            'advisorWorkload'
        ));
    }

    /**
     * Get session statistics for sidebar display
     */
    private function getSessionStats(string $academicYear, string $intake): array
    {
        $stats = [];
        $programs = ProgramGroupConfig::getSupportedPrograms();

        foreach ($programs as $code => $name) {
            $total = ProgramGroupConfig::forProgram($code)
                ->forSession($academicYear, $intake)
                ->count();

            $active = ProgramGroupConfig::forProgram($code)
                ->forSession($academicYear, $intake)
                ->active()
                ->count();

            $assigned = ProgramGroupConfig::forProgram($code)
                ->forSession($academicYear, $intake)
                ->active()
                ->assigned()
                ->count();

            $unassigned = $active - $assigned;

            $stats[$code] = [
                'name' => $name,
                'total' => $total,
                'active' => $active,
                'assigned' => $assigned,
                'unassigned' => $unassigned,
            ];
        }

        return $stats;
    }

    /**
     * Sync AcademicAdvisor.program_groups with ProgramGroupConfig assignments
     */
    private function syncAcademicAdvisorGroups(array $userIds): void
    {
        foreach ($userIds as $userId) {
            $user = User::find($userId);
            if (!$user || $user->current_role !== 'academic_advisor') {
                continue;
            }

            // Get all groups assigned to this user from ProgramGroupConfig
            $assignedGroups = ProgramGroupConfig::where('assigned_user_id', $userId)
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

            // Update or create AcademicAdvisor record
            if ($user->academicAdvisor) {
                $user->academicAdvisor->update([
                    'program_groups' => $assignedGroups,
                ]);
            } else {
                AcademicAdvisor::create([
                    'id' => Str::uuid(),
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'program_groups' => $assignedGroups,
                ]);
            }
        }
    }
}
