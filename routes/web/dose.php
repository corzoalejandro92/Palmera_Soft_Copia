<?php
use Illuminate\Support\Facades\Route;
/**Rutas para configuración de aplicaciónes*/
Route::get('doses/index', 'Administrative\DoseController@doses')
->name('doses.index')
->middleware('can:doses.index');

Route::get('doses/lists', 'Administrative\DoseController@Lists')
->name('doses.list')
->middleware('can:doses.index');

Route::get('doses/form', 'Administrative\DoseController@Form')
->name('doses.form')
->middleware(['can:doses.create' || 'can:doses.edit']);

Route::get('doses/unit', 'Administrative\DoseController@units')
->name('doses.unit')
->middleware('can:doses.create');

Route::put('doses/save', 'Administrative\DoseController@Update')
->name('doses.put')
->middleware(['can:doses.edit']);

Route::post('doses/save', 'Administrative\DoseController@Store')
->name('doses.save')
->middleware(['can:doses.create']);

Route::get('doses/delete-form', 'Administrative\DoseController@DeleteForm')
->name('doses.delete-form')
->middleware(['can:doses.destroy']);

Route::delete('doses/delete', 'Administrative\DoseController@SoftDelete')
->middleware('can:doses.destroy');