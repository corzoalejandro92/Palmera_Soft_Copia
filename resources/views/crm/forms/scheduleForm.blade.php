<?php 
use Carbon\Carbon;
use Jenssegers\Date\Date;

?>
<div class="modal-header">
    <h5 class="modal-title text-uppercase">Generar eventos de cronograma.</h5>
    {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button> --}}
</div>

<div class="modal-body">
    <form id="supplies">
        {{ method_field('POST') }}
        {{ csrf_field() }}
        
        <input type="hidden" value="{{ $workschedule->id ?? '' }}" name="workschedule_id" />
        <input type="hidden" value="{{ $crm->id ?? '' }}" name="crm_id" />
        <input type="hidden" value="{{ $office->id ?? '' }}" name="office_id" />

        @if($fromQuote)
            <input type="hidden" value="{{$quoteId}}" name="quote_id" />
        @endif
        <input type="hidden" value="{{$key}}" name="key" />
        <div style="width: 95%;margin-left: 2.5%;">
            <div class="col-12">
                <div class="details">
                    <hr>
                    <div>
                        <div class="row">
                            <div class="col-3">
                                <h6>Cliente</h6>
                                <img class="avatar xxs" src="<?= url($crm->avatar ?? '')?>" onerror="this.src='{{url("images/default/customer.png")}}'" /> <span>&nbsp;<?= $crm->name ?></span>
                            </div>
                            <div class="col-3">
                                <h6>Sucursal</h6>
                                <img class="avatar xxs" src="{{url("images/default/office.png")}}" style="display: inline-block;" /> <span>&nbsp;<?= $office->name ?></span>
                            </div>
                            @if(isset($quote))
                            <div class="col-3">
                                <h6>Cotización</h6>
                                <img class="avatar xxs" src="{{url("images/default/control.png")}}" style="display: inline-block;vertical-align: top;"/> 
                                <div style="display: inline-block;">
                                    <span>&nbsp;Cotización #<?= $quote->code ?></span>
                                    <br><span>&nbsp;<?= $quote->commercial->name ?></span>
                                </div>
                                
                            </div>
                            @endif
                        </div>
                    </div>
                    <hr>
                </div>
            </div>
            @if(isset($from))
                @if($isEdit)
                <div class="alert alert-warning" role="alert">
                    <strong>Estás editando un cronograma!</strong>  Queremos hacerte saber que estás realizando una modifcación a un origen existente, si tu intención es crear uno nuevo,  <a class="btn btn-warning" href="{{route('crm.create-workschedule', [$crm->id, $office->id])}}">haz clic aquí.</a>
                  </div>
                <div class="col-4">
                    <label class="col-form-label">Modificar origen a partir de: @new(['date' => '2022-02-25', 'title' => 'Modificar origen a partir de', 'text' => 'Este campo te permitirá realizar cambios en el cronograma a partir de una fecha, útil para realizar variaciones en el origen en fechas futuras, extender cronogramas sin alterar la rotación del ingrediente activo y tener un mayor control de las modificaciones. Si vas a crear un cronograma desde cero no es necesario utilizar o diligenciar este campo.']) </label>
                    <input type="text" class="form-control pickadate-translations" data-change="hiden-error" placeholder="Fecha" name="from" placeholder="Ingrese una fecha" readonly="" value="{{ucwords(Date::parse($from)->format('d F, Y') )}}">
                </div>
                @endif
            @endif

            <hr>
           
            <ul class="nav nav-tabs nav-tabs-schedule">
                @if((isset($services) && $services->count() > 0))
                
                    @php $count = 0; @endphp
                    @foreach($services as $service)
                        <li class="nav-item serviceControls-{{$service->key}}">
                            
                            <a class="nav-link nav-link-schedule {{$count == 0 ? 'active':''}}" id="baseIcon-tab1" data-toggle="tab" aria-controls="tabIcon1" href="#tab{{$service->key ?? ''}}" aria-expanded="true" data-id="{{$service->id ?? ''}}" data-schedule_service_id="{{isset($service->service) ? $service->service_id : ''}}" data-click="setService">
                                <i data-toggle="tooltip" data-placement="top" title="" class="icon-{{$service->service->avatar ?? ''}}" ></i>&nbsp;
                                {{$service->service->name ?? ''}} {{$service->num ?? ''}}
                                @if(!isset($date))
                                 <i class="las la-times-circle la-3x float-right" data-click="{{$service->from_session ? 'removeServiceFromSession':'removeService'}}" data-id="{{ $service->key ?? '' }}"></i>
                                @endif
                            </a>

                        </li>
                        @php $count++; @endphp
                    @endforeach
                @endif

                @if(!isset($date))

                <li class="nav-item">
                    <a data-click="addNewService" 
                        class="nav-link"><i class="ft-plus-circle"></i>Agregar Servicio</a>
                </li>
                <li class="nav-item">
                    <a data-click="addNewServiceFromQuote" 
                        class="nav-link"><i class="ft-plus-circle"></i> Importar de una cotización</a>
                </li>
                <li class="nav-item">
                    <a data-click="addNewServiceFromOffice" 
                        class="nav-link"><i class="ft-plus-circle"></i> Copiar de una sucursal</a>
                </li>
                @endif
            </ul>
            <hr>
     
            <div class="tab-content tab-content-schedule px-1 pt-3 underTabContent ">
                @if(isset($services) && $services->count() > 0)
                    @php $count = 0; @endphp
                    @foreach($services as $service)
                    <div role="tabpanel" class="tab-pane tab-pane-schedule {{$count == 0 ? 'active':''}}" id="tab{{$service->key ?? ''}}" aria-expanded="true" aria-labelledby="baseIcon-tab4">
                        @if(!is_null($service))
                            <input type='hidden' name='services[{{$service->id}}][id]' value="{{$service->id ?? ''}}" />
                            <input type='hidden' name='services[{{$service->id}}][service_id]' value="{{$service->service->id}}" />
                        @endif

                        @if(!is_null($service) && $service->genuine_service)
                            <div class="alert alert-warning" role="alert">
                                <b>Algunos campos no son modificables:</b> Este servicio tiene algunos campos bloqueados debido 
                                a que poseen valores definidos en la cotización y al cambiarlos alterarán los términos inicialmente 
                                acordados, si requiere modificar algún campo bloqueado debes modificar la cotización primero.
                            </div>
                        @endif

                        @if($service->params)
                            <hr>
                                <div class="alert alert-primary" role="alert">
                                    <p><i class="la la-info-circle"></i> Este servicio heredará la siguiente configuración definida en la pestaña "Técnicos X Servicios":</p>
                                    <table class="table table-bordered" style="color:#fff;">
                                        <thead>
                                        <thead>
                                            <tr>
                                                <th>Hora Fija</th>
                                                <th>Estado inicial</th>
                                                <th>Cantidad técnicos</th>
                                                <th>Técnico(s) asignado(s)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{$service->params->time ?? 'A convenir'}}</td>
                                                <td>{{\App\CrmScheduleStatus::find($service->params->init_status)->name}}</td>
                                                <td>{{$service->params->technical_quantity}}</td>
                                                <td>{{$service->params->technicals ? \App\User::find($service->params->technicals)->implode('name',', ') : 'Aleatorio'}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <p> Para descartar o realizar una excepción a esta configuración marque/desmarque a continuación las opciones que desea mantener:</p>
                                    <div class="form-check">
                                        <input class="form-check-input" name="services[{{ $service->id }}][keep_config][]" type="checkbox" value="time" id="{{$service->id}}configtime" checked>
                                        <input  type="hidden" name="services[{{ $service->id }}][config][time]"  value="{{$service->params->time ?? ''}}">
                                        <label class="form-check-label" for="{{$service->id}}configtime" style="color:#fff;">
                                            Hora Fija
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" name="services[{{ $service->id }}][keep_config][]" type="checkbox" value="status" id="{{$service->id}}configstatus" checked>
                                        <input  type="hidden" name="services[{{ $service->id }}][config][status]" value="{{$service->params->init_status ?? ''}}">
                                        <label class="form-check-label" for="{{$service->id}}configstatus" style="color:#fff;">
                                            Estado inicial
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" name="services[{{ $service->id }}][keep_config][]" type="checkbox" value="quantity" id="{{$service->id}}configquantity" checked>
                                        <input  type="hidden" name="services[{{ $service->id }}][config][quantity]" value="{{$service->params->technical_quantity ?? ''}}">
                                        <label class="form-check-label" for="{{$service->id}}configquantity" style="color:#fff;">
                                            Cantidad técnicos
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" name="services[{{ $service->id }}][keep_config][]" type="checkbox" value="technicals" id="{{$service->id}}configtechinicals" checked>
                                        @if($service->params->technicals)
                                            @foreach ($service->params->technicals as $technicals)
                                            <input  type="hidden" name="services[{{ $service->id }}][config][technicals][]" value="{{$technicals}}">
                                            @endforeach
                                        @else
                                            <input  type="hidden" name="services[{{ $service->id }}][config][technicals]" value="">
                                        @endif
                                        
                                        <label class="form-check-label" for="{{$service->id}}configtechinicals" style="color:#fff;">
                                            Técnicos asignados
                                        </label>
                                    </div>

                              </div>
                            <hr>
                        @endif
                        <div>
                            @if(isset($service->observation))
                                <div class="alert alert-info" role="alert">
                                    OBS INTERNA: {{ $service->observation}}
                                </div>
                            @endif
                            @if(isset($service->public_observation))
                                <div class="alert alert-info" role="alert">
                                    OBS CLIENTE: {{ $service->public_observation}}
                                </div>
                            @endif
                            <h4>Establecer configuración personalizada Técnicos X Servicio (Opcional):</h4>
                            <p><i class="la la-info-circle"></i> Si estableces una configuración personalizada, el sistema tomará estos datos y omitirá la configuración que exista en la pestaña "Técnicos X Servicios" para este servicio:</p>
                            <div class="form-group row mt-2">
                                <div class="col-2">
                                        <label class="col-form-label">Hora Fija</label>
                                        <input type="time"  class="form-control" data-change="hiden-error" name="services[{{$service->id}}][start_hour]" value="{{$service->start_hour ?? ''}}" >
                                    </div>
                                <div class="col-2">
                                    <label class="col-form-label">Estado Inicial</label>
                                    <select class="form-control select2" data-change="hiden-error" name="services[{{ $service->id }}][init_status]" data-service-model="{{ $service->service_id}}" data-service = "{{$service->id }}" data-placeholder="Cobertura" autocomplete="off">
                                        @if (isset($service->init_status))
                                            <option value="<?= $service->init_status->id; ?>" selected ><?= $service->init_status->name; ?></option>
                                        @endif
                                    </select>
                                </div>
                                <div class="col-2">
                                    <label class="col-form-label">Cantidad de técnicos</label>
                                    <input type="number" class="form-control" data-change="hiden-error"  name="services[{{ $service->id }}][technical_quantity]"  value="{{$service->technical_quantity ?? ''}}">
                                </div>
                                <div class="col-4">
                                    <label class="col-form-label">Técnicos asignados</label>
                                    <select class="form-control select2" data-change="hiden-error" name="services[{{ $service->id }}][user][]" data-service-model="{{ $service->service_id}}" data-service = "{{$service->id }}" data-placeholder="Cobertura" autocomplete="off" multiple>
                                        @if (isset($service->users))
                                        @foreach ($service->users as $technical)
                                        <option value="<?= $technical->id; ?>" selected ><?= $technical->name; ?></option>
                                        @endforeach
                                            
                                        @endif
                                    </select>
                                </div>
                                <div class="col-12">
                                    <br>
                                 </div>
                                <div class="col-12 addToRouteDiv">

                                </div>
                            </div>
                        </div>
                        <hr>

                        <h4 class="opaque">Cobertura establecida</h4>

                        <div class="form-group row mt-2">
                            <div class="col-2">
                                <label class="col-form-label">Cobertura*</label>
                                <select class="form-control select2" data-change="hiden-error" name="services[{{ $service->id }}][detail_service_id]" data-service-model="{{ $service->service_id}}" data-service = "{{$service->id }}" data-placeholder="Cobertura" autocomplete="off">
                                    @if (isset($service->coverage))
                                        <option value="<?= $service->coverage->id; ?>" selected ><?= $service->coverage->zone; ?></option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-2">
                                <label class="col-form-label">Precio*</label>
                                <input type="text"  class="form-control text-left"  data-keyup="hiden-error" data-format="1" placeholder="Precio" name="services[{{$service->id}}][price]" value="{{$service->price ? number_format($service->price,0,'.',','): 0}}" >
                            </div>

                            <div class="col-2">
                                <label class="col-form-label">Periodicidad</label>

                                <select class="form-control select2" data-change="hiden-error" name="services[{{ $service->id }}][periodicity_id]" data-service="{{ $service->service_id}}" data-serviceid="{{$service->id}}"  data-placeholder="Cliente" autocomplete="off">
                                    @if ($service->periodicity)
                                        <option value="<?= $service->periodicity->id; ?>" selected ><?= $service->periodicity->name; ?></option>
                                    @endif
                                    @if(isset($date))
                                    <option value="13" selected >Sin Periodicidad</option>
                                    @endif
                                </select>
                            </div>

                            <div class="col-2">
                                <label class="col-form-label">Inicio periodo</label>
                                <input type="text" class="form-control pickadate-translations" data-change="hiden-error" placeholder="Fecha" name="services[{{ $service->id }}][start_date]" placeholder="Ingrese una fecha" readonly="" value="{{ucwords(isset($service->periodicity_start) ?  Date::parse($service->periodicity_start)->format('d F, Y') : (isset($date) ? Date::parse($date)->format('d F, Y') : Date::now()->format('d F, Y')))}}">
                            </div>

                            <div class="col-2">
                                <label class="col-form-label">Fin periodo</label>
                                <input type="text" class="form-control pickadate-translations" data-change="hiden-error" placeholder="Fecha" name="services[{{ $service->id }}][end_date]" placeholder="Ingrese una fecha" readonly="" value="{{ucwords(isset($service->periodicity_end) ?  Date::parse($service->periodicity_end)->format('d F, Y') : (isset($date) ? Date::parse($date)->format('d F, Y') : Date::now()->addYear()->format('d F, Y')))}}">
                            </div>

                            <div class="col-2">
                                <label class="col-form-label">Inicio controles</label>
                                <input type="text" class="form-control start-week"  data-serviceid="{{$service->id}}" data-change="hiden-error" placeholder="Fecha" name="services[{{ $service->id }}][start_week]" placeholder="Ingrese una fecha" readonly="" value="{{ucwords(isset($service->week_start) ?  Date::parse($service->week_start)->format('d F, Y') : (isset($date) ? Date::parse($date)->format('d F, Y') : Date::now()->format('d F, Y')))}}">
                            </div>

                            <div class="col-2">
                                <label class="col-form-label">Visitas</label>
                                <input type="number" class="form-control" data-change="hiden-error" placeholder="cant. visitas" name="services[{{ $service->id }}][visits]" placeholder="Ingrese una fecha" value="{{$service->visits ?? 1}}">
                            </div>

                            <div class="col-2">
                                <label class="col-form-label">Duración</label>
                               
                                <div style="display: flex;" class="inputs-group">
                                    <input type="number" style="padding: 10px;" class="form-control cant"  name="services[{{$service->id}}][time_value]" placeholder="Cantidad" data-keyup="hiden-error" value="<?=$service->time_value ?? (isset($date) ? '1':'0') ?>"/>
                                    <select class="form-control select2"  style="padding: 0px;"  data-service="{{$service->id}}" data-change="hiden-error" name="services[{{$service->id}}][time_unit]" data-placeholder="Cliente" data-key="{{$key}}" autocomplete="off">
                                        @if(isset($timeUnits))
                                            @foreach ($timeUnits as $tieUnit)
                                                <option value="{{ $tieUnit->id }}" {{(isset($service) && $service->time_unit && $service->time_unit == $tieUnit->id ? 'selected':'')}} {{isset($date) && $tieUnit->id == 2 ? 'selected': ''}} >{{ $tieUnit->name}}</option>
                        
                                            @endforeach
                                        @endif
                                    </select>
                                   
                                </div>
                            </div>

                            <div class="col-2">
                                <label class="col-form-label">Fijar día</label>
                                @php
                                if(isset($date)){
                                    $dayWeek = Carbon::parse($date)->dayOfWeek - 1;
                                    $dayWeek = $dayWeek == -1 ? 6 : $dayWeek;
                                }
                                @endphp

                                <select class="form-control select2" data-change="hiden-error" name="services[{{ $service->id }}][start_day]" data-service="{{ $service->service_id}}" data-serviceid="{{$service->id}}"  data-placeholder="Cliente" autocomplete="off">
                                    <option value="0" {{$service->day == 0 ? 'selected':''}} {{isset($date )&& $dayWeek == 0 ? 'selected': ''}}> Lunes</option>
                                    <option value="1" {{$service->day == 1 ? 'selected':''}} {{isset($date) && $dayWeek == 1 ? 'selected': ''}}>Martes</option>
                                    <option value="2" {{$service->day == 2 ? 'selected':''}} {{isset($date) && $dayWeek == 2 ? 'selected': ''}}>Miércoles</option>
                                    <option value="3" {{$service->day == 3 ? 'selected':''}} {{isset($date) && $dayWeek == 3 ? 'selected': ''}}>Jueves</option>
                                    <option value="4" {{$service->day == 4 ? 'selected':''}} {{isset($date) && $dayWeek == 4 ? 'selected': ''}}>Viernes</option>
                                    <option value="5" {{$service->day == 5 ? 'selected':''}} {{isset($date) && $dayWeek == 5 ? 'selected': ''}}>Sabado</option>
                                    <option value="6" {{$service->day == 6 ? 'selected':''}} {{isset($date) && $dayWeek == 6 ? 'selected': ''}}>Domingo</option>
                                </select>
                            </div>
                            <div class="col-2">
                            
                                <label class="col-form-label">Numero de servicio</label>
                                <input type="number" class="form-control" data-change="hiden-error" placeholder="sin numeración" name="services[{{ $service->id }}][num]"  value="{{$service->num ?? '1'}}">
                            </div>

                            <div class="col-4">
                                <label class="col-form-label">Opciones</label><br>
                                <label class="checkbox-inline">
                                    <input type="checkbox" value="1" name="services[{{ $service->id }}][enable_fix_weeks]" {{$service->enable_fix_weeks ? 'checked' : ''}} {{isset($date) ? 'checked': ''}}> Habilitar funcion de reacomodar semanas 
                                </label><i class="la la-info-circle" style="cursor: help" data-click="showinfo"  data-title="Habilitar funcion de reacomodar semanas" data-text="Permite que el sitema tome los meses de cinco semanas para acomordar un cronograma que no inicia desde la primer semana del mes, pero se requiere que se acomoden automáticamente hacia la primer semana del mes. (Periodicidad: MENSUAL, Semanas: 4)"></i><br>
                            </div>
                    

                           
                            {{-- <div class="col-12">
                                <label class="col-form-label">controles</label>
                                <select class="form-control select2" data-service="{{$service->id}}" data-serviceid="{{$service->id}}" data-change="hiden-error" name="services[{{ $service->id }}][controls][]" data-placeholder="Cliente" autocomplete="off" multiple>
                                    @if($service->controls)
                                        @foreach ($service->controls as $control)
                                            <option value="{{$control->infestation->id}}" selected>{{$control->infestation->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div> --}}
                            
                            <div class="col-12">
                                <label class="col-form-label">Semanas a programar</label>
                 
                                <select class="form-control select2" data-service="{{$service->service->id}}" data-serviceid="{{$service->id}}" data-change="hiden-error" name="services[{{ $service->id }}][weeks][]" data-placeholder="Cliente" autocomplete="off" multiple>
                                    @if($service->current_weeks)
                                        @foreach ($service->current_weeks as $week)
                                            <option value="{{$week->id}}" {{ isset($service->weeks) ? (in_array($week->id, $service->weeks) ? 'selected': '') : 'selected'}}>{{$week->text}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            
                            <div class="col-12">
                                <div class="form-check" align="right">
                                    <input class="form-check-input" type="checkbox" value=1 {{isset($service->current_weeks) ? 'checked':''}} data-serviceid="{{$service->id}}" data-click="checkAllWeeks" id="services{{ $service->id }}weeks">
                                    <label class="form-check-label" for="services{{ $service->id }}weeks">
                                      Seleccionar todas las semanas
                                    </label>
                                  </div>
                            </div>
                            <div class="col-12"><hr></div>

                        <!--- Ajsute Agosto 2/2020
                             aqui se incluyen los multiples formularios de los controles 
                        -->
                        <div id="weekFramesSection{{$service->id}}" style="width: 100%;">
                            @if($service->current_weeks)
                                @php $control = null; @endphp
                                @foreach($service->current_weeks as $week)
                                    @if(isset($service->weeks))
                                        @if(in_array($week->id,$service->weeks))
                                            @include('crm.forms.frames.week',compact(['control','service','timeUnits','week']))
                                        @endif
                                    @else
                                        @include('crm.forms.frames.week',compact(['control','service','timeUnits','week']))
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                        @php $count++; @endphp
                @endforeach
                @endif
            </div>


        </div>

    </form>
</div>

<div class="modal-footer">
    @if($isEdit)
        <button type="button" data-id="{{$workschedule->id ?? ''}}" class="btn btn-outline-danger round scheduleFullDelete"  style="position: absolute;left: 18px;" >Eliminar cronograma completo</button>
        @if($isEdit) <button type="button" class="btn btn-warning round" style="position: absolute;left: 278px;" data-click="saveSupplies" data-action="force" data-type="edit">Regenerar nuevamente</button> @endif
    @endif
    <button type="button" class="btn btn-outline-secondary round close-modal" >Cerrar</button>
    @if($isEdit)<button type="button" class="btn btn-success round" data-click="saveSupplies" data-action="soft">{{$isEdit ? 'Editar': 'Generar'}}</button>@endif
    @if(!$isEdit) <button type="button" class="btn btn-success round" data-click="saveSupplies" data-action="force" data-type="create">Crear</button> @endif
</div>

<style>
    .select2-selection__choice {
        width: auto!important
    }
</style>