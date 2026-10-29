<?php
use Illuminate\Support\Facades\Route;
/**Rutas para configuración de aplicaciónes*/
Route::get('applications/index', 'Administrative\ApplicationController@applications')
->name('applications.index')
->middleware('can:applications.index');

Route::get('applications/lists', 'Administrative\ApplicationController@Lists')
->name('applications.list')
->middleware('can:applications.index');

Route::get('applications/form', 'Administrative\ApplicationController@Form')
->name('applications.form')
->middleware(['can:applications.create' || 'can:applications.edit']);

Route::put('applications/save', 'Administrative\ApplicationController@Update')
->name('applications.put')
->middleware(['can:applications.edit']);

Route::post('applications/save', 'Administrative\ApplicationController@Store')
->name('applications.save')
->middleware(['can:applications.create']);

Route::get('applications/delete-form', 'Administrative\ApplicationController@DeleteForm')
->name('applications.delete-form')
->middleware(['can:applications.destroy']);

Route::delete('applications/delete', 'Administrative\ApplicationController@SoftDelete')
->middleware('can:applications.destroy');