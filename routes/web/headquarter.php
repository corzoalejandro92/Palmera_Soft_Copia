<?php
use Illuminate\Support\Facades\Route;

/******************************************************************************/

/**Guardar*/
Route::post('headquarters/save', 'Administrative\HeadquarterController@store')
        ->name('headquarters.store')
        ->middleware('can:headquarters.create');

Route::post('headquarters/zone/save', 'Administrative\HeadquarterController@storeZone')
        ->name('headquarters.storezone')
        ->middleware('can:headquarters.create');


/******************************************************************************/

/**Actualizar*/
Route::put('headquarters/save', 'Administrative\HeadquarterController@update')
        ->name('headquarters.update')
        ->middleware('can:headquarters.edit');

Route::put('headquarters/zone/save', 'Administrative\HeadquarterController@updateZone')
        ->name('headquarters.updadteZone')
        ->middleware('can:headquarters.edit');
/******************************************************************************/
      
/**Eliminar*/
Route::delete('headquarters/delete', 'Administrative\HeadquarterController@softDelete')
        ->name('headquarters.destroy')
        ->middleware('can:headquarters.destroy');

Route::delete('headquarters/zone/delete', 'Administrative\HeadquarterController@destroyZone')
        ->name('headquarters.destroyZone')
        ->middleware('can:headquarters.destroy');

/******************************************************************************/

/**formularios*/
Route::get('headquarters/roles', 'Administrative\HeadquarterController@roles')
        ->name('headquarters.roles')
        ->middleware('can:headquarters.create');

Route::get('headquarters/headquarters', 'Administrative\HeadquarterController@headquarters')
        ->name('headquarters.headquarters')
        ->middleware('can:headquarters.create');

Route::get('headquarters/document-form', 'Administrative\HeadquarterController@documentForm')
        ->name('headquarters.documentform')
        ->middleware('can:headquarters.create');

Route::get('headquarters/add-document-row', 'Administrative\HeadquarterController@addDocumentRow')
        ->name('headquarters.documentrow')
        ->middleware('can:headquarters.create');

Route::get('headquarters/form', 'Administrative\HeadquarterController@form')
        ->name('headquarters.form')
        ->middleware('can:headquarters.index');

Route::get('headquarters/zone/form', 'Administrative\HeadquarterController@formZone')
        ->name('headquarters.formZones')
        ->middleware('can:headquarters.create');

Route::get('headquarters/delete-form', 'Administrative\HeadquarterController@deleteForm')
        ->name('headquarters.deleteform')
        ->middleware('can:headquarters.destroy');

Route::get('headquarters/zone/delete-form', 'Administrative\HeadquarterController@deleteFormZone')
        ->name('headquarters.deleteformZone')
        ->middleware('can:headquarters.destroy');

Route::get('crms/switch/{id}', 'Administrative\HeadquarterController@switchCrm')
        ->name('crms.switch');

Route::get('headquarters/switch/{id}', 'Administrative\HeadquarterController@switch')
        ->name('headquarters.switch')
        ->middleware('can:headquarters.switch');
/******************************************************************************/

/**listas*/
Route::get('headquarters/list', 'Administrative\HeadquarterController@list')
        ->name('headquarters.list')
        ->middleware('can:headquarters.index');

Route::get('headquarters', 'Administrative\HeadquarterController@index')
        ->name('headquarters.index')
        ->middleware('can:headquarters.index');

/******************************************************************************/

/**vistas */

Route::get('headquarters/showfile', 'Administrative\HeadquarterController@showFile')
        ->name('headquarters.showfile')
        ->middleware('can:headquarters.show');

Route::get('headquarters/create', 'Administrative\HeadquarterController@create')
        ->name('headquarters.create')
        ->middleware('can:headquarters.create');

Route::get('headquarters/{headquarter}', 'Administrative\HeadquarterController@show')
        ->name('headquarters.show')
        ->middleware('can:headquarters.show');

Route::get('headquarters/{headquarter}/edit', 'Administrative\HeadquarterController@edit')
        ->name('headquarters.edit')
        ->middleware('can:headquarters.edit');

/******************************************************************************/
