<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlideTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slider', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('news_id')
                  ->unsigned();
            $table->foreign('news_id')
                  ->on('news')
                  ->references('id')
                  ->onDelete('CASCADE')
                  ->onUpdate('CASCADE');
            $table->string('slug');
            $table->string('main_title');
            $table->string('secondary_title')
                  ->nullable();
            $table->string('first_tag')
                  ->nullable();
            $table->string('second_tag')
                  ->nullable();
            $table->string('third_tag')
                  ->nullable();
            $table->string('forth_tag')
                  ->nullable();
            $table->integer('order');
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
        Schema::dropIfExists('slider');
    }
}