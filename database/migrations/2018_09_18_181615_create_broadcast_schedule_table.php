<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBroadcastScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('broadcast_schedule',function (Blueprint $table) {
            $table->increments('id');
            $table->string('host');
            $table->string('guest');
            $table->foreignId('broadcast_channel_id')
                  ->constrained('broadcast_channels')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();
            $table->timestamp('datetime');
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
        Schema::dropIfExists('broadcast_schedule');
    }
}
