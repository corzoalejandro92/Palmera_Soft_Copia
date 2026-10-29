<?php

use App\DocumentTypeFoxcon;
use Illuminate\Database\Seeder;

class DocumentTypeFoxconSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DocumentTypeFoxcon::updateOrCreate(
            ['name' => 'ORDEN DE COMPRA', 'slug' => 'E']
        );
        DocumentTypeFoxcon::updateOrCreate(
            ['name' => 'TRASLADOS', 'slug' => 'ES']
        );
        DocumentTypeFoxcon::updateOrCreate(
            ['name' => 'CONSUMOS', 'slug' => 'S']
        );
        DocumentTypeFoxcon::updateOrCreate(
            ['name' => 'ENTRADA MERCANCIA', 'slug' => 'E']
        );
        DocumentTypeFoxcon::updateOrCreate(
            ['name' => 'OBSEQUIOS', 'slug' => 'S']
        );
        
    }
}
