<?php
use Illuminate\Support\Facades\Route;
/**Rutas para configuración de aplicaciónes*/
Route::get('units/index', 'Administrative\UnitController@units')
->name('units.index')
->middleware('can:units.index');

Route::get('units/lists', 'Administrative\UnitController@Lists')
->name('units.list')
->middleware('can:units.index');

Route::get('units/form', 'Administrative\UnitController@Form')
->name('units.form')
->middleware(['can:units.create' || 'can:units.edit']);

Route::put('units/save', 'Administrative\UnitController@Update')
->name('units.put')
->middleware(['can:units.edit']);

Route::post('units/save', 'Administrative\UnitController@Store')
->name('units.save')
->middleware(['can:units.create']);

Route::get('units/delete-form', 'Administrative\UnitController@DeleteForm')
->name('units.delete-form')
->middleware(['can:units.destroy']);

Route::delete('units/delete', 'Administrative\UnitController@SoftDelete')
->middleware('can:units.destroy');