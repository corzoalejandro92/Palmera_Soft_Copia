

    <div class="card">
        <div class="card-header" id="headingTwo">
          <h5 class="mb-0">
            <a class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseD2" aria-expanded="false" aria-controls="collapseD2">
              Definir cliente
          </a>
          </h5>
        </div>
        <div  aria-labelledby="headingTwo" >
          <div class="card-body">
              <div class="form-group row">
                  <label class="col-4 col-form-label">Cliente</label>
                  <div class="col-8">
                      <select class="form-control select2" data-change="hiden-error" name="crm_id" data-placeholder="Cliente" autocomplete="off">
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
              <div class="row">
                <div class="col-12">
                    <label class="col-form-label">Detalles:</label>
                    <textarea class="form-control" rows="5" name="description" data-keyup="hiden-error" autocomplete="off" placeholder="Detalle aqui este evento">{!! $scheduling->description ?? (isset($description) ? $description : '')!!}</textarea>
                </div>
            </div>
            <hr>
              <div class="form-group row">
                <label class="col-4 col-form-label">Servicio del cronograma</label>
                <div class="col-8">
                    <a href="#" class="btn btn-primary btn-round" data-click="initMiniCalendar">Seleccionar en el calendario</a>
                </div>
                <div class="col-12">
                    <hr>
                </div>
                <div class="col-12">
                    <div id="minicalendar"></div>
                </div>
            </div>
              
          </div>
        </div>
      </div>
