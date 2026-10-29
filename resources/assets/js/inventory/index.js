COUNTRY_ID = 47;
STATE_ID = '';
CITY_ID = '';
PRODUCTS = '';


$(document).ready(function() {
    getList();
    setTimeout(() => {
        $('.nav-headder').removeClass('expanded');
        $(".vertical-menu-modern").removeClass('menu-expanded');
        $(".vertical-menu-modern").addClass('menu-collapsed');

       }, 500);
});

function getList() {
    var list = $('#list');
    $('.loading', list).attr('data-show', 'true');
    loadList().done(function(data) {
        list.html(data.view);
        $('.loading', list).attr('data-show', 'false');
        initDataTable();
    }).fail(function(error) {
        handleErrors(error);
    });
}

function loadList() {
    return $.ajax({
        url: "inventory/inventory",
        method: "GET",
        dataType: "json"
    });
}

/******************************************************************************/

$('body').on('click', '[data-click="codeFoxcon"]', function (e) {
    let id = $(this).data('id');

    localStorage.setItem('warehouse_selected',id);
    localStorage.setItem('warehouse_selected_data_click', 'LisInventory');

    WAREHOUSE_SELECTED = id;
    $('.warehouse_selected').removeClass('warehouse_selected');
    $("#warehouse_"+WAREHOUSE_SELECTED).parent().parent().addClass('warehouse_selected');

    e.preventDefault();
    var mdlForm = $('#mdlDelete');
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'true');
    mdlForm.modal('show');

    loadFormCodeFoxcon(id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        loading.attr('data-show', 'false');
    }).fail(function(error) {
        handleErrors(error);
    });
});

function loadFormCodeFoxcon(id) {
    return $.ajax({
        url: "inventory/code-foxcon",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

$('body').on('click', '[data-click="saveCodeFoxcon"]', function(e) {

    var mdlForm = $('#mdlDelete');
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'true');
    codeFoxconForm().done(function(data) {
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "warning");
    }).fail(function(error) {
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'false');
        showErrors(error.responseJSON.errors, '#mdlDelete');
        handleErrors(error);
    });
});


function codeFoxconForm() {

    var form = $('#frmCodeFoxcon');
    var data = form.serialize();
    return $.ajax({
        url: "inventory/code-foxcon-save",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });
}
$('body').on('click', '[data-click="warehouseForm"]', function(e) {

    //abrir el modal y mostrar loading
    e.preventDefault();
    var mdlForm = $('#mdlForm');
    var loading = $('.loading', mdlForm).first();
    var parent = $(this).data('parent');
    var id = $(this).data('id');
    loading.attr('data-show', 'true');
    mdlForm.modal('show');

    //leer el formulario de bodegas
    loadWarehouseFormForm(parent, id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        initSelectStates();
        initSelectZones();
        if (data.edit) {
            STATE_ID = $('#state_id').val();
            initSelectCities();
        }

        if (data.edit == true) {
            initSelectStates(data.state);
        }

        loading.attr('data-show', 'false');

    });

    function loadWarehouseFormForm(parent, id) {
        return $.ajax({
            url: ULR + "inventory/warehouse-form",
            method: "GET",
            data: {
                parent: parent,
                id: id
            },
            dataType: "json"
        });
    }


});

//FORMULARIO TEMPORAL DE INYECCIÓN DE INVENTARIO
$('body').on('click', '[data-click="inyectInventoryForm"]', function(e) {

    if($(this).data('force')){
        $(this).data('id', localStorage.getItem('warehouse_selected'));
    }

    localStorage.setItem('warehouse_selected',$(this).data('id'));
    localStorage.setItem('warehouse_selected_data_click', 'LisInventory');
    WAREHOUSE_SELECTED = $(this).data('id');
    $('.warehouse_selected').removeClass('warehouse_selected');
    $("#warehouse_"+WAREHOUSE_SELECTED).parent().parent().addClass('warehouse_selected');

    //abrir el modal y mostrar loading
    e.preventDefault();
    var mdlForm = $('#mdlFormMove');
    var loading = $('.loading', mdlForm).first();
    var warehouse = $(this).data('warehouse');

    var id = $(this).data('id');
    getListInventory(id);
    loading.attr('data-show', 'true');
    mdlForm.modal('show');

    //leer el formulario de configurarion de inventario
    loadFormInyection(warehouse, id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        //inicializar funciones de plugins
        loading.attr('data-show', 'false');
        initSelectProduct();
        initSelectTypeDocumentTranfer();
    }).fail(function(error) {
        handleErrors(error);
    });
});

function loadFormInyection(warehouse, id) {
    return $.ajax({
        url: ULR + "inventory/inyection-form",
        method: "GET",
        data: {
            warehouse: warehouse,
            id: id
        },
        dataType: "json"
    });
}
//FORMULARIO TEMPORAL DE INYECCIÓN DE INVENTARIO
$('body').on('click', '[data-click="giftInventoryForm"]', function(e) {


    //abrir el modal y mostrar loading
    e.preventDefault();
    var mdlForm = $('#mdlFormMove');
    var loading = $('.loading', mdlForm).first();
    var warehouse = $(this).data('warehouse');

    var id = $(this).data('id');
    mdlForm.modal('show');

    //leer el formulario de configurarion de inventario
    loadFormGift(warehouse, id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        //inicializar funciones de plugins
        loading.attr('data-show', 'false');
        initSelectProduct();
        initStockProducts();
        initSelectTypeDocumentTranfer();
    }).fail(function(error) {
        handleErrors(error);
    });
});

function loadFormGift(warehouse, id) {
    return $.ajax({
        url: ULR + "inventory/gift-form",
        method: "GET",
        data: {
            warehouse: warehouse,
            id: id
        },
        dataType: "json"
    });
}

//GUARDAR INYECCION DE INVENTARIO
$('body').on('click', '[data-click="saveInyect"]', function(e) {

    var mdlForm = $('#mdlFormMove');
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'true');
    saveFormInyect().done(function(data) {
        loading.attr('data-show', 'false');
        
        if(data.success){
            mdlForm.modal('hide');
            getListInventory(data.warehouse);
        }
        
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "warning");
    }).fail(function(error) {
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'false');
        showErrors(error.responseJSON.errors, '#mdlFormMove');
        handleErrors(error);
    });
});


function saveFormInyect() {

    var form = $('#inyect');
    var data = form.serialize();
    return $.ajax({
        url: "inventory/inyect-save",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });
}

/******************************************************************************/
//INVENTARIO - COMPRAS

//LISTA DE COMPRAS
$('body').on('click', '[data-click="ListShoppingInventory"]', function(e) {

    localStorage.setItem('warehouse_selected',$(e.target).data('warehouse'));
    localStorage.setItem('warehouse_selected_data_click', 'ListShoppingInventory');
    WAREHOUSE_SELECTED = $(e.target).data('warehouse');
    $('.warehouse_selected').removeClass('warehouse_selected');
    $("#warehouse_"+WAREHOUSE_SELECTED).parent().parent().addClass('warehouse_selected');

    e.stopPropagation();
    e.stopImmediatePropagation();
    getListshopping($(e.target).data('warehouse'));
});

function getListshopping(warehouse) {
    var list = $('#list');
    $('.loading', list).attr('data-show', 'true');
    loadListshopping(warehouse).done(function(data) {
        list.html(data.view);
        $('.loading', list).attr('data-show', 'false');
    }).fail(function(error) {
        handleErrors(error);
    });
}

function loadListshopping(warehouse) {
    return $.ajax({
        url: "inventory/purchase-list",
        method: "GET",
        data: {
            warehouse: warehouse,
        },
        dataType: "json"
    });
}

//FORMULARIO DE COMPRAS
$('body').on('click', '[data-click="shoppingInventoryForm"]', function(e) {

    //abrir el modal y mostrar loading
    e.preventDefault();
    var mdlForm = $('#mdlFormMove');
    var loading = $('.loading', mdlForm).first();
    var warehouse = $(this).data('warehouse');
    var id = $(this).data('id');
    loading.attr('data-show', 'true');
    mdlForm.modal('show');

    //leer el formulario de configurarion de inventario
    loadFormShopping(warehouse, id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        //inicializar funciones de plugins
        loading.attr('data-show', 'false');
        initSelectProduct();
    }).fail(function(error) {
        handleErrors(error);
    });
});

function loadFormShopping(warehouse, id) {
    return $.ajax({
        url: ULR + "inventory/purchase-form",
        method: "GET",
        data: {
            warehouse: warehouse,
            id: id
        },
        dataType: "json"
    });
}

//GUARDAR EL FORMULARIO DE COMPRAS
$('body').on('click', '[data-click="savePurchase"]', function(e) {

    var mdlForm = $('#mdlFormMove');
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'true');
    saveFormPurchase().done(function(data) {
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        getListshopping(data.warehouse);
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "warning");
    }).fail(function(error) {
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'false');
        showErrors(error.responseJSON.errors, '#purchase');
        handleErrors(error);
    });
});

function saveFormPurchase() {

    var form = $('#purchase');
    var data = form.serialize();
    return $.ajax({
        url: "inventory/purchase-save",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });
}

//FORMULARIO PARA ELIMINAR EL REGISTRO DE COMPRA
$('body').on('click', '[data-click="openFormDeletePurchase"]', function(e) {

    e.preventDefault();
    var mdlForm = $('#mdlDelete');
    var loading = $('.loading', mdlForm).first();
    var id = $(this).data('id');
    loading.attr('data-show', 'true');
    mdlForm.modal('show');

    loadFormDeletePurchase(id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        loading.attr('data-show', 'false');
    }).fail(function(error) {
        handleErrors(error);
    });

});

function loadFormDeletePurchase(id) {
    return $.ajax({
        url: "inventory/purchase-delete-form",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

//ELIMINAR EL REGISTRO DE COMPRA
$('body').on('click', '[data-click="deleteShopping"]', function(e) {
    e.preventDefault();

    var mdlForm = $('#mdlDelete');
    var loading = $('.loading', mdlForm).first();

    loading.attr('data-show', 'true');

    sendDeleteShopping().done(function(data) {
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
        window.location.href = ULR + 'inventory';
    }).fail(function(error) {
        handleErrors(error);
        loading.attr('data-show', 'false');
    });

});

function sendDeleteShopping() {

    var form = $('#frmDelete');
    var data = form.serialize();

    return $.ajax({
        url: "inventory/sopping-delete",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });

}

//AGREGAR UN FILA NUEVA DE DETALLES DE COMPRAS
$('body').on('click', '[data-click="addRowShopping"]', function(e) {
    e.preventDefault();
    var loading = $('.loading-min').first();
    loading.attr('data-show', 'true');
    addShoppingRow().done(function(data) {
        $('.tbl-documents tbody').append(data.row);
        loading.attr('data-show', 'false');
        initSelectProduct(data.key);
    }).fail(function(error) {
        handleErrors(error);
        loading.attr('data-show', 'false');
    });
});

function addShoppingRow() {
    return $.ajax({
        url: ULR + "inventory/add-purchase-row",
        method: "GET",
        data: {},
        dataType: "json"
    });
}

//FORMULARIO PARA ELIMINAR UN DETALLE DE COMPRA
$('body').on('click', '[data-click="removeRowShopping"]', function(e) {


    if ($(this + ' input[name*="id"]').length > 0) {


        let key = $(this).data('id');

        var mdlForm = $('#mdlDelete');
        var loading = $('.loading-min', '.tbl-documents').first();
        loading.attr('data-show', 'true');

        KEY_DELETE = key;
        let id = $('input[name="purchase[' + key + '][id]"]').val();
        e.preventDefault();
        $('tr[data-id="' + KEY_DELETE + '"]').remove();
        loading.attr('data-show', 'false');

        /* loadFormDeleteShoppingDetail(id).done(function(data) {

            if (!data.success) {
                $('tr[data-id="' + KEY_DELETE + '"]').remove();
                setTimeout(() => {
                    mdlForm.modal('hide');
                }, 500);
            } else mdlForm.modal('show');


            $('.modal-content', mdlForm).html(data.view);
            loading.attr('data-show', 'false');
        }).fail(function(error) {
            handleErrors(error);
        }); */
    }

});

function loadFormDeleteShoppingDetail(id) {
    return $.ajax({
        url: ULR + "inventory/delete-purchase-row",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

//ELIMINAR EL DETALLE DE DE COMPRA
$('body').on('click', '[data-click="deleteDetailPurchase"]', function(e) {
    e.preventDefault();

    var mdlForm = $('#mdlDelete');
    var loading = $('.loading', mdlForm).first();

    loading.attr('data-show', 'true');

    sendDeleteDetailPurchase().done(function(data) {
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        $('tr[data-id="' + KEY_DELETE + '"]').remove();
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }).fail(function(data) {
        swal("Error", data.message, "error");
        loading.attr('data-show', 'false');
    });
});

function sendDeleteDetailPurchase() {

    var form = $('#frmDelete');
    var data = form.serialize();

    return $.ajax({
        url: ULR + "inventory/purchase-delete",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });

}

/******************************************************************************/
//INVENTARIO -

//LISTA DE INVENTARIO
$('body').on('click', '[data-click="LisInventory"]', function(e) {

    if($(this).data('force')){
        $(e.target).data('warehouse', localStorage.getItem('warehouse_selected'));
    }

   localStorage.setItem('warehouse_selected',$(e.target).data('warehouse'));
   localStorage.setItem('warehouse_selected_data_click', 'LisInventory');

   if($(e.target).data('warehouse') == 1){
    $("a[data-click='inyectInventoryForm']").hide();
   }else{
    $("a[data-click='inyectInventoryForm']").show();
   }


   WAREHOUSE_SELECTED = $(e.target).data('warehouse');
   $('.warehouse_selected').removeClass('warehouse_selected');
   $("#warehouse_"+WAREHOUSE_SELECTED).parent().parent().addClass('warehouse_selected');

    e.stopPropagation();
    e.stopImmediatePropagation();
    getListInventory($(e.target).data('warehouse'));
});

function getListInventory(warehouse) {
    var list = $('#list');
    $('.loading', list).attr('data-show', 'true');
    loadListInventory(warehouse).done(function(data) {
        list.html(data.view);
        initDataTable();
        $('.loading', list).attr('data-show', 'false');
    }).fail(function(error) {
        handleErrors(error);
    });
}

function loadListInventory(warehouse) {
    return $.ajax({
        url: ULR + "inventory/list-inventory-individual",
        method: "GET",
        data: {
            warehouse: warehouse,
        },
        dataType: "json"
    });
}
//INVENTARIO - AJUSTE DE INVENTARIO MANUAL 
//FORMULARIO DE AJUSTE DE INVENTARIO
$('body').on('click', '[data-click="openFormEditInventory"]', function(e) {
    //abrir el modal y mostrar loading
    e.preventDefault();
    var mdlForm = $('#mdlFormMove');
    var loading = $('.loading', mdlForm).first();
    var id = $(this).data('id');
    var deleted = $(this).data('deleted');
    loading.attr('data-show', 'true');
    mdlForm.modal('show');

    //leer el formulario de configurarion de inventario
    loadFormEditInventory(id,deleted).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        //inicializar funciones de plugins
        loading.attr('data-show', 'false');
    }).fail(function(error) {
        handleErrors(error);
    });
});

function loadFormEditInventory(id,deleted=null) {
    return $.ajax({
        url: "inventory/edit-inventory-form",
        method: "GET",
        data: {
            id: id,
            deleted: deleted
        },
        dataType: "json"
    });
}

$('body').on('click', '[data-click="saveEditInventory"]', function(e) {
    //console.log("entro en la funcion");
    var mdlForm = $('#mdlFormMove');
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'true');
    saveFormEditInventory().done(function(data) {
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        getListInventory(data.warehouse);
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "warning");
    }).fail(function(error) {
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'false');
        showErrors(error.responseJSON.errors, '#editInventory');
        handleErrors(error);
    });
});

function saveFormEditInventory() {

    var form = $('#editInventory');
    var data = form.serialize();
    return $.ajax({
        url: "inventory/edit-inventory-save",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });
}


//eliminar inventario
$('body').on('click', '[data-click="deleteInventory"]', function(e) {
    //abrir el modal y mostrar loading
    e.preventDefault();
    var mdlForm = $('#mdlFormMove');
    var loading = $('.loading', mdlForm).first();
    var id = $(this).data('id');
    var warehouse = $(this).data('warehouse');
    Swal.fire({
        title: 'Quitar este elemento',
        text: "¿Estas seguro que deseas eliminar este producto del inventario? Esta acción será irreversible.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#009688',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Si, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        //console.log(result);
        if (result.value) {
            removeinventory(id).done(function(data) {

                swal.fire({
                    'title': data.success ? "Correcto" : "Error",
                    'text': data.message,
                    'type': data.success ? "success" : "error",
                }).then(function() {
                    mdlForm.modal('hide')
                    getListInventory(warehouse);
                });

            }).fail(function(error) {
                handleErrors(error);
            });
        }

    });
});
//eliminar inventario
$('body').on('click', '[data-click="removeRowEditInventory"]', function(e) {
    //abrir el modal y mostrar loading
    e.preventDefault();
    var mdlForm = $('#mdlFormMove');
    var loading = $('.loading', mdlForm).first();
    var id = $(this).data('id');
    var warehouse = $(this).data('warehouse');
    Swal.fire({
        title: 'Quitar este elemento',
        text: "¿Estas seguro que deseas eliminar este registro del inventario? Esta acción será irreversible.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#009688',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Si, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        //console.log(result);
        if (result.value) {
            removeDetailInventory(id).done(function(data) {

                swal.fire({
                    'title': data.success ? "Correcto" : "Error",
                    'text': data.message,
                    'type': data.success ? "success" : "error",
                }).then(function() {
                    mdlForm.modal('hide')
                    getListInventory(warehouse);
                });

            }).fail(function(error) {
                handleErrors(error);
            });
        }

    });
});


function removeDetailInventory(id) {

    return $.ajax({
        url: "inventory/delete/detail/" + id,
        method: 'GET',
        dataType: "json"
    });
}
function removeinventory(id) {

    return $.ajax({
        url: "inventory/delete/" + id,
        method: 'GET',
        dataType: "json"
    });
}

/******************************************************************************/
//INVENTARIO - TRANSFERENCIA

//LISTA DE TRANSFERENCIA
$('body').on('click', '[data-click="LisTransferInventory"]', function(e) {
    e.stopPropagation();
    e.stopImmediatePropagation();

    if($(this).data('force')){
        $(e.target).data('warehouse', localStorage.getItem('warehouse_selected'));
    }


    localStorage.setItem('warehouse_selected',$(e.target).data('warehouse'));
    localStorage.setItem('warehouse_selected_data_click', 'LisTransferInventory');

    if($(e.target).data('warehouse') == 1){
        $("a[data-click='inyectInventoryForm']").hide();
       }else{
        $("a[data-click='inyectInventoryForm']").show();
       }

   WAREHOUSE_SELECTED = $(e.target).data('warehouse');
   $('.warehouse_selected').removeClass('warehouse_selected');
   $("#warehouse_"+WAREHOUSE_SELECTED).parent().parent().addClass('warehouse_selected');
    getListTransferInventory($(e.target).data('warehouse'));
});

function getListTransferInventory(warehouse) {
    var list = $('#list');
    $('.loading', list).attr('data-show', 'true');
    loadListTranferInventory(warehouse).done(function(data) {
        list.html(data.view);
        $('.loading', list).attr('data-show', 'false');
    }).fail(function(error) {
        handleErrors(error);
    });
}

function loadListTranferInventory(warehouse) {
    return $.ajax({
        url: "inventory/transferInventory-list",
        method: "GET",
        data: {
            warehouse: warehouse,
        },
        dataType: "json"
    });
}
//LISTA DE TRANSFERENCIA
$('body').on('click', '[data-click="LisGiftInventory"]', function(e) {
    e.stopPropagation();
    e.stopImmediatePropagation();
    getListGiftInventory($(e.target).data('warehouse'));
});

function getListGiftInventory(warehouse) {
    var list = $('#list');
    $('.loading', list).attr('data-show', 'true');
    loadListGiftInventory(warehouse).done(function(data) {
        list.html(data.view);
        $('.loading', list).attr('data-show', 'false');
    }).fail(function(error) {
        handleErrors(error);
    });
}

function loadListGiftInventory(warehouse) {
    return $.ajax({
        url: "inventory/giftInventory-list",
        method: "GET",
        data: {
            warehouse: warehouse,
        },
        dataType: "json"
    });
}

//FORMULARIO DE TRANSFERENCIA DE INVENTARIO
$('body').on('click', '[data-click="moveInventoryForm"]', function(e) {

    //abrir el modal y mostrar loading
    e.preventDefault();
    var mdlForm = $('#mdlFormMove');
    var loading = $('.loading', mdlForm).first();
    var warehouse = $(this).data('warehouse');
    var id = $(this).data('id');
    var warehousable = $(this).data('warehousable');
    loading.attr('data-show', 'true');
    mdlForm.modal('show');

    //leer el formulario de configurarion de inventario
    loadFormMoveInventory(warehouse, id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        //inicializar funciones de plugins
        initSelectWarehouseTranfer(warehouse);
        initSelectTypeDocumentTranfer();
        initSelectInventoryProduct();
        initStockProducts();
        $('.icheck_minimal input').iCheck({
            checkboxClass: 'icheckbox_minimal',
            radioClass: 'iradio_minimal',
        });
        loading.attr('data-show', 'false');
        loadQuantityConfirmation();
    }).fail(function(error) {
        handleErrors(error);
    });
});

function loadQuantityConfirmation(){
    setTimeout(() => {
        $(".product_id").each(function( index ) {
            let key= $(this).data('key');
            let id_product=$(this).val();
            let plot=$('[name="transferInventory['+key+'][plot]"]').val();
            $.ajax({
                url: "inventory/quantity/" + plot,
                data: { 
                    warehouse_id: $("[name='warehouse_id']").val(),
                    product_id: id_product
                },
                dataType: 'json',
                method: "GET",
            }).done(function(data) {
                const quantityElement=$('#quantity' + key);
                quantityElement.val(data.stock);
            });
        });
    },100);
}
function loadFormMoveInventory(warehouse, id) {
    return $.ajax({
        url: "inventory/move-inventory-form",
        method: "GET",
        data: {
            warehouse: warehouse,
            id: id
        },
        dataType: "json"
    });
}

//SELECT DE BODEGAS EN EL FORMULARIO DE TRANSFERENCIA
function initSelectWarehouseTranfer(warehouse) {
    $('.warehouse_transfer').select2({
        width: '100%',
        dropdownParent: $('.modal-content', $('#mdlFormMove')),
        ajax: {
            url: "inventory/select-warehouse?warehouse=" + warehouse,
            dataType: 'json',
            method: "GET"
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        WAREHOUSE = select_val;
        initSelectOrderPurchase();
    });

}
//SELECT DE TIPO DOCUMENTO EN EL FORMULARIO DE TRANSFERENCIA
function initSelectTypeDocumentTranfer() {
    let id=$('#warehouse_id').first().val();
    let model=$('#warehousable_type').val();
    let docId=$('#docId').val();
    $('.type_document').select2({
        width: '100%',
        dropdownParent: $('.modal-content', $('#mdlFormMove')),
        ajax: {
            url: "inventory/select-type-document?id=" + id+"&model="+model+"&documentId="+docId,
            dataType: 'json',
            method: "GET"
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        $("[name='code_foxcon']").val(e.params.data.consecutive);
    });

}

//GUARDAR EL FORMULARIO DE CONFIGURACION DE FOXCON
$('body').on('click', '[data-click="saveFoxconSetting"]', function(e) {

    var mdlForm = $('#mdlFormSetting');
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'true');
    saveFormFoxconSetting().done(function(data) {
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "warning");
    }).fail(function(error) {
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'false');
        showErrors(error.responseJSON.errors, '#mdlFormSetting');
        handleErrors(error);
    });
});

function saveFormFoxconSetting() {

    var form = $('#settingFoxconInventory');
    var data = form.serialize();
    return $.ajax({
        url: "inventory/foxconSetting-save",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });
}
//AGREGAR UNA FILA NUEVA DE CONFIGURACIONES DE FOXCON
$('body').on('click', '[data-click="addRowFoxconSetting"]', function(e) {
    e.preventDefault();
    var loading = $('.loading-min').first();
    loading.attr('data-show', 'true');
    addRowFoxconSetting().done(function(data) {
        $('.tbl-documents tbody').append(data.row);
        loading.attr('data-show', 'false');
        initSelectDocumentType(data.key);
    }).fail(function(error) {
        handleErrors(error);
        loading.attr('data-show', 'false');
    });
});

function addRowFoxconSetting() {
    return $.ajax({
        url: ULR + "inventory/add-row-foxcon-setting",
        method: "GET",
        dataType: "json"
    });
}
$('body').on('click', '[data-click="removeRowFoxconSetting"]', function(e) {


    if ($(this + ' input[name*="id"]').length > 0) {

        let key = $(this).data('id');

        var mdlForm = $('#mdlDelete');
        var loading = $('.loading-min', '.tbl-documents').first();
        loading.attr('data-show', 'true');

        KEY_DELETE = key;
        let id = $('input[name="settingFoxcon[' + key + '][id]"]').val();
        e.preventDefault();

        loadFormFoxconDeleteRowSetting(id).done(function(data) {
            if (!data.success) {
                $('tr[data-id="' + KEY_DELETE + '"]').remove();
                setTimeout(() => {
                    mdlForm.modal('hide');
                }, 500);
            } else mdlForm.modal('show');


            $('.modal-content', mdlForm).html(data.view);
            loading.attr('data-show', 'false');
        }).fail(function(error) {
            handleErrors(error);
        });
    }
});

function loadFormFoxconDeleteRowSetting(id) {
    return $.ajax({
        url: ULR + "inventory/delete-setting-foxcon-row",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}
$('body').on('click', '[data-click="deleteFoxconSettings"]', function(e) {
    e.preventDefault();

    var mdlForm = $('#mdlDelete');
    var loading = $('.loading', mdlForm).first();
    var mdlFormSetting = $('#mdlFormSetting');

    loading.attr('data-show', 'true');

    sendDeleteFoxconSettings().done(function(data) {
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error")
        .then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            Swal.fire('Saved!', '', 'success')
        } else if (result.isDenied) {
            Swal.fire('Changes are not saved', '', 'info')
        }
        });
        mdlFormSetting.modal('hide');

    }).fail(function(data) {
        swal("Error", data.message, "error");
        loading.attr('data-show', 'false');
    });

});

function sendDeleteFoxconSettings() {

    var form = $('#frmFoxconDelete');
    var data = form.serialize();

    return $.ajax({
        url: "inventory/foxcon-settings-delete",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });

}
function initSelectDocumentType(key = '') {
    var selector = '.document-type';
    if (key != '') {
        selector = "[name='settingFoxcon[" + key + "][document_type]']";
    } 
    $(selector).select2({
        width: '100%',
        dropdownParent: $('.modal-content', $('#mdlFormSetting')),
        tags: false,
        placeholder: "Buscar tipo documento",
        ajax: {
            url: "inventory/documentType",
            dataType: 'json',
            method: "GET",
        },
    })
}

//SELECT DE ORDEN DE COMPRA EN EL FORMULARIO DE TRANSFERENCIA
function initSelectOrderPurchase() {

    $('#order_purchase').select2({
        width: '100%',
        dropdownParent: $('.modal-content', $('#mdlFormMove')),
        ajax: {
            url: "inventory/select-orderPurchase/" + WAREHOUSE,
            dataType: 'json',
            method: "GET",
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        ORDERPURCHASE = select_val;
        loadFramesDetailOrder(select_val);
    });
}

//FRAGMENTO CON LA INFORMACION DE LA ORDEN DE COMPRA EN EL FORMULARIO DE TRANSFERENCIA
function loadFramesDetailOrder(select_val) {

    var mdlForm = $('#mdlFormMove');
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'true');
    mdlForm.modal('show');

    loadFormlistOrderPurchase(select_val).done(function(data) {
        $('.tranferInventory', mdlForm).html(data.view);
        loading.attr('data-show', 'false');

        initSelectProduct();
        /* if (data.products.length > 0) {
            console.log(data.products);
            data.products.forEach((product) => {
                initSelectPlot('', product);
            });
        } */
        $('.icheck_minimal input').iCheck({
            checkboxClass: 'icheckbox_minimal',
            radioClass: 'iradio_minimal',
        });

    }).fail(function(error) {
        handleErrors(error);
    });
}

function loadFormlistOrderPurchase(select_val) {
    return $.ajax({
        url: ULR + "orderPurchase/form-orderPuchase/" + select_val,
        method: "GET",
        dataType: "json"
    });
}
//SELECT DE LOTE EN EL FORMULARIO DE TRANSFERENCIA
function initSelectPlot(key = '', e = undefined) {
    var selector = '[name*="[plot]"]';
    if (key != '') {
        selector = '[name="transferInventory[' + key + '][plot]"]';
    }
    let product=$('[name="transferInventory[' + key + '][product_id]"]').val();// : PRODUCTS;
    //console.log(key,product);
    $(selector).each(function(element) {
        $(this).select2({
            width: '100%',
            dropdownParent: $('.modal-content', $('#mdlFormMove')),
            ajax: {
                url: "inventory/plot/" + product,
                data: function (params) {
                    var facturable = $('[name="facturable"]:checked').val();
                    var warehouse =  $("[name='warehouse_id']").val();
                    var query = {
                        term: params.term,
                        _type: params._type,
                        q: params.q,
                        facturable: facturable,
                        warehouse_id: warehouse
                    }
              
                    // Query parameters will be ?search=[term]&type=public
                    return query;
                  },
                dataType: 'json',
                method: "GET",
                processResults: function(data) {
                    return {
                        results: $.map(data.results, function(obj) {
                            //console.log(obj);
                            return { id: obj.fake, text: obj.text };
                        })
                    };
                }
            }
        }).on("select2:select", function(e) {
            var selected_element = $(e.currentTarget);
            //console.log(e.currentTarget);
            var select_val = selected_element.val();
            PLOT = select_val;

            initSelectQuantity(e.target);
            initSelectFoxcon(e.target);
            initSelectDateExpiration(e.target);
        });
    });

    //aplicarlo en la inyección de inventario tambien
    var selector2 = '[name*="[plot_input]"]';
    if (key != '') {
        selector2 = '[name="purchase[' + key + '][plot_input]"]';
    }

    $.ajax({
    url: "inventory/plot/"+product,
    data: function (params) {
        var facturable = $('[name="facturable"]:checked').val();
        var warehouse =  $("[name='warehouse_id']").val();
        var query = {
            term: params.term,
            _type: params._type,
            q: params.q,
            facturable: facturable,
            warehouse_id: warehouse
        }
  
        // Query parameters will be ?search=[term]&type=public
        return query;
      },
    data: { warehouse_id: $("[name='warehouse_id']").val() },
    dataType: 'json',
    method: "GET",}).done(function(data) {
        $(selector).val(data.text);
    });

    




}

//INPUT DE FOXCON EN EL FORMULARIO DE TRANSFERENCIA
function initSelectFoxcon(element) {
    console.log('plot', element);
    $.ajax({
        url: "inventory/foxcon/",
        data: { warehouse_id: $("[name='warehouse_id']").val(),plot: PLOT },
        dataType: 'json',
        method: "GET",
    }).done(function(data) {
        $(element).closest('.row').find('.foxcon .input-group').val(data.code);
        var key = $(element).data('key');
        $('#foxcon' + key).val(data.code);
    });
}

//INPUT DE FOXCON EN EL FORMULARIO DE TRANSFERENCIA
function initSelectDateExpiration(element) {
    $.ajax({
        url: "inventory/expiration",
        data: { warehouse_id: $("[name='warehouse_id']").val(), plot: PLOT },
        dataType: 'json',
        method: "GET",
    }).done(function(data) {
        $(element).closest('.row').find('.date .input-group-append').val(data.date_expiration);
        var key = $(element).data('key');
        $('#date' + key).val(data.date_expiration ? data.date_expiration: 'N/A');
    });
}

//INPUT DE CANTIDAD DOSPONIBLE EN EL FORMULARIO DE TRANSFERENCIA
function initSelectQuantity(element) {
    let key=$(element).data('key');
    let product = $(`[name='transferInventory[${key}][product_id]']`).val();
    //console.log(product);
    $.ajax({
        url: "inventory/quantity/",
        data: { 
            warehouse_id: $("[name='warehouse_id']").val(),
            product_id: product,
            plot: PLOT
        },
        dataType: 'json',
        method: "GET",
    }).done(function(data) {
        $(element).closest('.row').find('.quantity .input-group').val(data.stock);
        var key = $(element).data('key');
        $('#quantity' + key).val(data.stock);
    });
}

//GUARDAR EL FORMULARIO DE TRANSFERENCIA
$('body').on('click', '[data-click="saveTransferInventory"]', function(e) {

    var mdlForm = $('#mdlFormMove');
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'true');
    saveFormTransferInventory().done(function(data) {
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        getListTransferInventory(data.warehouse);
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "warning");
    }).fail(function(error) {
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'false');
        showErrors(error.responseJSON.errors, '#transferInventory');
        handleErrors(error);
    });
});

function saveFormTransferInventory() {

    var form = $('#transferInventory');
    var data = form.serialize();
    return $.ajax({
        url: "inventory/transferInventory-save",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });
}
//GUARDAR EL FORMULARIO DE TRANSFERENCIA
$('body').on('click', '[data-click="saveGiftInventory"]', function(e) {

    var mdlForm = $('#mdlFormMove');
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'true');
    let warehouse = $('#warehouse_id').val();
    saveFormGiftInventory().done(function(data) {
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        console.log(warehouse);
        getListGiftInventory(warehouse);
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "warning");
    }).fail(function(error) {
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'false');
        showErrors(error.responseJSON.errors, '#transferInventory');
        handleErrors(error);
    });
});

function saveFormGiftInventory() {

    var form = $('#transferInventory');
    var data = form.serialize();
    return $.ajax({
        url: "inventory/giftInventory-save",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });
}

//AGREGAR UNA FILA NUEVA DE TRANSFERENCIA
$('body').on('click', '[data-click="addRowTranferInventory"]', function(e) {
    e.preventDefault();
    var loading = $('.loading-min').first();
    loading.attr('data-show', 'true');
    addRowTransferInventory().done(function(data) {
        $('.tbl-documents tbody').append(data.row);
        loading.attr('data-show', 'false');
        initSelectInventoryProduct(data.key);
        initSelectUnitProduct(null,data.key);
        //initSelectPlot(data.key);
        $('.icheck_minimal input').iCheck({
            checkboxClass: 'icheckbox_minimal',
            radioClass: 'iradio_minimal',
        });
    }).fail(function(error) {
        handleErrors(error);
        loading.attr('data-show', 'false');
    });
});

function addRowTransferInventory() {
    return $.ajax({
        url: ULR + "inventory/add-row-transferInventory",
        method: "GET",
        data: {},
        dataType: "json"
    });
}


//AGREGAR UNA FILA NUEVA DE OBSEQIOS
$('body').on('click', '[data-click="addRowGiftInventory"]', function(e) {
    e.preventDefault();
    var loading = $('.loading-min').first();
    loading.attr('data-show', 'true');
    addRowGiftInventory().done(function(data) {
        $('.tbl-documents tbody').append(data.row);
        loading.attr('data-show', 'false');
        initSelectInventoryProduct(data.key);
        initSelectUnitProduct(null,data.key);
        //initSelectPlot(data.key);
        $('.icheck_minimal input').iCheck({
            checkboxClass: 'icheckbox_minimal',
            radioClass: 'iradio_minimal',
        });
    }).fail(function(error) {
        handleErrors(error);
        loading.attr('data-show', 'false');
    });
});

function addRowGiftInventory() {
    return $.ajax({
        url: ULR + "inventory/add-row-giftInventory",
        method: "GET",
        data: {},
        dataType: "json"
    });
}

//FORMULARIO PARA ELIMINAR EL REGISTRO DE TRANSFERENCIA
$('body').on('click', '[data-click="openFormDeleteTransferInventory"]', function(e) {

    e.preventDefault();
    var mdlForm = $('#mdlDelete');
    var loading = $('.loading', mdlForm).first();
    var id = $(this).data('id');
    loading.attr('data-show', 'true');
    mdlForm.modal('show');

    loadFormDeleteTranferInventory(id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        loading.attr('data-show', 'false');
    }).fail(function(error) {
        handleErrors(error);
    });

});

function loadFormDeleteTranferInventory(id) {
    return $.ajax({
        url: "inventory/transferInventory-delete-form",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

//ELIMINAR EL REGISTRO DE TRANSFERENCIA
$('body').on('click', '[data-click="deleteTransferInventory"]', function(e) {
    e.preventDefault();

    var mdlForm = $('#mdlDelete');
    var loading = $('.loading', mdlForm).first();

    loading.attr('data-show', 'true');

    sendDeleteTranferInventory().done(function(data) {
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
        window.location.href = ULR + 'inventory';
    }).fail(function(data) {
        swal("Error", data.message, "error");
        loading.attr('data-show', 'false');
    });

});

function sendDeleteTranferInventory() {

    var form = $('#frmDelete');
    var data = form.serialize();

    return $.ajax({
        url: "inventory/tranferInventory-delete",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });

}

//FORMULARIO PARA ELIMINAR UN DETALLE DE TRANSFERENCIA
$('body').on('click', '[data-click="removeRowTransfer"]', function(e) {


    if ($(this + ' input[name*="id"]').length > 0) {

        let key = $(this).data('id');

        var mdlForm = $('#mdlDelete');
        var loading = $('.loading-min', mdlForm).first();
        loading.attr('data-show', 'true');

        KEY_DELETE = key;
        let id = $('input[name="transferInventory[' + key + '][id]"]').val();
        e.preventDefault();

        loadFormDeleteTransferDetail(id).done(function(data) {

            if (!data.success) {
                $('tr[data-id="' + KEY_DELETE + '"]').remove();
                $('tr[data-id="' + KEY_DELETE + '-2"]').remove();
                setTimeout(() => {
                    mdlForm.modal('hide');
                }, 500);
            } else mdlForm.modal('show');

            
            $('.modal-content', mdlForm).html(data.view);
            loading.attr('data-show', 'false');
        }).fail(function(error) {
            handleErrors(error);
        });
    }
});

function loadFormDeleteTransferDetail(id) {
    return $.ajax({
        url: ULR + "inventory/delete-transfer-row",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}
//FORMULARIO PARA ELIMINAR UN DETALLE DE TRANSFERENCIA
$('body').on('click', '[data-click="removeRowGift"]', function(e) {
        let id = $(this).data('id');
        let warehouse = $(this).data('warehouse');

        var mdlForm = $('#mdlDelete');
        var loading = $('.loading-min', mdlForm).first();
        loading.attr('data-show', 'true');

        e.preventDefault();

        loadFormDeleteGiftDetail(id).done(function(data) {
            getListGiftInventory(warehouse);
            if (!data.success) {
                $('tr[data-id="' + id + '"]').remove();
                setTimeout(() => {
                    mdlForm.modal('hide');
                }, 500);
            } else mdlForm.modal('show');

            
            $('.modal-content', mdlForm).html(data.view);
            loading.attr('data-show', 'false');
        }).fail(function(error) {
            handleErrors(error);
        });
});

function loadFormDeleteGiftDetail(id) {
    return $.ajax({
        url: ULR + "inventory/delete-gift-row",
        method: "GET",
        data: {id: id },
        dataType: "json"
    });
}

//ELIMINAR EL DETALLE DE LA TRANSFERENCIA
$('body').on('click', '[data-click="deleteDetailGiftInventory"]', function(e) {
    e.preventDefault();

    var mdlForm = $('#mdlDelete');
    var loading = $('.loading', mdlForm).first();

    loading.attr('data-show', 'true');

    sendDeleteDetailGift().done(function(data) {
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        $('tr[data-id="' + data.id + '"]').remove();
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }).fail(function(error) {
        loading.attr('data-show', 'false');
        handleErrors(error);
    });
});

function sendDeleteDetailGift() {

    var form = $('#frmDelete');
    var data = form.serialize();

    return $.ajax({
        url: ULR + "inventory/gift-delete",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });

}

//ELIMINAR EL DETALLE DE LA TRANSFERENCIA
$('body').on('click', '[data-click="deleteDetailTransferInventory"]', function(e) {
    e.preventDefault();

    var mdlForm = $('#mdlDelete');
    var loading = $('.loading', mdlForm).first();

    loading.attr('data-show', 'true');

    sendDeleteDetailTransfer().done(function(data) {
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        $('tr[data-id="' + KEY_DELETE + '"]').remove();
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }).fail(function(data) {
        swal("Error", data.message, "error");
        loading.attr('data-show', 'false');
    });
});

function sendDeleteDetailTransfer() {

    var form = $('#frmDelete');
    var data = form.serialize();

    return $.ajax({
        url: ULR + "inventory/transfer-delete",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });

}


/******************************************************************************/
//INVENTARIO - CONFIGURACION

//LISTA DE CONFIGURACION
$('body').on('click', '[data-click="ListSetting"]', function(e) {
    localStorage.setItem('warehouse_selected',$(e.target).data('warehouse'));
    localStorage.setItem('warehouse_selected_data_click', 'LisInventory');
   WAREHOUSE_SELECTED = $(e.target).data('warehouse');
   $('.warehouse_selected').removeClass('warehouse_selected');
   $("#warehouse_"+WAREHOUSE_SELECTED).parent().parent().addClass('warehouse_selected');
    
    getListSetting($(e.target).data('warehouse'));
});

function getListSetting(warehouse) {
    var list = $('#list');
    $('.loading', list).attr('data-show', 'true');
    loadListSettings(warehouse).done(function(data) {
        list.html(data.view);
        $('.loading', list).attr('data-show', 'false');
        initDataTable();
    }).fail(function(error) {
        handleErrors(error);
    });
}

function loadListSettings(warehouse) {
    return $.ajax({
        url: ULR + "inventory/settings-list",
        method: "GET",
        data: {
            warehouse: warehouse,
        },
        dataType: "json"
    });
}

//FORMULARIO DE CONFIGURACION FOXCON
$('body').on('click', '[data-click="settingFoxconForm"]', function(e) {

    //abrir el modal y mostrar loading
    e.preventDefault();
    var mdlForm = $('#mdlFormSetting');
    var loading = $('.loading', mdlForm).first();
    var warehouse = $(this).data('warehouse');
    var id = $(this).data('id');
    loading.attr('data-show', 'true');
    mdlForm.modal('show');

    //leer el formulario de configurarion de inventario
    loadFoxconFormSetting(warehouse, id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        //inicializar funciones de plugins
        loading.attr('data-show', 'false');
        initSelectDocumentType();
    }).fail(function(error) {
        handleErrors(error);
    });

});
function loadFoxconFormSetting(warehouse, id) {
    return $.ajax({
        url: "inventory/setting-foxcon-form",
        method: "GET",
        data: {
            warehouse: warehouse,
            id: id
        },
        dataType: "json"
    });
}

//FORMULARIO DE CONFIGURACION
$('body').on('click', '[data-click="settingForm"]', function(e) {

    //abrir el modal y mostrar loading
    e.preventDefault();
    var mdlForm = $('#mdlFormSetting');
    var loading = $('.loading', mdlForm).first();
    var warehouse = $(this).data('warehouse');
    var id = $(this).data('id');
    loading.attr('data-show', 'true');
    mdlForm.modal('show');

    //leer el formulario de configurarion de inventario
    loadFormSetting(warehouse, id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        //inicializar funciones de plugins
        loading.attr('data-show', 'false');
        initSelectProduct();
    }).fail(function(error) {
        handleErrors(error);
    });

});

function loadFormSetting(warehouse, id) {
    return $.ajax({
        url: "inventory/setting-form",
        method: "GET",
        data: {
            warehouse: warehouse,
            id: id
        },
        dataType: "json"
    });
}

//GUARDAR EL FORMULARIO DE CONFIGURACION DE STOCK
$('body').on('click', '[data-click="saveProductWarehouse"]', function(e) {

    var mdlForm = $('#mdlFormSetting');
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'true');
    saveFormProductWarehouse().done(function(data) {
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        getListSetting(data.warehouse);
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "warning");
    }).fail(function(error) {
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'false');
        showErrors(error.responseJSON.errors, '#settingInventory');
        handleErrors(error);
    });
});

function saveFormProductWarehouse() {

    var form = $('#settingInventory');
    var data = form.serialize();
    return $.ajax({
        url: "inventory/productWarehouse-save",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });
}



//FUNCIONES PARA CONFIGURAR EL VALOR DE MINIMO Y MAXIMO

$('body').on('click', '[data-click="restarMinimo"]', function(e) {

    let key = $(this).data('id');
    e.preventDefault();
    var input = $(this).closest('.row').find('[name="setting[' + key + '][minimum]"]');
    if ($(input).val() != '') $(input).val(parseInt($(input).val()) - 1);
    else $(input).val('0');

});

$('body').on('click', '[data-click="sumarMinimo"]', function(e) {

    let key = $(this).data('id');

    e.preventDefault();
    var input = $(this).closest('.row').find('[name="setting[' + key + '][minimum]"]');
    if ($(input).val() != '') $(input).val(parseInt($(input).val()) + 1);
    else $(input).val('0');

});

$('body').on('click', '[data-click="restarMaximo"]', function(e) {

    let key = $(this).data('id');

    e.preventDefault();
    var input = $(this).closest('.row').find('[name="setting[' + key + '][maximum]"]');
    if ($(input).val() != '') $(input).val(parseInt($(input).val()) - 1);
    else $(input).val('0');

});

$('body').on('click', '[data-click="sumarMaximo"]', function(e) {

    let key = $(this).data('id');

    e.preventDefault();
    var input = $(this).closest('.row').find('[name="setting[' + key + '][maximum]"]');
    if ($(input).val() != '') $(input).val(parseInt($(input).val()) + 1);
    else $(input).val('0');

});

//ELIMINAR EL REGISTRO DE CONFIGURACION DE STOCK
$('body').on('click', '[data-click="openFormDeleteSettings"]', function(e) {

    e.preventDefault();
    var mdlForm = $('#mdlDelete');
    var loading = $('.loading', mdlForm).first();
    var id = $(this).data('id');
    loading.attr('data-show', 'true');
    mdlForm.modal('show');

    loadFormDeleteSettings(id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        loading.attr('data-show', 'false');
    }).fail(function(error) {
        handleErrors(error);
    });

});

function loadFormDeleteSettings(id) {
    return $.ajax({
        url: "inventory/settings-delete-form",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

$('body').on('click', '[data-click="deleteSettings"]', function(e) {
    e.preventDefault();

    var mdlForm = $('#mdlDelete');
    var loading = $('.loading', mdlForm).first();

    loading.attr('data-show', 'true');

    sendDeleteSettings().done(function(data) {
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
        window.location.href = ULR + 'inventory';
    }).fail(function(data) {
        swal("Error", data.message, "error");
        loading.attr('data-show', 'false');
    });

});


//AGREGAR UNA FILA NUEVA DE CONFIGURACIONES DE PRODUCTO
$('body').on('click', '[data-click="addRowSetting"]', function(e) {
    e.preventDefault();
    var loading = $('.loading-min').first();
    loading.attr('data-show', 'true');
    addRowSetting().done(function(data) {
        $('.tbl-documents tbody').append(data.row);
        loading.attr('data-show', 'false');
        initSelectProduct(data.key);
    }).fail(function(error) {
        handleErrors(error);
        loading.attr('data-show', 'false');
    });
});

function addRowSetting() {
    return $.ajax({
        url: ULR + "inventory/add-row-setting",
        method: "GET",
        data: {},
        dataType: "json"
    });
}



//FORMULARIO PARA ELIMINAR UN DETALLE DE CONFIGURACIONES DE PRODUCTO
$('body').on('click', '[data-click="removeRowSetting"]', function(e) {


    if ($(this + ' input[name*="id"]').length > 0) {

        let key = $(this).data('id');

        var mdlForm = $('#mdlDelete');
        var loading = $('.loading-min', '.tbl-documents').first();
        loading.attr('data-show', 'true');

        KEY_DELETE = key;
        let id = $('input[name="transferInventory[' + key + '][id]"]').val();
        e.preventDefault();

        loadFormDeleteRowSetting(id).done(function(data) {

            if (!data.success) {
                $('tr[data-id="' + KEY_DELETE + '"]').remove();
                setTimeout(() => {
                    mdlForm.modal('hide');
                }, 500);
            } else mdlForm.modal('show');


            $('.modal-content', mdlForm).html(data.view);
            loading.attr('data-show', 'false');
        }).fail(function(error) {
            handleErrors(error);
        });
    }
});

function loadFormDeleteRowSetting(id) {
    return $.ajax({
        url: ULR + "inventory/delete-setting-row",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}


/******************************************************************************/

$('body').on('click', '[data-click="save"]', function(e) {

    var mdlForm = $('#mdlForm');
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'true');
    saveForm().done(function(data) {
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        tree.reset('FlatTree4');
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "warning");
    }).fail(function(data) {
        loading.attr('data-show', 'false');
        showErrors(data.responseJSON.errors, '#warehouseForm');
    });
});



$('body').on('click', '[data-click="openFormDelete"]', function(e) {

    e.preventDefault();
    var mdlForm = $('#mdlDelete');
    var loading = $('.loading', mdlForm).first();
    var id = $(this).data('id');
    loading.attr('data-show', 'true');
    mdlForm.modal('show');

    loadFormDelete(id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        loading.attr('data-show', 'false');
    }).fail(function(error) {
        handleErrors(error);
    });

});



$('body').on('click', '[data-click="delete"]', function(e) {
    e.preventDefault();

    var mdlForm = $('#mdlDelete');
    var loading = $('.loading', mdlForm).first();

    loading.attr('data-show', 'true');

    sendDelete().done(function(data) {
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        tree.reset('FlatTree4');
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }).fail(function(error) {
        handleErrors(error);
    });

});


function saveForm() {

    var form = $('#warehouseForm');
    var data = form.serialize();
    return $.ajax({
        url: ULR + $(form).attr('action'),
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });
}



function success(data) {
    if (data.success) {
        var mdlForm = $('#mdlForm');
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        getList();
        swal("Correcto", data.message, "success");
    }

}

function fail(data) {
    var mdlForm = $('#mdlForm');
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'false');
    showErrors(data.responseJSON.errors);
}

function loadFormDelete(id) {
    return $.ajax({
        url: "inventory/delete-form",
        method: "GET",
        data: {
            id: id
        },
        dataType: "json"
    });
}





function loadFormDeleteTranferInventory(id) {
    return $.ajax({
        url: "inventory/transferInventory-delete-form",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}




function sendDelete() {

    var form = $('#frmDelete');
    var data = form.serialize();

    return $.ajax({
        url: "inventory/delete",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });

}



function sendDeleteSettings() {

    var form = $('#frmDelete');
    var data = form.serialize();

    return $.ajax({
        url: "inventory/settings-delete",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });

}



function initSelectStates(state) {

    $('#state_id').select2({
        width: '100%',
        dropdownParent: $('.modal-content', $('#mdlForm')),
        ajax: {
            url: "inventory/states/" + COUNTRY_ID,
            dataType: 'json',
            method: "GET",
            success: function() {
                //$('#state_id').val(state).trigger('change');
            }
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        STATE_ID = select_val;
        initSelectCities();
    });
}

function initSelectCities() {

    $('#city_id').select2({
        width: '100%',
        dropdownParent: $('.modal-content', $('#mdlForm')),
        ajax: {
            url: "inventory/cities/" + STATE_ID,
            dataType: 'json',
            method: "GET"
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        var text = $("#city_id option:selected").text();
        $("[name='name']").val(text);
        CITY_ID = select_val;
        initSelectZones();

    });
}

function initSelectZones() {

    $('#zones').select2({
        width: '100%',
        dropdownParent: $('.modal-content', $('#mdlForm')),
        tags: true,
        ajax: {
            url: "inventory/zones/" + CITY_ID,
            dataType: 'json',
            method: "GET",
        }
    }).on('select2:close', function(e) {
        var select2SearchField = $(this).parent().find('.select2-search__field');
        var setfocus = setTimeout(function() {
            select2SearchField.focus();
        }, 100);
    });
}

function initStockProducts(){
    var stockSelector = $('.stock');
    stockSelector.each(function(index,element ) {
        let key = $(element).data('key');
        let product_id = $(`[name='transferInventory[${key}][product_id]'`).val();
        let plot = $(`[name='transferInventory[${key}][plot]'`).val();
        $.ajax({
            url: "inventory/quantity/" + plot,
            data: { 
                warehouse_id: $("[name='warehouse_id']").val(),
                product_id: product_id
            },
            dataType: 'json',
            method: "GET",
        }).done(function(data) {
            $('#quantity' + key).val(data.stock);
        });
        });
    
}

function initSelectProduct(key = '') {
    var selector = '.product_id';
    if (key != '') {
        selector = "[name='purchase[" + key + "][product_id]']," + "[name='orderPurchase[" + key + "][product_id]']" + "[name='setting[" + key + "][product_id]']";
    }
    var warehouseid = $('[name="warehouse_id"]').val();

    $(selector).select2({
        width: '100%',
        dropdownParent: $('.modal-content', $('#mdlFormMove')),
        placeholder: "Buscar productos 2",
        minimumInputLength: 1,
        ajax: {
            url: "inventory/product",
            dataType: 'json',
            method: "GET",
            cache: true
        },
        templateResult: dataFormatResult, // omitted for brevity, see the source of this page
        templateSelection: dataFormatSelection,
        dropdownCssClass: "bigdrop", // apply css that makes the dropdown taller
        escapeMarkup: function(m) { return m; } // we do not want to escape markup since we are displaying html in results
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        var key = $(selected_element).data('key');
        PRODUCTS = select_val;
        initSelectUnitProduct(e.target, key);
        initSelectPlot(key, e.target);
    });


    //-----------------------------------------------------

}

function initSelectInventoryProduct(key = '') {
    var selector = '.product_id';
    if (key != '') {
        selector = "[name='transferInventory[" + key + "][product_id]']";
    }

    var warehouseid = $('[name="warehouse_id"]').val();
    

    $(selector).select2({
        width: '100%',
        dropdownParent: $('.modal-content', $('#mdlFormMove')),
        placeholder: "Buscar productos",
        minimumInputLength: 1,
        ajax: {
            url: "inventory/" + warehouseid + "/product",
            dataType: 'json',
            data: function (params) {
                var facturable = $('[name="facturable"]:checked').val();
                var query = {
                    term: params.term,
                    _type: params._type,
                    q: params.q,
                    facturable: facturable
                }
          
                // Query parameters will be ?search=[term]&type=public
                return query;
              },
            method: "GET",
            cache: true
        },
        templateResult: dataFormatResult, // omitted for brevity, see the source of this page
        templateSelection: dataFormatSelection,
        dropdownCssClass: "bigdrop", // apply css that makes the dropdown taller
        escapeMarkup: function(m) { return m; } // we do not want to escape markup since we are displaying html in results
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        var key = $(selected_element).data('key');
        PRODUCTS = select_val;
        initSelectUnitProduct(e.target, key);
        //initSelectPlot(key, e.target);
        $("[name='transferInventory["+key+"][plot]']").attr('data-product',select_val);
        
        //rellenar  campos complementarios
        $("[name='transferInventory["+key+"][foxcon]']").attr('data-product',select_val);
    });


    $('body').on('click', '[name="facturable"]', function($q){
        if( $('[name="facturable"]:checked').val() == 1){
            $('.foxcon-doc').hide();
        }else{
            $('.foxcon-doc').show();
        }
    });


    //-----------------------------------------------------

}

function initSelectUnitProduct(element, key = false) {

    $.ajax({
        url: "inventory/unit/" + PRODUCTS,
        dataType: 'json',
        method: "GET",
    }).done(function(data) {
        var unit;
        try {
            unit = data.unit.split(' ', 1);
        } catch (error) {
            unit = "N/N";
        }

        $(element).closest('.row').find('.unity input-group-text span').text(unit);

        if (key === false) key = $(element).data('key');
        $('.unit' + key).text(unit);
        //console.log(".purchase" + key + "Unity");
        $(".purchase" + key + "Unity").text(unit);
    });
    initSelectPlot(key,element);

}







function initDataTable() {
    $('#tblList').dataTable().fnDestroy();
    $('#tblList').DataTable({
        language: dtEs,
        "aaSorting": [],
        "columnDefs": [
            { "orderable": false, "targets": [] }
        ],
        "initComplete": function() {
            var api = this.api();
            //filterSelect([2], api);
            //filterInput([0], api);
        }
    });
}













/******************************************************************************/

$('body').on('click', '[data-click="removeRow"]', function(e) {



    if ($(this + ' input[name*="id"]').length > 0) {



        let key = $(this).data('id');

        var mdlForm = $('#mdlDelete');
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'true');
        mdlForm.modal('show');

        KEY_DELETE = key;
        let id = $('input[name="purchase[' + key + '][id]"]').val();
        e.preventDefault();

        loadFormDeleteShopping(id).done(function(data) {
            if (!data.success) {
                setTimeout(() => {
                    mdlForm.modal('hide');
                    $('tr[data-id="' + KEY_DELETE + '"]').remove();
                }, 500);
            }
            $('.modal-content', mdlForm).html(data.view);
            loading.attr('data-show', 'false');
        });
    }
});

function loadFormDeleteShopping(id) {
    return $.ajax({
        url: "inventory/detail-shopping-delete",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

/******************************************************************************/


//-- RENDERIZACIÓN DE SELECT2
function dataFormatResult(data) {
    if (data.loading) return dataFormatSelection({ text: 'Consultando...' });

    if (data.name) {


        var markup = "<div class='select2-result-repository clearfix'>" +
            "<div class='select2-result-repository__avatar'><img class='avatarSelect2' src='" + data.full_avatar + "' /></div>" +
            "<div class='select2-result-repository__meta'>" +
            "<div class='select2-result-repository__title'>" + (data.draft ? '(Borrador) ' : '') + data.name + "</div>";
    }

    if (data.description) {
        markup += "<div class='select2-result-repository__description'>" + data.description + "</div>";
    }

    if (data.name) {
        markup += "<div class='select2-result-repository__statistics'>" +
            "<div class='select2-result-repository__forks center-items'><i class='las la-barcode la-2x'></i>" + data.code + "</div>" +
            "<div class='select2-result-repository__stargazers center-items'><i class='las la-balance-scale la-2x'></i>" + (data.unit != null ? data.unit.name : 'indefinida') + "</div>" +
            "<div class='select2-result-repository__watchers center-items'><i class='las la-biohazard la-2x'></i>" + (data.toxico ? 'Si' : 'No') + "</div>" +
            "</div>" +
            "</div></div>";
    }
    if (markup == undefined) {
        markup = "<b>Crear nuevo producto:</b> '" + data.text + "'";
    }

    return markup;
}

function dataFormatSelection(data) {
    return data.name || data.text;
}


$('body').on('click','[data-click="makeConsumtion"]', function(e) {
     //abrir el modal y mostrar loading
     e.preventDefault();
     var warehouse = $(this).data('warehouse');
     var mdlForm = $('#mdlFormMove');
     mdlForm.modal('show');
 
     //leer el formulario de configurarion de inventario
     loadFormConsumptionInventory(warehouse).done(function(data) {
         $('.modal-content', mdlForm).html(data.view);
         initSelectTypeDocumentTranfer();
        initSelectInventoryProduct();
        initStockProducts();
    
     }).fail(function(error) {
         handleErrors(error);
     });
});

function loadFormConsumptionInventory(warehouse) {
    return $.ajax({
        url: "inventory/consumption-form",
        method: "GET",
        data: {
            warehouse: warehouse
        },
        dataType: "json"
    });
}

$('body').on('click', '[data-click="addRowConsumptionInventory"]', function(e) {
    e.preventDefault();
    var loading = $('.loading-min').first();
    loading.attr('data-show', 'true');
    addRowConsumptionInventory().done(function(data) {
        $('.tbl-documents tbody').append(data.row);
        loading.attr('data-show', 'false');
        initSelectInventoryProduct(data.key);
        initSelectUnitProduct(null,data.key);
        //initSelectPlot(data.key);
        $('.icheck_minimal input').iCheck({
            checkboxClass: 'icheckbox_minimal',
            radioClass: 'iradio_minimal',
        });
    }).fail(function(error) {
        handleErrors(error);
        loading.attr('data-show', 'false');
    });
});

function addRowConsumptionInventory() {
    return $.ajax({
        url: ULR + "inventory/add-row-consumptionInventory",
        method: "GET",
        data: {},
        dataType: "json"
    });
}


$('body').on('click', '[data-click="saveConsumptionInventory"]', function(e) {

    var mdlForm = $('#mdlFormMove');
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'true');
    saveConsumptionInventory().done(function(data) {
       if(data.success){
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        $('[title="Recargar"]').trigger('click');
       }
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "warning");
    }).fail(function(error) {
        showErrors(error.responseJSON.errors, '#transferInventory');
        handleErrors(error);
    });
});

function saveConsumptionInventory() {

    var form = $('#transferInventory');
    var data = form.serialize();
    return $.ajax({
        url: "inventory/consumptionInventory-save",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });
}