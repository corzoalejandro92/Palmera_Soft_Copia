METHOD = 'POST';
CATEGORY = '';
KEY_DELETE = '';

//cargar el formulario
$('body').on('click', '[data-click="openForm"]', function(e) {

    //abrir el modal y mostrar loading
    e.preventDefault();
    var mdlForm = $('#mdlForm');
    var loading = $('.loading', mdlForm).first();
    var id = $(this).data('id');
    loading.attr('data-show', 'true');
    mdlForm.modal('show');


    //leer el formulario
    loadForm(id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);

        loading.attr('data-show', 'false');
        // Checkbox & Radio 2
        $('.icheck_minimal input').iCheck({
            checkboxClass: 'icheckbox_minimal',
            radioClass: 'iradio_minimal',
        });
        loadDetailForm(id).done(function(data) {

            $('.detailForm').html(data.view);
            if (data.showMechanism) {
                $('[name="mechanism"]').parent('div').removeClass('display-hidden');
                $('[name="avatar_mechanism"]').parent('div').removeClass('display-hidden');
            }

            $(".colorpicker").spectrum({
                //color: "#ff2626"
            });

            loadSpecificationForm(id).done(function(data) {
                $('.specification').html(data.view);
                $('.icheck_minimal input').iCheck({
                    checkboxClass: 'icheckbox_minimal',
                    radioClass: 'iradio_minimal',
                });
                initSelects();
                initPlugin();
            });

            loadFileForm(id).done(function(data) {
                $('.documents').html(data.view);
            });

            function loadFileForm(id) {
                return $.ajax({
                    url: ULR + "product/file-form",
                    method: "GET",
                    data: { id: id },
                    dataType: "json"
                });
            }

            loadMethodForm(id).done(function(data) {
                $('.methods').html(data.view);
            });

            function loadMethodForm(id) {
                return $.ajax({
                    url: ULR + "product/method-form",
                    method: "GET",
                    data: { id: id },
                    dataType: "json"
                });
            }
        });
    });
});

$('body').on('click', '[data-click="save"]', function(e) {

    var mdlForm = $('#mdlForm');
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'true');
    saveForm();
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
        url: "product/delete",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });

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
        url: "product/delete-form",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

$('body').on('click', '[data-click="deleteDocument"]', function(e) {
    e.preventDefault();

    var mdlForm = $('#mdlDelete');
    var loading = $('.loading', mdlForm).first();

    loading.attr('data-show', 'true');

    sendDeleteDocument().done(function(data) {
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        console.log(KEY_DELETE);
        $('tr[data-id="' + KEY_DELETE + '"]').remove();
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }).fail(function(error) {
        loading.attr('data-show', 'false');
        showErrors(error.responseJSON.errors, '#documents');
        handleErrors(error);
    });

});

function sendDeleteDocument() {

    var form = $('#frmDeleteDocument');
    var data = form.serialize();

    return $.ajax({
        url: "product/delete-doc",
        method: $('[name="_document"]', form).val(),
        data: data,
        dataType: "json"
    });

}


//lista de productos

$(document).ready(function() {

    getList();
});

function getList() {
    var list = $('#list');
    $('.loading', list).attr('data-show', 'true');
    loadList().done(function(data) {
        list.html(data.view);
        $('.loading', list).attr('data-show', 'false');
        initDataTable();
    });
}

function loadList() {
    return $.ajax({
        url: "products/form-list",
        method: "GET",
        dataType: "json"
    });
}

function loadForm(id) {
    return $.ajax({
        url: ULR + "product/form-product",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

function loadDetailForm(id) {
    return $.ajax({
        url: ULR + "product/detail-form",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

function initSelects() {

    setTimeout(function() {
        initSelectUnit();
        initSelectApplication();
        initSelectCategory();
        initSelectMechanism();
        initSelectService();
        initSelectInfestation();
        initSelectIngredient();
    }, 100);

}

function initSelectUnit() {

    $('#unit').select2({
        width: '100%',
        dropdownParent: $('.modal-content', $('#mdlForm')),
        tags: true,
        ajax: {
            url: "product/unit",
            dataType: 'json',
            method: "GET",
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        UNITS = select_val;
    });
}

function initSelectApplication() {

    $('#application').select2({
        width: '100%',
        dropdownParent: $('.modal-content', $('#mdlForm')),
        tags: true,
        ajax: {
            url: "product/application",
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

function initSelectCategory() {

    $('#category').select2({
        width: '100%',
        dropdownParent: $('.modal-content', $('#mdlForm')),
        ajax: {
            url: "product/category",
            dataType: 'json',
            method: "GET",
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        CATEGORY = select_val;
        let id = '';
        if (CATEGORY.includes('3')) {
            $('[name="mechanism"]').parent('div div').removeClass('display-hidden');
            $('[name="avatar_mechanism"]').parent('div').parent('div').removeClass('display-hidden');
        } else {
            $('[name="mechanism"]').parent('div div').addClass('display-hidden');
            $('[name="avatar_mechanism"]').parent('div').parent('div').addClass('display-hidden');
        }

        if (CATEGORY.includes('1')) {
            $('[name="related_products[]"]').parent('div').parent('div').removeClass('display-hidden');
            $('.colordiv').parent('div').removeClass('display-hidden');


        } else {
            $('[name="related_products[]"]').parent('div').parent('div').addClass('display-hidden');
            $('.colordiv').parent('div').addClass('display-hidden');
        }

    }).on("select2:unselect", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        ROLE_ID = select_val;
        if (!CATEGORY.includes('3')) {
            $('[name="mechanism"]').parent('div').parent('div').removeClass('display-hidden');
            $('[name="avatar_mechanism"]').parent('div').parent('div').removeClass('display-hidden');
        }
    });
}


function initSelectMechanism() {

    $('#mechanism').select2({
        width: '100%',
        dropdownParent: $('.modal-content', $('#mdlForm')),
        tags: true,
        ajax: {
            url: "product/mechanism",
            dataType: 'json',
            method: "GET",
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        MECHANISM = select_val;

        //$("#sltCity").val('').trigger('change');
        //$('#sltCity').select2('destroy');
        //initSelectCity();
    });
}

function initSelectService() {

    $('#service').select2({
        tags: true,
        width: '100%',
        dropdownParent: $('.modal-content', $('#mdlForm')),
        ajax: {
            url: "product/service",
            dataType: 'json',
            method: "GET",
        },
        cache: true,
        templateResult: dataFormatResultService,
        templateSelection: dataFormatSelection,
        dropdownCssClass: "bigdrop",
        escapeMarkup: function(m) { return m; },
        "language": {
            "noResults": () => "No se encontraron plagas registradas en el sistema.",
            "searching": () => "Consultando..."
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        SERVICE = select_val;


        //$("#sltCity").val('').trigger('change');
        //$('#sltCity').select2('destroy');
        //initSelectCity();
    });
}

function initSelectInfestation() {

    $('#infestation').select2({
        width: '100%',
        dropdownParent: $('.modal-content', $('#mdlForm')),
        ajax: {
            url: "product/infestation",
            dataType: 'json',
            method: "GET",
        },
        cache: true,
        templateResult: dataFormatResultInfestation,
        templateSelection: dataFormatSelection,
        dropdownCssClass: "bigdrop",
        escapeMarkup: function(m) { return m; },
        "language": {
            "noResults": () => "No se encontraron plagas registradas en el sistema.",
            "searching": () => "Consultando..."
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        INFESTATION = select_val;


        //$("#sltCity").val('').trigger('change');
        //$('#sltCity').select2('destroy');
        //initSelectCity();
    });
}

function initSelectIngredient() {

    $('[name="related_products[]"]').select2({
        width: '100%',
        dropdownParent: $('.modal-content', $('#mdlForm')),
        ajax: {
            url: "product/related_products",
            dataType: 'json',
            method: "GET",
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
        var select_val = selected_element.val();
        INGREDIENT = select_val;
    });
}

function loadSpecificationForm(id) {
    return $.ajax({
        url: ULR + "product/specification-form",
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
        },
        hide: function(remove) {
            $(this).slideUp(500);
        }
    });

    if (setList.length > 0) {
        console.log(setList);
        repeater.setList(setList);
    }
}

function saveForm() {

    var form = $('#product')[0];
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


//-- EVENTOS PARA FORMULARIO DE DOCUMENTOS DE USUARIO -----

$('body').on('click', '[data-click="addRow"]', function(e) {
    e.preventDefault();
    var loading = $('.loading-min').first();
    loading.attr('data-show', 'true');
    addDocumentRow().done(function(data) {
        $('.tbl-documents tbody').append(data.row);
        loading.attr('data-show', 'false');
    }).fail(function(error) {
        handleErrors(error);
        loading.attr('data-show', 'false');
    });
});

function addDocumentRow() {
    return $.ajax({
        url: ULR + "product/add-document-row",
        method: "GET",
        data: {},
        dataType: "json"
    });
}

$('body').on('change', '.custom-file input', function(e) {
    let key = $(this).data('file');
    let name = e.target.files[0].name;
    $(this).next('.custom-file-label[data-label="' + key + '"]').html(name);
});


$('body').on('click', '[data-click="removeRow"]', function(e) {

    if ($(this + ' input[name*="id"]').length > 0) {

        let key = $(this).data('id');

        var mdlForm = $('#mdlDelete');
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'true');


        KEY_DELETE = key;
        let id = $('input[name="document[' + key + '][id]"]').val();
        e.preventDefault();



        loadFormDeleteDocument(id).done(function(data) {
            if (!data.success) {
                setTimeout(() => {
                    mdlForm.modal('hide');
                    $('tr[data-id="' + KEY_DELETE + '"]').remove();
                }, 500);
            } else {
                mdlForm.modal('show');
            }

            $('.modal-content', mdlForm).html(data.view);
            loading.attr('data-show', 'false');
        });
    }


});

function loadFormDeleteDocument(id) {
    return $.ajax({
        url: ULR + "product/document",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}


//--- FUNCIONES PARA FORMULARIOS DE DOCUMENTOS DE USUARIO

function loadDocumentForm(id) {
    return $.ajax({
        url: ULR + "product/document-form",
        method: "GET",
        data: { id: id },
        dataType: "json"
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
    showErrors(error.responseJSON.errors, '#product');
    handleErrors(error);
}

$('body').on('click', '[data-upload="fileM"]', function(e) {
    $('[data-input="fileImageM"]').click();
});


function loadImageM(input) {

    var element = $('[data-upload="fileM"]');
    var loading = $('.loading', element).first();
    loading.attr('data-show', 'true');

    if (input.files && input.files.length > 0) {
        var reader = new FileReader();
        var file = input.files[0];
        reader.readAsDataURL(file);
        reader.onload = function() {

            if (validateImg(file.type)) {
                $('.avatarMechanism', element).attr('src', reader.result);
                $('[name="avatar_mechanism" ]', element).val(reader.result);
            }
            loading.attr('data-show', 'false');
        }
    } else {
        loading.attr('data-show', 'false');
    }

}



///--RENDERIZAR SELECTS DE SERVICIOS
function dataFormatResultService(data) {
    if (data.loading) return dataFormatSelection({ text: 'Consultando...' });

    var markup = "<div class='select2-result-repository clearfix'>" +
        "<div class='select2-result-repository__avatar'>" +
        "<i class='success font-large-2 " + data.avatar + "'></i>" +
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

//-- EVENTOS DE FORMULARIO PARA IMAGENES DE METODOS -----

$('body').on('click', '[data-click="addRowMethod"]', function(e) {
    e.preventDefault();
    var loading = $('.loading-min').first();
    loading.attr('data-show', 'true');
    addMethodRow().done(function(data) {
        $('.tbl-methods tbody').append(data.row);
        loading.attr('data-show', 'false');
    }).fail(function(error) {
        handleErrors(error);
        loading.attr('data-show', 'false');
    });
});

function addMethodRow() {
    return $.ajax({
        url: ULR + "product/add-method-row",
        method: "GET",
        data: {},
        dataType: "json"
    });
}

$('body').on('click', '[data-click="removeRowMethod"]', function(e) {

    if ($(this + ' input[name*="id"]').length > 0) {


        let key = $(this).data('id');
        let id = $('input[name="method[' + key + '][id]"]').val();
        KEY_DELETE = key;

        var mdlForm = $('#mdlDeleteMethod');
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'true');



        loadFormDeleteMethod(id).done(function(data) {
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

function loadFormDeleteMethod(id) {
    return $.ajax({
        url: "product/deleteMethod",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

$('body').on('click', '[data-click="deleteMethod"]', function(e) {
    e.preventDefault();

    var mdlForm = $('#mdlDeleteMethod');
    var loading = $('.loading', mdlForm).first();

    loading.attr('data-show', 'true');

    sendDeleteMethod().done(function(data) {
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        console.log(KEY_DELETE);
        $('tr[data-id="' + KEY_DELETE + '"]').remove();
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }).fail(function(error) {
        loading.attr('data-show', 'false');
        showErrors(error.responseJSON.errors, '#methods');
        handleErrors(error);
    });

});

function sendDeleteMethod() {

    var form = $('#frmDeleteMethod');
    var data = form.serialize();

    return $.ajax({
        url: "product/delete-method",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });

}