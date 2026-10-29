<?php

use App\CrmScheduleStatus;
use Illuminate\Database\Seeder;
use App\SchedulingType;


class SchedulingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //tipos de  estados para el cronograma
        CrmScheduleStatus::create([
            "slug"  => "init",
            "name"  => "Sin Programar",
            "color" => "#d556ff"
        ]);

        CrmScheduleStatus::create([
            "slug"  => "route",
            "name"  => "En ruta",
            "color" => "#795548"
        ]);

        CrmScheduleStatus::create([
            "slug"  => "service-success",
            "name"  => "Realizado",
            "color" => "#419644"
        ]);

        CrmScheduleStatus::create([
            "slug"  => "service-fail",
            "name"  => "No Realizado",
            "color" => "#FF9800"
        ]);
        
        CrmScheduleStatus::create([
            "slug"  => "cancelled",
            "name"  => "Cancelado",
            "color" => "#f44336"
        ]);

        CrmScheduleStatus::create([
            "slug"  => "programed",
            "name"  => "Programado",
            "color" => "#2196f3"
        ]);

        CrmScheduleStatus::create([
            "slug"  => "invoiced",
            "name"  => "Programado",
            "color" => "#2196f3"
        ]);


        //tipos de eventos para la agenda
        SchedulingType::create([
            "id"   => 0,
            "name" => "Traslado Ruta",
            "color" => '#252525',
            "disabled_color" => 'rgba(37, 37, 37, 0.45)',
            "enabled" => 0
        ]);

        SchedulingType::create([
            "id"   => 1,
            "name" => "Servicio",
            "color" => "#3788d8",
            "disabled_color" => 'rgba(55, 136, 216, 0.56)'
        ]);
        SchedulingType::create([
            "id"   => 2,
            "name" => "Visita",
        ]);
        SchedulingType::create([
            "id"   => 3,
            "name" => "Recordatorio",
            "color" => "#8BC34A",
            "disabled_color" => "rgba(76, 175, 80, 0.56)"

        ]);
        SchedulingType::create([
            "id"   => 4,
            "name" => "Reunión",
        ]);
        SchedulingType::create([
            "id"   => 5,
            "name" => "Asunto Personal",
        ]);
        
        // $schedule = new Scheduling();
        // $schedule->name = "Evento de prueba";
        // $schedule->scheduling_type_id = 2;
        // $schedule->crm_id = 1;
        // $schedule->start = Carbon::now()->format('Y-m-d h:i:s');
        // $schedule->end = Carbon::now()->addHours('1')->format('Y-m-d H:i:s');
        // $schedule->headquarter_id = 2;
        // $schedule->save();
        // $schedule->users()->sync(1);
       
    }
}
