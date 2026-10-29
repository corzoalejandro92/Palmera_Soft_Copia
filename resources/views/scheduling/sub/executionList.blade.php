<?php 
use Carbon\Carbon;
use Illuminate\Support\Str;
?>
<form action="venta.php" method="post" id="form">
    <table class="table table-striped table-bordered zero-configuration" id="tblList" >
        <thead>
            <tr>
                <th></th>
                <th>Fecha</th>
                <th class="text-center">Actividad</th>
                <th class="text-center">Técnico</th>
                <th class="text-center">Cliente</th>
                <th class="text-center">Estado</th>
                <th class="text-center">Seguimiento</th>
                @can('scheduling.automation')
                    <th class="text-center">Revisado</th>
                @endcan
                <th class="text-center"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($schedules as $scheduling)
            
            @php 
                $schedule = $scheduling->crmSchedule;
                $sameActivity =  true;

                //Se añade esta validacion para
                //diferencias servicios cancelados 
                //pero realizados en una fecha posterior

                if($schedule){
            

                    $eq= Carbon::parse($schedule->date)
                    ->startOfDay()
                    ->eq(Carbon::parse($scheduling->start)->startOfDay());

                    $sameActivity = $eq && $scheduling->completed == 1;
                }
                
                if(isset($visual_type) && $visual_type == 'day'){
                    $date  =  Carbon::parse($sheluding->start); 
                    $start =  $date->copy()->startOfWeek()->format('d');
                    $end   =  $date->copy()->endOfWeek()->format('d');
                    $title_date =  Str::ucfirst($date->copy()->startOfWeek()->monthName). " ". $start ." a " .Str::ucfirst($date->copy()->endOfWeek()->monthName). " ". $end;
                }else{
                    $date  =  Carbon::parse($scheduling->start); 
                    $start =  $date->copy()->format('d');
                    $title_date =  Str::ucfirst($date->copy()->monthName). " ". $start ." de ".$date->format('Y') . "<br>" .$date->format('h:i A');
                }

                // if($schedule->infestation->monthly){
                //     $date  =  Carbon::parse($schedule->date); 
                //     $start =  $date->copy()->format('d');
                //     $title_date =  Str::ucfirst($date->copy()->monthName);
                // }
                
            @endphp
                <tr class="pointer" data-click="{{$scheduling->id}}">
                    <td></td>
                    <td class="text-center font-weight-bolder" style="width:10%" data-click="openForm" data-id="{{$scheduling->id}}">
                        {!!$title_date!!}
                    </td>
                    <td style="width:15%" data-click="openForm" data-id="{{$scheduling->id}}">

                        @if($schedule)
                        <div class="row">
                            <div class="col-12" style="display: flex;">
                                <div style="display: inline-block;">
                                    <i class="icon-opacity success font-large-1 mr-1 {{ $schedule->service->avatar?? 'default'}}"></i>
                                </div>
                                <div style="display: inline-block; vertical-align: middle;">
                                    <span><b>{{isset($schedule->service->name) ? $schedule->service->name . ' '.$schedule->num :'Sin servicio' }}</b></span>
                                    @if($scheduling->mechanismReports->count() > 0)
                                        <div class="cursor-pointer" data-click="openReportsModal" data-id="{{$scheduling->id}}" >
                                            <span  class="badge badge-pill badge-glow badge-info">{{$scheduling->mechanismReports->count()}}</span> Reporte nuevo
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @else
                           <span> <b>{{$scheduling->name}}</b></span>
                           @if($scheduling->mechanismReports->count() > 0)
                                <div class="cursor-pointer" data-click="openReportsModal" data-id="{{$scheduling->id}}" >
                                    <span  class="badge badge-pill badge-glow badge-info">{{$scheduling->mechanismReports->count()}}</span> Reporte nuevo
                                </div>
                            @endif
                        @endif


                    </td>
                    <td  style="width:10%;" data-click="openForm" data-id="{{$scheduling->id}}">
                        @if($scheduling->users)
                        @foreach ($scheduling->users as $technical)
                               {{$technical->name}}<br>
                        @endforeach
                        @else
                            <i>Cualquier téncico</i>
                        @endif
                    </td>
                    <td  style="width:15%;" data-click="openForm" data-id="{{$scheduling->id}}">
                        @if($schedule)
                        <a href="{{route('crm.gotoSchedule',[$schedule->crm_id, $schedule->office_id])}}?date={{$schedule->date}}#tabSchedule" target="_blank">
                                <div style="display:inline-flex;">
                                    <div style="display: inline-block;">
                                        <img class="avatar xxs mr-1" src="{{url($schedule->crm->avatar ?? '')}}" onerror="this.src='{{asset('images/default/customer.png')}}'" />
                                    </div>
                                    <div style="display: inline-block; vertical-align: middle;">
                                        <span>
                                            {{strtoupper($schedule->crm->name ?? '')}}<br> 
                                            {{$schedule->office->name ?? ''}}, {{$schedule->office->full_address ?? ''}}
                                            <hr>
                                            @if($schedule->office->schedulecontact)
                                                {{$schedule->office->schedulecontact->name}}<br>
                                                Tel/cel:  @if($schedule->office->schedulecontact)
                                                            {{$schedule->office->schedulecontact->phone}}
                                                        @else
                                                            <i style="color: #ccc"> Teléfono sin registrar</i>
                                                        @endif
                                            @else
                                                <i style="color: #ccc"> Contacto sin registrar</i>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                        </a>
                        @endif
                    </td>
                    @if($schedule && $sameActivity)
                    <td class="text-center" data-click="openForm" data-id="{{$scheduling->id}}" style="width:10%; background-color: {{$schedule->status->color ?? '#fff'}}; color: {{$schedule->status->text_color ?? '#000'}};">
                        {{$schedule->status->name}}
                    </td>
                    @else
                     @switch($scheduling->completed)
                         @case(0)
                            <td class="text-center" data-click="openForm" data-id="{{$scheduling->id}}" style="width:10%; background-color: #795548; color: #fff;">
                                En ruta
                            </td>
                             @break
                        @case(1)
                             <td class="text-center" data-click="openForm" data-id="{{$scheduling->id}}" style="width:10%; background-color: green; color: #fff;">
                                 Completado
                             </td>
                              @break
                       @case(2)
                              <td class="text-center" data-click="openForm" data-id="{{$scheduling->id}}" style="width:10%; background-color: red; color: #fff;">
                                  Cancelado
                              </td>
                               @break
                        @case(3)
                               <td class="text-center" data-click="openForm" data-id="{{$scheduling->id}}" style="width:10%; background-color: #FF9800; color: #fff;">
                                   No completado
                               </td>
                                @break
                         @default
                             
                     @endswitch

                    @endif


              
                    
                    <td class="text-center">
                        @if($scheduling->start_management != null)<p style="text-align: left;"><b>{{\Carbon\Carbon::parse($scheduling->start_management)->translatedFormat('d M h:i a')}}: </b>Inicio del servicio </p>@endif
                        @if($scheduling->end_management != null)<p style="text-align: left;"><b>{{  \Carbon\Carbon::parse($scheduling->end_management)->translatedFormat('d M h:i a') }}: </b>Fin del servicio </p>@endif
                        @if ($scheduling->signature)
                            <hr>
                      
                            <div style="text-align: left;">
                                <b>Firma: </b><img style="height:70px;" src="{{$scheduling->signature->file_url}}">
                                @if($scheduling->signature->name)<br><b>Recibe: </b><span>{{$scheduling->signature->name}}</span>@endif
                                @if($scheduling->signature->document)<br><b>CC: </b><span>{{$scheduling->signature->document}}</span>@endif
                            
                            </div>    
                     
                            
                        @endif

                        @if (($scheduling->signature == null && $scheduling->completed == 1) || ($scheduling->signature == null && $scheduling->completed == 2))
                            
                            <div style="text-align: left;">
                                <b style="text-align: left;">El cliente no firma</b>
                            </div>
                        @endif

                    </td>
                    @can('scheduling.automation')
                        <td class="text-center"  title="Haz click para marcar como revisado" data-click="markAsOk" data-id="{{$scheduling->id}}" style="width:7%;cursor: pointer; {{ $scheduling->reviewed ?  'background-color: green; color: #fff;' : 'background-color: #ccc; color: #000;'}} ">
                            {{$scheduling->reviewed ? 'Si': 'No'}}
                        </td>
                    @endcan
                    <td class="text-center" style="width:5%;">
                        
                        <div style="display: inline-flex!important;">
                            <div class="dropdown">
                                <a data-toggle="dropdown" href="#"><i class="las la-ellipsis-v la-2x cursor-pointer"></i></a>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                    @if($schedule)
                                        <li data-click="openStatusForm" data-id="{{$schedule->id}}" ><i class="las la-angle-right la-2x"></i><span>Cambiar estado</span></li>
                                    @else
                                        <li data-click="openStatusFormScheduling" data-id="{{$scheduling->id}}" ><i class="las la-angle-right la-2x"></i><span>Cambiar estado</span></li>
                                    @endif
                                    @if($schedule && $schedule->technicalReports->count() > 0)
                                        <li data-click="openReportsModal" data-id="{{$scheduling->id}}" ><i class="las la-angle-right la-2x"></i><span>Reporte de mecanismos</span></li>
                                    @endif

                                    @if($schedule)
                                    <li data-click="openReportService" data-id="{{$schedule->id}}" >
                                        <a href="{{route('crmschedule.report',[$schedule->id])}}" target="_blank">
                                            <i class="las la-angle-right la-2x"></i><span>Reporte del servicio</span>
                                        </a>
                                    </li>
                                    @endif

                                    @if(
                                    isset($scheduling->crmSchedule->answersSurveys) && 
                                    !is_null($scheduling->crmSchedule->answersSurveys) && 
                                    isset($scheduling->crmSchedule->answersSurveys->last()->id)
                                    )
                                        <li data-click="openFormResults" data-id="{{$scheduling->crmSchedule->answersSurveys->last()->id}}" >
                                            <i class="las la-angle-right la-2x"></i><span>Encuesta</span>
                                        </li>
                                    @endif
                                   
                                     @if($schedule)
                                        <li data-click="openExpenseInfo" data-id="{{$schedule->id}}" ><i class="las la-angle-right la-2x"></i><span>Consumos Reportados</span></li>
                                     @endif
                                </ul>

                            </div>
                        
                        </div>
                        
                    </td>
                </tr>
            @endforeach
            
        </tbody>
    </table>
</form>
