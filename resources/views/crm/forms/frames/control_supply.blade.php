
<section  style="    margin-bottom: -7px;
padding: 24px 70px;
border: 7px dotted #e6e6e6;" id="section{{$service->id}}{{$control->infestation->id}}">
    <div class="row">
        <div class="col-12" >
            <h4 class="opaque"><img class="avatar xxs" 
                src="{{ url($control->infestation->avatar ?? '')}}" 
                onerror="this.src='{{url("images/default/infestation.png")}}'" /> 
                <span>&nbsp; Configuración para 
                    control "{{ $control->infestation->name }}" 
                    de {{ $service->name }}</span></h4><br>
            
            <div class="form-group row mt-2">
                <div class="col-4">
                    <label class="col-form-label">Duración</label>
                       <div class="row">
                           <div class="col-md-4">
                                 <input type="number" data-keyup="hiden-error" data-change="hiden-error" class=" form-control" id="time{{$service->id}}" name='services[{{ $service->id }}][control][{{$control->infestation->id}}][time_value]' value="{{ $control->time_value ?? 0 }}" >
                            </div>
                            <div class="col-md-8">
                                 <select class="form-control select2" data-change="hiden-error" name="services[{{ $service->id }}][control][{{$control->infestation->id}}][time_unit_id]" data-placeholder="Cliente" autocomplete="off">
                                     @foreach($timeUnits as $timeUnit)
                                        <option value="{{ $timeUnit->id }}" {{ $timeUnit->id == isset($control->time_unit_id)  ?  'selected':'' }}>{{ $timeUnit->name }}</option>
                                     @endforeach
                                 </select>
                            </div>
                       </div>
                </div>
                <div class="col-3">
                    <label class="col-form-label">Posible fecha de ejecución</label>
                    <input type="text" class="form-control pickadate-translations" data-change="hiden-error" placeholder="Fecha" name="services[{{ $service->id }}][control][{{$control->infestation->id}}][possible_date]" placeholder="Ingrese una fecha" readonly="" value="{{ucwords(isset($control->possible_date) ?  $control->possible_date->format('d F, Y') : (isset($service->possible_date) ? $service->possible_date->format('d F, Y'): ''))}}">
                </div>
                <div class="col-3">
                    <label class="col-form-label">Inicio periodo a programar</label>
                    <input type="text" class="form-control pickadate-translations" data-change="hiden-error" placeholder="Fecha" name="services[{{ $service->id }}][control][{{$control->infestation->id}}][start_date]" placeholder="Ingrese una fecha" readonly="" value="{{ucwords(isset($control->start_date) ?  $control->start_date->format('d F, Y') : (isset($control->possible_date) ? $service->possible_date->format('d F, Y'): ''))}}">
                </div>
                <div class="col-3">
                    <label class="col-form-label">Fin periodo a programar</label>
                    <input type="text" class="form-control pickadate-translations" data-change="hiden-error" placeholder="Fecha" name="services[{{ $service->id }}][control][{{$control->infestation->id}}][end_date]" placeholder="Ingrese una fecha" readonly="" value="{{ucwords(isset($control->end_date) ?  $control->end_date->format('d F, Y') : (isset($service->possible_date) ? $service->possible_date->format('d F, Y'): ''))}}">
                </div>
            </div>
            
            
            <div class="col-12">
                {{-- <select class="form-control select2" data-service="{{$service->id}}" data-change="hiden-error" name="services[{{ $service->id }}][control][{{$control->infestation->id}}][areas][]" data-placeholder="Cliente" autocomplete="off" multiple>
                   @foreach($control->areas as $area)
                        @if ($area)
                            <option value="{{ $area->id; }}" selected >{{$area->name; }}</option>
                        @endif
                    @endforeach
                </select> --}}
    
                <hr class="mt-3">
            </div>
        </div>
    </div>
    
    <table class="table stripe tbl-supplies" id="table{{$service->id}}{{$control->infestation->id}}">
        <thead>
        <tr class="th_title">
            <th>Area</th>
            <th>Sustancia</th>
            <th>Cant/Dosis</th>
            <th>Aplicación</th>
            <th style="width: 10px;"></th>
        </tr>
        <tbody>
            
        @if(isset($control->products) && count($control->products) > 0)
            @foreach($control->products as $supply)
                @php
                    $key = uniqid();
                    $quoteServiceId = $service->id;
                    $serviceId = $service->service_id;
                    $infestationId = $control->infestation->id;
                    $controlId = $control->infestation->id;

                    //calcular las semanas de la periodicidad seleccionada sobre la fecha actual
                    //1 - cantidad de semanas del periodo seleccionado
                    $weeksQuantity = 1;

                @endphp
                @include('crm.forms.frames.supply_new_row',compact(['key','supply','quoteServiceId','serviceId','infestationId','control','timeUnits']))
            @endforeach
        @else
            @php $key = isset($suplyNewRowFrameKey) ? $suplyNewRowFrameKey : uniqid(); $supply= null; $quoteServiceId = $service->id; $serviceId = $service->id; $infestationId = $control->infestation->id; $controlId = $control->infestation->id; @endphp
            @include('crm.forms.frames.supply_new_row',compact(['key','supply','quoteServiceId','serviceId', 'infestationId','control','timeUnits']))
        @endif
        </tbody>
        <tfoot>
        <tr>
            <th colspan="1" class="add_row_th" style="width: 10px;">
                <a class="btn btn-outline-teal" data-click="addSupplyRow" data-infestation="{{$infestationId}}" data-control='{{$control}}'>
                    <i class="la la-plus-circle"></i>
                </a>
                @include('layouts/loading/min')
            </th>
            <th colspan="4"></th>
        </tr>
        </tfoot>
    </table>
    <br><br>
    <div class="form-group row mt-2">
        <div class="col-12">
            <label class="col-form-label">Observaciones para el control "<?= $control->infestation->name ?>"</label>
            <textarea class="form-control" data-keyup="hiden-error" name="services[{{ $service->id }}][control][{{$control->infestation->id}}][observations]" placeholder="Obervaciones o notas que deba conocer programación y/o el técnico" rows="5">{{$control->observation ?? ''}}</textarea>
        </div>
    </div>
    
</section>
