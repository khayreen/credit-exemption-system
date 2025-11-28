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
            // Drop the foreign key constraint
            $table->dropForeign(['degree_course_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_equivalencies', function (Blueprint $table) {
            // Re-add the foreign key constraint if needed to rollback
            $table->foreign('degree_course_code')->references('code')->on('courses')->onDelete('cascade');
        });
    }
};
