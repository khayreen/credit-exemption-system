<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Restores the complete HEA endorsement workflow for CS110 Internal Equivalency Lists.
     * This workflow allows Resource Persons to submit semester lists to HEA for review and endorsement.
     */
    public function up(): void
    {
        Schema::table('equivalency_lists', function (Blueprint $table) {
            // Restore submission fields (Resource Person submits to HEA)
            $table->timestamp('submitted_at')->nullable()->after('created_by_user_id');
            $table->text('submission_notes')->nullable()->after('submitted_at');

            // Restore HEA review fields
            $table->uuid('reviewed_by_user_id')->nullable()->after('submission_notes');
            $table->timestamp('reviewed_at')->nullable()->after('reviewed_by_user_id');
            $table->text('review_notes')->nullable()->after('reviewed_at');

            // Restore HEA endorsement fields
            $table->uuid('endorsed_by_user_id')->nullable()->after('review_notes');
            $table->timestamp('endorsed_at')->nullable()->after('endorsed_by_user_id');
            $table->text('endorsement_notes')->nullable()->after('endorsed_at');

            // Add foreign keys
            $table->foreign('reviewed_by_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('endorsed_by_user_id')->references('id')->on('users')->onDelete('set null');
        });

        // Restore complete status workflow
        if (DB::connection()->getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE `equivalency_lists` MODIFY COLUMN `status` ENUM('draft', 'submitted', 'under_review', 'endorsed', 'published', 'rejected') NOT NULL DEFAULT 'draft'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equivalency_lists', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['reviewed_by_user_id']);
            $table->dropForeign(['endorsed_by_user_id']);

            // Remove endorsement workflow columns
            $table->dropColumn([
                'submitted_at',
                'submission_notes',
                'reviewed_by_user_id',
                'reviewed_at',
                'review_notes',
                'endorsed_by_user_id',
                'endorsed_at',
                'endorsement_notes',
            ]);
        });

        // Revert to simplified status
        if (DB::connection()->getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE `equivalency_lists` MODIFY COLUMN `status` ENUM('draft', 'published', 'archived') NOT NULL DEFAULT 'draft'");
        }
    }
};
