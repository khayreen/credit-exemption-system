<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * ARCHITECTURAL CHANGE:
     * - ONE continuous CS110 list per program (not multiple lists)
     * - Semester is specified at submission time (for publication target)
     * - Course equivalencies track history when updated/deleted
     * - After publication, list reverts to draft for continuous editing
     */
    public function up(): void
    {
        // Create course equivalency history table
        Schema::create('course_equivalency_history', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('equivalency_list_id');
            $table->uuid('original_equivalency_id')->nullable(); // Reference to original record

            // Course details (snapshot at time of archival)
            $table->string('program_code', 20);
            $table->string('diploma_course_code', 50);
            $table->string('diploma_course_name', 255);
            $table->integer('diploma_credit_hour');
            $table->string('diploma_institution', 255)->nullable();
            $table->string('degree_course_code', 50);
            $table->string('degree_course_name', 255);
            $table->integer('degree_credit_hour');
            $table->decimal('match_percentage', 5, 2);
            $table->boolean('is_eligible')->default(false);

            // History metadata
            $table->enum('action', ['deleted', 'replaced', 'archived']); // Why it's in history
            $table->uuid('archived_by_user_id')->nullable();
            $table->timestamp('archived_at');
            $table->text('archive_reason')->nullable();

            $table->timestamps();

            // Foreign keys
            $table->foreign('equivalency_list_id')->references('id')->on('equivalency_lists')->onDelete('cascade');
            $table->foreign('archived_by_user_id')->references('id')->on('users')->onDelete('set null');

            // Indexes
            $table->index('equivalency_list_id');
            $table->index('program_code');
            $table->index('archived_at');
        });

        // Update equivalency_lists table - remove unique constraint on semester
        Schema::table('equivalency_lists', function (Blueprint $table) {
            // Drop the old unique constraint
            $table->dropUnique('unique_equivalency_list');

            // Add new unique constraint: ONE list per program+category (no semester)
            // For internal: Only one CS110 list per program
            // For external: Only one list per program+institution
            $table->unique(['program_code', 'category', 'source_institution'], 'unique_program_category_source');

            // Make semester nullable (will be set at submission time)
            $table->string('semester', 20)->nullable()->change();

            // Add target_semester for publication (set at submission)
            $table->string('target_semester', 20)->nullable()->after('semester');
        });

        // Update course_equivalencies table - add metadata for tracking
        Schema::table('course_equivalencies', function (Blueprint $table) {
            // Track when mapping was last updated
            $table->uuid('last_updated_by_user_id')->nullable()->after('is_published');
            $table->timestamp('last_updated_at')->nullable()->after('last_updated_by_user_id');

            // Foreign key
            $table->foreign('last_updated_by_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop course equivalency history table
        Schema::dropIfExists('course_equivalency_history');

        // Restore equivalency_lists table
        Schema::table('equivalency_lists', function (Blueprint $table) {
            // Drop new unique constraint
            $table->dropUnique('unique_program_category_source');

            // Restore original unique constraint
            $table->unique(['program_code', 'semester', 'category', 'source_institution'], 'unique_equivalency_list');

            // Remove target_semester
            $table->dropColumn('target_semester');

            // Make semester required again
            $table->string('semester', 20)->nullable(false)->change();
        });

        // Restore course_equivalencies table
        Schema::table('course_equivalencies', function (Blueprint $table) {
            $table->dropForeign(['last_updated_by_user_id']);
            $table->dropColumn(['last_updated_by_user_id', 'last_updated_at']);
        });
    }
};
