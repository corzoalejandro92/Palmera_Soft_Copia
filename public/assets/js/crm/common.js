var zX = 1;
var mdlForm = $('#mdlFormShow');
var mdlDelete = $('#mdlDelete');

$('body').ready(function() {

    //elementCounter();
    $("#cardImage").mCustomScrollbar({
        theme: "rounded-light",
        scrollButtons: { enable: true, scrollType: "stepped", scrollAmount: 100 },
        keyboard: { scrollType: "stepless" },
        mouseWheel: { scrollAmount: 100 },
        axis: "yx",
        setHeight: 'auto',
        setWidth: "auto",
    });

    $("#elements").mCustomScrollbar({
        theme: "rounded-light",
        scrollButtons: { enable: true, scrollType: "stepped", scrollAmount: 100 },
        keyboard: { scrollType: "stepless" },
        mouseWheel: { scrollAmount: 100 },
        axis: "y",
    });

    //$(".mCSB_container").draggable({ cursor: 'move', });

    $(".mechanism").draggable({
        helper: "clone",
        cursor: 'move',
        revert: 'invalid',
        appendTo: 'body',
        containment: 'window',
        scroll: false,
        stop: function(ev, ui) {}
    });
    $(".mechanismP").draggable({
        helper: "clone",
        cursor: 'move',
        revert: 'invalid',
        appendTo: 'body',
        containment: 'window',
        scroll: true,
        stop: function(ev, ui) {
            adjustUpdatePosition(this, ui.offset);
        }
    });

    $(".plan").droppable({
        accept: ".mechanism",
        tolerance: 'pointer',
        greedy: true,
        drop: function(ev, ui) {
            //var element = $(ui.draggable).clone(true).off().detach().css({
            var element = $(ui.draggable).clone(true).off().detach().css({
                position: 'absolute',
                top: '0px',
                left: '0px',
            }).appendTo(this);
            //ui.draggable.draggable({ disabled: true });
            adjustPosition(element, ui.offset);


        }
    });

    $("#customRange").change(function() {
        var size = $(this).val();
        $(".list-mechanism .convention-md-dot").css('height', size + 'px').css('width', size + 'px');
    });

    
});


//hacer zoomeable los planos
/* $('body').on('mousewheel', '#mCSB_1_container', function(e, event) {
    var dir;
    console.log(e, event);
    console.log(e.originalEvent.clientY);
    dir = (e.originalEvent.wheelDelta / 120 > 0) ? 0.1 : -0.1;
    zX += dir;
    for (var i = 0; i < $(this).length; i++) {
        this.style.transform = 'scale(' + zX + ')';
    }
    e.preventDefault();
}); */

function adjustUpdatePosition(element, position) {

    y = position.top - $('.plan').offset()['top'];
    x = position.left - $('.plan').offset()['left'];


    //convertir los valores a porcentajes
    var y$ = y * 100 / $('.plan')[0].offsetHeight;
    var x$ = x * 100 / $('.plan')[0].offsetWidth;
    var size = document.getElementById("customRange").value;

    $(element).css({ top: y$ + '%', left: x$ + '%'});

        updatePositionMechanism(element, x$, y$).done(function(data) {
            if (data.success) {
                $(element).data('y', y$);
                $(element).data('x', x$);
                $(element).data('id', data.id);
                $(element).data('size', data.size);
                addDeleteButton(element, y$, x$, data.id, data.size,'update');
                let title = $(element).data('title');
                if(title == 'Mecanismos'){
                    addText(element,'update');
                    countText(element,x$,y$);
                }
            } else {
                $(element).remove();
                swal(data.success ? 'Correcto' : "Error", data.message, data.success ? "success" : "error");
            }
        }).fail(function(error) {
            handleErrors(error);
        });
}



function adjustPosition(element, position) {


    y = position.top - $('.plan').offset()['top'];
    x = position.left - $('.plan').offset()['left'];


    //convertir los valores a porcentajes
    var y$ = y * 100 / $('.plan')[0].offsetHeight;
    var x$ = x * 100 / $('.plan')[0].offsetWidth;
    var size = document.getElementById("customRange").value;

    $(element[0]).css({ top: y$ + '%', left: x$ + '%', height: size + 'px', width: size + 'px' });
        saveElement(element[0], x$, y$).done(function(data) {
            location.reload();
        }).fail(function(error) {
            handleErrors(error);
        });
}

function addDeleteButton(element, y, x, id, size, operation=null) {

    let element0= operation == 'update' ? $(element):$(element[0]) ;
    let size2 = element0.data('size');
    var xmargin = size2 /10;
    var ymargin = size2 /40;
    let ELEMENTB=$('.deleteB'+id).first();
        ELEMENTB.remove();
    var button = $(`<i class="la la-chevron-circle-down la-2x deleteB${id} remove-mechanism"  data-toggle="dropdown"></i>
    `).insertAfter(element0);
    
    $(button).css({ position: 'absolute', top: (y+ymargin) + '%', left: (x+xmargin) + '%', height: size + 'px', width: size + 'px' });
    $(button).attr('data-id',id);
    $(button).attr('data-click','options');
    $(button).attr('data-title',element0.data('title'));
    $(button).bind( "click", function( ) {
        options(id,element0.data('title'));
    });

}
function options(id,title) {
    let SELECTELEM=$("div#options");
    SELECTELEM.html('');

    let optionss='';
    //<li ><span>${id}Reemplazar (Cambiar mecanismo, mismo qr) [Validar escenarios con Oscar] () </span></li>
    if(title == 'Mecanismos'){
        optionss = `
             <li data-id="${id}" data-click="reNumberElement" data-title="Renumerar(Manual)"><span>Renumerar (Manual)</span></li>
            <li data-id="${id}" data-click="reNumberElement" data-title="Escalar"><span>Escalar icono</span></li>
            <li data-id="${id}" data-click="reNumberAutomatic"><span>Renumerar(Masivo)</span></li>
            <li data-id="${id}" data-click="recordElement"><span>Historial </span></li>
            
            <li data-id="${id}" data-click="removeElement"><span>Retirar</span></li>
            <li data-id="${id}" data-click="replaceMechanism"><span>Reemplazar </span></li>`
    }else{
        optionss = `
            <li data-id="${id}" data-click="reNumberElement" data-title="Escalar"><span>Escalar icono</span></li>
            <li data-id="${id}" data-click="removeElement"><span>Retirar</span></li>
            `
    };

    SELECTELEM.append(optionss)

}
$(".remove-mechanism").on('click',function(){
    let id = $(this).data('id');
    let title = $(this).data('title');
    options(id,title)
});

function initSelectMechanism() {
    $('#new_file').select2({
        width: '100%',
        //tags: true,
        ajax: {
            url: ULR + "convection/selectMechanims",
            dataType: 'json',
            method: "GET",
        }
    }).on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        APPLICATION = select_val; 
    });
}


function removeElement(id, affectInventory) {
    return $.ajax({
        method: 'get',
        url: ULR + 'crm/element/' + id + '/delete',
        data: { id: id, affectInventory: affectInventory }
    });
}

$('body').on('click', "[data-click='removeElement']", function() {
    var id = $(this).data('id');
    Swal.fire({
        title: 'Retirar este mecanismo',
        text: "¿Estas seguro que deseas desinstalar este mecanismo? Esta acción será irreversible.",
        icon: 'warning',
        input: 'checkbox',
        inputPlaceholder: 'Afectar el inventario',
        showCancelButton: true,
        confirmButtonColor: '#009688',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Si, Desintalar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {

        

        if (result.value != undefined) {
            removeElement(id, result.value).done(function(data) {

                swal.fire({
                    'title': data.success ? "Correcto" : "Error",
                    'text': data.message,
                    'showConfirmButton': false,
                    'timer': 1500,
                    'type': data.success ? "success" : "error",
                }).then(function() {
                    if(data.success == true) {
                        let ELEMENT=$('.delete'+id);
                        let ELEMENTB=$('i.deleteB'+id).first();
                        let ELEMENTD=$('span.deleteT'+id).first();
                        let ELEMENTC=$('span.spancount'+id).first();
                        ELEMENT.remove();
                        ELEMENTB.remove();
                        ELEMENTD.remove();
                        ELEMENTC.remove();
                        //$('[data-click="reNumberAutomatic"][data-id="'+id+'"]').trigger('click');
                    }
                });

            }).fail(function(error) {
                handleErrors(error);
            });
        }

    });
});
$('body').on('click', "[data-click='associateQr']", function() {
    var id = $(this).data('id');
    var loading = $('.loading', mdlDelete).first();
    loading.attr('data-show', 'true');
    mdlDelete.modal('show');
    loadQr(id).done(function(data) {
        $('.modal-content', mdlDelete).html(data.view);
        initSelectQr();
        let searchParams = new URLSearchParams(window.location.search)
        $('input[name="qr_office"]').val(searchParams.get('office'));
    }).fail(function(error) {
        handleErrors(error);
    });

});

$('body').on('click', "[data-click='replaceMechanism']", function() {
    var id = $(this).data('id');
    var loading = $('.loading', mdlDelete).first();
    loading.attr('data-show', 'true');
    mdlDelete.modal('show');
    loadReplace(id).done(function(data) {
        $('.modal-content', mdlDelete).html(data.view);
        initSelectMechanism();
        let searchParams = new URLSearchParams(window.location.search)
        $('input[name="qr_office"]').val(searchParams.get('office'));
    }).fail(function(error) {
        handleErrors(error);
    });

});

$('body').on('click', '[data-click="saveReplace"]', function(e) {
    e.preventDefault();
    replaceMechanism().done(function(data) {
        mdlDelete.modal('hide');
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
        if(data.success == true) location.reload();
    }).fail(function(error) {
        handleErrors(error);
        showErrors(error.responseJSON.errors, '#mdlDelete')
    });

});

function replaceMechanism() {

    var form = $('#frm-replace');
    var data = form.serialize();
    data+='&crm='+$('#crm').val()+"&office="+$('#office').val();
    return $.ajax({
        url: ULR + `crm/replaceMechanism/replace/${$('#old_file').val()}/${$('#new_file').val()}`,
        method: 'PUT',
        data: data,
        dataType: "json"
    });
}
$('body').on('click', "[data-click='recordElement']", function() {
    var id = $(this).data('id');

    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'true');
    mdlForm.modal('show');
    loadRecord(id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
    }).fail(function(error) {
        handleErrors(error);
    });

});
$('body').on('click', "[data-click='reNumberElement']", function() {
    let id = $(this).data('id');
    let title = $(this).data('title');

    var loading = $('.loading', mdlDelete).first();
    loading.attr('data-show', 'true');
    mdlDelete.modal('show');
    $('.modal-content', mdlDelete).first().html('');
    loadReNumber(id,title).done(function(data) {
        $('.modal-content', mdlDelete).first().html(data.view);
        initRange();
    }).fail(function(error) {
        handleErrors(error);
    });

});
$('body').on('click', "[data-click='reNumberAutomatic']", function() {
    let id = $(this).data('id');
    Swal.fire({
        title: 'Renumerar masivamente',
        text: "¿Estas seguro que deseas renumerar masivamente este elemento? Esta acción será irreversible.",
        icon: 'warning',
        type:'question',
        showCancelButton: true,
        confirmButtonColor: '#009688',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Si, Renumerar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.value) {
            loadReCountAutomatic(id).done(function(data) {
                swal.fire({
                    'title': data.success ? "Correcto" : "Error",
                    'text': data.message,
                    'showConfirmButton': false,
                    'timer': 1500,
                    'type': data.success ? "success" : "error",
                }).then(function() {
                        data.filesExtends.forEach(function(element,index){
                            let SPANCOUNT=$('.spancount'+element.id).first();
                            SPANCOUNT.text(element.num);
        
                        })
                });
            }).fail(function(error) {
                handleErrors(error);
            });
        }
      })
    

});
//enviar formulario para guardar información
$('body').on('click', '[data-click="saveRenumber"]', function(e) {
    e.preventDefault();
    let id = $(this).data('id');
    saveReNumber(id).done(function(data) {
        if (data.success) {
            let SPANCOUNT=$('.spancount'+data.fileExtend.id);
            SPANCOUNT.text(data.fileExtend.num);

            let img=$('.delete'+data.fileExtend.id);
                img.css('height',data.fileExtend.size);
                img.css('width',data.fileExtend.size);

            let deleteB=$('.deleteT'+data.fileExtend.id)
                deleteB.html(`${data.fileExtend.extendible.slug}`);
            
                mdlDelete.modal('hide');
        }
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }).fail(function(error) {
        handleErrors(error);
        showErrors(error.responseJSON.errors, '#mdlDelete')
    });

});

$('body').on('click', '[data-click="saveAssociateQr"]', function(e) {
    e.preventDefault();
    associateQrUpdate().done(function(data) {
       if(data.success){
        mdlDelete.modal('hide');
       }
        swal(data.success ? "Correcto" : "Error", data.message, data.success ? "success" : "error");
    }).fail(function(error) {
        handleErrors(error);
        showErrors(error.responseJSON.errors, '#mdlDelete')
    });

});

$('body').on('click', "#imageCanvas", function(e) {
    var cursorX = e.pageX;
    var cursorY = e.pageY;
    //console.log(cursorX, cursorY);
});

function addCount(element) {
    let element0 = $(element);
    let id = element0.data('id');
        var style = element0.attr('style');
        var spancount = $('<span class="spancount'+id+'" style="' + style + '"></span>').css('top', '-=3%').css('width', '').css('height', 'auto').css('border-radius', '0px').css('height', 'auto').css('color', 'red').css('width', 'auto').css('left','+=1%');
        spancount.text(element0.data('num'));
        $(element).after(spancount);
}

function countText(element, x, y){
    let ELEMENT=$(element);
    let id=$(ELEMENT).data('id');
    let spanCount=$('.spancount'+id).first();
        spanCount.remove();
    var spancount = spanCount.css('top',y+'%').css('left',x+'%').css('top', '-=3%');
    $(ELEMENT).after(spancount);
}
function addText(element, operation=null) {
 
    let element0 = operation == 'update' ? element:element[0];

    console.log(element, $(element0).first().data('id'));
    console.log('-----------------------------------');

    let id = element0.dataset.id;
    let size = element0.dataset.size;
    let num = element0.dataset.num;
    let ELEMENTB=$('.deleteT'+id).first();
    console.log(ELEMENTB);
        ELEMENTB.remove();
        var style = element0.attributes.style.value;
        var xmargin = size /10;
        var ymargin = size /40;
        var spantext = $('<span class="spantext deleteT'+id+'" style="' + style + '">' + element0.dataset.slug + num + '</span>')
            .css('top', '+='+ymargin+'%')
            .css('left', '+='+xmargin+'%')
            .css('border-radius', '0px')
            .css('height', 'auto')
            .css('background-color', 'transparent')
            .css('color', '#000')
            .css('width', 'auto')
            .css('padding', '1px')
            .css('font-size', '0.6rem')
            .css('font-weight', '900');

        $(element).after(spantext);
}
function initRange(){
    $("input[type='range']").change(function() {
        $('#size').text(this.value)
    });
}
function updatePositionMechanism(element, x, y) {
    var $element = $(element);
    //console.log($element.data('id'));
    return $.ajax({
        method: 'get',
        url: ULR + 'crm/mechanism/updatePosition/' + $element.data('id'),
        data: {
            x: x,
            y: y,
        }
    });
}
function loadRecord(id) {
    return $.ajax({
        url: ULR + "crm/record/"+id,
        method: "GET",
        dataType: "json",
    });
}
function loadQr(id) {
    return $.ajax({
        url: ULR + "crm/associateQr/"+id,
        method: "GET",
        dataType: "json",
    });
}
function loadReplace(id) {
    return $.ajax({
        url: ULR + "crm/replaceMechanism/"+id,
        method: "GET",
        dataType: "json",
    });
}
function loadReNumber(id,title) {
    return $.ajax({
        url: ULR + "crm/renumber/"+id,
        method: "GET",
        dataType: "json",
        data:{ title: title}
    });
}
function loadReCountAutomatic(id) {
    return $.ajax({
        url: ULR + "crm/renumberAutomatic/"+id,
        method: "GET",
        data:{office_id:$('#office').val()},
        dataType: "json",
    });
}
function associateQrUpdate() {

    var form = $('#frm-associateQr');
    var data = form.serialize();
    data+='&crm='+$('#crm').val()+"&office="+$('#office').val();
    return $.ajax({
        url: ULR + "crm/associateQrUpdate/update",
        method: 'PUT',
        data: data,
        dataType: "json"
    });
}
function saveReNumber(id) {

    var form = $('#frm-renumber');
    var data = form.serialize();
    return $.ajax({
        url: ULR + "crm/renumber/updateReNumber/"+id,
        method: 'PUT',
        data: data,
        dataType: "json"
    });
}

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
var slider = document.getElementById("customRange");

if (localStorage.getItem('customRange') !== null) {
    slider.value = localStorage.getItem('customRange');
} else {
    localStorage.setItem('customRange', slider.value);
    slider.value = localStorage.getItem('customRange');
}

$(".list-mechanism .convention-md-dot").css('height', localStorage.getItem('customRange')+ 'px').css('width', localStorage.getItem('customRange')+ 'px');

var output = document.getElementById("demo");
output.innerHTML = slider.value;


slider.oninput = function() {
    localStorage.setItem('customRange', this.value);
    output.innerHTML = this.value;
};

function restoreDefaultSize() {
    var slider = document.getElementById("customRange");
    slider.value = 30;
    localStorage.setItem('customRange', 30);
    output.innerHTML = 30;
}