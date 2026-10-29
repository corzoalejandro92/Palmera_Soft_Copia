<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RouteStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $table="route_status";
        DB::table($table)->delete();
        $data = [
            ['name' => 'Sin iniciar', 'slug' => 'init'],
            ['name' => 'En ejecución','slug' => 'started'],
            ['name' => 'Finalizada', 'slug' => 'finished'],
            ['name' => 'Cancelada', 'slug' => 'cancelled']
        ];

        DB::table($table)->insert($data);
    }

}
