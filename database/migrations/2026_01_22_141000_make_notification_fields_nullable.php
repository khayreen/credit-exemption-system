<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Make custom notification fields nullable for Laravel notification compatibility
     */
    public function up(): void
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            return; // SQLite columns are inherently nullable
        }
        DB::statement('ALTER TABLE notifications MODIFY title VARCHAR(255) NULL');
        DB::statement('ALTER TABLE notifications MODIFY message TEXT NULL');
        DB::statement('ALTER TABLE notifications MODIFY is_read TINYINT(1) NULL DEFAULT 0');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            return;
        }
        DB::statement('ALTER TABLE notifications MODIFY title VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE notifications MODIFY message TEXT NOT NULL');
        DB::statement('ALTER TABLE notifications MODIFY is_read TINYINT(1) NOT NULL DEFAULT 0');
    }
};
