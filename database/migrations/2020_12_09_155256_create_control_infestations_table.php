<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateControlInfestationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('control_infestations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('avatar',200)->nullable();
            $table->longText('description')->nullable();
            $table->boolean('enabled')->default(1);

            $table->bigInteger('infestation_id')->nullable()->unsigned();
            $table->foreign('infestation_id')->references('id')->on('infestations')->onDelete('SET NULL')->onUpdate('CASCADE');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('control_infestations');
    }
}
