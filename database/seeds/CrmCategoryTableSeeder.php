<?php

use Illuminate\Database\Seeder;
use App\CrmType;
use App\Crm;
use App\MeanContact;

class CrmCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CrmType::create(["name" => "Clientes"]);
        CrmType::create(["name" => "Contactados"]);
        CrmType::create(["name" => "Interesado"]);
        CrmType::create(["name" => "No Interesados"]);
        CrmType::create(["name" => "Base de datos"]);
        CrmType::create(["name" => "Contacto Web"]);

        
        $crm = Crm::create([
            "type_customer" => 1,
            "document" => '353545',
            "name" => "Software Web S.A.S",
            "crm_type_id" => 1,
            "mean_contact_id" => 2,
            "customer_national" => 0,
        ]);
        

        $office = $crm->offices()->create([
            'name' => 'Oficina Principal',
            'city_id' => 12578,
            'address' => 'Diagonal 32B #29 Sur 74',
            'observation' => 'Casa',
            'lat' => 6.1760844,
            'lng' => -75.5834625,
            'headquarter_id' => 3
            ]);
        
            $office->warehouse()->create([
                "name" => "{$crm->name} {$office->name}",
                "parent" => 5,
                "buttons" => ['add' => false, 'buy'=> false, 'conf'=> true, 'delete' =>false, 'edit'=> false, 'transf' => true],
                "icon" => 'customer',
                "action" => true
            ]);

        $contact = $office->contacts()->create([
            'name' =>           'Bryan Maya Galeano',
            'phone' =>          3712893,
            'position' =>       'Gerente',
            'email' =>          'bmaya@swebsas.com'
        ]);

        $contact->types()->sync([1,2,3]);

        
        $crm = Crm::create([
            "type_customer" => 1,
            "document" => '0093433',
            "name" => "Solla",
            "crm_type_id" => 1,
            "mean_contact_id" => 2,
            "customer_national" => 1,
        ]);
        

        $office = $crm->offices()->create([
            'name' => 'Sede Bello',
            'city_id' => 12549,
            'address' => 'Solla (Cra 49)',
            'observation' => 'Planta',
            'lat' => 6.3128522,
            'lng' => -75.5564255,
            'headquarter_id' => 3
            ]);
        
        $office->warehouse()->create([
            "name" => "{$crm->name} {$office->name}",
            "parent" => 5,
            "buttons" => ['add' => false, 'buy'=> false, 'conf'=> true, 'delete' =>false, 'edit'=> false, 'transf' => true],
            "icon" => 'customer',
            "action" => true
        ]);

        $contact =  $office->contacts()->create([
            'name' =>           'Mariana Angel Deossa',
            'phone' =>          3712893,
            'position' =>       'Calidad',
            'email' =>          'marianad@swebsas.com'
        ]);

        $contact->types()->sync([1,2,3]);


        $crm = Crm::create([
            "type_customer" => 0,
            "document" => '8655678',
            "name" => "Daniel Gallo",
            "crm_type_id" => 1,
            "mean_contact_id" => 2,
            "customer_national" => 1,
        ]);
        

        $office = $crm->offices()->create([
            'name' => 'Mega 5000',
            'city_id' => 12601,
            'address' => 'Calle 50',
            'observation' => 'Local',
            'lat' => 6.250027099999999,
            'lng' => -75.5681333,
            'headquarter_id' => 3
            ]);
        
        $office->warehouse()->create([
            "name" => "{$crm->name} {$office->name}",
            "parent" => 5,
            "buttons" => ['add' => false, 'buy'=> false, 'conf'=> true, 'delete' =>false, 'edit'=> false, 'transf' => true],
            "icon" => 'customer',
            "action" => true
        ]);
        
        $contact =$office->contacts()->create([
                'name' =>           'Daniel Gallo',
                'phone' =>          3712893,
                'position' =>       'Gerente',
                'email' =>          'dgallo@swebsas.com'
        ]);

        $contact->types()->sync([1]);

        $contact = $office->contacts()->create([
            'name' =>           'Maria Isabel Restrepo',
            'phone' =>          3712893,
            'position' =>       'Secretaria Local',
            'email' =>          'mariai@swebsas.com'
        ]);

        $contact->types()->sync([3]);

        $contact = $office->contacts()->create([
            'name' =>           'Katerine Gil',
            'phone' =>          3712893,
            'position' =>       'Secretaria Administrativa',
            'email' =>          'katerineg@swebsas.com'
        ]);

        $contact->types()->sync([2]);




    }
}
