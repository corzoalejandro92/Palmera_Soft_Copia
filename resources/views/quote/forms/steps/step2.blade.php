<h6><i class="step-icon ft-settings"></i>Configuración</h6>
<fieldset class="fieldspace" id="fdate">
    <div class="row">
        <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
            <h5>Posible fecha del servicio</h5>
            <fieldset class="form-group position-relative">
                <div class="input-group">
                    <input type="text" class="form-control pickadate-translations"  name="possible_date" placeholder="Ingrese una fecha" readonly="" value="{{ucwords($quote->possible_date->format('d F, Y'))}}">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <span class="ft-calendar"></span>
                        </span>
                    </div>
                </div>

            </fieldset>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4" align="center">
            <h5>Cliente MIP</h5>
            <div class="row skin skin-square">
                <div class="col-md-12 col-sm-12">
                    <fieldset>
                        <input type="radio" name="diagnostic"  value="1" required {{$quote->diagnostic? 'checked':''}}>
                        <label for="input-radio-11" style="margin-right: 20px;">Si</label>
                        <input type="radio" name="diagnostic"  value="0" required {{!$quote->diagnostic? 'checked':''}}>
                        <label for="input-radio-11">No</label>
                    </fieldset>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-8 col-lg-4 col-xl-4 select-coordinator {{!$quote->diagnostic? 'display-hidden':''}}">
            <h5>Coordinador MIP</h5>
            <fieldset class="form-group position-relative">
                <select class="form-control input-lg" id="coordinator_id"  name="coordinator_id" required>
                    <option value="">--Seleccionar--</option>
                    @if($quote->coordinator)
                        <option value="{{$quote->coordinator->id}}" selected>{{$quote->coordinator->name}}</option>
                    @endif
                </select>
            </fieldset>
        </div>
    </div>
</fieldset>