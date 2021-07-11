<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFixturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fixtures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained('competitions')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('host');
            $table->string('guest');
            $table->string('host_point')->nullable();
            $table->string('guest_point')->nullable();
            $table->boolean('final')->default(false);
            $table->timestamp('datetime')->nullable();
            $table->integer('season')->index();
            $table->string('match_type')->index()->nullable()->comment('LEAGUE | GROUP_STAGE | PLAY_OFFS');
            $table->string('odd_even')->nullable()->comment('ODD | EVEN');
            $table->string('round')->nullable();
            $table->integer('period')->index()->nullable();
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
        Schema::dropIfExists('fixtures');
    }
}
