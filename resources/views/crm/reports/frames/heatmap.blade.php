

@section('viewjs2', 'crm/reports/heatmap')
<div role="tabpanel" class="tab-pane" id="heatmap" aria-expanded="false" aria-labelledby="base-heatmap"> 
    <div class="row">
        <div class="col-12">
            <hr>
            <form id="heatmap_form" action="#">
                {{ method_field('POST') }} 
                {{ csrf_field() }}
                <input type="hidden" name="office_id" value="">
                <div class="form-row align-items-center">
                    <div class="col-4">
                        <label for="inlineFormInputGroup"><strong>Área</strong></label>
                        <div class="input-group mb-2">
                          <select class="form-control" name="heatmap_area" >
                            <option>Seleccione una opción</option>
                          </select>
                        </div>
                    </div>
             
                    <div class="col-4">
                        <label for="inlineFormInputGroup"><strong>Reporte</strong></label>
                        <div class="input-group mb-2">
                          <select class="form-control" name="heatmap_technical_report_type" >
                          </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <label for="inlineFormInputGroup"><strong>Control</strong></label>
                        <div class="input-group mb-2">
                          <select class="form-control" name="heatmap_control" >
                            <option value="">Dejar vacío para todos</option>
                          </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <label for="inlineFormInputGroup"><strong>Plaga / Especie</strong></label>
                        <div class="input-group mb-2">
                          <select class="form-control" name="heatmap_pest" >
                            <option value="">Dejar vacío para todas</option>
                          </select>
                        </div>
                    </div>
                    <div class="col-2">
                      <label for="inlineFormInput" class="font-weight-700"><strong>Inicio</strong></label>
                      <input type="date" class="form-control mb-2" id="inlineFormInput" placeholder="Inicio" value="{{date('Y-m-01')}}" name="heatmap_start">
                    </div>
                    <div class="col-2">
                      <label for="inlineFormInputGroup"><strong>Fin</strong></label>
                      <div class="input-group mb-2">
                        <input type="date" class="form-control" id="inlineFormInputGroup" placeholder="Fin" value="{{date('Y-m-t')}}" name="heatmap_end">
                      </div>
                    </div>
            
                    <div class="col-4" style="margin-top:26px;">
                      <a type="button" class="btn btn-primary mb-2" style="color:#fff;" data-click="send-heat-form">Filtrar</a>
                    </div>
                  </div>
            </form>
            
        </div>
        <div class="col-12" align="center">
          <div id="wrapper">
            <section id="sectionCanvas"></section>
            <div id="buttonWrapper">
              <input type="button" id="plus" value="+"><input type="button" id="minus" value="-">
            </div>
          </div>
          <div style="display:inline-block;"; align="center" >
            <div style="display:inline-flex; padding:8px;"><div style="width:22px; height: 10px; background:#c498ff;"></div> <label style="position: relative;top: -5px;padding-left: 5px;">1</label></div>
            <div style="display:inline-flex; padding:8px;"><div style="width:22px; height: 10px; background:cyan;"></div> <label style="position: relative;top: -5px;padding-left: 5px;">2-4</label></div>
            <div style="display:inline-flex; padding:8px;"><div style="width:22px; height: 10px; background:lime;"></div> <label style="position: relative;top: -5px;padding-left: 5px;">5-10</label></div>
            <div style="display:inline-flex; padding:8px;"><div style="width:22px; height: 10px; background:yellow;"></div> <label style="position: relative;top: -5px;padding-left: 5px;">11-14</label></div>
            <div style="display:inline-flex; padding:8px;"><div style="width:22px; height: 10px; background:orange;"></div> <label style="position: relative;top: -5px;padding-left: 5px;">15-19 </label></div>
            <div style="display:inline-flex; padding:8px;"><div style="width:22px; height: 10px; background:red;"></div> <label style="position: relative;top: -5px;padding-left: 5px;">20 o +</label></div>
        </div>
</div>
            
        </div>
    </div>
