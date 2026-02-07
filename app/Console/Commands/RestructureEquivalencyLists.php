<?php

namespace App\Console\Commands;

use App\Models\EquivalencyList;
use App\Models\CourseEquivalency;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RestructureEquivalencyLists extends Command
{
    protected $signature = 'equivalency:restructure {--dry-run : Show what would be done without making changes}';
    protected $description = 'Restructure equivalency lists: keep only CS110 courses in internal lists, move external courses to external lists';

    // Patterns that identify CS110 (UiTM internal) courses
    private $cs110Patterns = ['UiTM', 'CS110', 'Universiti Teknologi MARA'];

    public function handle()
    {
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->info("=== DRY RUN MODE - No changes will be made ===\n");
        }

        // Get all internal lists
        $internalLists = EquivalencyList::where('category', 'internal')->get();

        $this->info("Found " . $internalLists->count() . " internal lists to process.\n");

        foreach ($internalLists as $internalList) {
            $this->processInternalList($internalList, $dryRun);
        }

        $this->info("\n=== RESTRUCTURING COMPLETE ===");
    }

    private function processInternalList(EquivalencyList $internalList, bool $dryRun)
    {
        $this->info("Processing: {$internalList->program_code} (Internal List)");
        $this->info("  List ID: {$internalList->id}");

        $courses = $internalList->courseEquivalencies()->get();
        $this->info("  Total courses: " . $courses->count());

        // Separate CS110 courses from external courses
        $cs110Courses = $courses->filter(fn($c) => $this->isCS110($c->diploma_institution));
        $externalCourses = $courses->reject(fn($c) => $this->isCS110($c->diploma_institution));

        $this->info("  CS110 courses (to keep): " . $cs110Courses->count());
        $this->info("  External courses (to move): " . $externalCourses->count());

        if ($externalCourses->isEmpty()) {
            $this->info("  ✓ No external courses to move. List is already clean.\n");
            return;
        }

        // Group external courses by institution for moving
        $groupedExternal = $externalCourses->groupBy(function($c) {
            return $this->normalizeInstitution($c->diploma_institution);
        });

        foreach ($groupedExternal as $institutionKey => $institutionCourses) {
            $this->moveCoursesToExternalList(
                $internalList,
                $institutionKey,
                $institutionCourses,
                $dryRun
            );
        }

        // Update internal list statistics
        if (!$dryRun) {
            $internalList->refresh();
            $internalList->updateStatistics();
            $this->info("  ✓ Updated internal list statistics: " . $internalList->total_equivalencies . " courses remaining");
        }

        $this->info("");
    }

    private function isCS110(?string $institution): bool
    {
        if (empty($institution)) {
            return false;
        }

        foreach ($this->cs110Patterns as $pattern) {
            if (stripos($institution, $pattern) !== false) {
                return true;
            }
        }

        return false;
    }

    private function normalizeInstitution(?string $institution): string
    {
        if (empty($institution)) {
            return 'Unknown';
        }

        // Normalize various Politeknik spellings to a single key
        $normalized = $institution;

        // Check if it's a Politeknik
        if (stripos($institution, 'politeknik') !== false || stripos($institution, 'politaknik') !== false || stripos($institution, 'politeknk') !== false) {
            // Extract specific polytechnic name if present
            $specificPolytechnics = [
                'Sultan Azlan Shah' => 'Politeknik Sultan Azlan Shah',
                'Sultan Mizan Zainal Abidin' => 'Politeknik Sultan Mizan Zainal Abidin',
                'Sultan Idris Shah' => 'Politeknik Sultan Idris Shah',
                'Seberang Perai' => 'Politeknik Seberang Perai',
                'Sebarang Prai' => 'Politeknik Seberang Perai', // Typo fix
                'Ungku Omer' => 'Politeknik Ungku Omar',
                'Ungku Umar' => 'Politeknik Ungku Omar', // Typo fix
                'UNGKU OMER' => 'Politeknik Ungku Omar',
                'Muadzam Shah' => 'Politeknik Muadzam Shah',
                'Balik Pulau' => 'Politeknik Balik Pulau',
                'MetroKL' => 'Politeknik Metro KL',
                'Tuanku Syed Sirajuddin' => 'Politeknik Tuanku Syed Sirajuddin',
                'Mersing' => 'Politeknik Mersing',
            ];

            foreach ($specificPolytechnics as $pattern => $normalizedName) {
                if (stripos($institution, $pattern) !== false) {
                    return $normalizedName;
                }
            }

            // Generic Politeknik
            return 'Politeknik';
        }

        return $normalized;
    }

    private function moveCoursesToExternalList(
        EquivalencyList $internalList,
        string $institutionKey,
        $courses,
        bool $dryRun
    ) {
        $programCode = $internalList->program_code;

        $this->info("    Moving " . $courses->count() . " courses to: {$institutionKey}");

        // Find or create external list for this institution
        $externalList = EquivalencyList::where('program_code', $programCode)
            ->where('category', 'external')
            ->where('source_institution', $institutionKey)
            ->first();

        if (!$externalList) {
            $this->info("      Creating new external list for {$institutionKey}...");

            if (!$dryRun) {
                $externalList = EquivalencyList::create([
                    'program_code' => $programCode,
                    'program_name' => $internalList->program_name,
                    'semester' => $internalList->semester,
                    'academic_year' => $internalList->academic_year ?? date('Y'),
                    'category' => 'external',
                    'source_institution' => $institutionKey,
                    'status' => 'published',
                    'created_by_user_id' => $internalList->created_by_user_id,
                    'published_at' => now(),
                    'published_by_user_id' => $internalList->published_by_user_id,
                    'endorsed_at' => $internalList->endorsed_at,
                    'endorsed_by_user_id' => $internalList->endorsed_by_user_id,
                    'is_active' => true,
                ]);
                $this->info("      ✓ Created external list: {$externalList->id}");
            } else {
                $this->info("      [DRY RUN] Would create external list");
            }
        } else {
            $this->info("      Found existing external list: {$externalList->id}");
        }

        // Move courses to external list
        foreach ($courses as $course) {
            if (!$dryRun) {
                // Check if course already exists in external list
                $existing = CourseEquivalency::where('equivalency_list_id', $externalList->id)
                    ->where('diploma_course_code', $course->diploma_course_code)
                    ->first();

                if ($existing) {
                    // Course already exists, just delete from internal
                    $course->delete();
                    $this->line("        - {$course->diploma_course_code}: Deleted (already in external)");
                } else {
                    // Move to external list
                    $course->update([
                        'equivalency_list_id' => $externalList->id,
                        'diploma_institution' => $institutionKey, // Normalize institution name
                    ]);
                    $this->line("        - {$course->diploma_course_code}: Moved");
                }
            } else {
                $this->line("        - {$course->diploma_course_code}: [DRY RUN] Would move");
            }
        }

        // Update external list statistics
        if (!$dryRun && $externalList) {
            $externalList->updateStatistics();
        }
    }
}
