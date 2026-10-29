<tr data-id="<?=$key?>">
        @if($observation)
            <input type="hidden" name="obs[<?=$key?>][id]" value="{{$observation->id ?? ''}}" />
        @endif 
        <input type="hidden" name="obs[<?=$key?>][type_id]" value="1">
    <td>
        <select class="form-control select2" data-change="hiden-error" name="obs[<?=$key?>][role]" data-placeholder="Cliente" autocomplete="off">
            <option value="">Seleccione un rol</option>
            @if(isset($observation->role))
                <option value="{{ $observation->role->id }}" selected>{{ $observation->role->name}}</option>
            @endif
        </select> 
    </td>

    <td class="td-100">
        <textarea class="form-control"  name="obs[<?=$key?>][observation]" placeholder="Opcional" data-keyup="hiden-error" rows="1"><?=$observation->content ?? '' ?></textarea>
    </td>
    <td>
        @if(isset($observation->file))
            <div class="row">
                <div class="col-md-12">
                    <input type="hidden" name="obs[<?=$key?>][id]" value="{{$observation->file->id}}" data-change="hiden-error" />
                    <a class="file-link" href="{{route('scheduling.showfile',['id'=>$observation->file->id])}}" target="_blank"><i class="la la-eye"></i> {{$observation->file->name ?? 'desconocido'}}</a>
                </div>
            </div>
        @else
            <div class="custom-file">
                <input type="file" data-file="<?=$key?>" name="obs[<?=$key?>][file]" class="custom-file-input" id="inputGroupFile01" data-change="hiden-error">
                <label class="custom-file-label" data-label="<?=$key?>" for="inputGroupFile01">Abrir</label>
            </div>
        @endif 
    </td>
    <td class="remove_row_td" style="width: 10px;">
        <a class="btn btn-outline-danger" data-click="removeObservationRow" data-id="<?=$key?>">
            <i class="la la-trash-o"></i>
        </a>
    </td>
</tr>