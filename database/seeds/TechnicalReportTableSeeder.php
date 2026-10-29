<?php

use Illuminate\Database\Seeder;
use App\TechnicalReportType;

class TechnicalReportTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    	TechnicalReportType::create([
        	"name" => 'Instalación',
        	"slug" => 'mechanism-install',
        	"picture_required" => true,
        	"observation_required" => false,
        ]);

        $review = new TechnicalReportType();
        $review->name = "Revisión";
        $review->slug = "mechanism-review";
        $review->picture_required = false;
        $review->observation_required = false;
        $review->save();

        TechnicalReportType::create([
        	"name" => 'Revisión',
        	"slug" => 'mechanism-review',
        	"picture_required" => true,
        	"observation_required" => false,
        ]);

    	TechnicalReportType::create([
        	"name" => 'No encontrado',
        	"slug" => 'mechanism-not-found',
        	"picture_required" => true,
        	"observation_required" => false,
        ]);

        TechnicalReportType::create([
        	"name" => 'Inaccesible',
        	"slug" => 'mechanism-not-accessible',
        	"picture_required" => true,
        	"observation_required" => false,
        ]);

        

        TechnicalReportType::create([
        	"name" => 'Captura',
        	"parent_id" => $review->id,
        	"picture_required" => true,
        	"observation_required" => false,
        ]);

        TechnicalReportType::create([
        	"name" => 'Consumo',
        	"parent_id" => $review->id,
        	"picture_required" => true,
        	"observation_required" => false,
        ]);

        TechnicalReportType::create([
        	"name" => 'Conteo',
        	"parent_id" => $review->id,
        	"picture_required" => true,
        	"observation_required" => false,
        ]);

        TechnicalReportType::create([
        	"name" => 'Limpieza',
        	"parent_id" => $review->id,
        	"picture_required" => true,
        	"observation_required" => false,
        ]);

        TechnicalReportType::create([
        	"name" => 'Otro',
        	"parent_id" => $review->id,
        	"picture_required" => false,
        	"observation_required" => true,
        ]);

        TechnicalReportType::create([
        	"name" => 'Vacía',
        	"parent_id" => $review->id,
        	"picture_required" => false,
        	"observation_required" => false,
        ]);
    }
}
