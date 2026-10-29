<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('polls', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('observation')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('questions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('poll_id')->nullable()->unsigned();
            $table->foreign('poll_id')->references('id')->on('polls')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->string('question');
            $table->softDeletes();
            $table->timestamps();
        });


        Schema::create('answers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('question_id')->nullable()->unsigned();
            $table->foreign('question_id')->references('id')->on('questions')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->string('answer');
            $table->softDeletes();
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
        Schema::dropIfExists('polls');
        Schema::dropIfExists('answers');
        Schema::dropIfExists('questions');
    }
}
