<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScorersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calcio_scorers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('season');
            $table->string('club');
            $table->string('name');
            $table->integer('count_scores');
            $table->integer('count_assists')->nullable();
            $table->timestamps();
        });
        Schema::create('coppa_italia_scorers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('season');
            $table->string('club');
            $table->string('name');
            $table->integer('count_scores');
            $table->integer('count_assists')->nullable();
            $table->timestamps();
        });
        Schema::create('khaligefars_scorers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('season');
            $table->string('club');
            $table->string('name');
            $table->integer('count_scores');
            $table->integer('count_assists')->nullable();
            $table->timestamps();
        });
        Schema::create('hazfi_scorers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('season');
            $table->string('club');
            $table->string('name');
            $table->integer('count_scores');
            $table->integer('count_assists')->nullable();
            $table->timestamps();
        });
        Schema::create('bundesliga_scorers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('season');
            $table->string('club');
            $table->string('name');
            $table->integer('count_scores');
            $table->integer('count_assists')->nullable();
            $table->timestamps();
        });
        Schema::create('dfb_pokal_scorers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('season');
            $table->string('club');
            $table->string('name');
            $table->integer('count_scores');
            $table->integer('count_assists')->nullable();
            $table->timestamps();
        });
        Schema::create('azadegan_scorers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('season');
            $table->string('club');
            $table->string('name');
            $table->integer('count_scores');
            $table->integer('count_assists')->nullable();
            $table->timestamps();
        });
        Schema::create('laliga_scorers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('season');
            $table->string('club');
            $table->string('name');
            $table->integer('count_scores');
            $table->integer('count_assists')->nullable();
            $table->timestamps();
        });
        Schema::create('copa_del_rey_scorers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('season');
            $table->string('club');
            $table->string('name');
            $table->integer('count_scores');
            $table->integer('count_assists')->nullable();
            $table->timestamps();
        });
        Schema::create('premier_league_scorers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('season');
            $table->string('club');
            $table->string('name');
            $table->integer('count_scores');
            $table->integer('count_assists')->nullable();
            $table->timestamps();
        });
        Schema::create('fa_cup_scorers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('season');
            $table->string('club');
            $table->string('name');
            $table->integer('count_scores');
            $table->integer('count_assists')->nullable();
            $table->timestamps();
        });
        Schema::create('efl_cup_scorers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('season');
            $table->string('club');
            $table->string('name');
            $table->integer('count_scores');
            $table->integer('count_assists')->nullable();
            $table->timestamps();
        });
        Schema::create('eredivisie_scorers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('season');
            $table->string('club');
            $table->string('name');
            $table->integer('count_scores');
            $table->integer('count_assists')->nullable();
            $table->timestamps();
        });
        Schema::create('loshampione_scorers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('season');
            $table->string('club');
            $table->string('name');
            $table->integer('count_scores');
            $table->integer('count_assists')->nullable();
            $table->timestamps();
        });
        Schema::create('stars_league_scorers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('season');
            $table->string('club');
            $table->string('name');
            $table->integer('count_scores');
            $table->integer('count_assists')->nullable();
            $table->timestamps();
        });
        Schema::create('champions_league_scorers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('season');
            $table->string('club');
            $table->string('name');
            $table->integer('count_scores');
            $table->integer('count_assists')->nullable();
            $table->timestamps();
        });
        Schema::create('europe_league_scorers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('season');
            $table->string('club');
            $table->string('name');
            $table->integer('count_scores');
            $table->integer('count_assists')->nullable();
            $table->timestamps();
        });
        Schema::create('afc_champions_league_scorers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('season');
            $table->string('club');
            $table->string('name');
            $table->integer('count_scores');
            $table->integer('count_assists')->nullable();
            $table->timestamps();
        });
        Schema::create('europe_nations_league_scorers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('season');
            $table->string('club');
            $table->string('name');
            $table->integer('count_scores');
            $table->integer('count_assists')->nullable();
            $table->timestamps();
        });
        Schema::create('uefa_euro_scorers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('season');
            $table->string('club');
            $table->string('name');
            $table->integer('count_scores');
            $table->integer('count_assists')->nullable();
            $table->timestamps();
        });
        Schema::create('afc_asian_scorers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('season');
            $table->string('club');
            $table->string('name');
            $table->integer('count_scores');
            $table->integer('count_assists')->nullable();
            $table->timestamps();
        });
        Schema::create('world_cup_scorers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('season');
            $table->string('club');
            $table->string('name');
            $table->integer('count_scores');
            $table->integer('count_assists')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calcio_scorers');
        Schema::dropIfExists('khaligefars_scorers');
        Schema::dropIfExists('azadegan_scorers');
        Schema::dropIfExists('bundesliga_scorers');
        Schema::dropIfExists('laliga_scorers');
        Schema::dropIfExists('premier_league_scorers');
        Schema::dropIfExists('eredivisie_scorers');
        Schema::dropIfExists('loshampione_scorers');
        Schema::dropIfExists('stars_league_scorers');
        Schema::dropIfExists('uefa_champions_league_scorers');
        Schema::dropIfExists('europe_league_scorers');
        Schema::dropIfExists('afc_champions_league_scorers');
        Schema::dropIfExists('europe_nations_league_scorers');
        Schema::dropIfExists('uefa_euro_scorers');
        Schema::dropIfExists('afc_asian_cup_scorers');
        Schema::dropIfExists('world_cup_scorers');
        Schema::dropIfExists('coppa_italia_scorers');
        Schema::dropIfExists('hazfi_scorers');
        Schema::dropIfExists('dfb_pokal_scorers');
        Schema::dropIfExists('copa_del_rey_scorers');
        Schema::dropIfExists('fa_cup_scorers');
        Schema::dropIfExists('efl_cup_scorers');
    }
}
