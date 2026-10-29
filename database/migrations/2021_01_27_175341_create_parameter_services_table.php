<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParameterServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('parameter_services', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('service_id')->nullable()->unsigned();
            $table->foreign('service_id')->references('id')->on('services')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->string('item')->nullable();
            $table->double('cost')->nullable();
            $table->longText("description")->nullable();
            $table->boolean('enabled')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('headquarter_parameter_service', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('parameter_service_id')->nullable()->unsigned();
            $table->foreign('parameter_service_id')->references('id')->on('parameter_services')->onDelete('SET NULL')->onUpdate('CASCADE');
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
        Schema::dropIfExists('headquarter_parameter_service');
        Schema::dropIfExists('parameter_services');
    }
}
