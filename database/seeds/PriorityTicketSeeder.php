<?php

use App\PriorityTicket;
use Illuminate\Database\Seeder;

class PriorityTicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $priorityTicket = new PriorityTicket();
        $priorityTicket->name = "Alta";
        $priorityTicket->full_name = "PRIORIDAD ALTA";
        $priorityTicket->slug = "high";
        $priorityTicket->class = "danger";
        $priorityTicket->save();

        $priorityTicket = new PriorityTicket();
        $priorityTicket->name = "Baja";
        $priorityTicket->full_name = "PRIORIDAD BAJA";
        $priorityTicket->slug = "low";
        $priorityTicket->class = "primary";
        $priorityTicket->save();

        $priorityTicket = new PriorityTicket();
        $priorityTicket->name = "Media";
        $priorityTicket->full_name = "PRIORIDAD MEDIA";
        $priorityTicket->slug = "medium";
        $priorityTicket->class = "warning";
        $priorityTicket->save();
    
    }
}
