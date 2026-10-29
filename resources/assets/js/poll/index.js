var PAGE=1;
$(document).ready(function() {
    let urll=window.location.pathname;
    (urll == "/poll") ? getList() : getListResults();
});
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

$('body').on('keyup', "input[type='search']", delay(function(e) {
    getListResults();
},300));
$('body').on('click', ".search", function(e) {
    e.preventDefault();
    let val = $(this).val()
    if(val != ''){
        $(this).val('')
        getListResults();
    }
});
$('body').on('change', "[name='tblList_length']", function(e) {
    getListResults();
});
$('body').on('click', '.page-link', function(e) {
    e.preventDefault();
    PAGE = $(this).attr('href').split('page=')[1];
    getListResults(PAGE)
});

function getListResults(page=1) {
    var listResult = $('#listResult');
    $('.loading', listResult).attr('data-show', 'true');
    loadListResults(page).done(function(data) {
        listResult.html(data.view);
        $('.loading', listResult).attr('data-show', 'false');
        initDataTable();
    });
}

function loadListResults(page) {
    return $.ajax({
        url: ULR + "poll/index/result",
        method: "GET",
        data: {page:page, search: $("input[type='search']").val(), perpage: $("[name='tblList_length']").val()},
        dataType: "json"
    });
}

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
        url: ULR+"poll/list",
        method: "GET",
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
        url:  ULR+"poll/delete-form",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

$('body').on('click', '[data-click="openFormResults"]', function(e) {

    e.preventDefault();
    var mdlForm = $('#mdlShowResults');
    var loading = $('.loading', mdlForm).first();
    var id = $(this).data('id');
    loading.attr('data-show', 'true');
    mdlForm.modal('show');

    loadFormShowSurvey(id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        loading.attr('data-show', 'false');
    });
});

function loadFormShowSurvey(id) {
    return $.ajax({
        url:  ULR+"poll/result/survey/show",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

$('body').on('click', "[data-click='deleteSurveys']", function() {
    let id = $(this).data('id');
    Swal.fire({
        title: 'Eliminar encuesta',
        text: "¿Estas seguro que deseas eliminar encuesta N° "+id+" ? Esta acción será irreversible.",
        icon: 'warning',
        type:'question',
        showCancelButton: true,
        confirmButtonColor: '#009688',
        cancelButtonColor: '#aaa',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Si, Eliminar',
    }).then((result) => {
        if (result.value) {
            sendDeleteSurveys(id).done(function(data) {
                getListResults(PAGE);
                swal.fire({
                    'title': data.success ? "Correcto" : "Error",
                    'text': data.message,
                    'showConfirmButton': false,
                    'timer': 1500,
                    'type': data.success ? "success" : "error",
                })
            }).fail(function(error) {
                handleErrors(error);
            });
        }
      })
    

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
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success": "error");
    }).fail(function(error) {
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'false');
    showErrors(error.responseJSON.errors,'#frmDelete');
    handleErrors(error);
   });

});

function sendDelete() {

    var form = $('#frmDelete');
    var data = form.serialize();

    return $.ajax({
        url: ULR+"poll/delete",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });

}
function sendDeleteSurveys(id) {

    return $.ajax({
        url: ULR+"poll/delete/survey",
        method: 'GET',
        data: {id:id},
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