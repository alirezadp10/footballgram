<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('abilities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
        });

        Schema::create('users_abilities', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ability_id');
            $table->unsignedInteger('user_id');
            $table->foreign('ability_id')
                  ->references('id')
                  ->on('abilities')
                  ->onDelete('CASCADE')
                  ->onUpdate('CASCADE');
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('CASCADE')
                  ->onUpdate('CASCADE');
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
        Schema::dropIfExists('user_actions_pivot_users');
        Schema::dropIfExists('user_actions');
    }
}
