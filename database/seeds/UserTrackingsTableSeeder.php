<?php

use Illuminate\Database\Seeder;
use App\UserTrackingType;

class UserTrackingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserTrackingType::create([
            "name" => 'Inicio dia laboral',
            "slug" => 'user'
        ]);

        UserTrackingType::create([
            "name" => 'Fin dia laboral',
            "slug" => 'user',
        ]);

        UserTrackingType::create([
            "name" => 'Inicio del servicio',
            "slug" => 'user'
        ]);

        UserTrackingType::create([
            "name" => 'Finalización de servicio',
            "slug" => 'user'
        ]);

        UserTrackingType::create([
            "name" => 'Inicio de contrato laboral',
            "slug" => 'user',
            "disable_user" => true
        ]);

        UserTrackingType::create([
            "name" => 'Finalización de contrato laboral',
            "slug" => 'user',
            "disable_user" => true,
        ]);

        UserTrackingType::create([
            "name" => 'Inicio periodo vacacional',
            "slug" => 'user',
            "disable_user" => true
        ]);

        UserTrackingType::create([
            "name" => 'Fin periodo vacacional',
            "slug" => 'user',
            "disable_user" => false
        ]);

        UserTrackingType::create([
            "name" => 'Ininio Enfermedad/Incapacidad',
            "slug" => 'user',
            "disable_user" => true
        ]);

        UserTrackingType::create([
            "name" => 'Fin Enfermedad/Incapacidad',
            "slug" => 'user',
            "disable_user" => false
        ]);

        UserTrackingType::create([
            "name" => 'Solicitud de permiso personal',
            "slug" => 'user',
            "disable_user" => true
        ]);

        UserTrackingType::create([
            "name" => 'regreso de permiso personal',
            "slug" => 'user',
            "disable_user" => false
        ]);

        UserTrackingType::create([
            "name" => 'Novedad de un mecanismo',
            "slug" => 'user',
            "disable_user" => false
        ]);
    }
}
