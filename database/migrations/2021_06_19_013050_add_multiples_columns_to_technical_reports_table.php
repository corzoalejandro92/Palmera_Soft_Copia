<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMultiplesColumnsToTechnicalReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('technical_reports', function (Blueprint $table) {

            $table->bigInteger('crm_schedule_id')->unsigned()->nullable();
            $table->foreign('crm_schedule_id')->references('id')->on('crm_schedules')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('crm_id')->unsigned()->nullable();
            $table->foreign('crm_id')->on('crms')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->bigInteger('office_id')->unsigned()->nullable();
            $table->foreign('office_id')->on('offices')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
  
            $table->bigInteger('service_id')->unsigned()->nullable();
            $table->foreign('service_id')->on('services')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->integer('headquarter_id')->after('user_id')->nullable()->unsigned();
            $table->foreign('headquarter_id')->references('id')->on('headquarters')->onDelete('SET NULL')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('technical_reports', function (Blueprint $table) {

            $table->dropForeign(['crm_schedule_id']);
            $table->dropColumn(['crm_schedule_id']);
            $table->dropForeign(['crm_id']);
            $table->dropColumn(['crm_id']);
            $table->dropForeign(['service_id']);
            $table->dropColumn(['service_id']);
            $table->dropForeign(['office_id']);
            $table->dropColumn(['office_id']);
            $table->dropForeign(['headquarter_id']);
            $table->dropColumn('headquarter_id');
        });
    }
}
