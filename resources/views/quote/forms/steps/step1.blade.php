<h6><i class="step-icon la la-building"></i>Cliente</h6>
<fieldset class="fieldspace">
    <div class="row">
    <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
            <div class="form-group">
                <h5>Clientes</h5>
                <select class="select2 form-control" id="customer" name="crm_id" data-change="hiden-error">
                    <option value="">--seleccionar--</option>
                    @if($quote->crm)
                        <option value="{{$quote->crm->id}}" selected>{{$quote->crm->name}}</option>
                    @endif
                </select>
           </div>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
            <h5>Sucursal</h5>
            <fieldset class="form-group position-relative">
                <select class="select2 form-control" id="office_id" name="office_id" data-change="hiden-error">
                    <option value="">--Seleccionar--</option>
                    @if($quote->office)
                        <option value="{{$quote->office->id}}" selected>{{$quote->office->name}}</option>
                    @endif
                </select>
            </fieldset>
        </div>

        <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
            <h5>Contacto</h5>
            <fieldset class="form-group position-relative">
                <select class="select2 form-control" id="contact_id"  name="contact_id" data-change="hiden-error">
                    <option value="">--Seleccionar--</option>
                    @if($quote->contact)
                        <option value="{{$quote->contact->id}}" selected>{{$quote->contact->name}}</option>
                    @endif
                </select>
            </fieldset>
        </div>
    </div>
</fieldset>