//Funciones para el cambio de fecha del control
$('body').on('click', '[data-click="openStatusForm"]', function(e) {
    e.preventDefault();
    $('.popover').remove();
    var mdlForm = $('#mdlDelete');
    var id = $(this).attr('href');

    loadDateForm(id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        $('.skin-square input').iCheck({ checkboxClass: 'icheckbox_square-red', radioClass: 'iradio_square-red', });
        mdlForm.modal('show');
        initSelectTechnicals();
    }).fail(function(error) {
        handleErrors(error);
    });
});

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
    }).on("select2:open", e => selectScroll())
    .on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var service = selected_element.data('service');
        var user = selected_element.val();
        var date = $("[name='services["+service+"][start_week]_submit']").val();
        findARoute(user, service, date);
    }).on("select2:unselect", (e) => {

        var selected_element = $(e.currentTarget);
        var service = selected_element.data('service');
        var user = selected_element.val();
        var date = $("[name='services["+service+"][start_week]_submit']").val();
        findARoute(user, service, date);
    });

}

function findARoute(user,service,date){
    //consultar via ajax si el técnico asignado tiene rutas existentes para el dia en cuestion
    findARouteAjax(user,service,date).done(function(data){
        $(".addToRouteDiv").html(data.view);
    })

}

function findARouteAjax(user,service,date){
    return $.ajax({
        url: ULR + 'crm/find-a-route',
        method: 'GET',
        data: {
            user: user,
            service: service,
            date: date
        },
        dataType: 'json'
    })
}

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


$('body').on('click', '[data-click="openObservationForm"]', function(e) {
    e.preventDefault();
    var mdlForm = $('#mdlFormShowFullScreen2');
    var id = $(this).attr('href');

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
            $('.popover').remove();
            var mdlForm = $('#mdlFormShowFullScreen2');
            mdlForm.modal('hide');
            $("#agenda").fullCalendar('refetchEvents');

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



$('body').on('click', '[data-click="updateStatus"]', function(e) {
    e.preventDefault();
    var mdlForm = $('#mdlDelete');
    updateStatus().done(function(data) {
        if (data.success) {
            mdlForm.modal('hide');
            $('.close').trigger('click');
            $("#sendScheduleFilter").trigger('submit');
            //swal("Correcto", data.message, "success");
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

function moveEventRN(id, name, start) {
    updateEventDateRN(id, start).done(function(response) {
        //$("#agenda").fullCalendar('refetchEvents');
        //swal(response.success ? 'Cambios Guardados' : 'Hubo un problema', response.message, response.success ? 'success' : 'warning');
    }).fail(function(error) {
        handleErrors(error);
        $("#agenda").fullCalendar('refetchEvents');
    });
}

function moveNote(id, name, start) {
    updateNoteDate(id, start).done(function(response) {
        //$("#agenda").fullCalendar('refetchEvents');
        //swal(response.success ? 'Cambios Guardados' : 'Hubo un problema', response.message, response.success ? 'success' : 'warning');
    }).fail(function(error) {
        handleErrors(error);
        $("#agenda").fullCalendar('refetchEvents');
    });
}

function updateEventDateRN(id, start) {
    console.log('Aquii 1');
    return $.ajax({
        url: ULR + "crm/scheduling/update-date",
        method: "GET",
        data: { id: id, start: start },
        dataType: "json"
    });
}

function updateNoteDate(id, start) {
    return $.ajax({
        url: ULR + "crm/note/update-date",
        method: "GET",
        data: { id: id, start: start },
        dataType: "json"
    });
}


$('body').on('click', '.popover-header .close', function() {
    $('.popover').remove();
});


$('body').on('click', '.scheduleDelete', function(e) {
    var id = $(this).attr('href');
    e.preventDefault();
    Swal.fire({
        title: '¿Desea eliminar este control del cronograma?',
        text: 'Ten presente que esta es una acción irreversible',
        input: 'radio',
        inputOptions: {
            'one': 'Solamente este control',
            'all': 'Todos los controles de este tipo de aqui en adelante'
        },
        showCancelButton: true,
        cancelButtonText: 'No, Canelar',
        confirmButtonText: 'Si, Eliminar',
        confirmButtonColor: '#d44035',

        // validator is optional
        inputValidator: function(result) {
            if (!result) {
                return 'Es obligatorio seleccionar una opción de borrado';
            }
        }
    }).then(function(result) {
        if (result.value !== undefined) {
            $.ajax({
                url: ULR + 'crm/schedule/delete/' + id,
                method: 'GET',
                data: { type: result.value }
            }).done((data) => {
                swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
                $("#agenda").fullCalendar('refetchEvents');
                $('.popover').remove();
            }).fail(function(error) {
                handleErrors(error);
            });
        }
    });
});



$('body').on('change', '.pickadate-range', function(e) {
    checkRangeSelected();
});



$('body').on('submit', '#sendScheduleFilter', function(e) {
    e.preventDefault();
    TAB = '#tabSchedule';
    if ($("[name='office_id']").val() == null) {

        $('#buttonsOptionsShedulesDiv').hide();
        $('.filterRangeSchedule').hide();
    } else {
        var pdfHref = $('.buttonPdfSchedule').data('base');
        pdfHref = pdfHref.replace("_crm_", $("[name='crm_id']").val());
        pdfHref = pdfHref.replace("_office_", $("[name='office_id']").val());
        pdfHref = pdfHref.replace("_start_", $("[name='start_date_submit']").val());
        pdfHref = pdfHref.replace("_end_", $("[name='end_date_submit']").val());
        pdfHref += "&visual_type=" + $('[name="visual_type"]:checked').val();
        $('.buttonPdfSchedule').attr('href', pdfHref);

        $('#buttonsOptionsShedulesDiv').show();
        $('.filterRangeSchedule').show();

        $('[data-toggle="tooltip"]').tooltip();
        $("#agenda").fullCalendar('refetchEvents');

        ///añadir propiedad data crm al botón de mail
        var sendMail = $("[data-click='sendSchedule']");
        sendMail.data('crm', $("[name='crm_id']").val());
    }

});


$('body').on('click', '.scheduleFullDelete', function(e) {
    var id = $(this).data('id');
    e.preventDefault();
    Swal.fire({
        title: '¿Está seguro que desea eliminar este cronograma por completo?',
        text: 'Ten presente que esta es una acción irreversible y deberás generar un nuevo cronograma desde cero para programar rutas.',
        showCancelButton: true,
        cancelButtonText: 'No, Canelar',
        confirmButtonText: 'Si, Eliminar',
        confirmButtonColor: '#d44035',


    }).then(function(result) {
        if (result.value !== undefined) {
            $.ajax({
                url: ULR + 'crm/schedule/full-delete/' + id,
                method: 'GET',
            }).done((data) => {
                swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
                $("#agenda").fullCalendar('refetchEvents');
                $('.popover').remove();
            }).fail(function(error) {
                handleErrors(error);
            });
        }
    });
});


$('body').on('click', "[data-click='sendSchedule']", function(e) {

    e.preventDefault();
    var startDate = $("[name='start_date_submit']").val();
    var endDate = $("[name='end_date_submit']").val();
    var officeId = $("[name='office_id']").val();
    var mdlForm = $('#mdlForm');
    var crm_id = $(this).data('crm');
    mdlForm.modal('show');

    getSendScheduleForm(crm_id, startDate, endDate, officeId).done((data) => {
        $('.modal-content', mdlForm).html(data.view);
        $(".tags").tagging({
            "case-sensitive": false,
            "forbidden-chars": [",", "?", ";", '*', '$', '%', '&', '¿', '|', '°', '!', '¡'],
            "tag-char": '@',
        });

        $('[data-tags-input-name="destinatary"]').tagging("add", data.destinatary);


        hideLoading();
        initCKEditor();

    }).fail(function(error) {
        handleErrors(error);
    });


});

function getSendScheduleForm(crm_id, startDate, endDate, officeId) {
    return $.ajax({
        url: ULR + 'crm/' + crm_id + '/schedule/send-form',
        data: {
            start_date_submit: startDate,
            end_date_submit: endDate,
            office_id: officeId
        },

    });
}

$('body').on('click', '[data-click="send-schedule"]', function(e) {
    e.preventDefault();
    var mdlForm = $('#mdlForm');
    sendSchedule().done(function(data) {
        if (data.success) {
            mdlForm.modal('hide');
        }
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }).fail(function(error) {
        handleErrors(error);
    });

});

function sendSchedule() {
    var body = CKEDITOR.instances.ckeditor.getData();
    $("[name='body']").val(body);
    var form = $('#sendSchedule');
    if (form) {
        var data = form.serialize();
        return $.ajax({
            url: ULR + "crm/" + $('[name="crm_id"]').val() + "/schedule-send",
            method: $('[name="_method"]', form).val(),
            data: data,
            dataType: "json"
        });
    }
}

function initCKEditor() {

    EDITOR = CKEDITOR.instances['ckeditor'];
    if (EDITOR) { EDITOR.destroy(true); }
    CKEDITOR.replace('ckeditor', {
        height: '350px',
        extraPlugins: 'forms'
    });
    //https://ckeditor.com/docs/ckeditor4/latest/guide/dev_file_browse_upload.html
}

$('body').on('click', '.close-modal', function(e) {
    e.preventDefault();
    Swal.fire({
        type: 'question',
        title: '¿Estás seguro que deseas salir? ',
        text: 'Si lo haces perderás todo el trabajo que has realizado en este cronograma y deberás comenzar desde cero. Si necesitas ir a otro apartado del sistema es preferible que abras una nueva pestaña del sistema y lo hagas desde allí.',
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: `Si, salir`,
    }).then((result) => {

        /* Read more about isConfirmed, isDenied below */
        if (result.value) {
            var mdlForm = $('#mdlForm');
            mdlForm.modal('hide');
        }
    });
});


$('body').on('click', '[data-click="openFormSchedule"]', function(e) {
    modalForceClose = false;
    e.preventDefault();
    var mdlForm = $('#mdlForm');
    mdlForm.modal('show');

    var crm_id = $("[name='crm_id']").val();
    var schedule_id = $(this).data('schedule');
    var office_id = $("[name='office_id']").val();

    openFormSchedule(crm_id, schedule_id, office_id).done(function(data) {
        if (data.success) {
            $('.modal-content', mdlForm).html(data.view);
            $('.nav-link-schedule.active').trigger('click');
            initSelectProducts();
            initInputDateSimple();
            initSelectPeriodicities();
            initSelectInfestations();
            initSelectApplications();
            initSelectAreaControl();
            initAllRadios();
            initSelectCoverages();
            processAvatars();
            initSelectTechnicals();
            initSelectScheduleStatuses();

            $('.skin-square input').iCheck({ checkboxClass: 'icheckbox_square-red', radioClass: 'iradio_square-red', });
        }
    }).fail(function(error) {
        handleErrors(error);
    });

});



function openFormSchedule(crm_id, schedule_id, office_id) {
    return $.ajax({
        url: ULR + 'crm/' + crm_id + '/schedule-form',
        data: {
            schedule_id: schedule_id,
            office_id: office_id
        }
    });
}

$('body').on('click', '[data-click="addNewService"]', function(e) {
    var id = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
    var tabContainer = $('.nav-tabs-schedule');
    var navlink = $('.nav-link-schedule', tabContainer);
    var tabContent = $('.tab-content-schedule ');
    var tabPane = $('.tab-pane-schedule');
    var addButton = $("[data-click='addNewService']").parent();
    $(addButton).remove();
    navlink.removeClass('active');
    tabPane.removeClass('active');
    tabContainer.append('<li class="nav-item serviceControls-' + id + ' temporal"><a class="nav-link active" id="baseIcon-tab' + id + '" data-toggle="tab" aria-controls="tabIcon1" href="#tab' + id + '" aria-expanded="true" data-id="' + id + '" data-quote_service_id="" data-click="setService">*Nuevo Servicio* <i class="las la-times-circle la-3x float-right" data-click="removeServiceFake" data-id="' + id + '" data-fake="' + id + '"></i></a></li><li class="nav-item temporal"><a data-click="addNewService" class="nav-link"><i class="ft-plus-circle"></i>Agregar Servicio</a></li>');
    tabContent.append('<div role="tabpanel" class="tab-pane active" id="tab' + id + '" aria-expanded="true" aria-labelledby="baseIcon-tab' + id + '"><div class="row"><div class="col-md-4"><select class="form-control select2" name="temporalServiceSelect[' + id + ']"><option>Seleccione un servicio</option></select></div><div class="col-md-4"><button class="btn btn-success" data-click="saveNewService" data-id="' + id + '">Agregar</button></div></div></div>');
    initSelectServices(id);
});

$('body').on('click', '[data-click="addNewServiceFromQuote"]', function(e) {
    var id = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
    var tabContainer = $('.nav-tabs-schedule');
    var navlink = $('.nav-link-schedule', tabContainer);
    var tabContent = $('.tab-content-schedule');
    var tabPane = $('.tab-pane-schedule');
    var addButton = $("[data-click='addNewServiceFromQuote']").parent();
    $(addButton).remove();
    navlink.removeClass('active');
    tabPane.removeClass('active');
    tabContainer.append('<li class="nav-item temporal serviceControls-' + id + ' temporal" ><a data-click="addNewServiceFromQuote" class="nav-link"><i class="ft-plus-circle"></i>importar desde una cotización</a></li><li class="nav-item serviceControls-' + id + ' temporal"><a class="nav-link active" id="baseIcon-tab' + id + '" data-toggle="tab" aria-controls="tabIcon1" href="#tab' + id + '" aria-expanded="true" data-id="' + id + '" data-quote_service_id="" data-click="setService">*Importar desde una cotización* <i class="las la-times-circle la-3x float-right" data-click="removeServiceFake" data-id="' + id + '" data-fake="' + id + '"></i></a></li>');
    tabContent.append('<div role="tabpanel" class="tab-pane active" id="tab' + id + '" aria-expanded="true" aria-labelledby="baseIcon-tab' + id + '"><div class="row"><div class="col-md-4"><select class="form-control select2" name="temporalQuoteSelect[' + id + ']"><option>Seleccione una cotización</option></select></div><div class="col-md-4"><button class="btn btn-success" data-click="saveNewImport" data-id="' + id + '">Comenzar importación</button></div></div></div>');
    initSelectQuotes(id);
});

$('body').on('click', '[data-click="addNewServiceFromOffice"]', function(e) {
    var id = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
    var tabContainer = $('.nav-tabs-schedule');
    var navlink = $('.nav-link-schedule', tabContainer);
    var tabContent = $('.tab-content-schedule');
    var tabPane = $('.tab-pane-schedule');
    var addButton = $("[data-click='addNewServiceFromOffice']").parent();
    $(addButton).remove();
    navlink.removeClass('active');
    tabPane.removeClass('active');
    tabContainer.append('<li class="nav-item temporal serviceControls-' + id + ' temporal" ><a data-click="addNewServiceFromOffice" class="nav-link"><i class="ft-plus-circle"></i>copiar de una sucursal</a></li><li class="nav-item serviceControls-' + id + ' temporal"><a class="nav-link active" id="baseIcon-tab' + id + '" data-toggle="tab" aria-controls="tabIcon1" href="#tab' + id + '" aria-expanded="true" data-id="' + id + '" data-quote_service_id="" data-click="setService">*Copiar de una sucursal* <i class="las la-times-circle la-3x float-right" data-click="removeServiceFake" data-id="' + id + '" data-fake="' + id + '"></i></a></li>');
    tabContent.append('<div role="tabpanel" class="tab-pane active" id="tab' + id + '" aria-expanded="true" aria-labelledby="baseIcon-tab' + id + '"><div class="row"><div class="col-md-4"><select class="form-control select2" name="temporalOfficeSelect[' + id + ']"><option>Seleccione una sucursal</option></select></div><div class="col-md-4"><button class="btn btn-success" data-click="saveNewCopy" data-id="' + id + '">Comenzar importación</button></div></div></div>');
    initSelectTempOffices(id);
});

function initSelectServices(key = false) {

    selector = '[name*="temporalServiceSelect[' + key + ']"]';
    $(selector).select2({
        placeholder: "Seleccione un servicio",
        ajax: {
            url: ULR + "quotes/services",
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
            "noResults": () => "No se encontraron servicios registrados en el sistema",
            "searching": () => "Consultando..."
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        key = $(e.currentTarget).data('key');

        initSelectCoverages(key);
        initSelectPeriodicities(key);
        initInputDateSimple(key);
        initSelectWeeks(key);
        initSelectAreaControl(key);
        //initSelectInfestation(select_val,key);
    }).on("select2:open", e => selectScroll());
}

function initSelectQuotes(key = false) {

    selector = '[name*="temporalQuoteSelect[' + key + ']"]';
    $(selector).select2({
        placeholder: "Seleccione una cotización",
        ajax: {
            url: ULR + "crm/" + $("[name='crm_id']").val() + "/office/" + $("[name='office_id']").val() + "/schedule/quotes",
            dataType: 'json',
            method: "GET",
            success: selectScroll,
            error: function(error) {
                handleErrors(error);
            }
        },
        templateResult: dataFormatResultQuote,
        templateSelection: dataFormatSelection,
        dropdownCssClass: "bigdrop",
        escapeMarkup: function(m) { return m; },
        "language": {
            "noResults": () => "No se encontraron otras sucursales para este cliente",
            "searching": () => "Consultando..."
        }
    });
}

function initSelectTempOffices(key = false) {

    selector = '[name*="temporalOfficeSelect[' + key + ']"]';
    $(selector).select2({
        placeholder: "Seleccione otra sucursal de este cliente",
        ajax: {
            url: ULR + "crm/" + $("[name='crm_id']").val() + "/office/" + $('#OfficesCustomer').val() + "/schedule/other-offices",
            dataType: 'json',
            method: "GET",
            success: selectScroll,
            error: function(error) {
                handleErrors(error);
            }
        },
        templateResult: dataFormatResultOffice,
        templateSelection: dataFormatSelection,
        dropdownCssClass: "bigdrop",
        escapeMarkup: function(m) { return m; },
        "language": {
            "noResults": () => "No se encontraron otras sucursales para este cliente",
            "searching": () => "Consultando..."
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        key = $(e.currentTarget).data('key');
        //initSelectArea(select_val, key);
        //initSelectInfestation(select_val,key);
    });
}

$('body').on('click', '[data-click="saveNewImport"]', function(e) {
    e.preventDefault();
    var id = $(this).data('id');
    var quote = $("[name='temporalQuoteSelect[" + id + "]']").val();
    window.location.href = '/crm/' + $("[name='crm_id']").val() + '/add-schedule-quote/office/' + $('#OfficesCustomer').val() + '/quote/' + quote;
});

$('body').on('click', '[data-click="saveNewCopy"]', function(e) {
    e.preventDefault();
    var id = $(this).data('id');
    var office = $("[name='temporalOfficeSelect[" + id + "]']").val();
    window.location.href = '/crm/' + $("[name='crm_id']").val() + '/add-schedule-office/' + office + '/keep-office/' + $('#OfficesCustomer').val();
});


$('body').on('click', '[data-click="saveNewService"]', function(e) {
    e.preventDefault();
    var key = $(this).data('id');
    var service = $('[name*="temporalServiceSelect[' + key + ']"]').val();
    saveNewService(service, key).done(function(data) {

        if (data.success) {
            showToast(data.message);
        } else {
            showToastError(data.message);
        }

        if (data.success) {
            //volver a cargar la vista
            if ((typeof(callOpenForm) !== "undefined") && callOpenForm) {
                window.location.reload();
            } else {
                openFormSchedule($("[name='crm_id']").val(), null, $("[name='office_id']").val()).done(function(data) {
                    if (data.success) {
                        $('.modal-content', mdlForm).html(data.view)
                        $('.nav-link-schedule.active').trigger('click');
                        initSelectCoverages();
                        initSelectProducts();
                        initInputDateSimple();
                        initSelectPeriodicities();
                        initSelectInfestations();
                        initSelectApplications();
                        initTextareaResize();
                        initSelectTechnicals();
                        initSelectScheduleStatuses();
                    }
                }).fail(function(error) {
                    handleErrors(error);
                });
            }
        }


    }).fail(function(error) {
        handleErrors(error);
    });
});

function countServicesInto(service) {

    var total = 0;

    //recorrer todos los elementos de la vista
    $("[data-schedule_service_id='" + service + "']").each(function(index, element) {
        total++;
    });
    return total;

}

function saveNewService(service, key) {

    var form = $('#supplies');
    var data = form.serialize();
    return $.ajax({
        url: ULR + "crm/" + $('[name="crm_id"]').val() + "/schedule/service-save",
        method: 'GET',
        data: {
            service_id: service,
            office_id: $('[name="OfficesCustomer"]').val(),
            key: key,
            num: (countServicesInto(service) + 1)
        },
        dataType: "json"
    });
}

$('body').on('click', '[data-click="removeService"]', function(e) {
    e.preventDefault();
    var id = $(this).data('id');

    Swal.fire({
        title: 'Quitar este servicio',
        text: "¿Estas seguro que deseas eliminar este servicio?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e91e4d',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Si, Quitar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.value) {
            removeService(id).done(function(data) {

                if (data.success) {
                    showToast(data.message);
                    //volver a cargar la vista
                    $('.serviceControls-' + id).remove();
                    $(DIV_TAB).remove();
                } else {
                    showToastError(data.message);
                }


            }).fail(function(error) {
                handleErrors(error);
            });
        }
    });
});

function removeService(id) {
    return $.ajax({
        url: ULR + 'crm/' + $("[name='crm_id']").val() + '/schedule/service-remove',
        method: 'GET',
        data: {
            service_id: id,
            office_id: $('[name="office_id"]').val(),
        },
        dataType: "json"
    });
}



function initInputDate() {
    var from_$input = $('#picker_from').pickadate({
        formatSubmit: 'yyyy-mm-dd',
        container: '.app-content',
        firstDay: 1,
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
        monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
        today: 'Hoy',
        clear: 'Limpiar',
        close: 'Cerrar'
    });

    TO_PICKER = to_$input.pickadate('picker');
    checkRangeSelected();
}

function initSelectCoverages(key = false) {

    var selector = '[name*="[detail_service_id]"]';
    if (key) selector = '[name*="[' + key + '][detail_service_id]"]';
    elements = $(selector);
    elements.each((index, coverage) => {
        service = $(coverage).data('service');
        serviceModel = $(coverage).data('service-model');
        $(coverage).select2({
            width: '100%',
            ajax: {
                url: ULR + "quotes/areas/" + serviceModel,
                dataType: 'json',
                method: "GET",
                error: function(error) {
                    var mdlForm = $('#mdlForm');
                    var loading = $('.loading', mdlForm).first();
                    loading.attr('data-show', 'false');
                    handleErrors(error);
                }
            },
            cache: true,
            "language": {
                "noResults": () => "No se encontraron coberturas registradas en esta sede.",
                "searching": () => "Consultando..."
            }
        }).on("select2:select", function(e) {
            var selected_element = $(e.currentTarget);
            var area = selected_element.val();
            service = selected_element[0].dataset.service;
            $('[name*="[' + service + '][price]"]').val(number_format(e.params.data.cost));
            $('[name*="[' + service + '][visits]"]').val(number_format(e.params.data.visits));

        });
    });


}

function initSelectPeriodicities(key = false) {

    var selector = '[name*="[periodicity_id]"]';
    if (key) selector = '[name*="[' + key + '][periodicity_id]"]';

    var mdlForm = $('#mdlForm');
    var dropdownParentEl = $('.modal-content', mdlForm);

    $(selector).select2({
        width: '100%',
        dropdownParent: dropdownParentEl,
        ajax: {
            url: ULR + "quotes/periodicities",
            dataType: 'json',
            method: "GET",
            success: selectScroll,
            error: function(error) {
                handleErrors(error);
            }
        },
        cache: true,
        "language": {
            "noResults": () => "No se encontraron periodicidades registradas en el sistema.",
            "searching": () => "Consultando...",
            "errorLoading": () => "No se puede leer la respuesta del servidor"
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var service = selected_element.data('serviceid');

        var periodicity = e.params.data.id;

        resetWeeks(service);

    }).on("select2:open", e => selectScroll());
}


function resetWeeks(service) {

    //verificar si ya hay datos de semanas configurado para no borrar los bloques

    //$('[name="services[' + service + '][weeks][]"]').val(null).trigger("change");
    $("[name*='services[" + service + "][weeks][]']").select2('destroy');
    $("[name*='services[" + service + "][weeks][]']").off('select2:select');
    //$('[name="services[' + service + '][weeks][]"]').html('');
    ///$('#services' + service + 'weeks').prop('checked', false);
    //$("#weekFramesSection" + service).html('');
    initSelectWeeks(service);
}



function initSelectInfestations(initConst = false, key = false) {


    if (key == false) var selector = '[name*="[infestation]"]';
    else var selector = '[name*="[' + key + '][infestation]"]';

    $(selector).each(function() {
        $(this).select2({
            width: '100%',
            allowClear: true,
            dropdownParent: $('.modal-content', $('#mdlForm')),
            placeholder: "Control",
            ajax: {
                url: ULR + "quotes/infestations/",
                dataType: 'json',
                method: "GET",
                error: (error) => {
                    var mdlForm = $('#mdlForm');
                    var loading = $('.loading', mdlForm).first();
                    loading.attr('data-show', 'false');
                    handleErrors(error);
                },
                processResults: function(data) {
                    return {
                        results: $.map(data.results, function(obj, index) {
                            obj.text = obj.name;
                            return obj;
                        })
                    };
                },
                success: selectScroll
            },
            cache: true,
            templateResult: dataFormatResultInfestation,
            templateSelection: dataFormatSelection,
            dropdownCssClass: "bigdrop",
            escapeMarkup: (m) => { return m; },
            "language": {
                "noResults": () => "No se encontraron controles para este servicio.",
                "searching": () => "Consultando..."
            }
        }).on("select2:select", (e) => {
            var selected_element = $(e.currentTarget);
            var control = e.params.data.id;
        }).on("select2:open", e => selectScroll());
    });
}

function initSelectDoses(key = false) {

    if (key == false) var selector = '[name*="[dose]"]';
    else var selector = '[name*="[' + key + '][dose]"]';

    $(selector).each(function() {

        $(this).select2({
            allowClear: true,
            width: '100%',
            dropdownParent: $('.modal-content', $('#mdlForm')),
            placeholder: "Dosis",
            ajax: {
                url: ULR + 'crm/' + $("[name='crm_id']").val() + "/schedule/doses",
                dataType: 'json',
                method: "GET",
                error: (error) => {
                    var mdlForm = $('#mdlForm');
                    var loading = $('.loading', mdlForm).first();
                    loading.attr('data-show', 'false');
                    handleErrors(error);
                },
                success: selectScroll
            },
            "language": {
                "noResults": () => "No se encontraron dosis creadas en el sistema",
                "searching": () => "Consultando..."
            }
        }).on("select2:select", (e) => {
            var selected_element = $(e.currentTarget);
            CONTROLSELECTED = $(this).val();
            var control = e.params.data.id;
            //addControlFrame(service, control);
        }).on("select2:open", e => selectScroll());
    });
}

function initSelectApplications(key = false) {

    if (key == false) var selector = '[name*="[application]"]';
    else var selector = '[name*="[' + key + '][application]"]';


    $('[name*="[application]"]').each(function() {
        $(this).select2({
            width: '100%',
            dropdownParent: $('.modal-content', $('#mdlForm')),
            placeholder: "Aplicación",
            ajax: {
                url: ULR + "crm/" + $("[name='crm_id']").val() + "/schedule/applications",
                dataType: 'json',
                method: "GET",
                error: function(error) {
                    handleErrors(error);
                },
                success: selectScroll
            },
            cache: true,
            dropdownCssClass: "bigdrop",
            escapeMarkup: function(m) { return m; },
            "language": {
                "noResults": () => "No se encontraron aplicaciones registradas en el sistema.",
                "searching": () => "Consultando...",
                "errorLoading": () => "No se puede leer la respuesta del servidor"
            }
        }).on("select2:open", e => selectScroll())
    });
}

function initAllRadios() {
    $('[data-click="productType"]:checked').each(function() {
        updateSelects(this);
    });
}

function initRadio(key) {
    $('.skin-square input').iCheck({ checkboxClass: 'icheckbox_square-red', radioClass: 'iradio_square-red', });
    element = $("[name*='[" + key + "][type]']");
    updateSelects(element);
}

function updateSelects(element) {
    var value = $(element).val();
    var service = $(element).data('service');
    var infestation = $(element).data('infestation');
    var key = $(element).data('key');

    if (value == 'application') {
        $('[name*="services[' + service + '][control][' + infestation + '][product][' + key + ']"].select-supply').next(".select2-container").hide();
        $('[name*="services[' + service + '][control][' + infestation + '][product][' + key + ']"].select-application').attr("disabled", false);
        $('[name*="services[' + service + '][control][' + infestation + '][product][' + key + ']"].select-application').next(".select2-container").show();
        $('[name*="services[' + service + '][control][' + infestation + '][product][' + key + ']"].select-supply').attr("disabled", true);

        //deshabilitar campos cant/dosis, medida
        $('[name*="services[' + service + '][control][' + infestation + '][product][' + key + '][quantity]"]').attr('disabled', true);
        $('[name*="services[' + service + '][control][' + infestation + '][product][' + key + '][quantity]"]').attr('type', 'text');
        $('[name*="services[' + service + '][control][' + infestation + '][product][' + key + '][quantity]"]').val('NO APLICA');
        $('[name*="services[' + service + '][control][' + infestation + '][product][' + key + '][unity]"]').attr('disabled', true);
        $('[name*="services[' + service + '][control][' + infestation + '][product][' + key + '][unity]"]').val('NO APLICA');
    }

    if (value == 'supply') {
        $('[name*="services[' + service + '][control][' + infestation + '][product][' + key + ']"].select-supply').next(".select2-container").show();
        $('[name*="services[' + service + '][control][' + infestation + '][product][' + key + ']"].select-application').attr("disabled", true);
        $('[name*="services[' + service + '][control][' + infestation + '][product][' + key + ']"].select-application').next(".select2-container").hide();
        $('[name*="services[' + service + '][control][' + infestation + '][product][' + key + ']"].select-supply').attr("disabled", false);

        //habilitar campos cant/dosis, medida
        $('[name*="services[' + service + '][control][' + infestation + '][product][' + key + '][quantity]"]').attr('disabled', false);
        $('[name*="services[' + service + '][control][' + infestation + '][product][' + key + '][quantity]"]').attr('type', 'number');
        $('[name*="services[' + service + '][control][' + infestation + '][product][' + key + '][unity]"]').attr('disabled', false);

    }
}

$('body').on('ifChecked', '[data-click="productType"]', function(e) {
    e.preventDefault();
    updateSelects(this);
});

$('body').on('click', '[data-click="setService"]', function(e) {
    SERVICE = $(this).data('id');
    DIV_TAB = $(this).attr('href');
    //initSelectArea(SERVICE);
    initSelectWeeks(SERVICE, false, false, false)

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

    var elemente = $('.start-week');

    $.each(elemente, function(el) {

        $(this).pickadate({
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
            close: 'Cerrar',
            onSet: (context) => {
                var week_select_values = $("[name='services[" + $(this).data('serviceid') + "][weeks][]']").val();
                if (week_select_values.length == 0) {
                    resetWeeks($(this).data('serviceid'));
                }

            }
        });
    });

}

async function addWeekFrame(service, week, serviceName) {

    //verificar que el bloque de la semana no exista
    var block = $(".section" + service + week.week);

    if (block.length == 0) {
        await loadWeekFrame(service, week, serviceName).done((data) => {
            $("#weekFramesSection" + service).append(data.html);
            var key = data.suplyNewRowFrameKey;
            initSelectInfestations(true, key);
            processAvatars();
            initSelectAreaControl(key);
            initSelectProducts(key);
            initSelectDoses(key);
            initSelectApplications(key);
            initTextareaResize();
        });
    }
}

function loadWeekFrame(service, week, serviceName) {
    return $.ajax({
        url: ULR + "crm/" + $("[name='crm_id']").val() + "/schedule/add-week-frame",
        method: "GET",
        data: {
            service: service,
            week: week,
            serviceName: serviceName
        },
        dataType: "json"
    });
}


function initSelectWeeks(service = false, control = false, periodicity = false, key = false) {
    if (key != false) var selector = "[name*='[services][" + key + "][weeks][]']";
    else var selector = "[name*='[weeks][]']";

    $(selector).each(function() {
        if (!$("[name='" + $(this).attr('name') + "']").hasClass("select2-hidden-accessible")) {
            var service = $(this).data('serviceid');
            if (periodicity == false) periodicity = $("[name*='services[" + service + "][periodicity_id]']").val();
            if (control == false) control = $("[name*='services[" + service + "]controls][]']").val();

            $("[name='" + $(this).attr('name') + "']").select2({
                width: '100%',
                dropdownParent: $('.modal-content', $('#mdlForm')),
                placeholder: "Seleccione una o mas semanas",
                multiple: true,
                ajax: {
                    url: ULR + "crm/" + $("[name='crm_id']").val() + "/schedule/getWeeks",
                    method: "GET",
                    data: {
                        service: service,
                        control: control,
                        periodicity: periodicity,
                        start_date: $("[name*='[" + service + "][start_week]_submit']").val()
                    },
                    dataType: "json",
                    error: (error) => {
                        var mdlForm = $('#mdlForm');
                        var loading = $('.loading', mdlForm).first();
                        loading.attr('data-show', 'false');
                        handleErrors(error);
                    },

                },
                closeOnSelect: false,
                success: selectScroll,
                language: {
                    "noResults": () => "No se encontraron semanas disponibles.",
                    "searching": () => "Consultando..."
                }
            }).on("select2:select", async function(e, week = null) {

                var selected_element = $(e.currentTarget);
                var service = selected_element.data('serviceid');
                if (week == null) week = e.params.data;
                var serviceParent = $(this).closest('.tab-pane-schedule').first().attr('id');
                var serviceName = $('[href="#' + serviceParent + '"]').first().text().trim();

                await addWeekFrame(service, week, serviceName);
            }).on("select2:open", e => selectScroll()).on("select2:unselect", (e, service = null, control = null) => {

                var selected_element = $(e.currentTarget);
                if (service == null) service = selected_element.data('serviceid');
                if (control == null) control = e.params.data.id;
                $(".section" + service + control).remove();
            });
        } else {
            console.warn("Selected2 in tab has already initialized, new init was ignored");
        }

    });


}

function loadWeeks(service, control, periodicity) {
    return $.ajax({
        url: ULR + "crm/" + $("[name='crm_id']").val() + "/schedule/getWeeks",
        method: "GET",
        data: {
            service: service,
            control: control,
            periodicity: periodicity,
            start_date: $("[name*='[" + service + "][start_week]_submit']").val()
        },
        dataType: "json"
    });
}

function initSelectAreaControl(key = false) {
    if (key != false) selector = '[name*="[' + key + '][area]"]';
    else selector = '[name*="[area]"]';
    $(selector).select2({
        width: '100%',
        dropdownParent: $('.modal-content', $('#mdlForm')),
        placeholder: "Área",
        ajax: {
            url: ULR + "config/areas/",
            dataType: 'json',
            method: "GET",
            success: selectScroll,
            error: function(error) {
                handleErrors(error);
            }
        },
        cache: true,
        allowClear: true,
        "language": {
            "noResults": () => "No se han registrado areas para controles",
            "searching": () => "Consultando...",
            "errorLoading": () => "No se puede leer la respuesta del servidor"
        }
    }).on("select2:open", e => selectScroll());

}

function initSelectProducts(key = false) {
    if (key == false) var selector = '[name*="[product]"]';
    else var selector = '[name*="[' + key + '][product]"]';
    $(selector).each(function() {
        $(this).select2({
            width: '100%',
            dropdownParent: $('.modal-content', $('#mdlForm')),
            placeholder: "Producto",
            allowClear: true,
            ajax: {
                url: ULR + "quotes/products/category/1/control/" + $(this).data('control'),
                dataType: 'json',
                method: "GET",
                processResults: function(data) {
                    return {
                        results: $.map(data.results, function(obj, index) {
                            obj.text = obj.name;
                            return obj;
                        })
                    };
                },
                success: selectScroll,
                error: function(error) {
                    handleErrors(error);
                }
            },
            cache: true,
            templateResult: dataFormatResultProduct,
            templateSelection: dataFormatSelectionControlProduct,
            dropdownCssClass: "bigdrop",
            escapeMarkup: function(m) { return m; },
            "language": {
                "noResults": () => "No se encontraron productos registrados en el sistema.",
                "searching": () => "Consultando...",
                "errorLoading": () => "No se puede leer la respuesta del servidor"
            }
        }).on("select2:select", function(e) {
            var selected_element = $(e.currentTarget);
            var key = selected_element.data('key');
            var product = selected_element.val();
            $('[name*="[' + key + '][unity]"]').val(e.params.data.unit.name);

            if (e.params.data.category_id == 1) {
                $("input[name*='[" + key + "][from]']").val('');
                $("input[name*='[" + key + "][to]']").val('');
                $("#iac_" + key).css({ 'display': 'flex' });
                if (e.params.data.color != '') {
                    $("input[name*='[" + key + "][from]']").val('');
                    $("input[name*='[" + key + "][to]']").val('');
                    $("#iac_" + key + " .aic-icon").css({ 'color': e.params.data.color });
                }
            } else {
                $("#iac_" + key).hide();
            }
        }).on("select2:open", e => selectScroll());
    });
}

$('body').on('click', '[data-click="checkAllWeeks"]', function(e) {
    var service = $(this).data('serviceid');
    if ($(this).is(':checked')) {
        $('[name="services[' + service + '][weeks][]"]').val(null).trigger("change");
        $('[name="services[' + service + '][weeks][]"]').html('');
        $("#weekFramesSection" + service).html('');
        var periodicity = $("[name*='services[" + service + "][periodicity_id]']").val();
        loadWeeks(service, null, periodicity).done(function(data) {
            showLoading();
            $.ajaxSetup({ async: false });
            data.results.forEach(function(week, indx) {
                var $newOption = $("<option selected></option>").val(week.id).text(week.text);
                $('[name="services[' + service + '][weeks][]"]').append($newOption);



                var serviceParent = $('[name="services[' + service + '][weeks][]"]').closest('.tab-pane-schedule').first().attr('id');
                var serviceName = $('[href="#' + serviceParent + '"]').first().text().trim();

                addWeekFrame(service, week, serviceName);
                if ((indx + 1) == data.results.length) {
                    $.ajaxSetup({ async: true });
                    setTimeout(() => {
                        hideLoadingOnly();
                    }, 600);
                }
            });
            $('[name="services[' + service + '][weeks][]"]')
        });

    } else {
        $('[name="services[' + service + '][weeks][]"]').val(null).trigger("change");
        $('[name="services[' + service + '][weeks][]"]').html('');
        $("#weekFramesSection" + service).html('');
    }
});

$('body').on('click', '[data-click="addSupplyRow"]', function(e) {

    e.preventDefault();
    var week = $(this).data('week');
    var service = $(this).data('service');
    addSupplyRow(week, service).done(function(data) {
        $('#table' + service + week + ' tbody').append(data.row);
        initSelectInfestations(true, data.key);
        processAvatars();
        initSelectAreaControl(data.key);
        initSelectProducts(data.key);
        initSelectDoses(data.key);
        initSelectApplications(data.key);
    }).fail(function(error) {
        handleErrors(error);
    });
});

function addSupplyRow(week, service) {
    return $.ajax({
        url: ULR + "crm/" + $("[name='crm_id']").val() + "/schedule/add-supply-row",
        method: "GET",
        data: { service: service, week: week },
        dataType: "json"
    });
}

$('body').on('click', '[data-click="removeSupplyRow"]', function(e) {

    e.preventDefault();
    let key = $(this).data('key');
    KEY_DELETE = key;
    $('tr[data-id="' + KEY_DELETE + '"]').remove();
});

$('body').on('click', '[data-click="saveSupplies"]', function(e) {

    var data_action = $(this).data('action');
    var data_type = $(this).data('type');

    if (data_action == 'force' && data_type == 'edit') {
        Swal.fire({
            title: 'Regenerar nuevamente',
            text: "Este botón te permite resetear todos los cambios del cronograma y restaurarlo a su origen, perderás  todos los cambios de fechas, estados, notas exernas y refuerzos que hayas realizado sobre uno o mas servicios.",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#fdb901',
            cancelButtonColor: '#aaa',
            confirmButtonText: 'Si, Regenerar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value) {
                var mdlForm = $('#mdlForm');
                saveFormSupplies(data_action).done(function(data) {
                    if (data.success) {
                        $("#sendScheduleFilter").trigger('submit');
                        mdlForm.modal('hide');
                    }
                    swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
                }).fail(function(error) {
                    showErrors(error.responseJSON.errors, '#supplies');
                    handleErrors(error);
                });
            }
        });
    } else {

        var mdlForm = $('#mdlForm');
        saveFormSupplies(data_action).done(function(data) {
            if (data.success) {
                $("#sendScheduleFilter").trigger('submit');
                mdlForm.modal('hide');
            }

            if(window.location.href.indexOf("close=true") > -1){
                swal.fire({
                    'title': data.success ? "Correcto" : "Error",
                    'text': data.message,
                    'type': data.success ? "success" : "error",
                }).then(function(e) {
                    if (data.success == true) {
                        window.close();
                        done(true);
                    } else {
                        done(false);
                    }
                });
            }else{
                swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
            }
            
        }).fail(function(error) {
            showErrors(error.responseJSON.errors, '#supplies');
            handleErrors(error);
        });

    }


});

function saveFormSupplies(data_action) {

    var form = $('#supplies');
    var data = form.serialize();
    return $.ajax({
        url: ULR + 'crm/' + $("[name='crm_id']").val() + '/schedule/save?action=' + data_action,
        method: 'POST',
        data: data,
        dataType: "json"
    });
}


/**
 * 
 * FUNCIONES JS PARA FUNCION INDIVIDUAL DE FORMULARIO DE CONTROL
 * 
 */

$('body').on('click', '.scheduleEdit', function(e, params= null) {
    var id = $(this).attr('href');
    e.preventDefault();

    var mdlForm = null;
    if(params && params.origin == 'programmer'){
        mdlForm = $('#mdlLargueForm');
    }else{
        mdlForm = $('#mdlForm');
    }
    
    editControlForm(id, params).done((data) => {
        $('.modal-content', mdlForm).html(data.view);
        initSelectTimeUnitControlForm();
        initInputDateSimple();
        initSelectProducts();
        initSelectAreaControl();
        initSelectApplications();
        initSelectDoses();
        initSelectInfestations();
        initSelectScheduleStatuses();
        initSelectTechnicals();

        mdlForm.modal('show');
    }).fail((error) => {
        handleErrors(error);
    });

    mdlForm.modal('show');

});

function editControlForm(id, params= null) {
    var crm = null;
    if(params != null && params.crm != undefined){
        crm = params.crm;
    }else{
        crm = $("[name='crm_id']").val();
    }
    
    return $.ajax({
        
        url: ULR + 'crm/' + crm + '/schedules/edit/' + id,
        method: 'GET',
    });
}

function initSelectTimeUnitControlForm() {
    $("[name='time_unit_id']").select2({
        width: '100%',
    });
}

function initSelectAreaControlForm(key) {
    selector = '[name*="area"]';
    $(selector).select2({
        allowClear: true,
        ajax: {
            url: ULR + "quotes/areas",
            dataType: 'json',
            method: "GET",
            success: selectScroll,
            error: function(error) {
                handleErrors(error);
            }
        },
        cache: true,
        "language": {
            "noResults": () => "No se han resgistrado areas para este cliente, en esta sede.",
            "searching": () => "Consultando...",
            "errorLoading": () => "No se puede leer la respuesta del servidor"
        }
    }).on("select2:open", e => selectScroll());

}

function initSelectAreaControlForm(key) {
    selector = '[name*="area"]';
    $(selector).select2({
        allowClear: true,
        ajax: {
            url: ULR + "quotes/areas",
            dataType: 'json',
            method: "GET",
            success: selectScroll,
            error: function(error) {
                handleErrors(error);
            }
        },
        cache: true,
        "language": {
            "noResults": () => "No se han resgistrado areas para este cliente, en esta sede.",
            "searching": () => "Consultando...",
            "errorLoading": () => "No se puede leer la respuesta del servidor"
        }
    }).on("select2:open", e => selectScroll());

}

$('body').on('click', '[data-click="addSupplyRowFormControl"]', function(e) {

    e.preventDefault();
    var control = $(this).data('control');
    var infestation = $(this).data('infestation');
    addSupplyRowFormControl(control, infestation).done(function(data) {
        $('#table' + infestation + ' tbody').append(data.row);
        initSelectProduct(data.key);
        initSelectApplications(data.key);
        initSelectAreaControl(data.key);
        initSelectInfestations(data.key);
        initSelectProducts(data.key);
        initSelectDoses(data.key);
    }).fail(function(error) {
        handleErrors(error);
    });
});

function addSupplyRowFormControl(control, infestation) {
    return $.ajax({
        url: ULR + "crm/{crm}/schedule/add-supply-row-form-control",
        method: "GET",
        data: { serviceId: SERVICE, infestationId: infestation, control: control },
        dataType: "json"
    });
}

$('body').on('click', '[data-click="saveControlForm"]', function(e) {
    e.preventDefault();
    var mdlForm = $('#mdlForm');
    saveControlForm().done(function(data) {
        if (data.success) {
            mdlForm.modal('hide');
            $('.close').trigger('click');
            $("#agenda").fullCalendar('refetchEvents');
            $('.popover').remove();
            $('body').trigger('save-event');
        }
        //swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }).fail(function(error) {
        showErrors(error.responseJSON.errors, '#control');
        handleErrors(error);
    })

});

function saveControlForm() {
    var form = $('#control');
    var data = form.serialize();
    var crm_id = $("[name='crm_id']").val() !="" ? $("[name='crm_id']").val() : $("[name='crm_id_global']").val();
    return $.ajax({
        url: ULR + 'crm/' + crm_id + '/schedule/control/save',
        method: 'POST',
        data: data,
        dataType: "json"
    });
}

$('body').on('click', '[data-click="removeServiceFromSession"]', function(e) {
    e.preventDefault();
    var id = $(this).data('id');
    Swal.fire({
        title: 'Quitar este servicio',
        text: "¿Estas seguro que deseas eliminar este servicio?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e91e4d',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Si, Quitar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.value) {
            removeServiceFromSession(id).done(function(data) {

                if (data.success) {
                    showToast(data.message);
                    //volver a cargar la vista
                    $('.serviceControls-' + id).remove();
                    $(DIV_TAB).remove();
                } else {
                    showToastError(data.message);
                }


            }).fail(function(error) {
                handleErrors(error);
            });
        }
    });

});


function removeServiceFromSession(id) {
    return $.ajax({
        url: ULR + 'crm/' + $("[name='crm_id']").val() + '/schedule/session/service-remove',
        method: 'GET',
        data: {
            service_id: id,
            office_id: $('[name="office_id"]').val(),
        },
        dataType: "json"
    });
}

$('body').on('click', '[data-click="removeServiceFake"]', function(e) {
    e.preventDefault();
    var id = $(this).data('fake');
    $('.serviceControls-' + id).remove();
    $("#tab" + id).remove();
});

function removeService(id) {
    return $.ajax({
        url: ULR + 'crm/' + $("[name='crm_id']").val() + '/schedule/service-remove',
        method: 'GET',
        data: {
            service_id: id,
            office_id: $('[name="office_id"]').val(),
        },
        dataType: "json"
    });
}


$('body').on('click', '[data-click="removeRowObservation"]', function(e) {

    
    if ($(this + ' input[name*="id"]').length > 0) {

        let key = $(this).data('key');

        let id = $('input[name="observation[' + key + '][id]"]').val();
                if(id.trim() == ''){
                    $('tr[data-id="' + key + '"]').remove();
                    return;
                }

        e.preventDefault();
        Swal.fire({
            title: '¿Está seguro que desea eliminar esta observación?',
            showCancelButton: true,
            cancelButtonText: 'No, Canelar',
            confirmButtonText: 'Si, Eliminar',
            confirmButtonColor: '#d44035',


        }).then(function(result) {
            if (result.value !== undefined) {
                
                $.ajax({
                    url: ULR + 'crm/' + $("[name='crm_id']").val() + '/schedule/observation/' + id + '/delete',
                    method: 'GET',
                }).done((data) => {
                    swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
                    $('tr[data-id="' + key + '"]').remove();
                    $("#agenda").fullCalendar('refetchEvents');
                    $('.popover').remove();
                }).fail(function(error) {
                    handleErrors(error);
                });
            }
        });
    }
});

/**
 * FUNCIONES JS PARA FUNCION DE FORMULARIO DE NOTAS EXTERNAS
 * 
 */
$('body').on('click', '.externalNoteNew', function(e) {
    var id = $(this).attr('href');
    var date = $(this).attr('data-date');
    e.preventDefault();
    externalNoteNew(id, date);
});

function externalNoteNew(id = null, date = null, type = 1) {
    var mdlForm = $('#mdlFormShow');
    newExternalNoteForm(id, date, type).done((data) => {
        $('.modal-content', mdlForm).html(data.view);
        initSelectWorkschedule();
        mdlForm.modal('show');
        initSelects();
    }).fail((error) => {
        handleErrors(error);
    });
}

function newExternalNoteForm(id, date, type = 1) {
    return $.ajax({
        url: ULR + 'crm/' + $("[name='crm_id']").val() + '/external-note/form',
        data: { id: id, date: date, office: $("[name='OfficesCustomer']").val(), type: type },
        method: 'GET',
    });
}

$("body").on('submit', '#formExternalNote', function(e) {
    saveExternalNote();
});
$("body").on('click', '[data-click="saveExternalNote"]', function(e) {
    saveExternalNote();
});

function saveExternalNote() {
    var mdlForm = $('#mdlFormShow');
    saveExternalNoteAjax().done(function(data) {
        if (data.success) {
            $("#sendScheduleFilter").trigger('submit');
            mdlForm.modal('hide');
        }
        //swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }).fail(function(error) {
        showErrors(error.responseJSON.errors, '#formExternalNote');
        handleErrors(error);
    });
}

function saveExternalNoteAjax() {
    var form = $('#formExternalNote');
    var data = form.serialize();
    return $.ajax({
        url: ULR + 'crm/' + $("[name='crm_id']").val() + '/external-note/save',
        method: 'POST',
        data: data,
        dataType: "json"
    });
}
function initSelects() {

    setTimeout(function() {
        initSelectCommercials();
    }, 300);

}
function initSelectCommercials(key = false) {

    var selector = '[name*="commercial"]';
    if (key) {
        selector = '[name*="[' + key + '][user]"]';
    }
    $(selector).select2({
        placeholder: "Dejar vacío para cualquiera",
        allowClear: true,
        width: "100%",
        ajax: {
            url: ULR + "crm/commercials/all",
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
$('body').on('click', '.externalNoteDelete', function(e) {
    var id = $(this).data('id');
    e.preventDefault();
    Swal.fire({
        title: '¿Desea eliminar este nota?',
        text: 'Ten presente que esta es una acción irreversible',
        showCancelButton: true,
        cancelButtonText: 'No, Canelar',
        confirmButtonText: 'Si, Eliminar',
        confirmButtonColor: '#d44035',
    }).then(function(result) {
        if (result.value !== undefined) {
            $.ajax({
                url: ULR + 'crm/' + $("[name='crm_id']").val() + '/external-note/delete/' + id,
                method: 'GET',
                data: { type: result.value }
            }).done((data) => {
                swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");

                if (data.success) {
                    var mdlForm = $('#mdlFormShow');
                    mdlForm.modal('hide');
                    $("#agenda").fullCalendar('refetchEvents');
                }
            }).fail(function(error) {
                handleErrors(error);
            });
        }
    });
});


/**
 * FUNCIONES JS PARA FUNCION DE FORMULARIO DE NOTAS DE FACTURACION
 * 
 */
$('body').on('click', '.externalNoteInvoiceNew', function(e) {
    var id = $(this).attr('href');
    var date = $(this).attr('data-date');
    e.preventDefault();
    externalNoteNew(null, date, 2);
});


/**
 * 
 * FUNCIONES JS PARA FUNCION INDIVIDUAL DE FORMULARIO DE REFUERZOS
 * 
 */

$('body').on('click', '.scheduleNew', function(e, params) {
    var id = $(this).attr('href');
    var date = $(this).attr('data-date');
    e.preventDefault();
    scheduleNew(null, date, $(this).data('module'));
});

function scheduleNew(id = null, date = null, module = null) {
    
    var mdlForm = $('#mdlForm');

    if(module == 'programmer'){
        mdlForm = $('#mdlLargueForm');
    }

    newControlForm(id, date).done((data) => {
        $('.modal-content', mdlForm).html(data.view);
        if(module == 'programmer'){
            $('#mdlLargueForm').modal('show');
            $('[data-click="gotoNewSchedule"]').data('module', 'programmer');
        }
        initSelectWorkschedule();
        initSelectNewService();
        mdlForm.modal('show');
    }).fail((error) => {
        handleErrors(error);
    });
}

function newControlForm(id, date) {

    return $.ajax({
        url: ULR + 'crm/' + $("[name='crm_id']").val() + '/schedules/new',
        data: { id: id, timestamp: date },
        method: 'GET',
    });
}

function initSelectWorkschedule() {
    var selector = $('[name="workschedule"]');
    selector.select2({
        width: '100%',
        placeholder: 'Seleccione un origen',
        allowClear: true,
        ajax: {
            url: ULR + 'crm/' + selector.data('crm') + '/office/' + $("[name='office_id']").val() + '/workschedules',
            dataType: 'json',
            method: "GET",
            success: selectScroll,
            error: function(error) {
                handleErrors(error);
            }
        },
        cache: true,
        "language": {
            "noResults": () => "No se han encontrado origenes para este cliente.",
            "searching": () => "Consultando...",
            "errorLoading": () => "No se puede leer la respuesta del servidor"
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);

        initSelectWorkscheduleServices(e.params.data);

    }).on("select2:open", e => selectScroll());
}

function initSelectWorkscheduleServices(data) {
    let select_service = $('[name="service"]');
    select_service.empty();
    var $newOption = $("<option></option>").val('').text('Seleccione un servicio');
    $('[name="service"]').append($newOption);
    Object.values(data._services).forEach(element => {
        var $newOption = $("<option></option>").val(element.id).text(element.service.name);
        $('[name="service"]').append($newOption);
    });
}

$('body').on('change', '[name="service"]', function(e) {
    var id = $(this).val();
    var date = $('[name="date"]').val();
    $.ajax({
        url: ULR + 'crm/workschedules/service/' + id,
        data: { date: date },
        method: 'GET',
        dataType: 'json'
    }).done(function(data) {
        if (data.success) {
            $('#new_schedule_frame').html(data.view);
            initSelectTimeUnitControlForm();
            initInputDateSimple();
            initSelectProducts();
            initSelectAreaControl();
            initSelectApplications();
            initSelectDoses();
            initSelectInfestations();
        }
    }).fail(function(error) {
        handleErrors(error);
    });
});


$("body").on('submit', '#FormNew', function(e) {
    saveControlFormNew();
});
$("body").on('click', '[data-click="saveControlFormNew"]', function(e) {
    saveControlFormNew();
});

function saveControlFormNew() {
    var mdlForm = $('#FormNew');
    saveControlFormNewAjax().done(function(data) {
        if (data.success) {
            $("#sendScheduleFilter").trigger('submit');
            mdlForm.modal('hide');
        }
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }).fail(function(error) {
        showErrors(error.responseJSON.errors, '#FormNew');
        handleErrors(error);
    });
}

function saveControlFormNewAjax() {
    var form = $('#FormNew');
    var data = form.serialize();
    return $.ajax({
        url: ULR + 'crm/' + $("[name='crm_id']").val() + '/office/' + $("[name='OfficesCustomer']").val() + '/workschedules/save',
        method: 'POST',
        data: data,
        dataType: "json"
    });
}


/** FUNCIONALIDAD PARA COPIAR SEMANAS */

$('body').on('click', '[data-click="copyFromWeek"]', async function() {


    options = {};
    $(".section-copyable").each((d, weekSection) => {

        var weekName = $(weekSection).find('.weekName').data('service-name') + " --- " + $(weekSection).find('.weekName').text();
        options[d] = weekName;
    });

    const { value: weekObjectIndex } = await Swal.fire({
        title: 'Copiar configuración de una semana existente',
        input: 'select',
        inputOptions: options,
        inputPlaceholder: 'Selecciona la semana a copiar',
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Copiar',
        inputValidator: (value) => {
            return new Promise((resolve) => {
                if (value !== '') {
                    resolve();
                } else {
                    resolve('Debes seleccionar una semana.');
                }
            })
        }
    })

    if (weekObjectIndex) {
        //deshabilitar los selects 2 para poder copiar correctamente
        $('.select.select2-hidden-accessible').select2('data', null);
        $("select.select2-hidden-accessible").select2('destroy');

        var weekObject = $(".section-copyable")[weekObjectIndex];
        var tbody = reeplaceKeys($(weekObject).find(".tbl-supplies>tbody").html(), $(this).data('service'), $(this).data('week'));
        $(this).closest('section').find('table>tbody').html(tbody);

        //habilitar nuevamente los select2
        initSelectCoverages();
        initSelectPeriodicities();
        initSelectWeeks();
        initSelectInfestations(true);
        initSelectAreaControl();
        initSelectProducts();
        initSelectDoses();
        initSelectApplications();
    }
});

function reeplaceKeys(tbody, service, week) {

    //reemplazar servicio por la correspondiente
    var regexWeekNum = /(?<=services\[)(.*)(?=\]\[week\])/gi;
    tbody = tbody.replace(regexWeekNum, service);

    //reemplazar la semana por la correspondiente
    var regexWeekNum = /(?<=\[week\]\[)(.*)(?=\]\[control\])/gi;
    tbody = tbody.replace(regexWeekNum, week);

    //asignar un key de control nuevo
    var regexControl = /(?<=\[control\]\[)(.*)(?=\]\[)/gi;
    var oldKeys = tbody.match(regexControl);
    if (oldKeys) oldKeys = oldKeys.filter((v, i, a) => a.indexOf(v) === i);

    oldKeys.forEach((oldKey) => {
        var regex = new RegExp(oldKey, "g");
        var key = Math.random().toString(36).substring(2, 15);
        tbody = tbody.replace(regex, key);
    });

    //eliminar los ids de select2
    var regexSelect2Id = /(?=data-select2-id=")(.*)(?<=")/gi;
    tbody = tbody.replace(regexSelect2Id, '');


    return tbody;
}


function initSelectNewService() {
    $('[name="newservice"]').select2({
        width: '100%',
        placeholder: 'Selecciona un servicio',
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

$('body').on('click','[data-click="gotoNewSchedule"]', function(e){
    e.preventDefault();
    var date = $("[name='date']").val();
    var crm  = $("[name='crm_id']").val();
    var service = $("[name='newservice']").val();
    var office = $("#OfficesCustomer").val();

    if(office == undefined || office == '' || office == null){
        office = $("[name='office_id']").val();
    }


    if($(this).data('module') == 'programmer'){
        $('#mdlLargueForm').modal('hide');
        Object.assign(document.createElement('a'), {
            target: '_blank',
            href: ULR + 'crm/'+crm+'/reforce/date/'+date+'/office/' + office + '/service/'+service + '?close=true',
          }).click();

    }else{
        window.location.href = ULR + 'crm/'+crm+'/reforce/date/'+date+'/office/' + office + '/service/'+service;

    }
    
});

$('body').on('click', '[data-click="openReportsModal"]', function(e) {
    e.preventDefault();
    var mdlForm = $('#mdlFormShow');
    var id = $(this).data('id');

    loadReportInfo(id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        mdlForm.modal('show');
    }).fail(function(error) {
        handleErrors(error);
    });
});

function loadReportInfo(id) {
    return $.ajax({
        url: ULR + "scheduling/execution/reports",
        method: "GET",
        data: {
            schedule: id,
        },
        dataType: "json"
    });
}