$(document).ready(function() {
    getList();
});


function getList() {
    var list = $('#listSetting');
    $('.loading', list).attr('data-show', 'true');
    loadList().done(function(data) {

        $(list).html(data.view);
        $('.loading', list).attr('data-show', 'false');
        initDataTable();
    });
}

function loadList() {
    return $.ajax({
        url:   "settings/list",
        method: "GET",
        dataType: "json"
    });
}

function loadForm(id) {
    return $.ajax({
        url: "settings/form",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

function saveForm() {

    var form = $('#settings');
    var data = form.serialize();
    return $.ajax({
        url: 'settings/save',
        method: $('[name="_method"]', form).val(),
        data: data,
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
    }).fail(function(error){
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
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success": "error");
    }).fail(function(error) {
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'false');
        showErrors(error.responseJSON.errors,'#settings');
        handleErrors(error);
    }); 
});
