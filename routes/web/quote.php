<?php

use Illuminate\Support\Facades\Route;

/**Guardar*/
Route::post('quotes/save', 'Administrative\QuoteController@store')
    ->name('quotes.store')
    ->middleware('can:quotes.create');

Route::post('quotes/contract', 'Administrative\QuoteController@saveContract')
->name('quotes.saveContract')
->middleware('can:quotes.contract');

Route::get('quotes/{quote}/save-service', 'Administrative\QuoteController@saveServices')
    ->name('quotes.storeservice')
    ->middleware('can:quotes.supplies');

Route::get('quotes/{quote}/disable-service', 'Administrative\QuoteController@disableServices')
    ->name('quotes.disableservice')
    ->middleware('can:quotes.supplies');

Route::get('quotes/{quote}/restore-services', 'Administrative\QuoteController@restoreServices')
    ->name('quotes.restoreservice')
    ->middleware('can:quotes.supplies');

/******************************************************************************/


/******************************************************************************/

/**Actualizar Area PLan*/
Route::post('quotes/update-area-plan', 'Administrative\QuoteController@storeplanos')
->name('quotes.update')
->middleware('can:quotes.plans');



/**Actualizar*/
Route::put('quotes/save', 'Administrative\QuoteController@update')
    ->name('quotes.update')
    ->middleware('can:quotes.edit');



Route::put('quotes/save/supplies', 'Administrative\QuoteController@storeSupplies')
    ->name('quotes.storesuplies')
    ->middleware('can:quotes.supplies');

/*actualizar el estado de la cotización*/
Route::put('quotes/status', 'Administrative\QuoteController@updateStatus')
->name('quotes.updateStatus');



/******************************************************************************/
Route::get('quotes', 'Administrative\QuoteController@index')
    ->name('quotes.index')
    ->middleware('can:quotes.index');

/**listas*/
/* Route::get('quotes/list', 'Administrative\QuoteController@list')
->name('quotes.list')
->middleware('can:quotes.index'); */

Route::get('quotes/listConfig', 'Administrative\QuoteController@listConfig')
->name('quotes.list')
->middleware('can:quotes.index');

Route::get('quotes/config/{id}/delete', 'Administrative\QuoteController@deleteConfig')
->middleware('can:quotes.index');

/**listas de areas*/
Route::get('quotes/area-list', 'Administrative\QuoteController@areaList')
->name('quotes.listArea')
->middleware('can:quotes.plans');


/******************************************************************************/

//Query de tipos de planos

Route::get('quotes/planos-list', 'Administrative\QuoteController@typeFilePlan')
->name('quotes.planoslist')
->middleware('can:quotes.index');



/******************************************************************************/

Route::get('quotes/email-contact', 'Administrative\QuoteController@emailContact')
->name('quotes.emailContact')
->middleware('can:quotes.create');

/******************************************************************************/
//Query de tipos de documentos de adjuntar diagnosticos

Route::get('quotes/type-documento-diagnosis', 'Administrative\QuoteController@typeFilePlan')
->name('quotes.documentoDiagnosis')
->middleware('can:quotes.index');


/**Guardar el documentos de diagnostico*/
Route::post('quotes/save-quote-file', 'Administrative\QuoteController@storeDocumentoQuote')
    ->name('quotes.storeDocumentoDiagnosis')
    ->middleware('can:quotes.create');

Route::get('quote/showfile', 'FileController@show')
        ->name('quote.showfile');

Route::get('quote/deleteFile','Administrative\QuoteController@deleteQFile')
->middleware('can:quotes.create');

/******************************************************************************/


/**formularios **/

Route::get('quotes/config-form', 'Administrative\QuoteController@configForm')
    ->name('quotes.configForm')
    ->middleware('can:quotes.config');

Route::get('quotes/block-content/{block}', 'Administrative\QuoteController@blockContent')
->name('quotes.blockContent')
->middleware('can:quotes.config');

Route::get('quotes/headquarters', 'Administrative\QuoteController@headquarters')
->name('quotes.blockContent')
->middleware('can:quotes.config');

Route::post('quotes/config/save-template', 'Administrative\QuoteController@storeTemplate')
->name('quotes.saveTemplate')
->middleware('can:quotes.config');

Route::put('quotes/config/save-template', 'Administrative\QuoteController@updateTemplate')
->name('quotes.saveTemplate')
->middleware('can:quotes.config');

Route::post('quotes/config/{template}/delete', 'Administrative\QuoteController@deleteTemplate')
->name('quotes.saveTemplate')
->middleware('can:quotes.config');

Route::get('quotes/templateFrame', 'Administrative\QuoteController@templateFrame')
    ->name('quotes.templateFrame')
    ->middleware('can:quotes.create');

Route::get('quotes/formats', 'Administrative\QuoteController@formats')
    ->name('quotes.formarts')
    ->middleware('can:quotes.create');

Route::get('quotes/format/{format}', 'Administrative\QuoteController@format')
    ->name('quotes.formarts')
    ->middleware('can:quotes.create');

Route::get('quotes/add-service-row', 'Administrative\QuoteController@addServiceRow')
    ->name('quotes.addservicerow')
    ->middleware('can:quotes.create');

Route::get('quotes/services', 'Administrative\QuoteController@services')
    ->name('quotes.services');

Route::get('quotes/infestations', 'Administrative\QuoteController@infestations')
    ->name('quotes.infestations');

Route::get('quotes/service/{service}/parameters', 'Administrative\QuoteController@parameters')
    ->middleware('can:quotes.create');

Route::get('quotes/resume', 'Administrative\QuoteController@resume')
    ->name('quotes.resume')
    ->middleware('can:quotes.create');

Route::get('quotes/supplies-form', 'Administrative\QuoteController@suppliesForm')
    ->name('quotes.suppliesform')
    ->middleware('can:quotes.supplies');

Route::get('quotes/status-form', 'Administrative\QuoteController@statusForm')
    ->name('quotes.statusform');

Route::get('quotes/contract-form', 'Administrative\QuoteController@contractForm')
    ->name('quotes.contractform')
    ->middleware('can:quotes.contract');

Route::get('quotes/areas-customer', 'Administrative\QuoteController@areasCustomer')
->name('quotes.areasCustomer')
->middleware('can:quotes.plans');

Route::get('quotes/email-send', 'Administrative\QuoteController@sendEmail')
->name('quotes.sendEmail')
->middleware('can:quotes.send');

Route::get('quotes/document-diagnosis', 'Administrative\QuoteController@documentDiagnosis')
->name('quotes.documentDiagnosis')
->middleware('can:quotes.create');

Route::get('quotes/areas/{service}', 'Administrative\QuoteController@coverages')
    ->name('quotes.areas');

Route::get('quotes/periodicities', 'Administrative\QuoteController@periodicities')
    ->name('quotes.products')
    ->middleware('can:quotes.index');

    Route::get('config/areas', 'Administrative\QuoteController@crmAreas')
    ->name('quotes.areas');

Route::get('quotes/add-supply-row', 'Administrative\QuoteController@addSupplyRow')
    ->name('quotes.addsupplyrow')
    ->middleware('can:quotes.supplies');

Route::get('quotes/delete-service-form', 'Administrative\QuoteController@deleteServiceForm')
    ->name('quotes.deleteserviceform')
    ->middleware('can:quotes.create');

Route::get('quotes/delete-supply-form', 'Administrative\QuoteController@deleteSupplyForm')
    ->name('quotes.deletesupplyform')
    ->middleware('can:quotes.supplies');

Route::get('quotes/products/category/{category}/control/{infestation}', 'Administrative\QuoteController@products')
    ->name('quotes.products');

Route::get('quotes/products/applications', 'Administrative\QuoteController@applications')
    ->name('quotes.applications')
    ->middleware('can:quotes.index');

Route::get('quotes/products/supplies/{service}', 'Administrative\QuoteController@supplies')
    ->name('quotes.supplies');

Route::get('quotes/service/add-control-frame', 'Administrative\QuoteController@addControlFrame')
    ->name('quotes.addControlFrame')
    ->middleware('can:quotes.index');
    

Route::get('quotes/customer', 'Administrative\QuoteController@customer')
->name('quotes.customer')
->middleware('can:quotes.create');

Route::get('quotes/lis-contact/{customerContact}', 'Administrative\QuoteController@contactCustomer')
->name('quotes.lisContact')
->middleware('can:quotes.create');

Route::get('quotes/offices/{customerOffices}', 'Administrative\QuoteController@offices')
->name('quotes.offices')
->middleware('can:quotes.create');

Route::get('quotes/coordinator', 'Administrative\QuoteController@coordinator')
->name('quotes.coordinador')
->middleware('can:quotes.create');

Route::get('quotes/validate-step/{step}', 'Administrative\QuoteController@validateStep')
    ->name('quotes.validatestep');

/******************************************************************************/
/**Eliminar*/
Route::delete('quotes/delete-supply', 'Administrative\QuoteController@deleteSupply')
    ->name('quotes.deletesupply')
    ->middleware('can:quotes.supplies');


/******************************************************************************/

/**vistas */

Route::get('quotes/config', 'Administrative\QuoteController@config')
->name('quotes.config')
->middleware('can:quotes.config');

Route::get('quotes/create', 'Administrative\QuoteController@create')
    ->name('quotes.create')
    ->middleware('can:quotes.create');

Route::get('quotes/{quote}', 'Administrative\QuoteController@edit')
    ->name('quotes.edit')
    ->middleware('can:quotes.edit');


/******************************************************************************/

/**add formulario de carga de planos**/

Route::get('quote/add-document-row', 'Administrative\QuoteController@addPlanRow')
->name('quotes.addPlanRow')
->middleware('can:quotes.plans');

/******************************************************************************/

Route::get('quote/showfile', 'FileController@show')
->name('quote.showfile');




/******************************************************************************/
 
Route::get('quote/pdf/{id}', 'Administrative\QuoteController@generalPdf')
->name('quote.general-pdf')
->middleware('can:quotes.show');

Route::get('quote/format/pdf/{id}', 'Administrative\QuoteController@formatPdf')
->name('quote.format-pdf')
->middleware('can:quotes.show');


/************************************************************************** */

Route::get('quote/{quote}/send-form', 'Administrative\QuoteController@sendForm')
->name('quote.send-form')
->middleware('can:quotes.send');

Route::post('quote/{quote}/send', 'Administrative\QuoteController@send')
->name('quote.send')
->middleware('can:quotes.send');

Route::put('quotes/apply-format', 'Administrative\QuoteController@aplyFormat')
    ->name('quotes.updateformat')
    ->middleware('can:quotes.edit');


Route::get('quote/{quote}/delete', 'Administrative\QuoteController@destroy')
    ->middleware('can:quotes.edit');