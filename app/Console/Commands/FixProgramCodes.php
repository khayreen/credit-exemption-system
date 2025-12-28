<?php

namespace App\Console\Commands;

use App\Models\CourseEquivalencyRequest;
use App\Models\ProgramCoordinator;
use Illuminate\Console\Command;

class FixProgramCodes extends Command
{
    protected $signature = 'fix:program-codes';
    protected $description = 'Add CDCS prefix to program codes and assign coordinators';

    public function handle()
    {
        $coordinators = ProgramCoordinator::all();
        $this->info('Found ' . $coordinators->count() . ' coordinators');

        $requests = CourseEquivalencyRequest::all();
        $this->info('Found ' . $requests->count() . ' requests to update');

        $updated = 0;
        foreach ($requests as $req) {
            $originalCode = $req->current_program_code;
            $newCode = $originalCode;

            // Add CDCS prefix if missing
            if (!str_starts_with($originalCode, 'CDCS')) {
                // If it starts with CS, replace CS with CDCS
                if (str_starts_with($originalCode, 'CS')) {
                    $newCode = 'CDCS' . substr($originalCode, 2);
                } else {
                    $newCode = 'CDCS' . $originalCode;
                }
            }

            // Find coordinator for this program
            $coordinator = null;
            foreach ($coordinators as $c) {
                if (in_array($newCode, $c->program_codes)) {
                    $coordinator = $c;
                    break;
                }
            }

            // Update request
            $req->current_program_code = $newCode;
            if ($coordinator) {
                $req->coordinator_id = $coordinator->id;
            }
            $req->save();

            $this->line('Updated: ' . $originalCode . ' → ' . $newCode . ' | Coordinator: ' . ($coordinator ? $coordinator->name : 'NONE'));
            $updated++;
        }

        $this->info("\nTotal updated: " . $updated);
        return 0;
    }
}
