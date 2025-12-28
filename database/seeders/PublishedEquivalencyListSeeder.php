<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EquivalencyList;
use App\Models\CourseEquivalency;
use App\Models\User;
use Illuminate\Support\Str;

class PublishedEquivalencyListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates published equivalency lists for student access testing.
     */
    public function run(): void
    {
        $this->command->info('Creating published equivalency lists for student access...');

        // Get or create a resource person user
        $resourcePerson = User::where('role', 'resource_person')->first();
        if (!$resourcePerson) {
            $resourcePerson = User::create([
                'name' => 'Dr. Resource Person',
                'email' => 'resource.person@uitm.edu.my',
                'password' => bcrypt('password'),
                'role' => 'resource_person',
                'current_role' => 'resource_person',
                'email_verified_at' => now(),
            ]);
        }

        // Get or create an HEA personnel user
        $heaPersonnel = User::where('role', 'hea_personnel')->first();
        if (!$heaPersonnel) {
            $heaPersonnel = User::create([
                'name' => 'HEA Admin',
                'email' => 'hea.admin@uitm.edu.my',
                'password' => bcrypt('password'),
                'role' => 'hea_personnel',
                'current_role' => 'hea_personnel',
                'email_verified_at' => now(),
            ]);
        }

        $programs = [
            'CDCS230' => 'Bachelor of Computer Science (Hons.)',
            'CDCS251' => 'Bachelor of Computer Science (Hons.) Netcentric Computing',
            'CDCS253' => 'Bachelor of Computer Science (Hons.) Multimedia Computing',
            'CDCS255' => 'Bachelor of Computer Science (Hons.) Computer Networking',
            'CDCS266' => 'Bachelor of Information Systems (Hons.) Information Systems Engineering',
        ];

        $semester = '2024/2025-2';
        $academicYear = '2024/2025';

        // Course mappings for CS110 (Internal)
        $cs110Mappings = [
            ['CSC118', 'Introduction to Computers', 3, 'CSC650', 'Computer Architecture', 3, 85, true],
            ['CSC128', 'Fundamentals of Programming', 3, 'CSC584', 'Programming Principles', 3, 90, true],
            ['CSC138', 'Structured Programming', 4, 'CSC248', 'Discrete Structures', 3, 82, true],
            ['CSC159', 'Computer Organization', 3, 'CSC405', 'Computer Organization and Architecture', 3, 88, true],
            ['MAT183', 'Calculus I', 3, 'MAT421', 'Calculus', 3, 75, false],
            ['CTU101', 'Fundamentals of Islamic Studies', 2, 'CTU553', 'Islamic Studies', 2, 95, true],
            ['ELC121', 'English for Academic Writing', 3, 'ELC501', 'English for Professional Communication', 3, 80, true],
            ['CSC186', 'Object-Oriented Programming', 4, 'CSC404', 'Object-Oriented Programming', 3, 92, true],
            ['CSC264', 'Web Programming', 3, 'ITS460', 'Web Application Development', 3, 87, true],
            ['CSC253', 'Database Management', 3, 'CSC584', 'Database Systems', 3, 88, true],
            ['CSC226', 'Discrete Structures', 3, 'MAT416', 'Discrete Mathematics', 3, 78, false],
            ['MAT133', 'Linear Algebra', 3, 'MAT425', 'Linear Algebra', 3, 82, true],
        ];

        // External institution mappings (Politeknik)
        $politeknikMappings = [
            ['DFC1013', 'Programming Fundamentals', 3, 'CSC584', 'Programming Principles', 3, 80, true],
            ['DFC2083', 'Data Structures', 3, 'CSC248', 'Discrete Structures', 3, 75, false],
            ['DFC1043', 'Computer Architecture', 3, 'CSC405', 'Computer Organization', 3, 82, true],
            ['DFC2093', 'Database Design', 3, 'CSC584', 'Database Systems', 3, 78, false],
            ['DFC3103', 'Web Development', 3, 'ITS460', 'Web Application Development', 3, 85, true],
        ];

        // External institution mappings (UTM)
        $utmMappings = [
            ['SCSC1013', 'Programming I', 3, 'CSC584', 'Programming Principles', 3, 83, true],
            ['SCSC1023', 'Programming II', 3, 'CSC404', 'Object-Oriented Programming', 3, 80, true],
            ['SCSC2013', 'Data Structures', 3, 'CSC248', 'Discrete Structures', 3, 77, false],
            ['SCSC2023', 'Database Management', 3, 'CSC584', 'Database Systems', 3, 85, true],
        ];

        foreach ($programs as $programCode => $programName) {
            // Create Internal (CS110) List
            $internalList = EquivalencyList::updateOrCreate(
                [
                    'program_code' => $programCode,
                    'semester' => $semester,
                    'category' => 'internal',
                    'source_institution' => null,
                ],
                [
                    'program_name' => $programName,
                    'academic_year' => $academicYear,
                    'status' => 'published',
                    'created_by_user_id' => $resourcePerson->id,
                    'submitted_at' => now()->subDays(5),
                    'submission_notes' => 'CS110 course equivalencies for ' . $programCode,
                    'reviewed_by_user_id' => $heaPersonnel->id,
                    'reviewed_at' => now()->subDays(3),
                    'endorsed_by_user_id' => $heaPersonnel->id,
                    'endorsed_at' => now()->subDays(2),
                    'endorsement_notes' => 'Approved after thorough review',
                    'published_by_user_id' => $heaPersonnel->id,
                    'published_at' => now()->subDays(2),
                    'is_active' => true,
                    'total_equivalencies' => count($cs110Mappings),
                    'eligible_count' => count(array_filter($cs110Mappings, fn($m) => $m[7])),
                    'not_eligible_count' => count(array_filter($cs110Mappings, fn($m) => !$m[7])),
                ]
            );

            // Add course equivalencies for internal list
            foreach ($cs110Mappings as $mapping) {
                CourseEquivalency::updateOrCreate(
                    [
                        'equivalency_list_id' => $internalList->id,
                        'diploma_course_code' => $mapping[0],
                        'degree_course_code' => $mapping[3],
                    ],
                    [
                        'diploma_course_name' => $mapping[1],
                        'diploma_credit_hour' => $mapping[2],
                        'degree_course_name' => $mapping[4],
                        'degree_credit_hour' => $mapping[5],
                        'match_percentage' => $mapping[6],
                        'is_eligible' => $mapping[7],
                        'program_code' => $programCode,
                        'is_published' => true,
                        'source' => 'manual',
                        'diploma_institution' => 'UiTM',
                        'approved_by_user_id' => $heaPersonnel->id,
                    ]
                );
            }

            $this->command->info("Created CS110 -> {$programCode} list with " . count($cs110Mappings) . " mappings");

            // Create External (Politeknik) List for first 2 programs
            if (in_array($programCode, ['CDCS251', 'CDCS230'])) {
                $politeknikList = EquivalencyList::updateOrCreate(
                    [
                        'program_code' => $programCode,
                        'semester' => $semester,
                        'category' => 'external',
                        'source_institution' => 'Politeknik',
                    ],
                    [
                        'program_name' => $programName,
                        'academic_year' => $academicYear,
                        'status' => 'published',
                        'created_by_user_id' => $resourcePerson->id,
                        'submitted_at' => now()->subDays(4),
                        'submission_notes' => 'Politeknik course equivalencies for ' . $programCode,
                        'reviewed_by_user_id' => $heaPersonnel->id,
                        'reviewed_at' => now()->subDays(2),
                        'endorsed_by_user_id' => $heaPersonnel->id,
                        'endorsed_at' => now()->subDay(),
                        'endorsement_notes' => 'Approved for external credit transfer',
                        'published_by_user_id' => $heaPersonnel->id,
                        'published_at' => now()->subDay(),
                        'is_active' => true,
                        'total_equivalencies' => count($politeknikMappings),
                        'eligible_count' => count(array_filter($politeknikMappings, fn($m) => $m[7])),
                        'not_eligible_count' => count(array_filter($politeknikMappings, fn($m) => !$m[7])),
                    ]
                );

                foreach ($politeknikMappings as $mapping) {
                    CourseEquivalency::updateOrCreate(
                        [
                            'equivalency_list_id' => $politeknikList->id,
                            'diploma_course_code' => $mapping[0],
                            'degree_course_code' => $mapping[3],
                        ],
                        [
                            'diploma_course_name' => $mapping[1],
                            'diploma_credit_hour' => $mapping[2],
                            'degree_course_name' => $mapping[4],
                            'degree_credit_hour' => $mapping[5],
                            'match_percentage' => $mapping[6],
                            'is_eligible' => $mapping[7],
                            'program_code' => $programCode,
                            'is_published' => true,
                            'source' => 'manual',
                            'diploma_institution' => 'Politeknik',
                            'approved_by_user_id' => $heaPersonnel->id,
                        ]
                    );
                }

                $this->command->info("Created Politeknik -> {$programCode} list with " . count($politeknikMappings) . " mappings");
            }

            // Create External (UTM) List for CDCS251 only
            if ($programCode === 'CDCS251') {
                $utmList = EquivalencyList::updateOrCreate(
                    [
                        'program_code' => $programCode,
                        'semester' => $semester,
                        'category' => 'external',
                        'source_institution' => 'UTM',
                    ],
                    [
                        'program_name' => $programName,
                        'academic_year' => $academicYear,
                        'status' => 'published',
                        'created_by_user_id' => $resourcePerson->id,
                        'submitted_at' => now()->subDays(3),
                        'submission_notes' => 'UTM course equivalencies for ' . $programCode,
                        'reviewed_by_user_id' => $heaPersonnel->id,
                        'reviewed_at' => now()->subDay(),
                        'endorsed_by_user_id' => $heaPersonnel->id,
                        'endorsed_at' => now(),
                        'endorsement_notes' => 'Approved for UTM credit transfer',
                        'published_by_user_id' => $heaPersonnel->id,
                        'published_at' => now(),
                        'is_active' => true,
                        'total_equivalencies' => count($utmMappings),
                        'eligible_count' => count(array_filter($utmMappings, fn($m) => $m[7])),
                        'not_eligible_count' => count(array_filter($utmMappings, fn($m) => !$m[7])),
                    ]
                );

                foreach ($utmMappings as $mapping) {
                    CourseEquivalency::updateOrCreate(
                        [
                            'equivalency_list_id' => $utmList->id,
                            'diploma_course_code' => $mapping[0],
                            'degree_course_code' => $mapping[3],
                        ],
                        [
                            'diploma_course_name' => $mapping[1],
                            'diploma_credit_hour' => $mapping[2],
                            'degree_course_name' => $mapping[4],
                            'degree_credit_hour' => $mapping[5],
                            'match_percentage' => $mapping[6],
                            'is_eligible' => $mapping[7],
                            'program_code' => $programCode,
                            'is_published' => true,
                            'source' => 'manual',
                            'diploma_institution' => 'UTM',
                            'approved_by_user_id' => $heaPersonnel->id,
                        ]
                    );
                }

                $this->command->info("Created UTM -> {$programCode} list with " . count($utmMappings) . " mappings");
            }
        }

        $this->command->info('');
        $this->command->info('Published Equivalency Lists created successfully!');
        $this->command->info('Total Lists: ' . EquivalencyList::published()->count());
        $this->command->info('Active Lists: ' . EquivalencyList::active()->count());
    }
}
