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
        Schema::create('degree_course_syllabi', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('course_code', 20);
            $table->string('course_name', 255);
            $table->decimal('credit_hours', 3, 1)->default(3.0);
            $table->string('program_code', 20); // CDCS251, CDCS253, etc.
            $table->string('syllabus_file_path', 255); // Path to PDF
            $table->string('syllabus_file_original_name', 255)->nullable();
            $table->string('digital_signature', 64)->nullable(); // SHA256 hash
            $table->text('description')->nullable(); // Brief course description
            $table->string('academic_session', 20)->nullable(); // 2024/2025
            $table->uuid('uploaded_by')->nullable(); // Resource Person who uploaded
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Indexes
            $table->index('course_code');
            $table->index('program_code');
            $table->index(['course_code', 'program_code']);

            // Foreign key
            $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('degree_course_syllabi');
    }
};
