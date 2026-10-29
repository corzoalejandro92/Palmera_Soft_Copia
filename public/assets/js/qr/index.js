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
    }).fail(function(error) {
        handleErrors(error);
    });
}

function loadList() {
    return $.ajax({
        url: ULR + "qrs/list",
        method: "GET",
        dataType: "json"
    });
}



$('body').on('click', '[data-click="checkQr"]', function(e) {
    Swal.fire({
        title: 'Ingrese el código del QR',
        input: 'text',
        inputAttributes: {
          autocapitalize: 'off'
        },
        showCancelButton: true,
        confirmButtonText: 'Comprobar',
        cancelButtonText: 'Cancelar',
        showLoaderOnConfirm: true,
       
        allowOutsideClick: () => !Swal.isLoading()
      }).then((result) => {

        if (result.value) {
            var mdlForm = $('#mdlForm');
            
            $.ajax({
                url: ULR + 'qrs/' + result.value + '/check',
                method: 'GET',
                dataType: "json"
            }).done((response)=>{
                if(response.success) {
                    mdlForm.modal('show');
                    $('.modal-content', mdlForm).html(response.view);
                }
              
             
            }).fail(function(error) {
                handleErrors(error);
            });
        }

      });
});

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
        initSelectHeadquarters();
        initSelectPaperSizes();
        $('.skin-square input').iCheck({
            checkboxClass: 'icheckbox_square',
            radioClass: 'iradio_square',
        });
    }).fail(function(error) {
        handleErrors(error);
    });
});

function loadForm(id) {
    return $.ajax({
        url: ULR + "qrs/form",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

function initSelectHeadquarters() {

    $('#headquarter_id').select2({
        width: '100%',
        ajax: {
            url: ULR + "qrs/headquarters",
            dataType: 'json',
            method: "GET",
            error: function(error) {
                handleErrors(error);
            }
        }
    });
}

function initSelectPaperSizes() {

    $('#paper_size_id').select2({
        width: '100%',
        ajax: {
            url: ULR + "qrs/papersizes",
            dataType: 'json',
            method: "GET",
            error: function(error) {
                handleErrors(error);
            }
        }
    });
}

$('body').on('click', '[data-click="save"]', function(e) {

    var mdlForm = $('#mdlForm');
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'true');
    saveForm().done(function(data) {
        if (data.success) {
            mdlForm.modal('hide');
            getList();
        }

        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }).fail(function(error) {
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'false');
        showErrors(error.responseJSON.errors, '#mdlForm');
        handleErrors(error);
    });
});

function saveForm() {

    var form = $('#qr');
    var data = form.serialize();
    return $.ajax({
        url: 'qrs/save',
        method: $('[name="_method"]').val(),
        data: data,
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
        if (data.success) {
            loading.attr('data-show', 'false');
            mdlForm.modal('hide');
            swal("Correcto", data.message, "success");
            getList();
        } else {
            swal("Error", data.message, "error");
        }


    }).fail(function(error) {
        swal("Error", error.message, "error");
        loading.attr('data-show', 'false');
        handleErrors(error);
    });

});

function loadFormDelete(id) {
    return $.ajax({
        url: ULR + "qrs/delete-form",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

function sendDelete() {

    var form = $('#frmDelete');
    var data = form.serialize();

    return $.ajax({
        url: ULR + "qrs/delete",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });

}