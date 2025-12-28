<?php

namespace Database\Seeders;

use App\Models\CourseEquivalency;
use App\Models\EquivalencyList;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MigrateExistingEquivalenciesToListsSeeder extends Seeder
{
    /**
     * Migrate existing orphaned course equivalencies into proper EquivalencyLists
     * This fixes the issue where old data doesn't have equivalency_list_id
     */
    public function run(): void
    {
        $this->command->info('Starting migration of existing course equivalencies...');

        // Get a Program Coordinator to be the creator
        $programCoordinator = User::where('role', 'program_coordinator')->first();
        if (!$programCoordinator) {
            $this->command->error('No Program Coordinator found. Please create one first.');
            return;
        }

        // Get all orphaned course equivalencies (no equivalency_list_id)
        $orphanedEquivalencies = CourseEquivalency::whereNull('equivalency_list_id')->get();

        if ($orphanedEquivalencies->isEmpty()) {
            $this->command->info('No orphaned course equivalencies found.');
            return;
        }

        $this->command->info("Found {$orphanedEquivalencies->count()} orphaned course equivalencies.");

        // Group by program_code and diploma_institution
        $grouped = $orphanedEquivalencies->groupBy(function($eq) {
            return $eq->program_code . '|' . ($eq->diploma_institution ?? 'Unknown');
        });

        foreach ($grouped as $key => $equivalencies) {
            [$programCode, $institution] = explode('|', $key);

            // Determine category (internal vs external)
            $isInternal = stripos($institution, 'UiTM') !== false ||
                          stripos($institution, 'CS110') !== false ||
                          stripos($institution, 'Politeknik') !== false;

            $category = $isInternal ? 'internal' : 'external';
            $sourceInstitution = $category === 'internal' ? null : $institution;

            // Get program name
            $program = DB::table('programs')->where('code', $programCode)->first();
            $programName = $program ? $program->name : "Program {$programCode}";

            // Create or get existing EquivalencyList
            $list = EquivalencyList::firstOrCreate(
                [
                    'program_code' => $programCode,
                    'category' => $category,
                    'source_institution' => $sourceInstitution,
                    'semester' => 'Semester 1 2024/2025', // Default semester
                ],
                [
                    'program_name' => $programName,
                    'academic_year' => '2024/2025',
                    'status' => 'published', // Set as published immediately
                    'is_active' => true,
                    'created_by_user_id' => $programCoordinator->id,
                    'published_by_user_id' => $programCoordinator->id,
                    'published_at' => now(),
                ]
            );

            // Associate all equivalencies with this list
            $count = 0;
            foreach ($equivalencies as $equivalency) {
                $equivalency->equivalency_list_id = $list->id;

                // Set is_eligible based on match_percentage
                if ($equivalency->match_percentage === null) {
                    $equivalency->match_percentage = 85.0; // Default
                }
                $equivalency->is_eligible = $equivalency->match_percentage >= 80;

                $equivalency->save();
                $count++;
            }

            // Update list statistics
            $list->updateStatistics();

            $this->command->info("✓ Migrated {$count} equivalencies to list: {$programCode} - {$category} ({$institution})");
        }

        $this->command->info('Migration completed successfully!');

        // Display summary
        $this->command->table(
            ['Program', 'Category', 'Institution', 'Mappings', 'Status'],
            EquivalencyList::with('courseEquivalencies')
                ->get()
                ->map(fn($list) => [
                    $list->program_code,
                    $list->category,
                    $list->source_institution ?? 'CS110',
                    $list->courseEquivalencies->count(),
                    $list->status
                ])
        );
    }
}
