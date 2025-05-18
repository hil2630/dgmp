<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seasons', function (Blueprint $table) {
            $table->foreignId('winner_team_id')->nullable()->after('status')->constrained('teams')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('seasons', function (Blueprint $table) {
            $table->dropForeign(['winner_team_id']);
            $table->dropColumn('winner_team_id');
        });
    }
};
