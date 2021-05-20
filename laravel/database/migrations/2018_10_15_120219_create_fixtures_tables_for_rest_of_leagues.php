<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFixturesTablesForRestOfLeagues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uefa_champions_league_fixtures', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('source_id')->nullable();
            $table->string('source_type')->nullable();
            $table->string('host');
            $table->string('guest');
            $table->string('host_point')->nullable();
            $table->string('guest_point')->nullable();
            $table->string('season');
            $table->string('match_type')->comment('LEAGUE | GROUP_STAGE | PLAY_OFFS');
            $table->string('odd_even')->comment('ODD | EVEN');
            $table->string('round')->nullable();
            $table->integer('period')->nullable();
            $table->integer('final')->default(FALSE);
            $table->timestamp('datetime')->nullable();
            $table->timestamp('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('uefa_champions_league_fixtures');
    }
}
