<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('seasons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('status', ['upcoming', 'active', 'completed'])->default('upcoming');
            $table->dateTime('starts_at');
            $table->dateTime('end_date')->nullable();
            $table->integer('time_limit_hours')->default(4);
            $table->boolean('is_locked')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('seasons');
    }
};
