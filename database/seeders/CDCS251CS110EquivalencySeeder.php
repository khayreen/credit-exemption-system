<?php

namespace Database\Seeders;

use App\Models\CourseEquivalency;
use App\Models\EquivalencyList;
use Illuminate\Database\Seeder;

class CDCS251CS110EquivalencySeeder extends Seeder
{
    /**
     * Seed CS110 → CDCS251 course equivalencies (14 courses total)
     * Based on official UiTM curriculum document (Kemaskini 19 September 2022)
     */
    public function run(): void
    {
        // Get a resource person to use as approver for seeded courses
        $resourcePerson = \App\Models\User::whereHas('resourcePerson')->first();
        if (!$resourcePerson) {
            echo "❌ Error: No resource person found in database. Please seed users first.\n";
            return;
        }

        // Find or create the CDCS251 internal CS110 equivalency list
        $list = EquivalencyList::firstOrCreate(
            [
                'program_code' => 'CDCS251',
                'category' => EquivalencyList::CATEGORY_INTERNAL,
                'source_institution' => null,
            ],
            [
                'program_name' => 'Bachelor of Computer Science (Hons.) Netcentric Computing',
                'status' => EquivalencyList::STATUS_DRAFT,
                'is_active' => true,
                'semester' => 'Semester 1 2024/2025',
                'published_at' => now(),
            ]
        );

        // Clear existing CS110 courses only (keep external institution courses)
        CourseEquivalency::where('equivalency_list_id', $list->id)
            ->where(function($query) {
                $query->where('diploma_course_code', 'LIKE', 'CSC%')
                      ->orWhere('diploma_course_code', 'LIKE', 'ICT%')
                      ->orWhere('diploma_course_code', 'LIKE', 'STA%')
                      ->orWhere('diploma_course_code', 'LIKE', 'MAT%')
                      ->orWhere('diploma_course_code', 'LIKE', 'ITT%')
                      ->orWhere('diploma_course_code', 'LIKE', 'HXXX%');
            })
            ->delete();

        echo "Adding 14 CS110 → CDCS251 course equivalencies...\n";

        // Main Courses (11 courses - 30 credits)
        $courses = [
            // 1. CSC126 → CSC402
            [
                'diploma_course_code' => 'CSC126',
                'diploma_course_name' => 'Fundamentals of Algorithms and Computer Problem Solving',
                'diploma_credit_hour' => 3,
                'degree_course_code' => 'CSC402',
                'degree_course_name' => 'Programming I',
                'degree_credit_hour' => 3,
                'match_percentage' => 100,
                'is_eligible' => true,
            ],

            // 2. CSC253 → CSC413
            [
                'diploma_course_code' => 'CSC253',
                'diploma_course_name' => 'Interactive Multimedia',
                'diploma_credit_hour' => 3,
                'degree_course_code' => 'CSC413',
                'degree_course_name' => 'Introduction to Interactive Multimedia',
                'degree_credit_hour' => 3,
                'match_percentage' => 100,
                'is_eligible' => true,
            ],

            // 3. CSC159 → CSC428
            [
                'diploma_course_code' => 'CSC159',
                'diploma_course_name' => 'Computer Organization',
                'diploma_credit_hour' => 3,
                'degree_course_code' => 'CSC428',
                'degree_course_name' => 'Computer Architecture & Organization',
                'degree_credit_hour' => 3,
                'match_percentage' => 100,
                'is_eligible' => true,
            ],

            // 4. CSC186 → CSC435
            [
                'diploma_course_code' => 'CSC186',
                'diploma_course_name' => 'Object Oriented Programming',
                'diploma_credit_hour' => 3,
                'degree_course_code' => 'CSC435',
                'degree_course_name' => 'Object Oriented Programming',
                'degree_credit_hour' => 3,
                'match_percentage' => 100,
                'is_eligible' => true,
            ],

            // 5. CSC138/CSC126 → CSC404 (Combination course)
            [
                'diploma_course_code' => 'CSC138/CSC126',
                'diploma_course_name' => 'Structure Programming + Fundamentals of Algorithms and Computer Problem Solving',
                'diploma_credit_hour' => 3,
                'degree_course_code' => 'CSC404',
                'degree_course_name' => 'Programming II',
                'degree_credit_hour' => 3,
                'match_percentage' => 100,
                'is_eligible' => true,
                'notes' => 'Combination course: CSC138 OR CSC126 required',
            ],

            // 6. ICT450 → ICT200
            [
                'diploma_course_code' => 'ICT450',
                'diploma_course_name' => 'Database Design and Development',
                'diploma_credit_hour' => 3,
                'degree_course_code' => 'ICT200',
                'degree_course_name' => 'Intro to Database Management',
                'degree_credit_hour' => 3,
                'match_percentage' => 100,
                'is_eligible' => true,
            ],

            // 7. STA416 → STA116
            [
                'diploma_course_code' => 'STA416',
                'diploma_course_name' => 'Applied Statistics & Probability',
                'diploma_credit_hour' => 3,
                'degree_course_code' => 'STA116',
                'degree_course_name' => 'Introduction to Probability & Statistics',
                'degree_credit_hour' => 3,
                'match_percentage' => 100,
                'is_eligible' => true,
            ],

            // 8. ITT400 → ITT300
            [
                'diploma_course_code' => 'ITT400',
                'diploma_course_name' => 'Intro to Data Communication & Networking',
                'diploma_credit_hour' => 3,
                'degree_course_code' => 'ITT300',
                'degree_course_name' => 'Introduction to Data Communication & Networking',
                'degree_credit_hour' => 3,
                'match_percentage' => 100,
                'is_eligible' => true,
            ],

            // 9. MAT406 → MAT133
            [
                'diploma_course_code' => 'MAT406',
                'diploma_course_name' => 'Foundation Mathematics',
                'diploma_credit_hour' => 3,
                'degree_course_code' => 'MAT133',
                'degree_course_name' => 'Pre Calculus',
                'degree_credit_hour' => 3,
                'match_percentage' => 100,
                'is_eligible' => true,
            ],

            // 10. MAT421 → MAT183
            [
                'diploma_course_code' => 'MAT421',
                'diploma_course_name' => 'Calculus I',
                'diploma_credit_hour' => 3,
                'degree_course_code' => 'MAT183',
                'degree_course_name' => 'Calculus I',
                'degree_credit_hour' => 3,
                'match_percentage' => 100,
                'is_eligible' => true,
            ],

            // 11. CSC574 → CSC264
            [
                'diploma_course_code' => 'CSC574',
                'diploma_course_name' => 'Dynamic Web Application Development',
                'diploma_credit_hour' => 4,
                'degree_course_code' => 'CSC264',
                'degree_course_name' => 'Introduction to Web and Mobile Application',
                'degree_credit_hour' => 4,
                'match_percentage' => 100,
                'is_eligible' => true,
            ],

            // Co-Curriculum Courses (3 courses - 3 credits)

            // 12. HXXX → HXXX (Ko Kurikulum I)
            [
                'diploma_course_code' => 'HXXX',
                'diploma_course_name' => 'Ko Kurikulum I',
                'diploma_credit_hour' => 1,
                'degree_course_code' => 'HXXX',
                'degree_course_name' => 'Ko Kurikulum I',
                'degree_credit_hour' => 1,
                'match_percentage' => 100,
                'is_eligible' => true,
                'notes' => 'Co-Curriculum I (direct PC to student)',
            ],

            // 13. HXXX → HXXX (Ko Kurikulum II)
            [
                'diploma_course_code' => 'HXXX',
                'diploma_course_name' => 'Ko Kurikulum II',
                'diploma_credit_hour' => 1,
                'degree_course_code' => 'HXXX',
                'degree_course_name' => 'Ko Kurikulum II',
                'degree_credit_hour' => 1,
                'match_percentage' => 100,
                'is_eligible' => true,
                'notes' => 'Co-Curriculum II (direct PC to student)',
            ],

            // 14. HXXX → HXXX (Ko Kurikulum III)
            [
                'diploma_course_code' => 'HXXX',
                'diploma_course_name' => 'Ko Kurikulum III',
                'diploma_credit_hour' => 1,
                'degree_course_code' => 'HXXX',
                'degree_course_name' => 'Ko Kurikulum III',
                'degree_credit_hour' => 1,
                'match_percentage' => 100,
                'is_eligible' => true,
                'notes' => 'Co-Curriculum III (direct PC to student)',
            ],
        ];

        // Insert all courses
        foreach ($courses as $index => $course) {
            CourseEquivalency::create([
                'equivalency_list_id' => $list->id,
                'diploma_course_code' => $course['diploma_course_code'],
                'diploma_course_name' => $course['diploma_course_name'],
                'diploma_credit_hour' => $course['diploma_credit_hour'],
                'diploma_institution' => 'UiTM (CS110)',
                'degree_course_code' => $course['degree_course_code'],
                'degree_course_name' => $course['degree_course_name'],
                'degree_credit_hour' => $course['degree_credit_hour'],
                'program_code' => 'CDCS251',
                'match_percentage' => $course['match_percentage'],
                'is_eligible' => $course['is_eligible'],
                'is_published' => false,
                'source' => 'seeded',
                'approved_by_user_id' => $resourcePerson->id,
                'notes' => $course['notes'] ?? 'Official CS110 → CDCS251 equivalency from curriculum (Kemaskini 19 September 2022)',
            ]);

            echo "✓ Added: {$course['diploma_course_code']} → {$course['degree_course_code']}\n";
        }

        // Update list statistics
        $list->updateStatistics();

        echo "\n✅ Successfully added 14 CS110 → CDCS251 course equivalencies!\n";
        echo "Total equivalencies in list: {$list->total_equivalencies}\n";
        echo "Eligible for exemption: {$list->eligible_count}\n";
    }
}
