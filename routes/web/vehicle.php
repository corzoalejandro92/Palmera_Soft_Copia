<?php

/******************************************************************************/

/**Eliminar*/
Route::get('vehicle/delete-form', 'Administrative\VehicleController@deleteForm')
->name('vehicles.deleteform')
->middleware('can:vehicles.destroy');

Route::get('vehicle/document', 'Administrative\VehicleController@deleteDocumentoForm')
->name('vehicles.documentDeleteform')
->middleware('can:vehicles.destroy');

Route::delete('vehicle/delete-doc', 'Administrative\VehicleController@softDeleteDocument')
->name('vehicles.destroyDocument')
->middleware('can:vehicles.destroy');

Route::delete('vehicle/delete', 'Administrative\VehicleController@softDelete')
->name('vehicles.destroy')
->middleware('can:vehicles.destroy');

/******************************************************************************/

/**Vista listar*/
Route::get('vehicles/form-list', 'Administrative\VehicleController@list')
->name('vehicles.index')
->middleware('can:vehicles.index');

Route::get('vehicles', 'Administrative\VehicleController@index')
->name('vehicles.index')
->middleware('can:vehicles.index');

/******************************************************************************/

/**Vista crear*/
Route::get('vehicles/form-vehicle', 'Administrative\VehicleController@formVehicle')
->name('vehicles.create')
->middleware('can:vehicles.index');


Route::get('vehicles/type', 'Administrative\VehicleController@typeVehicle')
->name('vehicles.vehicleType')
->middleware('can:vehicles.create');

Route::get('vehicles/headquarter', 'Administrative\VehicleController@headquarter')
->name('vehicles.vehicleheadquarter')
->middleware('can:vehicles.create');

Route::get('vehicles/user', 'Administrative\VehicleController@user')
->name('vehicles.vehicleUser')
->middleware('can:vehicles.create');


Route::get('vehicles/document-form', 'Administrative\VehicleController@documentForm')
->name('vehicles.documentform')
->middleware('can:vehicles.create');

Route::get('vehicles/add-document-row', 'Administrative\VehicleController@addDocumentRow')
->name('vehicles.documentrow')
->middleware('can:vehicles.create');

/******************************************************************************/

/**Guardar*/
Route::post('vehicles/save', 'Administrative\VehicleController@store')
->name('vehicles.store')
->middleware('can:vehicles.create');

Route::get('vehicles/save-user/{vehicle}', 'Administrative\VehicleController@storeUser')
->name('vehicles.saveUser')
->middleware('can:vehicles.create');

/******************************************************************************/

/**Actualizar*/
Route::post('vehicles/update', 'Administrative\VehicleController@update')
->name('vehicles.update')
->middleware('can:vehicles.edit');

/******************************************************************************/

/**Ver el archivo de los documentos del vehiculo*/
Route::get('vehicles/showfile', 'FileController@show')
        ->name('vehicles.showfile')
        ->middleware('can:vehicles.show');
/******************************************************************************/
