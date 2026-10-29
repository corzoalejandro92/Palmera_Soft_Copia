
<div class="modal-header">
    <h5 class="modal-title">{{isset($availability->id)?'EDITAR':'CREAR'}} DISPONIBILIDAD TÉCNICA</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <form id="availability">
        {{ method_field( isset($availability->id)?'PUT':'POST') }} 
        {{ csrf_field() }}
        <input type="hidden" value="<?= $availability->id ?? ''; ?>" name="id" />
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="form-group m-form__group row">
                    <label class="col-4 col-form-label">Nombre identificador</label>
                    <div class="col-8">
                        <input class="form-control" data-keyup="hiden-error" type="text" name="name" value="<?= $availability->name ?? ''; ?>" placeholder="Nombre de esta disponibilidad" autocomplete="no">
                    </div>
                </div>
     
                <div class="form-group m-form__group row">
                    <label class="col-4 col-form-label">Establecer jornada</label>
                    <div class="col-8">
                        @foreach($businessDays as $index => $day)
                        
                        <div class="custom-control-inline custom-switch">
                            <input 
                                type="checkbox" 
                                name="businessDay[]" 
                                value="{{$day->id}}" 
                                class="custom-control-input" 
                                id="{{$day->id}}" 
                                {{$availability && $availability->businessDays->pluck('id')->contains($day->id) ? 'checked':  ''}}>
                                <label class="custom-control-label" for="{{$day->id}}">{{$day->name}} </label>
                        </div>
                        
                        @endforeach
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <div class="offset-4 col-8">
                            @include('availability.forms.workingHourForm',compact(['availability']))
                    </div>
                    
                </div>
                <div class="form-group m-form__group row">
                        <div class="offset-4 col-8">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" value="1" name="holidays" id="holidays"  {{$availability && $availability->holidays ? 'checked':''}}>
                                <label class="custom-control-label" for="holidays">Excluir dias festivos y eventualidades en esta disponibilidad</label>
                            </div>
                        </div>
                    </div>
                <div class="form-group m-form__group row">
                    <label class="col-4 col-form-label">Técnicos vinculados a esta disponibilidad</label>
                    <div class="col-8">
                       <select class="form-control select2" data-change="hiden-error" id="users" name="users[]" data-placeholder="Seleccionar un rol" multiple>
                            <option value="">Seleccione uno o mas técnicas</option>
                            <?php if (!empty($availability->users)): ?>
                                @foreach($availability->users as $index => $user)
                                    <option value="{{$user->id}}" selected>{{$user->name}}</option>
                                @endforeach
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>        
    </form>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    @if (isset($availability->id))
        @can('availability.edit')
           <button type="button" class="btn btn-primary round" data-click="save">Editar</button>
        @endcan
    @else
        @can('availability.create')
           <button type="button" class="btn btn-success round" data-click="save">Guardar</button>
        @endcan
    @endif
</div>
 
