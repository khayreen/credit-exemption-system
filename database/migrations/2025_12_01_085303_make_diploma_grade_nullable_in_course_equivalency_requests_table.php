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
        Schema::table('course_equivalency_requests', function (Blueprint $table) {
            // Make diploma_grade nullable since grade is not required for equivalency requests
            $table->string('diploma_grade', 5)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_equivalency_requests', function (Blueprint $table) {
            // Revert diploma_grade to NOT NULL
            $table->string('diploma_grade', 5)->nullable(false)->change();
        });
    }
};
