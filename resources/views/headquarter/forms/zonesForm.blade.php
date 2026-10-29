<div class="modal-header">
    <h5 class="modal-title"><b>{{$headquarter->name}} > {{ isset($zone->id) ? 'EDITAR' : 'AGREGAR'}} ZONA</b></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form id="zones">
        <!-- valores ocultos del formulario -->
        {{ method_field( isset($zone->id)?'PUT' : 'POST') }}  
        {{ csrf_field() }}
        <input type="hidden" value="<?= isset($zone->id) ? $zone->id : '' ?>" name="id" />
        <input type="hidden" value="<?= isset($headquarter->id) ? $headquarter->id : '' ?>" name="headquarter_id" />
        <input type="hidden" value="<?= isset($zone->lat) ?  $zone->lat : '' ?>" name="lat" />
        <input type="hidden" value="<?= isset($zone->lng) ? $zone->lng : '' ?>" name="lng" />

        <div class="form-group row">
            <label class="col-4 col-form-label">Nombre zona*</label>
            <div class="col-8">
                <input class="typeahead form-control" data-keyup="hiden-error" value="<?= isset($zone->name) ? $zone->name :  '' ?>" name="name" type="text" value="" placeholder="Nombre" autocomplete="no">
            </div>
            <div class="col-12">
                <hr>
            </div>
            <div class="col-12">
                <p>Seleccione por favor en un radio la zona que se cubrirá. <i>(Para aplicar los cambios del tamaño del radio cuando sea modificado, presione enter).</i></p>
            </div>
            <label class="col-4 col-form-label">Tamaño del radio n kilómetros*</label>
            <div class="col-8">
                <input class="typeahead form-control" data-keyup="hiden-error" data-change="changeRadius" value="{{isset($zone->radius) ? $zone->radius : '10'}}" min='1' name="radius" type="number"  placeholder="Radio en metros" autocomplete="no">
            </div>
            <div class="col-12" align='center'>
                <br><br>
                <div id="map" ></div>
            </div>
            
        </div>
        
        
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    @if (isset($headquarter->id))
        @can('headquarters.edit')
            <button type="button" class="btn btn-primary round" data-click="saveZone">Editar</button>
        @endcan
    @else
        @can('headquarters.create')
            <button type="button" class="btn btn-success round" data-click="saveZone">Agregar</button>
        @endcan
    @endif
    
</div>
