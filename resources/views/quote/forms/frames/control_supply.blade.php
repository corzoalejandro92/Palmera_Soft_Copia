
<section  style="    margin-bottom: -7px;
padding: 24px 70px;
border: 7px dotted #e6e6e6;" id="section{{$service->id}}{{$infestation->infestation->id}}">
    <div class="row">
        <div class="col-12" >
            <h4 class="opaque"><img class="avatar xxs" src="{{ url($infestation->infestation->avatar ?? '')}}" onerror="this.src='{{url("images/default/infestation.png")}}'" /> <span>&nbsp; Configuración para control "{{ $infestation->infestation->name }}" de {{ $service->service->name }}</span></h4><br>
            
            <div class="form-group row mt-2">
                <div class="col-2">
                    <label class="col-form-label">Visitas</label>
                    <input type="number" name="services[{{ $service->id }}][control][{{$infestation->infestation->id}}][visits]" data-keyup="hiden-error" data-change="hiden-error" placeholder="Cant. Visistas" class="form-control" min="1" value="{{ isset($infestation->visits) && $infestation->visits > 0 ? $infestation->visits :  (isset($service->visits) && $service->visits > 0 ? $service->visits :  1)}}">
                </div>

                <div class="col-3">
                    <label class="col-form-label">Periodicidad</label>
                    <select class="form-control select2" data-change="hiden-error" name="services[{{ $service->id }}][control][{{$infestation->infestation->id}}][periodicity_id]" data-placeholder="Cliente" autocomplete="off">
                        @if ($infestation->periodicity)
                            <option value="<?= $infestation->periodicity->id; ?>" selected ><?= $infestation->periodicity->name; ?></option>
                        @elseif($service->periodicity)
                            <option value="<?= $service->periodicity->id; ?>" selected ><?= $service->periodicity->name; ?></option>
                        @endif
                    </select>
                </div>
                <div class="col-4">
                    <label class="col-form-label">Duración</label>
                       <div class="row">
                           <div class="col-md-4">
                                 <input type="number" data-keyup="hiden-error" data-change="hiden-error" class=" form-control" id="time{{$service->id}}" name='services[{{ $service->id }}][control][{{$infestation->infestation->id}}][time_value]' value="{{ $infestation->time_value ?? 0 }}" >
                            </div>
                            <div class="col-md-8">
                                 <select class="form-control select2" data-change="hiden-error" name="services[{{ $service->id }}][control][{{$infestation->infestation->id}}][time_unit_id]" data-placeholder="Cliente" autocomplete="off">
                                     @foreach($timeUnits as $timeUnit)
                                        <option value="{{ $timeUnit->id }}" {{ $timeUnit->id == isset($infestation->time_unit_id)  ?  'selected':'' }}>{{ $timeUnit->name }}</option>
                                     @endforeach
                                 </select>
                            </div>
                       </div>
                </div>
                <div class="col-3">
                    <label class="col-form-label">Posible fecha de ejecución</label>
                    <input type="text" class="form-control pickadate-translations" data-change="hiden-error" placeholder="Fecha" name="services[{{ $service->id }}][control][{{$infestation->infestation->id}}][possible_date]" placeholder="Ingrese una fecha" readonly="" value="{{ucwords(isset($infestation->possible_date) ?  $infestation->possible_date->format('d F, Y') : (isset($service->possible_date) ? $service->possible_date->format('d F, Y'): ''))}}">
                </div>
                <div class="col-3">
                    <label class="col-form-label">Inicio periodo a programar</label>
                    <input type="text" class="form-control pickadate-translations" data-change="hiden-error" placeholder="Fecha" name="services[{{ $service->id }}][control][{{$infestation->infestation->id}}][start_date]" placeholder="Ingrese una fecha" readonly="" value="{{ucwords(isset($infestation->start_date) ?  $infestation->start_date->format('d F, Y') : (isset($infestation->possible_date) ? $service->possible_date->format('d F, Y'): ''))}}">
                </div>
                <div class="col-3">
                    <label class="col-form-label">Fin periodo a programar</label>
                    <input type="text" class="form-control pickadate-translations" data-change="hiden-error" placeholder="Fecha" name="services[{{ $service->id }}][control][{{$infestation->infestation->id}}][end_date]" placeholder="Ingrese una fecha" readonly="" value="{{ucwords(isset($infestation->end_date) ?  $infestation->end_date->format('d F, Y') : (isset($service->possible_date) ? $service->possible_date->format('d F, Y'): ''))}}">
                </div>
            </div>
            
            
            <div class="col-12">
                {{-- <select class="form-control select2" data-service="{{$service->id}}" data-change="hiden-error" name="services[{{ $service->id }}][control][{{$infestation->infestation->id}}][areas][]" data-placeholder="Cliente" autocomplete="off" multiple>
                   @foreach($infestation->areas as $area)
                        @if ($area)
                            <option value="{{ $area->id; }}" selected >{{$area->name; }}</option>
                        @endif
                    @endforeach
                </select> --}}
    
                <hr class="mt-3">
            </div>
        </div>
    </div>
    
    <table class="table stripe tbl-supplies" id="table{{$service->id}}{{$infestation->infestation->id}}">
        <thead>
        <tr class="th_title">
            <th>Area</th>
            <th>Sustancia</th>
            <th>Cant/Dosis</th>
            <th>Aplicación</th>
            <th style="width: 10px;"></th>
        </tr>
        <tbody>
        @if(isset($infestation->products) && count($infestation->products) > 0)
            @foreach($infestation->products as $supply)
                @php
                    $key = uniqid();
                    $quoteServiceId = $service->id;
                    $serviceId = $service->service_id;
                    $infestationId = $infestation->infestation->id;
                    $control = $infestation->infestation->id;
                @endphp
                @include('quote.forms.frames.supply_new_row',compact(['key','supply','quoteServiceId','serviceId','infestationId','control','timeUnits']))
            @endforeach
        @else
            @php $key = isset($suplyNewRowFrameKey) ? $suplyNewRowFrameKey : uniqid(); $supply= null; $quoteServiceId = $service->id; $serviceId = $service->service->id; $infestationId = $infestation->infestation->id; $control = $infestation->infestation->id; @endphp
            @include('quote.forms.frames.supply_new_row',compact(['key','supply','quoteServiceId','serviceId', 'infestationId','control','timeUnits']))
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
            <label class="col-form-label">Observaciones para el control "<?= $infestation->infestation->name ?>"</label>
            <textarea class="form-control" data-keyup="hiden-error" name="services[{{ $service->id }}][control][{{$infestation->infestation->id}}][observations]" placeholder="Obervaciones o notas que deba conocer programación y/o el técnico" rows="5">{{$infestation->observation ?? ''}}</textarea>
        </div>
    </div>
</section>
