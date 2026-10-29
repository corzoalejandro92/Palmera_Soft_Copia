<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAnuladoColumnToFoxconDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('foxcon_documents', function (Blueprint $table) {
            $table->boolean('anulado')->default(0)->after('sync');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('foxcon_documents', function (Blueprint $table) {
            $table->dropColumn('anulado');
        });
    }
}
