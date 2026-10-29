<?php
//LISTA DE CONFIRMACION DE TRANSFERENCIA
Route::get('inventory/confirmationTransfer-list', 'Administrative\InventoryController@listConTransferInventory')
->name('inventory.TransferInventorylist')
->middleware('can:confirmationTransfer.index');



//FORMULARIO DE CONFIRMACION DE TRANSFERENCIA
Route::get('inventory/confirmationTransfer-form', 'Administrative\InventoryController@confirmationTransferForm')
->name('inventory.confirmationTransferform')
->middleware(['can:confirmationTransfer.create' || 'confirmationTransfer.show']);


//AGREGAR UNA FILA DEL DETALLES DE CONFIRMACION DE TRANSFERENCIA
Route::get('inventory/add-row-confirmationtransfer', 'Administrative\InventoryController@addRowConfirmationTransfer')
->name('inventory.confirmationtransfer-row')
->middleware('can:confirmationTransfer.create');

//FORMULARIO PARA ELIMINAR UN DETALE DE CONFIRMACION DE TRANSFERENCIA
Route::get('inventory/delete-confirmationtransfer-row', 'Administrative\InventoryController@deleteConfirmationTransferRow')
->name('inventory.confirmationTransferRow')
->middleware('can:confirmationTransfer.create');

//GUARDAR EL FORMULARIO DE CONFIRMACION DE TRANSFERENCIA
Route::post('inventory/confirmationTransferInventory-save', 'Administrative\InventoryController@storeConfirmationTransferInventory')
->name('inventory.storeConfirmationTransferInventory')
->middleware(['can:inventory.create' || 'confirmationTransfer.create']);

//FORMULARIO PARA ELIMINAR REGISTRO DE CONFIRMACION DE TRANSFERENCIA
Route::get('inventory/confirmationTransferInventory-delete-form', 'Administrative\InventoryController@deleteFormConfirmationTransferInventory')
->name('inventory.ConfirmationTransferInventorydeleteform')
->middleware('can:inventory.destroy');

//ELIMINAR EL REGISTRO DE TRANSFERENCIA
Route::delete('inventory/confirmationTranfer-delete', 'Administrative\InventoryController@softDeleteConfirmationTransferInventory')
->name('inventory.confirmationInventoryDestroy')
->middleware('can:inventory.destroy');