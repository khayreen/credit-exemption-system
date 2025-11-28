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
        Schema::create('application_subjects', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('exemption_application_id')->constrained('exemption_applications')->onDelete('cascade');
            $table->string('course_code');
            $table->string('course_name');
            $table->integer('credit_hour');
            $table->decimal('grade', 5, 2)->nullable(); // Store grade like 3.67
            $table->string('status')->default('Submitted'); // Submitted, Pending Resource Person, Approved, Rejected, etc.
            $table->string('syllabus_path')->nullable(); // Path to uploaded syllabus
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_subjects');
    }
};
