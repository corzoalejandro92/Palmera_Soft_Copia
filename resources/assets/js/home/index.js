$('body').ready(function() {
    getFilters();
    getTableCommercial();
    getTableProgramming();
    getTableTechnical();

    $('body').on('click','[name="integration-switch"]', function (event) {
        $.ajax({
            url: ULR +"integrations/switch",
            type: 'GET',
            dataType: 'json',
            data: {'id': $(this).data('id'), 'enabled': $(this).is(':checked') ? 1 : 0}
        }).done(function(response) {
            if(response.success){
                showToast(response.message);
            }else{
                showToastError(response.message);
            }
        }).fail(function(response) {
            handleErrors(response);
        });
    });

    $('body').on('click','[name="integration-switch-headquarters[]"]', function (event) {
        $.ajax({
            url: ULR +"integrations/switch/headquarter",
            type: 'GET',
            dataType: 'json',
            data: {'id': $(this).data('id'),  'headquarter_id': $(this).data('headquarter'), 'enabled': $(this).is(':checked') ? 1 : 0}
        }).done(function(response) {
            if(response.success){
                showToast(response.message);
            }else{
                showToastError(response.message);
            }
        }).fail(function(response) {
            handleErrors(response);
        });
    });

});

/******************************************************************************/
//funciones para filtrar

function getFilters() {
    var container = $('#filterContent');
    $('.loading', container).attr('data-show', 'true');
    loadContainer().done(function(data) {
        container.html(data.view);
        $('.loading', container).attr('data-show', 'false');
        $(".collapsed").trigger('click');
        initSelect();
    }).fail(function(error){
        handleErrors(error);
    });
}

function loadContainer() {
    return $.ajax({
        url:   ULR +"dashboard/filter",
        method: "GET",
        dataType: "json"
    });
}

function initSelect(){
    initSelectfilterUser();
    initSelectfilterService();
    initSelectfilterCustomer();
    initSelectfilterProgramming();
}

function initSelectfilterUser() {
    $('[name="filterTechnical[]"]').select2({
        placeholder: "Dejar vacío",
        ajax: {
        url: ULR +"dashboard/user-filter",
        dataType: 'json',
        method: "GET",
        error: function(error){
            handleErrors(error);
        }
        },
        "language": {
            "noResults": () => "No se encontraron tipos de eventos registrados en el sistema.",
            "searching":() => "Consultando..."
        },
    });
}

function initSelectfilterService() {
    $('[name="filterService[]"]').select2({
        placeholder: "Dejar vacío",
        ajax: {
        url: ULR +"dashboard/service-filter",
        dataType: 'json',
        method: "GET",
        error: function(error){
            handleErrors(error);
        }
        },
        "language": {
            "noResults": () => "No se encontraron tipos de eventos registrados en el sistema.",
            "searching":() => "Consultando..."
        },
    });
}

function initSelectfilterCustomer() {
    $('[name="filterCustomer[]"]').select2({
        placeholder: "Dejar vacío",
        ajax: {
        url: ULR +"dashboard/customer-filter",
        dataType: 'json',
        method: "GET",
        error: function(error){
            handleErrors(error);
        }
        },
        "language": {
            "noResults": () => "No se encontraron tipos de eventos registrados en el sistema.",
            "searching":() => "Consultando..."
        },
    });
}

function initSelectfilterProgramming() {
    $('[name="filterProgramming[]"]').select2({
        placeholder: "Dejar vacío",
        ajax: {
        url: ULR +"dashboard/programming-filter",
        dataType: 'json',
        method: "GET",
        error: function(error){
            handleErrors(error);
        }
        },
        "language": {
            "noResults": () => "No se encontraron tipos de eventos registrados en el sistema.",
            "searching":() => "Consultando..."
        },
    });
}

/******************************************************************************/
//funciones para cargar la tabla de commercial 
function getTableCommercial(start=null, end=null) {
    var list = $('#tableCommercial');
    $('.loading', list).attr('data-show', 'true');
    loadTableCommercial(start, end).done(function(data) {
        list.html(data.view);
        $('.loading', list).attr('data-show', 'false');
    }).fail(function(error){
        handleErrors(error);
    });
}

function loadTableCommercial(start, end) {
    return $.ajax({
        url:   ULR +"dashboard/commercial",
        method: "GET",
        data: {start: start, end: end},
        dataType: "json"
    });
}

/******************************************************************************/
//funciones para cargar la tabla de commercial 
function getTableProgramming(start=null, end=null) {
    var list = $('#tableProgramming');
    $('.loading', list).attr('data-show', 'true');
    loadTableProgramming(start,end).done(function(data) {
        list.html(data.view);
        $('.loading', list).attr('data-show', 'false');
    }).fail(function(error){
        handleErrors(error);
    });
}

function loadTableProgramming(start, end) {
    return $.ajax({
        url: ULR +"dashboard/programming",
        method: "GET",
        dataType: "json",
        data: {start: start, end: end},
    });
}

/******************************************************************************/
//funciones para cargar la tabla de tecnicos
function getTableTechnical(start=null, end=null) {
    var list = $('#tableTechnical');
    $('.loading', list).attr('data-show', 'true');
    loadTableTechnical(start, end).done(function(data) {
        list.html(data.view);
        $('.loading', list).attr('data-show', 'false');
    }).fail(function(error){
        handleErrors(error);
    });
}

function loadTableTechnical(start, end) {
    return $.ajax({
        url: ULR +"dashboard/technical",
        method: "GET",
        dataType: "json",
        data: {start: start, end: end},
    });
}

/******************************************************************************/

$('body').on('submit', '#dasboardMonth', function(e) {
    e.preventDefault();
    var start = $("[name='start_date']").val();
    var end = $("[name='end_date']").val();
    getTableCommercial(start, end);
    getTableProgramming(start, end);
    getTableTechnical(start, end);
});