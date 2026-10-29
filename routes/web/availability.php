<?php

/******************************************************************************/

/**Guardar*/
Route::post('availability/save', 'Administrative\AvailabilityController@store')
        ->name('availability.store')
        ->middleware('can:availability.create');

/******************************************************************************/

/**Actualizar*/
Route::put('availability/save', 'Administrative\AvailabilityController@update')
        ->name('availability.update')
        ->middleware('can:availability.edit');

/******************************************************************************/
      
/**Eliminar*/
Route::delete('availability/delete', 'Administrative\AvailabilityController@softDelete')
        ->name('availability.destroy')
        ->middleware('can:availability.destroy');

/******************************************************************************/

/**formularios*/
Route::get('availability/users', 'Administrative\AvailabilityController@users')
        ->name('availability.roles')
        ->middleware('can:availability.create');

Route::get('availability/add-working-hour-row', 'Administrative\AvailabilityController@addWorkingHourRow')
        ->name('availability.documentrow')
        ->middleware('can:availability.create');

Route::get('availability/form', 'Administrative\AvailabilityController@form')
        ->name('availability.form')
        ->middleware('can:availability.create');

Route::get('availability/delete-form', 'Administrative\AvailabilityController@deleteForm')
        ->name('availability.deleteform')
        ->middleware('can:availability.destroy');

/******************************************************************************/

/**listas*/
Route::get('availability/list', 'Administrative\AvailabilityController@list')
        ->name('availability.list')
        ->middleware('can:availability.index');

Route::get('availability', 'Administrative\AvailabilityController@index')
        ->name('availability.index')
        ->middleware('can:availability.index');

/******************************************************************************/

/**vistas */

Route::get('availability/showfile', 'Administrative\AvailabilityController@showFile')
        ->name('availability.showfile')
        ->middleware('can:availability.show');

Route::get('availability/create', 'Administrative\AvailabilityController@create')
        ->name('availability.create')
        ->middleware('can:availability.create');

Route::get('availability/{user}', 'Administrative\AvailabilityController@show')
        ->name('availability.show')
        ->middleware('can:availability.show');

Route::get('availability/{user}/edit', 'Administrative\AvailabilityController@edit')
        ->name('availability.edit')
        ->middleware('can:availability.edit');

/******************************************************************************/
