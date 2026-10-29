<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OfficeUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('office_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->nullable()->unsigned();
            $table->foreign('user_id')->on('users')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->bigInteger('office_id')->nullable()->unsigned();
            $table->foreign('office_id')->on('offices')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
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
        Schema::dropIfExists('office_user');    
    }
}
