<?php

use App\CategoryTicketHasRoles;
use Illuminate\Database\Seeder;

class CategoryTicketsHasRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CategoryTicketHasRoles::create([
            'category_ticket_id'=>1,
            'rol_id'=>8,
        ]);  
        CategoryTicketHasRoles::create([
            'category_ticket_id'=>1,
            'rol_id'=>10,
        ]);  
        CategoryTicketHasRoles::create([
            'category_ticket_id'=>2,
            'rol_id'=>8,
        ]);  
        CategoryTicketHasRoles::create([
            'category_ticket_id'=>2,
            'rol_id'=>10,
        ]);  
        CategoryTicketHasRoles::create([
            'category_ticket_id'=>3,
            'rol_id'=>8,
        ]);  
        CategoryTicketHasRoles::create([
            'category_ticket_id'=>3,
            'rol_id'=>10,
        ]);  
        CategoryTicketHasRoles::create([
            'category_ticket_id'=>5,
            'rol_id'=>12,
        ]);  
        CategoryTicketHasRoles::create([
            'category_ticket_id'=>5,
            'rol_id'=>6,
        ]);  
        CategoryTicketHasRoles::create([
            'category_ticket_id'=>6,
            'rol_id'=>6,
        ]);  
        CategoryTicketHasRoles::create([
            'category_ticket_id'=>6,
            'rol_id'=>12,
        ]);  
        CategoryTicketHasRoles::create([
            'category_ticket_id'=>7,
            'rol_id'=>6,
        ]);  
        CategoryTicketHasRoles::create([
            'category_ticket_id'=>7,
            'rol_id'=>12,
        ]);  
        CategoryTicketHasRoles::create([
            'category_ticket_id'=>4,
            'rol_id'=>4,
        ]);  
    }
}
