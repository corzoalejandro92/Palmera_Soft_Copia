var modalForceClose = false;

try { if (AUTOCOMPLETE_OFFICE !== undefined && AUTOCOMPLETE_OFFICE != '') officeObserverHandler(); } catch (error) {console.warn(error);}

function officeObserverHandler() {
    var OfficeSelectObserver = new MutationObserver(function(mutations, me) {
        console.log('observing office select...');
        if (window['OfficesCustomer']) {
            initSelectHandler($('[name="OfficesCustomer"]'), AUTOCOMPLETE_OFFICE);
            me.disconnect(); // stop observing
            console.log('desconecting observer office select...');
            return;
        }
    });

    // start observing
    OfficeSelectObserver.observe(document, {
        childList: true,
        subtree: true
    });
}

// callback executed when canvas was found
function initSelectHandler(element, encodeData) {
    var data = JSON.parse(atob(encodeData));
    var $newOption = $("<option selected='selected'></option>").val(data.id).text(data.name);
    element.append($newOption).trigger('change');
}

function callOpenFormFunction() {
    //abrir el modal y mostrar loading

    SET_DAFAULT_TIME = true;
    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'true');
    var mdlForm = $('#mdlForm');
    $('.modal-content', mdlForm).html(decodeURIComponent(escape(atob(callOpenFormView))));
    mdlForm.modal('show');
    $('[data-click="openlistSchedule"]').trigger('click');

    showLoading();

    var functionsObservers = new MutationObserver(function(mutations, me) {
        console.log('waiting function...');
        if (typeof initSelectCoverages != "undefined") {
            initSelectCoverages();
            initSelectPeriodicities();
            initInputDateSimple();
            initSelectWeeks();


            initSelectInfestations(true);
            processAvatars();
            initSelectAreaControl();
            initSelectProducts();
            initSelectDoses();
            initSelectApplications();
            initTextareaResize();
            initSelectTechnicals();
            initSelectScheduleStatuses();
            
            console.log('desconecting observable');
            me.disconnect(); // stop observing
            setTimeout(() => {
                hideLoading();
            }, 500);
            return;
        }
    });

    // start observing
    functionsObservers.observe(document, {
        childList: true,
        subtree: true
    });

    //initSelectScheduling();
    //initSelectUsers();
    //initSelectCrms();
    //initTimePickersPlugin();
    //$('[name="type"]').val($('[name="type"]').val()).trigger('select2:select');

}