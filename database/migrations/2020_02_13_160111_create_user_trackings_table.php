<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTrackingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_tracking_types', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->string('name');
            $table->boolean('disable_user')->default(false);
            $table->boolean('enabled')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('user_coords', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->nullable()->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->text('lat')->nullable();
            $table->text('lng')->nullable();
            $table->timestamp('date')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('user_trackings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->nullable()->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->bigInteger('user_coord_id')->nullable()->unsigned();
            $table->foreign('user_coord_id')->references('id')->on('user_coords')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->timestamp('date')->nullable();
            $table->bigInteger('user_tracking_type')->nullable()->unsigned();
            $table->foreign('user_tracking_type')->references('id')->on('user_tracking_types')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->morphs('trackable');
            $table->text('observation')->nullable();
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
        Schema::dropIfExists('user_trackings');
        Schema::dropIfExists('user_coords');
        Schema::dropIfExists('user_tracking_types');
    }
}
