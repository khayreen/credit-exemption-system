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
        Schema::create('course_equivalency_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_id');

            // Diploma Course Information
            $table->string('diploma_course_code', 20);
            $table->string('diploma_course_name');
            $table->string('diploma_institution');
            $table->string('diploma_program');
            $table->decimal('diploma_credit_hours', 5, 2);
            $table->string('diploma_grade', 5);

            // Student's suggested degree course (optional)
            $table->string('suggested_degree_course_code', 20)->nullable();
            $table->string('suggested_degree_course_name')->nullable();

            // Current program context
            $table->string('current_program_code', 10);
            $table->string('current_program_name');

            // Justification and supporting documents
            $table->text('justification');
            $table->string('syllabus_path')->nullable();

            // Request status and processing
            $table->enum('status', ['pending', 'under_review', 'approved', 'rejected'])->default('pending');
            $table->uuid('reviewed_by')->nullable();
            $table->text('reviewer_notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();

            // Equivalency result (if approved)
            $table->string('approved_degree_course_code', 20)->nullable();
            $table->string('approved_degree_course_name')->nullable();
            $table->integer('match_percentage')->nullable();

            $table->timestamps();

            // Foreign keys
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('reviewed_by')->references('id')->on('resource_persons')->onDelete('set null');

            // Indexes for better query performance
            $table->index('student_id');
            $table->index('status');
            $table->index('current_program_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_equivalency_requests');
    }
};
