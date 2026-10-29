<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFoxconDocumentIdColumnToInvetoryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventory_details', function (Blueprint $table) {
            $table->bigInteger('foxcon_document_id')->nullable()->after('plot');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventory_details', function (Blueprint $table) {
            $table->dropColumn('foxcon_document_id');
        });
    }
}
