<?php

namespace Database\Seeders;

use App\Models\CourseEquivalency;
use App\Models\EquivalencyList;
use Illuminate\Database\Seeder;

class CDCS255CS110EquivalencySeeder extends Seeder
{
    /**
     * Seed CS110 → CDCS255 course equivalencies (19 courses total including ISP451 combinations)
     * CDCS255: Sarjana Muda Sains Komputer (Kepujian) Rangkaian Komputer
     * Note: CDCS255 has unique courses different from CDCS230/CDCS253
     */
    public function run(): void
    {
        // Get a resource person to use as approver for seeded courses
        $resourcePerson = \App\Models\User::whereHas('resourcePerson')->first();
        if (!$resourcePerson) {
            echo "❌ Error: No resource person found in database. Please seed users first.\n";
            return;
        }

        // Find or create the CDCS255 internal CS110 equivalency list
        $list = EquivalencyList::firstOrCreate(
            [
                'program_code' => 'CDCS255',
                'category' => EquivalencyList::CATEGORY_INTERNAL,
                'source_institution' => null,
            ],
            [
                'program_name' => 'Sarjana Muda Sains Komputer (Kepujian) Rangkaian Komputer',
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
                      ->orWhere('diploma_course_code', 'LIKE', 'ITS%')
                      ->orWhere('diploma_course_code', 'LIKE', 'ISP%')
                      ->orWhere('diploma_course_code', 'LIKE', 'MGT%')
                      ->orWhere('diploma_course_code', 'LIKE', 'HXXX%');
            })
            ->delete();

        echo "Adding 19 CS110 → CDCS255 course equivalencies (including 6 ISP451 combinations)...\n";

        // All Courses
        $courses = [
            // 1. CSC118/CSC126 → CSC402 (UNIQUE - has CSC118 alternative)
            [
                'diploma_course_code' => 'CSC118/CSC126',
                'diploma_course_name' => 'Fundamentals of Computer Problem Solving / Programming Technique I',
                'diploma_credit_hour' => 3,
                'degree_course_code' => 'CSC402',
                'degree_course_name' => 'Fundamentals of Computer Problem Solving',
                'degree_credit_hour' => 3,
                'match_percentage' => 88,
                'is_eligible' => true,
                'notes' => 'Alternative codes: CSC118 OR CSC126 (UNIQUE TO CDCS255)',
            ],

            // 2. CSC253 → CSC413
            [
                'diploma_course_code' => 'CSC253',
                'diploma_course_name' => 'Object Oriented Programming',
                'diploma_credit_hour' => 3,
                'degree_course_code' => 'CSC413',
                'degree_course_name' => 'Data Structures',
                'degree_credit_hour' => 3,
                'match_percentage' => 86,
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
                'match_percentage' => 88,
                'is_eligible' => true,
            ],

            // 4. CSC138/CSC126 + CSC186 → CSC404 (Combination course)
            [
                'diploma_course_code' => 'CSC138/CSC126 + CSC186',
                'diploma_course_name' => 'Structured Programming / Programming Technique I + Object Oriented Programming',
                'diploma_credit_hour' => 6,
                'degree_course_code' => 'CSC404',
                'degree_course_name' => 'Programming II',
                'degree_credit_hour' => 3,
                'match_percentage' => 87,
                'is_eligible' => true,
                'notes' => 'Combination course: (CSC138 OR CSC126) + CSC186 required',
            ],

            // 5. CSC238 → CSC435 (UNIQUE - uses CSC238 instead of CSC186)
            [
                'diploma_course_code' => 'CSC238',
                'diploma_course_name' => 'Object Oriented Programming',
                'diploma_credit_hour' => 3,
                'degree_course_code' => 'CSC435',
                'degree_course_name' => 'Object Oriented Programming',
                'degree_credit_hour' => 3,
                'match_percentage' => 88,
                'is_eligible' => true,
                'notes' => 'UNIQUE TO CDCS255 - uses CSC238 instead of CSC186',
            ],

            // 6-11. ISP451 Combinations (6 total - UNIQUE TO CDCS255)
            // Combination 1: ITS250 + ITS330 + ICT200
            [
                'diploma_course_code' => 'ITS250 + ITS330 + ICT200',
                'diploma_course_name' => 'Information Technology Systems I + Information Technology Systems II + Operating System Concepts',
                'diploma_credit_hour' => 9,
                'degree_course_code' => 'ISP451',
                'degree_course_name' => 'Information Systems Project Management',
                'degree_credit_hour' => 3,
                'match_percentage' => 90,
                'is_eligible' => true,
                'notes' => 'ISP451 Combination 1 of 6 (UNIQUE TO CDCS255)',
            ],

            // Combination 2: ITS250 + ITS332 + ICT200
            [
                'diploma_course_code' => 'ITS250 + ITS332 + ICT200',
                'diploma_course_name' => 'Information Technology Systems I + Information Technology Systems III + Operating System Concepts',
                'diploma_credit_hour' => 9,
                'degree_course_code' => 'ISP451',
                'degree_course_name' => 'Information Systems Project Management',
                'degree_credit_hour' => 3,
                'match_percentage' => 90,
                'is_eligible' => true,
                'notes' => 'ISP451 Combination 2 of 6 (UNIQUE TO CDCS255)',
            ],

            // Combination 3: ITS250 + ISP250 + ICT200
            [
                'diploma_course_code' => 'ITS250 + ISP250 + ICT200',
                'diploma_course_name' => 'Information Technology Systems I + Information Systems Programming + Operating System Concepts',
                'diploma_credit_hour' => 9,
                'degree_course_code' => 'ISP451',
                'degree_course_name' => 'Information Systems Project Management',
                'degree_credit_hour' => 3,
                'match_percentage' => 90,
                'is_eligible' => true,
                'notes' => 'ISP451 Combination 3 of 6 (UNIQUE TO CDCS255)',
            ],

            // Combination 4: ITS232 + ITS330 + ICT200
            [
                'diploma_course_code' => 'ITS232 + ITS330 + ICT200',
                'diploma_course_name' => 'Information Technology Systems + Information Technology Systems II + Operating System Concepts',
                'diploma_credit_hour' => 9,
                'degree_course_code' => 'ISP451',
                'degree_course_name' => 'Information Systems Project Management',
                'degree_credit_hour' => 3,
                'match_percentage' => 90,
                'is_eligible' => true,
                'notes' => 'ISP451 Combination 4 of 6 (UNIQUE TO CDCS255)',
            ],

            // Combination 5: ITS232 + ITS332 + ICT200
            [
                'diploma_course_code' => 'ITS232 + ITS332 + ICT200',
                'diploma_course_name' => 'Information Technology Systems + Information Technology Systems III + Operating System Concepts',
                'diploma_credit_hour' => 9,
                'degree_course_code' => 'ISP451',
                'degree_course_name' => 'Information Systems Project Management',
                'degree_credit_hour' => 3,
                'match_percentage' => 90,
                'is_eligible' => true,
                'notes' => 'ISP451 Combination 5 of 6 (UNIQUE TO CDCS255)',
            ],

            // Combination 6: ITS232 + ISP250 + ICT200
            [
                'diploma_course_code' => 'ITS232 + ISP250 + ICT200',
                'diploma_course_name' => 'Information Technology Systems + Information Systems Programming + Operating System Concepts',
                'diploma_credit_hour' => 9,
                'degree_course_code' => 'ISP451',
                'degree_course_name' => 'Information Systems Project Management',
                'degree_credit_hour' => 3,
                'match_percentage' => 90,
                'is_eligible' => true,
                'notes' => 'ISP451 Combination 6 of 6 (UNIQUE TO CDCS255)',
            ],

            // 12. STA116 → STA416
            [
                'diploma_course_code' => 'STA116',
                'diploma_course_name' => 'Introduction to Statistics',
                'diploma_credit_hour' => 3,
                'degree_course_code' => 'STA416',
                'degree_course_name' => 'Probability and Statistics',
                'degree_credit_hour' => 3,
                'match_percentage' => 92,
                'is_eligible' => true,
            ],

            // 13. ITT270 → ITT470 (UNIQUE TO CDCS255 - Digital Electronics)
            [
                'diploma_course_code' => 'ITT270',
                'diploma_course_name' => 'Digital Electronics',
                'diploma_credit_hour' => 3,
                'degree_course_code' => 'ITT470',
                'degree_course_name' => 'Digital Electronics',
                'degree_credit_hour' => 3,
                'match_percentage' => 88,
                'is_eligible' => true,
                'notes' => 'UNIQUE TO CDCS255 - Digital Electronics mapping',
            ],

            // 14. MGT162/MGT160 → MGT420 (UNIQUE TO CDCS255 - uses MGT420)
            [
                'diploma_course_code' => 'MGT162/MGT160',
                'diploma_course_name' => 'Fundamentals of Management',
                'diploma_credit_hour' => 3,
                'degree_course_code' => 'MGT420',
                'degree_course_name' => 'Principles of Management',
                'degree_credit_hour' => 3,
                'match_percentage' => 87,
                'is_eligible' => true,
                'notes' => 'Alternative codes: MGT162 OR MGT160 (UNIQUE TO CDCS255 - uses MGT420)',
            ],

            // 15. MAT133 → MAT406
            [
                'diploma_course_code' => 'MAT133',
                'diploma_course_name' => 'Pre-Calculus',
                'diploma_credit_hour' => 3,
                'degree_course_code' => 'MAT406',
                'degree_course_name' => 'Pre-Calculus',
                'degree_credit_hour' => 3,
                'match_percentage' => 83,
                'is_eligible' => true,
            ],

            // Co-Curriculum Courses (3 courses - 3 credits)

            // 16. HXXX → HXXX (Ko Kurikulum I)
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

            // 17. HXXX → HXXX (Ko Kurikulum II)
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

            // 18. HXXX → HXXX (Ko Kurikulum III)
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
                'program_code' => 'CDCS255',
                'match_percentage' => $course['match_percentage'],
                'is_eligible' => $course['is_eligible'],
                'is_published' => false,
                'source' => 'seeded',
                'approved_by_user_id' => $resourcePerson->id,
                'notes' => $course['notes'] ?? 'Official CS110 → CDCS255 equivalency',
            ]);

            echo "✓ Added: {$course['diploma_course_code']} → {$course['degree_course_code']}\n";
        }

        // Update list statistics
        $list->updateStatistics();

        echo "\n✅ Successfully added 18 CS110 → CDCS255 course equivalencies!\n";
        echo "Total equivalencies in list: {$list->total_equivalencies}\n";
        echo "Eligible for exemption: {$list->eligible_count}\n";
    }
}
