<?php

use Illuminate\Database\Seeder;
use App\FileTypeVehicle;

class FileTypeVehicleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FileTypeVehicle::create([
            "name" => "Matrícula"
        ]);
        FileTypeVehicle::create([
            "name" => "SOAT"
        ]);
        FileTypeVehicle::create([
            "name" => "Técno mecánica"
        ]);
        FileTypeVehicle::create([
            "name" => "Otros"
        ]);
    }
}
