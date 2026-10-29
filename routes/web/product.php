<?php 
use Illuminate\Support\Facades\Route;


/******************************************************************************/
 
/**Eliminar*/
Route::get('product/delete-form', 'Administrative\ProductController@deleteForm')
->name('products.deleteform')
->middleware('can:products.destroy');

Route::delete('product/delete', 'Administrative\ProductController@softDelete')
->name('products.destroy')
->middleware('can:products.destroy');

Route::get('product/document', 'Administrative\ProductController@deleteDocumentoForm')
->name('products.documentDeleteform')
->middleware('can:products.destroy');

Route::delete('product/delete-doc', 'Administrative\ProductController@softDeleteDocument')
->name('products.destroyDocument')
->middleware('can:products.destroy');

Route::get('product/deleteMethod', 'Administrative\ProductController@deleteMethodForm')
->name('products.methodDeleteform')
->middleware('can:products.destroy');

Route::delete('product/delete-method', 'Administrative\ProductController@softDeleteMethod')
->name('products.destroyMethod')
->middleware('can:products.destroy');

/******************************************************************************/

/**Vista crear*/
Route::get('product/form-product', 'Administrative\ProductController@formProduct')
->name('products.create')
->middleware('can:products.index');

Route::get('product/detail-form', 'Administrative\ProductController@detailForm')
->name('products.detailForm')
->middleware('can:products.index');

Route::get('product/specification-form', 'Administrative\ProductController@specificationForm')
->name('products.detailForm')
->middleware('can:products.index');

Route::get('product/file-form', 'Administrative\ProductController@fileForm')
->name('products.fileForm')
->middleware('can:products.index');

Route::get('product/method-form', 'Administrative\ProductController@methodForm')
->name('products.methodForm')
->middleware('can:products.index');

Route::get('product/unit', 'Administrative\ProductController@units')
->name('product.unit')
->middleware('can:products.create');

Route::get('product/application', 'Administrative\ProductController@application')
->name('product.application')
->middleware('can:products.create');

Route::get('product/category', 'Administrative\ProductController@category')
->name('product.category')
->middleware('can:products.create');

Route::get('product/mechanism', 'Administrative\ProductController@mechanism')
->name('product.mechanism')
->middleware('can:products.create');

Route::get('product/service', 'Administrative\ProductController@service')
->name('product.service')
->middleware('can:products.create');

Route::get('product/infestation', 'Administrative\ProductController@infestations')
->name('product.control')
->middleware('can:products.create');

Route::get('product/related_products', 'Administrative\ProductController@relatedProducts')
->name('product.related_products')
->middleware('can:products.create');

Route::get('product/document-form', 'Administrative\ProductController@documentForm')
->name('product.documentform')
->middleware('can:products.create');

Route::get('product/method-form', 'Administrative\ProductController@methodForm')
->name('product.methodform')
->middleware('can:products.create');
        

Route::get('product/add-document-row', 'Administrative\ProductController@addDocumentRow')
->name('product.documentrow')
->middleware('can:products.create');

Route::get('product/add-method-row', 'Administrative\ProductController@addMethodRow')
->name('product.methodrow')
->middleware('can:products.create');

/******************************************************************************/

/**Vista listar*/
Route::get('products/form-list', 'Administrative\ProductController@list')
->name('products.index')
->middleware('can:products.index');

Route::get('products', 'Administrative\ProductController@index')
->name('products.index')
->middleware('can:products.index');

/******************************************************************************/

/**Guardar*/
Route::post('products/save', 'Administrative\ProductController@store')
->name('products.store')
->middleware('can:products.create');

/******************************************************************************/

/**Actualizar*/
Route::post('products/update', 'Administrative\ProductController@update')
->name('products.update')
->middleware('can:products.edit');

/******************************************************************************/

/**Vista editar*/
Route::get('products/{products}/editar', 'Administrative\ProductController@edit')
->name('products.edit')
->middleware('can:products.edit');

/******************************************************************************/


/******************************************************************************/

Route::get('products/showfile', 'FileController@show')
        ->name('products.showfile')
        ->middleware('can:products.show');