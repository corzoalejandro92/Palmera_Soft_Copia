<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_expenses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('inventory_detail_id')->nullable()->unsigned();
            $table->foreign('inventory_detail_id')->references('id')->on('inventory_details')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('warehouse_id')->unsigned()->nullable();
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('product_id')->unsigned()->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('control_id')->unsigned()->nullable();
            $table->foreign('control_id')->references('id')->on('crm_schedule_controls')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('crm_schedule_id')->unsigned()->nullable();
            $table->foreign('crm_schedule_id')->references('id')->on('crm_schedules')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('foxcon_document_id')->unsigned()->nullable();
            $table->foreign('foxcon_document_id')->references('id')->on('foxcon_documents')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->integer('headquarter_id')->nullable()->unsigned();
            $table->foreign('headquarter_id')->references('id')->on('headquarters')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('user_id')->nullable()->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->string('code')->nullable();
            $table->string('plot')->nullable();
            $table->timestamp('date_expiration')->nullable();
            $table->string('quantity')->nullable();
            $table->boolean('enabled')->default(1);
            $table->timestamp('date')->nullable()->comment('Esta es la fecha real de creación del registro desde la app del técnico');
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
        Schema::dropIfExists('inventory_expenses');
    }
}
