<div>
    <div class="row">
        <input type="hidden" name="id" value="{{ $crm->id ?? '' }}" />
        <input type="hidden" name="enabled" value="1" />
        <div class="form-group col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">
            <h5>Periodicidad</h5>
            <input type="number" class="form-control text-center" name="periodicity" value="{{ $crm->periodicity ?? ''}}"
                placeholder="Periodicidad" data-keyup="hiden-error">
        </div>

        <div class="form-group col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">
            <h5>Ciclo de facturacion</h5>
            <input type="number" class="form-control text-center" name="billing_cycle" value="{{ $crm->billing_cycle ?? ''}}"
                placeholder="Ciclo de facturacion" data-keyup="hiden-error">
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <h5>Plazo de pago</h5>
            <div class="skin skin-square">
                <div class="col-12 col-md-12">
                    <fieldset>
                        <input type="radio" name="pay_method" id="input-radio-10" value="0" 
                        {{ isset($crm->pay_method) ? ($crm->pay_method == "0" ? 'checked': ''): '' }}>
                        <label for="input-radio-10" style="margin-right: 20px;">Mes vencido</label>
                        <input type="radio" name="pay_method" id="input-radio-11" value="1"
                        {{ isset($crm->pay_method) ? ($crm->pay_method == "1" ? 'checked': ''): '' }}>
                        <label for="input-radio-11">Mes anticipado</label>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>

</div>