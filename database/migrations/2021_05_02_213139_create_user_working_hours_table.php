<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserWorkingHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_working_hours', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->nullable()->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('route_id')->nullable()->unsigned();
            $table->foreign('route_id')->references('id')->on('routes')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->decimal('ordinary',18,1)->default(0)->unsigned();
            $table->decimal('extra',18,1)->default(0)->unsigned();
            $table->decimal('total',18,1)->default(0)->unsigned();
            $table->enum('status',['estimated','pending','paid_out'])->default('estimated');
            $table->timestamp('pending_date')->nullable();
            $table->timestamp('paid_out_date')->nullable();
            $table->bigInteger('paid_out_by')->nullable()->unsigned();
            $table->foreign('paid_out_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->longText('observation')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });


        Schema::create('ava_use_wor_hou', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_working_hour_id')->nullable()->unsigned();
            $table->foreign('user_working_hour_id','uwh_avauseworhourid')->references('id')->on('user_working_hours')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('availability_id')->nullable()->unsigned();
            $table->foreign('availability_id','ava_avauseworhourid')->references('id')->on('availabilities')->onDelete('SET NULL')->onUpdate('CASCADE');
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
        Schema::dropIfExists('ava_use_wor_hou');
        Schema::dropIfExists('user_working_hours');
    }
}
