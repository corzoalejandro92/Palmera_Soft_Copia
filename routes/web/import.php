<?php 
    use Illuminate\Support\Facades\Route;

/******************************************************************************/
/**listas*/
Route::group(['middleware' => 'superAdmin'], function () {
    Route::get('import/index', 'ImportController@index')
    ->name('import.index');

    Route::post('import/upload', 'ImportController@upload')
    ->name('import.upload');
});


?>