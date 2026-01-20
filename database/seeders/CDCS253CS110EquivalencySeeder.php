<?php

namespace Database\Seeders;

use App\Models\CourseEquivalency;
use App\Models\EquivalencyList;
use Illuminate\Database\Seeder;

class CDCS253CS110EquivalencySeeder extends Seeder
{
    /**
     * Seed CS110 → CDCS253 course equivalencies (14 courses total)
     * CDCS253: Sarjana Muda Sains Komputer (Kepujian) Pengkomputeran Multimedia
     * Note: CDCS253 shares the same equivalency list as CDCS230
     */
    public function run(): void
    {
        // Get a resource person to use as approver for seeded courses
        $resourcePerson = \App\Models\User::whereHas('resourcePerson')->first();
        if (!$resourcePerson) {
            echo "❌ Error: No resource person found in database. Please seed users first.\n";
            return;
        }

        // Find or create the CDCS253 internal CS110 equivalency list
        $list = EquivalencyList::firstOrCreate(
            [
                'program_code' => 'CDCS253',
                'category' => EquivalencyList::CATEGORY_INTERNAL,
                'source_institution' => null,
            ],
            [
                'program_name' => 'Sarjana Muda Sains Komputer (Kepujian) Pengkomputeran Multimedia',
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

        echo "Adding 14 CS110 → CDCS253 course equivalencies...\n";

        // All Courses (11 main courses + 3 co-curriculum)
        $courses = [
            // 1. CSC126 → CSC402
            [
                'diploma_course_code' => 'CSC126',
                'diploma_course_name' => 'Programming Technique I',
                'diploma_credit_hour' => 3,
                'degree_course_code' => 'CSC402',
                'degree_course_name' => 'Fundamentals of Computer Problem Solving',
                'degree_credit_hour' => 3,
                'match_percentage' => 92,
                'is_eligible' => true,
            ],

            // 2. CSC253 → CSC413
            [
                'diploma_course_code' => 'CSC253',
                'diploma_course_name' => 'Object Oriented Programming',
                'diploma_credit_hour' => 3,
                'degree_course_code' => 'CSC413',
                'degree_course_name' => 'Data Structures',
                'degree_credit_hour' => 3,
                'match_percentage' => 93,
                'is_eligible' => true,
            ],

            // 3. CSC159 → CSC429
            [
                'diploma_course_code' => 'CSC159',
                'diploma_course_name' => 'Computer Systems Fundamentals',
                'diploma_credit_hour' => 3,
                'degree_course_code' => 'CSC429',
                'degree_course_name' => 'Computer Organization',
                'degree_credit_hour' => 3,
                'match_percentage' => 90,
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
                'match_percentage' => 85,
                'is_eligible' => true,
            ],

            // 5. CSC138/CSC126 + CSC186 → CSC404 (Combination course)
            [
                'diploma_course_code' => 'CSC138/CSC126 + CSC186',
                'diploma_course_name' => 'Structured Programming / Programming Technique I + Object Oriented Programming',
                'diploma_credit_hour' => 6,
                'degree_course_code' => 'CSC404',
                'degree_course_name' => 'Programming II',
                'degree_credit_hour' => 3,
                'match_percentage' => 88,
                'is_eligible' => true,
                'notes' => 'Combination course: (CSC138 OR CSC126) + CSC186 required',
            ],

            // 6. ICT200 → ICT450
            [
                'diploma_course_code' => 'ICT200',
                'diploma_course_name' => 'Operating System Concepts',
                'diploma_credit_hour' => 3,
                'degree_course_code' => 'ICT450',
                'degree_course_name' => 'Operating Systems',
                'degree_credit_hour' => 3,
                'match_percentage' => 95,
                'is_eligible' => true,
            ],

            // 7. STA116 → STA416
            [
                'diploma_course_code' => 'STA116',
                'diploma_course_name' => 'Introduction to Statistics',
                'diploma_credit_hour' => 3,
                'degree_course_code' => 'STA416',
                'degree_course_name' => 'Probability and Statistics',
                'degree_credit_hour' => 3,
                'match_percentage' => 88,
                'is_eligible' => true,
            ],

            // 8. ITT300 → ITT400
            [
                'diploma_course_code' => 'ITT300',
                'diploma_course_name' => 'Data Communication and Networking',
                'diploma_credit_hour' => 3,
                'degree_course_code' => 'ITT400',
                'degree_course_name' => 'Data Communication and Networking',
                'degree_credit_hour' => 3,
                'match_percentage' => 94,
                'is_eligible' => true,
            ],

            // 9. CSC264 → CSC574
            [
                'diploma_course_code' => 'CSC264',
                'diploma_course_name' => 'Introduction to Web and Mobile Application',
                'diploma_credit_hour' => 4,
                'degree_course_code' => 'CSC574',
                'degree_course_name' => 'Web Programming',
                'degree_credit_hour' => 4,
                'match_percentage' => 94,
                'is_eligible' => true,
            ],

            // 10. MAT133 → MAT406
            [
                'diploma_course_code' => 'MAT133',
                'diploma_course_name' => 'Pre-Calculus',
                'diploma_credit_hour' => 3,
                'degree_course_code' => 'MAT406',
                'degree_course_name' => 'Pre-Calculus',
                'degree_credit_hour' => 3,
                'match_percentage' => 92,
                'is_eligible' => true,
            ],

            // 11. MAT183 → MAT421
            [
                'diploma_course_code' => 'MAT183',
                'diploma_course_name' => 'Calculus I',
                'diploma_credit_hour' => 3,
                'degree_course_code' => 'MAT421',
                'degree_course_name' => 'Calculus I',
                'degree_credit_hour' => 3,
                'match_percentage' => 85,
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
                'program_code' => 'CDCS253',
                'match_percentage' => $course['match_percentage'],
                'is_eligible' => $course['is_eligible'],
                'is_published' => false,
                'source' => 'seeded',
                'approved_by_user_id' => $resourcePerson->id,
                'notes' => $course['notes'] ?? 'Official CS110 → CDCS253 equivalency',
            ]);

            echo "✓ Added: {$course['diploma_course_code']} → {$course['degree_course_code']}\n";
        }

        // Update list statistics
        $list->updateStatistics();

        echo "\n✅ Successfully added 14 CS110 → CDCS253 course equivalencies!\n";
        echo "Total equivalencies in list: {$list->total_equivalencies}\n";
        echo "Eligible for exemption: {$list->eligible_count}\n";
    }
}
