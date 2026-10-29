<div class="modal-header">
    <h4 class="modal-title">Asociar QR > #{{$fileExtend->num}} -> {{$fileExtend->extendible->category ? $fileExtend->extendible->category->name: ''}}</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="mt-2">
        <form id="frm-associateQr">
            {{ csrf_field() }}
            <div class="form-group col-12">
                <input type="hidden" name="qr_old" value="{{$fileExtend->qr->id ?? ''}}">
                <input type="hidden" name="qr_office" value="">
                <input type="hidden" name="file_extend_id" value="{{$fileExtend->id}}">
                <h5>QR<span class="text-danger">*</span></h5>
                <select class="form-control select2" data-change="hiden-error" name="qr"  data-placeholder="Qr" autocomplete="off">
                    @if (isset($fileExtend->qr))
                        <option value="<?= $fileExtend->qr->id; ?>" selected ><?= $fileExtend->qr->token; ?></option>
                    @endif
                </select>
            </div>
            <div class="form-group col-12">
                @if (!isset($fileExtend->qr))
                <div class="form-check">
                    <input class="form-check-input" name="is_new" value="1" type="checkbox" value="" id="defaultCheck1">
                    <label class="form-check-label" for="defaultCheck1">
                        Afectar inventario
                    </label>
                </div>
            @else
                <input type="hidden" name="update_qr" value="1">
            @endif
                <div class="form-check" onclick="refresh()">
                    <input class="form-check-input " type="checkbox"  value="1" id="defaultCheck3" >
                    <label class="form-check-label" for="defaultCheck3">
                        Qr de Técnico
                    </label>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    <button type="button" class="btn btn-success round" data-click="saveAssociateQr">Guardar</button>
</div>