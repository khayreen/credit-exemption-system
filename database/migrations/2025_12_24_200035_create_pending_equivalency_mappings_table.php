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
        Schema::create('pending_equivalency_mappings', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Resource Person who created this mapping
            $table->uuid('resource_person_user_id');
            $table->foreign('resource_person_user_id')->references('id')->on('users')->onDelete('cascade');

            // Program this mapping is for
            $table->string('program_code', 10);

            // Diploma course information
            $table->string('diploma_course_code', 50);
            $table->string('diploma_course_name');
            $table->decimal('diploma_credit_hour', 3, 1);
            $table->string('diploma_institution');

            // Degree course information
            $table->string('degree_course_code', 50);
            $table->string('degree_course_name');
            $table->decimal('degree_credit_hour', 3, 1);

            // Match percentage and notes
            $table->decimal('match_percentage', 5, 2);
            $table->text('notes')->nullable();

            // Status tracking
            $table->enum('status', ['pending', 'added', 'rejected'])->default('pending');

            // Program Coordinator actions
            $table->uuid('added_by_coordinator_id')->nullable();
            $table->foreign('added_by_coordinator_id')->references('id')->on('users')->onDelete('set null');
            $table->timestamp('added_at')->nullable();
            $table->text('rejection_reason')->nullable();

            // Link to student's equivalency request (if applicable)
            $table->uuid('course_equivalency_request_id')->nullable();
            $table->foreign('course_equivalency_request_id', 'pem_ceq_request_fk')->references('id')->on('course_equivalency_requests')->onDelete('set null');

            $table->timestamps();

            // Indexes for faster queries
            $table->index('resource_person_user_id');
            $table->index('program_code');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pending_equivalency_mappings');
    }
};
