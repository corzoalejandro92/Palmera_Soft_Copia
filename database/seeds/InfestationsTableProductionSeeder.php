<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class InfestationsTableProductionSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('infestations')->delete();
        
        \DB::table('infestations')->insert(array (
            0 => 
            array (
                'id' => 1,
                'avatar' => 'images/default/control.png',
                'name' => 'Control de Roedores',
                'description' => NULL,
                'enabled' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-20 15:53:08',
                'updated_at' => '2021-01-22 17:37:04',
                'monthly' => 0,
            ),
            1 => 
            array (
                'id' => 2,
                'avatar' => 'images/default/control.png',
                'name' => 'Control de cucarachas',
                'description' => NULL,
                'enabled' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-22 17:39:50',
                'updated_at' => '2021-01-22 18:01:46',
                'monthly' => 0,
            ),
            2 => 
            array (
                'id' => 3,
                'avatar' => 'images/default/control.png',
                'name' => 'Control de moscas',
                'description' => NULL,
                'enabled' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-22 17:44:23',
                'updated_at' => '2021-01-23 12:37:06',
                'monthly' => 0,
            ),
            3 => 
            array (
                'id' => 4,
                'avatar' => 'images/default/control.png',
                'name' => 'Control de Zancudos',
                'description' => NULL,
                'enabled' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-25 16:47:37',
                'updated_at' => '2021-01-25 16:47:37',
                'monthly' => 1,
            ),
            4 => 
            array (
                'id' => 5,
                'avatar' => 'images/default/control.png',
                'name' => 'Control de hormigas',
                'description' => NULL,
                'enabled' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-25 16:50:59',
                'updated_at' => '2021-01-25 16:52:47',
                'monthly' => 0,
            ),
            5 => 
            array (
                'id' => 6,
                'avatar' => 'images/default/control.png',
                'name' => 'Control de plagas de granos almacenados',
                'description' => NULL,
                'enabled' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-25 17:20:21',
                'updated_at' => '2021-01-26 09:51:22',
                'monthly' => 0,
            ),
            6 => 
            array (
                'id' => 7,
                'avatar' => 'images/default/control.png',
                'name' => 'Control de ofidios',
                'description' => NULL,
                'enabled' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-26 09:56:56',
                'updated_at' => '2021-01-26 09:56:56',
                'monthly' => 0,
            ),
            7 => 
            array (
                'id' => 8,
                'avatar' => 'images/default/control.png',
                'name' => 'Control de Palomas',
                'description' => NULL,
                'enabled' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-26 09:58:55',
                'updated_at' => '2021-01-26 09:58:55',
                'monthly' => 0,
            ),
            8 => 
            array (
                'id' => 9,
                'avatar' => 'images/default/control.png',
                'name' => 'Control de aves',
                'description' => NULL,
                'enabled' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-26 09:59:58',
                'updated_at' => '2021-01-26 09:59:58',
                'monthly' => 0,
            ),
            9 => 
            array (
                'id' => 10,
                'avatar' => 'images/default/control.png',
                'name' => 'Control de gallinazos',
                'description' => NULL,
                'enabled' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-26 10:01:05',
                'updated_at' => '2021-01-26 10:01:05',
                'monthly' => 0,
            ),
            10 => 
            array (
                'id' => 11,
                'avatar' => 'images/default/control.png',
                'name' => 'Control de caracoles',
                'description' => NULL,
                'enabled' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-26 10:02:23',
                'updated_at' => '2021-01-26 10:02:23',
                'monthly' => 0,
            ),
            11 => 
            array (
                'id' => 12,
                'avatar' => 'images/default/control.png',
                'name' => 'Control de abejas',
                'description' => NULL,
                'enabled' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-26 10:04:36',
                'updated_at' => '2021-01-26 10:04:36',
                'monthly' => 0,
            ),
            12 => 
            array (
                'id' => 13,
                'avatar' => 'images/default/control.png',
                'name' => 'Control de avispas',
                'description' => NULL,
                'enabled' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-26 10:05:37',
                'updated_at' => '2021-01-26 10:05:37',
                'monthly' => 0,
            ),
            13 => 
            array (
                'id' => 14,
                'avatar' => 'images/default/control.png',
                'name' => 'Control de murciélagos',
                'description' => NULL,
                'enabled' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-26 10:06:28',
                'updated_at' => '2021-01-26 10:06:28',
                'monthly' => 0,
            ),
            14 => 
            array (
                'id' => 15,
                'avatar' => 'images/default/control.png',
                'name' => 'Control de anfibios',
                'description' => NULL,
                'enabled' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-26 10:07:55',
                'updated_at' => '2021-01-26 10:07:55',
                'monthly' => 0,
            ),
            15 => 
            array (
                'id' => 16,
                'avatar' => 'images/default/control.png',
                'name' => 'Control de Zarigüeyas',
                'description' => NULL,
                'enabled' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-26 10:09:10',
                'updated_at' => '2021-01-26 10:09:10',
                'monthly' => 0,
            ),
            16 => 
            array (
                'id' => 17,
                'avatar' => 'images/default/control.png',
                'name' => 'Control de gatos',
                'description' => NULL,
                'enabled' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-26 10:14:33',
                'updated_at' => '2021-01-26 10:14:33',
                'monthly' => 0,
            ),
            17 => 
            array (
                'id' => 18,
                'avatar' => 'images/default/control.png',
                'name' => 'Control de arañas',
                'description' => NULL,
                'enabled' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-26 10:18:40',
                'updated_at' => '2021-01-26 10:18:40',
                'monthly' => 0,
            ),
            18 => 
            array (
                'id' => 19,
                'avatar' => 'images/default/control.png',
                'name' => 'Control de comején',
                'description' => NULL,
                'enabled' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-26 10:19:44',
                'updated_at' => '2021-01-26 10:19:44',
                'monthly' => 0,
            ),
            19 => 
            array (
                'id' => 20,
                'avatar' => 'images/default/control.png',
                'name' => 'Control de pescadito de plata',
                'description' => NULL,
                'enabled' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-26 10:20:41',
                'updated_at' => '2021-01-26 10:20:41',
                'monthly' => 0,
            ),
            20 => 
            array (
                'id' => 21,
                'avatar' => 'images/default/control.png',
                'name' => 'Control de Miriápodos',
                'description' => NULL,
                'enabled' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-26 10:21:49',
                'updated_at' => '2021-01-26 10:21:49',
                'monthly' => 0,
            ),
            21 => 
            array (
                'id' => 22,
                'avatar' => 'images/default/control.png',
                'name' => 'Control de Coleópteros',
                'description' => NULL,
                'enabled' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-26 10:23:04',
                'updated_at' => '2021-01-26 10:23:04',
                'monthly' => 0,
            ),
            22 => 
            array (
                'id' => 23,
                'avatar' => 'images/default/control.png',
                'name' => 'Control de grillos',
                'description' => NULL,
                'enabled' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-26 10:24:02',
                'updated_at' => '2021-01-26 10:24:02',
                'monthly' => 0,
            ),
            23 => 
            array (
                'id' => 24,
                'avatar' => 'images/default/control.png',
                'name' => 'Control de Alacranes',
                'description' => NULL,
                'enabled' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-26 10:24:56',
                'updated_at' => '2021-01-26 10:24:56',
                'monthly' => 0,
            ),
            24 => 
            array (
                'id' => 25,
                'avatar' => 'images/default/control.png',
                'name' => 'Control de Iguanas',
                'description' => NULL,
                'enabled' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-26 10:26:01',
                'updated_at' => '2021-01-26 10:26:01',
                'monthly' => 0,
            ),
            25 => 
            array (
                'id' => 26,
                'avatar' => 'images/default/control.png',
                'name' => 'Control de Salamanquesas',
                'description' => NULL,
                'enabled' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-26 10:27:35',
                'updated_at' => '2021-01-26 10:27:35',
                'monthly' => 0,
            ),
            26 => 
            array (
                'id' => 27,
                'avatar' => 'images/default/control.png',
                'name' => 'Control de lagartijas',
                'description' => NULL,
                'enabled' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-26 10:28:35',
                'updated_at' => '2021-01-26 10:28:35',
                'monthly' => 0,
            ),
            27 => 
            array (
                'id' => 28,
                'avatar' => 'images/default/control.png',
                'name' => 'Control de Ardillas',
                'description' => NULL,
                'enabled' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-26 10:29:58',
                'updated_at' => '2021-01-26 10:29:58',
                'monthly' => 0,
            ),
            28 => 
            array (
                'id' => 29,
                'avatar' => 'images/default/control.png',
                'name' => 'Control de Insectos',
                'description' => NULL,
                'enabled' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-03-08 15:11:14',
                'updated_at' => '2021-03-08 15:11:14',
                'monthly' => 1,
            ),
        ));
        
        
    }
}