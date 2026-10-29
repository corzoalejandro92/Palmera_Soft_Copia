$('body').on('click', '[data-click="openForm"]', function(e) {

    //abrir el modal y mostrar loading
    e.preventDefault();
    var mdlForm = $('#mdlForm');
    var loading = $('.loading', mdlForm).first();
    var id = $(this).data('id');
    loading.attr('data-show', 'true');
    mdlForm.modal('show');


    //leer el formulario de usuarios
    loadForm(id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        initSelectInfestation();
        initSelectHeadquarters();
        //inicializar funciones de plugins
        initPlugin();
        loading.attr('data-show', 'false');
    }).fail(function(error) {
        handleErrors(error);
    });

});

$('body').on('click', '[data-click="save"]', function(e) {

    var mdlForm = $('#mdlForm');
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'true');
    saveForm().done(function(data) {
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
        getList();
    }).fail(function(error) {
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'false');
        showErrors(error.responseJSON.errors, '#service');
        handleErrors(error);
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
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }).fail(function(error) {
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'false');
        showErrors(error.responseJSON.errors, '#frmDelete');
        handleErrors(error);
    });

});

$(document).ready(function() {
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

$('body').on('keyup', "[name='search']", delay(function(e) {
    getList(this.value);

},300));
$('body').on('click', "[name='search']", delay(function(e) {
    getList(this.value);
},1));
function getList(search='') {
    var list = $('#list');
    $('.loading', list).attr('data-show', 'true');
    loadList(search).done(function(data) {
        list.html(data.view);
        $('.loading', list).attr('data-show', 'false');
    }).fail(function(error) {
        handleErrors(error);
    });
}

function loadList(search) {
    return $.ajax({
        url: "services/form-list?search="+search,
        method: "GET",
        dataType: "json"
    });
}

function loadForm(id) {
    return $.ajax({
        url: ULR + "services/form-service",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

function loadFormDelete(id) {
    return $.ajax({
        url: "services/delete-form",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

function saveForm() {

    var form = $('#service');
    var data = form.serialize();
    return $.ajax({
        url: "services/save",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });
}

function sendDelete() {

    var form = $('#frmDelete');
    var data = form.serialize();

    return $.ajax({
        url: "services/delete",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });

}

function initPlugin() {

    $(".select2-icons").select2({
        width: '100%',
        dropdownParent: $('.modal-content', $('#mdlForm')),
        minimumResultsForSearch: Infinity,
        templateResult: iconFormat,
        templateSelection: iconFormat2,
        escapeMarkup: function(es) { return es; }
    });


}


//EVENTOS Y FUNCIONES PARA COBERTURAS Y SERVICIOS
//--FUNCIONES PARA AÑADIR/ELIMINAR COLUMNAS A LOS INSUMOS

$('body').on('click', '[data-click="addCoverageRow"]', function(e) {

    e.preventDefault();
    var loading = $('.loading-min', '.tbl-coverages').first();
    loading.attr('data-show', 'true');
    addCoverageRow().done(function(data) {
        $('.tbl-coverages tbody').append(data.row);
        initSelectHeadquarters();
        loading.attr('data-show', 'false');
    }).fail(function(error) {
        handleErrors(error);
        loading.attr('data-show', 'false');
    });
});

$('body').on('click', '[data-click="removeCoverageRow"]', function(e) {

    if ($(this + ' input[name*="id"]').length > 0) {

        let service = $(this).data('service');
        let key = $(this).data('id');

        var mdlForm = $('#mdlDelete');
        var loading = $('.loading-min', '.tbl-coverages').first();
        loading.attr('data-show', 'true');

        KEY_DELETE = key;
        let id = $('input[name="specificationService[' + key + '][id]"]').val();
        e.preventDefault();

        loadFormDeleteCoverage(id).done(function(data) {

            if (!data.success) {
                $('tr[data-id="' + KEY_DELETE + '"]').remove();
                $('tr[data-id="' + KEY_DELETE + '-2"]').remove();
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

$('body').on('click', '[data-click="deleteCoverage"]', function(e) {
    e.preventDefault();

    var mdlForm = $('#mdlDelete');
    var loading = $('.loading', mdlForm).first();

    loading.attr('data-show', 'true');

    sendDeleteCoverage().done(function(data) {
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        $('tr[data-id="' + KEY_DELETE + '"]').remove();
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }).fail(function(error) {
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'false');
        showErrors(error.responseJSON.errors, '#frmDeleteDocument');
        handleErrors(error);
    });

});

function addCoverageRow() {
    return $.ajax({
        url: ULR + "services/add-coverage-row",
        method: "GET",
        dataType: "json"
    });
}

function loadFormDeleteCoverage(id) {
    return $.ajax({
        url: "services/delete-coverage-form",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

function sendDeleteCoverage() {

    var form = $('#frmDeleteCoverage');
    var data = form.serialize();

    return $.ajax({
        url: "services/delete-coverage",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });

}


$('body').on('click', '[data-click="addParameterRow"]', function(e) {

    e.preventDefault();
    var loading = $('.loading-min', '.tbl-parameters').first();
    loading.attr('data-show', 'true');
    addParameterRow().done(function(data) {
        $('.tbl-parameters tbody').append(data.row);
        initSelectHeadquarters();
        loading.attr('data-show', 'false');
    }).fail(function(error) {
        handleErrors(error);
        loading.attr('data-show', 'false');
    });
});

$('body').on('click', '[data-click="removeParameterRow"]', function(e) {

    if ($(this + ' input[name*="id"]').length > 0) {

        let service = $(this).data('service');
        let key = $(this).data('id');

        var mdlForm = $('#mdlDelete');
        var loading = $('.loading-min', '.tbl-parameters').first();
        loading.attr('data-show', 'true');

        KEY_DELETE = key;
        let id = $('input[name="parameterService[' + key + '][id]"]').val();
        e.preventDefault();

        loadFormDeleteParameter(id).done(function(data) {

            if (!data.success) {
                $('tr[data-id="' + KEY_DELETE + '"]').remove();
                $('tr[data-id="' + KEY_DELETE + '-2"]').remove();
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

$('body').on('click', '[data-click="deleteParameter"]', function(e) {
    e.preventDefault();

    var mdlForm = $('#mdlDelete');
    var loading = $('.loading', mdlForm).first();

    loading.attr('data-show', 'true');

    sendDeleteParameter().done(function(data) {
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        $('tr[data-id="' + KEY_DELETE + '"]').remove();
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }).fail(function(error) {
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'false');
        showErrors(error.responseJSON.errors, '#frmDeleteDocument');
        handleErrors(error);
    });

});

function addParameterRow() {
    return $.ajax({
        url: ULR + "services/add-parameter-row",
        method: "GET",
        dataType: "json"
    });
}

function loadFormDeleteParameter(id) {
    return $.ajax({
        url: "services/delete-parameter-form",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

function sendDeleteParameter() {

    var form = $('#frmDeleteParameter');
    var data = form.serialize();

    return $.ajax({
        url: "services/delete-parameter",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });

}





function initSelectInfestation() {
    var selector = '[name="infestations[]"]';

    $(selector).select2({
        width: '100%',
        dropdownParent: $('.modal-content', $('#mdlForm')),
        placeholder: "Seleccione uno o mas controles",
        multiple: true,
        ajax: {
            url: ULR + "service/infestations/",
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

function initSelectHeadquarters() {
    var selector = '[name*="[headquarter_id][]"]';

    $(selector).select2({
        width: '100%',
        dropdownParent: $('.modal-content', $('#mdlForm')),
        placeholder: "Seleccione una o mas sedes",
        multiple: true,
        ajax: {
            url: ULR + "services/headquarters/",
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
        dropdownCssClass: "bigdrop",
        closeOnSelect: false,
        escapeMarkup: function(m) { return m; },
        "language": {
            "noResults": () => "No se encontraron sedes para este servicio.",
            "searching": () => "Consultando..."
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var area = selected_element.val();
    }).on("select2:open", e => selectScroll());
}