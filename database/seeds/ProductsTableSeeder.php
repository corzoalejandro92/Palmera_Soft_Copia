<?php

use Illuminate\Database\Seeder;
use App\Category;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            "name" => "Ingredientes Activos"
        ]);
        Category::create([
            "name" => "Papelería"
        ]);
        Category::create([
            "name" => "Mecanismo"
        ]);
        Category::create([
            "name" => "EPP"
        ]);
        Category::create([
            "name" => "Equipos"
        ]);
        Category::create([
            "name" => "Sustancias"
        ]);
    }
}
