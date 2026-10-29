TIMELINE = null;
var MOVE_EVENTS = {};
var REMOVE_EVENTS = {};

$('body').on('click', '.page-link', function(e) {
    e.preventDefault();
    var page = $(this).attr('href').split('page=')[1];
    return $.ajax({
        url: ULR + "scheduling/routes",
        method: "GET",
        dataType: "json",
        data: {page:page, search: $("input[type='search']").val(), perpage: $("[name='tblList_length']").val(), date: $('input#datetable').val()},
        success: function(data){
            $('#list').html(data.view);
        }
    });
    
});

$('body').on('click', '[data-click="editRoute"]', function(e) {
    e.preventDefault();
    var mdlForm = $('#mdlInitLargueForm');
    var id = $(this).data('id');
    editRouteForm(id).done(function(data) {
        if (data.success) {
            $('.modal-content', mdlForm).html(data.view);
            mdlForm.modal('show');
            initLineTimeVisualizator(data.events);
            initSelectStatus();
        }
    }).fail(function(error) {
        handleErrors(error);
    });

});

function delay(callback, ms) {
    var timer = 0;
    return function() {
      var context = this, args = arguments;
      clearTimeout(timer);
      timer = setTimeout(function () {
        callback.apply(context, args);
      }, ms || 0);
    };
  }

$('body').on('keyup', "input[type='search']", delay(function(e) {
    return $.ajax({
        url: ULR + "scheduling/routes",
        method: "GET",
        dataType: "json",
        data: {search: this.value, perpage: $("[name='tblList_length']").val(), date: $('input#datetable').val()},
        success: function(data){
            $('#list').html(data.view);
        }
    });

},300));

$('body').on('search', "input[type='search']", function(e) {
    return $.ajax({
        url: ULR + "scheduling/routes",
        method: "GET",
        dataType: "json",
        data: {search: this.value, perpage:  $("[name='tblList_length']").val(), date: $('input#datetable').val()},
        success: function(data){
            $('#list').html(data.view);
        }
    });
});

$('body').on('change', "input#datetable", function(e) {
    return $.ajax({
        url: ULR + "scheduling/routes",
        method: "GET",
        dataType: "json",
        data: {date: this.value, perpage: $("[name='tblList_length']").val(), search: $("input[type='search']").val()},
        success: function(data){
            $('#list').html(data.view);
        }
    });
});


$('body').on('change', "[name='tblList_length']", function(e) {
    return $.ajax({
        url: ULR + "scheduling/routes",
        method: "GET",
        dataType: "json",
        data: {perpage: $("[name='tblList_length']").val(), date: $('input#datetable').val(), search: $("input[type='search']").val()},
        success: function(data){
            $('#list').html(data.view);
        }
    });
});


function initLineTimeVisualizator(events) {
    // DOM element where the Timeline will be attached
    var container = document.getElementById('visualization');

    // Create a DataSet (allows two way data-binding)
    var items = new vis.DataSet(events.data);

    // Configuration for the Timeline
    var options = {
        min: events.start,
        max: events.end,
        editable: {
            add: true, // add new items by double tapping
            updateTime: true, // drag items horizontally
            updateGroup: true, // drag items from one group to another
            remove: true // delete an item by tapping the delete button top right
        },
        selectable: true, //
        onMove: function(item, callback) {
            MOVE_EVENTS["id-" + item.id] = item;
            console.log('move', item);
            callback(item);
        },
        onRemove: function(item, callback) {
            REMOVE_EVENTS["id-" + item.id] = item;
            console.log('remove', item);
            callback(item);
        }

    };

    // Create a Timeline
    TIMELINE = new vis.Timeline(container, items, options).on('select', onSelect);
}

function onSelect(data) {
    if (data.items.length > 0) {
        $(".btn-edit").data('id', data.items[0]).show();
    } else {
        $(".btn-edit").data('id', data.items[0]).hide();
    }
}


function destroyTimeLine() {
    TIMELINE.destroy();
}

function editRouteForm(id) {
    return $.ajax({
        url: ULR + 'scheduling/route/' + id + '/editForm',
        method: 'GET',
        dataType: 'json'

    });
}

$('body').on('click', "[data-click='sendRoute']", function(e) {

    e.preventDefault();
    var mdlForm = $('#mdlForm');
    var route_id = $(this).data('route');


    getSendRouteForm(route_id).done((data) => {
        $('.modal-content', mdlForm).html(data.view);
        mdlForm.modal('show');
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

function getSendRouteForm(route_id) {
    return $.ajax({
        url: ULR + 'scheduling/route/' + route_id + '/send-form',
        dataType: 'json',
        method: 'GET'

    });
}

$('body').on('click', '[data-click="send-route"]', function(e) {
    e.preventDefault();
    var mdlForm = $('#mdlForm');
    sendRoute().done(function(data) {
        if (data.success) {
            mdlForm.modal('hide');
            $("input[type='search']").trigger('search');
        }
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }).fail(function(error) {
        handleErrors(error);
    });

});

function sendRoute() {
    var body = CKEDITOR.instances.ckeditor.getData();
    $("[name='body']").val(body);
    var form = $('#sendRoute');
    if (form) {
        var data = form.serialize();
        return $.ajax({
            url: ULR + "scheduling/route/" + $('[name="route_id"]').val() + "/route-send",
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


$('body').on('click', '[data-click="removeRoute"]', function(e) {
    e.preventDefault();
    var id = $(this).data('id');

    Swal.fire({
        title: 'Eliminar esta ruta',
        html: "¿Estas seguro que deseas eliminar esta ruta?<br></br> Ten presente que esto eliminará los eventos de la agenda en relación a la ruta y los servicios quedarán nuevamente pendiente por programación. Esta acción es <b style='font-weight: 900'>irreversible.</b>",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e91e4d',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Si, Eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        console.log(result);
        if (result.value) {
            removeRoute(id).done(function(data) {

                swal(data.success ? 'Cambios Guardados' : 'Hubo un problema', data.message, data.success ? 'success' : 'warning');
                if (data.success) {
                    $("input[type='search']").trigger('search');
                }

            }).fail(function(error) {
                handleErrors(error);
            });
        }
    });
});

function removeRoute(id) {
    return $.ajax({
        url: ULR + 'scheduling/route/' + id + '/delete',
        method: 'GET',
        dataType: "json"
    });
}


$('body').on('click', '[data-click="openEventForm"]', function(e) {

    e.preventDefault();
    var route = $(this).data('route');
    var id = $(this).data('id');
    var user = $(this).data('user');
    var start = $('[name="date"]').val();
    openEventForm(route, id, user, start);
});

function openEventForm(route, id, user = null, start = null) {
    //abrir el modal y mostrar loading

    SET_DAFAULT_TIME = true;
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'true');
    var mdlForm = $('#mdlForm');


    //leer el formulario de eventos
    loadEventForm(route, id, user, start).done(function(data) {
        loading.attr('data-show', 'false');
        $('.modal-content', mdlForm).html(data.view);

        /* $('.icheck_minimal input').iCheck({
            checkboxClass: 'icheckbox_minimal',
            radioClass: 'iradio_minimal',
        }); */

        mdlForm.modal('show');

        //añadir el campo de ruta
        $("form#scheduling").prepend($("<input/>", {
            type: 'hidden',
            name: 'route',
            value: route
        }));

        //inicializar funciones de plugins
        initSelectScheduling(data.edit);
        initSelectUsers();
        initSelectCrms();

        CRM_SCHEDULE_ID = null;


        if (($("[name='lat']").val() != '' && $("[name='lat']").val() != undefined) && ($("[name='lng']").val() != '') && $("[name='lng']").val() != undefined) {
            var coords = { lat: $("[name='lat']").val(), lng: $("[name='lng']").val() }
            console.log("comenzando desde las cooredenadas");
            initMarkerMap('Colombia', coords);
        } else {
            if ($("[name='lat']").val() != undefined) {
                initSimpleMap();
            }
        }

        initTimePickersPlugin();


        if (data.edit) {
            //getTypeSubform($("[name='type']").val(), data.edit);
        }
    }).fail(function(error) {
        handleErrors(error);
    });
}

function loadEventForm(route, id, user = null, start = null) {
    return $.ajax({
        url: ULR + "scheduling/form",
        method: "GET",
        data: { route: route, id: id, params: { user_id: user, start: start } },
        dataType: "json",
    });
}


$('body').on('click', '[data-click=findInMap]', function(e) {
    var address = $("[name='address']").val();
    initMarkerMap(address, false);
});

$('body').on('save-event', function(e, events) {
    destroyTimeLine();
    console.log(events.events);
    initLineTimeVisualizator(events.events);
    CRM_SCHEDULE_ID = null;
});

$('body').on('click', '[data-click="edit-route"]', function(e) {
    e.preventDefault();
    var id = $(this).data('id');
    var mdlForm = $('#mdlInitLargueForm');
    editRoute(id).done(function(data) {
        if (data.success) {
            mdlForm.modal('hide');
            $("input[type='search']").trigger('search');
        }
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }).fail(function(error) {
        handleErrors(error);
    });
});


function editRoute(id) {

    var form = $('#editRoute');

    if (form) {
        $(form).prepend($('<textarea/>', {
            "class": "display-hidden",
            "name": "remove_events",
            "text": JSON.stringify(REMOVE_EVENTS)
        }));


        $(form).prepend($('<textarea/>', {
            "class": "display-hidden",
            "name": "move_events",
            "text": JSON.stringify(MOVE_EVENTS)
        }));

        var data = form.serialize();
        return $.ajax({
            url: ULR + 'scheduling/route/' + id + '/edit',
            method: $('[name="_method"]', form).val(),
            data: data,
            dataType: "json"
        });
    }
}


function initSelectStatus() {
    $('[name="status"]').select2({
        width: '100%',
        allowClear: true,
        closeOnSelect: false,
        placeholder: 'Selecciona un estado',
        ajax: {
            url: ULR + "scheduling/statuses",
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