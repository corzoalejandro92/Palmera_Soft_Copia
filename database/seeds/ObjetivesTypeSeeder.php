<?php

use App\ObjetiveType;
use Illuminate\Database\Seeder;

class ObjetivesTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ObjetiveType::create([
            "slug" => "new-objetive-month",
            "name" => "Ventas nuevas por mes"
        ]);
        ObjetiveType::create([
            "slug" => "total-objetive-month",
            "name" => "Ventas totales por mes"
        ]);
        
    }
}
