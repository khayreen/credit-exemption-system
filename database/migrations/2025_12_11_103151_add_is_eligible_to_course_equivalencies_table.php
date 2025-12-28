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
            // is_eligible: true = LULUS (can get exemption), false = TIDAK LULUS (cannot get exemption)
            $table->boolean('is_eligible')->default(true)->after('match_percentage');

            // Add index for quick filtering
            $table->index('is_eligible');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_equivalencies', function (Blueprint $table) {
            $table->dropIndex(['is_eligible']);
            $table->dropColumn('is_eligible');
        });
    }
};
