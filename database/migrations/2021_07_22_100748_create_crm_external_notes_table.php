<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCrmExternalNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_external_notes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('crm_id')->nullable()->unsigned();
            $table->foreign('crm_id')->references('id')->on('crms')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->bigInteger('office_id')->unsigned()->nullable();
            $table->foreign('office_id')->references('id')->on('offices')->onDelete('SET NULL')->onUpdate('CASCADE');  
            $table->bigInteger('user_id')->nullable()->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->date('date')->nullable();
            $table->enum('type',['1','2'])->comment('1=> Nota externa | 2=>Nota de factura')->default('1');
            $table->text('observation')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['type']);
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crm_external_notes');
    }
}
