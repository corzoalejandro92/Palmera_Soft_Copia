<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMultipleColumnsToQrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('qrs', function (Blueprint $table) {
            $table->bigInteger('scan_by')->nullable()->unsigned();
            $table->foreign('scan_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('crm_id')->nullable()->unsigned();
            $table->foreign('crm_id')->references('id')->on('crms')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('office_id')->nullable()->unsigned();
            $table->foreign('office_id')->references('id')->on('offices')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('crm_schedule_id')->nullable()->unsigned();
            $table->foreign('crm_schedule_id')->references('id')->on('crm_schedules')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('scheduling_id')->nullable()->unsigned();
            $table->foreign('scheduling_id')->references('id')->on('schedulings')->onDelete('SET NULL')->onUpdate('CASCADE');
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
        Schema::table('qrs', function (Blueprint $table) {
            $table->dropForeign(['crm_id']);
            $table->dropColumn('crm_id');

            $table->dropForeign(['office_id']);
            $table->dropColumn('office_id');

            $table->dropForeign(['crm_schedule_id']);
            $table->dropColumn('crm_schedule_id');

            $table->dropForeign(['scheduling_id']);
            $table->dropColumn('scheduling_id');

            $table->dropForeign(['scan_by']);
            $table->dropColumn('scan_by');

            $table->dropSoftDeletes();

        });
    }
}
