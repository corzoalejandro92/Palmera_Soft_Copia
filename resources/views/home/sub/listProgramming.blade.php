<div class="card">
    <div class="card-header p-1">
        <h4 class="card-title float-left">Cantidad por servicios programados para el mes {{Jenssegers\Date\Date::parse($start->format('Y-m-d'))->format('F')}}</h4>
    </div>
    <div  class="card-columns">


        @if($services && $services->count() > 0)
          
           
                @foreach ($services as $service)
                   
                        <div class="card bg-gradient-directional-info">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="media d-flex">
                                        <div class="media-body text-white text-left align-self-bottom ">
                                            <span class="d-block mb-1 font-medium-1">{{$service->service->name}}</span>

                                            <h1 class="text-white mb-0">{{$service->completed_count}}/{{$service->total}}</h1>

                                            @if($service->completed_count != 0)
                                            <a target="_blank" href="{{route('scheduling.callsheet')}}?services[]={{$service->service->id}}&startDate={{$start->startOfMonth()->format('Y-m-d')}}&endDate={{$start->endOfMonth()->format('Y-m-d')}}&status[]=3">
                                                <span class="badge badge-info bg-success bg-darken-4 text-contrast">REALIZADOS: {{$service->completed_count}}</span><br>
                                            </a>
                                            @endif

                                            @if($service->cancelled_count != 0)
                                                <a target="_blank" href="{{route('scheduling.callsheet')}}?services[]={{$service->service->id}}&startDate={{$start->startOfMonth()->format('Y-m-d')}}&endDate={{$start->endOfMonth()->format('Y-m-d')}}&status[]=5">
                                                    <span class="badge badge-danger bg-red bg-darken-4 text-current">CANCELADO: {{$service->cancelled_count}}</span><br>
                                                </a>
                                            @endif

                                            @if($service->incompleted_count != 0)
                                                <a target="_blank" href="{{route('scheduling.callsheet')}}?services[]={{$service->service->id}}&startDate={{$start->startOfMonth()->format('Y-m-d')}}&endDate={{$start->endOfMonth()->format('Y-m-d')}}&status[]=4">
                                                    <span class="badge badge-danger bg-warning bg-darken-4 text-current">NO REALIZADO: {{$service->incompleted_count}}</span><br>
                                                </a>
                                            @endif

                                            @if($service->in_route != 0)
                                                <a target="_blank" href="{{route('scheduling.callsheet')}}?services[]={{$service->service->id}}&startDate={{$start->startOfMonth()->format('Y-m-d')}}&endDate={{$start->endOfMonth()->format('Y-m-d')}}&status[]=2">
                                                <span class="badge badge-info bg-brown bg-darken-4 text-current">EN RUTA: {{$service->in_route}}</span><br>
                                                </a>
                                            @endif

                                            @if($service->programmed != 0)
                                                <a target="_blank" href="{{route('scheduling.callsheet')}}?services[]={{$service->service->id}}&startDate={{$start->startOfMonth()->format('Y-m-d')}}&endDate={{$start->endOfMonth()->format('Y-m-d')}}&status[]=6">
                                                <span class="badge badge-info bg-info bg-darken-4 text-current">PROGRAMADOS: {{$service->programmed}}</span><br>
                                                </a>
                                            @endif
                                            @if($service->to_be_confirm != 0)
                                                <a target="_blank" href="{{route('scheduling.callsheet')}}?services[]={{$service->service->id}}&startDate={{$start->startOfMonth()->format('Y-m-d')}}&endDate={{$start->endOfMonth()->format('Y-m-d')}}&status[]=1">
                                                <span class="badge badge-info bg-violet bg-darken-4 text-current">POR CONFIRMAR: {{$service->to_be_confirm}}</span><br>
                                                </a>
                                            @endif
                                            @if($service->init != 0)
                                                <a target="_blank" href="{{route('scheduling.callsheet')}}?services[]={{$service->service->id}}&startDate={{$start->startOfMonth()->format('Y-m-d')}}&endDate={{$start->endOfMonth()->format('Y-m-d')}}&status[]=1">
                                                <span class="badge badge-info bg-purple bg-lighten-2 text-current">SIN PROGRAMAR: {{$service->init}}</span><br>
                                                </a>
                                            @endif
                                        </div>
                                        <div class="align-self-top">
                                            <i class="{{$service->service->avatar}} icon-opacity text-white font-large-4 float-right"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                  
                @endforeach
           
        @else
            <div class="alert alert-danger mb-2" role="alert">
                <strong>Sin datos aún!</strong>  Al parecer aun no hay automatizaciones/programaciones manuales en esta sede.
            </div>
        @endif
    </div>
</div>


