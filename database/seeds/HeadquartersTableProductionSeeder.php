<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class HeadquartersTableProductionSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('headquarters')->delete();
        
        \DB::table('headquarters')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Bucaramanga',
                'city_id' => 13398,
                'address' => 'Calle 60 # 16-35 La Esmeralda',
                'lat' => NULL,
                'lng' => NULL,
                'phone' => '6760050',
                'cell_phone' => '3153730506',
                'email' => 'bucaramanga@palmerajunior.com',
                'enabled' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-19 00:41:45',
                'updated_at' => '2021-01-19 11:57:48',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Bogota',
                'city_id' => 12688,
                'address' => 'Barrios Unidos',
                'lat' => '4.6691341',
                'lng' => '-74.0666829',
                'phone' => '7498855',
                'cell_phone' => '3184721588',
                'email' => 'administracionbogota@palmerajunior.com',
                'enabled' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-19 11:55:33',
                'updated_at' => '2021-01-19 11:55:33',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Medellín',
                'city_id' => 12578,
                'address' => 'Carrera 43a # 32B sur - 51',
                'lat' => NULL,
                'lng' => NULL,
                'phone' => '3222414',
                'cell_phone' => '3187161045',
                'email' => 'medellin@palmerajunior.com',
                'enabled' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-19 12:00:03',
                'updated_at' => '2021-01-19 12:00:03',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Cali',
                'city_id' => 48332,
                'address' => 'Av. 3 Nte. #45 Norte-10',
                'lat' => '3.4794503',
                'lng' => '-76.5166126',
                'phone' => '5241212',
                'cell_phone' => '3153351762',
                'email' => 'contabilidadcali@palmerajunior.com',
                'enabled' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-19 12:04:23',
                'updated_at' => '2021-01-19 12:04:23',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Barranquilla',
                'city_id' => 12666,
                'address' => 'Cra. 46 #67-44',
                'lat' => '10.9920268',
                'lng' => '-74.8009469',
                'phone' => '3201313',
                'cell_phone' => '3144066156',
                'email' => 'barranquilla@palmerajunior.com',
                'enabled' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-19 12:05:48',
                'updated_at' => '2021-01-19 12:05:48',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Barrancabermeja',
                'city_id' => 13368,
                'address' => 'Carrera 33 # 65-84 Floresta Alta',
                'lat' => NULL,
                'lng' => NULL,
                'phone' => '6112298',
                'cell_phone' => '3167403720',
                'email' => 'barranca@palmerajunior.com',
                'enabled' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-19 12:06:59',
                'updated_at' => '2021-01-19 12:06:59',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Cúcuta',
                'city_id' => 48333,
                'address' => 'Cl. 6 Nte. #3-17',
                'lat' => '7.903717899999999',
                'lng' => '-72.5007371',
                'phone' => '6896883',
                'cell_phone' => '3165208822',
                'email' => 'cucuta@palmerajunior.com',
                'enabled' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-19 12:08:54',
                'updated_at' => '2021-01-19 12:08:54',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Eje Cafetero',
                'city_id' => 13358,
                'address' => 'Carrera 11 # 48-60 Maraya',
                'lat' => NULL,
                'lng' => NULL,
                'phone' => '3444566',
                'cell_phone' => '3162898272',
                'email' => 'administrativoeje@palmerajunior.com',
                'enabled' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-19 12:11:47',
                'updated_at' => '2021-01-19 12:11:47',
            ),
        ));
        
        
    }
}