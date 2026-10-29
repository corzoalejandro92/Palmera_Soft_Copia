<?php 
use Illuminate\Support\Facades\Route;
/******************************************************************************/

/**listas*/
Route::get('infestations/list', 'Administrative\InfestationController@list')
->name('infestations.list')
->middleware('can:infestations.index');

Route::get('infestations', 'Administrative\InfestationController@index')
->name('infestations.index')
->middleware('can:infestations.index');

/******************************************************************************/

/**Formulario*/

Route::get('infestations/form', 'Administrative\InfestationController@form')
->name('infestations.form')
->middleware(['can:infestations.create' || 'can:infestations.edit']);

Route::get('infestations/services', 'Administrative\InfestationController@services')
->name('infestations.services')
->middleware('can:infestations.create');

Route::get('infestations/document-form', 'Administrative\InfestationController@documentForm')
->name('infestations.documentform')
->middleware('can:infestations.create');

Route::get('infestations/add-document-row', 'Administrative\InfestationController@addDocumentRow')
->name('infestations.documentrow')
->middleware('can:infestations.create');



/******************************************************************************/


/**Guardar*/
Route::post('infestations/save', 'Administrative\InfestationController@store')
->name('infestations.storeform')
->middleware('can:infestations.create');

Route::post('infestations/store', 'Administrative\InfestationController@store')
->name('infestations.store')
->middleware('can:infestations.create');

/******************************************************************************/


/**Actualizar*/
Route::put('infestations/save', 'Administrative\InfestationController@update')
->name('infestations.updateform')
->middleware('can:infestations.edit');

Route::post('infestations/update', 'Administrative\InfestationController@update')
->name('infestations.update')
->middleware('can:infestations.edit');

/******************************************************************************/

/**Eliminar*/
Route::get('infestations/delete-form', 'Administrative\InfestationController@deleteForm')
->name('infestations.deleteform')
->middleware('can:infestations.destroy');

Route::delete('infestations/delete', 'Administrative\InfestationController@softDelete')
->name('infestations.destroy')
->middleware('can:infestations.destroy');

Route::get('infestations/document', 'Administrative\InfestationController@deleteDocumentoForm')
->name('infestations.documentDeleteform')
->middleware('can:infestations.destroy');

Route::delete('infestations/delete-doc', 'Administrative\InfestationController@softDeleteDocument')
->name('infestations.destroyDocument')
->middleware('can:infestations.destroy');

/******************************************************************************/

/**Ver el archivo de las imagenes de las plagas*/
Route::get('infestations/showfile', 'FileController@show')
        ->name('infestations.showfile')
        ->middleware('can:infestations.show');
/******************************************************************************/
