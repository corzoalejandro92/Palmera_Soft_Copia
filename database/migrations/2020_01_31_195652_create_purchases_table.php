<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamp('date_shopping')->nullable();
            $table->string('code_foxcon')->nullable();
            $table->integer('headquarter_id')->nullable()->unsigned();
            $table->foreign('headquarter_id')->references('id')->on('headquarters')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('warehouse_id')->unsigned()->nullable();
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->string('observation')->nullable();
            $table->boolean('enabled')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('purchase_product_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('purchase_id')->nullable()->unsigned();
            $table->foreign('purchase_id')->references('id')->on('purchases')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('product_id')->unsigned()->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->string('code')->nullable();
            $table->string('plot')->nullable();
            $table->string('quantity')->nullable();
            $table->timestamp('date_expiration')->nullable();
            $table->string('stock')->nullable();
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
        Schema::dropIfExists('purchase_product_details');
        Schema::dropIfExists('purchases');
        
    }
}
