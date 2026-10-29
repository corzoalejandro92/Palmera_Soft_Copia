
<div class="modal-body">
    <form id="crmUser" action="crm/user-save" enctype="multipart/form-data">
        {{ method_field('POST')}}
        {{ csrf_field() }}
        <input type="hidden" name="id" value="{{$id}}"/>
        <input type="hidden" name="office_id" id="office_id_crmUser" value="{{$office->id}}"/>
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="form-group m-form__group row">
                    <label class="col-12 col-form-label">Usuario</label>
                    <div class="col-12">
                        <select class="form-control" id="user_id" name="user_id" data-change="hiden-error">
                            <option value="">--seleccionar--</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group col-sm-12 col-md-12">
            <h5>Sucursales (Dejar vacío para todas)</h5>
            <select class="form-control" name="office_id[]" data-change="hiden-error" multiple>
                <option value="">--seleccionar--</option>
                <?php if (!empty($office)): ?>
                        <option value="<?= $office->id; ?>" selected="">{{ $office->name}}</option>
                <?php endif; ?>
                <?php if (!empty($contact->offices)): ?>
                    @foreach ($contact->offices as $office)
                        <option value="<?= $office->id; ?>" selected="">{{ $office->name}} - {{ $office->full_address }}</option>
                    @endforeach
                <?php endif; ?>
            </select>
            <div style="margin-top:8px;">
            <label for="checkbox">
                <input type="checkbox" data-click="checkAllOffices" id="checkbox"> Seleccionar todas
            </label>
            </div>
            
        </div>
        <div class="form-group col-sm-12 col-md-12">
            <h5>Autorizado para temas de</h5>
            <select class="form-control typeContact" name="contact_type_id[]" data-change="hiden-error" multiple>
                <option value="">--seleccionar--</option>
                <?php if (!empty($contact->types)): ?>
                    @foreach ($contact->types as  $type)
                        <option value="<?= $type->id; ?>" selected=""><?= $type->name; ?></option>
                    @endforeach
                <?php endif; ?>
            </select>
        </div>

            
         
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="alert alert-info" role="alert">
                    Al asociar un usuario estás dando el total acceso al mismo para que pueda consultar la toda la información de este cliente. El contacto que desees asociar aqui ya debe estar registrado en un cliente anterior y debe tener el acceso a clientes habilitado, de lo contrario no aparecerá en esta lista.
                </div>
            </div>
        </div>
    </form>
</div>
