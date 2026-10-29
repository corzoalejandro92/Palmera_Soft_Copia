<?php

use Illuminate\Database\Seeder;
use App\FileTypePlan;

class FileTypePlanTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FileTypePlan::create([
            "name" => "Plano de mecanismo"
        ]);
        FileTypePlan::create([
            "name" => "Plano de riesgos"
        ]);
        FileTypePlan::create([
            "name" => "Plano de rutas"
        ]);
        FileTypePlan::create([
            "name" => "Otros"
        ]);
    }
}
