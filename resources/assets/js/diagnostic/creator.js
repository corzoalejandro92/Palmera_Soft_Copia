SERVER_SUCCESS = false;
var ctx = null;
var externalLink = "../../../../../../../../public/storage/ckeditor/clipboard/";
$('body').ready(function() {

    setInterval(saveDraft, 30000);
    setTimeout(function name(params) {
        window.p = Painterro({
            id: 'conatiner',
            initText: '',
            defaultTool: 'rect',
            activeColor: '#000000',
            activeColorAlpha: 1,
            defaultLineWidth: 2,
            defaultFontSize: 12,
            defaultEraserWidth: 50,
            language: 'es',
            hideByEsc: true,
            saveByEnter: true,
            // defaultTool: 'arrow',
            onUndo: (e) => { console.log('onUndo e', e) },
            onRedo: (e) => { console.log('onRedo e', e) },


            changeHandler: function(state) {
                console.log('Something drawed: ', state);
                // localStorage.setItem('painterroImage', this.imageSaver.asDataULR())
            },
            onBeforeClose: function(has, done) {
                console.log('has unsaved: ', has);
                window.confirmClose = done;
            },
            // language: 'ja',
            availableLineWidths: [1, 2, 4, 8, 16, 64],
            availableEraserWidths: [1, 2, 4, 8, 16, 64],
            availableFontSizes: [1, 2, 4, 8, 10, 12, 14, 16, 64],
            availableArrowLengths: [10, 20, 30, 40, 50, 60],
            // how_to_paste_actions: ['replace_all'],
            saveHandler: function(image, done) {

                    var formData = {

                        diagnostic_id: $('#diagnostic').val(),
                        quote_id: $('#quote').val(),
                        plan_id: $('#plan').val(),
                        "_token": $('[name=_token]').val(),
                        image: image.asDataURL(),
                    };

                    savePlan(formData).done(function(data) {
                        SERVER_SUCCESS = data.success;
                        swal.fire({
                            'title': data.success ? "Correcto" : "Error",
                            'text': data.message,
                            'type': data.success ? "success" : "error",
                        }).then(function(e) {
                            if (data.success == true) {
                                window.close();
                                done(true);
                            } else {
                                done(false);
                            }


                        });

                        setTimeout
                    }).fail(function(error) {
                        done(false);
                        handleErrors(error);
                        showErrors(error.responseJSON.errors, '#selectPlanType');
                    });

                }
                //toolbarPosition: 'top',
                // fixMobilePageReloader: false,
                // defaultTool: 'line',
                //hiddenTools: ['line']

        }).show(); //;
        if (OPEN_IMAGE != '') {
            setTimeout(() => {
                window.p.show(OPEN_IMAGE);
            }, 200);

        }
        ctx = window.p.ctx;
        ctx.beginPath();
        ctx.moveTo(0, 0);
        ctx.lineTo(300, 150);
        ctx.strokeStyle = "#FF0000";
        ctx.stroke();




    }, 500);

    CKEDITOR.replace('header', {
        extraPlugins: 'uploadimage,dragresize',
        uploadUrl: '/template/app-assets/vendors/js/editors/ckeditor/server/ajaxfile.php?type=clipboard',
        filebrowserUploadUrl: '/template/app-assets/vendors/js/editors/ckeditor/server/ajaxfile.php?type=file',
        filebrowserImageUploadUrl: '/template/app-assets/vendors/js/editors/ckeditor/server/ajaxfile.php?type=image',
        filebrowserUploadMethod: 'form'
    });
    CKEDITOR.replace('table1', {
        extraPlugins: 'uploadimage,dragresize',
        uploadUrl: '/template/app-assets/vendors/js/editors/ckeditor/server/ajaxfile.php?type=clipboard',
        filebrowserUploadUrl: '/template/app-assets/vendors/js/editors/ckeditor/server/ajaxfile.php?type=file',
        filebrowserImageUploadUrl: '/template/app-assets/vendors/js/editors/ckeditor/server/ajaxfile.php?type=image',
        filebrowserUploadMethod: 'form'
    });
    CKEDITOR.replace('table2', {
        extraPlugins: 'uploadimage,dragresize',
        uploadUrl: '/template/app-assets/vendors/js/editors/ckeditor/server/ajaxfile.php?type=clipboard',
        filebrowserUploadUrl: '/template/app-assets/vendors/js/editors/ckeditor/server/ajaxfile.php?type=file',
        filebrowserImageUploadUrl: '/template/app-assets/vendors/js/editors/ckeditor/server/ajaxfile.php?type=image',
        filebrowserUploadMethod: 'form'
    });
    CKEDITOR.replace('table3', {
        extraPlugins: 'uploadimage,dragresize',
        uploadUrl: '/template/app-assets/vendors/js/editors/ckeditor/server/ajaxfile.php?type=clipboard',
        filebrowserUploadUrl: '/template/app-assets/vendors/js/editors/ckeditor/server/ajaxfile.php?type=file',
        filebrowserImageUploadUrl: '/template/app-assets/vendors/js/editors/ckeditor/server/ajaxfile.php?type=image',
        filebrowserUploadMethod: 'form'
    });
    CKEDITOR.replace('table4', {
        extraPlugins: 'uploadimage,dragresize',
        uploadUrl: '/template/app-assets/vendors/js/editors/ckeditor/server/ajaxfile.php?type=clipboard',
        filebrowserUploadUrl: '/template/app-assets/vendors/js/editors/ckeditor/server/ajaxfile.php?type=file',
        filebrowserImageUploadUrl: '/template/app-assets/vendors/js/editors/ckeditor/server/ajaxfile.php?type=image',
        filebrowserUploadMethod: 'form'
    });
    CKEDITOR.replace('table5', {
        extraPlugins: 'uploadimage,dragresize',
        uploadUrl: '/template/app-assets/vendors/js/editors/ckeditor/server/ajaxfile.php?type=clipboard',
        filebrowserUploadUrl: '/template/app-assets/vendors/js/editors/ckeditor/server/ajaxfile.php?type=file',
        filebrowserImageUploadUrl: '/template/app-assets/vendors/js/editors/ckeditor/server/ajaxfile.php?type=image',
        filebrowserUploadMethod: 'form'
    });
    CKEDITOR.replace('footer', {
        extraPlugins: 'uploadimage,dragresize',
        uploadUrl: '/template/app-assets/vendors/js/editors/ckeditor/server/ajaxfile.php?type=clipboard',
        filebrowserUploadUrl: '/template/app-assets/vendors/js/editors/ckeditor/server/ajaxfile.php?type=file',
        filebrowserImageUploadUrl: '/template/app-assets/vendors/js/editors/ckeditor/server/ajaxfile.php?type=image',
        filebrowserUploadMethod: 'form'
    });

    initSelectStatus();

});

function saveDraft() {
    console.log("new save draft at" + Date());
    save(true);

}

$('body').on('click', '[data-click="save"]', function(e) {
    e.preventDefault();
    save(false);

});

$('body').on('click', '[data-click="cancel"]', function(e) {
    e.preventDefault();
    window.close();
});



function save(draft = false) {

    var mdlForm = $('#mdlForm');

    if (draft) SEE_LOADING = false;
    else SEE_LOADING = true;


    saveDiagnostic().done(function(data) {
        if (!draft) {
            swal.fire({
                'title': data.success ? "Correcto" : "Error",
                'text': data.message,
                'type': data.success ? "success" : "error",
            }).then(function(e) {
                if (data.success == true) {
                    window.close();
                    done(true);
                }

            });
        } else {
            SEE_LOADING = true;
        }

    }).fail(function(error) {
        handleErrors(error);
        if (error && error.responseJSON) {
            showErrors(error.responseJSON.errors, '#form');
        } else {
            //swal("Error", "No se pudo procesar la respuesta del servidor", "error");
        }

    });
}

function savePlan(data) {
    return $.ajax({
        method: $("[name=_method]").val(),
        url: ULR + 'quote/diagnostic/creator/paint/save',
        data: data
    });
}

function saveDiagnostic() {
    $("[name='header']").val(CKEDITOR.instances.header.getData());
    $("[name='table1']").val(CKEDITOR.instances.table1.getData());
    $("[name='table2']").val(CKEDITOR.instances.table2.getData());
    $("[name='table3']").val(CKEDITOR.instances.table3.getData());
    $("[name='table4']").val(CKEDITOR.instances.table4.getData());
    $("[name='table5']").val(CKEDITOR.instances.table5.getData());
    $("[name='footer']").val(CKEDITOR.instances.footer.getData());

    var form = $('#form');
    var data = form.serialize();
    return $.ajax({
        url: ULR + "quote/diagnostic/save",
        method: $("[name='_method']").val(),
        data: data,
        dataType: "json"
    });
}

$('body').on('dblclick', '[data-click="setBlockHeader"]', function(e) {

    e.preventDefault();
    var id = $(this).data('id');
    var diagnostic = $(this).data('diagnostic');
    $(this).css({ 'background': '#c4e0a3' });
    return $.ajax({
        url: ULR + "quote/diagnostic/" + diagnostic + "/block-content/" + id,
        method: "GET",
        dataType: "json"
    }).done(function(data) {
        CKEDITOR.instances.header.insertHtml(data.block);
    });


});

$('body').on('dblclick', '[data-click="setInfestation"]', function(e) {

    e.preventDefault();
    var id = $(this).data('id');
    $(this).css({ 'background': '#c4e0a3' });
    return $.ajax({
        url: ULR + "quote/diagnostic/add-row-to-table1/" + id,
        method: "GET",
        dataType: "json"
    }).done(function(data) {

        var editor = CKEDITOR.instances.table1;
        var datac = $("<div>" + editor.getData() + "</div>");
        $('#table1 tbody', datac).append(data.row);
        console.log(datac);
        editor.setData(datac.html());
    });


});

$('body').on('dblclick', '[data-click="setInfestation2"]', function(e) {

    e.preventDefault();
    var id = $(this).data('id');
    $(this).css({ 'background': '#c4e0a3' });
    return $.ajax({
        url: ULR + "quote/diagnostic/add-row-to-table1/" + id,
        method: "GET",
        dataType: "json"
    }).done(function(data) {

        var editor = CKEDITOR.instances.table2;
        var datac = $("<div>" + editor.getData() + "</div>");
        $('#table2 tbody', datac).append(data.row);
        console.log(datac);
        editor.setData(datac.html());
    });


});

$('body').on('dblclick', '[data-click="setMethods"]', function(e) {

    e.preventDefault();
    var id = $(this).data('id');
    $(this).css({ 'background': '#c4e0a3' });
    return $.ajax({
        url: ULR + "quote/diagnostic/add-row-to-table3/" + id,
        method: "GET",
        dataType: "json"
    }).done(function(data) {

        var editor = CKEDITOR.instances.table3;
        var datac = $("<div>" + editor.getData() + "</div>");
        $('#table3 tbody', datac).append(data.row);
        console.log(datac);
        editor.setData(datac.html());
    });


});

function initSelectStatus() {

    $("[name='status_id']").select2({
        // dropdownParent: $('.modal-content', $('#mdlForm')),
        width: '300px',
        ajax: {
            url: ULR + "diagnostic/status",
            dataType: 'json',
            method: "GET",
        }
    });
}

$('body').on('click', '[data-click="plan"]', function(e) {
    localStorage.getItem('observable', 'true');

});


document.addEventListener("visibilitychange", function() {
    if (document.visibilityState == 'visible' && localStorage.getItem('observable') == 'true') {
        localStorage.getItem('observable', 'false');
        getPlan();
    }
});

function getPlan() {

    return $.ajax({
        url: ULR + "diagnostic/plan/" + $("[name='id']").val(),
        method: "GET",
        dataType: "json"
    }).done(function(data) {
        $(".create").hide();
        var href = $(".edit").attr('href');
        var newHref = href.replace('/0/', '/' + data.id + '/');
        $(".edit").attr('href', newHref);
        $(".edit").show();
        $(".content-plan img").attr('src', data.plan);
    });
}