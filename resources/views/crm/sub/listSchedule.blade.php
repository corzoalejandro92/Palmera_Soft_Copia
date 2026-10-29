<?php 
use Carbon\Carbon;
use Illuminate\Support\Str;
use Jenssegers\Date\Date;
 ?>

<div class="card">

    <div class="card-content collapse show">
        <div class="card-body">
            <div class="card-text">
                @if($schedules->count() > 0)
                    <div style="position: absolute;top: 10px;right: 85px;">
                        @can('crms.schedule-create')
                            <button class="btn btn-outline-warning buttonAddSchedule" data-click="openFormSchedule" data-crm="{{$crm->id}}" data-office="{{$office->id}}" data-toggle="tooltip" data-placement="top" title="Añadir Servicio"><i class="ft-plus"></i></button>
                        @endcan
                        @can('crms.schedule-send')
                            <button class="btn btn-outline-info" data-click="sendSchedule" data-crm="{{$crm->id}}" data-toggle="tooltip" data-placement="top" title="Enviar al cliente"><i class="ft-mail"></i></button>
                        @endcan
                            <a class="btn btn-outline-danger buttonPdfSchedule" href="{{route('crm.schedulePdf',["crm" => $crm->id, "start_date_submit" =>$startDate, "end_date_submit"=>$endDate, 'office_id' => $office->id])}}" target="_blank" data-toggle="tooltip" data-placement="top" title="Descargar PDF">PDF</a>
                        
                    </div>
                
                    <section class="cd-horizontal-timeline">
                        <div class="timeline">
                            <div class="events-wrapper">
                                <div class="events">
                                    <ol>
                                        @php $count = 0; @endphp
                                        @foreach ($schedules as $date => $schedule)
                                        @php 
                                        $date  =  Carbon::parse($date); 
                                        $start =  $date->copy()->startOfWeek()->format('d');
                                        $end   =  $date->copy()->endOfWeek()->format('d');
                                        $title =  Str::limit(Str::ucfirst($date->copy()->startOfWeek()->monthName), 3,''). " ". $start ." a <br>" .Str::limit(Str::ucfirst($date->copy()->endOfWeek()->monthName), 3,''). " ". $end;
                                        @endphp
                                        <li><a href="#0" data-date="{{$date->format('d/m/Y')}}" class="{{$count == 0 ? 'selected': ''}}">{!!$title!!}</a></li>
                                            @php $count++; @endphp
                                        @endforeach
                                    </ol>
    
                                    <span class="filling-line" aria-hidden="true"></span>
                                </div>
                                <!-- .events -->
                            </div>
                            <!-- .events-wrapper -->
    
                            <ul class="cd-timeline-navigation">  
                                <li><a href="#0" class="prev inactive">Prev</a></li>
                                <li><a href="#0" class="next">Next</a></li>
                            </ul>
                            <!-- .cd-timeline-navigation -->
                        </div>
                        <!-- .timeline -->
    
                        <div class="events-content">
                            <ol>
                                @php $count = 0; @endphp
                                @foreach ($schedules as $date => $_schedules)
                                <li class="{{$count == 0 ? 'selected': ''}}" data-date="{{Carbon::parse($date)->format('d/m/Y')}}">
                                   @foreach($_schedules as $service => $controls)
                                        @php 
                                    
                                            $service    =  \App\Service::find($service);
                                            $date       =  Carbon::parse($date); 
                                            $start      =  $date->copy()->startOfWeek()->format('d');
                                            $end        =  $date->copy()->endOfWeek()->format('d');
                                            $title_date =  "Semana ".Str::ucfirst($date->copy()->startOfWeek()->monthName). " ". $start ." a " .Str::ucfirst($date->copy()->endOfWeek()->monthName). " ". $end;
                                        @endphp
                                           
                                               
                                                    <h3 style="display:flex;color: #1976D2;"><i class="custom-{{$service->avatar}}"></i> &nbsp;<span>{{$service->name}}</span></h3>
                          
                                                    <table class="table  table-striped table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th></th>
                                                                <th>Control</th>
                                                                <th>Área</th>
                                                                <th>Sustancia</th>
                                                                <th>Dosis</th>
                                                                <th>Aplicación</th>
                                                                <th>Observaciones</th>
                                                                <th>Op.</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ( $controls as $control)
                                                                @php $count = 0; @endphp
                                                                @foreach ($control->products as $product )
                                                                    <tr>
                                                                        <td rowspan="{{$control->products->count()}}" class="{{$count > 0 ? 'display-hidden' : 'NO'}}">
                                                                            
                                                                        </td>
                                                                        <td rowspan="{{$control->products->count()}}" class="{{$count > 0 ? 'display-hidden' : 'NO'}}">
                                                                            {{$control->infestation->name}}
                                                                        </td>
                                                                        <td rowspan="{{$control->products->count()}}" class="{{$count > 0 ? 'display-hidden' : ''}}">{{isset($product->area) ? $product->area->name : 'NO'}}</td>
                                                                        <td rowspan="1" style="word-break: break-word;max-width: 130px;">{{(isset($product->product) && isset($product->product->name)) ? $product->product->name :'NO'}}</td>
                                                                        <td rowspan="1">{{isset($product->product->unit) ? $product->quantity.' '.Str::limit($product->product->unit->name, 3, '')  : 'NO' }}</td>
                                                                        <td rowspan="1">{{isset($product->application) ? $product->application->name : 'NO' }}</td>
                                                                        <td rowspan="1">{{isset($control->observation) ? $control->observation : 'NO' }}</td>
                                                                        <td rowspan="1">
                                                                            <div class="dropleft">
                                                                                <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="dropdownMenuButton{{$product->id}}" aria-haspopup="true" aria-expanded="false"></a>
                                                                                <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="dropdownMenuButton{{$product->id}}">  
                                                                                <span class="dropdown-item-text">Control {{$control->infestation->name}}</span>
                                                                                    <li role="presentation" class="divider"></li>
                                                                                    <li><a href="{{route('quotes.edit',[$product->id])}}"><i class="las la-edit la-2x"></i><span>Ver/Editar</span></a></li>
                                                                                    
                                                                                    <li><a href="{{route('quotes.edit',[$product->id])}}" class="text-danger"><i class="las la-trash la-2x"></i><span>Eliminar</span></a></li>
                                                                                </ul>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    @php $count++; @endphp
                            
                                                                @endforeach
                                                            @endforeach
                                                            
                                                        </tbody>
                                                    </table>
                                                    <hr>
                                                    <br><br>
                                               
                                            
                                        @php $count++; @endphp
                                    @endforeach
                                </li>
                                @endforeach

                            </ol>
                        </div>
                        <!-- .events-content -->
                    </section>
                @else
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <strong> <i class="la la-info-circle"></i> No hay nada por aquí!</strong> No se ha establecido un cronograma para este rango de fechas.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                @endif
            </div>
        </div>
    </div>
</div>