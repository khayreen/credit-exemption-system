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
        Schema::create('course_equivalencies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('diploma_course_code');
            $table->string('diploma_course_name');
            $table->integer('diploma_credit_hour');
            $table->string('diploma_institution');

            // FIX: Define the column first...
            $table->string('degree_course_code');
            // ...then add the foreign key constraint.
            $table->foreign('degree_course_code')->references('code')->on('courses')->onDelete('cascade');

            $table->float('match_percentage');
            $table->foreignUuid('approved_by_user_id')->constrained('users')->onDelete('cascade');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_equivalencies');
    }
};
