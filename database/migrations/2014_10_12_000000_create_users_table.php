<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('position')->nullable();
            $table->string('document',20)->nullable();
            $table->bigInteger('phone')->nullable();
            $table->string('email');
            $table->string('password');
            $table->string('avatar',200)->nullable();
            $table->string('signature',200)->nullable();
            $table->rememberToken();
            $table->integer('headquarter_id')->nullable()->unsigned();
            $table->foreign('headquarter_id')->references('id')->on('headquarters')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->boolean('enabled')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });
        

        Schema::create('user_zone', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->nullable()->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->Integer('zone_id')->nullable()->unsigned();
            $table->foreign('zone_id')->references('id')->on('zones')->onDelete('SET NULL')->onUpdate('CASCADE');
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
        Schema::dropIfExists('user_zone');
        Schema::dropIfExists('users');
        
    }
}
