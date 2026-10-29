<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMultiplesColumnsToSchConPro extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('crm_schedule_controls', function (Blueprint $table){
            $table->integer('status_id')->unsigned()->nullable();
            $table->foreign('status_id')->on('crm_schedule_status')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->integer('visits')->default(1);
            $table->integer('periodicity_id')->unsigned()->nullable();
            $table->foreign('periodicity_id')->on('periodicities')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->integer('time_value')->nullable();
            $table->integer('time_unit_id')->nullable()->unsigned();
            $table->foreign('time_unit_id')->on('time_units')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->date('date')->nullable();
            $table->text('observation')->nullable();
        }); 

        Schema::table('quote_service_infestations', function (Blueprint $table){
            $table->integer('visits')->default(1);
            $table->integer('periodicity_id')->unsigned()->nullable();
            $table->foreign('periodicity_id')->on('periodicities')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->integer('time_value')->nullable();
            $table->integer('time_unit_id')->nullable()->unsigned();
            $table->foreign('time_unit_id')->on('time_units')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->timestamp('possible_date')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->text('observation')->nullable();
        }); 


        Schema::table('quote_service_infestation_products', function (Blueprint $table) {
            $table->dropMorphs('productable','acqsiproducts_id');
            $table->bigInteger('crm_area_id')->unsigned()->nullable();
            $table->foreign('crm_area_id')->on('crm_areas')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->bigInteger('product_id')->unsigned()->nullable();
            $table->foreign('product_id')->on('products')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->bigInteger('application_id')->unsigned()->nullable();
            $table->foreign('application_id')->on('applications')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
        });

        Schema::table('crm_sch_con_pro', function (Blueprint $table) {
            $table->dropMorphs('productable','acqsiproducts_id');
            $table->bigInteger('crm_area_id')->unsigned()->nullable();
            $table->foreign('crm_area_id')->on('crm_areas')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->bigInteger('product_id')->unsigned()->nullable();
            $table->foreign('product_id')->on('products')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->bigInteger('application_id')->unsigned()->nullable();
            $table->foreign('application_id')->on('applications')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
        });

        Schema::table('crm_schedules', function (Blueprint $table) {
            $table->dropForeign(['time_unit_id']);
            $table->dropForeign(['status_id']);
            $table->dropColumn(['time_unit_id','time_value','observation','status_id','date','allow_automation']);
        }); 
   
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sch_con_pro', function (Blueprint $table) {
            $table->nullableMorphs('productable','acqsiproducts_id');
        });

    }
}
