<?php
/**Rutas para api de conexion con FOXCON */

use Illuminate\Support\Facades\Route;
/* RUTAS PARA API DE FOXCON */
Route::middleware('auth:api')->put('inventory/documents/{id}/synchronized', 'Api\InventoryController@setAsSynchronized');
Route::middleware('auth:api')->put('inventory/documents/synchronized', 'Api\InventoryController@setAllAsSynchronized');
Route::middleware('auth:api')->post('/inventory/documents', 'Api\InventoryController@saveNewFoxconDoc');
Route::middleware('auth:api')->get('/inventory/documents', 'Api\InventoryController@getFoxconDocuments');


/** RUTAS PARA API APP TÉCNICOS */
Route::get('inventory/confirm','Api\InventoryController@toConfirm')->middleware('verifyAPIToken');
Route::get('inventory/confirm/save','Api\InventoryController@saveConfirm')->middleware('verifyAPIToken');
Route::post('inventory/expense','Api\InventoryController@expense')->middleware('verifyAPIToken');
Route::post('inventory/expense/delete', 'Api\InventoryController@deleteExpense')->middleware('verifyAPIToken');

Route::apiResource('inventory','Api\InventoryController')->middleware('verifyAPIToken');