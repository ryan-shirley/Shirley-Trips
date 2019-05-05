<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('day_id')->unsigned();
            $table->foreign('day_id')->references('id')->on('days')->onDelete('cascade');

            $table->bigInteger('flight_id')->unsigned()->nullable();
            $table->foreign('flight_id')->references('id')->on('flights');

            $table->bigInteger('comment_id')->unsigned()->nullable();
            $table->foreign('comment_id')->references('id')->on('comments');

            $table->integer('order');

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
        Schema::dropIfExists('activities');
    }
}
