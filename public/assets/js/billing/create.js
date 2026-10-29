let TOTAL_FACTURA = 0;

$(document).ready(function() {

    getListProforma();
    getFilters();
});

function getListProforma() {
    var list = $('#listProforma');
    $('.loading', list).attr('data-show', 'true');
    loadListProforma().done(function(data) {
        list.html(data.view);
        $('.icheck_minimal input').iCheck({
            checkboxClass: 'icheckbox_minimal',
            radioClass: 'iradio_minimal',
        });
        initDataTable();
    }).fail(function(error) {
        handleErrors(error);
    });
}

function loadListProforma() {
    return $.ajax({
        url: ULR + "billing/proforma-list",
        method: "GET",
        dataType: "json"
    });
}

$('body').on('click', '[data-click="openForm"]', function(e) {
    console.log("entra");
    //abrir el modal y mostrar loading
    e.preventDefault();
    var mdlForm = $('#mdlForm');
    var loading = $('.loading', mdlForm).first();
    var ids = $('[name="received"]', '.checked').map(function() {
        return $(this).val();
    }).toArray();
    loading.attr('data-show', 'true');
    mdlForm.modal('show');

    //leer el formulario
    loadForm(ids).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        loading.attr('data-show', 'false');
        initSelectCustomer();
        calc();
    }).fail(function(error) {
        handleErrors(error);
    });
});

function loadForm(ids) {
    return $.ajax({
        url: ULR + "billing/form-proforma",
        method: "GET",
        data: { ids: ids },
        dataType: "json"
    });
}

$('body').on('click', '[data-click="save"]', function(e) {

    var mdlForm = $('#mdlForm');
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'true');
    saveForm().done(function(data) {
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
        window.location.href = ULR + 'billing';
    }).fail(function(error) {
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'false');
        showErrors(error.responseJSON.errors, '#billing');
        handleErrors(error);
    });
});

function saveForm() {

    var form = $('#billing');
    var data = form.serialize();
    return $.ajax({
        url: ULR + "billing/save",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });
}



//-- FUNCIÓN PARA CARGAR LA VISTA DEL FILTRO Y SUS FUNCIONES

function getFilters() {
    var container = $('#filteContainer');
    $('.loading', container).attr('data-show', 'true');
    loadFilter().done(function(data) {
        container.html(data.view);
        $('.loading', container).attr('data-show', 'false');
        initCollapsable();
        initSelectCustomer();
        $(".collapsed").trigger('click');
    }).fail(function(error) {
        handleErrors(error);
    });
}

function loadFilter() {
    return $.ajax({
        url: ULR + "billing/filters",
        method: "GET",
        dataType: "json"
    });
}

//FILTRO DE PROFORMAS
function initSelectCustomer() {
    $('[name="customer[]"]').select2({
        width: "100%",
        ajax: {
            url: ULR + "billing/crms",
            dataType: 'json',
            method: "GET",
            error: function(error) {
                handleErrors(error);
            }
        },
        "language": {
            "noResults": () => "No se encontraron registros para esta sede.",
            "searching": () => "Consultando..."
        },
        //dropdownAdapter: Utils.Decorate( Utils.Decorate(Dropdown,AttachBody),SelectAll)
    });
}

//AGREGAR UNA FILA NUEVA DE DETALLE DE FACTURACION
$('body').on('click', '[data-click="addRow"]', function(e) {
    e.preventDefault();
    var loading = $('.loading-min').first();
    loading.attr('data-show', 'true');
    addRow().done(function(data) {
        $('.tbl-documents tbody').append(data.row);
        loading.attr('data-show', 'false');
    }).fail(function(error) {
        handleErrors(error);
        loading.attr('data-show', 'false');
    });
});

function addRow() {
    return $.ajax({
        url: ULR + "billing/add-row",
        method: "GET",
        data: {},
        dataType: "json"
    });
}

//FORMULARIO PARA ELIMINAR UN DETALE DE ORDEN DE COMPRA
$('body').on('click', '[data-click="removeRow"]', function(e) {

    if ($(this + ' input[name*="id"]').length > 0) {

        let key = $(this).data('id');

        var mdlForm = $('#mdlDelete');
        var loading = $('.loading-min', '.tbl-documents').first();
        loading.attr('data-show', 'true');

        KEY_DELETE = key;
        let id = $('input[name="billing[' + key + '][id]"]').val();
        e.preventDefault();

        loadFormDelete(id).done(function(data) {

            if (!data.success) {
                $('tr[data-id="' + KEY_DELETE + '"]').remove();
                calc();
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

function loadFormDelete(id) {
    return $.ajax({
        url: ULR + "billing/delete-row",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

//--- RENDERIZAR COLLAPSABLES

function initCollapsable() {
    $('a[data-action="collapse"]').on('click', function(e) {
        e.preventDefault();
        $(this).closest('.card').children('.card-content').collapse('toggle');
        $(this).closest('.card').find('[data-action="collapse"] i').toggleClass('ft-plus ft-minus');
    });
}

function initDataTable() {
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



function calc() {


    //añadir al total los conceptos
    let concepts = $("[name*='valor']").find();
    let concepts_value = 0;
    if (concepts.prevObject.length > 0) {
        $.each(concepts.prevObject, (index, element) => {
            if ($(element).val() != '') {
                concepts_value += parseInt(clear_format($(element).val()));
            }
        });
        TOTAL_FACTURA = concepts_value;
    }

    $("#total").text(number_format(TOTAL_FACTURA, 0, '.', '.'));
    $("[name='total']").val(number_format(TOTAL_FACTURA, 0, '.', '.'));

}


function initSelectCustomer() {

    $('#customer').select2({
        ajax: {
            width: "100%",
            url: ULR + "billing/customer",
            dataType: 'json',
            method: "GET",
            success: selectScroll
        },
        cache: true,
        templateResult: dataFormatResultCRM,
        templateSelection: dataFormatSelection,
        dropdownCssClass: "bigdrop",
        escapeMarkup: function(m) { return m; },
        "language": {
            "noResults": () => "No se encontraron clientes asignados a ti.",
            "searching": () => "Consultando..."
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        CUSTOMER_ID = select_val;

    }).on("select2:open", e => selectScroll());
}







function initDataTable() {
    $('#formProformaList').DataTable({
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

//cuando Jquery se encuentre completamente cargado
// $(document).ready(function() {
//     $('.repeater-default').repeater({
//         show: function() {
//             $(this).slideDown(500);
//         },
//         hide: function(remove) {
//             $(this).slideUp(500);
//         }
//     });
//     $(".select2").select2({
//         tags: true
//     });
//     $('.skin-square input').iCheck({
//         checkboxClass: 'icheckbox_square-red',
//         radioClass: 'iradio_square-red',
//     });
// });
$('body').on('ifChecked', '[type="checkbox"]', function(event) {
    $(".send-email").removeClass('display-hidden');
});

$('body').on('ifunchecked', '[type="checkbox"]', function(event) {
    $(".send-email").addClass('display-hidden');
});

// $('body').on('ifUnchecked', '[type="checkbox"]', function(event) {
//     $(".send-email").addclass('display-hidden');
// });