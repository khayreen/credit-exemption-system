<?php

namespace App\Console\Commands;

use App\Models\Faculty;
use Illuminate\Console\Command;

class CleanFacultyData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean:faculty-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove old English faculty data and keep only scraped Bahasa Melayu faculties';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Cleaning faculty data...');
        
        // Get all faculties
        $allFaculties = Faculty::all();
        $this->info("Total faculties before cleaning: {$allFaculties->count()}");
        
        $removedCount = 0;
        $keptCount = 0;
        
        foreach ($allFaculties as $faculty) {
            // Keep faculties that start with FAKULTI, AKADEMI, or KOLEJ (Bahasa Melayu)
            if (str_starts_with($faculty->name, 'FAKULTI ') || 
                str_starts_with($faculty->name, 'AKADEMI ') || 
                str_starts_with($faculty->name, 'KOLEJ ')) {
                
                $this->line("KEEPING: {$faculty->name}");
                $keptCount++;
            } else {
                // Remove English faculty names
                $this->line("REMOVING: {$faculty->name}");
                $faculty->delete();
                $removedCount++;
            }
        }
        
        $this->info("Cleaning completed!");
        $this->info("Kept: {$keptCount} faculties");
        $this->info("Removed: {$removedCount} faculties");
        $this->info("Final count: " . Faculty::count());
        
        return 0;
    }
}
