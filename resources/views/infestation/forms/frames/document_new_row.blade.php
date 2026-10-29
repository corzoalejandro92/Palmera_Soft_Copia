<tr data-id="<?=$key?>">
    <input type="hidden" name="document[<?=$key?>][id]" value="{{$plaga->id ?? ''}}" />
    <td style="width: 20%">
        @if(isset($plaga->id))
            <div class="row">
                <div class="col-md-12">
                    <input type="hidden" name="plaga[<?=$key?>][id]" value="{{$plaga->id}}" />
                    
                    <div class="custom-file">
                        <input type="hidden" value="<?= $plaga->id ?? ''; ?>" name="plaga[<?=$key?>][id]" />
                        <input type="file" data-input="fileImage3" accept="image/*" onchange="loadImage3(this)" class="custom-file-input" value="<?= $plaga->full_avatar ?? 'images/default/plaga.png'; ?>" name="plaga[<?=$key?>][avatar]" /> 
                        <label  class="custom-file-label" data-label="<?=$key?>" for="inputGroupFile01"><img  style="position: relative;top: -6px;" class="avatar xxs" src="<?= url($plaga->full_avatar ?? 'images/default/plaga.png' ); ?>" onerror="this.src='{{url("images/default/plaga.png")}}'" /> <span style="position: relative;top: -12px;">Cambiar imagen</span></label>
                    </div>
                </div>
            </div>
        @else
            <div class="custom-file">
                <input type="hidden" value="<?= $plaga->id ?? ''; ?>" name="plaga[<?=$key?>][id]" />
                <input type="file" data-input="fileImage3" accept="image/*" onchange="loadImage3(this)" class="custom-file-input" value="<?= $plaga->full_avatar ?? 'images/default/plaga.png'; ?>" name="plaga[<?=$key?>][avatar]" /> 
                <label class="custom-file-label" data-label="<?=$key?>" for="inputGroupFile01">
                    <img style="position: relative;top: -6px;" class="avatar xxs" src="<?= url($plaga->full_avatar ?? 'images/default/plaga.png' ); ?>" onerror="this.src='{{url("images/default/plaga.png")}}'" />
                    <span style="position: relative;top: -12px;">Cambiar imagen</span></label>
            </div>
        @endif 
    </td>
    <td style="width: 80%">
        <input class="form-control" data-keyup="hiden-error" value="<?= $plaga->name??'' ?>" name="plaga[<?=$key?>][name]" type="text" value="" placeholder="Nombre de la plaga" autocomplete="off">
    </td>
        <td class="remove_row_td" style="width: 10px;">
            @canAny(['infestations.create', 'infestations.edit'])
                <a class="btn btn-outline-danger" data-click="removeRow" data-id="<?=$key?>">
                    <i class="la la-trash-o"></i>
                </a>
            @endcanAny
        </td>
</tr>
<tr data-id="<?=$key?>-2">
    <td colspan="3">
        <textarea class="form-control"  name="plaga[<?=$key?>][description]" placeholder="Descripción" rows="1"><?=$plaga->description ?? '' ?></textarea>
    </td>
</tr>
<tr data-id="<?=$key?>-3">
    <td colspan="3">
        <textarea class="form-control"  name="plaga[<?=$key?>][causes]" placeholder="Posibles causas de su presencia" rows="1"><?=$plaga->causes ?? '' ?></textarea>
    </td>
</tr>
<tr data-id="<?=$key?>-3">
    <td colspan="3" style="height: 40px;">
        
    </td>
</tr>