<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tournaments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('season_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->enum('type', ['main', 'on_day']);
            $table->dateTime('date');
            $table->integer('prize_pool');
            $table->text('description');
            $table->enum('status', ['upcoming', 'active', 'completed', 'cancelled'])->default('upcoming');
            $table->json('distribution')->nullable();
            $table->foreignId('winner_team_id')->nullable()->constrained('teams')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tournaments');
    }
};
