SERVICE = null;
SET_DAFAULT_TIME = true;
CRM_ID = null;
SERVICE_ID = null;
SCHEDULING = null;
CRM_SCHEDULE_ID = null;

//importar todas las funcionalidades de cronogramas
(async function() {
    await $.getScript(ULR + "assets/js/crm/show/schedule_functions.js?v="+APP_VERSION);
})();

$('body').on('click', '[data-click="openForm"]', function(e, params = null) {

    e.preventDefault();
    var id = SCHEDULING = $(this).data('id');
    CRM_SCHEDULE_ID = null;
    if (id == null || id == undefined || id == '') {
        //intentar buscar el id en los params
        if (params !== null && params.id != undefined) {
            id = params.id;
        }
    }
    var enabled = $(this).data('enabled') !== undefined ? $(this).data('enabled') : 1;
    var automationdate = $(this).data('automation_date') ? $(this).data('automation_date') : 0;
    if (automationdate == "1") {
        var capture_date = $('[name="init_date_submit"]').val();
        console.log(capture_date);
    } else {
        var capture_date = false;
    }
    openForm(id, enabled, capture_date, params);
});

$('body').on('click', '[data-click="covertSpaces"]', function(e, params = null) {

    e.preventDefault();
    var automationdate = $(this).data('automation_date') ? $(this).data('automation_date') : 0;
    if (automationdate == "1") {
        var capture_date = $('[name="init_date_submit"]').val();
        console.log(capture_date);
    } else {
        var capture_date = false;
    }

    Swal.fire({
        title: '¿Agregar traslados ahora?',
        text: "Si agregas los traslados ahora, no podrás reversar esta acción a menos que comiences la automatización nuevamente.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#464855',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Si, agregar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        console.log(result);
        if (result.value) {
            covertSpaces(capture_date).done(function(response){
                $('body').trigger('save-event', {});
                console.log(response);
            });
        }

    });

    
    
});

function covertSpaces(capture_date) {
    return $.ajax({
        url: ULR + "scheduling/automation/covert-white-spaces",
        method: "GET",
        data: {init_date_submit: capture_date},
        dataType: "json",
    });
}

$('body').on('click', '[data-click="save"]', function(e) {

    saveForm();
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

//Seleccionar solo Técnicos con checkbox

$('body').on('click', '[name="technical"]', function(e) {

    if ($(this).is(':checked')) {

        getListTechnicals().done(function(data) {
            data.results.forEach(function(technical) {
                var $newOption = $("<option selected='selected'></option>").val(technical.id).text(technical.name);
                $('[name="users[]"]').append($newOption).trigger('change');
            });
        });

    } else {
        $("[name='users[]'] option:selected").remove();
    }
});

function getListTechnicals() {

    return $.ajax({
        url: ULR + "scheduling/technicals",
        method: "GET",
        dataType: "json",
    });
}

function openForm(id, enabled = 1, capture_date = false, params = null) {
    //abrir el modal y mostrar loading

    SET_DAFAULT_TIME = true;
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'true');
    var mdlForm = $('#mdlForm');


    //leer el formulario de eventos
    if(params && params.type === 'App\\CrmSchedule'){
        var obj  = Object.assign(document.createElement('a'), {
            href: id,
            target: '_blank'
        });

        obj.setAttribute('class', 'scheduleEdit');
        $("body").append(obj);
        $(obj).trigger('click', params);


        
    }else{
        loadForm(id, enabled, capture_date, params).done(function(data) {
            loading.attr('data-show', 'false');
            $('.modal-content', mdlForm).html(data.view);

            if(params && params.origin === 'programmer'){
                $('form#scheduling').prepend('<input type="hidden" name="origin" value="programmer"/>');
            }
    
            /* $('.icheck_minimal input').iCheck({
                checkboxClass: 'icheckbox_minimal',
                radioClass: 'iradio_minimal',
            }); */
    
            mdlForm.modal('show');
    
            //inicializar funciones de plugins
            initSelectScheduling(data.edit);
            initSelectUsers();
            initSelectCrms();
            initTimePickersPlugin();
    
            if (document.getElementById("map") != null) {
                if (($("[name='lat']").val() != '' && $("[name='lat']").val() != undefined) && ($("[name='lng']").val() != '') && $("[name='lng']").val() != undefined) {
                    var coords = { lat: $("[name='lat']").val(), lng: $("[name='lng']").val() }
                    console.log("comenzando desde las cooredenadas");
                    initMarkerMap('', coords);
                } else {
    
                    if (data.edit) {
                        console.log("comenzando desde current position");
                        initSimpleMap();
                    }
    
                }
            }
            if (data.edit) {
                //getTypeSubform($("[name='type']").val(), data.edit);
            }
        }).fail(function(error) {
            handleErrors(error);
        });
    }
    
}

function getTypeSubform(type, edit = false, ) {
    var mdlForm = $('#mdlForm');
    mdlForm.modal('show');
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'true');
    getSchedulingSubForm(type).done(function(data) {
        $('#mdlForm .modal-dialog').removeClass('modal-xl');
        $('#mdlForm .modal-dialog').removeClass('modal-md');
        $('#mdlForm .modal-dialog').removeClass('modal-sm');
        $('#mdlForm .modal-dialog').addClass('modal-' + data.modalSize);
        loading.attr('data-show', 'false');
        $('.Typeframe').html(data.view);

        //initSelectServices();
        if (edit) {
            CRM_ID = $('[name="crm_id"]').val();
            getTabProccess($('[name="services_id[]"]').val(), edit);
        }
        initSelectCrms();
    }).fail(function(error) {
        loading.attr('data-show', 'false');
        handleErrors(error);
    });
}

function getTypeFrame(type, edit = false, start = false, end = false) {

    getTypeFrameAjax(type, start, end).done(function(data) {

        $('.event-type-div').html(data.view);

        //initSelectServices();
        if (edit) {
            CRM_ID = $('[name="crm_id"]').val();
        }

        setTimeout(function() {
            if ($("[name='lat']").val() != undefined) {
                if (($("[name='lat']").val() != '' && $("[name='lat']").val() != undefined) && ($("[name='lng']").val() != '') && $("[name='lng']").val() != undefined) {
                    var coords = { lat: $("[name='lat']").val(), lng: $("[name='lng']").val() }
                    console.log("comenzando desde las cooredenadas");
                    initMarkerMap('Colombia', coords);
                } else {
                    initSimpleMap();
                }
            }
        }, 1000)



        initTimePickersPlugin();
    }).fail(function(error) {
        handleErrors(error);
    });
}

function loadForm(id, enabled = 1, capture_date = false, params = null) {
    return $.ajax({
        url: ULR + "scheduling/form",
        method: "GET",
        data: { id: id, enabled: enabled, capture_date: capture_date, params: params },
        dataType: "json",
    });
}

function getSchedulingSubForm(type) {
    return $.ajax({
        url: ULR + "scheduling/subform",
        method: "GET",
        data: { type: type, scheduling_id: SCHEDULING, description : $("[name='description']").val() },
        dataType: "json",
    });
}

function getTypeFrameAjax(type) {
    return $.ajax({
        url: ULR + "scheduling/type/" + type + "/typeframe",
        method: "GET",
        data: { 
            type: type, 
            scheduling_id: SCHEDULING, 
            start: $('[name="start_submit"]').val(), 
            end: $('[name="end_submit"]').val(),
            start_hour:  $('[name="time_start"]').val(), 
            description: $('[name="description"]').val(),
        },
        dataType: "json",
    });
}

function getTabServices(services) {
    return $.ajax({
        url: ULR + "scheduling/tab-services",
        method: "GET",
        data: { scheduling_id: SCHEDULING, services: services },
        dataType: "json",
    });
}

function getAreaInfo(area) {
    return $.ajax({
        url: ULR + "scheduling/area-info",
        method: "GET",
        data: { area: area },
        dataType: "json",
    });
}

function getProductInfo(product) {
    return $.ajax({
        url: ULR + "scheduling/product-info",
        method: "GET",
        data: { product: product },
        dataType: "json",
    });
}

function saveForm() {

    var form = $('#scheduling')[0];
    var formData = new FormData(form);

    if (CRM_SCHEDULE_ID != null) {
        formData.append('crm_schedule_id', CRM_SCHEDULE_ID);
    }

    return $.ajax({
        url: ULR + $(form).attr('action'),
        contentType: false,
        processData: false,
        headers: {
            'X-CSRF-Token': $('#scheduling [name="_token"]').val()
        },
        method: $('[name="_method"]', form).val(),
        data: formData,
        success: success,
        error: fail
    });
}

function success(data) {
    if (data.success) {

        $("#mdlForm").modal('hide');
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();

        CRM_SCHEDULE_ID = null;

        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
        //aqui se debe pensar en un método que refresque los eventos del timeline
        $('body').trigger('save-event', { events: data.events });
        localStorage.setItem('programmer_observable', 'false');
    }
}


function fail(error) {
    var mdlForm = $('#mdlForm');
    showErrors(error.responseJSON.errors, '#scheduling');
    handleErrors(error);
}

function loadFormDelete(id) {
    return $.ajax({
        url: ULR + "availability/delete-form",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

function sendDelete() {

    var form = $('#frmDelete');
    var data = form.serialize();

    return $.ajax({
        url: ULR + "availability/delete",
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
        $('.timepicker-end').wickedpickadate(endconf);
    }

}

function initTimePickersPlugin() {
    $('.pickadate-translations').pickadate({
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

    $('.timepickers').mdtimepicker({
        theme: 'green',
        hourPadding: false
    });
}




//---FUNCIONES PARA INICIALIZAR LOS SELECTS2

function initSelectScheduling(edit = false) {
    $('[name="filterTypes[]"]').select2({
        placeholder: "Dejar vacío para todos",
        ajax: {
            url: ULR + "scheduling/types",
            dataType: 'json',
            method: "GET",
            error: function(error) {
                handleErrors(error);
            }
        },
        "language": {
            "noResults": () => "No se encontraron tipos de eventos registrados en el sistema.",
            "searching": () => "Consultando..."
        },
    });
    $('[name="type"]').select2({
        width: '100%',
        dropdownParent: $('.modal-content', $('#mdlForm')),
        placeholder: "Seleccione un tipo",
        ajax: {
            url: ULR + "scheduling/types",
            dataType: 'json',
            method: "GET",
            error: function(error) {
                handleErrors(error);
            }
        },
        "language": {
            "noResults": () => "No se encontraron tipos de eventos registrados en el sistema.",
            "searching": () => "Consultando..."
        },
    }).on("select2:select", function(e) {
        CRM_SCHEDULE_ID = null;
        var selected_element = $(e.currentTarget);
        var type = selected_element.val();
        getTypeFrame(type, edit);
        getTypeSubform(type, edit);
    });
}

function initSelectServices() {
    $('[name="services_id[]"]').select2({
        width: '100%',
        dropdownParent: $('.modal-content', $('#mdlForm')),
        placeholder: "Seleccione uno o varios servicios",
        ajax: {
            url: ULR + "scheduling/services",
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
        var services = selected_element.val();
        getTabProccess(services);

    }).on("select2:unselect", function(e) {
        var selected_element = $(e.currentTarget);
        var services = selected_element.val();
        getTabProccess(services);
    });
}

function initSelectCrossing() {
    $('[name="crossing"]').select2({
        width: '100%',
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        CROSSING = select_val;
        initSelectCrosssingData();
    });
}

function initSelectCrosssingData() {
    $('[name="crossingData[]"]').select2({
        width: '100%',
        placeholder: "Dejar vacío para todos",
        ajax: {
            url: ULR + "scheduling/" + CROSSING,
            dataType: 'json',
            method: "GET",
            error: function(error) {
                handleErrors(error);
            }
        },
        "language": {
            "noResults": () => "No se encontraron registros para esta sede.",
            "searching": () => "Consultando..."
        },
        //dropdownAdapter: Utils.Decorate( Utils.Decorate(Dropdown,AttachBody),SelectAll)
    });
}

function initSelectCrms() {
    $('[name="crm_id"]').select2({
        width: '100%',
        dropdownParent: $('.modal-content', $('#mdlForm')),
        placeholder: "Seleccione un cliente",
        ajax: {
            url: ULR + "scheduling/crms",
            dataType: 'json',
            method: "GET",
            error: function(error) {
                handleErrors(error);
            }
        },
        "language": {
            "noResults": () => "No se encontraron registros de clientes para esta sede.",
            "searching": () => "Consultando..."
        },
        //dropdownAdapter: Utils.Decorate( Utils.Decorate(Dropdown,AttachBody),SelectAll)
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        CRM_ID = selected_element.val();
        initSelectCRMOffice();
    });
}

function initSelectCRMOffice() {
    $('[name="office_id"]').select2({
        placeholder: "Seleccione una sucursal del cliente",
        ajax: {
            url: ULR + "scheduling/crms-offices",
            dataType: 'json',
            method: "GET",
            data: { crm: CRM_ID },
            error: function(error) {
                handleErrors(error);
            }
        },
        "language": {
            "noResults": () => "No se encontraron sucursales registradas para esta sede.",
            "searching": () => "Consultando..."
        },
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        OFFICE_ID = selected_element.val();
        initSelectCRMContact();
    });

}

function initSelectCRMContact() {
    $('[name="contact_id"]').select2({
        placeholder: "Seleccione un contacto del cliente",
        ajax: {
            url: ULR + "scheduling/crms-contacts",
            dataType: 'json',
            method: "GET",
            data: { crm: CRM_ID },
            error: function(error) {
                handleErrors(error);
            }
        },
        "language": {
            "noResults": () => "No se encontraron contactos registrados para este cliente.",
            "searching": () => "Consultando..."
        },
    });
}

function initSelectUsers() {

    $('[name="users[]"]').select2({
        width: '100%',
        dropdownParent: $('.modal-content', $('#mdlForm')),
        placeholder: "Al menos un usuario",
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

function initSelectArea() {
    $('[name*="[detail_service_id]"]').select2({
        placeholder: "Seleccione un área",
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var area = selected_element.val();
        setDetailProductInfo(area);

    });
}

function initSelectRoles() {
    $('[name*="[role]"]').select2({
        placeholder: "Seleccione un rol",
        ajax: {
            url: ULR + "scheduling/roles",
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

function initSelectProducts(key = false) {
    $('[name*="' + (key !== false ? '[' + key + ']' : '') + '[product]"]').select2({
        placeholder: "Seleccione un producto",
        ajax: {
            url: ULR + "scheduling/products/" + SERVICE,
            dataType: 'json',
            method: "GET",
            error: function(error) {
                handleErrors(error);
            }
        },
        cache: true,
        templateResult: dataFormatResultProduct,
        templateSelection: dataFormatSelection,
        dropdownCssClass: "bigdrop",
        escapeMarkup: function(m) { return m; },
        "language": {
            "noResults": () => "No se encontraron productos registrados en el sistema.",
            "searching": () => "Consultando..."
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var key = selected_element.data('key');
        var product = selected_element.val();
        setProductUnity(product, key);

    });
}

function getTabProccess(services, edit = false) {
    $('.tabServices').html('');
    var mdlForm = $('#mdlForm');
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'true');
    getTabServices(services).done(function(data) {
        loading.attr('data-show', 'false');
        $('.tabServices').html(data.view);
        initSelectRoles();
        initSelectProducts();
        initSelectArea();
        if (edit) {
            CRM_ID = $('[name="crm_id"]').val();
            initSelectCRMContact();
            initSelectCRMOffice();
            initSelectRoles();
            initSelectProducts();
        }
    }).fail(function(error) {
        handleErrors(error);
    });
}

function setDetailProductInfo(area) {
    $('[name*="[' + SERVICE + '][price]').val('');
    var mdlForm = $('#mdlForm');
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'true');
    getAreaInfo(area).done(function(data) {
        loading.attr('data-show', 'false');
        $('[name*="[' + SERVICE + '][price]"]').val(data.area.cost);
    }).fail(function(error) {
        handleErrors(error);
    });
}

function setProductUnity(product, key) {
    $('#product' + key).val('');
    var mdlForm = $('#mdlForm');
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'true');
    getProductInfo(product).done(function(data) {
        loading.attr('data-show', 'false');
        $('#product' + key).val(data.product.unit.name);

    }).fail(function(error) {
        handleErrors(error);
    });
}

$('body').on('click', '[data-click="setService"]', function(e) {
    SERVICE = $(this).data('id');
    initSelectProducts();
});

//---FUNCIONES PARA AÑADIR COLUMNAS A LAS OBSERVACIONES

$('body').on('click', '[data-click="addObservationRow"]', function(e) {

    e.preventDefault();
    var loading = $('.loading-min', '.tbl-observations').first();
    loading.attr('data-show', 'true');
    addObservationRow().done(function(data) {
        $('.tbl-observations tbody').append(data.row);
        initSelectRoles();
        loading.attr('data-show', 'false');
    }).fail(function(error) {
        handleErrors(error);
        loading.attr('data-show', 'false');
    });
});

$('body').on('change', '.custom-file input', function(e) {
    let key = $(this).data('file');
    let name = e.target.files[0].name;
    $(this).next('.custom-file-label[data-label="' + key + '"]').html(name);
});

$('body').on('click', '[data-click="removeObservationRow"]', function(e) {

    if ($(this + ' input[name*="id"]').length > 0) {

        let key = $(this).data('id');

        var mdlForm = $('#mdlDeleteDocument');
        var loading = $('.loading-min', '.tbl-observations').first();
        loading.attr('data-show', 'true');
        mdlForm.modal('show');

        KEY_DELETE = key;
        let id = $('input[name="observation[' + key + '][id]"]').val();
        e.preventDefault();

        loadFormDeleteObservation(id).done(function(data) {
            if (!data.success) {
                $('tr[data-id="' + KEY_DELETE + '"]').remove();
                setTimeout(() => {
                    mdlForm.modal('hide');

                }, 500);
            }

            $('.modal-content', mdlForm).html(data.view);
            loading.attr('data-show', 'false');
        }).fail(function(error) {
            handleErrors(error);
        });
    }

});


function addObservationRow() {
    return $.ajax({
        url: ULR + "scheduling/add-observation-row",
        method: "GET",
        data: { serviceId: SERVICE },
        dataType: "json"
    });
}

function loadFormDeleteObservation(id) {
    return $.ajax({
        url: ULR + "scheduling/delete-observation-form",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}



//--FUNCIONES PARA AÑADIR COLUMNAS A LOS INSUMOS

$('body').on('click', '[data-click="addSupplyRow"]', function(e) {

    e.preventDefault();
    var loading = $('.loading-min', '.tbl-supplies').first();
    loading.attr('data-show', 'true');
    addSupplyRow().done(function(data) {
        $('.tbl-supplies tbody').append(data.row);
        initSelectProducts(data.key);
        loading.attr('data-show', 'false');
    }).fail(function(error) {
        handleErrors(error);
        loading.attr('data-show', 'false');
    });
});

$('body').on('click', '[data-click="removeSupplyRow"]', function(e) {

    if ($(this + ' input[name*="id"]').length > 0) {

        let key = $(this).data('id');

        var mdlForm = $('#mdlDeleteDocument');
        var loading = $('.loading-min', '.tbl-supplies').first();
        loading.attr('data-show', 'true');
        mdlForm.modal('show');

        KEY_DELETE = key;
        let id = $('input[name="supply[' + key + '][id]"]').val();
        e.preventDefault();

        loadFormDeleteSupply(id).done(function(data) {

            if (!data.success) {
                $('tr[data-id="' + KEY_DELETE + '"]').remove();
                setTimeout(() => {
                    mdlForm.modal('hide');
                }, 500);
            }

            $('.modal-content', mdlForm).html(data.view);
            loading.attr('data-show', 'false');
        }).fail(function(error) {
            handleErrors(error);
        });
    }

});

function addSupplyRow() {
    return $.ajax({
        url: ULR + "scheduling/add-supply-row",
        method: "GET",
        data: { serviceId: SERVICE },
        dataType: "json"
    });
}

function loadFormDeleteSupply(id) {
    return $.ajax({
        url: ULR + "scheduling/delete-supply-form",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}



//--FUNCIONES PARA AÑADIR COLUMNAS A LAS TAREAS ADICIONALES

$('body').on('click', '[data-click="addTaskRow"]', function(e) {

    e.preventDefault();
    var loading = $('.loading-min', '.tbl-tasks').first();
    loading.attr('data-show', 'true');
    addTaskRow().done(function(data) {
        $('.tbl-tasks tbody').append(data.row);
        initSelectProducts();
        loading.attr('data-show', 'false');
    }).fail(function(error) {
        handleErrors(error);
        loading.attr('data-show', 'false');
    });
});

$('body').on('click', '[data-click="removeTaskRow"]', function(e) {

    if ($(this + ' input[name*="id"]').length > 0) {

        let key = $(this).data('id');

        var mdlForm = $('#mdlDeleteTasks');
        var loading = $('.loading-min', '.tbl-tasks').first();
        loading.attr('data-show', 'true');
        mdlForm.modal('show');

        KEY_DELETE = key;
        let id = $('input[name="task[' + key + '][id]"]').val();
        e.preventDefault();

        loadFormDeleteTask(id).done(function(data) {

            if (!data.success) {
                $('tr[data-id="' + KEY_DELETE + '"]').remove();
                setTimeout(() => {
                    mdlForm.modal('hide');
                }, 500);
            }

            $('.modal-content', mdlForm).html(data.view);
            loading.attr('data-show', 'false');
        }).fail(function(error) {
            handleErrors(error);
        });
    }

});


function addTaskRow() {
    return $.ajax({
        url: ULR + "scheduling/add-task-row",
        method: "GET",
        data: { serviceId: SERVICE },
        dataType: "json"
    });
}

function loadFormDeleteTask(id) {
    return $.ajax({
        url: ULR + "scheduling/delete-task-form",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

///--RENDERIZAR SELECTS DE PLAGAS (CONTROL)
function dataFormatResultInfestation(data) {
    if (data.loading) return dataFormatSelection({ text: 'Consultando...' });

    var markup = "<div class='select2-result-repository clearfix'>" +
        "<div class='select2-result-repository__avatar'>" +
        "<img class='avatarSelect2' src='" + data.full_avatar + "' />" +
        "</div>" +
        "<div class='select2-result-repository__meta'>" +
        "<div class='select2-result-repository__title'>" + data.name + "</div>";

    if (data.description) {
        markup += "<div class='select2-result-repository__description'>" + data.description + "</div>";
    }

    return markup;
}

function dataFormatSelection(data) {
    return data.name || data.text;
}

//INICIAR MINI VALENDAR PARA SELECCIONAR UN SERVICIO DE CRONOGRAMA
$('body').on('click', '[data-click="initMiniCalendar"]', function(e) {
    e.preventDefault();
    localStorage.setItem('programmer_observable', 'true');

    if($('[name="crm_id"]').val() == null) {
        swal('No has seleccionado un cliente');
        return false;
    }else if($('[name="office_id"]').val() == null) {
        swal('No has seleccionado una sucursal del cliente');
        return false;
    }

    $("#minicalendar").fullCalendar('destroy');
    $('#minicalendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,year'
        },
        defaultView: 'month',
        titleRangeSeparator: " al ",
        locale: 'es',
        eventLimit: true, // for all non-agenda views
        eventLimitText: "Ver más",
        timezone: 'America/Bogota',
        showNonCurrentDates: false,
        views: {
            month: {
                eventLimit: 5
            }
        },
        events: function(start, end, timezone, callback) {
            var loading = $('.loading', '#agendaSection').first();
            loading.attr('data-show', 'true');
            getSchedulingCalendar(start, end).done((doc, ) => {
                var events = [];
                $.each(doc.schedules, function(index, event) {

                    var shortname = '<i  class="font-medium-2 ' + event.service.avatar + ' event-name-i"></i>&nbsp;<span class="event-name-span">' + event.service.name + (event.num ? ' ' + event.num : '') + '</span>';
                    var name = shortname + '<div style="margin-top: 8px;">';
                    event.controls.forEach(control => {
                        name += '<img class="avatar-percent" style="border: 1px solid #fff;background-color:#fff;" src="' + (control.infestation? control.infestation.full_avatar : '') + '">';
                    });
                    name += '</div>'

                    if (event.observations) {
                        name += '<div style="margin-top: 8px;"><span>';
                        event.observations.forEach(observation => {
                            name += ' // ' + observation.observation;
                        });
                        name += '</span></div>';
                    }

                    events.push({
                        id: event.id,
                        title: name,
                        shortitle: shortname,
                        description: event.service.name,
                        fullObs: event.fullObs ? event.fullObs : '',
                        start: event.start,
                        end: event.end,
                        color: event.status ? (event.status.color ? event.status.color : '#d556ff') : '#d556ff',
                        data: event,
                        type: event.observation,
                        incharge: '',
                        crm: event.crm ? event.crm.name : 'Ninguno',
                        isCustomer: doc.isCustomer
                    });
                });

                callback(events);
            }).fail(function(error) {
                loading.attr('data-show', 'false');
                handleErrors(error);
            });

        },
        eventAfterAllRender: function(view) {
            $("#minicalendar .fc-content-skeleton table tfoot").remove();
            var loading = $('.loading', '#agendaSection').first();
            loading.attr('data-show', 'false');
            $('#minicalendar .fc-day').html('');
            if (view.name == 'month' && !IS_CUSTOMER) {
                $('#minicalendar .fc-day').each(function() {
                    var date = $(this).data('date');
                    $(this).css('position', 'relative');
                    if (!$(this).hasClass('fc-disabled-day')) {
                        $(this).append("<span style='color:#4caf50;font-size: 14px;' class='dayAddButton btn btn-default btn-sm scheduleNew' data-module='programmer' title='Agregar refuerzo/control' data-date='" + date + "'>(+)</span>");
                        //$(this).append("<span style=' margin-left: 65px;font-size: 18px;' class='dayAddButton btn btn-default btn-sm' data-click='openStatusForm'  title='Cambiar estado' data-date='" + date + "'><i class='las la-exchange-alt'></i></span>");
                    }

                });
            }
            $("#minicalendar .dayAddButton").css({ 'padding': '0.1rem 0', 'cursor': 'pointer', 'position': 'absolute', 'bottom': '0px', 'margin-top': '10px', 'display': 'inline-block', 'z-index': '200000' });
            $("#minicalendar .fc-content-skeleton table").append('<tfoot><tr><td>&nbsp;</td></tr><tfoot>');
        },
        eventRender: function(event, element) {
            element.find('.fc-title').html(event.title);
        },
        eventClick: function(calEvent, jsEvent, view) {
            console.log(calEvent, jsEvent);
            //scheduleNew(null, info);
            $('.fc-event').removeClass('event-selected');
            $(jsEvent.currentTarget).addClass('event-selected');
            var name_event = calEvent.description + ' ' + $('[name="crm_id"]').text().trim() + ' en sucursal ' + $('[name="office_id"]').text().trim();
            $('[name="title"]').val(name_event);
            $('[name="description"]').html(calEvent.fullObs);
            CRM_SCHEDULE_ID = calEvent.id;
        },
        timeFormat: 'hh:mm a'

    });

});

function getSchedulingCalendar(start, end) {
    var form = $('#scheduling');
    var data = form.serialize() + "&start=" + start.unix() + "&end=" + end.unix();
    return $.ajax({
        url: ULR + "crm/" + $('[name="crm_id"]').val() + "/schedule-calendar",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });
}

document.addEventListener("visibilitychange", function() {
    if (document.visibilityState == 'visible' && localStorage.getItem('programmer_observable') == 'true') {
        $('#minicalendar').fullCalendar('refetchEvents');
    }
});

function initSelectScheduleStatuses(key = false) {
    var selector = '[name*="[init_status]"], [name="init_status"]';
    if (key) {
        selector = '[name*="[' + key + '][init_status]"], [name="init_status"]';
    }
    $(selector).select2({
        placeholder: "Seleccione una opcion",
        allowClear: true,
        width: "100%",
        dropdownParent: $('.modal-content', $('#mdlLargueForm')),
        ajax: {
            url: ULR + "crm/schedule-statuses",
            dataType: 'json',
            method: "GET",
            success: selectScroll
        },
        cache: true,
    }).on("select2:open", e => selectScroll());
}