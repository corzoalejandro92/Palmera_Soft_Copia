<?php


/******************************************************************************/
   //INVENTARIO - ORDENES DE COMPRA

  //LISTA DE ORDENES DE COMPRA
Route::get('orderPurchase/list', 'Administrative\InventoryController@listOrderPurchase')
->name('orderPurchases.list')
->middleware('can:orderPurchases.index');


  //FORMULARIO DE ORDEN DE COMPRAS
Route::get('orderPurchase/form-orderPurchase', 'Administrative\InventoryController@formOrderPurchase')
->name('orderPurchases.formOrderPurchase')
->middleware('can:orderPurchases.create');

//SELECT DE BODEGAS EN EL FORMULARIO ORDEN DE COMPRAS
Route::get('orderPurchase/select-warehouse', 'Administrative\InventoryController@warehouseOrderPurchase')
->name('orderPurchases.selectWarehouse')
->middleware('can:orderPurchases.create');

//GUARDAR LA ORDEN DE ORDEN DE COMPRAS
Route::post('orderPurchase/save', 'Administrative\InventoryController@storeOrderPurchase')
->name('0rderPurchases.store')
->middleware('can:orderPurchases.create');

//ACTUALIZAR LA ORDEN DE ORDEN DE COMPRAS
Route::put('orderPurchase/save', 'Administrative\InventoryController@updateOrderPurchase')
->name('orderPurchases.update')
->middleware('can:orderPurchases.edit');

//FORMULARIO PARA ELIMINAR LA DE ORDEN DE COMPRA
Route::get('orderPurchase/OrderPurchase-delete-form', 'Administrative\InventoryController@deleteFormOrderPurchase')
->name('orderPurchases.OrderPurchasedeleteform')
->middleware('can:orderPurchases.destroy');

//ELIMINAR LA ORDEN DE COMPRA
Route::delete('orderPurchase/delete', 'Administrative\InventoryController@softDeleteOrderPurchase')
->name('orderPurchases.destroyOrderPurchase')
->middleware('can:orderPurchases.destroy');

//AGREGAR UNA FILA DEL DETALLES DE ORDEN DE COMPRAS
Route::get('orderPurchase/add-row-orderpurchase', 'Administrative\InventoryController@addRowOrderPurchase')
->name('orderPurchase.orderPurchase-row')
->middleware('can:orderPurchases.create');


//FORMULARIO PARA ELIMINAR UN DETALE DE ORDEN DE COMPRA
Route::get('orderPurchase/delete-orderpurchase-row', 'Administrative\InventoryController@deleteOrderPurchaseRow')
->name('orderPurchase.deleteOrderPurchaseRow')
->middleware('can:orderPurchases.create');

//ELIMINAR EL DETALLE DE LA ORDEN DE COMPRA
Route::delete('orderPurchase/orderpurchase-delete', 'Administrative\InventoryController@softDeleteDetailOrderPurchase')
->name('orderPurchases.destroyDetailOrderPurchase')
->middleware('can:orderPurchases.destroy');


/******************************************************************************/
   //INVENTARIO - ORDEN DE COMPRA GENERAL

//LISTA DE ORDENES DE COMPRA GENERAL
Route::get('orderPurchase/general-list', 'Administrative\InventoryController@listGeneralOrderPurchase')
->name('orderPurchases.general-list')
->middleware('can:orderPurchases.create');

//FORMULARIO PARA UNIFIRCAR LAS ORDENES DE COMPRA
Route::get('orderPurchase/general-form-orderPuchase', 'Administrative\InventoryController@formGeneralOrderPurchase')
->name('orderPurchases.formGeneralOrderPurchase')
->middleware('can:orderPurchases.create');

//GUARDAR EL FORMUMARIO DE ORDEN DE COMPRA GENERAL
Route::post('orderPurchase/generalOrder-save', 'Administrative\InventoryController@storeGeneralOrderPurchase')
->name('orderPurchases.storeGeneralPurchase')
->middleware('can:orderPurchases.create');

//FORMULARIO PARA EDITAR LA ORDEN DE COMPRA GENERAL (ESTA FUNCIONA ESTA DESABILITADA)
Route::get('orderPurchase/edit-general-form-orderPuchase', 'Administrative\InventoryController@EditformGeneralOrderPurchase')
->name('orderPurchases.EditformGeneralOrderPurchase')
->middleware('can:orderPurchases.create');

//FORMULARIO PARA ELIMINAR LA ORDEN DE COMPRA GENERAL
Route::get('orderPurchase/General-OrderPurchase-delete-form', 'Administrative\InventoryController@deleteFormGeneralOrderPurchase')
->name('orderPurchases.GeneralOrderPurchaseDeleteform')
->middleware('can:orderPurchases.destroy');

//ELIMINAR LA ORDEN DE COMPRA GENERAL
Route::delete('orderPurchase/general-delete', 'Administrative\InventoryController@softDeleteGeneralOrderPurchase')
->name('orderPurchases.destroyGeneralOrderPurchase')
->middleware('can:orderPurchases.destroy');










/*index las ordenes generales*/
Route::get('orderPurchase/general', 'Administrative\InventoryController@indexGeneralOrderPurchase')
->name('orderPurchases.index-general')
->middleware('can:OrderPurchases.create');

/*Index*/
Route::get('orderPurchase', 'Administrative\InventoryController@indexOrderPurchase')
->name('orderPurchases.index')
->middleware('can:inventory.index');

/******************************************************************************/

