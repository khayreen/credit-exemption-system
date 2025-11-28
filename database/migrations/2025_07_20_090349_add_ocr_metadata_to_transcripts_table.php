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
        Schema::table('transcripts', function (Blueprint $table) {
            $table->enum('ocr_status', ['pending', 'processing', 'completed', 'failed'])->default('pending')->after('file_hash');
            $table->timestamp('ocr_processed_at')->nullable()->after('ocr_status');
            $table->longText('ocr_raw_text')->nullable()->after('ocr_processed_at');
            $table->text('ocr_error_message')->nullable()->after('ocr_raw_text');
            $table->decimal('ocr_confidence_score', 5, 2)->nullable()->after('ocr_error_message');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transcripts', function (Blueprint $table) {
            $table->dropColumn([
                'ocr_status',
                'ocr_processed_at', 
                'ocr_raw_text',
                'ocr_error_message',
                'ocr_confidence_score'
            ]);
        });
    }
};
