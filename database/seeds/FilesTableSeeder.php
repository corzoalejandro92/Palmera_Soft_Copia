<?php

use App\FileType;
use Illuminate\Database\Seeder;

class FilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FileType::create([
            "name" => "Soporte de observaciones",
            "slug" => "scheduling",
        ]);
        
        
        FileType::create([
            "name" => "Documento de identidad",
            "slug" => "user",
        ]);
        FileType::create([
            "name" => "Certificado ARL",
            "slug" => "user",
        ]);
        FileType::create([
            "name" => "Seguridad Social",
            "slug" => "user",
        ]);
        FileType::create([
            "name" => "Otros",
            "slug" => "user",
        ]);
        
        
        FileType::create([
            "name" => "RUT",
            "slug" => "crm",
        ]);
        FileType::create([
            "name" => "Cámara de comercio",
            "slug" => "crm",
        ]);
        FileType::create([
            "name" => "Cédula representante legal",
            "slug" => "crm",
        ]);
        FileType::create([
            "name" => "Contrato",
            "slug" => "crm",
        ]);
        FileType::create([
            "name" => "Autorizacion de tratamiento de datos",
            "slug" => "crm",
        ]);
        FileType::create([
            "name" => "Solicitud de crédito",
            "slug" => "crm",
        ]);
        FileType::create([
            "name" => "Otros",
            "slug" => "crm",
        ]);
        
        
        
        FileType::create([
            "name" => "Ficha de seguridad",
            "slug" => "product",
        ]);
        FileType::create([
            "name" => "Registro legal",
            "slug" => "product",
        ]);
        FileType::create([
            "name" => "Ficha técnica",
            "slug" => "product",
        ]);
        FileType::create([
            "name" => "Otros",
            "slug" => "product",
        ]);
        
        
        
        FileType::create([
            "name" => "Matrícula",
            "slug" => "vehicle",
        ]);
        FileType::create([
            "name" => "SOAT",
            "slug" => "vehicle",
        ]);
        FileType::create([
            "name" => "Tecno mecánica",
            "slug" => "vehicle",
        ]);
        FileType::create([
            "name" => "Otros",
            "slug" => "vehicle",
        ]);

        
        FileType::create([
            "name" => "Plano inicial",
            "slug" => "plan-init",
        ]);

        FileType::create([
            "name" => "Plano mecanismo",
            "slug" => "plan-mec",
        ]);
        FileType::create([
            "name" => "Plano riesgos",
            "slug" => "plan-risk",
        ]);
        FileType::create([
            "name" => "Plano rutas",
            "slug" => "plan-route",
        ]);
        FileType::create([
            "name" => "Otros",
            "slug" => "plan",
        ]);

        FileType::create([
            "name" => "Archivo de diagnóstico",
            "slug" => "quote-diagnosis",
        ]);
    }
}
