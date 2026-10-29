<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ApplicationsTableProductionSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('applications')->delete();
        
        \DB::table('applications')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Aspersión',
                'enabled' => 1,
                'updated_user' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-19 00:42:43',
                'updated_at' => '2021-01-20 10:31:10',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Nebulización con Sthil',
                'enabled' => 1,
                'updated_user' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-19 00:42:44',
                'updated_at' => '2021-01-20 10:36:26',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Termonebulización',
                'enabled' => 1,
                'updated_user' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-19 00:42:44',
                'updated_at' => '2021-01-20 10:31:20',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Inyección',
                'enabled' => 1,
                'updated_user' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-19 00:42:44',
                'updated_at' => '2021-01-20 10:31:38',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Gel',
                'enabled' => 1,
                'updated_user' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-19 00:42:44',
                'updated_at' => '2021-01-20 10:31:45',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Angeos',
                'enabled' => 1,
                'updated_user' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-19 00:42:44',
                'updated_at' => '2021-01-20 10:32:07',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Trampas de captura',
                'enabled' => 1,
                'updated_user' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-19 00:42:44',
                'updated_at' => '2021-01-20 10:32:26',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Cebos',
                'enabled' => 1,
                'updated_user' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-19 00:42:44',
                'updated_at' => '2021-01-20 10:32:31',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'Brochado',
                'enabled' => 1,
                'updated_user' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-19 00:42:44',
                'updated_at' => '2021-01-20 10:32:36',
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'Lámpara atrapainsectos',
                'enabled' => 1,
                'updated_user' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-19 00:42:44',
                'updated_at' => '2021-01-20 10:37:34',
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'Espolvoreo',
                'enabled' => 1,
                'updated_user' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-19 00:42:44',
                'updated_at' => '2021-01-20 10:32:45',
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'Choque Térmico',
                'enabled' => 1,
                'updated_user' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-19 00:42:44',
                'updated_at' => '2021-01-20 10:42:41',
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'Otros',
                'enabled' => 1,
                'updated_user' => NULL,
                'deleted_at' => '2021-01-20 10:31:32',
                'created_at' => '2021-01-19 00:42:44',
                'updated_at' => '2021-01-20 10:31:32',
            ),
            13 => 
            array (
                'id' => 14,
                'name' => 'Neolite',
                'enabled' => 1,
                'updated_user' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-20 10:32:18',
                'updated_at' => '2021-01-20 10:32:18',
            ),
            14 => 
            array (
                'id' => 15,
                'name' => 'Nebulización con eléctrico',
                'enabled' => 1,
                'updated_user' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-20 10:36:40',
                'updated_at' => '2021-01-20 10:36:40',
            ),
            15 => 
            array (
                'id' => 16,
                'name' => 'Control no tóxico',
                'enabled' => 1,
                'updated_user' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-20 10:37:11',
                'updated_at' => '2021-01-20 10:37:11',
            ),
            16 => 
            array (
                'id' => 17,
                'name' => 'Banderines',
                'enabled' => 1,
                'updated_user' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-20 10:38:00',
                'updated_at' => '2021-01-20 10:38:00',
            ),
            17 => 
            array (
                'id' => 18,
                'name' => 'Atomización',
                'enabled' => 1,
                'updated_user' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-20 10:38:19',
                'updated_at' => '2021-01-20 10:38:19',
            ),
            18 => 
            array (
                'id' => 19,
                'name' => 'Repelente',
                'enabled' => 1,
                'updated_user' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-20 10:38:41',
                'updated_at' => '2021-01-20 10:38:41',
            ),
            19 => 
            array (
                'id' => 20,
                'name' => 'Fregado Manual',
                'enabled' => 1,
                'updated_user' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-20 10:42:07',
                'updated_at' => '2021-01-20 10:42:07',
            ),
            20 => 
            array (
                'id' => 21,
                'name' => 'Gasificación',
                'enabled' => 1,
                'updated_user' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-20 10:44:50',
                'updated_at' => '2021-01-20 10:44:50',
            ),
            21 => 
            array (
                'id' => 22,
                'name' => 'Al voleo',
                'enabled' => 1,
                'updated_user' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-20 10:45:47',
                'updated_at' => '2021-01-20 10:45:47',
            )
        ));
        
        
    }
}