<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add reminder tracking columns to exemption_applications
     */
    public function up(): void
    {
        Schema::table('exemption_applications', function (Blueprint $table) {
            $table->timestamp('reminder_sent_at')->nullable()->after('status');
            $table->foreignUuid('reminder_sent_by')->nullable()->after('reminder_sent_at')
                  ->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exemption_applications', function (Blueprint $table) {
            $table->dropForeign(['reminder_sent_by']);
            $table->dropColumn(['reminder_sent_at', 'reminder_sent_by']);
        });
    }
};
