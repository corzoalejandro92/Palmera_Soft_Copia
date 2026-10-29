//----------- FUNCIONES PARA EDITAR PERFIL DEL USUARIO -------
$('body').on('click', '[data-click="openFormProfile"]', function(e) {

    //abrir el modal y mostrar loading
    e.preventDefault();
    var mdlForm = $('#mdlFormProfile');
    var loading = $('.loading', mdlForm).first();
    var id = $(this).data('id');
    loading.attr('data-show', 'true');
    mdlForm.modal('show');

    //leer el formulario de usuarios
    loadFormProfile(id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        loading.attr('data-show', 'false');
        //inicializar funciones de plugins
        $("#headquarter_id").select2({
            disabled: true
        });
        $("#role_id").select2({
            disabled: true
        });
        initSelectsProfile();
    }).fail(function(error) {
        setTimeout(function() {
            mdlForm.modal('hide');
            handleErrors(error);
        }, 500);

    });
});


function loadProfileInfo(id) {
    return $.ajax({
        url: ULR + "users/profileInfo",
        method: "GET",
        dataType: "json"
    });
}

$('body').on('click', '[data-click="saveProfile"]', function(e) {

    var mdlForm = $('#mdlForm');
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'true');
    saveFormProfile();
});

function saveFormProfile() {

    var form = $('#user')[0];
    var formData = new FormData(form);
    return $.ajax({
        url: ULR + $(form).attr('action'),
        contentType: false,
        processData: false,
        headers: {
            'X-CSRF-Token': $('#user [name="_token"]').val()
        },
        method: $('[name="_method"]', form).val(),
        data: formData,
        success: successProfile,
        error: failProfile
    });
}

function successProfile(data) {
    if (data.success) {
        var mdlForm = $('#mdlFormProfile');
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        if (data.profile == 'false') {
            getList();
        } else {
            getProfileInfo();
        }

        swal("Correcto", data.message, "success");
    }

}

function failProfile(error) {
    var mdlForm = $('#mdlFormProfile');
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'false');
    showErrors(error.responseJSON.errors);
    handleErrors(error);
}

function getProfileInfo() {
    loadProfileInfo().done(function(data) {
        $('.dropdown.dropdown-user.nav-item').remove();
        $('.nav.navbar-nav.float-right').append(data.view);
    }).fail(function(error) {
        handleErrors(error);
    });
}

//DEFINIR CONSTANTES
HEADQUARTER_ID = '';
ROLE_ID = '';



//-- EVENTOS PARA FORMULARIO DE DOCUMENTOS DE USUARIO -----

$('body').on('click', '[data-click="addDocumentRow"]', function(e) {
    e.preventDefault();
    addDocumentRow().done(function(data) {
        $('.tbl-documents tbody').append(data.row);
    }).error(function(error) {
        handleErrors(error)
    });
});

$('body').on('change', '.custom-file input', function(e) {
    let key = $(this).data('file');
    let name = e.target.files[0].name;
    $(this).next('.custom-file-label[data-label="' + key + '"]').html(name);
});

$('body').on('click', '[data-click="removeDocumentRow"]', function(e) {
    e.preventDefault();

    if ($(this + ' input[name*="id"]').length > 0) {
        let key = $(this).data('id');
        $('tr[data-id="' + key + '"]').remove();
    }
});



//-- FUNCIÓNES PARA FORMULARIOS DE USUARIOS

function loadFormProfile(id) {
    return $.ajax({
        url: ULR + "users/profile",
        method: "GET",
        data: { id: id, profile: true },
        dataType: "json"
    });
}


function initSelectsProfile() {

    setTimeout(function() {
        initSelectHeadquarters();
        initSelectRoles();
    }, 100);

}

function initSelectHeadquarters() {

    $('#headquarter_id').select2({
        ajax: {
            url: "users/headquarters",
            dataType: 'json',
            method: "GET",
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
        ajax: {
            url: "users/roles",
            dataType: 'json',
            method: "GET",
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        ROLE_ID = select_val;
        let id = '';
        if (ROLE_ID.includes('11')) {
            loadDocumentForm(id).done(function(data) {
                $('.documents').html(data.view);
            });
        } else {
            $('.documents').html('');
        }

    }).on("select2:unselect", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        ROLE_ID = select_val;
        if (!ROLE_ID.includes('11')) {
            $('.documents').html('');
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

function sendDeleteDocument(id) {

}