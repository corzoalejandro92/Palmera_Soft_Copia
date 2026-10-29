<?php
use Illuminate\Support\Facades\Route;
/******************************************************************************/

/**Guardar*/
Route::post('users/store', 'UserController@store')
        ->name('users.store')
        ->middleware('can:users.create');

/******************************************************************************/

/**Actualizar*/
Route::post('users/update', 'UserController@update')
        ->name('users.update')
        ->middleware('can:users.edit');

Route::post('users/update-profile', 'UserController@update')
        ->name('users.updateProfile')
        ->middleware('can:users.profile');
        

/******************************************************************************/
      
/**Eliminar*/
Route::delete('users/delete', 'UserController@softDelete')
        ->name('users.destroy')
        ->middleware('can:users.destroy');

Route::get('users/document', 'UserController@deleteDocumentForm')
        ->name('users.documentDeleteform')
        ->middleware('can:users.destroy');
        
Route::delete('users/delete-doc', 'UserController@softDeleteDocument')
        ->name('users.destroyDocument')
        ->middleware('can:users.destroy');

/******************************************************************************/

/**formularios*/
Route::get('users/roles', 'UserController@roles')
        ->name('users.roles')
        ->middleware('can:users.create');

Route::get('users/headquarters', 'UserController@headquarters')
        ->name('users.headquarters')
        ->middleware('can:users.create');

Route::get('users/zones', 'UserController@zones')
        ->name('users.zones')
        ->middleware('can:users.create');

Route::get('users/availabilities', 'UserController@availabilities')
        ->name('users.zones')
        ->middleware('can:users.create');

Route::get('users/customers', 'UserController@customers')
        ->name('users.customers')
        ->middleware('can:users.index');

Route::get('users/services', 'UserController@services')
    ->name('users.services')
    ->middleware('can:users.index');

Route::get('users/document-form', 'UserController@documentForm')
        ->name('users.documentform')
        ->middleware('can:users.index');

Route::get('users/add-document-row', 'UserController@addDocumentRow')
        ->name('users.documentrow')
        ->middleware('can:users.index');

Route::get('users/form', 'UserController@form')
        ->name('users.form')
        ->middleware('can:users.index');

Route::get('users/profileInfo', 'UserController@profile')
    ->name('users.profile');

    Route::get('users/profile', 'UserController@form')
    ->name('users.profile')
    ->middleware('can:users.profile');
    

Route::get('users/delete-form', 'UserController@deleteForm')
        ->name('users.deleteform')
        ->middleware('can:users.destroy');

/******************************************************************************/

/**listas*/
Route::get('users/list', 'UserController@list')
        ->name('users.list')
        ->middleware('can:users.index');

Route::get('users', 'UserController@index')
        ->name('users.index')
        ->middleware('can:users.index');

/******************************************************************************/

/**vistas */

Route::get('users/showfile', 'FileController@show')
        ->name('users.showfile');
Route::get('users/create', 'UserController@create')
        ->name('users.create')
        ->middleware('can:users.create');

Route::get('users/{user}', 'UserController@show')
        ->name('users.show')
        ->middleware('can:users.show');

Route::get('users/{user}/edit', 'UserController@edit')
        ->name('users.edit')
        ->middleware('can:users.edit');

/******************************************************************************/
