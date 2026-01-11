<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CourseEquivalency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EquivalencyCheckerController extends Controller
{
    /**
     * Search for diploma courses from course equivalencies
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchDiplomaCourses(Request $request)
    {
        try {
            $query = $request->input('q', '');
            $programCode = $request->input('program', '');

            // Return empty array if search query is too short
            if (strlen($query) < 3) {
                return response()->json([]);
            }

            // Search diploma courses from BOTH course_equivalencies AND course_equivalency_requests tables
            // This shows both already-mapped courses and previously requested courses
            // Search pattern: starts with query for course codes, anywhere for course names
            $fromEquivalencies = \DB::table('course_equivalencies')
                ->select(
                    'diploma_course_code as code',
                    'diploma_course_name as name',
                    'diploma_institution as institution',
                    'diploma_credit_hour as credit_hour',
                    \DB::raw("'equivalency' as source")
                )
                ->where(function ($q) use ($query) {
                    $q->where('diploma_course_code', 'LIKE', "{$query}%")
                      ->orWhere('diploma_course_name', 'LIKE', "%{$query}%");
                });

            $fromRequests = \DB::table('course_equivalency_requests')
                ->select(
                    'diploma_course_code as code',
                    'diploma_course_name as name',
                    'diploma_institution as institution',
                    'diploma_credit_hours as credit_hour',
                    \DB::raw("'request' as source")
                )
                ->where(function ($q) use ($query) {
                    $q->where('diploma_course_code', 'LIKE', "{$query}%")
                      ->orWhere('diploma_course_name', 'LIKE', "%{$query}%");
                });

            // Combine both queries and remove duplicates
            $courses = $fromEquivalencies
                ->union($fromRequests)
                ->get()
                ->unique('code')
                ->take(20)
                ->map(function ($course) {
                    return [
                        'code' => $course->code,
                        'name' => $course->name,
                        'institution' => $course->institution,
                        'credit_hour' => $course->credit_hour,
                    ];
                })
                ->values();

            \Log::info('Diploma course search', [
                'query' => $query,
                'program_code' => $programCode,
                'results_count' => $courses->count()
            ]);

            return response()->json($courses);

        } catch (\Exception $e) {
            \Log::error('Error searching diploma courses', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }

    /**
     * Search for degree courses from course equivalencies
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchDegreeCourses(Request $request)
    {
        try {
            $query = $request->input('q', '');

            // Return empty array if search query is too short
            if (strlen($query) < 3) {
                return response()->json([]);
            }

            // Search degree courses from BOTH courses table AND course_equivalencies table
            // This shows all UiTM degree courses plus any from equivalency mappings
            // Search pattern: starts with query for course codes, anywhere for course names
            $fromCourses = \DB::table('courses')
                ->select(
                    'code',
                    'name',
                    'credit_hour',
                    \DB::raw("'courses' as source")
                )
                ->where(function ($q) use ($query) {
                    $q->where('code', 'LIKE', "{$query}%")
                      ->orWhere('name', 'LIKE', "%{$query}%");
                });

            $fromEquivalencies = \DB::table('course_equivalencies')
                ->select(
                    'degree_course_code as code',
                    'degree_course_name as name',
                    'degree_credit_hour as credit_hour',
                    \DB::raw("'equivalency' as source")
                )
                ->where(function ($q) use ($query) {
                    $q->where('degree_course_code', 'LIKE', "{$query}%")
                      ->orWhere('degree_course_name', 'LIKE', "%{$query}%");
                });

            // Combine both queries and remove duplicates
            $courses = $fromCourses
                ->union($fromEquivalencies)
                ->get()
                ->unique('code')
                ->take(20)
                ->map(function ($course) {
                    return [
                        'code' => $course->code,
                        'name' => $course->name,
                        'credit_hour' => $course->credit_hour,
                    ];
                })
                ->values();

            \Log::info('Degree course search', [
                'query' => $query,
                'results_count' => $courses->count()
            ]);

            return response()->json($courses);

        } catch (\Exception $e) {
            \Log::error('Error searching degree courses', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }

    /**
     * Check if a diploma-degree course pairing has an equivalency
     *
     * Logic:
     * - Search for row where diploma_course_code AND degree_course_code match
     * - If found, check match_percentage field:
     *   - match_percentage >= 80% → EQUIVALENT
     *   - match_percentage < 80% → NOT EQUIVALENT
     * - If no row found → NOT FOUND
     *
     * Does NOT filter by program_code, is_published, or is_eligible
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkEquivalency(Request $request)
    {
        $validated = $request->validate([
            'diploma_code' => 'required|string|max:20',
            'degree_code' => 'required|string|max:20',
            'program_code' => 'nullable|string|max:10', // Keep for backward compatibility but don't use in query
        ]);

        // Search for row where BOTH codes match, regardless of program/published status
        $equivalency = \DB::table('course_equivalencies')
            ->where('diploma_course_code', strtoupper(trim($validated['diploma_code'])))
            ->where('degree_course_code', strtoupper(trim($validated['degree_code'])))
            ->first();

        if ($equivalency) {
            // Check match_percentage to determine equivalency status
            $matchPercentage = $equivalency->match_percentage ?? 0;
            $isEquivalent = $matchPercentage >= 80;

            return response()->json([
                'found' => true,
                'is_equivalent' => $isEquivalent,
                'match_percentage' => $matchPercentage,
                'data' => [
                    'diploma_course_name' => $equivalency->diploma_course_name,
                    'diploma_institution' => $equivalency->diploma_institution,
                    'diploma_credit_hour' => $equivalency->diploma_credit_hour,
                    'degree_course_name' => $equivalency->degree_course_name,
                    'degree_credit_hour' => $equivalency->degree_credit_hour,
                    'program_code' => $equivalency->program_code,
                ],
            ]);
        }

        return response()->json([
            'found' => false,
        ]);
    }
}
