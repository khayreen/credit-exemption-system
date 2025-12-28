<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\HeaPersonnel;
use App\Mail\UserApprovedMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ApproveHeaUser extends Command
{
    protected $signature = 'hea:approve {email : The email of the pending HEA user}';

    protected $description = 'Approve a pending HEA personnel registration';

    public function handle()
    {
        $email = $this->argument('email');

        $user = User::where('email', $email)
                    ->where('approval_status', 'pending_admin')
                    ->where('requested_role', 'hea')
                    ->first();

        if (!$user) {
            $this->error("❌ No pending HEA registration found for: {$email}");
            return 1;
        }

        $this->info("Found pending HEA registration:");
        $this->line("Name:  {$user->name}");
        $this->line("Email: {$user->email}");
        $this->line("");

        if (!$this->confirm('Approve this HEA registration?', true)) {
            $this->info('Cancelled.');
            return 0;
        }

        try {
            DB::transaction(function() use ($user) {
                $user->update([
                    'role' => 'hea', // Legacy field for backward compatibility
                    'current_role' => 'hea',
                    'approval_status' => 'approved',
                    'approved_at' => now(),
                ]);

                HeaPersonnel::create([
                    'id' => Str::uuid(),
                    'user_id' => $user->id,
                ]);

                // Send email verification notification
                $user->sendEmailVerificationNotification();
            });

            $this->info("✅ HEA user approved successfully!");
            $this->info("📧 Approval email sent to {$user->email}");

            return 0;

        } catch (\Exception $e) {
            $this->error("❌ Failed to approve: " . $e->getMessage());
            return 1;
        }
    }
}
