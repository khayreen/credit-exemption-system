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
        // 1. Admins table
        Schema::create('admins', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->string('staff_id')->nullable();
            $table->string('department')->nullable();
            $table->timestamps();
        });

        // 2. Login Attempts table (ST01 - Authentication Monitoring)
        Schema::create('login_attempts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('email');
            $table->string('ip_address', 45);
            $table->text('user_agent')->nullable();
            $table->enum('status', ['success', 'failed'])->default('failed');
            $table->string('failure_reason')->nullable(); // wrong_password, account_locked, etc.
            $table->timestamp('attempted_at');
            $table->timestamps();

            $table->index(['email', 'attempted_at']);
            $table->index(['ip_address', 'attempted_at']);
            $table->index(['status', 'attempted_at']);
        });

        // 3. Add lockout fields to users table (ST01, ST03)
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('locked_at')->nullable()->after('two_factor_recovery_codes');
            $table->string('lock_reason')->nullable()->after('locked_at');
            $table->integer('failed_login_attempts')->default(0)->after('lock_reason');
            $table->timestamp('last_failed_login_at')->nullable()->after('failed_login_attempts');
        });

        // 4. Announcements table
        Schema::create('announcements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->text('content');
            $table->enum('type', ['info', 'warning', 'success', 'danger'])->default('info');
            $table->json('target_roles')->nullable(); // ['student', 'hea_personnel', 'all']
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->boolean('is_dismissible')->default(true);
            $table->boolean('is_active')->default(true);
            $table->foreignUuid('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['is_active', 'starts_at', 'ends_at']);
        });

        // 5. FAQ Items table
        Schema::create('faq_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('category')->nullable();
            $table->text('question');
            $table->text('answer');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['category', 'is_active', 'sort_order']);
        });

        // 6. Help Articles table
        Schema::create('help_articles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category')->nullable();
            $table->longText('content');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->foreignUuid('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['category', 'is_active', 'sort_order']);
        });

        // 7. Contact Settings table
        Schema::create('contact_settings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('key')->unique();
            $table->string('label');
            $table->string('value', 500);
            $table->enum('type', ['email', 'phone', 'url', 'text'])->default('text');
            $table->foreignUuid('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 8. Access Logs table (ST05 - Access Control Monitoring)
        Schema::create('access_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('route');
            $table->string('method', 10);
            $table->string('ip_address', 45);
            $table->enum('status', ['allowed', 'denied'])->default('allowed');
            $table->string('denial_reason')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status', 'created_at']);
            $table->index(['status', 'created_at']);
        });

        // 9. Security Events table (ST06 - Input Validation Monitoring)
        Schema::create('security_events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('event_type', ['sql_injection', 'xss_attempt', 'csrf_failure', 'suspicious_input', 'brute_force', 'other']);
            $table->foreignUuid('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('ip_address', 45);
            $table->text('request_data')->nullable(); // Sanitized request data
            $table->string('route')->nullable();
            $table->boolean('blocked')->default(true);
            $table->text('details')->nullable();
            $table->timestamps();

            $table->index(['event_type', 'created_at']);
            $table->index(['ip_address', 'created_at']);
        });

        // 10. Terms and Conditions Versions table (for version history)
        Schema::create('terms_versions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('version');
            $table->longText('content');
            $table->date('effective_date');
            $table->boolean('is_current')->default(false);
            $table->foreignUuid('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['is_current']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('terms_versions');
        Schema::dropIfExists('security_events');
        Schema::dropIfExists('access_logs');
        Schema::dropIfExists('contact_settings');
        Schema::dropIfExists('help_articles');
        Schema::dropIfExists('faq_items');
        Schema::dropIfExists('announcements');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['locked_at', 'lock_reason', 'failed_login_attempts', 'last_failed_login_at']);
        });

        Schema::dropIfExists('login_attempts');
        Schema::dropIfExists('admins');
    }
};
