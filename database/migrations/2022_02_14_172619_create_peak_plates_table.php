<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeakPlatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('peak_plates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('city_id')->unsigned();
            $table->integer('states_id')->unsigned();
            $table->bigInteger('condition_id')->unsigned();
            $table->integer('headquarter_id')->unsigned();
            $table->bigInteger('vehicle_type_id')->unsigned();
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('digits');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('city_id')->references('id')->on('cities');
            $table->foreign('states_id')->references('id')->on('states');
            $table->foreign('condition_id')->references('id')->on('conditions_peak_plates');
            $table->foreign('headquarter_id')->references('id')->on('headquarters');
            $table->foreign('vehicle_type_id')->references('id')->on('vehicle_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('peak_plates');
    }
}
