<div class="modal-header">
    <h6 class="modal-title">{{(isset($service->id)?'EDITAR SERVICIO':'CREAR SERVICIO')}}</h6>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">

    
    <form id="service">
        {{ method_field( isset($service->id)?'PUT':'POST') }}
        {{ csrf_field() }}
        <input type="hidden" value="<?= $service->id ?? ''; ?>" name="id" />
        <div class="container">

            <div class="row">

                <div class="form-group col-7 col-sm-7 col-md-7 col-lg-7 col-xl-7">
                    <h6>Nombre</h6>
                    <input type="text" class="form-control" data-keyup="hiden-error" name="name" value="{{$service->name ?? ''}}"
                        placeholder="Ingrese el nombre">
                </div>
                <div class="col-5 col-sm-5 col-md-5 col-lg-5 col-xl-5">
                    <div class="form-group">
                        <h6>Avatar</h6>
                        <select data-placeholder="--Seleccionar---" name="avatar" class="select2-icons form-control" data-change="hiden-error"
                            id="select2-icons">
                            <optgroup label="Seleccione un ícono">
                                @foreach($avatars as $avatar)
                                     <option value="{{ $avatar }}" {{ isset($service->avatar) ? ($service->avatar == $avatar ? 'selected': ''): '' }} data-icon="{{ $avatar }}"></option>
                                @endforeach
                            </optgroup>
                        </select>
                        {!! $errors->first('avatar', '<span class=error>:message</span>' ) !!}
                    </div>
                </div>

                <div class="form-group col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <h6>Descripción</h6>
                    <textarea rows="1" class="form-control" name="description" data-keyup="hiden-error"
                        placeholder="Ingrese la descripción del servicio">{{$service->description ?? ''}}</textarea>
                    {!! $errors->first('description', '<span class=error>:message</span>' ) !!}
                </div>

                <div class="form-group col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <h6>Opciones</h6>
                    <div class="form-check">
                        <input type="checkbox" id="allow_control_group" name="allow_control_group" value="1" {{isset($service->allow_control_group)  && $service->allow_control_group ? 'checked': '' }} class="form-check-input" id="aexampleCheck1">
                        <label class="form-check-label" for="allow_control_group">Agrupar los controles dentro de este servicio</label>
                      </div>
                </div>
{{-- 
                <div class="form-group col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <h6>Controles relacionados</h6>
                    <select class="form-control" name="infestations[]" multiple>
                        <option>Seleccione una opción</option>
                        @if(isset($service->infestations))
                        @foreach ($service->infestations as $infestation)
                            <option value="{{$infestation->id}}" selected>{{$infestation->name}}</option>
                        @endforeach
                        @endif
                    </select>
                </div> --}}
            </div>
            <hr>
            <!-- coberturas y precios -->
            <div align="center">
                @include('layouts/loading/min')
            </div>
            <div class="coverage">
                    @include('service.forms.coverageForm')
            </div>    
            <br><br>
            <div class="parameters">
                @include('service.forms.parameterForm')
        </div>  

        </div>
    </form>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    @if (isset($service->id))
        @can('services.edit')
        <button type="button" class="btn btn-primary round" data-click="save">Editar</button>
        @endcan
    @else
        @can('services.create')
        <button type="button" class="btn btn-success round" data-click="save">Crear</button>
        @endcan
    @endif

</div>