<?php

use App\Infestation;
use Illuminate\Database\Seeder;
use App\Product;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //Crear plagas de demostración
        Infestation::create([
            "id" => 1,
            "avatar" => '/storage/app/public/avatars/2020/04/5ea10f08be1d4.jpeg',
            "name" => 'Cucarachas'
        ]);
        Infestation::create([
            "id" => 2,
            "avatar" => '/storage/app/public/avatars/2020/04/5ea10f1aa3a1d.jpeg',
            "name" => 'Ardillas'
        ]);
        Infestation::create([
            "id" => 3,
            "avatar" => '/storage/app/public/avatars/2020/04/5ea10f2f5d27b.jpeg',
            "name" => 'Roedores'
        ]);
        Infestation::create([
            "id" => 4,
            "avatar" => '/storage/app/public/avatars/2020/04/5ea10f3eb6cb8.jpeg',
            "name" => 'Polvos/Mugre/Suciedad'
        ]);
        Infestation::create([
            "id" => 5,
            "avatar" => '/storage/app/public/avatars/2020/04/5ea1102c783e7.jpeg',
            "name" => 'Avispas'
        ]);
        Infestation::create([
            "id" => 6,
            "avatar" => '/storage/app/public/avatars/2020/04/5ea11051f2e43.jpeg',
            "name" => 'Bacterias'
        ]);
        Infestation::create([
            "id" => 7,
            "avatar" => '/storage/app/public/avatars/2020/04/5ea1107a83f90.jpeg',
            "name" => 'Hormigas'
        ]);
        Infestation::create([
            "id" => 8,
            "avatar" => '/storage/app/public/avatars/2020/04/5ea1108a66be8.jpeg',
            "name" => 'Moscas'
        ]);
        Infestation::create([
            "id" => 9,
            "avatar" => '/storage/app/public/avatars/2020/04/5ea110b31e0f2.jpeg',
            "name" => 'Insectos'
        ]);
        Infestation::create([
            "id" => 10,
            "avatar" => '/storage/app/public/avatars/2020/04/5ea13f173dadc.png',
            "name" => 'Aves'
        ]);





        $product = Product::create([
            "avatar" => '/storage/app/public/avatars/2020/04/5ea12cfe3042a.jpeg',
            "name" => "Palmera Gel",
            "code" => 01,
            "enabled" => 1,
            "description" => "Una gota de Palmera Gel, no más grande que una lenteja, mata miles de hormigas. En las hormigas Palmera Gel actúa como atrayente alimentario, ya que contiene fructuosa (azúcar de frutas). Cuando la hormiga consume de Palmera Gel la lleva al resto de la colonia, incluyendo a la reina y antes de 12 horas, estas desaparecerán.",
            "unit_id" => 2,
            "application_id" => 5,
            "category_id" => 1,
            "toxico" => 0
        ]);
        $product->services()->sync([1]);
        $product->services()->sync([1]);
        $product->infestations()->sync([1,7]);


        $product = Product::create([
            "avatar" => '/storage/app/public/avatars/2020/04/5ea135e82d1b1.jpeg',
            "name" => "Ratonera Ecojaula MaxR15",
            "code" => 02,
            "enabled" => 1,
            "unit_id" => 1,
            "application_id" => 7,
            "category_id" => 3,
            "toxico" => 0
        ]);
        $product->services()->sync([2]);
        $product->infestations()->sync([3]);


        $product = Product::create([
            "avatar" => '/storage/app/public/avatars/2020/04/5ea136b69f99a.png',
            "name" => "Ratonera Victor035",
            "code" => 03,
            "enabled" => 1,
            "unit_id" => 1,
            "application_id" => 7,
            "category_id" => 3,
            "toxico" => 0
        ]);
        $product->services()->sync([2]);
        $product->infestations()->sync([3]);


        $product = Product::create([
            "avatar" => '/storage/app/public/avatars/2020/04/5ea13867e9436.jpeg',
            "name" => "Detergente Desinfectante RMAX20",
            "code" => 04,
            "enabled" => 1,
            "unit_id" => 2,
            "application_id" => 12,
            "category_id" => 1,
            "toxico" => 1
        ]);
        $product->services()->sync([4,5]);
        $product->infestations()->sync([4,6]);

        $product = Product::create([
            "avatar" => '/storage/app/public/avatars/2020/04/5ea13da991139.jpeg',
            "name" => "Lavadora a presión XM-2098-1",
            "code" => 05,
            "enabled" => 1,
            "unit_id" => 1,
            "application_id" => 11,
            "category_id" => 3,
            "toxico" => 0
        ]);
        $product->services()->sync([4,5]);
        $product->infestations()->sync([4,6]);

        $product = Product::create([
            "avatar" => '/storage/app/public/avatars/2020/04/5ea13d7baa43a.jpeg',
            "name" => "Aspersor De Espalda A Bateria Equipmaster 16l 12v 22-60psi",
            "code" => 06,
            "enabled" => 1,
            "unit_id" => 1,
            "application_id" => 1,
            "category_id" => 3,
            "toxico" => 0
        ]);
        $product->services()->sync([4]);
        $product->infestations()->sync([6]);

        $product = Product::create([
            "avatar" => '/storage/app/public/avatars/2020/04/5ea13e03b663d.jpeg',
            "name" => "Jaula trampa SX355",
            "code" => 07,
            "enabled" => 1,
            "unit_id" => 1,
            "application_id" => 7,
            "category_id" => 3,
            "toxico" => 0
        ]);
        $product->services()->sync([6]);
        $product->infestations()->sync([10,2]);


        
    
    }
}
