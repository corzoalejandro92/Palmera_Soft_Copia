<tr data-id="<?=$key?>">
    <td style="width: 20%;">
        <select class="form-control select2" data-week="{{$weekId}}" data-service="{{$serviceId}}"  data-change="hiden-error" name="services[{{$serviceId}}][week][{{$weekId}}][control][{{$key}}][infestation]" data-placeholder="Control" data-key="{{$key}}" autocomplete="off">
            @if(isset($supply) && isset($supply->infestation))
                <option value="{{ $supply->infestation }}" selected>{{ App\Infestation::find($supply->infestation)->name}}</option>
            @endif
        </select>
    </td>

    <td style="width: 18%;">
        <select class="form-control select2" data-week="{{$weekId}}" data-service="{{$serviceId}}" data-infestation="{{$infestationId}}"  data-change="hiden-error" name="services[{{$serviceId}}][week][{{$weekId}}][control][<?=$key?>][area]" data-placeholder="Areas" data-key="{{$key}}" autocomplete="off">
            @if(isset($supply) && isset($supply->area))
            @php $area = App\CrmArea::find($supply->area); @endphp
                @if($area)
                    <option value="{{ $area->id }}" selected>{{ $area->name}}</option>
                @endif
            @endif
        </select>
    </td>
    <!--- <td style="width: 10%;">
        <div style="display: inline-flex;" class="inputs-group">
            <input type="number" style="padding: 10px;width: 60px;" class="form-control cant"  name="services[{{$serviceId}}][week][{{$weekId}}][control][<?=$key?>][time_value]" placeholder="Cantidad" data-keyup="hiden-error" value="<?=$supply->time_value ?? '0' ?>"/>
            <select class="form-control select2"  style="padding: 0px;width: 100px;" data-week="{{$weekId}}" data-service="{{$serviceId}}" data-infestation="{{$infestationId}}"  data-change="hiden-error" name="services[{{$serviceId}}][week][{{$weekId}}][control][<?=$key?>][time_unit]" data-placeholder="Cliente" data-key="{{$key}}" autocomplete="off">
                {{-- @if(isset($timeUnits))
                    @foreach ($timeUnits as $tieUnit)
                        <option value="{{ $tieUnit->id }}" {{(isset($supply) && $supply->time_unit && $supply->time_unit == $tieUnit->id ? 'selected':'')}} >{{ $tieUnit->name}}</option>
                    @endforeach
                @endif --}}
            </select>
           
        </div>
    </td> -->
    
    <td style="width: 25%">
        <select class="form-control select2 select-supply" data-week="{{$weekId}}" data-service="{{$serviceId}}" data-infestation="{{$infestationId}}"  data-change="hiden-error" name="services[{{$serviceId}}][week][{{$weekId}}][control][<?=$key?>][product]" data-placeholder="Cliente" data-key="{{$key}}" autocomplete="off">
            @if(isset($supply) && isset($supply->product))
                @php $product = App\Product::find($supply->product); @endphp
                @if($product)
                    <option value="{{ $product->id }}" selected>{{ $product->name }}</option>
                @endif
            @endif
        </select>
        <div style="display: {{(isset($supply) && isset($supply->product_from) && isset($supply->product_to)) ? 'flex' : 'none' }};margin-top: 3px;" id="iac_{{$key}}">
            <i class="lab la-adn aic-icon" style="font-size: 30px;line-height: 40px; color: {{(isset($supply) && isset($product)) ? $product->color : '' }}"></i>&nbsp;&nbsp;&nbsp;&nbsp;
            <label style="line-height: 38px;margin-right: 12px;">de:</label>
            <input type="number" class="form-control" style="width: 55px;padding: 6px;" value="{{(isset($supply) && isset($supply->product_from) &&  $supply->product_from) ? $supply->product_from : '' }}" name="services[{{$serviceId}}][week][{{$weekId}}][control][<?=$key?>][product_from]"  onchange="$(this).attr('value', $(this).val() )"/>
            <i style="font-size: 30px;line-height: 37px;">&nbsp;&nbsp;-&nbsp;&nbsp;</i>
            <label style="line-height: 38px;margin-right: 12px;">a:</label>
            <input type="number" class="form-control" style="width: 55px;padding: 6px;" value="{{(isset($supply) && isset($supply->product_to) &&  $supply->product_to) ? $supply->product_to : '' }}" name="services[{{$serviceId}}][week][{{$weekId}}][control][<?=$key?>][product_to]" onchange="$(this).attr('value', $(this).val() )"/> 
        </div>
        <input  class="form-control transparent substance-disabled" style="display: none;" value="NO APLICA" readonly />
    </td>
        
    <td style="width: 15%">
        <select class="form-control select2" data-week="{{$weekId}}" data-service="{{$serviceId}}" data-infestation="{{$infestationId}}"  data-change="hiden-error" name="services[{{$serviceId}}][week][{{$weekId}}][control][<?=$key?>][dose]" data-placeholder="Dosis" data-key="{{$key}}" autocomplete="off">
            @if(isset($supply->dose))
            @php $dose = App\Dose::find($supply->dose); @endphp
                @if($dose)
                    <option value="{{ $dose->id }}" selected>{{ $dose->text}}</option>
                @endif
            @endif
        </select>
    </td>
    <td style="width: 16%">
        <select  name="services[{{$serviceId}}][week][{{$weekId}}][control][<?=$key?>][application]" class="form-control text-center">
            @if(isset($supply) && isset($supply->application))
            @php $application = App\Application::find($supply->application); @endphp
                @if($application)
                    <option value="{{ $application->id }}" selected>{{ $application->name}}</option>
                @endif
                
            @endif
        </select>
    </td>

    <td class="remove_row_td" style="width: 10px;">
        <a class="btn btn-outline-danger" data-click="removeSupplyRow" data-key="<?=$key?>" data-service="{{$serviceId}}" data-week="{{$infestationId}}">
            <i class="la la-trash-o"></i>
        </a>
    </td>
</tr>
