var table = null;
TR_ELEMENT = null;
CRM_SCHEDULE_ID = null;

$("body").ready(function() {
    initInputDate();
    initSelectServices();
    initSelectStatuses();
    initSelectUsers();
    if(AUTOFILTER){
        $("#sendScheduleFilter").trigger('submit');
    }
});

function initInputDateSimple(locknow = false) {
    $('.pickadate-translations').pickadate({
        formatSubmit: 'dd/mm/yyyy',
        container: '.app-content',
        selectYears: true,
        selectMonths: true,
        min: locknow ? new Date() : false,
        firstDay: 1,
        monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
        today: 'Hoy',
        clear: 'Limpiar',
        close: 'Cerrar'
    });
}

function initInputDate() {
    var from_$input = $('#picker_from').pickadate({
        formatSubmit: 'yyyy-mm-dd',
        container: '.app-content',
        firstDay: 1,
        selectYears: true,
        selectMonths: true,
        monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
        today: 'Hoy',
        clear: 'Limpiar',
        close: 'Cerrar'
    });

    FROM_PICKER = from_$input.pickadate('picker');

    var to_$input = $('#picker_to').pickadate({
        formatSubmit: 'yyyy-mm-dd',
        container: '.app-content',
        firstDay: 1,
        selectYears: true,
        selectMonths: true,
        monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
        today: 'Hoy',
        clear: 'Limpiar',
        close: 'Cerrar'
    });

    TO_PICKER = to_$input.pickadate('picker');
}

function checkRangeSelected() {
    // Check if there’s a “from” or “to” date to start with.
    if (FROM_PICKER.get('value')) {
        TO_PICKER.set('min', FROM_PICKER.get('select'));
    }
    if (TO_PICKER.get('value')) {
        FROM_PICKER.set('max', TO_PICKER.get('select'));
    }
}

function initDataTable() {
    table = $('#tblList').DataTable({
        language: dtEs,
        responsive: true,
        "iDisplayLength": 25,
        "aaSorting": [
            [1, "asc"]
        ],
        "columnDefs": [
            { "orderable": false, className: 'select-checkbox', targets: 0 },
            { responsivePriority: 1, targets: 1, "orderable": false },
            { responsivePriority: 2, targets: -1 }
        ],
        select: {
            style: 'multi', //'os' para seleccion unica 
            selector: 'td:first-child'
        },
        "initComplete": function() {
            var api = this.api();
            //filterSelect([2], api);
            //filterInput([0], api);
        }
    });

    table.on("click", "th.select-checkbox", function() {
        if ($("th.select-checkbox").hasClass("selected")) {
            table.rows().deselect();
            $("th.select-checkbox").removeClass("selected");
        } else {
            table.rows().select();
            $("th.select-checkbox").addClass("selected");
        }
    }).on("select deselect", function() {
        ("Some selection or deselection going on")
        if (table.rows({
                selected: true
            }).count() !== table.rows().count()) {
            $("th.select-checkbox").removeClass("selected");
        } else {
            $("th.select-checkbox").addClass("selected");
        }
    });



}

$('body').on('click', '.select-checkbox', function(e) {

    if ($('.select-checkbox').parent('.selected').length > 0) {
        if ($('.masive_status').length == 0) {

            //botón para cambiar fecha y estado masivamente
            $('<button/>', {
                class: 'masive_status btn btn-primary ml-1',
                text: 'Cambiar fecha y estado'
            }).appendTo('#tblList_length label');

            //botón para enviar email de confirmación de servicio
            $('<button/>', {
                class: 'masive_confirmation btn btn-info ml-1',
                text: 'Enviar solicitud de confirmación'
            }).appendTo('#tblList_length label');

        }
    } else {
        $('#tblList_length label .masive_status').remove();
        $('#tblList_length label .masive_confirmation').remove();
    }
});

$('body').on('click', '.masive_status', function(e) {
    e.preventDefault();
    var ids = [];
    if (table) {
        table.rows({ selected: true }).every(function(index, row) {
            ids.push(this.node().dataset.click);
        });
    }

    var mdlForm = $('#mdlDelete');
    var id = ids;

    loadStatusForm(id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        $('.skin-square input').iCheck({ checkboxClass: 'icheckbox_square-red', radioClass: 'iradio_square-red', });
        initSelectTechnicals();
        mdlForm.modal('show');
        
    }).fail(function(error) {
        handleErrors(error);
    });

});

$('body').on('click', '.masive_confirmation', function(e) {
    e.preventDefault();
    var ids = [];
    if (table) {
        table.rows({ selected: true }).every(function(index, row) {
            ids.push(this.node().dataset.click);
        });
    }

    var mdlForm = $('#mdlDelete');
    var id = ids;

    PromptServiceConfirmation(id);

});

function getList(start, end, services, statuses, users) {
    var list = $('#list');
    $('.loading', list).attr('data-show', 'true');
    loadList(start, end, services, statuses, users).done(function(data) {
        list.html(data.view);
        $('.loading', list).attr('data-show', 'false');
        initDataTable();
        $('[data-toggle="tooltip"]').tooltip();
    }).fail(function(error) {
        handleErrors(error);
    });
}

function getTrFrame(){

    var start = $("[name='start_date_submit']").val();
    var end = $("[name='end_date_submit']").val();
    var services = $("[name='services[]'").val();
    var users = $("[name='users[]'").val();
    var statuses = $("[name='statuses[]'").val();


    $.ajax({
            url: ULR + "scheduling/sheet/list",
            method: "GET",
            dataType: "json",
            data: { id: CRM_SCHEDULE_ID, start: start, end: end, services: services, statuses: statuses, users: users }
        }).done(function(data) {
            var $dt = $('#tblList').DataTable();
            console.log($(data.view)[0].outerHTML);
            var newRowDataArray = TrToData($(data.view)[0].outerHTML); 
            $dt.row(TR_ELEMENT).data(newRowDataArray).draw('page');
            //TR_ELEMENT.html(data.view);
        });
}

function TrToData(row) {
    return $(row).find('td').map(function(i,el) {
        return el.innerHTML;
    }).get();
}

function loadList(start, end, services, statuses, users) {
    return $.ajax({
        url: ULR + "scheduling/sheet/list",
        method: "GET",
        dataType: "json",
        data: { start: start, end: end, services: services, statuses: statuses, users: users }
    });
}


//Funciones para el cambio de estado del evento control

$('body').on('click', '[data-click="openStatusForm"]', function(e) {
    e.preventDefault();
    TR_ELEMENT = $(this).parents('tr');
    CRM_SCHEDULE_ID = $(this).data('id');
    var mdlForm = $('#mdlDelete');
    var id = $(this).data('id');

    loadStatusForm(id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        $('.skin-square input').iCheck({ checkboxClass: 'icheckbox_square-red', radioClass: 'iradio_square-red', });
        mdlForm.modal('show');
        initSelectTechnicals();
    }).fail(function(error) {
        handleErrors(error);
    });
});

function loadStatusForm(id) {
    return $.ajax({
        url: ULR + "scheduling/callsheet/" + (typeof id == 'object' ? "status-multiple-form" : "status-form"),
        method: "GET",
        data: {
            id: id
        },
        dataType: "json"
    });
}

function PromptServiceConfirmation(id) {

    Swal.fire({
        title: '¿Enviar '+id.length+' correo'+(id.length != 1 ? 's':'')+' de confirmación?',
        text: '¿Estas seguro que deseas enviar '+id.length+' correo'+(id.length != 1 ? 's':'')+' requieriendo confirmación para programar los servicios seleccionados?.',
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#009688',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Si, Enviar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {

        if (result.value) {
             $.ajax({
                url: ULR + "scheduling/callsheet/confirm-request",
                method: "GET",
                data: {
                    id: id
                },
                dataType: "json"
            }).done(function(data) {

                swal.fire({
                    'title': data.success ? "Correcto" : "Error",
                    'text': data.message,
                    'showConfirmButton': true,
                    'timer': 1500,
                    'type': data.success ? "success" : "error",
                }).then(function() {
                    if(data.success == true) {
                        var start = $("[name='start_date_submit']").val();
                        var end = $("[name='end_date_submit']").val();
                        var services = $("[name='services[]'").val();
                        var users = $("[name='users[]'").val();
                        var statuses = $("[name='statuses[]'").val();

                        getList(start, end, services, statuses, users);
                    }
                });

            }).fail(function(error) {
                handleErrors(error);
            });
        }

    });

}



// $('body').on('click', '[data-click="updateStatus"]', function(e) {

//     var mdlForm = $('#mdlDelete');
//     updateStatus().done(function(data) {
//         mdlForm.modal('hide');
//         $("#sendScheduleFilter").trigger('submit');
//         swal("Correcto", data.message, "success");
//     }).fail(function(error) {
//         showErrors(error.responseJSON.errors, '#status');
//         handleErrors(error);
//     })
// });

function updateStatus() {

    var form = $('#status');
    var data = form.serialize();
    return $.ajax({
        url: ULR + 'scheduling/callsheet/status',
        method: 'PUT',
        data: data,
        dataType: "json"
    });
}

$('body').on('submit', '#sendScheduleFilter', function(e) {
    e.preventDefault();
    TAB = '#tabSchedule';
    var start = $("[name='start_date_submit']").val();
    var end = $("[name='end_date_submit']").val();
    var services = $("[name='services[]'").val();
    var users = $("[name='users[]'").val();
    var statuses = $("[name='statuses[]'").val();

    getList(start, end, services, statuses, users);

});


//Funciones para el cambio de fecha del control

$('body').on('click', '[data-click="openDateForm"]', function(e) {
    e.preventDefault();
    var mdlForm = $('#mdlDelete');
    var id = $(this).data('id');

    loadDateForm(id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        $('.skin-square input').iCheck({ checkboxClass: 'icheckbox_square-red', radioClass: 'iradio_square-red', });
        mdlForm.modal('show');
    }).fail(function(error) {
        handleErrors(error);
    });
});

function loadDateForm(id) {
    return $.ajax({
        url: ULR + "scheduling/callsheet/status-form",
        method: "GET",
        data: {
            id: id
        },
        dataType: "json"
    });
}

$('body').on('click', '[data-click="updateStatus"]', function(e) {

    var mdlForm = $('#mdlDelete');
    updateStatus().done(function(data) {
        if (data.success) {
            mdlForm.modal('hide');
            getTrFrame();
            //$("#sendScheduleFilter").trigger('submit');
            swal("Correcto", data.message, "success");
        } else {
            swal("Error", data.message, "error");
        }

    }).fail(function(error) {
        showErrors(error.responseJSON.errors, '#status');
        handleErrors(error);
    })
});

function updateStatus() {

    var form = $('#status');
    var data = form.serialize();
    return $.ajax({
        url: ULR + 'scheduling/callsheet/status',
        method: 'PUT',
        data: data,
        dataType: "json"
    });
}



//funciones para añadir un evento de seguimiento en la hoja de llamadas
$('body').on('click', '[data-click="openFormEvent"]', function(e) {

    TR_ELEMENT = $(this).parents('tr');
    CRM_SCHEDULE_ID = $(this).data('id');
    //definir metodo
    METHOD = 'POST';
    $('[name="_method"]').val(METHOD);

    //abrir el modal y mostrar loading
    e.preventDefault();
    var mdlForm = $('#mdlLargueForm');
    var loading = $('.loading', mdlForm).first();
    var id = $(this).data('id');
    loading.attr('data-show', 'true');
    mdlForm.modal('show');

    //leer el formulario de usuarios
    loadFormEvent(id).done(function(data) {

        $('.modal-content', mdlForm).html(data.view);
        initSelectCrmtrackingType();
        mdlForm.modal('show');
        loading.attr('data-show', 'false');

    });
});

function loadFormEvent(id) {
    return $.ajax({
        url: ULR + "scheduling/callsheet/event-form",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

function initSelectCrmtrackingType() {

    $('.crmTrackingType').select2({
        width: '100%',
        dropdownParent: $('.modal-content', $('#mdlLargueForm')),
        ajax: {
            url: ULR + "crm/crm-tracking-type",
            dataType: 'json',
            method: "GET",
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        TYPECRM = select_val;
    });
}


$('body').on('click', '[data-click="saveEvent"]', function(e) {

    var mdlForm = $('#mdlLargueForm');
    saveEvent().done(function(data) {
        if (data.success) {
            mdlForm.modal('hide');
            getTrFrame();
            //$("form#sendScheduleFilter").trigger('submit');
            swal("Correcto", data.message, "success");
        } else {
            swal("Error", data.message, "error");
        }


    }).fail(function(error) {
        showErrors(error.responseJSON.errors, '#eventForm');
        handleErrors(error);
    });
});

function saveEvent() {

    var form = $('#eventForm');
    var data = form.serialize();
    return $.ajax({
        url: ULR + 'scheduling/callsheet/save-event',
        method: 'POST',
        data: data,
        dataType: "json"
    });
}


//funciones para añadir una nota externa en el evento de cronograma
$('body').on('click', '[data-click="openObservationForm"]', function(e) {
    TR_ELEMENT = $(this).parents('tr');
    CRM_SCHEDULE_ID = $(this).data('id');
    e.preventDefault();
    var mdlForm = $('#mdlLargueForm');
    var id = $(this).data('id');

    loadObservationForm(id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        mdlForm.modal('show');
        initTextareaResize();
    }).fail(function(error) {
        handleErrors(error);
    });
});

function loadObservationForm(id) {
    return $.ajax({
        url: ULR + "crm/" + $("[name='crm_id']").val() + "/scheduling/observations-form",
        method: "GET",
        data: {
            id: id
        },
        dataType: "json"
    });
}

$('body').on('click', '[data-click="addRowObservation"]', function(e) {
    e.preventDefault();
    var loading = $('.loading-min').first();
    loading.attr('data-show', 'true');
    addObservationRow().done(function(data) {
        $('.tbl-technical tbody').append(data.row);
        addObservationRow(data.key);
        loading.attr('data-show', 'false');
        initTextareaResize();
    }).fail(function(error) {
        handleErrors(error);
        loading.attr('data-show', 'false');
    });

});

function addObservationRow() {
    return $.ajax({
        url: ULR + "crm/" + $("[name='crm_id']").val() + "/schedule/add-observation-row",
        method: "GET",
        data: {},
        dataType: "json"
    });
}

$('body').on('click', '[data-click="saveObservations"]', function(e) {
    e.preventDefault();
    saveObservations().done(function(data) {
        if (data.success) {
            getTrFrame();
            //$("form#sendScheduleFilter").trigger('submit');
            var mdlForm = $('#mdlLargueForm');
            mdlForm.modal('hide');
        }

        swal(data.success ? "Correcto" : 'Error', data.message, data.success ? "success" : "error");
    }).fail(function(error) {
        showErrors(error.responseJSON.errors, '#status');
        handleErrors(error);
    })

});

function saveObservations() {
    var form = $('#observations');
    var data = form.serialize();
    return $.ajax({
        url: ULR + 'crm/' + $("[name='crm_id']").val() + '/schedule/save/observations',
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });
}



//seleccionar todos los servicios desde un checbox
$('body').on('click', '[data-click="checkAllServices"]', function(e) {

    if ($(this).is(':checked')) {
        $('[name="services[]"]').val(null).trigger("change");
        $('[name="services[]"]').html('');
        var periodicity = $("[name*='services[]']").val();
        loadServices().done(function(data) {
            showLoading();
            data.results.forEach(function(service, indx) {
                var $newOption = $("<option selected></option>").val(service.id).text(service.name);
                $('[name="services[]"]').append($newOption);
                if ((indx + 1) == data.results.length) {
                    setTimeout(() => {
                        hideLoadingOnly();
                    }, 600);
                }
            });
        });

    } else {
        $('[name="services[]"]').val(null).trigger("change");
        $('[name="services[]"]').html('');
        $('[name="services[]"]').html('');
    }
});

function loadServices() {
    return $.ajax({
        url: ULR + "scheduling/services",
        method: "GET",
        dataType: "json"
    });
}

function initSelectServices(restart = false) {
    if (restart) $('[name="services[]"]').val('').trigger('change');
    $('[name="services[]"]').select2({
        width: '100%',
        allowClear: true,
        closeOnSelect: false,
        placeholder: 'Selecciona uno o mas servicios, dejar vacío para aplicar todos',
        ajax: {
            url: ULR + "scheduling/services",
            dataType: 'json',
            method: "GET",
            success: selectScroll,
            error: function(error) {
                handleErrors(error);
            }
        },
        cache: true,
        templateResult: dataFormatResultService,
        templateSelection: dataFormatSelection,
        dropdownCssClass: "bigdrop",
        escapeMarkup: function(m) { return m; },
        "language": {
            "noResults": () => "No se encontraron servicios registrados en el sistema.",
            "searching": () => "Consultando...",
            "errorLoading": () => "Error del servidor. Contacta a soporte técnico."
        }
    }).on("select2:open", e => selectScroll());
}

function initSelectStatuses(restart = false) {
    if (restart) $('[name="statuses[]"]').val('').trigger('change');
    $('[name="statuses[]"]').select2({
        width: '100%',
        allowClear: true,
        closeOnSelect: false,
        placeholder: 'Selecciona uno o mas estados',
        ajax: {
            url: ULR + "scheduling/statuses",
            dataType: 'json',
            method: "GET",
            success: selectScroll,
            error: function(error) {
                handleErrors(error);
            }
        },
        cache: true,
        "language": {
            "noResults": () => "No se encontraron servicios registrados en el sistema.",
            "searching": () => "Consultando...",
            "errorLoading": () => "Error del servidor. Contacta a soporte técnico."
        }
    }).on("select2:open", e => selectScroll());
}


function initSelectTechnicals(key = false) {


    var selector = '[name*="[user][]"], [name*="user[]"]';
    if (key) {
        selector = '[name*="[' + key + '][user][]"]';
    }
    $(selector).select2({
        placeholder: "Dejar vacío para cualquiera",
        allowClear: true,
        multiple: true,
        width: "100%",
        ajax: {
            url: ULR + "crm/technicals",
            dataType: 'json',
            method: "GET",
            success: selectScroll
        },
        cache: true,
        templateResult: dataFormatResultUser,
        templateSelection: dataFormatSelection,
        dropdownCssClass: "bigdrop",
        escapeMarkup: function(m) { return m; },
    }).on("select2:open", e => selectScroll());

}

function initSelectUsers() {

    $('[name="users[]"]').select2({
        width: '100%',
        placeholder: "Al menos un técnico/usuario",
        ajax: {
            url: ULR + "scheduling/users",
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

$('body').on('click','.delete-tracking-button', function(e) {
    var id = $(this).data('id');
    Swal.fire({
        title: 'Eliminar nota de seguimiento',
        html: "Ten presente que esta nota no volverá a estar disponible, en caso de posponerlo, recuerda que solo tu puedes eliminar tus notas y que una nota de seguimiento solo se puede eliminar dentro de los primeros 30  minutos de creación, luego de transcurrido dicho tiempo solo un super administrador podrá borrarla",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#fda401',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Si, Eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        console.log(result);
        if (result.value) {
            deleteTracking(id).done(function(data) {
                $("#sendScheduleFilter").trigger('submit');
                swal.close();
                var mdlForm = $('#mdlFormShow');
                mdlForm.modal('hide');
                swal(data.success ? 'Correcto' : "Error", data.message, data.success ? "success" : "error");
            }).fail(function(error) {
                handleErrors(error);
            });
        }
    });
});

function deleteTracking(id) {
    return $.ajax({
        method: 'get',
        url: ULR + 'scheduling/callsheet/tracking/' + id + '/delete',
        data: { id: id }
    });
}