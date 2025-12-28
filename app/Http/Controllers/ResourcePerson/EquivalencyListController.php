<?php

namespace App\Http\Controllers\ResourcePerson;

use App\Http\Controllers\Controller;
use App\Models\EquivalencyList;
use App\Models\PendingEquivalencyMapping;
use App\Models\ResourcePerson;
use App\Models\CourseEquivalencyRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EquivalencyListController extends Controller
{
    /**
     * Get the current resource person
     */
    private function getResourcePerson()
    {
        return ResourcePerson::where('user_id', Auth::id())->first();
    }

    /**
     * Get assigned programs for the current resource person
     */
    private function getAssignedPrograms()
    {
        $rp = $this->getResourcePerson();
        return $rp ? ($rp->assigned_programs ?? []) : [];
    }

    /**
     * Display CS110 Internal Equivalency Lists Management
     * NEW ARCHITECTURE: Shows ONE continuous list per program
     */
    public function index()
    {
        $resourcePerson = $this->getResourcePerson();
        $assignedPrograms = $this->getAssignedPrograms();
        $programNames = $this->getProgramNames($assignedPrograms);

        // Get ONE CS110 list per assigned program
        $programLists = [];
        foreach ($assignedPrograms as $programCode) {
            $list = EquivalencyList::internal()
                ->where('program_code', $programCode)
                ->whereNull('source_institution')
                ->with(['reviewer', 'endorser', 'publisher', 'courseEquivalencies', 'courseEquivalencyHistory'])
                ->first();

            $programLists[$programCode] = [
                'list' => $list,
                'program_name' => $programNames[$programCode] ?? $programCode,
                'exists' => $list !== null,
                'status' => $list ? $list->status : 'not_created',
                'total_mappings' => $list ? $list->total_equivalencies : 0,
                'last_published' => $list && $list->published_at ? $list->published_at : null,
                'last_semester' => $list && $list->semester ? $list->semester : null,
            ];
        }

        return view('resource_person.cs110_lists.index', compact(
            'resourcePerson',
            'assignedPrograms',
            'programNames',
            'programLists'
        ));
    }

    /**
     * Display all equivalency lists (READ-ONLY for Resource Person)
     * Same view as Program Coordinator sees
     */
    public function viewAllLists()
    {
        // Get all lists grouped by category
        $internalLists = EquivalencyList::internal()
            ->with(['creator', 'publisher'])
            ->orderBy('semester', 'desc')
            ->orderBy('program_code')
            ->get()
            ->groupBy('program_code');

        $externalLists = EquivalencyList::external()
            ->with(['creator', 'publisher'])
            ->orderBy('semester', 'desc')
            ->orderBy('program_code')
            ->orderBy('source_institution')
            ->get()
            ->groupBy('source_institution');

        // Get pending mappings from Resource Persons
        $pendingMappingsCount = PendingEquivalencyMapping::pending()->count();

        // Statistics
        $stats = [
            'total_lists' => EquivalencyList::count(),
            'published_lists' => EquivalencyList::published()->count(),
            'draft_lists' => EquivalencyList::draft()->count(),
            'pending_mappings' => $pendingMappingsCount,
        ];

        return view('resource_person.equivalency_lists.view_all', compact(
            'internalLists',
            'externalLists',
            'stats'
        ));
    }

    /**
     * Edit CS110 Internal Equivalency List
     * NEW ARCHITECTURE: Auto-creates list if doesn't exist for program
     */
    public function edit(string $programCode)
    {
        $assignedPrograms = $this->getAssignedPrograms();

        // Verify program is assigned to this resource person
        if (!in_array($programCode, $assignedPrograms)) {
            abort(403, 'You are not assigned to this program.');
        }

        $programNames = $this->getProgramNames($assignedPrograms);

        DB::beginTransaction();
        try {
            // Get or create the ONE CS110 list for this program
            $list = EquivalencyList::internal()
                ->where('program_code', $programCode)
                ->whereNull('source_institution')
                ->first();

            if (!$list) {
                // Auto-create if doesn't exist
                $list = EquivalencyList::create([
                    'program_code' => $programCode,
                    'program_name' => $programNames[$programCode] ?? $programCode,
                    'category' => EquivalencyList::CATEGORY_INTERNAL,
                    'source_institution' => null,
                    'status' => EquivalencyList::STATUS_DRAFT,
                    'created_by_user_id' => Auth::id(),
                ]);
            }

            // IMPORTANT: If list is published, auto-revert to draft for continuous editing
            // This handles edge cases where publish() didn't auto-revert
            if ($list->status === EquivalencyList::STATUS_PUBLISHED) {
                $list->update([
                    'status' => EquivalencyList::STATUS_DRAFT,
                    'target_semester' => null,
                    // Keep semester, published_at, published_by for historical record
                    // Clear submission/review/endorsement data for next cycle
                    'submitted_at' => null,
                    'submission_notes' => null,
                    'reviewed_by_user_id' => null,
                    'reviewed_at' => null,
                    'review_notes' => null,
                    'endorsed_by_user_id' => null,
                    'endorsed_at' => null,
                    'endorsement_notes' => null,
                ]);
            }

            // Can only edit if in draft or rejected status
            if (!$list->canBeEdited()) {
                return redirect()->route('resource_person.equivalency_lists.index')
                    ->withErrors(['error' => 'This list cannot be edited. Current status: ' . $list->status_display]);
            }

            $list->load(['courseEquivalencies', 'courseEquivalencyHistory']);

            DB::commit();

            return view('resource_person.cs110_lists.edit', compact('list', 'programCode'));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('resource_person.equivalency_lists.index')
                ->withErrors(['error' => 'Failed to load list: ' . $e->getMessage()]);
        }
    }

    /**
     * Submit the list to HEA for review
     * NEW ARCHITECTURE: Requires target_semester for publication
     */
    public function submit(Request $request, string $programCode)
    {
        $request->validate([
            'target_semester' => 'required|string|max:20',
            'submission_notes' => 'nullable|string|max:1000',
        ]);

        // Verify the resource person is assigned to this program
        $assignedPrograms = $this->getAssignedPrograms();
        if (!in_array($programCode, $assignedPrograms)) {
            abort(403, 'You are not assigned to this program.');
        }

        // Get the list for this program
        $list = EquivalencyList::internal()
            ->where('program_code', $programCode)
            ->whereNull('source_institution')
            ->first();

        if (!$list) {
            return back()->withErrors(['error' => 'List not found for this program.']);
        }

        if (!$list->canBeSubmitted()) {
            return back()->withErrors(['error' => 'This list cannot be submitted. Ensure it has at least one course mapping.']);
        }

        DB::beginTransaction();
        try {
            $list->submit(Auth::user(), $request->target_semester, $request->submission_notes);

            // TODO: Send notification to HEA Personnel
            // Mail::to($heaPersonnel)->send(new EquivalencyListSubmitted($list));

            DB::commit();

            return redirect()->route('resource_person.equivalency_lists.index')
                ->with('success', 'CS110 equivalency list submitted to HEA for semester ' . $request->target_semester);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to submit list: ' . $e->getMessage()]);
        }
    }

    /**
     * View a CS110 Internal Equivalency List
     */
    public function show(EquivalencyList $list)
    {
        // Verify ownership or published status
        if ($list->created_by_user_id !== Auth::id() && !$list->isPublished()) {
            abort(403, 'Unauthorized access.');
        }

        $list->load(['reviewer', 'endorser', 'publisher', 'courseEquivalencies']);

        return view('resource_person.cs110_lists.show', compact('list'));
    }

    /**
     * Add a course mapping to the CS110 equivalency list
     */
    public function addMapping(Request $request, string $programCode)
    {
        $request->validate([
            'diploma_course_code' => 'required|string|max:50',
            'diploma_course_name' => 'required|string|max:255',
            'diploma_credit_hour' => 'required|integer|min:1|max:10',
            'degree_course_code' => 'required|string|max:50',
            'degree_course_name' => 'required|string|max:255',
            'degree_credit_hour' => 'required|integer|min:1|max:10',
            'match_percentage' => 'required|numeric|min:0|max:100',
            'is_eligible' => 'required|boolean',
        ]);

        // Verify the resource person is assigned to this program
        $assignedPrograms = $this->getAssignedPrograms();
        if (!in_array($programCode, $assignedPrograms)) {
            abort(403, 'You are not assigned to this program.');
        }

        // Get the list for this program
        $list = EquivalencyList::internal()
            ->where('program_code', $programCode)
            ->whereNull('source_institution')
            ->first();

        if (!$list) {
            return back()->withErrors(['error' => 'List not found for this program.']);
        }

        // Can only add mappings to draft or rejected lists
        if (!$list->canBeEdited()) {
            return back()->withErrors(['error' => 'This list cannot be edited in its current status.']);
        }

        DB::beginTransaction();
        try {
            // Create the course equivalency
            $equivalency = \App\Models\CourseEquivalency::create([
                'equivalency_list_id' => $list->id,
                'program_code' => $list->program_code,
                'diploma_course_code' => strtoupper($request->diploma_course_code),
                'diploma_course_name' => $request->diploma_course_name,
                'diploma_credit_hour' => $request->diploma_credit_hour,
                'diploma_institution' => 'CS110', // Always CS110 for internal lists
                'degree_course_code' => strtoupper($request->degree_course_code),
                'degree_course_name' => $request->degree_course_name,
                'degree_credit_hour' => $request->degree_credit_hour,
                'match_percentage' => $request->match_percentage,
                'is_eligible' => $request->is_eligible,
                'source' => 'hea_endorsed', // Will be published by HEA
                'is_published' => false,
                'last_updated_by_user_id' => Auth::id(),
                'last_updated_at' => now(),
            ]);

            // Update list statistics
            $list->updateStatistics();

            DB::commit();

            return back()->with('success', 'Course mapping added successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to add mapping: ' . $e->getMessage()]);
        }
    }

    /**
     * Update an existing course mapping in the CS110 equivalency list
     */
    public function updateCourseMapping(Request $request, string $programCode, \App\Models\CourseEquivalency $mapping)
    {
        $request->validate([
            'diploma_course_code' => 'required|string|max:50',
            'diploma_course_name' => 'required|string|max:255',
            'diploma_credit_hour' => 'required|integer|min:1|max:10',
            'degree_course_code' => 'required|string|max:50',
            'degree_course_name' => 'required|string|max:255',
            'degree_credit_hour' => 'required|integer|min:1|max:10',
            'match_percentage' => 'required|numeric|min:0|max:100',
            'is_eligible' => 'required|boolean',
        ]);

        // Verify the resource person is assigned to this program
        $assignedPrograms = $this->getAssignedPrograms();
        if (!in_array($programCode, $assignedPrograms)) {
            abort(403, 'You are not assigned to this program.');
        }

        // Get the list for this program
        $list = EquivalencyList::internal()
            ->where('program_code', $programCode)
            ->whereNull('source_institution')
            ->first();

        if (!$list) {
            return back()->withErrors(['error' => 'List not found for this program.']);
        }

        // Verify the mapping belongs to this list
        if ($mapping->equivalency_list_id !== $list->id) {
            abort(403, 'This mapping does not belong to this list.');
        }

        // Can only edit mappings in draft or rejected lists
        if (!$list->canBeEdited()) {
            return back()->withErrors(['error' => 'This list cannot be edited in its current status.']);
        }

        DB::beginTransaction();
        try {
            // Update the course equivalency
            $mapping->update([
                'diploma_course_code' => strtoupper($request->diploma_course_code),
                'diploma_course_name' => $request->diploma_course_name,
                'diploma_credit_hour' => $request->diploma_credit_hour,
                'degree_course_code' => strtoupper($request->degree_course_code),
                'degree_course_name' => $request->degree_course_name,
                'degree_credit_hour' => $request->degree_credit_hour,
                'match_percentage' => $request->match_percentage,
                'is_eligible' => $request->is_eligible,
            ]);

            // Update list statistics
            $list->updateStatistics();

            DB::commit();

            return back()->with('success', 'Course mapping updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to update mapping: ' . $e->getMessage()]);
        }
    }

    /**
     * Delete a course mapping from the CS110 equivalency list
     * NEW ARCHITECTURE: Preserves deleted mapping in history
     */
    public function deleteMapping(string $programCode, \App\Models\CourseEquivalency $mapping)
    {
        // Verify the resource person is assigned to this program
        $assignedPrograms = $this->getAssignedPrograms();
        if (!in_array($programCode, $assignedPrograms)) {
            abort(403, 'You are not assigned to this program.');
        }

        // Get the list for this program
        $list = EquivalencyList::internal()
            ->where('program_code', $programCode)
            ->whereNull('source_institution')
            ->first();

        if (!$list) {
            return back()->withErrors(['error' => 'List not found for this program.']);
        }

        // Can only delete mappings from draft or rejected lists
        if (!$list->canBeEdited()) {
            return back()->withErrors(['error' => 'This list cannot be edited in its current status.']);
        }

        // Verify the mapping belongs to this list
        if ($mapping->equivalency_list_id !== $list->id) {
            abort(404, 'Mapping not found in this list.');
        }

        DB::beginTransaction();
        try {
            // Archive the mapping to history before deletion
            \App\Models\CourseEquivalencyHistory::create([
                'equivalency_list_id' => $list->id,
                'original_equivalency_id' => $mapping->id,
                'program_code' => $mapping->program_code,
                'diploma_course_code' => $mapping->diploma_course_code,
                'diploma_course_name' => $mapping->diploma_course_name,
                'diploma_credit_hour' => $mapping->diploma_credit_hour,
                'diploma_institution' => $mapping->diploma_institution,
                'degree_course_code' => $mapping->degree_course_code,
                'degree_course_name' => $mapping->degree_course_name,
                'degree_credit_hour' => $mapping->degree_credit_hour,
                'match_percentage' => $mapping->match_percentage,
                'is_eligible' => $mapping->is_eligible,
                'action' => \App\Models\CourseEquivalencyHistory::ACTION_DELETED,
                'archived_by_user_id' => Auth::id(),
                'archived_at' => now(),
                'archive_reason' => 'Deleted by Resource Person',
            ]);

            // Delete the mapping
            $mapping->delete();

            // Update list statistics
            $list->updateStatistics();

            DB::commit();

            return back()->with('success', 'Course mapping deleted and archived to history.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to delete mapping: ' . $e->getMessage()]);
        }
    }

    /**
     * Show form to create and forward a new course mapping to Program Coordinator
     */
    public function createMapping(Request $request)
    {
        $assignedPrograms = $this->getAssignedPrograms();
        $programs = $this->getProgramNames($assignedPrograms);
        $institutions = $this->getInstitutionsList();

        // Check if this is from a student equivalency request
        $courseEquivalencyRequestId = $request->get('request_id');
        $studentRequest = null;

        if ($courseEquivalencyRequestId) {
            $studentRequest = CourseEquivalencyRequest::find($courseEquivalencyRequestId);
        }

        return view('resource_person.equivalency_mappings.create', compact(
            'programs',
            'institutions',
            'assignedPrograms',
            'studentRequest'
        ));
    }

    /**
     * Store and forward a new course mapping to Program Coordinator
     */
    public function storeMapping(Request $request)
    {
        $request->validate([
            'program_code' => 'required|string|max:20',
            'diploma_course_code' => 'required|string|max:50',
            'diploma_course_name' => 'required|string|max:255',
            'diploma_credit_hour' => 'required|integer|min:1|max:10',
            'diploma_institution' => 'required|string|max:255',
            'degree_course_code' => 'required|string|max:50',
            'degree_course_name' => 'required|string|max:255',
            'degree_credit_hour' => 'required|integer|min:1|max:10',
            'match_percentage' => 'required|numeric|min:0|max:100',
            'notes' => 'nullable|string|max:1000',
            'course_equivalency_request_id' => 'nullable|exists:course_equivalency_requests,id',
        ]);

        $assignedPrograms = $this->getAssignedPrograms();

        // Verify the program is assigned to this resource person
        if (!in_array($request->program_code, $assignedPrograms)) {
            return back()->withErrors(['program_code' => 'You are not authorized to create mappings for this program.']);
        }

        DB::beginTransaction();
        try {
            // Create pending mapping for Program Coordinator
            $mapping = PendingEquivalencyMapping::create([
                'resource_person_user_id' => Auth::id(),
                'program_code' => $request->program_code,
                'diploma_course_code' => strtoupper($request->diploma_course_code),
                'diploma_course_name' => $request->diploma_course_name,
                'diploma_credit_hour' => $request->diploma_credit_hour,
                'diploma_institution' => $request->diploma_institution,
                'degree_course_code' => strtoupper($request->degree_course_code),
                'degree_course_name' => $request->degree_course_name,
                'degree_credit_hour' => $request->degree_credit_hour,
                'match_percentage' => $request->match_percentage,
                'notes' => $request->notes,
                'status' => PendingEquivalencyMapping::STATUS_PENDING,
                'course_equivalency_request_id' => $request->course_equivalency_request_id,
            ]);

            // TODO: Send notification to Program Coordinators
            // $coordinators = User::where('role', 'program_coordinator')->get();
            // foreach ($coordinators as $coordinator) {
            //     Mail::to($coordinator->email)->send(new NewMappingForwarded($mapping));
            // }

            DB::commit();

            return redirect()->route('resource_person.equivalency_lists.index')
                ->with('success', 'Course mapping forwarded to Program Coordinator successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to forward mapping: ' . $e->getMessage()]);
        }
    }

    /**
     * View details of a forwarded mapping
     */
    public function showMapping(PendingEquivalencyMapping $mapping)
    {
        // Verify ownership
        if ($mapping->resource_person_user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $mapping->load(['coordinator', 'courseEquivalencyRequest']);

        return view('resource_person.equivalency_mappings.show', compact('mapping'));
    }

    /**
     * Update a pending mapping (only if still pending)
     */
    public function updateMapping(Request $request, PendingEquivalencyMapping $mapping)
    {
        // Verify ownership
        if ($mapping->resource_person_user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        // Can only update pending mappings
        if (!$mapping->isPending()) {
            return back()->withErrors(['error' => 'This mapping has already been processed and cannot be updated.']);
        }

        $request->validate([
            'diploma_course_code' => 'required|string|max:50',
            'diploma_course_name' => 'required|string|max:255',
            'diploma_credit_hour' => 'required|integer|min:1|max:10',
            'diploma_institution' => 'required|string|max:255',
            'degree_course_code' => 'required|string|max:50',
            'degree_course_name' => 'required|string|max:255',
            'degree_credit_hour' => 'required|integer|min:1|max:10',
            'match_percentage' => 'required|numeric|min:0|max:100',
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $mapping->update([
                'diploma_course_code' => strtoupper($request->diploma_course_code),
                'diploma_course_name' => $request->diploma_course_name,
                'diploma_credit_hour' => $request->diploma_credit_hour,
                'diploma_institution' => $request->diploma_institution,
                'degree_course_code' => strtoupper($request->degree_course_code),
                'degree_course_name' => $request->degree_course_name,
                'degree_credit_hour' => $request->degree_credit_hour,
                'match_percentage' => $request->match_percentage,
                'notes' => $request->notes,
            ]);

            DB::commit();

            return back()->with('success', 'Mapping updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to update mapping: ' . $e->getMessage()]);
        }
    }

    /**
     * Delete a pending mapping forwarded to Program Coordinator (only if still pending)
     */
    public function deletePendingMapping(PendingEquivalencyMapping $mapping)
    {
        // Verify ownership
        if ($mapping->resource_person_user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        // Can only delete pending mappings
        if (!$mapping->isPending()) {
            return back()->withErrors(['error' => 'This mapping has already been processed and cannot be deleted.']);
        }

        DB::beginTransaction();
        try {
            $mapping->delete();

            DB::commit();

            return redirect()->route('resource_person.equivalency_lists.index')
                ->with('success', 'Pending mapping deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to delete mapping: ' . $e->getMessage()]);
        }
    }

    /**
     * View Published Equivalency Lists (same UI as Academic Advisor)
     * READ-ONLY view showing HEA-endorsed published lists organized by program
     */
    public function viewPublishedEquivalencyLists()
    {
        // Define supported programs
        $programs = ['CDCS230', 'CDCS251', 'CDCS253', 'CDCS255', 'CDCS266'];

        $programData = [];
        $totalPublished = 0;
        $latestPublishedProgram = null;
        $latestPublishedDate = null;

        foreach ($programs as $programCode) {
            // Get current (latest) HEA-endorsed published list for this program
            // ONLY shows lists that went through: Resource Person → HEA Endorsement → Published
            $current = EquivalencyList::where('program_code', $programCode)
                ->where('status', 'published')
                ->whereNotNull('endorsed_at') // CRITICAL: Only HEA-endorsed lists
                ->with(['creator', 'publisher', 'endorser', 'courseEquivalencies'])
                ->orderByRaw('COALESCE(endorsed_at, created_at) DESC')
                ->first();

            // Get history (all other HEA-endorsed published lists except the latest)
            $history = EquivalencyList::where('program_code', $programCode)
                ->where('status', 'published')
                ->whereNotNull('endorsed_at') // CRITICAL: Only HEA-endorsed lists
                ->with(['creator', 'publisher', 'endorser', 'courseEquivalencies'])
                ->orderByRaw('COALESCE(endorsed_at, created_at) DESC')
                ->when($current, function($query) use ($current) {
                    return $query->where('id', '!=', $current->id);
                })
                ->get();

            $count = ($current ? 1 : 0) + $history->count();
            $totalPublished += $count;

            $programData[$programCode] = [
                'current' => $current,
                'history' => $history,
                'count' => $count,
                'name' => $this->getProgramNameByCode($programCode),
            ];

            // Track the program with the latest HEA-endorsed list to auto-expand
            $currentEndorsedDate = $current ? ($current->endorsed_at ?: $current->created_at) : null;
            if ($currentEndorsedDate && (!$latestPublishedDate || $currentEndorsedDate > $latestPublishedDate)) {
                $latestPublishedProgram = $programCode;
                $latestPublishedDate = $currentEndorsedDate;
            }
        }

        // Statistics (ONLY HEA-endorsed lists shown as "Published")
        $stats = [
            'total_published' => $totalPublished,
        ];

        // If no published lists exist, default to first program
        if ($latestPublishedProgram === null) {
            $latestPublishedProgram = $programs[0];
        }

        // Indicate this is read-only for Resource Persons
        $isReadOnly = true;

        return view('resource_person.equivalency_lists.published_index', compact(
            'programData',
            'programs',
            'stats',
            'isReadOnly',
            'latestPublishedProgram'
        ));
    }

    /**
     * Get program name by code
     */
    private function getProgramNameByCode($code)
    {
        $programNames = [
            'CDCS230' => 'SARJANA MUDA SAINS KOMPUTER (KEPUJIAN)',
            'CDCS251' => 'SARJANA MUDA SAINS KOMPUTER (KEPUJIAN) PENGKOMPUTERAN NETSENTRIK',
            'CDCS253' => 'SARJANA MUDA SAINS KOMPUTER (KEPUJIAN) PENGKOMPUTERAN MULTIMEDIA',
            'CDCS255' => 'SARJANA MUDA SAINS KOMPUTER (KEPUJIAN) RANGKAIAN KOMPUTER',
            'CDCS266' => 'SARJANA MUDA SISTEM MAKLUMAT (KEPUJIAN) KEJURUTERAAN SISTEM MAKLUMAT',
        ];

        return $programNames[$code] ?? $code;
    }

    /**
     * Helper: Get program names mapping
     */
    private function getProgramNames(array $programCodes): array
    {
        $programNames = [
            'CDCS230' => 'Bachelor of Computer Science (Hons.)',
            'CDCS251' => 'Bachelor of Computer Science (Hons.) Netcentric Computing',
            'CDCS253' => 'Bachelor of Computer Science (Hons.) Multimedia Computing',
            'CDCS255' => 'Bachelor of Computer Science (Hons.) Computer Networking',
            'CDCS266' => 'Bachelor of Information Systems (Hons.) Information Systems Engineering',
        ];

        $result = [];
        foreach ($programCodes as $code) {
            $result[$code] = $programNames[$code] ?? $code;
        }
        return $result;
    }

    /**
     * Helper: Get list of institutions
     */
    private function getInstitutionsList(): array
    {
        return [
            'Politeknik' => 'Politeknik Malaysia',
            'UTM' => 'Universiti Teknologi Malaysia (UTM)',
            'MMU' => 'Multimedia University (MMU)',
            'GMI' => 'German-Malaysian Institute (GMI)',
            'UPSI' => 'Universiti Pendidikan Sultan Idris (UPSI)',
            'KUIS' => 'Kolej Universiti Islam Antarabangsa Selangor (KUIS)',
            'UniKL' => 'Universiti Kuala Lumpur (UniKL)',
            'UTHM' => 'Universiti Tun Hussein Onn Malaysia (UTHM)',
            'UMP' => 'Universiti Malaysia Pahang (UMP)',
            'Other' => 'Other Institution',
        ];
    }
}
