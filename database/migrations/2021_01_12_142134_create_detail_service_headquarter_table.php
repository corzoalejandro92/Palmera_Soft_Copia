<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailServiceHeadquarterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_service_headquarter', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('detail_service_id')->nullable()->unsigned();
            $table->foreign('detail_service_id')->references('id')->on('detail_services')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->integer('headquarter_id')->nullable()->unsigned();
            $table->foreign('headquarter_id')->references('id')->on('headquarters')->onDelete('SET NULL')->onUpdate('CASCADE');
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
        Schema::dropIfExists('detail_service_headquarter');
    }
}
