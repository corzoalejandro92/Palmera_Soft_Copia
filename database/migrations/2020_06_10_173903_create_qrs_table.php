<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('paper_sizes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->boolean('enabled')->default(1);
            $table->timestamps();
        });

        Schema::create('qr_lots', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('paper_size_id')->unsigned()->nullable();
            $table->foreign('paper_size_id')->references('id')->on('paper_sizes')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->string('orientation')->default('portrait');
            $table->integer('headquarter_id')->nullable()->unsigned();
            $table->foreign('headquarter_id')->references('id')->on('headquarters')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->timestamps();
        });

        Schema::create('qrs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('qr_lot_id')->unsigned()->nullable();
            $table->foreign('qr_lot_id')->references('id')->on('qr_lots')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('file_extend_id')->unsigned()->nullable();
            $table->foreign('file_extend_id')->references('id')->on('file_extends')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->string('token')->unique();
            $table->boolean('enabled')->default(1);
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
        Schema::dropIfExists('qrs');
        Schema::dropIfExists('qrs_lots');
        Schema::dropIfExists('paper_sizes');
    }
}
