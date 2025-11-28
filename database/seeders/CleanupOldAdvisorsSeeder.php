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
        echo "\n🧹 Cleaning up old academic advisor accounts with @test.com emails...\n";
        echo "==================================================================\n\n";

        DB::beginTransaction();

        try {
            // Find all users with @test.com emails who are academic advisors
            $oldUsers = User::where('role', 'academic_advisor')
                           ->where('email', 'LIKE', '%@test.com')
                           ->get();

            $count = $oldUsers->count();

            if ($count === 0) {
                echo "✓ No old accounts found. Database is clean!\n\n";
                DB::commit();
                return;
            }

            foreach ($oldUsers as $user) {
                echo "🗑️  Deleting: {$user->name} ({$user->email})\n";

                // Delete associated academic advisor record first
                AcademicAdvisor::where('user_id', $user->id)->delete();

                // Delete the user
                $user->delete();
            }

            DB::commit();

            echo "\n==================================================================\n";
            echo "✅ Successfully deleted {$count} old academic advisor accounts!\n";
            echo "==================================================================\n\n";

        } catch (\Exception $e) {
            DB::rollBack();
            echo "\n❌ Error: " . $e->getMessage() . "\n\n";
        }
    }
}
