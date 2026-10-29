<?php
use Illuminate\Support\Facades\Route;
/******************************************************************************/
/**listas*/
Route::get('convection/list', 'Administrative\ConvectionController@list')
        ->name('convection.list')
        ->middleware('can:products.index');

Route::get('convection', 'Administrative\ConvectionController@index')
        ->name('convection.index')
        ->middleware('can:products.index');

/*************************************************************************** 
 * Formularios
*/

Route::get('convection/form', 'Administrative\ConvectionController@form')
        ->name('convection.form')
        ->middleware('can:products.create');


Route::get('convection/products', 'Administrative\ConvectionController@products')
        ->name('convection.products')
        ->middleware('can:products.create');

Route::get('convection/category', 'Administrative\ConvectionController@category')
        ->name('convection.category')
        ->middleware('can:products.create');

Route::get('convection/add-product-row', 'Administrative\ConvectionController@addProductRow')
        ->name('convection.products')
        ->middleware('can:products.create');


Route::post('convection/save', 'Administrative\ConvectionController@store')
        ->middleware('can:products.create');

Route::put('convection/save', 'Administrative\ConvectionController@update')
        ->middleware('can:products.edit');

Route::get('convection/delete-form', 'Administrative\ConvectionController@deleteForm')
        ->name('convection.deleteform')
        ->middleware('can:products.destroy');

Route::delete('convection/delete', 'Administrative\ConvectionController@softDelete')
        ->middleware('can:products.destroy');