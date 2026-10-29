<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfigurarionHeadquarterFoxconsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configurarion_headquarter_foxcons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('warehouse_id')->unsigned();
            $table->bigInteger('document_type_id')->unsigned();
            $table->string('nomenclature');
            $table->string('consecutive');
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
            $table->foreign('document_type_id')->references('id')->on('document_type_foxcons');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('configurarion_headquarter_foxcons');
    }
}
