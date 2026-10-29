<tr data-id="<?=$key?>">


    <input type="hidden" name="specificationService[<?=$key?>][id]" value="{{$detailService->id ?? ''}}" />
    <td style="border-bottom: 1px solid #ffffff; width: 40%">

        <select class="form-control select2" data-change="hiden-error" name="specificationService[{{ $key }}][headquarter_id][]" multiple>
            <option>Seleccionar Sede</option>
            @if(isset($detailService->headquarters))
            @foreach ($detailService->headquarters as $headquarter)
                <option value="{{$headquarter->id}}" selected>{{$headquarter->name}}</option>
            @endforeach
            @endif
        </select>
    </td>

    <td style="border-bottom: 1px solid #ffffff;">
         <input class="form-control" type="text" data-keyup="hiden-error" name="specificationService[{{ $key }}][zone]" value="{{ $detailService->zone ?? '' }}" placeholder="Ingrese el área">
     </td>
     <td style="border-bottom: 1px solid #ffffff;width: 40px;">
        <input class="form-control" type="number" data-keyup="hiden-error" name="specificationService[{{ $key }}][visits]" value="{{ number_format(($detailService->visits ?? 0),0,".",",")}}" placeholder="">
    </td>
     <td style="border-bottom: 1px solid #ffffff;width: 20%;">
         <div class="input-group">
            <div class="input-group-prepend">
                <div class="input-group-text">$</div>
            </div>
            <input type="text" name="specificationService[{{ $key }}][cost]" data-format="1" value="{{ number_format(($detailService->cost ?? 0),0,".",",")}}" class="form-control" id="inlineFormInputGroup"
                placeholder="Ingrese el valor">
        </div>
     </td>
     <td class="remove_row_td" style="width: 10px; border-bottom: 1px solid #ffffff;">
        <a class="btn btn-outline-danger" data-click="removeCoverageRow" data-id="<?=$key?>">
            <i class="la la-trash-o"></i>
        </a>
    </td>
</tr>
<tr data-id="<?=$key?>">
    <td colspan="5" style="padding-bottom: 40px;">
       <textarea rows="1" class="form-control" type="text" name="specificationService[{{ $key }}][description]" data-keyup="hiden-error"  placeholder="(Opcional) Ingrese la descripción de la cobertura">{{ $detailService->description ?? ''}}</textarea>
    </td>
</tr>