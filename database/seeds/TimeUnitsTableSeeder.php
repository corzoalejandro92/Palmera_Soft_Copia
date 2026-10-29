<?php

use Illuminate\Database\Seeder;
use App\TimeUnit;

class TimeUnitsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TimeUnit::create([
        	"name" => "Minutos",
        	"seconds" => 60,
        ]);

        TimeUnit::create([
        	"name" => "Horas",
        	"seconds" => 3600,
        ]);

        TimeUnit::create([
        	"name" => "Días",
        	"seconds" => 86400,
        ]);
    }
}
