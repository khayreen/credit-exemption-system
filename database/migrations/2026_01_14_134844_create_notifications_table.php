<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Alter existing notifications table to support Laravel's notification system
     */
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Add Laravel notification columns if they don't exist
            if (!Schema::hasColumn('notifications', 'notifiable_type')) {
                $table->string('notifiable_type')->after('user_id')->nullable();
            }
            if (!Schema::hasColumn('notifications', 'notifiable_id')) {
                $table->char('notifiable_id', 36)->after('notifiable_type')->nullable();
            }
            if (!Schema::hasColumn('notifications', 'data')) {
                $table->text('data')->nullable()->after('link');
            }
        });

        // Update existing records to set notifiable columns from user_id
        DB::table('notifications')
            ->whereNull('notifiable_type')
            ->update([
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id' => DB::raw('user_id'),
            ]);

        // Add index for Laravel notifications
        Schema::table('notifications', function (Blueprint $table) {
            $table->index(['notifiable_id', 'notifiable_type'], 'notifications_notifiable_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex('notifications_notifiable_index');
            $table->dropColumn(['notifiable_type', 'notifiable_id', 'data']);
        });
    }
};
