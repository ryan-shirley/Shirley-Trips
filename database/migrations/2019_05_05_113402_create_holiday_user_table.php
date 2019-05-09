<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHolidayUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('holiday_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->bigInteger('holiday_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('holiday_id')->references('id')->on('holidays')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users');

            $table->boolean('editPermission')->default(false);
            $table->boolean('owner')->default(false);

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
        Schema::dropIfExists('holiday_user');
    }
}
