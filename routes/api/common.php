<?php
use Illuminate\Support\Facades\Route;
Route::post('/token', 'Api\CommonController@getToken');
Route::middleware('auth:api')->get('/test', 'Api\CommonController@test');

/**Guardar respuestas*/
Route::post('poll/save/answer', 'Administrative\PollController@storeAnswer')
    ->name('polls.storeAnswer');
    // ->middleware('can:polls.create');

