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
        Schema::table('course_equivalencies', function (Blueprint $table) {
            // Add source field to track where equivalency came from
            $table->enum('source', ['seeded', 'imported', 'manual'])->default('manual')->after('notes');

            // Add index on diploma_institution for faster institution-based queries
            $table->index('diploma_institution', 'idx_diploma_institution');

            // Add composite index for institution-aware matching queries
            // This optimizes: WHERE diploma_course_code = X AND program_code = Y AND diploma_institution = Z
            $table->index(
                ['diploma_course_code', 'program_code', 'diploma_institution'],
                'idx_course_program_institution'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_equivalencies', function (Blueprint $table) {
            $table->dropIndex('idx_course_program_institution');
            $table->dropIndex('idx_diploma_institution');
            $table->dropColumn('source');
        });
    }
};
