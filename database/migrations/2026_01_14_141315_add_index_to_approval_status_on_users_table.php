<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add index on approval_status for faster queries on pending/approved users
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->index('approval_status', 'users_approval_status_index');
            $table->index('requested_role', 'users_requested_role_index');
            $table->index(['approval_status', 'requested_role'], 'users_approval_role_composite_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_approval_status_index');
            $table->dropIndex('users_requested_role_index');
            $table->dropIndex('users_approval_role_composite_index');
        });
    }
};
