<?php

use App\Availability;
use Illuminate\Database\Seeder;
use App\BusinessDay;

class AvailabilitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BusinessDay::create([
            "name" => "Lunes"
        ]);
        BusinessDay::create([
            "name" => "Martes"
        ]);
        BusinessDay::create([
            "name" => "Miércoles"
        ]);
        BusinessDay::create([
            "name" => "Jueves"
        ]);
        BusinessDay::create([
            "name" => "Viernes"
        ]);
        BusinessDay::create([
            "name" => "Sábado"
        ]);
        BusinessDay::create([
            "name" => "Domingo"
        ]);


    
            $availability = Availability::create([
                "name" => "Lunes a viernes (ordinario)",
                "holidays" => 0,
                "headquarter_id" => 1
            ]);
                $availability->businessDays()->sync([1,2,3,4,5]);
                $availability->workingHours()->create([
                    "start"=>"8:00",
                    "end"=>"12:30",
                    "extra"=>0
                ]);
                $availability->workingHours()->create([
                    "start"=>"13:00",
                    "end"=>"18:00",
                    "extra"=>0
                ]);
        
            $availability = Availability::create([
                "name" => "Lunes a viernes (extra)",
                "holidays" => 0,
                "headquarter_id" => 1
            ]);
                    $availability->businessDays()->sync([1,2,3,4,5]);
                    $availability->workingHours()->create([
                        "start"=>"18:00",
                        "end"=>"20:00",
                        "extra"=>1
                    ]);
        
            $availability = Availability::create([
                "name" => "Sabados, Domingos",
                "holidays" => 1,
                "headquarter_id" => 1
            ]);
                    $availability->businessDays()->sync([6,7]);
                    $availability->workingHours()->create([
                        "start"=>"09:00",
                        "end"=>"12:30",
                        "extra"=>1
                    ]);
                    





            $availability = Availability::create([
                "name" => "Lunes a viernes (ordinario)",
                "holidays" => 0,
                "headquarter_id" => 2
            ]);
                    $availability->businessDays()->sync([1,2,3,4,5]);
                    $availability->workingHours()->create([
                        "start"=>"8:00",
                        "end"=>"12:30",
                        "extra"=>0
                    ]);
                    $availability->workingHours()->create([
                        "start"=>"13:00",
                        "end"=>"18:00",
                        "extra"=>0
                    ]);
                
            $availability = Availability::create([
                "name" => "Lunes a viernes (extra)",
                "holidays" => 0,
                "headquarter_id" => 2
            ]);
                    $availability->businessDays()->sync([1,2,3,4,5]);
                    $availability->workingHours()->create([
                        "start"=>"18:00",
                        "end"=>"20:00",
                        "extra"=>1
                    ]);
                
            $availability = Availability::create([
                "name" => "Sabados, Domingos",
                "holidays" => 1,
                "headquarter_id" => 2
            ]);
                    $availability->businessDays()->sync([6,7]);
                    $availability->workingHours()->create([
                        "start"=>"09:00",
                        "end"=>"12:30",
                        "extra"=>1
                    ]);
    }
}
