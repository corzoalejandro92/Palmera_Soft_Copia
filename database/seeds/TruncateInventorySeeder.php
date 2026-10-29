<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TruncateInventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('confirmation_transfer_inventories')->truncate();
        DB::table('transfer_inventory_details')->truncate();
        DB::table('transfer_inventories')->truncate();
        DB::table('inventory_expenses')->truncate();
        DB::table('inventory_details')->truncate();
        DB::table('inventories')->truncate();
        DB::table('purchases')->truncate();
        DB::table('purchase_product_details')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
