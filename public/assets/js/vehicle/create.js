METHOD = 'POST';
KEY_DELETE = '';
//lista de vehiculos
$(document).ready(function() {

    getList();
});

function getList() {
    var list = $('#list');
    $('.loading', list).attr('data-show', 'true');
    loadList().done(function(data) {
        list.html(data.view);
        $('.loading', list).attr('data-show', 'false');
        initSelecUsers();
        initDataTable();
    });
}

function loadList() {
    return $.ajax({
        url: "vehicles/form-list",
        method: "GET",
        dataType: "json"
    });
}


//cargar el formulario
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

        initSelects();
        loading.attr('data-show', 'false');
    }).fail(function(error) {
        handleErrors(error);
    });
});

function loadForm(id) {
    return $.ajax({
        url: ULR + "vehicles/form-vehicle",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}


function initSelects() {

    setTimeout(function() {
        initSelectVehiculo();
        initSelectheadquarters();
        initSelecUsers();
    }, 100);

}

function initSelectVehiculo() {

    $('#vehicletype').select2({
        tags: true,
        ajax: {
            url: "vehicles/type",
            dataType: 'json',
            method: "GET",
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        VEHICULO = select_val;

        //$("#sltCity").val('').trigger('change');
        //$('#sltCity').select2('destroy');
        //initSelectCity();
    });
}

function initSelectheadquarters() {

    $('#headquarters').select2({
        ajax: {
            url: "vehicles/headquarter",
            dataType: 'json',
            method: "GET",
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        HEADQUARTER = select_val;

        //$("#sltCity").val('').trigger('change');
        //$('#sltCity').select2('destroy');
        //initSelectCity();
    });
}

function initSelecUsers() {

    $('.userVehicle').select2({
        ajax: {
            url: "vehicles/user",
            dataType: 'json',
            method: "GET",
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        USER = select_val;

        //$("#sltCity").val('').trigger('change');
        //$('#sltCity').select2('destroy');
        //initSelectCity();
    });
}


$('body').on('click', '[data-click="save"]', function(e) {

    var mdlForm = $('#mdlForm');
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'true');
    saveForm();
});


function saveForm() {

    var form = $('#vehicle')[0];
    var formData = new FormData(form);
    return $.ajax({
        url: $(form).attr('action'),
        contentType: false,
        processData: false,
        headers: {
            'X-CSRF-Token': $('#vehicle [name="_token"]').val()
        },
        method: METHOD,
        data: formData,
        success: success,
        error: fail
    });
}

function saveSelect(id, user) {

    return $.ajax({
        url: ULR + "vehicles/save-user/" + id,
        method: "GET",
        data: { user: user },
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
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }

}

function fail(error) {
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'false');
    showErrors(error.responseJSON.errors, '#vehicle');
    handleErrors(error);
}

function addDocumentRow() {
    return $.ajax({
        url: ULR + "vehicles/add-document-row",
        method: "GET",
        data: {},
        dataType: "json"
    });
}

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
        url: "vehicle/delete-form",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

function loadFormDeleteDocument(id) {
    return $.ajax({
        url: "vehicle/document",
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
        showErrors(error.responseJSON.errors, '#vehicle');
        handleErrors(error);
    });

});

$('body').on('click', '[data-click="deleteDocument"]', function(e) {
    e.preventDefault();

    var mdlForm = $('#mdlDeleteDocument');
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
        showErrors(error.responseJSON.errors, '#vehicle');
        handleErrors(error);
    });

});

function sendDeleteDocument() {

    var form = $('#frmDeleteDocument');
    var data = form.serialize();

    return $.ajax({
        url: "vehicle/delete-doc",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });

}

function sendDelete() {

    var form = $('#frmDelete');
    var data = form.serialize();

    return $.ajax({
        url: "vehicle/delete",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });

}

//-- EVENTOS PARA FORMULARIO DE DOCUMENTOS DE USUARIO -----

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

$('body').on('change', '.custom-file input', function(e) {
    let key = $(this).data('file');
    let name = e.target.files[0].name;
    $(this).next('.custom-file-label[data-label="' + key + '"]').html(name);
});

$('body').on('click', '[data-click="removeRow"]', function(e) {

    if ($(this + ' input[name*="id"]').length > 0) {

        let key = $(this).data('id');

        var mdlForm = $('#mdlDeleteDocument');
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'true');
        mdlForm.modal('show');



        KEY_DELETE = key;
        let id = $('input[name="document[' + key + '][id]"]').val();
        e.preventDefault();



        loadFormDeleteDocument(id).done(function(data) {
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

//
$('body').on('change', '[data-change="saveListVehicle"]', function(e) {

    let user = $(this).val();
    let id = $(this).data('id');



    saveSelect(id, user).done(function(data) {
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }).fail(function(error) {
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'false');
        showErrors(error.responseJSON.errors, '#vehicle');
        handleErrors(error);
    });
});