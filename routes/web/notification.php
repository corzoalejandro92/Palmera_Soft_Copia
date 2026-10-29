<?php 

use Illuminate\Support\Facades\Route;

Route::get('notifications/mark-as-read', 'NotificationController@markAsRead');
Route::get('notifications/list', 'NotificationController@list');
Route::get('notifications/{notification}/delete', 'NotificationController@destroy');
Route::get('notifications/delete/all', 'NotificationController@destroyAll');
