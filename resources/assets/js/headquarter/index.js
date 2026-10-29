//DEFINIR CONSTANTES
HEADQUARTER_ID = '';
ROLE_ID = '';

COUNTRY_ID = 47;
STATE_ID = '';
CITY_ID = '';

$(document).ready(function() {
    getList();
});

//-- EVENTOS PARA FORMULARIO DE SEDES ----- 

$('body').on('click', '[data-click="openForm"]', function(e) {

    //abrir el modal y mostrar loading
    e.preventDefault();
    var mdlForm = $('#mdlForm');
    var id = $(this).data('id');
    mdlForm.modal('show');

    //leer el formulario de sedes
    loadForm(id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        //inicializar funciones de plugins
        initSelectStates();
        initSelectZones();
        initAddress();
        if (data.edit) {
            STATE_ID = $('#state_id').val();
            initSelectCities();
        }

        if (data.edit == true) {
            initSelectStates(data.state);
        }
        hideLoading();
    }).fail(function(error) {
        handleErrors(error);
    });
});

$('body').on('click', '[data-click="openFormZone"]', function(e) {

    //abrir el modal y mostrar loading
    e.preventDefault();
    var mdlForm = $('#mdlFormMap');
    var loading = $('.loading', mdlForm).first();
    var id = $(this).data('id');
    var headquarter = $(this).data('headquarter');
    var city = $(this).data('city');
    loading.attr('data-show', 'true');
    mdlForm.modal('show');

    //leer el formulario de usuarios
    loadFormZone(id, headquarter).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        loading.attr('data-show', 'false');
        initMap(city, data.coords);
    }).fail(function(error) {
        handleErrors(error);
    });
});

$('body').on('click', '[data-click="save"]', function(e) {

    var mdlForm = $('#mdlForm');
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'true');
    saveForm().done(function(data) {
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        getList();
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "warning");
    }).fail(function(error) {
        loading.attr('data-show', 'false');
        showErrors(error.responseJSON.errors, '#headquarter');
        handleErrors(error);
    });
});

$('body').on('click', '[data-click="saveZone"]', function(e) {

    var mdlForm = $('#mdlFormMap');
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'true');
    saveFormZone().done(function(data) {
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        getList();
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "warning");
    }).fail(function(error) {
        loading.attr('data-show', 'false');
        showErrors(error.responseJSON.errors, '#zones');
        handleErrors(error);
    });
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

$('body').on('click', '[data-click="openFormDeleteZone"]', function(e) {

    e.preventDefault();
    var mdlForm = $('#mdlDelete');
    var loading = $('.loading', mdlForm).first();
    var id = $(this).data('id');
    loading.attr('data-show', 'true');
    mdlForm.modal('show');

    loadFormDeleteZone(id).done(function(data) {
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

$('body').on('click', '[data-click="deleteZone"]', function(e) {
    e.preventDefault();

    var mdlForm = $('#mdlDelete');
    var loading = $('.loading', mdlForm).first();

    loading.attr('data-show', 'true');

    sendDeleteZone().done(function(data) {
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
//FORMULARIO DE CONFIGURACION FOXCON
$('body').on('click', '[data-click="settingFoxconForm"]', function(e) {

    //abrir el modal y mostrar loading
    e.preventDefault();
    var mdlForm = $('#mdlFormSetting');
    var loading = $('.loading', mdlForm).first();
    var headquarter = $(this).data('headquarter');
    var id = $(this).data('id');
    loading.attr('data-show', 'true');
    mdlForm.modal('show');

    //leer el formulario de configurarion de inventario
    loadFoxconFormSetting(headquarter, id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        //inicializar funciones de plugins
        loading.attr('data-show', 'false');
        initSelectDocumentType();
    }).fail(function(error) {
        handleErrors(error);
    });

});

$('body').on('change', '[name="role"]', function(e) {
    var roleId = $(this).val();
    var username = $('[name="username"]');
    var password = $('[name="password"]');

    if (roleId === '5') {
        username.val('').prop('readonly', true);
        password.val('').prop('readonly', true);
        $('.errorShow', username.parent()).remove();
        $('.errorShow', password.parent()).remove();
    } else {
        username.prop('readonly', false);
        password.prop('readonly', false);
    }
});


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
        url: "headquarters/list",
        method: "GET",
        dataType: "json"
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

function loadForm(id) {
    return $.ajax({
        url: ULR + "headquarters/form",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

function loadFormZone(id = 0, headquarter) {
    return $.ajax({
        url: ULR + "headquarters/zone/form",
        method: "GET",
        data: { id: id, headquarter: headquarter },
        dataType: "json"
    });
}

function saveForm() {

    var form = $('#headquarter');
    var data = form.serialize();
    return $.ajax({
        url: 'headquarters/save',
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });
}

function saveFormZone() {

    var form = $('#zones');
    var data = form.serialize();
    return $.ajax({
        url: 'headquarters/zone/save',
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });
}

function loadFormDelete(id) {
    return $.ajax({
        url: "headquarters/delete-form",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

function loadFormDeleteZone(id) {
    return $.ajax({
        url: "headquarters/zone/delete-form",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

function sendDelete() {

    var form = $('#frmDelete');
    var data = form.serialize();

    return $.ajax({
        url: "headquarters/delete",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });

}

function sendDeleteZone() {

    var form = $('#frmDelete');
    var data = form.serialize();

    return $.ajax({
        url: "headquarters/zone/delete",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });

}


function initSelectStates(state) {

    $('#state_id').select2({
        width: '100%',
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
        initSelectCities(STATE_ID);

    });
}

function initSelectCities(STATE_ID) {

    $('#city_id').select2({
        width: '100%',
        tags: true,
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