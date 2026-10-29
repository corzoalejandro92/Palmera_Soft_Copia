<?php

use App\SharedMechasnims;
use App\Warehouse;
use Illuminate\Database\Seeder;

class SharedMechasnimsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sharedMechasnimsBuc = SharedMechasnims::create([
            'name'              => "MECANISMOS PRESTADOS BUCARAMANGA",
            'headquarter_id'    => "1",
            'slug'              => "share.mechanism.bucaramanga",

        ]);
        Warehouse::create(
            [
                "name"      => "MECANISMOS PRESTADOS BUCARAMANGA",
                "parent"    => 2,
                "warehousable_id" => $sharedMechasnimsBuc->id,
                "warehousable_type" => 'App\SharedMechasnims',
                "buttons"   => ['add' => false,'inventory' => true, 'buy'=> false, 'conf'=> false, 'delete' =>false, 'edit'=> false, 'transf' => true],
                "icon"      => "sharedMechasnims",
                "action"    => true
            ]
        );

        $sharedMechasnimsBog = SharedMechasnims::create([
            'name'              => "MECANISMOS PRESTADOS BOGOTA",
            'headquarter_id'    => "2",
            'slug'              => "share.mechanism.bogota",

        ]);
        Warehouse::create(
            [
                "name"      => "MECANISMOS PRESTADOS BOGOTA",
                "parent"    => 4,
                "warehousable_id" => $sharedMechasnimsBog->id,
                "warehousable_type" => 'App\SharedMechasnims',
                "buttons"   => ['add' => false,'inventory' => true, 'buy'=> false, 'conf'=> false, 'delete' =>false, 'edit'=> false, 'transf' => true],
                "icon"      => "sharedMechasnims",
                "action"    => true
            ]
        );

        $sharedMechasnimsMed = SharedMechasnims::create([
            'name'              => "MECANISMOS PRESTADOS MEDELLIN",
            'headquarter_id'    => "3",
            'slug'              => "share.mechanism.medellin",

        ]);
        Warehouse::create(
            [
                "name"      => "MECANISMOS PRESTADOS MEDELLIN",
                "parent"    => 5,
                "warehousable_id" => $sharedMechasnimsMed->id,
                "warehousable_type" => 'App\SharedMechasnims',
                "buttons"   => ['add' => false,'inventory' => true, 'buy'=> false, 'conf'=> false, 'delete' =>false, 'edit'=> false, 'transf' => true],
                "icon"      => "sharedMechasnims",
                "action"    => true
            ]
        );

        $sharedMechasnimsCal = SharedMechasnims::create([
            'name'              => "MECANISMOS PRESTADOS CALI",
            'headquarter_id'    => "4",
            'slug'              => "share.mechanism.cali",

        ]);
        Warehouse::create(
            [
                "name"              => "MECANISMOS PRESTADOS CALI",
                "parent"            => 6,
                "warehousable_id"   => $sharedMechasnimsCal->id,
                "warehousable_type" => 'App\SharedMechasnims',
                "buttons"           => ['add' => false,'inventory' => true, 'buy'=> false, 'conf'=> false, 'delete' =>false, 'edit'=> false, 'transf' => true],
                "icon"              => "sharedMechasnims",
                "action"            => true
            ]
        );

        $sharedMechasnimsBar = SharedMechasnims::create([
            'name'              => "MECANISMOS PRESTADOS BARRANQUILLA",
            'headquarter_id'    => "5",
            'slug'              => "share.mechanism.barranquilla",

        ]);
        Warehouse::create(
            [
                "name"              => "MECANISMOS PRESTADOS BARRANQUILLA",
                "parent"            => 7,
                "warehousable_id"   => $sharedMechasnimsBar->id,
                "warehousable_type" => 'App\SharedMechasnims',
                "buttons"           => ['add' => false,'inventory' => true, 'buy'=> false, 'conf'=> false, 'delete' =>false, 'edit'=> false, 'transf' => true],
                "icon"              => "sharedMechasnims",
                "action"            => true
            ]
        );

        $sharedMechasnimsBarr = SharedMechasnims::create([
            'name'              => "MECANISMOS PRESTADOS BARRANCABERMEJA",
            'headquarter_id'    => "6",
            'slug'              => "share.mechanism.barrancabermeja",

        ]);
        Warehouse::create(
            [
                "name"      => "MECANISMOS PRESTADOS BARRANCABERMEJA",
                "parent"    => 8,
                "warehousable_id" => $sharedMechasnimsBarr->id,
                "warehousable_type" => 'App\SharedMechasnims',
                "buttons"   => ['add' => false,'inventory' => true, 'buy'=> false, 'conf'=> false, 'delete' =>false, 'edit'=> false, 'transf' => true],
                "icon"      => "sharedMechasnims",
                "action"    => true
            ]
        );

        $sharedMechasnimsCuc = SharedMechasnims::create([
            'name'              => "MECANISMOS PRESTADOS CUCUTA",
            'headquarter_id'    => "7",
            'slug'              => "share.mechanism.cucuta",

        ]);
        Warehouse::create(
            [
                "name"      => "MECANISMOS PRESTADOS CUCUTA",
                "parent"    => 9,
                "warehousable_id" => $sharedMechasnimsCuc->id,
                "warehousable_type" => 'App\SharedMechasnims',
                "buttons"   => ['add' => false,'inventory' => true, 'buy'=> false, 'conf'=> false, 'delete' =>false, 'edit'=> false, 'transf' => true],
                "icon"      => "sharedMechasnims",
                "action"    => true
            ]
        );

        $sharedMechasnimsEje = SharedMechasnims::create([
            'name'              => "MECANISMOS PRESTADOS EJE CAFETERO",
            'headquarter_id'    => "8",
            'slug'              => "share.mechanism.ejecafetero",

        ]);
        Warehouse::create(
            [
                "name"      => "MECANISMOS PRESTADOS EJE CAFETERO",
                "parent"    => 10,
                "warehousable_id" => $sharedMechasnimsEje->id,
                "warehousable_type" => 'App\SharedMechasnims',
                "buttons"   => ['add' => false,'inventory' => true, 'buy'=> false, 'conf'=> false, 'delete' =>false, 'edit'=> false, 'transf' => true],
                "icon"      => "sharedMechasnims",
                "action"    => true
            ]
        );
    }
}
