<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFactorColumnToFoxconDocumentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('foxcon_document_details', function (Blueprint $table) {
            $table->decimal('factor', 10, 2)->nullable()->after('cantidad');
            $table->string('unidad', 10)->nullable()->after('factor');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('foxcon_document_details', function (Blueprint $table) {
            $table->dropColumn('factor');
            $table->dropColumn('unidad');
        });
    }
}
