<div class="card-body">

    <ul class="nav nav-tabs">
        @foreach($services as $service)
            <li class="nav-item serviceControls-1">
                <a class="nav-link" id="baseIcon-tab1" data-toggle="tab" aria-controls="tabIcon1" href="#tab{{$service->id ?? ''}}" aria-expanded="true" data-id="{{$service->id}}" data-click="setService">
                    <i data-toggle="tooltip" data-placement="top" title="" class="icon-{{$service->avatar ?? ''}}" ></i>&nbsp;
                    {{$service->name ?? ''}} 
                </a>
            </li>
        @endforeach
    </ul>
    <hr>
    
    <div class="tab-content px-1 pt-3 underTabContent">
        @foreach($services as $service)
        @php $schedulingService = null; @endphp
          
        @if($schedulingServices)
           @php  
                $schedulingService = $schedulingServices->where('service_id',$service->id)->last()
           @endphp
        @endif
            <div role="tabpanel" class="tab-pane" id="tab{{$service->id ?? ''}}" aria-expanded="true" aria-labelledby="baseIcon-tab4">
            @if(!is_null($schedulingService))
                <input type='hidden' name='services[{{$service->id}}][id]' value="{{$schedulingService->id}}" />
            @endif
            <input type='hidden' name='services[{{$service->id }}][service_id]' value="{{$schedulingService->id ?? $service->id}}" />
            <h4 class="opaque">Configuración del servicio</h4>
     
        <div class="form-group row">
            <div class="col-4">
                <label class="col-form-label">Área*</label>
                <select class="form-control select2" data-change="hiden-error" name="services[{{$service->id}}][detail_service_id]" data-placeholder="Cliente" autocomplete="off">
                    <option value="">Seleccione un área</option>
                    @if ($service->detailService)
                        @foreach($service->detailService as $ds)
                            <option value="<?= $ds->id; ?>" <?=  isset($schedulingService->detailService) &&  $schedulingService->detailService->id == $ds->id ? 'selected': ''?>><?= $ds->zone; ?></option>
                        @endforeach
                    @endif
        
                </select>
            </div>
            <div class="col-4">
                <label class="col-form-label">Precio*</label>
                <input type="text" class="form-control" data-keyup="hiden-error" placeholder="Precio" name="services[{{$service->id}}][price]" value="{{$schedulingService->price ?? $service->price}}">
            </div>
        </div>
        <br>
        <h4 class="opaque">Observaciones dirigidas</h4>
        <table class="table stripe tbl-observations">
            <thead>
                <tr class="th_title">
                    <th>Rol</th>
                    <th>Observación</th>
                    <th>Adjunto</th>
                    <th style="width: 10px;"></th>
                </tr>
                <tbody>
      
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="1" class="add_row_th" style="width: 10px;">
                            <a class="btn btn-outline-teal" data-click="addObservationRow">
                                <i class="la la-plus-circle"></i>
                            </a>
                            @include('layouts/loading/min')
                        </th>
                        <th colspan="3"></th>
                    </tr>
                </tfoot>
            </table>
        
        <br>
        <h4 class="opaque">Insumos a utilizar</h4>
        <table class="table stripe tbl-supplies">
            <thead>
                <tr class="th_title">
                    <th>Producto</th>
                    <th>Cant/Dosis</th>
                    <th>Medida</th>
                    <th style="width: 10px;"></th>
                </tr>
                <tbody>
                    @if(isset($schedulingService->schedulingSerSup))
                        @foreach($schedulingService->schedulingSerSup as $supply)
                            @php 
                                $key = uniqid(); 
                                $serviceId = $service->id;
                            @endphp
                            @include('scheduling.forms.frames.supply_new_row',compact(['key','supply','serviceId']))
                        @endforeach
                    @else
                        @php $key = uniqid(); $supply= null; $serviceId = $service->id; @endphp
                        @include('scheduling.forms.frames.supply_new_row',compact(['key','supply','serviceId']))
                    @endif
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="1" class="add_row_th" style="width: 10px;">
                            <a class="btn btn-outline-teal" data-click="addSupplyRow">
                                <i class="la la-plus-circle"></i>
                            </a>
                            @include('layouts/loading/min')
                        </th>
                        <th colspan="3"></th>
                    </tr>
                </tfoot>
            </table>
              
        <br>
        <h4 class="opaque">Tareas de este servicio</h4>
        <table class="table stripe tbl-tasks">
            <thead>
                <tr class="th_title">
                    <th>#</th>
                    <th>Tarea</th>
                    <th style="width: 10px;"></th>
                </tr>
                <tbody>
                    @if(isset($schedulingService->schedulingSerTas))
                        @foreach($schedulingService->schedulingSerTas as $task)
                            @php 
                                $key = uniqid(); 
                                $serviceId = $service->id;
                            @endphp
                            @include('scheduling.forms.frames.task_new_row',compact(['key','task','serviceId']))
                        @endforeach
                    @else
                        @php $key = uniqid(); $task= null; $serviceId = $service->id; @endphp
                        @include('scheduling.forms.frames.task_new_row',compact(['key','task','serviceId']))
                    @endif
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="1" class="add_row_th" style="width: 10px;">
                            <a class="btn btn-outline-teal" data-click="addTaskRow">
                                <i class="la la-plus-circle"></i>
                            </a>
                            @include('layouts/loading/min')
                        </th>
                        <th colspan="3"></th>
                    </tr>
                </tfoot>
            </table>

        </div>
        @endforeach
    </div>
    
    
</div>