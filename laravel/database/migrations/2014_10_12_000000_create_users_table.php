<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->string('instagram_id')->nullable();
            $table->string('telegram_id')->nullable();
            $table->string('role')->default('user');
            $table->integer('count_news')->default(0);
            $table->integer('count_user_contents')->default(0);
            $table->integer('count_followers')->default(0);
            $table->integer('count_followings')->default(0);
            $table->integer('count_likes_given')->default(0);
            $table->integer('count_dislikes_given')->default(0);
            $table->integer('count_comments_taken')->default(0);
            $table->integer('count_comments_given')->default(0);

            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
