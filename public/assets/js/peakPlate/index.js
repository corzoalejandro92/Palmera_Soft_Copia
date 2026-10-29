//DEFINIR CONSTANTES
HEADQUARTER_ID = '';
ROLE_ID = '';

COUNTRY_ID = 47;
STATE_ID = '';
CITY_ID = '';
//código para leer tabla

$('body').ready(function() {
    getList();
    
});

function getList() {
    var list = $('#list');
    $('.loading', list).attr('data-show', 'true');
    loadList().done(function(data) {
        list.html(data.view);
        $('.loading', list).attr('data-show', 'false');
        try {
            initDataTable();
        } catch (error) {

        }
    }).fail(function(error) {
        handleErrors(error);
    });
}

function loadList() {
    return $.ajax({
        url: ULR + "peak-plate/list",
        method: "GET",
        dataType: "json"
    });
}

//código para abrir modal (formulario)
$('body').on('click', '[data-click="openForm"]', function(e) {
    e.preventDefault();
    var mdlForm = $('#mdlDelete');
    var loading = $('.loading', mdlForm).first();
    var id = $(this).data('id');
    loading.attr('data-show', 'true');

    loadForm(id).done(function(data) {
        loading.attr('data-show', 'false');
        $('.modal-content', mdlForm).html(data.view);
        mdlForm.modal('show');
        //inicializar funciones de plugins
        initSelectStates();
        initSelectZones();
        initSelectDaysWeek();
        initSelectVehiculo();
        initSelectDigits();
        initSelectRules();
        initVerifyWeek();
        if (data.edit) {
            STATE_ID = $('#state_id').val();
            initSelectCities();
        }

        if (data.edit == true) {
            initSelectStates(data.state);
        }
        hideLoading();
    }).fail(function(error) {
        handleErrors(error);
    });
});
function initVerifyWeek() {
    $("input[type=date]").change(function () {
        let start_date = $('#start_date').val();
        let end_date = $('#end_date').val();
        if (start_date == end_date) {
            $('#verify_days_week').val('1')
        } else {
            $('#verify_days_week').val('0')
        }
  });
}

function loadForm(id) {
    return $.ajax({
        url: ULR + "peak-plate/form",
        method: "GET",
        data: {
            id: id
        },
        dataType: "json"
    });
}

function initProductSelects(key = false) {
    const select = key !== false ? '[name*="[' + key + '][product]"]' : '[name *= "[product]"]';
    $(select).each(function() {
        $(this).select2({
            width: '100%',
            dropdownParent: $('.modal-content', $('#mdlDelete')),
            placeholder: "Seleccione un producto",
            ajax: {
                url: ULR + "peak-plate/products",
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
                "searching": () => "Consultando...",
                "errorLoading": () => "No se puede leer la respuesta del servidor"
            }
        }).on("select2:select", function(e) {
            var data = e.params.data;
            makeConvectionName();
            //$('[name*="[' + key + '][unity]"]').val(e.params.data.unit.name);
        }).on("select2:unselect", function(e) {
            var data = e.params.data;
            makeConvectionName();
            //$('[name*="[' + key + '][unity]"]').val(e.params.data.unit.name);
        });
    });
}

//añadir una nueva fila de productos
$('body').on('click', '[data-click="addProductRow"]', function(e) {

    e.preventDefault();
    var loading = $('.loading-min').first();
    loading.attr('data-show', 'true');
    addProductRow().done(function(data) {
        $('.tbl-documents tbody').append(data.row);
        loading.attr('data-show', 'false');
        initProductSelects(data.key);
    }).fail(function(error) {
        handleErrors(error);
        loading.attr('data-show', 'false');
    });
});

function addProductRow() {
    return $.ajax({
        url: ULR + "peak-plate/add-product-row",
        method: "GET",
        data: {},
        dataType: "json"
    });
}

//remover una fila de productos
$('body').on('click', '[data-click="removeProductRow"]', function(e) {
    e.preventDefault();

    if ($(this + ' input[name*="id"]').length > 0) {
        let key = $(this).data('id');
        $('tr[data-id="' + key + '"]').remove();
        makeConvectionName();
    }
});

//enviar formulario para guardar información
$('body').on('click', '[data-click="save"]', function(e) {
    e.preventDefault();
    var mdlForm = $('#mdlDelete');
    saveConvection().done(function(data) {
        if (data.success) {
            mdlForm.modal('hide');
            getList();
        }
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }).fail(function(error) {
        handleErrors(error);
        showErrors(error.responseJSON.errors, '#mdlDelete');
    });

});

function saveConvection() {
    var form = $('#form-peak-plate');
    var data = form.serialize();
    return $.ajax({
        url: ULR + 'peak-plate/save',
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });
}

//abrir formulario de eliminacion de registro
$('body').on('click', '[data-click="openFormDelete"]', function(e) {
    e.preventDefault();
    var id = $(this).data('id');
    var mdlForm = $('#mdlDelete');
    loadFormDelete(id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        mdlForm.modal('show');
    }).fail(function(error) {
        handleErrors(error);
    });

});

function loadFormDelete(id) {
    return $.ajax({
        url: ULR + "peak-plate/delete-form",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

$('body').on('click', '[data-click="delete"]', function(e) {
    e.preventDefault();

    var mdlForm = $('#mdlDelete');

    sendDelete().done(function(data) {
        mdlForm.modal('hide');
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
        getList();
    }).fail(function(error) {
        showErrors(error.responseJSON.errors, '#frmDeleteDocument');
        handleErrors(error);
    });

});

function sendDelete() {

    var form = $('#frmDelete');
    var data = form.serialize();

    return $.ajax({
        url: ULR + "peak-plate/delete",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });
}

//autocompletar nombres y siglas según selección de productos

function initSelectStates(state) {

    $('#state_id').select2({
        width: '100%',
        ajax: {
            url: "inventory/states/" + COUNTRY_ID,
            dataType: 'json',
            method: "GET",
            success: function() {
                //$('#state_id').val(state).trigger('change');
            }
        }
    }).on("select2:select", function(e) {
        $('#city_id').empty();
        initSelectCities();

    });
}

function initSelectCities(STATE_ID) {
    let state_id = $('#state_id').val();
    $('#city_id').select2({
        width: '100%',
        tags: false,
        ajax: {
            url: "inventory/cities/" + state_id,
            dataType: 'json',
            method: "GET"
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        var text = $("#city_id option:selected").text();
        $("[name='name']").val(text);
        CITY_ID = select_val;
        initSelectZones();

    });
}
function initSelectRules() {

    $('#rule_id').select2({
        width: '100%',
        tags: false,
        ajax: {
            url: "peak-plate/rules",
            dataType: 'json',
            method: "GET"
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        var text = $("#city_id option:selected").text();
        $("[name='name']").val(text);
        CITY_ID = select_val;
        initSelectZones();

    });
}
var days_week = [
    {
        id:'Monday',
        text: 'Lunes'
},
{
        id:'Tuesday',
        text: 'Martes'
},
{
        id:'Wednesday',
        text: 'Miercoles'
},
{
        id:'Thursday',
        text: 'Jueves'
},
{
        id:'Friday',
        text: 'Viernes'
},
{
        id:'Saturday',
        text: 'Sabado'
},
{
        id:'Sunday',
        text: 'Domingo'
},
];
function initSelectDaysWeek() {

    $('#days_week').select2({
        width: '100%',
        multiple: true,
        data:days_week,
    });
}
var digits = [
    {
        id: 0,
        text: '0'
    },
    {
        id: 1,
        text: '1'
    },
    {
        id: 2,
        text: '2'
    },
    {
        id: 3,
        text: '3'
    },
    {
        id: 4,
        text: '4'
    },
    {
        id: 5,
        text: '5'
    },
    {
        id: 6,
        text: '6'
    },
    {
        id: 7,
        text: '7'
    },
    {
        id: 8,
        text: '8'
    },
    {
        id: 9,
        text: '9'
    },
];
function initSelectDigits() {
    let ELEMENTSELECT = $('#digits');
    let selected = ELEMENTSELECT.data('select');
    ELEMENTSELECT.select2({
        width: '100%',
        multiple: true,
        data: digits,
        
    });
    if (typeof selected == "object") {
        ELEMENTSELECT.val(selected).trigger('change');
    }
}
function initSelectVehiculo() {

    $('#vehicletype').select2({
        tags: false,
        width:'100%',
        ajax: {
            url: "vehicles/type",
            dataType: 'json',
            method: "GET",
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        VEHICULO = select_val;

        //$("#sltCity").val('').trigger('change');
        //$('#sltCity').select2('destroy');
        //initSelectCity();
    });
}
function initSelectZones() {

    $('#zones').select2({
        width: '100%',
        tags: false,
        ajax: {
            url: "inventory/zones/" + CITY_ID,
            dataType: 'json',
            method: "GET",
        }
    }).on('select2:close', function(e) {
        var select2SearchField = $(this).parent().find('.select2-search__field');
        var setfocus = setTimeout(function() {
            select2SearchField.focus();
        }, 100);
    });
}