<?php

use Illuminate\Support\Facades\Route;

Route::get('tickets', 'Administrative\TicketController@tickets')
->name('tickets.tickets')
->middleware('can:ticket.index');

Route::get('tickets/users', 'Administrative\TicketController@users')
->name('tickets.users');

Route::get('tickets/customer', 'Administrative\TicketController@customer')
->name('quotes.customer');

/**formulario de editar cliente*/
Route::get('crm-form-ticket', 'Administrative\CrmController@formTicket')
->name('ticket.create')
->middleware('can:ticket.create');

Route::post('crm/save-ticket', 'Administrative\CrmController@storeTicket')
->name('crms.saveTiket')
->middleware('can:ticket.create');

Route::post('crm/save-ticket-answer', 'Administrative\CrmController@storeTicketAnswer')
->name('crms.saveTiketAnswer')
->middleware('can:ticket.answer');
/**Vista show crm lista de la gestion del crm*/
Route::get('crm-ticket-show', 'Administrative\CrmController@listTicketShow')
->name('crms.showTicket')
->middleware(['can:ticket.view' || 'can:ticket.view.customer']);

Route::post('ticket-state-change', 'Administrative\CrmController@StateChange')
->name('crms.showTicketStateChange')
->middleware('can:ticket.solved');

Route::post('crm-priority-change', 'Administrative\CrmController@PriorityChange')
->name('crms.showTicketStateChange')
->middleware('can:ticket.changed.priority');

Route::get('dowloadFileTicket', 'Administrative\CrmController@dowloadFile')
->name('crms.dowloadFileTicket')
->middleware(['can:ticket.view' || 'can:ticket.view.customer']);
/**Vista show crm lista de Ticket*/
Route::get('crm-tickets-list', 'Administrative\CrmController@listTicketsShow')
->name('crms.showTickets')
->middleware('can:ticket.index.customer');

/**Vista show crm lista de Ticket*/
Route::get('crm-tickets-list-filter', 'Administrative\CrmController@listTicketsFilter')
->name('crms.showTickets')
->middleware('can:ticket.view.customer');