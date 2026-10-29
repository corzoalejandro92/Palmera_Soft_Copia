<div class="modal-header">
    <h5 class="modal-title">{{isset($setting->id)?'EDITAR CONFIGURACION':'CREAR CONFIGURACION'}}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form id="settings">
        <!-- valores ocultos del formulario -->
        {{ method_field( isset($setting->id)?'PUT':'POST') }}
        {{ csrf_field() }}
        <input type="hidden" value="<?= $setting->id ??'' ?>" name="id" />


        <div class="form-group row">
            <label class="col-4 col-form-label">Sede</label>
            <div class="col-8">
                <input type="hidden" name="headquarter_id" value="{{session('headquarter_id')}}">
                <input type="text" class="form-control" readonly value="{{session('headquarter_name')}}">
            </div>
        </div>


        <div class="form-group row">
            <label class="col-4 col-form-label">Factura</label>
            <div class="col-8">
                <input class="typeahead form-control" data-keyup="hiden-error" value="<?= $setting->invoice??'' ?>"
                    name="invoice" type="text" value="" placeholder="# Factura" autocomplete="off">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-4 col-form-label">Cotización</label>
            <div class="col-8">
                <input class="typeahead form-control" data-keyup="hiden-error" value="<?= $setting->quote??'' ?>"
                    name="quote" type="text" value="" placeholder=" # Cotizacione" autocomplete="off">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-4 col-form-label">Comprobante de ingreso</label>
            <div class="col-8">
                <input class="form-control" data-keyup="hiden-error" name="proof_income" type="text"
                    value="<?= $setting->proof_income??'' ?>" placeholder="# Ingreso" autocomplete="off">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-4 col-form-label">Comprobantes de egreso</label>
            <div class="col-8">
                <input class="form-control" data-keyup="hiden-error" name="proof_discharge" type="text"
                    value="<?= $setting->proof_discharge??'' ?>" placeholder="# Egreso" autocomplete="off">
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    <button type="button" class="btn btn-success round" data-click="save">{{isset($setting->id)?'Actualizar':'Guardar'}}</button>
</div>