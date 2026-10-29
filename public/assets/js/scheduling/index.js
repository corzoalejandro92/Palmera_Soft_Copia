CROSSING = 'users';
ITEMCALENDAR = null,




    $(document).ready(function() {
        //getList();
        try {
            getFilters();

            //verificar si se debe abrir el formulario automaticamente
            if (callOpenForm) {
                callOpenFormFunction();
            }
        } catch (error) {

        }

    });


function callOpenFormFunction() {
    //abrir el modal y mostrar loading

    SET_DAFAULT_TIME = true;
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'true');
    var mdlForm = $('#mdlForm');
    $('.modal-content', mdlForm).html(decodeURIComponent(escape(atob(callOpenFormView))));
    mdlForm.modal('show');

    initSelectScheduling();
    initSelectUsers();
    initSelectCrms();
    initTimePickersPlugin();
    initSimpleMap();
    //$('[name="type"]').val($('[name="type"]').val()).trigger('select2:select');


}


//-- EVENTOS PARA FORMULARIO DE EVENTOS -----
$('body').on('click', '[data-click="sendFilters"]', function(e) {
    e.preventDefault();
    $("#agenda").fullCalendar('refetchEvents');
});






//-- FUNCIÓNES PARA FORMULARIOS DE EVENTOs

function getFilters() {
    var container = $('#filteContainer');
    $('.loading', container).attr('data-show', 'true');
    loadContainer().done(function(data) {
        container.html(data.view);
        $('.loading', container).attr('data-show', 'false');
        initSelectScheduling();
        initSelectCrossing();
        initSelectCrosssingData();
        initAgenda();
        initCollapsable();
        $(".collapsed").trigger('click');
    }).fail(function(error) {
        handleErrors(error);
    });
}

function loadContainer() {
    return $.ajax({
        url: ULR + "scheduling/filters",
        method: "GET",
        dataType: "json"
    });
}

function getScheduling(start, end) {

    var form = $('#schedulingFilters');
    var data = form.serialize() + "&start=" + start.unix() + "&end=" + end.unix();
    return $.ajax({
        url: ULR + 'scheduling/agenda',
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });
}


//--- INICIALIZAR CALENDARIO

$(document).on("contextmenu", "a.fc-event", function(e) {
    console.log(e);
});

function initAgenda() {
    $('#agenda').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'agendaDay,agendaWeek,month,listWeek'
        },
        defaultView: 'month',
        titleRangeSeparator: " al ",
        locale: 'es',
        editable: true,
        droppable: true, // this allows things to be dropped onto the calendar
        eventLimit: true, // for all non-agenda views
        eventLimitText: "Ver más",
        timezone: 'America/Bogota',
        views: {
            month: {
                eventLimit: 5
            }
        },
        events: function(start, end, timezone, callback) {
            var loading = $('.loading', '#agendaSection').first();
            loading.attr('data-show', 'true');
            getScheduling(start, end).done((doc, ) => {
                var events = [];
                $.each(doc.results, function(index, event) {
                    events.push({
                        id: event.id,
                        title: event.name,
                        start: event.start,
                        end: event.end,
                        color: event.color ? event.color : '#967ADC',
                        data: event,
                        type: event.scheduling_type.name,
                        incharge: event.users.map((user) => user.name).join(', '),
                        crm: event.crm ? event.crm.name : 'Ninguno'
                    });
                });
                callback(events);
            }).fail(function(error) {
                loading.attr('data-show', 'false');
                handleErrors(error);
            });

        },
        eventAfterAllRender: function(event, element) {
            var loading = $('.loading', '#agendaSection').first();
            loading.attr('data-show', 'false');
        },
        eventRender: function(event, element) {
            var serviceInfo = "";
            var description = "";
            console.log(event.data);
            if (event.data.crm_schedule_control && event.data.crm_schedule_control.length > 0) {
                serviceInfo = "<br><b>Servicio(s): </b>" + event.data.crm_schedule_control.map((data) => data.service.name).join(', ') + "<br>";
            }

            if (event.data.description) {
                description = "<br><br><b>Descripción: </b>" + event.data.description + "<br>";
            }

            let html = "<b>Evento:</b> " + event.title + "<br>\n\
                       <b>Tipo:</b> " + event.type + "\n\
                       " + serviceInfo + "\n\
                       <br><b>Responsable:</b> " + event.incharge + "<br>\n\
                       " + (event.crm ? "<b>Cliente:</b> " + event.crm : "") + "\n\
                       " + description;

            element.popover({
                html: true,
                title: event.data.start.split(' ')[1] + (event.data.end != null ? " - " + event.data.end.split(' ')[1] : ''),
                content: html,
                trigger: 'hover',
                placement: 'top',
                container: 'body'
            });
        },
        eventClick: function(info) {
            SCHEDULING = info.id;
            openForm(info.id);
        },
        timeFormat: 'hh:mm a',
        drop: function() {
            // is the "remove after drop" checkbox checked?
            //if ($('#drop-remove').is(':checked')) {
            // if so, remove the element from the "Draggable Events" list
            $(this).remove();
            //}
        },
        eventDrop: function(event) {
            console.log(event);
            moveEvent(event.id, event.title, event.start.unix(), event.end.unix());
        }
    });
    $(function() {
        $.contextMenu({
            selector: '.fc-event',
            items: {
                "edit": {
                    name: "Eliminar evento",
                    icon: "fa-trash-alt",
                    callback: function(key, options) {
                        $('#editService').modal('show');
                    },
                },
            }
        });
    });
}

function moveEvent(id, name, start, end) {
    Swal.fire({
        title: 'Confirmación necesaria',
        text: "¿Estás seguro que deseas mover " + name + "?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3faa6a',
        cancelButtonColor: 'rgba(0,0,0,0.51)',
        confirmButtonText: 'Seguro',
        cancelButtonText: 'Cancelar',
    }).then((result) => {
        if (result.value) {
            updateEventDate(id, start, end).done(function(response) {
                $("#agenda").fullCalendar('refetchEvents');
                swal(response.success ? 'Cambios Guardados' : 'Hubo un problema', response.message, response.success ? 'success' : 'warning');
            }).fail(function(error) {
                handleErrors(error);
                $("#agenda").fullCalendar('refetchEvents');
            });
        } else {
            $("#agenda").fullCalendar('refetchEvents');
        }
    });
}

function updateEventDate(id, start, end) {
    return $.ajax({
        url: ULR + "scheduling/update-date",
        method: "GET",
        data: { id: id, start: start, end: end },
        dataType: "json"
    });
}

//--- RENDERIZAR COLLAPSABLES

function initCollapsable() {
    $('a[data-action="collapse"]').on('click', function(e) {
        e.preventDefault();
        $(this).closest('.card').children('.card-content').collapse('toggle');
        $(this).closest('.card').find('[data-action="collapse"] i').toggleClass('ft-plus ft-minus');
    });
}



function success(data) {
    if (data.success) {
        var mdlForm = $('#mdlForm');
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        $("#agenda").fullCalendar('refetchEvents');
        swal("Correcto", data.message, "success");
        getNotifications();
    } else {
        swal("Error", data.message, "error");
    }

}

$('body').on('click', '[data-click=findInMap]', function(e) {
    var address = $("[name='address']").val();
    initMarkerMap(address, false);
});


//Escuchar evento cuando se realiza un cambio en el formulario de servicios
$('body').on('save-event', function(e, events) {
    $("#agenda").fullCalendar('refetchEvents');
});