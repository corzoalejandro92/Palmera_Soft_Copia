<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParameterQuoteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parameter_quote_services', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('parameter_service_id')->nullable()->unsigned();
            $table->foreign('parameter_service_id','psqs1_id_foreign')->references('id')->on('parameter_services')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('quote_service_id')->nullable()->unsigned();
            $table->foreign('quote_service_id','psqs2_id_foreign')->references('id')->on('quote_services')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->decimal('price')->default(0);
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
        Schema::dropIfExists('parameter_quote_services');
    }
}
