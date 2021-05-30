<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookieRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookie_rates', function (Blueprint $table) {
            $table->id();

            $table->string('single');
            $table->string('jodi');
            $table->string('single_patti');
            $table->string('double_patti');
            $table->string('tripple_patti');
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
        Schema::dropIfExists('bookie_rates');
    }
}
