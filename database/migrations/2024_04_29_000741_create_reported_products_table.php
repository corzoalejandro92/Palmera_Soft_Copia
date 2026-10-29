<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportedProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reported_products', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('user_id')->nullable()->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('crm_schedule_id')->nullable()->unsigned();
            $table->foreign('crm_schedule_id')->references('id')->on('crm_schedules')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('control_id')->nullable()->unsigned();
            $table->foreign('control_id')->references('id')->on('crm_schedule_controls')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('product_id')->nullable()->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->string('rsa')->nullable();
            $table->string('plot')->nullable();
            $table->date('expiration_date')->nullable();
            $table->double('quantity')->default(0);
            $table->double('dose')->nullable()->default(0);
            $table->double('prepared')->nullable()->default(0);
            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reported_products');
    }
}
