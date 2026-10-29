<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveInnecesaryColumnsToConsumptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('consumptions', function (Blueprint $table) {

            $table->dropForeign(['warehouse_id']);
            $table->dropColumn('warehouse_id');

            $table->dropForeign(['control_id']);
            $table->dropColumn('control_id');

            $table->dropForeign(['product_id']);
            $table->dropColumn('product_id');

            $table->dropColumn('rsa');
            $table->dropColumn('plot');
            $table->dropColumn('expiration_date');

            $table->dropForeign(['unit_id']);
        
            $table->dropColumn('dose');
            $table->dropColumn('consumption');
            $table->dropColumn('unit_id');

            $table->bigInteger('inventory_detail_id')->nullable()->unsigned();
            $table->foreign('inventory_detail_id')->references('id')->on('inventory_details')->onDelete('SET NULL')->onUpdate('CASCADE');
      
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('consumptions', function (Blueprint $table) {

            $table->bigInteger('control_id')->nullable()->unsigned();
            $table->foreign('control_id')->references('id')->on('crm_schedule_controls')->onDelete('SET NULL')->onUpdate('CASCADE');

            $table->bigInteger('product_id')->nullable()->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('SET NULL')->onUpdate('CASCADE');



            $table->string('rsa')->nullable();
            $table->string('plot')->nullable();
            $table->date('expiration_date')->nullable();

            $table->double('dose')->after('quantity')->nullable()->default(0);
            $table->double('consumption')->nullable()->default(0);

            $table->bigInteger('unit_id')->nullable()->unsigned();
            $table->foreign('unit_id')->references('id')->on('units')->onUpdate('CASCADE');
            
            $table->bigInteger('warehouse_id')->nullable()->unsigned();
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('SET NULL')->onUpdate('CASCADE');

            $table->dropForeign(['inventory_detail_id']);
            $table->dropColumn('inventory_detail_id');
        });
    }
}
