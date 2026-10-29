<?php

use Illuminate\Database\Seeder;
use App\MeanContact;

class MeanContactTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MeanContact::create(["name" => "Supermercados"]);
        MeanContact::create(["name" => "Telemercadeo"]);
        MeanContact::create(["name" => "Visita de ruta"]);
        MeanContact::create(["name" => "Voz a voz"]);
    }
}
