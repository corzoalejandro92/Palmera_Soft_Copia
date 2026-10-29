var PRODUCTS_SELECTED = [];

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
        url: ULR + "convection/list",
        method: "GET",
        dataType: "json"
    });
}

//código para abrir modal (formulario)
$('body').on('click', '[data-click="openForm"]', function(e) {
    e.preventDefault();
    var mdlForm = $('#mdlDelete');
    var loading = $('.loading', mdlForm).first();
    var area = $(this).data('id');
    loading.attr('data-show', 'true');

    loadForm(area).done(function(data) {
        loading.attr('data-show', 'false');
        $('.modal-content', mdlForm).html(data.view);
        $('.skin-square input').iCheck({ checkboxClass: 'icheckbox_square-red', radioClass: 'iradio_square-red', });
        initProductSelects();
        initSelectCategory();
        mdlForm.modal('show');
    }).fail(function(error) {
        handleErrors(error);
    });
});

function loadForm(id) {
    return $.ajax({
        url: ULR + "convection/form",
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
                url: ULR + "convection/products",
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
        url: ULR + "convection/add-product-row",
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
    var form = $('#frm');
    var data = form.serialize();
    return $.ajax({
        url: ULR + 'convection/save',
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
        url: ULR + "convection/delete-form",
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
        url: ULR + "convection/delete",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });
}

//autocompletar nombres y siglas según selección de productos
function makeConvectionName() {



    PRODUCTS_SELECTED = [];
    const select = '[name *= "[product]"]';
    $(select).each(function(index, element) {
        PRODUCTS_SELECTED.push($(element).select2('data')[0].name);
    });

    var name = PRODUCTS_SELECTED.join('+');
    var slug = PRODUCTS_SELECTED.map(function(item, index) {
        return item[0].toUpperCase();
    }).join('+');

    $("[name='name']").val(name.trim());
    $("[name='slug']").val(slug.trim());
}
function initSelectCategory() {

    $('#category_convention').select2({
        width: '100%',
        //tags: true,
        ajax: {
            url: ULR + "convection/category",
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