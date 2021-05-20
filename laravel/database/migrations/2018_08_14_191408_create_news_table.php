<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->increments('id');
            $table->string('main_title');
            $table->string('secondary_title')->nullable();
            $table->text('context')->nullable();
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
            $table->integer('view')->default(0);
            $table->integer('comment')->default(0);
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
        Schema::dropIfExists('news');
    }
}
