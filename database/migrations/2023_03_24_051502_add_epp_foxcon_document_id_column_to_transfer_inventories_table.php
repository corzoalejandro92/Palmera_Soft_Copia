<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEppFoxconDocumentIdColumnToTransferInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transfer_inventories', function (Blueprint $table) {
            $table->bigInteger('epp_document_foxcon_id')->nullable()->unsigned();
            $table->foreign('epp_document_foxcon_id')->references('id')->on('foxcon_documents')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transfer_inventories', function (Blueprint $table) {
            $table->dropForeign(['epp_document_foxcon_id']);
            $table->dropColumn(['epp_document_foxcon_id']);
        });
    }
}
