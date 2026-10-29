<?php 
    use Illuminate\Support\Facades\Route;

/******************************************************************************/
/**listas*/

  Route::get('importFoxcon/bnacional', 'ImportFoxconController@bnacional')
    ->name('importFoxcon.bnacional')
    ->middleware('can:orderPurchases.index');



    Route::get('importFoxcon/index', 'ImportFoxconController@index')
    ->name('importFoxcon.index')
    ->middleware('can:orderPurchases.index');

  

 


    Route::post('importFoxcon/upload', 'ImportFoxconController@upload')
    ->name('importFoxcon.upload')
    ->middleware('can:orderPurchases.index');


    Route::post('importFoxcon/bnacional-upload', 'ImportFoxconController@bnacionalUpload')
    ->name('bnacional.upload')
    ->middleware('can:orderPurchases.index');

?>