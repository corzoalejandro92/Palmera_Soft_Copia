<?php 
use Carbon\Carbon; 
?>
<table class="table table-striped table-bordered zero-configuration" >
    <thead>
        <tr>
            <th>Ruta</th>
            <th class="text-center">Técnico</th>
            <th class="text-center">Progreso</th>
            <th class="text-center">Horas</th>
            <th class="text-center" title="Cantidad de servicios">Ser.</th>
            <th class="text-center" title="Capacidad para ejecutar servicios el día de ruta">Capacidad</th>
            <th class="text-center">Estado</th>
            <th class="text-center">F. Creación</th>
            <th class="text-center">Creado por</th>
            <th class="text-center"></th>
        </tr>
    </thead>
    <tbody>
        @foreach($routes as $route)
            <tr class="pointer">
                <td class="text-center font-weight-bolder"  data-id="{{$route->id}}">
                    {{Carbon::parse($route->date)->format('d/m/Y')}}
                </td>
                <td  style="width:250px;"  data-id="{{$route->id}}">

                    <div class="col-12" style="display: flex;">
                        <div style="display: inline-block;">
                            <img class="avatar xxs mr-1" src="{{$route->user->full_avatar}}" onerror="this.src='{{asset('images/default/user.jpg')}}'" />
                        </div>
                        <div style="display: inline-flex; vertical-align: middle;">
                            <span style="position: relative;">{{mb_strtoupper($route->user->name)  }}</span>
                        </div>
                    </div>

                </td>
                <td class="text-center"  data-id="{{$route->id}}">
                    <a href="{{route('scheduling.execution')}}?date={{$route->date}}&user={{$route->user->id}}" title="ver ejecución de la rutta">
                        <div class="progress progress-sm mt-1 mb-0">
                            <div 
                                class="progress-bar bg-gradient-x-{{($route->progress_per < 50 ? 'danger': ($route->progress_per < 100 ? 'warning' : 'success'))}}" 
                                role="progressbar" 
                                style="width: {{$route->progress_per}}%" 
                                aria-valuenow="{{$route->progress_per}}" 
                                aria-valuemin="0" 
                                aria-valuemax="100"></div>
                            </div>
                        ({{$route->progress}}) {{$route->progress_per}}%
                    </a>
                    
                </td>
                <td class="text-center"  data-id="{{$route->id}}">
                    <span class="badge badge-info badge-pill">Ord. {{$route->times->ordinary_time_humans ?? 0}}</span><br>
                    @if(isset($route->times->extra_time_humans) && $route->times->extra_time_humans > 0)
                        <span class="badge badge-danger badge-pill">Extra. {{ $route->times->extra_time_humans}}</span><br>
                    @endif
                    <span class="badge badge-light badge-pill">total. {{ $route->times->duration_humans ?? 0 }}</span>
                </td>
                <td class="text-center"  data-id="{{$route->id}}">
                    <span class="badge badge-dark badge-pill">{{$route->schedulings_count}}</span>
                </td>
                <td class="text-center"  data-id="{{$route->id}}">
                    @php 
                        try{
                            $capacity = number_format((($route->times->ordnary_time) * 100) / 8 ,0);
                        }catch(Exception $ex){
                            $capacity = 0;
                        }
                         @endphp
                    <div class="progress progress-sm mt-1 mb-0">
                        <div 
                        class="progress-bar bg-gradient-x-{{($capacity < 70 ? 'success': ($capacity < 100 ? 'warning' : 'danger'))}}" 
                        role="progressbar" 
                    style="width: {{$capacity}}%" 
                        aria-valuenow="{{$capacity}}" 
                        aria-valuemin="0" 
                        aria-valuemax="100"
                        ></div>
                    </div>
                    <b>{{$capacity > 100 ? '100' : $capacity}}%</b>
                </td>
                <td class="text-center"  data-id="{{$route->id}}">
                    {{isset($route->status) ? $route->status->name: 'Error de estado'}}
                </td>
                <td class="text-center"  data-id="{{$route->id}}">
                    {{Carbon::parse($route->created_at)->format('d/m/Y')}}
                </td>
                <td style="width:250px;"  data-id="{{$route->id}}">

                    <div class="col-12" style="display: flex;">
                        <div style="display: inline-block;">
                            <img class="avatar xxs mr-1" src="{{$route->automation->user->full_avatar ?? ''}}" onerror="this.src='{{asset('images/default/user.jpg')}}'" />
                        </div>
                        <div style="display: inline-flex; vertical-align: middle;">
                            <span style="position: relative;  {{$route->automation->user->deleted_at ? 'text-decoration: line-through;' : ''}}">{{mb_strtoupper($route->automation->user->name)  }}</span>
                        </div>
                    </div>
                </td>
                
                <td class="text-center" >
                    <div style="display: inline-flex!important;">
                        @if(is_null($route->mailqueue))
                        <i class="las la-hourglass-end la-2x" data-toggle="tooltip" ></i>
                            
                        @else($route->sended)
                        <i class="las la-check-double la-2x text-success" data-toggle="tooltip" title="Esta ruta ya fue enviada al correo del técnico"></i>
                        @endif
                        <div class="dropdown">
                            <a data-toggle="dropdown" href="#"><i class="las la-ellipsis-v la-2x cursor-pointer"></i></a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                @canany(['routes.destroy'])    
                                    <li data-click="removeRoute" data-id="{{$route->id}}" class="text-danger"><i class="las la-trash la-2x"></i><span>Eliminar</span></li>
                                    <li role="presentation" class="divider"></li>
                                @endcanany
                                @canany(['scheduling.automation'])    
                                    <li>
                                        <a href="{{route('scheduling.route', [$route->id])}}" target="_blank">
                                            <i class="las la-eye la-2x"></i><span>Ver PDF</span>
                                        </a>
                                    </li>
                                    <li role="presentation" class="divider"></li>
                                @endcanany
                                @canany(['scheduling.automation'])    
                                    <li data-click="editRoute" data-id="{{$route->id}}">
                                        <a >
                                            <i class="las la-edit la-2x"></i><span>Realizar modificaciones</span>
                                        </a>
                                    </li>
                                    <li role="presentation" class="divider"></li>
                                @endcanany
                                @canany(['scheduling.automation'])    
                                    <li data-click="sendRoute" data-route="{{$route->id}}" ><i class="las la-paper-plane la-2x"></i><span>Enviar / Reenviar</span></li>
                                    <li role="presentation" class="divider"></li>
                                @endcanany
                            </ul>
                            </ul>
                        </div>
                    
                    </div>
                </td>
            </tr>
        @endforeach
        
    </tbody>
</table>
<span align="left" style="margin-top: -58px;">Mostrando del {{$routes->firstItem()}}  al {{$routes->lastItem()}} de {{$routes->total()}} resultados</span>
<div align="right" style="margin-top: -40px;">
    {!!$routes->render()!!}
</div>

