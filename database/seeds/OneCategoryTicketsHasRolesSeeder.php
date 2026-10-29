<?php

use App\CategoryTicketHasRoles;
use Illuminate\Database\Seeder;

class OneCategoryTicketsHasRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CategoryTicketHasRoles::create([
            'category_ticket_id'=>8,
            'rol_id'=>6,
        ]);  
        CategoryTicketHasRoles::create([
            'category_ticket_id'=>8,
            'rol_id'=>12,
        ]);  
    }
}
