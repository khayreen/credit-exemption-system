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
        Schema::table('course_equivalencies', function (Blueprint $table) {
            $table->string('program_code')->nullable()->after('degree_credit_hour');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_equivalencies', function (Blueprint $table) {
            $table->dropColumn('program_code');
        });
    }
};
