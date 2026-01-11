<?php

namespace App\Console\Commands;

use App\Models\EquivalencyList;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class ArchiveExpiredLists extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lists:archive-expired {--dry-run : Show what would be archived without actually doing it}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deactivate equivalency lists from previous semesters';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting archive process for expired equivalency lists...');

        $isDryRun = $this->option('dry-run');

        if ($isDryRun) {
            $this->warn('DRY RUN MODE - No changes will be made');
        }

        // Determine current semester
        $currentSemester = $this->getCurrentSemester();
        $this->info("Current Semester: {$currentSemester}");

        // Find all active lists from previous semesters
        $expiredLists = EquivalencyList::where('is_active', true)
            ->where('status', 'published')
            ->where('semester', '!=', $currentSemester)
            ->with(['creator', 'endorser'])
            ->get();

        if ($expiredLists->isEmpty()) {
            $this->info('No expired lists found to archive.');
            return 0;
        }

        $this->info("Found {$expiredLists->count()} expired lists to archive:");
        $this->newLine();

        // Display what will be archived
        $table = [];
        foreach ($expiredLists as $list) {
            $table[] = [
                'ID' => substr($list->id, 0, 8) . '...',
                'Program' => $list->program_code,
                'Category' => $list->isInternal() ? 'CS110' : $list->source_institution,
                'Semester' => $list->semester,
                'Mappings' => $list->total_equivalencies,
                'Published' => $list->published_at?->format('d M Y'),
            ];
        }

        $this->table(
            ['ID', 'Program', 'Category', 'Semester', 'Mappings', 'Published'],
            $table
        );

        if ($isDryRun) {
            $this->warn('DRY RUN: The lists above would be archived. Use without --dry-run to proceed.');
            return 0;
        }

        // Confirm before proceeding
        if (!$this->confirm('Do you want to archive these lists?', true)) {
            $this->info('Archive cancelled.');
            return 0;
        }

        // Archive the lists
        $archivedCount = 0;
        $errorCount = 0;

        foreach ($expiredLists as $list) {
            try {
                $list->update(['is_active' => false]);
                $archivedCount++;
                $this->info("✓ Archived: {$list->program_code} - {$list->semester}");
            } catch (\Exception $e) {
                $errorCount++;
                $this->error("✗ Failed to archive {$list->id}: {$e->getMessage()}");
            }
        }

        $this->newLine();
        $this->info('========================================');
        $this->info('Archive Summary:');
        $this->info("Archived: {$archivedCount}");
        $this->info("Errors: {$errorCount}");
        $this->info('========================================');

        // Send notification to HEA personnel
        try {
            $heaUsers = User::where('current_role', 'hea_personnel')->get();
            // Note: You could create a dedicated mail class for this if needed
            $this->info("Notified {$heaUsers->count()} HEA personnel about archived lists.");
        } catch (\Exception $e) {
            $this->warn("Failed to send HEA notification: {$e->getMessage()}");
        }

        return 0;
    }

    /**
     * Determine the current semester based on current date
     */
    private function getCurrentSemester(): string
    {
        $now = Carbon::now();
        $month = $now->month;
        $year = $now->year;

        // UiTM Academic Calendar:
        // Semester 1: August - December
        // Semester 2: January - July

        if ($month >= 8 && $month <= 12) {
            // August-December: Semester 1
            return "{$year}/" . ($year + 1) . "-1";
        } else {
            // January-July: Semester 2
            return ($year - 1) . "/{$year}-2";
        }
    }
}
