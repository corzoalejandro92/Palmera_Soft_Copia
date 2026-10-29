<h6><i class="step-icon ft-layout"></i>Revisión</h6>
<fieldset class="fieldspace">
    <div class="row">
        <h5>Revisar cotización</h5>
        <div class="col-md-12" id="step4table">

        </div>
        @include('layouts.loading.full')
    </div>
    <div class="row">
       <div class="col-sm-12 col-md-6 col-lg-3 col-xl-3 fieldspace">
            <h5>Vigencia de esta cotización</h5>
            <fieldset class="form-group position-relative">
                <div class="input-group">
                    <input type="text" class="form-control pickadate-translations" placeholder="Ingrese una fecha" readonly="" name="expire_date" value="{{ucwords($quote->expire_date->format('d F, Y'))}}">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <span class="ft-calendar"></span>
                        </span>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
</fieldset>