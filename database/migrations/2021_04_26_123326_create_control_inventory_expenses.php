<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateControlInventoryExpenses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('control_inventory_expenses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('inventory_detail_id')->nullable()->unsigned();
            $table->foreign('inventory_detail_id','cie_ide_id')->on('inventory_details')->references('id')->onDelete('SET NULL')->onUpdate('SET NULL');
            $table->bigInteger('crm_schedule_control_id')->nullable()->unsigned();
            $table->foreign('crm_schedule_control_id','cie_csc_id')->on('crm_schedule_controls')->references('id')->onDelete('SET NULL')->onUpdate('SET NULL');
            $table->bigInteger('warehouse_id')->unsigned()->nullable();
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('product_id')->unsigned()->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->string('code')->nullable();
            $table->string('plot')->nullable();
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
        Schema::dropIfExists('control_inventory_expenses');
    }
}
