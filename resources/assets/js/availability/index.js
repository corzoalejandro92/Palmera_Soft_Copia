SET_DAFAULT_TIME = true;
$(document).ready(function() {
    getList();

});

//-- EVENTOS PARA FORMULARIO DE USUARIOS ----- 

$('body').on('click', '[data-click="openForm"]', function(e) {

    //abrir el modal y mostrar loading
    e.preventDefault();
    SET_DAFAULT_TIME = true;
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'true');
    var mdlForm = $('#mdlForm');
    mdlForm.modal('show');


    var id = $(this).data('id');

    //leer el formulario de usuarios
    loadForm(id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);

        loading.attr('data-show', 'false');
        //inicializar funciones de plugins
        initSelectUsers();
        if (data.edit) {
            SET_DAFAULT_TIME = false;
            initTimePickers();
        } else {
            SET_DAFAULT_TIME = true;
            initTimePickers(true);
        }

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
        handleErrors(error);
        showErrors(error.responseJSON.errors, '#availability');
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
        url: "availability/list",
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
        url: ULR + "availability/form",
        method: "GET",
        data: { id: id },
        dataType: "json",
    });
}

function saveForm() {

    var form = $('#availability');
    var data = form.serialize();
    return $.ajax({
        url: 'availability/save',
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });
}

function loadFormDelete(id) {
    return $.ajax({
        url: "availability/delete-form",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

function sendDelete() {

    var form = $('#frmDelete');
    var data = form.serialize();

    return $.ajax({
        url: "availability/delete",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });

}

function initTimePickers(newrow = false) {


    if (!SET_DAFAULT_TIME && !newrow) {
        $('.timepicker-start').each(function(index, element) {
            var value = $(element).val();
            $(element).wickedpicker({
                now: value,
                twentyFour: false,
                title: 'Selector de hora'
            });
        });

        $('.timepicker-end').each(function(index, element) {
            var value = $(element).val();
            $(element).wickedpicker({
                now: value,
                twentyFour: false,
                title: 'Selector de hora'
            });
        });

    } else {
        var startconf = {
            now: '8:00',
            twentyFour: false,
            title: 'Selector de hora'
        };

        var endconf = {
            now: '18:00',
            twentyFour: false,
            title: 'Selector de hora'
        };

        $('.timepicker-start').wickedpicker(startconf);
        $('.timepicker-end').wickedpicker(endconf);
    }

}

function initSelectUsers() {

    $.fn.select2.amd.require([
        'select2/utils',
        'select2/dropdown',
        'select2/dropdown/attachBody'
    ], function(Utils, Dropdown, AttachBody) {
        function SelectAll() {}

        SelectAll.prototype.render = function(decorated) {
            var $rendered = decorated.call(this);
            var self = this;

            var $selectAll = $(
                '<button id="check_action" class="btn btn-teal" style="width:100%"> <i class="la la-check" style="color: #fff;"></i> Seleccionar todos</button>'
            );

            $rendered.find('.select2-dropdown').prepend($selectAll);
            $selectAll.on('click', function(e) {
                setTimeout(function() {
                    var $results = $rendered.find('.select2-results__option[aria-selected=false]');
                    // Get all results that aren't selected

                    $results.each(function() {
                        var $result = $(this);

                        // Get the data object for it
                        var data = $result.data('data');

                        // Trigger the select event
                        console.log($result);
                        self.trigger('select', { data: { "text": $result[0].outerText, "id": $result.data('select2Id') } });
                    });
                    self.trigger('close');
                }, 1);

            });
            return $rendered;
        };

        $('#users').select2({
            width: '100%',
            dropdownParent: $('.modal-content', $('#mdlForm')),
            ajax: {
                url: "availability/users",
                dataType: 'json',
                method: "GET",

            },
            "language": {
                "noResults": () => "No se han registrado técnicos en el sistema.",
                "searching": () => "Consultando..."
            },
            //dropdownAdapter: Utils.Decorate( Utils.Decorate(Dropdown,AttachBody),SelectAll)
        });
    });

}


//-- EVENTOS PARA FORMULARIO DE BLOQUES DE JORNADAS -----

$('body').on('click', '[data-click="addRow"]', function(e) {
    e.preventDefault();
    var loading = $('.loading-min').first();
    loading.attr('data-show', 'true');
    addWorkingHourRow().done(function(data) {
        $('.tbl-documents tbody').append(data.row);
        initTimePickers(true);
        loading.attr('data-show', 'false');
    }).fail(function(error) {
        handleErrors(error);
        loading.attr('data-show', 'false');
    });
});

$('body').on('click', '[data-click="removeRow"]', function(e) {
    e.preventDefault();

    if ($(this + ' input[name*="id"]').length > 0) {
        let key = $(this).data('id');
        $('tr[data-id="' + key + '"]').remove();
    }
});

//--- FUNCIONES PARA FORMULARIO DE BLOQUES DE JORNADAS

function addWorkingHourRow() {
    return $.ajax({
        url: ULR + "availability/add-working-hour-row",
        method: "GET",
        data: {},
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