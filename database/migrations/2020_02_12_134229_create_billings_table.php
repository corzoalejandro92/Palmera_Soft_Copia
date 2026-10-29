<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {



        Schema::create('billing_states', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->boolean('enabled')->default(1);
            $table->timestamps();
        });

        Schema::create('billings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('headquarter_id')->nullable()->unsigned();
            $table->foreign('headquarter_id')->references('id')->on('headquarters')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('crm_id')->unsigned()->nullable();
            $table->foreign('crm_id')->references('id')->on('crms')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('billing_state_id')->nullable()->unsigned();
            $table->foreign('billing_state_id')->references('id')->on('billing_states')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->integer('number_billing')->nullable();
            $table->timestamp('date_billing')->nullable();
            $table->decimal('total',18,0)->default(0);
            $table->boolean('enabled')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });



        Schema::create('billing_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('billing_id')->nullable()->unsigned();
            $table->foreign('billing_id')->references('id')->on('billings')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->string('name');
            $table->integer('quantity')->nullable();
            $table->decimal('valor',18,0)->default(0);
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
        Schema::dropIfExists('billing_states');
        Schema::dropIfExists('billings');
        Schema::dropIfExists('billing_details');

    }
}
