<?php

namespace App\Http\Controllers\Hea;

use App\Http\Controllers\Controller;
use App\Models\EquivalencyList;
use App\Models\PendingEquivalencyMapping;
use App\Models\AuditTrail;
use App\Models\CourseEquivalency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EquivalencyListController extends Controller
{
    /**
     * Display all equivalency lists with filtering (READ-ONLY)
     * HEA Personnel have VIEW-ONLY access to monitor equivalency lists
     */
    public function index(Request $request)
    {
        $statusFilter = $request->get('status', 'all');
        $categoryFilter = $request->get('category', 'all');
        $programFilter = $request->get('program', 'all');

        $query = EquivalencyList::with(['creator', 'publisher', 'courseEquivalencies']);

        // Apply filters
        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        if ($categoryFilter !== 'all') {
            $query->where('category', $categoryFilter);
        }

        if ($programFilter !== 'all') {
            $query->where('program_code', $programFilter);
        }

        // Order by status priority and date
        $lists = $query->orderByRaw("CASE
            WHEN status = 'draft' THEN 1
            WHEN status = 'published' THEN 2
            WHEN status = 'archived' THEN 3
            ELSE 4 END")
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Get programs for filter dropdown
        $programs = $this->getProgramNames();

        // Get statistics
        $stats = $this->getStatistics();

        return view('hea.equivalency_lists.index', compact(
            'lists',
            'statusFilter',
            'categoryFilter',
            'programFilter',
            'programs',
            'stats'
        ));
    }

    /**
     * Display equivalency lists in accordion view (READ-ONLY)
     * Same grouped view as Program Coordinator and Resource Person
     */
    public function viewGrouped(Request $request)
    {
        $endorserFilter = $request->get('endorser', 'all');

        // Build base query - EXCLUDE published lists (work-in-progress only)
        $internalQuery = EquivalencyList::internal()
            ->whereNull('published_at') // Only work-in-progress
            ->with(['creator', 'publisher', 'endorser']);

        $externalQuery = EquivalencyList::external()
            ->whereNull('published_at') // Only work-in-progress
            ->with(['creator', 'publisher', 'endorser']);

        // Published lists query (both internal and external combined)
        $publishedQuery = EquivalencyList::published()
            ->with(['creator', 'publisher', 'endorser', 'courseEquivalencies']);

        // Apply endorser filter
        if ($endorserFilter === 'me') {
            $internalQuery->where(function($query) {
                $query->where('endorsed_by_user_id', auth()->id())
                      ->orWhere(function($q) {
                          // For legacy lists: include lists where endorsed_by is null but published_by matches
                          // (Lists revert to draft status after publishing, so we check published_by_user_id)
                          $q->whereNull('endorsed_by_user_id')
                            ->where('published_by_user_id', auth()->id());
                      });
            });
            $externalQuery->where(function($query) {
                $query->where('endorsed_by_user_id', auth()->id())
                      ->orWhere(function($q) {
                          // For legacy lists: include lists where endorsed_by is null but published_by matches
                          $q->whereNull('endorsed_by_user_id')
                            ->where('published_by_user_id', auth()->id());
                      });
            });
        } elseif ($endorserFilter === 'endorsed') {
            $internalQuery->where(function($query) {
                $query->whereNotNull('endorsed_by_user_id')
                      ->orWhere(function($q) {
                          $q->whereNull('endorsed_by_user_id')
                            ->whereNotNull('published_by_user_id');
                      });
            });
            $externalQuery->where(function($query) {
                $query->whereNotNull('endorsed_by_user_id')
                      ->orWhere(function($q) {
                          $q->whereNull('endorsed_by_user_id')
                            ->whereNotNull('published_by_user_id');
                      });
            });
            $publishedQuery->where(function($query) {
                $query->whereNotNull('endorsed_by_user_id')
                      ->orWhere(function($q) {
                          $q->whereNull('endorsed_by_user_id')
                            ->whereNotNull('published_by_user_id');
                      });
            });
        }

        // Get all lists grouped by category
        $internalLists = $internalQuery
            ->orderBy('semester', 'desc')
            ->orderBy('program_code')
            ->get()
            ->groupBy('program_code');

        $externalLists = $externalQuery
            ->orderBy('semester', 'desc')
            ->orderBy('program_code')
            ->orderBy('source_institution')
            ->get()
            ->groupBy('source_institution');

        // Get published lists grouped by program code (both internal and external)
        $publishedLists = $publishedQuery
            ->orderBy('program_code')
            ->orderBy('semester', 'desc')
            ->get()
            ->groupBy('program_code');

        // Get pending mappings from Resource Persons
        $pendingMappingsCount = PendingEquivalencyMapping::pending()->count();

        // Statistics
        $stats = [
            'total_lists' => EquivalencyList::count(),
            'published_lists' => EquivalencyList::published()->count(),
            'wip_internal' => EquivalencyList::internal()->whereNull('published_at')->count(), // Work-in-progress internal
            'wip_external' => EquivalencyList::external()->whereNull('published_at')->count(), // Work-in-progress external
            'pending_mappings' => $pendingMappingsCount,
            'endorsed_by_me' => EquivalencyList::where(function($query) {
                $query->where('endorsed_by_user_id', auth()->id())
                      ->orWhere(function($q) {
                          // For legacy lists: count lists where endorsed_by is null but published_by matches
                          $q->whereNull('endorsed_by_user_id')
                            ->where('published_by_user_id', auth()->id());
                      });
            })->count(),
        ];

        return view('hea.equivalency_lists.grouped', compact(
            'internalLists',
            'externalLists',
            'publishedLists',
            'stats',
            'endorserFilter'
        ));
    }

    /**
     * Display published lists archive (READ-ONLY)
     */
    public function published(Request $request)
    {
        $programFilter = $request->get('program', 'all');
        $categoryFilter = $request->get('category', 'all');

        $query = EquivalencyList::with(['creator', 'publisher', 'courseEquivalencies'])
            ->published();

        if ($programFilter !== 'all') {
            $query->where('program_code', $programFilter);
        }

        if ($categoryFilter !== 'all') {
            $query->where('category', $categoryFilter);
        }

        // Group by program and category
        $lists = $query->orderBy('program_code')
            ->orderBy('category')
            ->orderBy('published_at', 'desc')
            ->get()
            ->groupBy('program_code');

        $programs = $this->getProgramNames();

        // Get counts
        $publishedCounts = [
            'total' => EquivalencyList::published()->count(),
            'internal' => EquivalencyList::published()->internal()->count(),
            'external' => EquivalencyList::published()->external()->count(),
            'active' => EquivalencyList::published()->active()->count(),
        ];

        return view('hea.equivalency_lists.published', compact(
            'lists',
            'programs',
            'publishedCounts',
            'programFilter',
            'categoryFilter'
        ));
    }

    /**
     * Display draft lists being prepared by Program Coordinators (READ-ONLY)
     */
    public function drafts(Request $request)
    {
        $programFilter = $request->get('program', 'all');
        $categoryFilter = $request->get('category', 'all');

        $query = EquivalencyList::with(['creator', 'courseEquivalencies'])
            ->draft();

        if ($programFilter !== 'all') {
            $query->where('program_code', $programFilter);
        }

        if ($categoryFilter !== 'all') {
            $query->where('category', $categoryFilter);
        }

        $lists = $query->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('category');

        $internalLists = $lists->get('internal', collect());
        $externalLists = $lists->get('external', collect());

        $programs = $this->getProgramNames();

        // Get counts
        $draftCounts = [
            'total' => $internalLists->count() + $externalLists->count(),
            'internal' => $internalLists->count(),
            'external' => $externalLists->count(),
        ];

        return view('hea.equivalency_lists.drafts', compact(
            'internalLists',
            'externalLists',
            'draftCounts',
            'programs',
            'programFilter',
            'categoryFilter'
        ));
    }

    /**
     * Display pending equivalency lists awaiting HEA endorsement
     */
    public function pending(Request $request)
    {
        $categoryFilter = $request->get('category', 'all');

        $query = EquivalencyList::with(['creator', 'courseEquivalencies'])
            ->pending(); // Uses the scopePending() we just added

        if ($categoryFilter !== 'all') {
            $query->where('category', $categoryFilter);
        }

        $lists = $query->orderBy('submitted_at', 'asc')->get();

        // Split by category
        $internalLists = $lists->where('category', 'internal');
        $externalLists = $lists->where('category', 'external');

        $pendingCounts = [
            'total' => $lists->count(),
            'internal' => $internalLists->count(),
            'external' => $externalLists->count(),
        ];

        return view('hea.equivalency_lists.pending', compact(
            'internalLists',
            'externalLists',
            'pendingCounts',
            'categoryFilter'
        ));
    }

    /**
     * Show review page for a specific pending list
     */
    public function review(EquivalencyList $list)
    {
        // Ensure the list is in a reviewable state
        if (!in_array($list->status, ['submitted', 'under_review'])) {
            return redirect()->route('hea.equivalency_lists.pending')
                ->with('error', 'This list is not in a reviewable state.');
        }

        // Automatically mark as under review if still submitted
        if ($list->status === 'submitted') {
            $list->update([
                'status' => 'under_review',
                'reviewed_by_user_id' => auth()->id(),
                'reviewed_at' => now(),
            ]);
        }

        $list->load(['creator', 'publisher', 'courseEquivalencies']);

        // Get activity log
        $activityLog = AuditTrail::where('target_entity', 'EquivalencyList')
            ->where('target_id', $list->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get previous semester list for comparison
        $previousList = $this->getPreviousSemesterList($list);
        $changes = $previousList ? $this->calculateChanges($list, $previousList) : null;

        return view('hea.equivalency_lists.review', compact('list', 'activityLog', 'previousList', 'changes'));
    }

    /**
     * Endorse and publish the equivalency list
     */
    public function endorse(Request $request, EquivalencyList $list)
    {
        $request->validate([
            'endorsement_notes' => 'nullable|string|max:1000',
            'confirm' => 'required|accepted',
        ]);

        if (!$list->canBeEndorsed()) {
            return back()->with('error', 'This list cannot be endorsed in its current state.');
        }

        try {
            \DB::beginTransaction();

            // Endorse the list
            $list->endorse(auth()->user(), $request->endorsement_notes);

            // Immediately publish after endorsement
            $list->publish(auth()->user());

            // Log the action
            AuditTrail::create([
                'user_id' => auth()->id(),
                'action' => 'Endorsed and Published Equivalency List',
                'target_entity' => 'EquivalencyList',
                'target_id' => $list->id,
                'details' => json_encode([
                    'program_code' => $list->program_code,
                    'category' => $list->category,
                    'semester' => $list->semester,
                    'total_equivalencies' => $list->total_equivalencies,
                    'endorsement_notes' => $request->endorsement_notes,
                ]),
            ]);

            \DB::commit();

            return redirect()->route('hea.equivalency_lists.pending')
                ->with('success', 'Equivalency list has been endorsed and published successfully!');
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Failed to endorse equivalency list: ' . $e->getMessage());
            return back()->with('error', 'Failed to endorse list: ' . $e->getMessage());
        }
    }

    /**
     * Reject the equivalency list and return to Resource Person
     */
    public function reject(Request $request, EquivalencyList $list)
    {
        $request->validate([
            'review_notes' => 'required|string|min:10|max:1000',
        ]);

        if (!in_array($list->status, ['submitted', 'under_review'])) {
            return back()->with('error', 'This list cannot be rejected in its current state.');
        }

        try {
            \DB::beginTransaction();

            // Reject the list
            $list->reject(auth()->user(), $request->review_notes);

            // Log the action
            AuditTrail::create([
                'user_id' => auth()->id(),
                'action' => 'Rejected Equivalency List',
                'target_entity' => 'EquivalencyList',
                'target_id' => $list->id,
                'details' => json_encode([
                    'program_code' => $list->program_code,
                    'category' => $list->category,
                    'semester' => $list->semester,
                    'review_notes' => $request->review_notes,
                ]),
            ]);

            \DB::commit();

            return redirect()->route('hea.equivalency_lists.pending')
                ->with('success', 'Equivalency list has been rejected and returned to Resource Person for revision.');
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Failed to reject equivalency list: ' . $e->getMessage());
            return back()->with('error', 'Failed to reject list: ' . $e->getMessage());
        }
    }

    /**
     * View pending mappings from Resource Persons (READ-ONLY)
     */
    public function pendingMappings()
    {
        $pendingMappings = PendingEquivalencyMapping::pending()
            ->with(['resourcePerson', 'courseEquivalencyRequest'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('program_code');

        $stats = [
            'pending' => PendingEquivalencyMapping::pending()->count(),
            'added' => PendingEquivalencyMapping::added()->count(),
            'rejected' => PendingEquivalencyMapping::rejected()->count(),
        ];

        return view('hea.pending_mappings.index', compact('pendingMappings', 'stats'));
    }

    /**
     * Show a specific equivalency list details (READ-ONLY)
     */
    public function show(EquivalencyList $list)
    {
        $list->load(['creator', 'publisher', 'courseEquivalencies']);

        // Get activity log
        $activityLog = AuditTrail::where('target_entity', 'EquivalencyList')
            ->where('target_id', $list->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get previous semester list for comparison (if exists)
        $previousList = $this->getPreviousSemesterList($list);

        // Calculate changes from previous semester
        $changes = $previousList ? $this->calculateChanges($list, $previousList) : null;

        return view('hea.equivalency_lists.show', compact('list', 'activityLog', 'previousList', 'changes'));
    }

    /**
     * Download PDF of the equivalency list (READ-ONLY)
     */
    public function downloadPdf(EquivalencyList $list)
    {
        $list->load(['creator', 'publisher', 'courseEquivalencies']);

        return view('hea.equivalency_lists.pdf', compact('list'));
    }

    /**
     * Delete a draft equivalency list
     * Only allows deletion of draft lists that haven't been endorsed
     */
    public function destroy(EquivalencyList $list)
    {
        // Security check: only allow deletion of draft lists that haven't been endorsed or published
        if ($list->published_at !== null || $list->endorsed_at !== null) {
            return back()->with('error', 'Only draft lists that have not been endorsed or published can be deleted.');
        }

        try {
            \DB::beginTransaction();

            $programCode = $list->program_code;
            $semester = $list->semester;

            // Delete associated course equivalencies
            $list->courseEquivalencies()->delete();

            // Delete the list itself
            $list->delete();

            // Log the action
            AuditTrail::create([
                'user_id' => auth()->id(),
                'action' => 'Deleted Draft Equivalency List',
                'target_entity' => 'EquivalencyList',
                'target_id' => $list->id,
                'details' => json_encode([
                    'program_code' => $programCode,
                    'semester' => $semester,
                    'category' => $list->category,
                    'total_equivalencies' => $list->total_equivalencies,
                ]),
            ]);

            \DB::commit();

            return back()->with('success', "Draft equivalency list for {$programCode} - {$semester} has been deleted successfully.");
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Failed to delete equivalency list: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete list: ' . $e->getMessage());
        }
    }

    /**
     * View statistics and reports (READ-ONLY)
     */
    public function statistics()
    {
        // Comprehensive statistics for HEA monitoring
        $stats = [
            // Overall counts
            'total_lists' => EquivalencyList::count(),
            'draft_lists' => EquivalencyList::draft()->count(),
            'published_lists' => EquivalencyList::published()->count(),
            'archived_lists' => EquivalencyList::archived()->count(),
            'active_lists' => EquivalencyList::active()->count(),

            // By category
            'internal_published' => EquivalencyList::published()->internal()->count(),
            'external_published' => EquivalencyList::published()->external()->count(),

            // Pending workflow
            'pending_mappings' => PendingEquivalencyMapping::pending()->count(),
            'added_mappings' => PendingEquivalencyMapping::added()->count(),
            'rejected_mappings' => PendingEquivalencyMapping::rejected()->count(),

            // By program
            'by_program' => EquivalencyList::published()
                ->select('program_code')
                ->selectRaw('COUNT(*) as count')
                ->groupBy('program_code')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->program_code => $item->count];
                }),

            // Recent activity
            'recent_publications' => EquivalencyList::published()
                ->orderBy('published_at', 'desc')
                ->limit(5)
                ->get(),

            'recent_drafts' => EquivalencyList::draft()
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
        ];

        $programs = $this->getProgramNames();

        return view('hea.equivalency_lists.statistics', compact('stats', 'programs'));
    }

    /**
     * Get program names mapping
     */
    private function getProgramNames(): array
    {
        return [
            'CDCS230' => 'Bachelor of Computer Science (Hons.)',
            'CDCS251' => 'Bachelor of Computer Science (Hons.) Netcentric Computing',
            'CDCS253' => 'Bachelor of Computer Science (Hons.) Multimedia Computing',
            'CDCS255' => 'Bachelor of Computer Science (Hons.) Computer Networking',
            'CDCS266' => 'Bachelor of Information Systems (Hons.) Information Systems Engineering',
        ];
    }

    /**
     * Get statistics for dashboard
     */
    private function getStatistics(): array
    {
        return [
            'draft' => EquivalencyList::draft()->count(),
            'draft_internal' => EquivalencyList::draft()->internal()->count(),
            'draft_external' => EquivalencyList::draft()->external()->count(),
            'published' => EquivalencyList::published()->count(),
            'published_internal' => EquivalencyList::published()->internal()->count(),
            'published_external' => EquivalencyList::published()->external()->count(),
            'archived' => EquivalencyList::archived()->count(),
            'active' => EquivalencyList::active()->count(),
            'total' => EquivalencyList::count(),
            'pending_mappings' => PendingEquivalencyMapping::pending()->count(),
        ];
    }

    /**
     * Get the previous semester list for comparison
     */
    private function getPreviousSemesterList(EquivalencyList $currentList): ?EquivalencyList
    {
        return EquivalencyList::where('program_code', $currentList->program_code)
            ->where('category', $currentList->category)
            ->where('source_institution', $currentList->source_institution)
            ->where('id', '!=', $currentList->id)
            ->published()
            ->orderBy('published_at', 'desc')
            ->first();
    }

    /**
     * Calculate changes between current and previous list
     */
    private function calculateChanges(EquivalencyList $current, EquivalencyList $previous): array
    {
        $currentMappings = $current->courseEquivalencies->keyBy(function ($eq) {
            return $eq->diploma_course_code . '|' . $eq->degree_course_code;
        });

        $previousMappings = $previous->courseEquivalencies->keyBy(function ($eq) {
            return $eq->diploma_course_code . '|' . $eq->degree_course_code;
        });

        $added = [];
        $removed = [];
        $modified = [];

        // Find new and modified mappings
        foreach ($currentMappings as $key => $mapping) {
            if (!$previousMappings->has($key)) {
                $added[] = $mapping;
            } else {
                $prevMapping = $previousMappings->get($key);
                if ($mapping->match_percentage != $prevMapping->match_percentage) {
                    $modified[] = [
                        'current' => $mapping,
                        'previous' => $prevMapping,
                    ];
                }
            }
        }

        // Find removed mappings
        foreach ($previousMappings as $key => $mapping) {
            if (!$currentMappings->has($key)) {
                $removed[] = $mapping;
            }
        }

        return [
            'added' => $added,
            'removed' => $removed,
            'modified' => $modified,
            'added_count' => count($added),
            'removed_count' => count($removed),
            'modified_count' => count($modified),
        ];
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

        // Indicate this is read-only for HEA Personnel
        $isReadOnly = true;

        return view('hea.equivalency_lists.published_index', compact(
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
     * View all course equivalencies across all lists (published and drafts)
     */
    public function viewAllCourseEquivalencies()
    {
        // Get all programs that have course equivalencies
        // Use LEFT JOIN to handle programs not in the programs table
        $programs = DB::table('course_equivalencies')
            ->join('equivalency_lists', 'course_equivalencies.equivalency_list_id', '=', 'equivalency_lists.id')
            ->leftJoin('programs', 'course_equivalencies.program_code', '=', 'programs.code')
            ->whereNotNull('course_equivalencies.program_code')
            ->where('course_equivalencies.program_code', '!=', '')
            ->select(
                'course_equivalencies.program_code as code',
                DB::raw('COALESCE(programs.name, course_equivalencies.program_code) as name')
            )
            ->distinct()
            ->orderBy('course_equivalencies.program_code')
            ->get();

        return view('hea.course_equivalencies.view', compact('programs'));
    }

    /**
     * API endpoint to get existing course equivalencies for a program
     */
    public function getExistingEquivalencies(Request $request)
    {
        $programCode = $request->get('program_code');

        if (!$programCode) {
            return response()->json(['error' => 'Program code is required'], 400);
        }

        // Build query for all equivalencies (published and drafts)
        $query = CourseEquivalency::with('equivalencyList');

        // If not "ALL", filter by specific program code
        if ($programCode !== 'ALL') {
            $query->where('program_code', $programCode);
        }

        // Get all equivalencies
        $equivalencies = $query
            ->orderBy('program_code')
            ->orderBy('diploma_course_code')
            ->get()
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
                    'source_institution' => $eq->equivalencyList->source_institution ?? 'Internal',
                ];
            });

        return response()->json($equivalencies);
    }
}
