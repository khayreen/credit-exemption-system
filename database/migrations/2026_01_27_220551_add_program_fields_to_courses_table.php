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
        Schema::table('courses', function (Blueprint $table) {
            $table->string('program_code')->nullable()->after('code');
            $table->integer('semester')->nullable()->after('credit_hour');
            $table->string('type')->default('core')->after('semester'); // core or elective

            // Add index for faster lookups
            $table->index('program_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropIndex(['program_code']);
            $table->dropColumn(['program_code', 'semester', 'type']);
        });
    }
};
