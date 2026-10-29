<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInfestationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('infestations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('avatar',200)->nullable();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->boolean('enabled')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('infestation_service', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('service_id')->nullable()->unsigned();
            $table->foreign('service_id')->references('id')->on('services')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('infestation_id')->nullable()->unsigned();
            $table->foreign('infestation_id')->references('id')->on('infestations')->onDelete('SET NULL')->onUpdate('CASCADE');
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
        Schema::dropIfExists('infestations');
    }
}
