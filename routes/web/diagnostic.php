<?php 
use Illuminate\Support\Facades\Route;


Route::get('quote/{quote}/diagnostic/creator', 'Administrative\DiagnosticController@creator')
->name('diagnostic.creator')
->middleware('can:quotes.diagnosis');

Route::get('crm-diagnostices-list', 'Administrative\DiagnosticController@index')
->name('diagnostic.listUser')
->middleware('can:quotes.diagnosis');

Route::get('quote/{quote}/diagnostic/creator/paint', 'Administrative\DiagnosticController@paint')
->name('diagnostic.paint')
->middleware('can:quotes.diagnosis');

Route::get('quote/{quote}/diagnostic/creator/paint/{plan}/edit', 'Administrative\DiagnosticController@editPaint')
->name('diagnostic.edit-paint')
->middleware('can:quotes.diagnosis');

Route::get('diagnostic/creatorUser/{diagnostic?}', 'Administrative\DiagnosticController@creatorUser')
->name('diagnostic.creatorUser')
->middleware('can:quotes.diagnosis');

Route::get('diagnostic/creator/paintUser/{diagnostic}', 'Administrative\DiagnosticController@paintUser')
->name('diagnostic.paintUser')
->middleware('can:quotes.diagnosis');

Route::get('diagnostic/creator/paint/{plan}/edit', 'Administrative\DiagnosticController@editPaintUser')
->name('diagnostic.edit-paintUser')
->middleware('can:quotes.diagnosis');

Route::post('quote/diagnostic/creator/paint/save','Administrative\DiagnosticController@planSave')
->middleware('can:quotes.diagnosis');

Route::put('quote/diagnostic/creator/paint/save','Administrative\DiagnosticController@planUpdate')
->middleware('can:quotes.diagnosis');

Route::get('quote/diagnostic/add-row-to-table1/{id}', 'Administrative\DiagnosticController@addRowToTable1')
->middleware('can:quotes.diagnosis');

Route::get('quote/diagnostic/add-row-to-table3/{id}', 'Administrative\DiagnosticController@addRowToTable3')
->middleware('can:quotes.diagnosis');

Route::post('quote/diagnostic/save', 'Administrative\DiagnosticController@store')
->middleware('can:quotes.diagnosis');

Route::put('quote/diagnostic/save', 'Administrative\DiagnosticController@update')
->middleware('can:quotes.diagnosis');

Route::get('quote/diagnostic/{diagnostic}/block-content/{block}', 'Administrative\DiagnosticController@blockContent')
->name('quotes.blockContent')
->middleware('can:quotes.diagnosis');

Route::get('diagnostic/status', 'Administrative\DiagnosticController@status')
->middleware('can:quotes.diagnosis');

Route::get('delete/diagnostic/{diagnostic}', 'Administrative\DiagnosticController@deleteDiagnostic')
->middleware('can:quotes.diagnosis');

Route::get('diagnostic/{diagnostic}/show', 'Administrative\DiagnosticController@show')
->name('diagnostic.show')
->middleware('can:quotes.diagnosis');

Route::get('diagnostic/plan/{plan}', 'Administrative\DiagnosticController@plan')
->middleware('can:quotes.diagnosis');

Route::get('diagnostic/{diagnostic}/delete', 'Administrative\DiagnosticController@destroy')

->middleware('can:quotes.diagnosis');