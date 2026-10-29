<?php

use Illuminate\Support\Facades\Route;

Route::get('indicators/month', 'Administrative\IndicatorController@indicatorsMonth')
->name('indicator.month.indicators')
->middleware(['can:indicator.report.month.index' || 'can:indicator.report.year.index']);

Route::get('indicators/year', 'Administrative\IndicatorController@indicatorsYear')
->name('indicator.year.indicators')
->middleware('can:indicator.report.year.index');

