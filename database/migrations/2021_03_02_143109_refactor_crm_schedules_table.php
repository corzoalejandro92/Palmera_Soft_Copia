<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RefactorCrmSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workschedules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('headquarter_id')->nullable()->unsigned();
            $table->foreign('headquarter_id')->references('id')->on('headquarters')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->string('name',400)->nullable();
            $table->timestamp('date')->nullable();
            $table->bigInteger('user_id')->nullable()->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->json('json')->nullable();
            $table->longText('html')->nullable();
            $table->bigInteger('quote_id')->nullable()->unsigned();
            $table->foreign('quote_id')->references('id')->on('quotes')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->bigInteger('crm_id')->nullable()->unsigned();
            $table->foreign('crm_id')->references('id')->on('crms')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->bigInteger('office_id')->nullable()->unsigned();
            $table->foreign('office_id')->references('id')->on('offices')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->json('services_data')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('crm_schedules', function (Blueprint $table) {

            //drop old fields
            // $table->dropForeign(['crm_id']);
            // $table->dropColumn('crm_id');
            // $table->dropForeign(['quote_id']);
            // $table->dropColumn('quote_id');
            // $table->dropForeign(['office_id']);
            // $table->dropColumn('office_id');
             $table->dropColumn('common_key');
            // $table->dropForeign(['headquarter_id']);
            // $table->dropColumn('headquarter_id');


            $table->bigInteger('workschedule_id')->nullable()->unsigned()->after('id');
            $table->foreign('workschedule_id')->references('id')->on('workschedules')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->string('coverage_text')->nullable()->after('coverage_id');
            $table->integer('periodicity_id')->nullable()->unsigned()->after('coverage_text');
            $table->foreign('periodicity_id')->references('id')->on('periodicities')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->date('periodicity_start')->nullable();
            $table->date('periodicity_end')->nullable();
            $table->integer('visits')->default(1);
            $table->json('weeks');
            $table->json('week_data');
            $table->integer('assigned_week')->nullable();
            $table->integer('status_id')->unsigned()->nullable();
            $table->foreign('status_id')->on('crm_schedule_status')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->date('date')->nullable();
            $table->integer('time_value')->nullable();
            $table->integer('time_unit_id')->nullable()->unsigned();
            $table->foreign('time_unit_id')->on('time_units')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->integer('start_day')->default(0);
            $table->longText('observation')->nullable();
        });

        Schema::table('crm_schedule_controls', function (Blueprint $table) {

            //drop old fields
            $table->dropColumn('visits');
            $table->dropForeign(['periodicity_id']);
            $table->dropColumn('periodicity_id');
            

            //create new fields
            $table->bigInteger('crm_area_id')->unsigned()->nullable()->after('infestation_id');
            $table->foreign('crm_area_id')->on('crm_areas')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->bigInteger('product_id')->unsigned()->nullable()->after('time_unit_id');
            $table->foreign('product_id')->on('products')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->integer('product_from')->nullable()->after('product_id');
            $table->integer('product_to')->nullable()->after('product_from');
            $table->bigInteger('dose_id')->nullable()->unsigned()->after('product_to');
            $table->foreign('dose_id')->on('doses')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->bigInteger('application_id')->unsigned()->nullable()->after('dose_id');
            $table->foreign('application_id')->on('applications')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('crm_schedule_controls', function (Blueprint $table) {

            //restore deleted fields
            $table->integer('visits')->default(1);
            $table->integer('periodicity_id')->unsigned()->nullable();
            $table->foreign('periodicity_id')->on('periodicities')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
            

            //drop the created fields
            $table->dropForeign(['crm_area_id']);
            $table->dropColumn('crm_area_id');
            $table->dropForeign(['product_id']);
            $table->dropColumn('product_id');
            $table->dropColumn('product_from');
            $table->dropColumn('product_to');
            $table->dropForeign(['application_id']);
            $table->dropColumn('application_id');
            $table->dropForeign(['dose_id']);
            $table->dropColumn('dose_id');
        });
        
        Schema::table('crm_schedules', function (Blueprint $table) {

            //restore deleted fields
            // $table->bigInteger('quote_id')->nullable()->unsigned();
            // $table->foreign('quote_id')->references('id')->on('quotes')->onUpdate('CASCADE')->onDelete('SET NULL');
            // $table->bigInteger('crm_id')->nullable()->unsigned();
            // $table->foreign('crm_id')->references('id')->on('crms')->onUpdate('CASCADE')->onDelete('SET NULL');
            // $table->bigInteger('office_id')->nullable()->unsigned();
            // $table->foreign('office_id')->references('id')->on('offices')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->string('common_key','10')->nullable();
            // $table->integer('headquarter_id')->nullable()->unsigned();
            // $table->foreign('headquarter_id')->references('id')->on('headquarters')->onDelete('SET NULL')->onUpdate('CASCADE');
            

            //drop the created fields
            $table->dropForeign(['workschedule_id']);
            $table->dropColumn('workschedule_id');
            $table->dropColumn('coverage_text');
            $table->dropForeign(['periodicity_id']);
            $table->dropColumn('periodicity_id');
            $table->dropColumn('periodicity_start');
            $table->dropColumn('periodicity_end');
            $table->dropColumn('visits');
            $table->dropColumn('weeks');
            $table->dropColumn('week_data');
        });
        Schema::dropIfExists('workschedules');
    }
    
}
