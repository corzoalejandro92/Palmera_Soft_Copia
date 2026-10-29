<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFoxconDocumentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('foxcon_document_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('foxcon_document_id')->unsigned()->nullable();
            $table->foreign('foxcon_document_id')->on('foxcon_documents')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->string('documento',3)->nullable();
            $table->string('numero',6)->nullable();
            $table->timestamp('timestamp')->nullable();
            $table->string('bodega',4)->nullable()->comment('Este es el id de la bodega del sistema foxcon');
            $table->string('producto',6)->nullable()->comment('Este es el id del producto del sistema foxcon');
            $table->decimal('cantidad',16,2)->nullable();
            $table->string('nit',20)->nullable();
            $table->enum('tipoes',['E','S'])->default('E')->comment('E => Entrada, S => Salida');
            $table->timestamps();
            $table->index(['tipoes']);
            $table->index(['nit','documento']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('foxcon_document_details');
    }
}
