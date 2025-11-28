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
        Schema::table('exemption_applications', function (Blueprint $table) {
            $table->string('student_group', 20)->nullable()->after('current_program_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exemption_applications', function (Blueprint $table) {
            $table->dropColumn('student_group');
        });
    }
};
