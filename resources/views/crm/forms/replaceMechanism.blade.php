<div class="modal-header">
    <h4 class="modal-title">Remplazar Mecanismo = {{$fileExtend->extendible->name ?? ''}} > N: {{$fileExtend->num ?? ''}}</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="mt-2">
        <form id="frm-replace">
            {{ csrf_field() }}
                <div class="form-group col-12">
                       <h5>Mecanismo <span class="text-danger">*</span></h5>
                            <select class="form-control" id="new_file" name="new_file" required data-change="hiden-error">
                                <option value="">--Seleccionar--</option>
                                @if(isset($fileExtend->extendible->id))
                                    <option value="{{ $fileExtend->extendible->id }}" selected>{{ $fileExtend->extendible->name }}</option>
                                @endif
                            </select>
                </div>
                <div class="form-group col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="qr_tecnic"  value="1" id="defaultCheck3" >
                        <label class="form-check-label" for="defaultCheck3">
                            Qr de Técnico
                        </label>
                    </div>
                </div>
                <div class="form-group col-12">
                    <input type="hidden" id="old_file" name="old_file" value="{{$fileExtend->id}}">
                </div>
        </form>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    <button type="button" class="btn btn-success round" data-id="{{$fileExtend->id}}" data-click="saveReplace">Guardar</button>
</div>