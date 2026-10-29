<?php

use App\TechnicalReportType;
use Illuminate\Database\Seeder;

class NewTechnicalReportTypeSedder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       

        TechnicalReportType::updateOrCreate(
            [
                "slug"                   => "mechanism-replace",
            ]
            ,
            [
                "name"                   => "Reponer trampa",
                "pest_required"          => 0,
                "picture_required"       => 1,
                "observation_required"   => 1
            ]
            
        );

        TechnicalReportType::updateOrCreate(
            [
                "slug"                   => "mechanism-not-found",
            ]
            ,
            [
                "name"                   => "No encontrado",
            "pest_required"          => 0,
            "picture_required"       => 1,
            "observation_required"   => 1
            ]
            
        );

        TechnicalReportType::updateOrCreate(
            [
                "slug"                   => "mechanism-damaged",
            ]
            ,
            [
                "name"                   => "Roto / Deteriorado",
            "pest_required"          => 0,
            "picture_required"       => 1,
            "observation_required"   => 1
            ]
            
        );

       
    }
}
