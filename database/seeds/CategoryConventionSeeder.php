<?php

use App\CategoryConvention;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryConventionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        CategoryConvention::create([
            "slug" => "non-toxic-controls",
            "name" => "Controles no tóxicos"
        ]);
        CategoryConvention::create([
            "slug" => "baits",
            "name" => "Estaciones de Cebado"
        ]);
        CategoryConvention::create([
            "slug" => "fly-control-lamps",
            "name" => "Control de moscas (lámparas)"
        ]);
        CategoryConvention::create([
            "slug" => "pennants",
            "name" => "Banderines"
        ]);
        CategoryConvention::create([
            "slug" => "screens-or-pheromones",
            "name" => "Pantallas o Feromonas"
        ]);
        CategoryConvention::create([
            "slug" => "flutes-and-stakes",
            "name" => "Flautas y Estacas"
        ]);
        CategoryConvention::create([
            "slug" => "clock-cages",
            "name" => "Jaulas Reloj"
        ]);
        CategoryConvention::create([
            "slug" => "decoys",
            "name" => "Señuelos"
        ]);
    }
}
