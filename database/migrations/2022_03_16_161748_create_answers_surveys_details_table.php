<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswersSurveysDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answers_surveys_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('answers_surveys_id')->nullable()->unsigned();
            $table->foreign('answers_surveys_id')->references('id')->on('answers_surveys')->onDelete('SET NULL');
            $table->bigInteger('question_id')->nullable()->unsigned();
            $table->foreign('question_id')->references('id')->on('questions')->onDelete('SET NULL');
            $table->bigInteger('answer_id')->nullable()->unsigned();
            $table->foreign('answer_id')->references('id')->on('answers')->onDelete('SET NULL');
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
        Schema::dropIfExists('answers_surveys_details');
    }
}
