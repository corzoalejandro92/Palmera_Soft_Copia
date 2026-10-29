TIMELINE = null;
var MOVE_EVENTS = {};
var REMOVE_EVENTS = {};

$("body").ready(function() {
    getList();
    initSelectUserss();
});
$('body').on('click', '.page-link', function(e) {
    e.preventDefault();
    var page = $(this).attr('href').split('page=')[1];
    return $.ajax({
        url: ULR + "audits",
        method: "GET",
        dataType: "json",
        data: {page:page,search: $("[name='search']").val(),perstartdate: $("[name='start_date']").val(),perenddate:$("[name='end_date']").val(),perusers:$("[name='users[]']").val(),perheadquarter:$("[name='tblList_headquarter']").val() ,peraction:$("[name='tblList_action']").val(), perpage: $("[name='tblList_length']").val(),permodel: $("[name='tblList_model']").val() , date: $('input#datetable').val(), search: $("[name='search']").val()},
        success: function(data){
            $('#list').html(data.view);
        }
    });
    
});
$('body').on('click', '[data-click="restore"]', function(e) {
    e.preventDefault();

    var mdlForm = $('#mdlForm');
    
    sendRestore().done(function(data) {
        mdlForm.modal('hide');
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
        getList();
    }).fail(function(error) {
        showErrors(error.responseJSON.errors, '#frmRestoreDocument');
        handleErrors(error);
    });

});
function getList() {
    var list = $('#list');
    $('.loading', list).attr('data-show', 'true');
    loadList().done(function(data) {
        list.html(data.view);
        $('.loading', list).attr('data-show', 'false');
        try {
            initDataTable();
        } catch (error) {

        }

        $('.dropdown-toggle').dropdown();
    }).fail(function(error) {
        handleErrors(error);
    });
}
function loadList() {
    return $.ajax({
        url: ULR + "audits",
        method: "GET",
        dataType: "json"
    });
}
function sendRestore() {

    var form = $('#frmRestore');
    var data = form.serialize();

    return $.ajax({
        url: ULR + "audits/restore",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });
}

function delay(callback, ms) {
    var timer = 0;
    return function() {
      var context = this, args = arguments;
      clearTimeout(timer);
      timer = setTimeout(function () {
        callback.apply(context, args);
      }, ms || 0);
    };
  }

$('body').on('keyup', "[name='search']", delay(function(e) {
    return $.ajax({
        url: ULR + "audits",
        method: "GET",
        dataType: "json",
        data: {search: $("[name='search']").val(), perstartdate: $("[name='start_date']").val(),perenddate:$("[name='end_date']").val(),perusers:$("[name='users[]']").val(),perheadquarter:$("[name='tblList_headquarter']").val() ,peraction:$("[name='tblList_action']").val(), permodel: $("[name='tblList_model']").val() ,perpage: $("[name='tblList_length']").val(), date: $('input#datetable').val(), search: $("[name='search']").val()},
        success: function(data){
            $('#list').html(data.view);
        }
    });

},300));

$('body').on('click', "#startdatebtn", function(e) {
    return $.ajax({
        url: ULR + "audits",
        method: "GET",
        dataType: "json",
        data: {search: $("[name='search']").val(),perstartdate: $("[name='start_date']").val(),perenddate:$("[name='end_date']").val(),perusers:$("[name='users[]']").val(),perheadquarter:$("[name='tblList_headquarter']").val() ,peraction:$("[name='tblList_action']").val(), permodel: $("[name='tblList_model']").val() ,perpage: $("[name='tblList_length']").val(), date: $('input#datetable').val(), search: $("[name='search']").val()},
        success: function(data){
            $('#list').html(data.view);
        }
    });
});
$('body').on('click', "#enddatebtn", function(e) {
    return $.ajax({
        url: ULR + "audits",
        method: "GET",
        dataType: "json",
        data: {search: $("[name='search']").val(),perstartdate: $("[name='start_date']").val(),perenddate:$("[name='end_date']").val(),perusers:$("[name='users[]']").val(),perheadquarter:$("[name='tblList_headquarter']").val() ,peraction:$("[name='tblList_action']").val(), permodel: $("[name='tblList_model']").val() ,perpage: $("[name='tblList_length']").val(), date: $('input#datetable').val(), search: $("[name='search']").val()},
        success: function(data){
            $('#list').html(data.view);
        }
    });
});
//abrir formulario de eliminacion de registro
$('body').on('click', '[data-click="openFormView"]', function(e) {
    e.preventDefault();
    var id = $(this).data('id');
    var mdlForm = $('#mdlForm');
    loadFormView(id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        mdlForm.modal('show');
        initTextareaResize();
    }).fail(function(error) {
        handleErrors(error);
    });

});

function loadFormView(id) {
    return $.ajax({
        url: ULR + "audits-view",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

$('body').on('change', "[name='tblList_length']", function(e) {
    return $.ajax({
        url: ULR + "audits",
        method: "GET",
        dataType: "json",
        data: {search: $("[name='search']").val(),perstartdate: $("[name='start_date']").val(),perenddate:$("[name='end_date']").val(),perusers:$("[name='users[]']").val(),perheadquarter:$("[name='tblList_headquarter']").val() ,peraction:$("[name='tblList_action']").val(), perpage: $("[name='tblList_length']").val(),permodel: $("[name='tblList_model']").val() , date: $('input#datetable').val(), search: $("[name='search']").val()},
        success: function(data){
            $('#list').html(data.view);
        }
    });
});
$('body').on('change', "[name='tblList_model']", function(e) {
    return $.ajax({
        url: ULR + "audits",
        method: "GET",
        dataType: "json",
        data: {search: $("[name='search']").val(),perstartdate: $("[name='start_date']").val(),perenddate:$("[name='end_date']").val(),perusers:$("[name='users[]']").val(),perheadquarter:$("[name='tblList_headquarter']").val() ,peraction:$("[name='tblList_action']").val(), permodel: $("[name='tblList_model']").val() ,perpage: $("[name='tblList_length']").val(), date: $('input#datetable').val(), search: $("[name='search']").val()},
        success: function(data){
            $('#list').html(data.view);
        }
    });
});
$('body').on('change', "[name='tblList_action']", function(e) {
    return $.ajax({
        url: ULR + "audits",
        method: "GET",
        dataType: "json",
        data: {search: $("[name='search']").val(),perstartdate: $("[name='start_date']").val(),perenddate:$("[name='end_date']").val(),perusers:$("[name='users[]']").val(),perheadquarter:$("[name='tblList_headquarter']").val() ,peraction:$("[name='tblList_action']").val(), permodel: $("[name='tblList_model']").val() ,perpage: $("[name='tblList_length']").val(), date: $('input#datetable').val(), search: $("[name='search']").val()},
        success: function(data){
            $('#list').html(data.view);
        }
    });
});
$('body').on('change', "[name='tblList_headquarter']", function(e) {
    return $.ajax({
        url: ULR + "audits",
        method: "GET",
        dataType: "json",
        data: {search: $("[name='search']").val(),perstartdate: $("[name='start_date']").val(),perenddate:$("[name='end_date']").val(),perusers:$("[name='users[]']").val(),perheadquarter:$("[name='tblList_headquarter']").val() ,peraction:$("[name='tblList_action']").val(), permodel: $("[name='tblList_model']").val() ,perpage: $("[name='tblList_length']").val(), date: $('input#datetable').val(), search: $("[name='search']").val()},
        success: function(data){
            $('#list').html(data.view);
        }
    });
});
$('body').on('change', "[name='start_date']", function(e) {
    return $.ajax({
        url: ULR + "audits",
        method: "GET",
        dataType: "json",
        data: {search: $("[name='search']").val(),perstartdate: $("[name='start_date']").val(),perenddate:$("[name='end_date']").val(),perusers:$("[name='users[]']").val(),perheadquarter:$("[name='tblList_headquarter']").val() ,peraction:$("[name='tblList_action']").val(), permodel: $("[name='tblList_model']").val() ,perpage: $("[name='tblList_length']").val(), date: $('input#datetable').val(), search: $("[name='search']").val()},
        success: function(data){
            $('#list').html(data.view);
        }
    });
});
$('body').on('change', "[name='end_date']", function(e) {
    return $.ajax({
        url: ULR + "audits",
        method: "GET",
        dataType: "json",
        data: {search: $("[name='search']").val(),perstartdate: $("[name='start_date']").val(),perenddate:$("[name='end_date']").val(),perusers:$("[name='users[]']").val(),perheadquarter:$("[name='tblList_headquarter']").val() ,peraction:$("[name='tblList_action']").val(), permodel: $("[name='tblList_model']").val() ,perpage: $("[name='tblList_length']").val(), date: $('input#datetable').val(), search: $("[name='search']").val()},
        success: function(data){
            $('#list').html(data.view);
        }
    });
});
$('body').on('change', "[name='users[]']", function(e) {
    return $.ajax({
        url: ULR + "audits",
        method: "GET",
        dataType: "json",
        data: {search: $("[name='search']").val(),perusers:$("[name='users[]']").val(),perheadquarter:$("[name='tblList_headquarter']").val() ,peraction:$("[name='tblList_action']").val(), permodel: $("[name='tblList_model']").val() ,perpage: $("[name='tblList_length']").val(), date: $('input#datetable').val(), search: $("[name='search']").val()},
        success: function(data){
            $('#list').html(data.view);
        }
    });
});
function initSelectUserss() {

    $('[name="users[]"]').select2({
        placeholder: "Seleccionar un usuario",
        width: "100%",
        ajax: {
            url: ULR + "audits-users",
            dataType: 'json',
            method: "GET",

            error: function(error) {
                handleErrors(error);
            }
        },
        "language": {
            "noResults": () => "No se encontraron usuarios para esta sede.",
            "searching": () => "Consultando..."
        },
        //dropdownAdapter: Utils.Decorate( Utils.Decorate(Dropdown,AttachBody),SelectAll)
    });
}

