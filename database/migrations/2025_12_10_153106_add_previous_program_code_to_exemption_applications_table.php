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
            $table->string('previous_program_code', 10)->nullable()->after('previous_program');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exemption_applications', function (Blueprint $table) {
            $table->dropColumn('previous_program_code');
        });
    }
};
