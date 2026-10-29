@php 
    use \App\General\Menu;
@endphp
<tr data-id="<?=$key?>">
    <td style="width: 20%;">
        @if(!Menu::isActAction('crm','getFrameFromService'))
            <input type="hidden" name="control[{{$key}}][id]" value="{{$control->id ?? ''}}">
        @endif
        <select class="form-control select2"  data-infestation="{{$serviceId}}"  data-change="hiden-error" name="control[{{$key}}][infestation]" data-placeholder="Areas" data-key="{{$key}}" autocomplete="off">
            @if(isset($control) && isset($control->infestation))
                <option value="{{ $control->infestation->id }}" selected>{{ $control->infestation->name}}</option>
            @endif
        </select>
    </td>
    
    <td style="width: 15%;">
        <select class="form-control select2"  data-infestation="{{$serviceId}}"  data-change="hiden-error" name="control[{{$key}}][area]" data-placeholder="Areas" data-key="{{$key}}" autocomplete="off">
            @if(isset($control) && isset($control->area))
                <option value="{{ $control->area->id }}" selected>{{ $control->area->name}}</option>
            @endif
        </select>
    </td>
    
    <td style="width: 30%">
        <select class="form-control select2 select-supply" data-infestation="{{$serviceId}}"  data-change="hiden-error" name="control[{{$key}}][product]" data-placeholder="Cliente" data-key="{{$key}}" autocomplete="off">
            @if(isset($control) && isset($control->product))
                <option value="{{ $control->product->id }}" data-color="{{$control->product->color ?? '#fff'}}" selected>{{ $control->product->name}}</option>
            @endif
        </select>
        <input  class="form-control transparent substance-disabled" style="display: none;" value="NO APLICA" readonly />
    </td>
        
    <td style="width: 15%;">
        <select class="form-control select2" data-infestation="{{$serviceId}}"  data-change="hiden-error" name="control[{{$key}}][dose]" data-placeholder="Dosis" data-key="{{$key}}" autocomplete="off">
            @if(isset($control->dose))
                <option value="{{ $control->dose->id }}" selected>{{ $control->dose->text}}</option>
            @endif
        </select>
    </td>
    <td style="width: 15%;">
        <select  name="control[{{$key}}][application]" class="form-control text-center">
            @if(isset($control) && isset($control->application))
                <option value="{{ $control->application->id }}" selected>{{ $control->application->name}}</option>
            @endif
        </select>
    </td>

    <td class="remove_row_td" style="width: 10px;">
        <a class="btn btn-outline-danger" data-click="removeSupplyRow" data-key="<?=$key?>"  data-control="{{$serviceId}}">
            <i class="la la-trash-o"></i>
        </a>
    </td>
</tr>
