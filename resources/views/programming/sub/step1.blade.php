@section('viewjs','programming/automation')
<h6><i class="step-icon ft-calendar"></i>Fecha y horario</h6>
<fieldset class="fieldspace">
    <div class="row">
        <div class="col-sm-12 col-md-8 col-lg-8 offset-md-2">
            <h5>Rango de fecha a programar</h5>
            <div class="row">
                <div class="col-md-6 col-sm-6">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <span class="ft-calendar"></span>
                            </span>
                        </div>
                        <input id="picker_from" class="form-control datepicker" name="date_from" type="date" placeholder="Fecha inicio">
                    </div>
                </div>

                <div class="col-md-6 col-sm-6">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <span class="ft-calendar"></span>
                            </span>
                        </div>
                        <input id="picker_to" class="form-control datepicker" name="date_to" type="date" placeholder="Fecha fin">
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

                <div class="input-group">
                    <div class="input-group-prepend">
                            <span class="input-group-text">
                                <span class="ft-calendar"></span>
                            </span>
                    </div>
                    <select class="form-control" name="availabilities[]" multiple>
                        <option>-Seleccione una o mas disponibilidades-</option>
                    </select>
                </div>

            <p><small class="text-muted mb-8">Este horario representa la <code>jornada laboral</code> en la que se programará los técnicos.</small><br></p>
        </div> 
    </div>
</fieldset>