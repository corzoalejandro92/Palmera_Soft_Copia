<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UnitDosesTableProductionSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('unit_doses')->delete();
        
        \DB::table('unit_doses')->insert(array (
            0 => 
            array (
                'id' => 4,
                'name' => 'ml/L',
                'enabled' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-26 14:59:55',
                'updated_at' => '2021-01-26 14:59:55',
                'user_id' => 1,
            ),
            1 => 
            array (
                'id' => 5,
                'name' => 'g/punto',
                'enabled' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-26 15:00:16',
                'updated_at' => '2021-01-26 15:00:16',
                'user_id' => 1,
            ),
            2 => 
            array (
                'id' => 6,
                'name' => 'g/ml',
                'enabled' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-26 15:01:25',
                'updated_at' => '2021-01-26 15:01:25',
                'user_id' => 1,
            ),
            3 => 
            array (
                'id' => 7,
                'name' => 'g/L',
                'enabled' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-26 15:01:33',
                'updated_at' => '2021-01-26 15:01:33',
                'user_id' => 1,
            ),
            4 => 
            array (
                'id' => 8,
                'name' => 'g/t',
                'enabled' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-26 15:05:00',
                'updated_at' => '2021-01-26 15:05:00',
                'user_id' => 1,
            ),
            5 => 
            array (
                'id' => 9,
                'name' => 'g/m3',
                'enabled' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-26 15:05:11',
                'updated_at' => '2021-01-26 15:05:11',
                'user_id' => 1,
            ),
            6 => 
            array (
                'id' => 10,
                'name' => 'g/estación',
                'enabled' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-26 15:05:34',
                'updated_at' => '2021-01-26 15:05:34',
                'user_id' => 1,
            ),
        ));
        
        
    }
}