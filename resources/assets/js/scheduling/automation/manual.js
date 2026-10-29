IGNORE_NEXT = false;
CURRENT_INDEX = 0;
CALENDAR = null;
VALIDATE = true;
TO_PICKER = null;
FROM_PICKER = null;

$("body").ready(function() {

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

    $('.pickadate-translations').pickadate({
        formatSubmit: 'yyyy-mm-dd',
        container: '.app-content',
        min: new Date(),
        firstDay: 1,
        monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
        today: 'Hoy',
        clear: 'Limpiar',
        close: 'Cerrar'
    });


    initAllSelects();

});


function initAllSelects() {

    //initWizard();
    initManualSelectUsers();

}

//Seleccionar solo Técnicos con checkbox

$('body').on('click', '[name="manual_technical"]', function(e) {

    if ($(this).is(':checked')) {

        getListManualTechnicals().done(function(data) {
            data.results.forEach(function(technical) {
                var $newOption = $("<option selected='selected'></option>").val(technical.id).text(technical.name);
                $('[name="manual_users[]"]').append($newOption).trigger('change');
            });
        });

    } else {
        $("[name='manual_users[]'] option:selected").remove();
    }
});

function getListManualTechnicals() {

    return $.ajax({
        url: ULR + "scheduling/technicals",
        method: "GET",
        dataType: "json",
    });
}


function initManualSelectUsers() {

    $('[name="manual_users[]"]').select2({
        width: '100%',
        placeholder: "Uno o mas usuarios",
        closeOnSelect: false,
        ajax: {
            url: ULR + "scheduling/users",
            dataType: 'json',
            method: "GET",
            error: function(error) {
                handleErrors(error);
            }
        },
        cache: true,
        templateResult: dataFormatResultUser,
        templateSelection: dataFormatSelection,
        dropdownCssClass: "bigdrop",
        escapeMarkup: function(m) { return m; },
        "language": {
            "noResults": () => "No se encontraron usuarios disponibles en esta sede.",
            "searching": () => "Consultando..."
        }
    }).on("select2:select", function(e) {

    }).on("select2:open", e => selectScroll());
}


function initTimePickersPluginAutomation() {
    $('.pickadate-fixed').pickadate({
        formatSubmit: 'yyyy-mm-dd',
        container: '.app-content',
        min: new Date(),
        firstDay: 1,
        monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
        hiddenSuffix: '[_submit]',
        today: 'Hoy',
        clear: 'Limpiar',
        close: 'Cerrar'
    });


    $('.timepickers').mdtimepicker({
        theme: 'green',
        hourPadding: false
    });
}


$('body').on('click', '[data-action="reload"]', function() {
    console.log('click', CURRENT_INDEX);
    getStepContent(CURRENT_INDEX);
});

var wizard = $(".steps-validation").steps({
    headerTag: "h6",
    bodyTag: "fieldset",
    transitionEffect: 1,
    transitionEffectSpeed: 300,
    titleTemplate: '<span class="step">#index#</span> #title#',
    labels: {
        finish: 'Terminar',
        previous: 'Anterior',
        next: 'Siguiente'

    },
    onFinished: function(event, currentIndex) {
        var loading = $('.loading').first();
        loading.attr('data-show', 'true');
        saveAutomation().done(function(data) {
            swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");

            if (data.success) window.location.href = ULR + 'scheduling/routes';
        }).fail(function(error) {
            handleErrors(error);
        });

    },
    onStepChanged: (event, currentIndex, priorIndex) => {
        CURRENT_INDEX = currentIndex;
    },
    onStepChanging: function(event, currentIndex, newIndex) {

        //validar el formulario
        if (newIndex < currentIndex) IGNORE_NEXT = true;



        if (!IGNORE_NEXT) initValidateStep(currentIndex, newIndex, this);
        else {
            if (newIndex == 1) {
                $('[href="#next"]').parent().show();
            } else {
                $('[href="#next"]').parent().hide();
            }
            IGNORE_NEXT = false;
        }

        return true;

    }
});

function initValidateStep(step, nextStep, e) {
    setTimeout(() => {
        if (VALIDATE === true) {
            validateStep(step).done((data) => {
                if (data.continue) {
                    getStepContent(nextStep);
                } else {
                    showErrors(data.responseJSON.errors, '#manualAutomation');
                    handleErrors(data);
                    IGNORE_NEXT = true;
                    $(this).steps('previous');
                }
            }).fail((error) => {
                showErrors(error.responseJSON.errors, '#manualAutomation');
                handleErrors(error);
                IGNORE_NEXT = true;
                $(e).steps('previous');
            });
        } else {
            VALIDATE = true;
            getStepContent(nextStep);
        }
    }, 10);
}

function validateStep(step) {
    var form = $('#manualAutomation');
    var data = form.serialize();
    return $.ajax({
        url: ULR + 'scheduling/automation/validate-step/1' + (step + 1),
        method: 'POST',
        data: data,
        dataType: "json",
        async: false
    });
}

function initGeneralSelects() {
    $('.select2').select2({
        placeholder: 'Seleccione una opción'
    });

}

function getStepContent(step) {
    switch (step) {
        case 1:
            stepContent('1' + step).done(function(data) {
                $('.step12').html(data.view);
                initGeneralSelects();
                initFullCalendar4(data.data, data.resources, data.date, data.workigHours);
                SEE_LOADING = true;
                $('.loading-container-text').show();
            }).fail(function(error) {
                handleErrors(error);
            });
            break;
        case 2:
            stepContent('1' + step).done(function(data) {
                $('.step5').html(data.view);
            }).fail(function(error) {

                handleErrors(error);
            });
            break;
        case 3:
            SEE_LOADING = false;
            console.log('hideloading');
            stepContent(step).done(function(data) {

                $('.step4').html(data.view);
                initGeneralSelects();
                initFullCalendar4(data.data, data.resources, data.date, data.workigHours);
                SEE_LOADING = true;

            }).fail(function(error) {

                handleErrors(error);
            });
            break;
        case 4:
            stepContent(step).done(function(data) {
                $('.step5').html(data.view);
            }).fail(function(error) {

                handleErrors(error);
            });
            break;
        default:
            break;
    }
}

function stepContent(step) {

    var form = $('#manualAutomation');
    var data = form.serialize();

    return $.ajax({
        url: ULR + 'scheduling/automation/get-step/' + step,
        method: 'POST',
        data: data,
        dataType: "json"
    });
}

function saveAutomation() {

    var form = $('#manualAutomation');
    var data = form.serialize();

    return $.ajax({
        url: ULR + 'scheduling/automation/manualsave',
        method: 'POST',
        data: data,
        dataType: "json"
    });
}

function initFullCalendar4(data, resources, date, workigHours) {
    if (CALENDAR != null) CALENDAR.destroy();
    var calendarEl = document.getElementById('calendar');
    let count = 0;

    CALENDAR = new FullCalendar.Calendar(calendarEl, {
        plugins: ['resourceTimeGrid', 'interaction'],
        schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
        defaultView: 'resourceTimeGridDay',
        defaultDate: date,
        resources: resources,
        resourceOrder: 'order',
        events: data,
        slotDuration: '00:30:00',
        minTime: '05:00:00',
        maxTime: '23:59:59',
        aspectRatio: 1.8,
        editable: true,
        droppable: true,
        startEditable: true,
        resourceEditable: true,
        durationEditable: true,
        eventStartEditable: true,
        titleRangeSeparator: " al ",
        locale: 'es',
        timezone: 'America/Bogota',
        selectable: true,
        selectMirror: true,
        fixedMirrorParent: $('#manualAutomation', $("#manualAutomation-p-1")),
        expandRows: true,
        contentHeight: 500,

        resourceRender: function (render) {
            sendRender(render);
        },
        eventRender: function(event, element) {

            element = $(event.el);
            element.find(".fc-title").html("&nbsp;<i class='" + event.event.extendedProps.icon + "' style='font-size: 16px;'></i>&nbsp;<span style='line-height: 13px;'>" + event.event.title + "</span>");
            //element.attr('data-click', 'openForm');
            //element.attr('data-id', event.event.id);

        },
        eventDrop: function(event) {
            if (event.newResource !== null) {
                moveEvent(event.event.id, event.event.title, event.newResource.id, convertDate(event.event.start), convertDate(event.event.end));
            } else {
                moveEvent(event.event.id, event.event.title, null, convertDate(event.event.start), convertDate(event.event.end));
            }

        },
        eventResize: function(event) {
            if (event.newResource !== null) {
                moveEvent(event.event.id, event.event.title, null, convertDate(event.event.start), convertDate(event.event.end));
            } else {
                moveEvent(event.event.id, event.event.title, null, convertDate(event.event.start), convertDate(event.event.end));
            }
        },
        select: function(event) {
            var params = { user_id: event.resource.id, start: convertDate(event.start) };
            $('[data-click="openForm"]').trigger('click', params);
        },
        eventClick: function(data) {
            var params = { id: data.event.id };
            $('[data-click="openForm"]').trigger('click', params);
        }

    });
    CALENDAR.render();
/* 
    //date = moment(date);
    setTimeout(() => {
        //date = moment(date);
        CALENDAR.gotoDate(date);
        $('.fc-prev-button').trigger('click');
        $('.fc-next-button').trigger('click');
        setAvatarResource(resources,date);
        hideLoading();
    }, 400); */

}

function moveEvent(id, name, user_id, start, end) {
    Swal.fire({
        title: 'Confirmación necesaria',
        text: "¿Estás seguro que deseas mover " + name + "?",
        type: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3faa6a',
        cancelButtonColor: 'rgba(0,0,0,0.51)',
        confirmButtonText: 'Seguro',
        cancelButtonText: 'Cancelar',
    }).then((result) => {
        if (result.value) {
            updateEventDate(id, user_id, start, end).done(function(response) {
                swal(response.success ? 'Cambios Guardados' : 'Hubo un problema', response.message, response.success ? 'success' : 'warning');
                $('[data-action="reload"]').trigger('click');
            }).fail(function(error) {
                handleErrors(error);
                $('[data-action="reload"]').trigger('click');
            });
        } else {
            $('[data-action="reload"]').trigger('click');
        }
    });
}
function sendRender(object) {
    let date = convertDate(object.resource._calendar.state.dateProfile.renderRange.end,true);
    let user = [object.resource._resource.extendedProps];
    let user_id = object.resource._resource.id;
    setAvatarResource(user, date,user_id);
}

function convertDate(date_ob,type=false) {
    let date = ("0" + date_ob.getDate()).slice(-2);

    // current month
    let month = ("0" + (date_ob.getMonth() + 1)).slice(-2);

    // current year
    let year = date_ob.getFullYear();

    // current hours
    let hours = zfill(date_ob.getHours(), 2);

    // current minutes
    let minutes = zfill(date_ob.getMinutes(), 2);

    // current seconds
    let seconds = zfill(date_ob.getSeconds(), 2);
    if(type)return year + "-" + month + "-" + date;
    // prints date & time in YYYY-MM-DD HH:MM:SS format
    return year + "-" + month + "-" + date + " " + hours + ":" + minutes + ":" + seconds;
}

function zfill(number, width) {
    var numberOutput = Math.abs(number); /* Valor absoluto del número */
    var length = number.toString().length; /* Largo del número */
    var zero = "0"; /* String de cero */

    if (width <= length) {
        if (number < 0) {
            return ("-" + numberOutput.toString());
        } else {
            return numberOutput.toString();
        }
    } else {
        if (number < 0) {
            return ("-" + (zero.repeat(width - length)) + numberOutput.toString());
        } else {
            return ((zero.repeat(width - length)) + numberOutput.toString());
        }
    }
}

function updateEventDate(id, user_id, start, end) {
    return $.ajax({
        url: ULR + "crm/scheduling/automation/update-date",
        method: "GET",
        data: { id: id, start: start, end: end, user_id: user_id },
        dataType: "json"
    });
}


function drawWorkHoursDelimiter(date, workigHours) {

    workigHours.forEach(time => {
        drawLine(date + ' ' + time.start, 'start');
        drawLine(date + ' ' + time.end, 'end');


    });


}

function drawLine(sdate, type) {
    var date = Date.parse(sdate);
    var parentDiv = jQuery(".fc-slats:visible").parent();
    var timeline = parentDiv.children(".timeline");

    timeline = jQuery("<hr>").addClass("slot-timeline-" + type);
    parentDiv.prepend(timeline);


    var curTime = new Date(date);

    var curCalView = CALENDAR.view;
    var minTime = ((curCalView.props.dateProfile.minTime.milliseconds / 1000) / 60) / 60;
    var maxTime = ((curCalView.props.dateProfile.maxTime.milliseconds / 1000) / 60) / 60;

    timeline.show();

    var curSeconds = ((curTime.getHours() - minTime) * 60 * 60) + (curTime.getMinutes() * 60) + curTime.getSeconds();
    var percentOfDay = curSeconds / ((maxTime - minTime) * 3600); // 60 * 60 = 3600, # of seconds in a hour
    var topLoc = Math.floor(parentDiv.height() * percentOfDay);

    timeline.css("top", (type == 'start' ? topLoc + 1 : topLoc) + "px");
}

function setAvatarResource(resources,date=null,user_id=0) {
    resources.forEach((resource) => {
        let userId=user_id > 0 ? user_id : resource.id
        $("th[data-resource-id='" + userId + "']").prepend('<img class="avatar" src="' + resource.avatar + '" style="margin: 0 auto;display: flex;" onerror="this.src=\'' + ULR + 'images/default/user.jpg\'"/> ');
        $.ajax({
            url: ULR + 'peak-plate/verifyUser',
            method: 'GET',
            data: {
                user_id: userId,
                date:date
            },
        }).done((data) => {
            $("th[data-resource-id='" + userId + "']").append(data);
        });
    });
}

function success(data) {
    if (data.success) {
        var mdlForm = $('#mdlForm');
        mdlForm.modal('hide');
        swal("Correcto", data.message, "success");
        getStepContent(CURRENT_INDEX);
    }

}

$('body').on('click', '.schedulingDelete', function(e) {
    var id = $(this).data('id');
    e.preventDefault();
    Swal.fire({
        title: '¿Está seguro que desea eliminar este evento?',
        showCancelButton: true,
        cancelButtonText: 'No, Canelar',
        confirmButtonText: 'Si, Eliminar',
        confirmButtonColor: '#d44035',


    }).then(function(result) {
        if (result.value !== undefined) {
            $.ajax({
                url: ULR + 'scheduling/' + id + '/hardelete',
                method: 'GET',
            }).done((data) => {
                swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");

                $('[data-action="reload"]').trigger('click');
                var mdlForm = $('#mdlForm');
                mdlForm.modal('hide');

            }).fail(function(error) {
                handleErrors(error);
            });
        }
    });
});


//Escuchar evento cuando se realiza un cambio en el formulario de servicios
$('body').on('save-event', function(e, events) {
    $('[data-action="reload"]').trigger('click');
});

$('body').on('click', '[data-click=findInMap]', function(e) {
    var address = $("[name='address']").val();
    initMarkerMap(address, false);
});