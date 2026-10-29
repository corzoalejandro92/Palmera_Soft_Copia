<tr data-id="{{$key}}">
    @if(isset($configurationwarehouseFoxcon))
     <input type="hidden"  name="settingFoxcon[{{$key}}][id]" value="{{ $configurationwarehouseFoxcon->id ?? '' }}">
    @endif
    <td style="width: 60%;">
        <div>
            <select class="select2 form-control document-type"  data-change="hiden-error"  name="settingFoxcon[{{$key}}][document_type]">
                <option value="">--Seleccionar--</option>
                @if (isset($configurationwarehouseFoxcon->typeDocument))
                    <option value="{{ $configurationwarehouseFoxcon->typeDocument->id}}" selected="">{{ $configurationwarehouseFoxcon->typeDocument->name }}</option>
                @endif
            </select>
        </div>
    </td>
    
    <td>
        <div class="inputMinimunVal">
            <input type="text" style="text-align: center;" class="form-control" name="settingFoxcon[{{$key}}][nomenclature]"
                value="{{$configurationwarehouseFoxcon->nomenclature ?? ''}}" placeholder="#">
        </div>
    </td>
    <td>
        <div class="inputMaximunVal">
            <input type="number" style="text-align: center;" name="settingFoxcon[{{$key}}][consecutive]"
                class="form-control" value="{{$configurationwarehouseFoxcon->consecutive ?? ''}}"
                placeholder="#">
        </div>
    </td>
    <td class="remove_row_td" style="width: 10px;">

        <a class="btn btn-outline-danger" data-click="removeRowFoxconSetting" data-id="{{$key}}">
            <i class="la la-trash-o"></i>
        </a>
    </td>
</tr>