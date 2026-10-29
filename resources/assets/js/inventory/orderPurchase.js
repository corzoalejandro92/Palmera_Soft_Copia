/******************************************************************************/
//INVENTARIO - ORDEN DE COMPRA


//LISTA DE ORDEN DE COMPRAS

$(document).ready(function() {
    getList();
    getListGeneral();
});

function getList() {
    var list = $('#listOrderPurchase');
    $('.loading', list).attr('data-show', 'true');
    loadListOrderPurchase().done(function(data) {
        list.html(data.view);
        $('.loading', list).attr('data-show', 'false');
        $('.icheck_minimal input').iCheck({
            checkboxClass: 'icheckbox_minimal',
            radioClass: 'iradio_minimal',
        });
        initDataTable();
    }).fail(function(error) {
        handleErrors(error);
    });
}

function loadListOrderPurchase() {
    return $.ajax({
        url: ULR + "orderPurchase/list",
        method: "GET",
        dataType: "json"
    });
}

//FORMULARIO DE ORDEN DE COMPRAS
$('body').on('click', '[data-click="openFormOrderPurchase"]', function(e) {

    //abrir el modal y mostrar loading
    e.preventDefault();
    var mdlForm = $('#mdlFormOrderPurchase');
    var loading = $('.loading', mdlForm).first();
    var id = $(this).data('id');
    loading.attr('data-show', 'true');
    mdlForm.modal('show');


    //leer el formulario de crear orden de compras
    loadFormOrderPurchase(id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        loading.attr('data-show', 'false');
        initSelectWarehouse();
        initSelectProduct();
    }).fail(function(error) {
        handleErrors(error);
    });
});

function loadFormOrderPurchase(id) {
    return $.ajax({
        url: "orderPurchase/form-orderPurchase",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

//SELECT DE BODEGAS EN EL FORMULARIO DE TRANSFERENCIA
function initSelectWarehouse() {

    $('.warehouse').select2({
        ajax: {
            url: "orderPurchase/select-warehouse",
            dataType: 'json',
            method: "GET",
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        WAREHOUSE = select_val;
    });

}

function initSelectProduct(key = '') {
    var selector = '.product_id';
    if (key != '') {
        selector = "[name='orderPurchase[" + key + "][product_id]']";
    }

    $(selector).select2({
        tags: true,
        width: '100%',
        placeholder: "Buscar productos",
        minimumInputLength: 3,
        ajax: {
            url: "inventory/product",
            dataType: 'json',
            method: "GET",
            cache: true
        },
        language: {
            inputTooShort: function() {
                return 'Por favor ingrese al menos 3 caracteres para buscar';
            }
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

//GUARDAR EL FORMUMARIO DE ORDEN DE COMPRA
$('body').on('click', '[data-click="saveOrderPurchase"]', function(e) {

    var mdlForm = $('#mdlFormOrderPurchase');
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'true');
    saveFormOrderPurchase().done(function(data) {
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        getList();
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "warning");
    }).fail(function(error) {
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'false');
        showErrors(error.responseJSON.errors, '#orderPurchase');
        handleErrors(error);
    });
});


function saveFormOrderPurchase() {

    var form = $('#orderPurchase');
    var data = form.serialize();
    return $.ajax({
        url: "orderPurchase/save",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });
}

//FORMULARIO PARA ELIMINAR LA DE ORDEN DE COMPRA
$('body').on('click', '[data-click="openFormDeleteOrderPurchase"]', function(e) {

    e.preventDefault();
    var mdlForm = $('#mdlDelete');
    var loading = $('.loading', mdlForm).first();
    var id = $(this).data('id');
    loading.attr('data-show', 'true');
    mdlForm.modal('show');

    loadFormDeleteOrderPurchase(id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        loading.attr('data-show', 'false');
    }).fail(function(error) {
        handleErrors(error);
    });

});

function loadFormDeleteOrderPurchase(id) {
    return $.ajax({
        url: "orderPurchase/OrderPurchase-delete-form",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

//ELIMINAR LA ORDEN DE COMPRA
$('body').on('click', '[data-click="deleteOrderPurchase"]', function(e) {
    e.preventDefault();

    var mdlForm = $('#mdlDelete');
    var loading = $('.loading', mdlForm).first();

    loading.attr('data-show', 'true');

    sendDeleteOrderPurchase().done(function(data) {
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        getList();
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }).fail(function(data) {
        swal("Error", data.message, "error");
        loading.attr('data-show', 'false');
    });

});

function sendDeleteOrderPurchase() {

    var form = $('#frmDelete');
    var data = form.serialize();

    return $.ajax({
        url: "orderPurchase/delete",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });

}

//AGREGAR UNA FILA NUEVA DE ORDEN DE COMPRA
$('body').on('click', '[data-click="addRowOrderPurchase"]', function(e) {
    e.preventDefault();
    var loading = $('.loading-min').first();
    loading.attr('data-show', 'true');
    addRowOrderPurchase().done(function(data) {
        $('.tbl-documents tbody').append(data.row);
        loading.attr('data-show', 'false');
        initSelectProduct(data.key);
    }).fail(function(error) {
        handleErrors(error);
        loading.attr('data-show', 'false');
    });
});


function addRowOrderPurchase() {
    return $.ajax({
        url: ULR + "orderPurchase/add-row-orderpurchase",
        method: "GET",
        data: {},
        dataType: "json"
    });
}

//FORMULARIO PARA ELIMINAR UN DETALE DE ORDEN DE COMPRA
$('body').on('click', '[data-click="removeRowOrderPurchase"]', function(e) {


    if ($(this + ' input[name*="id"]').length > 0) {

        let key = $(this).data('id');

        var mdlForm = $('#mdlDelete');
        var loading = $('.loading-min', '.tbl-documents').first();
        loading.attr('data-show', 'true');

        KEY_DELETE = key;
        let id = $('input[name="orderPurchase[' + key + '][id]"]').val();
        e.preventDefault();

        loadFormDeleteOrderPurchaseDetail(id).done(function(data) {

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

function loadFormDeleteOrderPurchaseDetail(id) {
    return $.ajax({
        url: ULR + "orderPurchase/delete-orderpurchase-row",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}


//ELIMINAR EL DETALLE DE LA ORDEN DE COMPRA
$('body').on('click', '[data-click="deleteDetailOrderPurchase"]', function(e) {
    e.preventDefault();

    var mdlForm = $('#mdlDelete');
    var loading = $('.loading', mdlForm).first();

    loading.attr('data-show', 'true');

    sendDeleteDetailOrderPurchase().done(function(data) {
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        $('tr[data-id="' + KEY_DELETE + '"]').remove();
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }).fail(function(data) {
        swal("Error", data.message, "error");
        loading.attr('data-show', 'false');
    });
});

function sendDeleteDetailOrderPurchase() {

    var form = $('#frmDelete');
    var data = form.serialize();

    return $.ajax({
        url: ULR + "orderPurchase/orderpurchase-delete",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });

}

/******************************************************************************/
//INVENTARIO - ORDEN DE COMPRA GENERAL

//FORMULARIO PARA UNIFIRCAR LAS ORDENES DE COMPRA GENERAL
$('body').on('click', '[data-click="openFormGeneralOrderPuchase"]', function(e) {

    //abrir el modal y mostrar loading
    e.preventDefault();
    var mdlForm = $('#mdlFormOrderPurchase');
    var loading = $('.loading', mdlForm).first();
    var ids = $('[name="received"]', '.checked').map(function() {
        return $(this).val();
    }).toArray();
    loading.attr('data-show', 'true');
    mdlForm.modal('show');

    //abrir el formulario de ordenes generales
    loadFormGeneralOrderPurchase(ids).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        loading.attr('data-show', 'false');
        initSelectProduct();
        if (data.successError == false) {
            swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
        }

    }).fail(function(error) {
        handleErrors(error);
    });
});

function loadFormGeneralOrderPurchase(ids) {
    return $.ajax({
        url: ULR + "orderPurchase/general-form-orderPuchase",
        method: "GET",
        data: { ids: ids },
        dataType: "json"
    });
}

//GUARDAR EL FORMUMARIO DE ORDEN DE COMPRA GENERAL
$('body').on('click', '[data-click="saveGeneralOrderPuschase"]', function(e) {

    var mdlForm = $('#mdlFormOrderPurchase');
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'true');
    saveFormGeneralOrderPurchase().done(function(data) {
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "warning");
        window.location.href = ULR + 'orderPurchase/general';
    }).fail(function(error) {
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'false');
        showErrors(error.responseJSON.errors, '#generalOrderPurchase');
        handleErrors(error);
    });
});

function saveFormGeneralOrderPurchase() {

    var form = $('#generalOrderPurchase');
    var data = form.serialize();
    return $.ajax({
        url: ULR + "orderPurchase/generalOrder-save",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });
}

//LISTA DE ORDEN DE COMPRAS GENERAL
function getListGeneral() {
    var list = $('#listGeneralOrderPurchase');
    $('.loading', list).attr('data-show', 'true');
    loadListGeneralOrderPurchase().done(function(data) {
        list.html(data.view);
        $('.loading', list).attr('data-show', 'false');
        initDataTableAdvance();
    }).fail(function(error) {
        handleErrors(error);
    });
}

function loadListGeneralOrderPurchase() {
    return $.ajax({
        url: ULR + "orderPurchase/general-list",
        method: "GET",
        dataType: "json"
    });
}

//FORMULARIO PARA EDITAR LA ORDEN DE COMPRA GENERAL (ESTA FUNCIONA ESTA DESABILITADA)
$('body').on('click', '[data-click="openFormEditGeneralOrderPuchase"]', function(e) {

    //abrir el modal y mostrar loading
    e.preventDefault();
    var mdlForm = $('#mdlFormOrderPurchase');
    var loading = $('.loading', mdlForm).first();
    var id = $(this).data('id');
    loading.attr('data-show', 'true');
    mdlForm.modal('show');

    //abrir el formulario de ordenes generales
    loadFormEditGeneralOrderPurchase(id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        loading.attr('data-show', 'false');

        initSelectWarehouseTranfer();
        initSelectProduct();
    }).fail(function(error) {
        handleErrors(error);
    });
});

function loadFormEditGeneralOrderPurchase(id) {
    return $.ajax({
        url: ULR + "orderPurchase/edit-general-form-orderPuchase",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

function initDataTableAdvance() {
    $(' .text-inputs-searching').dataTable().fnDestroy();
    var tableSearching = $(' .text-inputs-searching').DataTable({
        "bDestroy": true,
        "language": spanish,
        dom: "<'row mb-2'<'col-sm-12'<'float-right' B>>><'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        buttons: [{
                extend: 'copyHtml5',
                text: 'Copiar',
                exportOptions: {
                    columns: ':not(.nofilter)'
                }
            }, {
                extend: 'excelHtml5',
                text: 'Excel',
                exportOptions: {
                    columns: ':not(.nofilter)'
                }
            },
            {
                extend: 'colvis',
                text: 'Seleccionar columnas'
            }
        ],
        columnDefs: [{
            targetinitDataTableAdvances: [0],
            orderable: false
        }],
        order: [
            [1, "desc"]
        ],
        colReorder: false,
        responsive: true,
        initComplete: function() {
            this.api().columns().every(function() {
                if (!$(this.header()).hasClass("nofilter")) {
                    var column = this;
                    if ($(this.header()).hasClass("htmlfilter")) {
                        var select = $('<select multiple title="Filtrar"></select>')
                            .appendTo($(column.footer()).empty())
                            .on('change', function() {
                                var val = $(this).val();
                                val = $.map(val, function(x) { return $.fn.dataTable.util.escapeRegex(x); });
                                column.search(val.length > 0 ? val.join("|") : '', true, false).draw();
                            });

                        column.data().unique().sort().each(function(d, j) {
                            if (d != "") {
                                if (d != "No disponible") {
                                    d = $(d)[0].innerHTML;
                                }
                                select.append('<option value="' + d + '">' + d + '</option>')
                            }
                        });
                    } else {
                        var select = $('<select multiple title="Filtrar"></select>')
                            .appendTo($(column.footer()).empty())
                            .on('change', function() {
                                var val = $(this).val();
                                val = $.map(val, function(x) { return $.fn.dataTable.util.escapeRegex(x); });
                                column.search(val.length > 0 ? '^' + val.join("|") + '$' : '', true, false).draw();
                            });

                        column.data().unique().sort().each(function(d, j) {
                            if (d != "") {
                                select.append('<option value="' + d + '">' + d + '</option>')
                            }
                        });
                    }
                    select.selectpicker({
                        width: ($(this.header()).width() < 300) ? $(this.header()).width() : 150,
                        liveSearch: true,
                        container: "body"
                    });
                }
            });


        }
    });
}

//FORMULARIO PARA ELIMINAR LA ORDEN DE COMPRA GENERAL
$('body').on('click', '[data-click="openFormDeleteGeneralOrderPurchase"]', function(e) {

    e.preventDefault();
    var mdlForm = $('#mdlDelete');
    var loading = $('.loading', mdlForm).first();
    var id = $(this).data('id');
    loading.attr('data-show', 'true');
    mdlForm.modal('show');

    loadFormDeleteGeneralOrderPurchase(id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        loading.attr('data-show', 'false');
    }).fail(function(error) {
        handleErrors(error);
    });

});

function loadFormDeleteGeneralOrderPurchase(id) {
    return $.ajax({
        url: ULR + "orderPurchase/General-OrderPurchase-delete-form",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

//ELIMINAR LA ORDEN DE COMPRA GENERAL
$('body').on('click', '[data-click="deleteGeneralOrderPurchase"]', function(e) {
    e.preventDefault();

    var mdlForm = $('#mdlDelete');
    var loading = $('.loading', mdlForm).first();

    loading.attr('data-show', 'true');

    sendDeleteGeneralOrderPurchase().done(function(data) {
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        getListGeneral();
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }).fail(function(data) {
        swal("Error", data.message, "error");
        loading.attr('data-show', 'false');
    });

});

function sendDeleteGeneralOrderPurchase() {

    var form = $('#frmDelete');
    var data = form.serialize();

    return $.ajax({
        url: ULR + "orderPurchase/general-delete",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });

}