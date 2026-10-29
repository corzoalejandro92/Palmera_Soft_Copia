SERVER_SUCCESS = false;
var ctx = null;
$(document).ready(function() {
    setTimeout(function name(params) {
        window.p = Painterro({
            id: 'conatiner',
            initText: '',
            defaultTool: 'rect',
            activeColor: '#000000',
            activeColorAlpha: 1,
            defaultLineWidth: 2,
            defaultFontSize: 13,
            defaultEraserWidth: 50,
            hiddenTools: ['close'],
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
            availableFontSizes: [1, 2, 4, 8, 16, 64],
            availableArrowLengths: [10, 20, 30, 40, 50, 60],
            // how_to_paste_actions: ['replace_all'],
            saveHandler: function(image, done) {

                    var formData = {

                        crm_id: $('#crm').val(),
                        area_id: $('#area').val(),
                        type_id: $('#type').val(),
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

        setTimeout(() => {
            getPlanTypes().done(function(data) {
                $("#conatiner-bar > div > span:first").append("<button type='button' data-click='setLogo' title='Insertar el logo de palmera' class='ptro-icon-btn ptro-color-control painterro-custom-button'> Logo</button>");
                $("#conatiner-bar > div > span:first").append("<button type='button' data-click='setDate' title='Insertar fecha actual' class='ptro-icon-btn ptro-color-control painterro-custom-button'> Fecha</button>");
                var options = '<option value="">Seleccione un tipo de plano</option>';
                data.options.forEach(function(item) {
                    options += '<option value="' + item.id + '">' + item.name + '</option>';
                });
                $('.navbar-collapse .navbar-nav').append('<form id="selectPlanType"><div class="form-group m-form__group row" style="position: fixed;"><div class="col-12"><select class="form-control" data-change="hiden-error"  id="type" name="type_id" style="position: relative;top: 18px;height: 34px;">' + options + '</select></div></div></form>');
            });
        }, 500);


    }, 500);


});


//crear un observador
const observer = new MutationObserver(putDate);



function getPlanTypes() {
    return $.ajax({
        method: "get",
        url: ULR + 'crm/plan/types',
        data: {}
    });
}

function savePlan(data) {
    return $.ajax({
        method: $("[name=_method]").val(),
        url: ULR + 'crm/plan/save',
        data: data
    });
}

$('body').on("click", "[data-click='setLogo']", function() {
    loadXHR("/template/app-assets/images/logo/logo.png").then(function(blob) {
        window.p.documentHandlers.putImage(blob);
    });
});

$('body').on("click", "[data-click='setDate']", function() {
    var buttonId = window.p.toolByKeyCode[84].buttonId;
    $("#" + buttonId).trigger('click');

    const targetNode = document.querySelector(".ptro-text-tool-input");
    const observerOptions = {
        childList: true
    };
    observer.observe(targetNode, observerOptions);


});

function putDate() {
    observer.disconnect();

    const d = new Date();
    const ye = new Intl.DateTimeFormat('es', { year: 'numeric' }).format(d);
    const mo = new Intl.DateTimeFormat('es', { month: 'short' }).format(d);
    const da = new Intl.DateTimeFormat('es', { day: '2-digit' }).format(d);


    $(".ptro-text-tool-input")[0].innerText = da + '/' + mo + '/' + ye;
}


function loadXHR(url) {

    return new Promise(function(resolve, reject) {
        try {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", url);
            xhr.responseType = "blob";
            xhr.onerror = function() { reject("Network error.") };
            xhr.onload = function() {
                if (xhr.status === 200) { resolve(xhr.response) } else { reject("Loading error:" + xhr.statusText) }
            };
            xhr.send();
        } catch (err) { reject(err.message) }
    });
}