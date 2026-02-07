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
        Schema::create('pending_reevaluations', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // The application subject that needs re-evaluation
            $table->uuid('application_subject_id');
            $table->foreign('application_subject_id')
                  ->references('id')
                  ->on('application_subjects')
                  ->onDelete('cascade');

            // The new equivalency that triggered this re-evaluation
            $table->uuid('course_equivalency_id');
            $table->foreign('course_equivalency_id')
                  ->references('id')
                  ->on('course_equivalencies')
                  ->onDelete('cascade');

            // The academic advisor assigned to review (nullable - can be any AA)
            $table->uuid('assigned_academic_advisor_id')->nullable();
            $table->foreign('assigned_academic_advisor_id')
                  ->references('id')
                  ->on('academic_advisors')
                  ->onDelete('set null');

            // Status of the re-evaluation
            $table->enum('status', ['pending', 'approved', 'rejected', 'expired'])->default('pending');

            // Store original status before potential update
            $table->string('original_subject_status');

            // Store the potential match details
            $table->string('diploma_course_code');
            $table->string('degree_course_code');
            $table->decimal('match_percentage', 5, 2);

            // Decision tracking
            $table->uuid('decided_by_user_id')->nullable();
            $table->foreign('decided_by_user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
            $table->timestamp('decided_at')->nullable();
            $table->text('decision_notes')->nullable();

            // Expiry (optional - re-evaluations could expire after X days)
            $table->timestamp('expires_at')->nullable();

            $table->timestamps();

            // Indexes for performance
            $table->index('status');
            $table->index(['application_subject_id', 'status']);
            $table->index('course_equivalency_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pending_reevaluations');
    }
};
