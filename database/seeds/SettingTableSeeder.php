<?php

use Illuminate\Database\Seeder;
use App\Setting;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::create([
            "invoice" => "000000001",
            "quote" => "000000001",
            "proof_income" => "000000001",
            "proof_discharge" => "000000001"
        ]);
    }
}
