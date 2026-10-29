<?php
use Illuminate\Support\Facades\Route;
/******************************************************************************/
/**listas*/
Route::get('qrs/list', 'Administrative\QrController@list')
        ->name('qrs.list')
        ->middleware('can:qrs.index');

// Route::get('qrs', 'Administrative\QrController@index')
//         ->name('qrs.index')
//         ->middleware('can:qrs.index');

/*************************************************************************** 
 * Formularios
*/

Route::get('qrs/form', 'Administrative\QrController@form')
        ->name('qrs.form')
        ->middleware('can:qrs.create');

Route::get('qrs/form', 'Administrative\QrController@form')
        ->name('qrs.form')
        ->middleware('can:qrs.create');

Route::get('qrs/headquarters', 'Administrative\QrController@headquarters')
        ->name('qrs.headquarters')
        ->middleware('can:qrs.create');

Route::get('qrs/papersizes', 'Administrative\QrController@papersizes')
        ->name('qrs.papersizes')
        ->middleware('can:qrs.create');

Route::post('qrs/save', 'Administrative\QrController@store')
        ->name('qrs.papersizes')
        ->middleware('can:qrs.create');

Route::get('qrs/pdf/{id}', 'Administrative\QrController@pdf')
        ->name('qrs.show')
        ->middleware('can:qrs.show');

Route::get('qrs/delete-form', 'Administrative\QrController@deleteForm')
        ->name('qrs.deleteform')
        ->middleware('can:qrs.destroy');

Route::delete('qrs/delete', 'Administrative\QrController@delete')
        ->name('qrs.destroy')
        ->middleware('can:qrs.destroy');

Route::get('qrs/{token}/check', 'Administrative\QrController@checkQr');
        