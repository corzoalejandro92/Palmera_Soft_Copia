<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeneralOrderPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('general_order_purchases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('number_order_purchase')->unsigned();
            $table->integer('headquarter_id')->nullable()->unsigned();
            $table->foreign('headquarter_id')->references('id')->on('headquarters')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->timestamp('date_order')->nullable();
            $table->string('observation')->nullable();
            $table->softDeletes();
            $table->boolean('enabled')->default(1);
            $table->timestamps();
        });

        Schema::create('detail_general_order_purchases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('general_order_purchase_id')->nullable()->unsigned();
            $table->foreign('general_order_purchase_id')->references('id')->on('general_order_purchases')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('product_id')->unsigned()->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->integer('quantity')->nullable();
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
        Schema::dropIfExists('general_order_purchases');
        Schema::dropIfExists('detail_general_order_purchases');
    }
}
