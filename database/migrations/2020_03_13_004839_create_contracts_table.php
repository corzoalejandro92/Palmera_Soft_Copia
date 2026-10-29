<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->boolean('enabled')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('contracts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('number')->unsigned()->nullable();
            $table->bigInteger('crm_id')->unsigned()->nullable();
            $table->foreign('crm_id')->on('crms')->references('id')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('quote_id')->unsigned()->nullable();
            $table->foreign('quote_id')->on('quotes')->references('id')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->integer('status_id')->nullable()->unsigned();
            $table->foreign('status_id')->on('contract_statuses')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->integer('headquarter_id')->nullable()->unsigned();
            $table->foreign('headquarter_id')->references('id')->on('headquarters')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->on('users')->references('id')->onDelete('SET NULL')->onUpdate('CASCADE');
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

        Schema::dropIfExists('contract_statuses');
        Schema::dropIfExists('contracts');
    }
}
