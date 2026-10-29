<?php 


/******************************************************************************/

/**listas*/
Route::get('settings/list', 'Administrative\SettingController@list')
->name('settings.list')
->middleware('can:settings.index');

Route::get('settings', 'Administrative\SettingController@index')
->name('settings.index')
->middleware('can:settings.index');

/******************************************************************************/

/**Formulario*/
Route::get('settings/form', 'Administrative\SettingController@form')
->name('settings.form')
->middleware('can:settings.create');


/******************************************************************************/

/**Guardar*/
Route::post('settings/save', 'Administrative\SettingController@store')
->name('settings.store')
->middleware('can:settings.create');

/**Actualizar*/
Route::put('settings/save', 'Administrative\SettingController@update')
->name('settings.update')
->middleware('can:settings.edit');

/******************************************************************************/