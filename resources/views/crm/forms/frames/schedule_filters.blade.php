<?php 
use  App\General\DateTranslator;
$startDate =  new Date(date('Y-m-01'));
$endDate =  new Date(Carbon\Carbon::now()->addYears(1)->format('Y-m-t'));
?>
<div class="col-md-12 ">
    <div class="card" style='padding-bottom: 0px;'>
        <div class="card-content collapse show">
            <div class="card-body" style='padding-bottom: 0px;'>

                <div class="row">
                    <div class="col-md-12 ">
                        <div class="component-list" >
                            <div class="component-list_header" style="cursor: pointer;">
                              <strong class="list-group-header" data-toggle="collapse" data-target="#project" aria-expanded="true">
                                <span class="dropdown-toggle"></span>
                                <i class="mdi mdi-domain"></i> Filtros @can('crms.info.admin') y opciones @endcan
                                <hr>
                              </strong>
                            </div>
                            <div id="project" class="component-list_body collapse">
                                <form id="sendScheduleFilter" action="#">
                                    {{ method_field('POST') }} 
                                    {{ csrf_field() }}
                                    <input type="hidden" name="office_id" id="FilterOffice_id" />
            
                                            <!-- <div class="col-md-4">
                                                <h6>Sucursal del cliente</h6>
                                                <fieldset class="form-group position-relative">
                                                    <select class="form-control" name="office_id">
            
                                                    </select>
                                                </fieldset>
                                            </div> -->
                                        <div class="row">
                                        
                                            <div class="col-md-2">
                                                <h6>Tipo de visualización</h6>
                                                <fieldset class="form-group position-relative" style="line-height: 40px;">
                                                    <div class="form-check-inline">
                                                        <label class="form-check-label">
                                                          <input type="radio" class="form-check-input" name="visual_type" value="week">Semana
                                                        </label>
                                                      </div>
                                                      <div class="form-check-inline">
                                                        <label class="form-check-label">
                                                          <input type="radio" class="form-check-input" name="visual_type" value="day" checked>Dia
                                                        </label>
                                                      </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-2">
                                                <h6>&nbsp;</h6>
                                                <button class="btn btn-secondary btn-block">Filtrar</button>
                                            </div>
                                 
                                            <div class="col-md-2 filterRangeSchedule" style="display:none;">
                                                <h6>Rango a exportar</h6>
                                                <fieldset class="form-group position-relative">
                                                    <input type="text" id="picker_from" class="form-control pickadate-range"  name="start_date" placeholder="Ingrese una fecha" readonly="" value="{{ucwords($startDate->format('d F, Y'))}}">
                                                </fieldset>
                                            </div>
                                            <div class="col-md-2 filterRangeSchedule" style="display: none;">
                                                <h6>&nbsp;</h6>
                                                <fieldset class="form-group position-relative">
                                                    <input type="text" id="picker_to" class="form-control pickadate-range"  name="end_date" placeholder="Ingrese una fecha" readonly="" value="{{ucwords($endDate->format('d F, Y'))}}">
                                                </fieldset>
                                            </div>
                                            <div id='buttonsOptionsShedulesDiv' style="position: relative;top: 23px; display:none;">
                                                @can('crms.schedule-create')
                                                    <button class="btn btn-outline-warning buttonAddSchedule" data-click="openFormSchedule"  data-toggle="tooltip" data-placement="top" title="Añadir Servicio"><i class="ft-plus"></i></button>
                                                @endcan
                                                @can('crms.schedule-send')
                                                    <button class="btn btn-outline-info" data-click="sendSchedule"  data-toggle="tooltip" data-placement="top" title="Enviar al cliente"><i class="ft-mail"></i></button>
                                                @endcan
                                                    <a class="btn btn-outline-danger buttonPdfSchedule" href="{{route('crm.schedulePdf',["crm" => '_crm_', "start" =>'_start_', "end"=>'_end_', 'office_id' => '_office_'])}}" data-base="{{route('crm.schedulePdf',["crm" => '_crm_', "start" =>'_start_', "end"=>'_end_', 'office_id' => '_office_'])}}" target="_blank" data-toggle="tooltip" data-placement="top" title="Descargar PDF">PDF</a>
                                            </div>
                                    </form>
                            </div>
                          </div>
                        
                    </div>
                </div>
                @can('crms.info.admin')
                    <div class="col-12">
                        <label>NOTA GENERAL DEL CRONOGRAMA</label>
                        <textarea class="form-control" name="general_observation" placeholder="Nota general de cronograma"></textarea>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
