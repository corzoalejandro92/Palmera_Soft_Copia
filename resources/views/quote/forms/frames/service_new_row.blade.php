<tr data-id="<?=$key?>">

        @if($service)
            <input type="hidden" name="services[<?=$key?>][id]" value="{{$service->id ?? ''}}" />
        @endif 
    <td style="width: 25%;border-bottom: 1px solid #ffffff;">
        <select class="form-control select2" data-change="hiden-error" name="services[<?=$key?>][service]" data-placeholder="Servicio" data-key="{{$key}}" autocomplete="off">
            <option value="">Seleccione un servicio</option>
            @if(isset($service->service))
                <option value="{{ $service->service->id }}" selected>{{ $service->service->name}}</option>
            @endif
        </select> 
    </td>
    <td style="border-bottom: 1px solid #ffffff;width: 25%;">
        <select class="form-control select2" data-change="hiden-error" name="services[<?=$key?>][detailService]" data-placeholder="Cliente" data-key="{{$key}}" autocomplete="off">
            <option value="">Seleccione una cobertura</option>
            @if(isset($service->coverage_id))
                <option value="{{ $service->coverage_id }}" selected>{{ $service->coverage}}</option>
            @endif
        </select>
    </td>
    <td style="border-bottom: 1px solid #ffffff;width: 50px;">
        <input type="number" class="form-control"  name="services[<?=$key?>][quantity]" placeholder="#" data-keyup="hiden-error" data-key="{{$key}}" value="<?= number_format( isset($service->quantity) && $service->quantity > 0 ? $service->quantity : 1) ?>"/>
    </td>
    <td style="border-bottom: 1px solid #ffffff;width: 150px;">
        <input type="text" class="form-control"  name="services[<?=$key?>][price]" placeholder="Precio" data-keyup="hiden-error" data-format="1" value="<?= number_format( $service->price ?? 0,0,'.',',') ?>"/>
        <input type="hidden" class="form-control"  name="services[<?=$key?>][_price]" placeholder="Precio" data-keyup="hiden-error" data-format="1" value="<?= number_format( $service->price ?? 0,0,'.',',') ?>"/>
    </td>
    <td style="border-bottom: 1px solid #ffffff;width: 50px;">
        <input type="number" class="form-control"  name="services[<?=$key?>][visits]" placeholder="#" data-keyup="hiden-error"  value="<?= number_format( $service->visits ?? 0) ?>"/>
    </td>
    <td style="border-bottom: 1px solid #ffffff;width: 50px;">
        <select class="form-control"  name="services[<?=$key?>][periodicity_id]" placeholder="Periodicidad" data-change="hiden-error" >
            @if(isset($service->periodicity))
                <option value="{{ $service->periodicity->id }}" selected>{{ $service->periodicity->name}}</option>
            @endif
        </select>
    </td>

    <td class="remove_row_td" style="width: 10px;;border-bottom: 1px solid #ffffff;">
        <a class="btn btn-outline-danger" data-click="removeServiceRow" data-id="<?=$key?>">
            <i class="la la-trash-o"></i>
        </a>
    </td>
</tr>
<tr data-id="<?=$key?>">
    <td class="td-100"  >
        <label style="font-weight: 900;line-height: 40px;float: right;">Controles:</label>
    </td>
    <td class="td-100" colspan="6" >
        <select class="form-control select2" style="width: 100%;" data-change="hiden-error"  {{isset($service->infestations) ? 'multiple': ''}} name="services[<?=$key?>][infestations][]" data-placeholder="Cliente" data-key="{{$key}}" data-service="{{ isset($service) ? $service->service->id: ''}}" autocomplete="off">
            @if(isset($service->infestations))
                @foreach($service->infestations as $infestation)
                    <option value="{{ $infestation->infestation->id }}" selected>{{ $infestation->infestation->name}}</option>
                @endforeach
            @endif
        </select>
    </td>
</tr>
<tr data-id="<?=$key?>">
    <td class="td-100"  >
        <label style="font-weight: 900;line-height: 40px;float: right;">Observaciones:</label>
    </td>
    <td class="td-100" colspan="6">
        <textarea class="form-control"  name="services[<?=$key?>][observation]" placeholder="Observaciones" data-keyup="hiden-error" rows="2"><?=$service->observation ?? '' ?></textarea>
    </td>
</tr>
<tr data-id="<?=$key?>">
    <td class="td-100"  >
        <label style="font-weight: 900;line-height: 40px;float: right;">Observaciones al cliente:</label>
    </td>
    <td class="td-100" colspan="6">
        <textarea class="form-control"  name="services[<?=$key?>][public_observation]" placeholder="Observaciones" data-keyup="hiden-error" rows="2"><?=$service->public_observation ?? '' ?></textarea>
    </td>
</tr>
<tr data-id="<?=$key?>">
    <td class="td-100">
        <label style="font-weight: 900;line-height: 40px;float: right;">Parámetros adicionales:</label>
    </td>
    <td class="td-100 parameters" colspan="6">

        @if (isset($service->service->parameters))

            @php 
            $index = uniqid(); 
            $parameters= $service->service->parameters; 
            $quoteParameters = $service->parameters;

            @endphp
            @include('quote.forms.frames.add_parameter_row',compact(['key','parameters','index', 'quoteParameters']))

        @else
            Sin parámetros adicionales para seleccionar
        @endif
        
    </td>
</tr>