<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCrmSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_schedule_status', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->nullable();
            $table->string('name');
            $table->string('color')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('crm_schedules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('crm_id')->unsigned()->nullable();
            $table->foreign('crm_id')->on('crms')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->bigInteger('office_id')->unsigned()->nullable();
            $table->foreign('office_id')->on('offices')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->bigInteger('quote_id')->unsigned()->nullable();
            $table->foreign('quote_id')->references('id')->on('quotes')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('service_id')->unsigned()->nullable();
            $table->foreign('service_id')->on('services')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->bigInteger('coverage_id')->unsigned()->nullable();
            $table->foreign('coverage_id')->on('detail_services')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->date('date')->nullable();
            $table->integer('status_id')->unsigned()->nullable();
            $table->foreign('status_id')->on('crm_schedule_status')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->decimal('price',18,0)->default(0);
            $table->integer('time_value')->nullable();
            $table->integer('time_unit_id')->nullable()->unsigned();
            $table->foreign('time_unit_id')->on('time_units')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->boolean('allow_automation')->default(1);
            $table->string('common_key','10')->nullable();
            $table->text('observation')->nullable();
            $table->integer('headquarter_id')->nullable()->unsigned();
            $table->foreign('headquarter_id')->references('id')->on('headquarters')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->boolean('enabled')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('crm_schedule_controls', function (Blueprint $table){
            $table->bigIncrements('id');
            $table->bigInteger('crm_schedule_id')->unsigned()->nullable();
            $table->foreign('crm_schedule_id')->on('crm_schedules')->references('id')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('infestation_id')->unsigned()->nullable();
            $table->foreign('infestation_id')->references('id')->on('infestations')->onDelete('SET NULL')->onUpdate('CASCADE');
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
        Schema::dropIfExists('crm_schedule_status');
        Schema::dropIfExists('crm_schedule_controls');
        Schema::dropIfExists('crm_schedules');
    }
}
