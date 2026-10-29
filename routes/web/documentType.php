<?php 
use Illuminate\Support\Facades\Route;
/******************************************************************************/

/**listas*/
Route::get('documentTypes/list', 'Administrative\DocumentTypeController@list')
->name('documentTypes.list')
->middleware('can:documentTypes.index');

Route::get('documentTypes', 'Administrative\DocumentTypeController@index')
->name('documentTypes.index')
->middleware('can:documentTypes.index');

/******************************************************************************/

/**Formulario*/

Route::get('documentTypes/form', 'Administrative\DocumentTypeController@form')
->name('documentTypes.form')
->middleware('can:documentTypes.create');

Route::get('infestation/services', 'Administrative\DocumentTypeController@services')
->name('documentTypes.services')
->middleware('can:documentTypes.create');

/******************************************************************************/


/**Crear*/

Route::get('documentTypes/document-type', 'Administrative\DocumentTypeController@documentType')
->name('documentTypes.documentType')
->middleware('can:documentTypes.create');


/******************************************************************************/


/**Guardar*/
Route::post('documentTypes/save', 'Administrative\DocumentTypeController@store')
->name('documentTypes.store')
->middleware('can:documentTypes.create');

/******************************************************************************/


/**Actualizar*/
Route::put('documentTypes/save', 'Administrative\DocumentTypeController@update')
->name('documentTypes.update')
->middleware('can:documentTypes.edit');

/******************************************************************************/

/**Eliminar*/
Route::get('documentTypes/delete-form', 'Administrative\DocumentTypeController@deleteForm')
->name('documentTypes.deleteform')
->middleware('can:documentTypes.destroy');

Route::delete('documentTypes/delete', 'Administrative\DocumentTypeController@softDelete')
->name('documentTypes.destroy')
->middleware('can:documentTypes.destroy');

/******************************************************************************/