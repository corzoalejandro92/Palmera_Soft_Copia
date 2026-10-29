<?php

use Illuminate\Support\Facades\Route;
/**Eliminar*/
Route::get('services/delete-form', 'Administrative\ServiceController@deleteForm')
->name('services.deleteform')
->middleware('can:services.destroy');

Route::delete('services/delete', 'Administrative\ServiceController@softDelete')
->name('services.destroy')
->middleware('can:services.destroy');

/**Vista crear*/
Route::get('services/form-service', 'Administrative\ServiceController@formService')
->name('services.create')
->middleware('can:services.index');

Route::get('service/infestations', 'Administrative\ServiceController@infestations')
->name('services.create')
->middleware('can:services.index');


Route::get('services/create', 'Administrative\ServiceController@create')
->name('services.create')
->middleware('can:services.create');

Route::get('services/headquarters', 'Administrative\ServiceController@headquarters')
->name('services.headquarters')
->middleware('can:services.create');


/**Vista listar*/
Route::get('services/form-list','Administrative\ServiceController@list')
->name('services.form-list')
->middleware('can:services.index');

Route::get('services','Administrative\ServiceController@index')
->name('services.index')
->middleware('can:services.index');

Route::get('services/add-coverage-row','Administrative\ServiceController@addCoverageRow')
->middleware('can:services.index');

Route::get('services/add-parameter-row','Administrative\ServiceController@addParameterRow')
->middleware('can:services.index');

Route::get('services/delete-coverage-form','Administrative\ServiceController@deleteCoverageForm')
->middleware('can:services.index');

Route::get('services/delete-parameter-form','Administrative\ServiceController@deleteParameterForm')
->middleware('can:services.index');

Route::delete('services/delete-coverage', 'Administrative\ServiceController@deleteCoverage')
    ->name('services.delete-coverage')
    ->middleware('can:services.destroy');

Route::delete('services/delete-parameter', 'Administrative\ServiceController@deleteParameter')
    ->name('services.delete-coverage')
    ->middleware('can:services.destroy');



/**Vista detalles*/
Route::get('services/{service}', ['as'=> 'servicesDetails', 'uses'=> 'Administrative\ServiceController@show'])
->name('services.show')
->middleware('can:services.show');


/**Vista editar*/
Route::get('services/{service}/editar', 'Administrative\ServiceController@edit')
->name('services.edit')
->middleware('can:services.edit');



/**Guardar*/
Route::post('services/save', 'Administrative\ServiceController@store')
->name('services.store')
->middleware('can:services.create');


/**Actualizar*/
Route::put('services/save', 'Administrative\ServiceController@update')
->name('services.update')
->middleware('can:services.edit');