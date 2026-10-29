<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

class AlterRecordsToTechnicalReportsTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('technical_reports_types', function (Blueprint $table) {
            //
        });

        Artisan::call('db:seed',['--class'=>'NewTechnicalReportTypeSedder']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('technical_reports_types', function (Blueprint $table) {
            //
        });
    }
}
