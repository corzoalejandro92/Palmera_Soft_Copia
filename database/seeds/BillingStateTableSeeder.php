<?php

use Illuminate\Database\Seeder;
use App\BillingState;

class BillingStateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BillingState::create([
            "name" => "Pendiente"
        ]);

        BillingState::create([
            "name" => "Pagada"
        ]);

        BillingState::create([
            "name" => "Anulada"
        ]);
    }
}
