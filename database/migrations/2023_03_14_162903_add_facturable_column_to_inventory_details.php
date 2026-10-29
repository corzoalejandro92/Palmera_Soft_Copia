<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFacturableColumnToInventoryDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventory_details', function (Blueprint $table) {
            $table->boolean('facturable')->default(0);
            $table->date('facturable_date')->nullable();
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
            $table->dropColumn('facturable');
            $table->dropColumn('facturable_date');
        });
    }
}
