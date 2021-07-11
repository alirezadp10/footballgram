<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDislikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dislikes', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('dislikable');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')
                  ->on('users')
                  ->references('id')
                  ->onUpdate('CASCADE')
                  ->onDelete('CASCADE');
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
        Schema::dropIfExists('dislikes');
    }
}
