<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDocumentFoxconIdToTransferInvetoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transfer_inventories', function (Blueprint $table) {
            $table->bigInteger('document_foxcon_id')->nullable()->unsigned();
            $table->foreign('document_foxcon_id')->references('id')->on('foxcon_documents')->onDelete('SET NULL')->onUpdate('CASCADE');
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
            $table->dropForeign(['document_foxcon_id']);
            $table->dropColumn('document_foxcon_id');
        });
    }
}
