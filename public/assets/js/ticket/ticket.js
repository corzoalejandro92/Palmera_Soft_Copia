TIMELINE = null;
var MOVE_EVENTS = {};
var REMOVE_EVENTS = {};
var mdlForm = $('#mdlFormShow');


$("body").ready(function() {
    getList();
    initSelectUsers();
    initSelectCustomer();
});
$('body').on('click', '.page-link', function(e) {
    e.preventDefault();
    var page = $(this).attr('href').split('page=')[1];
    getList(page);
    
});
$('body').on('click', '[data-click="openlistTicketShow"]', function(e) {
    e.preventDefault();
    var mdlForm = $('#mdlFormShow');
    var loading = $('.loading', mdlForm).first();
    var id = $(this).data('id');
    loading.attr('data-show', 'true');
    mdlForm.modal('show');

    loadListTicketShow(id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        loading.attr('data-show', 'false');
    }).fail(function(error) {
        handleErrors(error);
    });
});
function loadListTicketShow(id) {
    return $.ajax({
        url: ULR + "crm-ticket-show",
        method: "GET",
        data: { id: id},
        dataType: "json"
    });
}
$('body').on('click', '[data-click="solved"]', function(e) {
    sendStateChange().done(function(success) {
        setTimeout(() => {
            getList();
            $("[data-dismiss=modal]").trigger({ type: "click" });
        }, 500);
    }).fail(function(error) {
        handleErrors(error);
    });
});
$('body').on('click', '[name="priority_id"]', function(e) {
    sendPriorityChange().done(function(success) {
        setTimeout(() => {
            getList();
            $("[data-dismiss=modal]").trigger({ type: "click" });
        }, 500);
    }).fail(function(error) {
        handleErrors(error);
    });
});
function sendPriorityChange() {
    var form = $('#answerTicket');
    var data = form.serialize();
    return $.ajax({
        url: ULR + "crm-priority-change",
        method: 'POST',
        data: data,
        dataType: "json"
    });

}
function sendStateChange() {
    var form = $('#answerTicket');
    var data = form.serialize();
    return $.ajax({
        url: ULR + "ticket-state-change",
        method: 'POST',
        data: data,
        dataType: "json"
    });

}
function getList(page=null) {
    var list = $('#list');
    $('.loading', list).attr('data-show', 'true');
    loadList(page).done(function(data) {
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
function loadList(page) {
    let Npage = $('ul.pagination>li.active').text();
    let n_page = page != null ? page : Npage;
    return $.ajax({
        url: ULR + "tickets",
        method: "GET",
        dataType: "json",
        data:{
            paginate:$("[name='tblList_length']").val(),
            category:$("[name='tblList_category']").val(),
            priority:$("[name='tblList_priority']").val(),
            date:$("[name='start_date']").val(),
            users:$("[name='users[]'").val(),
            read:$('input[name="read"]:checked').val(),
            solved:$('input[name="solved"]:checked').val(),
            date:$("[name='date']").val(),
            order:$("[name='order']").val(),
            page:n_page,
            customer:$('select[name="crm_id"] option').filter(':selected').val(),
        }
    });
}
function sendRestore() {

    var form = $('#frmRestore');
    var data = form.serialize();

    return $.ajax({
        url: ULR + "tickets/restore",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });
}


$('body').on('click', "#datebtn", function(e) {
    getList();
});
//abrir formulario de eliminacion de registro
$('body').on('click', '[data-click="openFormView"]', function(e) {
    e.preventDefault();
    var id = $(this).data('id');
    var mdlForm = $('#mdlForm');
    loadFormView(id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        mdlForm.modal('show');
        initTextareaResize();
    }).fail(function(error) {
        handleErrors(error);
    });

});
$('body').on('click', '[name="read"]', function(e) {
    getList();
});

$('body').on('click', '[name="solved"]', function(e) {
    getList();
});


function loadFormView(id) {
    return $.ajax({
        url: ULR + "tickets-view",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}
$('body').on('click', '[data-click="answer_ticket"]', function(e) {

    var mdlForm = $('#mdlFormShow');

    saveAnswerTicket().done(function(data) {
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
        mdlForm.modal('hide');
        getList();
    }).fail(function(error) {
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'false');
        showErrors(error.responseJSON.errors, '#answerTicket');
        handleErrors(error);
    });
});
function saveAnswerTicket() {
    

    var form = $('#answerTicket')[0];
    var formData = new FormData(form);
    return $.ajax({
        url: ULR + "crm/save-ticket-answer",
        contentType: false,
        processData: false,
        headers: {
            'X-CSRF-Token': $('#formSaveTicket [name="_token"]').val()
        },
        method: $('[name="_method"]', form).val(),
        data: formData,
        success: success,
        error: fail
    });
}
function success(data) {
    if (data.success) {
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'false');

        $("[data-dismiss=modal]").trigger({ type: "click" });

    }
    swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");

}

function fail(error) {
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'false');
    showErrors(error.responseJSON.errors, '#formSaveTicket');
    handleErrors(error);
}
$('body').on('change', "[name='crm_id']", function(e) {
    getList();
});
$('body').on('change', "[name='tblList_length']", function(e) {
    getList();
});
$('body').on('change', "[name='order']", function(e) {
    getList();
});
$('body').on('change', "[name='tblList_action']", function(e) {
    return $.ajax({
        url: ULR + "tickets",
        method: "GET",
        dataType: "json",
        data: {search: $("[name='search']").val(),perstartdate: $("[name='start_date']").val(),perenddate:$("[name='end_date']").val(),perusers:$("[name='users[]']").val(),perheadquarter:$("[name='tblList_category']").val() ,peraction:$("[name='tblList_action']").val(), permodel: $("[name='tblList_model']").val() ,perpage: $("[name='tblList_length']").val(), date: $('input#datetable').val(), search: $("[name='search']").val()},
        success: function(data){
            $('#list').html(data.view);
        }
    });
});
$('body').on('change', "[name='tblList_category']", function(e) {
    getList();
});
$('body').on('change', "[name='tblList_priority']", function(e) {
    getList();
});
$('body').on('change', "[name='date']", function(e) {
    getList();
});
$('body').on('change', "[name='users[]']", function(e) {
    getList();
});
function initSelectUsers() {

    $('[name="users[]"]').select2({
        width: '100%',
        placeholder: "Al menos un usurio",
        ajax: {
            url: ULR + "scheduling/users",
            dataType: 'json',
            method: "GET",
            error: function(error) {
                handleErrors(error);
            }
        },
        "language": {
            "noResults": () => "No se encontraron usuarios para esta sede.",
            "searching": () => "Consultando..."
        },
        //dropdownAdapter: Utils.Decorate( Utils.Decorate(Dropdown,AttachBody),SelectAll)
    });
}
function initSelectCustomer() {

    $('#customer').select2({
        width: '100%',
        allowClear: true,
        placeholder: 'Cliente',
        ajax: {
            url: ULR + "tickets/customer",
            dataType: 'json',
            method: "GET",
            success: selectScroll
        },
        cache: true,
        templateResult: dataFormatResultCRM,
        templateSelection: dataFormatSelection,
        dropdownCssClass: "bigdrop",
        escapeMarkup: function(m) { return m; },
        "language": {
            "noResults": () => "No se encontraron clientes asignados a ti.",
            "searching": () => "Consultando..."
        }
    })/*.on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        CUSTOMER_ID = select_val;


    }).on("select2:open", e => selectScroll());*/
}