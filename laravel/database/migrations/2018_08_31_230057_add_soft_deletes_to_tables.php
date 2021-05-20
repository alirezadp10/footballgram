<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoftDeletesToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('news', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('user_contents', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('users', function (Blueprint $table) {
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

        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });

        Schema::table('user_contents', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });

    }
}
