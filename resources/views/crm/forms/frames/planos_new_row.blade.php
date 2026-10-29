<tr data-id="<?=$key?>">
    @if(isset($plan))
        <input type="hidden" name="areas[{{$area->id}}][plan][<?=$key?>][id]" value="{{$file->id ?? ''}}" />
    @endif
    <td>
        @if(isset($file))
        
        @if(in_array($file->fileType->slug,['plan-mec','plan-risk']))
        <a class="file-link" href="{{route('quote.previewfile',['id'=>$file->id])}}" target="_blank"><i class="la la-eye"></i> &nbsp; {{$file->name ?? 'desconocido'}}</a>
        @else
            <a class="file-link" href="{{route('quote.showfile',['id'=>$file->id])}}" target="_blank"><i class="la la-eye"></i> &nbsp; {{$file->name ?? 'desconocido'}}</a>
        @endif
        <input type="hidden"  name="areas[{{$area->id}}][plan][<?=$key?>][id]" value="{{$file->id ?? ''}}" />
        @else
            <div class="custom-file">
                <input type="file" data-file="<?=$key?>" name="areas[{{$area->id}}][plan][<?=$key?>][file]" value="{{$file->name ?? ''}}" class="custom-file-input" id="inputGroupFile01">
                 <label class="custom-file-label" data-label="<?=$key?>" for="inputGroupFile01">Seleccione un archivo</label>
            </div>
        @endif 
    </td>
    <td class="remove_row_td" style="width: 10px;;border-bottom: 1px solid #ffffff;">
        @if(isset($file->fileType) && in_array($file->fileType->slug, ['plan-risk','plan-mec']))

        <a class="btn btn-warning" title="Ubicar sobre el plano" href="{{route('quote.draw'.ucfirst(explode('-',$file->fileType->slug)[1]),
        [
            'id' => $file->id,
            'area'=>$area->id, 
            'crm' => $crm, 
        ])}}" target="_blank">
                <i class="las la-thumbtack la-1x"></i>
        </a>
        @endif
    </td>
    <td style="border-bottom: 1px solid #ffffff;">
        <select class="form-control typeFilePlan" name="areas[{{$area->id}}][plan][<?=$key?>][type_id]" required>
            <option value="">--Seleccionar--</option>
            <?php if (isset($file->fileType)): ?>
            <option value="<?= $file->fileType->id; ?>" selected=""><?= $file->fileType->name; ?>
            </option>
            <?php endif; ?>
        </select>
    </td>
    <td class="remove_row_td" style="width: 10px;;border-bottom: 1px solid #ffffff;">
        <a class="btn btn-outline-danger" data-click="removePlanRow" data-area="{{ $area->id}}" data-id="<?=$key?>">
            <i class="la la-trash-o"></i>
        </a>
    </td>
</tr>
<tr data-id="<?=$key?>-2">
    <td class="td-100" colspan="6" style="padding-bottom: 40px;">
        <textarea class="form-control"  name="areas[{{$area->id}}][plan][<?=$key?>][observation]" placeholder="Observaciones del plano" data-keyup="hiden-error" rows="2"><?=$file->observation ?? '' ?></textarea>
    </td>
</tr>