METHOD = 'POST';
COUNTRYOFFICE = '';
STATEOFFICE = '';
HEADQUARTER_ID = '';
//lista de productos
$(document).ready(function() {

    getList();
});


//validar duplicidad de nit para evirar clientes duplicados en la creacón
$('body').on('change',"[name='document']",function(){
    if(this.value.trim() != ''){
        //buscar el nit a nivel nacional para encontrar coincidencias
        SEE_LOADING = false;
        $.ajax({
            type: 'GET', 
            url: ULR + 'crm/validate-nit', 
            data: { document: this.value }, 
            dataType: 'json',
        }).done(function(response){
            if(response.success == true){
                var mdlForm = $('#mdlDelete');
                mdlForm.modal('show');
                $('.modal-content', mdlForm).html(response.view);
                SEE_LOADING = true;
            }
            
        });
    }
});

$('body').on('click','[data-click="useThisCustomer"]', function(){
    SEE_LOADING = true;
    var crm = $("input[name='customerExist']:checked").val();
    $.ajax({
        type: 'GET', 
        url: ULR + 'crm/reesamble', 
        data: { id: crm }, 
        dataType: 'json',
    }).done(function(response){
        if(response.success == true){
           window.location.href = ULR + 'crm/'+crm;
        }
        
    }).fail(function(error) {
        handleErrors(error);
    });
});

function getList() {
    var list = $('#list');
    $('.loading', list).attr('data-show', 'true');
    loadList().done(function(data) {
        list.html(data.view);
        
        $('.loading', list).attr('data-show', 'false');
        initDataTable('<t>');
    }).fail(function(error) {
        handleErrors(error);
    });
}

function loadList() {
    return $.ajax({
        url: "crm",
        method: "GET",
        dataType: "json"
    });
}

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
        url: ULR + "crm",
        method: "GET",
        dataType: "json",
        data: {search: this.value, perpage: $("[name='tblList_length']").val()},
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
        url: ULR + "crm",
        method: "GET",
        dataType: "json",
        data: {page:page, search: $("input[type='search']").val(), perpage: $("[name='tblList_length']").val()},
        success: function(data){
            $('#list').html(data.view);
            initDataTable('<t>');
        }
    });
    
});

$('body').on('search', "input[type='search']", function(e) {
    return $.ajax({
        url: ULR + "crm",
        method: "GET",
        dataType: "json",
        data: {search: this.value, perpage:  $("[name='tblList_length']").val()},
        success: function(data){
            $('#list').html(data.view);
            initDataTable('<t>');
        }
    });
});

$('body').on('change', "[name='tblList_length']", function(e) {
    return $.ajax({
        url: ULR + "crm",
        method: "GET",
        dataType: "json",
        data: {perpage: $("[name='tblList_length']").val(), search: $("input[type='search']").val()},
        success: function(data){
            $('#list').html(data.view);
            initDataTable('<t>');
        }
    });
});
//cargar el formulario
$('body').on('click', '[data-click="openForm"]', function(e) {

    //abrir el modal y mostrar loading
    e.preventDefault();
    var mdlForm = $('#mdlForm');
    var loading = $('.loading', mdlForm).first();
    var id = $(this).data('id');
    loading.attr('data-show', 'true');
    mdlForm.modal('show');


    //leer el formulario de clientes
    loadForm(id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);

        loading.attr('data-show', 'false');


        loadInfoForm(id).done(function(data) {
            $('.infoForm').html(data.view);
            loadcontactForm(id).done(function(data) {
                $('.contactForm').html(data.view);
                loadofficeForm(id).done(function(data) {
                    initSelectTypeContact();
                    $('.officeForm').html(data.view);
                    // //inicializar funciones de plugins
                    initSelects();
                    initPlugin();

                }).fail(function(error) {
                    handleErrors(error);
                });

                loadFileForm(id).done(function(data) {
                    $('.fileForm').html(data.view);
                    initSelectTypeFileCrm();
                }).fail(function(error) {
                    handleErrors(error);
                });

                loadTechnicalForm(id).done(function(data) {
                    $('.technicalForm').html(data.view);
                    initSelectServices();
                }).fail(function(error) {
                    handleErrors(error);
                });
                // Checkbox & Radio 2
                $('.icheck_minimal input').iCheck({
                    checkboxClass: 'icheckbox_minimal',
                    radioClass: 'iradio_minimal',
                });
                $('.skin-square input').iCheck({
                    checkboxClass: 'icheckbox_square-red',
                    radioClass: 'iradio_square-red',
                });
            }).fail(function(error) {
                handleErrors(error);
            });

        }).fail(function(error) {
            handleErrors(error);
        });
    }).fail(function(error) {
        handleErrors(error);
    });
});
//cargar el formulario simplificado de clientes
$('body').on('click', '[data-click="openFormSimple"]', function(e) {

    //abrir el modal y mostrar loading
    e.preventDefault();
    var mdlForm = $('#mdlDelete');
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'true');
    mdlForm.modal('show');
    //leer el formulario de clientes
    loadFormSimple().done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        loading.attr('data-show', 'false');
    }).fail(function(error) {
        handleErrors(error);
    });
});

function loadFormSimple() {
    return $.ajax({
        url: ULR + "crm/form-crm/simple",
        method: "GET",
        dataType: "json"
    });
}

//enviar formulario para guardar información
$('body').on('click', '[data-click="saveSimple"]', function(e) {
    e.preventDefault();
    var mdlForm = $('#mdlDelete');
    save().done(function(data) {
        if (data.success) {
            mdlForm.modal('hide');
            getList();
        }
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }).fail(function(error) {
        showErrors(error.responseJSON.errors, '#mdlDelete');
        handleErrors(error);
    });

});

function save() {

    var form = $('#customerSimple');
    var data = form.serialize();
    return $.ajax({
        url: ULR + 'crm/save/simple',
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });
}
function loadForm(id) {
    return $.ajax({
        url: ULR + "crm/form-crm",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

function loadInfoForm(id) {
    return $.ajax({
        url: ULR + "crm/info-crm",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}


function loadcontactForm(id) {
    return $.ajax({
        url: ULR + "crm/contact-form",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

function loadofficeForm(id) {
    return $.ajax({
        url: ULR + "crm/office-form",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

function loadFileForm(id) {
    return $.ajax({
        url: ULR + "crm/file-form",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

function loadTechnicalForm(id) {
    return $.ajax({
        url: ULR + "crm/technical-form",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

function initPlugin() {
    let repeater = $('.repeater-default').repeater({
        defaultValues: defaultValues,
        show: function() {
            $(this).slideDown(500);
            initAddress();
            initSelects();
        },
        hide: function(remove) {
            $(this).slideUp(500);
        }
    });

    if (setList.length > 0) {
        repeater.setList(setList);
    }

    //init Google Maps Search
    initAddress();
    initSelectTypeCrm();
    initSelectMeanContact();
}

function initSelects() {

    setTimeout(function() {
        initHeadquarterOffice();
        initSelectCitiesCustomer();
        initSelectHeadquarters();
        initSelectCommercials();
    }, 500);

}

$('body').on('ifChanged', '[name="customer_national"]', function(event) {
    if (event.target.checked) {
        $(".div-national-heaquarters").show();
        $(".div-local-headquarter").hide();
    } else {
        $(".div-local-headquarter").show();
        $(".div-national-heaquarters").hide();
    }
});

function initSelectHeadquarters() {

    $('[name="headquarters[]"]').select2({
        width: '100%',
        dropdownParent: $('.modal-content', $('#mdlForm')),
        ajax: {
            url: ULR + "crm/headquarters",
            dataType: 'json',
            method: "GET",
            success: selectScroll
        },
        "language": {
            "noResults": () => "No se encontraron sedes disponibles",
            "searching": () => "Consultando..."
        }
    });
}

function initSelectCitiesCustomer() {

    $('[name*="[city_name]"]').select2({
        width: '100%',
        dropdownParent: $('.modal-content', $('#mdlForm')),
        ajax: {
            url: ULR + "crm/cities",
            dataType: 'json',
            method: "GET",
            success: selectScroll
        },
        "language": {
            "noResults": () => "No se encontraron cuidades disponibles",
            "searching": () => "Consultando..."
        },
        cache: true,
        templateResult: dataFormatResultCities,
        templateSelection: dataFormatSelection,
        dropdownCssClass: "bigdrop",
        escapeMarkup: function(m) { return m; },
    });
}

function initSelectTypeCrm() {

    $('.typeCrm').select2({
        width: '100%',
        dropdownParent: $('.modal-content', $('#mdlForm')),
        ajax: {
            url: "crm/type-crm",
            dataType: 'json',
            method: "GET",
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        TYPECRM = select_val;

        //$("#sltCity").val('').trigger('change');
        //$('#sltCity').select2('destroy');
        //initSelectCity();
    });
}

function initSelectTypeContact() {

    $('.typeContact').select2({
        width: '100%',
        dropdownParent: $('.modal-content', $('#mdlForm')),
        ajax: {
            url: "crm/type-contact",
            dataType: 'json',
            method: "GET",
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        CONTACTYPE = select_val;
    });
}

function initSelectTypeFileCrm() {

    $('.typeFileCrm').select2({
        width: '100%',
        dropdownParent: $('.modal-content', $('#mdlForm')),
        ajax: {
            url: "crm/type-file",
            dataType: 'json',
            method: "GET",
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        FILE = select_val;

    });
}

function initSelectMeanContact() {

    $('#mean_contact').select2({
        width: '100%',
        dropdownParent: $('.modal-content', $('#mdlForm')),
        tags: true,
        ajax: {
            url: "crm/mean-contact",
            dataType: 'json',
            method: "GET",
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        APPLICATION = select_val;

        //$("#sltCity").val('').trigger('change');
        //$('#sltCity').select2('destroy');
        //initSelectCity();
    });
}

function initSelectCountryOffice() {

    $('#country_office').select2({
        width: '100%',
        dropdownParent: $('.modal-content', $('#mdlForm')),
        ajax: {
            url: "crm/country-office",
            dataType: 'json',
            method: "GET",
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        COUNTRYOFFICE = select_val;


        initSelectStateOffice();
    });
}

function initSelectStateOffice() {

    $('#state_office').select2({
        width: '100%',
        dropdownParent: $('.modal-content', $('#mdlForm')),
        ajax: {
            url: "crm/states-office/" + 47,
            dataType: 'json',
            method: "GET",
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        STATEOFFICE = select_val;


        initSelectCityOffice();
    });
}

function initSelectCityOffice() {

    $('#city_office').select2({
        width: '100%',
        dropdownParent: $('.modal-content', $('#mdlForm')),
        ajax: {
            url: "crm/city-office/" + STATEOFFICE,
            dataType: 'json',
            method: "GET",
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        CITYOFFICE = select_val;
        initHeadquarterOffice();

    });
}

function initSelectServices(key = false) {
    var selector = '[name*="[service]"]';
    if (key) {
        selector = '[name*="[' + key + '][service]"]';
        console.log('init key', key);
    }
    $(selector).select2({
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
    }).on("select2:open", e => selectScroll());
}

function initHeadquarterOffice() {

    $('.headquarter_id').select2({
        ajax: {
            url: "crm/headquarters",
            dataType: 'json',
            method: "GET",
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        HEADQUARTER_ID = select_val;
        initSelectZone();

    });
}

function initSelectZone() {

    $('#zone_office').select2({
        tags: true,
        ajax: {
            url: "crm/zona-office/" + HEADQUARTER_ID,
            dataType: 'json',
            method: "GET",
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        Zona = select_val;

        //$("#sltCity").val('').trigger('change');
        //$('#sltCity').select2('destroy');
        //initSelectCity();
    });
}

$('body').on('click', '[data-click="save"]', function(e) {

    var mdlForm = $('#mdlForm');
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'true');
    saveForm();
});

function saveForm() {

    var form = $('#customer')[0];
    var formData = new FormData(form);
    return $.ajax({
        url: $(form).attr('action'),
        contentType: false,
        processData: false,
        headers: {
            'X-CSRF-Token': $('#product [name="_token"]').val()
        },
        method: METHOD,
        data: formData,
        success: success,
        error: fail
    });
}

function success(data) {
    if (data.success) {
        var mdlForm = $('#mdlForm');
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        getList();
    }

    swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");

}

function fail(error) {
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'false');
    showErrors(error.responseJSON.errors, '#customer');
    handleErrors(error);
}


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
    });

});

function loadFormDelete(id) {
    return $.ajax({
        url: "crm/delete-form",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

$('body').on('click', '[data-click="delete"]', function(e) {
    e.preventDefault();

    var mdlForm = $('#mdlDelete');
    var loading = $('.loading', mdlForm).first();

    loading.attr('data-show', 'true');

    sendDelete().done(function(data) {
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        getList();
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }).fail(function(error) {
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'false');
        showErrors(error.responseJSON.errors, '#frmDelete');
        handleErrors(error);
    });

});

function sendDelete() {

    var form = $('#frmDelete');
    var data = form.serialize();

    return $.ajax({
        url: "crm/delete",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });

}

$('body').on('ifChecked', '[name="type_customer"]', function(event) {
    if (event.target.value == 0) {
        $('#documentLabel').text("Cédula");
    } else if (event.target.value == 1) {
        $('#documentLabel').text("NIT");
    } else {
        $('#documentLabel').text("CC / NIT");
    }
});

//-- EVENTOS PARA FORMULARIO DE DOCUMENTOS DE CLIENTE -----

$('body').on('click', '[data-click="addRow"]', function(e) {
    e.preventDefault();
    var loading = $('.loading-min').first();
    loading.attr('data-show', 'true');
    addDocumentRow().done(function(data) {
        $('.tbl-documents tbody').append(data.row);
        loading.attr('data-show', 'false');
        initSelectTypeFileCrm();
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

$('body').on('click', '[data-click="removeRowDocument"]', function(e) {
    e.preventDefault();

    if ($(this + ' input[name*="id"]').length > 0) {

    }

    let key = $(this).data('id');
    $('tr[data-id="' + key + '"]').remove();
});

function addDocumentRow() {
    return $.ajax({
        url: ULR + "crm/add-document-row",
        method: "GET",
        data: {},
        dataType: "json"
    });
}

function loadDocumentForm(id) {
    return $.ajax({
        url: ULR + "crm/file-form",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

function initAddress() {
    var autocomplete;
    $('.searchMapInput').each(function(index, input) {
        autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.addListener('place_changed', function() {
            var place = autocomplete.getPlace();

            var name = removeNameStringInput(input, 'address');
            $("[name='" + name + "[address]']").val(getAddress(place));
            $("[name='" + name + "[city_name]']").val(gettowncity(place.address_components));
            $("[name='" + name + "[lat]']").val(place.geometry.location.lat());
            $("[name='" + name + "[lng]']").val(place.geometry.location.lng());
        });
    });
}

//registrar el cliente en la tabla usario para que tenga acceso al sistema
$('body').on('change', '[data-change="saveCategoryCustomer"]', function(e) {

    let category = $(this).val();
    let id = $(this).data('id');

    saveSelectCategoryCRM(id, category).done(function(data) {
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }).fail(function(error) {
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'false');
        showErrors(error.responseJSON.errors, '#customer');
        handleErrors(error);
    });
});

function saveSelectCategoryCRM(id, category) {
    return $.ajax({
        url: ULR + "crm/save-customer/" + id,
        method: "GET",
        data: {
            category: category,
            id: id
        },
        dataType: "json"
    });
}

//-- EVENTOS FORMULARIO PARA ASIGNACION DE TÉCNICOS A CLIENTE -----

$('body').on('click', '[data-click="addRowTechnical"]', function(e) {
    e.preventDefault();
    var loading = $('.loading-min').first();
    loading.attr('data-show', 'true');
    addTechnicalRow().done(function(data) {
        $('.tbl-technical tbody').append(data.row);
        initSelectServices(data.key);
        initSelectTechnicals(data.key);
        initSelectScheduleStatuses(data.key);
        loading.attr('data-show', 'false');
    }).fail(function(error) {
        handleErrors(error);
        loading.attr('data-show', 'false');
    });
});

function initSelectTechnicals(key = false) {


    var selector = '[name*="[user][]"]';
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
    }).on("select2:open", e => selectScroll());

}

function initSelectCommercials(key = false) {


    var selector = '[name*="user_id"]';
    if (key) {
        selector = '[name*="[' + key + '][user]"]';
    }
    $(selector).select2({
        placeholder: "Dejar vacío para cualquiera",
        allowClear: true,
        width: "100%",
        ajax: {
            url: ULR + "crm/users",
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

function initSelectScheduleStatuses(key = false) {
    var selector = '[name*="[init_status]"], [name="init_status"]';
    if (key) {
        selector = '[name*="[' + key + '][init_status]"], [name="init_status"]';
    }
    $(selector).select2({
        placeholder: "Seleccione una opcion",
        allowClear: true,
        width: "100%",
        ajax: {
            url: ULR + "crm/schedule-statuses",
            dataType: 'json',
            method: "GET",
            success: selectScroll
        },
        cache: true,
    }).on("select2:open", e => selectScroll());
}

function addTechnicalRow() {
    return $.ajax({
        url: ULR + "crm/add-technical-row",
        method: "GET",
        data: {},
        dataType: "json"
    });
}

$('body').on('click', '[data-click="removeRowTechnical"]', function(e) {

    if ($(this + ' input[name*="id"]').length > 0) {


        let key = $(this).data('id');
        let id = $('input[name="method[' + key + '][id]"]').val();
        KEY_DELETE = key;

        var mdlForm = $('#mdlDeleteTechnical');
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'true');



        loadFormDeleteTechnical(id).done(function(data) {
            if (!data.success) {
                setTimeout(() => {

                }, 500);
                mdlForm.modal('hide');
                $('tr[data-id="' + KEY_DELETE + '"]').remove();
            } else {
                $('.modal-content', mdlForm).html(data.view);
                mdlForm.modal('show');
            }


            loading.attr('data-show', 'false');
        });
    } else {
        KEY_DELETE = key;
        let key = $(this).data('id');
        let id = $('input[name="method[' + key + '][id]"]').val();
        $('tr[data-id="' + KEY_DELETE + '"]').remove();
        e.preventDefault();
    }


});

function loadFormDeleteTechnical(id) {
    return $.ajax({
        url: "crm/deleteTechnical-form",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

$('body').on('click', '[data-click="deleteTechnical"]', function(e) {
    e.preventDefault();

    var mdlForm = $('#mdlDeleteTechnical');
    var loading = $('.loading', mdlForm).first();

    loading.attr('data-show', 'true');

    sendDeleteTechnical().done(function(data) {
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        console.log(KEY_DELETE);
        $('tr[data-id="' + KEY_DELETE + '"]').remove();
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }).fail(function(error) {
        loading.attr('data-show', 'false');
        showErrors(error.responseJSON.errors, '#technical');
        handleErrors(error);
    });

});

function sendDeleteTechnical() {

    var form = $('#frmDeleteTechnical');
    var data = form.serialize();

    return $.ajax({
        url: "crm/deleteTechnical",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });
}