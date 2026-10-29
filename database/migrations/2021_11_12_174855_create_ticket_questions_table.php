<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_questions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('subject')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->text('message')->nullable();
            $table->unsignedBigInteger('read_by_id')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->unsignedBigInteger('registered_by_id')->nullable();
            $table->unsignedBigInteger('headquarter_id')->nullable();
            $table->timestamp('registered_at')->nullable();
            $table->boolean('solved')->nullable();
            $table->string('file')->nullable();
            $table->integer('count_answers')->nullable();
            $table->unsignedBigInteger('priority_id')->nullable();
            $table->unsignedBigInteger('office_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('category_id')->references('id')->on('category_tickets');
            $table->foreign('read_by_id')->references('id')->on('users');
            $table->foreign('registered_by_id')->references('id')->on('users');
            $table->foreign('priority_id')->references('id')->on('priority_tickets');
            $table->foreign('office_id')->references('id')->on('offices');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ticket_questions');
    }
}
