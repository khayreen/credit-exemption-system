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
        // Add name and email to academic_advisors table
        Schema::table('academic_advisors', function (Blueprint $table) {
            $table->string('name')->nullable()->after('user_id');
            $table->string('email')->nullable()->after('name');
        });

        // Add name and email to resource_persons table
        Schema::table('resource_persons', function (Blueprint $table) {
            $table->string('name')->nullable()->after('user_id');
            $table->string('email')->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('academic_advisors', function (Blueprint $table) {
            $table->dropColumn(['name', 'email']);
        });

        Schema::table('resource_persons', function (Blueprint $table) {
            $table->dropColumn(['name', 'email']);
        });
    }
};
