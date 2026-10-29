<?php

/*Listar proformas*/
Route::get('billing/proforma-list', 'Administrative\BillingController@listProforma')
->name('billings.listProforma')
->middleware('can:billings.create');

/*Listar*/
Route::get('billing/list', 'Administrative\BillingController@list')
->name('billings.list')
->middleware('can:billings.create');

/*PDF*/
Route::get('billing/format/pdf/{id}', 'Administrative\BillingController@formatPdf')
->name('billings.format-pdf')
->middleware('can:billings.show');


/*Index*/
Route::get('billing', 'Administrative\BillingController@index')
->name('billings.index')
->middleware('can:billings.index');

Route::get('billing/evsf', 'Administrative\BillingController@evsf')
->name('billings.evsf')
->middleware('can:billings.index');

Route::get('billing/evsf-list', 'Administrative\BillingController@evsfList')
->middleware('can:billings.index');

Route::get('billing/state', 'Administrative\BillingController@state')
->name('billings.billingState')
->middleware('can:billings.index');

Route::get('billing/save-state/{states}', 'Administrative\BillingController@storeState')
->name('billings.saveState')
->middleware('can:billings.create');

/******************************************************************************/

/**Guardar*/
Route::post('billing/save', 'Administrative\BillingController@store')
->name('billings.store')
->middleware('can:billings.create');


/**Actualizar*/
Route::put('billing/save', 'Administrative\BillingController@update')
->name('billings.update')
->middleware('can:billings.edit');


/******************************************************************************/

Route::get('billing/form-proforma', 'Administrative\BillingController@formProforma')
->name('billings.formProforma')
->middleware('can:billings.create');

Route::get('billing/customer', 'Administrative\BillingController@customer')
->name('billing.customer')
->middleware('can:billings.create');

/*Crear*/
Route::get('billing/create', 'Administrative\BillingController@create')
->name('billings.create')
->middleware('can:billings.create');

/******************************************************************************/

//FILTRO DE PROFORMAS
Route::get('billing/filters', 'Administrative\BillingController@filters')
->name('billing.filters')
->middleware('can:billings.index');

Route::get('billing/crms', 'Administrative\BillingController@crms')
->name('billing.crms')
->middleware('can:billings.index');

/******************************************************************************/

/**Vista de configuracion*/
Route::get('billing/setting', 'Administrative\BillingController@setting')
->name('billings.setting')
->middleware('can:billings.create');

/******************************************************************************/


//AGREGAR UNA FILA NUEVA DE DETALLE DE FACTURACION
Route::get('billing/add-row', 'Administrative\BillingController@addRow')
->name('billings.addRow')
->middleware('can:billings.create');

//FORMULARIO PARA ELIMINAR UN DETALLE DE FACTURACION
Route::get('billing/delete-row', 'Administrative\BillingController@deleteRow')
->name('billings.deleteRow')
->middleware('can:billings.create');


/******************************************************************************/

