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
            // Program Coordinator assignment
            $table->uuid('coordinator_id')->nullable()->after('student_id');

            // Coordinator decision
            $table->enum('coordinator_decision', ['pending', 'equivalent', 'not_equivalent', 'forward_to_rp'])
                  ->default('pending')
                  ->after('status');
            $table->text('coordinator_notes')->nullable()->after('coordinator_decision');
            $table->timestamp('coordinator_decided_at')->nullable()->after('coordinator_notes');

            // Selected lecturer information (when forwarding to RP)
            $table->string('selected_lecturer_name')->nullable()->after('coordinator_decided_at');
            $table->string('selected_lecturer_email')->nullable()->after('selected_lecturer_name');

            // Foreign key
            $table->foreign('coordinator_id')->references('id')->on('program_coordinators')->onDelete('set null');

            // Index
            $table->index('coordinator_id');
            $table->index('coordinator_decision');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_equivalency_requests', function (Blueprint $table) {
            $table->dropForeign(['coordinator_id']);
            $table->dropIndex(['coordinator_id']);
            $table->dropIndex(['coordinator_decision']);
            $table->dropColumn([
                'coordinator_id',
                'coordinator_decision',
                'coordinator_notes',
                'coordinator_decided_at',
                'selected_lecturer_name',
                'selected_lecturer_email',
            ]);
        });
    }
};
