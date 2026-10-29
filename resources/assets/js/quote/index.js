METHOD = 'POST';
DIV_TAB = '';
SERVICE = null;
QUOTESERVICE = null;
QUOTE = null;

$('body').ready(function() {

    getList();

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
    $('input#datetable').val('');
    return $.ajax({
        url: ULR + "quotes",
        method: "GET",
        dataType: "json",
        data: {search: this.value, perpage: $("[name='tblList_length']").val(),date: $('input#datetable').val()},
        success: function(data){
            $('#list').html(data.view);
            initDataTable('<t>');
        }
    });

},300));

$('body').on('click', '.page-link', function(e) {
    e.preventDefault();
    var page = $(this).attr('href').split('page=')[1];
    return $.ajax({
        url: ULR + "quotes",
        method: "GET",
        dataType: "json",
        data: {page:page, search: $("input[type='search']").val(), perpage: $("[name='tblList_length']").val(),date: $('input#datetable').val()},
        success: function(data){
            $('#list').html(data.view);
            initDataTable('<t>');
        }
    });
    
});

$('body').on('search', "input[type='search']", function(e) {
    $('input#datetable').val('');
    return $.ajax({
        url: ULR + "quotes",
        method: "GET",
        dataType: "json",
        data: {search: this.value, perpage:  $("[name='tblList_length']").val(),date: $('input#datetable').val()},
        success: function(data){
            $('#list').html(data.view);
            initDataTable('<t>');
        }
    });
});
$('body').on('change', "input#datetable", function(e) {
    $("input[type='search']").val('');
    return $.ajax({
        url: ULR + "quotes",
        method: "GET",
        dataType: "json",
        data: {date: this.value, perpage: $("[name='tblList_length']").val(), search: $("input[type='search']").val()},
        success: function(data){
            $('#list').html(data.view);
            initDataTable('<t>');
        }
    });
});

$('body').on('change', "[name='tblList_length']", function(e) {
    return $.ajax({
        url: ULR + "quotes",
        method: "GET",
        dataType: "json",
        data: {perpage: $("[name='tblList_length']").val(), search: $("input[type='search']").val(),date: $('input#datetable').val()},
        success: function(data){
            $('#list').html(data.view);
        }
    });
});

$('body').on('click', '[data-click="setService"]', function(e) {
    SERVICE = $(this).data('id');
    QUOTESERVICE = $(this).data('quote_service_id');
    DIV_TAB = $(this).attr('href');
    initSelectArea(SERVICE);

});

$('body').on('click', '[data-click="diagnostic"]', function(e) {
    localStorage.getItem('observable', 'true');

});

document.addEventListener("visibilitychange", function() {
    if (document.visibilityState == 'visible' && localStorage.getItem('observable') == 'true') {
        localStorage.getItem('observable', 'false');
        getList();
    }
});

function getList() {
    var list = $('#list');
    $('.loading', list).attr('data-show', 'true');
    loadList().done(function(data) {
        list.html(data.view);
        initDataTable('<t>');
        $('.loading', list).attr('data-show', 'false');
        $('.dropdown-toggle').dropdown();
    }).fail(function(error) {
        handleErrors(error);
    });
}

function initSelects() {
    initSelectFileTypePlan();
}


function loadList() {
    return $.ajax({
        url: ULR + "quotes",
        method: "GET",
        dataType: "json"
    });
}

function initSelectPeriodicities(key = false) {

    var selector = '[name*="[periodicity_id]"]';
    if (key) selector = '[name*="[' + key + '][periodicity_id]"]';

    $(selector).select2({
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
    }).on("select2:open", e => selectScroll());
}

function initSelectAreas() {

    $('[name*="[area]"]').select2({
        tags: true,
        allowClear: true,
        ajax: {
            url: ULR + "quotes/areas",
            dataType: 'json',
            method: "GET",
            data: { quote: QUOTE },
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

function initSelectArea(key) {
    selector = '[name*="[' + key + '][area]"]';
    console.log(selector);
    $(selector).select2({
        allowClear: true,
        ajax: {
            url: ULR + "quotes/areas",
            dataType: 'json',
            method: "GET",
            data: { quote: QUOTE },
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

function initSelectInfestations() {

    $('[name*="[infestations][]"], [name*="[controls][]"]').each(function() {
        $(this).select2({
            placeholder: "Seleccione una o mas controles",
            multiple: true,
            ajax: {
                url: ULR + "quotes/infestations/",
                dataType: 'json',
                method: "GET",
                error: function(error) {
                    var mdlForm = $('#mdlForm');
                    var loading = $('.loading', mdlForm).first();
                    loading.attr('data-show', 'false');
                    handleErrors(error);
                },
                success: selectScroll
            },
            cache: true,
            templateResult: dataFormatResultInfestation,
            templateSelection: dataFormatSelection,
            dropdownCssClass: "bigdrop",
            closeOnSelect: false,
            escapeMarkup: function(m) { return m; },
            "language": {
                "noResults": () => "No se encontraron controles para este servicio.",
                "searching": () => "Consultando..."
            }
        }).on("select2:select", function(e) {
            var selected_element = $(e.currentTarget);
            var service = selected_element.data('serviceid');
            var control = e.params.data.id;
            addControlFrame(service, control);
        }).on("select2:open", e => selectScroll()).on("select2:unselect", function(e) {

            var selected_element = $(e.currentTarget);
            var service = selected_element.data('serviceid');
            var control = e.params.data.id;
            $("#section" + service + control).remove();
        });
    });
}

function addControlFrame(service, control) {
    loadControlFrame(service, control).done((data) => {
        $("#controlFramesSection" + service).append(data.html);
        $('.skin-square input').iCheck({ checkboxClass: 'icheckbox_square-red', radioClass: 'iradio_square-red' });

        //var partialE = $("select[name^='services[" + service + "][control][" + control + "][product]']");
        //var key = $(partialE[(partialE.length - 1)]).data('key');

        initSelectArea(data.suplyNewRowFrameKey);
        initSelectApplication(data.suplyNewRowFrameKey);
        initSelectProduct(data.suplyNewRowFrameKey);
        initSelectPeriodicities(data.key);
        initRadio(data.suplyNewRowFrameKey);
        initInputDateSimple();

    });
}

function initInputDateSimple(locknow = false) {
    $('.pickadate-translations').pickadate({
        formatSubmit: 'dd/mm/yyyy',
        container: '.app-content',
        selectYears: true,
        selectMonths: true,
        min: false,
        firstDay: 1,
        monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
        today: 'Hoy',
        clear: 'Limpiar',
        close: 'Cerrar'
    });
}

function loadControlFrame(service, control) {
    return $.ajax({
        url: ULR + "quotes/service/add-control-frame",
        method: "GET",
        data: {
            service: service,
            control: control
        },
        dataType: "json"
    });
}

function initSelectApplication(key) {

    $('[name*="[' + key + '][application]"]').select2({
        placeholder: "Seleccione una aplicación",
        allowClear: true,
        tags: true,
        ajax: {
            url: ULR + "quotes/products/applications",
            dataType: 'json',
            method: "GET",
            success: selectScroll,
            error: function(error) {
                handleErrors(error);
            }
        },
        cache: true,
        dropdownCssClass: "bigdrop",
        escapeMarkup: function(m) { return m; },
        "language": {
            "noResults": () => "No se encontraron aplicaciones registradas en el sistema.",
            "errorLoading": () => "No se puede leer la respuesta del servidor"
        }
    }).on("select2:open", e => selectScroll());
}

function initSelectApplications() {
    $('[name*="[application]"]').each(function() {
        $(this).select2({
            placeholder: "Seleccione una aplicación",
            tags: true,
            allowClear: true,
            ajax: {
                url: ULR + "quotes/products/applications",
                dataType: 'json',
                method: "GET",
                success: selectScroll,
                error: function(error) {
                    handleErrors(error);
                }
            },
            cache: true,
            escapeMarkup: function(m) { return m; },
            "language": {
                "noResults": () => "No se encontraron aplicaciones registradas en el sistema.",
                "searching": () => "Consultando...",
                "errorLoading": () => "No se puede leer la respuesta del servidor"
            }
        }).on("select2:open", e => selectScroll());
    });
}

function initSelectProduct(key) {
    const select = $('[name*="[' + key + '][supply]"]');
    $(select).select2({
        placeholder: "Seleccione una opción",
        allowClear: true,
        ajax: {
            url: ULR + "quotes/products/category/1/control/" + $(select).data('control'),
            dataType: 'json',
            method: "GET",
            success: selectScroll,
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
            "searching": () => "Consultando...",
            "errorLoading": () => "No se puede leer la respuesta del servidor"
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var key = selected_element.data('key');
        var product = selected_element.val();
        if (e.params.data.category_id == 1) {
            $('[name*="[' + key + '][unity]"]').val(e.params.data.unit.name);
        } else {
            $('[name*="[' + key + '][unity]"]').val('Und');
        }

        if (e.params.data.category_id == 1) {
            $("#iac_" + key).css({ 'display': 'flex' });
            $("input[name*='[" + key + "][from]']").val('');
            $("input[name*='[" + key + "][to]']").val('');
            if (e.params.data.color != '') {
                $("#iac_" + key + " .aic-icon").css({ 'color': e.params.data.color });
            }
        } else {
            $("input[name*='[" + key + "][from]']").val('');
            $("input[name*='[" + key + "][to]']").val('');
            $("#iac_" + key).hide();
        }

    }).on("select2:open", e => selectScroll());
}

function initSelectCoverage(service) {

    $('select[name*="[services][' + service + '][detail_service_id]"]').select2({
        placeholder: "Seleccione una cobertura",
        ajax: {
            url: ULR + "quotes/areas/" + service,
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
        $('[name*="[' + QUOTESERVICE + '][price]"]').val(number_format(e.params.data.cost));

    });
}

function initSelectCoverages(service) {
    var selector = $('select[name*="[detail_service_id]"]');
    selector.each((index, coverage) => {
        service = $(coverage).data('service');
        $(coverage).select2({
            placeholder: "Seleccione un área",
            ajax: {
                url: ULR + "quotes/areas/" + service,
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
            $('[name*="[' + QUOTESERVICE + '][price]"]').val(number_format(e.params.data.cost));

        });
    });


}

function initSelectProducts(control = false) {
    $('[name*="[supply]"]').each(function() {
        $(this).select2({
            placeholder: "Seleccione una sustancia",
            allowClear: true,
            ajax: {
                url: ULR + "quotes/products/category/1/control/" + $(this).data('control'),
                dataType: 'json',
                method: "GET",
                success: selectScroll,
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

function initInputDate(locknow = true) {
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


//Funciones para el guardado/edición cronoogramam de actividades
//------------------------------------------------------------------------------------



$('body').on('click', '[data-click="openSuppliesForm"]', function(e) {
    e.preventDefault();
    var mdlForm = $('#mdlForm');
    var loading = $('.loading', mdlForm).first();
    var quote = QUOTE = $(this).data('quote');
    loading.attr('data-show', 'true');

    loadSuppliesForm(quote).done(function(data) {
        loading.attr('data-show', 'false');
        $('.modal-content', mdlForm).html(data.view);

        setTimeout(() => {
            initSelectInfestations();
            initSelectProducts();
            initSelectApplications();
            initInputDate();
            initSelectCoverages();
            initSelectPeriodicities();
            initSelectAreas();
        }, 500);


        $('.nav-link.active').trigger('click');
        mdlForm.modal('show');
    }).fail(function(error) {
        handleErrors(error);
    });
});

function loadSuppliesForm(quote) {
    return $.ajax({
        url: ULR + "quotes/supplies-form",
        method: "GET",
        data: {
            quote: quote
        },
        dataType: "json"
    });
}

$('body').on('click', '[data-click="addNewService"]', function(e) {
    var id = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
    var tabContainer = $('.nav-tabs');
    var navlink = $('.nav-link', tabContainer);
    var tabContent = $('.tab-content ');
    var tabPane = $('.tab-pane');
    var addButton = $("[data-click='addNewService']").parent();
    $(addButton).remove();
    navlink.removeClass('active');
    tabPane.removeClass('active');
    tabContainer.append('<li class="nav-item serviceControls-1 temporal"><a class="nav-link active" id="baseIcon-tab' + id + '" data-toggle="tab" aria-controls="tabIcon1" href="#tab' + id + '" aria-expanded="true" data-id="' + id + '" data-quote_service_id="" data-click="setService">*Nuevo Servicio* <i class="las la-times-circle la-3x float-right" data-click="removeService" data-id="' + id + '" data-fake="1"></i></a></li><li class="nav-item temporal"><a data-click="addNewService" class="nav-link"><i class="ft-plus-circle"></i>Agregar</a></li>');
    tabContent.append('<div role="tabpanel" class="tab-pane active" id="tab' + id + '" aria-expanded="true" aria-labelledby="baseIcon-tab' + id + '"><div class="row"><div class="col-md-4"><select class="form-control select2" name="temporalServiceSelect[' + id + ']"><option>Seleccione un servicio</option></select></div><div class="col-md-4"><button class="btn btn-success" data-click="saveNewService" data-id="' + id + '">Agregar</button></div></div></div>');
    initSelectServices(id);
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
        var select_val = selected_element.val();
        key = $(e.currentTarget).data('key');
        //initSelectArea(select_val, key);
        //initSelectInfestation(select_val,key);
    }).on("select2:open", e => selectScroll());
}

$('body').on('click', '[data-click="saveNewService"]', function(e) {
    e.preventDefault();
    var quote = QUOTE;
    var key = $(this).data('id');
    var service = $('[name*="temporalServiceSelect[' + key + ']"]').val();
    saveNewService(quote, service).done(function(data) {

        //volver a cargar la vista
        loadSuppliesForm(quote).done(function(data) {
            $('.modal-content', mdlForm).html(data.view);
            initSelectProducts();
            initInputDate();
            initSelectPeriodicities();
            initSelectCoverage(service);
            initSelectAreas();
            initSelectInfestations();
            initSelectApplication();
            initAllRadios();
            $('.skin-square input').iCheck({ checkboxClass: 'icheckbox_square-red', radioClass: 'iradio_square-red', });


            $('.nav-link.active').trigger('click');
        }).fail(function(error) {
            handleErrors(error);
        });


    }).fail(function(error) {
        handleErrors(error);
        loading.attr('data-show', 'false');
    });
});


function saveNewService(quote, service) {

    var form = $('#supplies');
    var data = form.serialize();
    return $.ajax({
        url: ULR + 'quotes/' + quote + '/save-service',
        method: 'GET',
        data: { service: service },
        dataType: "json"
    });
}

$('body').on('click', '[data-click="removeService"]', function(e) {
    e.preventDefault();
    var id = $(this).data('id');
    var fake = $(this).data('fake');
    if (!fake) {
        removeService(id).done((data) => {
            swal(data.success ? 'Correcto' : "Error", data.message, data.success ? "success" : "error");
            //volver a cargar la vista
            $(this).parent().remove();
            $(DIV_TAB).remove();



        }).fail(function(error) {
            handleErrors(error);
            loading.attr('data-show', 'false');
        });
    } else {
        $(this).parent().remove();
        $('div#tab' + id).remove();
    }

});

function removeService(id) {

    var form = $('#supplies');
    var data = form.serialize();
    return $.ajax({
        url: ULR + 'quotes/' + QUOTE + '/disable-service',
        method: 'GET',
        data: { id: id },
        dataType: "json"
    });
}

$('body').on('click', '[data-click="restoreServices"]', function(e) {
    e.preventDefault();
    restoreServices().done(function(data) {
        swal(data.success ? 'Correcto' : '', data.message, data.success ? "success" : "info");
        //volver a cargar la vista
        loadSuppliesForm(QUOTE).done(function(data) {
            $('.modal-content', mdlForm).html(data.view);
            initSelectProducts();
            initInputDate();
            initSelectPeriodicities();
            initSelectAreas();
            initSelectInfestations();
            initAllRadios();
            $('.skin-square input').iCheck({ checkboxClass: 'icheckbox_square-red', radioClass: 'iradio_square-red', });


            $('.nav-link.active').trigger('click');
        }).fail(function(error) {
            handleErrors(error);
        });


    }).fail(function(error) {
        handleErrors(error);
        loading.attr('data-show', 'false');
    });
});

function restoreServices() {

    var form = $('#supplies');
    var data = form.serialize();
    return $.ajax({
        url: ULR + 'quotes/' + QUOTE + '/restore-services',
        method: 'GET',
        dataType: "json"
    });
}


$('body').on('click', '[data-click="saveSupplies"]', function(e) {

    var mdlForm = $('#mdlForm');
    saveFormSupplies().done(function(data) {
        if (data.success) {
            mdlForm.modal('hide');
            getList();
        }
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }).fail(function(error) {
        showErrors(error.responseJSON.errors, '#supplies');
        handleErrors(error);
    });
});

function saveFormSupplies() {

    var form = $('#supplies');
    var data = form.serialize();
    return $.ajax({
        url: ULR + 'quotes/save/supplies',
        method: 'POST',
        data: data,
        dataType: "json"
    });
}

$('body').on('click', '[data-click="addSupplyRow"]', function(e) {

    e.preventDefault();
    var loading = $('.loading-min', '.tbl-supplies').first();
    loading.attr('data-show', 'true');
    var control = $(this).data('control');
    var infestation = $(this).data('infestation');
    addSupplyRow(control, infestation).done(function(data) {
        $('#table' + QUOTESERVICE + infestation + ' tbody').append(data.row);
        initSelectArea(data.key);
        initSelectProduct(data.key);
        initSelectApplication(data.key);
        //initAllRadios();
        initRadio(data.key);
        loading.attr('data-show', 'false');
    }).fail(function(error) {
        handleErrors(error);
        loading.attr('data-show', 'false');
    });
});

$('body').on('click', '[data-click="removeSupplyRow"]', function(e) {

    if ($(this + ' input[name*="id"]').length > 0) {

        let service = $(this).data('service');
        let control = $(this).data('control');
        let key = $(this).data('key');

        var mdlForm = $('#mdlDelete');
        var loading = $('.loading-min', '.tbl-supplies').first();
        loading.attr('data-show', 'true');

        KEY_DELETE = key;
        let id = $('input[name="services[' + service + '][control][' + control + '][product][' + key + '][id]"]').val();
        e.preventDefault();

        loadFormDeleteSupply(id).done(function(data) {

            if (!data.success) {
                $('tr[data-id="' + KEY_DELETE + '"]').remove();
                setTimeout(() => {
                    mdlForm.modal('hide');
                }, 500);
            } else mdlForm.modal('show');


            $('.modal-content', mdlForm).html(data.view);
            loading.attr('data-show', 'false');
        }).fail(function(error) {
            handleErrors(error);
        });
    }

});

$('body').on('click', '[data-click="deleteSupply"]', function(e) {
    e.preventDefault();

    var mdlForm = $('#mdlDelete');

    sendDeleteSupply().done(function(data) {
        mdlForm.modal('hide');
        $('tr[data-id="' + KEY_DELETE + '"]').remove();
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }).fail(function(error) {
        var loading = $('.loading', mdlForm).first();
        showErrors(error.responseJSON.errors, '#frmDeleteDocument');
        handleErrors(error);
    });

});

function addSupplyRow(control, infestation) {
    return $.ajax({
        url: ULR + "quotes/add-supply-row",
        method: "GET",
        data: { serviceId: SERVICE, quoteServiceId: QUOTESERVICE, infestationId: infestation, control: control },
        dataType: "json"
    });
}

function loadFormDeleteSupply(id) {
    return $.ajax({
        url: ULR + "quotes/delete-supply-form",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

function sendDeleteSupply() {

    var form = $('#frmDeleteSupply');
    var data = form.serialize();

    return $.ajax({
        url: ULR + "quotes/delete-supply",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });

}

//Funciones para el cambio de estado de cotización

$('body').on('click', '[data-click="openStatusForm"]', function(e) {
    e.preventDefault();
    var mdlForm = $('#mdlDelete');
    var quote = $(this).data('quote');

    loadStatusForm(quote).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        $('.skin-square input').iCheck({ checkboxClass: 'icheckbox_square-red', radioClass: 'iradio_square-red', });
        
        $('.skin-flat input').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
            increaseArea: '20%' // optional
        });
        mdlForm.modal('show');
    }).fail(function(error) {
        handleErrors(error);
    });
});

$('body').on('ifChanged','.skin-square', function(event) {
    if(event.target.value == 2){
        $('#showaprovedCombo').show();
    }else{
        $('#showaprovedCombo').hide();
    }
});

function loadStatusForm(quote) {
    return $.ajax({
        url: ULR + "quotes/status-form",
        method: "GET",
        data: {
            quote: quote
        },
        dataType: "json"
    });
}

$('body').on('click', '[data-click="updateStatus"]', function(e) {

    var mdlForm = $('#mdlDelete');
    updateStatus().done(function(data) {


        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
        if (data.success) {
            getList();
            mdlForm.modal('hide');
        }
    }).fail(function(error) {
        showErrors(error.responseJSON.errors, '#supplies');
        handleErrors(error);
    })
});

function updateStatus() {

    var form = $('#status');
    var data = form.serialize();
    return $.ajax({
        url: ULR + 'quotes/status',
        method: 'PUT',
        data: data,
        dataType: "json"
    });
}

//Funciones para eliminar una cotización
$('body').on('click', '[data-click="deleteForm"]', function(e) {
    e.preventDefault(); // <--- prevent form from submitting
    var id = $(this).data('id');
    swal({
        title: "Confirmación necesaria",
        text: "¿Estás seguro que deseas elimininar esta cotizción?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: 'Si, eliminar',
        cancelButtonText: "No, cancelar"
    }).then(function(data) {
        console.log(data);
        if (data.value) {
            deleteQuote(id).done(function(data) {
                swal("Correcto", data.message, "success");
                getList();
            });
        }
    });
});


function deleteQuote(id) {
    return $.ajax({
        url: ULR + 'quote/' + id + '/delete',
        type: 'get',
        dataType: 'json'
    })
}


//Funciones para crear un contrato a partir de una cotización

$('body').on('click', '[data-click="openContractForm"]', function(e) {
    e.preventDefault();
    var mdlForm = $('#mdlDelete');
    var quote = $(this).data('quote');

    loadContractForm(quote).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        $('.skin-square input').iCheck({ checkboxClass: 'icheckbox_square-red', radioClass: 'iradio_square-red', });
        initInputDate(false);
        mdlForm.modal('show');
    }).fail(function(error) {
        handleErrors(error);
    });
});

function loadContractForm(quote) {
    return $.ajax({
        url: ULR + "quotes/contract-form",
        method: "GET",
        data: {
            quote: quote
        },
        dataType: "json"
    });
}

$('body').on('click', '[data-click="saveContract"]', function(e) {

    var mdlForm = $('#mdlDelete');
    saveContract().done(function(data) {
        mdlForm.modal('hide');
        getList();
        swal("Correcto", data.message, "success");
    }).fail(function(error) {
        showErrors(error.responseJSON.errors, '#supplies');
        handleErrors(error);
    })
});

function saveContract() {

    var form = $('#contract');
    var data = form.serialize();
    return $.ajax({
        url: ULR + 'quotes/contract',
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });
}

$('body').on('click', '[data-click="openFormSendEmail"]', function(e) {

    e.preventDefault();
    var mdlForm = $('#mdlFormEmail');
    var loading = $('.loading', mdlForm).first();
    var id = $(this).data('id');
    loading.attr('data-show', 'true');
    mdlForm.modal('show');

    loadFormSendEmail(id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        loading.attr('data-show', 'false');
        initSelectEmail(id);
    });
});

function loadFormSendEmail(id) {
    return $.ajax({
        url: ULR + "quotes/email-send",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

function initSelectEmail(id) {

    $('#lisContact').select2({
        tags: true,
        ajax: {
            url: ULR + "quotes/email-contact",
            dataType: 'json',
            data: { id: id },
            method: "GET",
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        TYPEPLAN = select_val;
    });
}

$('body').on('click', '[data-click="openFormDocumentoDiagnosis"]', function(e) {

    e.preventDefault();
    var mdlForm = $('#mdlFormEmail');
    var loading = $('.loading', mdlForm).first();
    var id = $(this).data('id');
    loading.attr('data-show', 'true');
    mdlForm.modal('show');

    loadFormDocumentoDiagnosis(id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        loading.attr('data-show', 'false');
        $('.custom-file input').change(function(e) {
            $(this).next('.custom-file-label').html(e.target.files[0].name);
        });
    });
});

function loadFormDocumentoDiagnosis(id, quote) {
    return $.ajax({
        url: ULR + "quotes/document-diagnosis",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

$('body').on('click', '[data-click="saveDocumentDiagnosis"]', function(e) {

    var mdlForm = $('#mdlForm');
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'true');
    saveFormDocumentDiagnosis().done(function(data) {
        mdlForm.modal('hide');
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
        if (data.success) {
            location.reload();
        }
    });
});

function saveFormDocumentDiagnosis() {

    var form = $('#documentDiagnosis')[0];
    var formData = new FormData(form);
    return $.ajax({
        url: ULR + $(form).attr('action'),
        contentType: false,
        processData: false,
        headers: {
            'X-CSRF-Token': $('#documentDiagnosis [name="_token"]').val()
        },
        method: METHOD,
        data: formData,
        success: successDiagnosis,
        error: failDiagnosis
    });
}

function successDiagnosis(data) {
    if (data.success) {
        var mdlForm = $('#mdlForm');
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }

}

function failDiagnosis(error) {
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'false');
    showErrors(error.responseJSON.errors, '#documentDiagnosis');
    handleErrors(error);
}

function initSelectTypeDocumentDiagnosis() {

    $('#typeDocumentDiagnosis').select2({
        ajax: {
            url: ULR + "quotes/type-documento-diagnosis",
            dataType: 'json',
            method: "GET",
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        TYPEDOCUMENTDIAGNOSIS = select_val;
    });
}

//--- activar y desactivar tipo de producto (aplicación o insumo)

function updateSelects(element) {

}

$('body').on('ifChecked', '[data-click="productType"]', function(e) {
    e.preventDefault();
    updateSelects(this);
});

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


$('body').on('click', '[data-click="productType"]', function(e) {


});

/** FUNVCIONES PARA EL ENVIO DE COTIZACIONES */


$('body').on('click', "[data-click='sendQuote']", function(e) {

    e.preventDefault();
    var mdlForm = $('#mdlForm');
    var quote_id = $(this).data('quote');
    mdlForm.modal('show');

    getSendQuoteForm(quote_id).done((data) => {
        $('.modal-content', mdlForm).html(data.view);
        $(".tags").tagging({
            "case-sensitive": false,
            "forbidden-chars": [",", "?", ";", '*', '$', '%', '&', '¿', '|', '°', '!', '¡'],
            "tag-char": '@',
        });

        $('[data-tags-input-name="destinatary"]').tagging("add", data.destinatary);
        //$('[data-tags-input-name="cc"]').tagging("add", data.cc);


        hideLoading();
        initCKEditor();

    }).fail(function(error) {
        handleErrors(error);
    });


});

function getSendQuoteForm(quote_id) {
    return $.ajax({
        url: ULR + 'quote/' + quote_id + '/send-form',
        method: 'GET',
        dataType: 'json'
    });
}

$('body').on('click', '[data-click="send-quote"]', function(e) {
    e.preventDefault();
    var mdlForm = $('#mdlForm');
    sendQuote().done(function(data) {
        if (data.success) {
            mdlForm.modal('hide');
        }
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }).fail(function(error) {
        handleErrors(error);
    });

});

function sendQuote() {
    var body = CKEDITOR.instances.ckeditor.getData();
    $("[name='body']").val(body);
    var form = $('#sendQuote');
    if (form) {
        var data = form.serialize();
        return $.ajax({
            url: ULR + "quote/" + $('[name="quote_id"]').val() + "/send",
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

}

$('body').on('click', '[data-click="deleteQuoteFile"]', function(e) {
    e.preventDefault();
    var id = $(this).data('id');
    var quote = $(this).data('quote');
    swal.fire({
        'title': "Confirmación necesaria",
        'text': "¿Estás seguro que deseas eliminar el archivo de la cotización " + quote + '?',
        'type': "warning",
        showCancelButton: true,
        confirmButtonText: 'Si, Eliminar',
        cancelButtonText: 'Cancelar',
    }).then((confirm) => {
        if (confirm.value === true) {
            $.ajax({
                url: ULR + "quote/deleteFile/",
                data: { id: id },
                method: 'get',
                dataType: "json"
            }).done(function(data) {

                swal.fire({
                    'title': data.success ? "Correcto" : "Error",
                    'text': data.message,
                    'type': data.success ? "success" : "error",
                }).then(function(e) {
                    if (data.success == true) {
                        location.reload();
                    }
                });
            }).fail(function(error) {
                handleErrors(error);
                if (error && error.responseJSON) {
                    showErrors(error.responseJSON.errors, '#form');
                } else {
                    //swal("Error", "No se pudo procesar la respuesta del servidor", "error");
                }

            });
        }
    });

});