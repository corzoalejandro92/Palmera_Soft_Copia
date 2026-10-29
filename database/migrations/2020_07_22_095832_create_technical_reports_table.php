<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTechnicalReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('technical_report_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->integer('parent_id')->nullable()->unsigned();
            $table->foreign('parent_id')->references('id')->on('technical_report_types')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->boolean('picture_required')->default(0);
            $table->boolean('observation_required')->default(0);
            $table->boolean('enabled')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });


        Schema::create('technical_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->nullable()->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('file_extend_id')->nullable()->unsigned();
            $table->foreign('file_extend_id')->references('id')->on('file_extends')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->integer('technical_report_type_id')->nullable()->unsigned();
            $table->foreign('technical_report_type_id')->references('id')->on('technical_report_types')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->text('observation')->nullable();
            $table->timestamp('timestamp')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('technical_report_pictures', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->bigInteger('technical_report_id')->nullable()->unsigned();
            $table->foreign('technical_report_id')->references('id')->on('technical_reports')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->text('picture')->nullable();
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
        Schema::dropIfExists('technical_report_pictures');
        Schema::dropIfExists('technical_reports');
        Schema::dropIfExists('technical_reports_types');
    }
}
