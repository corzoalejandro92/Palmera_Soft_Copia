
<tr data-id="<?=$key?>">
    <input type="hidden" name="cdocument[<?=$key?>][offices_id]" value="">
    <!-- @if(isset($seatype))
        <td width="20%">
            <select class="form-control" data-change="hiden-error" name="cdocument[<?=$key?>][office_id]" required>
                <option value="">--Seleccionar--</option>
                @if($cdocument && $cdocument->filable_type == 'App\Office')
                    <option value="{{$cdocument->filable->id}}" selected>{{ $cdocument->filable->name .' - '. $cdocument->filable->full_address }}</option>
                @endif
            </select>
        </td>
    @endif -->

    <td width="20%">
        @if($cdocument)
            <div class="row">
                <div class="col-md-12">
                    <input type="hidden" name="cdocument[<?=$key?>][id]" value="{{$cdocument->id}}" data-change="hiden-error" />
                    <a class="file-link" href="{{route('crms.showfile',['id'=>$cdocument->id])}}"  target="_blank"><i class="la la-eye"></i> {{$cdocument->name ?? 'desconocido'}}</a>
                </div>
            </div>
        @else
            <div class="custom-file">
                <input type="file" data-keyup="hiden-error" data-file="<?=$key?>" name="cdocument[<?=$key?>][file]" class="custom-file-input" id="inputGroupFile01">
                <label class="custom-file-label" data-label="<?=$key?>" for="inputGroupFile01">Seleccione un archivo</label>
            </div>
        @endif 
    </td>

    <td width="20%">
        <select class="form-control" data-change="hiden-error" name="cdocument[<?=$key?>][type_id]" required>
            <option value="">--Seleccionar--</option>
            @foreach($filetypes as $filetype)
                <option value="{{ $filetype->id }}" {{ isset($cdocument->type_id) && $cdocument->type_id === $filetype->id ? 'selected': '' }}> {{ $filetype->name }}</option>
            @endforeach
        </select>
    </td>
    <td>
        <textarea class="form-control" data-keyup="hiden-error" name="cdocument[<?=$key?>][observation]" placeholder="Opcional" rows="1"><?=$cdocument->observation ?? '' ?></textarea>
        </td>
        <td class="remove_row_td" style="width: 10px;">
        <a class="btn btn-outline-danger" data-click="removeRowDocument" data-id="<?=$key?>">
            <i class="la la-trash-o"></i>
        </a>
    </td>
</tr>