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
            $table->id();
            $table->string('name');
            $table->string('user_name')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('contact_no')->unique();
            $table->string('bank_name')->nullable();
            $table->string('acc_no')->nullable();
            $table->string('ifsc')->nullable();
            $table->string('upi_type')->nullable();
            $table->string('upi_no')->nullable();
            $table->string('status')->nullable(); //user status
            $table->integer('points')->nullable();
            $table->string('bookie_id'); //bookie or agent
            $table->unsignedBigInteger('rate_id');
            $table->string('role');

            $table->timestamps();

            $table->foreign('rate_id')->references('id')->on('bookie_rates');
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
