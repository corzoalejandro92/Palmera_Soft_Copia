<?php use Carbon\Carbon; ?>
<div class="modal-header">
    <h5 class="modal-title text-uppercase"> cotización # {{$quote->id}} > Generar Cronograma a partir de una cotización</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <form id="supplies">
        {{ method_field( isset($quote->id)?'PUT':'POST') }}
        {{ csrf_field() }}
        <input type="hidden" value="<?= $quote->id ?? ''; ?>" name="id" />
        <div class="container">
            <div class="col-12">
                <div class="details">
                    <hr>
                    <div>
                        <div class="row">
                            <div class="col-3">
                                <h6>Cliente</h6>
                                <img class="avatar xxs" src="<?= url($quote->crm->avatar ?? '')?>" onerror="this.src='{{url("images/default/customer.png")}}'" /> <span>&nbsp;<?= $quote->crm->name ?></span>
                            </div>
                            <div class="col-3">
                                <h6>Sucursal</h6>
                                <img class="avatar xxs" src="{{url("images/default/office.png")}}" /> <span>&nbsp;<?= $quote->office->name ?></span>
                            </div>
                            <div class="col-3">
                                <h6>Asesor comercial</h6>
                                <img class="avatar xxs" src="<?= url($quote->commercial->avatar ?? '')?>" onerror="this.src='{{url("images/default/user.jpg")}}'" /> <span>&nbsp;<?= $quote->commercial->name ?></span>
                            </div>
                            <div class="col-3">
                                <h6>Fecha creación</h6>
                                <img class="avatar xxs" src="{{url("images/default/calendar.png")}}" /> <span>&nbsp; {{ucwords($quote->created_at->format('d F, Y'))}}</span>
                            </div>
                        </div>
                    </div>
                    <hr>
                </div>
            </div>
            <ul class="nav nav-tabs">
                @if(isset($quote->services))
                <i class="las la-trash-restore la-2x restore-trash" title="Recuperar servicios originales de la cotización" data-click="restoreServices"></i>
                    @php $count = 0; @endphp
                    @foreach($quote->services as $service)
                        <li class="nav-item serviceControls-1">
                            <a class="nav-link {{$count == 0 ? 'active':''}}" id="baseIcon-tab1" data-toggle="tab" aria-controls="tabIcon1" href="#tab{{$service->id ?? ''}}" aria-expanded="true" data-id="{{$service->service->id}}" data-quote_service_id="{{$service->id}}" data-click="setService">
                                <i data-toggle="tooltip" data-placement="top" title="" class="icon-{{$service->service->avatar ?? ''}}" ></i>&nbsp;
                                {{$service->service->name ?? ''}} ({{$service->coverage ?? ''}})
                                <i class="las la-times-circle la-3x float-right" data-click="removeService" data-id="{{ $service->id }}"></i>
                            </a>

                        </li>
                        @php $count++; @endphp
                    @endforeach
                @endif
                <li class="nav-item">
                    <a data-click="addNewService" data-id={{$service->id}}
                        class="nav-link"><i class="ft-plus-circle"></i>Agregar</a>
                </li>
            </ul>
            <hr>

            <div class="tab-content px-1 pt-3 underTabContent">
                @if(isset($quote->services))
                    @php $count = 0; @endphp
                    @foreach($quote->services as $service)
                    <div role="tabpanel" class="tab-pane {{$count == 0 ? 'active':''}}" id="tab{{$service->id ?? ''}}" aria-expanded="true" aria-labelledby="baseIcon-tab4">
                        @if(!is_null($service))
                            <input type='hidden' name='services[{{$service->id}}][id]' value="{{$service->id}}" />
                            <input type='hidden' name='services[{{$service->id}}][service_id]' value="{{$service->service->id}}" />
                        @endif

                        @if(!is_null($service) && $service->genuine_service)
                        <div class="alert alert-warning" role="alert">
                            <b>NO PODRÁS MODIFICAR ALGUNAS COSAS EN ESTE SERVICIO:</b> Este servicio tiene algunos campos bloqueados debido 
                            a que poseen valores definidos en la cotización y al cambiarlos alterarán los términos inicialmente 
                            acordados, si requiere modificar algún campo bloqueado debes modificar la cotización primero.
                        </div>
                        @endif

                        <h4 class="opaque">Cobertura establecida</h4>

                        <div class="form-group row mt-2">
                            <div class="col-4">
                                <label class="col-form-label">Cobertura</label>
                                @if($service->genuine_service)
                                    <input type="text" readonly="readonly" class="form-control" value="{{ $service->coverage ?? '' }}">
                                    <input type="hidden" name="services[{{ $service->id }}][detail_service_id]" readonly="readonly" class="form-control" value="{{ $service->coverage_id ?? '' }}">
                                @else
                            <select class="form-control select2" data-change="hiden-error" data-service="{{$service->service->id}}" name="services[{{ $service->id }}][detail_service_id]" data-placeholder="Cliente" autocomplete="off">
                                        @if ($service->coverage)
                                            <option value="<?= $service->coverage_id; ?>" selected ><?= $service->coverage; ?></option>
                                        @endif
                                    </select>
                                @endif
                            </div>
                            <div class="col-4">
                                <label class="col-form-label">Precio*</label>
                                @if($service->genuine_service)
                                    <input type="text" readonly="readonly" class="form-control" value="{{$service->price ? number_format($service->price,0,'.',','): 0}}">
                                    <input type="hidden" class="form-control" data-keyup="hiden-error" data-format="1" placeholder="Precio" name="services[{{$service->id}}][price]" value="{{$service->price ? $service->price : 0}}">
                                @else
                                    <input type="text" class="form-control" data-keyup="hiden-error" data-format="1" placeholder="Precio" name="services[{{$service->id}}][price]" value="{{$service->price ? number_format($service->price,0,'.',','): 0}}" readonly>
                                @endif
                                
                            </div>
                    

                            {{-- <div class="col-4" >
                                <div class="row skin skin-square" style="padding-top: 17px;">
                                    <div class="col-1">
                                        <input type="checkbox" name="services[{{$service->id}}][allow_automation]" value="1" id="input-checkbox-{{ $service->id }}" {{$service->allow_automation == 0 ? '': 'checked' }}>
                                    </div>
                                    <div class="col-11">
                                        <label for="input-checkbox-{{ $service->id }}"> La fecha/hora de ejecución de este servicio  puede ser modificada por el área de programación.</label>
                                    </div>
                                </div>
                            </div> --}}
                            @if(1+1 ==3)
                                <div class="col-4">
                                    <label class="col-form-label">Fecha de finalización</label>
                                    <input type="text" class="form-control pickadate-translations" data-keyup="hiden-error" placeholder="Fecha" name="services[{{$service->id}}][end_date]" placeholder="Ingrese una fecha" readonly="" value="{{ucwords($service->end_date ?  $service->end_date->format('d F, Y') : $service->possible_date->format('d F, Y'))}}">

                                </div>
                            @endif
                            <div class="col-12">
                                @if((count($service->infestations) > 0 && $service->genuine_service) ||  ($service->observation && $service->genuine_service))

                                    <div class="infestations">
                                        <hr>
                                        @if($service->observation)
                                            <h6>Observaciones desde la cotización</h6>
                                            <div>
                                                <div class="row">
                                                    <div class="col-3">
                                                        <img class="avatar xxs" src="<?= url($quote->commercial->avatar ?? '')?>" onerror="this.src='{{url("images/default/user.jpg")}}'" /> <span>&nbsp;<?= $quote->commercial->name ?> :</span>
                                                    </div>

                                                    <div class="col-9">
                                                        <p>{{$service->observation}}</p>
                                                    </div>

                                                </div>
                                            </div>
                                        @endif
                            
                                        @if(count($service->infestations) > 0 && $service->genuine_service)
                                            {{-- <h6>Controles</h6>
                                            <div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        @foreach($service->infestations as $infestation)
                                                            &nbsp;<img class="avatar xxs" src="<{{ url($infestation->infestation->avatar ?? '')}}" onerror="this.src='{{url("images/default/infestation.png")}}'" /> <span>&nbsp;{{ $infestation->infestation->name }}</span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                         --}}
                                        @endif
                                        <hr>
                                    </div>
                                @endif
                            </div>
               
                                <div class="col-12">
                                    <hr class="mb-3">
                                    <h4>Controles</h4>
                                <select class="form-control select2" data-service="{{$service->service->id}}" data-serviceid="{{$service->id}}" data-change="hiden-error" name="services[{{ $service->id }}][controls][]" data-placeholder="Cliente" autocomplete="off" multiple>
                                        @foreach($service->infestations as $infestation)
                                                <option value="<?= $infestation->infestation->id; ?>" selected ><?= $infestation->infestation->name; ?></option>
                                        @endforeach
                                    </select>

                                    <hr class="mt-3">
                                </div>
                           
                        </div>

                        <!--- Ajsute Agosto 2/2020
                             aqui se incluyen los multiples formularios de los controles 
                        -->
                        <div id="controlFramesSection{{$service->id}}">
                            @foreach($service->infestations as $infestation)
                                @include('quote.forms.frames.control_supply',compact(['infestation','service','timeUnits']))
                            @endforeach
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
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    <button type="button" class="btn btn-success round"
        data-click="saveSupplies" data-id="{{$quote->id}}}">{{isset($quote->id)?'Actualizar':'Guardar'}}</button>
</div>

<style>
    .select2-selection__choice {
        width: auto!important
    }
</style>