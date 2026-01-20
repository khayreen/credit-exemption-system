<?php

namespace App\Http\Controllers\ProgramCoordinator;

use App\Http\Controllers\Controller;
use App\Models\EquivalencyList;
use App\Models\CourseEquivalency;
use App\Models\PendingEquivalencyMapping;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EquivalencyListController extends Controller
{
    /**
     * Display dashboard with all equivalency lists
     * Organized by program with current + history structure
     */
    public function index()
    {
        // Define supported programs
        $programs = ['CDCS230', 'CDCS251', 'CDCS253', 'CDCS255', 'CDCS266'];

        $programData = [];
        $totalPublished = 0;
        $latestPublishedProgram = null;
        $latestPublishedDate = null;

        foreach ($programs as $programCode) {
            // Get current (latest) published list for this program
            // Handle both:
            // 1. Legacy data: status = 'published' but published_at is NULL
            // 2. HEA workflow: published_at IS NOT NULL (after proper endorsement)
            $current = EquivalencyList::where('program_code', $programCode)
                ->where('category', 'internal') // CS110 internal lists
                ->whereNull('source_institution')
                ->where(function($q) {
                    $q->where('status', 'published')
                      ->orWhereNotNull('published_at');
                })
                ->with(['creator', 'publisher', 'endorsedBy', 'courseEquivalencies'])
                ->orderByRaw('COALESCE(published_at, created_at) DESC')
                ->first();

            // Program Coordinators do NOT see historical/archived lists
            // Only Resource Person and HEA can view archived lists
            $history = collect(); // Empty collection

            $count = $current ? 1 : 0;
            $totalPublished += $count;

            $programData[$programCode] = [
                'current' => $current,
                'history' => $history,
                'count' => $count,
                'name' => $this->getProgramName($programCode),
            ];

            // Track the program with the latest published list to auto-expand
            $currentDate = $current ? ($current->published_at ?? $current->created_at) : null;
            if ($currentDate && (!$latestPublishedDate || $currentDate > $latestPublishedDate)) {
                $latestPublishedProgram = $programCode;
                $latestPublishedDate = $currentDate;
            }
        }

        // Statistics (ONLY HEA-endorsed lists shown as "Published")
        $stats = [
            'total_published' => $totalPublished,
            'draft_lists' => EquivalencyList::draft()->count(),
        ];

        // If no published lists exist, default to first program
        if ($latestPublishedProgram === null) {
            $latestPublishedProgram = $programs[0];
        }

        // Program Coordinators have full edit access
        $isReadOnly = false;

        return view('program_coordinator.equivalency_lists.index', compact(
            'programData',
            'programs',
            'stats',
            'isReadOnly',
            'latestPublishedProgram'
        ));
    }

    /**
     * Show the form for creating a new equivalency list
     */
    public function create(Request $request)
    {
        $step = $request->get('step', 1);
        $category = $request->get('category');

        // All SARJANA MUDA programs
        $programs = $this->getProgramsList();
        $institutions = $this->getInstitutionsList();

        return view('program_coordinator.equivalency_lists.create', compact(
            'programs',
            'institutions',
            'step',
            'category'
        ));
    }

    /**
     * Store a newly created equivalency list
     */
    public function store(Request $request)
    {
        $request->validate([
            'program_code' => 'required|string|max:20',
            'category' => 'required|in:internal,external',
            'source_institution' => 'required_if:category,external|nullable|string|max:255',
            'academic_year' => 'required|string|max:20',
            'semester' => 'required|in:1,2',
            'copy_from_previous' => 'nullable|boolean',
        ]);

        $semesterString = $request->academic_year . '-' . $request->semester;
        $sourceInstitution = $request->category === 'external' ? $request->source_institution : null;

        // Check if list already exists
        $existingList = EquivalencyList::where('program_code', $request->program_code)
            ->where('semester', $semesterString)
            ->where('category', $request->category)
            ->where('source_institution', $sourceInstitution)
            ->first();

        if ($existingList) {
            return back()->withErrors(['semester' => 'An equivalency list already exists for this program, semester, and source.']);
        }

        DB::beginTransaction();
        try {
            $programName = $this->getProgramName($request->program_code);

            // Create the list
            $list = EquivalencyList::create([
                'program_code' => $request->program_code,
                'program_name' => $programName,
                'semester' => $semesterString,
                'academic_year' => $request->academic_year,
                'category' => $request->category,
                'source_institution' => $sourceInstitution,
                'status' => EquivalencyList::STATUS_DRAFT,
                'created_by_user_id' => Auth::id(),
            ]);

            // Copy equivalencies from previous semester if requested
            if ($request->copy_from_previous) {
                $this->copyFromPreviousSemester($list, $request->program_code, $request->category, $sourceInstitution);
            }

            DB::commit();

            return redirect()->route('program_coordinator.equivalency_lists.edit', $list)
                ->with('success', 'Equivalency list created successfully. You can now add course mappings.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to create equivalency list: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified equivalency list
     */
    public function show(EquivalencyList $list)
    {
        $list->load(['creator', 'publisher', 'courseEquivalencies']);

        // Program Coordinators have full edit access
        $isReadOnly = false;

        return view('program_coordinator.equivalency_lists.show', compact('list', 'isReadOnly'));
    }

    /**
     * Show the form for editing the equivalency list
     */
    public function edit(EquivalencyList $list)
    {
        // Can only edit draft lists
        if (!$list->canBeEdited()) {
            return redirect()->route('program_coordinator.equivalency_lists.show', $list)
                ->with('error', 'This list cannot be edited in its current status.');
        }

        $equivalencies = $list->courseEquivalencies()->orderBy('diploma_course_code')->get();

        return view('program_coordinator.equivalency_lists.edit', compact('list', 'equivalencies'));
    }

    /**
     * Add a course mapping to the list
     */
    public function addMapping(Request $request, EquivalencyList $list)
    {
        if (!$list->canBeEdited()) {
            return back()->withErrors(['error' => 'Cannot add mappings to a published list.']);
        }

        $request->validate([
            'diploma_course_code' => 'required|string|max:50',
            'diploma_course_name' => 'required|string|max:255',
            'diploma_credit_hour' => 'required|integer|min:1|max:10',
            'degree_course_code' => 'required|string|max:50',
            'degree_course_name' => 'required|string|max:255',
            'degree_credit_hour' => 'required|integer|min:1|max:10',
            'match_percentage' => 'required|numeric|min:0|max:100',
        ]);

        // Check for duplicates
        $exists = CourseEquivalency::where('equivalency_list_id', $list->id)
            ->where('diploma_course_code', $request->diploma_course_code)
            ->where('degree_course_code', $request->degree_course_code)
            ->exists();

        if ($exists) {
            return back()->withErrors(['diploma_course_code' => 'This course mapping already exists in the list.']);
        }

        $diplomaInstitution = $list->isInternal() ? 'UiTM (CS110)' : $list->source_institution;
        $isEligible = $request->match_percentage >= 80;

        CourseEquivalency::create([
            'equivalency_list_id' => $list->id,
            'diploma_course_code' => strtoupper($request->diploma_course_code),
            'diploma_course_name' => $request->diploma_course_name,
            'diploma_credit_hour' => $request->diploma_credit_hour,
            'diploma_institution' => $diplomaInstitution,
            'degree_course_code' => strtoupper($request->degree_course_code),
            'degree_course_name' => $request->degree_course_name,
            'degree_credit_hour' => $request->degree_credit_hour,
            'program_code' => $list->program_code,
            'match_percentage' => $request->match_percentage,
            'is_eligible' => $isEligible,
            'approved_by_user_id' => Auth::id(),
            'source' => 'manual',
        ]);

        // Update statistics
        $list->updateStatistics();

        return back()->with('success', 'Course mapping added successfully.');
    }

    /**
     * Update a course mapping
     */
    public function updateMapping(Request $request, EquivalencyList $list, CourseEquivalency $mapping)
    {
        if (!$list->canBeEdited() || $mapping->equivalency_list_id !== $list->id) {
            return back()->withErrors(['error' => 'Cannot update this mapping.']);
        }

        $request->validate([
            'diploma_course_code' => 'required|string|max:50',
            'diploma_course_name' => 'required|string|max:255',
            'diploma_credit_hour' => 'required|integer|min:1|max:10',
            'degree_course_code' => 'required|string|max:50',
            'degree_course_name' => 'required|string|max:255',
            'degree_credit_hour' => 'required|integer|min:1|max:10',
            'match_percentage' => 'required|numeric|min:0|max:100',
        ]);

        $isEligible = $request->match_percentage >= 80;

        $mapping->update([
            'diploma_course_code' => strtoupper($request->diploma_course_code),
            'diploma_course_name' => $request->diploma_course_name,
            'diploma_credit_hour' => $request->diploma_credit_hour,
            'degree_course_code' => strtoupper($request->degree_course_code),
            'degree_course_name' => $request->degree_course_name,
            'degree_credit_hour' => $request->degree_credit_hour,
            'match_percentage' => $request->match_percentage,
            'is_eligible' => $isEligible,
        ]);

        $list->updateStatistics();

        return back()->with('success', 'Course mapping updated successfully.');
    }

    /**
     * Delete a course mapping
     */
    public function deleteMapping(EquivalencyList $list, CourseEquivalency $mapping)
    {
        if (!$list->canBeEdited() || $mapping->equivalency_list_id !== $list->id) {
            return back()->withErrors(['error' => 'Cannot delete this mapping.']);
        }

        $mapping->delete();
        $list->updateStatistics();

        return back()->with('success', 'Course mapping deleted successfully.');
    }

    /**
     * Publish the list (make it active for students)
     */
    public function publish(EquivalencyList $list)
    {
        if (!$list->canBePublished()) {
            return back()->withErrors(['error' => 'This list cannot be published. It must be in draft status and have at least one course mapping.']);
        }

        DB::beginTransaction();
        try {
            $list->publish(Auth::user());

            // TODO: Send email notifications to students
            // $students = Student::where('program_code', $list->program_code)->get();
            // foreach ($students as $student) {
            //     Mail::to($student->user->email)->queue(new NewEquivalencyListPublished($list));
            // }

            DB::commit();

            return redirect()->route('program_coordinator.equivalency_lists.show', $list)
                ->with('success', 'Equivalency list published successfully and is now active for students.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to publish list: ' . $e->getMessage()]);
        }
    }

    /**
     * View all draft equivalency lists
     */
    public function drafts()
    {
        $programs = ['CDCS230', 'CDCS251', 'CDCS253', 'CDCS255', 'CDCS266'];
        $programNames = [
            'CDCS230' => 'SARJANA MUDA SAINS KOMPUTER (KEPUJIAN)',
            'CDCS251' => 'SARJANA MUDA SAINS KOMPUTER (KEPUJIAN) PENGKOMPUTERAN NETSENTRIK',
            'CDCS253' => 'SARJANA MUDA SAINS KOMPUTER (KEPUJIAN) PENGKOMPUTERAN MULTIMEDIA',
            'CDCS255' => 'SARJANA MUDA SAINS KOMPUTER (KEPUJIAN) RANGKAIAN KOMPUTER',
            'CDCS266' => 'SARJANA MUDA SISTEM MAKLUMAT (KEPUJIAN) KEJURUTERAAN SISTEM MAKLUMAT',
        ];

        $programData = [];
        foreach ($programs as $programCode) {
            $drafts = EquivalencyList::where('program_code', $programCode)
                ->where('status', 'draft')
                ->with(['creator', 'courseEquivalencies'])
                ->orderBy('created_at', 'desc')
                ->get();

            $programData[$programCode] = [
                'name' => $programNames[$programCode],
                'drafts' => $drafts,
                'count' => $drafts->count(),
            ];
        }

        // Get statistics
        $stats = [
            'total_drafts' => EquivalencyList::where('status', 'draft')->count(),
            'total_published' => EquivalencyList::where('status', 'published')->count(),
        ];

        return view('program_coordinator.equivalency_lists.drafts', compact('programs', 'programData', 'stats'));
    }

    /**
     * View pending mappings forwarded by Resource Persons
     */
    public function pendingMappings()
    {
        $pendingMappings = PendingEquivalencyMapping::pending()
            ->with(['resourcePerson', 'courseEquivalencyRequest'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('program_code');

        return view('program_coordinator.pending_mappings.index', compact('pendingMappings'));
    }

    /**
     * Add a pending mapping from RP to an equivalency list
     */
    public function addPendingMapping(Request $request, PendingEquivalencyMapping $mapping)
    {
        if (!$mapping->isPending()) {
            return back()->withErrors(['error' => 'This mapping has already been processed.']);
        }

        $request->validate([
            'equivalency_list_id' => 'required|exists:equivalency_lists,id',
        ]);

        DB::beginTransaction();
        try {
            $list = EquivalencyList::findOrFail($request->equivalency_list_id);

            // Check if list can be edited
            if (!$list->canBeEdited()) {
                return back()->withErrors(['error' => 'Cannot add mappings to a published list.']);
            }

            // Check for duplicates
            $exists = CourseEquivalency::where('equivalency_list_id', $list->id)
                ->where('diploma_course_code', $mapping->diploma_course_code)
                ->where('degree_course_code', $mapping->degree_course_code)
                ->exists();

            if ($exists) {
                return back()->withErrors(['error' => 'This course mapping already exists in the selected list.']);
            }

            // Add mapping to equivalency list
            CourseEquivalency::create([
                'equivalency_list_id' => $list->id,
                'diploma_course_code' => $mapping->diploma_course_code,
                'diploma_course_name' => $mapping->diploma_course_name,
                'diploma_credit_hour' => $mapping->diploma_credit_hour,
                'diploma_institution' => $mapping->diploma_institution,
                'degree_course_code' => $mapping->degree_course_code,
                'degree_course_name' => $mapping->degree_course_name,
                'degree_credit_hour' => $mapping->degree_credit_hour,
                'program_code' => $mapping->program_code,
                'match_percentage' => $mapping->match_percentage,
                'is_eligible' => $mapping->match_percentage >= 80,
                'approved_by_user_id' => Auth::id(),
                'source' => 'resource_person',
                'notes' => 'Added from RP forwarded mapping: ' . $mapping->notes,
            ]);

            // Update list statistics
            $list->updateStatistics();

            // Mark mapping as added
            $mapping->markAsAdded(Auth::id());

            // TODO: Send response back to student if this was from a student request
            // if ($mapping->course_equivalency_request_id) {
            //     $request = CourseEquivalencyRequest::find($mapping->course_equivalency_request_id);
            //     // Send notification to student
            // }

            DB::commit();

            return back()->with('success', 'Course mapping added to equivalency list successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to add mapping: ' . $e->getMessage()]);
        }
    }

    /**
     * Reject a pending mapping from RP
     */
    public function rejectPendingMapping(Request $request, PendingEquivalencyMapping $mapping)
    {
        if (!$mapping->isPending()) {
            return back()->withErrors(['error' => 'This mapping has already been processed.']);
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $mapping->markAsRejected(Auth::id(), $request->rejection_reason);

            // TODO: Notify Resource Person about rejection
            // Mail::to($mapping->resourcePerson->email)->send(new MappingRejected($mapping));

            DB::commit();

            return back()->with('success', 'Pending mapping rejected.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to reject mapping: ' . $e->getMessage()]);
        }
    }

    /**
     * Delete an equivalency list (only drafts can be deleted)
     */
    public function destroy(EquivalencyList $list)
    {
        if ($list->status !== EquivalencyList::STATUS_DRAFT) {
            return back()->withErrors(['error' => 'Only draft lists can be deleted.']);
        }

        DB::beginTransaction();
        try {
            // Delete all course equivalencies first
            $list->courseEquivalencies()->delete();

            // Delete the list
            $list->delete();

            DB::commit();

            return redirect()->route('program_coordinator.equivalency_lists.index')
                ->with('success', 'Equivalency list deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to delete list: ' . $e->getMessage()]);
        }
    }

    /**
     * Helper: Get list of programs
     */
    private function getProgramsList(): array
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
     * Helper: Get program name
     */
    private function getProgramName(string $code): string
    {
        $programs = $this->getProgramsList();
        return $programs[$code] ?? $code;
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
     * Helper: Copy equivalencies from previous semester
     */
    private function copyFromPreviousSemester(EquivalencyList $newList, string $programCode, string $category, ?string $sourceInstitution): void
    {
        $previousList = EquivalencyList::where('program_code', $programCode)
            ->where('category', $category)
            ->where('source_institution', $sourceInstitution)
            ->where('status', EquivalencyList::STATUS_PUBLISHED)
            ->where('id', '!=', $newList->id)
            ->orderBy('published_at', 'desc')
            ->first();

        if (!$previousList) {
            return;
        }

        foreach ($previousList->courseEquivalencies as $eq) {
            CourseEquivalency::create([
                'equivalency_list_id' => $newList->id,
                'diploma_course_code' => $eq->diploma_course_code,
                'diploma_course_name' => $eq->diploma_course_name,
                'diploma_credit_hour' => $eq->diploma_credit_hour,
                'diploma_institution' => $eq->diploma_institution,
                'degree_course_code' => $eq->degree_course_code,
                'degree_course_name' => $eq->degree_course_name,
                'degree_credit_hour' => $eq->degree_credit_hour,
                'program_code' => $eq->program_code,
                'match_percentage' => $eq->match_percentage,
                'is_eligible' => $eq->is_eligible,
                'approved_by_user_id' => Auth::id(),
                'source' => 'manual',
                'notes' => 'Copied from ' . $previousList->semester,
            ]);
        }

        $newList->updateStatistics();
    }

    /**
     * View all course equivalencies across all lists (published and drafts)
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
        $institutions = CourseEquivalency::whereNotNull('diploma_institution')
            ->where('diploma_institution', '!=', '')
            ->distinct()
            ->orderBy('diploma_institution')
            ->pluck('diploma_institution')
            ->toArray();

        // Pass the correct API route for Program Coordinators
        $apiRoute = route('program_coordinator.api.existing_equivalencies');
        $backRoute = route('program_coordinator.equivalency_lists.index');

        return view('program_coordinator.course_equivalencies.view', compact('programs', 'institutions', 'apiRoute', 'backRoute'));
    }

    /**
     * API endpoint to get existing course equivalencies for a program
     */
    public function getExistingEquivalencies(Request $request)
    {
        $programCode = $request->get('program_code');
        $status = $request->get('status', 'current'); // current, all_published, include_drafts
        $semester = $request->get('semester');
        $category = $request->get('category');
        $institution = $request->get('institution');

        if (!$programCode) {
            return response()->json(['error' => 'Program code is required'], 400);
        }

        // Build query for equivalencies
        $query = CourseEquivalency::with('equivalencyList');

        // Filter by program code
        if ($programCode !== 'ALL') {
            $query->where('program_code', $programCode);
        }

        // Filter by status
        $query->whereHas('equivalencyList', function($q) use ($status, $programCode) {
            if ($status === 'current') {
                // Only get from latest published list per program
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
            // 'include_drafts' = no status filter
        });

        // Filter by semester
        if ($semester && $semester !== 'ALL') {
            $query->whereHas('equivalencyList', function($q) use ($semester) {
                $q->where('semester', $semester);
            });
        }

        // Filter by category
        if ($category && $category !== 'ALL') {
            $query->whereHas('equivalencyList', function($q) use ($category) {
                $q->where('category', $category);
            });
        }

        // Filter by institution
        if ($institution && $institution !== 'ALL') {
            $query->whereHas('equivalencyList', function($q) use ($institution) {
                if ($institution === 'internal') {
                    $q->where('category', 'internal');
                } else {
                    $q->where('source_institution', $institution);
                }
            });
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
                    'list_status' => $eq->equivalencyList->status ?? 'N/A',
                    'source_institution' => $eq->equivalencyList->source_institution ?? 'Internal',
                ];
            });

        return response()->json($equivalencies);
    }

    /**
     * Download PDF of a published equivalency list
     */
    public function downloadListPdf(EquivalencyList $list)
    {
        // Ensure the list is published and HEA-endorsed
        if ($list->endorsed_at === null || !$list->is_active) {
            abort(404, 'List not available');
        }

        $list->load(['creator', 'endorser', 'publisher', 'courseEquivalencies']);

        return view('student.course_equivalencies.pdf', compact('list'));
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

        DB::beginTransaction();
        try {
            // Get program name from database or use static mapping
            $programNames = [
                'CDCS230' => 'Bachelor of Computer Science (Hons.)',
                'CDCS251' => 'Bachelor of Computer Science (Hons.) Netcentric Computing',
                'CDCS253' => 'Bachelor of Computer Science (Hons.) Multimedia Computing',
                'CDCS255' => 'Bachelor of Computer Science (Hons.) Computer Networking',
                'CDCS266' => 'Bachelor of Information Systems (Hons.) Information Systems Engineering',
            ];
            $programName = $programNames[$request->program_code] ?? $request->program_code;

            // Find or create an appropriate equivalency list for this mapping
            $list = EquivalencyList::firstOrCreate(
                [
                    'program_code' => $request->program_code,
                    'category' => strtoupper($request->diploma_institution) === 'CS110' ? 'internal' : 'external',
                    'source_institution' => strtoupper($request->diploma_institution) === 'CS110' ? null : $request->diploma_institution,
                    'status' => EquivalencyList::STATUS_DRAFT,
                ],
                [
                    'program_name' => $programName,
                    'semester' => now()->format('Y/Y') . '-' . (now()->month >= 7 ? '1' : '2'), // Auto-generate semester
                    'academic_year' => now()->format('Y/Y'),
                    'created_by_user_id' => Auth::id(),
                    'total_equivalencies' => 0,
                    'eligible_count' => 0,
                ]
            );

            // Create the course equivalency
            $equivalency = CourseEquivalency::create([
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
                'source' => 'manual', // Manually created by program coordinator
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
    public function showCourseEquivalency(CourseEquivalency $mapping)
    {
        return response()->json([
            'success' => true,
            'mapping' => $mapping
        ]);
    }

    /**
     * Update an existing course equivalency (for "All Course Mappings" CRUD)
     */
    public function updateCourseEquivalency(Request $request, CourseEquivalency $mapping)
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
    public function destroyCourseEquivalency(CourseEquivalency $mapping)
    {
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
