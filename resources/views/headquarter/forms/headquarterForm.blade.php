<div class="modal-header">
    <h5 class="modal-title"><b>{{ isset($headquarter->id)? 'EDITAR' : 'AGREGAR'}} SEDE</b></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form id="headquarter">
        <!-- valores ocultos del formulario -->
        {{ method_field( isset($headquarter->id)?'PUT' : 'POST') }}  
        {{ csrf_field() }}
        <input type="hidden" value="<?= $headquarter->id ??'' ?>" name="id" />
        
            <div class="form-group row">
                <div class="col-6">
                    <label class="col-form-label">Departamento*</label>
                    <select class="form-control select2" data-change="hiden-error" id="state_id" name='states_id'  data-placeholder="Departamento" autocomplete="off">
                        <option value="">Departamento</option>
                        <?php if (isset($headquarter->cities)): ?>
                            <option value="<?= $headquarter->cities->states->id; ?>" selected=""><?= $headquarter->cities->states->name; ?></option>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-6">
                    <label class="col-form-label">Ciudad*</label>
                    <select class="form-control select2" data-change="hiden-error" id="city_id" name='city_id'  data-placeholder="Ciudad" autocomplete="off">
                        <option value="">Ciudad</option>
                        
                        <?php
                        if (!empty($headquarter->cities)): ?>
                            <option value="<?= $headquarter->cities->id; ?>" selected=""><?= $headquarter->cities->name; ?></option>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-2 col-form-label">Dirección*</label>
                
                <div class="col-10">
                    <div class="display-hidden">
                        <input type="text" name="lat" value="{{$headquarter->lat ?? ''}}" />
                        <input type="text" name="lng" value="{{$headquarter->lng ?? ''}}" />
                    </div>
                   
                    <input  class="mapControls form-control searchMapInput" style="margin-bottom: 5px;" data-keyup="hiden-error" type="text" name="address" placeholder="Buscar dirección"  autocomplete="no" value="{{$headquarter->address??''}}"> 
                    <label>Ubicar dirección en el mapa: </label>&nbsp;<a class="btn btn-info" type="button" data-click="openMap" style="margin-bottom: 5px;" href="#">Abrir mapa</a>    
                    <span class="badge badge-success font-medium-1 display-hidden openMapSuccess"><i class="las la-check-circle"></i> Ubiación definida correctamente</span>                     
                </div>
            </div>

        <div class="form-group row">
            <label class="col-2 col-form-label">Nombre*</label>
            <div class="col-10">
                <input class="typeahead form-control" data-keyup="hiden-error" value="<?= $headquarter->name??'' ?>" name="name" type="text" value="" placeholder="Nombre" autocomplete="Off">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-2 col-form-label">Teléfono</label>
            <div class="col-10">
                <input class="form-control" data-keyup="hiden-error" name="phone" type="number" value="<?= $headquarter->phone??'' ?>" placeholder="Teléfono" autocomplete="off">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-2 col-form-label">Celular*</label>
            <div class="col-10">
                <input class="form-control" data-keyup="hiden-error" value="<?= $headquarter->cell_phone??'' ?>" name="cell_phone" type="number" placeholder="Celular" autocomplete="off">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-2 col-form-label">Correo*</label>
            <div class="col-10">
                <input class="form-control" data-keyup="hiden-error" name="email" type="email" value="{{ $headquarter->email ?? '' }}" placeholder="Correo Electrónico" autocomplete="off">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-2 col-form-label">Nit<span class="text-danger">*</span></label>
            <div class="col-10">
                <input class="form-control" data-keyup="hiden-error" name="nit" type="text" value="{{ $headquarter->nit ?? '' }}" placeholder="Nit..." autocomplete="off">
            </div>
        </div>

        @if(\App\Integration::find(1)->enabled && 
in_array(
	\App\Headquarter::find(
		session('headquarter_id')
	)->id , \App\Integration::find(1)->headquarters
))
            <div class="form-group row">
                <label class="col-3 col-form-label">ID Usuario Foxcon:</label>
                <div class="col-9">
                    <input class="form-control" data-keyup="hiden-error" name="foxcon_user_id" type="text" value="{{ $headquarter->foxcon_user_id ?? '' }}" placeholder="ID del usuario de compras de foxcon" autocomplete="off">
                </div>
            </div>
        @endif

        <button type="submit" hidden></button>
    </form>
</div>
<div class="modal-footer">
    <i>La configuración de zonas de cobertura se realiza despues de guardar</i>
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    @if (isset($headquarter->id))
        @can('headquarters.edit')
            <button type="button" class="btn btn-primary round" data-click="save">Editar</button>
        @endcan
    @else
        @can('headquarters.create')
            <button type="button" class="btn btn-success round" data-click="save">Agregar</button>
        @endcan
    @endif
</div>