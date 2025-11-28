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
        // Drop mode_of_study column from students table
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('mode_of_study');
        });

        // Drop mode_of_study column from exemption_applications table
        Schema::table('exemption_applications', function (Blueprint $table) {
            $table->dropColumn('mode_of_study');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-add mode_of_study column to students table
        Schema::table('students', function (Blueprint $table) {
            $table->enum('mode_of_study', ['Full-Time', 'Part-Time'])->nullable()->after('faculty_id');
        });

        // Re-add mode_of_study column to exemption_applications table
        Schema::table('exemption_applications', function (Blueprint $table) {
            $table->enum('mode_of_study', ['Full-Time', 'Part-Time'])->nullable()->after('current_faculty');
        });
    }
};
