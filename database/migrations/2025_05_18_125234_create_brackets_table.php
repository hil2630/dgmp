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
        Schema::create('brackets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->dateTime('starts_at');
            $table->integer('time_limit_hours');
            $table->boolean('is_locked')->default(false);
            $table->enum('status', ['pending', 'active', 'completed'])->default('pending');
            $table->timestamps();
        });

        Schema::create('bracket_team', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bracket_id')->constrained()->cascadeOnDelete();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bracket_team');
        Schema::dropIfExists('brackets');
    }
};
