<?php

use Illuminate\Database\Seeder;
use App\ContactType;

class ContactTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ContactType::create([
            "name" => "Cotización"
        ]);
        ContactType::create([
            "name" => "Facturación"
        ]);

        ContactType::create([
            "name" => "Programación"
        ]);
    }
}
