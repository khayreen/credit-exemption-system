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
        Schema::create('external_lecturer_submissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('external_lecturer_request_id');
            $table->string('institution_name');
            $table->string('course_code');
            $table->string('course_name');
            $table->integer('credit_hours');
            $table->text('justification_notes');
            $table->string('syllabus_file_path');
            $table->string('syllabus_file_original_name');
            $table->string('digital_signature')->nullable();
            $table->timestamps();

            $table->foreign('external_lecturer_request_id', 'ext_lecturer_submissions_request_id_fk')->references('id')->on('external_lecturer_requests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('external_lecturer_submissions');
    }
};
