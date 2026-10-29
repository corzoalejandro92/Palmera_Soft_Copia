<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnUnitDosesIdToDoesesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('doses', function (Blueprint $table) {
            $table->bigInteger('unit_dose_id')->unsigned()->nullable();
            $table->foreign('unit_dose_id')->on('unit_doses')->references('id')->onDelete('SET NULL')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('doses', function (Blueprint $table) {
            $table->dropForeign(['unit_dose_id']);
            $table->dropColumn(['unit_dose_id']);
        });
    }
}
