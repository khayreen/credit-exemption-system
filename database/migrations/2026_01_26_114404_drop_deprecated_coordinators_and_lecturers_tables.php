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
        Schema::dropIfExists('coordinators');
        Schema::dropIfExists('lecturers');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('coordinators', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
            $table->string('staff_id')->unique();
            $table->string('department');
            $table->timestamps();
        });

        Schema::create('lecturers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
            $table->string('staff_id')->unique();
            $table->string('department');
            $table->timestamps();
        });
    }
};
