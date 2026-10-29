<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFileExtendIdToTransferInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transfer_inventories', function (Blueprint $table) {
            $table->bigInteger('file_extend_id')->after('order_id')->unsigned()->nullable();
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
            $table->dropColumn('file_extend_id');
        });
    }
}
