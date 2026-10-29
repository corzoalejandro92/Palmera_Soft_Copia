<?php
$controller = 'Administrative\HomeController@';
Route::get('/',['as' => 'home', 'uses' =>  "{$controller}index"]);
Route::get('/home',['as' => 'home2', 'uses' =>  "{$controller}index"]);
Route::get('/report',['as' => 'report', 'uses' =>  "{$controller}report"]);
