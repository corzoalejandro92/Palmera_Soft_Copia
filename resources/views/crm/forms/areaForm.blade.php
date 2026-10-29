@include('layouts.loading.full') 
<div class="modal-header">
    <h4 class="modal-title">{{$title}}</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="mt-2">
        <form id="frm">
            {{ method_field( isset($area->id)?'PUT':'POST') }}
            {{ csrf_field() }}
            <input type="hidden" name="id" value="<?= $area->id ?? '' ?>" />
            <div class="form-group col-12">
                <h5>Nombre</h5>
                <input type="text" class="form-control" autocomplete="nope" name="name" value="{{ $area->name ?? ''}}"
                    placeholder="Razon Social  / Nombre" data-keyup="hiden-error">
            </div>
            @if(isset($area->id))
                <div class="col-12">
                    <div class="form-group">
                        <div class="col-12" >
                            <div class="row skin skin-square">
                                <div class="col-1">
                                    <input type="checkbox" name="enabled" value="1" id="input-checkbox-1" {{ isset( $area->enabled) ? ($area->enabled == "1" ? 'checked': ''):''}}>
                                </div>
                                <div class="col-11">
                                    <label for="input-checkbox-1"> Habilitada</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </form>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    <button type="button" class="btn btn-success round" data-click="save">Guardar</button>
</div>