<?php

use Illuminate\Database\Seeder;
use App\FileTypeCustomer;

class FileTypeCustomerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FileTypeCustomer::create([
            "name" => "RUT"
        ]);
        FileTypeCustomer::create([
            "name" => "Cámara de comercio"
        ]);
        FileTypeCustomer::create([
            "name" => "Cédula del representante Legal"
        ]);
        FileTypeCustomer::create([
            "name" => "Otros"
        ]);
    }
}
