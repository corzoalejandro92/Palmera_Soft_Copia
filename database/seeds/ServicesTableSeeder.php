<?php

use App\DetailService;
use Illuminate\Database\Seeder;
use App\Service;
use App\Headquarter;

class ServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() 
    {

        Service::create([
            "id" => 1,
            "name" => "Desinsectación",
            "avatar" => "custom-insect",
            "description" => "La desinsectación es la acción de eliminar determinados artrópodos dañinos - principalmente insectos - por medios químicos, físicos o con la aplicación de medidas de saneamiento básico.",
        ]);
            $dt = DetailService::create([
                "service_id" => 1,
                "headquarter_id" => 1,
                "zone" => "1mts2 - 100mts2",
                "visits" => 3,
                "cost" => 150000
            ]);
            $dt->headquarters([1,2,3,4,5,6,7,8]);
                

            $dt = DetailService::create([
                "service_id" => 1,
                "headquarter_id" => 1,
                "zone" => "101mts2 - 200mts2",
                "visits" => 3,
                "cost" => 250000
            ]);
            $dt->headquarters([1,2,3,4,5,6,7,8]);

            $dt = DetailService::create([
                "service_id" => 1,
                "headquarter_id" => 1,
                "zone" => "1mts2 - 100mts2",
                "visits" => 3,
                "cost" => 140000
            ]);
            $dt->headquarters([1,2,3,4,5,6,7,8]);


            $dt = DetailService::create([
                "service_id" => 1,
                "headquarter_id" => 3,
                "zone" => "101mts2 - 200mts2",
                "visits" => 3,
                "cost" => 240000
            ]);
            $dt->headquarters([1,2,3,4,5,6,7,8]);



        Service::create([
            "id" => 2,
            "name" => "Desratización",
            "avatar" => "custom-mouse",
            "description" => "La Desratización o Control de Roedores es el proceso que consiste en eliminar ratas y ratones que implica un conocimiento profundo de su hábitat, comportamiento y biología para un efectivo.",
        ]);
            $dt = DetailService::create([
                "service_id" => 2,
                "headquarter_id" => 1,
                "zone" => "10 Ratoneras",
                "visits" => 3,
                "cost" => 60000
            ]);
            $dt->headquarters([1,2,3,4,5,6,7,8]);

            $dt = DetailService::create([
                "service_id" => 2,
                "headquarter_id" => 1,
                "zone" => "20 Ratoneras",
                "visits" => 3,
                "cost" => 100000
            ]);
            $dt->headquarters([1,2,3,4,5,6,7,8]);

            $dt = DetailService::create([
                "service_id" => 2,
                "headquarter_id" => 3,
                "zone" => "10 Ratoneras",
                "visits" => 3,
                "cost" => 55000
            ]);
            $dt->headquarters([1,2,3,4,5,6,7,8]);

            $dt = DetailService::create([
                "service_id" => 2,
                "headquarter_id" => 3,
                "zone" => "20 Ratoneras",
                "visits" => 3,
                "cost" => 100000
            ]);
            $dt->headquarters([1,2,3,4,5,6,7,8]);

            Service::create([
                "id" => 3,
                "name" => "Programa MIP",
                "avatar" => "custom-package2",
                "description" => "Representa un paquete de servicios (desratización + desinsectación) a un costo mas ascequible para el cliente.",
            ]);
                $dt = DetailService::create([
                    "service_id" => 3,
                    "headquarter_id" => 1,
                    "zone" => "1mts2 - 100mts2",
                    "visits" => 3,
                    "cost" => 160000
                ]);
                $dt->headquarters([1,2,3,4,5,6,7,8]);

                $dt = DetailService::create([
                    "service_id" => 3,
                    "headquarter_id" => 1,
                    "zone" => "101mts2 - 200mts2",
                    "visits" => 3,
                    "cost" => 360000
                ]);
                $dt->headquarters([1,2,3,4,5,6,7,8]);

                $dt = DetailService::create([
                    "service_id" => 3,
                    "headquarter_id" => 3,
                    "zone" => "1mts2 - 100mts2",
                    "visits" => 3,
                    "cost" => 130000
                ]);
                $dt->headquarters([1,2,3,4,5,6,7,8]);

                $dt = DetailService::create([
                    "service_id" => 3,
                    "headquarter_id" => 3,
                    "zone" => "101mts2 - 200mts2",
                    "visits" => 3,
                    "cost" => 230000
                ]);
                $dt->headquarters([1,2,3,4,5,6,7,8]);


        Service::create([
            "id" => 4,
            "name" => "Desinfección",
            "avatar" => "custom-infection",
            "description" => "proceso químico que mata o erradica los microorganismos al igual como las bacterias, virus y protozoos impidiendo el crecimiento de microorganismos patógenos en fase vegetativa",
        ]);
        $dt =   DetailService::create([
                "service_id" => 4,
                "headquarter_id" => 1,
                "zone" => "1mts2 - 100mts2",
                "visits" => 3,
                "cost" => 200000
            ]);
            $dt->headquarters([1,2,3,4,5,6,7,8]);

            $dt = DetailService::create([
                "service_id" => 4,
                "headquarter_id" => 1,
                "zone" => "101mts2 - 200mts2",
                "visits" => 3,
                "cost" => 400000
            ]);
            $dt->headquarters([1,2,3,4,5,6,7,8]);

            $dt = DetailService::create([
                "service_id" => 4,
                "headquarter_id" => 3,
                "zone" => "1mts2 - 100mts2",
                "visits" => 3,
                "cost" => 190000
            ]);
            $dt->headquarters([1,2,3,4,5,6,7,8]);

            $dt = DetailService::create([
                "service_id" => 4,
                "headquarter_id" => 3,
                "zone" => "101mts2 - 200mts2",
                "visits" => 3,
                "cost" => 390000
            ]);
            $dt->headquarters([1,2,3,4,5,6,7,8]);


        Service::create([
            "id" => 5,
            "name" => "Lavado de tanques",
            "avatar" => "custom-infection",
            "description" => "proceso químico que mata o erradica los microorganismos al igual como las bacterias, virus y protozoos impidiendo el crecimiento de microorganismos patógenos en fase vegetativa",
        ]);
            $dt = DetailService::create([
                    "service_id" => 5,
                    "headquarter_id" => 1,
                    "zone" => "1lts - 50lts",
                    "visits" => 3,
                    "cost" => 75000
                ]);
                $dt->headquarters([1,2,3,4,5,6,7,8]);

                $dt = DetailService::create([
                    "service_id" => 5,
                    "headquarter_id" => 1,
                    "zone" => "51lts - 100lts",
                    "visits" => 3,
                    "cost" => 135000
                ]);
                $dt->headquarters([1,2,3,4,5,6,7,8]);

                $dt = DetailService::create([
                    "service_id" => 5,
                    "headquarter_id" => 3,
                    "zone" => "1lts - 50lts",
                    "visits" => 3,
                    "cost" => 80000
                ]);
                $dt->headquarters([1,2,3,4,5,6,7,8]);

                $dt = DetailService::create([
                    "service_id" => 5,
                    "headquarter_id" => 3,
                    "zone" => "51lts - 100lts",
                    "visits" => 3,
                    "cost" => 160000
                ]);
                $dt->headquarters([1,2,3,4,5,6,7,8]);


            Service::create([
                "id" => 6,
                "name" => "Control de Aves",
                "avatar" => "custom-bird",
                "description" => "Captura controlada de distintos tipos de aves, utilizando diferentes dispositivos de capturas dependiendo de la complejidad y tamaño.",
            ]);
            $dt = DetailService::create([
                            "service_id" => 6,
                            "headquarter_id" => 1,
                            "zone" => "10 trampas",
                            "visits" => 3,
                            "cost" => 50000
                        ]);
                        $dt->headquarters([1,2,3,4,5,6,7,8]);

                        $dt = DetailService::create([
                            "service_id" => 6,
                            "headquarter_id" => 1,
                            "zone" => "20 trampas",
                            "visits" => 3,
                            "cost" => 90000
                        ]);
                        $dt->headquarters([1,2,3,4,5,6,7,8]);

                        $dt = DetailService::create([
                            "service_id" => 6,
                            "headquarter_id" => 3,
                            "zone" => "10 trampas",
                            "visits" => 3,
                            "cost" => 50000
                        ]);
                        $dt->headquarters([1,2,3,4,5,6,7,8]);

                        $dt = DetailService::create([
                            "service_id" => 6,
                            "headquarter_id" => 3,
                            "zone" => "20 trampas",
                            "visits" => 3,
                            "cost" => 100000
                        ]);
                        $dt->headquarters([1,2,3,4,5,6,7,8]);
    }
}
