<?php 
use Carbon\Carbon;
use Illuminate\Support\Str;

?>
<h5>Clientes y tipos de servicios a ejecutar para el dia {{$initDate}} <i class="lar la-question-circle" data-toggle="tooltip" data-placement="top" title="Solo se visualizan clientes y servicios relacionados en estado programado para la fecha de ruta indicada."></i><br><br></h5>
    <ul class="nav nav-tabs">
        @if(isset($crms))
            @php $count = 0; $totalServices = 0; $totalControls = 0; @endphp
            @foreach($crms as $crm)
                <li class="nav-item serviceControls-1">
                    <a class="nav-link {{$count == 0 ? 'active':''}}"  data-toggle="tab" href="#tabcrm{{$crm->id ?? ''}}" aria-expanded="true" data-id="{{$crm->id}}" @if($crm->schedules->where('status.slug','cancelled')->first()) style="background: #ffd7d7;border: 3px solid #e28686;color: #e04e4e;" @endif @if($crm->schedules->where('status.slug','service-fail')->first()) style="    background: #ffe9d7;border: 3px solid #e2a186;color: #e0954e;" @endif>
                        <img class="avatar xxs" src="<?= url($crm->avatar ?? '')?>" onerror="this.src='{{url("images/default/customer.png")}}'" /> 
                        <span style="padding: 4px;">&nbsp;<?= $crm->name ?></span> 
                    </a>
                    &nbsp;<i class="las la-times-circle la-2x" data-click="removeCrm" data-count="{{ count($crm->schedules) }}" data-id="{{$crm->id}}" style="position: relative;top: -5px; cursor: pointer;"> </i>
                </li>
                @php $count++; @endphp
            @endforeach
        @endif
    </ul>
    <div class="tab-content px-1 pt-3 underTabContent">
        @if(isset($crms))
            @php $count = 0; @endphp
            @forelse($crms as $crm)
                <div role="tabpanel" class="tab-pane {{$count == 0 ? 'active':''}}" id="tabcrm{{$crm->id ?? ''}}" aria-expanded="true" aria-labelledby="baseIcon-tab4">
                    @foreach($crm->offices as $office)
                        @foreach($office->contacts as $contact)
                            <span style="top: 4px;font-style: italic;color: #868686;"> <i class="las la-user"></i> {{ $contact->name }} | {{ $contact->position }},  {{$office->name}} | {{$contact->phone}}</span><br>
                        @endforeach
                    @endforeach
                    <div class="">
                        <hr>
                        @if($crm)
                        @if($crm->schedules)
                        <h6>Antes de continuar, comprueba los clientes y servicios que serán automatizados:</h6>
                               

                        <div class="card-columns">
                            @foreach($crm->schedules as $pindex => $schedule)

                                    <div class="card" id="serviceTypeDiv{{ $crm->id.''.$schedule->id }}">
                                        <div class="card-header">
                                            <h4 class="card-title d-inline-block" >
                                                <i data-toggle="tooltip" data-placement="top" title="" class="icon-{{$schedule->service->avatar ?? ''}}" style="position: relative;font-size: 30px;top: 7px;display: inherit;"></i>
                                                <a href="{{route('crm.gotoSchedule',[
                                                    $crm->id,
                                                    $schedule->office ? $schedule->office->id : null,
                                                    ])}}#tabSchedule" target="_blank" title="Clic para ir al cronograma">
                                                
                                                {{$schedule->service->name }} <br> 
                                                {!!$schedule->office ? $schedule->office->name : '<b style="background:red; color:white;" >SUCURSAL ELIMINADA ('.\App\Office::withTrashed()->find($schedule->office_id)->name .'</b>' !!} <br> 
                                                {{$schedule->office ? $schedule->office->address : 'SIN DIRECCION'}}
                                            </a>
                                            </h4>
    
                                            <div class="heading-elements">
                                                <ul class="list-inline mb-0">
                                                    <li>
                                                            <span class="times" data-click="removeServiceType" data-id="{{$schedule->id}}"  data-crm="{{ $crm->id }}">&nbsp;<i class="las la-times-circle la-2x"> </i></span><br>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="card-content">
                                            <div class="card-body">
                                                <div class="col-12 timeline-left" id="activity">
                                                    <div class="timeline">
                                                        <ul class="base-timeline activity-timeline">
                                                            @if($schedule->controls)
                                                                @forelse($schedule->controls as $cindex => $control)
                                                                    <li>
                                                                        @if($control->infestation)
                                                                            <div class="timeline-icon bg-bg-gray">
                                                                                <img class="media-object rounded-circle" src="{{url($control->infestation->avatar)}}" alt="Avatar" onerror="this.src='/images/default/infestation.png'">
                                                                            </div>
                                                                            <div class="act-time" >{{$control->infestation->name}}</div>
                                                                        @else
                                                                        <div class="alert alert-warning" role="alert">
                                                                            Este servicio no tiene controles
                                                                          </div>
                                                                        @endif
                                                                        <div class="base-timeline-info">
                                                                            @if($control->product)<a href="#" class="text-bold-700">{{$control->product->name}}</a><br><br>@endif
                                                                            @if($control->area)<span class="text-bold-500">{{$control->area->name}}</span><br>@endif
                                                                            @if($control->dose)<span class="text-bold-500">{{$control->dose->text}}</span><br>@endif
                                                                            @if($control->application)<span class="text-bold-500">{{$control->application->name}}</span><br>@endif
                                                                        </div>
                                                                    </li>
                                                                @empty
                                                                <li><div class="alert alert-warning" role="alert">
                                                                    Este servicio no tiene controles
                                                                  </div></li>
                                                                @endforelse
                                                            @endif

                                                
                                                        </ul>
                                                    </div>
                                                </div>

                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item">
                                                        <span class="badge badge-pill bg-primary float-right text-bold-700 font-medium-1" style="background-color:{{$schedule->status->color}}!important">{{$schedule->status->name}}</span>
                                                        Estado en el cronograma
                                                    </li>
                                                    <li class="list-group-item">
                                                        <span class="badge badge-pill bg-primary float-right text-bold-700 font-medium-1" style="background-color:{{$schedule->status->color}}!important">{{$schedule->date}}</span>
                                                        Fecha en el cronograma
                                                    </li>
                                                    <li class="list-group-item">
                                                        <span class="badge badge-pill bg-primary float-right text-bold-700">{{$schedule->coverage_text}}</span>
                                                        Cobertura
                                                    </li>
                                                    <li class="list-group-item">
                                                        <span class="badge badge-pill bg-primary float-right text-bold-700">{{$schedule->coverage_text}}</span>
                                                        Cobertura
                                                    </li>
                                                    <li class="list-group-item">
                                                        <span class="badge badge-pill bg-warning float-right text-bold-700">{{$schedule->duration_text}}</span>
                                                        Duración
                                                    </li>
                                                    <li class="list-group-item">
                                                        <span class="badge badge-pill bg-teal bg-lighten-1 float-right text-bold-700">$ {{number_format($schedule->price,0,'.',',') }}</span>
                                                        Precio
                                                    </li>
                                                    @if($schedule->start_hour)
                                                        <li class="list-group-item">
                                                            <span class="badge badge-pill bg-danger float-right text-bold-700">{{$schedule->start_hour}}</span>
                                                            Hora Fija
                                                        </li>
                                                    @endif
                                                    @if($schedule->technicals)
                                                        <li class="list-group-item">
                                                            <span class="badge badge-pill bg-pink bg-accent-4 float-right text-bold-700">{{$schedule->technicals->implode('name', ', ')}}</span>
                                                            Técnicos Fijos
                                                        </li>
                                                        
                                                    @endif
                                                </ul>
                                                
                                                @if($schedule->observation)
                                                    <div class="card-body chat-application" style="padding: 0rem;">
                                                        <div class="chats height-300 position-relative">
                                                            <div class="chats">
                                                            
                                                                <div class="chat chat-left">
                                                                    <div class="chat-avatar">
                                                                        <a class="avatar" data-toggle="tooltip" href="#" data-placement="left" title="" data-original-title="">
                                                                            <div class="chat-icon bg-teal bg-darken-3">
                                                                                <i class="ft-message-circle font-medium-1" style="position: absolute;right: 14px;bottom: 13px;height: 8px;border-radius: 100%;"></i>
                                                                            </div>
                                                                        </a>
                                                                    </div>
                                                                    <div class="chat-body">
                                                                        <div class="chat-content">
                                                                            <p>{{$schedule->observation}}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                            @endforeach
                        </div>
                            
                        @else
                            <i class="text-center display-block">No has seleccionado una disponibilidad técnica</i>
                        @endif
                            

                        @else
                            <i class="text-center display-block">No has seleccionado una disponibilidad técnica</i>
                        @endif
                        <hr>
                        <select class="form-control display-hidden" name="servicesType[{{ $crm->id }}][]" multiple>
                            @if($crm->schedules)
                                @foreach($crm->schedules as $sindex =>$sschedule)
                                @php $totalServices ++; @endphp
                                    @php $totalControls ++; @endphp
                                        <option value="{{$sschedule->id}}" selected>{{$sschedule->name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                @php $count++; @endphp
            @empty
                <div class="alert alert-dark" role="alert">
                  No se encontraron clientes pendientes por programación en la fecha dada. Intenta cambiar las fechas
                </div>
            @endforelse
        @else
            <div class="alert alert-dark" role="alert">
              No se encontraron clientes pendientes por programación en la fecha dada. Intenta cambiar las fechas
            </div>
        @endif
        <p><b>Total servicios a programar: <span class="total-services">{{ $totalControls}}</b></p>
    </div>