<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\EquivalencyList;
use App\Models\CourseEquivalency;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EquivalencyViewController extends Controller
{
    /**
     * Display the course equivalency lists selection page
     */
    public function index(Request $request)
    {
        // Get the current student
        $student = Student::where('user_id', Auth::id())->first();

        // Use the student's registered program code
        $selectedProgram = $student?->program_code ?? null;

        // Get program name for display
        $programName = $this->getProgramName($selectedProgram);

        // Get active lists for the selected program
        $internalList = null;
        $externalLists = collect();

        if ($selectedProgram) {
            // Get internal (CS110) list
            $internalList = EquivalencyList::published()
                ->active()
                ->internal()
                ->where('program_code', $selectedProgram)
                ->with(['courseEquivalencies', 'endorser'])
                ->first();

            // Get external lists grouped by institution
            $externalLists = EquivalencyList::published()
                ->active()
                ->external()
                ->where('program_code', $selectedProgram)
                ->with(['courseEquivalencies', 'endorser'])
                ->get()
                ->groupBy('source_institution');
        }

        // Get list of external institutions with active lists
        $externalInstitutions = EquivalencyList::published()
            ->active()
            ->external()
            ->where('program_code', $selectedProgram)
            ->pluck('source_institution')
            ->unique()
            ->sort()
            ->values();

        return view('student.course_equivalencies.index', compact(
            'student',
            'selectedProgram',
            'programName',
            'internalList',
            'externalLists',
            'externalInstitutions'
        ));
    }

    /**
     * Display a specific equivalency list
     */
    public function show(Request $request, string $category, ?string $source = null)
    {
        $student = Student::where('user_id', Auth::id())->first();
        $programCode = $request->get('program');
        $search = $request->get('search');

        // Validate category
        if (!in_array($category, ['internal', 'external'])) {
            abort(404, 'Invalid category');
        }

        // For external, source is required
        if ($category === 'external' && !$source) {
            return redirect()->route('student.course_equivalencies.index');
        }

        // Build query
        $query = EquivalencyList::published()
            ->active()
            ->where('category', $category);

        if ($programCode) {
            $query->where('program_code', $programCode);
        }

        if ($category === 'external' && $source) {
            $query->where('source_institution', $source);
        }

        $list = $query->with(['courseEquivalencies', 'endorser', 'creator'])->first();

        if (!$list) {
            return redirect()->route('student.course_equivalencies.index')
                ->with('error', 'No published equivalency list found for the selected criteria.');
        }

        // Get equivalencies with optional search
        $equivalencies = $list->courseEquivalencies;

        if ($search) {
            $searchLower = strtolower($search);
            $equivalencies = $equivalencies->filter(function ($eq) use ($searchLower) {
                return str_contains(strtolower($eq->diploma_course_code), $searchLower) ||
                       str_contains(strtolower($eq->diploma_course_name), $searchLower) ||
                       str_contains(strtolower($eq->degree_course_code), $searchLower) ||
                       str_contains(strtolower($eq->degree_course_name), $searchLower);
            });
        }

        // Sort by diploma course code
        $equivalencies = $equivalencies->sortBy('diploma_course_code');

        return view('student.course_equivalencies.show', compact(
            'student',
            'list',
            'equivalencies',
            'search',
            'category',
            'source'
        ));
    }

    /**
     * Download PDF of an equivalency list
     */
    public function downloadPdf(EquivalencyList $list)
    {
        // Ensure the list is published and active
        // Check published_at instead of status (lists can be in draft for continuous editing)
        if ($list->published_at === null || !$list->is_active) {
            abort(404, 'List not available');
        }

        $list->load(['creator', 'endorser', 'courseEquivalencies']);

        return view('student.course_equivalencies.pdf', compact('list'));
    }

    /**
     * Get program name from code
     */
    private function getProgramName(?string $code): string
    {
        if (!$code) {
            return 'Not Selected';
        }

        $programNames = [
            'CDCS251' => 'Bachelor of Computer Science (Hons.) Netcentric Computing',
            'CDCS230' => 'Bachelor of Computer Science (Hons.)',
            'CDCS253' => 'Bachelor of Computer Science (Hons.) Multimedia Computing',
            'CDCS255' => 'Bachelor of Computer Science (Hons.) Computer Networking',
            'CDCS266' => 'Bachelor of Information Systems (Hons.) Information Systems Engineering',
        ];

        return $programNames[$code] ?? $code;
    }
}
