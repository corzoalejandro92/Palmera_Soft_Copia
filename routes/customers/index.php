<?php

/*Index*/
Route::get('customer', 'Customer\CustomerController@index')
->name('customers.index')
->middleware('can:customers.index');