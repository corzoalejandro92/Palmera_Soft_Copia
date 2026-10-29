<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnCrmScheduleIdToSchedulingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schedulings', function (Blueprint $table) {

            $table->dropForeign(['crm_schedule_control_id']);
            $table->dropColumn(['crm_schedule_control_id']);

            $table->bigInteger('crm_schedule_id')->unsigned()->nullable();
            $table->foreign('crm_schedule_id')->references('id')->on('crm_schedules')->onDelete('SET NULL')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('schedulings', function (Blueprint $table) {
            $table->dropForeign(['crm_schedule_id']);
            $table->dropColumn(['crm_schedule_id']);

            $table->bigInteger('crm_schedule_control_id')->unsigned()->nullable();
            $table->foreign('crm_schedule_control_id')->references('id')->on('crm_schedule_controls')->onDelete('SET NULL')->onUpdate('CASCADE');
        });
    }
}
