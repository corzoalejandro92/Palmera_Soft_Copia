//---FUNCIONES PARA INICIALIZAR SUMMERNOTE
$(document).ready(function() {
    $('#summernote').summernote({
        placeholder: 'Descripción de la encuesta',
        minHeight: null,
        maxHeight: null, 
        focus: true,
        height: 300,
        tabsize: 2,
        height: 100,
        dialogsFade: true,
    });

    var textareaValue = $("#summernote").summernote('code');
    console.log(textareaValue);
});



//---FUNCIONES PARA INICIALIZAR EL WIZARD
var wizard = $("#pollForm").steps({
    headerTag: "h6",
    bodyTag: "fieldset",
    transitionEffect: 1,
    transitionEffectSpeed: 300,
    titleTemplate: '<span class="step">#index#</span> #title#',
    labels: {
        finish: 'Terminar',
        previous: 'Anterior',
        next: 'Siguiente'

    },
    onFinished: function() {
        var loading = $('.loading').first();
        loading.attr('data-show', 'true');
        saveForm().done(function(data) {
            loading.attr('data-show', 'false');
            swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success": "error");
            window.location.href = ULR+'poll';
        }).fail(function(error){
            var loading = $('.loading').first();
            loading.attr('data-show', 'false');
            showErrors(error.responseJSON.errors,'#pollForm');
            handleErrors(error);
        });
    }
 
});

function saveForm() {

    var form = $('#pollForm');
    var data = form.serialize();
    return $.ajax({
        url: ULR+'poll/save',
        method:$('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });
}


//*************************************************************************/
//-- EVENTOS PARA AGREGAR FORMULARIO -----


//-- EVENTOS PARA AGREGAR UN QUESTIONARIO NUEVO DE PREGUNTA EN LA ENCUETRA -----
$('body').on('click', '[data-click="addRowQuestion"]', function(e){
    e.preventDefault();
    addRowQuestion().done(function(data){
        $('.tbl-question > tbody').append(data.row);
    }).fail(function(error){
        handleErrors(error);
        loading.attr('data-show', 'false');
    });
});

function addRowQuestion() {
    return $.ajax({
        url: ULR +"poll/add-question-row",
        method: "GET",
        data: { },
        dataType: "json"
    });
}

//-- EVENTOS PARA LLAMAR AL MODEL DE CONFIRMACION DE ELIMINACION DE UNA PREGUNTA-----
$('body').on('click', '[data-click="removeQuestionRow"]', function(e){


    if($(this + ' input[name*="id"]').length > 0){

        let key = $(this).data('key');

        var mdlForm = $('#mdlDelete');
        var loading = $('.loading-min','.tbl-question').first();
        loading.attr('data-show', 'true');
        mdlForm.modal('show');

        KEY_DELETE = key;
        let id = $('input[name="question['+key+'][id]"]').val();
        e.preventDefault();

        loadFormDelete(id).done(function(data) {

            if(!data.success){
                $('tr[data-id="'+KEY_DELETE+'"]').remove();
                setTimeout(() => {
                    mdlForm.modal('hide');
                }, 500);
            }

            $('.modal-content', mdlForm).html(data.view);
            loading.attr('data-show', 'false');
        }).fail(function(error){
            handleErrors(error);
        });
    }

});

function loadFormDelete(id) {
    return $.ajax({
        url: ULR +"poll/delete-question-form",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

//-- EVENTOS QUE ELIMINACION DE UNA PREGUNTA-----
$('body').on('click', '[data-click="deleteQuestion"]', function(e) {
     e.preventDefault();

     var mdlForm = $('#mdlDelete');
     var loading = $('.loading', mdlForm).first();

     loading.attr('data-show', 'true');

     sendDeleteQuestion().done(function(data) {
         loading.attr('data-show', 'false');
         mdlForm.modal('hide');
         $('tr[data-id="'+KEY_DELETE+'"]').remove();
         swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success": "error");
     }).fail(function(error){
         var loading = $('.loading', mdlForm).first();
         loading.attr('data-show', 'false');
         showErrors(error.responseJSON.errors,'#frmDeleteQuestion');
         handleErrors(error);
     });
});

function sendDeleteQuestion() {

     var form = $('#frmDeleteQuestion');
     var data = form.serialize();

     return $.ajax({
         url: ULR +"poll/delete-question",
         method: $('[name="_method"]', form).val(),
         data: data,
         dataType: "json"
     });

}
//*************************************************************************/

//-- EVENTOS PARA AGREGAR UNA FILA NUEVA DE RESPUESTAS EN LA PREGUNTA -----
$('body').on('click', '[data-click="addRowAnswer"]', function(e){
    e.preventDefault();
    var key = $(this).data('key');
    addRowAnswer(key).done(function(data){
        $('#answerContent_'+key).append(data.row);
    }).fail(function(error){
        handleErrors(error);
        loading.attr('data-show', 'false');
    });
});

function addRowAnswer(key) {
    return $.ajax({
        url: ULR + "poll/add-answer-row",
        method: "GET",
        data: { key : key },
        dataType: "json"
    });
}

//-- EVENTOS PARA LLAMAR AL MODEL DE CONFIRMACION DE ELIMINACION DE PREGUNTA-----
$('body').on('click', '[data-click="removeAnswerRow"]', function(e){


    if($(this + ' input[name*="id"]').length > 0){

        let skey = $(this).data('skey');
        let key = $(this).data('key');


        var mdlForm = $('#mdlDelete');
        var loading = $('.loading-min','.table responsive').first();
        loading.attr('data-show', 'true');
        mdlForm.modal('show');

        KEY_DELETE = skey;
        let id = $('input[name="question['+key+'][answer]['+skey+'][id]"]').val();
        e.preventDefault();

        loadFormDeleteAnswer(id).done(function(data) {

            if(!data.success){
                $('tr[data-id="'+KEY_DELETE+'"]').remove();
                setTimeout(() => {
                    mdlForm.modal('hide');
                }, 500);
            }

            $('.modal-content', mdlForm).html(data.view);
            loading.attr('data-show', 'false');
        }).fail(function(error){
            handleErrors(error);
        });
    }
});

function loadFormDeleteAnswer(id) {
    return $.ajax({
        url: ULR +"poll/delete-answer-form",
        method: "GET",
        data: { id: id },
        dataType: "json"
    });
}

//-- EVENTOS QUE ELIMINACION DE UNA PREGUNTA-----
$('body').on('click', '[data-click="deleteAnswer"]', function(e) {
    e.preventDefault();

    var mdlForm = $('#mdlDelete');
    var loading = $('.loading', mdlForm).first();

    loading.attr('data-show', 'true');

    sendDeleteAnwer().done(function(data) {
        loading.attr('data-show', 'false');
        mdlForm.modal('hide');
        $('tr[data-id="'+KEY_DELETE+'"]').remove();
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success": "error");
    }).fail(function(error){
        var loading = $('.loading', mdlForm).first();
        loading.attr('data-show', 'false');
        showErrors(error.responseJSON.errors,'#frmDeleteAnswer');
        handleErrors(error);
    });
});

function sendDeleteAnwer() {

    var form = $('#frmDeleteAnswer');
    var data = form.serialize();

    return $.ajax({
        url: ULR +"poll/delete-answer",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });

}
//*************************************************************************/