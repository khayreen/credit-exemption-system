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
        Schema::table('users', function (Blueprint $table) {
            $table->string('current_role')->nullable()->after('role')
                ->comment('Current active role (after approval)');

            $table->string('requested_role')->nullable()->after('current_role')
                ->comment('Role requested during registration');

            $table->json('requested_programs')->nullable()->after('requested_role')
                ->comment('Programs requested by user during registration (for AA/PC/RP)');

            $table->string('approval_status')->default('pending')->after('requested_programs')
                ->comment('Values: pending, pending_admin, approved, rejected');

            $table->foreignUuid('approved_by')->nullable()->after('approval_status')
                ->constrained('users')->nullOnDelete()
                ->comment('HEA or Admin who approved this user');

            $table->timestamp('approved_at')->nullable()->after('approved_by')
                ->comment('When the user was approved');

            $table->text('rejection_reason')->nullable()->after('approved_at')
                ->comment('Reason if registration was rejected');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn([
                'current_role',
                'requested_role',
                'requested_programs',
                'approval_status',
                'approved_by',
                'approved_at',
                'rejection_reason',
            ]);
        });
    }
};
