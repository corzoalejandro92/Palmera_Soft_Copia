<?php
use Illuminate\Support\Facades\Route;
Route::get('all-routes','Api\RouteController@allRoutes')->name('route.allRoutes')->middleware('verifyAPIToken');
Route::get('route/check-if-start-day','Api\RouteController@checkIfStartDay')->name('route.checkifstartday')->middleware('verifyAPIToken');
Route::post('start-scheduling/{scheduling}','Api\RouteController@startScheduling')->middleware('verifyAPIToken');
Route::post('back-scheduling/{scheduling}','Api\RouteController@backScheduling')->middleware('verifyAPIToken');
Route::post('end-scheduling/{scheduling}','Api\RouteController@endScheduling')->middleware('verifyAPIToken');
Route::get('route/pdf/{id}','Api\RouteController@showPdf')->name('route.pdf');
Route::get('qrs', 'Api\RouteController@qrs')->middleware('verifyAPIToken');

Route::post('route/sync', 'Api\RouteController@sync')->name('route.sync')->middleware('verifyAPIToken');
Route::post('route/sync-service', 'Api\RouteController@syncService')->name('route.sync-service')->middleware('verifyAPIToken');
Route::post('route/sync-technical-expenses', 'Api\RouteController@syncTechnicalExpenses')->name('route.sync-technical-expenses')->middleware('verifyAPIToken');

//rutas para el grado de infestación
Route::post('control/infestation-grade', 'Api\RouteController@saveInfestatioGrade')->middleware('verifyAPIToken');
Route::post('control/infestation-grade/delete', 'Api\RouteController@deleteInfestatioGrade')->middleware('verifyAPIToken');

//rutas para los mecanismos
Route::post('mechanism/replace', 'Api\RouteController@mechanimsReplace')->middleware('verifyAPIToken');
Route::post('mechanism/qr-install', 'Api\RouteController@qrInstall')->middleware('verifyAPIToken');
Route::post('mechanism/new', 'Api\RouteController@newMechanism')->middleware('verifyAPIToken');
Route::post('mechanism/qr-replace', 'Api\RouteController@qrReplaced')->middleware('verifyAPIToken');
Route::post('mechanism/save-position', 'Api\RouteController@syncExtendPosition')->middleware('verifyAPIToken');
Route::post('mechanism/remove', 'Api\RouteController@removeMechanism')->middleware('verifyAPIToken');
Route::post('mechanism/num', 'Api\RouteController@numMechanism')->middleware('verifyAPIToken');
Route::post('sync/technical-report', 'Api\RouteController@syncTechnicalReport');
Route::post('sync/technical-report/delete', 'Api\RouteController@deleteTechnicalReport')->middleware('verifyAPIToken');

Route::get('route/end', 'Api\RouteController@saveEndDate')->name('route.end')->middleware('verifyAPIToken');
Route::apiResource('routes','Api\RouteController')->middleware('verifyAPIToken');
Route::apiResource('trackings','Api\UserTrackingController')->middleware('verifyAPIToken');
