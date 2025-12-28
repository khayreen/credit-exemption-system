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
        Schema::table('students', function (Blueprint $table) {
            // Make ic_number nullable and remove unique constraint
            $table->string('ic_number')->nullable()->change();

            // Make campus and intake_semester nullable
            $table->string('campus')->nullable()->change();
            $table->string('intake_semester')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Revert back to NOT NULL (may fail if there are NULL values)
            $table->string('ic_number')->nullable(false)->unique()->change();
            $table->string('campus')->nullable(false)->change();
            $table->string('intake_semester')->nullable(false)->change();
        });
    }
};
