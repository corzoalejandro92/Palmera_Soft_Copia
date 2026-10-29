let TOTAL_FACTURA = 0;
$(document).ready(function() {
    getList();
});

function getList() {
    var list = $('#list');
    $('.loading', list).attr('data-show', 'true');
    loadList().done(function(data) {
        list.html(data.view);
        initDataTable();
        initSelecstateBilling();
    }).fail(function(error) {
        handleErrors(error);
    });
}

function loadList() {
    return $.ajax({
        url: ULR + "billing/list",
        method: "GET",
        dataType: "json"
    });
}

function initSelecstateBilling() {

    $('#stateBilling').select2({
        width: '100%',
        dropdownParent: $('.modal-content', $('#mdlForm')),
        ajax: {
            url: ULR + "billing/state",
            dataType: 'json',
            method: "GET",
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        STATES = select_val;

        //$("#sltCity").val('').trigger('change');
        //$('#sltCity').select2('destroy');
        //initSelectCity();
    });
}

$('body').on('change', '[data-change="saveStateBilling"]', function(e) {

    let state = $(this).val();
    let id = $(this).data('id');



    saveStateBilling(id, state).done(function(data) {
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }).fail(function(error) {
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'false');
        showErrors(error.responseJSON.errors, '#billing');
        handleErrors(error);
    });
});

function saveStateBilling(id, state) {

    return $.ajax({
        url: ULR + "billing/save-state/" + id,
        method: "GET",
        data: { state: state },
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
        getList();
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


$('body').on('click', '[data-click="openForm"]', function(e) {
    console.log("entra");
    //abrir el modal y mostrar loading
    e.preventDefault();
    var mdlForm = $('#mdlForm');
    var loading = $('.loading', mdlForm).first();
    var id = $(this).data('id');
    loading.attr('data-show', 'true');
    mdlForm.modal('show');

    //leer el formulario
    loadForm(id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        loading.attr('data-show', 'false');
        initSelectCustomer();
        calc();
    }).fail(function(error) {
        handleErrors(error);
    });
});

function loadForm(id) {
    return $.ajax({
        url: ULR + "billing/form-proforma",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

function initSelectCustomer() {
    $('[name="customer[]"]').select2({
        width: '100%',
        dropdownParent: $('.modal-content', $('#mdlForm')),
        placeholder: "Dejar vacío para todos",
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