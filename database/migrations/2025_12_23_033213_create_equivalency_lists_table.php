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
        Schema::create('equivalency_lists', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Target degree program
            $table->string('program_code', 20);
            $table->string('program_name', 255);

            // Semester tracking
            $table->string('semester', 20);          // e.g., "2024/2025-1", "2024/2025-2"
            $table->string('academic_year', 20);     // e.g., "2024/2025"

            // Category: Internal (CS110) vs External (Other Institutions)
            $table->enum('category', ['internal', 'external']);

            // For external category only (NULL for internal since it's always CS110)
            $table->string('source_institution', 255)->nullable();

            // Status workflow
            $table->enum('status', ['draft', 'submitted', 'under_review', 'endorsed', 'published', 'rejected'])->default('draft');

            // Resource Person (Creator)
            $table->uuid('created_by_user_id');
            $table->timestamp('submitted_at')->nullable();
            $table->text('submission_notes')->nullable();

            // HEA Review
            $table->uuid('reviewed_by_user_id')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_notes')->nullable();

            // HEA Endorsement
            $table->uuid('endorsed_by_user_id')->nullable();
            $table->timestamp('endorsed_at')->nullable();
            $table->text('endorsement_notes')->nullable();

            // Publication
            $table->uuid('published_by_user_id')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->boolean('is_active')->default(false);

            // Statistics (denormalized for performance)
            $table->integer('total_equivalencies')->default(0);
            $table->integer('eligible_count')->default(0);
            $table->integer('not_eligible_count')->default(0);

            $table->timestamps();

            // Foreign keys
            $table->foreign('created_by_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('reviewed_by_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('endorsed_by_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('published_by_user_id')->references('id')->on('users')->onDelete('set null');

            // Indexes
            $table->index('program_code');
            $table->index('category');
            $table->index('status');
            $table->index('is_active');
            $table->index(['program_code', 'semester', 'category']);

            // Unique constraint: One list per program per semester per category per source
            // For internal: source_institution is NULL, so unique on (program_code, semester, category) where category='internal'
            // For external: unique on (program_code, semester, category, source_institution)
            $table->unique(['program_code', 'semester', 'category', 'source_institution'], 'unique_equivalency_list');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equivalency_lists');
    }
};
