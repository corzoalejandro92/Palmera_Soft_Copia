<?php

use Illuminate\Database\Seeder;
use App\Unit;

class UnitTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Unit::create(["name" => "Und (Unidades)"]);
        Unit::create(["name" => "Gr (Gramos)"]);
        Unit::create(["name" => "Lts (Litros)"]);
        Unit::create(["name" => "mL (mililitros)"]);
        
    } 
}
