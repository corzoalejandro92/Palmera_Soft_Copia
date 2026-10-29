<?php

use App\CrmTrackingTypes;
use Illuminate\Database\Seeder;

class CrmTrackingTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CrmTrackingTypes::create([
            "name" => "Visitas comerciales",
            "slug" => "commercial_visits"
        ]);
    }
}
