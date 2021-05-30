<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('token')->nullable();
            $table->text('description')->nullable();
            $table->string('gameName')->nullable();
            $table->string('gameType')->nullable();
            $table->string('otc')->nullable();
            $table->string('played_no')->nullable();
            $table->integer('points')->nullable();
            $table->integer('wonAmt')->nullable();
            $table->string('result')->nullable();
            $table->string('resultStatus')->nullable();
            $table->string('playHistory')->nullable();
            $table->integer('balance')->nullable();
            $table->string('type')->nullable();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('histories');
    }
}
