<div class="modal-header">
    <h5 class="modal-title">CREAR CLIENTE</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
    <div class="modal-body" style="padding: 80px;">
        <form id="customerSimple" >
        {{ method_field('POST') }}
        {{ csrf_field() }}
        <!-- Formulario de detalle de contacto -->
                <div class="col-12">
                        <div class="form-group">
                            <label>Cliente<span class="text-danger">*</span></label>
                            <input type="text" data-keyup="hiden-error" class="form-control" autocomplete="off" name="crm_id"  placeholder="Ingrese el Cliente">
                       </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                        <label>Sucursal<span class="text-danger">*</span></label>
                            <input type="text" data-keyup="hiden-error" class="form-control" autocomplete="off" name="office_id"  placeholder="Ingrese la sucursal">
                        </div>
                    </div>
    </form>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    @can('crms.create')
    <button type="button" class="btn btn-success round" data-click="{{$data_click}}">Guardar</button>
    @endcan
</div>