METHOD = 'POST';
TAB = 'tabVerticalLeft21';
COUNTRYOFFICE = '';
STATEOFFICE = '';
HEADQUARTER_ID = '';
KEY_DELETE = '';
FROM_PICKER = null;
TO_PICKER = null;
EDITOR = null;
SERVICE = null;
modalForceClose = false;
OFFICECUSTOMER = null;
myObj = new Object();
var mdlDelete = $('#mdlDelete');
var Office = $('select[name="OfficesCustomer"] option').filter(':selected').val();


/******************************************************************************/

//lista de tablas de show


//------------------- CRONOGRAMA DEL CLIENTE ------------------------

(async function() {
    await $.getScript(ULR + "assets/js/crm/show/observers.js?v="+APP_VERSION);
    //importar todas las funcionalidades de cronogramas
    await $.getScript(ULR + "assets/js/crm/show/schedule_functions.js?v="+APP_VERSION);
    await $.getScript(ULR + "assets/js/crm/show/schedule.js?v="+APP_VERSION);
})();

$(document).ready(function() {

    getList();
    getFilters();
    initSelectTypeCrm();
    initSelectTypeOffice($("[name='id']").val());
    initSelectOfficesCustomerCrm($("[name='id']").val());

    initInputDate();
    setTimeout(() => {
        OFFICECUSTOMER = $('select[name="OfficesCustomer"] option').filter(':selected').val();
        try { if (callOpenForm) callOpenFormFunction(); } catch (error) {}
        InitHeatReport();
    }, 300);

});


$('body').on('change', '#OfficesCustomer', function(event) {
    OFFICECUSTOMER = $('#OfficesCustomer').val();
    Office = $('#OfficesCustomer').val();
    $(".nav-link.active").first().trigger('click');
});

function FocusOnInput(){
    setTimeout(() => {
        $("#search").focus()
        let value=$("#search").data('value');

        $("#search").val(value);
    },200)
    }


function getList() {
    var list = $('#list');
    $('.loading', list).attr('data-show', 'true');
    loadListCustomer().done(function(data) {
        list.html(data.view);
        $('.loading', list).attr('data-show', 'false');
       setTimeout(() => {
        $('.nav-headder').removeClass('expanded');
        $(".vertical-menu-modern").removeClass('menu-expanded');
        $(".vertical-menu-modern").addClass('menu-collapsed');

       }, 500);

        //$(".modern-nav-toggle").trigger('click');
        
        
    }).fail(function(error) {
        handleErrors(error);
    });
}


function getListContact() {
    var list = $('#listAdvance');
    $('.loading', list).attr('data-show', 'true');
    loadListContact($("#OfficesCustomer").val()).done(function(data) {
        list.html(data.view);
        initDataTableAdvance(TAB);
        $('.loading', list).attr('data-show', 'false');
    }).fail(function(error) {
        handleErrors(error);
    });
}

function getListOffice() {
    var list = $('#listAdvanceOffice');
    $('.loading', list).attr('data-show', 'true');
    loadListSeat().done(function(data) {
        list.html(data.view);
        initDataTableAdvance(TAB);
        $('.loading', list).attr('data-show', 'false');
    }).fail(function(error) {
        handleErrors(error);
    });
}

function getListDocument() {
    var list = $('#listAdvanceFIle');
    loadListFile(branchOffice).done(function(data) {
        list.html(data.view);
        initDataTableAdvance(TAB);

    }).fail(function(error) {
        handleErrors(error);
    });
}

function getListTechnical() {
    var list = $('#listAdvanceTechnical');
    $('.loading', list).attr('data-show', 'true');
    loadListTechnical().done(function(data) {
        list.html(data.view);
        initDataTableAdvance(TAB);
        $('.loading', list).attr('data-show', 'false');
    }).fail(function(error) {
        handleErrors(error);
    });
}
$('body').on('click', '[data-click="openFormResults"]', function(e) {

    e.preventDefault();
    var mdlForm = $('#mdlShowResults');
    var loading = $('.loading', mdlForm).first();
    var id = $(this).data('id');
    loading.attr('data-show', 'true');
    mdlForm.modal('show');

    loadFormShowSurvey(id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        loading.attr('data-show', 'false');
    });
});

function loadFormShowSurvey(id) {
    return $.ajax({
        url:  ULR+"poll/result/survey/show",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}
function getListQuote() {
    var list = $('#listAdvanceQuote');
    $('.loading', list).attr('data-show', 'true');
    loadListQuote().done(function(data) {
        list.html(data.view);
        initDataTableAdvance(TAB);
        $('.loading', list).attr('data-show', 'false');
    }).fail(function(error) {
        handleErrors(error);
    });
}

function getListConfigBilling() {
    var list = $('#listConfigBilling');
    $('.loading', list).attr('data-show', 'true');
    loadListConfigBilling().done(function(data) {
        list.html(data.view);
        $('.loading', list).attr('data-show', 'false');
    }).fail(function(error) {
        handleErrors(error);
    });
}


/******************************************************************************/

function loadListCustomer() {
    return $.ajax({
        url: ULR + "crm/show-list",
        method: "GET",
        data: { id: $('[name="id"]').val() },
        dataType: "json"
    });
}

function loadListContact(branchOffice) {
    return $.ajax({
        url: ULR + "crm/{crm}/contact-list",
        method: "GET",
        data: {
            id: $('[name="crm_id"]').val(),
            branchOffice: branchOffice
        },
        dataType: "json"
    });
}

function loadListSeat() {
    return $.ajax({
        url: ULR + "crm/{crm}/office-list",
        method: "GET",
        data: { id: $('[name="crm_id"]').val() },
        dataType: "json"
    });
}

function loadListUser(branchOffice) {
    return $.ajax({
        url: ULR + "crm/{crm}/user-list",
        method: "GET",
        data: {
            id: $('[name="crm_id"]').val(),
            branchOffice: branchOffice
        },
        dataType: "json"
    });
}

function loadListContract() {
    return $.ajax({
        url: ULR + "crm/{crm}/contract-list",
        method: "GET",
        data: { id: $('[name="crm_id"]').val() },
        dataType: "json"
    });
}

function loadListFile(branchOffice) {
    return $.ajax({
        url: ULR + "crm/{crm}/File-list",
        method: "GET",
        data: {
            id: $('[name="crm_id"]').val(),
            branchOffice: branchOffice
        },
        dataType: "json"
    });
}

function loadListTechnical(branchOffice) {
    return $.ajax({
        url: ULR + "crm/{crm}/technical-list",
        method: "GET",
        data: {
            id: $('[name="crm_id"]').val(),
            office: $('[name="OfficesCustomer"]').val(),
        },
        dataType: "json"
    });
}

function loadListQuote() {
    return $.ajax({
        url: ULR + "crm/{crm}/quote-list",
        method: "GET",
        data: { id: $('[name="crm_id"]').val(), office: $('[name="OfficesCustomer"]').val() },
        dataType: "json"
    });
}

function loadListConfigBilling() {
    return $.ajax({
        url: ULR + "crm/{crm}/config-billing-list",
        method: "GET",
        data: { id: $('[name="crm_id"]').val() }
    });
}

function loadListPlan() {
    return $.ajax({
        url: ULR + "crm/{crm}/plan-list",
        method: "GET",
        data: { id: $('[name="crm_id"]').val(), office: $('[name="OfficesCustomer"]').val() },
        dataType: "json"
    });
}

function loadListMechanism() {
    return $.ajax({
        url: ULR + "crm/{crm}/mechanisms",
        method: "GET",
        data: { id: $('[name="crm_id"]').val(), office: $('[name="OfficesCustomer"]').val() },
        dataType: "json"
    });
}



function loadListProcess() {
    return $.ajax({
        url: ULR + "crm/{crm}/process-list",
        method: "GET",
        data: { id: $('[name="OfficesCustomer"]').val() },
        dataType: "json"
    });
}

function loadListTicketShow(id) {
    return $.ajax({
        url: ULR + "crm-ticket-show",
        method: "GET",
        data: { id: id},
        dataType: "json"
    });
}
function loadListTicket() {
    return $.ajax({
        url: ULR + "crm-tickets-list",
        method: "GET",
        data: { id: $('[name="OfficesCustomer"]').val() },
        dataType: "json"
    });
}
function loadListTicketFilter(category,solved,order,page) {
    return $.ajax({
        url: ULR + "crm-tickets-list-filter",
        method: "GET",
        data: { id: $('[name="OfficesCustomer"]').val(), page:page, search:$("[name='search']").val(), category: category,solved:solved, order:order,perpage:$("[name='tblList_length']").val() },
        dataType: "json"
    });
}

/******************************************************************************/
function loadFormCustomer(id) {
    return $.ajax({
        url: ULR + "crm/form-customer",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}
/******************************************************************************/
function loadFormTicket(id,office) {
    return $.ajax({
        url: ULR + "crm-form-ticket",
        method: "GET",
        data: { id: id, office: office},
        dataType: "json"
    });
}

//cargar el formulario
$('body').on('click', '[data-click="newEditTicket"]', function(e) {

    //abrir el modal y mostrar loading
    e.preventDefault();
    var mdlForm = $('#mdlFormShow');
    var loading = $('.loading', mdlForm).first();
    var id = $(this).data('id');
    let officee = $('select[name="OfficesCustomer"] option').filter(':selected').val();
    loading.attr('data-show', 'true');
    //leer el formulario de usuarios
    mdlForm.modal('show');
    loadFormTicket(id,officee).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        $('.modal-title', mdlForm).html(data.title);

        /* setTimeout(function() {
            $('.btnsave').attr('data-click', data.data_click);
        }, 500);

        loading.attr('data-show', 'false');
        // Checkbox & Radio 2
        $('.icheck_minimal input').iCheck({
            checkboxClass: 'icheckbox_minimal',
            radioClass: 'iradio_minimal',
        });
        $('.skin-square input').iCheck({
            checkboxClass: 'icheckbox_square-red',
            radioClass: 'iradio_square-red',
        }); */
       // initSelects();
    }).fail(function(error) {
        handleErrors(error);
    });
});



function loadFormContact(id, branchOffice) {
    return $.ajax({
        url: ULR + "crm/form-contact",
        method: "GET",
        data: {
            id: id,
            office: branchOffice
        },
        dataType: "json"
    });
}

function loadFormDeleteContact(id) {
    return $.ajax({
        url: ULR + "crm/deleteContact-form",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

function loadFormOffice(id) {
    return $.ajax({
        url: ULR + "crm/form-oficce",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

function loadFormUser(id, office) {
    return $.ajax({
        url: ULR + "crm/user-customer-form",
        method: "GET",
        dataType: "json",
        data: { id: id, office: office}
    });
}

function loadFormDeleteOffice(id) {
    return $.ajax({
        url: ULR + "crm/deleteOffice-form",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

function loadFormDeleteUser(id, user_id) {
    return $.ajax({
        url: ULR + "crm/deleteUser-form",
        method: "GET",
        data: { id: id, user_id: user_id },
        dataType: "json"
    });
}

function loadFormFile(id, branchOffice) {
    return $.ajax({
        url: ULR + "crm/form-document",
        method: "GET",
        data: {
            id: id,
            office: branchOffice
        },
        dataType: "json"
    });
}

function loadFormTechnical(id) {
    return $.ajax({
        url: ULR + "crm/form-technical",
        method: "GET",
        data: { id: id, office: $("[name='OfficesCustomer'").val() },
        dataType: "json"
    });
}

function loadFormDeleteFile(id) {
    return $.ajax({
        url: ULR + "crm/deleteFile-form",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

function loadFormConfigBilling(id) {
    return $.ajax({
        url: ULR + "crm/form-config-billing",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

function loadFormEvent(id, office, crm) {
    return $.ajax({
        url: ULR + "crm/form-process",
        method: "GET",
        data: { id: id, office: office, crm: crm },
        dataType: "json"
    });
}




/******************************************************************************/
function saveForm() {

    var form = $('#customer');
    var data = form.serialize();
    return $.ajax({
        url: ULR + "crm/save-customer",
        method: 'PUT',
        data: data,
        dataType: "json"
    });
}

function saveFormContact() {

    var form = $('#customer');
    var data = form.serialize();
    return $.ajax({
        url: ULR + "crm/save-contact",
        method: METHOD,
        data: data,
        dataType: "json"
    });
}

function saveFormOffice() {

    var form = $('#customer');
    var data = form.serialize();
    return $.ajax({
        url: ULR + "crm/save-office",
        method: METHOD,
        data: data,
        dataType: "json"
    });
}

function saveFormUser() {

    var form = $('#customer');
    var data = form.serialize();
    return $.ajax({
        url: ULR + "crm/save-user",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });
}

function saveFormDocument() {

    var form = $('#customerFull')[0];
    var formData = new FormData(form);
    return $.ajax({
        url: ULR + $(form).attr('action'),
        contentType: false,
        processData: false,
        headers: {
            'X-CSRF-Token': $('#customer [name="_token"]').val()
        },
        method: METHOD,
        data: formData,
        success: success,
        error: failfull
    });
}

function success(data) {
    if (data.success) {
        var mdlForm = $('#mdlFormShow');
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'false');

        $("[data-dismiss=modal]").trigger({ type: "click" });

    }
    swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");

}

function fail(error) {
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'false');
    showErrors(error.responseJSON.errors, '#customer');
    handleErrors(error);
}



function failfull(error) {
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'false');
    showErrors(error.responseJSON.errors, '#customerFull');
    handleErrors(error);
}

function saveFormTechnical() {

    var form = $('#customerFull');
    var data = form.serialize();
    return $.ajax({
        url: ULR + $('#customerFull').attr('action'),
        method: METHOD,
        data: data,
        dataType: "json"
    });
}

function successTechnical(data) {
    if (data.success) {
        var mdlForm = $('#mdlFormShowFullScreen');
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        getListTechnical();
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }

}

function fail(error) {
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'false');
    showErrors(error.responseJSON.errors, '#customer');
    handleErrors(error);
}

function saveFormConfigBilling() {

    var form = $('#customer');
    var data = form.serialize();
    return $.ajax({
        url: ULR + "crm/save-config-billing",
        method: 'PUT',
        data: data,
        dataType: "json"
    });
}

function saveFormEvent() {

    var form = $('#customer');
    var data = form.serialize();
    return $.ajax({
        url: ULR + "crm/save-event",
        method: METHOD,
        data: data,
        dataType: "json"
    });
}

function saveSelectCategoryCRM(id, category) {
    return $.ajax({
        url: ULR + "crm/save-customer/" + id,
        method: "GET",
        data: {
            category: category,
            id: id
        },
        dataType: "json"
    });
}
function saveTicket() {
    

    var form = $('#formSaveTicket')[0];
    var formData = new FormData(form);
    return $.ajax({
        url: ULR + "crm/save-ticket",
        contentType: false,
        processData: false,
        headers: {
            'X-CSRF-Token': $('#formSaveTicket [name="_token"]').val()
        },
        method: $('[name="_method"]', form).val(),
        data: formData,
        success: success,
        error: failfull
    });
}
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
        error: failfull
    });
}

// function saveAvatarCustomerCRM(id, name) {
//     return $.ajax({
//         url: ULR + "crm/save-avatar/" + id,
//         method: "GET",
//         data: {
//             name: name,
//             id: id
//         },
//         dataType: "json"
//     });
// }


/******************************************************************************/


function sendDeleteContact(id) {

    var form = $('#frmDeleteContact');
    var data = form.serialize();

    return $.ajax({
        url: ULR + "crm/form-deleteContact",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });

}
function sendStateChange(id) {
    var form = $('#answerTicket');
    var data = form.serialize();
    return $.ajax({
        url: ULR + "ticket-state-change",
        method: 'POST',
        data: data,
        dataType: "json"
    });

}

function sendDeleteOffice(id) {

    var form = $('#frmDeleteOffice');
    var data = form.serialize();

    return $.ajax({
        url: ULR + "crm/form-deleteOffice",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });

}

function sendDeleteUser(id) {

    var form = $('#frmDeleteUser');
    var data = form.serialize();

    return $.ajax({
        url: ULR + "crm/form-deleteUser",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });

}

function sendDeleteFile() {

    var form = $('#frmDeleteFile');
    var data = form.serialize();

    return $.ajax({
        url: ULR + "crm/form-deleteFile",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });

}

/******************************************************************************/

function initDataTableAdvance(tab) {
    $(tab + ' .text-inputs-searching').dataTable().fnDestroy();
    var tableSearching = $(tab + ' .text-inputs-searching').DataTable({
        "bDestroy": true,
        "language": spanish,
        dom: "<'row mb-2'<'col-sm-12'<'float-right' B>>><'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        buttons: [{
                extend: 'copyHtml5',
                text: 'Copiar',
                exportOptions: {
                    columns: ':not(.nofilter)'
                }
            }, {
                extend: 'excelHtml5',
                text: 'Excel',
                exportOptions: {
                    columns: ':not(.nofilter)'
                }
            },
            {
                extend: 'colvis',
                text: 'Seleccionar columnas'
            }
        ],
        columnDefs: [{
            targetinitDataTableAdvances: [0],
            orderable: false
        }],
        colReorder: false,
        responsive: true,
        initComplete: function() {
            this.api().columns().every(function() {
                if (!$(this.header()).hasClass("nofilter")) {
                    var column = this;
                    if ($(this.header()).hasClass("htmlfilter")) {
                        var select = $('<select multiple title="Filtrar"></select>')
                            .appendTo($(column.footer()).empty())
                            .on('change', function() {
                                var val = $(this).val();
                                val = $.map(val, function(x) { return $.fn.dataTable.util.escapeRegex(x); });
                                column.search(val.length > 0 ? val.join("|") : '', true, false).draw();
                            });

                        column.data().unique().sort().each(function(d, j) {
                            if (d != "") {
                                if (d != "No disponible") {
                                    d = $(d)[0].innerHTML;
                                }
                                select.append('<option value="' + d + '">' + d + '</option>')
                            }
                        });
                    } else {
                        var select = $('<select multiple title="Filtrar"></select>')
                            .appendTo($(column.footer()).empty())
                            .on('change', function() {
                                var val = $(this).val();
                                val = $.map(val, function(x) { return $.fn.dataTable.util.escapeRegex(x); });
                                column.search(val.length > 0 ? '^' + val.join("|") + '$' : '', true, false).draw();
                            });

                        column.data().unique().sort().each(function(d, j) {
                            if (d != "") {
                                select.append("<option value='" + d + "'>" + d + "</option>");
                            }
                        });
                    }
                    select.selectpicker({
                        width: ($(this.header()).width() < 300) ? $(this.header()).width() : 150,
                        liveSearch: true,
                        container: "body",
                    });
                }
            });


        }
    });
}

/******************************************************************************/

function initSelectsEdit() {

    setTimeout(function() {
        initSelectTypeCrm();
        initSelectMeanContact();
        initSelectZone();
        initSelectStateOffice();
        initSelectTypeFileCrm();
        initSelectTypeContact();
        initSelectCommercialsEdit();

    }, 500);

    initSelectHeadquarters();
}

function initSelectOfficesCustomerCrm(id) {

    $('#OfficesCustomer').select2({
        width: '100%',
        ajax: {
            url: ULR + "crm/offices-customer-crm?id=" + id,
            dataType: 'json',
            method: "GET"
        },

        cache: true,
        templateResult: dataFormatResultOffice,
        templateSelection: dataFormatSelection,
        dropdownCssClass: "bigdrop",
        escapeMarkup: function(m) { return m; },
        "language": {
            "noResults": () => "No se encontraron sucursales registradas para este cliente.",
            "searching": () => "Consultando..."
        }

    }).on("select2:select", function(e) {

        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        officesCustomer = select_val;

        //$("#sltCity").val('').trigger('change');
        //$('#sltCity').select2('destroy');
        //initSelectCity();
    });
}

function initSelectTypeCrm() {

    $('.typeCrm').select2({
        width: '100%',
        ajax: {
            url: ULR + "crm/type-crm",
            dataType: 'json',
            method: "GET",
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        TYPECRM = select_val;

        //$("#sltCity").val('').trigger('change');
        //$('#sltCity').select2('destroy');
        //initSelectCity();
    });
}

function initSelectMeanContact() {

    $('#mean_contact').select2({
        width: '100%',
        tags: true,
        ajax: {
            url: ULR + "crm/mean-contact",
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

function initSelectCountryOffice() {

    $('#country_office').select2({
        width: '100%',
        ajax: {
            url: ULR + "crm/country-office",
            dataType: 'json',
            method: "GET",
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        COUNTRYOFFICE = select_val;


        initSelectStateOffice();
    });
}

function initSelectStateOffice() {

    $('#state_office').select2({
        width: '100%',
        ajax: {
            url: ULR + "crm/states-office/" + 47,
            dataType: 'json',
            method: "GET",
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        STATEOFFICE = select_val;


        initSelectCityOffice();
    });
}

function initSelectCityOffice() {

    $('#city_office').select2({
        width: '100%',
        ajax: {
            url: ULR + "crm/city-office/" + STATEOFFICE,
            dataType: 'json',
            method: "GET",
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        CITYOFFICE = select_val;
        initHeadquarterOffice();
    });
}

function initHeadquarterOffice() {

    $('#headquarter_id').select2({
        width: '100%',
        ajax: {
            url: ULR + "crm/headquarters",
            dataType: 'json',
            method: "GET",
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        HEADQUARTER_ID = select_val;
        initSelectZone();

    });
}

function initSelectZone() {

    $('#zone_office').select2({
        width: '100%',
        ajax: {
            url: ULR + "crm/zona-office/" + HEADQUARTER_ID,
            dataType: 'json',
            method: "GET",
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        Zona = select_val;

        //$("#sltCity").val('').trigger('change');
        //$('#sltCity').select2('destroy');
        //initSelectCity();
    });
}

function initSelectUser(roles=[]) {

    $('#user_id').select2({
        width: '100%',
        ajax: {
            url: ULR + "crm/users",
            dataType: 'json',
            method: "GET",
            data: function (term, page) {
                return {
                    term: term.term, // search term
                    roles: roles,
                    restrictHeadquarter: 0
                };
            },
            success: selectScroll,
        },
        cache: true,
        templateResult: dataFormatResultUserCustomer,
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
    }).on("select2:open", e => selectScroll());
}

function initSelectCommercialsEdit(key = false) {


    var selector = '[name*="user_id"]';
    if (key) {
        selector = '[name*="[' + key + '][user]"]';
    }
    $(selector).select2({
        placeholder: "Dejar vacío para cualquiera",
        allowClear: true,
        width: "100%",
        ajax: {
            url: ULR + "crm/users",
            dataType: 'json',
            method: "GET",
            success: selectScroll
        },
        cache: true,
        templateResult: dataFormatResultUser,
        templateSelection: dataFormatSelection,
        dropdownCssClass: "bigdrop",
        escapeMarkup: function(m) { return m; },
    }).on("select2:open", e => selectScroll());

}

function initSelectTypeFileCrm() {

    $('.typeFileCrm').select2({
        width: '100%',
        ajax: {
            url: ULR + "crm/type-file",
            dataType: 'json',
            method: "GET",
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        FILE = select_val;

    });
}

function initSelectTypeContact() {

    $('.typeContact').select2({
        width: '100%',
        ajax: {
            url: ULR + "crm/type-contact",
            dataType: 'json',
            method: "GET",
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        CONTACTYPE = select_val;
    });
}

function initSelectTypeOffice(id) {

    var dropdownParentEl = $('.tab-pane.active');
    $('[name="office_id[]"]').select2({
        multiple: true,
        closeOnSelect: false,
        width: '100%',
        ajax: {
            url: ULR + "crm/offices?id=" + id,
            dataType: 'json',
            method: "GET",
        },
        cache: true,
        templateResult: dataFormatResultOffice,
        templateSelection: dataFormatSelection,
        dropdownCssClass: "bigdrop",
        escapeMarkup: function(m) { return m; },
        "language": {
            "noResults": () => "No se encontraron sucursales registradas para este cliente.",
            "searching": () => "Consultando..."
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        TYPESEAT = select_val;
    });

    $('[name*="[office_id]"]').select2({
        width: '100%',
        ajax: {
            url: ULR + "crm/offices?id=" + id,
            dataType: 'json',
            method: "GET",
        },
        cache: true,
        templateResult: dataFormatResultOffice,
        templateSelection: dataFormatSelection,
        dropdownCssClass: "bigdrop",
        escapeMarkup: function(m) { return m; },
        "language": {
            "noResults": () => "No se encontraron sucursales registradas para este cliente.",
            "searching": () => "Consultando..."
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        TYPESEAT = select_val;
    });
}


$('body').on('click', '[data-click="checkAllOffices"]', function(e) {

    if ($(this).is(':checked')) {
        $('[name="office_id[]"]').val(null).trigger("change");
        $('[name="office_id[]"]').html('');
        var id = $('[name="crm_id"]').val();
        loadOffices(id).done(function(data) {
            showLoading();
            data.results.forEach(function(service, indx) {
                var $newOption = $("<option selected></option>").val(service.id).text(service.name);
                $('[name="office_id[]"]').append($newOption);
                if ((indx + 1) == data.results.length) {
                    setTimeout(() => {
                        hideLoadingOnly();
                    }, 600);
                }
            });
        });

    } else {
        $('[name="office_id[]"]').val(null).trigger("change");
        $('[name="office_id[]"]').html('');
        $('[name="office_id[]"]').html('');
    }
});

function loadOffices(id) {
    return $.ajax({
        url: ULR + "crm/offices?id=" + id,
        dataType: 'json',
        method: "GET",
    });
}

/******************************************************************************/

//cargar el formulario
$('body').on('click', '[data-click="openFormCustomer"]', function(e) {

    //abrir el modal y mostrar loading
    e.preventDefault();
    var mdlForm = $('#mdlFormShow');
    var loading = $('.loading', mdlForm).first();
    var id = $('[name="id"]').val();
    loading.attr('data-show', 'true');
    mdlForm.modal('show');


    //leer el formulario de usuarios
    loadFormCustomer(id).done(function(data) {
        $('.modal-content #formContent', mdlForm).html(data.view);
        $('.modal-title', mdlForm).html(data.title);

        setTimeout(function() {
            $('.btnsave').attr('data-click', data.data_click);
        }, 500);

        loading.attr('data-show', 'false');
        // Checkbox & Radio 2
        $('.icheck_minimal input').iCheck({
            checkboxClass: 'icheckbox_minimal',
            radioClass: 'iradio_minimal',
        });
        $('.skin-square input').iCheck({
            checkboxClass: 'icheckbox_square-red',
            radioClass: 'iradio_square-red',
        });

        initSelectsEdit();
        initSelectHeadquarters();

        //initSelects();
    }).fail(function(error) {
        handleErrors(error);
    });
});

//cargar el formulario de contactos
$('body').on('click', '[data-click="openFormContact"]', function(e) {
    //definir metodo
    METHOD = 'POST';
    $('[name="_method"]').val(METHOD);

    //abrir el modal y mostrar loading
    e.preventDefault();
    var mdlForm = $('#mdlFormShow');
    var loading = $('.loading', mdlForm).first();
    var id = $(this).data('id');
    loading.attr('data-show', 'true');
    mdlForm.modal('show');
    var branchOffice = $('select[name="OfficesCustomer"] option').filter(':selected').val();
    //leer el formulario de usuarios
    loadFormContact(id, branchOffice).done(function(data) {
        $('.modal-content #formContent', mdlForm).html(data.view);
        $('.modal-title', mdlForm).html(data.title);
        initSelectTypeContact();
        initSelectTypeOffice($('[name="crm_id"]').val());
        setTimeout(function() {
            $('.btnsave').attr('data-click', data.data_click);
        }, 500);

        loading.attr('data-show', 'false');

    }).fail(function(error) {
        handleErrors(error);
    });
});

$('body').on('ifChanged', '[name="customer_national"]', function(event) {
    if (event.target.checked) {
        $(".div-national-heaquarters").show();
        $(".div-local-headquarter").hide();
    } else {
        $(".div-local-headquarter").show();
        $(".div-national-heaquarters").hide();
    }
});
$('body').on('click', "[data-click='recordElement']", function() {
    var id = $(this).data('id');
    var mdlForm = $('#mdlFormShow');
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'true');
    mdlForm.modal('show');
    loadRecord(id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
    }).fail(function(error) {
        handleErrors(error);
    });
});


$('body').on('click', "[data-click='consolidatedReport']", function() {
    let office_id = $('select[name="OfficesCustomer"] option').filter(':selected').val();
    const ELEMENTOEMBED=$('#embed');
    const DIVBUTTTONCONSOLIDATE=$('#divDownloadConsolidated');
    ELEMENTOEMBED.css('min-height','');
    ELEMENTOEMBED.html(``)
    DIVBUTTTONCONSOLIDATE.html(``)
    loadConsolidated(office_id).done(function(data) {
        ELEMENTOEMBED.css('min-height','600px');
        DIVBUTTTONCONSOLIDATE.html(`<a class="btn btn-success mb-2" href="${data.url}" download><i class="la la-download"></i> Descargar</a>`);
        ELEMENTOEMBED.html(`
        <embed  src="${data.url}?#zoom=90&toolbar=0&navpanes=0&scrollbar=0" type="application/pdf" width="100%" height="600px"/>`)
    }).fail(function(error) {
        handleErrors(error);
    });
});

$('body').on('click', "[data-click='RegenerateconsolidatedReport']", function() {
    let office_id = $('select[name="OfficesCustomer"] option').filter(':selected').val();
    const ELEMENTOEMBED=$('#embed');
    const DIVBUTTTONCONSOLIDATE=$('#divDownloadConsolidated');
    ELEMENTOEMBED.css('min-height','');
    ELEMENTOEMBED.html(``)
    DIVBUTTTONCONSOLIDATE.html(``)
    loadConsolidated(office_id,1).done(function(data) {
        ELEMENTOEMBED.css('min-height','600px');
        DIVBUTTTONCONSOLIDATE.html(`<a class="btn btn-success mb-2" href="${data.url}" download><i class="la la-download"></i> Descargar</a>`);
        ELEMENTOEMBED.html(`
        <embed  src="${data.url}?#zoom=90&toolbar=0&navpanes=0&scrollbar=0" type="application/pdf" width="100%" height="600px"/>`)
    }).fail(function(error) {
        handleErrors(error);
    });
});

function loadConsolidated(office,regenerate='') {
    let form = $('#consolidated-form');
    let data = form.serialize();
    return $.ajax({
        url: ULR + "crm/consolidated?office="+office+"&regenerate="+regenerate,
        method: "GET",
        data:data,
        dataType: "json",
    });
}

function loadRecord(id) {
    return $.ajax({
        url: ULR + "crm/record/"+id,
        method: "GET",
        dataType: "json",
    });
}
$('body').on('click', "[data-click='associateQr']", function() {
    var id = $(this).data('id');
    var loading = $('.loading', mdlDelete).first();
    loading.attr('data-show', 'true');
    mdlDelete.modal('show');
    loadQr(id).done(function(data) {
        $('.modal-content', mdlDelete).html(data.view);
        initSelectQr();
        $('input[name="qr_office"]').val(Office)
    }).fail(function(error) {
        handleErrors(error);
    });
});

function refresh() {
    $('[name="qr"]').empty();
  };

function initSelectQr() {

    $('[name="qr"]').select2({
        width: '100%',
        ajax: {
            url: ULR + "crm/selectQr",
            dataType: 'json',
            data: function (params) {
                var query = {
                  term: params.term,
                  type: 'public',
                  tecnico: $('#defaultCheck3').is(':checked') ? 1 : 0,
                }
                return query;
              },
            method: "GET",
            success: selectScroll
        },
        "language": {
            "noResults": () => "No se encontraron QR disponibles",
            "searching": () => "Consultando..."
        }
    });
}

function loadQr(id) {
    return $.ajax({
        url: ULR + "crm/associateQr/"+id,
        method: "GET",
        dataType: "json",
    });
}
$('body').on('click', '[data-click="saveAssociateQr"]', function(e) {
    e.preventDefault();
    associateQrUpdate().done(function(data) {
        mdlDelete.modal('hide');
        $(".nav-link.active").first().trigger('click');
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }).fail(function(error) {
        handleErrors(error);
        showErrors(error.responseJSON.errors, '#mdlDelete')
    });

});
function associateQrUpdate() {

    var form = $('#frm-associateQr');
    var data = form.serialize();
    return $.ajax({
        url: ULR + "crm/associateQrUpdate/update",
        method: 'PUT',
        data: data,
        dataType: "json"
    });
}
$('body').on('click', "[data-click='removeElement']", function() {
    var id = $(this).data('id');
    Swal.fire({
        title: 'Desinstalar este elemento',
        text: "¿Estas seguro que deseas desintalar este elemento? Esta acción será irreversible.",
        input: 'checkbox',
        inputPlaceholder: 'Realizar documentos de traslados en inventario',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#009688',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Si, Desintalar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {

        if (result.value) {
            removeElement(id).done(function(data) {
                loadReCountAutomaticShow(data.id).done(function() {
                    $(".nav-link.active").first().trigger('click');
                });
                swal.fire({
                    'title': data.success ? "Correcto" : "Error",
                    'text': data.message,
                    'showConfirmButton': false,
                    'timer': 1500,
                    'type': data.success ? "success" : "error",
                })
            }).fail(function(error) {
                handleErrors(error);
            });
        }

    });
});
$('body').on('click', "[data-click='deleteDiagnostic']", function() {
    var id = $(this).data('id');
    Swal.fire({
        title: 'Eliminar el diagnostico',
        text: "¿Estas seguro que deseas eliminar el diagnostico "+id+"? Esta acción será irreversible.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#009688',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Si, Eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {

        if (result.value) {
            removeDiagnostic(id).done(function(data) {
                $(`#tr${data.id}`).remove();
                console.log(data)
                swal.fire({
                    'title': data.success ? "Correcto" : "Error",
                    'text': data.message,
                    'showConfirmButton': false,
                    'timer': 1500,
                    'type': data.success ? "success" : "error",
                })
            }).fail(function(error) {
                handleErrors(error);
            });
        }

    });
});
function removeDiagnostic(id) {
    return $.ajax({
        method: 'get',
        url: ULR + 'delete/diagnostic/' + id,
        // data: { id: id }
    });
}
$('body').on('click', '[data-click="ApprovedUpdateStatus"]', function(e) {

    var mdlForm = $('#mdlDelete');
    approvedUpdateStatus().done(function(data) {


        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
        if (data.success) {
            $(".nav-link.active").first().trigger('click');
            mdlForm.modal('hide');
        }
    }).fail(function(error) {
        showErrors(error.responseJSON.errors, '#supplies');
        handleErrors(error);
    })
});

function approvedUpdateStatus() {

    var form = $('#crmStatus');
    var data = form.serialize();
    return $.ajax({
        url: ULR + 'quotes/status',
        method: 'PUT',
        data: data,
        dataType: "json"
    });
}
//Funciones para el cambio de estado de cotización
$('body').on('click', '[data-click="approvedStatusForm"]', function(e) {
    e.preventDefault();
    var mdlForm = $('#mdlDelete');
    var quote = $(this).data('quote');

    loadStatusForm(quote).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        mdlForm.modal('show');
    }).fail(function(error) {
        handleErrors(error);
    });
});

function loadStatusForm(quote) {
    return $.ajax({
        url: ULR + "quotes/status-form",
        method: "GET",
        data: {
            quote: quote,
            crmStatus: true
        },
        dataType: "json"
    });
}
function loadReCountAutomaticShow(id) {
    return $.ajax({
        url: ULR + "crm/renumberAutomatic/"+id,
        method: "GET",
        dataType: "json",
    });
}
function removeElement(id) {
    return $.ajax({
        method: 'get',
        url: ULR + 'crm/element/' + id + '/delete',
        data: { id: id }
    });
}
function initSelectHeadquarters() {

    $('[name="headquarters[]"]').select2({
        width: '100%',
        ajax: {
            url: ULR + "crm/headquarters",
            dataType: 'json',
            method: "GET",
            success: selectScroll
        },
        "language": {
            "noResults": () => "No se encontraron sedes disponibles",
            "searching": () => "Consultando..."
        }
    });
}

function initSelectCitiesCustomer() {
    $('[name*="[city_name]"], [name="city_name"]').select2({
        width: '100%',
        ajax: {
            url: ULR + "crm/cities",
            dataType: 'json',
            method: "GET",
            success: selectScroll
        },
        "language": {
            "noResults": () => "No se encontraron cuidades disponibles",
            "searching": () => "Consultando..."
        },
        cache: true,
        templateResult: dataFormatResultCities,
        templateSelection: dataFormatSelection,
        dropdownCssClass: "bigdrop",
        escapeMarkup: function(m) { return m; },
    });
}

//cargar el formulario de oficinas
$('body').on('click', '[data-click="openFormOffice"]', function(e) {
    //definir metodo
    METHOD = 'POST';
    $('[name="_method"]').val(METHOD);

    //abrir el modal y mostrar loading
    e.preventDefault();
    var mdlForm = $('#mdlFormShow');

    var loading = $('.loading', mdlForm).first();
    var id = $(this).data('id');
    loading.attr('data-show', 'true');
    mdlForm.modal('show');

    //leer el formulario de usuarios
    loadFormOffice(id).done(function(data) {
        $('.modal-content #formContent', mdlForm).html(data.view);
        $('.modal-title', mdlForm).html(data.title);
        setTimeout(function() {
            $('.btnsave').attr('data-click', data.data_click);
        }, 500);
        loading.attr('data-show', 'false');
        //inicializar funciones de plugins
        initSelectsEdit();
        initAddress();
        initSelectCitiesCustomer();
    }).fail(function(error) {
        handleErrors(error);
    });
});

//cargar el formulario de usuarios
$('body').on('click', '[data-click="openFormUser"]', function(e) {
    //definir metodo
    METHOD = 'POST';
    $('[name="_method"]').val(METHOD);

    //abrir el modal y mostrar loading
    e.preventDefault();
    var mdlForm = $('#mdlFormShow');

    var loading = $('.loading', mdlForm).first();
    var id = $(this).data('id');
    loading.attr('data-show', 'true');
    mdlForm.modal('show');
    var branchOffice = $('select[name="OfficesCustomer"] option').filter(':selected').val();
    //leer el formulario de usuarios
    loadFormUser(id, branchOffice).done(function(data) {
        $('.modal-content #formContent', mdlForm).html(data.view);
        $('.modal-title', mdlForm).html(data.title);

        initSelectUser(['customer']);
        initSelectTypeContact();
        initSelectTypeOffice(id);
        setTimeout(function() {
            $('.btnsave').attr('data-click', data.data_click);
            $('#office_id_crmUser').val(branchOffice);
        }, 500);
        loading.attr('data-show', 'false');
    }).fail(function(error) {
        handleErrors(error);
    });
});

//cargar el formulario de archivos
$('body').on('click', '[data-click="openFormFile"]', function(e) {
    //definir metodo
    METHOD = 'POST';
    $('[name="_method"]').val(METHOD);

    //abrir el modal y mostrar loading
    e.preventDefault();
    var mdlForm = $('#mdlFormShowFullScreen');
    var loading = $('.loading', mdlForm).first();
    var id = $(this).data('id');
    loading.attr('data-show', 'true');
    mdlForm.modal('show');
    var branchOffice = $('select[name="OfficesCustomer"] option').filter(':selected').val();
    //leer el formulario de usuarios
    loadFormFile(id, branchOffice).done(function(data) {
        $('.modal-content #formContent', mdlForm).html(data.view);
        $('.modal-title', mdlForm).html(data.title);


        setTimeout(function() {
            $('.btnsave').attr('data-click', data.data_click);
            $('form#customerFull').attr('action', 'crm/save-document');
            $('form#customerFull').attr('enctype', 'multipart/form-data');

        }, 500);
        loading.attr('data-show', 'false');
        initSelectTypeFileCrm();
    }).fail(function(error) {
        handleErrors(error);
    });
});

//cargar el formulario de parámetros
$('body').on('click', '[data-click="openFormTechnical"]', function(e) {
    //definir metodo
    METHOD = 'POST';
    $('[name="_method"]').val(METHOD);

    //abrir el modal y mostrar loading
    e.preventDefault();
    var mdlForm = $('#mdlFormShowFullScreen');
    var loading = $('.loading', mdlForm).first();
    var id = $(this).data('id');
    loading.attr('data-show', 'true');
    mdlForm.modal('show');

    //leer el formulario de parámetros de técnicos
    loadFormTechnical(id).done(function(data) {
        $('.modal-content #formContent', mdlForm).html(data.view);
        $('.modal-title', mdlForm).html(data.title);
        initSelectServicesTechnical();
        initSelectTechnicals();
        initSelectScheduleStatuses();
        setTimeout(function() {
            $('.btnsave').attr('data-click', data.data_click);
            $('form#customerFull').attr('action', 'crm/save-technical');
            $('form#customerFull').attr('enctype', 'multipart/form-data');

        }, 500);
        loading.attr('data-show', 'false');
    }).fail(function(error) {
        handleErrors(error);
    });
});

function initSelectServicesTechnical(key = false) {
    var selector = '[name*="[service]"]';
    if (key) {
        selector = '[name*="[' + key + '][service]"]';
    }
    $(selector).select2({
        placeholder: "Dejar vacío para aplicar todos los servicios.",
        allowClear: true,
        width: '100%',
        ajax: {
            url: ULR + "quotes/services",
            dataType: 'json',
            method: "GET",
            success: selectScroll,
            error: function(error) {
                var mdlForm = $('#mdlForm');
                var loading = $('.loading', mdlForm).first();
                loading.attr('data-show', 'false');
                handleErrors(error);
            }
        },
        cache: true,
        templateResult: dataFormatResultService,
        templateSelection: dataFormatSelection,
        dropdownCssClass: "bigdrop",
        escapeMarkup: function(m) { return m; },
        "language": {
            "noResults": () => "No se encontraron servicios registrados en el sistema",
            "searching": () => "Consultando..."
        }
    }).on("select2:open", e => selectScroll());
}

function initSelectTechnicals(key = false) {


    var selector = '[name*="[user][]"], [name*="user[]"]';
    if (key) {
        selector = '[name*="[' + key + '][user][]"]';
    }
    $(selector).select2({
        placeholder: "Dejar vacío para cualquiera",
        allowClear: true,
        multiple: true,
        width: "100%",
        dropdownParent: $('.modal-content', $('#mdlForm')),
        ajax: {
            url: ULR + "crm/technicals",
            dataType: 'json',
            method: "GET",
            success: selectScroll
        },
        cache: true,
        templateResult: dataFormatResultUser,
        templateSelection: dataFormatSelection,
        dropdownCssClass: "bigdrop",
        escapeMarkup: function(m) { return m; },
    }).on("select2:open", e => selectScroll());

}

function initSelectScheduleStatuses(key = false) {
    var selector = '[name*="[init_status]"], [name="init_status"]';
    if (key) {
        selector = '[name*="[' + key + '][init_status]"], [name="init_status"]';
    }
    $(selector).select2({
        placeholder: "Seleccione una opcion",
        allowClear: true,
        width: "100%",
        dropdownParent: $('.modal-content', $('#mdlForm')),
        ajax: {
            url: ULR + "crm/schedule-statuses",
            dataType: 'json',
            method: "GET",
            success: selectScroll
        },
        cache: true,
    }).on("select2:open", e => selectScroll());
}

//cargar el formulario
$('body').on('click', '[data-click="openFormConfigBilling"]', function(e) {

    //abrir el modal y mostrar loading
    e.preventDefault();
    var mdlForm = $('#mdlFormShow');
    var loading = $('.loading', mdlForm).first();
    var id = $('[name="id"]').val();
    loading.attr('data-show', 'true');
    mdlForm.modal('show');


    //leer el formulario de usuarios
    loadFormConfigBilling(id).done(function(data) {
        $('.modal-content #formContent', mdlForm).html(data.view);
        $('.modal-title', mdlForm).html(data.title);

        setTimeout(function() {
            $('.btnsave').attr('data-click', data.data_click);
        }, 500);
        $('.icheck_minimal input').iCheck({
            checkboxClass: 'icheckbox_minimal',
            radioClass: 'iradio_minimal',
        });
        $('.skin-square input').iCheck({
            checkboxClass: 'icheckbox_square-red',
            radioClass: 'iradio_square-red',
        });
        loading.attr('data-show', 'false');
    }).fail(function(error) {
        handleErrors(error);
    });
});

$('body').on('click', '[data-click="openFormEvent"]', function(e) {
    //definir metodo
    METHOD = 'POST';
    $('[name="_method"]').val(METHOD);

    //abrir el modal y mostrar loading
    e.preventDefault();
    var mdlForm = $('#mdlFormShow');
    var loading = $('.loading', mdlForm).first();
    var id = $(this).data('id');
    var office = $("#OfficesCustomer").val();
    var crm = $('[name="crm_id"]').val();
    loading.attr('data-show', 'true');
    mdlForm.modal('show');

    //leer el formulario de usuarios
    loadFormEvent(id, office, crm).done(function(data) {
        $('.modal-content #formContent', mdlForm).html(data.view);
        $('.modal-title', mdlForm).html(data.title);
        setTimeout(function() {
            $('.btnsave').attr('data-click', data.data_click);
        }, 500);
        loading.attr('data-show', 'false');
        initSelectCrmtrackingType();
    });
});

function initSelectCrmtrackingType() {

    $('.crmTrackingType').select2({
        width: '100%',
        ajax: {
            url: ULR + "crm/crm-tracking-type",
            dataType: 'json',
            method: "GET",
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        TYPECRM = select_val;
    });
}


/******************************************************************************/

//cargar el formulario de editar los contactos
$('body').on('click', '[data-click="openEditContact"]', function(e) {
    //definir metodo
    METHOD = 'PUT';
    $('[name="_method"]').val(METHOD);

    //abrir el modal y mostrar loading
    e.preventDefault();
    var mdlForm = $('#mdlFormShow');
    var loading = $('.loading', mdlForm).first();
    var id = $(this).data('id');
    loading.attr('data-show', 'true');
    mdlForm.modal('show');

    //leer el formulario de usuarios
    loadFormContact(id).done(function(data) {
        $('.modal-content #formContent', mdlForm).html(data.view);
        $('.modal-title', mdlForm).html(data.title);
        initSelectTypeContact();
        initSelectTypeOffice($('[name="crm_id"]').val());
        setTimeout(function() {
            $('.btnsave').attr('data-click', data.data_click);
        }, 500);
        loading.attr('data-show', 'false');
    }).fail(function(error) {
        handleErrors(error);
    });
});

//cargar el formulario de editar los sucursales
$('body').on('click', '[data-click="openEditOffice"]', function(e) {
    //definir metodo
    METHOD = 'PUT';
    $('[name="_method"]').val(METHOD);

    //abrir el modal y mostrar loading
    e.preventDefault();
    var mdlForm = $('#mdlFormShow');
    var loading = $('.loading', mdlForm).first();
    var id = $(this).data('id');
    loading.attr('data-show', 'true');
    mdlForm.modal('show');

    //leer el formulario de usuarios
    loadFormOffice(id).done(function(data) {
        $('.modal-content #formContent', mdlForm).html(data.view);
        $('.modal-title', mdlForm).html(data.title);
        setTimeout(function() {
            $('.btnsave').attr('data-click', data.data_click);
        }, 500);
        loading.attr('data-show', 'false');
        //inicializar funciones de plugins
        initSelectsEdit();
        initAddress();
        initSelectCitiesCustomer();
    }).fail(function(error) {
        handleErrors(error);
    });


});



//cargar el formulario de editar los archivos
$('body').on('click', '[data-click="openEditFile"]', function(e) {
    //definir metodo
    METHOD = 'POST';
    $('[name="_method"]').val(METHOD);

    //abrir el modal y mostrar loading
    e.preventDefault();
    var mdlForm = $('#mdlFormShowFullScreen');
    var loading = $('.loading', mdlForm).first();
    var id = $(this).data('id');
    loading.attr('data-show', 'true');
    mdlForm.modal('show');
    var branchOffice = $('select[name="OfficesCustomer"] option').filter(':selected').val();
    //leer el formulario de usuarios
    loadFormFile(id, branchOffice).done(function(data) {
        $('.modal-content #formContent', mdlForm).html(data.view);
        $('.modal-title', mdlForm).html(data.title);
        setTimeout(function() {
            $('.btnsave').attr('data-click', data.data_click);
            $('form#customerFull').attr('action', 'crm/update-document');
            $('form#customerFull').attr('enctype', 'multipart/form-data');
        }, 500);
        loading.attr('data-show', 'false');
    }).fail(function(error) {
        handleErrors(error);
    });

    //inicializar funciones de plugins
    initSelectsEdit();
});

//cargar el formulario de editar los servicios X tecnico
$('body').on('click', '[data-click="openEditTechnical"]', function(e) {
    //definir metodo
    METHOD = 'POST';
    $('[name="_method"]').val(METHOD);

    //abrir el modal y mostrar loading
    e.preventDefault();
    var mdlForm = $('#mdlFormShowFullScreen');
    var loading = $('.loading', mdlForm).first();
    var id = $(this).data('id');
    loading.attr('data-show', 'true');
    mdlForm.modal('show');

    //leer el formulario de usuarios
    loadFormTechnical(id).done(function(data) {
        $('.modal-content #formContent', mdlForm).html(data.view);
        $('.modal-title', mdlForm).html(data.title);
        initSelectServicesTechnical();
        initSelectTechnicals();
        initSelectScheduleStatuses();
        setTimeout(function() {
            $('.btnsave').attr('data-click', data.data_click);
            $('form#customerFull').attr('action', 'crm/update-technical');
            $('form#customerFull').attr('enctype', 'multipart/form-data');
        }, 500);
        loading.attr('data-show', 'false');
    }).fail(function(error) {
        handleErrors(error);
    });

    //inicializar funciones de plugins
    initSelectsEdit();
});

//cargar el formulario de editar los eventos
$('body').on('click', '[data-click="openEditEvent"]', function(e) {
    //definir metodo
    METHOD = 'PUT';
    $('[name="_method"]').val(METHOD);

    //abrir el modal y mostrar loading
    e.preventDefault();
    var mdlForm = $('#mdlFormShow');
    var loading = $('.loading', mdlForm).first();
    var id = $(this).data('id');
    var office = $("#OfficesCustomer").val();
    var crm = $('[name="crm_id"]').val();
    loading.attr('data-show', 'true');
    mdlForm.modal('show');

    //leer el formulario de usuarios
    loadFormEvent(id, office, crm).done(function(data) {
        $('.modal-content #formContent', mdlForm).html(data.view);
        $('.modal-title', mdlForm).html(data.title);
        initSelectCrmtrackingType();
        setTimeout(function() {
            $('.btnsave').attr('data-click', data.data_click);
        }, 500);
        loading.attr('data-show', 'false');

    }).fail(function(error) {
        handleErrors(error);
    });
});

/******************************************************************************/



// //guardar el avatar del cliente
// $('body').on('change', '[data-avatar="saveAvatarCustomer"]', function(event) {

//     let name = event.target.files[0];
//     let id = $(this).data('id');

//     saveAvatarCustomerCRM(id, name).done(function(data) {
//         swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
//     }).fail(function(error) {
//         var loading = $('.loading', mdlForm).first();
//         loading.attr('data-show', 'false');
//         handleErrors(error);
//     });
// });

//registrar el cliente en la tabla usario para que tenga acceso al sistema
$('body').on('change', '[data-change="saveCategoryCustomer"]', function(e) {
    e.preventDefault();
    let category = $(this).val();
    let id = $(this).data('id');

    saveSelectCategoryCRM(id, category).done(function(data) {
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }).fail(function(error) {
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'false');
        showErrors(error.responseJSON.errors, '#customer');
        handleErrors(error);
    });
});
//registrar el cliente en la tabla usario para que tenga acceso al sistema
$('body').on('click', '[data-click="save_ticket"]', function(e) {
    e.preventDefault();
    var mdlForm = $('#mdlFormShow');

    let id = $(this).data('id');
    saveTicket(id).done(function(data) {
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
        mdlForm.modal('hide');
        $('[data-click="openlistTicket"]').trigger('click');
    }).fail(function(error) {
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'false');
        showErrors(error.responseJSON.errors, '#formSaveTicket');
        handleErrors(error);
    });
});
//registrar el cliente en la tabla usario para que tenga acceso al sistema
$('body').on('click', '[data-click="answer_ticket"]', function(e) {
    e.preventDefault();
    var mdlForm = $('#mdlFormShow');

    saveAnswerTicket().done(function(data) {
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
        mdlForm.modal('hide');
        $('[data-click="openlistTicket"]').trigger('click');
    }).fail(function(error) {
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'false');
        showErrors(error.responseJSON.errors, '#answerTicket');
        handleErrors(error);
    });
});

//guardar el formulario de cliente
$('body').on('click', '[data-click="save_customer"]', function(e) {

    var mdlForm = $('#mdlFormShow');
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'true');

    saveForm().done(function(data) {
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        getList();
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
        $('.customerNameT').text($("[name='name']").val());
        $('.customerCommercialNameT').text($("[name='commercial_name']").val());
    }).fail(function(error) {
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'false');
        showErrors(error.responseJSON.errors, '#customer');
        handleErrors(error);
    });
});

//guardar el formulario de contactos 
$('body').on('click', '[data-click="save_contact"]', function(e) {

    var mdlForm = $('#mdlFormShow');
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'true');

    saveFormContact().done(function(data) {
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        getListContact();
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }).fail(function(error) {
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'false');
        showErrors(error.responseJSON.errors, '#customer');
        handleErrors(error);
    });
});

//dar permisos de acceso a un contacto
$('body').on('click', '[data-click="giveAccess"]', function(e) {
    var id = $(this).data('id');
    Swal.fire({
        title: 'Dar Acceso al sistema a este contacto',
        html: "ten presente que se creará un usuario con rol <b>Cliente</b> y se enviarán las credenciales de acceso al correo registrado para este contacto.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#fda401',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Si, Continuar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.value) {
            giveAccess(id).done(function(data) {
                swal.close();
                var mdlForm = $('#mdlFormShow');
                mdlForm.modal('hide');
                swal(data.success ? 'Correcto' : "Error", data.message, data.success ? "success" : "error");
            }).fail(function(error) {
                handleErrors(error);
            });
        }
    });
});

function giveAccess(id) {
    return $.ajax({
        url: ULR + 'crm/' + $("[name='crm_id']").val() + '/contact/give-access',
        method: 'GET',
        data: { id: id },
        dataType: 'json'
    });
}

//revocar acceso al sistema
$('body').on('click', '[data-click="revokeAccess"]', function(e) {
    var id = $(this).data('id');
    Swal.fire({
        title: 'Revocar Acceso al sistema a este contacto',
        html: "ten presente que se eliminará el usuario en cuestión y toda la trazabiliad que esete haya generado durante su uso.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ff0d45',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Si, Continuar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {

        if (result.value) {
            revokeAccess(id).done(function(data) {
                swal.close();
                var mdlForm = $('#mdlFormShow');
                mdlForm.modal('hide');
                swal(data.success ? 'Correcto' : "Error", data.message, data.success ? "success" : "error");
            }).fail(function(error) {
                handleErrors(error);
            });
        }
    });
});

function giveAccess(id) {
    return $.ajax({
        url: ULR + 'crm/' + $("[name='crm_id']").val() + '/contact/give-access',
        method: 'GET',
        data: { id: id },
        dataType: 'json'
    });
}

function revokeAccess(id) {
    return $.ajax({
        url: ULR + 'crm/' + $("[name='crm_id']").val() + '/contact/revoke-access',
        method: 'GET',
        data: { id: id },
        dataType: 'json'
    });
}

//guardar el formulario de sucursales 
$('body').on('click', '[data-click="save_office"]', function(e) {

    var mdlForm = $('#mdlFormShow');
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'true');

    saveFormOffice().done(function(data) {
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        getListOffice();
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }).fail(function(error) {
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'false');
        showErrors(error.responseJSON.errors, '#customer');
        handleErrors(error);
    });
});

//guardar el formulario de usuarios
$('body').on('click', '[data-click="save_user"]', function(e) {

    var mdlForm = $('#mdlDelete');
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'true');

    saveFormUser().done(function(data) {
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        loading.attr('data-show', 'true');
        var listUser = $('#listAdvanceUser');
        $('.loading', listUser).attr('data-show', 'true');
        var branchOffice = $('select[name="OfficesCustomer"] option').filter(':selected').val();
        loadListUser(branchOffice).done(function(data) {
            listUser.html(data.view);
            $('.loading').attr('data-show', 'false');
            TAB = '#tabUser';
            initDataTableAdvance(TAB);

        }).fail(function(error) {
            handleErrors(error);
        });
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }).fail(function(error) {
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'false');
        showErrors(error.responseJSON.errors, '#crmUser');
        handleErrors(error);
    });
});

//guardar un cliente externo vinculado
$('body').on('click', '[data-click="save_customer_user"]', function(e) {

    var mdlForm = $('#mdlFormShow');


    saveFormUser().done(function(data) {

        mdlForm.modal('hide');
        var branchOffice = $('select[name="OfficesCustomer"] option').filter(':selected').val();
        getListContact();
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }).fail(function(error) {
        showErrors(error.responseJSON.errors, '#crmUser');
        handleErrors(error);
    });
});

//guardar el formulario de documentos
$('body').on('click', '[data-click="save_document"]', function(e) {

    var mdlForm = $('#mdlForm');
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'true');

    saveFormDocument();
});

//guardar el formulario de documentos
$('body').on('click', '[data-click="save_technical"]', function(e) {

    var mdlForm = $('#mdlFormShowFullScreen');
    saveFormTechnical().done((data) => {
        if (data.success) {
            mdlForm.modal('hide');
            getListTechnical();
        }

        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }).fail(function(error) {
        handleErrors(error);
    });
});

//guardar el formulario de eventos en la gestion del crm
$('body').on('click', '[data-click="save_config_billing"]', function(e) {

    var mdlForm = $('#mdlFormShow');
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'true');
    saveFormConfigBilling().done(function(data) {
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        $('[data-click="openlistConfigBilling"]').trigger('click');
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }).fail(function(error) {
        loading.attr('data-show', 'false');
        showErrors(error.responseJSON.errors, '#customer');
        handleErrors(error);
    });
});

//guardar el formulario de eventos en la gestion del crm
$('body').on('click', '[data-click="save_event"]', function(e) {

    var mdlForm = $('#mdlFormShow');
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'true');
    saveFormEvent().done(function(data) {
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        $('[data-click="openlistProcess"]').trigger('click');
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }).fail(function(error) {
        loading.attr('data-show', 'false');
        showErrors(error.responseJSON.errors, '#customer');
        handleErrors(error);
    });
});

/******************************************************************************/

//cargar la lista de contactos
$('body').on('click', '[data-click="openlistContact"]', function(e) {
    TAB = '#tabContacts';
    localStorage.setItem('observable', 'false');
    var listContact = $('#listAdvance');
    $('.loading', listContact).attr('data-show', 'true');
    var branchOffice = $('select[name="OfficesCustomer"] option').filter(':selected').val();
    loadListContact(branchOffice).done(function(data) {
        listContact.html(data.view);
        $('.loading', list).attr('data-show', 'false');
        initDataTableAdvance(TAB);
    }).fail(function(error) {
        handleErrors(error);
    });
});

//cargar la lista de sedes
$('body').on('click', '[data-click="openlistSeat"]', function(e) {

    var listSeat = $('#listAdvanceOffice');
    localStorage.setItem('observable', 'false');
    $('.loading', listSeat).attr('data-show', 'true');
    loadListSeat().done(function(data) {
        listSeat.html(data.view);
        $('.loading', list).attr('data-show', 'false');
        TAB = '#tabSeat';
        initDataTableAdvance(TAB);

    }).fail(function(error) {
        handleErrors(error);
    });
});

//cargar la lista de usuarios asignados
$('body').on('click', '[data-click="openlistUser"]', function(e) {

    var listUser = $('#listAdvanceUser');
    localStorage.setItem('observable', 'false');
    var branchOffice = $('select[name="OfficesCustomer"] option').filter(':selected').val();
    loadListUser(branchOffice).done(function(data) {
        listUser.html(data.view);
        TAB = '#tabUser';
        initDataTableAdvance(TAB);

    }).fail(function(error) {
        handleErrors(error);
    });
});

//cargar la lista de archivos
$('body').on('click', '[data-click="openlistFile"]', function(e) {

    var listFile = $('#listAdvanceFIle');
    localStorage.setItem('observable', 'false');
    $('.loading', listFile).attr('data-show', 'true');
    var branchOffice = $('select[name="OfficesCustomer"] option').filter(':selected').val();
    loadListFile(branchOffice).done(function(data) {
        listFile.html(data.view);
        $('.loading', list).attr('data-show', 'false');
        TAB = '#tabFile';
        initDataTableAdvance('#tabFile');

    }).fail(function(error) {
        handleErrors(error);
    });
});

//cargar la lista de técnicos X servicio
$('body').on('click', '[data-click="openlistTechnical"]', function(e) {

    var listTechnical = $('#listAdvanceTechnical');
    localStorage.setItem('observable', 'false');
    $('.loading', listTechnical).attr('data-show', 'true');
    var branchOffice = $('select[name="OfficesCustomer"] option').filter(':selected').val();
    loadListTechnical(branchOffice).done(function(data) {
        listTechnical.html(data.view);
        $('.loading', list).attr('data-show', 'false');
        TAB = '#tabTechnical';
        initDataTableAdvance('#tabTechnical');

    }).fail(function(error) {
        handleErrors(error);
    });
});

//cargar la lista de contratos realizados
$('body').on('click', '[data-click="openlistContract"]', function(e) {

    var listContract = $('#listAdvanceContract');
    localStorage.setItem('observable', 'false');
    loadListContract().done(function(data) {
        listContract.html(data.view);
        TAB = '#tabContract';
        initDataTableAdvance(TAB);

    }).fail(function(error) {
        handleErrors(error);
    });
});

//cargar la lista de cotizaciones
$('body').on('click', '[data-click="openlistQuote"]', function(e) {
    var listQuoete = $('#listAdvanceQuote');
    localStorage.setItem('observable', 'false');
    $('.loading', listQuoete).attr('data-show', 'true');
    loadListQuote().done(function(data) {
        listQuoete.html(data.view);
        $('.loading', list).attr('data-show', 'false');
        TAB = '#tabQuote';
        initDataTableAdvance(TAB);

    }).fail(function(error) {
        handleErrors(error);
    });
});

//cargar la lista de configuracion de facturacion
$('body').on('click', '[data-click="openlistConfigBilling"]', function(e) {
    var listConfigBilling = $('#listConfigBilling');
    $('.loading', listConfigBilling).attr('data-show', 'true');
    loadListConfigBilling().done(function(data) {
        listConfigBilling.html(data.view);
        $('.loading', list).attr('data-show', 'false');
        TAB = '#tabConfigBilling';
    }).fail(function(error) {
        handleErrors(error);
    });
});

//cargar la lista de planos
$('body').on('click', '[data-click="openlistPlan"]', function(e) {
    var listPlan = $('#listAdvancePlan');
    localStorage.setItem('observable', 'true');
    loadListPlan().done(function(data) {
        listPlan.html(data.view);
        TAB = '#tabPlan';
        $('[href="' + localStorage.getItem('currentAreaTab') + '"]').trigger('click');
    }).fail(function(error) {
        handleErrors(error);
    });
});

//cargar la lista de mecanismos
$('body').on('click', '[data-click="openlistMechanism"]', function(e) {
    var listPlan = $('#listAdvanceMechanism');
    localStorage.setItem('observable', 'false');
    loadListMechanism().done(function(data) {
        listPlan.html(data.view);
        TAB = '#tabMechanism';
        $('[href="' + localStorage.getItem('currentMechanismTab') + '"]').trigger('click');
    }).fail(function(error) {
        handleErrors(error);
    });
});

//cargar la lista de la gestion
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
function sendData(page=null){

    let listTickets = $('#listTicket');

    let category="";
    let solved="";
    let orderType=myObj.type;
        if(typeof orderType !== 'undefined'){
            if(orderType == 'category'){
                category=myObj.value;
            }
            if(orderType == 'solved'){
                solved=myObj.value;
            }
        }
    loadListTicketFilter(category,solved,"",page).done(function(data) {
        listTickets.html(data.view);
        FocusOnInput();

    }).fail(function(error) {
        handleErrors(error);
    });
}
$('body').on('click', '.page-link', function(e) {
    e.preventDefault();
    var page = $(this).attr('href').split('page=')[1];

    sendData(page);;
});
$('body').on('change', "[name='tblList_length']", function(e) {
    e.preventDefault();
    sendData();;

});

$('body').on('search', "input[type='search']", function(e) {
    sendData();

});
//cargar la lista de la gestion
$('body').on('click', '[data-click="solved"]', function(e) {
    var id = $(this).data('id');
    sendStateChange(id).done(function(success) {
        setTimeout(() => {
            $('[data-click="openlistTicket"]').trigger('click');
        }, 500);
    }).fail(function(error) {
        handleErrors(error);
    });
});
//cargar la lista de la gestion
$('body').on('click', '[data-click="openlistProcess"]', function(e) {
    var listProcess = $('#listPrecess');
    localStorage.setItem('observable', 'false');
    $('.loading', listProcess).attr('data-show', 'true');
    loadListProcess().done(function(data) {
        listProcess.html(data.view);
        $('.loading', list).attr('data-show', 'false');
        TAB = '#tabProcess';
        initDataTableAdvance(TAB);
    }).fail(function(error) {
        handleErrors(error);
    });
});
//cargar la lista de Ticket
$('body').on('click', '[data-click="openlistTicket"]', function(e) {
    let listTicket = $('#listTickets');
    localStorage.setItem('observable', 'false');
    $('.loading', listTicket).attr('data-show', 'true');
    

    loadListTicket().done(function(data) {
        listTicket.html(data.view);
        
        $('.loading', list).attr('data-show', 'false');
        $('[data-click="categoryFilter"][data-solved=""]').trigger('click');
        TAB = '#tabTicket';
        initDataTableAdvance(TAB);
    }).fail(function(error) {
        handleErrors(error);
    });
});

//cargar la lista de diagnosticos
$('body').on('click', '[data-click="openlistDiagnostic"]', function(e) {
    let listDiagnostic = $('#listDiagnostic');
    localStorage.setItem('observable', 'false');
    $('.loading', listDiagnostic).attr('data-show', 'true');
    

    loadlistDiagnostic().done(function(data) {
        listDiagnostic.html(data.view);
        
        $('.loading', list).attr('data-show', 'false');
        
        initDataTable();
        $("[name='tblList_length']").val(10);
        $("[name='tblList_length']").trigger('click');
    }).fail(function(error) {
        handleErrors(error);
    });
});

function loadlistDiagnostic() {
    return $.ajax({
        url: ULR + "crm-diagnostices-list",
        method: "GET",
        data: { office: $('[name="OfficesCustomer"]').val() },
        dataType: "json"
    });
}


$('body').on('click', '[data-click="categoryFilter"]', function(e) {
    e.preventDefault();
    let listTickets = $('#listTicket');
    localStorage.setItem('observable', 'false');
    $('.loading', listTickets).attr('data-show', 'true');
    let category=$(this).data('filter');
    let solved=$(this).data('solved');
    if(typeof category !== 'undefined'){
        myObj.type='category';
        myObj.value=category;
    }
    if(typeof solved !== 'undefined'){
        myObj.type='solved';
        myObj.value=solved;
    }
    let order=$(this).data('order');
    let classs=$(this).data('classs');
    let diff=$(this).data('diff');
    if(typeof order !== 'undefined'){
        let orderType=myObj.type;
        if(typeof orderType !== 'undefined'){
            if(orderType == 'category'){
                category=myObj.value;
            }
            if(orderType == 'solved'){
                solved=myObj.value;
            }
        }
        
    }
    $('#selected').text("Mas recientes");
    if(diff == 2){
        $('[data-diff="2"]').removeClass();
        $('[data-diff="1"]').removeClass();
        $('[data-diff="1"]').addClass('btn');
        $(this).addClass(classs);

    }
    if(diff == 1){
        $('[data-diff="1"]').removeClass();
        $('[data-diff="1"]').addClass('btn');
        $('[data-diff="2"]').removeClass();
        $(this).addClass('nav-link active');
    }
    if(diff == 3){
        $('#selected').text($(this).text());
    }
    loadListTicketFilter(category,solved,order).done(function(data) {
        listTickets.html(data.view);
        $('.loading', list).attr('data-show', 'false');
        TAB = '#tabReport';
        initDataTableAdvance(TAB);
    }).fail(function(error) {
        handleErrors(error);
    });
});

/******************************************************************************/

$('body').on('click', '[data-click="openFormDeleteContact"]', function(e) {

    e.preventDefault();
    var mdlForm = $('#mdlDelete');
    var loading = $('.loading', mdlForm).first();
    var id = $(this).data('id');
    loading.attr('data-show', 'true');
    mdlForm.modal('show');

    loadFormDeleteContact(id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        $('.skin-square input').iCheck({ checkboxClass: 'icheckbox_square-red', radioClass: 'iradio_square-red', });
        loading.attr('data-show', 'false');
    }).fail(function(error) {
        handleErrors(error);
    });
});

$('body').on('ifChecked','[name="action"]', function(event){
    //habilitar el botón de confirmacion
    switch (this.value) {
        case 'delete_and_keep_user':
        case 'delete_all':
            $('[data-click="deleteContact"]').attr('disabled',false);
            $('.keep_contact').hide();
            break;
        case 'keep_contact':
            $('.keep_contact').show();
            $('[data-click="deleteContact"]').attr('disabled',true);
            break;
    
        default:
            break;
    }

  });

$('body').on('click', '[data-click="openFormDeleteOffice"]', function(e) {

    e.preventDefault();
    var mdlForm = $('#mdlDelete');
    var loading = $('.loading', mdlForm).first();
    var id = $(this).data('id');
    loading.attr('data-show', 'true');
    mdlForm.modal('show');

    loadFormDeleteOffice(id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        loading.attr('data-show', 'false');
    }).fail(function(error) {
        handleErrors(error);
    });
});

$('body').on('click', '[data-click="openFormDeleteOffice"]', function(e) {

    e.preventDefault();
    var mdlForm = $('#mdlDelete');
    var loading = $('.loading', mdlForm).first();
    var id = $(this).data('id');
    loading.attr('data-show', 'true');
    mdlForm.modal('show');

    loadFormDeleteOffice(id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        loading.attr('data-show', 'false');
    }).fail(function(error) {
        handleErrors(error);
    });
});


$('body').on('click', '[data-click="openFormDeleteUser"]', function(e) {

    e.preventDefault();
    var mdlForm = $('#mdlDelete');
    var loading = $('.loading', mdlForm).first();
    var id = $(this).data('id');
    var user_id = $(this).data('user_id');
    var branchOffice = $('select[name="OfficesCustomer"] option').filter(':selected').val();
    loading.attr('data-show', 'true');
    mdlForm.modal('show');

    loadFormDeleteUser(id, user_id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        $('#office_id_frmDeleteUser').val(branchOffice);
        loading.attr('data-show', 'false');
    }).fail(function(error) {
        handleErrors(error);
    });
});


$('body').on('click', '[data-click="openFormFileUser"]', function(e) {

    e.preventDefault();
    var mdlForm = $('#mdlFormShowFullScreen2');
    var loading = $('.loading', mdlForm).first();
    var id = $(this).data('id');
    var user_id = $(this).data('user_id');
    loading.attr('data-show', 'true');
    mdlForm.modal('show');

    loadFormFileUser(id, user_id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        loading.attr('data-show', 'false');
    }).fail(function(error) {
        handleErrors(error);
    });
});

function loadFormFileUser(id, user_id) {
    return $.ajax({
        url: ULR + "crm/fileUser-form",
        method: "GET",
        data: { id: id, user_id: user_id },
        dataType: "json"
    });
}

/******************************************************************************/

$('body').on('click', '[data-click="deleteContact"]', function(e) {
    e.preventDefault();

    var mdlForm = $('#mdlDelete');
    var loading = $('.loading', mdlForm).first();

    loading.attr('data-show', 'true');

    sendDeleteContact().done(function(data) {
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        getListContact();
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }).fail(function(error) {
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'false');
        showErrors(error.responseJSON.errors, '#frmDelete');
        handleErrors(error);
    });

});

$('body').on('click', '[data-click="deleteOffice"]', function(e) {
    e.preventDefault();

    var mdlForm = $('#mdlDelete');
    var loading = $('.loading', mdlForm).first();

    loading.attr('data-show', 'true');

    sendDeleteOffice().done(function(data) {
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        getListOffice();
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }).fail(function(error) {
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'false');
        showErrors(error.responseJSON.errors, '#frmDelete');
        handleErrors(error);
    });

});

$('body').on('click', '[data-click="deleteUser"]', function(e) {
    e.preventDefault();

    var mdlForm = $('#mdlDelete');
    var loading = $('.loading', mdlForm).first();

    loading.attr('data-show', 'true');
    sendDeleteUser().done(function(data) {
        mdlForm.modal('hide');
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
        loading.attr('data-show', 'true');
        var listUser = $('#listAdvanceUser');
        var branchOffice = $('select[name="OfficesCustomer"] option').filter(':selected').val();
        $('.loading', listUser).attr('data-show', 'true');
        loadListUser(branchOffice).done(function(data) {
            listUser.html(data.view);
            $('.loading').attr('data-show', 'false');
            TAB = '#tabUser';
            initDataTableAdvance(TAB);

        }).fail(function(error) {
            handleErrors(error);
        });


    }).fail(function(error) {
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'false');
        showErrors(error.responseJSON.errors, '#frmDelete');
        handleErrors(error);
    });

});

$('body').on('click', '[data-click="deleteFile"]', function(e) {
    e.preventDefault();

    var mdlForm = $('#mdlDeleteShow');
    var loading = $('.loading', mdlForm).first();

    loading.attr('data-show', 'true');

    sendDeleteFile().done(function(data) {
        loading.attr('data-show', 'false');
        $("[data-dismiss=modal]").trigger({ type: "click" });
        getListDocument();
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }).fail(function(error) {
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'false');
        showErrors(error.responseJSON.errors, '#frmDelete');
        handleErrors(error);
    });

});

/******************************************************************************/


//-- EVENTOS PARA FORMULARIO DE DOCUMENTOS DE USUARIO -----

$('body').on('click', '[data-click="removeRowDocument"]', function(e) {
    if ($(this + ' input[name*="id"]').length > 0) {
        e.preventDefault();
        var mdlForm = $('#mdlDeleteShow');
        var loading = $('.loading-min', '.tbl-documents').first();
        loading.attr('data-show', 'true');


        let key = $(this).data('id');

        KEY_DELETE = key;
        let id = $('input[name="cdocument[' + key + '][id]"]').val();


        loadFormDeleteFile(id).done(function(data) {
            if (data.success !== undefined && !data.success) {
                $('tr[data-id="' + KEY_DELETE + '"]').remove();
            } else if (data.success !== undefined && data.success) {
                mdlForm.modal('show');
            }
            $('.modal-content', mdlForm).html(data.view);
            loading.attr('data-show', 'false');
        }).fail(function(error) {
            loading.attr('data-show', 'false');
            handleErrors(error);
        });
    }
});

$('body').on('change', '.custom-file input', function(e) {
    let key = $(this).data('file');
    let name = e.target.files[0].name;
    $(this).next('.custom-file-label[data-label="' + key + '"]').html(name);
});

$('body').on('click', '[data-click="removeRow"]', function(e) {
    e.preventDefault();

    if ($(this + ' input[name*="id"]').length > 0) {

    }

    let key = $(this).data('id');
    $('tr[data-id="' + key + '"]').remove();
});

function addDocumentRow(seatype = false) {
    return $.ajax({
        url: ULR + "crm/add-document-row",
        method: "GET",
        data: { seatype: seatype },
        dataType: "json"
    });
}

function loadDocumentForm(id) {
    return $.ajax({
        url: ULR + "crm/file-formShow",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

/*-----------------------------------------------------------------------------------------*/
/*
    EVENTOS PARA FORMULARIO DE SERVICIOS X TECNICO
*/

$('body').on('click', '[data-click="removeRowTechnical"]', function(e) {

    if ($(this + ' input[name*="id"]').length > 0) {

        e.preventDefault();

        let key = $(this).data('id');

        var mdlForm = $('#mdlDeleteShow');
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'true');




        KEY_DELETE = key;
        let id = $('input[name="technical[' + key + '][id]"]').val();
        let service_id = $('[name="technical[' + key + '][service]"]').val();
        $('tr[data-id="' + KEY_DELETE + '"]').remove();





        // loadFormDeleteTechnical(id, service_id).done(function(data) {
        //     if (!data.success) {
        //         setTimeout(() => {
        //             $('tr[data-id="' + KEY_DELETE + '"]').remove();
        //         }, 500);
        //     } else {
        //         mdlForm.modal('show');
        //     }

        //     $('.modal-content', mdlForm).html(data.view);
        //     loading.attr('data-show', 'false');
        // });
    }


});

function loadFormDeleteTechnical(id, service_id) {
    return $.ajax({
        url: ULR + "crm/deleteTechnical-form",
        method: "GET",
        data: { id: id, service_id: service_id },
        dataType: "json"
    });
}

$('body').on('click', '[data-click="deleteTechnical"]', function(e) {
    e.preventDefault();

    var mdlForm = $('#mdlDeleteShow');
    var loading = $('.loading', mdlForm).first();

    loading.attr('data-show', 'true');

    sendDeleteTechnical().done(function(data) {

        mdlForm.modal('hide');
        var listTechnical = $('#listAdvanceTechnical');
        loadListTechnical().done(function(data) {
            listTechnical.html(data.view);
            initDataTableAdvance('#tabTechnical');

        }).fail(function(error) {
            handleErrors(error);
        });
        $('tr[data-id="' + KEY_DELETE + '"]').remove();
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }).fail(function(error) {
        loading.attr('data-show', 'false');
        showErrors(error.responseJSON.errors, '#technical');
        handleErrors(error);
    });

});

function sendDeleteTechnical() {

    var form = $('#frmDeleteTechnical');
    var data = form.serialize();

    return $.ajax({
        url: ULR + "crm/form-deleteTechnical/" + $('[name="crm_id"]').val(),
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });

}


function addTechnicalRow() {
    return $.ajax({
        url: ULR + "crm/add-technical-row",
        method: "GET",
        data: {},
        dataType: "json"
    });
}

function loadTechnicalForm(id) {
    return $.ajax({
        url: ULR + "crm/technical-formShow",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

function initAddress() {
    var autocomplete;
    $('.searchMapInput').each(function(index, input) {
        autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.addListener('place_changed', function() {
            var place = autocomplete.getPlace();

            $("[name='address']").val(getAddress(place));
            $("[name='city_name']").val(gettowncity(place.address_components));
            $("[name='lat']").val(place.geometry.location.lat());
            $("[name='lng']").val(place.geometry.location.lng());
        });
    });
}


/******************************************************************************/

//funcion para iniciar los select de los filtros

function getFilters() {
    var container = $('#filteContainer');
    $('.loading', container).attr('data-show', 'true');
    loadContainer().done(function(data) {
        container.html(data.view);
        $('.loading', container).attr('data-show', 'false');
        initSelectfilter();
        initSelectfilterDate();
        initSelectfilterUser();
        initCollapsable();
        $(".collapsed").trigger('click');
        //inicializar  icheck
        $('.skin-square input').iCheck({
            checkboxClass: 'icheckbox_square-red',
            radioClass: 'iradio_square-red',
        });
    }).fail(function(error) {
        handleErrors(error);
    });
}

function loadContainer() {
    return $.ajax({
        url: ULR + "crms/filters",
        method: "GET",
        dataType: "json"
    });
}

function initSelectfilter() {
    $('#filterTypesEvent').select2({
        width: '100%',
        placeholder: "Dejar vacío",
        ajax: {
            url: ULR + "crms/types-event",
            dataType: 'json',
            method: "GET",
            error: function(error) {
                handleErrors(error);
            }
        },
        "language": {
            "noResults": () => "No se encontraron tipos de eventos registrados en el sistema.",
            "searching": () => "Consultando..."
        },
    });
}

function initSelectfilterDate() {

    $('#filterDateEvent').select2({
        width: '100%',
        placeholder: "Dejar vacío",
        ajax: {
            url: ULR + "crms/date-event",
            dataType: 'json',
            method: "GET",
            error: function(error) {
                handleErrors(error);
            }
        },
        "language": {
            "noResults": () => "No se encontraron tipos de eventos registrados en el sistema.",
            "searching": () => "Consultando..."
        },
    });
}

function initSelectfilterUser() {
    $('#filterUser').select2({
        width: '100%',
        placeholder: "Dejar vacío",
        ajax: {
            url: ULR + "crms/user-event",
            dataType: 'json',
            method: "GET",
            error: function(error) {
                handleErrors(error);
            }
        },
        "language": {
            "noResults": () => "No se encontraron tipos de eventos registrados en el sistema.",
            "searching": () => "Consultando..."
        },
    });
}

function getFilterCrmTracking() {

    var form = $('#crmTrackings');
    var data = form.serialize();
    return $.ajax({
        url: ULR + 'crms/event',
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });
}

function initCollapsable() {
    $('a[data-action="collapse"]').on('click', function(e) {
        e.preventDefault();
        $(this).closest('.card').children('.card-content').collapse('toggle');
        $(this).closest('.card').find('[data-action="collapse"] i').toggleClass('ft-plus ft-minus');
    });
}

//-- EVENTOS PARA FORMULARIO DE EVENTOS -----
$('body').on('click', '[data-click="sendFilterEvents"]', function(e) {
    e.preventDefault();

    var listProcess = $('#listPrecess');

    getFilterCrmTracking().done(function(data) {
        listProcess.html(data.view);
    }).fail(function(error) {
        handleErrors(error);
    });
});

//-- EVENTOS PARA FORMULARIO DE DOCUMENTOS DE CLIENTE -----

$('body').on('click', '[data-click="addRow"]', function(e) {
    e.preventDefault();
    var loading = $('.loading-min').first();
    loading.attr('data-show', 'true');
    var seatype = $(this).data('typeseat');

    addDocumentRow(seatype).done(function(data) {
        $('.tbl-documents tbody').append(data.row);
        loading.attr('data-show', 'false');
        var branchOffice = $('select[name="OfficesCustomer"] option').filter(':selected').val();
        $('[name*="[offices_id]').val(branchOffice);
        initSelectTypeFileCrm();
        // initSelectTypeOffice($("[name='crm_id']").val());
    }).fail(function(error) {
        handleErrors(error);
        loading.attr('data-show', 'false');
    });

});

//-- EVENTOS PARA FORMULARIO DE TÉCNICOS POR SERVICIO -----

$('body').on('click', '[data-click="addRowTechnical"]', function(e) {
    e.preventDefault();
    var loading = $('.loading-min').first();
    loading.attr('data-show', 'true');
    addTechnicalRow().done(function(data) {
        $('.tbl-technical tbody').append(data.row);
        initSelectServicesTechnical(data.key);
        initSelectTechnicals(data.key);
        initSelectScheduleStatuses(data.key);
        loading.attr('data-show', 'false');
    }).fail(function(error) {
        handleErrors(error);
        loading.attr('data-show', 'false');
    });

});

/** -------------- AREAS DEL CLIENTE ------------------------- */

$('body').on('click', '[data-click="addNewArea"]', function(e) {


    var id = $(this).data('id');

    Swal.fire({

        title: 'Nombre del área',
        input: 'text',
        name: 'area',
        inputAttributes: {
            autocapitalize: 'off'
        },
        showCancelButton: true,
        confirmButtonText: 'Guardar',
        cancelButtonText: 'Cancelar',
        showLoaderOnConfirm: true,
        preConfirm: (result) => {
            saveArea(result, id).done(function(data) {
                if (data.success) {
                    //añadir el nuevo li
                    $(data.li).insertBefore($('[data-click="addNewArea"]').parent());
                    $(".empty-areas").remove();
                    $('.underTabContent').append(data.div);
                } else {
                    swal('Error', data.message, 'error');
                }
            }).fail(function(error) {
                handleErrors(error);
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
    });
});

function saveArea(resultado, id) {
    return $.ajax({
        url: ULR + "crm/save-area-customer",
        method: "GET",
        dataType: "json",
        data: {
            area: resultado,
            id: id,
            office: $("[name='OfficesCustomer']").val()
        }
    });
}

$('body').on('click', '[data-click="openFormDeleteAreas"]', function(e) {

    e.preventDefault();
    var mdlForm = $('#mdlDelete');
    var loading = $('.loading', mdlForm).first();
    var id = $(this).data('id');
    loading.attr('data-show', 'true');
    mdlForm.modal('show');

    loadFormDeleteAreas(id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        loading.attr('data-show', 'false');
    });

});

$('body').on('dblclick', '.areas', function(e) {

    var id = $(this).data('id');
    var name = $(this).data('name');
    Swal.fire({
        title: 'Nombre del area',
        input: 'text',
        inputValue: name,
        name: 'area',
        inputAttributes: {
            autocapitalize: 'off'
        },
        showCancelButton: true,
        confirmButtonText: 'Guardar',
        cancelButtonText: 'Cancelar',
        showLoaderOnConfirm: true,
        preConfirm: (result) => {
            updateArea(result, id).done(function(data) {
                if (data.success) {
                    swal(data.message ? 'Correcto' : 'Error', data.message, data.message ? 'success' : 'error');
                    resetPlans();
                } else {
                    swal('Error', data.message, 'error');
                }
            }).fail(function(error) {
                handleErrors(error);
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
    });
});

function updateArea(resultado, id) {
    return $.ajax({
        url: ULR + "crm/update-area-customer",
        method: "GET",
        dataType: "json",
        data: {
            name: resultado,
            id: id
        },
    });
}

function loadFormDeleteAreas(id) {
    return $.ajax({
        url: ULR + "crm/delete-form-area",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

$('body').on('click', '[data-click="deleteArea"]', function(e) {
    e.preventDefault();

    var mdlForm = $('#mdlDelete');
    var loading = $('.loading', mdlForm).first();

    loading.attr('data-show', 'true');

    sendDeleteArea().done(function(data) {
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
        resetPlans();
    }).fail(function(error) {
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'false');
        showErrors(error.responseJSON.errors, '#plan');
        handleErrors(error);
        resetPlans();
    });

});


function addTechnicalRow() {
    return $.ajax({
        url: ULR + "crm/add-technical-row",
        method: "GET",
        data: {},
    });

}

function sendDeleteArea() {

    var form = $('#frmDelete');
    var data = form.serialize();

    return $.ajax({
        url: ULR + "crm/area/delete",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });

}

function resetPlans() {
    $('#tabPlanTab').trigger('click');
}

//----------- PLANOS DEL CLIENTE -----------------

$('body').on('click', '[data-click="areasCustomerForm"]', function(e) {
    //abrir el modal y mostrar loading
    e.preventDefault();
    var mdlForm = $('#mdlForm');
    var id = $(this).data('id');
    mdlForm.modal('show');

    //leer el formulario de usuarios
    loadFormAreasCustomer(id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        loading.attr('data-show', 'false');
        initSelectsEdit();
        loadListAreaCustomer(id).done(function(data) {
            QUOTE = id;
            $('.areasService').html(data.view);
            $('.nav-link.services.active').trigger('click');
            $('.nav-link.areas.active').trigger('click');
        });
    }).fail(function(error) {
        handleErrors(error);
    });
});

$('body').on('click', '[data-click="removePlanRow"]', function(e) {

    var mdlForm = $('#mdlDelete');
    let id = $(this).data('id');
    e.preventDefault();

    loadFormDeletePlan(id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
        mdlForm.modal('show');
    });
});

function loadFormDeletePlan(id) {
    return $.ajax({
        url: ULR + "crm/delete-plan-form",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

$('body').on('click', '[data-click="removeRow"]', function(e) {
    e.preventDefault();

    if ($(this + ' input[name*="id"]').length > 0) {

    }

    let key = $(this).data('id');
    $('tr[data-id="' + key + '"]').remove();
});

$('body').on('click', '[data-click="deletePlan"]', function(e) {
    e.preventDefault();
    var mdlForm = $('#mdlDelete');

    sendDeletePlan().done(function(data) {
        mdlForm.modal('hide');
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
        resetPlans();
    }).fail(function(error) {
        showErrors(error.responseJSON.errors, '#plan');
        handleErrors(error);
        resetPlans();
    });
});

function sendDeletePlan() {

    var form = $('#frmDelete');
    var data = form.serialize();

    return $.ajax({
        url: ULR + "crm/plan/delete",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });

}

document.addEventListener("visibilitychange", function() {
    if (document.visibilityState == 'visible' && localStorage.getItem('observable') == 'true') {
        resetPlans();
    }
});

function initInputDate() {
    var from_$input = $('#picker_from').pickadate({
        formatSubmit: 'yyyy-mm-dd',
        container: '.app-content',
        firstDay: 1,
        selectYears: true,
        selectMonths: true,
        monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
        today: 'Hoy',
        clear: 'Limpiar',
        close: 'Cerrar'
    });

    FROM_PICKER = from_$input.pickadate('picker');

    var to_$input = $('#picker_to').pickadate({
        formatSubmit: 'yyyy-mm-dd',
        container: '.app-content',
        firstDay: 1,
        selectYears: true,
        selectMonths: true,
        monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
        today: 'Hoy',
        clear: 'Limpiar',
        close: 'Cerrar'
    });

    TO_PICKER = to_$input.pickadate('picker');
    checkRangeSelected();
}

function checkRangeSelected() {
    // Check if there’s a “from” or “to” date to start with.
    if (FROM_PICKER.get('value')) {
        TO_PICKER.set('min', FROM_PICKER.get('select'));
    }
    if (TO_PICKER.get('value')) {
        FROM_PICKER.set('max', TO_PICKER.get('select'));
    }
}




//CKEDITOR.instances.ckeditor.getData();a();

$('body').on('change', '#showCrmImage input[name="avatar"]', function(e) {
    setAvatar().done(function(data) {
        if (data.success) {
            showToast(data.message);
        } else {
            showToastError(data.message)
        }

    }).fail(
        handleErrors(data)
    );
});

function setAvatar() {
    var form = $('#showCrmImage');
    var data = form.serialize();
    return $.ajax({
        url: ULR + 'crm/' + $("[name='crm_id']").val() + '/set-avatar',
        method: 'POST',
        data: data,
        dataType: 'json'
    });
}



function initSelectApplication(key) {

    $('[name*="[' + key + '][application]"]').select2({
        width: '100%',
        placeholder: "Seleccione una aplicación",
        allowClear: true,
        tags: true,
        ajax: {
            url: ULR + "quotes/products/applications",
            dataType: 'json',
            method: "GET",
            success: selectScroll,
            error: function(error) {
                handleErrors(error);
            }
        },
        cache: true,
        dropdownCssClass: "bigdrop",
        escapeMarkup: function(m) { return m; },
        "language": {
            "noResults": () => "No se encontraron aplicaciones registradas en el sistema.",
            "errorLoading": () => "No se puede leer la respuesta del servidor"
        }
    }).on("select2:open", e => selectScroll());
}


function initSelectProduct(key) {
    const select = $('[name*="[' + key + '][supply]"]');
    $(select).select2({
        width: '100%',
        dropdownParent: $('.modal-content', $('#mdlForm')),
        placeholder: "Seleccione una opción",
        allowClear: true,
        ajax: {
            url: ULR + "quotes/products/category/1/control/" + $(select).data('control'),
            dataType: 'json',
            method: "GET",
            success: selectScroll,
            error: function(error) {
                handleErrors(error);
            }
        },
        cache: true,
        templateResult: dataFormatResultProduct,
        templateSelection: dataFormatSelectionControlProduct,
        dropdownCssClass: "bigdrop",
        escapeMarkup: function(m) { return m; },
        "language": {
            "noResults": () => "No se encontraron productos registrados en el sistema.",
            "searching": () => "Consultando...",
            "errorLoading": () => "No se puede leer la respuesta del servidor"
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var key = selected_element.data('key');
        var product = selected_element.val();
        if (e.params.data.category_id == 1) {
            $('[name*="[' + key + '][unity]"]').val(e.params.data.unit.name);
        } else {
            $('[name*="[' + key + '][unity]"]').val('Und');
        }

    }).on("select2:open", e => selectScroll());
}


function initSelectCoverage(service) {

    $('select[name*="[services][' + service + '][detail_service_id]"]').select2({
        width: '100%',
        dropdownParent: $('.modal-content', $('#mdlForm')),
        placeholder: "Seleccione una cobertura",
        ajax: {
            url: ULR + "quotes/areas/" + service,
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
            "noResults": () => "No se encontraron coberturas registradas en esta sede.",
            "searching": () => "Consultando..."
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var area = selected_element.val();
        $('[name*="[' + QUOTESERVICE + '][price]"]').val(number_format(e.params.data.cost));

    });
}

function draw() {

    console.time('draw');
    heat.draw();
    console.timeEnd('draw');
    frame = null;
}

function InitHeatReport() {

    var canvas = document.getElementById("heatcanvas1"),
        ctx = canvas.getContext("2d");

    var background = new Image();
    background.src = ULR + "images/default/demo_blank_page.png";

    // Make sure the image is loaded first otherwise nothing will draw.
    background.onload = function() {
        document.getElementById("heatcanvas1").style.background = "url('" + background.src + "')";
        document.getElementById("heatcanvas2").style.background = "url('" + background.src + "')";
    }

    // Draw whatever else over top of it on the canvas.

    window.requestAnimationFrame = window.requestAnimationFrame || window.mozRequestAnimationFrame ||
        window.webkitRequestAnimationFrame || window.msRequestAnimationFrame;


    //mapa de calor 1
    var data1 = [

        [397, 150, 1],
        [297, 180, 1],
        [497, 250, 4],
        [297, 290, 8],
        [497, 300, 4],
        [297, 310, 1],
        [597, 340, 2],
        [297, 350, 2],
        [697, 360, 1],
        [297, 380, 2],
        [297, 430, 2],
        [297, 440, 5],
        [297, 450, 1]
        [297, 490, 2],
        [297, 540, 1],
        [297, 550, 1],
        [307, 120, 3],
        [307, 140, 2],
        [307, 250, 1],
        [307, 270, 1],
        [307, 290, 1],
        [307, 320, 1],
        [307, 350, 1],
        [316, 270, 1],
        [316, 410, 1],
        [316, 420, 1],
        [316, 430, 4],
        [316, 440, 1],
        [316, 460, 1],
        [316, 490, 1],
        [316, 510, 1],
        [316, 530, 2],
        [316, 550, 1],
        [345, 190, 2],
        [345, 240, 1],
        [345, 260, 1],
        [345, 290, 4],
        [355, 220, 1],
        [355, 250, 1],
        [355, 280, 1],
        [355, 290, 3],
        [355, 300, 1],
        [355, 310, 2],
        [355, 320, 2],
        [355, 330, 4],
        [364, 200, 1],
        [364, 230, 1],
        [364, 250, 1],
        [364, 290, 1],
        [364, 310, 1],
        [364, 430, 1],
        [364, 520, 1],
        [374, 180, 2],
        [374, 190, 2],
        [374, 250, 1],
        [374, 260, 2],
        [374, 340, 1],
        [374, 450, 1],
        [374, 480, 1],
        [384, 120, 1],
        [384, 140, 1],
        [384, 150, 1],
        [384, 180, 1],
        [384, 210, 1],
        [384, 250, 4],
        [384, 270, 1],
        [384, 300, 1],
        [384, 310, 1],
        [384, 350, 1],
        [384, 390, 1],
        [384, 400, 2],
        [384, 550, 1],
        [384, 560, 1],
        [333, 100, 1],
        [343, 140, 1],
        [353, 150, 2],
        [363, 160, 1],
        [373, 180, 2],
        [383, 210, 1],
        [393, 290, 1],
        [313, 310, 1],
        [323, 400, 2],
        [333, 450, 1],
        [343, 480, 1],
        [343, 510, 1],
        [341, 520, 1],
        [403, 160, 1],
        [403, 230, 2],
        [403, 250, 1],
        [403, 280, 1],
        [403, 390, 1],
        [403, 400, 2],
        [403, 450, 1],
        [403, 470, 1],
        [403, 500, 2],
        [403, 570, 1],
        [412, 330, 1],
        [412, 380, 2],
        [412, 400, 4],
        [422, 160, 1],
        [422, 170, 2],
        [422, 480, 1],
        [422, 490, 2],
        [422, 510, 1],
        [422, 640, 1],
        [432, 240, 1],
        [432, 250, 1],
        [432, 260, 1],
        [432, 520, 1],
        [451, 150, 3],
        [451, 480, 2],
        [451, 560, 1],
        [470, 520, 1],
        [480, 200, 1],
        [480, 220, 1],
        [480, 290, 2],
        [480, 320, 2],
        [480, 330, 7],
        [480, 410, 1],
        [480, 450, 2],
        [480, 510, 1],
        [480, 540, 1],
        [489, 130, 40],
        [489, 140, 20],
        [489, 150, 2],
        [489, 220, 2],
        [489, 230, 1],
        [489, 240, 2],
        [489, 250, 2],
        [489, 260, 1],
        [489, 270, 3],
        [499, 160, 2],
        [499, 310, 2],
        [508, 120, 1],
        [508, 130, 2],
        [508, 140, 2],
        [508, 150, 1],
        [508, 190, 1],
        [508, 280, 2],
        [508, 360, 1],
        [508, 410, 1],
        [508, 450, 2],
        [508, 490, 1],
        [508, 510, 2]
    ];
    // var heat1 = simpleheat('heatcanvas1').data(data1).max(18);
    // heat1.draw();

    // //mapa de calor 2
    // var data2 = [
    //     [297, 315, 1],
    //     [297, 315, 1],
    // ];
    // var heat2 = simpleheat('heatcanvas2').data(data2).max(18);
    // heat2.draw();
}

function simpleheat(canvas) {
    if (!(this instanceof simpleheat)) return new simpleheat(canvas);

    this._canvas = canvas = typeof canvas === 'string' ? document.getElementById(canvas) : canvas;

    this._ctx = canvas.getContext('2d');
    this._width = canvas.width;
    this._height = canvas.height;

    this._max = 1;
    this._data = [];
}

simpleheat.prototype = {

    defaultRadius: 25,

    // defaultGradient: {
    //     0.4: 'blue',
    //     0.6: 'cyan',
    //     0.7: 'lime',
    //     0.8: 'yellow',
    //     1.0: 'red'
    // },

    defaultGradient: {
        0.1: 'blue',
        0.2: 'cyan',
        0.3: 'lime',
        0.4: 'yellow',
        0.5: 'red'
    },

    data: function(data) {
        this._data = data;
        return this;
    },

    max: function(max) {
        this._max = max;
        return this;
    },

    add: function(point) {
        this._data.push(point);
        return this;
    },

    clear: function() {
        this._data = [];
        return this;
    },

    radius: function(r, blur) {
        blur = 5;

        // create a grayscale blurred circle image that we'll use for drawing points
        var circle = this._circle = this._createCanvas(),
            ctx = circle.getContext('2d'),
            r2 = this._r = r + blur;

        circle.width = circle.height = r2 * 2;

        ctx.shadowOffsetX = ctx.shadowOffsetY = r2 * 2;
        ctx.shadowBlur = blur;
        ctx.shadowColor = 'black';

        ctx.beginPath();
        ctx.arc(-r2, -r2, r, 0, Math.PI * 2, true);
        ctx.closePath();
        ctx.fill();

        return this;
    },

    resize: function() {
        this._width = this._canvas.width;
        this._height = this._canvas.height;
    },

    gradient: function(grad) {
        // create a 256x1 gradient that we'll use to turn a grayscale heatmap into a colored one
        var canvas = this._createCanvas(),
            ctx = canvas.getContext('2d'),
            gradient = ctx.createLinearGradient(0, 0, 0, 256);

        canvas.width = 1;
        canvas.height = 256;

        for (var i in grad) {
            gradient.addColorStop(+i, grad[i]);
        }

        ctx.fillStyle = gradient;
        ctx.fillRect(0, 0, 1, 256);

        this._grad = ctx.getImageData(0, 0, 1, 256).data;

        return this;
    },

    draw: function(minOpacity) {
        if (!this._circle) this.radius(this.defaultRadius);
        if (!this._grad) this.gradient(this.defaultGradient);

        var ctx = this._ctx;

        ctx.clearRect(0, 0, this._width, this._height);

        // draw a grayscale heatmap by putting a blurred circle at each data point
        for (var i = 0, len = this._data.length, p; i < len; i++) {
            p = this._data[i];
            ctx.globalAlpha = Math.min(Math.max(p[2] / this._max, minOpacity === undefined ? 0.1 : minOpacity), 1);
            ctx.drawImage(this._circle, p[0] - this._r, p[1] - this._r);
        }

        // colorize the heatmap, using opacity value of each pixel to get the right color from our gradient
        var colored = ctx.getImageData(0, 0, this._width, this._height);
        this._colorize(colored.data, this._grad);
        ctx.putImageData(colored, 0, 0);

        return this;
    },

    _colorize: function(pixels, gradient) {
        for (var i = 0, len = pixels.length, j; i < len; i += 4) {
            j = pixels[i + 3] * 4; // get gradient color from opacity value

            if (j) {
                pixels[i] = gradient[j];
                pixels[i + 1] = gradient[j + 1];
                pixels[i + 2] = gradient[j + 2];
            }
        }
    },

    _createCanvas: function() {
        if (typeof document !== 'undefined') {
            return document.createElement('canvas');
        } else {
            // create a new canvas instance in node.js
            // the canvas class needs to have a default constructor without any parameter
            return new this._canvas.constructor();
        }
    }
};