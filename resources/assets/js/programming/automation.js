var dragged = [{
        title: 'Encarpado: Argos',
        start: '2019-07-01',
        color: '#2D95BF'
    },
    {
        title: 'Desparacitación: Software Web S.A.S.',
        start: '2019-07-07',
        end: '2019-07-10',
        color: '#48CFAE'
    },
    {
        id: 999,
        title: 'Desparacitatción: Indigoo S.A',
        start: '2019-07-09T16:00:00',
        color: '#48CFAE'
    },
    {
        id: 999,
        title: 'Desratización: Solla',
        start: '2019-07-16T16:00:00',
        color: '#FB6E52'
    },
    {
        title: 'Desinfección: Zenú',
        start: '2019-07-11',
        end: '2019-07-13',
        color: '#6C5B7B'
    },
    {
        title: 'Desinfección: Allux',
        start: '2019-07-12T10:30:00',
        end: '2019-07-12T12:30:00',
        color: '#6C5B7B'
    },
    {
        title: 'Desratización: Bimbo',
        start: '2019-07-12T12:00:00',
        color: '#DA4453'
    },
    {
        title: 'Desinfección: Solla',
        start: '2019-07-12T17:30:00',
        color: '#FB6E52'
    },
    {
        title: 'Encarpado: Grupo Exito',
        start: '2019-07-12T20:00:00',
        color: '#6C5B7B'
    },
    {
        title: 'Desratización: Cootrasana',
        start: '2019-07-13T07:00:00',
        color: '#FB6E52'
    }
];

$("body").ready(function() {
    $(".select2").select2({
        width: '100%',
        tags: true
    });
    var options = {
        now: "12:35", //hh:mm 24 hour format only, defaults to current time
        twentyFour: false, //Display 24 hour format, defaults to false
        upArrow: 'wickedpicker__controls__control-up', //The up arrow class selector to use, for custom CSS
        downArrow: 'wickedpicker__controls__control-down', //The down arrow class selector to use, for custom CSS
        close: 'wickedpicker__close', //The close class selector to use, for custom CSS
        hoverState: 'hover-state', //The hover state class to use, for custom CSS
        title: 'Selector de hora', //The Wickedpicker's title,
        showSeconds: false, //Whether or not to show seconds,
        timeSeparator: ' : ', // The string to put in between hours and minutes (and seconds)
        secondsInterval: 1, //Change interval for seconds, defaults to 1,
        minutesInterval: 1, //Change interval for minutes, defaults to 1
        beforeShow: null, //A function to be called before the Wickedpicker is shown
        afterShow: null, //A function to be called after the Wickedpicker is closed/hidden
        show: null, //A function to be called when the Wickedpicker is shown
        clearable: false, //Make the picker's input clearable (has clickable "x")
    };
    $('.timepicker').wickedpicker(options);
});






document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    let data = [{
            end: "2019-10-17",
            resourceId: "a",
            start: "2019-10-17",
            title: '<a class="fc-day-grid-event fc-h-event fc-event fc-not-start fc-end context-menu-one" style="background-color: #5ed850;"><div class="fc-content"><span class="fc-title" style="color: #fff;font-size: 1.5em;">Recoger Suministros<i class="la la-building style="font-size: 16px;position: absolute;left: 52px;top: 2px;"></i></span></div></a>',
        },
        {
            end: "2019-10-17",
            resourceId: "a",
            start: "2019-10-17",
            title: '<a class="fc-day-grid-event fc-h-event fc-event fc-not-start fc-end context-menu-one" style="background-color: ##1ab7ea;"><div class="fc-content"><span class="fc-title" style="color: #fff;font-size: 1.5em;">Solla<i class="custom-insect" style="font-size: 18px;position: absolute;left: 34px;top: 2px;"></i></span></div></a>',
        },
        {
            end: "2019-10-17",
            resourceId: "a",
            start: "2019-10-17",
            title: '<a class="fc-day-grid-event fc-h-event fc-event fc-not-start fc-end context-menu-one" style="background-color: #444444;"><div class="fc-content"><span class="fc-title" style="color: #fff;font-size: 1.5em;">Reunión<i class="la la-briefcase style="font-size: 16px;position: absolute;left: 52px;top: 2px;"></i></span></div></a>',
        },
        {
            end: "2019-10-17",
            resourceId: "a",
            start: "2019-10-17",
            title: '<a class="fc-day-grid-event fc-h-event fc-event fc-not-start fc-end context-menu-one" style="background-color: #1ab7ea;"><div class="fc-content"><span class="fc-title" style="color: #fff;font-size: 1.5em;">Ramo<i class="custom-mouse" style="font-size: 19px;position: absolute;left: 45px;top: 1px;"></i></span></div></a>',
        },
        {
            end: "2019-10-17",
            resourceId: "b",
            start: "2019-10-17",
            title: '<a class="fc-day-grid-event fc-h-event fc-event fc-not-start fc-end context-menu-one" style="background-color: #1ab7ea;"><div class="fc-content"><span class="fc-title" style="color: #fff;font-size: 1.5em;">Exito<i class="custom-wash" style="font-size: 17px;position: absolute;left: 39px;top: 3px;"></i></span></div></a>',
        },
        {
            end: "2019-10-17",
            resourceId: "b",
            start: "2019-10-17",
            title: '<a class="fc-day-grid-event fc-h-event fc-event fc-not-start fc-end context-menu-one" style="background-color: #1ab7ea;"><div class="fc-content"><span class="fc-title" style="color: #fff;font-size: 1.5em;">Noel<i class="custom-fix" style="font-size: 17px;position: absolute;left: 39px;top: 3px;"></i></span></div></a>',
        },
        {
            end: "2019-10-17",
            resourceId: "b",
            start: "2019-10-17",
            title: '<a class="fc-day-grid-event fc-h-event fc-event fc-not-start fc-end context-menu-one" style="background-color: #1ab7ea;"><div class="fc-content"><span class="fc-title" style="color: #fff;font-size: 1.5em;">Zenu<i class="custom-mouse" style="font-size: 17px;position: absolute;left: 39px;top: 3px;"></i></span></div></a>',
        },
        {


            resourceId: "a",
            start: "2019-10-17T07:00:00",
            end: "2019-10-17T07:00:00",
            title: '<a class="fc-day-grid-event fc-h-event fc-event fc-not-start fc-end context-menu-one" style="background-color: #5ed850;"><div class="fc-content"><span class="fc-title" style="color: #fff;font-size: 1.5em;">Recoger Suministros<i class="la la-building style="font-size: 16px;position: absolute;left: 52px;top: 2px;"></i></span></div></a>',
        },
        {


            resourceId: "a",
            start: "2019-10-17T08:00:00",
            end: "2019-10-17T08:00:00",
            title: '<a class="fc-day-grid-event fc-h-event fc-event fc-not-start fc-end context-menu-one" style="background-color: #cb2027;"><div class="fc-content"><span class="fc-title" style="color: #fff;font-size: 1.5em;">Traslado<i class="la la-automobile style="font-size: 16px;position: absolute;left: 52px;top: 2px;"></i></span></div></a>',
        },
        {


            resourceId: "a",
            start: "2019-10-17T09:00:00",
            end: "2019-10-17T09:00:00",
            title: '<a class="fc-day-grid-event fc-h-event fc-event fc-not-start fc-end context-menu-one" style="background-color: #1ab7ea;"><div class="fc-content"><span class="fc-title" style="color: #fff;font-size: 1.5em;">Ramo<i class="custom-mouse" style="font-size: 16px;position: absolute;left: 52px;top: 2px;"></i></span></div></a>',
        }

    ];

    var calendar = new FullCalendar.Calendar(calendarEl, {
        plugins: ['resourceTimeGrid', 'interaction', 'dayGrid'],
        editable: true,
        droppable: true, // will let it receive events!
        timeZone: 'local',
        locale: 'es',
        defaultView: 'resourceTimeGridFourDay',
        header: {
            left: 'prev,next',
            center: 'title',
            right: 'resourceTimeGridDay,resourceTimeGridFourDay'
        },
        views: {
            resourceTimeGridFourDay: {
                type: 'resourceTimeGrid',
                duration: { days: 4 },
                buttonText: '4 Dias'
            }
        },
        eventReceive: function(info) {
            console.log('event received!', info.event);
        },
        resources: [
            { id: 'a', title: 'Alexander Martinez' },
            { id: 'b', title: 'Jhon Doe' }
        ],
        events: data,
        eventRender: function(info) {
            //     console.log(info.event.end);

            $(info.el).html(info.event.title)
        }
    });

    calendar.render();
});


$(document).ready(() => {


    $('.repeater-default').repeater({
        show: function() {
            $(this).slideDown(500);
        },
        hide: function(remove) {
            $(this).slideUp(500);
        }
    });

    var areaGradientChart = new Chartist.Line('#areaGradient', {

        labels: [1, 2, 3, 4, 5, 6, 7],
        series: [
            [20, 180, 125, 40, 80, 50, 20],
            [75, 120, 50, 80, 130, 60, 120],
            [110, 50, 70, 20, 90, 150, 0]
        ]
    }, {
        lineSmooth: Chartist.Interpolation.simple({
            divisor: 2
        }),
        fullWidth: true,
        showArea: true,
        chartPadding: {
            right: 25
        },

        axisX: {
            showGrid: false
        },
        axisY: {
            scaleMinSpace: 40
        },
        plugins: [
            Chartist.plugins.tooltip({
                appendToBody: true,
                pointClass: 'ct-point-circle'
            })
        ],
        low: 0,
        onlyInteger: true,
    });


    areaGradientChart.on('draw', function(data) {
        var circleRadius = 9;
        if (data.type === 'point') {
            var circle = new Chartist.Svg('circle', {
                cx: data.x,
                cy: data.y,
                'ct:value': data.value.y,
                r: circleRadius,
                class: data.value.y === 180 || data.value.y === 150 ? 'ct-point-circle' : 'ct-point-circle-transperent'
            });
            data.element.replace(circle);
        }
        if (data.type === 'line' || data.type == 'area') {
            data.element.animate({
                d: {
                    begin: 1000,
                    dur: 1000,
                    from: data.path.clone().scale(1, 0).translate(0, data.chartRect.height()).stringify(),
                    to: data.path.clone().stringify(),
                    easing: Chartist.Svg.Easing.easeOutQuint
                }
            });
        }
    });

    /*=========================================================================================
        File Name: fullcalendar.js
        Description: Fullcalendar
        --------------------------------------------------------------------------------------
        Item Name: Chameleon Admin - Modern Bootstrap 4 WebApp & Dashboard HTML Template + UI Kit
        Version: 1.2
        Author: ThemeSelection
        Author URL: https://themeselection.com/
    ==========================================================================================*/




    /************************************************
     *               External Dragging               *
     ************************************************/

    /* initialize the calendar
    -----------------------------------------------------------------*/



    $('#fc-external-drag').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'agendaDay,agendaWeek,month,listWeek'
        },
        defaultView: 'agendaWeek',
        titleRangeSeparator: " al ",
        locale: 'es',
        editable: true,
        droppable: true, // this allows things to be dropped onto the calendar
        defaultDate: '2019-07-12',
        events: [

        ],
        drop: function() {
            $(this).remove();
        }
    });

    /* initialize the external events
    -----------------------------------------------------------------*/

    $('#external-events .fc-event').each(function() {

        // Different colors for events
        $(this).css({ 'backgroundColor': $(this).data('color'), 'borderColor': $(this).data('color') });

        // store data so the calendar knows to render an event upon drop
        $(this).data('event', {
            title: $.trim($(this).text()), // use the element's text as the event title
            color: $(this).data('color'),
            stick: true // maintain when user navigates (see docs on the renderEvent method)
        });

        // make the event draggable using jQuery UI
        $(this).draggable({
            zIndex: 999,
            revert: true, // will cause the event to go back to its
            revertDuration: 0 //  original position after the drag
        });

    });


});


$(".steps-validation").steps({
    headerTag: "h6",
    bodyTag: "fieldset",
    transitionEffect: 1,
    transitionEffectSpeed: 300,
    titleTemplate: '<span class="step">#index#</span> #title#',
    labels: {
        finish: 'Finalizar',
        previous: 'Anterior',
        next: 'Siguiente'

    },
    onFinished: function(event, currentIndex) {
        if (createPDF) window.open(ULR + '/images/Reporte_pdf.pdf', '_blank');
    },
    onStepChanged: (event, currentIndex, priorIndex) => {
        if (currentIndex == 2) {
            // $('.steps-validation > .actions').css('visibility', 'hidden');
        }

    },
    onStepChanging: function(event, currentIndex, newIndex) {
        return true;

        //     // Allways allow previous action even if the current form is not valid!
        //     if (currentIndex > newIndex)
        //     {
        //         return true;
        //     }
        //     // Forbid next action on "Warning" step if the user is to young
        //     if (newIndex === 3 && Number($("#age-2").val()) < 18)
        //     {
        //         return false;
        //     }
        //     // Needed in some cases if the user went back (clean up)
        //     if (currentIndex < newIndex)
        //     {
        //         // To remove error styles
        //         form.find(".body:eq(" + newIndex + ") label.error").remove();
        //         form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
        //     }
        //     form.validate().settings.ignore = ":disabled,:hidden";
        //     return form.valid();
        // },
        // onFinishing: function (event, currentIndex)
        // {
        //     form.validate().settings.ignore = ":disabled";
        //     return form.valid();
        // },
        // onFinished: function (event, currentIndex)
        // {
        //     alert("Submitted!");
    }
});

// Initialize validation
// $(".steps-validation").validate({
//     ignore: 'input[type=hidden]', // ignore hidden fields
//     errorClass: 'danger',
//     successClass: 'success',
//     highlight: function(element, errorClass) {
//         $(element).removeClass(errorClass);
//     },
//     unhighlight: function(element, errorClass) {
//         $(element).removeClass(errorClass);
//     },
//     errorPlacement: function(error, element) {
//         error.insertAfter(element);
//     },
//     rules: {
//         email: {
//             email: true
//         }
//     }
// });


//Evento datepicker
// Date Range from & to
var from_$input = $('#picker_from').pickadate({
        formatSubmit: 'yyyy-mm-dd',
        hiddenName: true,
        container: '.app-content',
        min: new Date(),
        firstDay: 1,
        monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
        today: 'Hoy',
        clear: 'Limpiar',
        close: 'Cerrar'
    }),
    from_picker = from_$input.pickadate('picker');

var to_$input = $('#picker_to').pickadate({
        formatSubmit: 'yyyy-mm-dd',
        hiddenName: true,
        container: '.app-content',
        min: new Date(),
        firstDay: 1,
        monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
        today: 'Hoy',
        clear: 'Limpiar',
        close: 'Cerrar'
    }),
    to_picker = to_$input.pickadate('picker');


// Check if there’s a “from” or “to” date to start with.
if (from_picker.get('value')) {
    to_picker.set('min', from_picker.get('select'));
}
if (to_picker.get('value')) {
    from_picker.set('max', to_picker.get('select'));
}

// When something is selected, update the “from” and “to” limits.
from_picker.on('set', function(event) {
    if (event.select) {
        to_picker.set('min', from_picker.get('select'));
    } else if ('clear' in event) {
        to_picker.set('min', false);
    }
});
to_picker.on('set', function(event) {
    if (event.select) {
        from_picker.set('max', to_picker.get('select'));
    } else if ('clear' in event) {
        from_picker.set('max', false);
    }
});

// Date range to select
var $input = $('.pickatime-limits').pickatime();
var picker = $input.pickatime('picker')


$('.pickatime-minmax').pickatime({
    min: [0, 00],
    max: [23, 00],
    onSet: function(context) {
        let value = this.get('select');
        picker.set('min', [value.hour, value.mins]);
    }
});



function reloadCalendar() {
    console.log("reloading");
    dragged.forEach((value, index) => {
        $('#fc-external-drag').fullCalendar('renderEvent', value, true);
    });

}

$(function() {
    $.contextMenu({
        selector: '.context-menu-one',
        items: {
            "edit": {
                name: "Editar servicio",
                icon: "edit",
                callback: function(key, options) {
                    $('#editService').modal('show');
                },
            },
            // "cut": {
            //     name: "Preferencias de técnico", 
            //     icon: "cut",
            //     callback: function(key, options) {
            //         $('#edittechnical').modal('show');
            //     },
            // },
            //    copy: {name: "Copy", icon: "copy"},
            //     "paste": {name: "Paste", icon: "paste"},
            //     "delete": {name: "Delete", icon: "delete"},
            //     "sep1": "---------",
            //     "quit": {name: "Quit", icon: function(){
            //         return 'context-menu-icon context-menu-icon-quit';
            //     }}
        }
    });

    $('.context-menu-one').on('click', function(e) {
        console.log('clicked', this);
    })
});

$(function() {
    $.contextMenu({
        selector: '.context-menu-one2',
        items: {
            "edit": {
                name: "Preferencias de técnico",
                icon: "edit",
                callback: function(key, options) {
                    $('#edittechnical').modal('show');
                },
            },
            // "cut": {name: "Preferencias de técnico", icon: "cut"},
            //    copy: {name: "Copy", icon: "copy"},
            //     "paste": {name: "Paste", icon: "paste"},
            //     "delete": {name: "Delete", icon: "delete"},
            //     "sep1": "---------",
            //     "quit": {name: "Quit", icon: function(){
            //         return 'context-menu-icon context-menu-icon-quit';
            //     }}
        }
    });

    $('.context-menu-one').on('click', function(e) {
        console.log('clicked', this);
    })
});