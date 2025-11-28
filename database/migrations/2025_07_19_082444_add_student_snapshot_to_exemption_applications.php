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
            // Student snapshot fields at time of application
            $table->string('student_name')->after('student_id');
            $table->string('matric_no')->after('student_name');
            $table->string('ic_number')->after('matric_no');
            $table->text('home_address')->nullable()->after('ic_number');
            $table->string('current_program')->after('home_address');
            $table->string('current_campus')->after('current_program');
            $table->string('current_faculty')->nullable()->after('current_campus');
            $table->enum('mode_of_study', ['Full-Time', 'Part-Time'])->nullable()->after('current_faculty');
            $table->string('current_semester')->nullable()->after('mode_of_study');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exemption_applications', function (Blueprint $table) {
            $table->dropColumn([
                'student_name', 'matric_no', 'ic_number', 'home_address',
                'current_program', 'current_campus', 'current_faculty',
                'mode_of_study', 'current_semester'
            ]);
        });
    }
};
