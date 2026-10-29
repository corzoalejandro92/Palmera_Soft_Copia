<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('countries', function (Blueprint $table) {
           $table->engine = 'InnoDB';
           $table->increments('id');
           $table->string('code');
           $table->string('name');
           $table->integer('phonecode');
           $table->timestamps();
        });
        
        Schema::create('states', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('country_id')->nullable()->unsigned();
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->timestamps();
        });
        
        Schema::create('cities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');  
            $table->integer('state_id')->nullable()->unsigned();
            $table->foreign('state_id')->references('id')->on('states')->onDelete('SET NULL')->onUpdate('CASCADE');
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
        Schema::dropIfExists('countries');
        Schema::dropIfExists('states');
        Schema::dropIfExists('cities');
    }
}
