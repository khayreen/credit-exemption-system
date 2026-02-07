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
        Schema::create('program_group_configs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('program_code', 20);
            $table->tinyInteger('semester');
            $table->char('group_letter', 1);
            $table->string('group_code', 20);
            $table->boolean('is_active')->default(true);
            $table->string('academic_year', 9)->nullable();
            $table->uuid('created_by')->nullable();
            $table->timestamps();

            $table->unique(['program_code', 'semester', 'group_letter'], 'program_semester_group_unique');
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->index('program_code');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_group_configs');
    }
};
