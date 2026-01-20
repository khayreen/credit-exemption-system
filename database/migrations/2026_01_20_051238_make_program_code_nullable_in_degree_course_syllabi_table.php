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
        Schema::table('degree_course_syllabi', function (Blueprint $table) {
            // Make program_code nullable since one course can be taken by multiple programs
            $table->string('program_code', 20)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('degree_course_syllabi', function (Blueprint $table) {
            $table->string('program_code', 20)->nullable(false)->change();
        });
    }
};
