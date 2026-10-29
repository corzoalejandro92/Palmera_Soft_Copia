<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiagnosisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('diagnostic_status', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->string('name');
            $table->boolean('enabled')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('diagnostics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('quote_id')->nullable()->unsigned();
            $table->foreign('quote_id')->references('id')->on('quotes')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->bigInteger('created_user_id')->nullable()->unsigned();
            $table->foreign('created_user_id')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->bigInteger('updated_user_id')->nullable()->unsigned();
            $table->foreign('updated_user_id')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->bigInteger('status_id')->nullable()->unsigned();
            $table->foreign('status_id')->references('id')->on('diagnostic_status')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->longText('header')->nullable();
            $table->longText('draft_plane')->nullable();
            $table->longText('real_infestations')->nullable();
            $table->longText('future_infestations')->nullable();
            $table->longText('control_methods')->nullable();
            $table->longText('recomendations')->nullable();
            $table->longText('identification')->nullable();
            $table->longText('footer')->nullable();
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
        Schema::dropIfExists('diagnosis');
        Schema::dropIfExists('diagnostic_status');
    }
}
