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
        Schema::table('academic_advisors', function (Blueprint $table) {
            // Add program_groups column to store array of {program_code, group} objects
            // Example: [{"program_code":"CDCS251","group":"CDCS2513A"},{"program_code":"CDCS230","group":"CDCS2301B"}]
            $table->json('program_groups')->nullable()->after('assigned_programs')
                ->comment('Array of programme-group assignments');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('academic_advisors', function (Blueprint $table) {
            $table->dropColumn('program_groups');
        });
    }
};
