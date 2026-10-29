<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("name");
            $table->string("avatar");
            $table->longText("description")->nullable();
            $table->boolean('enabled')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('detail_services', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('service_id')->nullable()->unsigned();
            $table->foreign('service_id')->references('id')->on('services')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->integer('headquarter_id')->nullable()->unsigned();
            $table->foreign('headquarter_id')->references('id')->on('headquarters')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->string('zone')->nullable();
            $table->double('cost')->nullable();
            $table->integer('length')->nullable();
            $table->integer('categoryTime')->nullable();
            $table->longText("description")->nullable();
            $table->boolean('enabled')->default(1);
            $table->timestamps();
        });

        Schema::create('service_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('service_id')->nullable()->unsigned();
            $table->foreign('service_id')->references('id')->on('services')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('user_id')->nullable()->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('CASCADE');
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
        Schema::dropIfExists('service_user');
        Schema::dropIfExists('services');
        Schema::dropIfExists('detail_services');
    }
}
