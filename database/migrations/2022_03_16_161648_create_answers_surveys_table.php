<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswersSurveysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answers_surveys', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('headquarter_id')->unsigned()->nullable();
            $table->foreign('headquarter_id')->references('id')->on('headquarters')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('office_id')->unsigned()->nullable();
            $table->foreign('office_id')->references('id')->on('offices')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('crm_schedule_id')->unsigned()->nullable();
            $table->foreign('crm_schedule_id')->references('id')->on('crm_schedules')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('polls_id')->unsigned()->nullable();
            $table->foreign('polls_id')->references('id')->on('polls')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('CASCADE');

            $table->timestamps();
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
        Schema::dropIfExists('answers_surveys');
    }
}
