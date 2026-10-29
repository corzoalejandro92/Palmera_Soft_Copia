<div>
    <div class="row">
        <input type="hidden" name="id" value="{{ $crm->id ?? '' }}" />
        <input type="hidden" name="enabled" value="1" />
        <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
            <h5>Tipo</h5>
            <div class="skin skin-square">
                <div class="col-12 col-md-12">
                    <fieldset>
                        <input type="radio" name="type_customer" id="input-radio-10" value="0"
                            {{ isset($crm->type_customer) ? ($crm->type_customer == "0" ? 'checked': ''): '' }}>
                        <label for="input-radio-10" style="margin-right: 20px;">Natural</label>
                        <input type="radio" name="type_customer" id="input-radio-11" value="1"
                            {{ isset($crm->type_customer) ? ($crm->type_customer == "1" ? 'checked': ''): '' }}>
                        <label for="input-radio-11">Jurídica</label>
                    </fieldset>
                </div>
            </div>
        </div>
        <div class="form-group col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
            <h5 id="documentLabel">CC / NIT</h5>
            <input type="text" class="form-control" autocomplete="off" name="document" value="{{ $crm->document ?? '' }}"
                placeholder="Ingrese el documento" data-keyup="hiden-error">
        </div>
        <div class="form-group col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
            <h5>Razon Social</h5>
            <input type="text" class="form-control" autocomplete="off" name="name" value="{{ $crm->name ?? ''}}"
                placeholder="Razon Social" data-keyup="hiden-error">
        </div>
        <div class="form-group col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
            <h5>Nombre Comercial</h5>
            <input type="text" class="form-control" autocomplete="off" name="commercial_name" value="{{ $crm->commercial_name ?? ''}}"
                placeholder="Nombre comercial" data-keyup="hiden-error">
        </div>
        <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
            <div class="form-group">
                <h5>Categoría CRM</h5>
                <select class="form-control typeCrm" name="crm_type_id" data-change="hiden-error">
                    <option value="">--seleccionar--</option>
                    <?php if (!empty($crm->crmCategory)): ?>
                    <option value="<?= $crm->crmCategory->id; ?>" selected=""><?= $crm->crmCategory->name; ?></option>
                    <?php endif; ?>
                </select>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
            <div class="form-group">
                <h5>Medio de contacto</h5>
                <select class="select2 form-control" id="mean_contact" name="mean_contact_id" data-change="hiden-error">
                    <option value="">--seleccionar--</option>
                    <?php if (!empty($crm->meanContact)): ?>
                    <option value="<?= $crm->meanContact->id; ?>" selected=""><?= $crm->meanContact->name; ?></option>
                    <?php endif; ?>
                </select>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
            <div class="form-group">
                <div class="col-12" >
                    <div class="row skin skin-square" style="padding-top: 17px;">
                        <div class="col-1">
                            <input type="checkbox" name="customer_subcontract" value="1" id="input-checkbox-2" {{ isset( $crm->customer_subcontract) ? ($crm->customer_subcontract == "1" ? 'checked': ''):''}}>
                        </div>
                        <div class="col-10">
                            <label for="input-checkbox-2"> ¿Los servicios a prestar son subcontratados?</label>
                        </div>
                    </div>
                </div>
                <div class="col-12" >
                    <div class="row skin skin-square" style="padding-top: 17px;">
                        <div class="col-1">
                            <input type="checkbox" name="customer_national" value="1" id="input-checkbox-1" {{ isset( $crm->customer_national) ? ($crm->customer_national == "1" ? 'checked': ''):''}}>
                        </div>
                        <div class="col-10">
                            <label for="input-checkbox-1"> Cliente Nacional (varias sedes de Palmera donde se gestiona este cliente).</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 div-national-heaquarters" style="{{ isset( $crm->customer_national) ? ($crm->customer_national == "1" ? '': 'display: none;'):'display: none;'}}">
            <div class="form-group">
                <h5>Sedes que administran este cliente</h5>
                <select class="select2 form-control" name="headquarters[]" data-change="hiden-error" multiple>
                    <option value="">--seleccionar--</option>
                    @if (isset($crm->headquarters) && $crm->headquarters->count() > 0)
                        @foreach ($crm->headquarters as  $headquarter)
                            <option value="<?= $headquarter->id; ?>" selected><?= $headquarter->name; ?></option>
                        @endforeach
                    @else
                        <option value="{{session('headquarter_id')}}" selected>{{session('headquarter_name')}}</option>
                    @endif
                </select>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 div-local-headquarter" style="{{ isset( $crm->customer_national) ? ($crm->customer_national != "1" ? '': 'display: none;'):''}}"">
            <div class="form-group">
                <h5>Sede que administra</h5>
                <input type="text" readonly class="form-control" value="{{session('headquarter_name')}}" />
                    
            </div>
        </div>
        <div class="form-group col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
            <h5 title="Correo para facturación electronica">Correo F.E</h5>
            <input type="text" class="form-control" autocomplete="off" name="ei_email" value="{{ $crm->ei_email ?? ''}}"
                placeholder="Correo facturación electronica" data-keyup="hiden-error">
        </div> 
        <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 div-national-heaquarters">
            <div class="form-group">
                <h5>Comercial asignado</h5>
                <select class="select2 form-control" name="user_id" data-change="hiden-error">
                    <option value="">--seleccionar--</option>
                    
                    @if ($crm && $crm->user_id)
                   
                        @php $user = \App\User::withTrashed()->find($crm->user_id); @endphp

                        @if($user)
                            <option value="{{$user->id}}" selected>{{$user->name}}</option>
                        @endif
                    @else
                        @php $user = Auth::User(); @endphp
                        <option value="{{$user->id}}" selected>{{$user->name}}</option>
                    @endif
                </select>
            </div>
        </div>
    </div>

</div>