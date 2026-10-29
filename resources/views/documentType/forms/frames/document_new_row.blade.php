<tr data-id="<?=$key?>">
    <td>
        @if($document)
            <div class="row">
                <div class="col-md-12">
                    <input type="hidden" name="document[<?=$key?>][id]" value="{{$document->id}}" />
                    <a class="file-link" href="{{route('documentTypes.showfile',['id'=>$document->id])}}" target="_blank"><i class="la la-eye"></i> {{$document->name ?? 'desconocido'}}</a>
                </div>
            </div>
        @else
            <div class="custom-file">
                <input type="file" data-file="<?=$key?>" name="document[<?=$key?>][file]" class="custom-file-input" id="inputGroupFile01">
                <label class="custom-file-label" data-label="<?=$key?>" for="inputGroupFile01">Seleccione un archivo</label>
            </div>
        @endif 
    </td>
    <td>
        <select class="form-control" name="document[<?=$key?>][type_id]" required>
            <option value="">--Seleccionar--</option>
            @foreach($filetypes as $filetype)
                <option value="{{ $filetype->id }}" {{ isset($document->fileType) && $document->fileType->id == $filetype->id ? 'selected': '' }}>{{ $filetype->name }}</option>
            @endforeach
        </select>
    </td>
    <td>
        <textarea class="form-control"  name="document[<?=$key?>][observation]" placeholder="Opcional" rows="1"><?=$document->observation ?? '' ?></textarea>
        </td>
        <td class="remove_row_td" style="width: 10px;">
        <a class="btn btn-outline-danger" data-click="removeRow" data-id="<?=$key?>">
            <i class="la la-trash-o"></i>
        </a>
    </td>
</tr>