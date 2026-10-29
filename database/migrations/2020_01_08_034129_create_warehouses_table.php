<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cellars',function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->text('address');
            $table->string('phone')->nullable();
            $table->boolean('enabled')->default(1);
            $table->softDeletes();
            $table->timestamps();
            
        });
        
        
        Schema::create('warehouses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->nullableMorphs('warehousable');
            $table->bigInteger('parent')->nullable()->unsigned();
            $table->foreign('parent')->references('id')->on('warehouses')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->json('buttons')->nullable();
            $table->string('icon')->nullable();
            $table->boolean('action')->default(1);
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
        Schema::dropIfExists('cellars');
        Schema::dropIfExists('warehouses');
    }
}
