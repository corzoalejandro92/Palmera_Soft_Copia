
<h6><i class="step-icon ft-calendar"></i>Fecha y horario</h6>
<fieldset class="fieldspace">
    <div class="row">
        <div class="col-sm-12 col-md-8 col-lg-8 offset-md-2">
            <h5>Dia de la ruta a programar:</h5>
            <div class="row">
                <div class="col-md-6 col-sm-6">
                    <div class="input-group">
                        <div class="input-group-prepend" >
                            <span class="input-group-text">
                                <span class="ft-calendar"></span>
                            </span>
                        </div>
                        <input class="form-control datepicker pickadate-translations" style="width: 80%;" name="init_date" type="date" placeholder="A partir del"  data-change="hiden-error">
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8 offset-md-2 col-sm-12">
            <hr>
        </div>
        <div class="col-md-9 offset-md-2 col-sm-12">
            <h5>Disponibilidades técnicas</h5>
                <select class="form-control select2" data-change="hiden-error" name="availabilities[]" multiple>
                    <option value="">-Seleccione una o mas disponibilidades-</option>
                </select>
            <p><small class="text-muted mb-8">Este horario representa la <code>jornada laboral</code> en la que se programará los técnicos y los clientes</small><br></p>
        </div>
        <div class="col-md-9 offset-md-2 col-sm-12 users">
            <div class="users-loading">@include('layouts.loading.min')</div>
            <div class="users-result"></div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 offset-md-2 col-sm-12">
            <hr>
        </div>
        <div class="col-md-9 offset-md-2 col-sm-12">
            <h5>Tipos de servicio que se programarán</h5>
                <select class="form-control select2" data-change="hiden-error" name="services[]" multiple="multiple"></select>

                <p><small class="text-muted mb-8">Inique al sistema que tipos de servicios se programarán, esto nos permitirá ser mas precisos al momento de seleccionar servicios pendientes en los cronogramas de los coordinadores MIP, ya que únicamente tomaremos los servicios que coincidan con tu selección.</small><br></p>
        </div>
        <div class="col-md-9 offset-md-2 col-sm-12">
            <div class="form-check" align="right">
                <input class="form-check-input" type="checkbox"  data-click="checkAllServices" id="checkAllServices">
                <label class="form-check-label" for="checkAllServices">
                  Seleccionar todos los servicios
                </label>
              </div>
        </div>
        <div class="col-md-9 offset-md-2 col-sm-12">
            <fieldset class="border p-2">
                <legend  class="w-auto">Opciones de automatización</legend>
                <input class="form-check-input ml-1" type="checkbox" name="keep_last_services" value="1" id="keep_last_services" >
                <label class="form-check-label ml-3" for="keep_last_services" style="vertical-align: top;">
                   Recuperar los movimientos y servicios realizados para la fecha de ruta seleccionada
                </label><i class="la la-info-circle text-info" style="cursor: pointer" data-click="showinfo"  data-title="Recuperar los movimientos y servicios realizados para la fecha de ruta seleccionada" data-text="habilita esta opción si te saliste de la automatización por equivocación, para ajustar algo en el cronograma o por error del sistema y deseas continuar con lo que estabas haciendo, Si necesitas comenzar una automatizacion desde cero NO habilites esta opción "></i><br>
                <input class="form-check-input ml-1" type="checkbox" name="only_techical_fixes" value="1" id="only_techical_fixes" >
                <label class="form-check-label ml-3" for="only_techical_fixes" style="vertical-align: top;">
                   Solo programar clientes que tengan como técnico fijo a alguno de los técnicos seleccionados
                </label><i class="la la-info-circle text-info" style="cursor: pointer" data-click="showinfo"  data-title="Solo programar clientes que tengan como técnico fijo a alguno de los técnicos seleccionados" data-text="No se mostrarán clientes en el paso 2 que no tengan como técnicos fijos a alguno de los tecnicos seleccionados"></i><br>
                
                 <input class="form-check-input ml-1" type="checkbox" name="include_journal_time" value="1" id="include_journal_time" >
                 <label class="form-check-label ml-3" for="include_journal_time" style="vertical-align: top;">
                    @new(['date' => '2021-10-04']) Incluir la jornada laboral del técnico como observación de ruta
                 </label><i class="la la-info-circle text-info" style="cursor: pointer" data-click="showinfo"  data-title="Incluir la jornada laboral del técnico como observación de ruta" data-text="Esta opción mostrará las disponibilidades seleccionadas en la ruta del técnico como una observacion general"></i><br>
             
                <input class="form-check-input ml-1" type="checkbox" name="no_show_cancelled" value="1" id="no_show_cancelled" >
                <label class="form-check-label ml-3" for="no_show_cancelled" style="vertical-align: top;">
                    @new(['date' => '2021-10-04']) No mostrar servicios cancelados/no realizados en el tiempo 
                </label><i class="la la-info-circle text-info" style="cursor: pointer" data-click="showinfo"  data-title="No mostrar servicios cancelados/no realizados en el tiempo" data-text="A partir de ahora, los servicios cancelados y no realizados no apareceran en el paso 2 si seleccionas esta opción."></i><br>
             
                </fieldset>
        </div>
    </div>
</fieldset>