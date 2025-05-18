<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('runs', 'bracket_id')) {
            // Try to drop the foreign key constraint if it exists
            try {
                DB::statement('ALTER TABLE runs DROP FOREIGN KEY runs_bracket_id_foreign');
            } catch (\Exception $e) {
                // Ignore if it doesn't exist
            }
            Schema::table('runs', function (Blueprint $table) {
                $table->dropColumn('bracket_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('runs', function (Blueprint $table) {
            if (!Schema::hasColumn('runs', 'bracket_id')) {
                $table->foreignId('bracket_id')->nullable()->constrained()->nullOnDelete();
            }
        });
    }
};
