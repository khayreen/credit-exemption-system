<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\ResourcePerson;
use App\Models\EquivalencyList;
use App\Mail\SemesterReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendSemesterReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:semester {--force : Force send reminders regardless of schedule}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send semester reminders to Resource Persons to prepare equivalency lists';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting semester reminder process...');

        // Determine upcoming semester
        $upcomingSemester = $this->getUpcomingSemester();
        $deadline = $this->getDeadline();

        $this->info("Upcoming Semester: {$upcomingSemester}");
        $this->info("Deadline: {$deadline}");

        // Get all Resource Persons
        $resourcePersons = ResourcePerson::with('user')->get();

        if ($resourcePersons->isEmpty()) {
            $this->warn('No Resource Persons found.');
            return 0;
        }

        $sentCount = 0;
        $errorCount = 0;

        foreach ($resourcePersons as $rp) {
            if (!$rp->user || !$rp->user->email) {
                $this->warn("Skipping Resource Person (no user/email): {$rp->id}");
                continue;
            }

            $assignedPrograms = $rp->assigned_programs ?? [];

            if (empty($assignedPrograms)) {
                $this->warn("Skipping {$rp->user->name} (no assigned programs)");
                continue;
            }

            // Check which programs are missing lists for upcoming semester
            $missingLists = [];

            foreach ($assignedPrograms as $programCode) {
                // Check for internal (CS110) list
                $hasInternal = EquivalencyList::where('program_code', $programCode)
                    ->where('semester', $upcomingSemester)
                    ->where('category', 'internal')
                    ->whereIn('status', ['draft', 'submitted', 'under_review', 'endorsed', 'published'])
                    ->exists();

                if (!$hasInternal) {
                    $missingLists[] = [
                        'program' => $programCode,
                        'category' => 'CS110 (Internal)',
                    ];
                }

                // Note: We don't check external lists as they vary by institution
            }

            // Only send reminder if there are missing lists
            if (!empty($missingLists)) {
                try {
                    Mail::to($rp->user->email)->send(new SemesterReminder(
                        $rp->user,
                        $upcomingSemester,
                        $deadline,
                        $assignedPrograms,
                        $missingLists
                    ));

                    $sentCount++;
                    $this->info("✓ Sent reminder to: {$rp->user->name} ({$rp->user->email})");
                } catch (\Exception $e) {
                    $errorCount++;
                    $this->error("✗ Failed to send to {$rp->user->email}: {$e->getMessage()}");
                }
            } else {
                $this->info("⊘ Skipped {$rp->user->name} (all lists ready for {$upcomingSemester})");
            }
        }

        $this->info('');
        $this->info('========================================');
        $this->info("Semester Reminder Summary:");
        $this->info("Sent: {$sentCount}");
        $this->info("Errors: {$errorCount}");
        $this->info("Skipped: " . ($resourcePersons->count() - $sentCount - $errorCount));
        $this->info('========================================');

        return 0;
    }

    /**
     * Determine the upcoming semester based on current date
     */
    private function getUpcomingSemester(): string
    {
        $now = Carbon::now();
        $month = $now->month;
        $year = $now->year;

        // UiTM Academic Calendar:
        // Semester 1: August - December
        // Semester 2: January - July

        if ($month >= 6 && $month < 8) {
            // June-July: Prepare for Semester 1 (Aug start)
            return "{$year}/" . ($year + 1) . "-1";
        } elseif ($month >= 11 || $month < 1) {
            // November-December: Prepare for Semester 2 (Jan start)
            $nextYear = $month >= 11 ? $year + 1 : $year;
            return ($year) . "/{$nextYear}-2";
        }

        // Default fallback
        return "{$year}/" . ($year + 1) . "-1";
    }

    /**
     * Get the deadline for list submission
     */
    private function getDeadline(): string
    {
        $now = Carbon::now();
        $month = $now->month;
        $year = $now->year;

        // Deadline is typically 2 weeks before semester starts
        if ($month >= 6 && $month < 8) {
            // Semester 1 starts in August, deadline mid-July
            return "15 July {$year}";
        } elseif ($month >= 11 || $month < 1) {
            // Semester 2 starts in January, deadline mid-December
            $deadlineYear = $month >= 11 ? $year : $year - 1;
            return "15 December {$deadlineYear}";
        }

        return "To be announced";
    }
}
