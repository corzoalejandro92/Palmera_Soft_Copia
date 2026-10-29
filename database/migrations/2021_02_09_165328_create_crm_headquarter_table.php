<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCrmHeadquarterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_headquarter', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('crm_id')->unsigned()->nullable();
            $table->foreign('crm_id')->references('id')->on('crms')->onDelete('SET NULL')->onUpdate('CASCADE');
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
        Schema::dropIfExists('crm_headquarter');
    }
}
