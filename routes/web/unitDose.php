<?php
use Illuminate\Support\Facades\Route;
/**Rutas para configuración de aplicaciónes*/
Route::get('unitDoses/index', 'Administrative\UnitDoseController@unitDoses')
->name('unitDoses.index')
->middleware('can:unit-doses.index');

Route::get('unitDoses/lists', 'Administrative\UnitDoseController@Lists')
->name('unitDoses.list')
->middleware('can:unit-doses.index');

Route::get('unitDoses/form', 'Administrative\UnitDoseController@Form')
->name('unitDoses.form')
->middleware(['can:unit-doses.create' || 'can:unit-doses.edit']);

Route::put('unitDoses/save', 'Administrative\UnitDoseController@Update')
->name('unitDoses.put')
->middleware(['can:unit-doses.edit']);

Route::post('unitDoses/save', 'Administrative\UnitDoseController@Store')
->name('unitDoses.save')
->middleware(['can:unit-doses.create']);

Route::get('unitDoses/delete-form', 'Administrative\UnitDoseController@DeleteForm')
->name('unitDoses.delete-form')
->middleware(['can:unit-doses.destroy']);

Route::delete('unitDoses/delete', 'Administrative\UnitDoseController@SoftDelete')
->middleware('can:unit-doses.destroy');