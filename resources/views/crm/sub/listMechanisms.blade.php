<div class="row">
    <div class="col-12">
        <div class="card" style="height: 498.047px;">
            <div class="card-content">
                <div class="card-body">
                    <h4 class="card-title">Total de mecanismos instalados en esta sucursal: {{$mechanisms->flatten()->count()}}</h4>
                    <p class="card-text">Estos valores son obtenidos de las visitas de los técnicos y no referencian un valor en tiempo real</p>
                </div>
                <div id="accordion">
                    <div class="card collapse-icon panel mb-0 box-shadow-0 border-0">
                        @php $count = 0;  @endphp
                        @foreach ($mechanisms as $name => $mechanism)
                            @php $count ++; @endphp
                            <div class="card-header border-bottom-blue-grey border-bottom-lighten-4" id="heading{{$count}}">
                                <a href="#" class="h6 blue collapsed" data-toggle="collapse" data-target="#collapse{{$count}}" aria-expanded="false" aria-controls="collapse{{$count}}">
                                    <span class="badge badge-pill bg-warning float-left">{{($mechanism->count())}}</span>
                                    &nbsp;{{$name}} 
                                    </a>
                            </div>
                            <div id="collapse{{$count}}" class="collapse" aria-labelledby="heading{{$count}}" data-parent="#accordion" style="">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th scope="col" colspan="4">MECANISMO</th>
                                                    <th scope="col" colspan="2">ÚLTIMO REPORTE</th>
                                                    <th scope="col" colspan="2">INSTALACIÓN</th>
                                                    <th scope="col" colspan="1">ACCIONES</th>
                                                </tr>
                                            </thead>
                                            <thead>
                                                <tr>
                                                   
                                                    <th scope="col">Código QR</th>
                                                    <th scope="col">Mecanismo</th>
                                                    <th scope="col"># estación</th>
                                                    <th scope="col">Servicio</th>
                                                    <th scope="col">Estado</th>
                                                    <th scope="col">Fecha</th>
                                                    <th scope="col">Responsable</th>
                                                    <th scope="col">Fecha</th>
                                                    <th scope="col"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($mechanism as $detail)
                                                    <tr>
                                                        
                                                        <th scope="row">{{$detail->token}}</th>
                                                        <td>({{$detail->extend->extendible->slug}}) {{$detail->extend->extendible->name}}</td>
                                                        <th scope="row">{{$detail->extend->num}}</th>
                                                        <td>
                                                            @if($detail->schedule)
                                                                <span class="badge badge-info text-large">
                                                                    <b>{{$detail->schedule->service->name}}</b></span>
                                                            @else
                                                                 NO APLICA
                                                            @endif
                                                        </td>
                                                        <td >
                                                            @if($detail->extend->lastTechnicalReport)
                                                                @php 
                                                                    $alert = $detail->extend->lastTechnicalReport->type->id == 4 ? true : false;

                                                                    $warning = $detail->extend->lastTechnicalReport->type->id == 5 ? true : false;

                                                                @endphp
                                                                    <b class="{{$alert ?  'text-danger':''}}
                                                                    {{$warning ?  'text-warning':''}}">
                                                                        @if($alert || $warning)
                                                                            <i class="la la-exclamation-triangle
                                                                            "></i>
                                                                        @endif
                                                                        {{$detail->extend->lastTechnicalReport->type->name}}</b>
                                                            @else
                                                                 SIN REPORTE
                                                            @endif
                                                            
                                                        </td>
                                                        <td>
                                                            @if($detail->extend->lastTechnicalReport)
                                                      {{$detail->extend->lastTechnicalReport->timestamp}}
                                                            @else
                                                                 NO APLICA
                                                            @endif
                                                            
                                                        </td>
                                                        <td>{{$detail->scanBy->name ?? ''}}</td>
                                                        <td>{{$detail->scan_date}}</td>
                                                        <td>
                                                            
                                                                <div class="dropdown">
                                                                <a data-toggle="dropdown" href="#"><i class="las la-ellipsis-v la-2x cursor-pointer"></i></a>
                                                                    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                                                        @can('crms.info.admin')
                                                                            <li data-click="removeElement" data-id="{{$detail->extend->id}}"><span>Retirar Mecanismo</span></li>
                                                                            <li data-click="associateQr" data-id="{{$detail->extend->id}}"><span>Cambiar QR</span></li>
                                                                        @endcan

                                                                        <li data-click="recordElement" data-id="{{$detail->extend->id}}"><span>Reportes</span></li>
                                                                    </ul>
                                
                                                                </div>
                                                            
                                                    </td>
                                                        
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        

                       
                    </div>
                    <div class="card collapse-icon panel mb-0 box-shadow-0 border-0">
                            <div class="card-header border-bottom-blue-grey border-bottom-lighten-4" id="heading-mechanism-replaced">
                                <a href="#" class="h6 blue collapsed" data-toggle="collapse" data-target="#collapse-mechanism-replaced" aria-expanded="false" aria-controls="collapse-mechanism-replaced">
                                    <span class="badge badge-pill bg-danger float-left">{{($mechanisms_deleted->count())}}</span>
                                    &nbsp;Mecanismos Retirados y/o Remplazados 
                                    </a>
                            </div>
                            <div id="collapse-mechanism-replaced" class="collapse" aria-labelledby="heading-mechanism-replaced" data-parent="#accordion" style="">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th scope="col" colspan="4">MECANISMO</th>
                                                    <th scope="col" colspan="2">ÚLTIMO REPORTE</th>
                                                    <th scope="col" colspan="2">INSTALACIÓN</th>
                                                    <th scope="col" colspan="1">ACCIONES</th>
                                                </tr>
                                            </thead>
                                            <thead>
                                                <tr>
                                                   
                                                    <th scope="col">Código QR</th>
                                                    <th scope="col">Mecanismo</th>
                                                    <th scope="col"># estación</th>
                                                    <th scope="col">Servicio</th>
                                                    <th scope="col">Estado</th>
                                                    <th scope="col">Fecha</th>
                                                    <th scope="col">Responsable</th>
                                                    <th scope="col">Fecha</th>
                                                    <th scope="col"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($mechanisms_deleted as $detail)
                                                    <tr>
                                                        
                                                        <th scope="row">{{$detail->token}}</th>
                                                        <td>({{$detail->extend->extendible->slug ?? ''}}) {{$detail->extend->extendible->name ?? ''}}</td>
                                                        <th scope="row">{{$detail->extend->num ?? ''}}</th>
                                                        <td>
                                                            @if($detail->schedule)
                                                                <span class="badge badge-info text-large">
                                                                    <b>{{$detail->schedule->service->name}}</b></span>
                                                            @else
                                                                 NO APLICA
                                                            @endif
                                                        </td>
                                                        <td >
                                                            @if(isset($detail->extend->lastTechnicalReport))
                                                                @php 
                                                                    $alert = $detail->extend->lastTechnicalReport->type->id == 4 ? true : false;

                                                                    $warning = $detail->extend->lastTechnicalReport->type->id == 5 ? true : false;

                                                                @endphp
                                                                    <b class="{{$alert ?  'text-danger':''}}
                                                                    {{$warning ?  'text-warning':''}}">
                                                                        @if($alert || $warning)
                                                                            <i class="la la-exclamation-triangle
                                                                            "></i>
                                                                        @endif
                                                                        {{$detail->extend->lastTechnicalReport->type->name}}</b>
                                                            @else
                                                                 SIN REPORTE
                                                            @endif
                                                            
                                                        </td>
                                                        <td>
                                                            @if(isset($detail->extend->lastTechnicalReport))
                                                                {{$detail->extend->lastTechnicalReport->timestamp}}
                                                            @else
                                                                 NO APLICA
                                                            @endif
                                                            
                                                        </td>
                                                        <td>{{$detail->scanBy->name ?? ''}}</td>
                                                        <td>{{$detail->scan_date}}</td>
                                                        <td>
                                                            
                                                                <div class="dropdown">
                                                                <a data-toggle="dropdown" href="#"><i class="las la-ellipsis-v la-2x cursor-pointer"></i></a>
                                                                    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                                                        <li data-click="recordElement" data-id="{{$detail->extend->id ?? 0}}"><span>Reportes</span></li>
                                                                    </ul>
                                
                                                                </div>
                                                            
                                                    </td>
                                                        
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>