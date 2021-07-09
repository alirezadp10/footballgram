<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments',function (Blueprint $table) {
            $table->increments('id');
            $table->text('context');
            $table->morphs('commentable');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->on('users')->references('id')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->integer('parent_id')->nullable();
            $table->integer('like')->default(0);
            $table->integer('dislike')->default(0);
            $table->integer('report')->default(0);
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
        Schema::dropIfExists('comments');
    }
}
