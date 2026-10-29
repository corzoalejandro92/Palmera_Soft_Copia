<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMultiplesColumnsToCrmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('crms', function (Blueprint $table) {
            $table->string('ei_email',200)->nullable()->comment('Correo para la facturación electrónica');
            $table->string('commercial_name',500)->after('name')->nullable()->comment('nombre comercial diferente de la razon social del cliente');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('crms', function (Blueprint $table) {
            $table->dropColumn('ei_email');
            $table->dropColumn('commercial_name');
        });
    }
}
