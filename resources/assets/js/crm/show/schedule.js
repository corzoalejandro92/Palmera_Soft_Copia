//cargar la lista de cronograma de actividades
OFFICECUSTOMER = null;

$('body').on('change', '#OfficesCustomer', function(event) {
    OFFICECUSTOMER = $('#OfficesCustomer').val();
});


$('body').on('click', '[data-click="openlistSchedule"]', function(e) {
    METHOD = 'POST';
    $('#FilterOffice_id').val($("[name='OfficesCustomer'").val());
    $('[name="_method"]').val(METHOD);
    var listSchedule = $('#listAdvanceSchedule');
    localStorage.setItem('observable', 'false');
    TAB = '#tabSchedule';

    initCalendar();
    processAvatars();
    if (!IS_CUSTOMER) {
        getGeneralObservation();
    }

    if ($("[name='office_id']").val() == null) {

        $('#buttonsOptionsShedulesDiv').hide();
        $('.filterRangeSchedule').hide();
    } else {
        var pdfHref = $('.buttonPdfSchedule').data('base');
        pdfHref = pdfHref.replace("_crm_", $("[name='crm_id']").val());
        pdfHref = pdfHref.replace("_office_", $("[name='office_id']").val());
        pdfHref = pdfHref.replace("_start_", $("[name='start_date_submit']").val());
        pdfHref = pdfHref.replace("_end_", $("[name='end_date_submit']").val());
        pdfHref += "&visual_type=" + $('[name="visual_type"]:checked').val();
        $('.buttonPdfSchedule').attr('href', pdfHref);
        $("#sendScheduleFilter .btn-block").trigger('click');

        $('#buttonsOptionsShedulesDiv').show();
        $('.filterRangeSchedule').show();

        $('[data-toggle="tooltip"]').tooltip();

        ///añadir propiedad data crm al botón de mail
        var sendMail = $("[data-click='sendSchedule']");
        sendMail.data('crm', $("[name='crm_id']").val());
    }


});

function getGeneralObservation() {
    $.ajax({
        url: ULR + "crm/" + $('[name="crm_id"]').val() + "/" + $('#OfficesCustomer').val() + "/general-observation",
        method: 'get',
        dataType: "json"
    }).done((response) => {
        $("[name='general_observation']").val(response.data);
        if(response.data === '' || response.data === null) {
            $("[name='general_observation']").removeClass('textarea-highlighted');
        }else{
            $("[name='general_observation']").addClass('textarea-highlighted');
        }
    });
}

function delay(callback, ms) {
    var timer = 0;
    return function() {
        var context = this,
            args = arguments;
        clearTimeout(timer);
        timer = setTimeout(function() {
            callback.apply(context, args);
        }, ms || 0);
    };
}


$('body').on('keyup', "[name='general_observation']", delay((e) => {
    SEE_LOADING = false;

    if($("[name='general_observation']").val().trim() === '') {
        $("[name='general_observation']").removeClass('textarea-highlighted');
    }else{
        $("[name='general_observation']").addClass('textarea-highlighted');
    }

    $.ajax({
        url: ULR + "crm/" + $('[name="crm_id"]').val() + "/" + $('#OfficesCustomer').val() + "/general-observation/save",
        method: 'get',
        data: { 'observation': $("[name='general_observation']").val() },
        dataType: "json"
    });
    SEE_LOADING = true;
}, 700));


function loadListSchedule() {


    var form = $('#sendScheduleFilter');
    if (form) {
        var data = form.serialize();
        return $.ajax({
            url: ULR + "crm/" + $('[name="crm_id"]').val() + "/schedule-list",
            method: 'POST',
            data: data,
            dataType: "json"
        });
    }

}

function getSchedulingCalendar(start, end) {
    var form = $('#sendScheduleFilter');
    var data = form.serialize() + "&start=" + start.unix() + "&end=" + end.unix();
    return $.ajax({
        url: ULR + "crm/" + $('[name="crm_id"]').val() + "/schedule-calendar",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });
}

function getSchedulingExternalNotes(start, end) {
    var form = $('#sendScheduleFilter');
    var data = form.serialize() + "&start=" + start.unix() + "&end=" + end.unix();
    return $.ajax({
        url: ULR + "crm/" + $('[name="crm_id"]').val() + "/external-notes",
        method: $('[name="_method"]', form).val(),
        data: data,
        dataType: "json"
    });
}

function initCalendar() {
    $('#agenda').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'agendaWeek,month,year'
        },
        height: 400,
        width: 500,
        contentHeight:"auto",
        defaultView: 'month',
        titleRangeSeparator: " al ",
        defaultDate: (getUrlVars()['date']!= undefined ? getUrlVars()['date'] : moment().format('YYYY-MM-DD')),
        locale: 'es',
        editable: true,
        droppable: true, // this allows things to be dropped onto the calendar
        eventLimit: true, // for all non-agenda views
        eventLimitText: "Ver más",
        timezone: 'America/Bogota',
        showNonCurrentDates: false,
        views: {
            month: {
                eventLimit: 5
            }
        },
        events: function(start, end, timezone, callback) {
            var loading = $('.loading', '#agendaSection').first();
            loading.attr('data-show', 'true');
            var events = [];
            getSchedulingCalendar(start, end).done((doc, ) => {
                $.each(doc.schedules, function(index, event) {

                    var shortname = '<i  class="font-medium-2 ' + event.service.avatar + ' event-name-i"></i>&nbsp;<span class="event-name-span">' + event.service.name + (event.num ? ' ' + event.num : '') + '</span>';
                    var name = shortname + '<div style="margin-top: 8px;">';
                    event.controls.forEach(control => {
                        name += '<img class="avatar-percent" style="border: 1px solid #fff;background-color:#fff;" src="' + (control.infestation ? control.infestation.full_avatar : '') + '" onerror="this.src=\'/images/default/infestation.png\'">';
                    });
                    name += '</div>'

                    if (event.observations.length > 0 && !IS_CUSTOMER) {
                        name += '<div style="margin-top: 8px;font-weight: 500;"><span>';
                        //name += ' // ' + event.observations.length + ' Nota' +(event.observations.length > 1 ? 's': '')+ ' interna'+ (event.observations.length > 1 ? 's': '');
                        event.observations.forEach(observation => {
                            name +=  observation.private ? '<b>' : '';
                            name += ' // ' + observation.observation +(observation.feedback ? ' -[Feedback]: '+observation.feedback :'');
                            name +=  observation.private ? '</b>' : '';
                        });
                        name += '</span></div>';
                    }

                    if (!IS_CUSTOMER) {
                    
                        name += '<div style="float: right;"><span>';
                        name += '<a title="Cambiar estado" data-click="openStatusForm"  href="' + event.id + '"><i class="las la-adjust" style="color: #fff;"></i></a>'
                        name += '</span></div>';
                    
                    }
                    events.push({
                        id: event.id,
                        title: name,
                        shortitle: shortname,
                        description: event.service.name,
                        start: event.start,
                        end: event.end,
                        color: event.status ? (event.status.color ? event.status.color : '#d556ff') : '#d556ff',
                        textColor: event.status ? (event.status.text_color ? event.status.text_color + '!important' : '#fff!important') : '#fff!important',
                        data: event,
                        type: event.observation,
                        incharge: '',
                        crm: event.crm ? event.crm.name : 'Ninguno',
                        isCustomer: doc.isCustomer,
                        isNote: false
                    });
                });

                if (!IS_CUSTOMER) {
                    getSchedulingExternalNotes(start, end).done((doc, ) => {

                        $.each(doc.external_notes, function(index, event) {
                            events.push({
                                id: 'en' + event.id,
                                _id: event.id,
                                title: event.type == '2' ? `Fac: ${event.number}; Valor: ${event.value_format}; Nuevo: ${event.new_service == 1 ? 'Si ' : 'No '}; ${event.observation}` : event.observation,
                                start: event.date,
                                end: event.end,
                                color: event.type == '1' ? '#c8e5f9' : '#fff1aa',
                                textColor: event.type == '1' ? '#33767f!important' : '#8c7d0f!important',
                                isCustomer: doc.isCustomer,
                                isNote: true
                            });
                        });

                        callback(events);

                    }).fail(function(error) {
                        loading.attr('data-show', 'false');
                        handleErrors(error);
                    });
                } else {
                    callback(events);
                }



            }).fail(function(error) {
                loading.attr('data-show', 'false');
                handleErrors(error);
            });



        },
        eventRender: function(event, element) {
            element.find('.fc-title').html(event.title);

            //elaborar una tabla HTML  para visualizar los detalles

            if (!event.isNote) {
                var table = "<div>";

                table += "  <div class='row'><div class='col-md-12'></div>&nbsp;</div>";

                if (!event.isCustomer) {
                    table += "  <div class='row'>";
                    if (event.data.status) {
                        table += "          <div class='col-md-3 '><b>Origen:</b></div>";
                        if(event.data.workschedule){
                            if (event.data.status.slug != 'invoiced' && event.data.status.slug != 'service-fail' && event.data.status.slug != 'cancelled') {
                                table += "          <div class='col-md-9 '><a class='link' href='" + event.data.url_to_edit  + "' target='_blank'>" + event.data.workschedule.text + "</a></div>";
                            } else {
                                table += "          <div class='col-md-9 '>" + event.data.workschedule.text + "</div>";
                            }
                        }else{
                            table += "          <div class='col-md-9 text-danger'>No se puede mostrar el origen porque el cronograma relacionado fue eliminado del sistema.</div>";
                        }
                        
                    } else {
                        table += "          <div class='col-md-9 text-danger'> No se puede cargar el origen porque hay un problema con el estado de este servicio, por favor edite el estado de este servicio para continuar</div>";
                    }
                    table += "  </div>";
                }






                table += "  <div class='row'>";
                table += "          <div class='col-md-3 '><b>Cobertura:</b></div>";
                table += "          <div class='col-md-9 '>" + event.data.coverage.zone + "</div>";
                table += "  </div>";


                table += "  <div class='row'>";
                table += "          <div class='col-md-3 '><b>Duración:</b></div>";
                table += "          <div class='col-md-9 '>" + event.data.time_value + " " + event.data.time_unit.name + "</div>";
                table += "  </div>";

                table += "  <div class='row'>";
                table += "          <div class='col-md-3 '><b>Semana:</b></div>";
                table += "          <div class='col-md-9 '>" + event.data.assigned_week + "</div>";
                table += "  </div>";

                if (event.data.status) {
                    table += "  <div class='row'>";
                    table += "          <div class='col-md-3 '><b>Estado:</b></div>";
                    table += "          <div class='col-md-9 '><div style='background-color:" + event.data.status.color + ";height: 10px;width: 10px;border-radius: 50%;position: absolute;left: 0px;top: 2px;'></div>" + event.data.status.name + "</div>";
                    table += "  </div>";
                }


                if (event.data.start_hour) {

                    table += "  <div class='row'>";
                    table += "          <div class='col-md-3 '><b>Hora:</b></div>";
                    table += "          <div class='col-md-9 '>" + event.data.start_hour + "</div>";
                    table += "  </div>";
                }

                if (event.data.technical_quantity && !event.isCustomer) {

                    table += "  <div class='row'>";
                    table += "          <div class='col-md-3 '><b>Técnicos requeridos:</b></div>";
                    table += "          <div class='col-md-9 '>" + event.data.technical_quantity + "</div>";
                    table += "  </div>";
                }

                if (event.data.technicals) {
                    var technicals = "";
                    event.data.technicals.forEach((technical) => {
                        technicals += technical.name + ", "
                    });

                    table += "  <div class='row'>";
                    table += "          <div class='col-md-3 '><b>Técnico(s) Asignado(s):</b></div>";
                    table += "          <div class='col-md-9 '>" + technicals + "</div>";
                    table += "  </div>";
                }

                if((event.data.status.slug =='init' && event.data.technicals) || (event.data.status.slug == 'programed' && event.data.technicals)){
                    table += "  <div class='row'>";
                    table += "          <div class='col-md-3'></div>";
                    table += "          <div class='col-md-9 '>";
                        event.data.technicals.forEach((user) => {
                            table += "          <button class='btn btn-outline-primary' style='position: relative;' data-click='openFormFileUser' data-user_id='"+user.id+"'>Archivos de "+user.short_name+"</button>";
                        });
                    table += "          </div>";
                    table += "  </div>";
                }

                if (event.data.schedulings.length > 0) {

                    table += "  <div class='row'>";
                    table += "          <div class='col-md-12 '><hr></div>";
                    table += "          <div class='col-md-12 '><h4>Histórico de visitas realizadas</h4></div>";
                    table += "          <div class='col-md-12 '><hr></div>";
                    table += "  </div>";


                    if(event.data.technical_reports && event.data.technical_reports.length > 0){

                        table += "  <div class='row'>";
                        table += "          <div class='col-md-3 '><b>Reporte mecanismos:</b></div>";
                        table += "          <div class='col-md-9'>";
                        table += '              <div class="cursor-pointer" data-click="openReportsModal" data-id="'+event.data.id+'" >';
                        table += '                  <span  class="badge badge-pill badge-glow badge-info">'+event.data.technical_reports.length+'</span> Reporte(s) nuevo(s)<hr>';
                        table += '              </div>';
                        table += "          </div>";
                        table += "  </div>";
                        
                    }  

                    
                    event.data.schedulings.forEach((scheduling) => {
                        var technicals = "";
                        scheduling.routes.forEach((route) => {
                            technicals += route.user.name + ", ";
                        });

                        table += "  <div class='row'>";
                        table += "          <div class='col-md-3 '><b>Fecha:</b></div>";
                        table += "          <div class='col-md-5 '>" + moment(scheduling.start).format("MMM DD, YYYY") + "</div>";
                        table += "  </div>";

                        table += "  <div class='row'>";
                        table += "          <div class='col-md-3 '><b>Técnico(s):</b></div>";
                        table += "          <div class='col-md-5 '>" + technicals + "</div>";
                        table += "  </div>";
                        

                        var st_color = '';
                        var st_text  = '';

                        switch (scheduling.completed){
                            case 0:
                                st_color = '#673ab7';
                                st_text  = 'En trámite';
                                break;
                            case 1:
                                    st_color = '#419644';
                                    st_text  = 'visita realizada';
                                    break;
                            case 2:
                                st_color = '#f44336';
                                st_text  = 'Cancelada antes de realizarse';
                                break;
                            case 3:
                                st_color = '#ff9800';
                                st_text  = 'Interrumpida en la ejecución';
                                break;
                        }

                        table += "  <div class='row'>";
                        table += "          <div class='col-md-3'><b>Estado de visita:</b></div>";
                        table += "          <div class='col-md-5'><div style='background-color:" + st_color + ";height: 10px;width: 10px;border-radius: 50%;position: absolute;left: 0px;top: 2px;'></div>" + st_text + "</div>";
                        table += "  </div>";

                        table += "  <div class='row'>";
                        table += "          <div class='col-md-3'></div>";
                        table += "          <div class='col-md-9 '>";
                            scheduling.routes.forEach((route) => {
                                table += "          <button class='btn btn-outline-primary' style='position: relative;' data-click='openFormFileUser' data-user_id='"+route.user_id+"'>Archivos de "+route.user.short_name+"</button>";
                            });
                        table += "          </div>";
                        table += "  </div>";

                        if(event.data.user_trackings){
                            event.data.user_trackings.forEach((tdata,idx) => {
    
                                    //if(moment(tdata.date).format('YYYY-MM-DD') == moment(scheduling.start).format('YYYY-MM-DD')){
                                        var obs =  tdata.observation ? tdata.observation : 'Fin del servicio';
                                    var hour = tdata.date ? moment(tdata.date, 'DD-MM-YYYY H:m:s').format('hh:mm a') : 'N/A';
        
                                    table += "  <div class='row'>";
                                    table += "          <div class='col-md-3 '><br><br><b>"+(idx == 0 ? 'Seguimiento:': '')+"</b></div>";
                                    table += "          <div class='col-md-9 '><hr>" +hour + ": "+obs+"</div>";
                                    table += "  </div>";
        
                                    if(tdata.user_tracking_type == 4 || tdata.user_tracking_type == 3){
                                        if(tdata.file != null){
                                            table += "  <div class='row'>";
                                            table += "          <div class='col-md-3 '><b>Firma recibido:</b></div>";
                                            table += "          <div class='col-md-9 '><img src='"+tdata.file+"' style='height: 115px;' onerror='this.src=\"/images/default/signature.png\"'><br><b>Recibe:</b> <span>"+tdata.name_receives+"</span><br><b>CC:</b> <span>"+tdata.cc_receives+"</span></div>";
                                            table += "  </div>";
                                        }else{
                                            if(tdata.user_tracking_type == 4){
                                                table += "  <div class='row'>";
                                                table += "          <div class='col-md-3 '><b>Firma recibido:</b></div>";
                                                table += "          <div class='col-md-9 '>Sin firma<hr></div>";
                                                table += "  </div>";
                                            }
                                        }
                                       
                                    }
                                //} 
                                
                            }); 
                        }

                        

                       
                    });

                    
                }

                if(event.data.status.slug == 'service-success'){
                    var rsbtton = "<a type='button' target='_blank' class='btn btn-outline-danger' style='position: relative;' href='/crm/schedule/"+event.data.id+"/report'><i class='lar la-file-pdf'></i>Ver reporte de servicio</a>";
                    var surveysbtton="";
                    if(event.data.answers_surveys.length > 0){
                        event.data.answers_surveys.forEach((element,index) => {
                            if(element.id > 0){
                                surveysbtton = "<a type='button' data-id='"+element.id+"' data-click='openFormResults' class='btn btn-outline-info' style='position: relative;'><i class='las la-list-alt'></i> Encuesta</a>";
                            }
                        });

                    }
                    table += "  <div class='row'>";
                    table += "          <div class='col-md-3 '><br><br><b>Reporte de servicio</b></div>";
                    table += "          <div class='col-md-9 '><hr>" +rsbtton +" "+ surveysbtton +"</div>";
                    table += "  </div>";
                    table += "  <hr>";
                }

                var showInfestationGrade = event.data.status.slug =='service-success' || event.data.status.slug =='route' ? true : false;

                table += "  <div class='row'><div class='col-md-12'></div>&nbsp;</div>";

                table += "  <div class='row rowsimple' >";
                table += "          <div class='col-md-"+(showInfestationGrade ? 2:3)+" popover-div-td'><b>Control</b></div>";
                table += "          <div class='col-md-2 popover-div-td'><b>Areas</b></div>";
                table += "          <div class='col-md-"+(showInfestationGrade ? 2:3)+" popover-div-td'><b>Sustancia</b></div>";
                table += "          <div class='col-md-"+(showInfestationGrade ? 1:2)+" popover-div-td'><b>Dosis</b></div>";
                table += "          <div class='col-md-2 popover-div-td'><b>Alicación</b></div>";
                
                if(event.data.status.slug =='service-success' || event.data.status.slug =='route'){
                    table += "          <div class='col-md-3 popover-div-td'><b>Infestación Reportada</b></div>";
                }
                
                table += "  </div>";

                event.data.controls.forEach((control) => {
                    
                    var infestationGrade = "<span class='mini-badge badge-dark'>Sin reporte</span>";
                    
                    if(control.infestation_grade && control.infestation_grade.length > 0){
                        infestationGrade = "";
                        control.infestation_grade.forEach((ig)=>{
                            let img = "<img class='avatar popover-td-img-mini'  src='" + (ig.pest ? ig.pest.full_avatar : '') + "' onerror=\"this.src='/images/default/infestation.png';\">"
                            switch (ig.value) {
                                case '0':
                                    infestationGrade += "<div>"+img+ig.pest.name+": <span class='mini-badge badge-success'>Nulo</span></div>";
                                    break;
                                case '1':
                                    infestationGrade += "<div>"+img+ig.pest.name+": <span class='mini-badge badge-info'>Bajo</span></div>";
                                    break;
                                case '2':
                                    infestationGrade += "<div>"+img+ig.pest.name+": <span class='mini-badge badge-warning'>Medio</span></div>";
                                    break;
                                case '3':
                                    infestationGrade += "<div>"+img+ig.pest.name+": <span class='mini-badge badge-danger'>Alto</span></div>";
                            
                                default:
                                    break;
                            }
                        });

                        
                    }

                    table += "  <div class='row rowsimple'>";
                    table += "          <div class='col-md-"+(showInfestationGrade ? 2:3)+" popover-div-td'><img class='avatar popover-td-img'  src='" + (control.infestation ? control.infestation.full_avatar : '') + "' onerror=\"this.src='/images/default/infestation.png';\"><span class='popover-td-span'>" + (control.infestation ? control.infestation.name : '') + "</div>";
                    table += "          <div class='col-md-2 popover-div-td'>" + (control.area ? control.area.name : '') + "</div>";
                    table += "          <div class='col-md-"+(showInfestationGrade ? 2:3)+" popover-div-td' style='background:" + (control.product ? (control.product.color ? control.product.color : '#fff') : '') + ";' ><span style='color:" + control.color_text + "'>" + (control.product ? control.product.name : 'No especificado') + "</span></div>";
                    table += "          <div class='col-md-"+(showInfestationGrade ? 1:2)+" popover-div-td'>" + (control.dose != null ? control.dose.text : 'NO APLICA') + "</div>";
                    table += "          <div class='col-md-2 popover-div-td'>" + (control.application ? control.application.name : '') + "</div>";
                    if(event.data.status.slug =='service-success' || event.data.status.slug =='route'){
                        table += "          <div class='col-md-3 popover-div-td' align='left'>" + infestationGrade + "</div>";
                    }
                    table += "  </div>";
                });

                //Notas internas
                if (!event.isCustomer) {
                    if (event.data.observations && event.data.observations.length > 0) {
                        table += "  <div class='row'><div class='col-md-12'></div>&nbsp;</div>";
    
                    table += "  <hr>";

                    table += "  <div class='row'>";
                        table += "      <div class='col-md-3 '><b>Observaciones del servicio:</b></div>";
                            table += "  <div class='col-md-9 '>";
                                table += "  <div class='row rowsimple' >";
                                table += "          <div class='col-md-6 popover-div-td'><b>NOTA INTERNA</b></div>";
                                table += "          <div class='col-md-6 popover-div-td'><b>FEEDBACK</b></div>";
                                table += "  </div>";
                                event.data.observations.forEach((observation) => {
                                    table += "  <div class='row rowsimple' >";
                                        table += "          <div class='col-md-6 popover-div-td'>" + (observation.private ? '<i class="la la-eye-slash"></i> ': '' )+ (observation.observation ? observation.observation : '') + "</div>";
                                        table += "          <div class='col-md-6 popover-div-td'>" + (observation.feedback ? observation.feedback : '') + "</div>";
                                        table += "  </div>";
       
                                });
                            
                            table += "  </div>";
                        table += "  </div>";
                    table += "  <hr>";
                    }
                    

                    
                    
                }


                if (!event.isCustomer) {
                    table += "  <div class='row'><div class='col-md-12'></div>&nbsp;</div>";
                    table += "  <div class='row'>";
                    table += "          <div class='col-md-3 '><b>Observacion Cronograma:</b></div>";
                    table += "          <div class='col-md-9 '>" + (event.data.observation ? event.data.observation : 'Ninguna') + "</div>";
                    table += "  </div>";


                    table += "  <div class='row'><div class='col-md-12'></div>&nbsp;</div>";
                    table += "  <div class='row'>";
                    table += "          <div class='col-md-12 '>";

                    table += "          <a class='btn btn-outline-dark float-left' onclick='closePopover()'>X Cerrar</a>";

                    if (event.data.status != undefined) {
                        if (event.data.status.slug != 'service-success' || event.data.status.slug != 'invoiced') {
                            table += "          <a class='btn btn-success float-right scheduleEdit' data-click='editControl'  href='" + event.data.id + "'>Editar</a>";
                        }
                        if (event.data.status.slug == 'init') {
                            table += "          <a class='btn btn-outline-danger float-left mr-1 scheduleDelete' data-click='editControl'  href='" + event.data.id + "'>Eliminar</a>&nbsp;";
                        }
                    } else {
                        table += "  <p class='float-right text-danger'>No se pueden cargar algunos botones por que hay un problema con el estado de este servicio, por favor edite el estado de este servicio para continuar</p>";
                    }

                    table += "          <a class='btn btn-outline-warning float-right mr-1 scheduleStatus' data-click='openStatusForm'  href='" + event.data.id + "'>Estado</a>&nbsp;";
                    table += "          <a class='btn btn-outline-info float-right mr-1 scheduleObservations' data-click='openObservationForm'  href='" + event.data.id + "'>Notas Internas</a>&nbsp;";
                    table += "          </div>";
                    table += "  </div>";



                    table += "</div></div>";
                } else {
                    table += "<br><br><br>";
                }



                var html = $(table).html();
                var close = $('<button><span aria-hidden="true" class="close">&times;</span></button>');


                element.popover({
                        class: 'controlPopover',
                        html: true,
                        sanitize: false,
                        title: event.shortitle + close.html(),
                        content: html,
                        trigger: 'click',
                        placement: 'top',
                        container: 'body'
                    }).on('show.bs.popover', function() {
                        if (window._bsPopover) {
                            $(window._bsPopover).popover('hide');
                        }
                        window._bsPopover = this;
                    })
                    .on('hide.bs.popover', function() {
                        window._bsPopover = null; // see Peter Jacoby's comment
                    });
            } else {
                element.find('.fc-title').parent().addClass('externalNoteNew');
                element.find('.fc-title').parent().attr('href', event._id);
            }



        },
        // dayClick: function(info) {
        //     scheduleNew(null, info);

        // },
        timeFormat: 'hh:mm a',
        drop: function() {
            // is the "remove after drop" checkbox checked?
            //if ($('#drop-remove').is(':checked')) {
            // if so, remove the element from the "Draggable Events" list
            //$(this).remove();
            //}
        },
        eventDrop: function(event) {
            $(this).focus();
            if(event.isNote){
                moveNote(event._id, event.description, event.start.unix());
            }else{
                if (event.data.status.slug != 'init' && event.data.status.slug != 'programed') {
                    swal('Ya no puedes mover este evento porque se encuentra "' + event.data.status.name + '"', 'Movimiento no permitido', 'info');
                    $("#agenda").fullCalendar('refetchEvents');
                } else {
                    moveEventRN(event.id, event.description, event.start.unix());
                }
            }
            

        },
        eventAfterAllRender: function(view) {
            $(".fc-content-skeleton table tfoot").remove();
            var loading = $('.loading', '#agendaSection').first();
            loading.attr('data-show', 'false');
            $('.fc-day').html('');
            if (view.name == 'month' && !IS_CUSTOMER) {
                $('.fc-day').each(function() {
                    var date = $(this).data('date');
                    $(this).css('position', 'relative');
                    if (!$(this).hasClass('fc-disabled-day')) {
                        $(this).append("<span style='color:#4caf50;font-size: 14px;' class='dayAddButton btn btn-default btn-sm scheduleNew' title='Agregar refuerzo/control' data-date='" + date + "'>(+)</span>");
                        $(this).append("<span style='color:#2196f3; margin-left: 25px;font-size: 17px;' class='dayAddButton btn btn-default btn-sm externalNoteNew' title='Notas Externas' data-date='" + date + "'><i class='lar la-comment-dots'></i></span>");
                        $(this).append("<span style='color:#ffc107; margin-left: 45px;font-size: 18px;' class='dayAddButton btn btn-default btn-sm externalNoteInvoiceNew' title='Notas de facturación' data-date='" + date + "'><i class='las la-comments-dollar'></i></span>");
                        //$(this).append("<span style=' margin-left: 65px;font-size: 18px;' class='dayAddButton btn btn-default btn-sm' data-click='openStatusForm'  title='Cambiar estado' data-date='" + date + "'><i class='las la-exchange-alt'></i></span>");
                    }

                });
            }
            $(".dayAddButton").css({ 'padding': '0.1rem 0', 'cursor': 'pointer', 'position': 'absolute', 'bottom': '0px', 'margin-top': '10px', 'display': 'inline-block', 'z-index': '200000' });
            $(".fc-content-skeleton table").append('<tfoot><tr><td>&nbsp;</td></tr><tfoot>');
        },
        dayRender: function(info, el) {
            //console.log(info, el);
            //el[0].innerHTML += "<span class='dayAddButton btn btn-default btn-sm scheduleNew' title='Agregar refuerzo/control'>(+)</span>";
            //$(".dayAddButton").css({ 'padding': '0.1rem 0', 'cursor': 'pointer', 'color': '#009688', 'position': 'absolute', 'bottom': 0px });
            //el[0].style.padding = "20px 0 0 10px";
        }
    });
    // $(function() {
    //     $.contextMenu({
    //         selector: '.fc-event',
    //         items: {
    //             "edit": {
    //                 name: "Eliminar evento",
    //                 icon: "fa-trash-alt",
    //                 callback: function(key, options) {
    //                     $('#editService').modal('show');
    //                 },
    //             },
    //         }
    //     });
    // });
    $(".select_month").remove();
    $(".select_year").remove();
    $(".select_month_br").remove();
    $(".select_year_br").remove();

    var date = $("#agenda").fullCalendar('getDate');
    var year = moment($("#agenda").fullCalendar('getDate')['_d']).add(1, 'days').format('YYYY');
    var month = moment($("#agenda").fullCalendar('getDate')['_d']).add(1, 'days').format('M') -1;

    $(".fc-left").append('<br class="select_month_br"><select class="select_month form-control" style="margin-top: 5px;margin-left: 0px;width: 212px;"><option value="">Seleccione mes</option><option value=0>Enero</option><option value=1>Febrero</option><option value=2>Marzo</option><option value=3>Abril</option><option value=4>Mayo</option><option value=5>Junio</option><option value=6>Julio</option><option value=7>Agosto</option><option value=8>Septiembre</option><option value=9>Octubre</option><option value=10>Noviembre</option><option value=11>Diciembre</option></select>');
    $(".fc-right").append('<br class="select_year_br"><select class="select_year form-control" style="margin-top: 5px;margin-left: 0px;width: 212px;"><option value="2019">2019</option><option value="2020">2020</option><option value="2021">2021</option><option value="2022">2022</option><option value="2023">2023</option><option value="2024">2024</option><option value="2025">2025</option><option value="2026">2026</option></select>');

    $('.select_year option[value="' + year + '"]').attr('selected', 'selected');
    $('.select_month option[value="' + month + '"]').attr('selected', 'selected');

    $(".select_month").on("change", function(event) {
        $('#agenda').fullCalendar('gotoDate', $(".select_year").val() + "-" + (parseInt(this.value) + 1) + "-15");

    });
    $(".select_year").on("change", function(event) {
        $('#agenda').fullCalendar('gotoDate', this.value + "-" + (parseInt($(".select_month").val()) + 1) + "-15");

    });

    $('body').on('click', '.fc-next-button, .fc-prev-button', function(e) {

        var date = $("#agenda").fullCalendar('getDate');
        var year = moment($("#agenda").fullCalendar('getDate')['_d']).add(1, 'days').format('YYYY');
        var month = moment($("#agenda").fullCalendar('getDate')['_d']).add(1, 'days').format('M') -1;

        $(".select_year option:selected").removeAttr("selected");
        $(".select_month option:selected").removeAttr("selected");
        $('.select_year option[value="' + year + '"]').attr('selected', 'selected');
        $('.select_month option[value="' + month + '"]').attr('selected', 'selected');
    })
}

//hacer click en la tab cuando el observable lea esta variable
var tabScheduleObservable = true;

var collapseObserver = new MutationObserver(function(mutations, me) {
    console.log('waiting collapse element...');
    if (typeof $('.component-list_body') != "undefined") {
        $('body').on('hidden.bs.collapse','.component-list_body', function() {
            if (this.id) {
                localStorage[this.id] = 'true';
            }
        }).on('shown.bs.collapse','.component-list_body', function() {
            if (this.id) {
                localStorage.removeItem(this.id);
            }
        }).each(function() {
            console.log(this.id, localStorage[this.id],$(this));
            if (this.id && localStorage[this.id] === 'true' ) {
                $(this).collapse('hide');
            }
        });
        
        me.disconnect();
        console.log('desconecting observable collapse element');
    }
    
});

// start observing
collapseObserver.observe(document, {
    childList: true,
    subtree: true
});

function closePopover(){
    if (window._bsPopover) {
        $(window._bsPopover).popover('hide');
    }
}