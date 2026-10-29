<tr data-id="<?=$key?>">


    <input type="hidden" name="parameterService[<?=$key?>][id]" value="{{$parameterService->id ?? ''}}" />
    <td style="border-bottom: 1px solid #ffffff; width: 25%">

        <select class="form-control select2" data-change="hiden-error" name="parameterService[{{ $key }}][headquarter_id][]" multiple>
            <option>Seleccionar Sedes</option>
            @if(isset($parameterService->headquarters))
            @foreach ($parameterService->headquarters as $headquarter)
                <option value="{{$headquarter->id}}" selected>{{$headquarter->name}}</option>
            @endforeach
            @endif
        </select>
    </td>

    <td style="border-bottom: 1px solid #ffffff; width: 20%">
         <input class="form-control" type="text" data-keyup="hiden-error" name="parameterService[{{ $key }}][item]" value="{{ $parameterService->item ?? '' }}" placeholder="Ingrese el item">
     </td>
     <td style="border-bottom: 1px solid #ffffff;width: 15%;">
         <div class="input-group">
            <div class="input-group-prepend">
                <div class="input-group-text">$</div>
            </div>
            <input type="text" name="parameterService[{{ $key }}][cost]" data-format="1" value="{{ number_format(($parameterService->cost ?? 0),0,".",",")}}" class="form-control" id="inlineFormInputGroup"
                placeholder="Ingrese el valor">
        </div>
     </td>
     <td style="border-bottom: 1px solid #ffffff;">
        <textarea rows="1" class="form-control" type="text" name="parameterService[{{ $key }}][description]" data-keyup="hiden-error"  placeholder="(Opcional)">{{ $parameterService->description ?? ''}}</textarea>
     </td>
     <td class="remove_row_td" style="width: 10px; border-bottom: 1px solid #ffffff;">
        <a class="btn btn-outline-danger" data-click="removeParameterRow" data-id="<?=$key?>">
            <i class="la la-trash-o"></i>
        </a>
    </td>
    
</tr>
