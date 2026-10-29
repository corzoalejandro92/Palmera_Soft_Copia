//DEFINIR CONSTANTES
HEADQUARTER_ID = '';
ROLE_ID = '';
KEY_DELETE = '';

$(document).ready(function() {
    getList();
});

//-- EVENTOS PARA FORMULARIO DE USUARIOS ----- 

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
        loading.attr('data-show', 'false');
        //inicializar funciones de plugins
        initSelects();
    }).fail(function(error) {
        handleErrors(error);
    });
});

$('body').on('click', '[data-click="openFormProfile"]', function(e) {
    swal('Acción no permitida', 'No puedes editar tu perfil cuando estás viendo el <b>listado de usuarios</b>, para realizar alguna modificación a tu usuario, puedes buscarlo en el listado y editarlo directamente.', 'info');
});

$('body').on('click', '[data-click="save"]', function(e) {

    var mdlForm = $('#mdlForm');
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'true');
    saveForm();
});

$('body').on('keyup', '[data-keyup="search"]', function(e) {

    var input, filter, tr;
    input = $('[data-keyup="search"]');
    filter = input.val().toUpperCase();
    tr = $('#tblListShops tr');

    $.each(tr, function(key, value) {
        td = $('td', this);
        if (td) {
            if (td.text().toUpperCase().indexOf(filter) > -1) {
                $(this).removeClass('d-none');
            } else {
                $(this).addClass('d-none');
            }
        }
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
        getList();
        swal("Correcto", data.message, "success");
    }).fail(function(error) {
        swal("Error", error.message, "error");
        loading.attr('data-show', 'false');
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
        swal("Correcto", data.message, "success");
    }).fail(function(error) {
        loading.attr('data-show', 'false');
        showErrors(error.responseJSON.errors, '#user');
        handleErrors(error);
    });

});

function sendDeleteDocument() {

    var form = $('#frmDeleteDocument');
    var data = form.serialize();

    return $.ajax({
        url: "users/delete-doc",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });

}


//-- EVENTOS PARA FORMULARIO DE DOCUMENTOS DE USUARIO -----

$('body').on('click', '[data-click="addDocumentRow"]', function(e) {

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

$('body').on('click', '[data-click="removeDocumentRow"]', function(e) {

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
        }).fail(function(error) {
            handleErrors(error);
        });
    }

});

function loadFormDeleteDocument(id) {
    return $.ajax({
        url: "users/document",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}


//-- FUNCIÓNES PARA FORMULARIOS DE USUARIOS

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
        url: "users/list",
        method: "GET",
        dataType: "json"
    });
}

function loadForm(id) {
    return $.ajax({
        url: ULR + "users/" + 'form',
        method: "GET",
        data: { id: id, profile: false },
        dataType: "json"
    });
}

function saveForm() {

    var form = $('#user')[0];
    var formData = new FormData(form);
    return $.ajax({
        url: $(form).attr('action'),
        contentType: false,
        processData: false,
        headers: {
            'X-CSRF-Token': $('#user [name="_token"]').val()
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
        mdlForm.modal('hide');
        getList();

    }

    swal(data.success ? "Correcto" : 'Error', data.message, data.success ? "success" : "error");

}

function fail(error) {
    var mdlForm = $('#mdlForm');
    showErrors(error.responseJSON.errors);
    handleErrors(error);
}

function loadFormDelete(id) {
    return $.ajax({
        url: "users/delete-form",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

function sendDelete() {

    var form = $('#frmDelete');
    var data = form.serialize();

    return $.ajax({
        url: "users/delete",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });

}

function initSelects() {

    setTimeout(function() {
        initSelectHeadquarters();
        initSelectRoles();
        initSelectZones();
        initSelectAvailabilities();
        initSelectCustomers();
        initSelectServices();
    }, 100);

}

function initSelectHeadquarters() {

    $('#headquarter_id').select2({
        width: '100%',
        dropdownParent: $('.modal-content', $('#mdlForm')),
        ajax: {
            url: "users/headquarters",
            dataType: 'json',
            method: "GET",
            error: function(error) {
                handleErrors(error);
            }
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        HEADQUARTER_ID = select_val;

        //$("#sltCity").val('').trigger('change');
        //$('#sltCity').select2('destroy');
        //initSelectCity();
    });
}

function initSelectRoles() {

    $('#role_id').select2({
        width: '100%',
        dropdownParent: $('.modal-content', $('#mdlForm')),
        ajax: {
            url: "users/roles",
            dataType: 'json',
            method: "GET",
            error: function(error) {
                handleErrors(error);
            }
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        ROLE_ID = select_val;
        let id = '';
        if (ROLE_ID.includes('11')) {
            $('.zones').removeClass('display-hidden');
            $('.availabilities').removeClass('display-hidden');
            $('.customers').removeClass('display-hidden');
            $('.services').removeClass('display-hidden');
            $('.warehouse-foxcon').removeClass('display-hidden');
            $('.id-foxcon').addClass('display-hidden');
            var loading = $('.loading-min').first();
            loading.attr('data-show', 'true');
            loadDocumentForm(id).done(function(data) {
                loading.attr('data-show', 'false');
                $('.documents').html(data.view);
            }).fail(function(error) {
                handleErrors(error);
            });
        } else if (ROLE_ID.includes('4')) {
            $('.customers').removeClass('display-hidden');
            $('.warehouse-foxcon').addClass('display-hidden');
            $('.id-foxcon').addClass('display-hidden');
        } else if (ROLE_ID.includes('3')) {
            $('.customers').removeClass('display-hidden');
            $('.warehouse-foxcon').addClass('display-hidden');
            $('.id-foxcon').addClass('display-hidden');
        } else {
            $('.documents').html('');
            $('.zones').addClass('display-hidden');
            $('.availabilities').addClass('display-hidden');
            $('.customers').addClass('display-hidden');
            $('.services').addClass('display-hidden');
            $('.warehouse-foxcon').addClass('display-hidden');
            $('.id-foxcon').removeClass('display-hidden');
        }

    }).on("select2:unselect", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        ROLE_ID = select_val;
        if (!ROLE_ID.includes('11')) {
            $('.documents').html('');
            $('.zones').addClass('display-hidden');
            $('.availabilities').addClass('display-hidden');
            $('.customers').addClass('display-hidden');
            $('.services').addClass('display-hidden');
            $('.warehouse-foxcon').addClass('display-hidden');
            $('.id-foxcon').removeClass('display-hidden');
        }
    });
}

function initSelectZones() {

    $('#zone_id').select2({
        width: '100%',
        dropdownParent: $('.modal-content', $('#mdlForm')),
        allowClear: true,
        ajax: {
            url: "users/zones",
            dataType: 'json',
            method: "GET",
            error: function(error) {
                handleErrors(error);
            }
        }
    });
}

function initSelectServices() {

    $('#service_id').select2({
        width: '100%',
        dropdownParent: $('.modal-content', $('#mdlForm')),
        ajax: {
            url: "users/services",
            dataType: 'json',
            method: "GET",
            error: function(error) {
                handleErrors(error);
            }
        },
        placeholder: "Dejar vacío para aplicar a todos",
        allowClear: true
    });
}

function initSelectAvailabilities() {

    $('#availability_id').select2({
        width: '100%',
        dropdownParent: $('.modal-content', $('#mdlForm')),
        ajax: {
            url: "users/availabilities",
            dataType: 'json',
            method: "GET",
            error: function(error) {
                handleErrors(error);
            }
        }
    });
}

function initSelectCustomers() {

    $('#customer_id').select2({
        width: '100%',
        dropdownParent: $('.modal-content', $('#mdlForm')),
        ajax: {
            url: "users/customers",
            dataType: 'json',
            method: "GET",
            error: function(error) {
                handleErrors(error);
            }
        }
    });
}


//--- FUNCIONES PARA FORMULARIOS DE DOCUMENTOS DE USUARIO

function addDocumentRow() {
    return $.ajax({
        url: ULR + "users/add-document-row",
        method: "GET",
        data: {},
        dataType: "json"
    });
}

function loadDocumentForm(id) {
    return $.ajax({
        url: ULR + "users/document-form",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

function loadFormDocumentDelete(id) {
    return $.ajax({
        url: "users/delete-document-form",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}