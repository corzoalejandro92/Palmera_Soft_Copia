<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCodeColumnToInventoryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventory_details', function (Blueprint $table) {
            $table->string('code')->nullable(false)->default('N/A')->change();
        });

        Schema::table('inventory_expenses', function (Blueprint $table) {
            $table->string('code')->nullable(false)->default('N/A')->change();
        });

        Schema::table('transfer_inventory_details', function (Blueprint $table) {
            $table->string('foxcon')->nullable(false)->default('N/A')->change();
        });

        Schema::table('purchase_product_details', function (Blueprint $table) {
            $table->string('code')->nullable(false)->default('N/A')->change();
        });

        Schema::table('detail_confirmation_transfers', function (Blueprint $table) {
            $table->string('foxcon')->nullable(false)->default('N/A')->change();
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
            $table->string('code')->nullable(true)->change();
        });

        Schema::table('inventory_expenses', function (Blueprint $table) {
            $table->string('code')->nullable(true)->change();
        });

        Schema::table('transfer_inventory_details', function (Blueprint $table) {
            $table->string('foxcon')->nullable(true)->change();
        });


        Schema::table('purchase_product_details', function (Blueprint $table) {
            $table->string('code')->nullable(true)->change();
        });

        Schema::table('detail_confirmation_transfers', function (Blueprint $table) {
            $table->string('foxcon')->nullable(true)->change();
        });
    }
}
