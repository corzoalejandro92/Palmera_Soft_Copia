<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHeadquartersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('headquarters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('city_id')->unsigned()->nullable();
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->text('address')->nullable();
            $table->text('lat')->nullable();
            $table->text('lng')->nullable();
            $table->string('phone',50)->nullable();
            $table->boolean('enabled')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('zones',function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('headquarter_id')->unsigned()->nullable();
            $table->foreign('headquarter_id')->references('id')->on('headquarters')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->text('lat')->nullable();
            $table->text('lng')->nullable();
            $table->integer('radius')->nullable();
            $table->boolean('enabled')->default(1);
            $table->softDeletes();
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
        Schema::dropIfExists('headquarters');
        Schema::dropIfExists('zones');
        
    }
}
