<style>
table td {
    padding:12px;
}

</style>
<table id="tblList" listyle="width: 100%; position: relative;background-color: transparent;margin-bottom: 1rem;" class=" table table-striped table-bordered zero-configuration" >
    <thead>
    <tr>
        <th class="text-center">Código</th>
        <th class="text-center ">Asesor</th>
        <th class="text-center ">Cliente</th>
        <th class="text-center ">Servicios</th>
        <th class="text-center " style="width:50px">MIP</th>
        <th class="text-center">Fechas</th>
        <th class="text-center">Total</th>
        <th class="text-center">Estado</th>
        <th class="text-center">Opciones</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($quotes as $quote): ?>
        <tr class="pointer">
            <td class="text-center" nowrap><span style="font-size:13px"><b><?= $quote->code ?></b></span></td>
            <td class="text-center" style="width:150px;">
                <div class="row">
                <div class="col-12" style="display: flex;">
                    <div style="display: inline-block;">
                        <img class="avatar xxs mr-1" src="<?= url($quote->commercial->avatar ?? '')?>" onerror="this.src='{{url("images/default/user.jpg")}}'" />
                    </div>
                    <div style="display: inline-flex; vertical-align: middle;">
                        <span style="position: relative;font-size:13px">{{$quote->commercial ? mb_strtoupper($quote->commercial->name) : 'Ninguno' }} <br>
                            <small><span class="la la-map-marker-alt text-bold-400"></span>{{$quote->commercial ? $quote->commercial->headquarter->name: ''}}</small>
                        </span>
                    </div>
                </div>
                </div>

            </td>
            <td class="text-center" style="width:150px;">
                <div class="row">

                    <div class="col-12" style="display: flex;">

                        <div style="display: inline-block;">
                            <a href="{{route('crm.gotoSchedule',[
                                                    $quote->crm_id,
                                                    $quote->office_id ? $quote->office_id : null,
                                                    ])}}" target="_blank" title="Clic para ir al cliente">
                                <img class="avatar xxs mr-1" src="<?= url($quote->crm->avatar ?? '')?>" onerror="this.src='{{url("images/default/customer.png")}}'" />
                                </a>
                        </div>

                        <div style="display: inline-flex; vertical-align: middle;">
                            <span style="position: relative;font-size:13px">{{mb_strtoupper($quote->crm->name)  }}<br>
                                <small><span class="la la-map-marker-alt text-bold-400"></span>{{$quote->office->name}}</small>
                            </span>
                        </div>
                    </div>
                </div>
            </td>
            <td class="text-center">
                @foreach ($quote->services as $service)
                    <span class="badge badge-{{$service->approved ? 'success': 'info'}} text-bold-400" style="white-space: inherit;">{{$service->service->name}}
                        @if($service->observation)
                        <span style="font-size:13px">
                            <br><br><b style="font-size:13px">OBS INTERNA:</b> {{$service->observation}}
                        </span>
                            
                        @endif
                        @if($service->public_observation)
                        <span style="font-size:13px">
                            <br><br><b style="font-size:13px">OBS CLIENTE:</b> {{$service->public_observation}}
                        </span>
                        
                        @endif
                    </span><br><br>
                @endforeach
                
            </td style="width:50px">
            @if($quote->diagnostic)
                <td>
                    <span class="text-center">
                        <span class="badge badge-success text-bold-400"> CLIENTE MIP</span>
                    </span>
                </td>
            </td>
            @else
            <td>
                <span class="text-center">
                    <span class="badge badge-warning text-bold-400"> NO MIP</span>
                </span>
            </td>
            @endif
            <td class="text-center">
                <span class="badge badge-dark">Creada: {{$quote->created_at->format('d/m/Y')}}</span><br><br>
                @if (isset($quote->approved_at))
                    <span style="font-size:13px;" class="badge badge-success">Aprobado: {{ $quote->date_approved_at->format('d/m/Y H:i')}}</span><br><br>
                @endif
            </td>
            <td class="text-center">
                <b><span style="font-size:13px;">$ <?= number_format($quote->total,0,'.',',') ?></span></b>
                @if($quote->status_id == 2)
                    <br><span class="badge badge-success" style="font-size:13px;">$ <?= number_format($quote->services->where('approved',1)->sum('price') ,0,'.',',') ?> aprobado</span>
                @endif
            </td>
            <td class="text-center">
                {{ $quote->status->name }}
            </td>
            <td class="text-center">
                <div class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">Opciones</a>
                    <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="dLabel">
                        @if(in_array($quote->status_id,[1,4]))
                            @canany(['quotes.edit'])
                                @if($quote->commercial && $quote->commercial->id == auth()->user()->id || auth()->user()->roles->pluck('slug')->contains('root'))
                                    <li><a href="{{route('quotes.edit',[$quote->id])}}"><i class="las la-edit la-2x"></i><span>Ver/Editar</span></a></li>
                                    <li role="presentation" class="divider"></li>
                                @endif
                            @endcanany
                            @canany(['quotes.destroy'])
                                @if($quote->commercial && $quote->commercial->id == auth()->user()->id || auth()->user()->roles->pluck('slug')->contains('root'))    
                                    <li data-click="deleteForm" data-id="{{$quote->id}}" class="text-danger"><i class="las la-trash la-2x"></i><span>Eliminar</span></li>
                                    <li role="presentation" class="divider"></li>
                                @endif
                            @endcanany
                        @endif
                        @canany(['quotes.show'])
                          {{-- <li><a href="{{route('quote.format-pdf',[$quote->id])}}" target="_blank"><i class="las la-file-image la-2x"></i><span>Ver cotización</span></a></li> --}}
                        @endcanany
                        @canany(['quotes.show'])
                        @if(!$quote->file)
                         <li data-click="openFormDocumentoDiagnosis" data-id="{{$quote->id}}"><a href="{{route('quote.format-pdf',[$quote->id])}}" target="_blank"><i class="las la-paperclip la-2x"></i><span>Adjuntar Archivo</span></a></li>
                         @else
                         @if($quote->file)
                         <li><i class="las la-glasses la-2x text-info"></i> <span class="text-info"><a href="{{ route('quote.showfile',["id" => $quote->file->id])}}" target="_blank">Ver Archivo</a></span></li>
                         <li data-click="deleteQuoteFile" data-quote="{{$quote->code}}" data-id="{{$quote->file->id}}"><i class="las la-trash la-2x text-danger"></i> <span class="text-danger"><a >Eliminar Archivo</a></span></li>
                         @endif
                         @endif
                         @endcanany
                        @canany(['quotes.send'])
                        <li data-click="sendQuote"  data-quote="{{$quote->id}}"><i class="las la-envelope-open la-2x"></i> <span>Enviar Cotización</span></li>
                        @endcanany
                        <li role="presentation" class="divider"></li>

                        @if(!in_array($quote->status_id,[1,3]))
                        
                            @if($quote->diagnostic)
                                
                                    @can('quotes.diagnosis')
                                    <!--    <li  data-click="openFormDocumentoDiagnosis" data-id="{{$quote->id}}"><i class="las la-glasses la-2x"></i> <span>crear diagnóstico</span></li> -->
                                    <li><a target="_blank" data-click="diagnostic" href="{{route('diagnostic.creator',['quote'=>$quote->id])}}"><i class="las la-glasses la-2x"></i> <span>
                                        @if(!$quote->hasDiagnostic)
                                            Crear diagnóstico
                                        @else
                                            Editar Diagnóstico ({{$quote->hasDiagnostic->status->name}})
                                        @endif
                                        
                                    </span></a></li>

                                    @if($quote->hasDiagnostic)
                                    <li><i class="las la-glasses la-2x text-info"></i> <span class="text-info"><a href="{{ route('diagnostic.show',["id" => $quote->hasDiagnostic->id])}}" target="_blank">Ver Diagnóstico (PDF)</a></span></li>
                                    <li data-click="deleteDiagnostic" data-quote="{{$quote->consecutive}}" data-id="{{$quote->hasDiagnostic->id}}"><i class="las la-trash la-2x text-danger"></i> <span class="text-danger"><a >Eliminar diagnóstico</a></span></li>
                                    @endif
                                    @endcan
                         
                            @endif

                            @can('quotes.plans')
                                <li data-click="areasCustomerForm"  data-id="{{$quote->id}}" ><i class="las la-pencil-ruler la-2x"></i><span>Planos</span></li>
                            @endcan

                            @can('quotes.supplies')
                                <li ><a href="{{route('crm.add-schedule-from-quote', ['crm' => $quote->crm_id, 'office' => $quote->office_id, 'quote' =>$quote->id])}}#tabSchedule" target="_blank"><i class="las la-chalkboard-teacher la-2x"></i><span>Cronograma</span></a></li>
                                {{-- <li data-click="openSuppliesForm" data-quote="{{$quote->id}}"><i class="las la-chalkboard-teacher la-2x"></i><span>Cronograma</span></li> --}}
                            @endcan
                        @endif

                        {{-- @if(in_array($quote->status_id,[2,3]) && !$quote->contract)
                            @can('quotes.contract')
                                <li data-click="openContractForm" data-quote="{{$quote->id}}"><i class="las la-file-signature la-2x"></i> <span>Crear contrato</span></li>
                            @endcan
                        @endif --}}

                        @if($quote->contract)
                            <li style="background: #ececec;color: #673AB7; cursor: unset;">
                                <i class="las la-check-circle la-2x"></i> 
                                <span>Contrato creado</span>
                            </li>
                        @endif

                        @if(!$quote->contract)
                            <li role="presentation" class="divider"></li>
                            <li  class="text-info" data-click="openStatusForm" data-quote="{{ $quote->id }}">
                                <a href="#"><i class="las la-angle-right la-2x"></i><span>Cambiar estado</span></a>
                            </li>
                        @endif
                    </ul>
                </div>
            </td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>
<span align="left" style="margin-top: -58px;">Mostrando del {{$quotes->firstItem()}}  al {{$quotes->lastItem()}} de {{$quotes->total()}} resultados</span>
<div align="right" style="margin-top: -20px;">
    {!!$quotes->render()!!}
</div>
