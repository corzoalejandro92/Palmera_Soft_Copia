<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMultipleColumnToInventoryExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventory_expenses', function (Blueprint $table) {
            $table->string('health_register',150)->nullable()->after('quantity');
            $table->string('product_report',150)->nullable()->after('health_register');
            $table->string('dose_quantity',20)->nullable()->after('product_report');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventory_expenses', function (Blueprint $table) {
            $table->dropColumn('health_register');
            $table->dropColumn('product_report');
            $table->dropColumn('dose_quantity');
        });
    }
}
