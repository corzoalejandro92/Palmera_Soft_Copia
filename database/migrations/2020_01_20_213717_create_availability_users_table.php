<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAvailabilityUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_days',function(Blueprint $table){
            $table->bigIncrements('id');
            $table->string('name');
            $table->boolean('enabled')->default();
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::create('availabilities', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->string('name');
            $table->boolean('holidays')->default(1)->nullable();
            $table->integer('headquarter_id')->nullable()->unsigned();
            $table->foreign('headquarter_id')->references('id')->on('headquarters')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->boolean('enabled')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::create('working_hours', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->bigInteger('availability_id')->unsigned()->nullable();
            $table->foreign('availability_id')->references('id')->on('availabilities')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->string('start');
            $table->string('end');
            $table->boolean('extra')->default(0);
            $table->timestamps();
        });
        
        Schema::create('availability_business_day',function(Blueprint $table){
            $table->bigIncrements('id');
            $table->bigInteger('availability_id')->unsigned()->nullable();
            $table->bigInteger('business_day_id')->unsigned()->nullable();
            $table->foreign('availability_id')->references('id')->on('availabilities')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->foreign('business_day_id')->references('id')->on('business_days')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->timestamps();
        });
        
        Schema::create('inability_days',function(Blueprint $table){
            $table->bigIncrements('id');
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->text('description');
            $table->bigInteger('created_by')->unsigned()->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->softDeletes();
            $table->timestamps(); 
        });
        
        Schema::create('inability_day_user',function(Blueprint $table){
            $table->bigIncrements('id');
            $table->bigInteger('inability_day_id')->unsigned()->nullable();
            $table->foreign('inability_day_id')->references('id')->on('inability_days')->onDelete('SET NULL')->onDelete('CASCADE');
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL')->onDelete('CASCADE');  
        });
        
        Schema::create('availability_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('availability_id')->unsigned()->nullable();
            $table->foreign('availability_id')->references('id')->on('availabilities')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('user_id')->unsigned()->nullable();
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
        Schema::dropIfExists('availability_users');
        Schema::dropIfExists('inability_day_user');
        Schema::dropIfExists('inability_days');
        Schema::dropIfExists('availability_business_day');
        Schema::dropIfExists('workday');
        Schema::dropIfExists('availabilities');
        Schema::dropIfExists('business_days'); 
        
    }
}
