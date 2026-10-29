<div class="modal-header">
    <h5 class="modal-title"><b>{{ isset($warehouse->id)? 'EDITAR' : 'AGREGAR'}} BODEGA</b></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form id="warehouseForm" action="{{$type['action']}}">
        <!-- valores ocultos del formulario -->
        {{ method_field( isset($warehouse->id)?'PUT' : 'POST') }}  
        {{ csrf_field() }}
        <input type="hidden" value="<?= $warehouse->warehousable->id ??'' ?>" name="id" />
        <input type="hidden" value="<?= $warehouse->id ??'' ?>" name="id_warehouse" />
        <input type="hidden" value="<?= $parent->id??'' ?>" name="parent" />
        
        <!-- valores visibles del formulario -->
        <div class="form-group row">
            <label class="col-4">Tipo de bodega</label>
            <div class="col-8">
                <input class="typeahead form-control disabled" style="background-color: #ddd" data-keyup="hiden-error" type="text" value="<?= $type['label']??'' ?>" readonly="readonly">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-4">Bodega Padre</label>
            <div class="col-8">
                <input class="typeahead form-control" data-keyup="hiden-error" type="text" value="<?= $parent->name??'' ?>" readonly="readonly">
            </div>
        </div>
        
        @if($type['label'] == 'Sede')
            <div class="form-group row">
                <div class="col-6">
                    <label class="col-form-label">Departamento*</label>
                    <select class="form-control select2" data-change="hiden-error" id="state_id"  data-placeholder="Departamento" autocomplete="off">
                        <option value="">Departamento</option>
                        <?php if (!is_null($state)): ?>
                            <option value="<?= $state->id; ?>" selected=""><?= $state->name; ?></option>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-6">
                    <label class="col-form-label">Ciudad*</label>
                    <select class="form-control select2" data-change="hiden-error" id="city_id" name='city_id'  data-placeholder="Ciudad" autocomplete="off">
                        <option value="">Ciudad</option>
                        
                        <?php
                        if (!empty($warehouse->warehousable->cities)): ?>
                            <option value="<?= $warehouse->warehousable->cities->id; ?>" selected=""><?= $warehouse->warehousable->cities->name; ?></option>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-4 col-form-label">Zonas de cobertura*</label>
                <div class="col-8">
                    <select class="form-control select2" data-change="hiden-error" id="zones" name="zones[]" data-placeholder="Zonas de Cobertura" autocomplete="off" multiple>
                        @if(isset($warehouse->warehousable))
                            @foreach($warehouse->warehousable->zones as $key => $zone)
                            <option value="{{ $zone->id }}" selected >{{$zone->name}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
        @endif

        <div class="form-group row">
            <label class="col-4 col-form-label">Nombre*</label>
            <div class="col-8">
                <input class="typeahead form-control" data-keyup="hiden-error" value="<?= $warehouse->name??'' ?>" name="name" type="text" value="" placeholder="Nombre" autocomplete="off">
            </div>
        </div>
        
        @if(in_array($type['label'],['Sede','Bodega']))
        
            <div class="form-group row">
                <label class="col-4 col-form-label">Dirección*</label>
                <div class="col-8">
                    <input class="typeahead form-control" data-keyup="hiden-error" value="<?= $warehouse->warehousable->address??'' ?>" name="address" type="text" value="" placeholder="Dirección" autocomplete="off">
                </div>
            </div>
        
        @endif
        
        

        <div class="form-group row">
            <label class="col-4 col-form-label">Teléfono</label>
            <div class="col-8">
                <input class="form-control" data-keyup="hiden-error" name="phone" type="number" value="<?= $warehouse->warehousable->phone??'' ?>" placeholder="Teléfono" autocomplete="off">
            </div>
        </div>
        <button type="submit" hidden></button>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    <button type="button" class="btn btn-success round" data-click="save">{{ isset($warehouse->id)? 'Editar' : 'Agregar'}}</button>
</div>