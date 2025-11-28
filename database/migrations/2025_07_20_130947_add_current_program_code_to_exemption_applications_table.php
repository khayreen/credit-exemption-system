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
            $table->string('current_program_code')->nullable()->after('current_program');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exemption_applications', function (Blueprint $table) {
            $table->dropColumn('current_program_code');
        });
    }
};
