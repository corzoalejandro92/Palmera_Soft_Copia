<tr data-id="<?=$key?>">
    <input type="hidden" name="method[<?=$key?>][id]" value="{{$method->id ?? ''}}" />
    <td>
        @if(isset($method->id))
            <div class="row">
                <div class="col-md-12">
                    <div class="custom-file">
                        <input type="hidden" value="<?= $method->id ?? ''; ?>" name="method[<?=$key?>][id]" />
                        <input type="file" data-input="fileImage3" accept="image/*" onchange="loadImage3(this)" class="custom-file-input" value="<?= $method->avatar ?? 'images/default/method.png'; ?>" name="method[<?=$key?>][avatar]" /> 
                        <label  class="custom-file-label" data-label="<?=$key?>" for="inputGroupFile01"><img  style="position: relative;top: -6px;" class="avatar xxs" src="<?= url($method->avatar ?? 'images/default/method.png' ); ?>" onerror="this.src='{{url("images/default/method.png")}}'" /> <span style="position: relative;top: -12px;">Cambiar imagen</span></label>
                    </div>
                </div>
            </div>
        @else
            <div class="custom-file">
                <input type="hidden" value="<?= $method->id ?? ''; ?>" name="method[<?=$key?>][id]" />
                <input type="file" data-input="fileImage3" accept="image/*" onchange="loadImage3(this)" class="custom-file-input" value="<?= $method->avatar ?? 'images/default/method.png'; ?>" name="method[<?=$key?>][avatar]" /> 
                <label class="custom-file-label" data-label="<?=$key?>" for="inputGroupFile01">
                    <img style="position: relative;top: -6px;" class="avatar xxs" src="<?= url($method->avatar ?? 'images/default/method.png' ); ?>" onerror="this.src='{{url("images/default/method.png")}}'" />
                    <span style="position: relative;top: -12px;">Cambiar imagen</span></label>
            </div>
        @endif 
    </td>
    <td>
        <input class="form-control" data-keyup="hiden-error" value="<?= $method->name??'' ?>" name="method[<?=$key?>][name]" type="text" value="" placeholder="Nombre del Metodo" autocomplete="off">
    </td>
    <td>
        <input class="form-control" data-keyup="hiden-error" value="<?= $method->doses??'' ?>" name="method[<?=$key?>][doses]" type="text" value="" placeholder="Dosis" autocomplete="off">
    </td>
    <td>
        <textarea class="form-control"  name="method[<?=$key?>][description]" placeholder="Descripción" rows="1"><?=$method->description ?? '' ?></textarea>
    </td>
        <td class="remove_row_td" style="width: 10px;">
            <a class="btn btn-outline-danger" data-click="removeRowMethod" data-id="<?=$key?>">
                <i class="la la-trash-o"></i>
            </a>
        </td>
</tr>