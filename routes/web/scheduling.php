<?php
use Illuminate\Support\Facades\Route;
/******************************************************************************/

/**Guardar*/
Route::post('scheduling/store', 'Administrative\SchedulingController@store')
        ->name('scheduling.store')
        ->middleware('can:scheduling.create');

/******************************************************************************/

/**Actualizar*/
Route::post('scheduling/update', 'Administrative\SchedulingController@update')
        ->name('scheduling.update')
        ->middleware('can:scheduling.edit');

Route::get('scheduling/update-date', 'Administrative\SchedulingController@updateDate')
    ->name('scheduling.updatedate')
    ->middleware('can:scheduling.edit');

Route::get('scheduling/update-date-r', 'Administrative\SchedulingController@updateDateR')
    ->middleware('can:scheduling.edit');


/******************************************************************************/
      
/**Eliminar*/
Route::delete('scheduling/{id}/delete', 'Administrative\SchedulingController@softDelete')
        ->name('scheduling.destroy')
        ->middleware('can:scheduling.destroy');

Route::get('scheduling/{id}/hardelete', 'Administrative\SchedulingController@hardDeleleteScheduling')
        ->middleware('can:scheduling.automation');


/******************************************************************************/

/**formularios*/
Route::get('scheduling/types', 'Administrative\SchedulingController@types')
        ->name('scheduling.types')
        ->middleware('can:scheduling.index');

Route::get('scheduling/statuses', 'Administrative\SchedulingController@statuses')
        ->middleware('can:scheduling.index');

Route::get('scheduling/crms', 'Administrative\SchedulingController@crms')
        ->name('scheduling.crms')
        ->middleware('can:scheduling.index');

Route::get('scheduling/users', 'Administrative\SchedulingController@users')
        ->name('scheduling.users')
        ->middleware('can:scheduling.index');

Route::get('scheduling/technicals', 'Administrative\SchedulingController@technicals')
        ->name('scheduling.technicals')
        ->middleware('can:scheduling.index');

Route::get('scheduling/filters', 'Administrative\SchedulingController@filters')
        ->name('scheduling.filters')
        ->middleware('can:scheduling.index');

Route::get('scheduling/form', 'Administrative\SchedulingController@form')
        ->name('scheduling.form')
        ->middleware('can:scheduling.create');

Route::get('scheduling/subform', 'Administrative\SchedulingController@subform')
        ->name('scheduling.subform')
        ->middleware('can:scheduling.create');

Route::get('scheduling/crms-offices', 'Administrative\SchedulingController@crmsOffices')
        ->name('scheduling.crmsoffices')
        ->middleware('can:scheduling.create');

Route::get('scheduling/crms-contacts', 'Administrative\SchedulingController@crmsContacts')
        ->name('scheduling.crmsoffices')
        ->middleware('can:scheduling.create');

Route::get('scheduling/services', 'Administrative\SchedulingController@services')
        ->name('scheduling.tabservices');

Route::get('scheduling/area-info', 'Administrative\SchedulingController@areaInfo')
        ->name('scheduling.areas')
        ->middleware('can:scheduling.create');

Route::get('scheduling/product-info', 'Administrative\SchedulingController@productInfo')
        ->name('scheduling.products')
        ->middleware('can:scheduling.create');

Route::get('scheduling/areas', 'Administrative\SchedulingController@areas')
        ->name('scheduling.schedulingoffices')
        ->middleware('can:scheduling.create');

Route::get('scheduling/roles', 'Administrative\SchedulingController@roles')
        ->name('scheduling.schedulingroles')
        ->middleware('can:scheduling.create');

Route::get('scheduling/products/{service}', 'Administrative\SchedulingController@products')
        ->name('scheduling.schedulingproducts')
        ->middleware('can:scheduling.create');

Route::get('scheduling/tab-services', 'Administrative\SchedulingController@tabServices')
        ->name('scheduling.tabservices')
        ->middleware('can:scheduling.create');

Route::get('scheduling/add-observation-row', 'Administrative\SchedulingController@addObservationRow')
        ->name('scheduling.addobservationrow')
        ->middleware('can:scheduling.create');

Route::get('scheduling/delete-observation-form', 'Administrative\SchedulingController@deleteObservationForm')
        ->name('scheduling.deleteobservationform')
        ->middleware('can:scheduling.create');

Route::get('scheduling/add-supply-row', 'Administrative\SchedulingController@addSupplyRow')
        ->name('scheduling.addsupplyrow')
        ->middleware('can:scheduling.create');

Route::get('scheduling/delete-supply-form', 'Administrative\SchedulingController@deleteSupplyForm')
        ->name('scheduling.deletesupplyform')
        ->middleware('can:scheduling.create');

Route::get('scheduling/add-task-row', 'Administrative\SchedulingController@addTaskRow')
        ->name('scheduling.addtaskrow')
        ->middleware('can:scheduling.create');

Route::get('scheduling/delete-task-form', 'Administrative\SchedulingController@deleteTaskForm')
        ->name('scheduling.deletetaskform')
        ->middleware('can:scheduling.create');

Route::get('scheduling/availabilities', 'Administrative\SchedulingController@availabilities')
    ->name('scheduling.availabilities')
    ->middleware('can:scheduling.automation');

Route::get('scheduling/services', 'Administrative\SchedulingController@services')
    ->name('scheduling.services');

Route::get('scheduling/statuses', 'Administrative\SchedulingController@statuses')
    ->name('scheduling.statuses')
    ->middleware('can:scheduling.index');

Route::get('scheduling/routes', 'Administrative\SchedulingController@routes')
    ->name('scheduling.routes')
    ->middleware('can:scheduling.automation');

Route::get('scheduling/execution', 'Administrative\SchedulingController@execution')
    ->name('scheduling.execution')
    ->middleware('can:scheduling.automation');

Route::get('scheduling/route/{route}/editForm', 'Administrative\SchedulingController@editRouteForm')
    ->middleware('can:scheduling.automation');

Route::put('scheduling/route/{route}/edit', 'Administrative\SchedulingController@editRoute')
    ->middleware('can:scheduling.automation');


Route::get('scheduling/route/{route}/delete', 'Administrative\SchedulingController@deleteRoute')
    ->name('scheduling.routeDelete')
    ->middleware('can:scheduling.automation');

Route::get('scheduling/route/{route}/show', 'Administrative\SchedulingController@route')
    ->name('scheduling.route')
    ->middleware('can:scheduling.automation');

Route::get('scheduling/route/{route}/send-form', 'Administrative\SchedulingController@sendForm')
    ->name('scheduling.routeSendForm')
    ->middleware('can:scheduling.automation');

Route::post('scheduling/route/{route}/route-send', 'Administrative\SchedulingController@routeSend')
    ->name('scheduling.routeSend')
    ->middleware('can:scheduling.automation');

Route::get('scheduling/automation/list', 'Administrative\SchedulingController@automationList')
    ->name('scheduling.automationList')
    ->middleware('can:scheduling.automation');

Route::get('scheduling/routes/list', 'Administrative\SchedulingController@routesList')
    ->name('scheduling.routeList')
    ->middleware('can:scheduling.automation');

Route::get('scheduling/routes/execution', 'Administrative\SchedulingController@routesExcecution')
    ->middleware('can:scheduling.automation');

Route::get('scheduling/hours/settlement', 'Administrative\SchedulingController@settlementHours')
    ->middleware('can:scheduling.automation');

Route::get('scheduling/execution/mark-as-ok/{scheduling}', 'Administrative\SchedulingController@markAsOK')
    ->middleware('can:scheduling.automation');

Route::get('scheduling/programmer', 'Administrative\SchedulingController@programmer')
        ->name('scheduling.programmer')
        ->middleware('can:scheduling.index');

Route::get('scheduling/hours', 'Administrative\SchedulingController@hours')
        ->name('scheduling.hours')
        ->middleware('can:scheduling.index');

Route::get('scheduling/deleteFromProgrammer', 'Administrative\SchedulingController@deleteEvent')
        ->middleware('can:scheduling.automation');

Route::get('scheduling/programmer/resources', 'Administrative\SchedulingController@programmerResources')
        ->middleware('can:scheduling.index');

Route::post('scheduling/programmer/data', 'Administrative\SchedulingController@programmerData')
        ->middleware('can:scheduling.index');

Route::get('scheduling/callsheet/tracking/{tracking}/delete', 'Administrative\SchedulingController@deleteTracking')
        ->middleware('can:scheduling.index');

Route::get('scheduling/automation/covert-white-spaces','Administrative\SchedulingController@covertWhiteSpaces');       

Route::post('scheduling/automation/save', 'Administrative\SchedulingController@saveAutomation')
    ->name('scheduling.saveAutomation')
    ->middleware('can:scheduling.automation');


Route::post('scheduling/automation/manualsave', 'Administrative\SchedulingController@saveManualAutomation')
->name('scheduling.saveManualAutomation')
->middleware('can:scheduling.automation');

Route::get('scheduling/automation/users-availabilities', 'Administrative\SchedulingController@userAvailabilities')
    ->name('scheduling.availabilitiesuser')
    ->middleware('can:scheduling.automation');

Route::post('scheduling/automation/validate-step/{step}', 'Administrative\SchedulingController@validateStep')
    ->name('scheduling.automationvalidatestep')
    ->middleware('can:scheduling.automation');

Route::post('scheduling/automation/get-step/1', 'Administrative\SchedulingController@getCustomerStep')
    ->name('scheduling.step1')
    ->middleware('can:scheduling.automation');

Route::post('scheduling/automation/get-step/11', 'Administrative\SchedulingController@getManualCalendarStep')
    ->name('scheduling.step11')
    ->middleware('can:scheduling.automation');

Route::post('scheduling/automation/get-step/12', 'Administrative\SchedulingController@getManualResumeStep')
    ->name('scheduling.step11')
    ->middleware('can:scheduling.automation');

    

Route::post('scheduling/automation/get-step/2', 'Administrative\SchedulingController@getAutomationStep')
    ->name('scheduling.step2')
    ->middleware('can:scheduling.automation');

Route::post('scheduling/automation/get-step/3', 'Administrative\SchedulingController@getCalendarStep')
    ->name('scheduling.step3')
    ->middleware('can:scheduling.automation');

Route::post('scheduling/automation/get-step/4', 'Administrative\SchedulingController@getResumeStep')
    ->name('scheduling.step4')
    ->middleware('can:scheduling.automation');

Route::get('crm/scheduling/automation/update-date', 'Administrative\SchedulingController@updateAutomationDate')
        ->middleware('can:scheduling.automation');

Route::get('scheduling/dispatch/{user}', 'Administrative\SchedulingController@dispatcher')
        ->middleware('can:scheduling.automation');
    


/******************************************************************************/

/**listas*/
Route::get('scheduling/list', 'Administrative\SchedulingController@list')
        ->name('scheduling.list')
        ->middleware('can:scheduling.index');

Route::get('scheduling', 'Administrative\SchedulingController@index')
        ->name('scheduling.index')
        ->middleware('can:scheduling.index');

/******************************************************************************/

/**vistas */

Route::get('scheduling/showfile', 'FileController@show')
        ->name('scheduling.showfile')
        ->middleware('can:scheduling.show');

Route::post('scheduling/agenda', 'Administrative\SchedulingController@agenda')
        ->name('scheduling.agenda')
        ->middleware('can:scheduling.index');

Route::get('scheduling/create', 'Administrative\SchedulingController@create')
        ->name('scheduling.create')
        ->middleware('can:scheduling.create');

Route::get('scheduling/automation', 'Administrative\SchedulingController@automation')
    ->name('scheduling.automation')
    ->middleware('can:scheduling.automation');

Route::get('scheduling/automation/create', 'Administrative\SchedulingController@automationCreate')
    ->name('scheduling.automationCreate')
    ->middleware('can:scheduling.automation');

Route::get('scheduling/automation/{id}/edit', 'Administrative\SchedulingController@automationCreate')
    ->name('automation.edit')
    ->middleware('can:scheduling.automation');

Route::get('scheduling/automation/manual', 'Administrative\SchedulingController@automationManual')
    ->name('scheduling.automationManual')
    ->middleware('can:scheduling.automation');

Route::get('scheduling/callsheet', 'Administrative\SchedulingController@callsheet')
    ->name('scheduling.callsheet')
    ->middleware('can:scheduling.automation');

Route::get('scheduling/sheet/list', 'Administrative\SchedulingController@sheetList')
    ->middleware('can:scheduling.automation');

Route::get('scheduling/callsheet/status-form', 'Administrative\SchedulingController@statusForm')
    ->middleware('can:scheduling.automation');

Route::get('scheduling/execution/status-form', 'Administrative\SchedulingController@statusFormSchedulings')
    ->middleware('can:scheduling.automation');

Route::get('scheduling/execution/reports', 'Administrative\SchedulingController@reports');

Route::get('scheduling/execution/expenses', 'Administrative\SchedulingController@expenses')
    ->middleware('can:scheduling.automation');
    
Route::get('scheduling/callsheet/status-multiple-form', 'Administrative\SchedulingController@statusMultipleForm')
    ->middleware('can:scheduling.automation');

Route::get('scheduling/callsheet/confirm-request', 'Administrative\SchedulingController@confirmRequest')
    ->middleware('can:scheduling.automation');
    
Route::put('scheduling/callsheet/status', 'Administrative\SchedulingController@updateStatus')
    ->middleware('can:scheduling.automation');

Route::put('scheduling/callsheet/statusScheduling', 'Administrative\SchedulingController@updateStatusScheduling')
    ->middleware('can:scheduling.automation');

Route::get('scheduling/callsheet/event-form', 'Administrative\SchedulingController@eventForm')
->middleware('can:scheduling.automation');


Route::post('scheduling/callsheet/save-event', 'Administrative\SchedulingController@saveEvent')
->middleware('can:scheduling.automation');


Route::get('scheduling/{schedule}', 'Administrative\SchedulingController@show')
        ->name('scheduling.show')
        ->middleware('can:scheduling.show');

Route::get('scheduling/{schedule}/edit', 'Administrative\SchedulingController@edit')
        ->name('scheduling.edit')
        ->middleware('can:scheduling.edit');


Route::get('scheduling/type/{type}/typeframe', 'Administrative\SchedulingController@typeFrame');

/******************************************************************************/

/** vistas de recordatorios desde otros módulos */
Route::get('scheduling/add-quote-event/quote/{quote}/user/{user}/notification/{notification}', 'Administrative\SchedulingController@addEventQuote')
        ->name('sheduling.add-quote-event');

