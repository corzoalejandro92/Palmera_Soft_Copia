<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/**Rutas de logueo*/
Route::get('crm/consolidated2', 'Administrative\CrmController@consolidated2')->name('consolidated2');
Route::get('crm/schedule/{schedule}/report', 'Administrative\CrmController@reportSchedule')->name('crmschedule.report');
Route::get('logout',['as' => 'logout', 'uses' => 'UserController@logout']);
Route::get('login','UserController@login')->name('login');


//Agregadas JSON usuarios y permisos
Auth::routes();

Route::middleware(['auth'])->group(function(){
        Route::get('refresh-csrf', 'UserController@refreshCsrf');
        Route::get('integrations/switch', 'HomeController@integrationSwitch');
        Route::get('integrations/switch/headquarter', 'HomeController@integrationSwitchHeadquarter');
        Route::get('integrations', 'HomeController@integrations')->name('integrations');
        
        
        require (__DIR__ . '/web/general.php');
        require (__DIR__ . '/web/file.php');
        require (__DIR__ . '/web/user.php');
        require (__DIR__ . '/web/availability.php');
        require (__DIR__ . '/web/crm.php');
        require (__DIR__ . '/web/service.php');
        require (__DIR__ . '/web/product.php');
        require (__DIR__ . '/web/vehicle.php');
        require (__DIR__ . '/web/inventory.php');
        require (__DIR__.  '/web/orderPurchase.php');
        require (__DIR__.  '/web/confirmationTransfer.php');
        require (__DIR__ . '/web/headquarter.php');
        require (__DIR__ . '/web/setting.php');
        require (__DIR__ . '/web/infestation.php');
        require (__DIR__ . '/web/quote.php');
        require (__DIR__. '/web/scheduling.php');
        require (__DIR__. '/web/billings.php');
        require (__DIR__. '/web/diagnostic.php');
        require (__DIR__. '/web/poll.php');
        require (__DIR__. '/web/dashboard.php');
        require (__DIR__. '/web/qr.php' );
        require (__DIR__. '/web/convection.php' );
        require (__DIR__. '/web/documentType.php' );
        require (__DIR__ . '/web/application.php');
        require (__DIR__ . '/web/dose.php');
        require (__DIR__ . '/web/unit.php');
        require (__DIR__ . '/web/unitDose.php');
        require (__DIR__ . '/web/import.php');
        require (__DIR__ . '/web/notification.php');
        require (__DIR__ . '/web/audits.php');
        require (__DIR__ . '/web/indicatorReport.php');
        require (__DIR__ . '/web/objetive.php');
        require (__DIR__ . '/web/crmReport.php');
        require (__DIR__ . '/web/ticket.php');
        require (__DIR__ . '/web/peakPlate.php');
        require (__DIR__ . '/web/importFoxcon.php');
});
