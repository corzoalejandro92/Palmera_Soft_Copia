<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransferInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfer_inventories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamp('date_transfer')->nullable();
            $table->integer('headquarter_id')->nullable()->unsigned();
            $table->foreign('headquarter_id')->references('id')->on('headquarters')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('warehouse_id')->unsigned()->nullable();
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->integer('warehouse_transfer')->nullable();
            $table->integer('order_id')->nullable();
            $table->string('observation')->nullable();
            $table->boolean('enabled')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('transfer_inventory_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('transfer_inventory_id')->nullable()->unsigned();
            $table->foreign('transfer_inventory_id')->references('id')->on('transfer_inventories')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('product_id')->unsigned()->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->boolean('rental')->nullable();
            $table->double('cost')->nullable(); 
            $table->string('plot')->nullable();
            $table->string('foxcon')->nullable();
            $table->integer('quantity')->nullable();
            $table->integer('transfer_quantity')->nullable();
            $table->timestamp('date_expiration')->nullable();
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
        Schema::dropIfExists('transfer_inventories');
        Schema::dropIfExists('transfer_inventory_details');
    }
}
