<tr data-id="<?=$key?>">

        @if($supply)
            <input type="hidden" name="services[{{$serviceId}}][supplies][<?=$key?>][id]" value="{{$supply->id ?? ''}}" />
        @endif 
    <td style="width: 20%!important">
        <select class="form-control select2" style="width: 20%!important" data-change="hiden-error" name="services[{{$serviceId}}][supplies][<?=$key?>][product]" data-placeholder="Cliente" data-key="{{$key}}" autocomplete="off">
            <option value="">Seleccione un producto</option>
            @if(isset($supply->product))
                <option value="{{ $supply->product->id }}" selected>{{ $supply->product->name}}</option>
            @endif
        </select> 
    </td>

    <td style="width: 20%!important">
        <input type="number" class="form-control"  name="services[{{$serviceId}}][supplies][<?=$key?>][quantity]" placeholder="Cantidad" data-keyup="hiden-error" value="<?=$supply->quantity ?? '' ?>"/>
    </td>
    <td>
       <input class=" form-control transparent time" id="product{{$key}}" value="{{isset($supply->product) ? $supply->product->unit->name : ''}}">
    </td>
    <td class="remove_row_td" style="width: 10px;">
        <a class="btn btn-outline-danger" data-click="removeSupplyRow" data-id="<?=$key?>">
            <i class="la la-trash-o"></i>
        </a>
    </td>
</tr>