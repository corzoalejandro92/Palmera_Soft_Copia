<?php

use Illuminate\Database\Seeder;
use App\Application;

class ApplicationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Application::create([
            "name" => "Aspersión"
        ]);
        Application::create([
            "name" => "Nebulización"
        ]);
        Application::create([
            "name" => "Termonebulización"
        ]);
        Application::create([
            "name" => "Inyección"
        ]);
        Application::create([
            "name" => "Gel"
        ]);
        Application::create([
            "name" => "Anjenos o Neolite"
        ]);
        Application::create([
            "name" => "Trampas de captura"
        ]);
        Application::create([
            "name" => "Cebos"
        ]);

        Application::create([
            "name" => "Brochado"
        ]);

        Application::create([
            "name" => "Lámparas"
        ]);

        Application::create([
            "name" => "Espolvoreo"
        ]);

        Application::create([
            "name" => "A presión"
        ]);

        Application::create([
            "name" => "Otros"
        ]);
    }
}
