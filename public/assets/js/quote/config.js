EDITOR = null;
var externalLink = "../../../../../../../../public/storage/ckeditor/clipboard/";

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

        $('.dropdown-toggle').dropdown();
    }).fail(function(error) {
        handleErrors(error);
    });
}

function loadList() {
    return $.ajax({
        url: ULR + "quotes/listConfig",
        method: "GET",
        dataType: "json"
    });
}

$('body').on('click', '[data-click="openConfigForm"]', function(e) {
    e.preventDefault();
    var mdlForm = $('#mdlForm');
    var id = $(this).data('id');

    loadConfigForm(id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        mdlForm.modal('show');
        initCKEditor();
        initHeadquarterSelect();
        $('[data-toggle="tooltip"]').tooltip();
    }).fail(function(error) {
        handleErrors(error);
    });

});

function initHeadquarterSelect() {
    $('[name="headquarters[]"]').select2({
        width: '100%',
        dropdownParent: $('.modal-content', $('#mdlForm')),
        placeholder: "Seleccione una o mas sedes",
        ajax: {
            url: ULR + "quotes/headquarters",
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
            "noResults": () => "No se encontraron sedes.",
            "searching": () => "Consultando..."
        }
    });

}

function loadConfigForm(format) {
    return $.ajax({
        url: ULR + "quotes/config-form",
        method: "GET",
        data: {
            format: format
        },
        dataType: "json"
    });
}

function initCKEditor() {

    EDITOR = CKEDITOR.instances['ckeditor'];
    if (EDITOR) { EDITOR.destroy(true); }
    CKEDITOR.replace('ckeditor', {
        height: '800px',
        extraPlugins: 'uploadimage,dragresize',
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

$('body').on('dblclick', '[data-click="setBlock"]', function(e) {

    e.preventDefault();
    id = $(this).data('id');
    $(this).css({ 'background': '#c4e0a3' });
    getBlockData(id).done(function(data) {

        CKEDITOR.instances.ckeditor.insertHtml(JSON.parse(data.block.options).html);
    });

});

function getBlockData(id) {
    return $.ajax({
        url: ULR + "quotes/block-content/" + id,
        method: "GET",
        dataType: "json"
    });
}

$('body').on('click', '[data-click="saveTemplate"]', function(e) {
    e.preventDefault();
    var mdlForm = $('#mdlForm');
    saveTemplate().done(function(data) {
        if (data.success) {
            mdlForm.modal('hide');
            getList();
        }
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }).fail(function(error) {
        showErrors(error.responseJSON.errors, '#templateForm');
        handleErrors(error);
    });

});

function saveTemplate() {
    var body = CKEDITOR.instances.ckeditor.getData();
    $("[name='body']").val(body);
    var form = $('#templateForm');
    var data = form.serialize();
    return $.ajax({
        url: ULR + "quotes/config/save-template",
        method: $("[name='_method']").val(),
        data: data,
        dataType: "json"
    });
}

$('body').on('click', '[data-click="openForm"]', function(e) {

    e.preventDefault();
    var mdlForm = $('#mdlForm');
    var id = $(this).data('id');
    mdlForm.modal('show');

    loadFormSendEmail(id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        initSelectEmail(id);
    });
});

function loadForm(id) {
    return $.ajax({
        url: ULR + "quotes/config/" + id / +"/delete",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

$('body').on('click', '[data-click="openFormDelete"]',
    function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        Swal.fire({
            title: '¿Estás seguro que deseas eliminar este formato?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#ff0d45',
            cancelButtonColor: '#aaa',
            confirmButtonText: 'Si, Continuar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {

            if (result.value) {
                deleteFormat(id).done(function(data) {
                    swal.close();
                    swal(data.success ? 'Correcto' : "Error", data.message, data.success ? "success" : "error");
                    if (data.success) {
                        getList();
                    }
                }).fail(function(error) {
                    handleErrors(error);
                });
            }
        });
    }
);

function deleteFormat(id) {
    return $.ajax({
        url: ULR + 'quotes/config/' + id + '/delete',
        method: 'get',
        dataType: 'json'
    });

}