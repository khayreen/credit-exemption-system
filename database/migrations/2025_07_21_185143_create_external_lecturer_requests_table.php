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
        Schema::create('external_lecturer_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('application_subject_id');
            $table->string('external_lecturer_email');
            $table->string('external_lecturer_name')->nullable();
            $table->text('request_notes')->nullable();
            $table->string('status')->default('pending'); // pending, registered, submitted
            $table->string('access_token', 64)->unique();
            $table->timestamp('token_expires_at');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->foreign('application_subject_id')->references('id')->on('application_subjects')->onDelete('cascade');
            $table->index('external_lecturer_email');
            $table->index('access_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('external_lecturer_requests');
    }
};
