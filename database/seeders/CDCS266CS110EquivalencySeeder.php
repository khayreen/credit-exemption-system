<?php

namespace Database\Seeders;

use App\Models\CourseEquivalency;
use App\Models\EquivalencyList;
use Illuminate\Database\Seeder;

class CDCS266CS110EquivalencySeeder extends Seeder
{
    /**
     * Seed CS110 → CDCS266 course equivalencies (21 entries total including ISP451 combinations)
     * CDCS266: Sarjana Muda Sistem Maklumat (Kepujian) Kejuruteraan Sistem Maklumat
     * Based on: PENGECUALIAN KREDIT (PC) KURSUS BAGI PROGRAM CS266 (Kemaskini 11 Oktober 2022)
     */
    public function run(): void
    {
        // Get a resource person to use as approver for seeded courses
        $resourcePerson = \App\Models\User::whereHas('resourcePerson')->first();
        if (!$resourcePerson) {
            echo "❌ Error: No resource person found in database. Please seed users first.\n";
            return;
        }

        // Find or create the CDCS266 internal CS110 equivalency list
        $list = EquivalencyList::firstOrCreate(
            [
                'program_code' => 'CDCS266',
                'category' => EquivalencyList::CATEGORY_INTERNAL,
                'source_institution' => null,
            ],
            [
                'program_name' => 'Sarjana Muda Sistem Maklumat (Kepujian) Kejuruteraan Sistem Maklumat',
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
                      ->orWhere('diploma_course_code', 'LIKE', 'ACC%')
                      ->orWhere('diploma_course_code', 'LIKE', 'MGT%')
                      ->orWhere('diploma_course_code', 'LIKE', 'HXXX%');
            })
            ->delete();

        echo "Adding 21 CS110 → CDCS266 course equivalencies (including 6 ISP451 combinations)...\n";

        // All Courses (12 main courses + 6 ISP451 combinations + 3 co-curriculum = 21 entries)
        $courses = [
            // 1. CSC118/CSC126 → CSC402 (PROGRAMMING I)
            [
                'diploma_course_code' => 'CSC118/CSC126',
                'diploma_course_name' => 'Fundamental of Algorithm Development',
                'diploma_credit_hour' => 3,
                'degree_course_code' => 'CSC402',
                'degree_course_name' => 'Programming I',
                'degree_credit_hour' => 3,
                'match_percentage' => 92,
                'is_eligible' => true,
                'notes' => 'Alternative codes: CSC118 OR CSC126',
            ],

            // 2. CSC253 → CSC413 (INTRODUCTION TO INTERACTIVE MULTIMEDIA)
            [
                'diploma_course_code' => 'CSC253',
                'diploma_course_name' => 'Interactive Multimedia',
                'diploma_credit_hour' => 3,
                'degree_course_code' => 'CSC413',
                'degree_course_name' => 'Introduction to Interactive Multimedia',
                'degree_credit_hour' => 3,
                'match_percentage' => 93,
                'is_eligible' => true,
            ],

            // 3. CSC159 → CSC429 (COMPUTER ARCHITECTURE & ORGANIZATION)
            [
                'diploma_course_code' => 'CSC159',
                'diploma_course_name' => 'Computer Organization',
                'diploma_credit_hour' => 3,
                'degree_course_code' => 'CSC429',
                'degree_course_name' => 'Computer Architecture & Organization',
                'degree_credit_hour' => 3,
                'match_percentage' => 90,
                'is_eligible' => true,
            ],

            // 4. CSC138/CSC126 + CSC186 → CSC404 (PROGRAMMING II)
            [
                'diploma_course_code' => 'CSC138/CSC126 + CSC186',
                'diploma_course_name' => 'Structure Programming + Object Oriented Programming',
                'diploma_credit_hour' => 6,
                'degree_course_code' => 'CSC404',
                'degree_course_name' => 'Programming II',
                'degree_credit_hour' => 3,
                'match_percentage' => 88,
                'is_eligible' => true,
                'notes' => 'Combination course: (CSC138 OR CSC126) + CSC186 required',
            ],

            // 5. CSC238/CSC186 → CSC435 (OBJECT ORIENTED PROGRAMMING)
            [
                'diploma_course_code' => 'CSC238/CSC186',
                'diploma_course_name' => 'Object Oriented Programming',
                'diploma_credit_hour' => 3,
                'degree_course_code' => 'CSC435',
                'degree_course_name' => 'Object Oriented Programming',
                'degree_credit_hour' => 3,
                'match_percentage' => 88,
                'is_eligible' => true,
                'notes' => 'Alternative codes: CSC238 OR CSC186',
            ],

            // 6-11. ISP451 Combinations (6 total)
            // ISP451 - FUNDAMENTAL OF INFORMATION SYSTEMS DEVELOPMENT
            // Diploma: ITS250/232 + ITS330/332/ISP250 + ICT200

            // Combination 1: ITS250 + ITS330 + ICT200
            [
                'diploma_course_code' => 'ITS250 + ITS330 + ICT200',
                'diploma_course_name' => 'Intro to Database Management Systems + Information Systems Development + Operating System Concepts',
                'diploma_credit_hour' => 9,
                'degree_course_code' => 'ISP451',
                'degree_course_name' => 'Fundamental of Information Systems Development',
                'degree_credit_hour' => 3,
                'match_percentage' => 90,
                'is_eligible' => true,
                'notes' => 'ISP451 Combination 1 of 6',
            ],

            // Combination 2: ITS250 + ITS332 + ICT200
            [
                'diploma_course_code' => 'ITS250 + ITS332 + ICT200',
                'diploma_course_name' => 'Intro to Database Management Systems + Information Systems Development II + Operating System Concepts',
                'diploma_credit_hour' => 9,
                'degree_course_code' => 'ISP451',
                'degree_course_name' => 'Fundamental of Information Systems Development',
                'degree_credit_hour' => 3,
                'match_percentage' => 90,
                'is_eligible' => true,
                'notes' => 'ISP451 Combination 2 of 6',
            ],

            // Combination 3: ITS250 + ISP250 + ICT200
            [
                'diploma_course_code' => 'ITS250 + ISP250 + ICT200',
                'diploma_course_name' => 'Intro to Database Management Systems + Information Systems Programming + Operating System Concepts',
                'diploma_credit_hour' => 9,
                'degree_course_code' => 'ISP451',
                'degree_course_name' => 'Fundamental of Information Systems Development',
                'degree_credit_hour' => 3,
                'match_percentage' => 90,
                'is_eligible' => true,
                'notes' => 'ISP451 Combination 3 of 6',
            ],

            // Combination 4: ITS232 + ITS330 + ICT200
            [
                'diploma_course_code' => 'ITS232 + ITS330 + ICT200',
                'diploma_course_name' => 'Intro to Database Management Systems + Information Systems Development + Operating System Concepts',
                'diploma_credit_hour' => 9,
                'degree_course_code' => 'ISP451',
                'degree_course_name' => 'Fundamental of Information Systems Development',
                'degree_credit_hour' => 3,
                'match_percentage' => 90,
                'is_eligible' => true,
                'notes' => 'ISP451 Combination 4 of 6',
            ],

            // Combination 5: ITS232 + ITS332 + ICT200
            [
                'diploma_course_code' => 'ITS232 + ITS332 + ICT200',
                'diploma_course_name' => 'Intro to Database Management Systems + Information Systems Development II + Operating System Concepts',
                'diploma_credit_hour' => 9,
                'degree_course_code' => 'ISP451',
                'degree_course_name' => 'Fundamental of Information Systems Development',
                'degree_credit_hour' => 3,
                'match_percentage' => 90,
                'is_eligible' => true,
                'notes' => 'ISP451 Combination 5 of 6',
            ],

            // Combination 6: ITS232 + ISP250 + ICT200
            [
                'diploma_course_code' => 'ITS232 + ISP250 + ICT200',
                'diploma_course_name' => 'Intro to Database Management Systems + Information Systems Programming + Operating System Concepts',
                'diploma_credit_hour' => 9,
                'degree_course_code' => 'ISP451',
                'degree_course_name' => 'Fundamental of Information Systems Development',
                'degree_credit_hour' => 3,
                'match_percentage' => 90,
                'is_eligible' => true,
                'notes' => 'ISP451 Combination 6 of 6',
            ],

            // 12. STA116 → STA416 (APPLIED PROBABILITY & STATISTICS)
            [
                'diploma_course_code' => 'STA116',
                'diploma_course_name' => 'Introduction to Probability & Statistics',
                'diploma_credit_hour' => 3,
                'degree_course_code' => 'STA416',
                'degree_course_name' => 'Applied Probability & Statistics',
                'degree_credit_hour' => 3,
                'match_percentage' => 88,
                'is_eligible' => true,
            ],

            // 13. ITT300 → ITT400 (INTRODUCTION TO DATA COMMUNICATION & NETWORKING)
            [
                'diploma_course_code' => 'ITT300',
                'diploma_course_name' => 'Introduction to Data Communication & Networking',
                'diploma_credit_hour' => 3,
                'degree_course_code' => 'ITT400',
                'degree_course_name' => 'Introduction to Data Communication & Networking',
                'degree_credit_hour' => 3,
                'match_percentage' => 94,
                'is_eligible' => true,
            ],

            // 14. CSC318 + CSC264 → ISP465 (INTRO TO FRONT END WEB DEVELOPMENT)
            [
                'diploma_course_code' => 'CSC318 + CSC264',
                'diploma_course_name' => 'Web Application Development + Introduction to Web and Mobile Application',
                'diploma_credit_hour' => 6,
                'degree_course_code' => 'ISP465',
                'degree_course_name' => 'Intro to Front End Web Development',
                'degree_credit_hour' => 3,
                'match_percentage' => 88,
                'is_eligible' => true,
                'notes' => 'Combination course: CSC318 + CSC264 required',
            ],

            // 15. ACC117 → ACC407 (FUNDAMENTAL FINANCIAL ACCOUNTING AND REPORTING)
            [
                'diploma_course_code' => 'ACC117',
                'diploma_course_name' => 'Introduction to Financial Accounting',
                'diploma_credit_hour' => 3,
                'degree_course_code' => 'ACC407',
                'degree_course_name' => 'Fundamental Financial Accounting and Reporting',
                'degree_credit_hour' => 3,
                'match_percentage' => 85,
                'is_eligible' => true,
            ],

            // 16. MGT162/MGT160 → MGT400 (INTRODUCTION TO MANAGEMENT)
            [
                'diploma_course_code' => 'MGT162/MGT160',
                'diploma_course_name' => 'Fundamentals of Management',
                'diploma_credit_hour' => 3,
                'degree_course_code' => 'MGT400',
                'degree_course_name' => 'Introduction to Management',
                'degree_credit_hour' => 3,
                'match_percentage' => 87,
                'is_eligible' => true,
                'notes' => 'Alternative codes: MGT162 OR MGT160',
            ],

            // 17. MAT210 → MAT415 (DISCRETE MATHEMATICS)
            [
                'diploma_course_code' => 'MAT210',
                'diploma_course_name' => 'Discrete Mathematics',
                'diploma_credit_hour' => 3,
                'degree_course_code' => 'MAT415',
                'degree_course_name' => 'Discrete Mathematics',
                'degree_credit_hour' => 3,
                'match_percentage' => 90,
                'is_eligible' => true,
            ],

            // Co-Curriculum Courses (Kursus Universiti)

            // 18. HXXX → HXXX (Ko Kurikulum I)
            [
                'diploma_course_code' => 'HXXX',
                'diploma_course_name' => 'Ko-Kurikulum I',
                'diploma_credit_hour' => 1,
                'degree_course_code' => 'HXXX',
                'degree_course_name' => 'Ko-Kurikulum I',
                'degree_credit_hour' => 1,
                'match_percentage' => 100,
                'is_eligible' => true,
                'notes' => 'Co-Curriculum I (direct PC to student)',
            ],

            // 19. HXXX → HXXX (Ko Kurikulum II)
            [
                'diploma_course_code' => 'HXXX',
                'diploma_course_name' => 'Ko-Kurikulum II',
                'diploma_credit_hour' => 1,
                'degree_course_code' => 'HXXX',
                'degree_course_name' => 'Ko-Kurikulum II',
                'degree_credit_hour' => 1,
                'match_percentage' => 100,
                'is_eligible' => true,
                'notes' => 'Co-Curriculum II (direct PC to student)',
            ],

            // 20. HXXX → HXXX (Ko Kurikulum III)
            [
                'diploma_course_code' => 'HXXX',
                'diploma_course_name' => 'Ko-Kurikulum III',
                'diploma_credit_hour' => 1,
                'degree_course_code' => 'HXXX',
                'degree_course_name' => 'Ko-Kurikulum III',
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
                'program_code' => 'CDCS266',
                'match_percentage' => $course['match_percentage'],
                'is_eligible' => $course['is_eligible'],
                'is_published' => false,
                'source' => 'seeded',
                'approved_by_user_id' => $resourcePerson->id,
                'notes' => $course['notes'] ?? 'Official CS110 → CDCS266 equivalency (Kemaskini 11 Oktober 2022)',
            ]);

            echo "✓ Added: {$course['diploma_course_code']} → {$course['degree_course_code']}\n";
        }

        // Update list statistics
        $list->updateStatistics();

        echo "\n✅ Successfully added 20 CS110 → CDCS266 course equivalencies!\n";
        echo "Total equivalencies in list: {$list->total_equivalencies}\n";
        echo "Eligible for exemption: {$list->eligible_count}\n";
    }
}
