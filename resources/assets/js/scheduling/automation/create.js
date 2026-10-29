IGNORE_NEXT = false;
CURRENT_INDEX = 0;
CALENDAR = null;
VALIDATE = true;
TO_PICKER = null;
FROM_PICKER = null;

//no usar zoom -10% para no dañar el select de fullcalendar
document.body.style.zoom = "100%";

$("body").ready(function() {
    initSelectsA();
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
        firstDay: 1,
        monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
        today: 'Hoy',
        clear: 'Limpiar',
        close: 'Cerrar'
    });

    $('[data-action="reload"]').parent().hide();

});

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

$('body').on('change', '.pickadate-range', function(e) {
    // Check if there’s a “from” or “to” date to start with.
    if (FROM_PICKER.get('value')) {
        TO_PICKER.set('min', FROM_PICKER.get('select'));
    }
    if (TO_PICKER.get('value')) {
        FROM_PICKER.set('max', TO_PICKER.get('select'));
    }
});


function initSelectsA() {

    //initWizard();
    initSelectAvailabilities();
    initSelectServicesAutomation();

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

function initSelectAvailabilities(restart = false) {
    if (restart) $('[name="availabilities[]"]').val('').trigger('change');
    $('[name="availabilities[]"]').select2({
        width: '100%',
        dropdownParent: $('#automation', $('.content-wrapper')),
        allowClear: true,
        closeOnSelect: false,
        placeholder: 'Selecciona una o mas disponibilidades',
        ajax: {
            url: ULR + "scheduling/availabilities",
            dataType: 'json',
            method: "GET",
        },
        "language": {
            "noResults": () => "No se encontraron disponibilidades regisstradas para esta sede.",
            "searching": () => "Consultando...",
            "errorLoading": () => "Error del servidor. Contacta a soporte técnico."
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        getUsersByAvailabilities(select_val);
    }).on("select2:unselect", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        getUsersByAvailabilities(select_val);
    });
}

function initSelectServicesAutomation(restart = false) {
    if (restart) $('[name="services[]"]').val('').trigger('change');
    $('[name="services[]"]').select2({
        width: '100%',
        dropdownParent: $('#automation', $('.content-wrapper')),
        allowClear: true,
        closeOnSelect: false,
        placeholder: 'Selecciona uno o mas servicios',
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

function initGeneralSelects() {
    $('.select2').select2({
        placeholder: 'Seleccione una opción'
    });

}

function getUsersByAvailabilities(disponibility) {
    var loading = $('.loading-min', '.users-loading').first();
    var container = $('.users-result', '.users');
    container.hide();
    loading.attr('data-show', 'true');
    usersAvailabilities(disponibility).done(function(data) {
        loading.attr('data-show', 'false');
        $('.users-result', '.users').html(data.view);
        container.show();
    }).fail(function(e) {
        loading.attr('data-show', 'false');
        handleErrors(e);
    });

}

function usersAvailabilities(availabilities) {
    return $.ajax({
        url: ULR + 'scheduling/automation/users-availabilities',
        method: 'GET',
        data: { availabilities: availabilities }
    });
}

$('body').on('click', '[data-click="removeUser"]', function() {
    var id = $(this).data('id');
    $("option[value='" + id + "']", '[name="users[]"]').removeAttr("selected");
    $('#userDiv' + id).remove();
});

$('body').on('click', '[data-click="removeServiceType"]', function() {
    var id = $(this).data('id');
    var crm = $(this).data('crm');
    $("option[value='" + id + "']", '[name="servicesType[' + crm + '][]"]').removeAttr("selected");
    $('#serviceTypeDiv' + crm + '' + id).remove();
    $('.total-services').text(parseInt($('.total-services').text()) - 1);
});

$('body').on('click', '[data-click="removeCrm"]', function() {
    var id = $(this).data('id');
    $(this).parent().remove();
    $("#tabcrm" + id).remove();
    $('.total-services').text(parseInt($('.total-services').text()) - parseInt($(this).data('count')));
});

$('body').on('click', '[data-action="reload"]', function() {
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
        if (CURRENT_INDEX == 0) $('[data-action="reload"]').parent().hide();
        else $('[data-action="reload"]').parent().show();
        if (CURRENT_INDEX == 1 || CURRENT_INDEX == 2) $('[href="#next"]').parent().hide();
        else $('[href="#next"]').parent().show();

        if (priorIndex > currentIndex) {
            $('[href="#next"]').parent().show();
        }

        if (CURRENT_INDEX == 4) {
            $('[href="#next"]').parent().hide();
        }
        initTextareaResize();
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
                    if ($("[name='keep_last_services']").is(':checked') && nextStep == 2) {
                        getStepContent(nextStep + 1);
                        IGNORE_NEXT = true;
                        $('[href="#next"]').parent().show();
                        $('[href="#next"]').trigger('click');
                    } else {
                        getStepContent(nextStep);
                    }
                } else {
                    showErrors(data.responseJSON.errors, '#automation');
                    handleErrors(data);
                    IGNORE_NEXT = true;
                    $(this).steps('previous');
                }
            }).fail((error) => {
                showErrors(error.responseJSON.errors, '#automation');
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
    var form = $('#automation');
    var data = form.serialize();
    return $.ajax({
        url: ULR + 'scheduling/automation/validate-step/' + (step + 1),
        method: 'POST',
        data: data,
        dataType: "json",
        async: false
    });
}

function getStepContent(step) {
    switch (step) {
        case 1:
            stepContent(step).done(function(data) {
                $('.step2').html(data.view);
                initTimePickersPluginAutomation();
                setTimeout(() => {
                    if (data.success) {
                        $('[href="#next"]').parent().show();
                    } else {
                        $('[href="#next"]').parent().hide();
                    }
                }, 500);

            }).fail(function(error) {
                handleErrors(error);
            });
            break;
        case 2:
            SEE_LOADING = false;
            $('.loading-container').show();
            $('.loading-container-text').show();
            $('.loading-container-error').hide();
            $('.loading-container-back').hide();
            stepContent(step).done(function(data) {
                SEE_LOADING = true;
                VALIDATE = false;
                swal(data.success ? 'Automatización Finalizada Correctamente' : 'Se presentó un problema', data.message, data.success ? 'success' : 'warning');
                $('.loading-container').hide();
                $('.loading-container-text').hide();
                $('.loading-container-back').show();
                CURRENT_INDEX=3;
                $(".steps-validation").steps("next");


            }).fail(function(error) {
                SEE_LOADING = true;
                handleErrors(error);
                $('.loading-container').hide();
                $('.loading-container-text').hide();
                $('.loading-container-error').show();
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

    var form = $('#automation');
    var data = form.serialize();

    return $.ajax({
        url: ULR + 'scheduling/automation/get-step/' + step,
        method: 'POST',
        data: data,
        dataType: "json"
    });
}

function saveAutomation() {

    var form = $('#automation');
    var data = form.serialize();

    return $.ajax({
        url: ULR + 'scheduling/automation/save',
        method: 'POST',
        data: data,
        dataType: "json"
    });
}

function initFullCalendar4(data, resources, date, workigHours) {
    if (CALENDAR != null) CALENDAR.destroy();
    var calendarEl = document.getElementById('calendar');

    CALENDAR = new FullCalendar.Calendar(calendarEl, {
        plugins: ['resourceTimeGrid', 'interaction'],
        schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
        defaultView: 'resourceTimeGridDay',
        defaultDate: date,
        resources: resources,
        resourceOrder: 'order',
        events: data,
        slotDuration: '00:15:00',
        slotLabelInterval: 15,
        minTime: '05:00:00',
        maxTime: '23:59:59',
        editable: true,
        droppable: true,
        startEditable: true,
        resourceEditable: true,
        durationEditable: true,
        eventStartEditable: true,
        eventResizableFromStart: true,
        titleRangeSeparator: " al ",
        locale: 'es',
        timezone: 'America/Bogota',
        selectable: true,
        expandRows: true,
        contentHeight: 600,
        resourceRender: function(render) {

        },
        eventRender: function(event, element) {
            element = $(event.el);
            element.find(".fc-title").html("&nbsp;<i class='" + event.event.extendedProps.icon + "' style='font-size: 68px;height: 19px;'></i>&nbsp;<span style='line-height: 13px;'>" + event.event.title + "</span>");
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
    setTimeout(() => {

        $('.fc-resourceTimeGridDay-view').css("width", function(index) {
            var calc = resources.length * 12;
            if (calc < 100) return '100%';
            else return calc + '%';
        });
    }, 400);
    //date = moment(date);
    setTimeout(() => {
        //date = moment(date);
        CALENDAR.gotoDate(date);
        $('.fc-prev-button').trigger('click');
        $('.fc-next-button').trigger('click');
        drawWorkHoursDelimiter(date, workigHours);
        setAvatarResource(resources);
        hideLoading();
    }, 400);

}

function moveEvent(id, name, user_id, start, end) {
    updateEventDate(id, user_id, start, end).done(function(response) {
        if (!response.success) {
            swal(response.success ? 'Cambios Guardados' : 'Hubo un problema', response.message, response.success ? 'success' : 'warning');
            $('[data-action="reload"]').trigger('click');
        }

    }).fail(function(error) {
        handleErrors(error);
        $('[data-action="reload"]').trigger('click');
    });
}


function convertDate(date_ob) {
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
    console.log('Aquii 2');
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

function setAvatarResource(resources) {
    resources.forEach((resource) => {
        $("th[data-resource-id='" + resource.id + "']").prepend('<img class="avatar" src="' + resource.avatar + '" style="margin: 0 auto;display: flex;" onerror="this.src=\'' + ULR + 'images/default/user.jpg\'"/> ');
        if(resource.dispatched){
            $("th[data-resource-id='" + resource.id + "']").append('<br><span class="badge badge-success">Despachado</span>');
        }else{
            $("th[data-resource-id='" + resource.id + "']").append('<br><a href="#" class="btn btn-sm btn-info" data-click="dispatch" data-id="'+ resource.id+'" data-name="'+ resource.title+'">Despachar</a>');
        }
    });
}

// function success(data) {
//     if (data.success) {
//         var mdlForm = $('#mdlForm');
//         mdlForm.modal('hide');
//         swal("Correcto", data.message, "success");
//         getStepContent(CURRENT_INDEX);
//     }

// }

$('body').on('click', '.schedulingDelete', function(e) {
    var id = $(this).data('id');
    e.preventDefault();
    Swal.fire({
        title: '¿Está seguro que desea eliminar este evento?',
        text: 'Esta acción pondrá el servicio en estado sin programar',
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

$('body').on('click', '[data-click="dispatch"]', function(e) {
    var id      = $(this).data('id');
    var name    = $(this).data('name');
    e.preventDefault();
    Swal.fire({
        title: '¿Despachar a '+name+'?',
        showCancelButton: true,
        cancelButtonText: 'No, Canelar',
        confirmButtonText: 'Si, Despachar',
        confirmButtonColor: '#28afd0',


    }).then(function(result) {
        if (result.value !== undefined) {
            $.ajax({
                url: ULR + 'scheduling/dispatch/' + id ,
                data: {
                    init_date_submit: $('[name="init_date_submit"]').val(),
                    availabilities : $('[name="availabilities[]"]').val(),
                    services : $('[name="services[]"]').val()
                },
                method: 'GET',
            }).done((data) => {
                swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");

                if(data.success){
                    $('[data-action="reload"]').trigger('click');
                    Object.assign(document.createElement('a'), {
                        target: '_blank',
                        href: ULR + 'scheduling/routes',
                      }).click();
                }
                

            }).fail(function(error) {
                handleErrors(error);
            });
        }
    });
});

//Escuchar evento cuando se realiza un cambio en el formulario de servicios
$('body').on('save-event', function(e, events) {
    CURRENT_INDEX=3;
    $('[data-action="reload"]').trigger('click');
});