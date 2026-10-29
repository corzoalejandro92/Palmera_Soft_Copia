<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFileProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_extends', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('file_id')->unsigned()->nullable();
            $table->foreign('file_id')->on('files')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->nullableMorphs('extendible');
            $table->float('x')->nullable();
            $table->float('y')->nullable();
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
        Schema::dropIfExists('file_extends');
    }
}
