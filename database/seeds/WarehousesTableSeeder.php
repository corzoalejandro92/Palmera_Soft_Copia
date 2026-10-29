<?php

use Illuminate\Database\Seeder;
use App\Warehouse;

class WarehousesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      
        
        Warehouse::create(
        [
            "name"      => "Bodega Nacional",
            "buttons"   => ['add' => false,'inventory' => true,  'buy'=> true, 'conf'=> false, 'delete' =>false, 'edit'=> false, 'transf' => true],
            "icon"      => "main",
            "action"    => true
        ]
            
        );
        
        Warehouse::create(
        [
            "name"      => "Bucaramanga",
            "parent"    => 1,
            "warehousable_id" => 1,
            "warehousable_type" => 'App\Headquarter',
            "buttons"   => ['add' => true,'inventory' => true, 'buy'=> false, 'conf'=> true, 'delete' =>false, 'edit'=> false, 'transf' => true],
            "icon"      => "headquarter",
            "action"    => true
        ]  );

        Warehouse::create(
            [
                "name"      => "Bogotá",
                "parent"    => 1,
                "warehousable_id" => 2,
                "warehousable_type" => 'App\Headquarter',
                "buttons"   => ['add' => true,'inventory' => true, 'buy'=> false, 'conf'=> true, 'delete' =>false, 'edit'=> false, 'transf' => true],
                "icon"      => "headquarter",
                "action"    => true
            ]);
        
        Warehouse::create(
        [
            "name"      => "Medellín",
            "parent"    => 1,
            "warehousable_id" => 3,
            "warehousable_type" => 'App\Headquarter',
            "buttons"   => ['add' => true,'inventory' => true, 'buy'=> false, 'conf'=> true, 'delete' =>false, 'edit'=> false, 'transf' => true],
            "icon"      => "headquarter",
            "action"    => true
        ]);

        Warehouse::create(
        [
            "name"      => "Cali",
            "parent"    => 1,
            "warehousable_id" => 4,
            "warehousable_type" => 'App\Headquarter',
            "buttons"   => ['add' => true,'inventory' => true, 'buy'=> false, 'conf'=> true, 'delete' =>false, 'edit'=> false, 'transf' => true],
            "icon"      => "headquarter",
            "action"    => true
        ]);


            Warehouse::create(
                [
                    "name"      => "Barranquilla",
                    "parent"    => 1,
                    "warehousable_id" => 5,
                    "warehousable_type" => 'App\Headquarter',
                    "buttons"   => ['add' => true,'inventory' => true, 'buy'=> false, 'conf'=> true, 'delete' =>false, 'edit'=> false, 'transf' => true],
                    "icon"      => "headquarter",
                    "action"    => true
                ]);

                Warehouse::create(
                    [
                        "name"      => "Barrancabermeja",
                        "parent"    => 1,
                        "warehousable_id" => 6,
                        "warehousable_type" => 'App\Headquarter',
                        "buttons"   => ['add' => true,'inventory' => true, 'buy'=> false, 'conf'=> true, 'delete' =>false, 'edit'=> false, 'transf' => true],
                        "icon"      => "headquarter",
                        "action"    => true
                    ]);
            
            Warehouse::create(
                    [
                        "name"      => "Cúcuta",
                        "parent"    => 1,
                        "warehousable_id" => 7,
                        "warehousable_type" => 'App\Headquarter',
                        "buttons"   => ['add' => true,'inventory' => true, 'buy'=> false, 'conf'=> true, 'delete' =>false, 'edit'=> false, 'transf' => true],
                        "icon"      => "headquarter",
                        "action"    => true
                    ]);

                    Warehouse::create(
                        [
                            "name"      => "Eje Cafetero",
                            "parent"    => 1,
                            "warehousable_id" => 8,
                            "warehousable_type" => 'App\Headquarter',
                            "buttons"   => ['add' => true,'inventory' => true, 'buy'=> false, 'conf'=> true, 'delete' =>false, 'edit'=> false, 'transf' => true],
                            "icon"      => "headquarter",
                            "action"    => true
                        ]);
            
            
}
}