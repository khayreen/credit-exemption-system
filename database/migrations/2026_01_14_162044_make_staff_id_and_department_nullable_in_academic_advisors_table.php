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
        // Make academic_advisors fields nullable
        Schema::table('academic_advisors', function (Blueprint $table) {
            $table->string('staff_id', 50)->nullable()->change();
            $table->string('department', 100)->nullable()->change();
        });

        // Make program_coordinators program_codes nullable
        Schema::table('program_coordinators', function (Blueprint $table) {
            $table->json('program_codes')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('academic_advisors', function (Blueprint $table) {
            $table->string('staff_id', 50)->nullable(false)->change();
            $table->string('department', 100)->nullable(false)->change();
        });

        Schema::table('program_coordinators', function (Blueprint $table) {
            $table->json('program_codes')->nullable(false)->change();
        });
    }
};
