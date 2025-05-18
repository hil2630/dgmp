<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('team_user', function (Blueprint $table) {
            $table->string('class')->nullable()->after('role');
        });
    }

    public function down()
    {
        Schema::table('team_user', function (Blueprint $table) {
            $table->dropColumn('class');
        });
    }
};
