/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
SELECT2_DATA = {};
RESTORE_SELECT2 = false;
SEE_LOADING = true;

if (localStorage.getItem('menuLocked') == 'false') {
    $('body').addClass('fixed-navbar pace-done menu-collapsed');
    $('body').addClass('pace-done');
    $('body').addClass('menu-collapsed');
    $('.main-menu').removeClass('expanded');
    $('.open').addClass('menu-collapsed-open');
    $('.open').addClass('active');
    $('.menu-collapsed-open').removeClass('open');
}

if (localStorage.getItem('defaultZoom') == 'false') {
    localStorage.setItem('defaultZoom', 'true');
} else {
    document.body.style.zoom = "90%";
}


function selectScroll() {
    selectDestroyScroll();
    setTimeout(function() {
        $(".select2-results__options").mCustomScrollbar({
            theme: "rounded-dark",
            scrollButtons: { enable: true, scrollType: "stepped" },
            keyboard: { scrollType: "stepped" },
            mouseWheel: { scrollAmount: 100 },
            scrollInertia: 0,
            mouseWheelPixels: 170,
            autoDraggerLength: false,
            autoExpandScrollbar: true
        });
    }, 0.1);
}

function selectDestroyScroll() {
    if ($('.select2-results__options')) {
        try {
            $('.select2-results__options').mCustomScrollbar("destroy");
        } catch (error) {

        }

    }

} ///--RENDERIZAR SELECTS DE CLIENTE
function dataFormatResultCRM(data) {
    if (data.loading) return dataFormatSelection({ text: 'Consultando...' });

    var markup = "<div class='select2-result-repository clearfix'>" +
        "<div class='select2-result-repository__avatar'>" +
        "<img class='avatarSelect2' src='" + (data.avatar ? data.avatar : '/images/default/add.png') + "' />" +
        "</div>" +
        "<div class='select2-result-repository__meta'>" +
        "<div class='select2-result-repository__title'>" + (data.full_name ? data.full_name : data.text) + "</div>";

    if (data.full_name) {
        markup +=
            '<div class="select2-result-repository__statistics">' +
            '<div class="select2-result-repository__forks center-items">' +
            '<i class="las la-id-card la-2x"></i> NIT: ' + (data.document ? data.document : 'NN') +
            '</div>' +
            '<div class="select2-result-repository__stargazers center-items">' +
            '<i class="lab la-buffer la-2x"></i> Ciudad: ' + (data.offices ? data.offices[0].headquarter.name : 'NN') +
            '</div>';

            if(data.customer_national ){
                markup += '<div class="select2-result-repository__watchers center-items">' +
                '<i class="las la-globe-americas la-2x"></i>' + (data.customer_national ? 'Cliente Nacional' : 'No') +
                '</div>';
            }

            if(data.user ){
                markup += '<div class="select2-result-repository__watchers center-items" style="color:' + ($("[data-click='openFormProfile']").data('id') == data.user_id ? 'green':(data.user_id == null)? 'blue':'red') +' ">' +
                '<b><i class="las la-user la-2x"></i> Comercial: ' + (data.user ? data.user.name : 'Sin comercial') +
                '</b></div>';
            }
            
            

            markup += '</div>';
    } else {
        markup +=
            '<div class="select2-result-repository__statistics">' +
            '<div class="select2-result-repository__forks center-items">' +
            '<span>Crear nuevo cliente</span>' +
            '</div>' +
            '</div>';
    }

    return markup;
}

///--RENDERIZAR SELECTS DE USUARIOS
function dataFormatResultUser(data) {
    if (data.loading) return dataFormatSelection({ text: 'Consultando...' });

    var markup = "<div class='select2-result-repository clearfix'>" +
        "<div class='select2-result-repository__avatar'>" +
        "<img class='avatarSelect2' src='" + data.full_avatar + "' onerror='this.src=\"" + ULR + "images/default/user.jpg\"' />" +
        "</div>" +
        "<div class='select2-result-repository__meta'>" +
        "<div class='select2-result-repository__title'>" + (data.name ? data.name : data.text) + "</div>";



    markup +=
        '<div class="select2-result-repository__statistics">';

    if (data.full_roles) {
        markup += '<div class="select2-result-repository__forks center-items" style="color: #795548;">' +
            '<b><i class="las la-id-card-alt la-2x"></i>' + data.full_roles + '</b>' +
            '</div>';
    }

    markup += '<div class="select2-result-repository__forks center-items">' +
        '<i class="las la-map-marked-alt la-2x"></i>' + data.headquarter.name +
        '</div>' +
        '<div class="select2-result-repository__forks center-items">' +
        '<i class="las la-mail-bulk la-2x"></i>' + data.email +
        '</div>' +

        '</div>';


    return markup;
}

function dataFormatResultUserCustomer(data) {
    if (data.loading) return dataFormatSelection({ text: 'Consultando...' });

    var markup = "<div class='select2-result-repository clearfix'>" +
        "<div class='select2-result-repository__avatar'>" +
        "<img class='avatarSelect2' src='" + data.full_avatar + "' onerror='this.src=\"" + ULR + "images/default/user.jpg\"' />" +
        "</div>" +
        "<div class='select2-result-repository__meta'>" +
        "<div class='select2-result-repository__title'>" + (data.name ? data.name : data.text) + "</div>";



    markup +=
        '<div class="select2-result-repository__statistics">';

    if (data.full_customers) {
        markup += '<div class="select2-result-repository__forks center-items" style="color: #1976d2;">' +
            '<b><i class="las la-id-card-alt la-2x"></i>' + data.full_customers + '</b>' +
            '</div>';
    }

    markup += '<div class="select2-result-repository__forks center-items">' +
        '<i class="las la-map-marked-alt la-2x"></i>' + data.headquarter.name +
        '</div>' +
        '<div class="select2-result-repository__forks center-items">' +
        '<i class="las la-mail-bulk la-2x"></i>' + data.email +
        '</div>' +

        '</div>';


    return markup;
}

///--RENDERIZAR SELECTS DE SUCURSAL DEL CLIENTE
function dataFormatResultOffice(data) {
    if (data.loading) return dataFormatSelection({ text: 'Consultando...' });

    var markup = "<div class='select2-result-repository clearfix'>" +
        "<div class='select2-result-repository__avatar'>" +
        "<img class='avatarSelect2' src='" + (data.avatar ? data.avatar : '/images/default/add.png') + "' />" +
        "</div>" +
        "<div class='select2-result-repository__meta'>" +
        "<div class='select2-result-repository__title'>" + (data.name ? data.name : data.text) + "</div>";

    if (data.full_address) {
        markup += "<div class='select2-result-repository__description'>" + data.full_address + "</div>";
    } else {
        markup += "<div class='select2-result-repository__description'>Nueva sucursal</div>";
    }




    return markup;
}


///--RENDERIZAR SELECTS DE COTIZACIONES
function dataFormatResultQuote(data) {
    if (data.loading) return dataFormatSelection({ text: 'Consultando...' });

    var markup = "<div class='select2-result-repository clearfix'>" +
        "<div class='select2-result-repository__avatar'>" +
        "<img class='avatarSelect2' src='" + (data.avatar ? data.avatar : '/images/default/control.png') + "' />" +
        "</div>" +
        "<div class='select2-result-repository__meta'>" +
        "<div class='select2-result-repository__title'> Cotización " + (data.code) + "</div>";
    markup += "<div class='select2-result-repository__description'>" + data.created_at_ + " por " + data.commercial.name + "</div>";





    return markup;
}

///--RENDERIZAR SELECTS DE SERVICIOS
function dataFormatResultService(data) {
    if (data.loading) return dataFormatSelection({ text: 'Consultando...' });

    var markup = "<div class='select2-result-repository clearfix'>" +
        "<div class='select2-result-repository__avatar'>" +
        "<i class='current-color font-large-2 " + data.avatar + "'></i>" +
        "</div>" +
        "<div class='select2-result-repository__meta'>" +
        "<div class='select2-result-repository__title'>" + data.name + "</div></div>";

    if (data.description) {
        markup += "<div class='select2-result-repository__description'>" + data.description + "</div>";
    }

    return markup;
}

///--RENDERIZAR SELECTS DE COBERTURAS
function dataFormatResultCoverage(data) {
    if (data.loading) return dataFormatSelection({ text: 'Consultando...' });

    var markup = "<div class='select2-result-repository clearfix'>" +
        "<div class='select2-result-repository__title'>" + data.text + '($' + number_format(data.cost) + ") (" + data.visits + " visitas)</div>";

    if (data.description) {
        markup += "<div class='select2-result-repository__description'>" + data.description + "</div>";
    }

    return markup;
}


///--RENDERIZAR SELECTS DE PLAGAS (CONTROL)
function dataFormatResultInfestation(data) {
    if (data.loading) return dataFormatSelection({ text: 'Consultando...' });

    var markup = "<div class='select2-result-repository clearfix'>" +
        "<div class='select2-result-repository__avatar'>" +
        "<img class='avatarSelect2' src='" + data.full_avatar + "' onerror='this.src=\"" + ULR + "images/default/infestation.png\"' />" +
        "</div>" +
        "<div class='select2-result-repository__meta'>" +
        "<div class='select2-result-repository__title'>" + data.name + "</div>";

    if (data.description) {
        markup += "<div class='select2-result-repository__description'>" + data.description + "</div>";
    }

    return markup;
}

///--RENDERIZAR SELECTS DE PRODUCTOS
function dataFormatResultProduct(data) {
    if (data.loading) return dataFormatSelection({ text: 'Consultando...' });
    var markup = "";
    if (data.name) {


        var markup = "<div class='select2-result-repository clearfix'>" +
            "<div class='select2-result-repository__avatar'><img class='avatarSelect2' src='" + data.full_avatar + "'  onerror='this.src=\"" + ULR + "images/default/product.png\"' /></div>" +
            "<div class='select2-result-repository__meta'>" +
            "<div class='select2-result-repository__title'>" + (data.draft ? '(Borrador) ' : '') + data.name + "</div>";
    }

    if (data.description) {
        markup += "<div class='select2-result-repository__description'>" + data.description + "</div>";
    }

    if (data.name) {
        markup += "<div class='select2-result-repository__statistics'>" +
            "<div class='select2-result-repository__forks center-items'><i class='las la-barcode la-2x'></i>" + data.code + "</div>" +
            "<div class='select2-result-repository__stargazers center-items'><i class='las la-balance-scale la-2x'></i>" + (data.unit != null ? data.unit.name.split(" ")[0] : 'indefinida') + "</div>" +
            "<div class='select2-result-repository__watchers center-items'><i class='las la-biohazard la-2x'></i>" + (data.toxico ? 'Si' : 'No') + "</div>" +
            "</div>" +
            "</div></div>";
    }
    if (markup == undefined) {
        markup = "<b>Crear nuevo producto:</b> '" + data.text + "'";
    }

    return markup;
}

function dataFormatSelection(data) {
    return data.name || data.text;
}

function dataFormatSelectionControlProduct(data) {

    color = data.color ? data.color : (data.element && data.element.dataset.color ? data.element.dataset.color : '#fff');
    var $template = $(
        '<span><div class="product-color"></div> <span></span></span>'
    );

    // Use .text() instead of HTML string concatenation to avoid script injection issues
    $template.find("span").text((data.name ? data.name : data.text));
    $template.find(".product-color").css({ backgroundColor: color });

    return $template;
}



///--RENDERIZAR SELECTS DE CIUDADES
function dataFormatResultCities(data) {
    if (data.loading) return dataFormatSelection({ text: 'Consultando...' });

    var markup = "<div class='select2-result-repository clearfix'>" +
        "<div class='select2-result-repository__title'>" + data.name + ' (' + data.state.name + ") </div>";

    if (data.description) {
        markup += "<div class='select2-result-repository__description'>" + data.description + "</div>";
    }

    return markup;
}

var dtEs = {
    "sProcessing": "Procesando...",
    "sLengthMenu": "Mostrar _MENU_ registros",
    "sZeroRecords": "No se encontraron resultados",
    "sEmptyTable": "Ningún dato disponible en esta tabla",
    "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
    "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
    "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
    "sInfoPostFix": "",
    "sSearch": "Buscar:",
    "sUrl": "",
    "sInfoThousands": ",",
    "sLoadingRecords": "Cargando...",
    "oPaginate": {
        "sFirst": "Primero",
        "sLast": "Último",
        "sNext": "Siguiente",
        "sPrevious": "Anterior"
    },
    "oAria": {
        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
    }
};

var dtEsSm = {
    "sProcessing": "Procesando...",
    "sLengthMenu": "Mostrar _MENU_ registros",
    "sZeroRecords": "No se encontraron resultados",
    "sEmptyTable": "Ningún dato disponible en esta tabla",
    "sInfo": "Total _TOTAL_ registros",
    "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
    "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
    "sInfoPostFix": "",
    "sSearch": "Buscar:",
    "sUrl": "",
    "sInfoThousands": ",",
    "sLoadingRecords": "Cargando...",
    "oPaginate": {
        "sFirst": "Primero",
        "sLast": "Último",
        "sNext": "Siguiente",
        "sPrevious": "Anterior"
    },
    "oAria": {
        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
    }
};

$('body').on('click', '[data-upload="file"]', function(e) {
    var parentForm = $(this).data('form');
    $('#' + parentForm + ' [data-input="fileImage"]').click();
});

$('.modal').on("hidden.bs.modal", function(e) {
    if ($('.modal:visible').length) {
        $('body').addClass('modal-open');
    }
    $(this).find('.select2-container').css({ 'z-index': '1000' });
    RESTORE_SELECT2 = false;
});

$('body').on('keyup', '[data-keyup="hiden-error"]', function(e) {
    removeError($(this))
});

$('body').on('change', '[data-change="hiden-error"]', function(e) {
    removeError($(this))
});


$('body').on('click', '[data-click="load-menu-shops"]', function(e) {
    e.preventDefault();
    var mdlShops = $('#mdlMenuShops');
    var loading = $('.loading', mdlShops);
    mdlShops.modal('show');
    loading.attr('data-show', 'true');

    loadMenuShops().done(function(data) {
        $('.modal-content', mdlShops).html(data.view);
        loading.attr('data-show', 'false');
        $('.vertical-scroll').perfectScrollbar({
            suppressScrollX: true,
            theme: 'dark',
            wheelPropagation: true
        });
    });
});

$('body').on('keyup', '[data-keyup="search-bar"]', function(e) {

    var input, filter, div;
    input = $('[data-keyup="search-bar"]');
    filter = input.val().toUpperCase();
    div = $('#listShops div');
    $.each(div, function(key, value) {
        var p = $('p', this);
        if (p) {
            if (p.text().toUpperCase().indexOf(filter) > -1) {
                $(this).removeClass('d-none');
            } else {
                $(this).addClass('d-none');
            }
        }
    });
});

function removeError(element) {
    var field = element;
    $('.errorShow', field.parent()).remove();
    field.closest('.form-group').removeClass('has-danger');
}

function loadImage(input) {
    var element = $(input).parent();
    var loading = $('.loading', element).first();
    loading.attr('data-show', 'true');

    if (input.files && input.files.length > 0) {
        var reader = new FileReader();
        var file = input.files[0];
        reader.readAsDataURL(file);
        reader.onload = function() {

            if (validateImg(file.type)) {
                $('img', element).attr('src', reader.result);
                $('[name="avatar" ]', element).val(reader.result).trigger('change');
            }
            loading.attr('data-show', 'false');
        }
    } else {
        loading.attr('data-show', 'false');
    }

}


function validateImg(filetype) {
    var types = ["image/jpeg", "image/gif", "image/png", "image/jpg"];
    var valid = types.find(function(element) {
        return element === filetype;
    });
    if (valid !== undefined) {
        return true;
    }
    return false;
}

function filterSelect(columns, api) {

    columns.forEach(function(c) {
        api.column(c).every(function() {
            var column = this;
            var title = $(column.header()).text();
            var select = jQuery('<select class="form-control" style="width:110px;"><option value="">' + title + '</option></select>')
                .appendTo(jQuery(column.header()).empty())
                .on('change', function() {
                    var val = jQuery.fn.dataTable.util.escapeRegex(jQuery(this).val());
                    column.search(val ? '^' + val + '$' : '', true, false).draw();
                });
            column.data().unique().sort().each(function(d, j) {
                select.append('<option value="' + d + '">' + d + '</option>')
            });
        });
    });
}

function filterInput(columns, api) {

    columns.forEach(function(c) {
        api.column(c).every(function() {
            var column = this;
            var title = $(column.header()).text();
            jQuery('<input type="text" value="" placeholder="' + title + '" class="form-control" />')
                .appendTo(jQuery(column.header()).empty()).on('keyup change', function() {
                    if (column.search() !== this.value) {
                        column.search(this.value).draw();
                    }
                });
        });
    });

}

function sumColumn(columns, api, format = false) {

    var intVal = function(i) {
        return typeof i === 'string' ? i.replace(/[\$.]/g, '') * 1 : typeof i === 'number' ? i : 0;
    };
    columns.forEach(function(c) {
        sum = api.column(c, { search: 'applied' }).data().reduce(function(a, b) {
            return intVal(a) + intVal(b);
        }, 0);
        if (format) {
            $(api.column(c).footer()).html('$ ' + number_format(sum, 0, '.', '.'));
        } else {
            $(api.column(c).footer()).html(sum);
        }

    });
}

function showErrors(data, form = null) {

    $('.errorShow').remove();
    $('[data-keyup="hiden-error"]').each(function() {
        var field = $(this);
        $('.errorShow', field.parent()).remove();
        field.closest('.form-group').removeClass('has-danger');
    });

    $('[data-change="hiden-error"]').each(function() {
        var field = $(this);
        $('.errorShow', field.parent()).remove();
        field.closest('.form-group').removeClass('has-danger');
    });

    if (form === null) {
        var forms = $('form');
        var form = forms[forms.length - 1];
    }


    $.each(data, function(key, value) {
        key = key.split('.');
        var field = null,
            field2 = null;
        if (key.length > 1) {
            var name = getNestedKey(key);
            field = $('[name="' + name + '"]', form);
            field2 = $('[name="' + name + '[]"]', form);
        } else {
            field = $('[name="' + key + '"]', form);
            field2 = $('[name="' + key + '[]"]', form);
        }
        var html = '<div class="errorShow">' + value + '</div>';

        field.parent().append(html);
        field.closest('.form-group').addClass('has-danger');


        field2.parent().append(html);
        field2.closest('.form-group').addClass('has-danger');


    });


    var div = document.createElement("div");
    var txt = '';
    $.each(data, function(key, value) {
        if (key.includes("global_error_")) {
            txt = txt + '' + value + '<br/><br/>';
        }
    });
    if (txt.length > 0) {
        div.innerHTML = txt;
        sweetAlert({
            type: 'error',
            title: "Error",
            content: div,
            button: {
                text: "Aceptar",
            }
        });
    }

}

function getNestedKey(key) {
    let newkey = key[0] + '[';
    key.shift();
    newkey += key.join('][');
    newkey += "]";
    return newkey;
}

function showToast(message) {
    toastr.remove();
    toastr.success(message, '', { positionClass: 'toast-bottom-center', containerId: 'toast-bottom-center' });
}

function showToastError(message) {
    toastr.remove();
    toastr.error(message, '', { positionClass: 'toast-bottom-center', containerId: 'toast-bottom-center' });
}

function loadMenuShops() {
    return $.ajax({
        url: ULR + "/common/shops/load-list",
        method: "GET",
        dataType: "json"
    });
}

function frmFocus(form) {
    setTimeout(function() {
        $("input[type=text]", form).first().focus();
    }, 500);
}

function tooltip() {
    $('[data-toggle="tooltip"]').tooltip();
}

function tooltipHide() {
    $('[data-toggle="tooltip"]').tooltip('hide');
}

/**************************** Formato de números *******************************/

$('body').on('keypress', '[data-format="1"]', function(e) {
    if (e.which !== 8 && isNaN(String.fromCharCode(e.which))) {
        e.preventDefault();
    }
});

$('body').on('input', '[data-format="1"]', function(e) {
    var value = clear_format($(this).val());
    if (value === "") {
        return;
    }
    $(this).val(number_format(value, 0, '.', ','));
});

$('body').on('input', '[data-format="2"]', function(e) {
    this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
});

$('body').on('keypress', '[data-format="3"]', function(e) {
    if (e.which !== 8 && isNaN(String.fromCharCode(e.which))) {
        e.preventDefault();
    }
});

function clear_format(number) {
    number = number.replace(/\./g, '');
    return parseInt(number.replace(/\,/g, ''));
}

function number_format(number, decimals = 0, decPoint = '.', thousandsSep = ',') {
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '')
    var n = !isFinite(+number) ? 0 : +number
    var prec = !isFinite(+decimals) ? 0 : Math.abs(decimals)
    var sep = (typeof thousandsSep === 'undefined') ? ',' : thousandsSep
    var dec = (typeof decPoint === 'undefined') ? '.' : decPoint
    var s = ''

    var toFixedFix = function(n, prec) {
        if (('' + n).indexOf('e') === -1) {
            return +(Math.round(n + 'e+' + prec) + 'e-' + prec)
        } else {
            var arr = ('' + n).split('e')
            var sig = ''
            if (+arr[1] + prec > 0) {
                sig = '+'
            }
            return (+(Math.round(+arr[0] + 'e' + sig + (+arr[1] + prec)) + 'e-' + prec)).toFixed(prec)
        }
    }

    // @todo: for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec).toString() : '' + Math.round(n)).split('.')
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep)
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || ''
        s[1] += new Array(prec - s[1].length + 1).join('0')
    }

    return s.join(dec)
}

$(document).on('keyup', '[data-min_max]', function(e) {
    var min = parseInt($(this).data('min'));
    var max = parseInt($(this).data('max'));
    var val = parseInt($(this).val());
    if (val > max) {
        $(this).val(max);
        return false;
    } else if (val < min) {
        $(this).val(min);
        return false;
    }
});

$(document).on('keydown', '[data-toggle=just_number]', function(e) {
    // Allow: backspace, delete, tab, escape, enter and .
    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
        // Allow: Ctrl+A
        (e.keyCode == 65 && e.ctrlKey === true) ||
        // Allow: Ctrl+C
        (e.keyCode == 67 && e.ctrlKey === true) ||
        // Allow: Ctrl+X
        (e.keyCode == 88 && e.ctrlKey === true) ||
        // Allow: home, end, left, right
        (e.keyCode >= 35 && e.keyCode <= 39)) {
        return;
    }
    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
        e.preventDefault();
    }
});


function initDate() {
    $('.pickadate').pickadate({

        monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
        format: 'yyyy-mm-dd',
        today: 'Hoy',
        clear: 'Limp.',
        close: 'Cerrar',
        buttonClear: 'd-none',
        formatSubmit: 'yyyy-mm-dd',
    });

    $('.pickadate').pickadate({

        monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
        format: 'yyyy-mm-dd',
        today: 'Hoy',
        clear: 'Limp.',
        close: 'Cerrar',
        buttonClear: 'd-none',
        formatSubmit: 'yyyy-mm-dd',
    });
}

function iconFormat(icon) {
    var originalOption = icon.element;
    if (!icon.id) { return icon.text; }
    var $icon = "<i class='las la-" + $(icon.element).data('icon') + " la-3x'></i>" + icon.text;

    return $icon;
}

function iconFormat2(icon) {
    var originalOption = icon.element;
    if (!icon.id) { return icon.text; }
    var $icon = "<i class='las la-" + $(icon.element).data('icon') + " la-2x'></i>" + icon.text;

    return $icon;
}


function handleErrors(error) {
    switch (error.status) {
        case 404:
            swal('Error 404', 'Parece que la acción que solicitaste realizar no fue encontrada', 'error');
            closeLoading();
            break;
        case 401:
            closeLoading();
            location.href = ULR + 'login';
            break;
        case 500:
            getErrorFormat(error, 'Se ha generado un error interno en el sistema');
            closeLoading();
            break;
        case 405:
            getErrorFormat(error, 'Método no soportado');
            break;
        case 403:
            swal('No tienes privilegios para realizar esta acción', 'Parece que el rol que tienes en el sistema no te permite realizar esta acción', 'info');
            closeLoading();
            break;
        case 419:
            swal('Token de seguridad caducado', 'Al parecer tardaste demasiado en guardar los cambios y el token de seguridad caducó, o bien, abriste un nueva pestaña del navegador e intentaste abrir este formulario nuevamente, esto genera una cancelación entre tokens.<br><br> Solo debes refrescar la página e intentarlo nuevamente, esto generará un nuevo token válido', 'warning');
            closeLoading();
            break;
        case 422:
            swal('Formulario incompleto', 'Al parecer no has llenado todos los campos obligatorios de este formulario, revisa de nuevo los campos marcados en rojo.', 'info');
            closeLoading();
            break;
        default:
            if (error.readyState == 0 && error.statusText == "error") {
                console.log(error);
                setTimeout(function() {
                    Swal.close();
                    swal('Se  perdió la conexión', 'Parece que no hay conexión a internet, intentalo nuevamente', 'question');
                    closeLoading();
                }, 2500);
            } else {
                try {
                    swal('Código ' + error.status, error.responseJSON.message, 'info');
                } catch (error) {

                }

                closeLoading();
            }
            break;
    }



}

function getErrorFormat(error, title) {
    var trace = error.responseJSON.message.split('/');
    swal('Error ' + error.status, title + ', contacta al administrador y proporcionale el siguiente código: <br><br>\n\n\
                <div class="code" align="left">\n\
                    <b>Exeption:</b>' + error.responseJSON.exception + '<br>\n\
                    <b>trace: </b>' + trace.shift() + trace.pop() + '<br>\n\
                    <b>Line: </b>' + error.responseJSON.line + '<br>\n\
                    <b>File: </b>' + error.responseJSON.file.split('/').pop() + '\n\
                </div>', "error");
}

function closeLoading() {
    var loading = $('.loading');
    var loadingSmall = $('.loading-sub');
    loading.attr('data-show', 'false');
    if ($('.modal .modal-body').html() && $('.modal .modal-body').html().trim() == "") {
        $('.modal .modal-body').html('<div align="center" class="times-close" onclick="$(\'.modal\').modal(\'hide\')"><i class="las la-times-circle la-3x"></i><span>Cerrar</span></div>');
    }

    loadingSmall.attr('data-show', 'false');
}

function getIndexByName(name) {
    name = name.match(/\d+/)[0];
}


$('body').on('click', '[data-click="switchHeadquarter"]', function(e) {
    var id = $(this).data('id');

    Swal.fire({
        title: 'Cambiando de sede',
        html: 'Por favor espere...',
        timerProgressBar: true,
        onBeforeOpen: () => {
            Swal.showLoading()
            timerInterval = setInterval(() => {
                const content = Swal.getContent()
                if (content) {
                    const b = content.querySelector('b')
                    if (b) {
                        b.textContent = Swal.getTimerLeft()
                    }
                }
            }, 100)
        },

    });
    switchHeadquarter(id).done(function(data) {

        if (data.success) {
            location.reload();
        } else {
            Swal.close();
            swal("Sede no disponible", data.message, "warning");
        }
    }).fail(function(error) {
        Swal.close();
        handleErrors(error);
    });
});

function switchHeadquarter(id) {
    return $.ajax({
        url: ULR + 'headquarters/switch/' + id,
        method: 'GET',
        dataType: "json"
    });
}
$('body').on('click', '[data-click="switchCrm"]', function(e) {
    var id = $(this).data('id');

    Swal.fire({
        title: 'Cambiando de cliente',
        html: 'Por favor espere...',
        timerProgressBar: true,
        onBeforeOpen: () => {
            Swal.showLoading()
            timerInterval = setInterval(() => {
                const content = Swal.getContent()
                if (content) {
                    const b = content.querySelector('b')
                    if (b) {
                        b.textContent = Swal.getTimerLeft()
                    }
                }
            }, 100)
        },

    });
    switchCrm(id).done(function(data) {

        if (data.success) {
            location.href = ULR+"crm/"+data.id;
        } else {
            Swal.close();
            swal("Cliente no disponible", data.message, "warning");
        }
    }).fail(function(error) {
        Swal.close();
        handleErrors(error);
    });
});

function switchCrm(id) {
    return $.ajax({
        url: ULR + 'crms/switch/' + id,
        method: 'GET',
        dataType: "json"
    });
}


function initDataTable(dom ='<<"d-flex"<l><"absolute-right"f>><rt>ip>') {
    let date = new Date()
       
    $('#tblList').DataTable({
        language: dtEs,
        responsive: true,
        buttons: [
            {
                orientation: 'landscape',
                messageBottom: 'Información extraída del sistema PalmeraSoft - Palmera Junior S.A.S el '+ date.toISOString().split('T')[0],
                extend: 'pdf',
                text: 'Guardar en PDF',
                exportOptions: {
                    modifier: {
                        page: 'current'
                    }
                }
            }
        ],
        "iDisplayLength": 25,
        dom: dom,
        "aaSorting": [],
        "columnDefs": [
            { "orderable": false, "targets": [] },
            { responsivePriority: 1, targets: 0, "orderable": false },
            { responsivePriority: 2, targets: -1 }
        ],
        "initComplete": function() {
            var api = this.api();
            if (getUrlVars()['search']) {
                this.api().search(getUrlVars()['search']).draw();
            }

            //filterSelect([2], api);
            //filterInput([0], api);
        }
    });
}


var map;
var myCenter;
var marker;
var geocoder;
var circle;

function initialize() {

    marker = new google.maps.Marker({
        position: myCenter,
        map: map,
        draggable: true,
        animation: google.maps.Animation.DROP,
        icon: ULR + 'template/app-assets/images/logo/logo-min.png',
        size: new google.maps.Size(71, 71),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(17, 34),
        scaledSize: new google.maps.Size(25, 25)

    });

    //añadir eventos al marcador
    google.maps.event.addListener(marker, 'dragend', function(event) {
        setCoords(marker.getPosition());
    });

    // Add circle overlay and bind to marker
    circle = new google.maps.Circle({
        map: map,
        strokeWeight: 2,
        strokeColor: '#009688',
        draggable: true,
        radius: parseInt($('[data-change="changeRadius"]').val()) * 1000,
        fillColor: '#009687b5'
    });
    circle.bindTo('center', marker, 'position');

    //añadir eventos al radio
    google.maps.event.addListener(circle, 'dragend', function(event) {
        setCoords(marker.getPosition());
    });
}

function initSimpleMap() {



    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            initMarkerMap('Colombia', { lat: position.coords.latitude, lng: position.coords.longitude });
        });
    }

}

function _initGoogleMap(zoom = 11) {
    var mapProp = {
        center: myCenter,
        zoom: zoom,
        draggable: true,
        scrollwheel: true,
        streetViewControl: false,
        mapTypeControl: false,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    map = new google.maps.Map(document.getElementById("map"), mapProp);
    google.maps.event.addDomListener(window, 'load', initialize());
    google.maps.event.addDomListener(window, "resize", resizingMap());
    google.maps.event.trigger(map, "load");
}

function initMap(city = 'Bucaramanga', coords = false) {
    showLoadingMap();
    geocoder = new google.maps.Geocoder();
    geocoder.geocode({ 'address': city }, function(results, status) {
        if (coords != false) {
            setCoordsRaw(coords);
            myCenter = new google.maps.LatLng(coords.lat, coords.lng);
            _initGoogleMap();
        } else {
            if (status === 'OK') {
                setCoords(results[0].geometry.location);
                myCenter = new google.maps.LatLng(results[0].geometry.location.lat(), results[0].geometry.location.lng());
                _initGoogleMap();
            } else {
                Swal.close();
                swal('No se pudo inciar el mapa en la ciudad de esta sede', 'Las coordenadas de la ciudad ' + city + ' no pudieron ser encontradas por esta razón ' + status, 'warning');
            }
        }

    });
}



$('.modal').on('show.bs.modal', function() {
    localStorage.setItem('modal', 'show');
});


$('.modal').on('hide.bs.modal', function() {

    localStorage.setItem('modal', 'close');
});

$('#mdlFormMap').on('show.bs.modal', function() {
    //Must wait until the render of the modal appear, thats why we use the resizeMap and NOT resizingMap!! ;-)
    resizeMap();
});

$('body').on('change', '[data-change="changeRadius"]', function() {

    circle.setMap(null);
    circle = new google.maps.Circle({
        map: map,
        strokeWeight: 2,
        strokeColor: '#009688',
        draggable: true,
        radius: parseInt($(this).val()) * 1000, // kilómetros
        fillColor: '#009687b5',

    });
    circle.bindTo('center', marker, 'position');
    setCoords(marker.getPosition());
});

function resizeMap() {
    if (typeof map == "undefined") return;
    setTimeout(function() { resizingMap(); }, 400);
}

function resizingMap() {
    if (typeof map == "undefined") return;
    var center = map.getCenter();
    google.maps.event.trigger(map, "resize");
    map.setCenter(center);
    Swal.close();
}

function showLoadingMap() {
    let timerInterval
    Swal.fire({
        title: 'Cargando Mapa',
        html: 'Por favor espere...',
        timerProgressBar: true,
        onBeforeOpen: () => {
            Swal.showLoading()
            timerInterval = setInterval(() => {
                const content = Swal.getContent()
                if (content) {
                    const b = content.querySelector('b')
                    if (b) {
                        b.textContent = Swal.getTimerLeft()
                    }
                }
            }, 100)
        },
        onClose: () => {
            clearInterval(timerInterval)
        }
    });
}

function setCoords(coords) {
    $('[name*="lat"]').val(coords.lat());
    $('[name*="lng"]').val(coords.lng());
    verifyLatAndLng();
}

function setCoordsRaw(coords) {
    $('[name="lat"]').val(coords.lat);
    $('[name="lng"]').val(coords.lng);
}

const intersect = function(a, b) {
    return new Set(a.filter(v => ~b.indexOf(v)));
};

const gettowncity = function(addcomp) {
    if (typeof(addcomp) == 'object' && addcomp instanceof Array) {

        let order = ['locality', 'administrative_area_level_2'];

        for (let i = 0; i < addcomp.length; i++) {
            let obj = addcomp[i];
            let types = obj.types;
            if (intersect(order, types).size > 0) return obj.long_name;
        }
    }
    return false;
};

function removeNameStringInput(input, remove) {
    var name = $(input).attr('name');
    name = name.replace('[' + remove + ']', '');
    return name;
}

function getAddress(place) {
    if (place.vicinity != gettowncity(place.address_components)) {
        return place.vicinity ? place.vicinity : place.name;
    } else {
        return place.name;
    }
}

function initAddress() {
    var autocomplete;
    $('.searchMapInput').each(function(index, input) {
        autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.addListener('place_changed', function() {
            var place = autocomplete.getPlace();

            var name = removeNameStringInput(input, 'address');
            $("[name='address']").val(getAddress(place));
            $("[name='city_name']").val(gettowncity(place.address_components));
            $("[name='lat']").val(place.geometry.location.lat());
            $("[name='lng']").val(place.geometry.location.lng());
            $("[name='" + name + "[address]']").val(getAddress(place));
            $("[name='" + name + "[city_name]']").val(gettowncity(place.address_components));
            $("[name='" + name + "[lat]']").val(place.geometry.location.lat());
            $("[name='" + name + "[lng]']").val(place.geometry.location.lng());
            hideLoadingOnly();
        });
    });
}

/** Función para definir un punto en el mapa */
$('body').on('click', '[data-click="openMap"]', function(e) {
    e.preventDefault();
    var mdlForm = $("#mdlMap");

    var address = $("[name*='address']").val();
    var cityObject = $('[name*="city_name"]').select2('data');

    if (cityObject === undefined) {
        var cityObject = $('[name*="city_id"]').select2('data');
    }

    if (address == '' || cityObject[0] == undefined) {
        swal('Debes seleccionar la ciudad del cliente e ingresar una dirección física para poder abrir el mapa', "");
        return false;
    }

    try {
        var city = cityObject[0].name + ', ' + cityObject[0].state.name;
    } catch (error) {
        var city = cityObject[0].text;
    }


    if ($('[name*="lat"]').val() != '' && $('[name*="lng"]').val()) {
        var coords = {};
        coords.lat = $('[name*="lat"]').val();
        coords.lng = $('[name*="lng"]').val();
    } else {
        var coords = false;
    }


    getMapFrame(address, city).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        mdlForm.modal('show');
        initMarkerMap(address + ' ' + city, coords);
    }).fail((error) => {
        handleErrors(error);
    });
});

$('body').on('click', '.reset-pin', function(e) {
    initMarkerMap($('[name="map-address"]').val(), false);
});

function getMapFrame(address = '', city = '') {
    return $.ajax({
        url: ULR + 'crm/map-frame',
        data: { address: address, city: city },
        dataType: 'json',
        method: 'GET'
    });
}

function initMarkerMap(address = '', coords = false) {
    if (address.trim() == '') address == ' Medellín';
    showLoadingMap();
    geocoder = new google.maps.Geocoder();
    geocoder.geocode({ 'address': address }, function(results, status) {
        if (coords != false) {
            setCoordsRaw(coords);
            myCenter = new google.maps.LatLng(coords.lat, coords.lng);
            _initGoogleMapMarker();
        } else {
            if (status === 'OK') {
                setCoords(results[0].geometry.location);
                myCenter = new google.maps.LatLng(results[0].geometry.location.lat(), results[0].geometry.location.lng());
                _initGoogleMapMarker();
            } else {
                Swal.close();
                swal('No se pudo inciar el mapa en dirección especificada', 'Las coordenadas ' + address + ' no pudieron ser encontradas por esta razón ' + status, 'warning');
            }
        }

    });
}

function _initGoogleMapMarker() {
    var mapProp = {
        center: myCenter,
        zoom: 17,
        draggable: true,
        scrollwheel: true,
        streetViewControl: false,
        mapTypeControl: false,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    map = new google.maps.Map(document.getElementById("map"), mapProp);
    google.maps.event.addDomListener(window, 'load', initializeMarker());
    google.maps.event.addDomListener(window, "resize", resizingMap());
    google.maps.event.trigger(map, "load");
}

function initializeMarker() {

    marker = new google.maps.Marker({
        position: myCenter,
        map: map,
        draggable: true,
        animation: google.maps.Animation.DROP

    });

    //añadir eventos al marcador
    google.maps.event.addListener(marker, 'dragend', function(event) {
        setCoords(marker.getPosition());
    });

}

$('body').on('change', function(e) {
    verifyLatAndLng();
});

function verifyLatAndLng() {
    var lat = $('[name*="lat"]').val();
    var lng = $('[name*="lng"]').val();

    if (lat != '' && lng != '') {
        $('.openMapSuccess').removeClass('display-hidden');
        $('.openMapSuccess').addClass('display-block');
    } else {
        $('.openMapSuccess').removeClass('display-block');
        $('.openMapSuccess').addClass('display-hidden');
    }
}

/*
 * PARA CALCULAR SI UNA COORDENADA SE ENCUENTRA DENTRO DEL RADIO:
 * https://stackoverflow.com/questions/50342507/check-if-a-coordinate-is-contained-within-a-circle-in-google-maps
 */

var timeout = null;

function showLoading() {
    if (SEE_LOADING) {
        timeout = setTimeout(function() {
            $.blockUI({ message: '<div class="ft-refresh-cw icon-spin font-medium-2"></div><div class="">Espera un momento...</div>', overlayCSS: { backgroundColor: "#FFF", cursor: "wait", opacity: 0.8 }, css: { border: 0, padding: 0, backgroundColor: "none", zIndex: '105000001!important' } });
        }, 500);
    }
}

function hideLoadingOnly() {
    clearTimeout(timeout);
    try {
        $.unblockUI();
    } catch (error) {
        
    }
   
}

function hideLoading() {
    clearTimeout(timeout);
    try {
        $.unblockUI();
    } catch (error) {
        
    }
    
    initTextareaResize();
    if (RESTORE_SELECT2) {
        $('.select2-container').css({ 'z-index': '100000' });
    }
    try {
        $("[title='Filtrar']").selectpicker('refresh');
    } catch (error) {

    }
}

$(document).ajaxStart(showLoading)
    .ajaxStop(hideLoading)
    .ajaxError(hideLoading);

$('.dropdown-submenu > a').on("click", function(e) {
    e.preventDefault();
    var submenu = $(this);
    $('.dropdown-submenu .dropdown-menu').removeClass('show');
    submenu.next('.dropdown-menu').addClass('show');
    e.stopPropagation();
});

$('.dropdown').on("hidden.bs.dropdown", function() {
    // hide any open menus when parent closes
    $('.dropdown-menu.show').removeClass('show');
});

//** FUNCIONES PARA FIRMAS */

$('body').on('click', '[data-upload="file2"]', function(e) {
    $('[data-input="fileImage2"]').click();
});


function loadImage2(input) {

    var element = $('[data-upload="file2"]');
    var loading = $('.loading', element).first();
    loading.attr('data-show', 'true');

    if (input.files && input.files.length > 0) {
        var reader = new FileReader();
        var file = input.files[0];
        reader.readAsDataURL(file);
        reader.onload = function() {

            if (validateImg(file.type)) {
                $('.signature', element).attr('src', reader.result);
                $('[name="signature" ]', element).val(reader.result);
            }
            loading.attr('data-show', 'false');
        }
    } else {
        loading.attr('data-show', 'false');
    }

}


function loadImage3(input) {

    var element = $(input).parent();
    var loading = $('.loading', element).first();
    loading.attr('data-show', 'true');

    if (input.files && input.files.length > 0) {
        var reader = new FileReader();
        var file = input.files[0];
        reader.readAsDataURL(file);
        reader.onload = function() {

            if (validateImg(file.type)) {
                $('img', element).attr('src', reader.result);
                $('[name=*"[avatar]"', element).val(reader.result).trigger('change');
            }
            loading.attr('data-show', 'false');
        }
    } else {
        loading.attr('data-show', 'false');
    }
}

function unpush(arr, item) {
    var i = arr.indexOf(item);

    if (i !== -1) {
        arr.splice(i, 1);
    }
}


//evitar scroll cuando se hace click en enlace con href
$('body').on('click', 'a[href="#"]', function(e) {
    e.preventDefault();
});

//autosizar los textareas cuando se escribe

function initTextareaResize() {

    //inicializar los texareas que se muestran en pantalla
    var div = document.querySelector('textarea');
    var textAreaObserver = new MutationObserver(function(mutations, me) {
        if (div) {
            $("textarea").each(function(i,el) {
                $(el).css('height', el.value.trim().length != 0 ? el.scrollHeight : '42px');
            });
            me.disconnect(); // stop observing
            return;
        }
    });

    // start observing
    textAreaObserver.observe(document, {
        childList: true,
        subtree: true
    });




    //autoreizar cada vez que se escriba sobre el texarea
    $("textarea").on("input", function() {
        var element = $(this)[0];
        element.style.height = "42px";
        element.style.height = ((element.scrollHeight + 5)) + "px";
    });
}

/**LOADING PAGE; https://cssauthor.com/loading-animation/ */


// detectar cuando onesignal muestra notificaciones para actualizarlas en el sistema
window.OneSignal = window.OneSignal || [];
OneSignal.push(function() {
    OneSignal.on('notificationDisplay', function(event) {
        console.log('OneSignal notification displayed:', event);
        getNotifications();
    });
    OneSignal.on('notificationDismiss', function(event) {
        console.log('OneSignal notification dismissed:', event);
        getNotifications();
    });
});


//marcar notifcaciones como leídas cuando se abre el dropdown
$('body').on('click', '.markAsRead', function(e) {
    $('.badge-notification').hide();
    $('.ft-bell').removeClass('bell-shake');
    $.ajax({
        url: ULR + 'notifications/mark-as-read',
        method: 'GET',
        dataType: 'json'
    }).done(function(data) {
        console.log("Notifications mark as readed");
    }).fail(function(error) {
        handleErrors(error);
    });
});


//eliminar notificaciones cuando se hace click sobre ella (en los casos que aplique)

$('body').on('click', '[data-click="deleteNotification"]', function(e) {
    e.preventDefault();
    var id = $(this).data('id');

    deleteNotification(id).done((data) => {
        if (data.success) {
            if ($(this).attr('href') != "#" && $(this).attr('href') != "" && $(this).attr('href') != undefined) {
                window.location.href = $(this).attr('href');
            } else {
                getNotifications();
            }

        } else {
            swal('Algo salió mal al redireccionar desde la notificación', 'Error', 'error');
        }

    }).fail(function(error) {
        handleErrors(error);
    });

});

$('body').on('click', '.all-notifications', function(e) {
    e.preventDefault();

    $('body').on('click', '[data-click="deleteInventory"]', function(e) {
        //abrir el modal y mostrar loading
        e.preventDefault();
        var mdlForm = $('#mdlFormMove');
        var loading = $('.loading', mdlForm).first();
        var id = $(this).data('id');
        var warehouse = $(this).data('warehouse');
        Swal.fire({
            title: 'Eliminar todas las notificaciones',
            text: "¿Estas seguro que deseas eliminarlas? Esta acción será irreversible.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#009688',
            cancelButtonColor: '#aaa',
            confirmButtonText: 'Si, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            //console.log(result);
            if (result.value) {
                deleteAllNotification().done((data) => {
                    if (data.success) {
                        window.location.href = $(this).attr('href');
                    } else {
                        swal('Algo salió mal al eliminar todas la notificaciones', 'Error', 'error');
                    }
            
                }).fail(function(error) {
                    handleErrors(error);
                });
            }
    
        });
    });
});


function getNotifications() {
    $.ajax({
        url: ULR + 'notifications/list',
        method: 'get',
        dataType: 'json'
    }).done(function(data) {
        $(".notification-container").html(data.view);
    }).fail(function(error) {
        handleErrors(error);
    });
}

function deleteNotification(id) {
    return $.ajax({
        url: ULR + 'notifications/' + id + '/delete',
        method: 'get',
        dataType: 'json'
    });
}

function deleteAllNotification() {
    return $.ajax({
        url: ULR + 'notifications/delete/all',
        method: 'get',
        dataType: 'json'
    });
}

$(function() {

    var hash = window.location.hash;
    if (hash) {
        hideLoadingOnly();
        //remover todos los tabs activos
        $('a.nav-link').removeClass('active');

        $('a[href="' + hash + '"]').addClass('active');
        //remover todos los tabs activos
        $('div.tab-pane').removeClass('active');
        $('div' + hash).addClass('active');


        var hasObserver = new MutationObserver(function(mutations, me) {
            console.log('waiting tab hash event...');
            if (typeof window[(hash.split("#")[1]+'Observable')] != "undefined") {
                setTimeout(function() {
                    $('a[href="' + hash + '"]').trigger('click');
                },700)
                
                me.disconnect();
                console.log('desconecting observable tab hash');
            }
            
        });
    
        // start observing
        hasObserver.observe(document, {
            childList: true,
            subtree: true
        });

    } else {

    }
});

//procesar los avatars
var CONTROLSELECTED = [];
const colors = ['#00AA55', '#009FD4', '#B381B3', '#939393', '#E3BC00', '#D47500', '#DC2A2A', '#7b1fa2'];

function numberFromText(text) {
    // numberFromText("AA");
    const charCodes = text
        .split('') // => ["A", "A"]
        .map(char => char.charCodeAt(0)) // => [65, 65]
        .join(''); // => "6565"
    return parseInt(charCodes, 10);
}

function processAvatars() {
    var avatars = document.querySelectorAll('.text-avatar');

    avatars.forEach(avatar => {
        const text = avatar.innerText; // => "AA"
        avatar.style.backgroundColor = colors[numberFromText(text) % colors.length]; // => "#DC2A2A"
        var reference = avatar.dataset.reference;
        var table = document.querySelector('#' + reference + ' thead tr');
        table.style.backgroundColor = colors[numberFromText(text) % colors.length];
    });

}

$('body').on('click', '[data-click="showinfo"]', function() {
    Swal.fire({
        title: $(this).data('title'),
        text: $(this).data('text'),
        showClass: {
            popup: 'animate__animated animate__fadeInDown'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutUp'
        }
    });
});


function getUrlVars() {
    var url = window.location.href.split('#')[0]
    var vars = {};
    var parts = url.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m, key, value) {
        vars[key] = value;
    });
    return vars;

}

//proteger las imagenes del soft contra descargas (firmas y avatars)
$('body').on('contextmenu','img',function (e) {
    if(e.button == 2) { // right click
      return false; // do nothing!
    }
  });

//$('body').on('dragstart', 'img', function(event) { event.preventDefault(); });