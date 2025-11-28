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
            $table->text('home_address')->nullable()->after('ic_number');
            $table->foreignUuid('faculty_id')->nullable()->after('home_address');
            $table->enum('mode_of_study', ['Full-Time', 'Part-Time'])->nullable()->after('faculty_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['home_address', 'faculty_id', 'mode_of_study']);
        });
    }
};
