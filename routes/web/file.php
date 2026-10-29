<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Route::get('file/show', 'UserController@show')
        ->name('file.show')
        ->middleware('can:file.show');