<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConvectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('convections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('slug',15);
            $table->string('avatar',200)->nullable();
            $table->integer('headquarter_id')->nullable()->unsigned();
            $table->foreign('headquarter_id')->references('id')->on('headquarters')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('updated_user')->nullable()->unsigned();
            $table->foreign('updated_user')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->boolean('enabled')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('convection_product', function(Blueprint $table){  
            $table->bigIncrements('id');
            $table->bigInteger('convection_id')->unsigned()->nullable();
            $table->foreign('convection_id')->on('convections')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->bigInteger('product_id')->unsigned()->nullable();
            $table->foreign('product_id')->on('products')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->integer('quantity')->default(1);
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
        Schema::dropIfExists('convection_product');
        Schema::dropIfExists('convection');
    }
}
