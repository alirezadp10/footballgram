<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_actions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->timestamps();
        });

        Schema::create('user_actions_pivot_users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_action_id')
                  ->unsigned();
            $table->integer('user_id')
                  ->unsigned();
            $table->foreign('user_action_id')
                  ->references('id')
                  ->on('user_actions')
                  ->onDelete('RESTRICT')
                  ->onUpdate('RESTRICT');
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('RESTRICT')
                  ->onUpdate('RESTRICT');
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
