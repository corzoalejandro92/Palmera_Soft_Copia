<section  style="margin-bottom: -7px;
padding: 24px 70px;
border: 7px dotted #e6e6e6;" class="section{{$service->id}}{{$week->id}} section-copyable">
    <div class="row">
        <div class="col-12" >
            <h4 class="opaque" style="display: flex;line-height: 50px;">
                <div class="text-avatar xxs" data-reference="table{{$service->id}}{{$week->id}}">{{$week->week}}</div>
                <span class="ml-1 weekName" data-service-name="{{$service->service->name ?? ''}} {{$service->num ?? ''}}">{{$week->text}}</span>
                <button type="button" data-click="copyFromWeek"  data-week="{{$week->id}}" data-service="{{$service->id}}" style="position: relative;top: 6px;left: 10px;" class="btn btn-outline-dark round  btn-glow mr-1 mb-1" title="Copiar configuración de una semana existente"><i class="las la-paste"></i></button>
            </h4><br>
        </div>
    </div>
    <input type="hidden" name="services[{{$service->id}}][week][{{$week->id}}][start]" value="{{$week->start}}" />
    <input type="hidden" name="services[{{$service->id}}][week][{{$week->id}}][end]" value="{{$week->end}}" />
    <table class="table stripe tbl-supplies" id="table{{$service->id}}{{$week->id}}">
        <thead>
        <tr class="th_title">
            <th>Control</th>
            <th>Área</th>
            {{-- <th>Duración</th> --}}
            <th>Producto</th>
            <th>Dosis</th>
            <th>Aplicación</th>
            <th style="width: 10px;"></th>
        </tr>
        <tbody>
        @if(isset($service->week_data) && count((array)$service->week_data) > 0 && isset($service->week_data->{$week->id}->control))
            @php $weekData = $service->week_data->{$week->id}  @endphp
            @foreach($weekData->control as $supply)
                @php
                    $key = uniqid();
                    $serviceId = $service->id;
                    $infestationId = $week->id;
                    $weekId = $week->id;
                @endphp
                @include('crm.forms.frames.supply_new_row',compact(['key','supply','serviceId','week','timeUnits','weekId']))
            @endforeach
        @else
            @if(!$isEdit && !isset($date))
                @php $key = isset($suplyNewRowFrameKey) ? $suplyNewRowFrameKey : uniqid(); $supply= null; $serviceId = $service->id; $infestationId = $week->id; $weekId = $week->id; @endphp
                @include('crm.forms.frames.supply_new_row',compact(['key','supply','serviceId', 'infestationId','timeUnits','weekId']))
            @endif
        @endif
        </tbody>
        <tfoot>
        <tr>
            <th colspan="1" class="add_row_th" style="width: 10px;">
                <a class="btn btn-outline-teal" data-click="addSupplyRow" data-service="{{$service->id}}" data-week='{{$week->id}}'>
                    <i class="la la-plus-circle"></i>
                </a>
                @include('layouts/loading/min')
            </th>
            <th colspan="5"></th>
        </tr>
        </tfoot>
    </table>
    @if(isset($service->periodicity_id))
        @if(($service->periodicity_id != 13 && ($service->periodicity_start != $service->periodicity_end && $service->week_start != $service->periodicity_end)) || !$isEdit)
            <div class="form-group row">
                <div class="col-12">
                    <label class="col-form-label">Observaciones para la "<?= $week->text ?>"</label>
                    <textarea class="form-control" data-keyup="hiden-error" name="services[{{ $service->id }}][week][{{$week->id}}][observations]" placeholder="Obervaciones o notas que deba conocer programación y/o el técnico cada semana {{$week->id}} de la periodicidad y cobertura establecida" rows="1">{{$service->week_data->{$week->id}->observations ?? ''}}</textarea>
                </div>
            </div>
        @endif
    @else
        <div class="form-group row">
            <div class="col-12">
                <label class="col-form-label">Observaciones para la "<?= $week->text ?>"</label>
                <textarea class="form-control" data-keyup="hiden-error" name="services[{{ $service->id }}][week][{{$week->id}}][observations]" placeholder="Obervaciones o notas que deba conocer programación y/o el técnico cada semana {{$week->id}} de la periodicidad y cobertura establecida" rows="1">{{$service->week_data->{$week->id}->observations ?? ''}}</textarea>
            </div>
        </div>
    @endif
</section>
