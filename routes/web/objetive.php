<?php
use Illuminate\Support\Facades\Route;
/**Rutas para configuración de aplicaciónes*/
Route::get('objetives/index', 'Administrative\ObjetiveController@objetives')
->name('objetives.index')
->middleware('can:objetive.index');

Route::get('objetives/lists', 'Administrative\ObjetiveController@Lists')
->name('objetives.list')
->middleware('can:objetive.index');

Route::get('objetives/form', 'Administrative\ObjetiveController@Form')
->name('objetives.form')
->middleware(['can:objetive.create' || 'can:objetive.edit']);

Route::put('objetives/save', 'Administrative\ObjetiveController@Update')
->name('objetives.put')
->middleware(['can:objetive.edit']);

Route::post('objetives/save', 'Administrative\ObjetiveController@Store')
->name('objetives.save')
->middleware(['can:objetive.create']);

Route::get('objetives/delete-form', 'Administrative\ObjetiveController@DeleteForm')
->name('objetives.delete-form')
->middleware(['can:objetive.destroy']);

Route::delete('objetives/delete', 'Administrative\ObjetiveController@SoftDelete')
->middleware('can:objetive.destroy');