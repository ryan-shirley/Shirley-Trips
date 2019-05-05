<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flights', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('airline_id')->unsigned();
            $table->foreign('airline_id')->references('id')->on('airlines');

            $table->string('flightNumber');
            $table->dateTime('originDayTime');
            $table->dateTime('destinationDayTime');
            $table->double('duration', 8, 2);
            $table->string('originAirportShort');
            $table->string('originAirportLong');
            $table->string('destinationAirportShort');
            $table->string('destinationAirportLong');

            $table->bigInteger('connectingFlightId')->unsigned()->nullable();
            $table->double('layoverLength')->nullable();

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
        Schema::dropIfExists('flights');
    }
}
