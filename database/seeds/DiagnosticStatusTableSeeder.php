<?php

use Illuminate\Database\Seeder;
use App\DiagnosticStatus;

class DiagnosticStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DiagnosticStatus::create([
            "name" => "Borrador",
        ]);

        DiagnosticStatus::create([
            "name" => "Terminada (Borrador)",
        ]);

        DiagnosticStatus::create([
            "name" => "Revisada (Asistente MIP)",
        ]);

        DiagnosticStatus::create([
            "name" => "Corregida (Ajuste solicitado por el cliente)",
        ]);
    }
}
