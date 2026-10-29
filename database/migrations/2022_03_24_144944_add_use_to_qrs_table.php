<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUseToQrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('qrs', function (Blueprint $table) {
            $table->integer('use')->after('enabled')->default(1)->comment("El valor 1 para Tecnicos, El valor 2 para Administrativos");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('qrs', function (Blueprint $table) {
            $table->dropColumn('use');
        });
    }
}
