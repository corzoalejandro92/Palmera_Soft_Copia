<tr data-id="<?=$key?>">
    <td>
        @if($document)
            <div class="row">
                <div class="col-md-12">
                    <input type="hidden" name="document[<?=$key?>][id]" value="{{$document->id}}" data-change="hiden-error" />
                    <a class="file-link" href="{{route('users.showfile',['id'=>$document->id])}}" target="_blank"><i class="la la-eye"></i> {{$document->name ?? 'desconocido'}}</a>
                </div>
            </div>
        @else
            <div class="custom-file">
                <input type="file" data-file="<?=$key?>" name="document[<?=$key?>][file]" class="custom-file-input" id="inputGroupFile01" data-change="hiden-error">
                <label class="custom-file-label" data-label="<?=$key?>" for="inputGroupFile01">Seleccione un archivo</label>
            </div>
        @endif 
    </td>
    <td>
        <select class="form-control" name="document[<?=$key?>][type]" required data-change="hiden-error">
            <option value="">--Seleccionar--</option>
            <option value="1" <?= $document && $document->type == 1 ? 'selected': '' ?>>Documento de identidad</option>
            <option value="2" <?= $document && $document->type == 2 ? 'selected': '' ?>>Cerficiado ARL</option>
            <option value="3" <?= $document && $document->type == 3 ? 'selected': '' ?>>Seguridad social</option>
            <option value="4" <?= $document && $document->type == 4 ? 'selected': '' ?>>Otros</option> 
        </select>
    </td>
    <td>
        <textarea class="form-control"  name="document[<?=$key?>][observation]" placeholder="Opcional" data-keyup="hiden-error" rows="1"><?=$document->observation ?? '' ?></textarea>
        </td>
        <td class="remove_row_td" style="width: 10px;">
        <a class="btn btn-outline-danger" data-click="removeRow" data-id="<?=$key?>">
            <i class="la la-trash-o"></i>
        </a>
    </td>
</tr>