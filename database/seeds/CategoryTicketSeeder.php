<?php

use App\CategoryTicket;
use Illuminate\Database\Seeder;

class CategoryTicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categoryTicket = new CategoryTicket;
        $categoryTicket->name = "Facturación";
        $categoryTicket->slug = "billing";
        $categoryTicket->class = "bg-blue";
        $categoryTicket->save();

        $categoryTicket = new CategoryTicket;
        $categoryTicket->name = "Retiro de captura";
        $categoryTicket->slug = "capture.recall";
        $categoryTicket->class = "bg-red";
        $categoryTicket->save();

        $categoryTicket = new CategoryTicket;
        $categoryTicket->name = "Solicitud de servicio";
        $categoryTicket->slug = "service.requests";
        $categoryTicket->class = "bg-yellow";
        $categoryTicket->save();

        $categoryTicket = new CategoryTicket;
        $categoryTicket->name = "Cotizaciones";
        $categoryTicket->slug = "quotes";
        $categoryTicket->class = "bg-black";
        $categoryTicket->save();

        $categoryTicket = new CategoryTicket;
        $categoryTicket->name = "PQRS";
        $categoryTicket->slug = "pqrs";
        $categoryTicket->class = "bg-light-blue";
        $categoryTicket->save();

        $categoryTicket = new CategoryTicket;
        $categoryTicket->name = "Solicitud de visita Coordinador";
        $categoryTicket->slug = "coordinator.visit.request";
        $categoryTicket->class = "bg-green";
        $categoryTicket->save();

        $categoryTicket = new CategoryTicket;
        $categoryTicket->name = "Documentos";
        $categoryTicket->slug = "documents";
        $categoryTicket->class = "bg-purple";
        $categoryTicket->save();

    }
}
