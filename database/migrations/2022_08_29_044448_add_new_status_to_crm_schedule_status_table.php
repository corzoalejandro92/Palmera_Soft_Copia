<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

class AddNewStatusToCrmScheduleStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('crm_schedule_status', function (Blueprint $table) {
            //
        });

        Artisan::call('db:seed',['--class'=>'NewStatusCrmSchedule']);

        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('crm_schedule_status', function (Blueprint $table) {
            //
        });
    }
}
