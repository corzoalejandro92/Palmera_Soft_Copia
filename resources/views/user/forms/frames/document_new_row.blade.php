<?php

    use App\General\Menu;

?>
<tr data-id="<?=$key?>">
    <td @if(Menu::isController('Crm'))style="width: 40%;" @endif>
        @if($document)
            <div class="row">
                <div class="col-md-12">
                    <input type="hidden" name="document[<?=$key?>][id]" value="{{$document->id}}" data-change="hiden-error" />
                    <a class="file-link" style="white-space: break-spaces;" href="{{route('users.showfile',['id'=>$document->id])}}" target="_blank"><i class="la la-eye"></i> {{$document->name ?? 'desconocido'}}</a>
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
        @if(Menu::isController('User'))
        <select class="form-control" name="document[<?=$key?>][type_id]" required data-change="hiden-error">
            <option value="">--Seleccionar--</option>
            @foreach($filetypes as $filetype)
                <option value="{{ $filetype->id }}" {{ isset($document->type_id ) && $document->type_id == $filetype->id ? 'selected':''}}>{{ $filetype->name }}</option>
            @endforeach
        </select>
        @else
            <label>{{$document->fileType->name}} </label>
        @endif
    </td>
    <td @if(Menu::isController('Crm'))style="width: 45%;" @endif>
        <textarea class="form-control"  name="document[<?=$key?>][observation]" placeholder="Opcional" data-keyup="hiden-error" rows="1"><?=$document->observation ?? '' ?></textarea>
        </td>
        @if(Menu::isController('User'))
        <td class="remove_row_td" style="width: 10px;">
        <a class="btn btn-outline-danger" data-click="removeDocumentRow" data-id="<?=$key?>">
            <i class="la la-trash-o"></i>
        </a>
    </td>
    @endif
</tr>