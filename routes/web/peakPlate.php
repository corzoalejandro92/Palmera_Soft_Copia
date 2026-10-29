<?php
use Illuminate\Support\Facades\Route;
/******************************************************************************/
/**listas*/
Route::get('peak-plate/list', 'Administrative\PeakPlateController@list')
        ->name('peak-plate.list')
        ->middleware('can:peakPlate.index');

Route::get('peak-plate', 'Administrative\PeakPlateController@index')
        ->name('peak-plate.index')
        ->middleware('can:peakPlate.index');

Route::get('peak-plate/verifyUser', 'Administrative\PeakPlateController@verifyUser')
->name('peak-plate.verifyUser');

/*************************************************************************** 
 * Formularios
*/

Route::get('peak-plate/form', 'Administrative\PeakPlateController@form')
        ->name('peak-plate.form')
        ->middleware('can:peakPlate.create');


Route::get('peak-plate/rules', 'Administrative\PeakPlateController@rules')
        ->name('peak-plate.peakPlate')
        ->middleware('can:peakPlate.create');


Route::post('peak-plate/save', 'Administrative\PeakPlateController@store')
        ->middleware('can:peakPlate.create');

Route::put('peak-plate/save', 'Administrative\PeakPlateController@update')
        ->middleware('can:peakPlate.edit');

Route::get('peak-plate/delete-form', 'Administrative\PeakPlateController@deleteForm')
        ->name('peak-plate.deleteform')
        ->middleware('can:peakPlate.destroy');

Route::delete('peak-plate/delete', 'Administrative\PeakPlateController@softDelete')
        ->middleware('can:peakPlate.destroy');