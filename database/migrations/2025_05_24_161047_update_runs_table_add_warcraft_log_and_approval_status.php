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
        Schema::table('runs', function (Blueprint $table) {
            // Add new fields
            $table->string('warcraft_log_url')->nullable()->after('time_taken_seconds');
            $table->enum('approval_status', ['pending', 'approved', 'denied'])->default('pending')->after('status');

            // Remove completed_at field
            $table->dropColumn('completed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('runs', function (Blueprint $table) {
            // Remove new fields
            $table->dropColumn(['warcraft_log_url', 'approval_status']);

            // Add back completed_at field
            $table->dateTime('completed_at')->after('key_level');
        });
    }
};
