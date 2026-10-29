<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TruncateInventoryTables extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('detail_confirmation_transfers')->delete();
        DB::statement("ALTER TABLE `detail_confirmation_transfers` AUTO_INCREMENT = 1");

        DB::table('confirmation_transfer_inventories')->delete();
        DB::statement("ALTER TABLE `confirmation_transfer_inventories` AUTO_INCREMENT = 1");

        DB::table('detail_confirmation_transfers')->delete();
        DB::statement("ALTER TABLE `detail_confirmation_transfers` AUTO_INCREMENT = 1");

        DB::table('detail_general_order_purchases')->delete();
        DB::statement("ALTER TABLE `detail_general_order_purchases` AUTO_INCREMENT = 1");

        DB::table('order_purchase_details')->delete();
        DB::statement("ALTER TABLE `order_purchase_details` AUTO_INCREMENT = 1");

        DB::table('order_purchases')->truncate();
        DB::statement("ALTER TABLE `order_purchases` AUTO_INCREMENT = 1");
        
        DB::table('inventory_details')->truncate();
        DB::statement("ALTER TABLE `inventory_details` AUTO_INCREMENT = 1");
        
        DB::table('inventories')->truncate();
        DB::statement("ALTER TABLE `inventories` AUTO_INCREMENT = 1");

        DB::table('transfer_inventory_details')->truncate();
        DB::statement("ALTER TABLE `transfer_inventory_details` AUTO_INCREMENT = 1");

        DB::table('transfer_inventories')->truncate();
        DB::statement("ALTER TABLE `transfer_inventories` AUTO_INCREMENT = 1");

        DB::table('foxcon_document_details')->truncate();
        DB::statement("ALTER TABLE `foxcon_document_details` AUTO_INCREMENT = 1");

        DB::table('foxcon_documents')->truncate();
        DB::statement("ALTER TABLE `foxcon_documents` AUTO_INCREMENT = 1");
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
