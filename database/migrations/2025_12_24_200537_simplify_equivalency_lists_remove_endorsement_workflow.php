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

            // Update status to only allow: draft, published, archived
            // Note: Existing 'submitted', 'under_review', 'endorsed', 'rejected' will be migrated to 'draft'
            if (\DB::connection()->getDriverName() !== 'sqlite') {
                \DB::statement("ALTER TABLE `equivalency_lists` MODIFY COLUMN `status` ENUM('draft', 'published', 'archived') NOT NULL DEFAULT 'draft'");
            }

            // Migrate existing statuses to new statuses
            \DB::statement("UPDATE `equivalency_lists` SET `status` = 'draft' WHERE `status` NOT IN ('published')");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equivalency_lists', function (Blueprint $table) {
            // Re-add removed columns
            $table->timestamp('submitted_at')->nullable();
            $table->text('submission_notes')->nullable();
            $table->uuid('reviewed_by_user_id')->nullable();
            $table->foreign('reviewed_by_user_id')->references('id')->on('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_notes')->nullable();
            $table->uuid('endorsed_by_user_id')->nullable();
            $table->foreign('endorsed_by_user_id')->references('id')->on('users')->onDelete('set null');
            $table->timestamp('endorsed_at')->nullable();
            $table->text('endorsement_notes')->nullable();

            // Restore original status enum
            if (\DB::connection()->getDriverName() !== 'sqlite') {
                \DB::statement("ALTER TABLE `equivalency_lists` MODIFY COLUMN `status` ENUM('draft', 'submitted', 'under_review', 'endorsed', 'published', 'rejected') NOT NULL DEFAULT 'draft'");
            }
        });
    }
};
