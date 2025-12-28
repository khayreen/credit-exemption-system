<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\AcademicAdvisor;
use Illuminate\Support\Facades\DB;

class CleanupOldAdvisorsSeeder extends Seeder
{
    public function run()
    {
        echo "\n🧹 Cleaning up academic advisor accounts for removed programmes...\n";
        echo "==================================================================\n";
        echo "Removing advisors for: CS240, CS259, CS264, CS270\n";
        echo "==================================================================\n\n";

        DB::beginTransaction();

        try {
            // Define emails of advisors to remove (from removed programmes)
            $emailsToRemove = [
                // CS240
                'zainal.fikri@gmail.com',
                // CS259
                'raihah@gmail.com',
                // CS264
                'norzatul.bazamah@gmail.com',
                // CS270
                'nor.masri@gmail.com',
            ];

            $count = 0;

            foreach ($emailsToRemove as $email) {
                $user = User::where('email', $email)
                           ->where('role', 'academic_advisor')
                           ->first();

                if ($user) {
                    echo "🗑️  Deleting: {$user->name} ({$user->email})\n";

                    // Delete associated academic advisor record first
                    AcademicAdvisor::where('user_id', $user->id)->delete();

                    // Delete the user
                    $user->delete();
                    $count++;
                } else {
                    echo "⚠️  Not found: {$email}\n";
                }
            }

            DB::commit();

            echo "\n==================================================================\n";
            echo "✅ Successfully deleted {$count} academic advisor accounts!\n";
            echo "==================================================================\n\n";

        } catch (\Exception $e) {
            DB::rollBack();
            echo "\n❌ Error: " . $e->getMessage() . "\n\n";
        }
    }
}
