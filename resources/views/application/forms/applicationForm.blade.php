@include('layouts.loading.full') 
<div class="modal-header">
    <h4 class="modal-title">{{$title}}</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="mt-2">
        <form id="frm">
            {{ method_field( isset($application->id)?'PUT':'POST') }}
            {{ csrf_field() }}
            <input type="hidden" name="id" value="<?= $application->id ?? '' ?>" />
            <div class="row">
                <div class="col-3">
                    <div class="form-group col-12">
                        <input type="file" data-input="fileImage" accept="image/*" onchange="loadImage(this)" class="d-none" /> 
                        <div data-upload="file" data-form="frm" class="pointer text-center">
                            <img class="avatar sm pointer"  src="<?= url($application->avatar ?? 'images/default/method.png' ); ?>" onerror="this.src='{{url("images/default/method.png")}}'" />
                            <input type="hidden" value="<?= $application->avatar ?? 'images/default/method.png'; ?>" name="avatar" />
                            <div class="mt-1">
                                <button type="button" class="btn btn-outline-teal btn-sm round">Cambiar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-9">
                    <div class="form-group col-12">
                        <h5>Nombre</h5>
                        <input type="text" class="form-control" autocomplete="nope" name="name" value="{{ $application->name ?? ''}}"
                            placeholder="Nombre" data-keyup="hiden-error">
                    </div>
                    <div class="form-group col-12">
                        <h5>Descripción</h5>
                        <textarea class="form-control" data-keyup="hiden-error" name="description" type="text" placeholder="Información adicional" autocomplete="off">{{ $application->description??''}}</textarea>
                    </div>
                    @if(isset($application->id))
                        <div class="col-12">
                            <div class="form-group">
                                <div class="col-12" >
                                    <div class="row skin skin-square">
                                        <div class="col-1">
                                            <input type="checkbox" name="enabled" value="1" id="input-checkbox-1" {{ isset( $application->enabled) ? ($application->enabled == "1" ? 'checked': ''):''}}>
                                        </div>
                                        <div class="col-11">
                                            <label for="input-checkbox-1"> Habilitada</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    <button type="button" class="btn btn-success round" data-click="save">Guardar</button>
</div>