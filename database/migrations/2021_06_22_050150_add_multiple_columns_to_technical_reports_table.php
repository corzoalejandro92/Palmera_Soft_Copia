<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMultipleColumnsToTechnicalReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('technical_reports', function (Blueprint $table) {
            $table->bigInteger('user_coord_id')->nullable()->unsigned();
            $table->foreign('user_coord_id')->references('id')->on('user_coords')->onDelete('SET NULL')->onUpdate('CASCADE');
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
            $table->dropForeign(['user_coord_id']);
            $table->dropColumn('user_coord_id');
        });
    }
}
