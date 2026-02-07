<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('program_group_configs', function (Blueprint $table) {
            // Add intake column (march or september)
            $table->string('intake', 20)->default('march')->after('academic_year');

            // Drop the old unique constraint
            $table->dropUnique('program_semester_group_unique');

            // Add new unique constraint including academic_year and intake
            $table->unique(
                ['program_code', 'semester', 'group_letter', 'academic_year', 'intake'],
                'program_group_session_unique'
            );

            // Add index for faster filtering
            $table->index(['academic_year', 'intake'], 'academic_session_index');
        });

        // Update existing records to have the current academic year if null
        DB::table('program_group_configs')
            ->whereNull('academic_year')
            ->update(['academic_year' => '2025/2026']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('program_group_configs', function (Blueprint $table) {
            $table->dropIndex('academic_session_index');
            $table->dropUnique('program_group_session_unique');
            $table->dropColumn('intake');

            // Restore original unique constraint
            $table->unique(
                ['program_code', 'semester', 'group_letter'],
                'program_semester_group_unique'
            );
        });
    }
};
