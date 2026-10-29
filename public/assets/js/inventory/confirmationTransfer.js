$(document).ready(function() {
    getList();
});

//LISTA DE CONFIRMACION DE TRANSFERENCIA
function getList() {
    var list = $('#listConfirmationTransfer');
    $('.loading', list).attr('data-show', 'true');
    loadListConfirmationTransfer().done(function(data) {
        list.html(data.view);
        $('.loading', list).attr('data-show', 'false');
        initDataTable();
    }).fail(function(error) {
        handleErrors(error);
    });
}

function loadListConfirmationTransfer() {
    return $.ajax({
        url: ULR + "inventory/confirmationTransfer-list",
        method: "GET",
        dataType: "json"
    });
}

//FORMULARIO DE CONFIRMACION DE TRANSFERENCIA
$('body').on('click', '[data-click="confirmationTransferForm"]', function(e) {

    //abrir el modal y mostrar loading
    e.preventDefault();
    var mdlForm = $('#mdlFormMove');
    var loading = $('.loading', mdlForm).first();
    var id = $(this).data('id');
    loading.attr('data-show', 'true');
    mdlForm.modal('show');

    //leer el formulario de configurarion de inventario
    loadFormConfirmationTransferInventory(id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        //inicializar funciones de plugins
        initSelectProduct();
        loading.attr('data-show', 'false');
        $('.icheck_minimal input').iCheck({
            checkboxClass: 'icheckbox_minimal',
            radioClass: 'iradio_minimal',
        });

    }).fail(function(error) {
        handleErrors(error);
    });
});


function loadFormConfirmationTransferInventory(id) {
    return $.ajax({
        url: ULR + "inventory/confirmationTransfer-form",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}


function initSelectProduct(key = '') {
    var selector = '.product_id';
    if (key != '') {
        selector = "[name='confirmationTransfer[" + key + "][product_id]']";
    }

    $(selector).select2({
        width: '100%',
        tags: true,
        disabled:true,
        placeholder: "Buscar productos",
        minimumInputLength: 1,
        ajax: {
            url: ULR + "inventory/product",
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
    });
    //-----------------------------------------------------

}

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

$('body').on('ifChecked', '.confirmationTransfer', function(event) {
    let key = $(this).data('id');
    if (event.target.value == 0) {
        $('[name="confirmationTransfer[' + key + '][quantity_received]"]').removeAttr('readonly');
    }
});

//GUARDAR EL FORMULARIO DE CONFIRMACION DE INVENTARIO
$('body').on('click', '[data-click="saveConfirmationTransferInventory"]', function(e) {

    var mdlForm = $('#mdlFormOrderPurchase');
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'true');
    saveFormConfirmationTransfer().done(function(data) {
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "warning");
        window.location.href = ULR + 'inventory/transfer';
    }).fail(function(error) {
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'false');
        showErrors(error.responseJSON.errors, '#confirmationTranferInventory');
        handleErrors(error);
    });
});

function saveFormConfirmationTransfer() {

    var form = $('#confirmationTranferInventory');
    var data = form.serialize();
    return $.ajax({
        url: ULR + "inventory/confirmationTransferInventory-save",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });
}


//AGREGAR UNA FILA NUEVA DE CONFIRMACION DE TRANSFERENCIA
$('body').on('click', '[data-click="addRowConfirmationTransfer"]', function(e) {
    e.preventDefault();
    var loading = $('.loading-min').first();
    loading.attr('data-show', 'true');
    addRowConfirmationTransfer().done(function(data) {
        $('.tbl-documents tbody').append(data.row);
        loading.attr('data-show', 'false');
        initSelectProduct(data.key);
        $('.icheck_minimal input').iCheck({
            checkboxClass: 'icheckbox_minimal',
            radioClass: 'iradio_minimal',
        });
    }).fail(function(error) {
        handleErrors(error);
        loading.attr('data-show', 'false');
    });
});


function addRowConfirmationTransfer() {
    return $.ajax({
        url: ULR + "inventory/add-row-confirmationtransfer",
        method: "GET",
        data: {},
        dataType: "json"
    });
}

//FORMULARIO PARA ELIMINAR UN DETALE DE LA CONFIRMACION DE TRANSFERENCIA
$('body').on('click', '[data-click="removeRowDetailConfirmationTransfer"]', function(e) {


    if ($(this + ' input[name*="id"]').length > 0) {

        let key = $(this).data('id');

        var mdlForm = $('#mdlDelete');
        var loading = $('.loading-min', '.tbl-documents').first();
        loading.attr('data-show', 'true');

        KEY_DELETE = key;
        let id = $('input[name="confirmationTransfer[' + key + '][id]"]').val();
        e.preventDefault();

        loadFormDeleteDetailConfirmationTransfer(id).done(function(data) {

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

function loadFormDeleteDetailConfirmationTransfer(id) {
    return $.ajax({
        url: ULR + "inventory/delete-confirmationtransfer-row",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

//FORMULARIO PARA ELIMINAR UN REGISTRO DE LA CONFIRMACION DE TRANSFERENCIA
$('body').on('click', '[data-click="openFormDeleteConfirmationTransferInventory"]', function(e) {

    e.preventDefault();
    var mdlForm = $('#mdlDelete');
    var loading = $('.loading', mdlForm).first();
    var id = $(this).data('id');
    loading.attr('data-show', 'true');
    mdlForm.modal('show');

    loadFormDeleteCinfirmationTranfer(id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        loading.attr('data-show', 'false');
    }).fail(function(error) {
        handleErrors(error);
    });

});


function loadFormDeleteCinfirmationTranfer(id) {
    return $.ajax({
        url: ULR + "inventory/confirmationTransferInventory-delete-form",
        method: "GET",
        data: {
            id: id
        },
        dataType: "json"
    });
}

//GUARDAR EL FORMULARIO DE ELIMINAR CONFIRMACION DE INVENTARIO
$('body').on('click', '[data-click="deleteConfirmationTransferInventory"]', function(e) {

    var mdlForm = $('#mdlDelete');
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'true');
    deleteFormConfirmationTransfer().done(function(data) {
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "warning");
        window.location.href = ULR + 'inventory/transfer';
    }).fail(function(error) {
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'false');
        showErrors(error.responseJSON.errors, '#frmDelete');
        handleErrors(error);
    });
});

function deleteFormConfirmationTransfer() {

    var form = $('#frmDelete');
    var data = form.serialize();
    return $.ajax({
        url: ULR + "inventory/confirmationTranfer-delete",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });
}