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
            // Add external lecturer contact fields
            $table->string('external_lecturer_name')->after('justification');
            $table->string('external_lecturer_email')->after('external_lecturer_name');

            // Add syllabus request tracking fields
            $table->timestamp('syllabus_request_sent_at')->nullable()->after('external_lecturer_email');
            $table->timestamp('syllabus_received_at')->nullable()->after('syllabus_request_sent_at');

            // Remove the old syllabus_path column (student uploads)
            $table->dropColumn('syllabus_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_equivalency_requests', function (Blueprint $table) {
            // Restore syllabus_path column
            $table->string('syllabus_path')->nullable();

            // Remove lecturer fields
            $table->dropColumn([
                'external_lecturer_name',
                'external_lecturer_email',
                'syllabus_request_sent_at',
                'syllabus_received_at'
            ]);
        });
    }
};
