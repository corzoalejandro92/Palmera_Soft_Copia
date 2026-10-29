<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('file_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('slug');
            $table->boolean('enabled')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });
        
        Schema::create('files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->nullableMorphs('filable');
            $table->text('path');
            $table->string('name',250);
            $table->bigInteger('type_id')->nullable()->unsigned();
            $table->foreign('type_id')->references('id')->on('file_types')->onDelete('SET NULL')->onDelete('CASCADE');
            $table->string('observation')->nullable();
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
        Schema::dropIfExists('files');
        Schema::dropIfExists('file_types');
        
    }
}
