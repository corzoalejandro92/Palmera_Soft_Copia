<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGiftInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gift_inventories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->nullable()->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('warehouse_id')->nullable()->unsigned();
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('product_id')->nullable()->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('document_foxcon_id')->nullable()->unsigned();
            $table->foreign('document_foxcon_id')->references('id')->on('foxcon_documents')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('configuracion_document_foxcon_id')->nullable()->unsigned();
            $table->foreign('configuracion_document_foxcon_id')->references('id')->on('configurarion_headquarter_foxcons')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('quantity')->nullable();
            $table->string('plot')->nullable();
            $table->text('observation')->nullable();
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
        Schema::dropIfExists('gift_inventories');
    }
}
