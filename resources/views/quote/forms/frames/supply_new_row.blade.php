<tr data-id="<?=$key?>">
    @if($supply)
        <input type="hidden" name="services[{{$quoteServiceId}}][key][{{$key->id}}][control][<?=$key?>][id]" value="{{$supply->id ?? ''}}" /> 
    @endif

    <td style="width: 15%;">
        <select class="form-control select2" data-week="{{$week->id}}" data-service="{{$serviceId}}"  data-change="hiden-error" name="services[{{$quoteServiceId}}][week][{{$week->id}}][control][{{$key}}][infestation]" data-placeholder="Control" data-key="{{$key}}" autocomplete="off">
            @if(isset($week) && isset($week->control))
                <option value="{{ $week->control->id }}" selected>{{ $week->control->name }}</option>
            @endif
        </select>
    </td>

    <td style="width: 15%;">
        <select class="form-control select2" data-control="{{$control}}" data-service="{{$serviceId}}" data-infestation="{{$infestationId}}"  data-change="hiden-error" name="services[{{$quoteServiceId}}][week][{{$week->id}}][control][<?=$key?>][area]" data-placeholder="Areas" data-key="{{$key}}" autocomplete="off">
            @if(isset($supply) && isset($supply->area))
                <option value="{{ $supply->area->id }}" selected>{{ $supply->area->name}}</option>
            @endif
        </select>
    </td>

    <td style="width: 10%;">
        <div style="display: inline-flex;" class="inputs-group">
            <input type="number" style="padding: 10px;width: 60px;" class="form-control cant"  name="services[{{$quoteServiceId}}][week][{{$week->id}}][control][<?=$key?>][time_value]" placeholder="Cantidad" data-keyup="hiden-error" value="<?=$supply->quantity ?? '0' ?>"/>
            <select class="form-control select2"  style="padding: 0px;width: 100px;" data-control="{{$control}}" data-service="{{$serviceId}}" data-infestation="{{$infestationId}}"  data-change="hiden-error" name="services[{{$quoteServiceId}}][week][{{$week->id}}][control][<?=$key?>][time_unit]" data-placeholder="Cliente" data-key="{{$key}}" autocomplete="off">
                @if(isset($timeUnits))
                    @foreach ($timeUnits as $tieUnit)
                        <option value="{{ $tieUnit->id }}" >{{ $tieUnit->name}}</option>
                    @endforeach
                @endif
            </select>
           
        </div>
    </td>
    
    <td style="width: 20%">
        <select class="form-control select2 select-supply" data-control="{{$control}}" data-service="{{$serviceId}}" data-infestation="{{$infestationId}}"  data-change="hiden-error" name="services[{{$quoteServiceId}}][week][{{$week->id}}][control][<?=$key?>][product]" data-placeholder="Cliente" data-key="{{$key}}" autocomplete="off">
            @if(isset($supply) && isset($supply->product))
                <option value="{{ $supply->product->id }}" selected>{{ $supply->product->name}}</option>
            @endif
        </select>
        <div style="display: {{(isset($supply) && isset($supply->product) && isset($supply->product->category_id) && $supply->product->category_id == 1) ? 'flex' : 'none' }};margin-top: 3px;" id="iac_{{$key}}">
            <i class="lab la-adn aic-icon" style="font-size: 30px;line-height: 40px; color: {{(isset($supply) && isset($supply->product) && isset($supply->product->color) && $supply->product->color) ? $supply->product->color : '' }}"></i>&nbsp;&nbsp;&nbsp;&nbsp;
            <label style="line-height: 38px;margin-right: 12px;">de:</label>
            <input type="number" class="form-control" style="width: 55px;padding: 6px;" value="{{(isset($supply) && isset($supply->from) &&  $supply->from) ? $supply->from : '' }}" name="services[{{$quoteServiceId}}][week][{{$week->id}}][control][<?=$key?>][product_from]" />
            <i style="font-size: 30px;line-height: 37px;">&nbsp;&nbsp;-&nbsp;&nbsp;</i>
            <label style="line-height: 38px;margin-right: 12px;">a:</label>
            <input type="number" class="form-control" style="width: 55px;padding: 6px;" value="{{(isset($supply) && isset($supply->to) &&  $supply->to) ? $supply->to : '' }}" name="services[{{$quoteServiceId}}][week][{{$week->id}}][control][<?=$key?>][product_to]" /> 
        </div>
        <input  class="form-control transparent substance-disabled" style="display: none;" value="NO APLICA" readonly />
    </td>
        
    <td style="width: 15%">
        <select class="form-control select2" data-control="{{$control}}" data-service="{{$serviceId}}" data-infestation="{{$infestationId}}"  data-change="hiden-error" name="services[{{$quoteServiceId}}][week][{{$week->id}}][control][<?=$key?>][dose]" data-placeholder="Dosis" data-key="{{$key}}" autocomplete="off">
            @if(isset($supply->dose))
                <option value="{{ $supply->dose->id }}" selected>{{ $supply->dose->name}}</option>
            @endif
        </select>
    </td>
    <td style="width: 16%">
        <select  name="services[{{$quoteServiceId}}][week][{{$week->id}}][control][<?=$key?>][application]" class="form-control text-center">
            @if(isset($supply) && isset($supply->application))
                <option value="{{ $supply->application->id }}" selected>{{ $supply->application->name}}</option>
            @endif
        </select>
    </td>

    <td class="remove_row_td" style="width: 10px;">
        <a class="btn btn-outline-danger" data-click="removeSupplyRow" data-key="<?=$key?>" data-service="{{$quoteServiceId}}" data-control="{{$infestationId}}">
            <i class="la la-trash-o"></i>
        </a>
    </td>
</tr>
<tr>
    <td colspan="6" style="padding-bottom: 40px;"></td>
</tr>