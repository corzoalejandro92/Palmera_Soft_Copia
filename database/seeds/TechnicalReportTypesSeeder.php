<?php

use App\TechnicalReportType;
use Illuminate\Database\Seeder;

class TechnicalReportTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TechnicalReportType::create(['name'=>'Retiro huevos de palomas',
                                    'picture_required'=> false,
                                    'slug' => '',
                                    'observation_required' => false]);

        TechnicalReportType::create(['name'=>'Retiro palomas',
                                    'picture_required'=> false,
                                    'slug' => '',
                                    'observation_required' => false]);
    }
}
