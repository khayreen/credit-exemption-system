<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Make user_id nullable for Laravel notification compatibility
     */
    public function up(): void
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            return; // SQLite columns are inherently nullable
        }

        // Drop the foreign key constraint first, then make nullable
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        // Make user_id nullable
        DB::statement('ALTER TABLE notifications MODIFY user_id CHAR(36) NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            return;
        }

        DB::statement('ALTER TABLE notifications MODIFY user_id CHAR(36) NOT NULL');

        Schema::table('notifications', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
