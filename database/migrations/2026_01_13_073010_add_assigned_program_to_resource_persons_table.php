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
        Schema::table('resource_persons', function (Blueprint $table) {
            // Add assigned_program column to store single program code
            // Example: 'CDCS251'
            $table->string('assigned_program', 10)->nullable()->after('assigned_programs')
                ->comment('Single program code the resource person supports');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resource_persons', function (Blueprint $table) {
            $table->dropColumn('assigned_program');
        });
    }
};
