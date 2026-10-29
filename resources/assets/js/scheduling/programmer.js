
CALENDAR = null;
//no usar zoom -10% para no dañar el select de fullcalendar
document.body.style.zoom = "100%";

$("body").ready(function() {
    $('body').append("<button type='button' data-click='openForm' style='display:none;'></button>");
    getProgrammer();
    //initFullCalendar4();

});

function getProgrammer() {
    getProgrammerAjax().done(function(response){
        initFullCalendar4(response.resources, response.date)
    });
}

function getProgrammerAjax(){
    return $.ajax({
        method: "GET",
        dataType: "json",
        url: ULR + 'scheduling/programmer/resources'
    });
}

function getProgrammerData(date, end) {

    var form = $('#sendProgrammerFilter');
    var data = form.serialize() + "&start=" + moment(date.start).unix() + "&end=" + moment(date.end).unix();
    return $.ajax({
        url: ULR + "scheduling/programmer/data",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });
}

function initFullCalendar4(resources, date) {
    if (CALENDAR != null) CALENDAR.destroy();
    var calendarEl = document.getElementById('programmer');

    CALENDAR = new FullCalendar.Calendar(calendarEl, {
        plugins: ['resourceTimeGrid', 'interaction'],
        schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
        defaultView: 'resourceTimeGridDay',
        defaultDate: date,
        resources: resources,
        resourceOrder: 'order',
        customButtons: {
            myCustomButton: {
                text: 'Seleccionar fecha',
                click: function () {
                    //it scrolls to the position of the datepicker
                    var $input = $('<input type="hidden">').pickadate({
                        formatSubmit: 'dd/mm/yyyy',
                        container: '.app-content',
                        firstDay: 1,
                        monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                        monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                        weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
                        today: 'Hoy',
                        clear: 'Limpiar',
                        close: 'Cerrar'
                    });
                    var picker = $input.pickadate('picker');
                  
                    event.stopPropagation()
                    event.preventDefault()
                    picker.open();
                    picker.on({
                        close: function(){
                            CALENDAR.gotoDate(picker.get('select', 'yyyy-mm-dd'));
                        }
                    })
                }
            }
        },
        header: {
            right: 'myCustomButton prev,next today',
            left: 'title'
        },
        events: function(start, callback){
            getProgrammerData(start).done((doc, ) => {
                callback(doc.data);
            });
        },
        buttonText: {
            today:    'Hoy',
            month:    'Mes',
            week:     'Semana',
            day:      'Día',
            list:     'lista'
          },
        slotDuration: '00:30:00',
        slotLabelInterval: 30,
        minTime: '05:00:00',
        maxTime: '23:59:59',
        droppable: true,
        durationEditable: true, 
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
        contentHeight: 650,
        resourceRender: function(render) {

        },
        eventDrop: function(event) {
            console.log(event);
            if (event.newResource !== null) {
                moveEventProgrammer(event.event.id, event.event.title, event.newResource.id, moment(event.event.start).format('YYYY-MM-DD HH:mm:ss'), moment(event.event.end).format('YYYY-MM-DD HH:mm:ss'), event.event.extendedProps.type);
            } else {
                moveEventProgrammer(event.event.id, event.event.title, null, moment(event.event.start).format('YYYY-MM-DD HH:mm:ss'), moment(event.event.end).format('YYYY-MM-DD HH:mm:ss'), event.event.extendedProps.type);
            }

        },
        eventRender: function(event, element) {
            element = $(event.el);
            element.addClass('hasmenu');
            if(event.event.extendedProps.route != null){
                element.addClass('hasroute');
            }
            element.attr('data-type', event.event.extendedProps.type);
            element.attr('data-id', event.event.id);
            element.attr('data-crm', event.event.extendedProps.crm_id);
            element.attr('data-office', event.event.extendedProps.office_id);
            element.attr('data-user', event.event.extendedProps.user_id);
            element.attr('data-date', moment(event.event.start).format('YYYY-MM-DD'));
            element.attr('data-route',event.event.extendedProps.route);
            element.find(".fc-time").append("&nbsp;<i class='" + event.event.extendedProps.icon + "' style='height: 14px;'></i>&nbsp;<span style='line-height: 13px;'>" +(event.event.extendedProps.customer  ? event.event.extendedProps.customer : '') +' '+(event.event.extendedProps.office ? event.event.extendedProps.office : '')+"</span>");
            //element.attr('data-click', 'openForm');
            //element.attr('data-id', event.event.id);

            var table = "<div>";

                table += "  <div class='row'><div class='col-md-12'></div>&nbsp;</div>";
                table += "  <div class='row'>";
                table += "          <div class='col-md-3 '><b>Actividad</b></div>";
                table += "          <div class='col-md-9 '>" + event.event.title + "</div>";

                table += "          <div class='col-md-3 '><b>Tipo:</b></div>";
                table += "          <div class='col-md-9 '>" + (event.event.extendedProps.type === 'App\\CrmSchedule' ? 'Servicio de un cronograma'  : event.event.extendedProps.second_type) + "</div>";

                if(event.event.extendedProps.customer != null){
                    table += "          <div class='col-md-3 '><b>Cliente</b></div>";
                    table += "          <div class='col-md-9 '>" + event.event.extendedProps.customer + "</div>";
                }

                if(event.event.extendedProps.office != null){
                    table += "          <div class='col-md-3 '><b>Sucursal</b></div>";
                    table += "          <div class='col-md-9 '>" + event.event.extendedProps.office + "</div>";
                }
                
                if(event.event.extendedProps.observation){
                    table += "      <div class='col-md-3 '><b>Detalle</b></div>";
                table += "          <div class='col-md-9 '>" + (event.event.extendedProps.observation  != null ? event.event.extendedProps.observation : 'Ninguna') + "</div>";
                }
                
                
                table += "  </div>";
                

            var html = $(table).html();
                var close = $('<button><span aria-hidden="true" class="close">&times;</span></button>');


                element.popover({
                        class: 'controlPopover',
                        html: true,
                        sanitize: false,
                        title: moment(event.event.start).format('hh:mm a') + ' - ' +moment(event.event.end).format('hh:mm a'),
                        content: html,
                        trigger: 'hover',
                        placement: 'top',
                        container: 'body'
                    }).on('show.bs.popover', function() {
                        if (window._bsPopover) {
                            $(window._bsPopover).popover('hide');
                        }
                        window._bsPopover = this;
                    })
                    .on('hide.bs.popover', function() {
                        window._bsPopover = null; // see Peter Jacoby's comment
                    });

        },
        eventResize: function(event) {
            if (event.newResource !== null) {
                moveEventProgrammer(event.event.id, event.event.title, null, moment(event.event.start).format('YYYY-MM-DD HH:mm:ss'), moment(event.event.end).format('YYYY-MM-DD HH:mm:ss'), event.event.extendedProps.type);
            } else {
                moveEventProgrammer(event.event.id, event.event.title, null, moment(event.event.start).format('YYYY-MM-DD HH:mm:ss'), moment(event.event.end).format('YYYY-MM-DD HH:mm:ss'), event.event.extendedProps.type);
            }
        },
        select: function(event) {
            
             var params = { user_id: event.resource.id, start: moment(event.start).format('YYYY-MM-DD HH:mm:ss'), origin: 'programmer' };
             $('[data-click="openForm"]').trigger('click', params);
        },
        eventClick: function(event) {
            $("[name='crm_id_global']").val(event.event.extendedProps.crm_id);

            var params = { id: event.event.id, type: event.event.extendedProps.type, origin: 'programmer',crm :  event.event.extendedProps.crm_id};
             $('[data-click="openForm"]').trigger('click', params);
        }

    });

    CALENDAR.render();
    setTimeout(() => {

        $('.fc-resourceTimeGridDay-view').css("width", function(index) {
            var calc = resources.length * 14;
            if (calc < 100) return '100%';
            else return calc + '%';
        });
    }, 400);
    
}

$('body').on('scroll','.fc-view-container', function () {
    alert('3');
    $('.fc-axis.fc-time.fc-widget-content').css('left', $('.fc-view-container').scrollLeft());
   });

$('body').on('click','.fc-axis', function(){
    $('.fc-axis-selected').removeClass('fc-axis-selected');
    $(this).parent().addClass('fc-axis-selected');
});

$('body').on('save-event', function(){
    CALENDAR.refetchEvents();
});

$(function() {
    $.contextMenu({
        selector: '.hasmenu', 
        callback: function(key, options) {
            switch(key){
                case 'crmScheduling':
                    if(this[0].dataset.type != 'App\\CrmSchedule'){
                        swal('Accion no disponible','Este evento no pertenece a ningún cronograma');
                    }else{
                        Object.assign(document.createElement('a'), {
                            href: ULR + 'crm/'+this[0].dataset.crm+'/office/'+this[0].dataset.office+'?date='+this[0].dataset.date+'#tabSchedule',
                            target: '_blank',
                        }).click();
                    }
                    break;
                case 'execution':
                    Object.assign(document.createElement('a'), {
                        href: ULR + 'scheduling/execution?date='+this[0].dataset.date+'&user='+this[0].dataset.user,
                        target: '_blank',
                    }).click();
                    break;
                case 'pdf':
                    Object.assign(document.createElement('a'), {
                        href: ULR + 'scheduling/route/'+this[0].dataset.route+'/show',
                        target: '_blank',
                    }).click();
                    break;
                case 'quit':
                    Swal.fire({
                        title: '¿Seguro que deseas eliminar el evento '+this[0].text +'?',
                        html: "Ten presente que esto es una acción irreversible y no será posible reestablecerlo nuevamente.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#fda401',
                        cancelButtonColor: '#aaa',
                        confirmButtonText: 'Si, Continuar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        console.log(result);
                        if (result.value) {
                            deleteEvent(this[0].dataset).done(function(data) {
                                swal.close();
                                CALENDAR.refetchEvents();
                                swal(data.success ? 'Correcto' : "Error", data.message, data.success ? "success" : "error");
                            }).fail(function(error) {
                                handleErrors(error);
                            });
                        }
                    });
                    break;
                    
            }
            
        },
        items: {
            "crmScheduling": {name: "Ver en el cronograma", icon: "paste"},
            "execution": {name: "Ver ejecución de ruta", icon: "paste"},
            "pdf": {name: "Ver ruta en pdf", icon: "paste"},
            "sep1": "---------",
            "quit": {name: "Eliminar servicio/evento", icon: function(){
                return 'context-menu-icon context-menu-icon-quit';
            }}
        }
    });
});

function deleteEvent(dataset) {
    return $.ajax({
        url: ULR + 'scheduling/deleteFromProgrammer',
        method: 'GET',
        data: { id: dataset.id, type: dataset.type },
        dataType: 'json'
    });
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

                CALENDAR.refetchEvents();
                var mdlForm = $('#mdlForm');
                mdlForm.modal('hide');

            }).fail(function(error) {
                handleErrors(error);
            });
        }
    });
});

function moveEventProgrammer(id, name, user_id, start, end, type = null) {

    updateEventDateR(id, user_id, start, end, type).done(function(response) {
        if (!response.success) {
            swal(response.success ? 'Cambios Guardados' : 'Hubo un problema', response.message, response.success ? 'success' : 'warning');
            $('[data-action="reload"]').trigger('click');
        }

    }).fail(function(error) {
        handleErrors(error);
        $('[data-action="reload"]').trigger('click');
    });
}

function updateEventDateR(id, user,start, end, type) {
    return $.ajax({
        url: ULR + "scheduling/update-date-r",
        method: "GET",
        data: { id: id, user:user, start: start, end: end, type: type },
        dataType: "json"
    });
}


$('body').on('click', '[data-action="reload"]', function() {
    CALENDAR.refetchEvents();
});
