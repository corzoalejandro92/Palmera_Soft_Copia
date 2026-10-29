<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddControlInfestationIdColumnToTechnicalReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('technical_reports', function (Blueprint $table) {
            $table->bigInteger('control_infestation_id')->unsigned()->nullable()->after('observation');
            $table->foreign('control_infestation_id')->references('id')->on('control_infestations')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->integer('quantity')->default(0)->after('control_infestation_id');
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
            $table->dropForeign(['control_infestation_id']);
            $table->dropColumn('control_infestation_id');
            $table->dropColumn('quantity');
        });
    }
}
