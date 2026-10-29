<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchedulingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scheduling_types', function(Blueprint $table){
            DB::statement('SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";');
            $table->increments('id');
            $table->string('name');
            $table->string('color',20)->nullable();
            $table->string('disabled_color',30)->nullable();
            $table->boolean('enabled')->default(1);
            $table->softDeletes();
            $table->timestamps();
            
        });
        
        Schema::create('schedulings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('scheduling_type_id')->nullable()->unsigned();
            $table->foreign('scheduling_type_id')->references('id')->on('scheduling_types')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('crm_id')->unsigned()->nullable();
            $table->foreign('crm_id')->references('id')->on('crms')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('office_id')->nullable()->unsigned();
            $table->foreign('office_id')->references('id')->on('offices')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('contact_id')->nullable()->unsigned();
            $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->text('crm_address')->nullable();
            $table->text('crm_lat')->nullable();
            $table->text('crm_lng')->nullable();
            $table->timestamp('start')->nullable();
            $table->timestamp('end')->nullable();
            $table->timestamp('automation_init_date')->nullable();
            $table->bigInteger('crm_schedule_control_id')->unsigned()->nullable();
            $table->foreign('crm_schedule_control_id')->references('id')->on('crm_schedule_controls')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->integer('headquarter_id')->nullable()->unsigned();
            $table->foreign('headquarter_id')->references('id')->on('headquarters')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->boolean('completed')->default(0);
            $table->boolean('enabled')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });
        
        Schema::create('scheduling_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('scheduling_id')->unsigned()->nullable();
            $table->foreign('scheduling_id')->references('id')->on('schedulings')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('scheduling_observations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('content');
            $table->bigInteger('scheduling_id')->unsigned()->nullable();
            $table->foreign('scheduling_id')->references('id')->on('schedulings')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('role_id')->unsigned()->nullable();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->softDeletes();
            $table->timestamps();
        });
        
        
        Schema::create('scheduling_tasks', function (Blueprint $table){
            $table->bigIncrements('id');
            $table->bigInteger('scheduling_id')->unsigned()->nullable();
            $table->foreign('scheduling_id')->references('id')->on('schedulings')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->text('description');
            $table->boolean('enabled')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('automations', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->timestamp('date')->nullable();
            $table->json('date_range')->nullable();
            $table->json('availabilities')->nullable();
            $table->json('users')->nullable();
            $table->json('services')->nullable();
            $table->json('services_config')->nullable();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->boolean('enabled')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('routes',function(Blueprint $table){
            $table->bigIncrements('id');
            $table->bigInteger('automation_id')->unsigned()->nullable();
            $table->foreign('automation_id')->references('id')->on('automations')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('user_id')->nullable()->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->boolean('sync')->default(0);
            $table->bigInteger('mail_queue')->nullable()->unsigned();
            $table->bigInteger('sms_queue')->nullable()->unsigned();
            $table->text('observation')->nullable();
            $table->bigInteger('observation_user_id')->unsigned()->nullable();
            $table->foreign('observation_user_id')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->integer('headquarter_id')->nullable()->unsigned();
            $table->foreign('headquarter_id')->references('id')->on('headquarters')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->timestamp('date')->nullable();
            $table->boolean('enabled')->default(1);
            $table->softDeletes();
            $table->timestamps();

        });

        Schema::create('route_schedulings',function(Blueprint $table){
            $table->bigIncrements('id');
            $table->bigInteger('route_id')->nullable()->unsigned();
            $table->foreign('route_id')->references('id')->on('routes')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('scheduling_id')->nullable()->unsigned();
            $table->foreign('scheduling_id')->references('id')->on('schedulings')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->boolean('sync')->default(0);
            $table->text('observation')->nullable();
            $table->bigInteger('observation_user_id')->unsigned()->nullable();
            $table->foreign('observation_user_id')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->boolean('enabled')->default(1);
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
        Schema::dropIfExists('route_schedulings');
        Schema::dropIfExists('routes');
        Schema::dropIfExists('automations');
        Schema::dropIfExists('scheduling_service_tasks');
        Schema::dropIfExists('scheduling_service_supplies');
        Schema::dropIfExists('scheduling_service_observations');
        Schema::dropIfExists('scheduling_services');
        Schema::dropIfExists('scheduling_user');
        Schema::dropIfExists('schedulings');
        Schema::dropIfExists('scheduling_types');

    }
}
