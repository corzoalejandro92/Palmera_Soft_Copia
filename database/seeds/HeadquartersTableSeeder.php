<?php

use Illuminate\Database\Seeder;
use App\Headquarter;
use App\Zone;

class HeadquartersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $headquarter = Headquarter::create([
            "name" => "Bucaramanga",
            "city_id" => 13371,
            "phone" => '6760050',
            "address" => 'Calle 60 # 16-35, B. La Esmeralda'
        ]);  
        
        
        $headquarter = Headquarter::create([
            "name" => "Medellín",
            "city_id" => 12578,
            "phone" => '3222414',
            "address" => 'Av. El Poblado #32c Sur-3 a 32c Sur-107',
            "lat" => 6.174561299999999,
            "lng" => -75.5861836
        ]);  
        $headquarter->zones()->create([
            "name" => 'Centro',
            "lat" => 6.2486069,
            "lng" => -75.5742467,
            "radius" => 5,
        ]);
        $headquarter->zones()->create([
            "name" => 'Sur',
            "lat" => 6.18171115824371,
            "lng" => -75.59415941972657,
            "radius" => 5,
        ]);
        $headquarter->zones()->create([
            "name" => 'Norte',
            "lat" => 6.3073042851319725,
            "lng" => -75.56532030839844,
            "radius" => 5,
        ]);
    }
}
