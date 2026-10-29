<?php

use App\Http\Controllers\Api\V2\ApiAuthController;
use App\Http\Controllers\Api\V2\InventoryController;
use App\Http\Controllers\Api\V2\RouteController;
use App\Http\Controllers\Api\V2\SchedulingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['cors', 'json.response'])->prefix('v2')->group(function(){

    Route::post('register', [ApiAuthController::class, 'register']);
    Route::post('login', [ApiAuthController::class, 'login']);


    Route::middleware('auth:api')->group(function () {
        Route::get('routes', [RouteController::class, 'index']);
        Route::post('signature/save', [SchedulingController::class, 'saveSignature']);
        Route::put('scheduling/{scheduling}/update', [SchedulingController::class, 'update']);
        Route::post('scheduling/{scheduling}/observations', [SchedulingController::class, 'saveObservations']);
        Route::post('scheduling/{scheduling}/infestation-grades', [SchedulingController::class, 'saveInfestationGrades']);
        Route::post('scheduling/{scheduling}/consumptions', [SchedulingController::class, 'saveconsumptions']);
        Route::post('scheduling/{scheduling}/area/{area}/mechanism/{mechanism}/reports', [SchedulingController::class, 'saveReports']);
        Route::get('inventory', [InventoryController::class, 'index']);
        Route::get('inventory/pending-to-confirm', [InventoryController::class, 'pendingToConfirm']);
        Route::post('inventory/accept-transfer', [InventoryController::class, 'acceptTransfer']);
    });

});