<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFollowingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('followings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('follower_id')
                  ->unsigned();
            $table->foreign('follower_id')
                  ->on('users')
                  ->references('id')
                  ->onUpdate('RESTRICT')
                  ->onDelete('RESTRICT');
            $table->integer('follow_up_id')
                  ->unsigned();
            $table->foreign('follow_up_id')
                  ->on('users')
                  ->references('id')
                  ->onUpdate('RESTRICT')
                  ->onDelete('RESTRICT');
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
        Schema::dropIfExists('followings');
    }
}
