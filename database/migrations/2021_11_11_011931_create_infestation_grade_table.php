<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInfestationGradeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('infestation_grades', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('crm_id')->nullable()->unsigned();
            $table->foreign('crm_id')->on('crms')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->bigInteger('office_id')->nullable()->unsigned();
            $table->foreign('office_id')->on('offices')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->bigInteger('crm_schedule_id')->nullable()->unsigned();
            $table->foreign('crm_schedule_id')->on('crm_schedules')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->bigInteger('crm_schedule_control_id')->nullable()->unsigned();
            $table->foreign('crm_schedule_control_id')->on('crm_schedule_controls')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->bigInteger('infestation_id')->nullable()->unsigned();
            $table->foreign('infestation_id')->on('infestations')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->bigInteger('control_infestation_id')->nullable()->unsigned();
            $table->foreign('control_infestation_id')->on('control_infestations')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->enum('value',[0,1,2,3,4])->comment('0 => Nulo, 1 => Bajo, 2 => Medio, 3 => Alto')->default(0);
            $table->bigInteger('user_id')->nullable()->unsigned();
            $table->foreign('user_id')->on('users')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->integer('headquarter_id')->nullable()->unsigned();
            $table->foreign('headquarter_id')->on('headquarters')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('infestation_grades');
    }
}
