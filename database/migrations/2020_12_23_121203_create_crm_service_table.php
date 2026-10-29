<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCrmServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_service', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('crm_id')->nullable()->unsigned();
            $table->foreign('crm_id')->references('id')->on('crms')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('service_id')->nullable()->unsigned();
            $table->foreign('service_id')->references('id')->on('services')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->integer('technical_quantity')->nullable()->unsigned();

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
        Schema::dropIfExists('crm_service');
    }
}
