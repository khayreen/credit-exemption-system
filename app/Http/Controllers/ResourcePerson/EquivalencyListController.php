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
     * Shows ONLY assigned programs for editing
     */
    public function index()
    {
        $resourcePerson = $this->getResourcePerson();
        $assignedPrograms = $this->getAssignedPrograms();
        $programNames = $this->getProgramNames($assignedPrograms);

        // Get ONE CS110 list per ASSIGNED program only (for editing)
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
                return redirect()->route('resource_person.equivalency_lists.published')
                    ->withErrors(['error' => 'This list cannot be edited. Current status: ' . $list->status_display]);
            }

            $list->load(['courseEquivalencies', 'courseEquivalencyHistory']);

            DB::commit();

            return view('resource_person.cs110_lists.edit', compact('list', 'programCode'));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('resource_person.equivalency_lists.published')
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

            return redirect()->route('resource_person.equivalency_lists.published')
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
     * View any equivalency list (for general viewing from view_all page)
     */
    public function showList(EquivalencyList $list)
    {
        // Resource Person can view any published list, or their own lists
        if (!$list->isPublished() && $list->created_by_user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $list->load(['creator', 'publisher', 'endorser', 'courseEquivalencies']);
        $isReadOnly = true;

        return view('resource_person.equivalency_lists.show', compact('list', 'isReadOnly'));
    }

    /**
     * Download PDF of an equivalency list
     */
    public function downloadPdf(EquivalencyList $list)
    {
        // Resource Person can download PDF for any published list, or their own lists
        if (!$list->isPublished() && $list->created_by_user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $list->load(['creator', 'publisher', 'courseEquivalencies']);

        return view('hea.equivalency_lists.pdf', compact('list'));
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

            return redirect()->route('resource_person.equivalency_lists.published')
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

            return redirect()->route('resource_person.equivalency_lists.published')
                ->with('success', 'Pending mapping deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to delete mapping: ' . $e->getMessage()]);
        }
    }

    /**
     * View Published Equivalency Lists (unified view for Resource Person)
     * Shows all internal CS110 lists organized by program
     * Assigned programs have Edit/Submit functionality
     */
    public function viewPublishedEquivalencyLists()
    {
        // Define supported programs
        $programs = ['CDCS230', 'CDCS251', 'CDCS253', 'CDCS255', 'CDCS266'];

        // Get assigned programs for edit/submit functionality
        $assignedPrograms = $this->getAssignedPrograms();
        $programNames = $this->getProgramNames($programs);

        $programData = [];
        $totalPublished = 0;
        $latestPublishedProgram = null;
        $latestPublishedDate = null;

        foreach ($programs as $programCode) {
            // Get current internal CS110 list for this program (regardless of status)
            $current = EquivalencyList::where('program_code', $programCode)
                ->where('category', 'internal')
                ->whereNull('source_institution')
                ->with(['creator', 'publisher', 'endorser', 'reviewer', 'courseEquivalencies', 'courseEquivalencyHistory'])
                ->orderBy('published_at', 'desc')
                ->orderBy('created_at', 'desc')
                ->first();

            // Get history (Resource Person and HEA can see archived lists)
            $history = EquivalencyList::where('program_code', $programCode)
                ->where('category', 'internal')
                ->whereNull('source_institution')
                ->where('status', 'published')
                ->with(['creator', 'publisher', 'endorser', 'courseEquivalencies'])
                ->orderBy('published_at', 'desc')
                ->orderBy('created_at', 'desc')
                ->when($current, function($query) use ($current) {
                    return $query->where('id', '!=', $current->id);
                })
                ->get();

            $count = ($current ? 1 : 0) + $history->count();
            $totalPublished += $count;

            // Determine edit/submit permissions
            $isAssigned = in_array($programCode, $assignedPrograms);

            // RP can go to edit page if:
            // 1. They are assigned to this program AND
            // 2. The list exists AND
            // 3. The list is in DRAFT, REJECTED, or PUBLISHED status (published will auto-revert to draft on edit)
            $editableStatuses = [
                EquivalencyList::STATUS_DRAFT,
                EquivalencyList::STATUS_REJECTED,
                EquivalencyList::STATUS_PUBLISHED, // Will auto-revert to draft when edit() is called
            ];
            $canEdit = $isAssigned && $current && in_array($current->status, $editableStatuses);

            // RP can use inline edit/delete only if list is in DRAFT or REJECTED (not published)
            // Published lists require going to the edit page first (which auto-reverts to draft)
            $inlineEditableStatuses = [
                EquivalencyList::STATUS_DRAFT,
                EquivalencyList::STATUS_REJECTED,
            ];
            $canEditInline = $isAssigned && $current && in_array($current->status, $inlineEditableStatuses);

            // RP can submit if:
            // 1. They are assigned AND
            // 2. List is in DRAFT status (not published, not submitted, not under review)
            // 3. List has at least one mapping
            $canSubmit = $isAssigned && $current && $current->status === EquivalencyList::STATUS_DRAFT && $current->courseEquivalencies->count() > 0;

            $programData[$programCode] = [
                'current' => $current,
                'history' => $history,
                'count' => $count,
                'name' => $this->getProgramNameByCode($programCode),
                'isAssigned' => $isAssigned,
                'canEdit' => $canEdit,
                'canEditInline' => $canEditInline,
                'canSubmit' => $canSubmit,
            ];

        }

        // Statistics
        $stats = [
            'total_published' => $totalPublished,
        ];

        // Always default to the Resource Person's first assigned program
        // If no assigned programs, default to first program (CDCS230)
        $defaultProgram = !empty($assignedPrograms) ? $assignedPrograms[0] : $programs[0];

        return view('resource_person.equivalency_lists.published_index', compact(
            'programData',
            'programs',
            'stats',
            'assignedPrograms',
            'programNames',
            'defaultProgram'
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

    /**
     * View all course equivalencies (for "All Course Mappings" page)
     */
    public function viewAllCourseEquivalencies()
    {
        // Get all supported programs from config (show all 5 degree programs)
        $supportedPrograms = config('programs.supported_programs');
        $programs = collect($supportedPrograms)->map(function($name, $code) {
            return (object) [
                'code' => $code,
                'name' => $name,
            ];
        })->values();

        // Get all unique institutions from course equivalencies
        $institutions = \App\Models\CourseEquivalency::whereNotNull('diploma_institution')
            ->where('diploma_institution', '!=', '')
            ->distinct()
            ->orderBy('diploma_institution')
            ->pluck('diploma_institution')
            ->toArray();

        // Pass the correct API route for Resource Persons
        $apiRoute = route('resource_person.api.existing_equivalencies');
        $backRoute = route('resource_person.equivalency_lists.published');

        return view('program_coordinator.course_equivalencies.view', compact('programs', 'institutions', 'apiRoute', 'backRoute'));
    }

    /**
     * API endpoint to get existing course equivalencies for a program (for Resource Person)
     */
    public function getExistingEquivalencies(Request $request)
    {
        $programCode = $request->get('program_code');
        $status = $request->get('status', 'current');
        $semester = $request->get('semester');
        $category = $request->get('category');
        $institution = $request->get('institution');

        if (!$programCode) {
            return response()->json(['error' => 'Program code is required'], 400);
        }

        // Build query for equivalencies
        $query = \App\Models\CourseEquivalency::with('equivalencyList');

        // Filter by program code
        if ($programCode !== 'ALL') {
            $query->where('program_code', $programCode);
        }

        // Filter by status
        $query->whereHas('equivalencyList', function($q) use ($status, $programCode) {
            if ($status === 'current') {
                $q->where('status', 'published')
                  ->whereIn('id', function($subquery) use ($programCode) {
                      $subquery->selectRaw('MAX(id)')
                          ->from('equivalency_lists')
                          ->where('status', 'published')
                          ->when($programCode !== 'ALL', function($q2) use ($programCode) {
                              $q2->where('program_code', $programCode);
                          })
                          ->groupBy('program_code');
                  });
            } elseif ($status === 'all_published') {
                $q->where('status', 'published');
            }
        });

        // Filter by semester if provided
        if ($semester) {
            $query->whereHas('equivalencyList', function($q) use ($semester) {
                $q->where('semester', $semester);
            });
        }

        // Filter by category if provided
        if ($category) {
            $query->whereHas('equivalencyList', function($q) use ($category) {
                $q->where('category', $category);
            });
        }

        // Filter by institution if provided
        if ($institution) {
            $query->where('diploma_institution', $institution);
        }

        $equivalencies = $query->get()
            ->map(function($eq) {
                return [
                    'id' => $eq->id,
                    'program_code' => $eq->program_code,
                    'diploma_course_code' => $eq->diploma_course_code,
                    'diploma_course_name' => $eq->diploma_course_name,
                    'diploma_credit_hour' => $eq->diploma_credit_hour,
                    'diploma_institution' => $eq->diploma_institution,
                    'degree_course_code' => $eq->degree_course_code,
                    'degree_course_name' => $eq->degree_course_name,
                    'degree_credit_hour' => $eq->degree_credit_hour,
                    'match_percentage' => $eq->match_percentage,
                    'is_eligible' => $eq->is_eligible,
                    'list_semester' => $eq->equivalencyList->semester ?? 'N/A',
                    'list_category' => $eq->equivalencyList->category ?? 'N/A',
                    'list_status' => $eq->equivalencyList->status ?? 'N/A',
                    'source_institution' => $eq->equivalencyList->source_institution ?? 'Internal',
                ];
            });

        return response()->json($equivalencies);
    }

    /**
     * Store a new course equivalency (for "All Course Mappings" CRUD)
     */
    public function storeCourseEquivalency(Request $request)
    {
        $request->validate([
            'program_code' => 'required|string|in:CDCS230,CDCS251,CDCS253,CDCS255,CDCS266',
            'diploma_institution' => 'required|string|max:255',
            'diploma_course_code' => 'required|string|max:50',
            'diploma_course_name' => 'required|string|max:255',
            'diploma_credit_hour' => 'required|numeric|min:1|max:10',
            'degree_course_code' => 'required|string|max:50',
            'degree_course_name' => 'required|string|max:255',
            'degree_credit_hour' => 'required|numeric|min:1|max:10',
            'match_percentage' => 'required|numeric|min:0|max:100',
            'is_eligible' => 'required|boolean',
        ]);

        // Verify the resource person is assigned to this program
        $assignedPrograms = $this->getAssignedPrograms();
        if (!in_array($request->program_code, $assignedPrograms)) {
            return response()->json([
                'success' => false,
                'message' => 'You are not assigned to this program.'
            ], 403);
        }

        DB::beginTransaction();
        try {
            // Get program name from static mapping
            $programNames = [
                'CDCS230' => 'Bachelor of Computer Science (Hons.)',
                'CDCS251' => 'Bachelor of Computer Science (Hons.) Netcentric Computing',
                'CDCS253' => 'Bachelor of Computer Science (Hons.) Multimedia Computing',
                'CDCS255' => 'Bachelor of Computer Science (Hons.) Computer Networking',
                'CDCS266' => 'Bachelor of Information Systems (Hons.) Information Systems Engineering',
            ];
            $programName = $programNames[$request->program_code] ?? $request->program_code;

            // Find the appropriate equivalency list for this mapping
            $isCS110 = strtoupper($request->diploma_institution) === 'CS110';

            if ($isCS110) {
                // For CS110, find the internal list for this program
                $list = EquivalencyList::internal()
                    ->where('program_code', $request->program_code)
                    ->whereNull('source_institution')
                    ->first();

                if (!$list) {
                    return response()->json([
                        'success' => false,
                        'message' => 'CS110 list not found for this program. Please create it first.'
                    ], 404);
                }
            } else {
                // For external institutions, find or create external list
                $list = EquivalencyList::firstOrCreate(
                    [
                        'program_code' => $request->program_code,
                        'category' => 'external',
                        'source_institution' => $request->diploma_institution,
                        'status' => EquivalencyList::STATUS_DRAFT,
                    ],
                    [
                        'program_name' => $programName,
                        'semester' => now()->format('Y/Y') . '-' . (now()->month >= 7 ? '1' : '2'),
                        'academic_year' => now()->format('Y/Y'),
                        'created_by_user_id' => Auth::id(),
                        'total_equivalencies' => 0,
                        'eligible_count' => 0,
                    ]
                );
            }

            // Create the course equivalency
            $equivalency = \App\Models\CourseEquivalency::create([
                'equivalency_list_id' => $list->id,
                'program_code' => $request->program_code,
                'diploma_course_code' => strtoupper($request->diploma_course_code),
                'diploma_course_name' => $request->diploma_course_name,
                'diploma_credit_hour' => $request->diploma_credit_hour,
                'diploma_institution' => $request->diploma_institution,
                'degree_course_code' => strtoupper($request->degree_course_code),
                'degree_course_name' => $request->degree_course_name,
                'degree_credit_hour' => $request->degree_credit_hour,
                'match_percentage' => $request->match_percentage,
                'is_eligible' => $request->is_eligible,
                'source' => 'manual', // Manually created by resource person
                'is_published' => false, // Not published (only CS110 lists are published by HEA)
                'approved_by_user_id' => Auth::id(), // Set approver to current user
                'last_updated_by_user_id' => Auth::id(),
                'last_updated_at' => now(),
            ]);

            // Update list statistics
            $list->updateStatistics();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Course mapping added successfully!',
                'mapping' => $equivalency
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to add mapping: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show a specific course equivalency for editing (for "All Course Mappings" CRUD)
     */
    public function showCourseEquivalency(\App\Models\CourseEquivalency $mapping)
    {
        // Verify the resource person is assigned to this program
        $assignedPrograms = $this->getAssignedPrograms();
        if (!in_array($mapping->program_code, $assignedPrograms)) {
            return response()->json([
                'success' => false,
                'message' => 'You are not assigned to this program.'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'mapping' => $mapping
        ]);
    }

    /**
     * Update an existing course equivalency (for "All Course Mappings" CRUD)
     */
    public function updateCourseEquivalency(Request $request, \App\Models\CourseEquivalency $mapping)
    {
        $request->validate([
            'diploma_institution' => 'required|string|max:255',
            'diploma_course_code' => 'required|string|max:50',
            'diploma_course_name' => 'required|string|max:255',
            'diploma_credit_hour' => 'required|numeric|min:1|max:10',
            'degree_course_code' => 'required|string|max:50',
            'degree_course_name' => 'required|string|max:255',
            'degree_credit_hour' => 'required|numeric|min:1|max:10',
            'match_percentage' => 'required|numeric|min:0|max:100',
            'is_eligible' => 'required|boolean',
        ]);

        // Verify the resource person is assigned to this program
        $assignedPrograms = $this->getAssignedPrograms();
        if (!in_array($mapping->program_code, $assignedPrograms)) {
            return response()->json([
                'success' => false,
                'message' => 'You are not assigned to this program.'
            ], 403);
        }

        DB::beginTransaction();
        try {
            // Update the course equivalency
            $mapping->update([
                'diploma_course_code' => strtoupper($request->diploma_course_code),
                'diploma_course_name' => $request->diploma_course_name,
                'diploma_credit_hour' => $request->diploma_credit_hour,
                'diploma_institution' => $request->diploma_institution,
                'degree_course_code' => strtoupper($request->degree_course_code),
                'degree_course_name' => $request->degree_course_name,
                'degree_credit_hour' => $request->degree_credit_hour,
                'match_percentage' => $request->match_percentage,
                'is_eligible' => $request->is_eligible,
                'last_updated_by_user_id' => Auth::id(),
                'last_updated_at' => now(),
            ]);

            // Update list statistics
            $mapping->equivalencyList->updateStatistics();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Course mapping updated successfully!',
                'mapping' => $mapping
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update mapping: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a course equivalency (for "All Course Mappings" CRUD)
     */
    public function destroyCourseEquivalency(\App\Models\CourseEquivalency $mapping)
    {
        // Verify the resource person is assigned to this program
        $assignedPrograms = $this->getAssignedPrograms();
        if (!in_array($mapping->program_code, $assignedPrograms)) {
            return response()->json([
                'success' => false,
                'message' => 'You are not assigned to this program.'
            ], 403);
        }

        DB::beginTransaction();
        try {
            $list = $mapping->equivalencyList;

            // Delete the mapping
            $mapping->delete();

            // Update list statistics
            $list->updateStatistics();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Course mapping deleted successfully!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete mapping: ' . $e->getMessage()
            ], 500);
        }
    }
}
