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
            // Link to equivalency list
            $table->uuid('equivalency_list_id')->nullable()->after('id');

            // Version tracking
            $table->integer('version')->default(1)->after('equivalency_list_id');

            // Publication status
            $table->boolean('is_published')->default(false)->after('version');

            // Foreign key
            $table->foreign('equivalency_list_id')
                  ->references('id')
                  ->on('equivalency_lists')
                  ->onDelete('set null');

            // Index for faster queries
            $table->index('equivalency_list_id');
            $table->index('is_published');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_equivalencies', function (Blueprint $table) {
            $table->dropForeign(['equivalency_list_id']);
            $table->dropIndex(['equivalency_list_id']);
            $table->dropIndex(['is_published']);
            $table->dropColumn(['equivalency_list_id', 'version', 'is_published']);
        });
    }
};
