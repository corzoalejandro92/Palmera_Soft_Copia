<?php

//Ruta para cargar el formulario de filtros en la vista home


Route::get('dashboard/filter', 'Administrative\HomeController@filter')
->name('dashboards.filter')
->middleware('can:dashboards.index');

//Ruta del select tecnico
Route::get('dashboard/user-filter', 'Administrative\HomeController@userFilter ')
->name('dashboards.userFilter')
->middleware('can:dashboards.index');

//Rute del select de servicios
Route::get('dashboard/service-filter','Administrative\HomeController@serviceFilter')
->name('dashboards.userFilter')
->middleware('can:dashboards.index');


//Ruta del select de cliente
Route::get('dashboard/customer-filter','Administrative\HomeController@customerFilter')
->name('dashboards.customerFilter')
->middleware('can:dashboards.index');

//Ruta del select de usuario de programacion
Route::get('dashboard/programming-filter', 'Administrative\HomeController@programmingFilter')
->name('dashboards.programmingFilter')
->middleware('can:dashboards.index');

/******************************************************************************/
//Rutas de la tabla de comerciales

Route::get('dashboard/commercial', 'Administrative\HomeController@commercial')
->name('dashboards.commercial')
->middleware('can:dashboards.index');

/******************************************************************************/
//Rutas de la tabla de programacion

Route::get('dashboard/programming', 'Administrative\HomeController@programming')
->name('dashboards.programming')
->middleware('can:dashboards.index');

/******************************************************************************/
//Rutas de la tabla de tecnicos

Route::get('dashboard/technical', 'Administrative\HomeController@technical')
->name('dashboards.technical')
->middleware('can:dashboards.index');
