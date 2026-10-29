$(document).ready(function() {
    getList();
});

function getList() {
    var list = $('#list');
    $('.loading', list).attr('data-show', 'true');
    loadList().done(function(data) {
        list.html(data.view);
        $('.loading', list).attr('data-show', 'false');
        initDataTable();
    });
}

function loadList() {
    return $.ajax({
        url: "infestations/list",
        method: "GET",
        dataType: "json"
    });
}

function loadForm(id) {
    return $.ajax({
        url: "infestations/form",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

function saveForm() {

    var form = $('#infestations')[0];
    var formData = new FormData(form);
    return $.ajax({
        url: $(form).attr('action'),
        contentType: false,
        processData: false,
        headers: {
            'X-CSRF-Token': $('#infestations [name="_token"]').val()
        },
        method: $('[name="_method"]', form).val(),
        data: formData,
        success: success,
        error: fail
    });
}

function success(data) {
    if (data.success) {
        var mdlForm = $('#mdlForm');
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        getList();

    }

    swal(data.success ? "Correcto" : 'Error', data.message, data.success ? "success" : "error");

}

function fail(error) {
    var mdlForm = $('#mdlForm');
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'false');
    showErrors(error.responseJSON.errors);
    handleErrors(error);
}


$('body').on('click', '[data-click="openForm"]', function(e) {

    //abrir el modal y mostrar loading
    e.preventDefault();
    var mdlForm = $('#mdlForm');
    var loading = $('.loading', mdlForm).first();
    var id = $(this).data('id');
    loading.attr('data-show', 'true');
    mdlForm.modal('show');

    //leer el formulario de usuarios
    loadForm(id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        initSelectService();
        loading.attr('data-show', 'false');
    });
});

$('body').on('click', '[data-click="save"]', function(e) {

    var mdlForm = $('#mdlForm');
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'true');
    saveForm();
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
    });

});

function loadFormDelete(id) {
    return $.ajax({
        url: "infestations/delete-form",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

$('body').on('click', '[data-click="delete"]', function(e) {
    e.preventDefault();

    var mdlForm = $('#mdlDelete');
    var loading = $('.loading', mdlForm).first();

    loading.attr('data-show', 'true');

    sendDelete().done(function(data) {
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        getList();
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }).fail(function(error) {
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'false');
        showErrors(error.responseJSON.errors, '#frmDelete');
        handleErrors(error);
    });

});

function sendDelete() {

    var form = $('#frmDelete');
    var data = form.serialize();

    return $.ajax({
        url: "infestations/delete",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });

}

function initSelectService() {

    selector = '[name*="services[]"]';
    $(selector).select2({
        width: '100%',
        dropdownParent: $('.modal-content', $('#mdlForm')),
        placeholder: "Seleccione un servicio",
        ajax: {
            url: ULR + "infestations/services",
            dataType: 'json',
            method: "GET",
            success: selectScroll,
            error: function(error) {
                var mdlForm = $('#mdlForm');
                var loading = $('.loading', mdlForm).first();
                loading.attr('data-show', 'false');
                handleErrors(error);
            }
        },
        cache: true,
        templateResult: dataFormatResultService,
        templateSelection: dataFormatSelection,
        dropdownCssClass: "bigdrop",
        escapeMarkup: function(m) { return m; },
        "language": {
            "noResults": () => "No se encontraron servicios registrados en el sistema",
            "searching": () => "Consultando..."
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        key = $(e.currentTarget).data('key');
        initSelectArea(select_val, key);
        //initSelectInfestation(select_val,key);
    }).on("select2:open", e => selectScroll());
}

$('body').on('click', '[data-click="removeRow"]', function(e) {

    if ($(this + ' input[name*="id"]').length > 0) {

        let key = $(this).data('id');

        var mdlForm = $('#mdlDelete');
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'true');


        KEY_DELETE = key;
        let id = $('input[name="plaga[' + key + '][id]"]').val();
        e.preventDefault();



        loadFormDeleteDocument(id).done(function(data) {
            if (!data.success) {
                setTimeout(() => {
                    mdlForm.modal('hide');
                    $('tr[data-id="' + KEY_DELETE + '-3"]').remove();
                    $('tr[data-id="' + KEY_DELETE + '-2"]').remove();
                    $('tr[data-id="' + KEY_DELETE + '"]').remove();
                }, 500);
            } else {
                mdlForm.modal('show');
            }

            $('.modal-content', mdlForm).html(data.view);
            loading.attr('data-show', 'false');
        });
    }


});

function loadFormDeleteDocument(id) {
    return $.ajax({
        url: ULR + "infestations/document",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

$('body').on('click', '[data-click="delete"]', function(e) {
    e.preventDefault();

    var mdlForm = $('#mdlDelete');
    var loading = $('.loading', mdlForm).first();

    loading.attr('data-show', 'true');

    sendDelete().done(function(data) {
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        getList();
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }).fail(function(error) {
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'false');
        showErrors(error.responseJSON.errors, '#infestations');
        handleErrors(error);
    });

});

$('body').on('click', '[data-click="deleteFile"]', function(e) {
    e.preventDefault();

    var mdlForm = $('#mdlDelete');
    var loading = $('.loading', mdlForm).first();

    loading.attr('data-show', 'true');

    sendDeleteDocument().done(function(data) {
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        console.log(KEY_DELETE);
        $('tr[data-id="' + KEY_DELETE + '"]').remove();
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }).fail(function(error) {
        loading.attr('data-show', 'false');
        showErrors(error.responseJSON.errors, '#infestations');
        handleErrors(error);
    });

});

function sendDeleteDocument() {

    var form = $('#frmDeleteFile');
    var data = form.serialize();

    return $.ajax({
        url: ULR + "infestations/delete-doc",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });

}

//-- EVENTOS DE FORMULARIO PARA IMAGENES DE PLAGAS -----

$('body').on('click', '[data-click="addRow"]', function(e) {
    e.preventDefault();
    var loading = $('.loading-min').first();
    loading.attr('data-show', 'true');
    addDocumentRow().done(function(data) {
        $('.tbl-documents tbody').append(data.row);
        loading.attr('data-show', 'false');
    }).fail(function(error) {
        handleErrors(error);
        loading.attr('data-show', 'false');
    });
});

function addDocumentRow() {
    return $.ajax({
        url: ULR + "infestations/add-document-row",
        method: "GET",
        data: {},
        dataType: "json"
    });
}

//
$('body').on('change', '[data-change="saveListInfestation"]', function(e) {

    let user = $(this).val();
    let id = $(this).data('id');



    saveSelect(id, user).done(function(data) {
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }).fail(function(error) {
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'false');
        showErrors(error.responseJSON.errors, '#infestations');
        handleErrors(error);
    });
});