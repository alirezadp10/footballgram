<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRestOfLeaguesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uefa_champions_league_table', function (Blueprint $table) {
            $table->string('name');
            $table->integer('position')
                  ->default(0);
            $table->integer('played')
                  ->default(0);
            $table->integer('won')
                  ->default(0);
            $table->integer('drawn')
                  ->default(0);
            $table->integer('lost')
                  ->default(0);
            $table->integer('goals_for')
                  ->default(0);
            $table->integer('goals_against')
                  ->default(0);
            $table->integer('goals_difference')
                  ->default(0);
            $table->integer('points')
                  ->default(0);
            $table->timestamp('updated_at');
            $table->string('group')
                  ->nullable();
            $table->string('season');
        });
        Schema::create('europe_league_table', function (Blueprint $table) {
            $table->string('name');
            $table->integer('position')
                  ->default(0);
            $table->integer('played')
                  ->default(0);
            $table->integer('won')
                  ->default(0);
            $table->integer('drawn')
                  ->default(0);
            $table->integer('lost')
                  ->default(0);
            $table->integer('goals_for')
                  ->default(0);
            $table->integer('goals_against')
                  ->default(0);
            $table->integer('goals_difference')
                  ->default(0);
            $table->integer('points')
                  ->default(0);
            $table->timestamp('updated_at');
            $table->string('group')
                  ->nullable();
            $table->string('season');
        });
        Schema::create('afc_champions_league_table', function (Blueprint $table) {
            $table->string('name');
            $table->integer('position')
                  ->default(0);
            $table->integer('played')
                  ->default(0);
            $table->integer('won')
                  ->default(0);
            $table->integer('drawn')
                  ->default(0);
            $table->integer('lost')
                  ->default(0);
            $table->integer('goals_for')
                  ->default(0);
            $table->integer('goals_against')
                  ->default(0);
            $table->integer('goals_difference')
                  ->default(0);
            $table->integer('points')
                  ->default(0);
            $table->timestamp('updated_at');
            $table->string('group')
                  ->nullable();
            $table->string('season');
        });
        Schema::create('europe_nations_league_table', function (Blueprint $table) {
            $table->string('name');
            $table->integer('position')
                  ->default(0);
            $table->integer('played')
                  ->default(0);
            $table->integer('won')
                  ->default(0);
            $table->integer('drawn')
                  ->default(0);
            $table->integer('lost')
                  ->default(0);
            $table->integer('goals_for')
                  ->default(0);
            $table->integer('goals_against')
                  ->default(0);
            $table->integer('goals_difference')
                  ->default(0);
            $table->integer('points')
                  ->default(0);
            $table->timestamp('updated_at');
            $table->string('group')
                  ->nullable();
            $table->string('season');
        });
        Schema::create('uefa_euro_table', function (Blueprint $table) {
            $table->string('name');
            $table->integer('position')
                  ->default(0);
            $table->integer('played')
                  ->default(0);
            $table->integer('won')
                  ->default(0);
            $table->integer('drawn')
                  ->default(0);
            $table->integer('lost')
                  ->default(0);
            $table->integer('goals_for')
                  ->default(0);
            $table->integer('goals_against')
                  ->default(0);
            $table->integer('goals_difference')
                  ->default(0);
            $table->integer('points')
                  ->default(0);
            $table->timestamp('updated_at');
            $table->string('group')
                  ->nullable();
            $table->string('season');
        });
        Schema::create('afc_asian_cup_table', function (Blueprint $table) {
            $table->string('name');
            $table->integer('position')
                  ->default(0);
            $table->integer('played')
                  ->default(0);
            $table->integer('won')
                  ->default(0);
            $table->integer('drawn')
                  ->default(0);
            $table->integer('lost')
                  ->default(0);
            $table->integer('goals_for')
                  ->default(0);
            $table->integer('goals_against')
                  ->default(0);
            $table->integer('goals_difference')
                  ->default(0);
            $table->integer('points')
                  ->default(0);
            $table->timestamp('updated_at');
            $table->string('group')
                  ->nullable();
            $table->string('season');
        });
        Schema::create('world_cup_table', function (Blueprint $table) {
            $table->string('name');
            $table->integer('position')
                  ->default(0);
            $table->integer('played')
                  ->default(0);
            $table->integer('won')
                  ->default(0);
            $table->integer('drawn')
                  ->default(0);
            $table->integer('lost')
                  ->default(0);
            $table->integer('goals_for')
                  ->default(0);
            $table->integer('goals_against')
                  ->default(0);
            $table->integer('goals_difference')
                  ->default(0);
            $table->integer('points')
                  ->default(0);
            $table->timestamp('updated_at');
            $table->string('group')
                  ->nullable();
            $table->string('season');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('uefa_champions_league_table');
        Schema::dropIfExists('europe_league_table');
        Schema::dropIfExists('afc_champions_league_table');
        Schema::dropIfExists('europe_nations_league_table');
        Schema::dropIfExists('uefa_euro_table');
        Schema::dropIfExists('afc_asian_cup_table');
        Schema::dropIfExists('world_cup_table');
    }
}
