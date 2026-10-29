//código para leer tabla
START_DATE = moment().format('YYYY-MM-DD');
END_DATE = moment().format('YYYY-MM-DD');
START_DATE_FORMAT = moment().format('DD/MM/YYYY');
END_DATE_FORMAT = moment().format('DD/MM/YYYY');
PAGE = 1;


$('body').ready(function() {

    $('[name="range_date"]').daterangepicker({
        "showDropdowns": true,
        "startDate": START_DATE_FORMAT,
        "endDate": END_DATE_FORMAT,
        ranges: {
             'Hoy': [moment(), moment()],
            'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Últimos 7 dias': [moment().subtract(6, 'days'), moment()],
            'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
            'Este mes': [moment().startOf('month'), moment().endOf('month')],
            'El mes pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
           
            
        },
        "locale": {
            "format": "DD/MM/YYYY",
            "separator": " - ",
            "applyLabel": "Aplicar",
            "cancelLabel": "Cancelar",
            "customRangeLabel": "Personalizado",
            "fromLabel": "Desde",
            "toLabel": "a",
            "daysOfWeek": [
                "Su",
                "Mo",
                "Tu",
                "We",
                "Th",
                "Fr",
                "Sa"
            ],
            "monthNames": [
                "Enero",
                "Febrero",
                "Marzo",
                "Abril",
                "Mayo",
                "Junio",
                "Julio",
                "Agosto",
                "Septiembre",
                "Octubre",
                "Noviembre",
                "Deciembre"
            ],
            "firstDay": 1
        },
        "opens": "right",
        "drops": "auto",
        "buttonClasses": "btn",
        "applyButtonClasses": "btn-warning"
    });
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

$('body').on('change', "select[name='documento']", delay(function(e) {
    var type = $(this).data('type');
    return $.ajax({
        url: ULR + "inventory/foxconDocs/list?bnacional=" + $("[name='bnacional']").val(),
        method: "GET",
        dataType: "json",
        data: {
            //search: this.value, 
            documento: $("select[name='documento']").val(),
            search: $("input[type='search']").val(), 
            perpage: $("[name='tblList_length']").val(),
            date: $('input#datetable').val(),
            numero: $('input[name="numero"]').val(),
            document_type: $("select[name='documento']").val(),
            bodega: $("input[name='bodega']").val(),
            producto: $("input[name='producto']").val(),
            start_date: START_DATE,
            end_date: END_DATE
        },
        success: function(data){
            $('#list tbody').html(data.view);
            $('#list-footer').html(data.footer);
        }
    });

},0));

$('body').on('keyup', "input[name='numero'], input[name='bodega'], input[name='producto']", delay(function(e) {
    return $.ajax({
        url: ULR + "inventory/foxconDocs/list?bnacional=" + $("[name='bnacional']").val(),
        method: "GET",
        dataType: "json",
        data: {
            //search: this.value, 
            documento: $("select[name='documento']").val(),
            search: $("input[type='search']").val(), 
            perpage: $("[name='tblList_length']").val(),
            numero: $('input[name="numero"]').val(),
            date: $('input#datetable').val(),
            document_type: $("select[name='documento']").val(),
            bodega: $("input[name='bodega']").val(),
            producto: $("input[name='producto']").val(),
            start_date: START_DATE,
            end_date: END_DATE
        },
        success: function(data){
            $('#list tbody').html(data.view);
            $('#list-footer').html(data.footer);
        }
    });

},500));

$('input[name="range_date"]').on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
    START_DATE = picker.startDate.format('YYYY-MM-DD');
    END_DATE = picker.endDate.format('YYYY-MM-DD');
    return $.ajax({
        url: ULR + "inventory/foxconDocs/list?bnacional=" + $("[name='bnacional']").val(),
        method: "GET",
        dataType: "json",
        data: {
            //search: this.value, 
            documento: $("select[name='documento']").val(),
            page:PAGE, 
            search: $("input[type='search']").val(), 
            perpage: $("[name='tblList_length']").val(),
            numero: $('input[name="numero"]').val(),
            date: $('input#datetable').val(),
            document_type: $("select[name='documento']").val(),
            bodega: $("input[name='bodega']").val(),
            producto: $("input[name='producto']").val(),
            start_date: START_DATE,
            end_date: END_DATE
        },
        success: function(data){
            $('#list tbody').html(data.view);
            $('#list-footer').html(data.footer);
        }
    });
});

$('input[name="range_date"]').on('cancel.daterangepicker', function(ev, picker) {
    $(this).val('');
    START_DATE = null;
    END_DATE = null;
    return $.ajax({
        url: ULR + "inventory/foxconDocs/list?bnacional=" + $("[name='bnacional']").val(),
        method: "GET",
        dataType: "json",
        data: {
            //search: this.value, 
            documento: $("select[name='documento']").val(),
            page:PAGE, 
            search: $("input[type='search']").val(), 
            perpage: $("[name='tblList_length']").val(),
            numero: $('input[name="numero"]').val(),
            date: $('input#datetable').val(),
            document_type: $("select[name='documento']").val(),
            bodega: $("input[name='bodega']").val(),
            producto: $("input[name='producto']").val(),
            start_date: START_DATE,
            end_date: END_DATE
        },
        success: function(data){
            $('#list tbody').html(data.view);
            $('#list-footer').html(data.footer);
        }
    });
});

$('body').on('click', '.page-link', function(e) {
    e.preventDefault();
    PAGE = $(this).attr('href').split('page=')[1];
    return $.ajax({
        url: ULR + "inventory/foxconDocs/list?bnacional=" + $("[name='bnacional']").val(),
        method: "GET",
        dataType: "json",
        data: {
            documento: $("select[name='documento']").val(),
            page:PAGE, 
            search: $("input[type='search']").val(), 
            perpage: $("[name='tblList_length']").val(),
            numero: $('input[name="numero"]').val(),
            date: $('input#datetable').val(),
            document_type: $("select[name='documento']").val(),
            bodega: $("input[name='bodega']").val(),
            producto: $("input[name='producto']").val(),
            start_date: START_DATE,
            end_date: END_DATE
        },
        success: function(data){
            $('#list tbody').html(data.view);
            $('#list-footer').html(data.footer);
        }
    });
    
});



function getList() {
    var list = $('#list tbody');
    var footer = $('#list-footer');
    $('.loading', list).attr('data-show', 'true');
    loadList().done(function(data) {
        list.html(data.view);
        footer.html(data.footer);
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
        url: ULR + "inventory/foxconDocs/list?bnacional=" + $("[name='bnacional']").val(),
        method: "GET",
        dataType: "json",
        data: {
            documento: $("select[name='documento']").val(),
            page:PAGE, 
            search: $("input[type='search']").val(), 
            perpage: $("[name='tblList_length']").val(),
            date: $('input#datetable').val(),
            document_type: $("select[name='documento']").val(),
            bodega: $("input[name='bodega']").val(),
            producto: $("input[name='producto']").val(),
            start_date: START_DATE,
            end_date: END_DATE
        }
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

        mdlForm.modal('show');
    }).fail(function(error) {
        handleErrors(error);
    });
});

function loadForm(id) {
    return $.ajax({
        url: ULR + "applications/form",
        method: "GET",
        data: {
            id: id
        },
        dataType: "json"
    });
}


//enviar formulario para guardar información
$('body').on('click', '[data-click="save"]', function(e) {
    e.preventDefault();
    var mdlForm = $('#mdlDelete');
    save().done(function(data) {
        if (data.success) {
            mdlForm.modal('hide');
            getList();
        }
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }).fail(function(error) {
        handleErrors(error);
    });

});

function save() {

    var form = $('#frm');
    var data = form.serialize();
    return $.ajax({
        url: ULR + 'applications/save',
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });
}

//abrir formulario de eliminacion de registro
$('body').on('click', '[data-click="openFormDelete"]', function(e) {
    e.preventDefault();
    var id = $(this).data('id');
    var detail = $(this).data('detail');
    Swal.fire({
        title: 'Anular este documento',
        text: "¿Anular el documento "+detail+"? Se reestablecerá el stock a las bodegas correspondientes",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e91e4d',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Si, Anular',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.value) {
            sendDelete(id).done(function(data) {

                if (data.success) {
                    showToast(data.message);
                    getList();
                } else {
                    showToastError(data.message);
                }


            }).fail(function(error) {
                handleErrors(error);
            });
        }
    });

});


function sendDelete(id) {
    return $.ajax({
        url: ULR + "inventory/foxconDocs/list/"+id+"/delete",
        method: 'GET',
        dataType: "json"
    });
}

function loadFormDelete(id) {
    return $.ajax({
        url: ULR + "applications/delete-form",
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

