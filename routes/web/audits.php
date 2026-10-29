<?php

use Illuminate\Support\Facades\Route;

Route::get('audits', 'Administrative\AuditController@audits')
->name('audit.audits')
->middleware('can:audit.index');

Route::get('audits-users', 'Administrative\AuditController@users')
        ->name('audit.users')
        ->middleware('can:audit.index');

Route::get('audits-view', 'Administrative\AuditController@show')
        ->name('audits.view-form')
        ->middleware(['can:audit.view']);

Route::post('audits/restore', 'Administrative\AuditController@restore')
        ->name('audits.restore')
        ->middleware(['can:audit.restore']);