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
        Schema::table('program_coordinators', function (Blueprint $table) {
            // Add program_category column to store selected category
            // Values: 'category_1' (CDCS230, CDCS253) or 'category_2' (CDCS251, CDCS255, CDCS266)
            $table->string('program_category', 20)->nullable()->after('program_codes')
                ->comment('Category of programs: category_1 or category_2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('program_coordinators', function (Blueprint $table) {
            $table->dropColumn('program_category');
        });
    }
};
