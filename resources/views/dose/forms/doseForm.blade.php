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
            {{ method_field( isset($dose->id)?'PUT':'POST') }}
            {{ csrf_field() }}
            <input type="hidden" name="id" value="<?= $dose->id ?? '' ?>" />
            
            <div class="form-group col-12">
                <h6>Inicio Rango</h6>
                <input type="text" class="form-control" autocomplete="nope" name="start_range" value="{{ $dose->start_range ?? ''}}"
                    placeholder="Inicio Rango" data-keyup="hiden-error">
            </div>
    
            <div class="form-group col-12">
                <h6>Fin Rango</h6>
                <input type="text" class="form-control" autocomplete="nope" name="end_range" value="{{ $dose->end_range ?? ''}}"
                    placeholder="Fin Rango" data-keyup="hiden-error">
            </div>  

            <div class="form-group col-12">
                <h6>Unidad de medida</h6>
                <select class="form-control select2" id="unit" name="unit">
                    <option value="">--Seleccionar--</option>
                    <?php if (isset($dose->unit)): ?>
                    <option value="<?= $dose->unit->id; ?>" selected=""><?= $dose->unit->name; ?></option>
                    <?php endif; ?>
                </select>
            </div>
            @if(isset($dose->id))
                <div class="col-12">
                    <div class="form-group">
                        <div class="col-12" >
                            <div class="row skin skin-square">
                                <div class="col-1">
                                    <input type="checkbox" name="enabled" value="1" id="input-checkbox-1" {{ isset( $dose->enabled) ? ($dose->enabled == "1" ? 'checked': ''):''}}>
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