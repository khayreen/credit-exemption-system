<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class FixCurrentRole extends Command
{
    protected $signature = 'fix:current-role';
    protected $description = 'Fix current_role field for all users based on their role field';

    public function handle()
    {
        $users = User::whereNull('current_role')
                    ->orWhere('current_role', '')
                    ->get();

        foreach ($users as $user) {
            $user->current_role = $user->role;
            $user->save();
            $this->info("Fixed current_role for: {$user->email} -> {$user->role}");
        }

        $this->info("\nTotal users fixed: " . $users->count());

        return Command::SUCCESS;
    }
}
