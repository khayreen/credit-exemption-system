<?php

namespace Database\Seeders;

use App\Models\ProgramGroupConfig;
use Illuminate\Database\Seeder;

class ProgramGroupConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates default program groups (A-E) for semesters 1-7 for all supported programs.
     */
    public function run(): void
    {
        $programs = array_keys(ProgramGroupConfig::getSupportedPrograms());
        $semesters = ProgramGroupConfig::getSemesters();
        $groupLetters = ProgramGroupConfig::getGroupLetters();
        $academicYear = '2025/2026';
        $intake = 'march'; // Default intake for seeding

        $count = 0;

        foreach ($programs as $programCode) {
            foreach ($semesters as $semester) {
                foreach ($groupLetters as $groupLetter) {
                    ProgramGroupConfig::updateOrCreate(
                        [
                            'program_code' => $programCode,
                            'semester' => $semester,
                            'group_letter' => $groupLetter,
                            'academic_year' => $academicYear,
                            'intake' => $intake,
                        ],
                        [
                            'group_code' => ProgramGroupConfig::generateGroupCode($programCode, $semester, $groupLetter),
                            'is_active' => true,
                        ]
                    );
                    $count++;
                }
            }
        }

        $this->command->info("Program group configurations seeded for {$intake} {$academicYear}. Total: {$count}");
    }
}
