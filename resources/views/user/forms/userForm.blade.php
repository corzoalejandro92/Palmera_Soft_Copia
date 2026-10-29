
<div class="modal-header">
    <h5 class="modal-title">{{$profile ? 'EDITAR PERFIL' :(isset($user->id)?'EDITAR USUARIO':'CREAR USUARIO')}}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <form id="user" action="{{isset($user->id)? ($profile ? 'users/update-profile':'users/update'):'users/store'}}" enctype="multipart/form-data">
        {{ method_field('POST') }} 
        {{ csrf_field() }}
        <input type="hidden" value="<?= $user->id ?? ''; ?>" name="id" />
        <input type="hidden" value="<?= $profile ?>" name="profile" />
        <div class="row">
            <div class="col-12 col-lg-4">
                <div>
                    <input type="file" data-input="fileImage" accept="image/*" onchange="loadImage(this)" class="d-none" /> 
                    <div data-upload="file" data-form="user" class="pointer text-center">
                        <img class="avatar md pointer"  src="<?= $user->full_avatar ?? url('images/default/user.jpg' ); ?>" onerror="this.src='{{url("images/default/user.jpg")}}'" />
                        <input type="hidden" value="<?= $user->avatar ?? 'images/default/user.jpg'; ?>" name="avatar" />
                        <div class="mt-1">
                            <button type="button" class="btn btn-outline-teal btn-sm round">Cambiar avatar</button>
                        </div>
                    </div>
                </div>

                <div style="margin-top: 20px;margin-bottom: 20px;">
                    <input type="file" data-input="fileImage2" accept="image/*" onchange="loadImage2(this)" class="d-none" /> 
                    <div data-upload="file2" data-form="user" class="pointer text-center">
                        <img class="signature md pointer"  src="<?= url($user->signature_url ?? 'images/default/signature.png' ); ?>" onerror="this.src='{{url("images/default/signature.png")}}'" />
                        <input type="hidden" value="<?= $user->signature ?? 'images/default/signature.png'; ?>" name="signature" />
                        <div class="mt-1">
                            <button type="button" class="btn btn-outline-teal btn-sm round">Cambiar firma</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-8">
                <div class="form-group m-form__group row">
                    <label class="col-4 col-form-label">Nombres y apellidos</label>
                    <div class="col-8">
                        <input class="form-control" data-keyup="hiden-error" type="text" name="name" value="<?= $user->name ?? ''; ?>" placeholder="Nombre y apellidos">
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <label class="col-4 col-form-label">CC o ID</label>
                    <div class="col-8">
                        <input class="form-control" data-keyup="hiden-error" type="text" name="document_number" value="<?= $user->document ?? ''; ?>" <?= $profile ? 'readonly' : '' ?> placeholder="Documento de identidad">
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <label class="col-4 col-form-label">Cargo</label>
                    <div class="col-8">
                        <input class="form-control" data-keyup="hiden-error" type="text" name="position" value="<?= $user->position ?? ''; ?>" placeholder="Cargo empresarial">
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <label class="col-4 col-form-label">Correo electrónico</label>
                    <div class="col-8">
                        <input class="form-control" data-keyup="hiden-error" type="text" name="email" value="<?= $user->email ?? ''; ?>"  <?= $profile ? 'readonly' : '' ?> placeholder="Correo electrónico">
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <label class="col-4 col-form-label">Número celular</label>
                    <div class="col-8">
                        <input class="form-control" data-keyup="hiden-error" type="number" name="phone" value="<?= $user->phone ?? ''; ?>"  placeholder="Número celular de trabajo">
                    </div>
                </div>
               
                <div class="form-group m-form__group row">
                    <label class="col-4 col-form-label">Sede</label>
                    <div class="col-8">
                        @if(Auth::user()->roles->pluck('slug')->contains('root'))
                            <select class="form-control select2" data-change="hiden-error" id="headquarter_id" name="headquarter_id" data-placeholder="Seleccionar una sede" <?= $profile ? 'readonly' : '' ?>>
                                <option value="">Seleccionar Sede</option>
                                @if (!empty($user->headquarter))
                                    <option value="<?= $user->headquarter->id; ?>" selected=""><?= $user->headquarter->name; ?></option>
                                @else
                                    <option value="<?= session('headquarter_id'); ?>" selected=""><?= session('headquarter_name'); ?></option>
                                @endif
                            </select>
                        @else
                            <input type="hidden" name="headquarter_id" value="{{session('headquarter_id')}}">
                            <input type="text" class="form-control" readonly value="{{session('headquarter_name')}}">
                        @endif
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <label class="col-4 col-form-label">Contraseña</label>
                    <div class="col-8">
                        <input class="form-control" data-keyup="hiden-error" type="password" name="password" value="" placeholder="Contraseña" autocomplete="off">
                    </div>

                    @if(!$profile && !isset($user->id))
                        <div class="col-8 offset-4">
                            <label class="checkbox-inline">
                                <input type="checkbox" value="1" name="generic" onchange="" checked> Usar Clave Genérica
                            </label>
                        
                        </div>
                    @endif
                </div>
                <div class="form-group m-form__group row">
                    <label class="col-4 col-form-label">Confirmar contraseña</label>
                    <div class="col-8">
                        <input class="form-control" data-keyup="hiden-error" type="password" name="confirm_password" value="" placeholder="Contraseña" autocomplete="off">
                    </div>

                </div>
                <div class="form-group m-form__group row">
                    <label class="col-4 col-form-label">Rol en el sistema</label>
                    <div class="col-8">
                       <select class="form-control select2" data-change="hiden-error" id="role_id" name="role_id[]" data-placeholder="Seleccionar un rol" multiple>
                            <option value="">Asignar Rol</option>
                            <?php if (!empty($user->roles)): ?>
                                @foreach($user->roles as $index => $role)
                                    <option value="{{$role->id}}" selected>{{$role->name}}</option>
                                @endforeach
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

            
                <div class="form-group m-form__group row warehouse-foxcon {{ !(isset($user) && $user->roles->pluck('slug')->contains('technical')) ? 'display-hidden': '' }}">
                    <label class="col-4 col-form-label">Código bodega foxcon</label>
                    <div class="col-8">
                        <input class="form-control" data-keyup="hiden-error" type="number" name="warehouse_foxcon" value="<?= $user->warehouse->code_foxcon ?? '' ?>"  placeholder="Código para bodega">
                    </div>
                </div>

                <div class="form-group m-form__group row services {{ !(isset($user) && $user->roles->pluck('slug')->contains('technical')) ? 'display-hidden': '' }}">
                    <label class="col-4 col-form-label">Especialidades</label>
                    <div class="col-8">
                       <select class="form-control select2" data-change="hiden-error" id="service_id" name="service_id[]" data-placeholder="Seleccionar uno o mas servicios" multiple="multiple">
                            <?php if (!empty($user->services)): ?>
                                @foreach($user->services as $index => $service)
                                    <option value="{{$service->id}}" selected>{{$service->name}}</option>
                                @endforeach
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group m-form__group row zones {{ !(isset($user) && $user->roles->pluck('slug')->contains('technical')) ? 'display-hidden': '' }}">
                    <label class="col-4 col-form-label">Zonas de cobertura</label>
                    <div class="col-8">
                       <select class="form-control select2" data-change="hiden-error" id="zone_id" name="zone_id[]" data-placeholder="Seleccionar una zona" multiple>
                            <option value="">Asignar zona</option>
                            <?php if (!empty($user->zones)): ?>
                                @foreach($user->zones as $index => $zone)
                                    <option value="{{$zone->id}}" selected>{{$zone->name}}</option>
                                @endforeach
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group m-form__group row availabilities {{ !(isset($user) && $user->roles->pluck('slug')->contains('technical')) ? 'display-hidden': '' }}">
                    <label class="col-4 col-form-label">Disponibilidad</label>
                    <div class="col-8">
                       <select class="form-control select2" data-change="hiden-error" id="availability_id" name="availability_id[]" data-placeholder="Seleccionar una o más disponibilidades" multiple>
                            <option value="">Asignar disponibilidad</option>
                            <?php if (!empty($user->availabilities)): ?>
                                @foreach($user->availabilities as $index => $availability)
                                    <option value="{{$availability->id}}" selected>{{$availability->name}}</option>
                                @endforeach
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group m-form__group row customers {{ (isset($user) && $user->roles->pluck('slug')->contains('technical')) || (isset($user) && $user->roles->pluck('slug')->contains('commercial')) || (isset($user) && $user->roles->pluck('slug')->contains('customer'))  ? '':'display-hidden' }}">
                    <label class="col-4 col-form-label">Clientes asignados</label>
                    <div class="col-8">
                       <select class="form-control select2" data-change="hiden-error" id="customer_id" name="customer_id[]" data-placeholder="Seleccionar uno o mas clientes" multiple>
                            <option value="">Asignar Cliente</option>
                            <?php if (!empty($user->crms)): ?>
                                @foreach($user->crms as $index => $customer)
                                    <option value="{{$customer->id}}" selected>{{$customer->name}}</option>
                                @endforeach
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- documentos del técnico -->
        <div align="center">
            @include('layouts/loading/min')
        </div>
        <div class="documents">
            @if(isset($user) && $user->roles->pluck('slug')->contains('technical'))
                @include('user.forms.documentForm')
            @endif
        </div>    
        @if(!$profile && !isset($user->id))
            <div class="col-8 offset-4">
                <label class="checkbox-inline">
                    <input type="checkbox" value="1" name="send_access" checked> Enviar accesos al correo ingresado
                </label>
            </div>
        @endif    
    </form>
</div>

<div class="modal-footer">
    
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    @if (isset($user->id))
        @canAny(['users.edit','users.profile'])
            <button type="button" class="btn btn-primary round" data-click="{{$profile ? 'saveProfile': 'save' }}">Editar</button>
        @endcanAny
    @else
        @can('users.create')
            <button type="button" class="btn btn-success round" data-click="{{$profile ? 'saveProfile': 'save' }}">Guardar</button>
        @endcan
    @endif
</div>
 
