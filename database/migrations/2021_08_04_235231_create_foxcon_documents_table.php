<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFoxconDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('foxcon_documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('documento')->nullable();
            $table->string('numero')->nullable();
            $table->timestamp('timestamp')->nullable();
            $table->string('nit')->nullable();
            $table->string('sync')->default(0)->comment('0 => Sin sincronizar, 1 => Sincronizado');
            $table->integer('usuario')->nullable()->comment('Aqui debe ir el id de usuario de foxcon');
            $table->timestamp('grabacion')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['nit','documento','usuario']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('foxcon_documents');
    }
}
