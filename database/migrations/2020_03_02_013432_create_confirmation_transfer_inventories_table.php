<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfirmationTransferInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('confirmation_transfer_inventories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('headquarter_id')->nullable()->unsigned();
            $table->foreign('headquarter_id')->references('id')->on('headquarters')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->timestamp('date_transfer')->nullable();
            $table->integer('warehouse_exit')->nullable();
            $table->timestamp('date_confirmation')->nullable();
            $table->integer('warehouse_arrival')->nullable();
            $table->string('observation')->nullable();
            $table->boolean('enabled')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('detail_confirmation_transfers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('confirmation_transfer_id')->nullable()->unsigned();
            $table->foreign('confirmation_transfer_id')->references('id')->on('confirmation_transfer_inventories')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('product_id')->unsigned()->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->string('plot')->nullable();
            $table->string('foxcon')->nullable();
            $table->integer('quantity')->nullable();
            $table->boolean('approved')->default(1);
            $table->integer('quantity_received')->nullable();
            $table->timestamp('date_expiration')->nullable();
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
        Schema::dropIfExists('confirmation_transfer_inventories');
        Schema::dropIfExists('detail_confirmation_transfers');
    }
}
