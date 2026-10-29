<?php

use App\CrmTrackingTypes;
use Illuminate\Database\Seeder;

class AddTypeAgendaCrmTracking extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CrmTrackingTypes::create([
            "name"  =>  "Recordatorio",
            "slug"  =>  "reminder"
        ]);

        CrmTrackingTypes::create([
            "name"  =>  "Evento",
            "slug"  =>  "event"
        ]);
    }
}
