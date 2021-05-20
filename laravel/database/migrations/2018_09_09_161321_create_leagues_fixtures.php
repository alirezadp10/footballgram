<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaguesFixtures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calcio_fixtures', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('source_id')
                  ->nullable();
            $table->string('source_type')
                  ->nullable();
            $table->string('host');
            $table->string('guest');
            $table->string('host_point')
                  ->nullable();
            $table->string('guest_point')
                  ->nullable();
            $table->string('season');
            $table->integer('period');
            $table->integer('final')
                  ->default(FALSE);
            $table->timestamp('datetime')
                  ->nullable();
            $table->timestamp('updated_at');
        });
        Schema::create('laliga_fixtures', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('source_id')
                  ->nullable();
            $table->string('source_type')
                  ->nullable();
            $table->string('host');
            $table->string('guest');
            $table->string('host_point')
                  ->nullable();
            $table->string('guest_point')
                  ->nullable();
            $table->string('season');
            $table->integer('period');
            $table->integer('final')
                  ->default(FALSE);
            $table->timestamp('datetime')
                  ->nullable();
            $table->timestamp('updated_at');
        });
        Schema::create('bundesliga_fixtures', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('source_id')
                  ->nullable();
            $table->string('source_type')
                  ->nullable();
            $table->string('host');
            $table->string('guest');
            $table->string('host_point')
                  ->nullable();
            $table->string('guest_point')
                  ->nullable();
            $table->string('season');
            $table->integer('period');
            $table->integer('final')
                  ->default(FALSE);
            $table->timestamp('datetime')
                  ->nullable();
            $table->timestamp('updated_at');
        });
        Schema::create('premier_league_fixtures', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('source_id')
                  ->nullable();
            $table->string('source_type')
                  ->nullable();
            $table->string('host');
            $table->string('guest');
            $table->string('host_point')
                  ->nullable();
            $table->string('guest_point')
                  ->nullable();
            $table->string('season');
            $table->integer('period');
            $table->integer('final')
                  ->default(FALSE);
            $table->timestamp('datetime')
                  ->nullable();
            $table->timestamp('updated_at');
        });
        Schema::create('khaligefars_fixtures', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('source_id')
                  ->nullable();
            $table->string('source_type')
                  ->nullable();
            $table->string('host');
            $table->string('guest');
            $table->string('host_point')
                  ->nullable();
            $table->string('guest_point')
                  ->nullable();
            $table->string('season');
            $table->integer('period');
            $table->integer('final')
                  ->default(FALSE);
            $table->timestamp('datetime')
                  ->nullable();
            $table->timestamp('updated_at');
        });
        Schema::create('azadegan_fixtures', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('source_id')
                  ->nullable();
            $table->string('source_type')
                  ->nullable();
            $table->string('host');
            $table->string('guest');
            $table->string('host_point')
                  ->nullable();
            $table->string('guest_point')
                  ->nullable();
            $table->string('season');
            $table->integer('period');
            $table->integer('final')
                  ->default(FALSE);
            $table->timestamp('datetime')
                  ->nullable();
            $table->timestamp('updated_at');
        });
        Schema::create('stars_league_fixtures', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('source_id')
                  ->nullable();
            $table->string('source_type')
                  ->nullable();
            $table->string('host');
            $table->string('guest');
            $table->string('host_point')
                  ->nullable();
            $table->string('guest_point')
                  ->nullable();
            $table->string('season');
            $table->integer('period');
            $table->integer('final')
                  ->default(FALSE);
            $table->timestamp('datetime')
                  ->nullable();
            $table->timestamp('updated_at');
        });
        Schema::create('eredivisie_fixtures', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('source_id')
                  ->nullable();
            $table->string('source_type')
                  ->nullable();
            $table->string('host');
            $table->string('guest');
            $table->string('host_point')
                  ->nullable();
            $table->string('guest_point')
                  ->nullable();
            $table->string('season');
            $table->integer('period');
            $table->integer('final')
                  ->default(FALSE);
            $table->timestamp('datetime')
                  ->nullable();
            $table->timestamp('updated_at');
        });
        Schema::create('loshampione_fixtures', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('source_id')
                  ->nullable();
            $table->string('source_type')
                  ->nullable();
            $table->string('host');
            $table->string('guest');
            $table->string('host_point')
                  ->nullable();
            $table->string('guest_point')
                  ->nullable();
            $table->string('season');
            $table->integer('period');
            $table->integer('final')
                  ->default(FALSE);
            $table->timestamp('datetime')
                  ->nullable();
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
        Schema::dropIfExists('calcio_fixtures');
        Schema::dropIfExists('bundesliga_fixtures');
        Schema::dropIfExists('laliga_fixtures');
        Schema::dropIfExists('premier_league_fixtures');
        Schema::dropIfExists('khaligefars_fixtures');
        Schema::dropIfExists('azadegan_fixtures');
        Schema::dropIfExists('eredivisie_fixtures');
        Schema::dropIfExists('loshampione_fixtures');
        Schema::dropIfExists('stars_league_fixtures');
    }
}
