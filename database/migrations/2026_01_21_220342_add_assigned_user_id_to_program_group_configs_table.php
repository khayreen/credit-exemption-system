<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('program_group_configs', function (Blueprint $table) {
            // Add assigned Academic Advisor column
            $table->uuid('assigned_user_id')->nullable()->after('is_active');

            // Add foreign key constraint
            $table->foreign('assigned_user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');

            // Add index for faster lookups
            $table->index('assigned_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('program_group_configs', function (Blueprint $table) {
            $table->dropForeign(['assigned_user_id']);
            $table->dropIndex(['assigned_user_id']);
            $table->dropColumn('assigned_user_id');
        });
    }
};
