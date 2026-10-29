CUSTOMER_ID = null;
OFFICE_ID = null;
CONTACT_ID = null;
IGNORE_NEXT = false;
EDITOR = null;
var images = [
    ULR + '/images/plano_planta_palmerajunior.png',
];
var createPDF = false;
var form = $("#quoteForm").show();


function saveForm() {

    var form = $('#quoteForm');
    var data = form.serialize();
    return $.ajax({
        url: ULR + 'quotes/save',
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });
}

function setFormat() {

    var form = $('#quoteForm');
    var data = form.serialize();
    return $.ajax({
        url: ULR + 'quotes/set-format',
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });
}

$('body').ready(function() {
    initSelects();
    $('.content').on('keyup', "[name*='[quantity]']", function(e) {
        var quantity = $(this).val();
        var key = $(this).data('key');
        var price = $("[name*='[" + key + "][price]']");
        var _price = $("[name*='[" + key + "][_price]']");
        var result = quantity * clear_format(_price.val(), 0);
        price.val(number_format(result));
    });
    $('.repeater-default').repeater({
        show: function(e) {
            $(this).slideDown(500);

        },
        hide: function(remove) {
            $(this).slideUp(500);
        }
    });
    $("#success").on('shown.bs.modal', function() {
        $('#drawMap').on('shown.bs.modal', function(e) {
            $('.wPaint').wPaint({
                theme: 'standard classic',
                path: ULR + 'template/app-assets/vendors/js/wPaint/'
            });
        });
    });
    $('.pickadate-translations').pickadate({
        formatSubmit: 'dd/mm/yyyy',
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

});

function initSelects() {

    initSelectCustomer();
    initSelectOffices();
    initSelectContactCustomer();
    initSelectCoordinator();
    initSelectServices();
    initResumePlugins();
    initSelectInfestation();
    initSelectPeriodicities();

}

function initResumePlugins() {
    //inicializar  icheck
    $('.skin-square input').iCheck({
        checkboxClass: 'icheckbox_square-red',
        radioClass: 'iradio_square-red',
    });

    //manejo de descuento
    //$(".subtotal").fadeOut("slow", function() {});
    //$(".discount").fadeOut("slow", function() {});
}

function initSelectCustomer() {

    $('#customer').select2({
        width: '100%',
        allowClear: true,
        placeholder: 'Cliente',
        tags: true,
        ajax: {
            url: ULR + "quotes/customer",
            dataType: 'json',
            method: "GET",
            success: selectScroll
        },
        cache: true,
        templateResult: dataFormatResultCRM,
        templateSelection: dataFormatSelection,
        dropdownCssClass: "bigdrop",
        escapeMarkup: function(m) { return m; },
        "language": {
            "noResults": () => "No se encontraron clientes asignados a ti.",
            "searching": () => "Consultando..."
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        CUSTOMER_ID = select_val;


        initSelectOffices(true);
    }).on("select2:open", e => selectScroll());
}

function initSelectOffices(restart = false) {

    if (restart) $('#office_id').val('').trigger('change');
    if (CUSTOMER_ID == null) CUSTOMER_ID = $('#customer').val();

    $('#office_id').select2({
        width: '100%',
        allowClear: true,
        placeholder: 'sucursal',
        tags: true,
        ajax: {
            url: ULR + "quotes/offices/" + CUSTOMER_ID,
            dataType: 'json',
            method: "GET",
        },
        cache: true,
        templateResult: dataFormatResultOffice,
        templateSelection: dataFormatSelection,
        dropdownCssClass: "bigdrop",
        escapeMarkup: function(m) { return m; },
        "language": {
            "noResults": () => "No se encontraron sucursales del cliente para esta sede de Palmera.",
            "searching": () => "Consultando...",
            "errorLoading": () => "No ha seleccionado un cliente"
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        OFFICE_ID = select_val;

        initSelectContactCustomer(true);
    });
}

function initSelectContactCustomer(restart = false) {
    if (restart) $('#contact_id').val('').trigger('change');
    if (OFFICE_ID == null) OFFICE_ID = $('#office_id').val();
    $('#contact_id').select2({
        width: '100%',
        allowClear: true,
        placeholder: 'Contacto',
        tags: true,
        ajax: {
            url: ULR + "quotes/lis-contact/" + OFFICE_ID,
            dataType: 'json',
            method: "GET",
        },
        "language": {
            "noResults": () => "No se encontraron contactos para este cliente.",
            "searching": () => "Consultando...",
            "errorLoading": () => "No ha seleccionado una sucursal"
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        CONTACT_ID = select_val;
        $('[name*="email"]').val(e.params.data.email);
    });
}

function initSelectCoordinator() {

    $('#coordinator_id').select2({
        width: '100%',
        allowClear: true,
        placeholder: 'Seleccione un coordinador mip',
        dropdownParent: $('#quoteForm', $('.content-wrapper')),
        ajax: {
            url: ULR + "quotes/coordinator",
            dataType: 'json',
            method: "GET",
            success: selectScroll
        },
        cache: true,
        templateResult: dataFormatResultUser,
        templateSelection: dataFormatSelection,
        dropdownCssClass: "bigdrop",
        escapeMarkup: function(m) { return m; },
        "language": {
            "noResults": () => "No se encontraron coordinadores mips disponibles en esta sede.",
            "searching": () => "Consultando..."
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
    }).on("select2:open", e => selectScroll());
}

function initSelectServices(key = false) {
    var selector = '[name*="[service]"]';
    if (key) {
        selector = '[name*="[' + key + '][service]"]';
        console.log('init key', key);
    }
    $(selector).select2({
        width: '100%',
        placeholder: "Dejar vacío para aplicar todos los servicios.",
        allowClear: true,
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
        initSelectArea(select_val, key);
        initSelectInfestation(select_val, key);
        initParameters(key, select_val);
    }).on("select2:open", e => selectScroll()).on("select2:open", e => selectScroll()).on("select2:unselect", function(e) {
        var selected_element = $(e.currentTarget);
        var key = e.currentTarget.dataset.key;
        var td = $("[data-id='" + key + "'] .parameters");
        td.html("");
    });
}

$('body').on('click', '[data-click="related"]', function(e) {
    var related = $(this).data('related');
    var key = $(this).data('key');
    var index = $(this).data('index');
    var inputValue = $('[data-related-value="' + related + '"]');
    if ($(this).is(':checked')) {
        inputValue.attr('disabled', false);
        //addParameterValueToService(inputValue.val(), '+', key, index);
    } else {
        inputValue.attr('disabled', true);
        // addParameterValueToService(inputValue.val(), '-', key, index);
    }



});

function addParameterValueToService(value, operation = '+', key) {
    var finalValue = $('[name="services[' + key + '][price]"]');
    var serviceValue = $('[name="services[' + key + '][_price]"]');
    var Svalue = clear_format(serviceValue.val());

    var quantityNode = $('[name="services[' + key + '][quantity]"]');
    var Squantity = clear_format(quantityNode.val());


    console.log(Svalue);

    switch (operation) {
        case '+':
            Svalue = Svalue * Squantity;
            Svalue += clear_format(value);
            break;
        case '-':
            Svalue = clear_format(finalValue.val());
            Svalue -= clear_format(value);
            break;
        default:
            break;
    }
    finalValue.val(number_format(Svalue));
}

function initSelectArea(service, key) {
    $('[name*="[' + key + '][detailService]"]').val('').trigger('change');
    $('[name*="[' + key + '][quantity]"]').val('');
    $('[name*="[' + key + '][price]"]').val('');
    $('[name*="[' + key + '][visits]"]').val('');
    $('[name*="[' + key + '][quantity]"]').val('');
    $('[name*="[' + key + '][detailService]"]').select2({
        width: '100%',
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
        templateResult: dataFormatResultCoverage,
        dropdownCssClass: "bigdrop",
        escapeMarkup: function(m) { return m; },
        "language": {
            "noResults": () => "No se encontraron coberturas (listado de precios) para este servicio.",
            "searching": () => "Consultando..."
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var area = selected_element.val();
        $('[name*="[' + key + '][price]"]').val(number_format(e.params.data.cost, 0, '.', ','));
        $('[name*="[' + key + '][quantity]"]').val(number_format(1, 0, '.', ','));
        $('[name*="[' + key + '][_price]"]').val(number_format(e.params.data.cost, 0, '.', ','));
        $('[name*="[' + key + '][visits]"]').val(number_format(e.params.data.visits, 0, '.', ','));

    });
}

function initSelectInfestation(service, key = false) {



    if (key != false) {
        selector = '[name*="[' + key + '][infestations][]"]';
        $('[name*="[' + key + '][infestations][]"]').val('').trigger('change');
    } else {

        var selector = '[name*="[infestations][]"]';
        console.log('aqui');

        if (service == undefined) {
            service = $(selector).data('service');
        }
    }

    $(selector).select2({
        width: '100%',
        placeholder: "Seleccione uno o mas controles",
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
            "noResults": () => "No se encontraron plagas para este servicio.",
            "searching": () => "Consultando..."
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var area = selected_element.val();
    }).on("select2:open", e => selectScroll());
}

function initSelectPeriodicities(key = false) {

    var selector = '[name*="[periodicity_id]"]';
    if (key) selector = '[name*="[' + key + '][periodicity_id]"]';

    $(selector).select2({
        width: '100%',
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

function initParameters(key, service) {
    var td = $("[data-id='" + key + "'] .parameters");
    console.log(td);
    $.ajax({
        url: ULR + 'quotes/service/' + service + '/parameters',
        dataType: 'json',
        method: 'get',
        data: { key: key },
    }).done((data) => {
        td.html(data.view);
    });
}



//--FUNCIONES PARA AÑADIR COLUMNAS A LOS SERVICIOS

$('body').on('click', '[data-click="addServiceRow"]', function(e) {

    e.preventDefault();
    var loading = $('.loading-min', '.tbl-services').first();
    loading.attr('data-show', 'true');
    addServiceRow().done(function(data) {
        $('.tbl-services tbody').append(data.row);
        initSelectServices(data.key);
        initSelectPeriodicities(data.ky);
        loading.attr('data-show', 'false');
    }).fail(function(error) {
        handleErrors(error);
        loading.attr('data-show', 'false');
    });
});

$('body').on('click', '[data-click="removeServiceRow"]', function(e) {

    if ($(this + ' input[name*="id"]').length > 0) {

        let key = $(this).data('id');

        var mdlForm = $('#mdlDeleteDocument');
        var loading = $('.loading-min', '.tbl-services').first();
        loading.attr('data-show', 'true');
        mdlForm.modal('show');

        KEY_DELETE = key;
        let id = $('input[name="supply[' + key + '][id]"]').val();
        e.preventDefault();

        loadFormDeleteService(id).done(function(data) {

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

function addServiceRow() {
    return $.ajax({
        url: ULR + "quotes/add-service-row",
        method: "GET",
        dataType: "json"
    });
}

function loadFormDeleteService(id) {
    return $.ajax({
        url: ULR + "quotes/delete-service-form",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

function showEmailControl(show) {
    if (show) {
        $('[name="email"]').parent('div').removeClass('display-hidden');
    } else {
        $('[name="email"]').parent('div').addClass('display-hidden');
    }
}

function validateStep(step) {
    var form = $('#quoteForm');
    var data = form.serialize();
    return $.ajax({
        url: ULR + 'quotes/validate-step/' + (step + 1),
        method: 'GET',
        data: data,
        dataType: "json",
        async: false
    });
}

function generateQuoteResume() {
    var form = $('#quoteForm');
    var data = form.serialize();
    return $.ajax({
        url: ULR + 'quotes/resume',
        method: 'GET',
        data: data,
        dataType: "json",
        async: false
    });
}

var wizard = $("#quoteForm").steps({
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
        saveForm().done(function(data) {

            loading.attr('data-show', 'false');
            if (data.success) {
                // //cambiar los atributos 
                // //del form de create a put
                // var form = $('#quoteForm');
                // $('[name="_method"]', form).val('PUT');
                // $('[name="id"]', form).val(data.id);
                // setTemplateFrame(data.id);
                swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
                if (data.success) { window.location.href = ULR + 'quotes'; }

            } else {
                swal(data.success ? "Correcto" : "Ups!", data.message, data.success ? "success" : "warning");
                IGNORE_NEXT = true;
                $(this).steps('previous');
            }

        }).fail(function(error) {
            showErrors(error.responseJSON.errors, '#quoteForm');
            handleErrors(error);
            IGNORE_NEXT = true;
            $(this).steps('previous');
        });



        // loading.attr('data-show', 'true');
        // applyFormat().done(function(data) {
        //     loading.attr('data-show', 'false');

        //     swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");

        //     if (data.success) { window.location.href = ULR + 'quotes'; }
        // }).fail(function(error) {
        //     var loading = $('.loading').first();
        //     loading.attr('data-show', 'false');
        //     handleErrors(error);
        //     showErrors(error.responseJSON.errors, '#quoteForm');

        // });

    },
    onStepChanged: (event, currentIndex, priorIndex) => {

    },
    onStepChanging: function(event, currentIndex, newIndex) {

        //validar el formulario
        if (!IGNORE_NEXT) {
            var loading = $('.loading').first();
            loading.attr('data-show', 'true');
            setTimeout(() => {
                validateStep(currentIndex).done((data) => {
                    if (data.continue) {
                        if (newIndex == 3) {
                            var loading = $('.loading').first();
                            loading.attr('data-show', 'true');
                            generateQuoteResume().done((data) => {
                                loading.attr('data-show', 'false');
                                if (data.success) {
                                    $("#step4table").html(data.view);
                                    initResumePlugins();
                                } else {
                                    swal(data.success ? "Correcto" : "Ups!", data.message, data.success ? "success" : "warning");
                                    IGNORE_NEXT = true;
                                    $(this).steps('previous');
                                }
                            }).fail((error) => {
                                var loading = $('.loading').first();
                                loading.attr('data-show', 'false');
                                showErrors(error.responseJSON.errors, '#quoteForm');
                                handleErrors(error);
                                IGNORE_NEXT = true;
                                $(this).steps('previous');
                            });
                        }
                        if (newIndex == 4) {
                            //aqui iba el save que está en OnFinished
                        } else {
                            var loading = $('.loading').first();
                            loading.attr('data-show', 'false');
                        }
                    } else {
                        showErrors(data.responseJSON.errors, '#quoteForm');
                        handleErrors(data);
                        IGNORE_NEXT = true;
                        $(this).steps('previous');
                    }
                }).fail((error) => {
                    loading.attr('data-show', 'false');
                    showErrors(error.responseJSON.errors, '#quoteForm');
                    handleErrors(error);
                    IGNORE_NEXT = true;
                    $(this).steps('previous');
                });
            }, 10);
        } else {
            IGNORE_NEXT = false;
        }

        return true;
    }
});

$("#quoteForm-t-5").hide();



function initCKEditor() {

    EDITOR = CKEDITOR.instances['ckeditor'];
    if (EDITOR) { EDITOR.destroy(true); }
    CKEDITOR.replace('ckeditor', {
        height: '800px',
        extraPlugins: 'uploadimage,dragresize,newpage',
        uploadUrl: '/template/app-assets/vendors/js/editors/ckeditor/server/ajaxfile.php?type=clipboard',
        filebrowserUploadUrl: '/template/app-assets/vendors/js/editors/ckeditor/server/ajaxfile.php?type=file',
        filebrowserImageUploadUrl: '/template/app-assets/vendors/js/editors/ckeditor/server/ajaxfile.php?type=image',
        filebrowserUploadMethod: 'form'
    });

    CKEDITOR.instances.ckeditor.on('change', function() {
        var body = CKEDITOR.instances.ckeditor.getData();
        $("[name='body']").val(body).trigger('keyup');
    });

    CKEDITOR.addCss('.cke-page {background-image: url(/images/default/quote_background.png);height: 279mm;overflow: hidden;width: 216mm;background-size: 100% 100%;background-repeat: no-repeat;text-align: justify;border: solid;margin: 0 auto;}.cke-content {padding: 55mm 21mm 45mm 32mm;}');

}

function initFormatSelect(quote) {
    $('[name*="format"]').select2({
        width: '100%',
        dropdownParent: $('#quoteForm', $('.content-wrapper')),
        placeholder: "Seleccione un formato",
        ajax: {
            url: ULR + "quotes/formats/",
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
            "noResults": () => "No se encontraron formatos predefinidos.",
            "searching": () => "Consultando..."
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var format = selected_element.val();
        setFormatContent(format, quote).done(function(data) {
            if (data.success) {
                CKEDITOR.instances.ckeditor.setData(data.content);
            }
        });

    });
}

function setFormatContent(id, quote) {
    return $.ajax({
        url: ULR + 'quotes/format/' + id,
        method: 'GET',
        data: { quote: quote },
        dataType: 'json'
    });
}

function applyFormat() {
    var body = CKEDITOR.instances.ckeditor.getData();
    $("[name='body']").val(body);
    var form = $('#quoteForm');
    var data = form.serialize();
    return $.ajax({
        url: ULR + 'quotes/apply-format',
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });
}

// Función para obtener fragmento de formato de cotización
function setTemplateFrame(id) {
    var loading = $('.loading').first();
    loading.attr('data-show', 'true');
    getTemplateFrame(id).done(function(data) {
        if (data.success) {
            $("#step5div").html(data.html);
            initCKEditor();
            initFormatSelect(id);
            loading.attr('data-show', 'false');

        } else {
            swal(data.success ? "Correcto" : "Ups!", data.message, data.success ? "success" : "warning");
            IGNORE_NEXT = true;
            $(this).steps('previous');
        }

    }).fail(function(error) {
        showErrors(error.responseJSON.errors, '#quoteForm');
        handleErrors(error);
        IGNORE_NEXT = true;
        $(this).steps('previous');
    });

}

function getTemplateFrame(id) {
    return $.ajax({
        url: ULR + 'quotes/templateFrame',
        data: { id: id },
        method: 'GET',
        dataType: 'json'

    });
}

// Initialize validation
$("#quoteFormdd").validate({
    ignore: 'input[type=hidden]', // ignore hidden fields
    errorClass: 'danger',
    successClass: 'success',
    highlight: function(element, errorClass) {
        $(element).removeClass(errorClass);
    },
    unhighlight: function(element, errorClass) {
        $(element).removeClass(errorClass);
    },
    errorPlacement: function(error, element) {
        error.insertAfter(element);
    },
    rules: {
        email: { email: true },
        crm_id: { required: true }
    }
});


// Initialize plugins
// ------------------------------

$('body').on('ifChecked', '[name="type"]', function(event) {
    if (event.target.value == 1) {
        window.location.href = ULR + "cotizaciones/crear/advance";
    } else {
        window.location.href = ULR + "cotizaciones/crear";
    }
});

$('body').on('ifChecked', '[name="send_email"]', function(event) {
    showEmailControl(true);
});

$('body').on('ifUnchecked', '[name="send_email"]', function(event) {
    showEmailControl(false);
});

$('body').on('ifChecked', '[name="diagnostic"]', function(event) {
    if (event.target.value == 1) {
        $(".select-coordinator").removeClass('display-hidden');
    } else {
        $(".select-coordinator").addClass('display-hidden');
    }
});

$('body').on('ifChecked', '[name="create_pdf"]', function(event) {
    createPDF = true;
});
$('body').on('ifunchecked', '[name="create_pdf"]', function(event) {
    createPDF = false;
});

$('body').on('change', '[name*="area"]', function(element) {
    var name = element.target.name;
    $("[name='" + name.replace('area', 'price') + "']").val($("[name='" + name + "'] option:selected").data('price'));
});

$('body').on('ifChecked', '[name="has_discount"]', function(event) {
    $(".subtotal").fadeIn("slow", function() {});
    $(".discount").fadeIn("slow", function() {});
    $('[name="discount_value"]').val("");
    $('[name="discount_percent"]').val("");
});

$('body').on('ifUnchecked', '[name="has_discount"]', function(event) {

    $(".subtotal").fadeOut("slow", function() {});
    $(".discount").fadeOut("slow", function() {});
    let subtotal = parseInt(clear_format($('#subtotal').val()));
    $("#total").text(number_format((subtotal), 0, '.', ','));

});

$('.wizard').on('keyup', '[name="discount_percent"]', function(event) {
    let subtotal = parseInt(clear_format($('#subtotal').val()));
    if (event.target.value != "") {
        let discount = (parseInt(event.target.value) * subtotal) / 100;
        $("#total").text(number_format((subtotal - discount), 0, '.', ','));
        $('[name="discount_value"]').val(number_format(discount, 0, '.', ','));
    } else {
        $("#total").text(number_format((subtotal), 0, '.', ','));
        $('[name="discount_value"]').val("");
    }
});

$('.wizard').on('keyup', '[name="discount_value"]', function(event) {
    let subtotal = parseInt(clear_format($('#subtotal').val()));
    if (event.target.value != "") {
        let discount = (100 * parseInt(clear_format(event.target.value))) / subtotal;
        $("#total").text(number_format((subtotal - parseInt(clear_format(event.target.value))), 0, '.', ','));
        $('[name="discount_percent"]').val(number_format(discount, 1, '.', ','));
    } else {
        $("#total").text(number_format(subtotal, 0, '.', ','));
        $('[name="discount_percent"]').val("");
    }
});