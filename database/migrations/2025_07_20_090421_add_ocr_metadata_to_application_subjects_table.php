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
        Schema::table('application_subjects', function (Blueprint $table) {
            $table->enum('extraction_method', ['manual', 'ocr', 'imported'])->default('manual')->after('notes');
            $table->decimal('ocr_confidence_score', 5, 2)->nullable()->after('extraction_method');
            $table->boolean('needs_verification')->default(false)->after('ocr_confidence_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('application_subjects', function (Blueprint $table) {
            $table->dropColumn([
                'extraction_method',
                'ocr_confidence_score',
                'needs_verification'
            ]);
        });
    }
};
