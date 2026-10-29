<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMechanismReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mechanism_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->nullable()->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('scheduling_id')->nullable()->unsigned();
            $table->foreign('scheduling_id')->references('id')->on('schedulings')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('file_extend_id')->nullable()->unsigned();
            $table->foreign('file_extend_id')->references('id')->on('file_extends')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->integer('technical_report_type_id')->nullable()->unsigned();
            $table->foreign('technical_report_type_id')->references('id')->on('technical_report_types')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->longText('observation')->nullable();
            $table->longText('file_path')->nullable();
            $table->integer('quantity')->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mechanism_reports');
    }
}
