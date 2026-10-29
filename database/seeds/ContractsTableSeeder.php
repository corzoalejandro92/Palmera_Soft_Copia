<?php

use Illuminate\Database\Seeder;
use App\ContractStatus;

class ContractsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ContractStatus::create([
            "name" => 'Activo'
        ]);

        ContractStatus::create([
            "name" => 'Terminado'
        ]);

        ContractStatus::create([
            "name" => 'Inactivo'
        ]);

        ContractStatus::create([
            "name" => 'Suspendido'
        ]);

        ContractStatus::create([
            "name" => 'Cancelado Proveedor'
        ]);
        ContractStatus::create([
            "name" => 'Cancelado Cliente'
        ]);
    }
}
