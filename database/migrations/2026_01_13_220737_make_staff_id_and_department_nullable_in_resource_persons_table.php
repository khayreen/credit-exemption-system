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
            $table->string('staff_id')->nullable()->change();
            $table->string('department')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resource_persons', function (Blueprint $table) {
            $table->string('staff_id')->nullable(false)->change();
            $table->string('department')->nullable(false)->change();
        });
    }
};
