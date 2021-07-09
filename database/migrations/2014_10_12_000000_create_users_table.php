<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('username')->nullable();
            $table->string('mobile')->nullable();
            $table->text('bio')->nullable();
            $table->string('email')->nullable();
            $table->string('password');
            $table->string('image')->nullable();
            $table->integer('count_posts')->default(0);
            $table->integer('count_followers')->default(0);
            $table->integer('count_followings')->default(0);
            $table->integer('count_likes_given')->default(0);
            $table->integer('count_dislikes_given')->default(0);
            $table->integer('count_comments_taken')->default(0);
            $table->integer('count_comments_given')->default(0);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
