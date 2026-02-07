<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Change notifications table id from auto-increment to UUID for Laravel notification compatibility
     */
    public function up(): void
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            return; // SQLite notifications table already uses varchar id
        }
        DB::statement('ALTER TABLE notifications MODIFY id CHAR(36) NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            return;
        }
        DB::statement('ALTER TABLE notifications MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
    }
};
