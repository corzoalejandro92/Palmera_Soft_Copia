<hr>
<h4 class="card-title">Contactos</h4>
<div>
    <fieldset class="fieldspace">
        <div class="repeater-default">
            <div data-repeater-list="contactoCustomer">
                <div data-repeater-item>
                    <input type="hidden" name="id" value="{{$contact->id ?? '' }}" />
                    <input type="hidden" name="enabled" value="1" />
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-4">
                            <h5>Nombre</h5>
                        <input type="text" class="form-control" autocomplete="nope" data-keyup="hiden-error" name="name" value="{{ $contact->name ?? '' }}" placeholder="Ingrese el Nombre">
                        </div>
                        <div class="form-group col-sm-12 col-md-4">
                            <h5>Teléfono</h5>
                            <input type="number" class="form-control" autocomplete="nope" name="phone" value="{{ $contact->phone ?? '' }}" placeholder="Ingrese el Teléfono">
                        </div>
                        <div class="form-group col-sm-12 col-md-4">
                            <h5>Cargo</h5>
                            <input type="text" class="form-control" autocomplete="nope" name="position" value="{{ $contact->position ?? '' }}" placeholder="Ingrese su cargo">
                        </div>
                        <div class="form-group col-sm-12 col-md-4">
                            <h5>Email</h5>
                            <input type="email" data-keyup="hiden-error" autocomplete="nope" class="form-control" name="email" value="{{ $contact->email ?? '' }}" placeholder="Ingrese el correo">
                        </div>
                        <div class="form-group col-sm-12 col-md-8">
                            <h5>Autorizado para temas de</h5>
                            <select class="form-control typeContact" name="contact_type_id[]" data-change="hiden-error" multiple>
                                <option value="">--seleccionar--</option>
                                <?php if (!empty($contact->types)): ?>
                                    @foreach ($contact->types as  $type)
                                        <option value="<?= $type->id; ?>" selected=""><?= $type->name; ?></option>
                                    @endforeach
                                <?php endif; ?>
                            </select>
                        </div>
                        @if(!isset($contact))
                        <div class="col-4" >
                            <div class="row skin skin-square" style="padding-top: 17px;">
                                <div class="col-1">
                                    <input type="checkbox" name="giveAccess" value="1" id="input-checkbox-2">
                                </div>
                                <div class="col-11">
                                    <label for="input-checkbox-2"> Dar Acceso al sistema para consultas relacionadas a este cliente.</label>
                                </div>
                            </div>
                        </div>
                        @endif
                        <?php if(isset($seatype)){ ?>
                        <div class="form-group col-sm-12 col-md-8">
                            <h5>Sucursales</h5>
                            <select class="form-control" name="office_id[]" data-change="hiden-error" multiple>
                                <option value="">--seleccionar--</option>
                                <?php if (!empty($office)): ?>
                                        <option value="<?= $office->id; ?>" selected="">{{ $office->name}}</option>
                                <?php endif; ?>
                                <?php if (!empty($contact->offices)): ?>
                                    @foreach ($contact->offices as $office)
                                        <option value="<?= $office->id; ?>" selected="">{{ $office->name}} - {{ $office->full_address }}</option>
                                    @endforeach
                                <?php endif; ?>
                            </select>
                            <div style="margin-top:8px;">
                            <label for="checkbox">
                                <input type="checkbox" data-click="checkAllOffices" id="checkbox"> Seleccionar todas
                            </label>
                            </div>
                            
                        </div>
                         <?php }; ?>

                    </div>
                    <?php if(isset($contact) && !$contact->id){ ?>
                    <hr style="border-top: 1px dashed #a4aab6;">
                    <?php } ?>
                </div>
            </div>
            <?php if(isset($contact) && !$contact->id){ ?>
            <div class="form-group overflow-hidden">
                <div class="col-12">
                    <a data-repeater-create class="btn btn-sm btn-round btn-fab btn-success text-white">
                        <i class="ft-plus"></i>
                    </a>
                </div>
            </div>
            <?php } ?>
            @can('crms.info.admin')
            @if(isset($contact) && $contact->id)
                @if($contact->user)
                
                    <img class="avatar xxs mr-1" style="position: absolute;bottom: -50px;" src="{{$contact->user->avatar}}" onerror='this.src="{{url("images/default/user.jpg")}}"'>
                    <button type="button" style="position: absolute;bottom: -55px;left: 58px;    z-index: 2000000;" class="btn btn-outline-danger round float-left" data-id="{{$contact->id}}" data-click="revokeAccess">Revocar acceso al sistema</button>    
                    @else
                        <button type="button" style="position: absolute;bottom: -55px;     z-index: 2000000;" class="btn btn-outline-warning round float-left" data-id="{{$contact->id}}" data-click="giveAccess">Dar acceso al sistema</button>
                    @endif
                
            @endif
            @endcan
        </div>
    </fieldset>
</div>