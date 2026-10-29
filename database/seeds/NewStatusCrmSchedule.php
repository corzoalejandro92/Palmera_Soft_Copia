<?php

use App\CrmScheduleStatus;
use Illuminate\Database\Seeder;

class NewStatusCrmSchedule extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            "id" => 8,
            "slug" => "to-be-confirm",
            "name" => "Por confirmar",
            "color" => "#514a9d"
        ];

        CrmScheduleStatus::updateOrCreate(
        ['id' => 8],
        ["slug" => "to-be-confirm",
        "name" => "Por confirmar",
        "color" => "#514a9d"]);
    }
}
