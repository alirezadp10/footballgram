<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChiefChoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chief_choices', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('post_id')->constrained('posts')->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('order')->default(1);
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
        Schema::dropIfExists('chief_choices');
    }
}
