<?php
use Illuminate\Support\Facades\Route;
Route::post('user/login','Auth\LoginController@loginAPI')->name('user.loginAPI');
Route::post('user/login-customer','Auth\LoginController@loginAPICustomer')->name('user.loginAPI');
Route::post('customer/user/data','Auth\LoginController@userDataCustomer')->name('user.userDataAPI');
Route::get('user/logout','Auth\LoginController@logoutAPI')->name('user.logoutAPI');
Route::apiResource('users','UserController')->middleware('verifyAPIToken');
