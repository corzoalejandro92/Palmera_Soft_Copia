
<div class="row">
    <div class="col-12">
        <label class="col-form-label">Detalles:</label>
        <textarea class="form-control" rows="5" name="description" data-keyup="hiden-error" autocomplete="off" placeholder="Detalle aqui este evento">{!!$scheduling->description ?? '' !!}</textarea>
    </div>
</div>
<hr>

<div id="accordion" >
    <div class="card collapse-icon accordion-icon-rotate left">
      <div class="card-header" id="headingOne">
        <h5 class="mb-0">
          <a class="btn btn-link" data-toggle="collapse" data-target="#collapseD1" aria-expanded="false" aria-controls="collapseD1">
							Definir lugar/ubicación
					</a>
        </h5>
      </div>
  
      <div id="collapseD1" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
        <div class="card-body">

            <div class="row">
                <div class="col-9">
                    <label class="col-form-label">Dirección:</label>
                    <input class="form-control select2" data-keyup="hiden-error" name="address" value="{{$scheduling->crm_address?? ''}}" placeholder="Dirección física del lugar" autocomplete="off" />

                </div>
                <div class="col-3">
                    <label class="col-form-label">&nbsp;</label>
                  
                    <a class="btn btn-primary" style="color:#fff;display: block;" data-click="findInMap">Buscar</a>
                </div>

                <div class="display-hidden">
                    <input type="text" name="lat" value="{{$scheduling->crm_lat ?? ''}}">
                    <input type="text" name="lng" value="{{$scheduling->crm_lng ?? ''}}">
                </div>
            </div>
            <hr>
            <div id="map">

            </div>
        </div>
      </div>
    </div>
    <div class="card card collapse-icon accordion-icon-rotate left">
      <div class="card-header" id="headingTwo">
        <h5 class="mb-0">
          <a class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseD2" aria-expanded="false" aria-controls="collapseD2">
            Definir cliente
        </a>
        </h5>
      </div>
      <div id="collapseD2" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
        <div class="card-body">
            <div class="form-group row">
                <label class="col-4 col-form-label">Cliente</label>
                <div class="col-8">
                    <select class="form-control select2" data-change="hiden-error" name="crm_id" data-placeholder="Cliente" autocomplete="off">
                        <option value="">Seleccione un cliente</option>
                        <?php if (isset($scheduling->crm)): ?>
                            <option value="<?= $scheduling->crm->id; ?>" selected=""><?= $scheduling->crm->name; ?></option>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            
            <div class="form-group row">
                <label class="col-4 col-form-label">Sucursal</label>
                <div class="col-8">
                    <select class="form-control select2" data-change="hiden-error" name="office_id" data-placeholder="Sucursal" autocomplete="off">
                        <option value="">Seleccione una sucursal</option>
                        <?php if (isset($scheduling->office)): ?>
                            <option value="<?= $scheduling->office->id; ?>" selected=""><?= $scheduling->office->name; ?></option>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-4 col-form-label">Contacto</label>
                <div class="col-8">
                    <select class="form-control select2" data-change="hiden-error" name="contact_id" data-placeholder="Contacto cliente" autocomplete="off">
                        <option value="">Seleccione un contacto</option>
                       <?php if (isset($scheduling->contact)): ?>
                            <option value="<?= $scheduling->contact->id; ?>" selected=""><?= $scheduling->contact->name; ?></option>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            
        </div>
      </div>
    </div>

  </div>
