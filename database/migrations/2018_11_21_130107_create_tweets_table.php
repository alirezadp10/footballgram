<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTweetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tweets', function (Blueprint $table) {
            $table->increments('id');
            $table->text('context');
            $table->integer('user_id')
                  ->unsigned();
            $table->foreign('user_id')
                  ->on('users')
                  ->references('id')
                  ->onDelete('CASCADE')
                  ->onUpdate('CASCADE');
            $table->string('slug');
            $table->integer('like')->default(0);
            $table->integer('dislike')->default(0);
            $table->integer('report')->default(0);
            $table->integer('view')->default(0);
            $table->integer('comment')->default(0);
            $table->string('status');
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
        Schema::dropIfExists('tweets');
    }
}
