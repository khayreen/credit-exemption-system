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
        Schema::create('external_lecturers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            // This role might not have a user account initially, so user_id is nullable
            $table->foreignUuid('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('institution_name');
            $table->string('phone_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('external_lecturers');
    }
};
