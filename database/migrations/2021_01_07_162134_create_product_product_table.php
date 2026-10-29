<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_product', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('parent_product_id')->nullable()->unsigned();
            $table->foreign('parent_product_id')->references('id')->on('products')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('product_id')->nullable()->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('SET NULL')->onUpdate('CASCADE');
            
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
        Schema::dropIfExists('product_product');
    }
}
