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
        Schema::create('transcripts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('exemption_application_id')->constrained()->onDelete('cascade');
            $table->string('original_filename');
            $table->string('file_path'); // Stored in a secure, non-public disk
            $table->text('digital_signature'); // The encrypted SHA-256 hash of the file
            $table->string('file_hash'); // The raw SHA-256 hash for quick checks
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transcripts');
    }
};
