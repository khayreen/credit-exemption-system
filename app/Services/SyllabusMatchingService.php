<?php

namespace App\Services;

use App\Models\DegreeCourseSyllabus;
use App\Models\CourseEquivalency;
use Illuminate\Support\Collection;

class SyllabusMatchingService
{
    /**
     * Find similar degree courses based on similarity to the student's requested degree course.
     * This helps RP find alternative degree courses to reference when the requested course
     * doesn't have an uploaded syllabus.
     *
     * @param string $suggestedDegreeCourseCode The student's requested degree course code
     * @param string $suggestedDegreeCourseName The student's requested degree course name
     * @param string $programCode The program code to filter equivalencies by (e.g., CDCS251)
     * @param int $limit Maximum number of recommendations to return
     * @return Collection Collection of degree courses with similarity scores
     */
    public function findSimilarCourses(
        string $suggestedDegreeCourseCode,
        string $suggestedDegreeCourseName,
        string $programCode,
        int $limit = 10
    ): Collection {
        $recommendations = collect();

        // 1. First, get ALL uploaded degree syllabi (not filtered by program since syllabi are shared)
        $degreeSyllabi = DegreeCourseSyllabus::active()->get();

        foreach ($degreeSyllabi as $syllabus) {
            $isSuggested = strtoupper($syllabus->course_code) === strtoupper($suggestedDegreeCourseCode);
            // For the suggested course, similarity is 100%. For others, calculate similarity to the suggested course name.
            $similarity = $isSuggested ? 100 : $this->calculateNameSimilarity($suggestedDegreeCourseName, $syllabus->course_name);

            $recommendations->push([
                'course_code' => $syllabus->course_code,
                'course_name' => $syllabus->course_name,
                'credit_hours' => $syllabus->credit_hours,
                'similarity' => $similarity,
                'has_syllabus' => true,
                'syllabus' => $syllabus,
                'source' => 'syllabus',
                'is_suggested' => $isSuggested,
            ]);
        }

        // 2. Also get from course equivalencies (degree courses for the student's program)
        $equivalencies = CourseEquivalency::where('program_code', $programCode)
            ->select('degree_course_code', 'degree_course_name', 'degree_credit_hour')
            ->distinct()
            ->get();

        foreach ($equivalencies as $equiv) {
            // Skip if we already have this course from syllabi
            if ($recommendations->contains('course_code', $equiv->degree_course_code)) {
                continue;
            }

            $isSuggested = strtoupper($equiv->degree_course_code) === strtoupper($suggestedDegreeCourseCode);
            $similarity = $isSuggested ? 100 : $this->calculateNameSimilarity($suggestedDegreeCourseName, $equiv->degree_course_name ?? '');

            $recommendations->push([
                'course_code' => $equiv->degree_course_code,
                'course_name' => $equiv->degree_course_name ?? 'Unknown',
                'credit_hours' => $equiv->degree_credit_hour ?? 3,
                'similarity' => $similarity,
                'has_syllabus' => false,
                'syllabus' => null,
                'source' => 'equivalency',
                'is_suggested' => $isSuggested,
            ]);
        }

        // 3. Always include the student's suggested course if not already present
        if (!$recommendations->contains('course_code', strtoupper($suggestedDegreeCourseCode))) {
            $recommendations->push([
                'course_code' => strtoupper($suggestedDegreeCourseCode),
                'course_name' => $suggestedDegreeCourseName,
                'credit_hours' => 3,
                'similarity' => 100, // It's the exact course requested
                'has_syllabus' => false,
                'syllabus' => null,
                'source' => 'suggested',
                'is_suggested' => true,
            ]);
        }

        // Sort: suggested course first, then by has_syllabus, then by similarity (highest first)
        $sorted = $recommendations->sortBy([
            ['is_suggested', 'desc'],   // Suggested course first
            ['has_syllabus', 'desc'],   // Courses with syllabi next
            ['similarity', 'desc'],      // Then by similarity
        ]);

        return $sorted->take($limit)->values();
    }

    /**
     * Find similar degree courses from uploaded syllabi only.
     * Compares against the student's requested degree course name.
     */
    public function findSimilarCoursesFromSyllabi(
        string $suggestedDegreeCourseCode,
        string $suggestedDegreeCourseName,
        int $limit = 5
    ): Collection {
        // Get all active syllabi (not filtered by program since syllabi are shared)
        $degreeCourses = DegreeCourseSyllabus::active()->get();

        if ($degreeCourses->isEmpty()) {
            return collect();
        }

        $recommendations = $degreeCourses->map(function ($course) use ($suggestedDegreeCourseCode, $suggestedDegreeCourseName) {
            $isSuggested = strtoupper($course->course_code) === strtoupper($suggestedDegreeCourseCode);
            $similarity = $isSuggested ? 100 : $this->calculateNameSimilarity($suggestedDegreeCourseName, $course->course_name);

            return [
                'course' => $course,
                'similarity' => $similarity,
                'is_suggested' => $isSuggested,
            ];
        });

        $sorted = $recommendations->sortBy([
            ['is_suggested', 'desc'],
            ['similarity', 'desc'],
        ]);

        return $sorted->take($limit)->values();
    }

    /**
     * Calculate name similarity between two course names.
     *
     * @param string $name1 First course name
     * @param string $name2 Second course name
     * @return int Similarity percentage (0-100)
     */
    public function calculateNameSimilarity(string $name1, string $name2): int
    {
        // Normalize names: lowercase, remove special characters, trim
        $normalized1 = $this->normalizeName($name1);
        $normalized2 = $this->normalizeName($name2);

        // If either name is empty after normalization, return 0
        if (empty($normalized1) || empty($normalized2)) {
            return 0;
        }

        // Method 1: similar_text percentage
        similar_text($normalized1, $normalized2, $similarTextPercent);

        // Method 2: Word overlap (Jaccard-like similarity)
        $wordOverlapPercent = $this->calculateWordOverlap($normalized1, $normalized2);

        // Method 3: Levenshtein-based similarity (for shorter names)
        $levenshteinPercent = $this->calculateLevenshteinSimilarity($normalized1, $normalized2);

        // Weighted average: prioritize word overlap for course names
        $finalSimilarity = (
            ($similarTextPercent * 0.3) +
            ($wordOverlapPercent * 0.5) +
            ($levenshteinPercent * 0.2)
        );

        return (int) round($finalSimilarity);
    }

    /**
     * Normalize a course name for comparison.
     */
    private function normalizeName(string $name): string
    {
        // Convert to lowercase
        $name = strtolower($name);

        // Remove special characters but keep spaces
        $name = preg_replace('/[^a-z0-9\s]/', '', $name);

        // Remove extra whitespace
        $name = preg_replace('/\s+/', ' ', $name);

        // Remove common filler words
        $fillerWords = ['and', 'the', 'of', 'for', 'in', 'to', 'with', 'a', 'an', 'i', 'ii', 'iii', 'iv', 'v'];
        $words = explode(' ', trim($name));
        $words = array_diff($words, $fillerWords);

        return implode(' ', $words);
    }

    /**
     * Calculate word overlap similarity (Jaccard-like).
     */
    private function calculateWordOverlap(string $name1, string $name2): float
    {
        $words1 = array_unique(explode(' ', $name1));
        $words2 = array_unique(explode(' ', $name2));

        $intersection = array_intersect($words1, $words2);
        $union = array_unique(array_merge($words1, $words2));

        if (count($union) === 0) {
            return 0;
        }

        return (count($intersection) / count($union)) * 100;
    }

    /**
     * Calculate Levenshtein-based similarity.
     */
    private function calculateLevenshteinSimilarity(string $name1, string $name2): float
    {
        $maxLength = max(strlen($name1), strlen($name2));

        if ($maxLength === 0) {
            return 100; // Both empty strings are identical
        }

        $levenshteinDistance = levenshtein($name1, $name2);
        $similarity = (1 - ($levenshteinDistance / $maxLength)) * 100;

        return max(0, $similarity); // Ensure non-negative
    }

    /**
     * Get a single degree course syllabus by course code.
     */
    public function getDegreeSyllabus(string $courseCode): ?DegreeCourseSyllabus
    {
        return DegreeCourseSyllabus::active()
            ->where('course_code', $courseCode)
            ->first();
    }

    /**
     * Check if a degree syllabus exists for a specific course.
     */
    public function hasSyllabus(string $courseCode): bool
    {
        return DegreeCourseSyllabus::active()
            ->where('course_code', $courseCode)
            ->exists();
    }
}
