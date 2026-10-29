<?php

/******************************************************************************/

 //INVENTARIO 

//LISTA DEl inventario
Route::get('inventory/list-inventory-individual', 'Administrative\InventoryController@listInventoryIndividual')
->name('inventory.InventorylistInvidual')
->middleware('can:inventory.index');

Route::get('inventory/edit-inventory-form', 'Administrative\InventoryController@editInventoryForm')
->name('inventory.editInventoryForm');

Route::get('inventory/code-foxcon', 'Administrative\InventoryController@codeFoxconForm')
->name('inventory.codeFoxcon');

Route::put('inventory/code-foxcon-save', 'Administrative\InventoryController@codeFoxconSave')
->name('inventory.codeFoxconSave');


Route::get('inventory/delete/{id}', 'Administrative\InventoryController@deleteInventory')
->name('inventory.deleteInventory');

Route::get('inventory/delete/detail/{inventoryDetail}', 'Administrative\InventoryController@deleteDetailInventory')
->name('inventory.deleteDetailInventory');




/******************************************************************************/

/**Guardar*/
Route::post('inventory/save-headquarter', 'Administrative\InventoryController@storeHeadquarter')
        ->name('inventory.storeheadquarter')
        ->middleware('can:inventory.create');

Route::post('inventory/save-cellar', 'Administrative\InventoryController@storeCellar')
        ->name('inventory.storecellar')
        ->middleware('can:inventory.create');

/******************************************************************************/

/**Actualizar*/
Route::put('inventory/save-headquarter', 'Administrative\InventoryController@updateHeadquarter')
        ->name('inventory.updateheadquarter')
        ->middleware('can:inventory.edit');

Route::put('inventory/save-cellar', 'Administrative\InventoryController@updateCellar')
        ->name('inventory.updatecellar')
        ->middleware('can:inventory.edit');


/******************************************************************************/
/**Actualizar el inventario*/ 
Route::put('inventory/edit-inventory-save', 'Administrative\InventoryController@updateInventory')
->name('inventory.updateupdateInventory')
->middleware('can:inventory.edit');

/******************************************************************************/



/******************************************************************************/

/**Actualizar la configuracion de producto*/
Route::put('inventory/purchase-save', 'Administrative\InventoryController@updatePurchase')
->name('inventory.updatePurchase')
->middleware('can:inventory.edit');



/******************************************************************************/

/**Actualizar la tranferencia del inventario*/
/* Route::put('inventory/confirmationTransferInventory-save', 'Administrative\InventoryController@updateConfirmationTransferInventory')
->name('inventory.updateConfirmationTransferInventory')
->middleware('can:inventory.edit'); */

/******************************************************************************/



      
/**Eliminar*/
Route::delete('inventory/delete', 'Administrative\InventoryController@softDelete')
        ->name('inventory.destroy')
        ->middleware('can:inventory.destroy');

        
//Eliminar la compra 
Route::delete('inventory/sopping-delete', 'Administrative\InventoryController@softDeleteSopping')
->name('inventory.SoppingDestroy')
->middleware('can:inventory.destroy');

//Eliminar la configuracion
Route::delete('inventory/settings-delete', 'Administrative\InventoryController@softDeleteSettings')
->name('inventory.SettingsDestroy')
->middleware('can:inventory.destroy');
//Eliminar la configuracion Foxcon
Route::delete('inventory/foxcon-settings-delete', 'Administrative\InventoryController@softDeleteFoxconSettings')
->name('inventory.FoxconSettingsDestroy')
->middleware('can:inventory.destroy');



/******************************************************************************/

/**formularios*/
Route::get('inventory/warehouse-form', 'Administrative\InventoryController@warehouseForm')
        ->name('inventory.warehouseform')
        ->middleware('can:inventory.create');


/******************************************************************************/

//configurar del producto

Route::get('inventory/unit/{product}', 'Administrative\InventoryController@unit')
        ->name('inventory.unitproduct')
        ->middleware('can:inventory.create');

Route::get('inventory/product', 'Administrative\InventoryController@product')
->name('inventory.product');

Route::get('inventory/documentType', 'Administrative\InventoryController@documentType')
->name('inventory.documentType');

Route::get('inventory/{warehouse}/product', 'Administrative\InventoryController@productFromWarehouse');







/******************************************************************************/
 //TABLA DE INVENTARIO 

 //LISTA DE COMPRAS
 Route::get('inventory/inventory', 'Administrative\InventoryController@listInventory')
->name('inventory.inventory')
->middleware('can:inventory.index');





/******************************************************************************/
 //INVENTARIO - COMPRAS

 //LISTA DE COMPRAS
 Route::get('inventory/purchase-list', 'Administrative\InventoryController@listPurchase')
->name('inventory.purchaselist')
->middleware('can:inventory.index');

 //FORMULARIO DE COMPRAS
Route::get('inventory/purchase-form', 'Administrative\InventoryController@purchaseInventoryForm')
->name('inventory.purchaseform')
->middleware('can:inventory.create');

 //FORMULARIO DE INYECCION DE INVENTARIO
 Route::get('inventory/inyection-form', 'Administrative\InventoryController@purchaseInyectionForm')
 ->name('inventory.inyectForm')
 ->middleware('can:inventory.create');
 //FORMULARIO DE OBSEQUIOS DE INVENTARIO
 Route::get('inventory/gift-form', 'Administrative\InventoryController@giftForm')
 ->name('inventory.inyectForm')
 ->middleware('can:inventory.create');

//FUARDAR EL FORMULARIO DE COMPRAS
Route::post('inventory/purchase-save', 'Administrative\InventoryController@storePurchase')
->name('inventory.storePurchase')
->middleware('can:inventory.create');

Route::post('inventory/inyect-save', 'Administrative\InventoryController@storeInyect')
->name('inventory.storePurchase')
->middleware('can:inventory.create');

  //AGREGAR UN FILA NUEVA DE DETALLES DE COMPRAS
Route::get('inventory/add-purchase-row', 'Administrative\InventoryController@addPurchaseRow')
->name('inventory.purchaseRow')
->middleware('can:inventory.create');

//FORMULARIO PARA ELIMINAR UN DETALLE DE COMPRA
Route::get('inventory/delete-purchase-row', 'Administrative\InventoryController@deletePurchaseRow')
->name('inventory.deletePurchaseRow')
->middleware('can:inventory.create');

//ELIMINAR EL DETALLE DE COMPRA
Route::delete('inventory/purchase-delete', 'Administrative\InventoryController@softDeleteDetailPurchase')
->name('inventory.destroyDetailPurchase')
->middleware('can:inventory.create');

/******************************************************************************/
   //INVENTARIO - TRANSFERENCIA

   //LISTA DE TRANSFERENCIA
Route::get('inventory/transferInventory-list', 'Administrative\InventoryController@listTransferInventory')
->name('inventory.TransferInventorylist')
->middleware('can:inventory.index');
   //LISTA DE TRANSFERENCIA
Route::get('inventory/giftInventory-list', 'Administrative\InventoryController@listGiftInventory')
->name('inventory.TransferInventorylist')
->middleware('can:inventory.index');

   //FORMULARIO DE TRANSFERENCIA
Route::get('inventory/move-inventory-form', 'Administrative\InventoryController@moveInventoryForm')
->name('inventory.moveInventoryForm')
->middleware('can:inventory.create');

Route::get('inventory/consumption-form', 'Administrative\InventoryController@consumptionForm')
->middleware('can:inventory.create');


Route::post('inventory/consumptionInventory-save', 'Administrative\InventoryController@saveConsumption')
->middleware('can:inventory.edit');

  //SELECT DE BODEGAS EN EL FORMULARIO DE TRANSFERENCIA
Route::get('inventory/select-warehouse', 'Administrative\InventoryController@warehouse')
->name('inventory.selectWarehouse')
->middleware('can:inventory.create');

  //SELECT DE TIPO DE DOCUMENTO EN EL FORMULARIO DE TRANSFERENCIA
Route::get('inventory/select-type-document', 'Administrative\InventoryController@typeDocuments')
->name('inventory.selectTypeDocuments');

  //SELECT DE ORDEN DE COMPRA EN EL FORMULARIO DE TRANSFERENCIA
Route::get('inventory/select-orderPurchase/{warehouse}', 'Administrative\InventoryController@orderPurchase')
->name('inventory.selectOrderPurchase')
->middleware('can:inventory.create');
  
  //FRACMENTO CON LA INFORMACION DE LA ORDEN DE COMPRA EN EL FORMULARIO DE TRANSFERENCIA
Route::get('orderPurchase/form-orderPuchase/{order}', 'Administrative\InventoryController@FormlistOrderPurchase')
->name('inventory.formOrderPuchase')
->middleware('can:inventory.create');

  //SELECT DE LOTE EN EL FORMULARIO DE TRANSFERENCIA
Route::get('inventory/plot/{product}', 'Administrative\InventoryController@plot')
->name('inventory.plotproduct')
->middleware('can:inventory.create');

//INPUT DE FOXCON EN EL FORMULARIO DE TRANSFERENCIA
Route::get('inventory/foxcon', 'Administrative\InventoryController@foxcon')
->name('inventory.foxconProduct')
->middleware('can:inventory.create');

//INPUT DE FECHA DE VENCIMIENTO EN EL FORMULARIO DE TRANSFERENCIA
Route::get('inventory/expiration', 'Administrative\InventoryController@expiration')
->name('inventory.expirationproduct')
->middleware('can:inventory.create');

//INPUT DE CANTIDAD DOSPONIBLE EN EL FORMULARIO DE TRANSFERENCIA
Route::get('inventory/quantity', 'Administrative\InventoryController@quantity')
->name('inventory.unitproduct')
->middleware('can:inventory.create');

 //GUARDAR EL FORMULARIO DE TRANSFERENCIA
Route::post('inventory/transferInventory-save', 'Administrative\InventoryController@storeTransferInventory')
->name('inventory.storeTransferInventory')
->middleware('can:inventory.create');

 //ACTUALIZAR EL FORMULARIO DE TRANSFERENCIA
Route::put('inventory/transferInventory-save', 'Administrative\InventoryController@updateTransferInventory')
->name('inventory.updateTransferInventory')
->middleware('can:inventory.edit');
 //GUARDAR EL FORMULARIO DE TRANSFERENCIA
Route::post('inventory/giftInventory-save', 'Administrative\InventoryController@storeGiftInventory')
->name('inventory.storeGiftInventory')
->middleware('can:inventory.create');


//AGREGAR UNA FILA DEL DETALLES DE TRANSFERENCIA
Route::get('inventory/add-row-transferInventory', 'Administrative\InventoryController@addRowTransferInventory')
->name('inventory.tranferInventory-row')
->middleware('can:inventory.create');

Route::get('inventory/add-row-consumptionInventory', 'Administrative\InventoryController@addRowConsumptionInventory')
->middleware('can:inventory.create');

//AGREGAR UNA FILA DEL DETALLES DE TRANSFERENCIA
Route::get('inventory/add-row-giftInventory', 'Administrative\InventoryController@addRowGiftInventory')
->name('inventory.tranferInventory-row')
->middleware('can:inventory.create');

//FORMULARIO PARA ELIMINAR EL REGISTRO DE TRANSFERENCIA
Route::get('inventory/transferInventory-delete-form', 'Administrative\InventoryController@deleteFormTransferInventory')
->name('inventory.TransferInventorydeleteform')
->middleware('can:inventory.destroy');

//ELIMINAR EL REGISTRO DE TRANSFERENCIA
Route::delete('inventory/tranferInventory-delete', 'Administrative\InventoryController@softDeleteTransferInventory')
->name('inventory.TransferInventoryDestroy')
->middleware('can:inventory.destroy');


//ELIMINAR UNA FILA DEL DETALLES DE TRANSFERENCIA
Route::get('inventory/delete-transfer-row', 'Administrative\InventoryController@deleteTransferRow')
->name('inventory.deleteTransferRow')
->middleware('can:inventory.destroy');

//ELIMINAR UNA FILA DEL DETALLES DE TRANSFERENCIA
Route::get('inventory/delete-gift-row', 'Administrative\InventoryController@deleteGiftRow')
->name('inventory.deleteTransferRow')
->middleware('can:inventory.destroy');

//ELIMINAR EL DETALLE DE TRANSFERENCIA
Route::delete('inventory/gift-delete', 'Administrative\InventoryController@softDeleteDetailGift')
->name('inventory.destroyDetailTransfer')
->middleware('can:inventory.destroy');

//ELIMINAR EL DETALLE DE TRANSFERENCIA
Route::delete('inventory/transfer-delete', 'Administrative\InventoryController@softDeleteDetailTransfer')
->name('inventory.destroyDetailTransfer')
->middleware('can:inventory.destroy');

/******************************************************************************/
        //INVENTARIO - CONFIGURACION

//LISTA DE CONFIGURACION
Route::get('inventory/settings-list', 'Administrative\InventoryController@listSettings')
->name('inventory.Settingslist')
->middleware('can:inventory.index');

//FORMULARIO DE CONFIGURACION DE STOCK
Route::get('inventory/setting-form', 'Administrative\InventoryController@settingInventoryForm')
->name('inventory.settingform')
->middleware('can:inventory.create');

//FORMULARIO DE CONFIGURACION DE STOCK FOXCON
Route::get('inventory/setting-foxcon-form', 'Administrative\InventoryController@settingInventoryFoxconForm')
->name('inventory.settingfoxconform')
->middleware('can:inventory.create');


//AGREGAR UNA FILA DEL DETALLES DE CONFIGURACION DE STOCK
Route::get('inventory/add-row-setting', 'Administrative\InventoryController@addRowSetting')
->name('inventory.setting-row')
->middleware('can:inventory.create');

//AGREGAR UNA FILA DEL DETALLES DE CONFIGURACION DE STOCK
Route::get('inventory/add-row-foxcon-setting', 'Administrative\InventoryController@addRowFoxconSetting')
->name('inventory.setting-row')
->middleware('can:inventory.create');

//ELIMINAR UNA FILA DEL DETALLES DE CONFIGURACION DE STOCK
Route::get('inventory/delete-setting-foxcon-row', 'Administrative\InventoryController@deleteFoxconSetting')
->name('inventory.deleteSettingRow')
->middleware('can:inventory.destroy');
//ELIMINAR UNA FILA DEL DETALLES DE CONFIGURACION DE STOCK
Route::get('inventory/delete-setting-row', 'Administrative\InventoryController@deleteSetting')
->name('inventory.deleteSettingRow')
->middleware('can:inventory.destroy');
//ELIMINAR UNA FILA DEL DETALLES DE CONFIGURACION DE STOCK
Route::get('inventory/delete-setting-foxcon-row', 'Administrative\InventoryController@deleteFoxconSetting')
->name('inventory.deleteSettingRow')
->middleware('can:inventory.destroy');

//GUARDAR EL FORMULARIO DE CONFIGURACION DE STOCK
Route::post('inventory/productWarehouse-save', 'Administrative\InventoryController@storeProductWarehouse')
->name('inventory.storeProductWarehouse')
->middleware('can:inventory.create');


//ACTUALIZAR EL FORMULARIO DE CONFIGURACION DE STOCK
Route::put('inventory/productWarehouse-save', 'Administrative\InventoryController@updateProductWarehouse')
->name('inventory.updateProductWarehouse')
->middleware('can:inventory.edit');

//GUARDAR EL FORMULARIO DE CONFIGURACION DE STOCK
Route::post('inventory/foxconSetting-save', 'Administrative\InventoryController@storeFoxconSetting')
->name('inventory.storefoxconSetting')
->middleware('can:inventory.create');


//ACTUALIZAR EL FORMULARIO DE CONFIGURACION DE STOCK
Route::put('inventory/foxconSetting-save', 'Administrative\InventoryController@updateFoxconSetting')
->name('inventory.updateProductWarehouse')
->middleware('can:inventory.edit');

//ELIMINAR EL REGISTRO DE CONFIGURACION DE STOCK
Route::get('inventory/settings-delete-form', 'Administrative\InventoryController@deleteSetting')
->name('inventory.Settingsdeleteform')
->middleware('can:inventory.destroy');




/******************************************************************************/
//Formulario para ELiminar bodegas 
Route::get('inventory/delete-form', 'Administrative\InventoryController@deleteForm')
        ->name('inventory.deleteform')
        ->middleware('can:inventory.destroy');


//Formulario para ELiminar compras
Route::get('inventory/purchase-delete-form', 'Administrative\InventoryController@deleteFormPurchase')
->name('inventory.Purchasedeleteform')
->middleware('can:inventory.destroy');



/******************************************************************************/

/**data */
Route::get('inventory/states/{country}', 'Administrative\InventoryController@states')
        ->name('inventory.states')
        ->middleware('can:inventory.create');

Route::get('inventory/cities/{state}', 'Administrative\InventoryController@cities')
        ->name('inventory.states')
        ->middleware('can:inventory.create');

Route::get('inventory/zones/{city}', 'Administrative\InventoryController@zones')
        ->name('inventory.zones')
        ->middleware('can:inventory.create');

Route::get('inventory/tree', 'Administrative\InventoryController@tree')
        ->name('inventory.tree')
        ->middleware('can:inventory.index');

/******************************************************************************/

/**vistas*/
Route::get('inventory', 'Administrative\InventoryController@index')
        ->name('inventory.index')
        ->middleware('can:inventory.index');

Route::get('inventory/foxconDocs', 'Administrative\InventoryController@foxconDocs')
        ->name('inventory.foxcon')
        ->middleware('can:inventory.index');

Route::get('inventory/foxconDocs/list', 'Administrative\InventoryController@foxconDocsList')
        ->middleware('can:inventory.index');

Route::get('inventory/foxconDocs/list/{id}/delete', 'Administrative\InventoryController@foxconDocsDelete')
        ->middleware('can:inventory.index');

Route::get('inventory/by-user', 'Administrative\InventoryController@technical')
        ->name('inventory.technical')
        ->middleware('can:confirmationTransfer.index');


Route::get('inventory/by-user/list-inventory', 'Administrative\InventoryController@technicalList')
->middleware('can:confirmationTransfer.index');
        

/**vistas*/
Route::get('inventory/transfer', 'Administrative\InventoryController@indexTranferInventory')
->name('inventory.indexTranferInventory')
->middleware('can:confirmationTransfer.index');

Route::get('inventory/list', 'Administrative\InventoryController@list')
        ->name('inventory.list')
        ->middleware('can:inventory.index');


/******************************************************************************/





/******************************************************************************/