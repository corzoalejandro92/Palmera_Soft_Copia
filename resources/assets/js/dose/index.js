//código para leer tabla

$('body').ready(function() {

    getList();

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
        url: ULR + "doses/lists",
        method: "GET",
        dataType: "json"
    });
}


//código para abrir modal (formulario)
$('body').on('click', '[data-click="openForm"]', function(e) {
    e.preventDefault();
    var mdlForm = $('#mdlDelete');
    var loading = $('.loading', mdlForm).first();
    var area = $(this).data('id');
    loading.attr('data-show', 'true');

    loadForm(area).done(function(data) {
        loading.attr('data-show', 'false');
        $('.modal-content', mdlForm).html(data.view);
        $('.skin-square input').iCheck({ checkboxClass: 'icheckbox_square-red', radioClass: 'iradio_square-red', });

        initSelects();

        mdlForm.modal('show');
    }).fail(function(error) {
        handleErrors(error);
    });
});

function loadForm(id) {
    return $.ajax({
        url: ULR + "doses/form",
        method: "GET",
        data: {
            id: id
        },
        dataType: "json"
    });
}


//enviar formulario para guardar información
$('body').on('click', '[data-click="save"]', function(e) {
    e.preventDefault();
    var mdlForm = $('#mdlDelete');
    save().done(function(data) {
        if (data.success) {
            mdlForm.modal('hide');
            getList();
        }
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }).fail(function(error) {
        handleErrors(error);
    });

});

function save() {

    var form = $('#frm');
    var data = form.serialize();
    return $.ajax({
        url: ULR + 'doses/save',
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });
}

//abrir formulario de eliminacion de registro
$('body').on('click', '[data-click="openFormDelete"]', function(e) {
    e.preventDefault();
    var id = $(this).data('id');
    var mdlForm = $('#mdlDelete');
    loadFormDelete(id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        mdlForm.modal('show');
    }).fail(function(error) {
        handleErrors(error);
    });

});

function loadFormDelete(id) {
    return $.ajax({
        url: ULR + "doses/delete-form",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

$('body').on('click', '[data-click="delete"]', function(e) {
    e.preventDefault();

    var mdlForm = $('#mdlDelete');

    sendDelete().done(function(data) {
        mdlForm.modal('hide');
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
        getList();
    }).fail(function(error) {
        showErrors(error.responseJSON.errors, '#frmDeleteDocument');
        handleErrors(error);
    });

});

function sendDelete() {

    var form = $('#frmDelete');
    var data = form.serialize();

    return $.ajax({
        url: ULR + "doses/delete",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });
}

function initSelects() {

    setTimeout(function() {
        initSelectUnit();
    }, 100);

}

function initSelectUnit() {

    $('#unit').select2({
        width: '100%',
        tags: true,
        createTag: function(params) {
            var term = $.trim(params.term);

            if (term === '') {
                return null;
            }

            return {
                id: term,
                text: term + ' (nuevo)'
            };
        },
        ajax: {
            url: ULR + "doses/unit",
            dataType: 'json',
            method: "GET",
        },

    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        UNITS = select_val;
    });
}