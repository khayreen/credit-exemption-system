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
        Schema::table('external_lecturer_requests', function (Blueprint $table) {
            // Add nullable foreign key to course_equivalency_requests
            $table->uuid('course_equivalency_request_id')->nullable()->after('application_subject_id');
            $table->foreign('course_equivalency_request_id')
                  ->references('id')
                  ->on('course_equivalency_requests')
                  ->onDelete('cascade');

            // Make application_subject_id nullable since we can have either type of request
            $table->uuid('application_subject_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('external_lecturer_requests', function (Blueprint $table) {
            // Drop foreign key and column
            $table->dropForeign(['course_equivalency_request_id']);
            $table->dropColumn('course_equivalency_request_id');

            // Restore application_subject_id as required (non-nullable)
            // Note: This might fail if there are records with null application_subject_id
            $table->uuid('application_subject_id')->nullable(false)->change();
        });
    }
};
